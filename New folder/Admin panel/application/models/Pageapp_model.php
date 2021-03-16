<?php
class Pageapp_model extends CI_Model{
    protected $table_name;
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'pageapp';
    }
    function get_pageapp(){        
        $q = $this->db->query("select * from ".$this->table_name);
        return $q->result();
        
    }
    function get_page_by_id($id){
            $q = $this->db->query("Select * from ".$this->table_name."  
            where id= '".$id."' limit 1");
            return $q->row(); 
      }
}
?>