<?php
class Business_model extends CI_Model{
        function get_businesses(){
            $q = $this->db->query("Select business.*, users.user_fullname from business inner join users on users.user_id = business.user_id");
            return $q->result();
    }
    
    public function get_businesses_by_id($id){
        $q = $this->db->query("select * from business where  bus_id = '".$id."' limit 1");
        return $q->row();
    }
    public function get_businesses_details(){
        $q = $this->db->query("select * from business  limit 1");
        return $q->row();
    }
    public function get_businesses_by_userid($id){
        $q = $this->db->query("select * from business where  user_id = '".$id."' limit 1");
        return $q->row();
    }
       /* business service */ 
          public function get_business_service(){
            $q = $this->db->query("select * from `business_services`");
            return $q->result();
        }
              public function get_business_service_by_id($id){
        $q = $this->db->query("select * from business_services where  id = '".$id."' limit 1");
        return $q->row();
    }  
    /* business review */
          public function get_business_review(){
            $q = $this->db->query("select business_reviews.*, users.user_fullname, users.user_image from `business_reviews` inner join users on users.user_id = business_reviews.user_id  order by on_date DESC");
            return $q->result();
        }
        public function get_business_review_count(){
            $q = $this->db->query("select count(*) as total_reviews from `business_reviews` limit 1");
            return $q->row();
        }
        public function get_business_review_by_id($id){
            $q = $this->db->query("select  business_reviews.*, users.user_fullname, users.user_image  from business_reviews inner join users on users.user_id = business_reviews.user_id  where  business_reviews.id = '".$id."' limit 1");
            return $q->row();
        }  
    
        /* business photo */
        public function get_business_photo(){
            $q = $this->db->query("select * from `business_photo`");
            return $q->result();
        }
        public function get_business_photo_by_id($id){
            $q = $this->db->query("select * from business_photo where  id = '".$id."' limit 1");
            return $q->row();
        } 
     /* business appointment */
        public function get_business_appointment_group($from_date="", $to_date=""){
            $filter = "";
            if($from_date != ""){
                $filter .=" and appointment_date >= '".$from_date."' ";
            }
            if($to_date != ""){
                $filter .=" and appointment_date <= '".$to_date."' ";
            }
            
            $join = "";
            
            $sql = "Select count(*) as count_app,business_appointment.appointment_date  from business_appointment $join where 1 ".$filter." group by appointment_date ";
            
            $q = $this->db->query($sql);
            
            return $q->result();
        }
     public function get_user_appointment($user_id){
        $sql = "Select business_appointment.* 
        from business_appointment 
        where business_appointment.user_id = '".$user_id."' 
        order by business_appointment.appointment_date DESC,business_appointment.start_time DESC ";
        $q = $this->db->query($sql);
        return $q->result();
        
     }
     public function get_business_appointment( $fdate="", $tdate = ""){
        $filter = "";
        
        $join = "";
        
        if($fdate != "")
        {
            $filter.=" and business_appointment.appointment_date >= '".$fdate."'" ;
        }
        if($tdate != "")
        {
            $filter.=" and business_appointment.appointment_date <= '".$tdate."'" ;
        }
         
         $sql = "select business_appointment.*, users.user_fullname, ifnull(bus_app_service.taken_time, '00:00:00') as taken_time from `business_appointment` 
            inner join users on users.user_id = business_appointment.user_id
            left outer join (Select SEC_TO_TIME( SUM( TIME_TO_SEC( business_services.business_approxtime * business_appointment_services.service_qty ) ) ) as taken_time, `business_appointment_services`.`busness_appointment_id` from `business_appointment_services`
            inner join `business_services` on `business_services`.`id` = `business_appointment_services`.`busness_service_id` group by `business_appointment_services`.`busness_appointment_id` ) as bus_app_service  on bus_app_service.`busness_appointment_id` = `business_appointment`.`id`
            ".$join." 
            WHERE 1 ".$filter;
           
            $q = $this->db->query($sql);
            
            return $q->result();
     }
     public function get_business_appointment_by_id($id){
        $q = $this->db->query("select * from business_appointment where  id = '".$id."' limit 1");
        return $q->row();
    }
    public function get_business_appointment_temp_by_id($id){
        $q = $this->db->query("select * from business_appointment_temp where  id = '".$id."' limit 1");
        return $q->row();
    }
     public function get_business_appointment_by_user_id($user_id,$id){
        $q = $this->db->query("select * from business_appointment where  id = '".$id."' and user_id = '".$user_id."' limit 1");
        return $q->row();
    }
        
    public function get_business_appointment_count(){
        
            $q = $this->db->query("Select count(*) as total_count from business_appointment ");
            $row = $q->row();
            return $row->total_count;
    } 
    /* business appointment service */
    public function get_business_appointment_service($id){
            $q = $this->db->query("select business_appointment_services.*, business_services.business_approxtime, business_services.service_title,  business_services.service_price, business_services.service_discount from `business_appointment_services` inner join business_services on business_services.id = business_appointment_services.busness_service_id WHERE business_appointment_services.busness_appointment_id =".$id);
            return $q->result();
    }
    public function get_business_appointment_service_temp($id){
            $q = $this->db->query("select business_appointment_services_temp.*, business_services.business_approxtime, business_services.service_title,  business_services.service_price, business_services.service_discount from `business_appointment_services_temp` inner join business_services on business_services.id = business_appointment_services_temp.busness_service_id WHERE business_appointment_services_temp.busness_appointment_id =".$id);
            return $q->result();
        }
          
    public function get_business_appointment_service_by_id($id){
        $q = $this->db->query("select * from business_appointment where  id = '".$id."' limit 1");
        return $q->row();
    }
    public function get_business_schedule(){
        $q = $this->db->query("select * from business_appointment_schedule limit 1");
        return $q->row();
    }
    public function get_business_reviews(){
        $q = $this->db->query("Select business_reviews.*,users.user_fullname, users.user_image from business_reviews 
        inner join users on users.user_id = business_reviews.user_id 
        order by business_reviews.on_date DESC");
        return $q->result();
    }
    public function get_business_reviews_by_id($id){
        $q = $this->db->query("Select business_reviews.*,users.user_fullname, users.user_image from business_reviews 
        inner join users on users.user_id = business_reviews.user_id 
        where business_reviews.id = '".$id."' order by business_reviews.on_date DESC");
        return $q->row();
    }
    
    /*----- Time Slot for Appointment -----*/
    public function get_time_slot($date,$bus_id=""){
        $time_slots_date_array = array();
        $time_slots_array = array();
        $result_app = $this->db->query("Select * from business_appointment where appointment_date = '".$date."' ");
        $appointment = $result_app->result();
        
        $result = $this->db->query("Select * from business_appointment_schedule  limit 1");
        $schedule = $result->row();
        
        $allow_days =  explode(',',$schedule->working_days);
        
        $c_day = strtolower(date('D', strtotime($date)));
        if(in_array( $c_day , $allow_days )){
        
                $time_slots_morning_array =array();
                $start_time_morning =  $schedule->morning_time_start ;
                $next_time_morning; 
                do{
                    $next_time_morning = strtotime("+".$schedule->morning_tokens." minutes", strtotime( $start_time_morning ));
                    $time = date("H:i:s",$next_time_morning); 
                    
                    $start_time_morning = $time;
                    $is_booked = false;
                    foreach($appointment as $app){
                        if(strtotime($app->start_time) == $next_time_morning ){
                            $is_booked = true;
                        }
                    }
                    if($date == date("Y-m-d") && strtotime($time) <= strtotime(date("H:i:s"))){
                        $is_booked = true;
                    }
                    $time_slots_morning_array[] = array("slot"=>$time, "is_booked"=>$is_booked, "time_token"=>1);
                }while($next_time_morning < strtotime($schedule->morning_time_end));
                $time_slots_array["morning"] = $time_slots_morning_array;
                
                $time_slots_afternoon_array = array();
                $start_time_afternoon =  $schedule->afternoon_time_start ;
                $next_time_afternoon = ""; 
                do{
                    $next_time_afternoon = strtotime("+".$schedule->afternoon_tokens." minutes", strtotime( $start_time_afternoon ));
                    $time = date("H:i:s",$next_time_afternoon); 
                    
                    $start_time_afternoon = $time;
                    $is_booked = false;
                    foreach($appointment as $app){
                        if(strtotime(date("H:i:s",strtotime($app->start_time))) == strtotime($time) ){
                            $is_booked = true;
                        }
                    }
                    if($date == date("Y-m-d") && strtotime($time) <= strtotime(date("H:i:s"))){
                        $is_booked = true;
                    }
                    $time_slots_afternoon_array[] = array("slot"=>$time, "is_booked"=>$is_booked, "time_token"=>2);
                }while($next_time_afternoon < strtotime($schedule->afternoon_time_end));
                
                $time_slots_array["afternoon"] = $time_slots_afternoon_array;
                
                
                $time_slots_evening_array = array();
                $start_time_evening =  $schedule->evening_time_start ;
                $next_time_evening; 
                do{
                    $next_time_evening = strtotime("+".$schedule->evening_tokens." minutes", strtotime( $start_time_evening ));
                    $time = date("H:i:s",$next_time_evening); 
                    
                    $start_time_evening = $time;
                    $is_booked = false;
                    foreach($appointment as $app){
                        if(strtotime($app->start_time) == $next_time_evening ){
                            $is_booked = true;
                        }
                    }
                    if($date == date("Y-m-d") && strtotime($time) <= strtotime(date("H:i:s"))){
                        $is_booked = true;
                    }
                    $time_slots_evening_array[] = array("slot"=>$time, "is_booked"=>$is_booked, "time_token"=>3);
                }while($next_time_evening < strtotime($schedule->evening_time_end));
                $time_slots_array["evening"] = $time_slots_evening_array;
                
                $time_slots_date_array[$date] = $time_slots_array;
                
                //$date =  date("Y-m-d",strtotime("+1 Day" , strtotime( $date )));
                
            }
        return $time_slots_array;
    }
    /*----- End time slot for appoitnment -----*/
    
    function count_chats(){
        $result_app = $this->db->query("Select count(*) as total_chats from chat_room_message limit 1");
        return $result_app->row();
        
    }
    
    function get_business_appointment_total($app_id){
        $q = $this->db->query("Select sum( (business_services.service_price - (business_services.service_price * business_services.service_discount / 100 ) * business_appointment_services.service_qty ) ) as total_amount from business_appointment_services 
                        inner join business_services on business_appointment_services.busness_service_id  = business_services.id 
                        where business_appointment_services.busness_appointment_id = '".$app_id."'");
                        return $q->row();
    }
    function get_business_appointment_total_temp($app_id){
        $q = $this->db->query("Select ifnull( sum( (business_services.service_price - (business_services.service_price * business_services.service_discount / 100 ) * business_appointment_services_temp.service_qty ) ) , 0) as total_amount from business_appointment_services_temp 
                        inner join business_services on business_appointment_services_temp.busness_service_id  = business_services.id 
                        where business_appointment_services_temp.busness_appointment_id = '".$app_id."'");
                        return $q->row();
    }
}
?>