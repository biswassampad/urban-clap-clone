<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';
class User extends REST_Controller {

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
	user's login
	@param user_email for user's email
	@param user_password for user's password
	@return response for true/false
	@return message for error message
	@return data for login user's detail
	*/
	public function login_post(){
        
                 $this->load->library('form_validation');
                // Validate The Logi User
                $this->form_validation->set_rules('user_email', 'Email Id',  'trim|required|valid_email');
                $this->form_validation->set_rules('user_password', 'Password', 'trim|required');
               
                if ($this->form_validation->run() == FALSE) 
        		{
        		    $this->response(array(
                                        RESPONCE => false,
                                        MESSAGE => strip_tags($this->form_validation->error_string())
                                    ), REST_Controller::HTTP_OK); 
        		}else
                {
                   
                    $q = $this->db->query("Select * from `users` where user_email='".$this->post('user_email')."' and user_password='".md5($this->post('user_password'))."' Limit 1");
                    if ($q->num_rows() > 0)
                    {
                        $row = $q->row(); 
                        if($row->user_status == "0")
                        {
                            $this->response(array(
                                        RESPONCE => false,
                                        MESSAGE => "Your account currently inactive."
                                    ), REST_Controller::HTTP_OK); 
                            
                        }  
                        else
                        {
                            $this->response(array(
                                RESPONCE => true,"data"=>array(
                                "user_id"=>$row->user_id,
                                "user_type_id"=>$row->user_type_id,
                                "user_fullname"=>$row->user_fullname,
                                "user_email"=>$row->user_email,
                                "user_phone"=>$row->user_phone,
                                "user_bdate"=>$row->user_bdate,
                                "user_image"=>$row->user_image)), REST_Controller::HTTP_OK);
                        }
                    }
                    else
                    {
                        $this->response(array(
                                        RESPONCE => false,
                                        MESSAGE => "Invalide Username or Passwords"
                                    ), REST_Controller::HTTP_OK); 
                    }
                   
                    
                }
    }
    /*
	user registration
	@param user_fullname for user's fullname
	@param user_email for user's email
	@param user_password for user's password
	@param user_phone for user's phone
	@param user_bdate for user's birth date
	@return response for true/false
	@return message for error message
	@return data for register user's detail
	*/
    public function register_post(){
            $this->load->library('form_validation');
                /* add users table validation */
                $this->form_validation->set_rules('user_fullname', 'Full Name', 'trim|required'); 
                $this->form_validation->set_rules('user_email', 'Email Id',  'trim|required|valid_email|is_unique[users.user_email]');
                $this->form_validation->set_rules('user_password', 'Password', 'trim|required');
                $this->form_validation->set_rules('user_phone', 'Phone', 'trim|required'); 
                $this->form_validation->set_message('is_unique', 'Email address is already register');
                
                if ($this->form_validation->run() == FALSE) 
        		{
                    $this->response(array(
                                        RESPONCE => false,
                                        MESSAGE => strip_tags($this->form_validation->error_string())
                                    ), REST_Controller::HTTP_OK); 
                                    
        		}else
                {
                    
                  $user_id =  $this->common_model->data_insert("users", array("user_fullname"=>$this->post("user_fullname"),
                                            "user_email"=>$this->post("user_email"),
                                            "user_phone"=>$this->post("user_phone"),
                                            "user_bdate"=>$this->post("user_bdate"),
                                            "user_password"=>md5($this->post("user_password")), 
                                            "user_type_id"=>"3",
                                            "user_status"=>"1"  
                                            ));
                  
                  $this->response(array(
                                    RESPONCE => true,
                                    "data"=> array(
                                    "user_id"=>$user_id, 
                                    "user_phone"=>$this->post("user_phone"),
                                    "user_fullname"=>$this->post("user_fullname"),
                                    "user_email"=>$this->post("user_email"),
                                    "user_bdate"=>$this->post("user_bdate"),
                                    "user_image"=>""))
                                    , REST_Controller::HTTP_OK);
                    
                  }     
    }
	/*
	user registration
	@param user_fullname for user's fullname
	@param user_email for user's email
	@param user_password for user's password
	@param user_phone for user's phone
	@param user_bdate for user's birth date
	@return response for true/false
	@return message for error message
	@return data for register user's detail
	*/
    public function register_new_post(){
            $this->register_post();  
    }
	/*
	forget password
	@param email for email address to send mail
	@return response for true/false
	@return message for error message
	@return data for success message
	*/
    public function forgotpassword_post(){
            $this->load->library('form_validation');
            $this->form_validation->set_rules('email', 'Email', 'trim|required');
            if ($this->form_validation->run() == FALSE) 
      		{
            		    $this->response(array(
                                        RESPONCE => false,
                                        MESSAGE => strip_tags($this->form_validation->error_string())
                                    ), REST_Controller::HTTP_OK); 
                        
      		}else
            {
                   $request = $this->db->query("Select * from users where user_email = '".$this->post("email")."' limit 1");
                   if($request->num_rows() > 0){
                                
                                $user = $request->row();
                                
                                $token = uniqid(uniqid());
                                
                                $res = $this->emailsender->send_forgotpassword_email($user->user_id,$user->user_email);
                                
                                if ($res){
                                    $this->response(array(
                                        RESPONCE => false,
                                        MESSAGE => "Something is wrong with system to send mail."
                                    ), REST_Controller::HTTP_OK);
    
                                }else{
                                    $this->response(array(
                                    RESPONCE => true,
                                    "data"=> "Recovery mail sent to your email address please verify link.")
                                    , REST_Controller::HTTP_OK);
    
                                }
                   }else{
                                       	$this->response(array(
                                        RESPONCE => false,
                                        MESSAGE => "No user found with this email."
                                    ), REST_Controller::HTTP_OK);
                                           	    
    
                   }
                }
               
        }
		/*
		user's profile update
		@param user_phone for user's phone
		@param user_fullname for user's fullname
		@param user_id for update profile user's id
		@param user_bdate for user's birth date
		@param user_image for user's profile picture
		@return response for true/false
		@return message for error message
		@return data for success message
		*/
        public function updateprofile_post(){
                $this->load->library('form_validation');
                /* add users table validation */
                $this->form_validation->set_rules('user_phone', 'Phone', 'trim|required');
                $this->form_validation->set_rules('user_fullname', 'Full Name', 'trim|required');
                $this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
                if ($this->form_validation->run() == FALSE) 
        		{
        		    $this->response(array(
                                        RESPONCE => false,
                                        MESSAGE => strip_tags($this->form_validation->error_string())
                                    ), REST_Controller::HTTP_OK); 
        		}else
                {
                        // Data in Database
                    $update_array = array(
                        "user_fullname"=>$this->post("user_fullname"),
                        "user_phone"=>$this->post("user_phone"),
                        "user_bdate" => date("Y-m-d",strtotime($this->post("user_bdate")))
                    );
                                            
                    $file_name = "";
                    if(isset($_FILES["user_image"]) && $_FILES['user_image']['size'] > 0){
                        $path = './uploads/profile';
                        
                        if(!file_exists($path)){
                            mkdir($path);
                        }
                        $this->load->library("imagecomponent");
                        $file_name_temp = md5(uniqid())."_".$_FILES['user_image']['name'];
                        $file_name = $this->imagecomponent->upload_image_and_thumbnail('user_image',680,200,$path ,'crop',false,$file_name_temp);
                        $update_array["user_image"] = $file_name;
                    } 
                     
                    $this->common_model->data_update("users", $update_array, array("user_id"=>$this->post("user_id")));
                 
                    $this->response(array(
                                    RESPONCE => true,
                                    "data"=> "Profile Updated Successfully")
                                    , REST_Controller::HTTP_OK);
                    
                  }   
        }
		/*
		update profile picture
		@param user_id for user's id
		@param user_image for user's image
		@return response for true/false
		@return message for error message
		@return data for success filename return and failure error message
		*/
        public function updatepicture_post(){
            $this->load->library('form_validation');
                /* add users table validation */
                $this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
                if ($this->form_validation->run() == FALSE) 
        		{
        		    $this->response(array(
                                        RESPONCE => false,
                                        MESSAGE => strip_tags($this->form_validation->error_string())
                                    ), REST_Controller::HTTP_OK); 
                                    
        		}else
                {
                   
                    $file_name = "";
                    if(isset($_FILES["user_image"]) && $_FILES['user_image']['size'] > 0){
                        $path = './uploads/profile';
                        
                        if(!file_exists($path)){
                            mkdir($path);
                        }
                        $this->load->library("imagecomponent");
                            
                            $file_name_temp = md5(uniqid())."_".$_FILES['user_image']['name'];
                            $file_name = $this->imagecomponent->upload_image_and_thumbnail('user_image',680,200,$path ,'crop',false,$file_name_temp);
                          $update_array["user_image"] = $file_name;
                          $this->common_model->data_update("users", $update_array, array("user_id"=>$this->post("user_id")));
                          
                          $this->response(array(
                                    RESPONCE => true,
                                    "data"=>$file_name)
                                    , REST_Controller::HTTP_OK);
                    }else{
                        $this->response(array(
                                    RESPONCE => false,
                                    "data"=> "No file selected")
                                    , REST_Controller::HTTP_OK);
                    }                     
                 }   
        }
		
		/*
		change password
		@param user_id for user's id
		@param c_password for current password
		@param n_password for new password
		@param r_password for re-enter password
		@return response for true/false
		@return message for error message
		@return data for success message	
		*/
        public function changepass_post(){
           
                $this->load->library('form_validation');
                /* add users table validation */
                $this->form_validation->set_rules('c_password', 'Current Password', 'trim|required');
                $this->form_validation->set_rules('n_password', 'New Password', 'trim|required');
                $this->form_validation->set_rules('r_password', 'Re Password', 'trim|required');
                $this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
                if ($this->form_validation->run() == FALSE) 
        		{
        		    $this->response(array(
                                        RESPONCE => false,
                                        MESSAGE => strip_tags($this->form_validation->error_string())
                                    ), REST_Controller::HTTP_OK); 
        		}else
                {
                    $user_data  = $this->users_model->get_user_by_id($this->input->post("user_id"));
                    if($user_data->user_password == md5($this->input->post("c_password"))){
                        $n_password = $this->input->post("n_password");
                        $r_password = $this->input->post("r_password");
                        
                        if($n_password == $r_password){
                           
                            $this->common_model->data_update("users",
                                array("user_password"=>md5($n_password)),array("user_id"=>$user_data->user_id));
                           
                                    $this->response(array(
                                    RESPONCE => true,
                                    "data"=> "Change Password Successfully")
                                    , REST_Controller::HTTP_OK);
                        }else{
                            $this->response(array(
                                    RESPONCE => false,
                                    MESSAGE=> "New password do not match")
                                    , REST_Controller::HTTP_OK);
                        }
                        
                    }
                    else{
                         $this->response(array(
                                    RESPONCE => false,
                                    MESSAGE=> "Current Password Do Not Match")
                                    , REST_Controller::HTTP_OK);
                    }
                  }   
        }
		/*
		get user's detail
		@param user_id for user's id
		@return response for true/false
		@return message for error message
		@return data for user's detail
		*/
        public function list_get(){
       
            $id = $this->get('user_id');
 
           if($id === NULL){
            
               $this->response(array(
                            RESPONCE => false,
                            MESSAGE => 'Please provide user id'
                        ), REST_Controller::HTTP_OK); 
           }else{
                $id = (int) $id;
    
                // Validate the id.
                if ($id <= 0)
                {
                    // Invalid id, set the response and exit.
                    $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
                }
                
               
                $row =  $this->users_model->get_user_by_id($id);
                if($row){
                    $this->response(array(
                    RESPONCE => true,"data"=>array(
                                "user_id"=>$row->user_id,
                                "user_type_id"=>$row->user_type_id,
                                "user_fullname"=>$row->user_fullname,
                                "user_email"=>$row->user_email,
                                "user_phone"=>$row->user_phone,
                                "user_bdate"=>$row->user_bdate,
                                "user_image"=>$row->user_image)), REST_Controller::HTTP_OK);
               }else{
                   $this->response(array(
                            RESPONCE => false,
                            MESSAGE => 'No record were found'
                        ), REST_Controller::HTTP_OK); 
               }
           }
        
        }
        /*
		register fcm key 
		@param user_id for user's id
		@param token for fcm token
		@param device for device name
		@return response for true/false
		@return message for error message
		@return data for success message
		*/
        public function registerfcm_post(){
            $data = array();
            $this->load->library('form_validation');
            $this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
            $this->form_validation->set_rules('token', 'Token', 'trim|required');
            $this->form_validation->set_rules('device', 'Device', 'trim|required');
            if ($this->form_validation->run() == FALSE) 
      		{
      		    
            		  $this->response(array(
                                        RESPONCE => false,
                                        MESSAGE => strip_tags($this->form_validation->error_string())
                                    ), REST_Controller::HTTP_OK); 
                                
      		}else
            {   
                $device = $this->input->post("device");
                $token = $this->input->post("token");
                $user_id = $this->input->post("user_id");
                
                $field = "";
                if($device=="android"){
                    $field = "user_gcm_code";
                }else if($device=="ios"){
                    $field = "user_ios_token";
                }
                if($field!=""){
                    $this->db->query("update users set ".$field." = '".$token."' where user_id = '".$user_id."'");
                    $this->response(array(
                                    RESPONCE => true,
                                    "data"=> "Token updated successfully")
                                    , REST_Controller::HTTP_OK);    
                }else{
                    $this->response(array(
                                        RESPONCE => false,
                                        MESSAGE => "Device type is not set"
                                    ), REST_Controller::HTTP_OK); 
                }
                
                
            }
        
        }
                  /* contact us */
 /*
	get contact details
	@return response for true/false
	@return data for contact details
 */
 public function contact_get(){
    
     $q = $this->db->query("select * from `pageapp` WHERE id =1"); 
      
    $this->response(array(
                RESPONCE => true,"data"=>$q->row()), REST_Controller::HTTP_OK);
            
        
 }
 
 
 /* end contact us */
 
 /* about us */
 /*
	get aboutus details
	@return response for true/false
	@return data for aboutus details
 */
  public function aboutus_get(){
    
     $q = $this->db->query("select * from `pageapp` where id=2"); 
      
     $this->response(array(
                RESPONCE => true,"data"=>$q->row()), REST_Controller::HTTP_OK);  
 }
 
 
 /* end about us */
}
?>