<!DOCTYPE html>
<html>
 <head>
    <?php $this->load->view("common/common_css"); ?>
     <!-- daterange picker -->
     <link rel="stylesheet" href="<?php echo base_url($this->config->item("theme_admin")."/theme/plugins/datepicker/daterangepicker-bs3.css"); ?>">
   <!-- bootstrap datepicker -->
  
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
                         Add Offer 
                          <small> Add</small>
                    </h1> 
                </section>


                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-md-6">
                            <?php  if(isset($error)){ echo $error; }
                                    echo $this->session->flashdata('message'); ?>
                            <!-- general form elements -->
                            <div class="box box-primary"> 
                                <!-- form start -->
                                <form action="" method="post" enctype="multipart/form-data">
                                    <div class="box-body">
                                        <div class="form-group">
                                                <label class="">Title : <span class="text-danger">*</span></label> 
                                                <input type="text" name="offer_title" class="form-control" placeholder="Title" value="<?php echo $field->offer_title; ?>" />   
                                                <input type="hidden" name="offer_id" value="<?php if(!empty($field) && !empty($field->offer_id)){ echo $field->offer_id; } ?>" /> 
                                         </div>
                                         <div class="form-group">
                                                <label class="">Description : <span class="text-danger">*</span></label> 
                                                <input type="text" name="offer_description" class="form-control" placeholder="Description" value="<?php echo $field->offer_description; ?>" />    
                                         </div>
                                         
                                        <div class="form-group">
                                        <label>Date and time range:</label> 
                                        <div class="input-group">
                                          <div class="input-group-addon">
                                            <i class="fa fa-clock-o"></i>
                                          </div>
                                          <input type="text" class="form-control pull-right" name="reservationtime" id="reservationtime" value="<?php echo $field->offer_start_date; ?> / <?php echo $field->offer_end_date; ?>" />
                                        </div>
                                        <!-- /.input group -->
                                      </div>
                                          
                                          
                                      <div class="form-group">
                                            <label class="">Coupon : <span class="text-danger">*</span></label> 
                                            <input type="text" name="offer_coupon" class="form-control" placeholder="CODE15" value="<?php echo $field->offer_coupon; ?>" />    
                                     </div>
                                     
                                    
                                      <div class="form-group">
                                         
                						  <label>Discount</label>
                                          <div class="input-group col-md-6"> 
                                            <input type="number" class="form-control" name="offer_discount" value="<?php echo $field->offer_discount; ?>"  min="1" max="100"/>
                                            <span class="input-group-addon">%</span>
                                          </div>
                                         
                                      </div> 
                                   
                                   <div class="form-group"> 
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="cat_status" id="optionsRadios1" value="1"  <?php if($getcat->status == 1){ echo 'checked=""'; } ?> />
                                                     <?php echo _l("Active"); ?>
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="cat_status" id="optionsRadios2" value="0" <?php if($getcat->status == 0){ echo 'checked=""'; } ?> />
                                                     <?php echo _l("Deactive"); ?>
                                                </label>
                                            </div>
                                            <p class="help-block"> <?php echo _l("Set Categories Status."); ?></p>
                                        </div>
                                    </div><!-- /.box-body --> 

                                    <div class="box-footer">
                                        <input type="submit" class="btn btn-primary" name="add_offer" value="Add" />
                                       
                                    </div>
                                </form>
                            </div><!-- /.box -->
                        </div>
                    </div>
                    <!-- Main row -->
                </section><!-- /.content -->
            </aside><!-- /.right-side -->
        </div><!-- /.content-wrapper -->
      
      <?php  $this->load->view("common/footer"); ?>  

      
      <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
      <div class="control-sidebar-bg"></div>
    </div><!-- ./wrapper -->

<?php $this->load->view("common/common_js"); ?>
       <!-- date-range-picker -->
<script src="<?php echo base_url($this->config->item("theme_admin")."/theme/plugins/datepicker/moment.min.js"); ?>"></script> 
<script src="<?php echo base_url($this->config->item("theme_admin")."/theme/plugins/datepicker/daterangepicker.js"); ?>"></script>
<script src="<?php echo base_url($this->config->item("theme_admin")."/theme/plugins/datepicker/bootstrap-datepicker.js"); ?>"></script> 
 <script>
  $(function () {
         //Date range picker with time picker
        $('#reservationtime').daterangepicker({ timePicker: true, timePickerIncrement: 30, format: 'YYYY/MM/DD h:mm A' })
     $( "#reservationtime" ).daterangepicker({ 
        minDate: 0,
        'startDate': dateToday
        });
          });
 </script>
  </body>
</html>
