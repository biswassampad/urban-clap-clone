<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';
class Schedule extends REST_Controller {
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
	get time slots
	@param date for appointment's date
	@param total_time for appointment's total time
	@return response for true/false
	@return message for error message
	@return data for slot's list
	*/
    public function slot_post(){
                $this->load->library('form_validation');
                /* add users table validation */
                $this->form_validation->set_rules('date', 'Date', 'trim|required');
                $this->form_validation->set_rules('total_time', 'Time', 'trim|required');
                if ($this->form_validation->run() == FALSE) 
        		{
        		    $this->response(array(
                                        RESPONCE => false,
                                        MESSAGE => strip_tags($this->form_validation->error_string())
                                    ), REST_Controller::HTTP_OK); 
        		}else
                {
                        
                        
                        $slot = $this->schedule_model->get_time_slots($this->post("total_time"),$this->post("date"));
          		        if($slot["responce"]){

                        $this->response(array(
                                        RESPONCE => true,
                                        DATA => $slot
                                    ), REST_Controller::HTTP_OK); 
                        
                        }else{
                            $this->response(array(
                                        RESPONCE => false,
                                        MESSAGE => $slot["error"]
                                    ), REST_Controller::HTTP_OK);
                            
                        }
                    
                }
    }
}
?>