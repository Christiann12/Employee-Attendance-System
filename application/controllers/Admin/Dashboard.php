<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('Asia/Singapore');
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->helper('cookie');
		$this->load->model('Admin/Employee_model');
		$this->load->model('Admin/Attendance_model');
		$this->load->model('Admin/User_model');
	}

	public function index()
	{
		
		// dashboard data 
		// $data['test'] = get_cookie('remember_me_token');

		$data['activeEmp'] = $this->Employee_model->getNoActiveEmployee();
		$data['noPresent'] = $this->Attendance_model->getNoPresent();
		$data['totalNumEmp'] = $this->Employee_model->getTotalNoEmp();
		$data['noLate'] = $this->Attendance_model->getLate();

		$data['LateCountJanuary'] = $this->Attendance_model->getMonthCountLate(date("m", strtotime("January")));
		$data['LateCountFebruary'] = $this->Attendance_model->getMonthCountLate(date("m", strtotime("February")));
		$data['LateCountMarch'] = $this->Attendance_model->getMonthCountLate(date("m", strtotime("March")));
		$data['LateCountApril'] = $this->Attendance_model->getMonthCountLate(date("m", strtotime("April")));
		$data['LateCountMay'] = $this->Attendance_model->getMonthCountLate(date("m", strtotime("May")));
		$data['LateCountJune'] = $this->Attendance_model->getMonthCountLate(date("m", strtotime("June")));
		$data['LateCountJuly'] = $this->Attendance_model->getMonthCountLate(date("m", strtotime("July")));
		$data['LateCountAugust'] = $this->Attendance_model->getMonthCountLate(date("m", strtotime("August")));
		$data['LateCountSeptember'] = $this->Attendance_model->getMonthCountLate(date("m", strtotime("September")));
		$data['LateCountOctober'] = $this->Attendance_model->getMonthCountLate(date("m", strtotime("October")));
		$data['LateCountNovember'] = $this->Attendance_model->getMonthCountLate(date("m", strtotime("November")));
		$data['LateCountDecember'] = $this->Attendance_model->getMonthCountLate(date("m", strtotime("December")));

		$data['OnTimeCountJanuary'] = $this->Attendance_model->getMonthCountOntime(date("m", strtotime("January")));
		$data['OnTimeCountFebruary'] = $this->Attendance_model->getMonthCountOntime(date("m", strtotime("February")));
		$data['OnTimeCountMarch'] = $this->Attendance_model->getMonthCountOntime(date("m", strtotime("March")));
		$data['OnTimeCountApril'] = $this->Attendance_model->getMonthCountOntime(date("m", strtotime("April")));
		$data['OnTimeCountMay'] = $this->Attendance_model->getMonthCountOntime(date("m", strtotime("May")));
		$data['OnTimeCountJune'] = $this->Attendance_model->getMonthCountOntime(date("m", strtotime("June")));
		$data['OnTimeCountJuly'] = $this->Attendance_model->getMonthCountOntime(date("m", strtotime("July")));
		$data['OnTimeCountAugust'] = $this->Attendance_model->getMonthCountOntime(date("m", strtotime("August")));
		$data['OnTimeCountSeptember'] = $this->Attendance_model->getMonthCountOntime(date("m", strtotime("September")));
		$data['OnTimeCountOctober'] = $this->Attendance_model->getMonthCountOntime(date("m", strtotime("October")));
		$data['OnTimeCountNovember'] = $this->Attendance_model->getMonthCountOntime(date("m", strtotime("November")));
		$data['OnTimeCountDecember'] = $this->Attendance_model->getMonthCountOntime(date("m", strtotime("December")));
		
        if($this->session->userdata('isLogIn') === true){
			$userData = $query = $this->db->get_where('users', array('userId' => $this->session->userdata('userId')))->row();
			if (!empty($userData)) {
				$data['page'] = "AdminDashboard";
				$this->load->view('HeaderAndFooter/Header.php');
				$this->load->view('Pages/Admin/Wrapper.php',$data);
				$this->load->view('HeaderAndFooter/Footer.php',$data);
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
					redirect('AdminDashboard');
				} else {
					redirect('AdminLogin');
				}
				
			}
			else{
				redirect('AdminLogin');
			}
		}
	}

	public function getMonthDetail(){
		$date['monday'] = date("m", strtotime(""));
		// $date['tuesday'] = date("Y-m-d", strtotime("tuesday this week"));
		// $date['wednesday'] = date("Y-m-d", strtotime("wednesday this week"));
		// $date['thursday'] = date("Y-m-d", strtotime("thursday this week"));
		// $date['friday'] = date("Y-m-d", strtotime("friday this week"));
		// $date['saturday'] = date("Y-m-d", strtotime("saturday this week"));
		// $date['sunday'] = date("Y-m-d", strtotime("sunday this week"));
		return $date;
	}
	
}
