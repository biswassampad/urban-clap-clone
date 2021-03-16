<?php
defined('BASEPATH') OR exit('No direct script access allowed');
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);
@ini_set('max_execution_time', 240);

class Commonjson extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
        $this->load->database();
        
    }    
    public function date_time_zone_json(){
        header('Content-type: text/json');
        $time_zone = get_timezones_list();
        echo json_encode($time_zone[$this->input->post("time_zone")]);
    }
    public function change_status(){
        $table = $this->input->post("table");
        $id = $this->input->post("id");
        $on_off = $this->input->post("on_off");
        $id_field = $this->input->post("id_field");
        $status = $this->input->post("status");
        
        $this->db->update($table,array("$status"=>$on_off),array("$id_field"=>$id));
        if($table == "appointment"){
              
        }
    }
}