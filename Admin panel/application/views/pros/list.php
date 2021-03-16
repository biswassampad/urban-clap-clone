<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <?php $this->load->view("common/common_css"); ?>
  <link rel="stylesheet" href="<?php echo base_url("theme/plugins/tglbox.css"); ?>" />
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
        <?php echo _l("Pros"); ?>
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
                  <th>Image</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Degree</th>
                  <th>Experiance</th>
                  <th>Qualified</th>
                  <th width='60'>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($data as $dt){
                    ?>
                    <tr id="row_<?php echo $dt->id; ?>">
                        <td><div class="cat-img" style="width: 50px; height: 50px;"><img width="100%" height="100%" src="<?php echo base_url('uploads/pros/crop/small/'.$dt->pros_photo); ?>" /></div></td>
                      <td class="pros_name"><?php echo $dt->pros_name; ?></td>
                      
                      <td class="pros_email"><?php echo $dt->pros_email; ?></td>
                      <td class="pros_degree"><?php echo $dt->pros_degree; ?></td>
                      <td class="pros_exp"><?php echo $dt->	pros_exp; ?></td>
                      <td>
                            <input class='tgl tgl-ios tgl_checkbox' data-table="pros" data-status="is_qualified" data-idfield="id"  data-id="<?php echo $dt->id; ?>" id='cb_<?php echo $dt->id; ?>' type='checkbox' <?php echo ($dt->is_qualified==1)? "checked" : ""; ?> />
                            <label class='tgl-btn' for='cb_<?php echo $dt->id; ?>'></label>
                      </td>
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
<script>
      $(function () {
        
        var mSortingString = [];
var disableSortingColumn = 3; 
mSortingString.push({ "bSortable": false, "aTargets": [disableSortingColumn] });
        $("body").on("change",".tgl_checkbox",function(){
            var table = $(this).data("table");
            var status = $(this).data("status");
            var id = $(this).data("id");
            var id_field = $(this).data("idfield");
            var bin=0;
                                         if($(this).is(':checked')){
                                            bin = 1;
                                         }
            $.ajax({
              method: "POST",
              url: "<?php echo site_url("commonjson/change_status"); ?>",
              data: { table: table, status: status, id : id, id_field : id_field, on_off : bin }
            })
              .done(function( msg ) {
              //  alert(msg);
              }); 
        });
      });
    </script>
</body>
</html>
