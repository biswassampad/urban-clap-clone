<?php
class Pros_model extends CI_Model{
    protected $table_name;
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'pros';
    }
	/*
		get list of pros
		@param cat_id for category id, filter category wise
		@return object of pros data
	*/
    function get($parms=array()){
        $filter = "";
        $join = "";
        if(!empty($parms)){
            foreach($parms as $key=>$val){
                if($val != ""){
                    if($key == "cat_id"){
                        $join .= " left outer join pros_category_rel on pros_category_rel.pros_id = pros.id ";
                        $filter .= " and pros_category_rel.cat_id = '".$val."' ";
                    }   
                }
            }
        }  
        $q = $this->db->query("select DISTINCT pros.*,pros_services.pros_cats from ".$this->table_name." 
                left outer join (select pros_category_rel.pros_id, GROUP_CONCAT(categories.title) as pros_cats from pros_category_rel inner join categories on categories.id = pros_category_rel.cat_id group by pros_category_rel.pros_id ) as pros_services on pros_services.pros_id = pros.id 
        $join
        where 1 $filter" );
        return $q->result();
        
    }
	/* get single pros data
	@param id for pros id
	@return object of single pros data
	*/
    function get_by_id($id){
        $q = $this->db->query("select ".$this->table_name.".*,users.*,pros_services.pros_cats, users.user_email,users.user_password from ".$this->table_name."
        left outer join (select pros_category_rel.pros_id, GROUP_CONCAT(categories.title) as pros_cats from pros_category_rel inner join categories on categories.id = pros_category_rel.cat_id group by pros_category_rel.pros_id ) as pros_services on pros_services.pros_id = pros.id
        inner join users on users.user_id = ".$this->table_name.".id where ".$this->table_name.".id = '".$id."'");
        return $q->row();
    }
    /*
	get pros assigned service
	@param id for pros id
	@param status for filter status of service 
	@return object of services
	*/
     function get_pros_assigned($id,$status=""){
        $filter = "";
        if($status == "assigned"){
            $filter .= " and   appointment.status in (1,2) ";
        }else if($status == "completed"){
            $filter .= " and   appointment.status in (3) ";
        }
        $q = $this->db->query("select  appointment.*,
        tbl_service_count.service_count,tbl_service_count.total_service_amount,tbl_service_count.approx_time,
        address.delivery_fullname,address.delivery_mobilenumber,address.delivery_landmark,address.delivery_city,address.delivery_address,address.delivery_zipcode, TIMEDIFF(appointment.start_at,appointment.end_at) as taken_time from appointment
							  inner join address on address.id = appointment.address_id  
                              left outer join (select count(*) as service_count, sum(service_amount) as total_service_amount,SEC_TO_TIME( SUM( TIME_TO_SEC( service_time ) ) ) AS approx_time  ,appointment_id from appointment_services group by appointment_id) as tbl_service_count on tbl_service_count.appointment_id = appointment.id 
							  where appointment.pros_id = '".$id."' $filter"); 
        return $q->result();
    }
	
     function get_driver_details($driver_id){
         $q = $this->db->query("select review.*,driver.*,reviews.count,reviews.avg_rating from review
         inner join driver on driver.driver_id = review.driver_id
         left outer join (Select count(review_id) as count, avg(rating) as avg_rating, sum(rating) as total_rating, driver_id from review group by driver_id ) as reviews on reviews.driver_id = driver.driver_id 
        where driver.driver_id='".$driver_id."'");
        return $q->row();
    }
    public function get_curren_user_pros(){
		$user_id = _get_current_user_id($this);
		$q = $this->db->query("select * from ".$this->table_name." where id ='".$user_id."'");
		return $q->row();
	}
	/*
	get category related to pros 
	@param pros_id for pros id
	@return object of pros related categories
	*/
	public function get_pros_cat($pros_id)
	{
		$q = $this->db->query("select * from pros_category_rel where pros_id = '".$pros_id."'");
		return $q->result();
	}
    
}
?>