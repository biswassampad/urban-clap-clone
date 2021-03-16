<!DOCTYPE html>
<html>
 <head>
    <?php $this->load->view("common/common_css"); ?>
    <!-- daterange picker -->
     <link rel="stylesheet" href="<?php echo base_url("theme/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css"); ?>" />
     <link rel="stylesheet" href="<?php echo base_url("theme/plugins/timepicker/bootstrap-timepicker.min.css"); ?>" />
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
                         Add Leave 
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
                                    <input type="hidden" name="leave_id" value="<?php if(!empty($field) && !empty($field->leave_id)){ echo $field->leave_id; } ?>" />
                                        
                                         <label>Leave Date *</label>
                                    <div class="form-group">
                                        <div class="input-group date">
                                          <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                          </div>
                                          <input type="text" class="form-control pull-right" name="date" id="datepicker" placeholder="Leave Date" value="<?php echo _get_post_back($field,'date'); ?>">
                                        </div>
			    			          </div>
                                        
                                         
                                        <div class="form-group">
                                        <label>Date and time range:</label>
                                        <div class="row">
                                            <div class="col-md-6 bootstrap-timepicker"> 
                                        <div class="input-group ">
                                          <div class="input-group-addon">
                                            <i class="fa fa-clock-o"></i>
                                          </div>
                                          <input type="text" class="form-control timepicker pull-right" name="start_time" value="<?php echo date("h:i A",strtotime(_get_post_back($field,'start_time')));  ?>"  id="start_time" />
                                        </div>
                                            </div>
                                            <div class="col-md-6 bootstrap-timepicker">
                                        <div class="input-group">
                                          <div class="input-group-addon">
                                            <i class="fa fa-clock-o"></i>
                                          </div>
                                          <input type="text" class="form-control timepicker pull-right" name="end_time" value="<?php echo date("h:i A",strtotime(_get_post_back($field,'end_time'))); ?>"  id="end_time" />
                                        </div>
                                            </div>
                                        </div>    
                                        <!-- /.input group -->
                                      </div>
                                        <div class="form-group">
                                                <label class="">Reason : <span class="text-danger">*</span></label> 
                                                <input type="text" name="reason" value="<?php echo _get_post_back($field,'reason'); ?>"  class="form-control" placeholder="reason" />    
                                         </div>  
                                          
                                 
                                    </div><!-- /.box-body --> 

                                    <div class="box-footer">
                                        <input type="submit" class="btn btn-primary" name="add_offer" value="Add" />
										<a href="<?php echo site_url('admin/dashboard'); ?>" class="btn btn-danger">Cancel</a>
                                       
                                    </div>
                                </form>
                            </div><!-- /.box -->
                        </div>
                        <div class="col-md-6">
                           
                    <div class="row">
                        <div class="col-xs-12">
                            <?php  if(isset($error)){ echo $error; }
                                    echo $this->session->flashdata('success_req'); ?>
                                    
                            <div class="box box-primary"> 
                               
                                <div class="box-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Date </th>                                                 
                                                <th> Start Date </th>
                                                <th> End Date</th>
                                                <th> Reason</th>
                                                <th class="text-center" style="width: 100px;"> Action </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                           <?php foreach($offer as $off){ ?>
                                            <tr> 
                                                <td><?php echo $off->date; ?></td>
                                                <td> <?php echo date("h:i A",strtotime($off->start_time)); ?></td>
                                                <td> <?php echo date("h:i A",strtotime($off->end_time)); ?></td>
                                                <td> <?php echo $off->reason; ?></td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        <?php echo anchor('leave/edit/'.$off->leave_id, '<i class="fa fa-edit"></i>', array("class"=>"btn btn-success")); ?>
                                                        <?php echo anchor('leave/delete_leave/'.$off->leave_id, '<i class="fa fa-times"></i>', array("class"=>"btn btn-danger", "onclick"=>"return confirm('Are you sure delete?')")); ?>
                                                     </div>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div><!-- /.box-body -->
                            </div><!-- /.box -->
                        </div>
                    </div>
                    <!-- Main row -->
              
                        </div>
                        
                        
                    </div>
                    <!-- Main row -->
                </section><!-- /.content -->
            </aside><!-- /.right-side -->
        </div><!-- /.content-wrapper -->
      
      <?php $this->load->view("common/footer"); ?>

        
      <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
      <div class="control-sidebar-bg"></div>
    </div><!-- ./wrapper -->
<?php $this->load->view("common/common_js"); ?>

        <script src="<?php echo base_url("theme/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"); ?>"></script> 
        <script src="<?php echo base_url("theme/plugins/timepicker/bootstrap-timepicker.min.js"); ?>"></script>

      <script>
  $(function () {
  $('.timepicker').timepicker({
      showInputs: true
    });
    //Date picker
    $('#datepicker').datepicker({
        format: 'yyyy/mm/dd'
    });
    
    
  })
</script>
       <!-- date-range-picker -->

  </body>
</html>
