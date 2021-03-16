<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminLTE 2 | Data Tables</title>
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
             Change Password
            <small>Manage Password</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Admin</a></li>
            <li class="active"> Change Password</li>
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
                        
                            <form role="form" action="" method="post">
                             <?php  if(isset($error)){ echo $error; }
                                    echo $this->session->flashdata('message'); ?>
                              <div class="box-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="c_password"> Current Password <strong style="color: red;">*</strong></label>
                                            <input type="password" class="form-control" id="c_password" name="c_password" placeholder="Current Password *" required="" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="n_password"> New Password <strong style="color: red;">*</strong></label>
                                            <input type="password" class="form-control" id="n_password" name="n_password" placeholder="New Password *" required="" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="r_password"> Re-Type New Password<strong style="color: red;">*</strong></label>
                                            <input type="password" class="form-control" id="r_password" name="r_password" placeholder="Re-Type New password *" required="" />
                                        </div>
                                    </div>
                                </div>
                              </div>
                             </div><!-- /.box-body -->
                              <div class="box-footer">
                                <button type="submit" class="btn btn-primary"> Submit Change Password</button>
                              </div>
                            </form>
                            </div>
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
</body>
</html>
