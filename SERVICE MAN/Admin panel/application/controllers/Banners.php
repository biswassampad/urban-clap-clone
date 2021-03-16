<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Banners extends MY_Controller {
    protected $controller;
    protected $table_name;
    public function __construct()
    {
                parent::__construct();
                $this->controller = $this->router->fetch_class();
                $this->table_name = "banners";
                if(_is_user_login($this)){
                        
                }else{
                    redirect("login");
                    exit();
                }
                $this->load->model("banners_model");
    }
	/*
	banner page
	@return banner page
	*/
    public function index(){
        
        $data["data"] = $this->banners_model->get_categories();
        $this->load->view($this->controller."/list",$data);
    }
	
	/*
	delete banner by id
	@param id for banner id
	@return responce true/false
	@return message for success message
	@return error for error message
	*/
    public function delete($id){
        $cat = $this->banners_model->get_by_id($id);
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
	add banner 
	@return add banner page
	*/
    public function add(){
        if(_is_user_login($this)){
                
                $this->action();
                $data["field"] = $this->input->post();
                $data["categories"] = $this->category_model->get_categories();
                $this->load->view($this->controller."/add",$data);
          }
                //echo json_encode($responce);
    }
	
	/*
	action post data
	@param title for banner title
	@param description for banner type 
	@param image for banner image
	@return response for true/false
	@return error for error message
	*/
    private function action(){
                $post = $this->input->post();
                $this->load->library('form_validation');
                $this->form_validation->set_rules('title', 'Title', 'trim|required');
                $this->form_validation->set_rules('type', 'Type', 'trim|required');
                if(isset($post["type"]) && $post["type"] == "link"){
                    $this->form_validation->set_rules('type_value', 'Link', 'trim|required');
                }
                $responce = array();
                if ($this->form_validation->run() == FALSE)
        		{
     			    if($this->form_validation->error_string()!="")
                    {
                            $responce[RESPONCE] = false;
                            $responce[ERROR] = $this->message_model->error($this->form_validation->error_string(),true);
                    }
        		}
        		else
        		{
                            $type_value = ($post["type"] == "link") ? $post["type_value"] : $post["category_id"]; 
                            $addcat = array(
                            "title"=>$post["title"],
                            "type"=>$post["type"],
                            "type_value"=>$type_value
                            );
                            if(isset($_FILES["image"]) && $_FILES['image']['size'] > 0){
                                    $path = BANNER_PATH;
                                    
                                    if(!file_exists($path)){
                                        mkdir($path);
                                    }
                                    $this->load->library("imagecomponent");
                                        
                                    $file_name_temp = md5(uniqid())."_".$_FILES['image']['name'];
                                    $file_name = $this->imagecomponent->upload_image_and_thumbnail('image',680,200,$path ,'crop',true,$file_name_temp);
                                    $addcat["image"] = $file_name;
                            }

                    $responce[RESPONCE] = true;
                    if(!empty($post["id"])){
                        $this->common_model->data_update($this->table_name,$addcat,array("id"=>$post["id"]));
                        $this->message_model->action_mesage("update",$this->controller,false);
                        redirect($this->controller);
                    }else{
						if(isset($_FILES["image"]) && $_FILES['image']['size'] == 0){
							$this->message_model->error("Banner image must required!");
							//redirect($this->controller);		
						}
						else
						{
							$cat_id = $this->common_model->data_insert($this->table_name,$addcat);    
							 
							$this->message_model->action_mesage("add",$this->controller,false);
							redirect($this->controller);	
						}
                        
                    }        
                    
               	}
    }
	/*
	edit banner 
	@param id for banner id
	@return banner edit page
	*/
    public function edit($id){
        if(_is_user_login($this)){
                
                $this->action();
                $data["field"] = $this->banners_model->get_by_id($id);
                $data["categories"] = $this->category_model->get_categories();
                $this->load->view($this->controller."/add",$data);
          }
                //echo json_encode($responce);
    }
}
?>