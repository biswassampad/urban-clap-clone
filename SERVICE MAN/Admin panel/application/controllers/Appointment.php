<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Appointment extends MY_Controller {
    protected $controller;
    protected $table_name;
    public function __construct()
    {
                parent::__construct();
                $this->controller = $this->router->fetch_class();
                $this->table_name = "appointment";
                
                if(_is_user_login($this)){
                        
                }else{
                    redirect("login");
                    exit();
                }
    }
    
	/*
	appointment page
	@param status for appointment status pending,assigned,started,completed or canceled
	@return list of appointment
	*/
    public function index($status=""){
        
        $user_type_id = _get_current_user_type_id($this);
        $user_id = _get_current_user_id($this);
        $params = array();
        if($user_type_id == USER_PROS){
            $params["appointment.pros_id"] = $user_id;
        }
       
        if($status!="")
        {
            $params["appointment.status"] = $status;
        }
        $data["data"] = $this->appointment_model->get($params);
        $this->load->view($this->controller."/list",$data);
    }
	/*
	appointment view page
	@param status for appointment status pending,assigned,started,completed or canceled
	@return index page
	*/
    public function view($status){
        $this->index($status);
    }
		
    /*
	appointment detail page
	@param id for appointment id
	@return appointment detail page
	*/
	public function details($id){
    $order = $this->appointment_model->get_details($id);
         $post = $this->input->post();
                $this->load->library('form_validation');
                
				$this->form_validation->set_rules('pros_id', 'Pros Name', 'trim|required');
                
                if ($this->form_validation->run() == FALSE)
        		{
     			    if($this->form_validation->error_string()!="")
                    {
                           $this->message_model->error($this->form_validation->error_string(),false);
                    }
        		}
        		else
        		{
							$addpros = array(
                            "pros_id"=>$post["pros_id"],
                            "status"=>"1",
                            "visit_at"=>date(DB_TIME_FORMATE,strtotime($post["visit_at"])),
                            ); 
                            if(!empty($order)){    
                                $user = $this->users_model->get_user_by_id($post["pros_id"],1);
								$assignTime=$post["visit_at"];
								$assignTimeS=strtotime($assignTime);
								$working_hour_start=$user->working_hour_start;
								$working_hour_startS=strtotime($working_hour_start);
								$working_hour_end=$user->working_hour_end;
								$working_hour_endS=strtotime($working_hour_end);
								$isValid=0;
								
                                $errorMsg = $this->appointment_model->check_pros_free($post["pros_id"],$assignTime,$id,$order->appointment_date);
								//echo $errorMsg;exit;
								if($errorMsg!="")
								{
									$this->message_model->error($errorMsg);
								}
								else
								{
									
									if($assignTimeS>=$working_hour_startS && $assignTimeS<$working_hour_endS)
									{
										$isValid=1;
									}
									//echo $isValid;exit;
									if($isValid==1)
									{
										/** Send notification to Pros on assign Order */
										$message["message"] = $this->message_model->assign_service_to_pros($order->appointment_date, $order->start_time,$order->user_fullname);
										$message["created_at"] = date(COMMON_DATE_FORMATE);     
										$gcm = new GCM();   
										if($user->user_gcm_code != ""){
											$result = $gcm->send_notification(array($user->user_gcm_code),$message ,"android");
										}
										if($user->user_ios_token != ""){
											$result = $gcm->send_notification(array($user->user_ios_token),$message ,"ios");
										}
										/** END Send notification to Pros on assign Order */
										
										/** Email Send Code **/
										
										/*$this->load->library('email');
										$this->email->from(array($order->user_email,$order->user_fullname,$order->user_phone));
										$this->email->to(array($user->post["pros_id"]));
										$this->email->subject('Email Test');
										$this->email->message('Testing the email class.');
										$this->email->send();
										*/
										
										/** End Email Send **/
										
										/* send assigned mail */
										if($user->user_email != ""){
										$list = array($user->user_email);	
										$sendMail=$this->emailsender->send_assign_email($id,$list);
										}
										/* send assigned mail */
										$this->common_model->data_update($this->table_name,$addpros,array("id"=>$id));
										$this->message_model->action_mesage("update",$this->controller,false);
									}
									else											
									{
										$this->message_model->error("You can assign to that service man only at $working_hour_start to $working_hour_end");
									}	
									
								}	
                             }
                        
                        
               	} 
                
         $data["details"] = $this->appointment_model->get_details($id);
         $data["appointment_items"] = $this->appointment_model->get_appointment_items($id); 
         $data["appointment_extra_item"] = $this->appointment_model->get_appointment_extra_items($id); 
         $data["pros_list"] = $this->appointment_model->get_pros_by_cat($data["appointment_items"]);  
         $data["services"] = $this->services_model->get();    
        $this->load->view($this->controller."/details",$data);
    }
	/*
	delete appointment
	@param id for appointment's id
	@return response for true/false
	@return message for success message 
	@return error for error message	
	*/
    public function delete($id){
        $cat = $this->appointment_model->get_appointment_by_id($id);
        if(!empty($cat)){
            $this->common_model->data_remove($this->table_name,array("id"=>$cat->id),true);
            $data[RESPONCE] = true;
            $data[MESSAGE] = $this->message_model->action_mesage("delete",$this->controller,true);
            echo json_encode($data);
        }else{
			
            $data[RESPONCE] = false;
            $data[ERROR] = $this->controller." not available";
            echo json_encode($data);
        }
    }
	
     public function add(){
        if(_is_user_login($this)){
                
                $this->action();
                $data["field"] = $this->input->post();
                $this->load->view($this->controller."/add",$data);
          }
                //echo json_encode($responce);
    }
    private function action(){
        $post = $this->input->post();
                $this->load->library('form_validation'); 
                 $this->form_validation->set_rules('appointment_date', 'Date', 'trim|required');
                $this->form_validation->set_rules('start_time', 'Start Time', 'trim|required'); 
                $responce = array();
                if ($this->form_validation->run() == FALSE)
        		{
     			    if($this->form_validation->error_string()!="")
                    {
                            $responce[RESPONCE] = false;
                            $responce[ERROR] = $this->message_model->error($this->form_validation->error_string(),true);
                    }
        		}
        		else
        		{
        		    $addcat = array(
                            "appointment_date"=>$post["appointment_date"],
                            "start_time"=>$post["start_time"],
                            "time_token"=>$post["time_token"],
                            "promo_code" =>$post["promo_code"],
                            "payment_amount"=>$post["payment_amount"],
                            "total_time"=>$post["total_time"],
                            "status" =>$post["status"]
                            );

                    $responce[RESPONCE] = true;
                    if(!empty($post["id"])){
                        $this->common_model->data_update($this->table_name,$addcat,array("id"=>$post["id"]));
                        $this->message_model->action_mesage("update",$this->controller,false);
                        redirect($this->controller);
                    }else{
                        $cat_id = $this->common_model->data_insert($this->table_name,$addcat);    
                         
                        $this->message_model->action_mesage("add",$this->controller,false);
                        redirect($this->controller);
                    }        
                    
               	}
    }
    public function edit($id){
        if(_is_user_login($this)){
                
                $this->action();
                $data["field"] = $this->appointment_model->get_appointment_by_id($id);
                $this->load->view($this->controller."/add",$data);
          }
                //echo json_encode($responce);
    }
    public function service_add()
		{
		  $q = $this->db->query("Select SEC_TO_TIME( SUM( TIME_TO_SEC( service_approxtime) * ". $this->input->post('service_qty')." ) ) AS totaltime, service_price *  ". $this->input->post('service_qty')." as total_amount,service_discount,service_price,service_approxtime from `services` where id = '".($this->input->post("service_id"))."'  Limit 1");
          $ser = $q->row();
		
			$data = array(
            'appointment_id' => $this->input->post('appointment_id'),
					'service_id' => $this->input->post('service_id'),
					'service_qty' => $this->input->post('service_qty'),
                    'service_time' => $ser->totaltime,
                    'service_amount' => $ser->total_amount
				);
             $this->common_model->data_insert("appointment_services",$data);
			 
			 /*get appointment and update amounts*/
				$apmId=(!empty($data['appointment_id']))?$data['appointment_id']:0;	
				$this->appointment_model->update_appointment_amount($apmId);
			/*get appointment and update amounts*/
			
			 
			echo json_encode(array("status" => TRUE));
		}
		
		/* 
		get service by id 
		@param appointment_services_id for service id
		@return json of appointment service 
		*/
        public function ajax_edit($appointment_services_id)
		{
			$data = $this->appointment_model->get_by_id($appointment_services_id);
			echo json_encode($data);
		}
		/*
		appointment service update
		@param service_id for service id
		@param service_qty for service quantity
		@return status json
		*/
        public function services_update()
		{
		   $q = $this->db->query("Select SEC_TO_TIME( SUM( TIME_TO_SEC( service_approxtime) * ". $this->input->post('service_qty')." ) ) AS totaltime, service_price *  ". $this->input->post('service_qty')." as total_amount,service_discount,service_price,service_approxtime from `services` where id = '".($this->input->post("service_id"))."'  Limit 1");
			  $ser = $q->row();
			$data = array(
			'service_id' => $this->input->post('service_id'),
					'service_qty' => $this->input->post('service_qty'),
					'service_time' => $ser->totaltime,
						//'service_amount' => $ser->total_amount
						'service_amount' => $ser->service_price-$ser->service_discount
				);
				
				
				
				
				$this->common_model->data_update("appointment_services",$data,array('appointment_services_id' => $this->input->post("appointment_services_id")));
				
				/*get appointment and update amounts*/
					$amtExe=$this->db->get_where('appointment_services',array('appointment_services_id'=>$this->input->post("appointment_services_id")));
					$amtData=$amtExe->row();
					$apmId=(!empty($amtData))?$amtData->appointment_id:0;	
					$this->appointment_model->update_appointment_amount($apmId);
				/*get appointment and update amounts*/
				
			echo json_encode(array("status" => TRUE));
		}
	/* 
	service delete
	@param appointment_services_id for appointment service id
	@return status json
	*/	
    public function services_delete($appointment_services_id)
	{
		/*get appointment and update amounts*/
			$amtExe=$this->db->get_where('appointment_services',array('appointment_services_id'=>$appointment_services_id));
			$amtData=$amtExe->row();
			$apmId=(!empty($amtData))?$amtData->appointment_id:0;	
		/*get appointment and update amounts*/
		
		$this->db->where('appointment_services_id', $appointment_services_id);
		$this->db->delete("appointment_services");
		
		$this->appointment_model->update_appointment_amount($apmId);
		echo json_encode(array("status" => TRUE));
	}
	
	/*
	add extra service item 
	@param appointment_id for appointment's id
	@param title for title of extra service
	@param charge for charges of extra service
	@param qty for quantity of extra service
	@return status json
	*/
    public function extra_add()
		{
			$data = array(
            'appointment_id' => $this->input->post('appointment_id'),
					'title' => $this->input->post('title'),
					'charge' => $this->input->post('charge'),
                    'qty' => $this->input->post('qty'),
				);
              $this->common_model->data_insert("appointment_extra",$data);
			  
			  /*get appointment and update amounts*/
				$apmId=(!empty($data))?$data['appointment_id']:0;	
				$this->appointment_model->update_appointment_amount($apmId);
			/*get appointment and update amounts*/
			  
			echo json_encode(array("status" => TRUE));
		}
	/*
	update extra service 
	@param title for title of service
	@param charge for charges of service
	@param qty for quantity of service
	@return status json
	*/	
    public function extra_update()
	{
		$data = array(
        'title' => $this->input->post('title'),
					'charge' => $this->input->post('charge'),
                    'qty' => $this->input->post('qty'),
			);
            $this->common_model->data_update("appointment_extra",$data,array('appo_extra_id' => $this->input->post("appo_extra_id")));
			
			/*get appointment and update amounts*/
				$amtExe=$this->db->get_where('appointment_extra',array('appo_extra_id'=>$this->input->post("appo_extra_id")));
				$amtData=$amtExe->row();
				$apmId=(!empty($amtData))?$amtData->appointment_id:0;	
				$this->appointment_model->update_appointment_amount($apmId);
			/*get appointment and update amounts*/
			
		echo json_encode(array("status" => TRUE));
	}
	/*
	get extra service by id
	@param appo_extra_id for appointment extra service id
	@return json data
	*/
    public function ajax_edit_extra($appo_extra_id)
	{
		$data = $this->appointment_model->get_by_extra_id($appo_extra_id);
		echo json_encode($data);
	}

	/*
	delete extra service
	@param appo_extra_id for appointment extra service id
	@return status json
	*/	
	public function extra_delete($appo_extra_id)
	{
		/*get appointment and update amounts*/
			$amtExe=$this->db->get_where('appointment_extra',array('appo_extra_id'=>$appo_extra_id));
			$amtData=$amtExe->row();
			$apmId=(!empty($amtData))?$amtData->appointment_id:0;	
		/*get appointment and update amounts*/
		
	   $this->db->where('appo_extra_id', $appo_extra_id);
		$this->db->delete("appointment_extra");
		
		$this->appointment_model->update_appointment_amount($apmId);
		echo json_encode(array("status" => TRUE));
	}

}
?>