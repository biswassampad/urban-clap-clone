<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';
class Address extends REST_Controller {
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
	add new address
	@param user_id for user's id
	@param zipcode for user's zipcode
	@param address for user's address
	@param landmark for user's landmark
	@param fullname for user's fullname
	@param mobilenumber for user's mobilenumber
	@param city for user's city
	@return response for true/false
	@return DATA for added address data
	*/
    public function add_post(){
                $this->load->library('form_validation');
                /* add users table validation */
                $this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
                $this->form_validation->set_rules('zipcode', 'Zipcode', 'trim|required');
                $this->form_validation->set_rules('address', 'Address', 'trim|required');
                $this->form_validation->set_rules('landmark', 'Landmark', 'trim|required');
                if ($this->form_validation->run() == FALSE) 
        		{
        		    $this->response(array(
                                        RESPONCE => false,
                                        MESSAGE => strip_tags($this->form_validation->error_string())
                                    ), REST_Controller::HTTP_OK); 
        		}else
                {
                        
                        
                        $insert = array(
                        "user_id"=>$this->post("user_id"),
                        "delivery_zipcode"=>$this->post("zipcode"),
                        "delivery_address"=>$this->post("address"),
                        "delivery_landmark"=>$this->post("landmark"),
                        "delivery_fullname"=>$this->post("fullname"),
                        "delivery_mobilenumber"=>$this->post("mobilenumber"),
                        "delivery_city"=>$this->post("city")
                        );
                        
                        $id = $this->common_model->data_insert("address",$insert);
          		        
                        $address = $this->address_model->get_by_id($id);
                        $this->response(array(
                                        RESPONCE => true,
                                        DATA => $address
                                    ), REST_Controller::HTTP_OK); 

                    
                }
    }
	/*
	get list of address
	@param id for address's id
	@parmas user_id when id is null user's id will be useful for get address list
	@return response for true/false
	@return data for list of addresses
	*/
    public function list_get(){
        $id = $this->get('id');
        // Validate The Shop Id
       if($id === NULL){
            
            $parms =array();
            
            $user_id = $this->get('user_id');
            if($user_id === NULL){
                $this->response(array(
                        RESPONCE => false,
                        MESSAGE => 'User ID Required'
                    ), REST_Controller::HTTP_OK);
            }
            $parms["users.user_id"] = $user_id;
                       
           $address =  $this->address_model->get($parms);
           
           if(!empty($address)){
                $this->response(array(
                RESPONCE => true,"data"=>$address), REST_Controller::HTTP_OK);
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
            
           
            $address =  $this->address_model->get_by_id($id);
            if(!empty($address)){
                $this->response(array(
                RESPONCE => true,"data"=>$address), REST_Controller::HTTP_OK);
           }else{
               $this->response(array(
                        RESPONCE => false,
                        MESSAGE => 'No record were found'
                    ), REST_Controller::HTTP_OK); 
           }
       }
    }
    
     
        /*
		used for edit address
		@param zipcode for user's zipcode
		@param address for user's address
		@param landmark for user's landmark
		@param fullname for user's fullname
		@param mobilenumber for user's mobilenumber
		@param city for user's city
		@param id for address id
		@return response for true/false
		@return data for message of success
		*/
         public function edit_address_post(){
                $data = array(); 
                $this->load->library('form_validation');
                /* add users table validation */
                 
                $this->form_validation->set_rules('zipcode', 'Zipcode', 'trim|required');
                $this->form_validation->set_rules('address', 'Address',  'trim|required');
                $this->form_validation->set_rules('landmark', 'Landmark',  'trim|required');
                $this->form_validation->set_rules('fullname', 'Full Name',  'trim|required');
                $this->form_validation->set_rules('mobilenumber', 'Mobile Number',  'trim|required');
                $this->form_validation->set_rules('city', 'City',  'trim|required');
                $this->form_validation->set_rules('id', 'Address ID', 'trim|required');
                 
                if ($this->form_validation->run() == FALSE) 
        		{
        		    $this->response(array(
                                        RESPONCE => false,
                                        MESSAGE => strip_tags($this->form_validation->error_string())
                                    ), REST_Controller::HTTP_OK); 
                    
        		}else
                {
            $insert_array=  array(        
                                         
                                        "delivery_zipcode" => $this->input->post("zipcode"),
                                        "delivery_address" => $this->input->post("address"),
                                        "delivery_landmark" => $this->input->post("landmark"),
                                        "delivery_fullname" => $this->input->post("fullname"),
                                        "delivery_mobilenumber" => $this->input->post("mobilenumber"),
                                        "delivery_city" => $this->input->post("city"));
                     
                    $this->load->model("common_model");
                     
                    
                   $this->common_model->data_update("address",$insert_array,array("id"=>$this->input->post("id")));
                    
                      
                   $this->response(array(
                                    RESPONCE => true,
                                    DATA =>"Your Address Update successfully...")
                                    , REST_Controller::HTTP_OK);
                  }    
        }
        /*
		used for delete address
		@parmas id for address id to remove address
		@return response for true/false
		@return message for error message
		@return data for success message
		*/
        public function delete_address_post()
    	{
    	    $this->load->library('form_validation');
                     $this->form_validation->set_rules('id', 'ID', 'trim|required');
           
            if ($this->form_validation->run() == FALSE)
            		{
            			   $this->response(array(
                                        RESPONCE => false,
                                        MESSAGE => strip_tags($this->form_validation->error_string())
                                    ), REST_Controller::HTTP_OK); 
            		}
           
    	   else{
    	        
                $this->db->delete("address",array("id"=>$this->input->post("id")));
                 
                 $this->response(array(
                                    RESPONCE => true,
                                    DATA =>"Your Address deleted successfully...")
                                    , REST_Controller::HTTP_OK); 
                  
            }
            echo json_encode($data);
        }
    
}
?>