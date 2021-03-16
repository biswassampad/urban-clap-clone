<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pros extends MY_Controller {
    protected $controller;
    protected $table_name;
    public function __construct()
    {
                parent::__construct();
                $this->controller = $this->router->fetch_class();
                $this->table_name = "pros";
                if(_is_user_login($this)){
                        
                }else{
                    redirect("login");
                    exit();
                }
    }
	/*
	dashboard 
	@return pros dashboard
	*/
    public function dashboard(){
        $user_type_id = _get_current_user_type_id($this);
        $user_id = _get_current_user_id($this);
        $params = array();
        $params["appointment_date"] = date("Y-m-d");
        if($user_type_id == USER_PROS){
            $params["appointment.pros_id"] = $user_id;
        
        $data["data"] = $this->appointment_model->get($params);
        $data["pending"] =  $this->appointment_model->get_counts(array("status"=>STATUS_ASSIGNED,"appointment.pros_id"=>$user_id));
        $data["completed"] =  $this->appointment_model->get_counts(array("status"=>STATUS_COMPLETED,"appointment.pros_id"=>$user_id));
        $data["user_count"] =  $this->users_model->get_users_counts(USER_APP);
        
        $this->load->view($this->controller."/dashboard",$data);
        
        }
    }
	/*
	pros page
	@return list of pros page
	*/
    public function index(){
        
        $data["data"] = $this->pros_model->get();
        $this->load->view($this->controller."/list",$data);
    }
    
	/*
	delete pros
	@param id for pros id
	@return response for true/false
	@return message for success message
	@return error for error message
	*/
    public function delete($id){
        $cat = $this->pros_model->get_by_id($id);
        if(!empty($cat)){
			$this->common_model->data_remove($this->table_name,array("id"=>$cat->id),true);
			$this->common_model->data_remove("users",array("user_id"=>$cat->id),true);
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
	add pros
	@return add pros page
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
	pros page action
	@param pros_name for pros name
	@param user_email for user's email
	@param user_password for user's password
	@param pros_degree for pros degree
	@param pros_exp for pros experience
	@param working_hour_start for working start hour
	@param working_hour_end for working end hour
	@param pros_photo for pros photo
	@param pros_id_proof for pros id proof
	@param cat_id for pros related categories ids
	@param id for update pros record
	@return redirect to pros page
	*/
    private function action(){
        $post = $this->input->post();
                $this->load->library('form_validation');
                
				$this->form_validation->set_rules('pros_name', 'Title', 'trim|required');
                if(empty($post["id"])){	
					$this->form_validation->set_rules('user_email', 'Email', 'trim|required|valid_email|is_unique[users.user_email]');
					$this->form_validation->set_rules('user_password', 'Password', 'trim|required');
				}else{
				    
				    $pros = $this->pros_model->get_by_id($post["id"]);
                    if(empty($pros))
                    {
                        echo _l("Pros not found go back");
                        exit();
                    }
                    if($pros->user_email != $post["user_email"]) 
				        $this->form_validation->set_rules('user_email', 'Email', 'trim|required|valid_email|is_unique[users.user_email]');
				}
                
                $this->form_validation->set_message('is_unique', 'Email address is already register');
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
                            "pros_name"=>$post["pros_name"],
                            "pros_email"=>$post["user_email"],
                            "pros_degree"=>$post["pros_degree"],
                            "pros_exp"=>$post["pros_exp"],
                            "working_hour_start"=>$post["working_hour_start"],
                            "working_hour_end"=>$post["working_hour_end"]
                            );
							$invalidImage1=0;
							$invalidImage2=0;
							
                                if(isset($_FILES["pros_photo"]) && $_FILES['pros_photo']['size'] > 0 && ($_FILES['pros_photo']['type']=="image/jpeg" || $_FILES['pros_photo']['type']=="image/jpg" || $_FILES['pros_photo']['type']=="image/png")){
                                    $path = PROS_PATH;
                                    
                                    if(!file_exists($path)){
                                        mkdir($path);
                                    }
                                    $this->load->library("imagecomponent");
                                        
                                    $file_name_temp = md5(uniqid())."_".$_FILES['pros_photo']['name'];
                                    $file_name = $this->imagecomponent->upload_image_and_thumbnail('pros_photo',320,150,$path ,'crop',true,$file_name_temp);
                                    $addcat["pros_photo"] = $file_name;
                                }
								else
								{
									$invalidImage1=1;
								}
                                if(isset($_FILES["pros_id_proof"]) && $_FILES['pros_id_proof']['size'] > 0 && ($_FILES['pros_id_proof']['type']=="image/jpeg" || $_FILES['pros_id_proof']['type']=="image/jpg" || $_FILES['pros_id_proof']['type']=="image/png")){
                                    $path = PROS_PATH;
                                    
                                    if(!file_exists($path)){
                                        mkdir($path);
                                    }
                                    $this->load->library("imagecomponent");
                                        
                                    $file_name_temp = md5(uniqid())."_".$_FILES['pros_id_proof']['name'];
                                    $file_name = $this->imagecomponent->upload_image_and_thumbnail('pros_id_proof',680,200,$path ,'crop',true,$file_name_temp);
                                    $addcat["pros_id_proof"] = $file_name;
                                }
								else
								{
									$invalidImage2=1;
								}
					
						if(!empty($post["id"])){
							if(USER_ADMIN == _get_current_user_type_id($this)){
								$user_update = array("user_email"=>$post["user_email"]);
								if($post["user_password"] != $pros->user_password){
									$user_update["user_password"] = md5($post["user_password"]);
								}
								$this->common_model->data_update("users",$user_update,array("user_id"=>$pros->id));
							}
							$this->common_model->data_update($this->table_name,$addcat,array("id"=>$post["id"]));
							
							$id = $this->input->post("id");          
							$cat_id = $this->input->post("cat_id");
							$this->db->delete("pros_category_rel",array("pros_id"=>$id));
							if(!empty($cat_id))
							{
								foreach($_POST["cat_id"] as $key=>$val)
								{
									$this->db->insert("pros_category_rel",array("cat_id"=>$val, "pros_id"=>$id));
								}
							}
							
							$this->message_model->action_mesage("update",$this->controller,false);
							redirect($this->controller);
						}else{
							if($invalidImage1==0 && $invalidImage2==0)
							{
								if(isset($_FILES["pros_photo"]) && $_FILES['pros_photo']['size'] == 0){	
									$this->message_model->error("Profile Image Must Required!");
								}
								else if(isset($_FILES["pros_id_proof"]) && $_FILES['pros_id_proof']['size'] == 0){
									$this->message_model->error("Pros Id Proof must required!");
								}
								else
								{
									$user_id =  $this->common_model->data_insert("users",
											array(
											"user_fullname"=>$this->input->post("pros_name"),
											"user_email"=>$this->input->post("user_email"),
											"user_password"=>md5($this->input->post("user_password")),
											"user_type_id"=>"2",
											"user_status"=>"1",
										    "is_email_varified"=>"1"));
									$addcat["id"] = $user_id;
									$id = $this->common_model->data_insert($this->table_name,$addcat);    
									
									$this->emailsender->send_new_pros_email($this->input->post("user_email"),$this->input->post("user_password"));                           
									$this->message_model->action_mesage("add",$this->controller,false);
									redirect($this->controller);
								}
							}
							else
							{
								if($invalidImage1==1)
								{
									$this->message_model->error("Please choose valid file for profile image");
								}
								else
								{
									$this->message_model->error("Please choose valid file for id proof");
								}
							}
						}        
					
               	}
    }
    
	/*
	pros edit page
	@param id for pros id
	@return edit pros page
	*/
    public function edit($id){
        if(_is_user_login($this)){
                
                $this->action();
				
                $data["field"] = $this->pros_model->get_by_id($id);
                $data["categories"] = $this->category_model->get_categories();
                $data["selected_cat"] = $this->pros_model->get_pros_cat($id);
                
                $this->load->view($this->controller."/add",$data);
               
          }
                //echo json_encode($responce);
    }
    
}
?>