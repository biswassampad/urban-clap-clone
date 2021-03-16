<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminLTE 2 | Data Tables</title>
  
  <?php $this->load->view("common/common_css"); ?>
  <link rel="stylesheet" href="<?php echo base_url("theme/bower_components/bootstrap-datepicker/daterangepicker.css"); ?>" />
    <link rel="stylesheet" href="<?php echo base_url("theme/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css"); ?>" />
    <link rel="stylesheet" href="<?php echo base_url("theme/plugins/timepicker/bootstrap-timepicker.min.css"); ?>" />
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
        <?php echo _l("Services"); ?>
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo site_url($this->router->fetch_class()); ?>" class="btn btn-sm btn-default"><i class="fa fa-list"></i> <?php echo _l("List"); ?></a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div id="container_message">
        <?php echo $this->session->flashdata("message"); ?>
      </div>  
      <div class="row">
        <div class="col-xs-12">
            <form action="" id="form" method="post" enctype="multipart/form-data">
            
          <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                                        
                        <input type="hidden" name="id" value="<?php if(!empty($field) && !empty($field->id)){ echo $field->id; } ?>" />
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="">Appointment Date : <span class="text-danger">*</span></label>
                                            <div class="input-group date">
                                          <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                          </div>
                                          <input type="text" class="form-control pull-right" name="appointment_date" id="datepicker" value="<?php echo _get_post_back($field,'appointment_date'); ?>" placeholder="Appointment Date" required=""  data-validation="required" >
                                        </div>
                                        </div> 
                                    </div>
                                    <div class="col-md-6">
                                        <div class="bootstrap-timepicker">
                                                    <div class="form-group">
                                                        <label>Start Time <strong style="color: red;">*</strong></label>
                                                        <div class="input-group">
                                                            <input type="text" name="start_time"  class="form-control timepicker" value="<?php echo _get_post_back($field,'start_time'); ?>" placeholder="Start Time">
                                                            <div class="input-group-addon">
                                                                <i class="fa fa-clock-o"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="">Time Token : <span class="text-danger">*</span></label>
                                            <select class="form-control" name="time_token">
                                                    <option <?php if(!empty($field) && $field->time_token == 1) { echo "selected"; } ?>>1</option>
                                                    <option <?php if(!empty($field) && $field->time_token == 2) { echo "selected"; } ?>>2</option>
                                                    <option <?php if(!empty($field) && $field->time_token == 3) { echo "selected"; } ?>>3</option>
                                            </select>
                                        </div>
                                     </div>
                                    <div class="col-md-6">  
                                        <div class="form-group">
                                            <label class="">Promo Code : <span class="text-danger">*</span></label>
                                            <input type="text" name="promo_code" class="form-control" value="<?php echo _get_post_back($field,'promo_code'); ?>" placeholder="Promo Code" />
                                        </div>
                                     </div>
                                    <div class="col-md-6">   
                                        <div class="form-group">
                                            <label class="">Payment Amount : </label>
                                            <input type="text" name="payment_amount" class="form-control" value="<?php echo _get_post_back($field,'payment_amount'); ?>" placeholder="Payment Amount"  data-validation="required number" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="">Total Time : </label>
                                            <input type="text" name="total_time" class="form-control" value="<?php echo _get_post_back($field,'total_time'); ?>" placeholder="12:30:10" />
                                        </div>
                                    </div>
                                    
										<div class="row">
                                            <div class="col-md-4" style="padding-left: 30px;"> 
                                                <div class="form-group"> 
                                                    <div class="radio">
                                                        <label class="text-success">
                                                            <input type="radio" name="status" id="optionsRadios1" value="1"  checked="" />
                                                            <strong><?php echo _l("Active"); ?></strong>
                                                        </label>
                                                    </div>
                                                    <div class="radio">
                                                        <label class="text-danger">
                                                            <input type="radio" name="status" id="optionsRadios2" value="0" />
                                                            <strong><?php echo _l("Deactive"); ?></strong>
                                                        </label>
                                                    </div>
                                                    <p class="help-block">Set Appoinment Status.</p>
                                                </div>        
                                            </div>
                                        </div>									
                                        <input type="submit" class="btn btn-primary" name="addcatg" value="Add" />
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
          </form>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
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

<!-- InputMask -->
<script src="<?php echo base_url("theme/plugins/input-mask/jquery.inputmask.js"); ?>"></script>
<script src="<?php echo base_url("theme/plugins/input-mask/jquery.inputmask.date.extensions.js"); ?>"></script>
<script src="<?php echo base_url("theme/plugins/input-mask/jquery.inputmask.extensions.js"); ?>"></script>

<script>
	$(document).ready(function(){
		    $('#time').inputmask('99:99', { 'placeholder': 'HH:MM' });
		});
</script>
<script src="<?php echo base_url("theme/bower_components/bootstrap-datepicker/daterangepicker.js"); ?>"></script>   
        <script src="<?php echo base_url("theme/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"); ?>"></script> 
      <script>
  $(function () {
  
    //Date picker
    $('#datepicker').datepicker({
        format: 'yyyy/mm/dd'
    })
    
  })
</script>
<script src="<?php echo base_url("theme/plugins/timepicker/bootstrap-timepicker.min.js"); ?>" type="text/javascript"></script>
    <script>
    $(function() {
         $(".timepicker").timepicker({
                  showInputs: false
                });
               
        });
    </script>

<?php include 'third_party/crop/main.php'; ?>    
</body>
</html>

