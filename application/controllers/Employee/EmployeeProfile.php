<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EmployeeProfile extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('Asia/Singapore');
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->helper('cookie');
		$this->load->model('Admin/Employee_model');
		$this->load->model('Admin/Attendance_model');
	}

	public function index()
	{
		$data['lateCount'] = $this->Attendance_model->getTardinessMeasure('Late');
		$data['OnTimeCount'] = $this->Attendance_model->getTardinessMeasure('On Time');
        if($this->session->userdata('isLogInEmployee') === true){
			$userData = $this->db->get_where('employee', array('empId' => $this->session->userdata('employeeId')))->row();
			if (!empty($userData)) {
				$data['page'] = "EmployeeProfile";
				$data['empData'] = $userData;
				$this->load->view('HeaderAndFooter/HeaderEmployee.php');
				$this->load->view('Pages/Employee/WrapperEmployee.php',$data);
				$this->load->view('HeaderAndFooter/FooterEmployee.php',$data);
			} else {
				redirect('');
			}
		}
		else{
			if(!empty(get_cookie('remember_me_token_employee'))){
				$userData = $this->Employee_model->getCurrentUserCookie(get_cookie('remember_me_token_employee'));
				
				if (!empty($userData)) {
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
					redirect('EmployeeProfile');
				} else {
					redirect('');
				}
				
			}
			else{
				redirect('');
			}
		}
	}
	public function saveEdit(){
		// $this->form_validation->set_rules('fnameEdit', 'First Name' ,'required|callback_checkFieldIfHasNum|callback_checkFieldIfHasSP|callback_checkIfNameIsChanged['.$this->input->post('passwordEdit').']');
		// $this->form_validation->set_rules('lnameEdit', 'Last Name' ,'required|callback_checkFieldIfHasNum|callback_checkFieldIfHasSP|callback_checkIfNameIsChanged['.$this->input->post('passwordEdit').']');
		$this->form_validation->set_rules('fnameEdit', 'First Name' ,'required|callback_checkFieldIfHasNum|callback_checkFieldIfHasSP|max_length[50]');
		$this->form_validation->set_rules('lnameEdit', 'Last Name' ,'required|callback_checkFieldIfHasNum|callback_checkFieldIfHasSP|max_length[50]');

		if($this->input->post("passwordEdit") != ''){
			$this->form_validation->set_rules('passwordEdit', 'Password' ,'required|callback_checkPasswordStrength|max_length[100]');
			$this->form_validation->set_rules('confPasswordEdit', 'Confirm Password' ,'required|matches[passwordEdit]');
		}
		
		$postData = array(
            "empId" => $this->input->post('empIdEdit'),
            "fname" => ucfirst(strtolower($this->input->post("fnameEdit"))),
            "lname" => ucfirst(strtolower($this->input->post("lnameEdit"))),
        );

		if($this->input->post("passwordEdit") != ''){
			$postData['password'] = md5($this->input->post("passwordEdit"));
		}

		if($this->form_validation->run() === true){
			if ($this->Employee_model->saveEdit($postData)){
				$userData = $this->Employee_model->getEmp($this->session->userdata('employeeId'));
				$this->session->set_flashdata('successEditEmployee','Edit Success');
				// if($this->session->userdata('userId') == $this->input->post('userIdField')){
				$this->session->set_userdata([
					'isLogInEmployee'     => true,
					'employeeId'     => $postData['empId'],
					'firstNameEmployee'     => $postData['fname'],
					'lastNameEmployee'  => $postData['lname'],
					'employeeTimein'  => $userData->timein,
					'employeeTimeout'  => $userData->timeout,
					'employeeDayoff'  => $userData->dayoff,
					'secretIdEmployee'     => $userData->secretId,
				]);
				// }
			}
			else{
				$this->session->set_flashdata('errorEditEmployee','Edit Failed');
			}
            redirect('EmployeeProfile');
        }
        else{
			$this->session->set_flashdata('errorEditEmployee',validation_errors());
            redirect('EmployeeProfile');
        }
	}
	public function download($id = ''){
		$url = 'https://chart.googleapis.com/chart?cht=qr&chs=250x250&chl='.$id ;
		//$file_name = APPPATH.'assets/attachments/images/QR.png';
      
		// Use file_get_contents() function to get the file
		// from url and use file_put_contents() function to
		// save the file by using base name
		// if (file_put_contents($file_name, file_get_contents($url)))
		// {
		// 	echo "File downloaded successfully";
		// }
		// else
		// {
		// 	echo "File downloading failed.";
		// }
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="qrcode.png"');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		// header('Content-Length: ' . filesize($url));
		readfile($url);
		// redirect('EmployeeProfile');
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
		if( preg_match('/[\'^??$%&*(!)}+{@#~?><>\[\],|=_??-]/', $text)){
			$this->form_validation->set_message('checkFieldIfHasSP', 'The {field} has special character!');
            return false;
		}
		else{
			return true;
		}
	}
	public function checkIfNameIsChanged($text = '',$password = ''){
		if( ($text == $this->session->userdata('lastNameEmployee') || $text == $this->session->userdata('firstNameEmployee') ) && $password == ''){
			$this->form_validation->set_message('checkIfNameIsChanged', 'No new update in {field}!');
            return false;
		}
		else{
			return true;
		}
	}
	public function checkPasswordStrength($password = ''){
		if( strlen($password) < 8 ){
			$this->form_validation->set_message('checkPasswordStrength', 'The {field} must be 8 characters long');
            return false;
		}
		if( !preg_match('/[\'^??$%&*(!)}+{@#~?><>\[\],|=_??-]/', $password)){
			$this->form_validation->set_message('checkPasswordStrength', 'The {field} must contain atleast 1 special character');
            return false;
		}
		if( !preg_match('/[A-Z]/', $password)){
			$this->form_validation->set_message('checkPasswordStrength', 'The {field} must contain atleast 1 upper character');
            return false;
		}
		if( !preg_match('/[a-z]/', $password)){
			$this->form_validation->set_message('checkPasswordStrength', 'The {field} must contain atleast 1 lower character');
            return false;
		}
		else{
			return true;
		}
	}
}
