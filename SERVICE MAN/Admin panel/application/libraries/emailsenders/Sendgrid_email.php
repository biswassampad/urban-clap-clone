<?php   defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'third_party/sendgrid-php/vendor/autoload.php';

//include_once "swift_required.php";

class Sendgrid_email 
{
    public function __construct()
    {
        
    }
    /**
     * send email
     * @param  array("email"=>"name","email"=>"name") $to
     * @param  string $subject
     * @param  string $message
     * @param  array(array("path"=>"a/abc.pdf","type"=>"application/pdf"))
     * @return boolean
     */
    public function send($to,$subject,$message,$attachments = array()){
        
        
        $options = get_options(array("email_sender","sendgrid_api_key","site_name"));
         if($options["sendgrid_api_key"] == "")
            return false;
        
        $sendgrid = new \SendGrid($options["sendgrid_api_key"]);
        
        $email = new \SendGrid\Mail\Mail();
         
        $email->setFrom($options["email_sender"],$options["site_name"]);
        $email->setSubject($subject);
        $email->addContent("text/plain", strip_tags($message));

        // $to in in formate of array("email"=>"name","email"=>"name")
        $email->addTos($to);
        $email->addContent(
            "text/html", $message
        );
        if(!empty($attachments)){
            foreach($attachments as $file){
                $filename = basename($file["path"]);
    $file_encoded = base64_encode(file_get_contents($file["path"]));
    $attachment = new \SendGrid\Mail\Attachment();
    $attachment->setType($file["type"]);
    $attachment->setContent($file_encoded);
    $attachment->setDisposition("attachment");
    $attachment->setFilename($filename);
    $email->addAttachment($attachment);
                
            }    
        }
        try {
            //print_r($email);
            $response = $sendgrid->send($email);
            if( $response->statusCode() == 200 || $response->statusCode() == 202){
                return true;
            }else{
                return $response->statusCode();
            }
        } catch (Exception $e) {
            return false;
        }
    }
}