<!DOCTYPE html>
<html>
<head>
  
  <?php $this->load->view("common/common_css"); ?>
  <link rel="stylesheet" href="<?php echo base_url("theme/bower_components/select2/dist/css/select2.min.css"); ?>" />
    <style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
    background-color: #337ab7;
    border-color: #337ab7;
    padding: 1px 10px;
    color: #fff;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
    color: #000;
    cursor: pointer;
    display: inline-block;
    font-weight: bold;
    margin-right: 2px;
}
.select2-container--default.select2-container--focus .select2-selection--multiple {
    border: solid #ccc 1px;
    outline: 0;
}
.select2-container--default .select2-selection--multiple {
    background-color: white;
    border: 1px solid #aaa;
    border-radius: 0px;
    cursor: text;
}
    </style>
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
        <?php echo _l("Pros"); ?>
        <small></small>
      </h1>
       <?php 
                $user_type_id = _get_current_user_type_id($this);
                    if($user_type_id == USER_ADMIN)
                    {
                ?>
      <ol class="breadcrumb">
        <li><a href="<?php echo site_url($this->router->fetch_class()); ?>" class="btn btn-sm btn-default"><i class="fa fa-list"></i> <?php echo _l("List"); ?></a></li>
      </ol>
      <?php } else {} ?>   
    </section>

    <!-- Main content -->
    <section class="content">
      <div id="container_message">
        <?php echo $this->session->flashdata("message"); ?>
      </div>  
      <div class="row">
        <div class="col-xs-12">
            <form action="" id="form" method="post" enctype="multipart/form-data">
            <?php if(_get_current_user_type_id($this) == USER_ADMIN){
						?>
            <div class="box box-success">
                                        <div class="box-header">
                                            <h3  class="box-title"><?php echo _l("Login Details")?></h3>
                                        </div>
                                            <div class="box-body">
                                            <div class="row">
                                           	    <div class="col-xs-6 col-sm-6 col-md-6">
                			    				     <label for="user_email"> <?php echo _l("User Email")?> <strong style="color: red;">*</strong></label>
                                                    <input type="email" class="form-control" value="<?php echo _get_post_back($field,"user_email"); ?>" id="user_email" name="user_email" placeholder="<?php echo _l("User Email")?>" required=""  data-validation="required email server"  data-validation-url="<?php echo site_url("admin/check_email_exist?email="._get_post_back($field,"user_email")); ?>"  />
                                                    <div id="email_message"></div>
                                                </div>
                                                <div class="col-xs-6 col-sm-6 col-md-6">
                                                <label for="user_password"> <?php echo _l("Password")?> <strong style="color: red;">*</strong></label>
                                                    <input type="password" class="form-control" value="<?php echo _get_post_back($field,"user_password"); ?>" id="user_password"  name="user_password" placeholder="password *" required="" data-validation="required" />
                			    				</div>
                                            </div>
                                            </div>
            </div>
					<?php }?>
          <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                           
                        <input type="hidden" name="id" value="<?php if(!empty($field) && !empty($field->id)){ echo $field->id; } ?>" />
                                        <div class="form-group">
                                            <label class=""><?php echo _l("Name"); ?> : <span class="text-danger">*</span></label>
                                            <input type="text" name="pros_name" class="form-control" value="<?php echo _get_post_back($field,'pros_name'); ?>" placeholder="<?php echo _l("Name"); ?>" required=""  data-validation="required" />
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class=""><?php echo _l("Qualification"); ?> : </label>
                                            <input type="text" name="pros_degree" class="form-control" value="<?php echo _get_post_back($field,'pros_degree'); ?>" placeholder="<?php echo _l("Degree"); ?>" />
                                        </div>
                                        <div class="form-group">
                                            <label class=""><?php echo _l("Experiance"); ?> : </label>
                                            <input type="text" name="pros_exp" class="form-control" value="<?php echo _get_post_back($field,'pros_exp'); ?>" placeholder="<?php echo _l("Experiance"); ?>" />
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                              
                                                <div class="bootstrap-timepicker">
                                                    <div class="form-group">
                                                        <label><?php echo _l("Start Time"); ?> <strong style="color: red;">*</strong></label>
                                                        <div class="input-group">
                                                            <input type="text" name="working_hour_start"  class="form-control timepicker" value="<?php echo (isset($field->working_hour_start) && !empty($field) &&  $field->working_hour_start != "" ) ?  date("h:i A",strtotime( $field->working_hour_start )) :  _get_post_back($field,"working_hour_start"); ?>" placeholder="working_hour_start">
                                                            <div class="input-group-addon">
                                                                <i class="fa fa-clock-o"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                
                                                <div class="bootstrap-timepicker">
                                                    <div class="form-group">
                                                        <label><?php echo _l("End Time"); ?> <strong style="color: red;">*</strong></label>
                                                        <div class="input-group">
                                                            <input type="text" name="working_hour_end"  class="form-control timepicker1" value="<?php echo (isset($field->working_hour_end) && !empty($field) && $field->working_hour_end != "") ?  date("h:i A",strtotime( $field->working_hour_end )) :   _get_post_back($field,"working_hour_end"); ?>" placeholder="working_hour_end">
                                                            <div class="input-group-addon">
                                                                <i class="fa fa-clock-o"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><br />
                                         <?php 
                $user_type_id = _get_current_user_type_id($this);
                    if($user_type_id == USER_ADMIN)
                    {
                ?>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="cat_id"><?php echo _l("Choose Category"); ?></label>
                                                    <select id="cat_id" name="cat_id[]" multiple="true" class="form-control select2">
                                                        
                                                        <?php 
                                                        $p_cat = array();
                                            foreach($selected_cat as $p_c){
                                                $p_cat[] = $p_c->cat_id;
                                            }
                                                     foreach($categories as $cat){
                                                            ?>
                                                            <option value="<?php echo $cat->id; ?>" <?php if(in_array($cat->id,$p_cat)){ echo "selected"; } ?>><?php echo $cat->title; ?></option>
                                                            <?php
                                                        } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div><br />
                                        <?php } ?>
                                        <div class="row">
                                            <div class="col-md-6">
                                            <div class="form-group">
                                            <label><?php echo _l("Profile Image"); ?>: <span class="text-danger">*</span> </label>
                                            <p>We recommonded to user 320x320 px Image <?php echo _l("Allow jpg,png"); ?></p>
                                            <?php if(!empty($field->pros_photo)){ ?>
                                            <div class="cat-img pull-right" style="width: 70px; height: 70px;"><img width="100%" height="100%" src="<?php echo base_url('uploads/pros/crop/small/'.$field->pros_photo); ?>" /></div>
                                            <?php } ?>
                                            <input type="file" name="pros_photo" />
                                        </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                            <div class="form-group">
                                            <label>Pros Id Proof: <span class="text-danger">*</span> </label>
                                            <p>We recommonded to user 680x320 px Image <?php echo _l("Allow jpg,png"); ?></p>
                                            <?php if(!empty($field->pros_id_proof)){ ?>
                                            <div class="cat-img pull-right" style="width: 70px; height: 70px;"><img width="100%" height="100%" src="<?php echo base_url('uploads/pros/crop/small/'.$field->pros_id_proof); ?>" /></div>
                                            <?php } ?>
                                            <input type="file" name="pros_id_proof" />
                                        </div>
                                            </div>
                                        </div>
                                        <?php if(!empty($field) && !empty($field->id)){?>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label><?php echo _l("Is Qualified"); ?> :- </label>
                                                <td><?php if($field->is_qualified == "1"){ ?><span class="label label-success">YES</span><?php } else { ?><span class="label label-danger">NO</span><?php } ?></td>
                                            </div>
                                        </div>
                                       	<?php }else{ } ?> 
                                        <hr />
                                        <input type="submit" class="btn btn-primary" name="addcatg" value="Add" />
										<a href="<?php echo site_url('pros'); ?>" class="btn btn-danger">Cancel</a>
              
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

   <script src="<?php echo base_url("theme/bower_components/select2/dist/js/select2.full.min.js"); ?>"></script>
        <script>
var datatable;
$(function () {
        $(".select2").select2();
        datatable = $('#example2').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": true,
          "ordering": true,
          "info": true,
          "autoWidth": false
        });
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
                
         $(".timepicker1").timepicker({
                  showInputs: false
                });
               
        });
    </script> 
<?php include 'third_party/crop/main.php'; ?>    
</body>
</html>

