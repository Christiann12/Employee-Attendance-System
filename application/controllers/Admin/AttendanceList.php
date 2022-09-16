<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AttendanceList extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('Asia/Singapore');
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->model('Admin/Attendance_model');
	}

	public function index()
	{
        
		if($this->session->userdata('isLogIn') === true){
			$data['page'] = "AttendanceList";
			$this->load->view('HeaderAndFooter/Header.php');
			$this->load->view('Pages/Admin/Wrapper.php',$data);
			$this->load->view('HeaderAndFooter/Footer.php');
		}
		else{
			redirect('AdminLogin');
		}
	}
	public function generateTable(){
		$data1 = $this->Attendance_model->getTableData($this->input->post('empId'),$this->input->post('date'));
		
		// $productss = $this->inventory_model->productList();
		$data = array();

		foreach($data1 as $listItem){
			$row = array();
			$row['data1'] = $listItem->attendanceId;
			$row['data2'] = $listItem->empId;
			$row['data3'] = $listItem->fname.' '.$listItem->lname;
			$row['data4'] = $listItem->timein.' - '.$listItem->timeout;
			$row['data5'] = $listItem->late;
			$row['data6'] = $listItem->hours;
			$row['data7'] = $listItem->datetimein;
			$data[] = $row;
		}
		$json_data['data'] = $data;
		echo json_encode($json_data);
	}
}
