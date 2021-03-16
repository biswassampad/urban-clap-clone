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
        <?php echo _l("Services"); ?>
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo site_url($this->router->fetch_class()."/add"); ?>" class="btn btn-sm btn-default"><i class="fa fa-plus"></i> <?php echo _l("Add New"); ?></a></li>
      </ol>
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
                  <th>Title</th>
                  <th>Category</th>
                  <th>Price</th>
                  <th>Discount</th>
                  <th>Time</th>
                  <th width='60'>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($data as $dt){
                    ?>
                    <tr id="row_<?php echo $dt->id; ?>">
                      <td class="service_title"><?php echo $dt->service_title; ?></td>
                      <td class="title"><?php echo $dt->title; ?></td>
                      <td class="service_price"><?php echo $dt->service_price; ?></td>
                      
                      <td class="service_discount"><?php echo $dt->service_discount; ?></td>
                      <td class="service_approxtime"><?php echo $dt->service_approxtime; ?></td>
                      
                      <td>
                        <div class="btn-group">
                            <a href="<?php echo site_url($this->router->fetch_class()."/edit/".$dt->id); ?>" class="btn btn-success"><i class="fa fa-edit"></i></a>
                            <a href="javascript:deleteRecord('<?php echo site_url($this->router->fetch_class()."/delete/".$dt->id); ?>',<?php echo $dt->id; ?>)" onclick="return confirm('Are you sure to delete..?')" class="btn btn-danger"><i class="fa fa-remove"></i></a>
                            
                        </div>
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
