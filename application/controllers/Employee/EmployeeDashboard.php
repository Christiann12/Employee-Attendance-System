<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EmployeeDashboard extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('Asia/Singapore');
		$this->load->helper('url');
		$this->load->helper('cookie');
		$this->load->library('session');
		$this->load->model('Admin/Employee_model');
		$this->load->model('Admin/Attendance_model');
	}

	public function index()
	{
		

        if($this->session->userdata('isLogInEmployee') === true){
			$data['empData'] = $this->Employee_model->getEmp($this->session->userdata('employeeId'));
			$data['circularData'] = $this->circularProgressBar();
			$data['lateCount'] = $this->Attendance_model->getTardinessMeasure('Late');
			$data['OnTimeCount'] = $this->Attendance_model->getTardinessMeasure('On Time');
			$data['buttonStatus'] = $this->buttonStatus();
			$data['buttonStatusBreak'] = $this->buttonStatusBreak();
			$userData = $this->db->get_where('employee', array('empId' => $this->session->userdata('employeeId')))->row();
			if(!empty($userData)){
				$data['page'] = "EmployeeDashboard";
				$this->load->view('HeaderAndFooter/HeaderEmployee.php');
				$this->load->view('Pages/Employee/WrapperEmployee.php',$data);
				$this->load->view('HeaderAndFooter/FooterEmployee.php',$data);
			}
			else{
				redirect('');
			}
		}
		else{
			if(!empty(get_cookie('remember_me_token_employee'))){
				$userData = $this->Employee_model->getCurrentUserCookie(get_cookie('remember_me_token_employee'));
				if ($userData) {
					$this->session->set_userdata([
						'isLogInEmployee'     => true,
						'employeeId'     => $userData->empId,
						'secretIdEmployee'     => $userData->secretId,
						'firstNameEmployee'     => $userData->fname,
						'lastNameEmployee'  => $userData->lname,
						'employeeTimein'  => $userData->timein,
						'employeeTimeout'  => $userData->timeout,
						'employeeDayoff'  => $userData->dayoff,
					]);
					redirect('EmployeeDashboard');
				} else {
					redirect('');
				}
			}
			else{
				redirect('');
			}
		}
	}
	public function buttonStatus(){
		$empData = $this->Employee_model->getEmp($this->session->userdata('employeeId'));

        $attendanceDetail = $this->Attendance_model->getTimeIn($empData->empId);
		
		$timein = date("H:i",strtotime($empData->timein."-15 min"));
		$timeout = date("H:i",strtotime($empData->timeout.""));
		$now = date("H:i");

		if(!empty($attendanceDetail)){
			if($attendanceDetail->timeins == 'EMPTY' && $attendanceDetail->timeoutf != 'EMPTY' && $attendanceDetail->pictureUrlTimeout == 'empty' ){
				return false;
			}
		}
		if ($this->isBetween($timein,$timeout,$now)){
			return true;
		}
		else if(!empty($attendanceDetail)){
			return true;
		}
		else if($empData->timeout == 'timeout' || $empData->timein == 'timein' || $empData->dayoff == 'dayoff'){
			return false;
		}
		else{
			return false;
		}
	}
	public function buttonStatusBreak(){
		$empData = $this->Employee_model->getEmp($this->session->userdata('employeeId'));

        $attendanceDetail = $this->Attendance_model->getTimeIn($empData->empId);

		$timein = date("H:i",strtotime($empData->timein."-15 min"));
		$timeout = date("H:i",strtotime($empData->timeout.""));
		$now = date("H:i");

		$sample = gmdate("H:i:s", ( strtotime($empData->timeout) - strtotime($empData->timein) ) );

		if(!empty($attendanceDetail) && ($attendanceDetail->timeins == 'EMPTY' || $attendanceDetail->timeoutf == 'EMPTY')){
			if ( (int) date("H", strtotime($sample)) >= 8 ) {
				return true;
			} else {
				return false;
			}
			
		}
		else if($empData->timeout == 'timeout' || $empData->timein == 'timein' || $empData->dayoff == 'dayoff'){
			return false;
		}
		else{
			return false;
		}
			// return true;
	}
	public function generateTable(){
		$data1 = $this->Attendance_model->getTableDataByEmployee();
		
		// $productss = $this->inventory_model->productList();
		$data = array();

		foreach($data1 as $listItem){

			$empData = $this->Employee_model->getEmp($listItem->empId);
			$regularHour = $this->calculateWorkHour($listItem->timeinf,$listItem->timeoutf,$listItem->timeins,$listItem->timeouts,$listItem->dayoff);
			$overTimeHour = $this->calculateWorkHourOT($listItem->timeinf,$listItem->timeoutf,$listItem->timeins,$listItem->timeouts,$listItem->dayoff);
			$UT_OT = $this->checkifUT_OT($listItem->datetimein,$empData->dayoff,$listItem->timeinf,$listItem->timeoutf,$listItem->timeins,$listItem->timeouts,$overTimeHour,$regularHour,$listItem->pictureUrlTimeout,$listItem->dayoff);
			$breakHour = $this->calculateBreakHour($listItem->timeinf,$listItem->timeoutf,$listItem->timeins,$listItem->timeouts,$listItem->dayoff);
			$late = $this->checkiflate($listItem->late);
			
			$row = array();
			$row['timebefore'] = $listItem->timeinf.'-'.$listItem->timeoutf;
			$row['timeafter'] =$listItem->timeins.'-'.$listItem->timeouts;
			$row['date'] = $listItem->datetimein;
			$row['Hours_Worked_Regular'] = $regularHour;
			$row['Hours_Worked_OT'] = $overTimeHour;
			$row['Break_Hour'] = $breakHour[0];
			$row['Dayoff'] = $this->checkIfDayOff($listItem->dayoff);
			$row['Late'] = $late;
			$row['UT_OT'] = $UT_OT;
			$row['OverBreak'] = $breakHour[1];
			$data[] = $row;
		}
		$json_data['data'] = $data;
		echo json_encode($json_data);
	}
	public function circularProgressBar(){
		$attendanceDetail = $this->Employee_model->getEmp($this->session->userdata('employeeId'));

		$timein = date("H:i" , strtotime($attendanceDetail->timein));
		$timeout = date("H:i" , strtotime($attendanceDetail->timeout));

		$currentTime = date("H:i");
		$data = [];
		if($this->isBetween($timein,$timeout,$currentTime)){
			if($timein < $timeout){
				$timein = strtotime($attendanceDetail->timein);
					$timeout = strtotime($attendanceDetail->timeout);
					$diff  = ($timeout - $timein);

					$time1 = strtotime(date('H:i'));
					$time2 = strtotime($attendanceDetail->timeout);
					$diff2 = ($time2 - $time1);
					
					$temp1 = $diff / 3600;
					$temp2 = $diff2 / 3600;
					$newhour = $temp1 - $temp2;
					$percent = round(($newhour / $temp1)*100,2);
			}
			else{
				$timein = strtotime('today '.$attendanceDetail->timein);
				$timeout = strtotime('tomorrow '.$attendanceDetail->timeout);
				$diff  = ($timeout - $timein);

				
					$currentTime = strtotime(date('H:i'));
					$timeout2 = strtotime($attendanceDetail->timeout);
					if($currentTime > $timeout2){
						$timeout2 = strtotime('tomorrow '.$attendanceDetail->timeout);
						$diff2 = ($timeout2 - $currentTime);
					}
					else{
						$diff2 = ($timeout2 - $currentTime);
					}
				
				$temp1 = $diff / 3600;
				$temp2 = $diff2 / 3600;
				$newhour = $temp1 - $temp2;
				$percent = round(($newhour / $temp1)*100,2);
			}
			$data['message'] = floor($temp2) > 1? floor($temp2).' hours left' : floor($temp2).' hour left';
			// $data['message'] = $temp2;
			$data['percent'] = $percent;
		}
		else{
			$data['message'] = 'Not your schedule yet!';
			$data['percent'] = 0;
		}
		return $data;
	}
	function calculateWorkHour($timeinf,$timeoutf,$timeins,$timeouts,$dayoff){

		$timein1 = ($timeinf != 'EMPTY'&&$timeoutf != 'EMPTY' ) ? $timeinf : "00:00:00";
		$timeout1 = ($timeinf != 'EMPTY'&&$timeoutf != 'EMPTY' ) ? $timeoutf : "00:00:00";
		$timein2 = ($timeins != 'EMPTY'&&$timeouts != 'EMPTY' ) ? $timeins : "00:00:00";
		$timeout2 = ($timeins != 'EMPTY'&&$timeouts != 'EMPTY' ) ? $timeouts : "00:00:00";
		
		if($dayoff == 'Yes'){
			return "00:00:00";
		}
		else if ( ($timeinf != 'EMPTY'&&$timeoutf != 'EMPTY' )|| ($timeins != 'EMPTY' && $timeouts != 'EMPTY')) {
			$time1 = gmdate("H:i:s", ( strtotime($timeout1) - strtotime($timein1) )) ; 
			$time2 = gmdate("H:i:s", ( strtotime($timeout2) - strtotime($timein2) ))  ; 

			$secs = strtotime($time2)-strtotime("00:00:00");
			$result = date("H:i:s",strtotime($time1)+$secs); 

			$sample =  gmdate("H:i:s", ( strtotime("01:00:00") - strtotime(gmdate("H:i:s", ( strtotime($timeins) - strtotime($timeoutf) ))) ));
			
			if((int) date('H', strtotime($sample)) < 1){
				if(!$this->isBetween($timeout1, date("H:i",strtotime($timeout1."+1 hour")), $timeout2) ){
					$result = gmdate("H:i:s", ( strtotime($result) - strtotime($sample) ));
				}
				else{
					$result = gmdate("H:i:s", ( strtotime($result) - strtotime($time2) ));
				}
			}	

			return (int) date("H",strtotime($result)) >= 8 ? "08:00:00" : $result;
			// return $result;
		} else {
			return "00:00:00";
		}
		
	}
	function calculateWorkHourOT($timeinf,$timeoutf,$timeins,$timeouts,$dayoff){
		
		if($dayoff == 'Yes'){
			if (($timeinf != 'EMPTY'&&$timeoutf != 'EMPTY'&&$timeins != 'EMPTY'&&$timeouts != 'EMPTY' )) {
				$time1 = gmdate("H:i:s", ( strtotime($timeoutf) - strtotime($timeinf) )) ;
				$time2 = gmdate("H:i:s", ( strtotime($timeouts) - strtotime($timeins) ))  ;
	
				$secs = strtotime($time2)-strtotime("00:00:00");
				$result = date("H:i:s",strtotime($time1)+$secs);
	
				$sample =  gmdate("H:i:s", ( strtotime("01:00:00") - strtotime(gmdate("H:i:s", ( strtotime($timeins) - strtotime($timeoutf) ))) ));
			
				if((int) date('H', strtotime($sample)) < 1){
					if(!$this->isBetween($timeoutf, date("H:i",strtotime($timeoutf."+1 hour")), $timeouts) ){
						$result = gmdate("H:i:s", ( strtotime($result) - strtotime($sample) ));
					}
					else{
						$result = gmdate("H:i:s", ( strtotime($result) - strtotime($time2) ));
					}
				}	
				
				$test = floor((int) date("i",strtotime($result)) /15) * 15;
	
				return (int) date("H",strtotime($result)) >= 8  ? "08:00:00" : date("H",strtotime($result)) .':'.sprintf("%02d", $test).':'.date("s",strtotime($result)) ;
				return $result;
			} 
			else if(($timeinf != 'EMPTY'&&$timeoutf != 'EMPTY' )&& ($timeins == 'EMPTY'&&$timeouts == 'EMPTY') ){
				$time1 = gmdate("H:i:s", ( strtotime($timeoutf) - strtotime($timeinf) )) ;

				$test = floor((int) date("i",strtotime($time1)) /15) * 15;
	
				return (int) date("H",strtotime($time1)) >= 8  ? "08:00:00" : date("H",strtotime($time1)) .':'.sprintf("%02d", $test).':'.date("s",strtotime($time1)) ;
			}
		}
		else if (($timeinf != 'EMPTY'&&$timeoutf != 'EMPTY'&&$timeins != 'EMPTY'&&$timeouts != 'EMPTY' )) {
			$time1 = gmdate("H:i:s", ( strtotime($timeoutf) - strtotime($timeinf) )) ; 
			$time2 = gmdate("H:i:s", ( strtotime($timeouts) - strtotime($timeins) ))  ; 
 
			$secs = strtotime($time2)-strtotime("00:00:00");
			$result = date("H:i:s",strtotime($time1)+$secs); 

			if((int) date("H",strtotime($result)) < 8 ){
				return "00:00:00";
			}

			$sample =  gmdate("H:i:s", ( strtotime("01:00:00") - strtotime(gmdate("H:i:s", ( strtotime($timeins) - strtotime($timeoutf) ))) ));
			
			if((int) date('H', strtotime($sample)) < 1){
				$result = gmdate("H:i:s", ( strtotime($result) - strtotime($sample) ));
			}	
			$final = gmdate("H:i:s", ( strtotime($result) - strtotime("08:00:00") )); 
			

			$test = floor((int) date("i",strtotime($final)) /15) * 15;

			return (int) date("H",strtotime($final)) >= 8  ? "08:00:00" : date("H",strtotime($final)) .':'.sprintf("%02d", $test).':'.date("s",strtotime($final)) ;
			// return $result;
		} 
		else if(($timeinf != 'EMPTY'&&$timeoutf != 'EMPTY' )&& ($timeins == 'EMPTY'&&$timeouts == 'EMPTY') ){
			$time1 = gmdate("H:i:s", ( strtotime($timeoutf) - strtotime($timeinf) )) ;

			if((int) date("H",strtotime($time1)) < 8 ){
				return "00:00:00";
			}
			$final = gmdate("H:i:s", ( strtotime($time1) - strtotime("08:00:00") ));
			
			$test = floor((int) date("i",strtotime($final)) /15) * 15;

			return (int) date("H",strtotime($final)) >= 8  ? "08:00:00" : date("H",strtotime($final)) .':'.sprintf("%02d", $test).':'.date("s",strtotime($final)) ;
		}
		else {
			return "00:00:00";
		}
		
	}
	function calculateBreakHour($timeinf,$timeoutf,$timeins,$timeouts,$dayoff){

		$timein1 = ($timeinf != 'EMPTY'&&$timeoutf != 'EMPTY' ) ? $timeinf : "00:00:00";
		$timeout1 = ($timeinf != 'EMPTY'&&$timeoutf != 'EMPTY' ) ? $timeoutf : "00:00:00";
		$timein2 = ($timeins != 'EMPTY'&&$timeouts != 'EMPTY' ) ? $timeins : "00:00:00";
		$timeout2 = ($timeins != 'EMPTY'&&$timeouts != 'EMPTY' ) ? $timeouts : "00:00:00";
		
		if($dayoff == 'Yes'){
			if (($timeinf != 'EMPTY'&&$timeoutf != 'EMPTY'&&$timeins != 'EMPTY'&&$timeouts != 'EMPTY') || ($timeoutf != 'EMPTY'&&$timeins != 'EMPTY')) {

				$time1 = gmdate("H:i:s", ( strtotime($timeins) - strtotime($timeoutf) )) ;


				return[
					(int) date("H",strtotime($time1)) < 1 || ((int) date("H",strtotime($time1)) == 1 && (int) date("i",strtotime($time1)) <= 0 ) ? "01:00:00" : $time1 ,
					(int) date("H",strtotime($time1)) < 1 || ((int) date("H",strtotime($time1)) == 1 && (int) date("i",strtotime($time1)) <= 0 ) ? '<p class="text-success"><strong>On Time</strong></p>' : '<p class="text-danger"><strong>Over Break</strong></p>'
				];

				// return $time1;
			} else {
				return (
					[
						"00:00:00",
						'-'
					]
				);
			}
		}
		else if (($timeinf != 'EMPTY'&&$timeoutf != 'EMPTY'&&$timeins != 'EMPTY'&&$timeouts != 'EMPTY') || ($timeoutf != 'EMPTY'&&$timeins != 'EMPTY')) {

			$time1 = gmdate("H:i:s", ( strtotime($timeins) - strtotime($timeoutf) )) ;

			return (
				[
					(int) date("H",strtotime($time1)) < 1 || ((int) date("H",strtotime($time1)) == 1 && (int) date("i",strtotime($time1)) <= 0 ) ? "01:00:00" : $time1 ,
					(int) date("H",strtotime($time1)) < 1 || ((int) date("H",strtotime($time1)) == 1 && (int) date("i",strtotime($time1)) <= 0 ) ? '<p class="text-success"><strong>On Time</strong></p>' : '<p class="text-danger"><strong>Over Break</strong></p>'
				]
			);
			// return $time1;
		} else {
			return (
				[
					"00:00:00",
					'-'
				]
			);
		}
	}
	// function checkiflate($timeinf,$schedTimeIn,$schedTimeout,$timeoutf,$timeins,$timeouts,$datetimein,$dayoff){
	function checkiflate($late){
		// if(strtolower(date('l',strtotime($datetimein))) == strtolower($dayoff)){
		// 	return '<p class="text-primary text-wrap"><strong>Overtime dayoff</strong></p>';
		// }
		// else if ($timeinf != 'EMPTY') {
		// 	if ($this->isBetween($schedTimeIn,$schedTimeout,$timeinf)) 
		// 	{
		// 		return '<p class="text-danger"><strong>Late</strong></p>';
		// 	} else {	
		// 		return '<p class="text-success"><strong>On Time</strong></p>';
		// 	}
		// } else {
		// 	return '-';
		// }
		if ($late == 'Late') {
			return '<p class="text-danger"><strong>Late</strong></p>';
		} 
		else {
			return '<p class="text-success"><strong>On Time</strong></p>';
		}
		
	}
	function checkifUT_OT($datetimein,$dayoff,$timeinf,$timeoutf,$timeins,$timeouts,$overTimeHour,$regularHour,$pictureUrlTimeout,$newdayoff){
		if($pictureUrlTimeout == 'empty'){
			return '-';
		}
		else if($newdayoff == 'No'){
			if ($timeinf != 'EMPTY'&&$timeoutf != 'EMPTY'&&$timeins != 'EMPTY'&&$timeouts != 'EMPTY') {
			
				if( (int) date("H",strtotime($regularHour)) == 8 && (int) date("H",strtotime($overTimeHour)) == 0 && (int) date("i",strtotime($overTimeHour)) == 0 ){
					return '<p class="text-success"><strong>On Time</strong></p>';
				}
				else if( (int) date("H",strtotime($overTimeHour)) > 0 || ((int) date("H",strtotime($overTimeHour)) == 0  && (int) date("i",strtotime($overTimeHour)) >= 15) ){
					return '<p class="text-warning"><strong>Overtime</strong></p>';
				}
				else{
					return '<p class="text-danger"><strong>Undertime</strong></p>';
				}
			} 
			else if((($timeinf != 'EMPTY'&&$timeoutf != 'EMPTY' )&& ($timeins == 'EMPTY'&&$timeouts == 'EMPTY') ) && ( (int) date("H",strtotime($regularHour)) == 8 && (int) date("H",strtotime($overTimeHour)) == 0 && (int) date("i",strtotime($overTimeHour)) == 0) ){
				return '<p class="text-success"><strong>On Time</strong></p>';
			}
			else if((($timeinf != 'EMPTY'&&$timeoutf != 'EMPTY' )&& ($timeins == 'EMPTY'&&$timeouts == 'EMPTY') ) && ((int) date("H",strtotime($overTimeHour)) > 0 || ((int) date("H",strtotime($overTimeHour)) == 0  && (int) date("i",strtotime($overTimeHour)) >= 15)) ){
				return '<p class="text-warning"><strong>Overtime</strong></p>';
			}
			elseif (($timeinf != 'EMPTY'&&$timeoutf != 'EMPTY' )&& ($timeins == 'EMPTY'&&$timeouts == 'EMPTY')) {
				return '<p class="text-danger"><strong>UnderTime</strong></p>';
			}
		}
		else if($newdayoff == 'Yes'){
			if( (int) date("H",strtotime($overTimeHour)) == 8 && (int) date("i",strtotime($overTimeHour)) == 0 ){
				return '<p class="text-success"><strong>On Time</strong></p>';
			}
			else{
				return '<p class="text-danger"><strong>Undertime</strong></p>';
			}
		}
		else {
			return '-';
		}
	}
	function checkIfDayOff($dayoff){
		
		if ($dayoff == 'Yes') {
			return '<p class="text-primary text-wrap"><strong>Yes</strong></p>';
		} 
		else{
			return '<p class="text-wrap"><strong>No</strong></p>';
		}
		
	}
	function isBetween($from, $till, $input) {
		$f = DateTime::createFromFormat('!H:i', $from);
		$t = DateTime::createFromFormat('!H:i', $till);
		$i = DateTime::createFromFormat('!H:i', $input);
		if ($f > $t) $t->modify('+1 day');
		return ($f <= $i && $i <= $t) || ($f <= $i->modify('+1 day') && $i <= $t);
	}
	function isBetweenForTable($from, $till, $input) {
		$f = DateTime::createFromFormat('!H:i', $from);
		$t = DateTime::createFromFormat('!H:i', $till);
		$i = DateTime::createFromFormat('!H:i', $input);
		if ($f > $t) $t->modify('+1 day');
		return ($f < $i && $i < $t) || ($f < $i->modify('+1 day') && $i < $t);
	}
}
