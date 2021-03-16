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
                          <small> <?php echo _l("Site"); ?></small>
                    </h1> 
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-md-6">
                            <?php  _get_flash_message();
                            echo form_open();
                            echo _box_open();
                            echo _input_field("site_name",_l("Site Name"),$setting["site_name"],"text",array("data-validation"=>"required"));
                            
                            echo _input_field("email_id",_l("Email ID"),$setting["email_id"],"email",array("data-validation"=>"email"));
                            echo _select("default_timezone",$time_zones,_l("Time Zone"),array("key"),$setting["default_timezone"]);
                            echo _select("date_default_timezone",$date_time_zone,_l("Date Time Zone"),array("value"),$setting["date_default_timezone"]);
                            
							              echo _input_field("per_day_appointment",_l("Per Day Appointment"),$setting["per_day_appointment"],"number",array("data-validation"=>"required"));
                            echo _input_field("currency",_l("Currency"),get_option("currency"),'text',array("data-validation"=>"required"));
    
                            echo _submit_button(_l("Add")); 
                            echo _box_close();
                            echo form_close();    
                            ?>
                            
                        </div>
                    </div>
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