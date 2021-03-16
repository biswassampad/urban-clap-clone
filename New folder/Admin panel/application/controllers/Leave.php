<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Leave extends MY_Controller {
     protected $controller;
    protected $table_name;
    public function __construct()
    {
         parent::__construct();
                $this->controller = $this->router->fetch_class();
                $this->table_name = "leaves";
         if(_is_user_login($this)){
                        
         }else{
            redirect("login");
            exit();
         }       
    }
    
/* ========== Offer ==========*/
public function index(){
        
        $data["offer"]  = $this->offer_model->get_offer();
        $this->load->view($this->controller."/list_offer",$data);    
} 


 public function edit($leave_id){
        if(_is_user_login($this)){
                
                $this->action();
				$data["offer"]  = $this->leave_model->get_leave();
                $data["field"] = $this->leave_model->get_leave_by_id($leave_id);
                $this->load->view($this->controller."/add",$data);
          }
                //echo json_encode($responce);
    }


 public function add(){
        if(_is_user_login($this)){
                
                $this->action();
                $data["field"] = $this->input->post();
                $data["offer"]  = $this->leave_model->get_leave();
                $this->load->view($this->controller."/add",$data);
          }
                //echo json_encode($responce);
    }
  private function action(){
        $post = $this->input->post();
                $this->load->library('form_validation'); 
                 $this->form_validation->set_rules('date', 'date', 'trim|required');
                $this->form_validation->set_rules('reason', 'reason', 'trim|required'); 
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
        		     //$times = explode('-',$this->input->post("reservationtime")); 
                     $offer_start_date = date(DB_TIME_FORMATE,strtotime(trim($post["start_time"]))) ;
                     $offer_end_date = date(DB_TIME_FORMATE,strtotime(trim($post["end_time"])));
                    $addcat = array(
                            "date"=>date(DB_DATE_FORMATE,strtotime($post["date"])),
                            "start_time"=>$offer_start_date,
                            "end_time"=>$offer_end_date,
                            "reason"=>$post["reason"]
                            );

                    $responce[RESPONCE] = true;
                    if(!empty($post["leave_id"])){
                        $this->common_model->data_update($this->table_name,$addcat,array("leave_id"=>$post["leave_id"]));
                        $this->message_model->action_mesage("update",$this->controller,false);
                        redirect($this->controller."/add");
                    }else{
                        $cat_id = $this->common_model->data_insert($this->table_name,$addcat);    
                         
                        $this->message_model->action_mesage("add",$this->controller,false);
                        redirect($this->controller."/add");
                    }        
                    
               	}
    }
public function delete_leave($leave_id){
        $cat = $this->leave_model->get_leave_by_id($leave_id);
        if(!empty($cat)){
            $this->common_model->data_remove($this->table_name,array("leave_id"=>$cat->leave_id),true);
            redirect($this->controller."/add");
        }
}
/* ========== Products ==========*/  
  
}
