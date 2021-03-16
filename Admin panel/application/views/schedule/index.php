<!DOCTYPE html>
<html>
<head>
  
  <?php $this->load->view("common/common_css"); ?>
  <link rel="stylesheet" href="<?php echo base_url("theme/plugins/datepicker/jquery.timepicker.min.css"); ?>">
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
        <?php echo _l("Schedule"); ?>
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li>
            <a href="<?php echo site_url("leave/add"); ?>" class="btn btn-sm btn-default"><i class="fa fa-list"></i> <?php echo _l("Leave Of Schedule"); ?></a>
            
        </li>
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
                                        

<div class="row">
            			    				<div class="col-xs-12 col-sm-12 col-md-6">
        			    					    <div class="form-group">
                                                    <label for="morning_from">Morning Time Schedule</label>
                                                    <div class="row">
                                                    <label class="col-md-6">From</label>
                                                    <label class="col-md-6">To</label>
                                                    <div class="col-md-6">
                                                        <input type="text" name="morning_from" id="morning_from" value="<?php echo (!empty($schedule) &&  $schedule->morning_time_start != "" ) ?  date("h:i A",strtotime( $schedule->morning_time_start )) :  _get_post_back($this,"morning_from"); ?>" class="form-control input-sm" placeholder="HH:MM PP" />
                                                    </div>
                                                    <div class="col-md-6">
                                                      <input type="text" name="morning_to" id="morning_to" value="<?php echo (!empty($schedule) && $schedule->morning_time_end != "") ?  date("h:i A",strtotime( $schedule->morning_time_end )) :   _get_post_back($this,"morning_to"); ?>" class="form-control input-sm" placeholder="HH:MM PP"  />
                                                    </div>
                                                    </div>
                                                </div>
        			                        </div>
                                            
                                            
                                            <div class="col-xs-12 col-sm-12 col-md-6">
        			    					    <div class="form-group">
                                                    <label for="afternoon_from">Afternoon Time Schedule</label>
                                                    <div class="row">
														<label class="col-md-6">From</label>
														<label class="col-md-6">To</label>
														<div class="col-md-6">
															<input type="text" name="afternoon_from" id="afternoon_from" value="<?php echo (!empty($schedule) && $schedule->afternoon_time_start != "") ?  date("h:i A",strtotime( $schedule->afternoon_time_start )) :  _get_post_back($this,"afternoon_from") ?>" class="form-control input-sm" placeholder="HH:MM PP"  />
														</div>
														<div class="col-md-6">
														  <input type="text" name="afternoon_to" id="afternoon_to" value="<?php echo (!empty($schedule) && $schedule->afternoon_time_end != "") ?  date("h:i A",strtotime( $schedule->afternoon_time_end )) :  _get_post_back($this,"afternoon_to") ?>" class="form-control input-sm" placeholder="HH:MM PP"  />
														</div>
                                                    </div>
                                                </div>
        			                        </div>
                                            
                                            
                                            <div class="col-xs-12 col-sm-12 col-md-12">
        			    					    <div class="form-group">
													<div class="row">
													<label class="col-md-12">Working Days</label>
													<div class="col-md-12">
                                                    <?php 
                                                    $days = array();
                                                    if(!empty($schedule))
                                                        $days = explode(",",$schedule->working_days); ?>
                                                    <label for="sun">
                                                        <input type="checkbox" name="day[]" value="sun" <?php if(in_array('sun',$days)) { echo "checked"; } ?> id="sun" /> Sun
                                                    </label>
                                                    <label for="mon">
                                                        <input type="checkbox" name="day[]" value="mon" <?php if(in_array('mon',$days)) { echo "checked"; } ?> id="mon" /> Mon
                                                    </label>
                                                    <label for="tue">
                                                        <input type="checkbox" name="day[]" value="tue" <?php if(in_array('tue',$days)) { echo "checked"; } ?> id="tue" /> Tue
                                                    </label>

                                                    <label for="wed">
                                                        <input type="checkbox" name="day[]" value="wed" <?php if(in_array('wed',$days)) { echo "checked"; } ?> id="wed" /> Wed
                                                    </label>

                                                    <label for="thu">
                                                        <input type="checkbox" name="day[]" value="thu" <?php if(in_array('thu',$days)) { echo "checked"; } ?> id="thu" /> Thu
                                                    </label>
                                                    
                                                    <label for="fri">
                                                        <input type="checkbox" name="day[]" value="fri" <?php if(in_array('fri',$days)) { echo "checked"; } ?> id="fri" /> Fri
                                                    </label>
                                                    
                                                    <label for="sat">
                                                        <input type="checkbox" name="day[]" value="sat" <?php if(in_array('sat',$days)) { echo "checked"; } ?> id="sat" /> Sat
                                                    </label>
													</div>
													</div>
                                                </div>
                                            </div>
											
											<div class="col-md-12 col-xs-12 col-sm-12">
												<div class="form-group">
													<div class="row">
														
														<label class="col-md-4">Service allow on</label>
														<label class="col-md-4"><span class="noOfDaysDiv">No Of Days</span> &nbsp;</label>
														<label class="col-md-4">&nbsp;</label>
													
														<div class="col-md-4">
															<select class="form-control input-sm" name="on_day" id="on_day" onchange="validNoOfDays()">
																<option value="1" <?php if(!empty($schedule) && $schedule->on_day == 1) { echo "selected"; } ?> >Allow same day</option>
																<option value="2" <?php if(!empty($schedule) && $schedule->on_day == 2) { echo "selected"; } ?> >Next day</option>
																<option value="3" <?php if(!empty($schedule) && $schedule->on_day == 3) { echo "selected"; } ?> >After no of days</option>
															</select>
														</div>
														<div class="col-md-4">
															<div class="noOfDaysDiv">
																<input type="text" name="no_of_days" id="no_of_days" value="<?php echo (!empty($schedule) &&  $schedule->no_of_days != "" ) ? $schedule->no_of_days  :  0; ?>" class="form-control input-sm" placeholder="HH:MM PP"  />
															</div>
														</div>
														
													</div>
												</div>
											</div>
											
											
            			    			</div>
                                        	    
                                        <input type="submit" name="savebus" value="Save Business" class="btn btn-info btn-block"/>
            			    		                                 
              
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
<script src="<?php echo base_url("theme/plugins/datepicker/jquery.timepicker.min.js"); ?>"></script>
 <script>
   

$('#morning_from,#morning_to').timepicker({
    timeFormat: 'h:mm p',
    interval: 30,
    minTime: '6',
    maxTime: '12:00pm',
    startTime: '06:00',
    dynamic: false,
    dropdown: true,
    scrollbar: true
});

 /* $('#morning_from').on('changeTime', function() {
 }); */

$('#afternoon_from,#afternoon_to').timepicker({
    timeFormat: 'h:mm p',
    interval: 30,
    minTime: '12',
    maxTime: '11:59pm',
    startTime: '12:00',
    dynamic: false,
    dropdown: true,
    scrollbar: true
});

$('#evening_from,#evening_to').timepicker({
    timeFormat: 'h:mm p',
    interval: 30,
    minTime: '18',
    maxTime: '10:00pm',
    startTime: '18:00',
    dynamic: false,
    dropdown: true,
    scrollbar: true
});

validNoOfDays();
function validNoOfDays(){
	on_day=$("#on_day").val();
	if(on_day==3)
	{
		$(".noOfDaysDiv").show();
	}
	else
	{
		$(".noOfDaysDiv").hide();
	}	
}

   </script>      
<?php include 'third_party/crop/main.php'; ?>    
</body>
</html>

