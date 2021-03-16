<?php
Class MY_Controller Extends CI_Controller{

    public function __construct(){
        parent::__construct();
        
        date_default_timezone_set(get_option("date_default_timezone","GMT"));
        
        /*if(_is_user_login($this)){
        $user = $this->users_model->get_user_by_id(_get_current_user_id($this));
        $newdata = array(
                                                   'user_name'  => $user->user_fullname,
                                                   'user_email'     => $user->user_email,
                                                   'logged_in' => TRUE,
                                                   'user_id'=>$user->user_id,
                                                   'user_type_id'=>$user->user_type_id,
                                                   'user_image'=>$user->user_image
                                                  );
                            $this->session->set_userdata($newdata);
        }
        */
        
        
    }
    
}
?>