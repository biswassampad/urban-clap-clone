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
        List App Users
        <small></small>
      </h1>
      
    </section>

    <!-- Main content -->
    <section class="content">
      <div id="container_message">
        <?php echo $this->session->flashdata("message"); ?>
      </div>  
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Phone</th>
                  <th>Birth Date</th>
                  <th>User Date</th>
                  <th>Image</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($offer as $dt){
                    ?>
                    <tr>
                      <td class="service_title"><?php echo $dt->user_fullname; ?></td>
                      <td class="service_title"><?php echo $dt->user_email; ?></td>
                      <td class="title"><?php echo $dt->user_phone; ?></td>
                      <td class="service_price"><?php echo date(COM_DATE_FORMATE,strtotime($dt->user_bdate)); ?></td>
					  <td class="service_discount"><?php echo date(DATETIME_FORMATE,strtotime($dt->created_at)); ?></td>
                      <td><div class="cat-img" style="width: 50px; height: 50px;"><img width="100%" height="100%" src="<?php echo base_url('uploads/profile/crop/small/'.$dt->user_image); ?>" /></div></td>
                      <td><?php if($dt->user_status == "1"){ ?><span class="label label-success">Active</span><?php } else { ?><span class="label label-danger">Deactive</span><?php } ?></td>
                      <td>
                            <a href="<?php echo site_url("users/edit_user/".$dt->user_id); ?>" class="btn btn-success"><i class="fa fa-edit"></i></a>
                            <?php if($dt->user_id != _get_current_user_id($this)){ ?>
                            <a href="<?php echo site_url("users/delete_user/".$dt->user_id); ?>" onclick="return confirm('Are you sure to delete..?')" class="btn btn-danger"><i class="fa fa-remove"></i></a>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php
                } ?>
                
                </tbody>
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
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
</body>
</html>
