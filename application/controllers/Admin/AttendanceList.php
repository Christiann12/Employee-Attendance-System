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
			$userData = $query = $this->db->get_where('users', array('userId' => $this->session->userdata('userId')))->row();
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
			$row['data6'] = $listItem->hours;
			$row['data7'] = $listItem->datetimein;
			$data[] = $row;
		}
		$json_data['data'] = $data;
		echo json_encode($json_data);
	}
}
