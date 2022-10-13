<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ScanQrAndroid extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('Asia/Singapore');
		$this->load->helper('url');
		$this->load->helper('cookie');
		$this->load->library('session');
	}

	public function index()
	{
        if($this->session->userdata('isLogInEmployee') === true){
			$userData = $query = $this->db->get_where('employee', array('empId' => $this->session->userdata('employeeId')))->row();
			if (!empty($userData)) {
				$this->load->view('Pages/General/QrScanAndroid.php');
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
					$this->load->view('Pages/General/QrScanAndroid.php');
				} else {
					redirect('');
				}
				
			}
			else{
				redirect('');
			}
		}
	}
	
}
