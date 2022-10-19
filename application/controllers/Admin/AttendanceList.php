<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AttendanceList extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('Asia/Singapore');
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->library('csvimport');
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
			$row = array();
			$row['data1'] = $listItem->attendanceId;
			$row['data2'] = $listItem->empId;
			$row['data3'] = $listItem->fname;
			$row['data8'] = $listItem->lname;
			$row['data4'] = $listItem->timein.' - '.$listItem->timeout;
			$row['data5'] = $listItem->late;
			$row['data6'] = $listItem->ut_ot;
			$row['data9'] = $listItem->workhour;
			$row['data7'] = $listItem->datetimein;
			$data[] = $row;
		}
		$json_data['data'] = $data;
		echo json_encode($json_data);
	}
	public function import(){
		$file_data = $this->csvimport->get_array($_FILES["csv_file"]["tmp_name"]);
		$status = 'Still Good';
		$data = array();

		if(!array_key_exists('empId',$file_data[0]) || !array_key_exists('timein',$file_data[0]) || !array_key_exists('timeout',$file_data[0]) || !array_key_exists('datetimein',$file_data[0]) || !array_key_exists('datetimeout',$file_data[0])){
			$status = "Required header is missing or wrong!";
		}
		if(count($file_data) * 6 != count($file_data,1)){
			$status = "Invalid format of data, please double check the file for inconsistencies then try again.";
		}

		if ($status === 'Still Good') {
			foreach ($file_data as $csvitem) {
				// check if blank
				if($csvitem["empId"] == '' || $csvitem["timein"] == '' || $csvitem["timeout"] == '' || $csvitem["datetimein"] == '' || $csvitem["datetimeout"] == '' ){
					$status = "There's a blank record!";
					break;
				}
				// check if valid empId
				if($this->checkEmpId($csvitem["empId"])){
					$status = "An Employee ID listed does not exist";
					break;
				}
				// check if valid time
				if(!$this->checkTime($csvitem["timein"]) || !$this->checkTime($csvitem["timeout"])){
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

				$row["empId"] = $csvitem["empId"];
				$row["timein"] = $csvitem["timein"];
				$row["timeout"] = $csvitem["timeout"];
				$row["timeinsched"] = $empData->timein;
				$row["timeoutsched"] = $empData->timeout;
				$row["datetimein"] = date('Y-m-d', strtotime($csvitem["datetimein"]));
				$row["datetimeout"] = date('Y-m-d', strtotime($csvitem["datetimeout"]));
				
				if ($this->isBetween($empData->timein,$empData->timeout,$csvitem["timein"])) 
				{
					$row["late"] = 'Late';
				} else {	
					$row["late"] = 'On time';
				}
				
				if(strtolower(date('l',strtotime($csvitem["datetimein"]))) == strtolower($empData->dayoff)){
					$row['ut_ot'] = 'Overtime-DayOff';
					$row['workhour'] = gmdate("H:i:s", ( strtotime($csvitem["timeout"]) - strtotime($csvitem["timein"]) ));
				}
				else if ($this->isBetween($empData->timein,$empData->timeout,$csvitem["timeout"])){
					$row['ut_ot'] = 'Under Time';
					$row['workhour'] = gmdate("H:i:s", ( strtotime($csvitem["timeout"]) - strtotime($csvitem["timein"]) ));
				}
				else if ($empData->timeout == $csvitem["timeout"] || $this->isBetween($empData->timeout,date("H:i" , strtotime($empData->timeout."+15min")),$csvitem["timeout"])){
					$row['ut_ot'] = 'On Time';
					$row['workhour'] = gmdate("H:i:s", ( strtotime($csvitem["timeout"]) - strtotime($csvitem["timein"]) ));
				}
				else{
					$row['ut_ot'] = 'Over Time';
					$row['workhour'] = gmdate("H:i:s", ( strtotime($csvitem["timeout"]) - strtotime($csvitem["timein"]) )) <= strtotime("16 hour") ? 
					gmdate("H:i:s", ( strtotime($csvitem["timeout"]) - strtotime($csvitem["timein"]) )) :
					gmdate("H:i:s", ( strtotime(date("16:00")) - strtotime(date("0:00")))) ;
				}

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
	function isBetween($from, $till, $input) {
		$f = DateTime::createFromFormat('!H:i', $from);
		$t = DateTime::createFromFormat('!H:i', $till);
		$i = DateTime::createFromFormat('!H:i', $input);
		if ($f > $t) $t->modify('+1 day');
		return ($f < $i && $i < $t) || ($f < $i->modify('+1 day') && $i < $t);
	}
}
