<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Services extends MY_Controller {
    protected $controller;
    protected $table_name;
    public function __construct()
    {
                parent::__construct();
                $this->controller = $this->router->fetch_class();
                $this->table_name = "services";
                if(_is_user_login($this)){
                        
                }else{
                    redirect("login");
                    exit();
                }
    }
    /*
	services page
	@return list of services page
	*/
    public function index(){
        
        $data["data"] = $this->services_model->get();
        $this->load->view($this->controller."/list",$data);
    }
    
	/*
	delete service
	@param 
	*/
    public function delete($id){
        $cat = $this->services_model->get_by_id($id);
        if(!empty($cat)){
            $this->common_model->data_remove($this->table_name,array("id"=>$cat->id),true);
            $data[RESPONCE] = true;
            $data[MESSAGE] = $this->message_model->action_mesage("delete",$this->controller,true);
            echo json_encode($data);
        }else{
			
            $data[RESPONCE] = false;
            $data[ERROR] = $this->controller." not available";
            echo json_encode($data);
        }
    }
	/*
	add service 
	@return add service page
	*/
    public function add(){
        if(_is_user_login($this)){
                
                $this->action();
                $data["field"] = $this->input->post();
                $data["categories"] = $this->category_model->get_categories();
				$this->load->view($this->controller."/add",$data);
          }
    }
	
	/*
	action service
	@param service_title for service title
	@param service_price for service price
	@param service_approxtime for approx time of the service
	@param service_discount for service discount
	@param cat_id for category id which service belongs
	@param service_icon for service iconv
	@param id for update service's service id
	@return redirect to service page
	*/
    private function action(){
        $post = $this->input->post();
                $this->load->library('form_validation');
                
				$this->form_validation->set_rules('service_title', 'Title', 'trim|required');
                $this->form_validation->set_rules('service_price', 'Price', 'trim|required');
                $this->form_validation->set_rules('service_approxtime', 'Time', 'trim|required');
                
                if ($this->form_validation->run() == FALSE)
        		{
     			    if($this->form_validation->error_string()!="")
                    {
                           $this->message_model->error($this->form_validation->error_string(),false);
                    }
        		}
        		else
        		{
                    $addcat = array(
                            "service_title"=>$post["service_title"],
                            "service_price"=>$post["service_price"],
                            "service_discount"=>$post["service_discount"],
                            "service_approxtime"=>$post["service_approxtime"],
                            "cat_id"=>$post["cat_id"]
                            );
                            if(isset($_FILES["service_icon"]) && $_FILES['service_icon']['size'] > 0){
                                    $path = SERVICES_PATH;
                                    
                                    if(!file_exists($path)){
                                        mkdir($path);
                                    }
                                    $this->load->library("imagecomponent");
                                        
                                    $file_name_temp = md5(uniqid())."_".$_FILES['service_icon']['name'];
                                    $file_name = $this->imagecomponent->upload_image_and_thumbnail('service_icon',200,100,$path ,'crop',true,$file_name_temp);
                                    $addcat["service_icon"] = $file_name;
                                }

                    if(!empty($post["id"])){
                        $this->common_model->data_update($this->table_name,$addcat,array("id"=>$post["id"]));
                        $this->message_model->action_mesage("update",$this->controller,false);
                        redirect($this->controller);
                    }else{
						if(isset($_FILES["service_icon"]) && $_FILES['service_icon']['size'] == 0){
							$this->message_model->error("Service image must required!");	
						}
						else
						{
							$id = $this->common_model->data_insert($this->table_name,$addcat);    
							 
							$this->message_model->action_mesage("add",$this->controller,false);
							redirect($this->controller);
						}
                    }        
                    
               	}
    }
	/*
	edit service 
	@return edit service page
	*/
    public function edit($id){
        if(_is_user_login($this)){
                
                $this->action();
				
                $data["field"] = $this->services_model->get_by_id($id);
                $data["categories"] = $this->category_model->get_categories();
                $this->load->view($this->controller."/add",$data);
          }
    
    }
}
?>