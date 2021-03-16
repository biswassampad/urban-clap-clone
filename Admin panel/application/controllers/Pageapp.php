<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pageapp extends MY_Controller {
     protected $controller;
    protected $table_name;
    public function __construct()
    {
         parent::__construct();
                $this->controller = $this->router->fetch_class();
                $this->table_name = "pageapp";
         if(_is_user_login($this)){
                        
         }else{
            redirect("login");
            exit();
         }   
    }
    
/* ========== pageapp ==========*/
/*
app pages
@return app pages
*/
public function index(){
        
        $data["pageapp"]  = $this->pageapp_model->get_pageapp();
        $this->load->view($this->controller."/list",$data);    
} 

/*
add app page
@return add page
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
action
@param pg_title for page title
@param pg_descri for page description
@param pg_status for page status
@param pg_foot for page on footer 
@return response for true/false
@return error for error message
*/	
  private function action(){
        $post = $this->input->post();
                $this->load->library('form_validation'); 
                 $this->form_validation->set_rules('pg_title', 'Title', 'trim|required');
                $this->form_validation->set_rules('pg_descri', 'pg_descri', 'trim|required'); 
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
        		  $pgslug = url_title($this->input->post('pg_title'), 'dash', TRUE);
                    $addcat = array(
                            "pg_title"=>$post["pg_title"],
                            "pg_slug"=>$pgslug,
                            "pg_descri"=>$post["pg_descri"],
                            "pg_status"=>$post["pg_status"],
                            "pg_foot" =>$post["pg_foot"]
                            );

                    $responce[RESPONCE] = true;
                    if(!empty($post["id"])){
                        $this->common_model->data_update($this->table_name,$addcat,array("id"=>$post["id"]));
                        $this->message_model->action_mesage("update",$this->controller,false);
                        redirect($this->controller);
                    }else{
                        $cat_id = $this->common_model->data_insert($this->table_name,$addcat);    
                         
                        $this->message_model->action_mesage("add",$this->controller,false);
                        redirect($this->controller);
                    }        
                    
               	}
    }
	/*
	edit page 
	@param id for page's id
	@return edit page
	*/
     public function edit($id){
        if(_is_user_login($this)){
                
                $this->action();
				
                $data["field"] = $this->pageapp_model->get_page_by_id($id);
                $this->load->view($this->controller."/add",$data);
               
          }
                //echo json_encode($responce);
    }
	/*
	delete page
	@param id for page's id
	@return redirect to app pages
	*/
	public function deletepage($id){
		$cat = $this->pageapp_model->get_page_by_id($id);
		if(!empty($cat)){
			$this->common_model->data_remove($this->table_name,array("id"=>$cat->id),true);
			redirect($this->controller);
		}
	}
  
}
