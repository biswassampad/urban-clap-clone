<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php $this->load->view("common/common_css"); ?>
  </head>
  <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

        <?php  $this->load->view("common/header"); ?>
        <!-- Left side column. contains the logo and sidebar -->
        <?php  $this->load->view("common/sidebar"); ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                         <?php echo _l("Settings"); ?> 
                          <small> <?php echo _l("Notification"); ?></small>
                    </h1> 
                </section>

                <!-- Main content -->
                <section class="content">
                    <?php  echo _box_open();
                    _get_flash_message();
                                
                    ?>
                    <ul class="nav nav-tabs">
                      <li class="active"><a data-toggle="tab" href="#menu0">FCM</a></li>
                      <!--<li class=""><a data-toggle="tab" href="#menu1">One Signal</a></li>-->
                    </ul>
                    
                    <div class="tab-content">
                      <div id="menu0" class="tab-pane fade in active">
                        <h3>FCM</h3>
                        <div class="row">
                            <div class="col-md-6">
                            <?php 
                                echo form_open();
                                echo _checkbox("fcm_enable",_l("Enable"),(isset($setting["fcm_enable"])) ? $setting["fcm_enable"] : "",array(),(isset($setting["fcm_enable"]) && $setting["fcm_enable"]=="on") ? true : false);
                                
                                echo _input_field("fcm_server_key",_l("FCM Server Key"),(isset($setting["fcm_server_key"]))? $setting["fcm_server_key"] : "","text",array("data-validation"=>"required"));
                                echo "<hr />";
                                echo _input_field("fcm_topic",_l("Topic"),(isset($setting["fcm_topic"]))? $setting["fcm_topic"] : "","text",array("data-validation"=>"required"));
                                echo "<p>This will send notification to common topics</p>";
                                echo anchor("http://console.firebase.google.com",_l("Create your own account on http://console.firebase.google.com"));
                                
                                echo "<hr />";
                                echo _submit_button(_l("Save"),array("name"=>"fcm")); 
                                
                                echo form_close();        
                            ?>
                            </div>
                        </div>    
                      </div>
                      <!--<div id="menu1" class="tab-pane fade">
                        <h3>One Signal</h3>
                        <div class="row">
                            <div class="col-md-6">
                            <?php 
                                echo form_open();
                                echo _checkbox("onesignal_enable",_l("Enable"),(isset($setting["onesignal_enable"])) ? $setting["onesignal_enable"] : "",array(),(isset($setting["onesignal_enable"]) && $setting["onesignal_enable"]=="on") ? true : false);
                                
                                echo _input_field("onesignal_app_id",_l("App ID"),(isset($setting["onesignal_app_id"]))? $setting["onesignal_app_id"] : "","text",array("data-validation"=>"required"));
                                echo _input_field("onesignal_api_key",_l("Api Key"),(isset($setting["onesignal_api_key"]))? $setting["onesignal_api_key"] : "","text",array("data-validation"=>"required"));
                                echo anchor("https://onesignal.com",_l("Create your own account on https://onesignal.com"));
                                echo "<hr />";
                                echo _submit_button(_l("Save"),array("name"=>"onesignal")); 
                                
                                echo form_close();        
                            ?>
                            </div>
                        </div>    
                      </div> -->
                    </div>
                    
                    <?php echo _box_close(); ?>
                    <!-- Main row -->
                 </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      
      <!-- /.content-wrapper -->

    <?php $this->load->view("common/footer"); ?>
    
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<?php $this->load->view("common/common_js"); ?>
  </body>
</html>