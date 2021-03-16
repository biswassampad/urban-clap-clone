<?php
class Users_model extends CI_Model{
    public function get_users($filter=array()){
        $filter = "";
        if(!empty($filter))
        {
            if(isset($filter["user_type"])){
                $filter .=" and users.user_type_id = '".$filter["user_type"]."'";
            }
            if(isset($filter["status"])){
                $filter .=" and users.user_status = '".$filter["status"]."'";
            }
        }
        $q = $this->db->query("select * from users where 1 ".$filter);
        return $q->result();
    }
        public function get_alluser(){
        $q = $this->db->query("select * from users where user_type_id = 0 ");
        return $q->result();
    }
	/*
	get user by user id
	@param user_id for user's id
	@return object of user's data
	*/
    public function get_user_by_id($user_id,$isPro=0){
		if($isPro==0)
		{
			$q = $this->db->query("select * from users where  user_id = '".$user_id."' limit 1");
		}
		else
		{
			$q = $this->db->query("select * from users u join pros p on p.id=u.user_id where  user_id = '".$user_id."' limit 1");
		}
        return $q->row();
    }
    public function get_user_type(){
        $q = $this->db->query("select * from user_types");
        return $q->result();
    }
    public function get_user_type_id($id){
        $q = $this->db->query("select * from user_types where user_type_id = '".$id."'");
        return $q->row();
    }
    public function get_user_type_access($type_id){
        $q = $this->db->query("select * from user_type_access where user_type_id = '".$type_id."'");
        return $q->result();
    }
	
	/*
	get users count
	@param user_type for user's type
	@return total users
	*/
    public function get_users_counts($user_type = ""){
        $filter = "";
        if($user_type!=""){
            $filter .=" and user_type_id = '".$user_type."' ";
        }
        $q = $this->db->query("Select count(*) as total_users from users where 1 ".$filter);
        $row = $q->row();
        return $row->total_users;
    }
    
}
?>