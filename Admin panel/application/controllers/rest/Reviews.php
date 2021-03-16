<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';
class Reviews extends REST_Controller {
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
	add review of appointment
	@param user_id for user's id
	@param appointment_id for appointment's id
	@param reviews for review of appointment
	@param ratings for ratings
	@return response for true/false
	@return message for error message
	@return data for added review's data
	*/
    public function add_post(){
                $this->load->library('form_validation');
                /* add users table validation */
                $this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
                $this->form_validation->set_rules('appointment_id', 'Appointment ID', 'trim|required');
                $this->form_validation->set_rules('reviews', 'Reviews', 'trim|required');
                $this->form_validation->set_rules('ratings', 'Rating', 'trim|required');
                if ($this->form_validation->run() == FALSE) 
        		{
        		    $this->response(array(
                                        RESPONCE => false,
                                        MESSAGE => strip_tags($this->form_validation->error_string())
                                    ), REST_Controller::HTTP_OK); 
        		}else
                {
                        
                        //$cat_id = $this->post("cat_id");
                        //$pros_id = $this->post("pros_id");
                        $appointment_id = $this->post("appointment_id");
                        
                        $insert = array(
                        "user_id"=>$this->post("user_id"),
                        "reviews"=>$this->post("reviews"),
                        "ratings"=>$this->post("ratings"),
                        "on_date"=>date("Y-m-d h:i:s"),
                        "appointment_id"=>$appointment_id
                        );
                        
                        $id = $this->common_model->data_insert("reviews",$insert);
          		        
                        $review = $this->reviews_model->get_by_id($id);
                        $this->response(array(
                                        RESPONCE => true,
                                        DATA => $review
                                    ), REST_Controller::HTTP_OK); 

                    
                }
    }
	/*
	get list of review or single review
	@param id for review's id
	@param cat_id for category wise review
	@param pros_id for pros wise review
	@return response for true/false
	@return message for error message
	@return data for reviews object 
	*/
    public function list_get(){
        $id = $this->get('id');
        // Validate The Shop Id
       if($id === NULL){
            
            $parms =array();
            
            $cat_id = $this->get('cat_id');
            $pros_id = $this->get('pros_id');

            if($cat_id != NULL){
                $reviews = $this->reviews_model->get_by_cat_id($cat_id);
            }else if($pros_id != NULL){
                $reviews = $this->reviews_model->get_by_pros_id($pros_id);
            }else{
                $reviews =  $this->reviews_model->get($parms);
            }
            
            
            
           
           if(!empty($reviews)){
                $this->response(array(
                RESPONCE => true,"data"=>$reviews), REST_Controller::HTTP_OK);
           }else{
               $this->response(array(
                        RESPONCE => false,
                        MESSAGE => 'No record were found'
                    ), REST_Controller::HTTP_OK); 
           } 
           
          
       }else{
            $id = (int) $id;

            // Validate the id.
            if ($id <= 0)
            {
                // Invalid id, set the response and exit.
                $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
            }
            
           
            $reviews =  $this->reviews_model->get_by_id($id);
            if(!empty($reviews)){
                $this->response(array(
                RESPONCE => true,"data"=>$reviews), REST_Controller::HTTP_OK);
           }else{
               $this->response(array(
                        RESPONCE => false,
                        MESSAGE => 'No record were found'
                    ), REST_Controller::HTTP_OK); 
           }
       }
    }
}
?>