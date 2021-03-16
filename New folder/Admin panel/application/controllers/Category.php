<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends MY_Controller {
    protected $controller;
    protected $table_name;
    public function __construct()
    {
                parent::__construct();
                $this->controller = $this->router->fetch_class();
                $this->table_name = "categories";
                if(_is_user_login($this)){
                        
                }else{
                    redirect("login");
                    exit();
                }
    }
	/*
	category page
	@return category page
	*/
    public function index(){
        
        $data["data"] = $this->category_model->get_categories();
        $this->load->view($this->controller."/list",$data);
    }
	
	/*
	delete category by id
	@param id for category id
	@return responce true/false
	@return message for success message
	@return error for error message
	*/
    public function delete($id){
        $cat = $this->category_model->get_category_by_id($id);
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
	add category 
	@return add category page
	*/
    public function add(){
        if(_is_user_login($this)){
                
                $this->action();
                $data["field"] = $this->input->post();
                $this->load->view($this->controller."/add",$data);
          }
                //echo json_encode($responce);
    }
	
	/*
	action post data
	@param title for category title
	@param description for category description 
	@param image for category image
	@return response for true/false
	@return error for error message
	*/
    private function action(){
        $post = $this->input->post();
                $this->load->library('form_validation');
                $this->form_validation->set_rules('title', 'Title', 'trim|required');
                
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
                    $addcat = array(
                            "title"=>$post["title"],
                            "description"=>$post["description"]
                          //  "image"=>$post["image"]
                            );
                            if(isset($_FILES["image"]) && $_FILES['image']['size'] > 0){
                                    $path = CATEGORY_PATH;
                                    
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
							$this->message_model->error("Category image must required!");
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
	edit category 
	@param id for category id
	@return category edit page
	*/
    public function edit($id){
        if(_is_user_login($this)){
                
                $this->action();
                $data["field"] = $this->category_model->get_category_by_id($id);
                $this->load->view($this->controller."/add",$data);
          }
                //echo json_encode($responce);
    }
}
?>