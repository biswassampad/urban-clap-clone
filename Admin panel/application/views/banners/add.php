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
        <?php echo _l("Banners"); ?>
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo site_url($this->router->fetch_class()); ?>" class="btn btn-sm btn-default"><i class="fa fa-list"></i> <?php echo _l("List"); ?></a></li>
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
                <form action="<?php echo site_url("banners/add"); ?>" id="form" method="post" enctype="multipart/form-data">
                    
                        <input type="hidden" name="id" value="<?php if(!empty($field) && !empty($field->id)){ echo $field->id; } ?>" />
                                        <div class="form-group">
                                            <label class=""><?php echo _l("Title"); ?> : <span class="text-danger">*</span></label>
                                            <input type="text" name="title" class="form-control" value="<?php echo _get_post_back($field,'title'); ?>" placeholder="<?php echo _l("Title"); ?>" required="" />
                                        </div>
                                        <div class="form-group">
                                            <label class=""><?php echo _l("Type"); ?> : <span class="text-danger">*</span></label>
                                            <select class="form-control" name="type" id="type">
                                                <?php
                                                $types = array("category","link");
                                                foreach($types as $type){
                                                    ?>
                                                    <option value="<?php echo $type; ?>" <?php if(!empty($field->type) && $field->type == $type){ echo "selected"; } ?>> <?php echo $type; ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group <?php if(!empty($field->type) && $field->type != "category"){ echo "hide"; } ?>" id="category_id" >
                                            <label class=""><?php echo _l("Categories"); ?> : <span class="text-danger">*</span></label>
                                            <select class="form-control" name="category_id" >
                                                <?php
                                                foreach($categories as $cat){
                                                    ?>
                                                    <option value="<?php echo $cat->id; ?>" <?php if(!empty($field->category_id) && $field->category_id == $cat->id){ echo "selected"; } ?> ><?php echo $cat->title; ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group <?php if(empty($field->type) || $field->type != "link"){ echo "hide"; } ?>" id="type_value" >
                                            <label class=""><?php echo _l("Link"); ?> : <span class="text-danger">*</span></label>
                                            <input type="url" name="type_value" class="form-control" value="<?php echo _get_post_back($field,'type_value'); ?>" placeholder="<?php echo _l("Link"); ?>" />
                                        </div>
                                        
                                        <div class="row">
                                         <div class="col-sm-5">
                                            
                                            
                                            <div class="form-group">
                                            <label>Banners Image: <span class="text-danger">*</span> </label>
                                            <p>We recommoded 680x300 for batter resolution</p>
                                            <?php if(!empty($field->image)){ ?>
                                            <div class="cat-img pull-right" style="width: 70px; height: 70px;"><img width="100%" height="100%" src="<?php echo base_url('uploads/banners/crop/small/'.$field->image); ?>" /></div>
                                            <?php } ?>
                                            <input type="file" name="image" />
                                        </div>
                                        </div> 
                                            <div class="col-sm-7">
                                                <label class=""><?php echo _l("Image"); ?></label>
                                                
                                            </div>
                                        </div>
                                        <hr />
                                        <input type="submit" class="btn btn-primary" name="addcatg" value="Add" />
										<a href="<?php echo site_url('banners'); ?>" class="btn btn-danger">Cancel</a>
                                </form>
              
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

<?php include 'third_party/crop/main.php'; ?>    
<script>
    $(document).ready(function(){
        $('body').on("change","#type",function(){
        var type = $(this).val();
        if(type == "category"){
            $("#category_id").removeClass("hide");
            $("#type_value").addClass("hide");
        }else{
            $("#category_id").addClass("hide");
            $("#type_value").removeClass("hide");
        }
    });

    });
</script>
</body>
</html>

