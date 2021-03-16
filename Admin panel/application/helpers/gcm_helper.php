<?php

/*
* To change this template, choose Tools | Templates
* and open the template in the editor.
*/

/**
 * Description of GCM
 */

class GCM
{

    //put your code here
    // constructor
    function __construct()
    {

    }

    /**
     Sending Push Notification
	 @param type for notification type android or ios
	 @param fields registatoin_ids and message array
	 @return send or not
     */
    public function send($type, $fields)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';

        $api_key = get_option("fcm_server_key");

        $headers = array('Authorization: key=' . $api_key,
                'Content-Type: application/json');

        // Open connection
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        // Execute post
        $result = curl_exec($ch);
        if ($result === false)
        {
            die('Curl failed: ' . curl_error($ch));
        }
        // Close connection
        curl_close($ch);
        return $result;

    }
	
	/*
	send notification 
	@param registration_ids for register tokens array
	@param message for notification message
	@param type for notification type android or ios
	@return notification send or not
	*/
    public function send_notification($registatoin_ids, $message, $type)
    {

        $fields = array(
            'registration_ids' => $registatoin_ids,
            'data' => $message,
            );
        return $this->send($type, $fields);
    }
	
	/*
	send topics notification
	@param topics for topic
	@param message for message
	@param type for type
	@return send notification
	*/
    public function send_topics($topics, $message, $type)
    {

        $fields = array(
            'to' => $topics,
            'data' => $message,
            );
        if ($type == "ios")
        {
            $fields = array(
                'to' => $topics,
                'notification' => $message,
                'priority' => 'high',
                'content_available' => true);
        }
        return $this->send($type, $fields);
    }


}

?>