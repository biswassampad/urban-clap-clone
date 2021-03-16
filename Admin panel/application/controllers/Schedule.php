<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Schedule extends MY_Controller {
    protected $controller;
    protected $table_name;
    public function __construct()
    {
                parent::__construct();
                $this->controller = $this->router->fetch_class();
                $this->table_name = "schedule";
                if(_is_user_login($this)){
                        
                }else{
                    redirect("login");
                    exit();
                }
    }
	/*
	schedule page
	@param morning_from for morning from time
	@param morning_to for morning to time
	@param afternoon_from for afternoon from time
	@param afternoon_to for afternoon to time
	@param on_day for service allow on
	@param no_of_days for service no of days
	@param day for day like sun,mon,tue...
	@return schedule page
	*/
    public function index(){
        if(_is_user_login($this)){
            $data = array();
            if($_POST){
                    $this->load->library('form_validation');
                    $this->form_validation->set_rules('morning_from', 'Morning From', 'trim|required');
                    $this->form_validation->set_rules('morning_to', 'Morning To', 'trim|required');
                    $this->form_validation->set_rules('afternoon_from', 'Afternoon From', 'trim|required');
                    $this->form_validation->set_rules('afternoon_to', 'Afternoon To', 'trim|required');
                    if ($this->form_validation->run() == FALSE)
            		{
      		            if($this->form_validation->error_string()!=""){
            			     $this->session->set_flashdata("message", '<div class="alert alert-warning alert-dismissible" role="alert">
                                            <i class="fa fa-warning"></i>
                                          <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                          <strong>Warning!</strong> '.$this->form_validation->error_string().'
                                        </div>');
                        }
            		}
            		else
            		{
          		         $morning_from = date(COMMON_HOURS_FORMATE, strtotime($this->input->post("morning_from")));
                           $morning_to = date(COMMON_HOURS_FORMATE, strtotime($this->input->post("morning_to")));
                           $afternoon_from = date(COMMON_HOURS_FORMATE, strtotime($this->input->post("afternoon_from")));
                           $afternoon_to = date(COMMON_HOURS_FORMATE, strtotime($this->input->post("afternoon_to")));
                           
                           $on_day = $this->input->post("on_day");
                           $no_of_days = $this->input->post("no_of_days");
                           $book_type = "queue";
                           $days = $this->input->post('day');
                           if(!empty($days))
                           $days = implode(',',$this->input->post('day'));
                           
                            $sql = $this->db->insert_string($this->table_name,
                           array(
                           "id"=>"1",
                           "working_days"=>$days,
                           "morning_time_start"=>$morning_from,
                           "morning_time_end"=>$morning_to,
                           "afternoon_time_start"=>$afternoon_from,
                           "afternoon_time_end"=>$afternoon_to,
                           "on_day"=>$on_day,
                           "no_of_days"=>$no_of_days,
                           "book_type"=>$book_type)) . " ON DUPLICATE KEY UPDATE  working_days= '".$days."', ".
                           "morning_time_start = '".$morning_from."', ".
                           "morning_time_end = '".$morning_to."', ".
                           "afternoon_time_start = '".$afternoon_from."', ".
                           "afternoon_time_end = '".$afternoon_to."', ".
                           "on_day = '".$on_day."', ".
                           "no_of_days = '".$no_of_days."', ".
                           "book_type = '$book_type'";
                        
                        $this->db->query($sql);
                        $id = $this->db->insert_id();
                            
                        $this->session->set_flashdata("message", '<div class="alert alert-success alert-dismissible" role="alert">
                                            <i class="fa fa-success"></i>
                                          <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                          <strong>Success!</strong> Time slot added successfully
                                        </div>');
                              
                    }
                
        }
        
        $schedule = $this->schedule_model->get();
        $data["schedule"] = $schedule;
        $this->load->view($this->controller."/index",$data);
        
        }        
    }
}
?>