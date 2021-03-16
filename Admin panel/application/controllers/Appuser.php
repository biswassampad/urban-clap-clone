<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Appuser extends MY_Controller {
     protected $controller;
    protected $table_name;
    public function __construct()
    {
         parent::__construct();
                $this->controller = $this->router->fetch_class();
                $this->table_name = "users";
         if(_is_user_login($this)){
                        
         }else{
            redirect("login");
            exit();
         }         
    }  
    
	/*
	Appuser page
	@return appuser's list
	*/
	public function index(){
        $data["offer"]  = $this->appuser_model->get_user();
        $this->load->view($this->controller."/list",$data);    
	} 
  
}
