<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin | Dashboard</title>
    <?php  $this->load->view("admin/common/common_css"); ?>
	<!-- daterange picker -->
     <link rel="stylesheet" href="<?php echo base_url($this->config->item("theme_admin")."/theme/plugins/datepicker/datepicker3.css"); ?>">
  </head>
  <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

      <?php  $this->load->view("admin/common/common_header"); ?>
      <!-- Left side column. contains the logo and sidebar -->
      <?php  $this->load->view("admin/common/common_sidebar"); ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
             <?php echo _l("Users")?>
            <small><?php echo _l(" Manage Users")?></small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i>  <?php echo _l(" Admin")?></a></li>
            <li class="active"> <?php echo _l(" Add Users")?></li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-6">
                    <div class="box">
                        <div class="box-header">
                           
                        </div>
                        <div class="box-body">
                        
                            <form role="form" action="" method="post" enctype="multipart/form-data">
                             <?php  if(isset($error)){ echo $error; }
                                    echo $this->session->flashdata('message'); ?>
                              <div class="box-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="user_fullname"> <?php echo _l("Full Name")?> <strong style="color: red;">*</strong></label>
                                            <input type="text" class="form-control" id="user_fullname" name="user_fullname" placeholder="User Full Name *" required="" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="user_email"> <?php echo _l("User Email")?> <strong style="color: red;">*</strong></label>
                                            <input type="email" class="form-control" id="user_email" name="user_email" placeholder="user@gmail.com *" required="" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="user_password"> <?php echo _l("Password")?> <strong style="color: red;">*</strong></label>
                                            <input type="password" class="form-control" id="user_password" name="user_password" placeholder="password *" required="" />
                                        </div>
                                    </div>
                                </div>
                              </div>
                              <div class="form-group">
                                <div class="col-sm-4">
                                    <div class="bus-product thumbnail">   
                                        <?php
                                            $proicon = base_url($this->config->item('theme_folder')."/images/generic-profile.png");
                                        ?>                                 
                                        <div class="pro-icon" style="background-image: url('<?php echo $proicon; ?>'); height: 120px; width: 100%;"></div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class=""><?php echo _l("Choose User Photo")?></label>
                                        <input type="file" name="user_image" onchange="readURL1(this)"   /> 
                                    </div>
                                </div>
                              </div>
                              <div class="form-group">
                                <div class="col-md-12">
                                    <div class="checkbox">
                                          <label for="status">
                                            <input type="checkbox" id="status" name="status"  />   <?php echo _l("Status")?>
                                          </label>
                                    </div>
                                </div>
                              </div>
                             </div><!-- /.box-body -->
                              <div class="box-footer">
                                <button type="submit" class="btn btn-primary"> <?php echo _l("Submit")?></button>
                              </div>
                            </form>
                            </div>
                        </div>
                    </div>
                </div>
               
                
            </div>
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      
      <?php  $this->load->view("admin/common/common_footer"); ?>  

      
      <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
      <div class="control-sidebar-bg"></div>
    </div><!-- ./wrapper -->

    <?php  $this->load->view("admin/common/common_js"); ?>
	
	<script src="<?php echo base_url($this->config->item("theme_admin")."/theme/plugins/datepicker/bootstrap-datepicker.js"); ?>"></script> 
	<script>
	$(".datepicker").datepicker({format:"dd-mm-yyyy"});
	</script>
    <script>
   function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    jQuery('.pro-icon').css("background-image","url("+e.target.result+")");
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
   </script> 
       <style>
       .pro-icon { background-position: center; background-size: cover; background-repeat: no-repeat; }
       </style>    
    <script>
      $(function () {
        
        $('#example2').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": true,
          "ordering": true,
          "info": true,
          "autoWidth": false
        });

      });
    </script>
    <script>
    $(function(){
       $(".select2").select2();
    });
    </script>
    
  </body>
</html>
