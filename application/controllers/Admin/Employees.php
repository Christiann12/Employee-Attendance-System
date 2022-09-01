<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employees extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('Asia/Singapore');
		//helpers
		$this->load->helper('url');
		//libraries
		$this->load->library('session');
		$this->load->library('encryption');
		$this->load->library('csvimport');
		//model
		$this->load->model('Admin/Employee_model');
	}

	public function index()
	{
        // $data['test'] = $this->encryption->encrypt('My secret message');
		if($this->session->userdata('isLogIn') === true){
			$data['page'] = "AdminEmployee";
			$this->load->view('HeaderAndFooter/Header.php');
			$this->load->view('Pages/Admin/Wrapper.php',$data);
			$this->load->view('HeaderAndFooter/Footer.php');
		}
		else{
			redirect('');
		}
	}
	public function editEmployee($id = ''){
		$data['employeeData'] = $this->Employee_model->getEmp($id);
		if($this->session->userdata('isLogIn') === true){
			$data['page'] = "EmployeeEditPage";
			$this->load->view('HeaderAndFooter/Header.php');
			$this->load->view('Pages/Admin/Wrapper.php',$data);
			$this->load->view('HeaderAndFooter/Footer.php');
		}
		else{
			redirect('');
		}
	}
	public function deleteEmployee($id = ''){
		if ($this->Employee_model->deleteEmployee($id)){
			$this->session->set_flashdata('successAddEmployee','Delete Success');
		}
		else{
			$this->session->set_flashdata('failAddEmployee','Delete Failed');
		}
		redirect('AdminEmployees');
	}
	//save employee
	public function saveEdit(){
		$this->form_validation->set_rules('employeeFirstName', 'First Name' ,'required');
		$this->form_validation->set_rules('employeeLastName', 'Last Name' ,'required');
		
		$postData = array(
            "empId" => $this->input->post("employeeId"),
            "fname" => $this->input->post("employeeFirstName"),
            "lname" => $this->input->post("employeeLastName"),
			"timein" => $this->input->post("timein"),
            "timeout" => $this->input->post("timeout"),
			"dayoff" => $this->input->post("dayoff"),
        );

		if($this->form_validation->run() === true){
			if ($this->Employee_model->saveEdit($postData)){
				$this->session->set_flashdata('successEditEmployee','Edit Success');
			}
			else{
				$this->session->set_flashdata('failEditEmployee','Edit Failed');
			}
            redirect('EditEmployee/'.$this->input->post("employeeId"));
        }
        else{
			$this->session->set_flashdata('failEditEmployee',validation_errors());
            redirect('EditEmployee/'.$this->input->post("employeeId"));
        }
	}
	//save employee
	public function addEmployee(){
		$this->form_validation->set_rules('employeeFirstName', 'First Name' ,'required');
		$this->form_validation->set_rules('employeeLastName', 'Last Name' ,'required');
		
		$postData = array(
            "empId" => "EMP-".$this->randStrGen(2,7),
			"secretId" => "scrt".$this->randStrGen(1,12),
            "fname" => $this->input->post("employeeFirstName"),
            "lname" => $this->input->post("employeeLastName"),
        );

		if($this->form_validation->run() === true){
			if ($this->Employee_model->addEmployee($postData)){
				$this->session->set_flashdata('successAddEmployee','Add Success');
			}
			else{
				$this->session->set_flashdata('failAddEmployee','Add Failed');
			}
            redirect('AdminEmployees');
        }
        else{
			$this->session->set_flashdata('failAddEmployee',validation_errors());
            redirect('AdminEmployees');
        }
	}

	//get table data
	public function EmployeeTableAjax(){
		$data1 = $this->Employee_model->getTableData();
		
		// $productss = $this->inventory_model->productList();
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
	// import function 
	public function import(){
		$file_data = $this->csvimport->get_array($_FILES["csv_file"]["tmp_name"]);
		foreach($file_data as $row){
			$data = array (
                "empId" => "EMP-".$this->randStrGen(2,7),
				"secretId" => "scrt".$this->randStrGen(1,12),
				"fname" => $row["firstname"],
				"lname" => $row["lastname"],
            );
			$this->Employee_model->addEmployee($data);
		}
	}
	//functions
	public function randStrGen($mode = null, $len = null){
        $result = "";
        if($mode == 1):
            $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        elseif($mode == 2):
            $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        elseif($mode == 3):
            $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        elseif($mode == 4):
            $chars = "0123456789";
        endif;

        $charArray = str_split($chars);
        for($i = 0; $i <= $len; $i++) {
                $randItem = array_rand($charArray);
                $result .="".$charArray[$randItem];
        }
        return $result;
    }
}
