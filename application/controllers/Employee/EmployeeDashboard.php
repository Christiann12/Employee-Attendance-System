<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EmployeeDashboard extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('Asia/Singapore');
		$this->load->helper('url');
		$this->load->library('session');
	}

	public function index()
	{
        if($this->session->userdata('isLogInEmployee') === true){
			$data['page'] = "EmployeeDashboard";
			$this->load->view('HeaderAndFooter/HeaderEmployee.php');
			$this->load->view('Pages/Employee/WrapperEmployee.php',$data);
			$this->load->view('HeaderAndFooter/FooterEmployee.php');
		}
		else{
			redirect('');
		}
	}
	
}
