<?php
defined('BASEPATH') OR exit('No direct script access allowed');
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);
@ini_set('max_execution_time', 240);

class Install extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
        $this->load->database();
        
    }
    public function index(){
        $data = array();
        $is_dbconnected = $this->check_database();
        if($is_dbconnected){
           if ($this->db->table_exists('users') )
           {
                $q = $this->db->query("Select * from users where user_type_id = ".USER_ADMIN." limit 1");
                $row = $q->row();
                if(empty($row))
                {
                    redirect("install/site");
                }else{
                    _set_flash_message(_l("Installation process is completed. Please rename <label>application/controllers/Install.php</label> and refresh page."),"success");
                    if (is_writable(APPPATH."controllers/Install.php")) {
                        rename(APPPATH."controllers/Install.php",APPPATH."controllers/Install_finish.php");
                        redirect();
                    }
                } 
           }else{
           
            
             
           $this->load->helper("sqlparser_helper");
           $parser = new SqlScriptParser();
           if(file_exists(APPPATH."database/import_sql.sql")){
                $sqlStatements = $parser->parse(APPPATH."database/import_sql.sql");
                
                foreach ($sqlStatements as $statement) {
                    $distilled = $parser->removeComments($statement);
                    if (!empty($distilled)) {
                        $this->db->query($distilled);
                    }
                }
                redirect("install/site");
           }else{
                _set_flash_message(_l("Import process is failed. Base sql database file not located at <label>application/database/import_sql.sql</label>"),"warning"); 
           }
           
           }    
        }else{
            _set_flash_message(_l("Please config database in 'application/config/database.php'"),"success");            
        }
        $this->load->view("install/initdb",$data);
    }
    public function site(){
        $data = array();
        if($_POST){
                $this->load->library('form_validation');
                $this->form_validation->set_rules('site_name', 'Site Title', 'trim|required');
                $this->form_validation->set_rules('user_email', 'email Currectr', 'trim|required|valid_email|is_unique[users.user_email]');
                $this->form_validation->set_rules('user_password', 'Password', 'trim|required');
                $this->form_validation->set_rules('user_password_r', 'Password', 'trim|required');
                $this->form_validation->set_rules('default_timezone', 'Time Zone', 'trim|required');
                $this->form_validation->set_rules('date_default_timezone', 'Date Time Zone', 'trim|required');
                $this->form_validation->set_rules('default_country', 'Country', 'trim|required');
                
                $this->form_validation->set_message('is_unique', 'Email address is already register');
                
                if ($this->form_validation->run() == FALSE)
        		{
  		            if($this->form_validation->error_string()!=""){
  		                _set_flash_message($this->form_validation->error_string(),"warning");
                    }
        		}
        		else
        		{
      		        $post = $this->input->post();
                    $user_id = $this->common_model->data_insert("users",
                                array(
                                "user_fullname"=>$post["site_name"],
                                "user_email"=>$post["user_email"],
                                "user_password"=>md5($post["user_password"]),
                                "user_type_id"=>1,
                                "is_email_varified"=>1,
                                "user_status"=>1));
                         
                    add_options(array(
                    "site_name"=>$post["site_name"],
                    "default_country"=>$post["default_country"],
                    "default_timezone"=>$post["default_timezone"],
                    "date_default_timezone"=>$post["date_default_timezone"],
                    "dateformat"=>"d-m-Y",
                    "noti_on_appointment_book"=>"yes",
                    "noti_on_appointment_status"=>"yes",
                    "email_on_appointment_book"=>"yes",
                    "email_on_appointment_status"=>"yes",
                    "payment_mod"=>"cod",
                    "app_dateformat"=>"dd-MM-yyyy",
                    "app_timeformat"=>"HH:ss A",
                    "email_id"=>$post["user_email"],
                    "email_sender"=>$post["user_email"],
                    "currency"=>$post["currency"],
                    "fcm_topic"=>url_title($post["site_name"],"_")),"site_settings","1",true);
                    
                    _set_flash_message(_l("Installation process is completed. Please rename <label>application/controllers/Install.php</label> and refresh page."),"success");
                    rename(APPPATH."/controller/Install.php",APPPATH."/controller/Install_Finish.php");
                    redirect();                                   
                }
        }
        $this->load->model("area_model");
        $data["countries"] = $this->area_model->get_countries();
        $data["time_zones"] = get_timezones_list();
        $data["date_time_zone"] = $data["time_zones"]["EUROPE"];
        
        $this->load->view("install/site",$data);
    }
    
    public function finish(){
        $this->load->view("install/initdb");
    }
    private function check_database()
    {
        
        ini_set('display_errors', 'Off');
        
        //  Load the database config file.
        if(file_exists($file_path = APPPATH.'config/database.php'))
        {
            include($file_path);
        }
        
        $config = $db["default"];
        
        //  Check database connection if using mysqli driver
        if( $config['dbdriver'] === 'mysqli' )
        {
            if($config['hostname'] != "" && $config["database"] != "" && $config["username"] != ""){
            $mysqli = mysqli_connect( $config['hostname'] , $config['username'] , $config['password'] , $config['database'] );
                if($mysqli){
                    if (!mysqli_connect_errno())
                    {
                        return true;
                    }
                    else{
                        return false;
                    }
         
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }
        else
        {
            return false;
        }
    } 
}