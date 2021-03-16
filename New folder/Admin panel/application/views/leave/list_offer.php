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
             Offer
            <small>List</small>
          </h1>
          <ol class="breadcrumb">
        <li><a href="<?php echo site_url($this->router->fetch_class()."/add"); ?>" class="btn btn-sm btn-default"><i class="fa fa-plus"></i>  Add New </a></li>
      </ol>
        </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <?php  if(isset($error)){ echo $error; }
                                    echo $this->session->flashdata('success_req'); ?>
                                    
                            <div class="box box-primary"> 
                               
                                <div class="box-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Title </th>                                                 
                                                <th> Description </th>
                                                <th> Start Date </th>
                                                <th> End Date</th>
                                                <th> Coupon Code</th>
                                                <th> Discount</th>
                                                <th> Status </th>
                                                <th class="text-center" style="width: 100px;"> Action </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                           <?php foreach($offer as $off){ ?>
                                            <tr> 
                                                <td><?php echo $off->offer_title; ?></td>
                                                <td><?php echo $off->offer_description; ?></td>
                                                <td> <?php echo $off->offer_start_date; ?></td>
                                                <td> <?php echo $off->offer_end_date; ?></td>
                                                <td> <?php echo $off->offer_coupon; ?></td>
                                                <td> <?php echo $off->offer_discount; ?></td>	
                                                <td><?php if($off->offer_status=="1"){echo "Active";}else{echo "DeActive";} ?></td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        <?php echo anchor('offer/edit/'.$off->offer_id, '<i class="fa fa-edit"></i>', array("class"=>"btn btn-success")); ?>
                                                        <?php echo anchor('offer/delete_offer/'.$off->offer_id, '<i class="fa fa-times"></i>', array("class"=>"btn btn-danger", "onclick"=>"return confirm('Are you sure delete?')")); ?>
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
            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->
        <?php $this->load->view("common/footer"); ?>

      
      <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
      <div class="control-sidebar-bg"></div>
    </div><!-- ./wrapper -->
<?php $this->load->view("common/common_js"); ?>
    
  </body>
</html>
