<?php
class Leave_model extends CI_Model{
    protected $table_name;
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'leaves';
    }
    function get_leave(){        
        $q = $this->db->query("select * from ".$this->table_name);
        return $q->result();
        
    }
    function get_leave_by_id($leave_id){
            $q = $this->db->query("Select * from ".$this->table_name."  
            where leave_id= '".$leave_id."' limit 1");
            return $q->row(); 
      }
       
    
}
?>