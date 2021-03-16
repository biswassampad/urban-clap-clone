<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller {
    public function __construct()
    {
                parent::__construct();
                
				if(_is_user_login($this)){
                        
                }else{
                    redirect("login");
                    exit();
                }	

    }
	/*
	dashboard
	@return admin's dashboard
	*/
    public function dashboard(){
        
        $user_type_id = _get_current_user_type_id($this);
        $user_id = _get_current_user_id($this);
        $params = array();
        if($user_type_id == USER_PROS){
            $params["appointment.pros_id"] = $user_id;
        }
        $params["appointment.appointment_date"] = date(DB_DATE_FORMATE);
        
        $data["data"] = $this->appointment_model->get($params);
        $data["pending"] =  $this->appointment_model->get_counts(array("status"=>STATUS_PENDING));
        $data["assigned"] =  $this->appointment_model->get_counts(array("status"=>STATUS_ASSIGNED));
        $data["completed"] =  $this->appointment_model->get_counts(array("status"=>STATUS_COMPLETED));
        $data["appointment_total_count"] =  $this->appointment_model->get_total_amount(array("status"=>STATUS_COMPLETED));
        
        $data["user_count"] =  $this->users_model->get_users_counts(USER_APP);
        $this->load->view("admin/dashboard",$data);
        // $this->load->view($this->controller."/dashboard");
    }
	
	/* 
	for signout
	@return redirect to login page
	*/
    public function signout()
    {
        $this->session->unset_userdata(USER_FULLNAME);
        $this->session->unset_userdata(USER_EMAIL);
        $this->session->unset_userdata(LOGGED_ID);
        $this->session->unset_userdata(USER_ID);
        $this->session->unset_userdata(USER_TYPE_ID);
        $this->session->unset_userdata(USER_IMAGE);
        
        redirect("login");
    }
    
    /**
     * Check email is registerd with system or not
     * @param  string $user_email email id of registered user
     * @return json
     */
	public function check_email_exist(){
        header('Content-type: text/json');
        $data = array();
        $this->load->library('form_validation');
        $this->form_validation->set_rules('user_email', 'Email', 'trim|required|valid_email');
                if ($this->form_validation->run() == FALSE) 
        		{
        		  if($this->form_validation->error_string()!=""){
        			 $data["message"] = "<span class='text-red'>".$this->form_validation->error_string()."</span>";
                     $data["valid"] = false;               
                    }
                    
        		}else
                { 
                    if($this->input->get("email") != ""){
                        if($this->input->post("user_email") == $this->input->get("email")){
                            $data["message"] = "<span class='text-success'>"."Email awailable"."</span>";
                            $data["valid"] = true;
                            echo json_encode($data);
                            exit();
                        }
                    }
                    $q = $this->db->query("Select * from `users` where `user_email`='".$this->input->post("user_email")."' Limit 1");
                    
                   // print_r($q) ; 
                    if ($q->num_rows() > 0)
                    {
                        $data["message"] = "<span class='text-red'>"."Email already registered with system"."</span>";
                        $data["valid"] = false; 
                    }else{
                        $data["message"] = "<span class='text-success'>"."Email awailable"."</span>";
                        $data["valid"] = true;
                    }
                }
                echo json_encode($data);
    }
    
    /**
     * Edit User
     * @param  string $user_id  User id of registerd user
     * @return null
     */
    public function edit_user($user_id){
        if(_is_user_login($this)){
            $data = array();
            $this->load->model("users_model");
            $data["user_types"] = $this->users_model->get_user_type();
            $user = $this->users_model->get_user_by_id($user_id);
            $data["user"] = $user;
            if($_POST){
                $this->load->library('form_validation');
                
                $this->form_validation->set_rules('user_fullname', 'Full Name', 'trim|required');
                $this->form_validation->set_rules('user_password', 'Password', 'trim|required');
                if ($this->form_validation->run() == FALSE) 
        		{
        		  
        			$this->session->set_flashdata("message", '<div class="alert alert-warning alert-dismissible" role="alert">
                                  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                  <strong>Warning!</strong> '.$this->form_validation->error_string().'
                                </div>');
                    
        		}else
                {
                        $user_fullname = $this->input->post("user_fullname");
                       /* $user_type = $this->input->post("user_type");*/
                        $status = ($this->input->post("status")=="on")? 1 : 0;
                        
                        $update_array = array(
                                "user_fullname"=>$user_fullname,
                                "user_type_id"=>"3",
                                "user_status"=>$status);
                        $user_password = $this->input->post("user_password");
                        if($user->user_password != $user_password){
                            
                        $update_array["user_password"]= md5($user_password);
                        
                        }
                        if(isset( $_FILES["user_image"]) && $_FILES["user_image"]["size"] > 0)
                {
                    $config['upload_path']          = './uploads/profile/';
                    $config['allowed_types']        = 'gif|jpg|png|jpeg';
                    if(!is_dir($config['upload_path']))
                    {
                        mkdir($config['upload_path']);
                    }
                    $this->load->library('upload', $config);
                    if ( ! $this->upload->do_upload('user_image'))
                    {
                        $error = array('error' => $this->upload->display_errors());
                    }
                    else
                    {
                        $img_data = $this->upload->data();
                        $user_image=$img_data['file_name'];
                         $update_array["user_image"] =$user_image;
                    }
                }
                        
                        
                            $this->load->model("common_model");
                            $this->common_model->data_update("users",$update_array,array("user_id"=>$user_id)
                                );
                            $this->logs_model->set_log($this,"users","Edit","User is Update successfully");
                            
                            $this->session->set_flashdata("message", '<div class="alert alert-success alert-dismissible" role="alert">
                                  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                  <strong>Success!</strong> User Update Successfully
                                </div>');
                                redirect("admin/edit_user/".$user->user_id);
                        
                }
            }
            
            
            $this->load->view("users/edit_user",$data);
        }
    }
	
	/*
	delete user
	@param user_id for user's id
	@return redirect to user's list
	*/
	
    function delete_user($user_id){
        if(_is_user_login($this)){
            $data = array();
            $this->load->model("users_model");
            $user  = $this->users_model->get_user_by_id($user_id);
             $this->logs_model->set_log($this,"users","Delete","User is Deleted successfully");
            if(!empty($user)){
            $this->common_model->data_remove($this->table_name,array("user_id"=>$user->user_id),true);
                redirect("admin/listuser/".$user->user_type_id);
            }
        }
    }
    
}
?>