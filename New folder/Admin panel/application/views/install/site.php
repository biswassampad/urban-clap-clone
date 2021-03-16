<!DOCTYPE html>
<html>
  <head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Site Settings</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <?php $this->load->view("common/common_css"); ?>
  </head>
  <body class="hold-transition login-page" style="">
    <div class="row">
    <div class="col-md-6 col-md-offset-3">
    <h3><?php echo _l("Installation") ?></h3>
    <?php
    echo _get_flash_message();
    echo form_open();
    echo _box_open(_l("Site Settings"));
    echo _input_field("site_name",_l("Site Name"),get_option("site_name"),'text',array("data-validation"=>"required"));
    echo _l("Admin Login")."</br></br>";
    echo _input_field("user_email",_l("Admin Email"),'','email',array("data-validation"=>"email"));
    echo _input_field("user_password",_l("Admin Password"),'','password',array("data-validation"=>"required"));
    echo _input_field("user_password_r",_l("Re Enter Password"),'','password',array("data-validation"=>"required"));
    echo _l("Common Settings")."</br></br>";
    
    echo _select("default_timezone",$time_zones,_l("Time Zone"),array("key"));
    echo _select("date_default_timezone",$date_time_zone,_l("Date Time Zone"),array("value"));
    echo _select("default_country",$countries,_l("Country"),array("iso_code_2","country_name"));
    
    echo _input_field("currency",_l("Currency"),get_option("currency"),'text',array("data-validation"=>"required"));
    

    echo _submit_button(_l("Add"));
    echo _box_close();
    echo form_close();
    ?>
    </div>
    </div>


   
    <?php $this->load->view("common/common_js"); ?>

    <script>
    $(function(){
       $("#default_timezone").change(function(){
            $('#date_default_timezone').html("");
            var time_zone = $(this).val();
            
            $.ajax({
              method: "POST",
              url: '<?php echo site_url("commonjson/date_time_zone_json"); ?>',
              data: { time_zone: time_zone }
            })
              .done(function( data ) {
                    
                     $.each(data, function(index, element) {
                                $('#date_default_timezone').append("<option value='"+element+"'>"+element+"</option>");
                            });
                            $("#date_default_timezone").trigger("select2:updated");
            }); 
       }); 
    });
    </script>
  </body>
</html>
