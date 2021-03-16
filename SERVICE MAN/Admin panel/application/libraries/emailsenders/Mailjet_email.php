<?php   defined('BASEPATH') or exit('No direct script access allowed');
include APPPATH . 'vendor/autoload.php';
use \Mailjet\Resources;

class Mailjet_email 
{
    public function __construct()
    {
        
    }
    
    function send($to,$subject,$message){
        
        $options = get_options(array("email_sender","mailjet_api_key","mailjet_secret_key","site_name"));
        if($options["mailjet_secret_key"] == "" || $options["mailjet_api_key"] == "")
            return false;
        
        
        $mj = new \Mailjet\Client($options["mailjet_api_key"], $options["mailjet_secret_key"],
                      true,array('version' => 'v3.1'));
        
        $from = array('Email'=>$options["email_sender"],'Name'=>$options["site_name"]);
        $to_array = array();
        foreach($to as $k=>$t){
            $to_array[] = array('Email'=>$k,'Name'=>$t);    
        }              
        $body = array(
            'Messages' => array(
                array(
                    'From' => $from,
                    'To' => $to_array,
                    'Subject' => $subject,
                    'HTMLPart' => $message
                )
            )
        );
        $response = $mj->post(Resources::$Email, array('body' => $body));
        //$response->success() && var_dump($response->getData());
        if($response->success()){
            return true;
        }
        return false;
    }
}
?>