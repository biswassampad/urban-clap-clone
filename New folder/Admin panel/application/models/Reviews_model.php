<?php
class Reviews_model extends CI_Model{
    protected $table_name;
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'reviews';
    }
	/*
	get list of reviews
	@return object of reviews
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
	get review by id
	@param id for review's id
	@return object of review
	*/
    function get_by_id($id){
        $q = $this->db->query("select  ".$this->table_name.".*, users.user_fullname, users.user_image from ".$this->table_name."
        inner join users on users.user_id = ".$this->table_name.".user_id where ".$this->table_name.".id = '".$id."'");
        return $q->row();
    }
	/*
	get review by category
	@param cat_id for category id
	@return object of reviews
	*/
    function get_by_cat_id($cat_id){
        $q = $this->db->query("select  ".$this->table_name.".*, users.user_fullname, users.user_image from  ".$this->table_name." 
        inner join users on users.user_id = ".$this->table_name.".user_id 
        left outer join appointment on appointment.id =  ".$this->table_name.".appointment_id
        left outer join pros_category_rel on pros_category_rel.pros_id = appointment.pros_id
        where pros_category_rel.cat_id = '".$cat_id."'
        ");
        return $q->result();
    }
	/*
	get review by pros
	@param pros_id for pros id
	@return object of reviews
	*/
    function get_by_pros_id($pros_id){
        $q = $this->db->query("select  ".$this->table_name.".*, users.user_fullname, users.user_image from  ".$this->table_name." 
        inner join users on users.user_id = ".$this->table_name.".user_id 
        left outer join appointment on appointment.id =  ".$this->table_name.".appointment_id
        where appointment.pros_id = '".$pros_id."'
        ");
        return $q->result();
    }
    
}
?>