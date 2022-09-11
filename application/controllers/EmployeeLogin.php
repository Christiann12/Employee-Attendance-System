<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EmployeeLogin extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('Asia/Singapore');
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->model('Admin/Employee_model');
	}

	public function index()
	{

        $this->load->view('Pages/General/EmployeeLogin.php');

	}

	public function checkUser(){
		$this->form_validation->set_rules('empId', 'Employee ID' ,'required');
		$this->form_validation->set_rules('passwordLogin', 'Password' ,'required');

		$postData = array(
			'password' => md5($this->input->post('passwordLogin')),
            'empId' => $this->input->post('empId'),
		);

		if($this->form_validation->run() === true){
			$userData = $this->Employee_model->checkCredentialsEmployee($postData);
			if(!empty($userData)){
				$this->session->set_flashdata('successLoginEmployee','Login Successful');
		
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
			}
			else{
				$this->session->set_flashdata('errorLoginEmployee','Incorrect Email or Password');
				redirect('');
			}
			$this->session->set_flashdata('successLoginEmployee','Login Successful');
			redirect('');
		}
		else{
			$this->session->set_flashdata('errorLoginEmployee',validation_errors());
			redirect('');
		}
	}

	public function signout(){
		$array_items = array('isLogInEmployee', 'employeeId', 'firstNameEmployee', 'lastNameEmployee');
		$this->session->unset_userdata($array_items);
		redirect('');
	}
}
