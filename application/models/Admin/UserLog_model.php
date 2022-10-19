<?php defined('BASEPATH') OR exit('No direct script access allowed');

class UserLog_model extends CI_Model {
    private $table = "user_log";
    public function __construct() {
        parent::__construct();

        //create table if it doesn't exist
        if (!$this->db->table_exists($this->table) )
        {
            $this->load->dbforge();
            // define table fields
            $fields = array(
                'logId' => array(
                'type' => 'INTEGER',
                'constraint' =>20,
                'auto_increment' => TRUE
                ),
                'userId' => array(
                'type' => 'VARCHAR',
                'constraint' =>20,
                ),
                'action' => array(
                'type' => 'VARCHAR',
                'constraint' =>50,
                ),
                'datetime' => array(
                'type' => 'VARCHAR',
                'constraint' =>50,
                ),
                'note' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
                )
            ); 
            $this->dbforge->add_field($fields);
            // define primary key
            $this->dbforge->add_key('logId', TRUE);
            // create table
            $this->dbforge->create_table($this->table);
        }
    }
    // save user to database
    public function addLog($action = '',$id = '', $status = ''){

        $data = array(
            'userId' => $id,
            'action' => $action,
            'datetime' => date('Y-m-d h:i:s A'),
            'note' => $status ? 'Success' : 'Failed',
        );
      
        return $this->db->insert($this->table,$data);
    }
}