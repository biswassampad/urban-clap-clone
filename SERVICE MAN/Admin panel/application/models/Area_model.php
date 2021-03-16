<?php
class Area_model extends CI_Model{
    /* Get Country Function */
    public function get_countries($status=""){
        if($status!=""){
            $this->db->where("status",$status);
        }
        $q = $this->db->get("area_country");
        return $q->result();
    }
    /* End Get Country Function */
    
    /* Get Country Function Id */
    public function get_country_id($id){
        if($id!=""){
            $this->db->where("country_id",$id);
        }
        $q = $this->db->get("area_country");
        return $q->row();
    }
    /* End Get Country Id Function  */
    
    /* Get City Function */
    public function get_cities($status="",$country_id=""){
        $this->db->select("area_city.*,area_country.country_name");
        if($status!=""){
            $this->db->where("area_city.status",$status);
        }
        if($country_id!=""){
            $this->db->where("area_city.country_id",$country_id);
        }
        $this->db->join("area_country","area_country.country_id = area_city.country_id");
        $q = $this->db->get("area_city");
        return $q->result();
    }
    /* End Get City Function */
    
    /* Get City Id Function */
    public function get_city_id($id){
        $this->db->select("area_city.*,area_country.country_name");
        $this->db->join("area_country","area_country.country_id = area_city.country_id");
        $this->db->where("area_city.city_id",$id);
        $q = $this->db->get("area_city");
        return $q->row();
    }
    /* End Get City Id Function */
    
    /* Get Locality Function */
    public function get_locality($status="",$country_id="", $city_id = ""){
        $this->db->select("area_locality.*,area_country.country_name as country_name, area_city.city_name");
        
        if($status!=""){
            $this->db->where("area_locality.status",$status);
        }
        if($country_id!=""){
            $this->db->where("area_locality.country_id",$country_id);
        }
        if($city_id!=""){
            $this->db->where("area_locality.city_id",$city_id);
        }
        $this->db->join("area_city","area_city.city_id =  area_locality.city_id");
        $this->db->join("area_country","area_country.country_id = area_locality.country_id");
        
        $q = $this->db->get("area_locality");
                        
        return $q->result();
    }
     /* End Get Locality Function */
     
      /* Get Locality Id Function */
    public function get_locality_id($id){
        $this->db->select("area_locality.*,area_country.country_name as country_name, area_city.city_name");
        $this->db->join("area_city","area_city.city_id =  area_locality.city_id");
        $this->db->join("area_country","area_country.country_id = area_locality.country_id");
        $this->db->where("area_locality.locality_id",$id);
        $q = $this->db->get("area_locality");
        return $q->row();
    }
     /* End Get Locality Id Function */
}
?>