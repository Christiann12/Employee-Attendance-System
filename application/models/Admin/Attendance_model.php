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
                'type' => 'VARCHAR',
                'constraint' =>20,
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
                )
            ); 
            $this->dbforge->add_field($fields);
            // define primary key
            $this->dbforge->add_key('attendanceId', TRUE);
            // create table
            $this->dbforge->create_table($this->table);
        }
    }

    public function getTableData(){
        return $this->db->select("attendance.*,employee.fname,employee.lname")->from($this->table)->join('employee', 'attendance.empId = employee.empId', 'left')->get()->result();
    }
}