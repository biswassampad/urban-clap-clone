<?php
class Offer_model extends CI_Model{
    protected $table_name;
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'offer';
    }
    function get_offer($params = array()){
        $filter = "";
        if(!empty($params)){
            foreach($params as $key=>$val){
                $filter .= " and ".$key." = '".trim($val)."' ";
            }
        }
        $q = $this->db->query("select * from ".$this->table_name." where 1 ".$filter);
        return $q->result();
        
    }
    function get_offer_by_id($offer_id){
            $q = $this->db->query("Select * from offer  
            where offer_id= '".$offer_id."' limit 1");
            return $q->row(); 
      }
	function check_is_unique_code($id,$code)
	{
		$rows=$this->db->get_where("offer",array("offer_coupon"=>$code,"offer_id!="=>$id))->num_rows();
		return ($rows>0)?0:1;
	}	
    
}
?>