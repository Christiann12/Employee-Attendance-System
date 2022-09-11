<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EmployeeDashboard extends CI_Controller {

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
		$data['attendanceDetail'] = $this->Employee_model->getEmp($this->session->userdata('employeeId'));
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
	public function generateTable(){
		$data1 = $this->Attendance_model->getTableDataByEmployee();
		
		// $productss = $this->inventory_model->productList();
		$data = array();

		foreach($data1 as $listItem){
			$row = array();
			$row['data1'] = $listItem->timein;
			$row['data2'] = $listItem->timeout;
			$row['data3'] = $listItem->date;
			$data[] = $row;
		}
		$json_data['data'] = $data;
		echo json_encode($json_data);
	}
}
