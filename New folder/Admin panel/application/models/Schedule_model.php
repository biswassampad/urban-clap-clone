<?php
class Schedule_model extends CI_Model{
    protected $table_name;
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'schedule';
    }
    function get(){
        $q = $this->db->query("select ".$this->table_name.".* from ".$this->table_name);
        return $q->row();
        
    }
    public function get_time_slot($total_time, $app_date){
                        $data = array();
                        $date  = date("Y-m-d");
                        if($app_date!=""){
                            $date = $app_date; 
                        }
                        $current_time = date(DB_TIME_FORMATE);
                        $q = $this->db->query("Select * from leaves where date = '".$date."' and start_time <= '".$current_time."' and end_time >= '".$current_time."' ");
                        $leave = $q->row();
                        if(!empty($leave)){
                            $data["responce"] = false;
                            $data["error"] = "On leave : ".$leave->reason;
                        }else{
                        
                        $day_name =  date("D", strtotime($date));
                        $q = $this->db->query("Select * from ".$this->table_name."  limit 1");
                        $schedule = $q->row();
                        if(!empty($schedule)){
                            
                            
                            $total_expand_time =  explode(':',$total_time);
                            $total_expand_time_add = '+'.$total_expand_time[0].' hour +'.$total_expand_time[1].' minutes';
                            
                            $day_array = explode(",",$schedule->working_days);
                            if(in_array(strtolower($day_name) ,$day_array)){
                               
                            $morning =  $this->db->query("Select  ifnull( SEC_TO_TIME( SUM( TIME_TO_SEC( services.service_approxtime * a_service.service_qty ) ) ), '00:00:00' ) AS total_time, count(DISTINCT(app.id)) as total_app from 
                            appointment_services as a_service
                            inner join appointment as app on app.id = a_service.appointment_id
                            inner join services as services on services.id = a_service.service_id
                            where app.appointment_date = '".$date."'  and app.time_token = 1
                            limit 1
                            ");
                            
                            
                            $morning_total_time  = $morning->row();
                            
                            $q = $this->db->query("select ADDTIME(morning_time_start, '".$morning_total_time->total_time."') as time_slot, morning_time_end from ".$this->table_name." limit 1");
                            $morning_time_slot = $q->row();
                            
                            $m_slot = strtotime($total_expand_time_add, strtotime($morning_time_slot->time_slot));
                            if($m_slot > strtotime($morning_time_slot->morning_time_end)){
                                
                            }else{              
                            
                                $data["morning_appointment"] = $morning_total_time->total_app;
                                $data["morning_time_slot"] = $morning_time_slot->time_slot;
                                $data["morning_time_slot_end"] = date("H:i:s",$m_slot);
                                $data["morning_time_end"] = $morning_time_slot->morning_time_end;
                        
                            }
                            


                            $afternoon =  $this->db->query("Select  ifnull( SEC_TO_TIME( SUM( TIME_TO_SEC( services.service_approxtime * a_service.service_qty ) ) ), '00:00:00' ) AS total_time, count(DISTINCT(app.id)) as total_app  from 
                            appointment_services as a_service
                            inner join appointment as app on app.id = a_service.appointment_id
                            inner join services as services on services.id = a_service.service_id
                            where app.appointment_date = '".$date."' and app.time_token = 2
                            limit 1
                            ");
                            
                            
                            $afternoon_total_time  = $afternoon->row();
                            
                            $q2 = $this->db->query("select ADDTIME(afternoon_time_start, '".$afternoon_total_time->total_time."') as time_slot, afternoon_time_end from ".$this->table_name." limit 1");
                            $afternoon_time_slot = $q2->row();
                            
                            $af_slot = strtotime($total_expand_time_add, strtotime($afternoon_time_slot->time_slot));
                            if($af_slot > strtotime($afternoon_time_slot->afternoon_time_end)){
                                
                            }else{              

                            
                            $data["afternoon_appointment"] = $afternoon_total_time->total_app;
                            $data["afternoon_time_slot"] = $afternoon_time_slot->time_slot;
                            $data["afternoon_time_slot_end"] = date("H:i:s",$af_slot);
                            $data["afternoon_time_end"] = $afternoon_time_slot->afternoon_time_end;
    
                            }
                            
                            
                            $evening =  $this->db->query("Select  ifnull( SEC_TO_TIME( SUM( TIME_TO_SEC( services.service_approxtime * a_service.service_qty ) ) ), '00:00:00' ) AS total_time, count(DISTINCT(app.id)) as total_app  from 
                            appointment_services as a_service
                            inner join appointment as app on app.id = a_service.appointment_id
                            inner join services as services on services.id = a_service.service_id
                            where app.appointment_date = '".$date."' and app.time_token = 3
                            limit 1
                            ");
                            
                            
                            $evening_total_time  = $evening->row();
                            
                            $q3 = $this->db->query("select ADDTIME(evening_time_start, '".$evening_total_time->total_time."') as time_slot, evening_time_end from ".$this->table_name." limit 1");
                            $evening_time_slot = $q3->row();
                            
                            $ev_slot = strtotime($total_expand_time_add, strtotime($evening_time_slot->time_slot));
                            if($ev_slot > strtotime($evening_time_slot->evening_time_end)){
                                
                            }else{              

                            
                            $data["evening_appointment"] = $evening_total_time->total_app;
                            $data["evening_time_slot"] = $evening_time_slot->time_slot;
                            $data["evening_time_slot_end"] = date("H:i:s",$ev_slot);
                            $data["evening_time_end"] = $evening_time_slot->evening_time_end;
                            
                            
                            }
                            $data["responce"] = true;
                            
                            }else{
                                $data["responce"] = false;
                                $data["error"] = $day_name." IS Off Day";
                            }
                        }else{
                                $data["responce"] = false;
                                $data["error"] = " No time schedule set";
                        }
                        
                        }
                        return $data;
    }
	
	/*
	get time slots
	@param total_time for appointment's total time
	@param app_date for appointment's date
	@return responce for true/false
	@return error for error message
	@return morning_appointment for total morning appointment
	@return morning_time_slot for morning start time
	@return morning_time_end for morning end time
	@return afternoon_appointment for total afternoon appointment
	@return afternoon_time_slot for afternoon start time
	@return afternoon_time_end for afternoon end time
	*/
    public function get_time_slots($total_time, $app_date){
                        $data = array();
                        $date  = date("Y-m-d");
                        if($app_date!=""){
                            $date = $app_date; 
                        }
                        $current_time = date(DB_TIME_FORMATE);
						
                        $q = $this->db->query("Select * from leaves where date = '".$date."' and start_time <= '".$current_time."' and end_time >= '".$current_time."' ");
                        $leave = $q->row();
                        if(!empty($leave)){
                            $data["responce"] = false;
                            $data["error"] = "On leave : ".$leave->reason;
                        }else{
                        
							$day_name =  date("D", strtotime($date));
							$days_name =  date("l", strtotime($date));
							$q = $this->db->query("Select * from ".$this->table_name."  limit 1");
							$schedule = $q->row();
							if(!empty($schedule)){
								
								$onDay=$schedule->on_day;
								$noOfDays=$schedule->no_of_days;
								
								
								
								// setting options
								$setting = get_options(array("per_day_appointment"));
								$per_day_appointment=(isset($setting['per_day_appointment']) && $setting['per_day_appointment']>0)?$setting['per_day_appointment']:0;
								
								// Morning appointment
								$morning =  $this->db->query("Select count(DISTINCT(app.id)) as total_app from 
									appointment_services as a_service
									inner join appointment as app on app.id = a_service.appointment_id
									inner join services as services on services.id = a_service.service_id
									where app.appointment_date = '".$date."'  and app.time_token = 1
									limit 1
									");
								$morning_total_time  = $morning->row();
								
								// Afternoon appointment
								$afternoon =  $this->db->query("Select count(DISTINCT(app.id)) as total_app  from 
									appointment_services as a_service
									inner join appointment as app on app.id = a_service.appointment_id
									inner join services as services on services.id = a_service.service_id
									where app.appointment_date = '".$date."' and app.time_token = 2
									limit 1
									");
								$afternoon_total_time  = $afternoon->row();	
								$total_appointment=$morning_total_time->total_app+$afternoon_total_time->total_app;
								
								// if total appointment is less than per day appointment
								if($total_appointment<$per_day_appointment)
								{
									$day_array = explode(",",$schedule->working_days);
									if(in_array(strtolower($day_name) ,$day_array)){
										$errorMsg="";
										$mustbe="";
										if($onDay==1)
										{
											if(strtotime($date)>=strtotime(date('Y-m-d')))
											{
												/*AFTERNOON SLOT*/
												$data["afternoon_appointment"] = $afternoon_total_time->total_app;
												$data["afternoon_time_slot"] = $schedule->afternoon_time_start;
												//$data["afternoon_time_slot_end"] = date("H:i:s",$af_slot);
												$data["afternoon_time_end"] = $schedule->afternoon_time_end;	
											}
											else
											{
												$mustbe=" Today.";
											}
										}
										else if($onDay==2)
										{
											if(strtotime($date)>=strtotime(date('Y-m-d',strtotime(date('Y-m-d')." +1 days"))))
											{
												/*MORNING SLOT*/					
												$data["morning_appointment"] = $morning_total_time->total_app;
												$data["morning_time_slot"] = $schedule->morning_time_start;
												//$data["morning_time_slot_end"] = date("H:i:s",$m_slot);
												$data["morning_time_end"] = $schedule->morning_time_end;
												
												/*AFTERNOON SLOT*/
												$data["afternoon_appointment"] = $afternoon_total_time->total_app;
												$data["afternoon_time_slot"] = $schedule->afternoon_time_start;
												//$data["afternoon_time_slot_end"] = date("H:i:s",$af_slot);
												$data["afternoon_time_end"] = $schedule->afternoon_time_end;
											}
											else
											{
												$mustbe=" next day.";
											}
										}
										else if($onDay==3)
										{
											if(strtotime($date)>=strtotime(date('Y-m-d',strtotime(date('Y-m-d')." +$noOfDays days"))))
											{
												/*MORNING SLOT*/					
												$data["morning_appointment"] = $morning_total_time->total_app;
												$data["morning_time_slot"] = $schedule->morning_time_start;
												//$data["morning_time_slot_end"] = date("H:i:s",$m_slot);
												$data["morning_time_end"] = $schedule->morning_time_end;
												
												/*AFTERNOON SLOT*/
												$data["afternoon_appointment"] = $afternoon_total_time->total_app;
												$data["afternoon_time_slot"] = $schedule->afternoon_time_start;
												//$data["afternoon_time_slot_end"] = date("H:i:s",$af_slot);
												$data["afternoon_time_end"] = $schedule->afternoon_time_end;
											}
											else
											{
												$mustbe="after $noOfDays days. ";
											}
										}
										if($mustbe!="")
										{
											$errorMsg="Appointment must be appoint in $mustbe";		
											$data["responce"] = false;
											$data["error"] = $errorMsg;
										}
										else
										{
											$data["responce"] = true;
										}
										
									}else{
										$data["responce"] = false;
										$data["error"] = $days_name." is OFF Day";
									}
								}
								else
								{
									$data["responce"] = false;
									$data["error"] = "Appointment booking level reached the limit of $per_day_appointment appointment per day You may choose next date";
								}
							}else{
									$data["responce"] = false;
									$data["error"] = " No time schedule set";
							}
							
                        }
                        return $data;
    }
}
?>