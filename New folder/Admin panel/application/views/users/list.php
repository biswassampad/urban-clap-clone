<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin | Dashboard</title>
    <?php  $this->load->view("admin/common/common_css"); ?>
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
             <?php echo _l("User List")?>
            <small> <?php echo _l("Manage User")?></small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i>  <?php echo _l("Admin")?></a></li>
            <li class="active"> <?php echo _l("User")?></li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="box">
                <div class="box-header">
                <table id="example2" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th>  <?php echo _l("Email")?></th>
                        <th> <?php echo _l("Full Name")?></th>
                        <th> <?php echo _l("User Type")?></th>
                        <th> <?php echo _l("Status")?></th>
                       
                        <th width="80"> <?php echo _l("Action")?></th>
                      </tr>
                    </thead>
                    <tbody>
                <?php foreach($users as $user){
                    ?>
                    <tr>
                        <td><?php echo $user->user_email; ?></td>
                        <td><?php echo $user->user_fullname; ?></td>
                        <td><?php echo $user->user_type_title; ?></td>
                        <td><input class='tgl tgl-ios tgl_checkbox' data-table="users" data-status="user_status" data-idfield="user_id"  data-id="<?php echo $user->user_id; ?>" id='cb_<?php echo $user->user_id; ?>' type='checkbox' <?php echo ($user->user_status==1)? "checked" : ""; ?> />
    <label class='tgl-btn' for='cb_<?php echo $user->user_id; ?>'></label></td>
                        <td>
                            <a href="<?php echo site_url("admin/edit_user/".$user->user_id); ?>" class="btn btn-success"><i class="fa fa-edit"></i></a>
                            <?php if($user->user_id != _get_current_user_id($this)){ ?>
                            <a href="<?php echo site_url("admin/delete_user/".$user->user_id); ?>" onclick="return confirm('Are you sure to delete..?')" class="btn btn-danger"><i class="fa fa-remove"></i></a>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php
                } ?>
                    </tbody>
                </table>
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
    <script>
      $(function () {
        
        var mSortingString = [];
var disableSortingColumn = 3; 
mSortingString.push({ "bSortable": false, "aTargets": [disableSortingColumn] });
        $('#example2').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": true,
          "ordering": true,
          "info": true,
          "autoWidth": false,
            "aoColumnDefs": mSortingString

        });
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
