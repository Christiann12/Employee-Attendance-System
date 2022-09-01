<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UploadSched extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('Asia/Singapore');
		$this->load->helper('url');
		$this->load->library('session');
        $this->load->library('csvimport');
		$this->load->model('Admin/Employee_model');
	}

	public function index()
	{
        
		if($this->session->userdata('isLogIn') === true){
			$data['page'] = "UploadSched";
			$this->load->view('HeaderAndFooter/Header.php');
			$this->load->view('Pages/Admin/Wrapper.php',$data);
			$this->load->view('HeaderAndFooter/Footer.php');
		}
		else{
			redirect('');
		}
	}

    public function import(){
		$file_data = $this->csvimport->get_array($_FILES["csv_file"]["tmp_name"]);
		foreach($file_data as $row){
			$data = array (
                "empId" => $row["empId"],
                "timein" => date("H:i", strtotime($row["timein"])),
				"timeout" =>  date("H:i", strtotime($row["timeout"])),
                "dayoff" => $row["dayoff"],
            );
			$this->Employee_model->editSchedule($data);
		}
	}
    //get table data
	public function generateTable(){
		$data1 = $this->Employee_model->getTableFiltered();
		
		$data = array();

		foreach($data1 as $listItem){
			$row = array();
			$row['data1'] = $listItem->empId;
			$row['data2'] = $listItem->fname;
			$row['data3'] = $listItem->lname;
			$row['data4'] = $listItem->timein.' - '.$listItem->timeout;
			$row['data5'] = $listItem->dayoff;
			$row['data6'] = $listItem->secretId;
			$data[] = $row;
		}
		$json_data['data'] = $data;
		echo json_encode($json_data);
	}
}
