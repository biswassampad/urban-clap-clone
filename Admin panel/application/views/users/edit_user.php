<!DOCTYPE html>
<html>
<head>
  
  <?php $this->load->view("common/common_css"); ?>
  <!-- daterange picker -->
     <link rel="stylesheet" href="<?php echo base_url($this->config->item("theme_admin")."/theme/plugins/datepicker/datepicker3.css"); ?>">
	 
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
             Users
            <small> Manage Users</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i>  Admin</a></li>
            <li class="active"> Add Users</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header">
                           
                        </div>
                        <div class="box-body">
                            <form role="form" action="" method="post" enctype="multipart/form-data">
                              <div class="box-body">
                              <?php 
                                echo $this->session->flashdata("message");
                               ?>
                               <div class="col-md-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="user_fullname"> Full Name <strong style="color: red;">*</strong></label>
                                            <input type="text" class="form-control" id="user_fullname" value="<?php echo $user->user_fullname; ?>" name="user_fullname" placeholder="User Full Name" required="" />
                                        </div>
                                    </div>
                                </div>
                                </div>
                                <div class="col-md-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="user_email"> <?php echo _l("User Email")?> <strong style="color: red;">*</strong></label>
                                            <input type="email" class="form-control" id="user_email" disabled="" readonly=""  value="<?php echo $user->user_email; ?>"  name="user_email" placeholder="user@gmail.com" />
                                        </div>
                                    </div>
                                </div>
                                </div>
                                <div class="col-md-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="user_fullname"> Birth Date <strong style="color: red;">*</strong></label>
                                            <input type="text" class="form-control datepicker" id="user_bdate" value="<?php echo date(COM_DATE_FORMATE,strtotime($user->user_bdate)); ?>" name="user_bdate" placeholder="User Birth Date" required="" />
                                        </div>
                                    </div>
                                </div>
                                </div>
                                <div class="col-md-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="user_email"> Phone  <strong style="color: red;">*</strong></label>
                                            <input type="text" class="form-control" id="user_phone"  value="<?php echo $user->user_phone; ?>"  name="user_phone" placeholder="Phone" />
                                        </div>
                                    </div>
                                </div>
                                </div>
                                <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-sm-4">
                                        <div class="bus-product thumbnail">   
                                            <?php
                                                $proicon = base_url($this->config->item('theme_folder')."/images/generic-profile.png");
                                                if($user->user_image != ""){
                                                $proicon = base_url("uploads/profile/crop/small/".$user->user_image); } 
                                            ?>                                   
                                            <div class="pro-icon1" style="background-image: url('<?php echo $proicon; ?>'); height: 150px; width: 100%;"></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class=""><?php echo _l("Choose User Photo")?></label>
                                            <input type="file" name="user_image" onchange="readURL1(this)" value="<?php echo $user->user_image;  ?>"  accept="image/*" />
                                        </div>
                                    </div>
                                </div>
                                </div>
                                <div class="col-md-6">
                                <!--div class="form-group">
                                    <div class="col-md-12">
                                    <div class="checkbox">
                                      <label for="status">
                                        <input type="checkbox" id="status" name="status"   <?php echo ($user->user_status == 1) ? "checked" : ""; ?>  /> Status
                                      </label>
                                    </div>
                                    </div>
                                </div-->
								<?php if(_get_current_user_type_id($this) == USER_ADMIN && $user->user_id != _get_current_user_id($this)){ ?>
								<div class="form-group"> 
									<div class="radio">
										<label>
											<input type="radio" name="status" id="status" value="1"  <?php if (1 == _get_post_back($user, 'user_status')) {
                                    echo 'checked=""';
                                } ?> />
											 <?php echo _l("Active"); ?>
										</label>
									</div>
									<div class="radio">
										<label>
											<input type="radio" name="status" id="status" value="0" <?php if (0 == _get_post_back($user, 'user_status')) {
                                    echo 'checked=""';
                                } ?> />
											 <?php echo _l("Deactive"); ?>
										</label>
									</div>
								</div>
								<?php } ?>
                                </div>
                                
                              </div><!-- /.box-body -->
            
                              <div class="box-footer">
                                <button type="submit" class="btn btn-primary"> Update</button>
								<a href="<?php echo site_url('appuser'); ?>" class="btn btn-danger">Cancel</a>			
                              </div>
                            </form>
                        </div>
                    </div>
                </div>
               
                
            </div>
        </section><!-- /.content -->
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

<script src="<?php echo base_url($this->config->item("theme_admin")."/theme/plugins/datepicker/bootstrap-datepicker.js"); ?>"></script> 
<script>
$(".datepicker").datepicker({format:"dd-mm-yyyy"});
</script>

<?php include 'third_party/crop/main.php'; ?>    
</body>
</html>

