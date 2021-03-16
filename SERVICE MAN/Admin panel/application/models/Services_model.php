<?php
class Services_model extends CI_Model{
    protected $table_name;
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'services';
    }
	/*
	get list of services
	@param cat_id for category's id
	@return object of categories list
	*/
    function get($params=array()){
        $filter = "";
		if(!empty($params)){
            foreach($params as $key=>$val){
                if($val != "")
                    $filter .= " and ".$key." = '".$val."' ";
            }
        }
        $q = $this->db->query("select ".$this->table_name.".*,categories.title from ".$this->table_name."
							  inner join categories on categories.id = ".$this->table_name.".cat_id
							  where 1 ".$filter."");
        return $q->result();
        
    }
	/*
	get service by id
	@param id for service id
	@return object of service	
	*/
    function get_by_id($id){
        $q = $this->db->query("select ".$this->table_name.".*,categories.title from ".$this->table_name."
        inner join categories on categories.id = ".$this->table_name.".cat_id where ".$this->table_name.".id = '".$id."'");
        return $q->row();
    }
}
?>