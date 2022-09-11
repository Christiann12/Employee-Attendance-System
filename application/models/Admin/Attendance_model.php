<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Attendance_model extends CI_Model {
    private $table = "attendance";
    public function __construct() {
        parent::__construct();

        //create table if it doesn't exist
        if (!$this->db->table_exists($this->table) )
        {
            $this->load->dbforge();
            // define table fields
            $fields = array(
                'attendanceId' => array(
                'type' => 'INT',
                'constraint' =>20,
                'auto_increment' => TRUE
                ),
                'empId' => array(
                'type' => 'VARCHAR',
                'constraint' =>20,
                ),
                'imagepath' => array(
                'type' => 'VARCHAR',
                'constraint' =>50,
                'default' => 'unknown'
                ),
                'hours' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
                'default' => 'unknown'
                ),
                'late' => array(
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => 'unknown'
                ),
                'timein' => array(
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => 'timein'
                ),
                'timeout' => array(
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => 'timeout'
                ),
                'date' => array(
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => 'date'
                ),
                'pictureUrl' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'default' => 'empty'
                ),
            ); 
            $this->dbforge->add_field($fields);
            // define primary key
            $this->dbforge->add_key('attendanceId', TRUE);
            // create table
            $this->dbforge->create_table($this->table);
        }
    }
    public function create($data = []){
        return $this->db->insert($this->table,$data);
    }
    public function getTableData(){
        return $this->db->select("attendance.*,employee.fname,employee.lname")->from($this->table)->join('employee', 'attendance.empId = employee.empId', 'left')->get()->result();
    }
    public function getData($id = ''){
        return $this->db->select("*")->from($this->table)->where('empId',$id)->where('date',date("Y-m-d"))->where('timein !=','timein')->get()->row();
    }
    public function updateRecord($data = []){
        return $this->db->where('empId',$data['empId'])->update($this->table,$data); 
    }
    public function getTableDataByEmployee(){
        return $this->db->select("*")->from($this->table)->where('empId',$this->session->userdata('employeeId'))->get()->result();
    }
}