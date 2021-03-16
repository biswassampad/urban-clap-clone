<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crop extends CI_Controller {
    public function __construct()
    {
                parent::__construct();
    }
    public function image(){
        $post = $this->input->post();
        
        $crop = new CropAvatar(
          isset($post['avatar_src']) ? $post['avatar_src'] : null,
          isset($post['avatar_data']) ? $post['avatar_data'] : null,
          isset($_FILES['avatar_file']) ? $_FILES['avatar_file'] : null
        );
        
        $response = array(
          'state'  => 200,
          'message' => $crop -> getMsg(),
          'result' => $crop -> getResult(),
          'file_url' => $crop -> getFullUrl()
        );
        
        echo json_encode($response);
    }
}
?>