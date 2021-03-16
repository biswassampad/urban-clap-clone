<?php 
defined('BASEPATH') or exit('No direct script access allowed');
        
class Email_model extends CI_Model{
    
    /**
     * send email
     * @param  array("email"=>"name","email"=>"name") $to
     * @param  string $subject
     * @param  string $message
     * @param  array(array("path"=>"a/abc.pdf","type"=>"application/pdf")) 
     * @return boolean
     */
    function send_email($to,$subject,$message,$attachments = array()){
        $email_provider = get_option("email_provider");
        $status = false;
        
        $config['charset'] = "utf-8";
        $config['mailtype'] = "html";
        $config['newline'] = "\r\n";
        $config['wordwrap'] = true;
        
        $sender = get_option("email_sender");
        if($sender == "")
            return false;
                            
        switch($email_provider){
            case "php_mail":{
                
                $this->load->library('email');
                $this->email->initialize($config);
                $this->email->from($sender);
                $this->email->reply_to($sender);
                $this->email->to($to);
                $this->email->subject($subject);        
                $this->email->message($message);
                if(!empty($attachments)){
                    foreach($attachments as $files){
                        $this->email->attach($files["path"]);
                    }
                }
                if (!$this->email->send())
                {
                    $status = true;
                } else
                {
                    $status = false;
                }
                return $status;
                break;
            }case "smtp_mail":{
                $config['protocol'] = "smtp";
                $config['smtp_crypto'] = get_option("smtp_crypto");
                $config['smtp_host'] = get_option("smtp_host");
                $config['smtp_port'] = get_option("smtp_port");
                $config['smtp_user'] = get_option("smtp_user");
                $config['smtp_pass'] = get_option("smtp_pass");
                
                $this->load->library('email');
                $this->email->initialize($config);
                $this->email->from($sender);
                $this->email->reply_to($sender);
                $this->email->to($to);
                $this->email->subject($subject);        
                $this->email->message($message);
                if(!empty($attachments)){
                    foreach($attachments as $files){
                        $this->email->attach($files["path"]);
                    }
                }
                if (!$this->email->send())
                {
                    $status = true;
                } else
                {
                    $status = false;
                }

                return $status;                
                break;
            }
            case "sendgrid":{
                $this->load->library("emailsenders/sendgrid_email");
				$to=(is_array($to))?$to:array($to=>$to);
                $status = $this->sendgrid_email->send($to,$subject,$message,$attachments);
				return $status;
                break;
            }
        }
    }
    
    
}