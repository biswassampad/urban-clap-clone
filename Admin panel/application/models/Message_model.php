<?php
class Message_model extends CI_Model{
	/*
	get message
	@param message for language matcher message
	@return message
	*/
    function get_message($message){
        return ($this->lang->line($message) != "" ) ? $this->lang->line($message) : $message;
    }
    function confirm_delete($title){
        $title = $this->get_message($title);
        return str_replace("##",strtolower($title),$this->lang->line("msg_delete_confirm"));
    }
    function confirm_delete_script($title){
        $msg = $this->confirm_delete($title);
        return "return confirm('".$msg."')";
    }
	/*
	assign service to pros
	@param date for date of service
	@param time for time of service
	@param name for name of customer
	@return message
	*/
    function assign_service_to_pros($date,$time,$name){
        return  str_replace(array("{0}","{1}","{2}"),array($date,$time,$name),$this->get_message("assign_order_to_pros"));
    }
    function error($message,$string = false){
        if($string){
            return '<div class="alert alert-warning alert-dismissible" role="alert">
                                  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                  <strong>Warning!</strong> '.$message.'
                                </div>';    
        }else{
        $this->session->set_flashdata("message", '<div class="alert alert-warning alert-dismissible" role="alert">
                                  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                  <strong>Warning!</strong> '.$message.'
                                </div>');
        }
        
    }
    function custom_messages($case,$parms){
        $action_string = "";
        switch($case){
            case 'log' :
                $action_string =  str_replace(array("{0}","{1}"),$parms,$this->get_message("msg_action_log"));         
                break;
            case 'new_user_success' :
                if(is_array($parms)){
                    $parms = $parms[0];    
                }
                $action_string =  str_replace(array("{0}"),array($parms),$this->get_message("msg_new_user_add"));         
                break;
            case 'update_user' :
                if(is_array($parms)){
                    $parms = $parms[0];    
                }
                $action_string = str_replace(array("{0}"),array($parms),$this->get_message("msg_user_update"));         
                break;
            case 'remove_user' :
                if(is_array($parms)){
                    $parms = $parms[0];    
                }
                $action_string =  str_replace(array("{0}"),array($parms),$this->get_message("msg_user_update"));         
                break;
        }
        return $action_string;
    }
	
	/*
	action message 
	@param action for added/update/delete/cancel
	@param title for message title
	@param string true/false
	@return string message or flash message
	*/
    function action_mesage($action,$title,$string=false){
        $action_string;
        switch($action){
            case 'add' :
                $action_string = $this->get_message("added");         
                break;
            case 'update' :
                $action_string = $this->get_message("saved");
                break;
            case 'delete' :
                $action_string = $this->get_message("deleted");         
                break;
            case 'cancel' :
                $action_string = $this->get_message("cancelled");         
                break;
                
            
        }
        $message = str_replace(array("{0}","{1}"),array($title,$action_string),$this->lang->line("msg_action"));
         if($string){
            return '<div class="alert alert-success alert-dismissible" role="alert">
                                            <i class="fa fa-check"></i>
                                          <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                          <strong>Success!</strong> '.$message.'
                                        </div>';
        }else{
            $this->session->set_flashdata("message",'<div class="alert alert-success alert-dismissible" role="alert">
                                            <i class="fa fa-check"></i>
                                          <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                          <strong>Success!</strong> '.$message.'
                                        </div>');
        }
    }
    
    function success($message,$string = false){
       if($string){
            return '<div class="alert alert-success alert-dismissible" role="alert">
                                            <i class="fa fa-check"></i>
                                          <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                          <strong>Success!</strong> '.$message.'
                                        </div>';
        }else{
            $this->session->set_flashdata("message",'<div class="alert alert-success alert-dismissible" role="alert">
                                            <i class="fa fa-check"></i>
                                          <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                          <strong>Success!</strong> '.$message.'
                                        </div>');
        }
        
    }
    
}
?>