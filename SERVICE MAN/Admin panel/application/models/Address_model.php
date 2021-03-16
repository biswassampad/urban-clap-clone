<?php
class Address_model extends CI_Model{
    protected $table_name;
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'address';
    }
	
	/*
	get list of address object
	@param id for address's id
	@parmas user_id when id is null user's id will be useful for get address list
	@return object of address data
	*/
    function get($params = array()){
        $filter = "";
        if(!empty($params)){
            foreach($params as $key=>$val){
                if($val != "")
                    $filter .= " and ".$key." = '".$val."' ";
            }
        }
        $q = $this->db->query("select ".$this->table_name.".*, users.user_fullname, users.user_image from ".$this->table_name." 
        inner join users on users.user_id = ".$this->table_name.".user_id where 1 ".$filter);
        return $q->result();
        
    }
	/*
	get address data by id
	@param id for address id
	@return object of address data
	*/
    function get_by_id($id){
        $q = $this->db->query("select  ".$this->table_name.".*, users.user_fullname, users.user_image from ".$this->table_name."
        inner join users on users.user_id = ".$this->table_name.".user_id where ".$this->table_name.".id = '".$id."'");
        return $q->row();
    }
    
}
?>