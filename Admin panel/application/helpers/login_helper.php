<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('_is_user_login'))
{
     function _is_user_login($thi)
    {
        $userid = _get_current_user_id($thi);
        $usertype = _get_current_user_type_id($thi);
         $is_access = _get_user_type_access($thi,$usertype);
        
        if(isset($userid) && $userid!="" && isset($usertype))
        {
            if($is_access == true){
                 
                 return true;
            }else{
                $thi->load->view("common/not_access");
                return false;    
            }
            
        }else
        {
           
            return false;
        }

    }   
}
if ( ! function_exists('_is_frontend_user_login'))
{
     function _is_frontend_user_login($thi)
    {
        $userid = _get_current_user_id($thi);
        $usertype = _get_current_user_type_id($thi);
         
        if(isset($userid) && $userid!="" && isset($usertype))
        {
                 return true;
        }else
        {
           
            return false;
        }

    }   
}
if(! function_exists('_get_post_back')){
    function _get_post_back($field,$post){
        if(!empty($field)){
			if(is_array($field) && isset($field[$post]))
			{
				return $field[$post];
			}
			else if(isset($field->$post))
			{
				return $field->$post;
			}
        }
    }
}
if(! function_exists('_get_current_user_id')){
    function _get_current_user_id($thi){
        return $thi->session->userdata("user_id");
    }
}
if(! function_exists('_get_current_user_name')){
    function _get_current_user_name($thi){
        return $thi->session->userdata("user_name");
    }
}
if(! function_exists('_get_current_user_image')){
    function _get_current_user_image($thi){
        //echo dirname(__FILE__)."../../uploads/profile/".$thi->session->userdata("user_image");
        if($thi->session->userdata("user_image") != "" && file_exists(dirname(__FILE__)."./../../uploads/profile/".$thi->session->userdata("user_image")))
            return base_url("uploads/profile/".$thi->session->userdata("user_image"));
        else
            return base_url("theme/dist/img/avatar.png");
    }
}
if(! function_exists('_get_current_user_type_id')){
    function _get_current_user_type_id($thi){
        return $thi->session->userdata("user_type_id");
    }
}
if(! function_exists('_get_user_type_access')){
    function _get_user_type_access($thi,$user_type_id){
            $cur_class = $thi->router->fetch_class();
            $cur_method = $thi->router->fetch_method();
            $result = $thi->db->query("select * from user_type_access where user_type_id = '".$user_type_id."' and class = '".$cur_class."' and (method = '".$cur_method."' or method='*')");
            
            $row = $result->row();
            
            if($result->num_rows() > 0){
                return true;
            }else{
                return false;
            }
            return false;
    }
}
if(! function_exists('_get_user_redirect')){
    function _get_user_redirect($thi){
                            if(_get_current_user_type_id($thi)==USER_ADMIN)
                            {
                                return "admin/dashboard";
                            }
                            else if(_get_current_user_type_id($thi)==USER_PROS)
                            {
                                return "pros/dashboard";
                            }else if(_get_current_user_type_id($thi)==USER_APP){
                                redirect("admin/signout");
                            }
    }
}
if(! function_exists('_is_active_menu')){
    function _is_active_menu($thi,$class,$method){
        $c_class = $thi->router->fetch_class();
        $c_method = $thi->router->fetch_method();
        if(in_array($c_class,$class)){
            return "active";
        }
        if(in_array($c_method,$method)){
            return "active";
        }
    }
}