<?php
class Banners_model extends CI_Model{
    protected $table_name;
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'banners';
    }
	
	/*
	get list of banners
	@return object of banners
	*/
    function get_categories(){
        $this->db->select("banners.*,categories.title as cat_title");
        $this->db->order_by("banners.id desc");
        $this->db->join("categories","categories.id = banners.type_value and banners.type = 'category'","left");
        $q = $this->db->get($this->table_name);

        return $q->result();
        
    }
    /*
	get banners by id
	@param id for banners's id
	@return object of banners
	*/
    function get_by_id($id){
        $q = $this->db->query("select * from ".$this->table_name." where id = '".$id."'");
        return $q->row();
    }
    
}
?>