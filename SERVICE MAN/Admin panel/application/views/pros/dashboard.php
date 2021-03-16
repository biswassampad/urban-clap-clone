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
        Dashboard 
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <!-- Main content -->
     <section class="content">
          <!-- Small boxes (Stat box) -->
          <div class="row">
            <div class="col-lg-4 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3><?php  echo $pending->counts;  ?></h3>
                  <p><?php echo _l("Pending"); ?></p>
                </div>
                <div class="icon">
                  <i class="ion ion-clock"></i>
                </div>
                <a href="<?php echo site_url("appointment/view/0"); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
            <div class="col-lg-4 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-green">
                <div class="inner">
                  <h3><?php  echo $completed->counts;  ?></h3>
                  <p><?php echo _l("Completed"); ?></p>
                </div>
                <div class="icon">
                  <i class="fa fa-clock-o"></i>
                </div>
                <a href="<?php echo site_url("appointment/view/3"); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
            <div class="col-lg-4 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-yellow">
                <div class="inner">
                  <h3><?php echo $user_count; ?> </h3>
                  <p>User Registrations</p>
                </div>
                <div class="icon">
                  <i class="ion ion-person-add"></i>
                </div>
                <a href="#" class="small-box-footer"> - </a>
              </div>
            </div><!-- ./col -->
            
          </div><!-- /.row -->
          
          <div class="row">
            <div class="col-md-12">
                <div class="panel">
                    <div class="panel-heading">

                <h3>Todays Appointment : </h3>
                <table id="example1" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                  <th>User Name <br />Mobile</th>
                  <th>Email</th>
                  <th>Appointment Date</th>
                  <th>Details &<br /> Job Assign </th> 
                  <th>Pros Name</th>
                   <?php 
                $user_type_id = _get_current_user_type_id($this);
                    if($user_type_id == USER_ADMIN)
                    {
                ?>
                  <th width='60'>Action</th>
                   <?php } else {} ?>   
                </tr>
                    </thead>
                    <tbody>
                <?php foreach($data as $dt){
                    ?>
                    <tr id="row_<?php echo $dt->id; ?>">
                      <td class="user_fullname"><?php echo $dt->user_fullname; ?> <br /><?php echo $dt->user_phone; ?> </td>
                      <td><?php echo $dt->user_email; ?></td>
                      <td class="phone"><?php echo $dt->appointment_date; ?></td>  
                      <td class="pros_details"><a href="<?php echo site_url("appointment/details/".$dt->id); ?>" class="btn btn-primary">  View </a> </td>
                      <td class="pros_name"><?php echo $dt->pros_name; ?> </td> 
                       <?php 
                $user_type_id = _get_current_user_type_id($this);
                    if($user_type_id == USER_ADMIN)
                    {
                ?>
                      <td>
                        <div class="btn-group">
                            <a href="<?php echo site_url($this->router->fetch_class()."/edit/".$dt->id); ?>" class="btn btn-success"><i class="fa fa-edit"></i></a>
                            <a href="javascript:deleteRecord('<?php echo site_url($this->router->fetch_class()."/delete/".$dt->id); ?>',<?php echo $dt->id; ?>)" onclick="return confirm('Are you sure to delete..?')" class="btn btn-danger"><i class="fa fa-remove"></i></a>
                            
                        </div>
                      </td>
                       <?php } else {} ?>   
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
  </div>
  <!-- /.content-wrapper -->

  <?php $this->load->view("common/footer"); ?>  
</div>
<!-- ./wrapper -->
  <?php $this->load->view("common/common_js"); ?>  
  
</body>
</html>
