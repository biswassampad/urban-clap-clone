<!DOCTYPE html>
<html>
<head>
 
  <?php $this->load->view("common/common_css"); ?>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php $this->load->view("common/header"); ?>
  <!-- Left side column. contains the logo and sidebar -->
  <?php $this->load->view("common/sidebar"); ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Box Offices
            <small>Manage Theaters</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Admin</a></li>
            <li class="active">Box Offices</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-5">
                    <div class="box">
                        <div class="box-header">
                            Add new Boxoffice
                        </div>
                        <div class="box-body">
                        
                            <form role="form" action="" method="post">
                              <div class="box-body">
                                <div class="form-group">
                                  <label for="user_type">User Type</label>
                                  <input type="text" class="form-control" id="user_type" name="user_type" placeholder="Enter User Type">
                                </div>
                              </div><!-- /.box-body -->
            
                              <div class="box-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                              </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                
                
            <div class="box">
                <div class="box-header">
                    List of User Types
                </div>
                <div class="box-body">
                <table id="example2" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th>User Types</th>
                        <th>Access</th>
                        <th width="80">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                <?php foreach($user_types as $user_type){
                    ?>
                    <tr>
                        <td><?php echo $user_type->user_type_title; ?></td>
                        <td><a href="<?php echo site_url("access/user_access/".$user_type->user_type_id); ?>" class="btn btn-default">Set Access</a></td>
                        <td>    
                            <a href="<?php echo site_url("access/user_type_edit/".$user_type->user_type_id); ?>" class="btn btn-success"><i class="fa fa-edit"></i></a>
                            <!--<a href="<?php echo site_url("access/user_type_delete/".$user_type->user_type_id) ?>" class="btn btn-danger"><i class="fa fa-remove"></i></a>-->
                        </td>
                    </tr>
                    <?php
                } ?>
                    </tbody>
                </table>
                </div>
            </div>
                </div>
                
            </div>
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      
      <?php $this->load->view("common/footer"); ?>  
</div>
<!-- ./wrapper -->
  <?php $this->load->view("common/common_js"); ?>  
  
</body>
</html>
