<?php   defined('BASEPATH') or exit('No direct script access allowed');
include APPPATH . 'vendor/autoload.php';
include_once "swift_required.php";

class Maindrill_email 
{
    public function __construct()
    {
        
    }
    public function send($to,$subject,$message){
        $options = get_options(array("email_sender","mandrill_username","mandrill_password","site_name"));
        
        
        $from = array($options["email_sender"] =>$options["site_name"]);
        
        
        $html = $message;
        
        $transport = Swift_SmtpTransport::newInstance('smtp.mandrillapp.com', 587);
        $transport->setUsername($options["mandrill_username"]);
        $transport->setPassword($options["mandrill_password"]);
        $swift = Swift_Mailer::newInstance($transport);
        
        $message = new Swift_Message($subject);
        $message->setFrom($from);
        $message->setBody($html, 'text/html');
        $message->setTo($to);
        //$message->addPart($text, 'text/plain');
        
        if ($recipients = $swift->send($message, $failures))
        {
            return true;
        } else {
            return false;
        }
    }
}