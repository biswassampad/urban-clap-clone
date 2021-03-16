<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';
class Settings extends REST_Controller {
    function __construct()
    {
        // Construct the parent class
        parent::__construct();

    }
	/*
	get setting option list or get setting single key value
	@param key for setting key option's value
	@return response for true/false
	@return message for error message
	@return data for single key setting or list of setting's options
	*/
    function list_post(){
        $options = array();
        
        $key = $this->post("key");
        if($key === NULL)
        {
            $options = get_options_by_type("site_settings");
        }else{
            $options = get_option($key);
        }
            if(!empty($options)){
                $this->response(array(
                RESPONCE => true,"data"=>$options), REST_Controller::HTTP_OK);
           }else{
               $this->response(array(
                        RESPONCE => false,
                        MESSAGE => 'No record were found'
                    ), REST_Controller::HTTP_OK); 
           }
    }
}
?>