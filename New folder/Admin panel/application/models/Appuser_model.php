<?php
class Appuser_model extends CI_Model{
	/*
	get app user list
	@return object of appusers
	*/
    public function get_user(){
        $q = $this->db->query("select users.*, user_types.user_type_title from users 
        inner join user_types on user_types.user_type_id = users.user_type_id 
        where user_types.user_type_id=3");
        return $q->result();
    }
   
}
?>