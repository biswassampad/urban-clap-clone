<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notification extends MY_Controller {
    public function __construct()
    {
                parent::__construct();
                // Your own constructor code
                $this->load->database();
                $this->load->helper('login_helper'); 
                if(_is_user_login($this)){
                        
                }else{
                    redirect("login");
                    exit();
                }
    }
    
/* ========== Notification ==========*/
	/*
	send notification page
	@param descri for description/message
	@param subject for subject/title
	@return redirect to notification page
	*/
    function index()
    { 
        if(_is_user_login($this))
        {
            if(isset($_POST))
            {
                $this->load->library('form_validation');
                $this->form_validation->set_rules('descri', 'Description', 'trim|required');
                if ($this->form_validation->run() == FALSE)
                {
                    if($this->form_validation->error_string()!="")
                    $this->session->set_flashdata("message", '<div class="alert alert-warning alert-dismissible" role="alert">
                                        <i class="fa fa-warning"></i>
                                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                      <strong>Warning!</strong> '.$this->form_validation->error_string().'
                                    </div>');
                }
                else
                {
                    $message["title"] = $this->input->post("subject");
                    $message["message"] = $this->input->post("descri");
                    if($_FILES["file"]["size"] > 0){
                            $config['upload_path']          = './uploads/notification/';
                            if(!file_exists($config['upload_path'])){
                                mkdir($config['upload_path']);
                            }
                            $config['allowed_types']        = 'gif|jpg|png|jpeg';
                            $this->load->library('upload', $config);
            
                            if ( ! $this->upload->do_upload('file'))
                            {
                                    $error = array('error' => $this->upload->display_errors());
                            }
                            else
                            {
                                $img_data = $this->upload->data();
                                $message["image"] = base_url("uploads/notification/".$img_data['file_name']);
                            }
                            
                    }
                    $message["created_at"] = date("Y-m-d h:i:s");  
                            
                    $this->load->helper('gcm_helper');
                    $gcm = new GCM();   
                    //$result = $gcm->send_topics("/topics/rabbitapp",$message ,"ios"); 
                    $topic = "/topics/".get_option("fcm_topic","servpro")   ;     
                    $result = $gcm->send_topics($topic,$message ,"android");
                            
                    $q = $this->db->query("Select user_ios_token from users");
                    $registers = $q->result();
					$registatoin_ids=array();
                    foreach($registers as $regs)
                    {
                        if($regs->user_ios_token!="")
                            $registatoin_ids[] = $regs->user_ios_token;
                    }
                    if(count($registatoin_ids) > 1000)
                    {
                        $chunk_array = array_chunk($registatoin_ids,1000);
                        foreach($chunk_array as $chunk)
                        {
                            $result = $gcm->send_notification($chunk, $message,"ios");
                        }
                    }
                    else
                    {
                        
                    }  
                    redirect("notification");
                }
                $this->load->view("notification/add");
            }
        }
    } 
}
