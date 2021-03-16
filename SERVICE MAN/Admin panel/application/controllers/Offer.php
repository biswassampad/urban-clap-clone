<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Offer extends MY_Controller {
     protected $controller;
    protected $table_name;
    public function __construct()
    {
         parent::__construct();
                $this->controller = $this->router->fetch_class();
                $this->table_name = "offer";
         if(_is_user_login($this)){
                        
         }else{
            redirect("login");
            exit();
         }
    }
    
/* ========== Offer ==========*/
/*
Offer page
@return list of offers page
*/
public function index(){
        
        $data["offer"]  = $this->offer_model->get_offer();
        $this->load->view($this->controller."/list_offer",$data);    
} 

/*
edit offer 
@param offer_id for offer's id
@return edit offet page
*/
 public function edit($offer_id){
        if(_is_user_login($this)){
                
                $this->action();
				
                $data["field"] = $this->offer_model->get_offer_by_id($offer_id);
                $this->load->view($this->controller."/add",$data);
          }
                //echo json_encode($responce);
    }
/*
add offer  
@return add offer page
*/
 public function add(){
        if(_is_user_login($this)){
                
                $this->action();
                $data["field"] = $this->input->post();
                $this->load->view($this->controller."/add",$data);
          }
                //echo json_encode($responce);
    }
	
/*
action 
@param offer_title for offer's title
@param offer_description for offer's description
@param offer_coupon for offer's coupon
@param offer_discount for offer's discount
@param reservationtime for offer valid from and to time
@param offer_status for offer's active/deactive
@return response for true/false
@return error for error message
*/	
  private function action(){
        $post = $this->input->post();
                $this->load->library('form_validation'); 
                 $this->form_validation->set_rules('offer_title', 'Title', 'trim|required');
                $this->form_validation->set_rules('offer_description', 'Description', 'trim|required'); 
                $this->form_validation->set_rules('offer_coupon', 'offer_coupon', 'trim|required|callback_coupon_code_check');
                $this->form_validation->set_rules('offer_discount', 'discount', 'trim|required');
                $responce = array();
                if ($this->form_validation->run() == FALSE)
        		{
     			    if($this->form_validation->error_string()!="")
                    {
                            $responce[RESPONCE] = false;
                            $responce[ERROR] = $this->message_model->error($this->form_validation->error_string(),false);
							//$this->message_model->error($responce[ERROR],false);
                    }
        		}
        		else
        		{
        		     $times = explode('-',$this->input->post("reservationtime")); 
                     $offer_start_date = date(COMMON_DATE_FORMATE,strtotime(trim($times[0]))) ;
                     $offer_end_date = date(COMMON_DATE_FORMATE,strtotime(trim($times[1])));
                    $addcat = array(
                            "offer_title"=>$post["offer_title"],
                            "offer_description"=>$post["offer_description"],
                             "offer_start_date"=>$offer_start_date,
                            "offer_end_date"=>$offer_end_date,
                            "offer_coupon"=>$post["offer_coupon"],
                            "offer_discount" =>$post["offer_discount"],
                            "offer_status" =>$post['offer_status']
                            );

                    $responce[RESPONCE] = true;
                    if(!empty($post["offer_id"])){
                        $this->common_model->data_update($this->table_name,$addcat,array("offer_id"=>$post["offer_id"]));
                        $this->message_model->action_mesage("update",$this->controller,false);
                        redirect($this->controller);
                    }else{
                        $cat_id = $this->common_model->data_insert($this->table_name,$addcat);    
                         
                        $this->message_model->action_mesage("add",$this->controller,false);
                        redirect($this->controller);
                    }        
                    
               	}
    }
/*
delete offer 
@param offer_id for offer's id
@return redirect to offer page
*/	
public function delete_offer($offer_id){
        $cat = $this->offer_model->get_offer_by_id($offer_id);
        if(!empty($cat)){
            $this->common_model->data_remove($this->table_name,array("offer_id"=>$cat->offer_id),true);
            redirect($this->controller);
        }
}

public function coupon_code_check($code)
{
		$id=(isset($_POST['offer_id']))?$this->input->post('offer_id'):0;
		$isUnique=$this->offer_model->check_is_unique_code($id,$code);
		if ($isUnique == 0)
		{
				$this->form_validation->set_message('coupon_code_check', 'Coupon already used in somewhere else.');
				return FALSE;
		}
		else
		{
				return TRUE;
		}
}
  
}
