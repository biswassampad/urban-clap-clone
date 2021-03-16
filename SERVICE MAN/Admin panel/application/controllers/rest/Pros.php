<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';
class Pros extends REST_Controller {
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
	get list of pros or single pros
	@param cat_id for category wise filter
	@param id for pros id to get single pros
	@return response for true/false
	@return data for single pros or list of pros data
	*/
    public function list_get(){
        $id = $this->get('id');
        // Validate The Shop Id
       if($id === NULL){
            
            $parms =array();
            
            $cat_id = $this->get('cat_id');
            if($cat_id != NULL){
                $parms["cat_id"] = $cat_id;
            } 
           $pros =  $this->pros_model->get($parms);
           
           if(!empty($pros)){
                $this->response(array(
                RESPONCE => true,"data"=>$pros), REST_Controller::HTTP_OK);
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
            
           
            $pros =  $this->pros_model->get_by_id($id);
            if(!empty($pros)){
                $this->response(array(
                RESPONCE => true,"data"=>$pros), REST_Controller::HTTP_OK);
           }else{
               $this->response(array(
                        RESPONCE => false,
                        MESSAGE => 'No record were found'
                    ), REST_Controller::HTTP_OK); 
           }
       }
    }
    /*
	get list of pros team
	@return response for true/false
	@return message for error message
	@return data for list of pros object
	*/
    public function team_get(){
         $pros =  $this->pros_model->get();
            if(!empty($pros)){
                $this->response(array(
                RESPONCE => true,"data"=>$pros), REST_Controller::HTTP_OK);
           }else{
               $this->response(array(
                        RESPONCE => false,
                        MESSAGE => 'No record were found'
                    ), REST_Controller::HTTP_OK); 
           }
    }
    
	/*
	get pros assigned service
	@param pros_id for pros id to get assigned service
	@return response for true/false
	@return message for error message
	@return data for assigned service list
	*/
    public function pros_assigned_get(){
         $id = $this->get('pros_id');
            
        $pros =  $this->pros_model->get_pros_assigned($id,"assigned");
            if(!empty($pros)){
                $this->response(array(
                RESPONCE => true,"data"=>$pros), REST_Controller::HTTP_OK);
           }else{
               $this->response(array(
                        RESPONCE => false,
                        MESSAGE => 'No record were found'
                    ), REST_Controller::HTTP_OK); 
           }
    }
	
	/*
	get pros completed service
	@param pros_id for pros id to get completed service
	@return response for true/false
	@return message for error message
	@return data for completed service list
	*/
    public function pros_completed_get(){
         $id = $this->get('pros_id');
            
        $pros =  $this->pros_model->get_pros_assigned($id,"completed");
            if(!empty($pros)){
                $this->response(array(
                RESPONCE => true,"data"=>$pros), REST_Controller::HTTP_OK);
           }else{
               $this->response(array(
                        RESPONCE => false,
                        MESSAGE => 'No record were found'
                    ), REST_Controller::HTTP_OK); 
           }
    }
}
?>