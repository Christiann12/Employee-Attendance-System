<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UploadSched extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('Asia/Singapore');
		$this->load->helper('url');
		$this->load->library('session');
        $this->load->library('csvimport');
		$this->load->model('Admin/Employee_model');
		$this->load->model('Admin/UserLog_model');
		$this->load->model('Admin/Attendance_model');
	}

	//Load Upload Sched Page
	public function index(){
        
		if($this->session->userdata('isLogIn') === true){
			$userData = $this->db->get_where('users', array('userId' => $this->session->userdata('userId')))->row();
			if (!empty($userData)) {
				$data['page'] = "UploadSched";
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
					redirect('UploadSched');
				} else {
					redirect('AdminLogin');
				}	
			}
			else{
				redirect('AdminLogin');
			}
		}
	}
	//Upload Logic
    public function import(){
		$file_data = $this->csvimport->get_array($_FILES["csv_file"]["tmp_name"]);
		$data = array();
		$status = 'Still Good';
		$count = 0;
		if(!array_key_exists('empId',$file_data[0]) || !array_key_exists('timein',$file_data[0]) || !array_key_exists('timeout',$file_data[0]) || !array_key_exists('dayoff',$file_data[0])){
			$status = "Required header  is missing or wrong!";
			
		}
		if(count($file_data) * 5 != count($file_data,1)){
			$status = "Invalid format of data, please double check the file for inconsistencies then try again.";
			
		}
		if($status === 'Still Good'){
			$count++;
			foreach($file_data as $csvitem){
				// check if blank
				if($csvitem["empId"] == '' || $csvitem["timein"] == '' || $csvitem["timeout"] == '' || $csvitem["dayoff"] == ''){
					$status = "There's a blank record!";
					break;
				}
				// check if has number
				if(preg_match('~[0-9]+~', $csvitem["dayoff"])){
					$status = "There's a numerical value under Dayoff Field!";
					break;
				}
				// check if has special character
				if( preg_match('/[\'^£$%&*(!)}+{@#~?><>\[\],|=_¬]/', $csvitem["empId"]) || preg_match('/[\'^£$%&*(!)}+{@#~?><>\[\],|=_¬-]/', $csvitem["dayoff"])){
					$status = "There's a value with a special character";
					break;
				}
				// check if valid dayoff
				if(!$this->checkDayoff($csvitem["dayoff"])){
					$status = "A record has an invalid Dayoff";
					break;
				}
				// check if valid time
				if(!$this->checkTime($csvitem["timein"]) || !$this->checkTime($csvitem["timeout"])){
					$status = "A record has an invalid timein or timeout";
					break;
				}
				// check if valid empId
				if($this->checkEmpId($csvitem["empId"])){
					$status = "An Employee ID listed does not exist";
					break;
				}
				$attendanceDetail = $this->Attendance_model->getTimeIn($csvitem["empId"]);
				if (!empty($attendanceDetail)) {
					$status = "Employee ".$csvitem["empId"]." still has a pending timein/timeout. Please try again after their working hour.";
					break;
				}


				$row = array();
				
				$row["empId"] = $csvitem["empId"];
				$row["timein"] = date("H:i", strtotime($csvitem["timein"]));
				$row["timeout"] = date("H:i", strtotime($csvitem["timeout"]));
				$row["dayoff"] = ucfirst(strtolower($csvitem["dayoff"]));
				$data[] = $row;
			}
			if($status === 'Still Good'){
				if ($this->Employee_model->editSchedule($data)) {
					$this->UserLog_model->addLog('Upload Schedule',$this->session->userdata('userId'),True);
				} else {
					$this->UserLog_model->addLog('Upload Schedule',$this->session->userdata('userId'),False);
				}
				
			}
			else{
				$test = array();
				$this->output->set_status_header('400'); //Triggers the jQuery error callback
				$test['message'] = $status;
				$this->UserLog_model->addLog('Upload Schedule',$this->session->userdata('userId'),False);
				echo json_encode($test);
			}
		}
		else{
			$test = array();
			$this->output->set_status_header('400'); //Triggers the jQuery error callback
			$test['message'] = $status;
			$this->UserLog_model->addLog('Upload Schedule',$this->session->userdata('userId'),False);
			echo json_encode($test);
		}
		
	}
	
    //get table data
	public function generateTable(){
		$data1 = $this->Employee_model->getTableFiltered();
		
		$data = array();

		foreach($data1 as $listItem){
			$row = array();
			$row['data1'] = $listItem->empId;
			$row['data2'] = $listItem->fname;
			$row['data3'] = $listItem->lname;
			$row['data4'] = $listItem->timein.' - '.$listItem->timeout;
			$row['data5'] = $listItem->dayoff;
			$row['data6'] = $listItem->secretId;
			$data[] = $row;
		}
		$json_data['data'] = $data;
		echo json_encode($json_data);
	}
	// function 
	public function checkTime($date, $format = 'G:i'){
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
	public function checkEmpId($id = ''){
		$count = $this->db->select('*')->where('empId',$id)->get('employee')->num_rows();
		if ($count > 0) {
            return false;
        } else {
            return true;
        }
	}
}
