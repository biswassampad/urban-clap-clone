<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	/*
	 login page 
	 @param email for user's email address
	 @param password for user's password
	 @return login page or redirect to dashboard
	*/
	public function index()
	{
	    if(_is_user_login($this)){
            redirect(_get_user_redirect($this));
        }else{
            $this->load->helper('cookie');
            $data = array("error"=>"");       
            
            if(isset($_POST))
            {
                
                $this->load->library('form_validation');
                
                $this->form_validation->set_rules('email', 'Email', 'trim|required');
                $this->form_validation->set_rules('password', 'Password', 'trim|required');
                if ($this->form_validation->run() == FALSE) 
        		{
            		  if($this->form_validation->error_string()!=""){
  		                $this->message_model->error($this->form_validation->error_string());
                    }
                    
        		}else
                {
                   
                    $q = $this->db->query("Select * from `users` where (`user_email`='".$this->input->post("email")."') and user_password='".md5($this->input->post("password"))."' and user_type_id != '".USER_APP."' Limit 1");
                    
                   // print_r($q) ; 
                    if ($q->num_rows() > 0)
                    {
                        $row = $q->row(); 
                        if($row->user_status == "0")
                        {
                            
          		                $this->message_model->error($this->lang->line("msg_account_active"));
                            
                        }else if($row->is_email_varified == "0")
                        {
                                $this->message_model->error($this->lang->line("msg_verify_email_first"));
                        }
                        else
                        {
                             $rememberme = $this->input->post('remember');
                           
                            if( isset($rememberme) && $rememberme == "on" ) {
                                
                                set_cookie("c_email",$this->input->post("email"),'3600' );
                                set_cookie("c_password",$this->input->post("password"),'3600');
                               set_cookie("remiber_me",$rememberme,'3600');
                            }else{
                                delete_cookie('c_email');
                                delete_cookie('c_password');
                                   delete_cookie("remiber_me");
                            }
                            $newdata = array(
                                                   'user_name'  => $row->user_fullname,
                                                   'user_email'     => $row->user_email,
                                                   'logged_in' => TRUE,
                                                   'user_id'=>$row->user_id,
                                                   'user_type_id'=>$row->user_type_id,
                                                   'user_image'=>$row->user_image
                                                  );
                            $this->session->set_userdata($newdata);
                            redirect(_get_user_redirect($this));
                         
                        }
                    }
                    else
                    {
                        $this->message_model->error($this->lang->line("msg_correct_user_n_password"));
                    }
                   
                    
                }
            }
            $data["active"] = "login";
            $data["site_name"] = get_option("site_name");
            $this->load->view('login',$data);
        }   
		
	}
}
