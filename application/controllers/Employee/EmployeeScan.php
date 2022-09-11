<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EmployeeScan extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('Asia/Singapore');
		$this->load->helper('url');
		$this->load->library('session');
        $this->load->model('Admin/Attendance_model');
        $this->load->model('Admin/Employee_model');
	}

	public function index($id="")
	{
        $data['empData'] = $this->Employee_model->getEmpData($id);
        $attendanceDetail = $this->Attendance_model->getData($data['empData']->empId);
        
        if(empty($attendanceDetail)){
            $this->load->view('Pages/General/InputInformationTimein.php',$data);
        }
        else{
            if($attendanceDetail->timeout != 'timeout'){
                // echo '<script language="javascript">';
                // echo 'alert("message successfully sent")';
                // echo '</script>';
                redirect('EmployeeDashboard');
            }
            else{
                $timeout = date('H:i');
                $checkTime = $data['empData']->timeout;
                $diff = ($checkTime - $timeout);
                
                $postData = array(
                    'empId' => $data['empData']->empId,
                    'timeout' => $timeout,
                );
    
                if(date('l') == $data['empData']->dayoff){
                    $postData['hours'] = 'Overtime';
                }
                else{
                    $postData['hours'] = ($diff === 0) ? 'On Time' : (($diff < 0) ? 'Overtime': 'Halfday');
                }
    
                if($this->Attendance_model->updateRecord($postData)){
                    redirect('EmployeeDashboard');
                }
            }
            
        }
	}
    public function saveTimein(){
        $empData = $this->Employee_model->getEmpData($this->input->post("EmpId"));
        $this->form_validation->set_rules('EmpId', 'Name' ,'required');
        if (empty($_FILES['attachment']['name'])){
			$this->form_validation->set_rules('attachment', 'Attachment' ,'required');
		}

        //load config for upload library
		$config['upload_path']   = APPPATH.'assets/attachments/images/';
		$config['allowed_types'] = 'jpg|jpeg|jpe|png';
		$config['max_size']      = 0;
		$config['max_width']     = 0;
		$config['max_height']    = 0;
		$config['overwrite']     = false;

        // Helpers
		$this->load->helper('url');
		$this->load->library('upload', $config);
		$filename = "";
		
		$name = 'attachment';

        //check if late or not 
        $loginTime = date('H:i');
        $checkTime = $empData->timein;
        $diff = ($checkTime - $loginTime);


        $postData = array(
            "empId" => $empData->empId,
            "timein" => $loginTime,
            "date" => date('Y-m-d'),
            "late" => ($diff < 0)? 'Late' : 'On Time',
        );

        if($this->form_validation->run() === true){
            if ( ! $this->upload->do_upload($name) ) {
                $this->session->set_flashdata('failInput',$this->upload->display_errors());
                redirect('EmployeeScan/'.$this->input->post("EmpId"));
            } 
            else{
                $upload =  $this->upload->data();
                $postData['pictureUrl'] = $upload['file_name'];
                if($this->Attendance_model->create($postData)){
					$this->session->set_flashdata('successInput','Success!');
                    redirect('EmployeeDashboard');
				}
				else {
					$this->session->set_flashdata('failInput','Time in Failed');
                    redirect('EmployeeScan/'.$this->input->post("EmpId"));
				}
            }
        }
        else{
            $this->session->set_flashdata('failInput',validation_errors());
            redirect('EmployeeScan/'.$this->input->post("EmpId"));
        }
    }
}
