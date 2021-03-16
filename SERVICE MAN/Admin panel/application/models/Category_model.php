<?php
class Category_model extends CI_Model{
    protected $table_name;
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'categories';
    }
	
	/*
	get list of categories
	@return object of categories
	*/
    function get_categories(){
        
        $q = $this->db->query("select DISTINCT ".$this->table_name.".*, ifnull(reviews.avg_rating, 0) as avg_rating, ifnull(reviews.total_rating, 0) as total_rating, ifnull(reviews.count, 0) as review_count from ".$this->table_name."
        left outer join pros_category_rel on pros_category_rel.cat_id = ".$this->table_name.".id
        left outer join appointment on appointment.pros_id =  pros_category_rel.pros_id
        left outer join (Select count(id) as count, avg(ratings) as avg_rating, sum(ratings) as total_rating, appointment_id from reviews group by appointment_id ) as reviews on reviews.appointment_id = appointment.id
        group by ".$this->table_name.".id
        ");
        //
        return $q->result();
        
    }
    /*
	get category by id
	@param id for category's id
	@return object of category
	*/
    function get_category_by_id($id){
        $q = $this->db->query("select * from ".$this->table_name." where id = '".$id."'");
        return $q->row();
    }
    
}
?>