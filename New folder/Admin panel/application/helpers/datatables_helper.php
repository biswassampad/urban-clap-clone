<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * General function for datatable listing
 * @param  array $columns            table columns with database table field key array('database_field'=>'column_title'); 
 * @param  array $data               table records
 */
function _datatable($columns=array(),$data=array(),$edit=array(),$delete=array(),$image_fields=array()){
    $table = '<table class="table table-bordered table-responsive table-hover datatable">';
    $table .= '<thead><tr>';
        if(!empty($columns)){
            foreach($columns as $head)
            {
                $table .= "<th>".$head."</th>";
            }
        }
        if(!empty($edit) || !empty($delete)){
            $table .= "<th width='90px'>"._l("Action")."</th>";
        }
    $table .= '</tr></thead>';
    $table .= '<tbody>';
    if(!empty($data) ){
        foreach($data as $dt){
            $table .= "<tr>";
            if(!empty($columns)){
                foreach($columns as $key=>$head)
                {
                        if($key == "status"){
                            switch($dt->$key){
                                case "0":
                                    $table .= "<td width='85px'><label class='bg-gray-active label' >"._l("Pending")."</label></td>";
                                    break;
                                case "1":
                                    $table .= "<td width='85px'><label class='bg-green-active label ' >"._l("Approved")."</label></td>";
                                    break;
                                case "2":
                                    $table .= "<td width='85px'><label class='bg-yellow-active label' >"._l("Rejected")."</label></td>";
                                    break;
                                case "3":
                                    $table .= "<td width='85px'><label class='bg-aqua-active label' >"._l("Complete")."</label></td>";
                                    break;
                                case "4":
                                    $table .= "<td width='85px'><label class='bg-red-active label' >"._l("Cancel")."</label></td>";
                                    break;
                                
                            }
                        }else{
                            if(!empty($image_fields) && isset($image_fields[$key])){
                                $table .= "<td><div style='background-image:url(".base_url($image_fields[$key]."/".$dt->$key)."); background-size:cover; width : 40px; height : 40px; display:block; '></div></td>";
                            }else{
                                $table .= "<td>".$dt->$key."</td>";    
                            }
                        }
                            
                }                
            }
            
            /*if(!empty($image_fields)){
                    foreach($image_fields as $img_key=>$img_value){
                        $table .= "<td><div style='background-image:url(".$img_value."/".$dt->$img_key."); background-size:cover'></div></td>";
                    }
            }
            */
            
            if(!empty($edit) || !empty($delete)){
                $table .= "<td><div class='btn-group'>";
            }
            if(!empty($edit)){
                    $table .= anchor($edit["action"]."/".$dt->$edit["key"], '<i class="fa fa-edit"></i>', array("class"=>"btn btn-xs btn-success"));
            }
            if(!empty($delete)){
                    $table .= anchor($delete["action"]."/".$dt->$delete["key"], '<i class="fa fa-times"></i>', array("class"=>"btn btn-xs btn-danger", "onclick"=>"return confirm('"._l('Are you sure delete?')."')"));
            }
            if(!empty($edit) || !empty($delete)){
                $table .= "</div></td>";
            }
            $table .= "</tr>";
        }
            
    }
    $table .= '</tbody>';    
    $table .= '</table>';
    
    return $table;
}
