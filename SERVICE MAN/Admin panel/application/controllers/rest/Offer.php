<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';
class Offer extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        //$this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        //$this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        //$this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
    }
    /*
	used for get active offer's list
	@return response for true/false
	@return data for list of offers
	*/
    public function list_get(){   
                 $offer_list =  $this->offer_model->get_offer(array("offer_status"=>"1")); 
                $this->response(array(
                RESPONCE => true,"data"=>$offer_list), REST_Controller::HTTP_OK); 
    }
    
	
	/*
	used for check valid offer 
	@param offer_coupon for offer's coupon
	@param user_id for user's id
	@return response for true/false
	@return message for error message
	@return data for valid offer details
	*/
    public function offer_check_post(){
         $this->load->library('form_validation');
                // Validate The Logi User
                $this->form_validation->set_rules('offer_coupon', 'Offer Coupon',  'trim|required');
                $this->form_validation->set_rules('user_id', 'user id', 'trim|required');
               
                if ($this->form_validation->run() == FALSE) 
        		{
        		    $this->response(array(
                                        RESPONCE => false,
                                        MESSAGE => strip_tags($this->form_validation->error_string())
                                    ), REST_Controller::HTTP_OK); 
        		}else
                {
                   
                    $user_found = $this->db->query("Select * from `users` where user_id='".$this->post('user_id')."' Limit 1");
                     if ($user_found->num_rows() > 0)
                   {
                            $q = $this->db->query("Select * from `offer` where offer_coupon='".$this->post('offer_coupon')."' and offer_status = 1 Limit 1");
                             $row = $q->row(); 
                            if ($q->num_rows() > 0)
                            {  
                                 $c_date = strtotime(date('Y-m-d'));
                                 $s_date = strtotime($row->offer_start_date);
                                 $e_date = strtotime($row->offer_end_date);
                                 if($c_date < $s_date){
                                    $this->response(array(
                                                RESPONCE => false,
                                                MESSAGE => "Offer still not start"
                                            ), REST_Controller::HTTP_OK);  

                                 }
                                 if($c_date > $e_date){
                                    $this->response(array(
                                                RESPONCE => false,
                                                MESSAGE => "Offer expired"
                                            ), REST_Controller::HTTP_OK);  

                                 }
                                 $user_check = $this->db->query("Select * from `appointment` where promo_code='".$this->post('offer_coupon')."' and user_id='".$this->post('user_id')."' Limit 1");
                                 if($user_check->num_rows() > 0)
                                {
                                    $this->response(array(
                                                RESPONCE => false,
                                                MESSAGE => "This coupon code alredy used."
                                            ), REST_Controller::HTTP_OK);  
                                }  
                                else
                                { 
                                      $this->response(array(
                                        RESPONCE => true,"data"=>array(
                                        "offer_id"=>$row->offer_id,
                                        "offer_title"=>$row->offer_title, 
                                        "offer_coupon"=>$row->offer_coupon,
                                        "offer_discount"=>$row->offer_discount)), REST_Controller::HTTP_OK);
                                }
                            }
                            else
                            {
                                $this->response(array(
                                                RESPONCE => false,
                                                MESSAGE => "Invalide Offer Code"
                                            ), REST_Controller::HTTP_OK); 
                            }   
                   } else
                            {
                                $this->response(array(
                                                RESPONCE => false,
                                                MESSAGE => "User Not found in our system"
                                            ), REST_Controller::HTTP_OK); 
                            }   
                    
                }
    } 
         
     
}
?>