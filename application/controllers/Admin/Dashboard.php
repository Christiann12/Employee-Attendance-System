<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('Asia/Singapore');
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->model('Admin/Employee_model');
		$this->load->model('Admin/Attendance_model');
	}

	public function index()
	{
		
		// dashboard data 

		$data['activeEmp'] = $this->Employee_model->getNoActiveEmployee();
		$data['noPresent'] = $this->Attendance_model->getNoPresent();
		$data['totalNumEmp'] = $this->Employee_model->getTotalNoEmp();
		$data['noLate'] = $this->Attendance_model->getLate();
		
        if($this->session->userdata('isLogIn') === true){
			$data['page'] = "AdminDashboard";
			$this->load->view('HeaderAndFooter/Header.php');
			$this->load->view('Pages/Admin/Wrapper.php',$data);
			$this->load->view('HeaderAndFooter/Footer.php');
		}
		else{
			redirect('AdminLogin');
		}
	}
	
}
