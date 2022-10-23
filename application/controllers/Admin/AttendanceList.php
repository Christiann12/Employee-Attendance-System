<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AttendanceList extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('Asia/Singapore');
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->library('csvimport');
		$this->load->helper('cookie');
		$this->load->model('Admin/Attendance_model');
		$this->load->model('Admin/Employee_model');
		$this->load->model('Admin/UserLog_model');
	}

	public function index(){
        
		if($this->session->userdata('isLogIn') === true){
			$userData = $this->db->get_where('users', array('userId' => $this->session->userdata('userId')))->row();
			if (!empty($userData)) {
				$data['page'] = "AttendanceList";
				$this->load->view('HeaderAndFooter/Header.php');
				$this->load->view('Pages/Admin/Wrapper.php',$data);
				$this->load->view('HeaderAndFooter/Footer.php');
			} else {
				redirect('AdminLogin');
			}
			
		}
		else{
			if(!empty(get_cookie('remember_me_token'))){
				$userData = $this->User_model->getCurrentUserCookie(get_cookie('remember_me_token'));
				if (!empty($userData)) {
					$this->session->set_userdata([
						'isLogIn'     => true,
						'userRole'     => $userData->userRole,
						'userId'     => $userData->userId,
						'firstName'     => $userData->fname,
						'lastName'  => $userData->lname,
						'email'       => $userData->email,
					]);
					redirect('TimeSheet');
				} else {
					redirect('AdminLogin');
				}
				
			}
			else{
				redirect('AdminLogin');
			}
		}
	}
	public function generateTable(){
		$data1 = $this->Attendance_model->getTableData($this->input->post('empId'),$this->input->post('date'));

		$data = array();

		foreach($data1 as $listItem){
			$empData = $this->Employee_model->getEmp($listItem->empId);
			$regularHour = $this->calculateWorkHour($listItem->timeinf,$listItem->timeoutf,$listItem->timeins,$listItem->timeouts,$listItem->dayoff);
			$overTimeHour = $this->calculateWorkHourOT($listItem->timeinf,$listItem->timeoutf,$listItem->timeins,$listItem->timeouts,$listItem->dayoff);
			$UT_OT = $this->checkifUT_OT($listItem->datetimein,$empData->dayoff,$listItem->timeinf,$listItem->timeoutf,$listItem->timeins,$listItem->timeouts,$overTimeHour,$regularHour,$listItem->pictureUrlTimeout,$listItem->dayoff);
			$breakHour = $this->calculateBreakHour($listItem->timeinf,$listItem->timeoutf,$listItem->timeins,$listItem->timeouts,$listItem->dayoff);
			$late = $this->checkiflate($listItem->late);

			$row = array();
			$row['attendanceId'] = $listItem->attendanceId;
			$row['empId'] = $listItem->empId;
			$row['fname'] = $listItem->fname;
			$row['lname'] = $listItem->lname;
			$row['time1'] = $listItem->timeinf.' - '.$listItem->timeoutf;
			$row['time2'] = $listItem->timeins.' - '.$listItem->timeouts;
			$row['Hours_Worked_Regular'] = $regularHour;
			$row['Hours_Worked_OT'] = $overTimeHour;
			$row['Break_Hour'] = $breakHour[0];
			$row['Dayoff'] = $this->checkIfDayOff($listItem->dayoff);
			$row['Late'] = $late;
			$row['UT_OT'] = $UT_OT;
			$row['OverBreak'] = $breakHour[1];
			$row['Date_Time_In'] = $listItem->datetimein;
			
			// $row['data7'] = $listItem->datetimein;
			$data[] = $row;
		}
		$json_data['data'] = $data;
		echo json_encode($json_data);
	}
	public function import(){
		$file_data = $this->csvimport->get_array($_FILES["csv_file"]["tmp_name"]);
		$status = 'Still Good';
		$data = array();

		if(!array_key_exists('empId',$file_data[0]) || !array_key_exists('timein_beforebreak',$file_data[0]) || !array_key_exists('timeout_beforebreak',$file_data[0]) || !array_key_exists('timein_afterbreak',$file_data[0]) || !array_key_exists('timeout_afterbreak',$file_data[0]) || !array_key_exists('datetimein',$file_data[0]) || !array_key_exists('datetimeout',$file_data[0])){
			$status = "Required header is missing or wrong!";
		}
		if(count($file_data) * 8 != count($file_data,1)){
			$status = "Invalid format of data, please double check the file for inconsistencies then try again.";
		}

		if ($status === 'Still Good') {
			foreach ($file_data as $csvitem) {
				// check if blank
				if($csvitem["empId"] == '' || $csvitem["timein_beforebreak"] == '' || $csvitem["timeout_beforebreak"] == '' || $csvitem["timein_afterbreak"] == '' ||  $csvitem["timeout_afterbreak"] == '' || $csvitem["datetimein"] == '' || $csvitem["datetimeout"] == '' ){
					$status = "There's a blank record!";
					break;
				}
				// check if valid empId
				if($this->checkEmpId($csvitem["empId"])){
					$status = "An Employee ID listed does not exist";
					break;
				}
				// check if valid time
				if((!$this->checkTime($csvitem["timein_beforebreak"]) || !$this->checkTime($csvitem["timeout_beforebreak"]) || !$this->checkTime($csvitem["timein_afterbreak"]) || !$this->checkTime($csvitem["timeout_afterbreak"])) ){
					$status = "A record has an invalid timein or timeout";
					break;
				}
				// check if valid time
				if(!$this->checkDate($csvitem["datetimein"]) || !$this->checkDate($csvitem["datetimeout"])){
					$status = "A record has an invalid date format";
					break;
				}

				// check if Employee has schedule
				if(!$this->checkSched($csvitem["empId"])){
					$status = "An employee doesn't have a schedule";
					break;
				}


				$row = array();

				$empData = $this->Employee_model->getEmp($csvitem["empId"]);
				
				if ($this->isBetween($empData->timein,$empData->timeout,$csvitem["timein_beforebreak"])) 
				{
					$row["late"] = "Late";
				} else {	
					
					$row["late"] = "On Time";
				}
				if (strtolower(date('l',strtotime($csvitem["datetimein"]))) == strtolower($empData->dayoff)) 
				{
					$row["dayoff"] = "Yes";
				} else {	
					
					$row["dayoff"] = "No";
				}
			

				$row["empId"] = strtoupper($csvitem["empId"]);
				$row["timeinf"] = $csvitem["timein_beforebreak"];
				$row["timeoutf"] = $csvitem["timeout_beforebreak"];
				$row["timeins"] = $csvitem["timein_afterbreak"];
				$row["timeouts"] = $csvitem["timeout_afterbreak"];
				$row["datetimein"] = date('Y-m-d', strtotime($csvitem["datetimein"]));
				$row["datetimeout"] = date('Y-m-d', strtotime($csvitem["datetimeout"]));
				$row["pictureUrlTimein"] = "On Premise";
				$row["pictureUrlTimeOut"] = "On Premise";
				
				
				$data[] = $row;
			}
			if ($status === 'Still Good') {
				if ($this->Attendance_model->addBatch($data)) {
					$this->UserLog_model->addLog('Import Attendance List',$this->session->userdata('userId'),True);
				} else {
					$test = array();
					$this->output->set_status_header('400'); //Triggers the jQuery error callback
					$test['message'] = 'Something went wrong, please try again later';
					$this->UserLog_model->addLog('Import Attendance List',$this->session->userdata('userId'),False);
					echo json_encode($test);
				}
				
			} else {
				$test = array();
				$this->output->set_status_header('400'); //Triggers the jQuery error callback
				$test['message'] = $status;
				$this->UserLog_model->addLog('Import Attendance List',$this->session->userdata('userId'),False);
				echo json_encode($test);
			}
			
		} else {
			$test = array();
			$this->output->set_status_header('400'); //Triggers the jQuery error callback
			$test['message'] = $status;
			$this->UserLog_model->addLog('Import Attendance List',$this->session->userdata('userId'),False);
			echo json_encode($test);
		}
		
	}
	//custom function
	public function checkTime($date, $format = 'G:i'){
		if($date = "EMPTY"){
			return true;
		}
		$d = DateTime::createFromFormat($format, $date);
		// The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
		return $d && $d->format($format) === $date;
	}
	public function checkDate($date, $format = 'm/d/Y'){
		$d = DateTime::createFromFormat($format, $date);
		// The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
		return $d && $d->format($format) === $date;
	}
	public function checkDayoff($var = ''){
		$array = array('monday','tuesday','wednesday','thursday','friday','saturday','sunday');
		if(in_array(strtolower($var), $array)){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	public function checkSched($id = ''){
		$empData = $this->db->select('*')->where('empId',$id)->get('employee')->row();
		if ($empData->timein == 'timein' || $empData->timein == 'timeout' || $empData->timein == 'dayoff'  ) {
            return false;
        } else {
            return true;
        }
	}
	public function checkEmpId($id = ''){
		$count = $this->db->select('*')->where('empId',$id)->get('employee')->num_rows();
		if ($count > 0) {
            return false;
        } else {
            return true;
        }
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
	function checkifUT_OT($datetimein,$dayoff,$timeinf,$timeoutf,$timeins,$timeouts,$overTimeHour,$regularHour,$pictureUrlTimeout,$newDayOff){
		if($pictureUrlTimeout == 'empty'){
			return '-';
		}
		else if($newDayOff == 'No'){
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
		else if($newDayOff == 'Yes'){
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
		return ($f < $i && $i < $t) || ($f < $i->modify('+1 day') && $i < $t);
	}
}
