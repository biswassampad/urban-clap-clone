<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';
class Banners extends REST_Controller {
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
	used for get single category or list of category
	@param id for category id
	@return response for true/false
	@return message for error message
	@return data for category list or single category
	*/
    public function list_get(){
        $id = $this->get('id');
        // Validate The Shop Id
        $this->load->model("banners_model");
       if($id === NULL){
         
            
           $category =  $this->banners_model->get_categories();
           
           if(!empty($category)){
                $this->response(array(
                RESPONCE => true,"data"=>$category), REST_Controller::HTTP_OK);
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
            
           
            $category =  $this->banners_model->get_by_id($id);
            if(!empty($category)){
                $this->response(array(
                RESPONCE => true,"data"=>$category), REST_Controller::HTTP_OK);
           }else{
               $this->response(array(
                        RESPONCE => false,
                        MESSAGE => 'No record were found'
                    ), REST_Controller::HTTP_OK); 
           }
       }
    }
}