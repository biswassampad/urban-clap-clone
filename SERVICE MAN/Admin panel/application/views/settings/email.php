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
                          <small> <?php echo _l("Email"); ?></small>
                    </h1> 
                </section>

                <!-- Main content -->
                <section class="content">
                    <?php  echo _box_open();
                    _get_flash_message();
                                
                    ?>
                    <ul class="nav nav-tabs">
                      <li class="<?php if(!isset($setting["email_provider"]) || $setting["email_provider"] == ""){ echo "active"; } echo (isset($setting["email_provider"]) && $setting["email_provider"] == "php_mail") ? "active" : ""; ?>"><a data-toggle="tab" href="#menu0">PHP Mail</a></li>
                      <li class="<?php echo (isset($setting["email_provider"]) && $setting["email_provider"] == "smtp_mail") ? "active" : ""; ?>"><a data-toggle="tab" href="#menu1">SMTP Mail</a></li>
                      <li class="<?php echo (isset($setting["email_provider"]) && $setting["email_provider"] == "sendgrid") ? "active" : ""; ?>"><a data-toggle="tab" href="#menu2">Send Grid</a></li>
                    </ul>
                    
                    <div class="tab-content">
                      <div id="menu0" class="tab-pane fade <?php  if(!isset($setting["email_provider"]) || $setting["email_provider"] == ""){ echo "in active"; } echo (isset($setting["email_provider"]) && $setting["email_provider"] == "php_mail") ? "in active" : ""; ?>">
                        <h3>PHP Mail</h3>
                        <div class="row">
                            <div class="col-md-6">
                            <?php 
                                echo form_open();
                                echo _radio("email_provider",array("php_mail"=>"Php Mail"),(isset($setting["email_provider"])) ? $setting["email_provider"] : "",_l("Enable"));
                                echo _input_field("email_sender",_l("Sender Email"),(isset($setting["email_sender"]))? $setting["email_sender"] : "","text",array("data-validation"=>"email"));
                                echo _submit_button(_l("Save"),array("name"=>"php_mail")); 
                                
                                echo form_close();        
                            ?>
                            </div>
                        </div>    
                      </div>
                      <div id="menu1" class="tab-pane fade <?php echo (isset($setting["email_provider"]) && $setting["email_provider"] == "smtp_mail") ? "in active" : ""; ?>">
                        <h3>SMTP Mail</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <?php  
                                echo form_open();
                                
                                echo _radio("email_provider",array("smtp_mail"=>"SMTP"),(isset($setting["email_provider"])) ? $setting["email_provider"] : "",_l("Enable"));
                                
                                echo _select("smtp_crypto",array("ssl"=>"SSL","tls"=>"TLS"),_l("Encript"),array(),(isset($setting["smtp_crypto"]))? $setting["smtp_crypto"] : "ssl",array("data-validation"=>"required"));
                                
                                echo _input_field("smtp_host",_l("Smtp Host"),(isset($setting["smtp_host"]))? $setting["smtp_host"] : "","text",array("data-validation"=>"required"));
                                echo _input_field("smtp_port",_l("Smtp Port"),(isset($setting["smtp_port"])) ? $setting["smtp_port"] : "","text",array("data-validation"=>"required"));
                                echo _input_field("smtp_user",_l("Smtp User"),(isset($setting["smtp_user"])) ? $setting["smtp_user"] : "","text",array("data-validation"=>"required"));
                                echo _input_field("smtp_pass",_l("Smtp Password"),(isset($setting["smtp_pass"])) ? $setting["smtp_pass"] : "","text",array("data-validation"=>"required"));
                                
                                //echo _radio("email_on_appointment_book",array("yes"=>"Yes","no"=>"No"),(isset($setting["email_on_appointment_book"])) ? $setting["email_on_appointment_book"] : "",_l("Allow to send email on appointment booking"));
                                //echo _radio("email_on_appointment_status",array("yes"=>"Yes","no"=>"No"),(isset($setting["email_on_appointment_status"])) ? $setting["email_on_appointment_status"] : "",_l("Allow to send email on booking status change"));
                                
                                echo "<hr />";
                                echo _input_field("email_sender",_l("Sender Email"),(isset($setting["email_sender"]))? $setting["email_sender"] : "","text",array("data-validation"=>"email"));
                                echo _submit_button(_l("Add"),array("name"=>"smtp")); 
                                
                                echo form_close();    
                                ?>
                                
                            </div>
                        </div>
                        
                      </div>
                      <div id="menu2" class="tab-pane fade <?php echo (isset($setting["email_provider"]) && $setting["email_provider"] == "sendgrid") ? "in active" : ""; ?>">
                        <h3>SendGrid</h3>
                        <div class="row">
                            <div class="col-md-6">
                            <?php 
                                echo form_open();
                                echo _radio("email_provider",array("sendgrid"=>"SendGrid"),(isset($setting["email_provider"])) ? $setting["email_provider"] : "",_l("Email Provider"));
                                echo _input_field("sendgrid_api_key",_l("Api Key"),(isset($setting["sendgrid_api_key"]))? $setting["sendgrid_api_key"] : "","text",array("data-validation"=>"required"));
                                echo anchor("http://sendgrid.com",_l("Create your own account on http://sendgrid.com"));
                                
                                echo "<hr />";
                                echo _input_field("email_sender",_l("Sender Email"),(isset($setting["email_sender"]))? $setting["email_sender"] : "","text",array("data-validation"=>"email"));
                                echo _submit_button(_l("Save"),array("name"=>"sendgrid")); 
                                
                                echo form_close();        
                            ?>
                            </div>
                        </div>
                      </div>
                      
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