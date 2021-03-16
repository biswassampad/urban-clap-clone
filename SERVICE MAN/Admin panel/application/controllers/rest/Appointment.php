<?php

defined('BASEPATH') or exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/**
 @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';
class Appointment extends REST_Controller
{
    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        //$this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        //$this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        //$this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
    }
	
	/*
	used for appointment list
	@param id for appointment id
	@param user_id for user's id
	@param pros_id for pros id
	@param assigned_pros_id for assigned pros id
	@param completed_pros_id for completed pros id
	@return response for true/false
	@return data for appointment list/appointment data
	@return message for error message	
	*/
    public function list_post()
    {
        $id = $this->post('id');
        // Validate The Shop Id
        if ($id === null) {

            $param = array();

            if ($this->post("user_id") != null) {
                $param["appointment.user_id"] = $this->post("user_id");
            }
            if ($this->post("pros_id") != null) {
                $param["appointment.pros_id"] = $this->post("pros_id");
            }
            if ($this->post("assigned_pros_id") != null) {
                $param["assigned_pros_id"] = $this->post("assigned_pros_id");
            }
            if ($this->post("completed_pros_id") != null) {
                $param["completed_pros_id"] = $this->post("completed_pros_id");
            }

            $appointment = $this->appointment_model->get($param);

            if (!empty($appointment)) {
                $this->response(array(RESPONCE => true, "data" => $appointment), REST_Controller::
                    HTTP_OK);
            } else {
                $this->response(array(RESPONCE => false, MESSAGE => 'No record were found'),
                    REST_Controller::HTTP_OK);
            }


        } else {
            $id = (int)$id;

            // Validate the id.
            if ($id <= 0) {
                // Invalid id, set the response and exit.
                $this->response(null, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
            }


            $appointment = $this->appointment_model->get_details($id);
            if (!empty($appointment)) {
                $appointment->service = $this->appointment_model->get_services($id);
                $this->response(array(RESPONCE => true, "data" => $appointment), REST_Controller::
                    HTTP_OK);
            } else {
                $this->response(array(RESPONCE => false, MESSAGE => 'No record were found'),
                    REST_Controller::HTTP_OK);
            }
        }
    }
	
	/*
	used for add appointment
	@param user_id for user's id
	@param address for user's address
	@param date for appointment date
	@param total_time for appointment total time
	@param time_token for time slot morning afternoon evening
	@param services for [{"service_id":"1","service_qty":"1","service_time":"00:30"}]
	@return response for true/false
	@return message for error message
	@return data for added appointment data
	*/
    public function add_post()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
        $this->form_validation->set_rules('address', 'Address', 'trim|required');
        $this->form_validation->set_rules('date', 'App date', 'trim|required');
        $this->form_validation->set_rules('total_time', 'Total Time', 'trim|required');
        $this->form_validation->set_rules('start_time', 'Start Time', 'trim|required');
        $this->form_validation->set_rules('time_token', 'Time Token', 'trim|required');

        if ($this->form_validation->run() == false) {
            $this->response(array(RESPONCE => false, MESSAGE => strip_tags($this->
                    form_validation->error_string())), REST_Controller::HTTP_OK);

        } else {
            $user_id = $this->input->post("user_id");
            $appointment_date = $this->input->post("date");
            $total_time = $this->input->post("total_time");
            $start_time = $this->input->post("start_time");
            $time_token = $this->input->post("time_token");


            /* $check = $this->db->query("Select * from appointment where start_time = '" . $start_time .
                "' and appointment_date = '" . $appointment_date . "'");
            if ($check->num_rows() > 0) {
                $this->response(array(RESPONCE => false, MESSAGE =>
                        "opps! your choosing Date and Time already booked"), REST_Controller::HTTP_OK);
            } else { */
                $this->db->insert("appointment", array(
                    "user_id" => $user_id,
                    "address_id" => $this->post("address"),
                    "appointment_date" => date("Y-m-d", strtotime($appointment_date)),
                    "total_time" => date("H:i:s",strtotime($total_time)),
                    "start_time" => date("H:i:s", strtotime($start_time)),
                    "time_token" => $time_token,
                    "status" => 0,
                    "promo_code" => $this->input->post("promo_code")));

                $app_id = $this->db->insert_id();


                $data_post = $this->input->post("services");
                $data_array = json_decode($data_post);
                $total_amount = 0;
                if (!empty($data_array)) {
                    foreach ($data_array as $service) {
                        $q = $this->db->query("select * from services where id = '".$service->service_id."'");
                        $row = $q->row();
                        if(!empty($row)){
                        
                        $service_amount = $row->service_price - ($row->service_discount * $row->service_price / 100) ;
                        $total_amount = $total_amount + ($service_amount * $service->service_qty); 
                        $array = array(
                            "appointment_id" => $app_id,
                            "service_id" => $service->service_id,
                            "service_qty" => $service->service_qty,
                            "service_time" => $service->service_time,
                            "service_amount" => $service_amount);
                        $this->common_model->data_insert("appointment_services", $array);
                        
                        }
                    }
                }
                
                $net_amount = $total_amount;
                $discount = 0;
                $q = $this->db->query("select * from offer where offer_coupon = '".$this->input->post("promo_code")."' and offer_status = 1");
                $offer = $q->row();
                
                if(!empty($offer)){
                    $discount = $total_amount * $offer->offer_discount / 100;
                    $net_amount = $net_amount - $discount;
                }
                $this->common_model->data_update("appointment",array("total_amount"=>$total_amount,"net_amount"=>$net_amount,"discount"=>$discount),array("id"=>$app_id));
                
                $appointment = $this->db->query("Select * from appointment where id = '" . $app_id .
                    "' limit 1");
                
                $email = get_option("email_id","");
                
                if($email != "")
				{
					$list = array($email);
                    $this->emailsender->send_booking_email($app_id,$email);
				}
                
                $this->response(array(RESPONCE => true, DATA => $appointment->row()),
                    REST_Controller::HTTP_OK);
            //}
        }
    }

	/*
	used for cancel appointment
	@param user_id for user's id
	@param appointment_id for appointment's id
	@return response for true/false
	@return message for error or success message
	*/
    public function cancel_post()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
        $this->form_validation->set_rules('appointment_id', 'Appointment ID',
            'trim|required');

        if ($this->form_validation->run() == false) {
            $this->response(array(RESPONCE => false, MESSAGE => strip_tags($this->
                    form_validation->error_string())), REST_Controller::HTTP_OK);

        } else {

            //$this->common_model->data_remove("appointment",array("id"=>$this->post("appointment_id"),"user_id"=>$this->post("user_id")));
            //$this->common_model->data_remove("appointment_services",array("appointment_id"=>$this->post("appointment_id")));
            $this->common_model->data_update("appointment", array("status" => "4"), array("id" =>
                    $this->post("appointment_id"), "user_id" => $this->post("user_id")));
			
			
			/* send start appointment mail*/
			$user = $this->users_model->get_user_by_id($this->post("user_id"));
			$list = array();	
			if(!empty($user))
			{
				$list[]=$user->user_email;
			}
			
			$email = get_option("email_id","");
			if($email != "")
			{
				$list[]=$email;	
			}
			if(!empty($list))
			{
				$sendMail=$this->emailsender->send_cancel_email($this->post("appointment_id"),$list);
			}
			/* send start appointment mail*/
			
            $this->response(array(RESPONCE => true, MESSAGE => "Appointment Cancel Success"),
                REST_Controller::HTTP_OK);

        }
    }
	
	/*
	used for get appointment details
	@param id for appointment's id
	@return response for true/false
	@return data for appointment data
	*/
    function appointment_details_post()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('id', 'ID', 'trim|required');
        if ($this->form_validation->run() == false) {
            $this->response(array(RESPONCE => false, MESSAGE => strip_tags($this->
                    form_validation->error_string())), REST_Controller::HTTP_OK);

        } else {
            $appointment = $this->appointment_model->get_details($this->post("id"));
            if ($appointment->pros_id != 0) {
                $appointment->pros = $this->pros_model->get_by_id($appointment->pros_id);
            }
            if ($appointment->address_id != 0) {
                $appointment->address = $this->address_model->get_by_id($appointment->
                    address_id);
            }
            $this->response(array(RESPONCE => true, "data" => $appointment), REST_Controller::
                HTTP_OK);

        }
    }
	
	/*
	used for appointment status update
	@param id for appointment id
	@param status for appointment status
	@return response for true/false
	@return message for error message
	@return data for success message
	*/
    public function status_post()
    {
        $data = array();
        $this->load->library('form_validation');
        /* add users table validation */
        $this->form_validation->set_rules('status', 'Status', 'trim|required');
        $this->form_validation->set_rules('id', 'Appointment ID', 'trim|required');

        if ($this->form_validation->run() == false) {
            $this->response(array(RESPONCE => false, MESSAGE => strip_tags($this->
                    form_validation->error_string())), REST_Controller::HTTP_OK);

        } else {
            $insert_array = array("status" => $this->input->post("status"));

            $this->load->model("common_model");


            $this->common_model->data_update("appointment", $insert_array, array("id" => $this->
                    input->post("id")));


            $this->response(array(RESPONCE => true, DATA =>
                    "Your Appoinment Status Change successfully..."), REST_Controller::HTTP_OK);
        }
    }
	
	/*
	used for add appointment's extra charges
	@param appointment_id for appointment's id
	@param title for extra title
	@param charge for extra charges
	@param qty for extra quantity
	@return response for true/false
	@return message for error message
	@return data for added extra item's data
	*/
    public function appointment_extra_post()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('appointment_id', 'Appooinment ID',
            'trim|required');
        $this->form_validation->set_rules('title', 'Title', 'trim|required');
        $this->form_validation->set_rules('charge', 'Charge', 'trim|required');
        $this->form_validation->set_rules('qty', 'Item', 'trim|required');

        if ($this->form_validation->run() == false) {
            $this->response(array(RESPONCE => false, MESSAGE => strip_tags($this->
                    form_validation->error_string())), REST_Controller::HTTP_OK);

        } else {
            $insert = array(
                "appointment_id" => $this->post("appointment_id"),
                "title" => $this->post("title"),
                "charge" => $this->post("charge"),
                "qty" => $this->post("qty"));

            $id = $this->common_model->data_insert("appointment_extra", $insert);

            $extra = $this->appointment_model->get_extra_by_id($id);
            $this->response(array(RESPONCE => true, DATA => $extra), REST_Controller::
                HTTP_OK);
        }
    }
	
	/*
	used for appointment's paid
	@param appointment_id for appointment's id
	@parmas payment_amount for payment amount
	@param payment_type for payment type 
	@param payment_ref for payment reference
	@param lat for latitude
	@param lon for longitude
	@return response for true/false
	@return message for error message
	@return data for success message
	*/
    public function paid_post()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('appointment_id', 'Appointment Id',
            'trim|required');
        $this->form_validation->set_rules('payment_amount', 'Payment Amount',
            'trim|required');
        $this->form_validation->set_rules('payment_type', 'Payment Type',
            'trim|required');
        $this->form_validation->set_rules('payment_ref', 'Refrence', 'trim|required');
        //$this->form_validation->set_rules('extras', 'Extras', 'trim|required');


        if ($this->form_validation->run() == false) {
            $this->response(array(RESPONCE => false, MESSAGE => strip_tags($this->
                    form_validation->error_string())), REST_Controller::HTTP_OK);

        } else {

            $lat = $this->post("lat");
            $lon = $this->post("lon");


            $insert = array(
                "payment_amount" => $this->post("payment_amount"),
                "payment_type" => $this->post("payment_type"),
                "payment_ref" => $this->post("payment_ref"),
                //"end_at" => date("Y-m-d h:s:i"),
                "end_at" => date("Y-m-d H:i:s"),
                "end_lat" => $lat,
                "end_lon" => $lon,
                "status" => "3");

            $this->load->model("common_model");


            $this->common_model->data_update("appointment", $insert, array("id" => $this->
                    post("appointment_id")));
            $extras_ads = $this->post("extras");
            
            $extra_amount = 0;
            if (!empty($extras_ads)) {
                $extras = json_decode($this->post("extras"));
                if (!empty($extras)) {
                    foreach ($extras as $extra) {

                        $this->common_model->data_insert("appointment_extra", array(
                            "appointment_id" => $this->post("appointment_id"),
                            "title" => $extra->title,
                            "charge" => $extra->charge,
                            "qty" => $extra->qty));
                            
                            $extra_amount = $extra_amount + ($extra->charge * $extra->qty);
                    }
                }

            }
            $this->common_model->data_update("appointment",array("extra_charges"=>$extra_amount,"net_amount"=>$this->post("payment_amount")),array("id"=>$this->post("appointment_id")));
                
            /* send mail */
			$email = get_option("email_id","");
			if($email != "")
			{
				$list[]=$email;	
			}
			if(!empty($list))
			{
				$sendMail=$this->emailsender->send_complete_email($this->post("appointment_id"),$list);
			}
            /* send mail */
			
			
            $this->response(array(RESPONCE => true, DATA =>
                    "Your Appoinment Paid successfully..."), REST_Controller::HTTP_OK);
        }
    }

	/*
	used for start appointment
	@param appointment_id for appointment's id
	@param user_id for user's id
	@param lat for latitude
	@param lon for longitude
	@return response for true/false
	@return message for error message
	@return data for success message
	*/
    public function start_post()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('appointment_id', 'Appointment Id',
            'trim|required');
        $this->form_validation->set_rules('user_id', 'User ID', 'trim|required');


        if ($this->form_validation->run() == false) {
            $this->response(array(RESPONCE => false, MESSAGE => strip_tags($this->
                    form_validation->error_string())), REST_Controller::HTTP_OK);

        } else {
            $lat = $this->post("lat");
            $lon = $this->post("lon");

            $insert = array(
                //"start_at" => date("Y-m-d h:s:i"),
                "start_at" => date("Y-m-d H:i:s"),
                "start_lat" => $lat,
                "start_lon" => $lon,
                "status" => "2");

            $this->load->model("common_model");


            $this->common_model->data_update("appointment", $insert, array("id" => $this->post("appointment_id")));
			
			/* send start appointment mail*/
			$user = $this->users_model->get_user_by_id($this->post("user_id"));
			$list = array();	
			if(!empty($user))
			{
				$list[]=$user->user_email;
			}
			
			$email = get_option("email_id","");
			if($email != "")
			{
				$list[]=$email;	
			}
			if(!empty($list))
			{
				$sendMail=$this->emailsender->send_start_email($this->post("appointment_id"),$list);
			}
			/* send start appointment mail*/
			

            $this->response(array(RESPONCE => true, DATA => "Service started..."),
                REST_Controller::HTTP_OK);
        }
    }

}
?>