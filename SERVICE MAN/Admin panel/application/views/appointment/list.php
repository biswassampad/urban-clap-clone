<!DOCTYPE html>
<html>
<head>
 
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
         Appoinment  
        <small></small>
      </h1>
      <?php 
                $user_type_id = _get_current_user_type_id($this);
                    if($user_type_id == USER_ADMIN)
                    {
                ?>
      <!--<ol class="breadcrumb">
        <li><a href="<?php echo site_url($this->router->fetch_class()."/add"); ?>" class="btn btn-sm btn-default"><i class="fa fa-plus"></i> <?php echo _l("Add New"); ?></a></li>
      </ol> -->
      <?php } ?>  
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
                  <th>User Name <br />Mobile <br />Email</th>
                  <th>Address</th>
                  <th>Appointment Date</th>
                  <th>Time</th>
                  <th>Approx Time</th>
                  <th>Approx Cost</th> 
                  <th>Pros Name</th>
                  <th>Status</th>
                   <?php 
                $user_type_id = _get_current_user_type_id($this);
                    if($user_type_id == USER_ADMIN)
                    {
                ?>
                  <th width='100'>Action</th>
                   <?php } else { ?>
                    <th width='100'>Action</th>
                  <?php } ?>   
                </tr>
                </thead>
                <tbody>
                <?php foreach($data as $dt){
                    ?>
                    <tr id="row_<?php echo $dt->id; ?>">
                      <td class="user_fullname"><?php echo $dt->user_fullname; ?> <br /><?php echo $dt->user_phone; ?> <br /><?php echo $dt->user_email; ?> </td>
                      <td><?php echo $dt->delivery_address; ?> </td>
                      <td><?php echo date(COM_DATE_FORMATE,strtotime($dt->appointment_date)); ?></td>  
                      <td><?php echo $dt->total_time; ?> </td>
                      <td><?php echo $dt->approx_time; ?></td>
                      <td><?php echo $dt->net_amount; ?></td>
                      <!--td><?php echo $dt->total_service_amount; ?></td-->
                      <td class="pros_name"><?php echo $dt->pros_name; ?> </td> 
                      <td><?php 
                      if($dt->status == STATUS_PENDING){
                        echo "<label class='label bg-yellow'>".$this->lang->line("Pending")."</label>";
                      }else if($dt->status == STATUS_ASSIGNED){
                        echo "<label class='label bg-green'>".$this->lang->line("Assigned")."</label>";
                      }else if($dt->status == STATUS_STARTED){
                        echo "<label class='label bg-blue'>".$this->lang->line("Started")."</label>";
                      }else if($dt->status == STATUS_COMPLETED){
                        echo "<label class='label bg-aqua'>".$this->lang->line("Completed")."</label>";
                      }else if($dt->status == STATUS_CANCLED){
                        echo "<label class='label bg-red'>".$this->lang->line("Canceled")."</label>";
                      }
                       ?></td>
                       <?php 
                $user_type_id = _get_current_user_type_id($this);
                    if($user_type_id == USER_ADMIN)
                    {
                ?>
                      <td>
                        <div class="btn-group">
                            <a href="<?php echo site_url($this->router->fetch_class()."/details/".$dt->id); ?>" class="btn btn-primary">  View </a>
                            <a href="javascript:deleteRecord('<?php echo site_url($this->router->fetch_class()."/delete/".$dt->id); ?>',<?php echo $dt->id; ?>)" onclick="return confirm('Are you sure to delete..?')" class="btn btn-danger"><i class="fa fa-remove"></i></a>
                            
                        </div>
                      </td>
                       <?php } else if($user_type_id == USER_PROS) {
                        ?>
                        <td>
                        <div class="btn-group">
                            <a href="<?php echo site_url($this->router->fetch_class()."/details/".$dt->id); ?>" class="btn btn-primary">  View </a>
                        </div>
                        </td>
                        <?php
                       } ?>   
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
