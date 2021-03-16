<!DOCTYPE html>
<html>
    <?php  $this->load->view("common/common_css"); ?>
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
              App Pages  
          </h1> 
          <!--
          <ol class="breadcrumb">
            <li><a href="<?php echo site_url($this->router->fetch_class()."/add"); ?>" class="btn btn-sm btn-default"><i class="fa fa-plus"></i> <?php echo _l("Add New"); ?></a></li>
          </ol> -->
        </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-md-12">
                            <?php  if(isset($error)){ echo $error; }
                                    echo $this->session->flashdata('success_req'); ?>
                            <!-- general form elements -->
                           <div class="box box-primary"> 
                                <div class="box-body table-responsive">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th style="width: 30px;"> ID </th>
                                                <th > Title </th>
                                                <th style="width: 100px;">Status</th>
                                                
                                                <th class="text-center" style="width: 100px;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($pageapp as $allpage){ ?>
                                            <tr>
                                                <td><?php echo $allpage->id; ?></td>
                                                <td><?php echo $allpage->pg_title; ?></td>
                                                <td><?php if($allpage->pg_status == "1"){ ?><span class="label label-success">Active</span><?php } else { ?><span class="label label-danger">Deactive</span><?php } ?></td>
                                                
                                                <td class="text-center"><div class="btn-group">
                                                        <?php echo anchor('pageapp/edit/'.$allpage->id, '<i class="fa fa-edit"></i>', array("class"=>"btn btn-success")); ?>
                                                        <?php //echo anchor('pageapp/deletepage/'.$allpage->id, '<i class="fa fa-times"></i>', array("class"=>"btn btn-danger", "onclick"=>"return confirm('Are you sure delete?')")); ?> 
                                                        
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
                </section><!-- /.content -->
           
        </div><!-- ./wrapper -->

        <?php  $this->load->view("common/footer"); ?>  

      
      <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
      <div class="control-sidebar-bg"></div>
    </div><!-- ./wrapper -->

    <?php $this->load->view("common/common_js"); ?>
 
  </body>
</html>
