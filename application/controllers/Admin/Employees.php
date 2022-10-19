<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employees extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('Asia/Singapore');
		//helpers
		$this->load->helper('url');
		$this->load->helper('cookie');
		//libraries
		$this->load->library('session');
		$this->load->library('encryption');
		$this->load->library('csvimport');
		//model
		$this->load->model('Admin/Employee_model');
		$this->load->model('Admin/User_model');
		$this->load->model('Admin/UserLog_model');
	}
	//Display Employee Page
	public function index(){
		
		if($this->session->userdata('isLogIn') === true){
			$userData = $this->db->get_where('users', array('userId' => $this->session->userdata('userId')))->row();
			if (!empty($userData)) {
				$data['page'] = "AdminEmployee";
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
					redirect('AdminEmployees');
				} else {
					redirect('AdminLogin');
				}
			}
			else{
				redirect('AdminLogin');
			}
		}
	}
	//Display Edit Page
	public function editEmployee($id = ''){
		$urlData = urldecode($this->safe_decode($id));
		$data['employeeData'] = $this->Employee_model->getEmp($this->encryption->decrypt($urlData) == '' ? null : $this->encryption->decrypt($urlData));

		if(!empty($data['employeeData'])){
			if($this->session->userdata('isLogIn') === true){
				$userData = $this->db->get_where('users', array('userId' => $this->session->userdata('userId')))->row();
				if (!empty($userData)) {
					$data['page'] = "EmployeeEditPage";
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
						redirect('EditEmployee/'.$this->safe_encode(urlencode($this->encryption->encrypt($data['employeeData']->empId))));
					} else {
						redirect('AdminLogin');
					}
					
				}
				else{
					redirect('AdminLogin');
				}
			}
		}
		else{
			redirect('AdminEmployees');
		}
	}
	//Delete Employee
	public function deleteEmployee($id = ''){
		$urlData = urldecode($this->safe_decode($id));
		if ($this->Employee_model->deleteEmployee($this->encryption->decrypt($urlData) == '' ? null : $this->encryption->decrypt($urlData))){
			$this->session->set_flashdata('successAddEmployee','Delete Success');
			$this->UserLog_model->addLog('Delete Employee',$this->session->userdata('userId'),True);
		}
		else{
			$this->session->set_flashdata('failAddEmployee','Delete Failed');
			$this->UserLog_model->addLog('Delete Employee',$this->session->userdata('userId'),False);
		}
		redirect('AdminEmployees');
	}
	//Update employee
	public function saveEdit(){
		$this->form_validation->set_rules('employeeFirstName', 'First Name' ,'required|callback_checkFieldIfHasNum|callback_checkFieldIfHasSP|max_length[50]');
		$this->form_validation->set_rules('employeeLastName', 'Last Name' ,'required|callback_checkFieldIfHasNum|callback_checkFieldIfHasSP|max_length[50]');
		
		$postData = array(
            "empId" => $this->input->post("employeeId"),
            "fname" => ucfirst(strtolower($this->input->post("employeeFirstName"))),
            "lname" => ucfirst(strtolower($this->input->post("employeeLastName"))),
			"timein" => (empty($this->input->post("timein")) ? 'timein' : $this->input->post("timein")),
            "timeout" => (empty($this->input->post("timeout")) ? 'timeout' : $this->input->post("timeout")),
			"dayoff" => (empty($this->input->post("dayoff")) ? 'dayoff' : $this->input->post("dayoff")),
        );

		if($this->form_validation->run() === true){
			if ($this->Employee_model->saveEdit($postData)){
				$this->session->set_flashdata('successEditEmployee','Edit Success');
				$this->UserLog_model->addLog('Update Employee',$this->session->userdata('userId'),True);
			}
			else{
				$this->session->set_flashdata('failEditEmployee','Edit Failed');
				$this->UserLog_model->addLog('Update Employee',$this->session->userdata('userId'),False);
			}
            redirect('EditEmployee/'.$this->safe_encode(urlencode($this->encryption->encrypt($this->input->post("employeeId")))));
        }
        else{
			$this->session->set_flashdata('failEditEmployee',validation_errors());
			$this->UserLog_model->addLog('Update Employee',$this->session->userdata('userId'),False);
            redirect('EditEmployee/'.$this->safe_encode(urlencode($this->encryption->encrypt($this->input->post("employeeId")))));
        }
	}
	//Add employee
	public function addEmployee(){
		$this->form_validation->set_rules('employeeFirstName', 'First Name' ,'required|callback_checkFieldIfHasNum|callback_checkFieldIfHasSP|max_length[50]');
		$this->form_validation->set_rules('employeeLastName', 'Last Name' ,'required|callback_checkFieldIfHasNum|callback_checkFieldIfHasSP|max_length[50]');
		$this->form_validation->set_rules('employeeLocation', 'Branch' ,'required|callback_checkFieldIfHasNum|max_length[100]');
		
		$rowCount = $this->db->select("CAST(SUBSTR(empId,11,LENGTH(empId)) as UNSIGNED) as newid")->from('employee')->order_by('newid', 'DESC')->limit(1)->get()->row();
		$newId = !empty($rowCount) ? (int)$rowCount->newid : 0;
		$postData = array(
            "empId" => "EMP-".date('Y').'-0'. $newId+=1 ,
			"secretId" => "scrt".$this->randStrGen(1,12),
            "fname" => ucfirst(strtolower($this->input->post("employeeFirstName"))),
            "lname" => ucfirst(strtolower($this->input->post("employeeLastName"))),
            "location" => ucfirst(strtolower($this->input->post("employeeLocation"))),
        );
		
		if($this->form_validation->run() === true){
			if ($this->Employee_model->addEmployee($postData)){
				$this->session->set_flashdata('successAddEmployee','Add Success');
				$this->UserLog_model->addLog('Add Employee',$this->session->userdata('userId'),True);
			}
			else{
				$this->session->set_flashdata('failAddEmployee','Add Failed');
				$this->UserLog_model->addLog('Add Employee',$this->session->userdata('userId'),False);
			}
            redirect('AdminEmployees');
        }
        else{
			$this->UserLog_model->addLog('Add Employee',$this->session->userdata('userId'),False);
			$this->session->set_flashdata('failAddEmployee',validation_errors());
            redirect('AdminEmployees');
        }
	}
	//get table data
	public function EmployeeTableAjax(){
		$data1 = $this->Employee_model->getTableData();
	
		// $productss = $this->inventory_model->productList();
		$data = array();

		foreach($data1 as $listItem){
			$row = array();
			$row['data1'] = $listItem->empId;
			$row['data8'] = $listItem->newId;
			$row['data2'] = $listItem->fname;
			$row['data3'] = $listItem->lname;
			$row['data9'] = $listItem->location;
			$row['data4'] = $listItem->timein != 'timein' || $listItem->timeout != 'timeout' ? $listItem->timein.' - '.$listItem->timeout : 'No Schedule Available';
			$row['data5'] = $listItem->dayoff != 'dayoff' ? $listItem->dayoff : 'No Dayoff Available';
			$row['data6'] = $listItem->secretId;
			$row['data7'] = $this->safe_encode(urlencode($this->encryption->encrypt($listItem->empId)));
			$data[] = $row;
		}
		$json_data['data'] = $data;
		echo json_encode($json_data);
	}
	// import function 
	public function import(){
		$file_data = $this->csvimport->get_array($_FILES["csv_file"]["tmp_name"]);
		$status = 'Still Good';
		$rowCount = $this->db->select("CAST(SUBSTR(empId,11,LENGTH(empId)) as UNSIGNED) as newid")->from('employee')->order_by('newid', 'DESC')->limit(1)->get()->row();
		$newId = !empty($rowCount) ? (int)$rowCount->newid : 0;
		$data = array();

		if(!array_key_exists('firstname',$file_data[0]) || !array_key_exists('lastname',$file_data[0]) || !array_key_exists('branch',$file_data[0])){
			$status = "Required header is missing or wrong!";
			
		}
		if(count($file_data) * 4 != count($file_data,1)){
			$status = "Invalid format of data, please double check the file for inconsistencies then try again.";
		}
		
		if($status === 'Still Good'){
			foreach($file_data as $csvitem){
				// check if blank
				if($csvitem["firstname"] == '' || $csvitem["lastname"] == '' || $csvitem["branch"] == ''){
					$status = "There's a blank record!";
					break;
				}
				// check if has number
				if( preg_match('~[0-9]+~', $csvitem["firstname"]) || preg_match('~[0-9]+~', $csvitem["lastname"]) || preg_match('~[0-9]+~', $csvitem["branch"])){
					$status = "There's a numerical value in a record!";
					break;
				}
				// check if has special character
				if( preg_match('/[\'^£$%&*(!)}+{@#~?><>\[\],|=_¬-]/', $csvitem["firstname"]) || preg_match('/[\'^£$%&*(!)}+{@#~?><>\[\],|=_¬-]/', $csvitem["lastname"])){
					$status = "There's a value with a special character";
					break;
				}

				$row = array();
				
				$row["empId"] =  "EMP-".date('Y').'-0'.$newId+=1;
				$row["secretId"] = "scrt".$this->randStrGen(1,12);
				$row["fname"] = ucfirst(strtolower($csvitem["firstname"]));
				$row["lname"] = ucfirst(strtolower($csvitem["lastname"]));
				$row["location"] = ucfirst(strtolower($csvitem["branch"]));
				$data[] = $row;
			}
			if($status === 'Still Good'){
				if($this->Employee_model->addEmployeeBatch($data)){
					$this->UserLog_model->addLog('Import Employee',$this->session->userdata('userId'),True);
				}
				else{
					$test = array();
					$this->output->set_status_header('400'); //Triggers the jQuery error callback
					$test['message'] = 'Something went wrong, please try again later';
					$this->UserLog_model->addLog('Import Employee',$this->session->userdata('userId'),False);
					echo json_encode($test);
				}
			}
			else {
				$test = array();
				$this->output->set_status_header('400'); //Triggers the jQuery error callback
				$test['message'] = $status;
				$this->UserLog_model->addLog('Import Employee',$this->session->userdata('userId'),False);
				echo json_encode($test);
			}
		}
		else {
			$test = array();
			$this->output->set_status_header('400'); //Triggers the jQuery error callback
			$test['message'] = $status;
			$this->UserLog_model->addLog('Import Employee',$this->session->userdata('userId'),False);
			echo json_encode($test);
		}
		
	}
	//functions
	function safe_encode($string){
		return str_replace("%", ":", $string);
	}
	
	function safe_decode($string){
		return str_replace(":", "%", $string);
	}
	public function randStrGen($mode = null, $len = null){
        $result = "";
        if($mode == 1):
            $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        elseif($mode == 2):
            $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        elseif($mode == 3):
            $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        elseif($mode == 4):
            $chars = "0123456789";
        endif;

        $charArray = str_split($chars);
        for($i = 0; $i <= $len; $i++) {
                $randItem = array_rand($charArray);
                $result .="".$charArray[$randItem];
        }
        return $result;
    }
	public function checkFieldIfHasNum($text = ''){
		if( preg_match('~[0-9]+~', $text)){
			$this->form_validation->set_message('checkFieldIfHasNum', 'The {field} has numeric value!');
            return false;
		}
		else{
			return true;
		}
	}
	public function checkFieldIfHasSP($text = ''){
		if( preg_match('/[\'^£$%&*(!)}+{@#~?><>\[\],|=_¬-]/', $text)){
			$this->form_validation->set_message('checkFieldIfHasSP', 'The {field} has special character!');
            return false;
		}
		else{
			return true;
		}
	}
}
