<?php
class Appointment_model extends CI_Model{
    protected $table_name;
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'appointment';
    } 
	
	/*
	used for appointment list
	@param appointment.user_id for used id
	@param appointment.pros_id for pros id
	@param assigned_pros_id for pros id
	@param completed_pros_id for pros id
	@param appointment_date for appointment date
	@return list of appointment
	*/
    function get($params=array()){
        $filter = "";
		if(!empty($params)){
            foreach($params as $key=>$val){
                if($key == "assigned_pros_id"){
                    $filter .= " and  ".$this->table_name.".status in (1,2) and  ".$this->table_name.".pros_id = '".$val."' ";
                }else if($key == "completed_pros_id"){
                    $filter .= " and  ".$this->table_name.".status in (3) and  ".$this->table_name.".pros_id = '".$val."' ";
                }else if($val != ""){
                    $filter .= " and ".$key." = '".$val."' ";
                }     
            }
        }
        $sql = "select  ".$this->table_name.".*,
        users.user_fullname,
        users.user_phone,
        users.user_email,
        address.delivery_address,address.delivery_mobilenumber,address.delivery_landmark,address.delivery_city,address.delivery_zipcode,
        pros.pros_name,
        tbl_service_count.service_count,tbl_service_count.total_service_amount,tbl_service_count.approx_time, 
        TIMEDIFF(appointment.start_at,appointment.end_at) as taken_time 
        , ifnull(reviews.avg_rating, 0) as avg_rating, ifnull(reviews.total_rating, 0) as total_rating, ifnull(reviews.count, 0) as review_count
        from ".$this->table_name."
							  inner join address on address.id = ".$this->table_name.".address_id
                               inner join users on users.user_id = ".$this->table_name.".user_id
                               left outer join pros on pros.id = ".$this->table_name.".pros_id
							  left outer join (select count(*) as service_count, sum(service_amount) as total_service_amount,SEC_TO_TIME( SUM( TIME_TO_SEC( service_time ) ) ) AS approx_time  ,appointment_id from appointment_services group by appointment_id) as tbl_service_count on tbl_service_count.appointment_id = appointment.id
                              left outer join (Select count(id) as count, avg(ratings) as avg_rating, sum(ratings) as total_rating, appointment_id from reviews group by appointment_id ) as reviews on reviews.appointment_id = appointment.id 
                              where 1 ".$filter."";
                             
        $q = $this->db->query($sql);
        return $q->result();
        
    }
    
	/*
	used for get appointment details
	@param id for appointment id
	@return appointment data 
	*/
    function get_details($id){
        $q = $this->db->query("select  ".$this->table_name.".*,
        users.user_fullname,
        users.user_phone,
        users.user_email,
        address.delivery_address,address.delivery_mobilenumber,address.delivery_landmark,address.delivery_city,address.delivery_fullname,address.delivery_zipcode,
        pros.pros_name,
        tbl_service_count.service_count,tbl_service_count.total_service_amount,tbl_service_count.approx_time, 
        TIMEDIFF(appointment.start_at,appointment.end_at) as taken_time
        , ifnull(reviews.avg_rating, 0) as avg_rating, ifnull(reviews.total_rating, 0) as total_rating, ifnull(reviews.count, 0) as review_count
        from ".$this->table_name."
							  inner join address on address.id = ".$this->table_name.".address_id
                               inner join users on users.user_id = ".$this->table_name.".user_id
                              left outer join pros on pros.id = ".$this->table_name.".pros_id
							  left outer join (select count(*) as service_count, sum(service_amount) as total_service_amount,SEC_TO_TIME( SUM( TIME_TO_SEC( service_time ) ) ) AS approx_time  ,appointment_id from appointment_services group by appointment_id) as tbl_service_count on tbl_service_count.appointment_id = appointment.id
                              left outer join (Select count(id) as count, avg(ratings) as avg_rating, sum(ratings) as total_rating, appointment_id from reviews group by appointment_id ) as reviews on reviews.appointment_id = appointment.id
                              where ".$this->table_name.".id = '".$id."'");
        return $q->row();
    }
    function get_services($id)
    {
        $q = $this->db->query("select services.service_title, services.service_price, services.service_discount, services.service_approxtime, services.service_icon, appointment_services.service_qty from appointment_services
                              inner join services on services.id = appointment_services.service_id
                              where appointment_services.appointment_id = '".$id."'");
        return $q->result();
    }
	
	/*
	get appointment items
	@param id for appointment id
	@return object of appointment item list
	*/
    function get_appointment_items($id){
        $q = $this->db->query("select appointment_services.*,services.service_title,services.service_price,services.service_approxtime from appointment_services 
                              inner join services on services.id = appointment_services.service_id 
                              where appointment_services.appointment_id = '".$id."'");
        return $q->result();
    }
    /*
	get appointment extra items
	@param id for appointment id
	@return object of extra items 
	*/
    function get_appointment_extra_items($id){
        $q = $this->db->query("select appointment_extra.*,".$this->table_name.".appointment_date,".$this->table_name.".start_time,".$this->table_name.".created_at, ".$this->table_name.".total_time from appointment_extra 
                              inner join ".$this->table_name." on ".$this->table_name.".id = appointment_extra.appointment_id 
                              where appointment_extra.appointment_id = '".$id."'");
        return $q->result();
    }
    /* 
	get pros list 
	@return object of pros list
	*/
    function get_pros(){
          $q = $this->db->query("select * from pros");
        return $q->result();
    }
    /* 
	get pros by category list 
	@return object of pros list
	*/
    function get_pros_by_cat($items){
		$catId=array(0);
			if(!empty($items))
			{
				foreach($items as $itm)
				{
					$service=$this->db->get_where('services',array('id'=>$itm->service_id))->row();
					$catId[]=$service->cat_id;
				}
			}
			$category=implode(",",$catId);
          $q = $this->db->query("select * from pros where id in (select pros_id from pros_category_rel where cat_id in ($category))");
        return $q->result();
    }
	
	/** 
	* check pros is free
	*/
	function check_pros_free($proId,$assignTime,$appId,$appDate)
	{
		$res="";
		//date('H:i:s',$assignTime);
		$appointments=$this->db->query("select * from appointment where id='$appId'")->row();
		if(!empty($appointments))
		{
				
				$servTotal=$this->db->query("select ifnull( SEC_TO_TIME( SUM( TIME_TO_SEC( service_time ) ) ), '00:00:00' ) AS total_time from appointment_services where service_id in (select id from services where cat_id in (select cat_id from pros_category_rel where pros_id='$proId')) and appointment_id='$appId'")->row();
				$total_time=$servTotal->total_time;
				$total_time_arr=explode(":",$total_time);
				$hours=$total_time_arr[0];
				$mins=$total_time_arr[1];
				$secs=$total_time_arr[2];
				$astartTime=$assignTime;
				$aendTime=date("h:i A",strtotime("+$hours hour +$mins minutes",strtotime($assignTime)));
				$astartDT=strtotime($appDate." ".$astartTime);
				$aendDT=strtotime($appDate." ".$aendTime);
		}
		
		
		$appointments=$this->db->query("select * from appointment where (status='".STATUS_ASSIGNED."' or status='".STATUS_STARTED."') and pros_id='$proId' and id!='$appId' and appointment_date='$appDate'")->result();
		if(!empty($appointments))
		{
			
			foreach($appointments as $app)
			{
				$startTime=$app->start_time;
				$startTime=date("h:i A",strtotime($startTime));
				$servTotal=$this->db->query("select ifnull( SEC_TO_TIME( SUM( TIME_TO_SEC( service_time ) ) ), '00:00:00' ) AS total_time from appointment_services where service_id in (select id from services where cat_id in (select cat_id from pros_category_rel where pros_id='$proId')) and appointment_id='{$app->id}'")->row();
				if(!empty($servTotal))
				{
					//print_r($astartTime);
					$total_time=$servTotal->total_time;
					//print_r($total_time);
					$total_time_arr=explode(":",$total_time);
					$hours=$total_time_arr[0];
					$mins=$total_time_arr[1];
					$secs=$total_time_arr[2];
					$endTime=date("h:i A",strtotime("+$hours hour +$mins minutes",strtotime($startTime)));
					//print_r($startTime);
					//print_r($endTime);exit;
					$startDT=strtotime($appDate." ".$startTime);
					$endDT=strtotime($appDate." ".$endTime);
					if($astartDT>=$startDT && $astartDT<$endDT)
					{
						$res.=($res!="")?"<br>":"";
						$res.="Time slot(".date("d-m-Y h:i A",$startDT)." to ".date("d-m-Y h:i A",$endDT).") already booked for another appointment";
					}
				}
			}
		}
		return $res;
		
	}
	
    function get_appointment_by_id($id){
            $q = $this->db->query("Select * from ".$this->table_name."  
            where id= '".$id."' limit 1");
            return $q->row(); 
      }
    function get_appointment_today($params=array()){
        $filter = "";
		if(!empty($params)){
            foreach($params as $key=>$val){
                if($val != "")
                    $filter .= " and ".$key." = '".$val."' ";
            }
        }
        $q = $this->db->query("select  ".$this->table_name.".*,users.user_fullname,users.user_phone,users.user_email,address.delivery_address,pros.pros_name from ".$this->table_name."
							  inner join address on address.id = ".$this->table_name.".address_id
                               inner join users on users.user_id = ".$this->table_name.".user_id
                               left outer join pros on pros.id = ".$this->table_name.".pros_id
                              inner join appointment_services on appointment_services.appointment_id = ".$this->table_name.".id
							  where 1 ".$filter." and (`appointment_date` > DATE_SUB(now(), INTERVAL 1 DAY))  ");
        return $q->result();
        
    }
	/*
	get no of records for pending, assigned and completed appointment
	@param status for status of appointment
	@return counts object 
	*/
    public function get_counts($params=array()){
        $filter = "";
		
		if(!empty($params)){
            foreach($params as $key=>$val){
                if($val != "" || $val=="0"){
                    $filter .= " and ".$key." = '".$val."' ";
                }     
            }
        }
        //print_r($filter);exit;
        $q = $this->db->query("select count(*) as counts from ".$this->table_name." where 1 ".$filter);
        return $q->row();
    }
	/*
		get total earning amount after completed 
		@param status for status of appointment
		@return net amount counter
	*/
    public function get_total_amount($params=array()){
        $filter = "";
		if(!empty($params)){
            foreach($params as $key=>$val){
                if($val != ""){
                    $filter .= " and ".$key." = '".$val."' ";
                }     
            }
        }
        
        $q = $this->db->query("select sum(net_amount) as counts from ".$this->table_name." where 1 ".$filter);
        $data=$q->row();
		return $data->counts;
    }
     public function get_attribute_count(){
        $typeid = _get_current_user_type_id($this);
        if($typeid == 2 || $typeid == "2"){
            $user_id = _get_current_user_id($this);
            $q = $this->db->query("Select count(*) as total_count from ".$this->table_name." where id in (select id from ".$this->table_name." where user_id = '".$user_id."')");
            $row = $q->row();
            return $row->total_count;
        }else{
            $q = $this->db->query("Select count(*) as total_count from ".$this->table_name." ");
            $row = $q->row();
            return $row->total_count;
        }
    }
	
	/*
		get extra charges by id
		@param id for appointment extra id
		@return object of extra charges
	*/
    function get_extra_by_id($id){
        $q = $this->db->query("select  appointment_extra.*, ".$this->table_name.".appointment_date from appointment_extra
        inner join ".$this->table_name." on ".$this->table_name.".id = appointment_extra.appointment_id where appointment_extra.appo_extra_id = '".$id."'");
        return $q->row();
    }
    /*
	get extra service by id
	@param appointment_services_id for appointment service id
	@return appointment service object
	*/
    public function get_by_id($appointment_services_id)
	{
	   $q = $this->db->query("Select * from appointment_services  
            where appointment_services_id= '".$appointment_services_id."' limit 1");
            return $q->row(); 
	}
    /*** End Appointment_services Code  ***/
    
	/*
	get extra service by id
	@param appo_extra_id for extra service id
	@return object of extra service 
	*/
    public function get_by_extra_id($appo_extra_id)
	{
	   $q = $this->db->query("Select * from appointment_extra  
            where appo_extra_id= '".$appo_extra_id."' limit 1");
            return $q->row(); 
	}
	
	/*
	update appointment amount
	@param apmId for appointment id to update service and extra service data
	@return null
	*/
	public function update_appointment_amount($apmId)
	{
		$apmExe=$this->db->get_where('appointment',array('id'=>$apmId));
		$apmData=$apmExe->row();
		if(!empty($apmData))
		{
			
			/* appointment services */
				$asvExe=$this->db->query("select sum(service_qty*service_amount) as total_amount,SEC_TO_TIME( SUM( TIME_TO_SEC(service_time))) as total_time,sum(service_discount*service_qty) as total_discount from appointment_services join services on services.id=appointment_services.service_id where appointment_id='$apmId'");
				$asvData=$asvExe->row();
				$serviceTotal=(!empty($asvData))?$asvData->total_amount:0;
				$serviceTotalTime=(!empty($asvData))?$asvData->total_time:"00:00:00";
				$serviceTotalDiscount=(!empty($asvData))?$asvData->total_discount:0;
			/* appointment services */
			
			
			/* appointment extra services */
				$aesExe=$this->db->query("select sum(qty*charge) as total_amount from appointment_extra where appointment_id='$apmId'");
				$aesData=$aesExe->row();
				$extraTotal=(!empty($aesData))?$aesData->total_amount:0;
			/* appointment extra services */
			
				$payment_amount=$serviceTotal;
				$discount=$apmData->discount;
				//$discount=$serviceTotalDiscount;
				$total_amount=$payment_amount-$discount;
				$extra_charges=$extraTotal;
				$net_amount=$total_amount+$extra_charges;
				
				$updData=array();
				$updData['payment_amount']=$payment_amount;
				$updData['discount']=$discount;
				$updData['total_amount']=$total_amount;
				$updData['extra_charges']=$extra_charges;
				$updData['net_amount']=$net_amount;
				$updData['total_time']=$serviceTotalTime;
				$this->db->update('appointment',$updData,array('id'=>$apmId));
				
		}
	}
	
}
?>