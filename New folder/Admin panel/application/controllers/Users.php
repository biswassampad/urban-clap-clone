<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MY_Controller {
    public function __construct()
    {
                parent::__construct();
                // Your own constructor code
                $this->load->database();
                $this->load->helper('login_helper');
                
    }
	public function index()
	{
		if(_is_user_login($this)){
            $data = array();
            $this->load->model("users_model");
            $data["users"] = $this->users_model->get_alluser();
            $this->load->view("users/list",$data);
        }
    }
    public function add_user(){
        if(_is_user_login($this)){
            $data = array();
            $this->load->model("users_model");
            if($_POST){
                $this->load->library('form_validation');
                
                $this->form_validation->set_rules('user_fullname', 'Full Name', 'trim|required');
                $this->form_validation->set_rules('user_email', 'Email Id', 'trim|required');
                $this->form_validation->set_rules('user_password', 'Password', 'trim|required');
                
                if ($this->form_validation->run() == FALSE) 
        		{
        		  
        			$data["error"] = '<div class="alert alert-warning alert-dismissible" role="alert">
                                  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                  <strong>Warning!</strong> '.$this->form_validation->error_string().'
                                </div>';
                    
        		}else
                {
                        $user_fullname = $this->input->post("user_fullname");
                        $user_email = $this->input->post("user_email");
                        $user_password = $this->input->post("user_password");
                        
                        
                        
                        $status = ($this->input->post("status")=="on")? 1 : 0;
                        
                            $this->load->model("common_model");
                            $this->common_model->data_insert("users",
                                array(
                                "user_fullname"=>$user_fullname,
                                "user_email"=>$user_email,
                                "user_password"=>md5($user_password),
                                "user_type_id"=>USER_APP,
                                "user_status"=>$status));
                                 $this->session->set_flashdata("message", '<div class="alert alert-success alert-dismissible" role="alert">
                                  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                  <strong>Success!</strong> User Added Successfully
                                </div>');
                        
                }
            }
            
            $data["user_types"] = $this->users_model->get_user_type();
            $this->load->view("users/add_user",$data);
        }
    }
	/*
	edit user
	@param user_id for user's id
	@param user_fullname for user's fullname
	@param user_bdate for user's birthdate
	@param user_phone for user's phone
	@param status for user's status
	@param user_image for user's image
	@return edit user page
	*/
    public function edit_user($user_id=""){
        if(_is_user_login($this)){
            if(_get_current_user_type_id($this) == USER_ADMIN){
                if($user_id == ""){
                    $user_id = _get_current_user_id($this);
                }
            }else{
                $user_id = _get_current_user_id($this);
            }
            $data = array();
            $this->load->model("users_model");
            $data["user_types"] = $this->users_model->get_user_type();
            $user = $this->users_model->get_user_by_id($user_id);
            if(empty($user)){
                return;
            }
            $data["user"] = $user;
            if($_POST){
                $this->load->library('form_validation');
                
                $this->form_validation->set_rules('user_fullname', 'Full Name', 'trim|required');
                //$this->form_validation->set_rules('user_password', 'Password', 'trim|required');
                
                if ($this->form_validation->run() == FALSE) 
        		{
        		  
        			$data["error"] = '<div class="alert alert-warning alert-dismissible" role="alert">
                                  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                  <strong>Warning!</strong> '.$this->form_validation->error_string().'
                                </div>';
                    
        		}else
                {
                        $user_fullname = $this->input->post("user_fullname"); 
                        $user_bdate = date(DATE_FORMATE,strtotime($this->input->post("user_bdate"))); 
                        $user_phone = $this->input->post("user_phone"); 
                        
                        
                        $update_array = array(
                                "user_fullname"=>$user_fullname,
                               "user_bdate"=>$user_bdate,
                               "user_phone"=>$user_phone);
                               if (_get_current_user_type_id($this) == USER_ADMIN && $user_id != _get_current_user_id($this)) {
                                   $status = ($this->input->post("status")==1)? 1 : 0;
                                   $update_array["user_status"] = $status;
                               }
                        $isNotValidFile=0;
								if(isset($_FILES["user_image"]) && $_FILES['user_image']['size'] > 0){
                                    $path = USER_PATH;
                                    
                                    if(!file_exists($path)){
                                        mkdir($path);
                                    }
                                    $this->load->library("imagecomponent");
                                        
                                    $file_name_temp = md5(uniqid())."_".$_FILES['user_image']['name'];
									$ext = pathinfo($file_name_temp, PATHINFO_EXTENSION);
									if("jpg"!=strtolower($ext) && "jpeg"!=strtolower($ext) && "png"!=strtolower($ext) && "gif"!=strtolower($ext))
									{
										$isNotValidFile=1;
									}
									else
									{
										$file_name = $this->imagecomponent->upload_image_and_thumbnail('user_image',680,200,$path ,'crop',true,$file_name_temp);
										$update_array["user_image"] = $file_name;
									}
                                }
							if($isNotValidFile==0)
							{
								$this->load->model("common_model");
								$this->common_model->data_update("users",$update_array,array("user_id"=>$user_id)
									);
								
								$this->message_model->success($this->message_model->custom_messages("update_user",array($user_fullname)));
                                if($user_id == _get_current_user_id($this)){
                                    redirect("users/edit_user");
                                }else{
                                    redirect("appuser");
                                }
                                
							}
							else
							{
								$this->message_model->error("Invalid file format! Please upload image file.");
							}
                           
                }
            }
            
            
            $this->load->view("users/edit_user",$data);
        }
    }
	
	/*
	delete user
	@param user_id for user's id
	@return redirect to users
	*/
	function delete_user($user_id){
            $cat  = $this->users_model->get_user_by_id($user_id);
           if(!empty($cat)){
            $this->common_model->data_remove("users",array("user_id"=>$cat->user_id),true);
                redirect("appuser");
           }
    }
	/*
	forget password
	@param email for user's register email
	@return forget password page
	*/
    public function forgot(){
        
        $data = array();
        $this->load->library('form_validation');
        $this->form_validation->set_rules('email', 'Email', 'trim|required');
        if ($this->form_validation->run() == FALSE) 
  		{
        		  if($this->form_validation->error_string()!="")
                    $this->session->set_flashdata("message",'<div class="alert alert-warning alert-dismissible" role="alert">
                                  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                  <strong>Warning!</strong> '.$this->form_validation->error_string().'
                                </div>');
                    
  		}else
        {
               $request = $this->db->query("Select * from users where user_email = '".$this->input->post("email")."' limit 1");
               if($request->num_rows() > 0){
                            
                            $user = $request->row();
                            $token = uniqid(uniqid());
                            $this->db->update("users",array("varified_token"=>$token),array("user_id"=>$user->user_id)); 
                            
                            /* $subject=" Forgot password request ";
                            $message = "Hi ".$user->user_fullname." \n Your password forgot request is accepted please visit following link to change your password. \n
                                ".site_url("users/modify_password/".$token)."
                                "; */
                            $list = array($user->user_email);	
							/* $this->load->library('email');							
                            $this->email->from($this->config->item("SENDER_EMAIL"),$this->config->item("SENDER_NAME"));
                            
                            $this->email->to($list);
                            $this->email->reply_to($this->config->item("SENDER_EMAIL"),$this->config->item("SENDER_NAME"));
                            $this->email->subject('Forgot password request');
                            $this->email->message($message); */
							/* $this->load->model('email_model');
							$sendMail=$this->email_model->send_email($list,$subject,$message);
							*/
							$list = array($user->user_email);	
							$sendMail=$this->emailsender->send_forgotpassword_email($user->user_id,$list);
                            if ($sendMail){
                                
                    $this->session->set_flashdata("message", '<div class="alert alert-error alert-dismissible" role="alert">
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Error!</strong> Something is wrong with system to send mail. </div>');

                            }else{
                                  $this->session->set_flashdata("message", '<div class="alert alert-success alert-dismissible" role="alert">
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Success!</strong> Recovery mail sent to your email address please verify link. </div>');
                            }
               }else{
                        $this->session->set_flashdata("message", '<div class="alert alert-danger alert-dismissible" role="alert">
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Warning!</strong> No user found with this email. </div>');
               }
        }

        $this->load->view("users/forgot");
    }
    function modify_password($token){
        $data = array();
        $q = $this->db->query("Select * from users where varified_token = '".$token."' limit 1");
        if($q->num_rows() > 0){
                        $data = array();
                        $this->load->library('form_validation');
                        $this->form_validation->set_rules('n_password', 'New password', 'trim|required');
                        $this->form_validation->set_rules('r_password', 'Re-enter password', 'trim|required|matches[n_password]');
                        if ($this->form_validation->run() == FALSE) 
                  		{
                  		    if($this->form_validation->error_string()!=""){
                        		  
                                    $data["response"] = "error";
                        			$data["error"] = '<div class="alert alert-warning alert-dismissible" role="alert">
                                                  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                  <strong>Warning!</strong> '.$this->form_validation->error_string().'
                                                </div>';
                                                }
                                    
                  		}else
                        {
                                    $user = $q->row();
                                   $this->db->update("users",array("user_password"=>md5($this->input->post("n_password")),"varified_token"=>""),array("user_id"=>$user->user_id));
                                   $data["success"] = true;                             
								   redirect(base_url());
                                                                   
                        }
                        $this->load->view("users/modify_password",$data);
        }else{
            echo "No access token found";
        }
    }
    public function listuser($user_id){
        if(_is_user_login($this)){
            $data["users"] = $this->users_model->get_user($user_id);
            $this->load->view("appuser/list",$data);
        }
    }
	
	/*
	change password 
	@param c_password for current password 
	@param n_password for new password
	@param r_password for repeat password
	@return change password page
	*/
    function change_password(){
        if(_is_user_login($this)){
            $this->load->model("users_model");
                $user_data  = $this->users_model->get_user_by_id(_get_current_user_id($this));
                $data["user_data"] = $user_data;
                if($_POST){
                    $this->load->model("users_model");
           
                $this->load->library('form_validation');
                
                $this->form_validation->set_rules('c_password', 'Current Password', 'trim|required');
                $this->form_validation->set_rules('n_password', 'New Password', 'trim|required');
                $this->form_validation->set_rules('r_password', 'Re Password', 'trim|required');
                
                if ($this->form_validation->run() == FALSE) 
        		{
        		  
        			$data["error"] = '<div class="alert alert-warning alert-dismissible" role="alert">
                                  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                  <strong>Warning!</strong> '.$this->form_validation->error_string().'
                                </div>';
                    
        		}else
                {
                    if($user_data->user_password == md5($this->input->post("c_password"))){
                        $n_password = $this->input->post("n_password");
                        $r_password = $this->input->post("r_password");
                        
                        if($n_password == $r_password){
                            $this->load->model("common_model");
                            $resid = $this->common_model->data_update("users",
                                array("user_password"=>md5($n_password)),array("user_id"=>_get_current_user_id($this)));
                           
                             //$this->logs_model->set_log($this,"users","Change Password","Change Password ".$user_data->user_email." is Change Password successfully");
                            $this->session->set_flashdata("message", '<div class="alert alert-success alert-dismissible" role="alert">
                                  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                  <strong>Success!</strong> Password Change Successfully
                                </div>');
                        }
                        else{
                            $data["error"] = '<div class="alert alert-warning alert-dismissible" role="alert">
                                  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                  <strong>Warning!</strong> Re Enter password do not match
                                </div>';
                        }
                        
                    }else{
                        $data["error"] = '<div class="alert alert-warning alert-dismissible" role="alert">
                                  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                  <strong>Warning!</strong> Current password do not match
                                </div>';
                    }
                        
                        
                            
                        
                }
            }      
                $this->load->view("users/change_pass",$data);
        }
    }
    
	/*
	test send mail
	*/
	function test_send_mail($subject,$message)
	{
		$to="dev-php-2@arityinfoway.com";
		$this->load->model('email_model');
		$this->email_model->send_email($to,$subject,$message);
	}
	
}