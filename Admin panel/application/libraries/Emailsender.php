<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Email Send
 * This will send all system emails
 **/
class Emailsender
{
    private $_CI;
    public function __construct()
    {
        // Get the CodeIgniter reference
        $this->_CI = &get_instance();
        $this->_CI->load->model('email_model');

        
    }
    public function init(){
        
    }
	/*
	send booking email
	@param id for appointment id
	@param array_to for list of send email addresses
	@return send mail true/false
	*/
    public function send_booking_email($id,$array_to = array())
    {
        $this->init();        
        $data["details"] = $this->_CI->appointment_model->get_details($id);
        $data["appointment_items"] = $this->_CI->appointment_model->get_appointment_items($id); 
        $data["appointment_extra_item"] = $this->_CI->appointment_model->get_appointment_extra_items($id); 
        $message = $this->_CI->load->view("emails/email_template_appointment_book",$data,true);
        return $this->_CI->email_model->send_email($array_to,"New Appointment #".$data["details"]->id,$message);
    }
	
	/*
	send forget password mail
	@param user_id for user's id
	@param user_email for user's email
	@return send mail true/false
	*/
    public function send_forgotpassword_email($user_id,$user_email)
    {
        $this->init();
        $token = uniqid(uniqid());
        $this->_CI->common_model->data_update("users",array("varified_token"=>$token),array("user_id"=>$user_id)); 
        $message = $this->_CI->load->view("emails/email_template_forgot_password",array("token"=>$token),true);
        return $this->_CI->email_model->send_email($user_email,'Forgot password request',$message);
        
    }
	
	/*
	send assign email
	@param id for appointment id
	@param array_to for email addresses
	@return send mail true/false
	*/
	public function send_assign_email($id,$array_to = array())
	{
		$this->init();        
        $data["details"] = $this->_CI->appointment_model->get_details($id);
        $data["appointment_items"] = $this->_CI->appointment_model->get_appointment_items($id); 
        $data["appointment_extra_item"] = $this->_CI->appointment_model->get_appointment_extra_items($id); 
        $message = $this->_CI->load->view("emails/email_template_appointment_assign",$data,true);
        return $this->_CI->email_model->send_email($array_to,"Appointment Assign #".$data["details"]->id,$message);
	}
	
	/*
	send start email
	@param id for appointment id
	@param array_to for email addresses
	@return send mail true/false
	*/
	public function send_start_email($id,$array_to = array())
	{
		$this->init();        
        $data["details"] = $this->_CI->appointment_model->get_details($id);
        $data["appointment_items"] = $this->_CI->appointment_model->get_appointment_items($id); 
        $data["appointment_extra_item"] = $this->_CI->appointment_model->get_appointment_extra_items($id); 
        $message = $this->_CI->load->view("emails/email_template_appointment_start",$data,true);
        return $this->_CI->email_model->send_email($array_to,"Appointment Start Work #".$data["details"]->id,$message);
	}
	
	/*
	send completed and paid email
	@param id for appointment id
	@param array_to for email addresses
	@return send mail true/false
	*/
	public function send_complete_email($id,$array_to = array())
	{
		$this->init();        
        $data["details"] = $this->_CI->appointment_model->get_details($id);
        $data["appointment_items"] = $this->_CI->appointment_model->get_appointment_items($id); 
        $data["appointment_extra_item"] = $this->_CI->appointment_model->get_appointment_extra_items($id); 
        $message = $this->_CI->load->view("emails/email_template_appointment_complete_paid",$data,true);
        return $this->_CI->email_model->send_email($array_to,"Appointment Completed and Paid #".$data["details"]->id,$message);
	}
	
	/*
	send completed and paid email
	@param id for appointment id
	@param array_to for email addresses
	@return send mail true/false
	*/
	public function send_cancel_email($id,$array_to = array())
	{
		$this->init();        
        $data["details"] = $this->_CI->appointment_model->get_details($id);
        $data["appointment_items"] = $this->_CI->appointment_model->get_appointment_items($id); 
        $data["appointment_extra_item"] = $this->_CI->appointment_model->get_appointment_extra_items($id); 
        $message = $this->_CI->load->view("emails/email_template_appointment_cancel",$data,true);
        return $this->_CI->email_model->send_email($array_to,"Appointment Cancel #".$data["details"]->id,$message);
	}
	
    /*
	send for new registered pros
	@param email for pros login email
	@param password for password
	@return send mail true/false
	*/
    public function send_new_pros_email($email,$password)
    {
        $this->init();
        $message = $this->_CI->load->view("emails/email_template_pros_login_details",array("email"=>$email,"password"=>$password),true);
        return $this->_CI->email_model->send_email($email,'Your Pros Login',$message);
        
    }
    
}

?>