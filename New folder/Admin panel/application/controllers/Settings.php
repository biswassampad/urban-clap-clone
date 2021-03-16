<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends MY_Controller {
    public function __construct()
    {
                parent::__construct();
                // Your own constructor code
                
    }
	/*
	site setting page
	@param site_name for site name
	@param email_id for email address
	@param default_timezone for default timezone
	@param date_default_timezone for default timezone
	@param per_day_appointment for per day appointment
	@return setting page
	*/
    public function site(){
        if(_is_user_login($this)){
            if($_POST){
                $post = $this->input->post();
                add_options($post,"site_settings",true,true);
            }
            
            $setting = get_options(array("site_name","email_id","default_country","default_timezone","date_default_timezone","dateformat","per_day_appointment","currency"));
            $data["setting"] = $setting;
            
            
            //$data["countries"] = $this->area_model->get_countries();
            $data["time_zones"] = get_timezones_list();
            $keys = array_keys($data["time_zones"]);
            $default_timezoe = $keys[0];
            if(isset($setting["default_timezone"]) && $setting["default_timezone"] != ""){
                $default_timezoe = $setting["default_timezone"];
            }
            $data["date_time_zone"] = $data["time_zones"][$default_timezoe];
        
            $this->load->view("settings/site",$data);
        }
    }
	/*
	email setting
	@param smtp_host for smtp host name
	@param smtp_port for smtp port
	@param smtp_user for smtp user
	@param smtp_pass for smtp password
	@param mail_type for mail type
	@param email_sender for email sender
	@param email_provider for email provider
	@param sendgrid_api_key for sendgrid api key
	@return settings email page
	*/
    public function email(){
        if(_is_user_login($this)){
            
            if($_POST){
                $post = $this->input->post();
                add_options($post,"email_settings",true,true);
            }
            
            
            $setting = get_options(array(
            "smtp_host",
            "smtp_port",
            "smtp_user",
            "smtp_pass",
            "mail_type",
            "email_on_appointment_book",
            "email_on_appointment_status",
            "email_provider",
            "email_sender",
            "mandrill_api_key",
            "sendgrid_api_key",
            "mailjet_api_key",
            "mailjet_secret_key",
            "elasticmail_api_key"));
            $data["setting"] = $setting;
            
            
        
            $this->load->view("settings/email",$data);
        }
    }
    
    /*
	fcm settings
	@param fcm_server_key for fcm server key
	@param fcm_topic for topic name
	@param fcm_enable for enable fcm
	@return fcm setting page
	*/
    public function fcm(){
        if(_is_user_login($this)){
            
            if($_POST){
                $post = $this->input->post();
                add_options($post,"notification_settings",true,true);
            }
            
            
            $setting = get_options(array(
            "notification_provider",
            "fcm_server_key",
            "fcm_enable",
            "onesignal_enable",
            "onesignal_app_id",
            "onesignal_api_key"));
            $data["setting"] = $setting;
            
            
        
            $this->load->view("settings/fcm",$data);
        }
    }
    
}
?>