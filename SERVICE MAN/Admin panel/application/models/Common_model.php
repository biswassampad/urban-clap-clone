<?php
class Common_model extends CI_Model{
	/*
	insert record
	@param table for table name
	@param insert_array for insert data array
	@param insert_by for insert by user
	@return last record inserted id
	*/
    function data_insert($table,$insert_array,$insert_by=false){
        if($insert_by){
            $user_id = _get_current_user_id($this);
            if(!empty($user_id)){
                $insert_array["created_by"] = $user_id;
            }
            $insert_array["created_at"] = date("Y-m-d H:s:i");
        }
        $this->db->insert($table,$insert_array);
        $id = $this->db->insert_id();
        $this->logs_model->set_log($table,"add","",$insert_array,array("key"=>$id));
        return $id;
    }
	/*
	update record
	@param table for table name
	@param set_array for array of update data
	@param condition for update data condition
	@param update_by for update by loggedin user or not true/false
	@return affected rows of table/record updated row
	*/
    function data_update($table,$set_array,$condition,$update_by=false){
        if($update_by){
            $user_id = _get_current_user_id($this);
            if(!empty($user_id)){
                $set_array["modified_by"] = $user_id;
            }
            $set_array["modified_at"] = date("Y-m-d H:s:i");
        }
        $this->db->update($table,$set_array,$condition);
        $return = $this->db->affected_rows();
        if(isset($set_array["draft"])){
            $this->logs_model->set_log($table,"update","",$set_array,$condition);
        }else{
            $this->logs_model->set_log($table,"delete","",$set_array,$condition);
        }
        return $return;
    }
	/*
	remove record
	@param table for table name
	@param condition for remove data condition array
	@param hard for remove permanently or temporary
	@param update_by for update by user
	@return null
	*/
    function data_remove($table,$condition,$hard=true,$update_by=false){
        $filter = "";
        if(!empty($condition)){
            foreach($condition as $key=>$con){
                $filter .= " and ".$key." = '".$con."' ";
            }
        $sql = "Select * from ".$table." where 1 $filter";
        $q = $this->db->query($sql);
        $row = $q->row(); 
        if($row){
            if($hard){
                $this->db->delete($table,$condition);
                $this->logs_model->set_log($table,"delete","",array(),$condition);
            }else{
                
                $this->data_update($table,array("draft"=>"1"),$condition,$update_by);
            }
            
        }
        
        }
    }
    
}
?>