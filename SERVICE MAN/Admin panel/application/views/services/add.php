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
            <form action="" id="form" method="post" enctype="multipart/form-data">
            
          <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                                        
                        <input type="hidden" name="id" value="<?php if(!empty($field) && !empty($field->id)){ echo $field->id; } ?>" />
                                        <div class="form-group">
                                            <label class=""><?php echo _l("Categories"); ?> : <span class="text-danger">*</span></label>
                                            <select class="form-control" name="cat_id">
                                                <?php
                                                foreach($categories as $cat){
                                                    ?>
                                                    <option value="<?php echo $cat->id; ?>" <?php if(!empty($field->cat_id) && $field->cat_id == $cat->id){ echo "selected"; } ?> ><?php echo $cat->title; ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class=""><?php echo _l("Title"); ?> : <span class="text-danger">*</span></label>
                                            <input type="text" name="service_title" class="form-control" value="<?php echo _get_post_back($field,'service_title'); ?>" placeholder="<?php echo _l("Title"); ?>" required=""  data-validation="required" />
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class=""><?php echo _l("Price"); ?> : <span class="text-danger">*</span></label>
                                            <input type="text" name="service_price" class="form-control" value="<?php echo _get_post_back($field,'service_price'); ?>" placeholder="<?php echo _l("Price"); ?>"  data-validation="required number" />
                                        </div>
                                        <div class="form-group">
                                            <label class=""><?php echo _l("Discount"); ?> : </label>
                                            <input type="text" name="service_discount" class="form-control" value="<?php echo _get_post_back($field,'service_discount'); ?>" placeholder="<?php echo _l("Discount"); ?>" />
                                        </div>
																				<div class="form-group">
                                            <label class=""><?php echo _l("Time"); ?> : <span class="text-danger">*</span></label>
                                            <input type="text" name="service_approxtime" class="form-control" value="<?php echo _get_post_back($field,'service_approxtime'); ?>" placeholder="<?php echo _l("Time"); ?>" id="time" />
                                            <small>Note : This is approx time taken to be finish this service</small>                                            
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                            <!--<div class="col-sm-5">
                                                <div class="bus-product thumbnail avatar-view" data-height="250" data-width="250" data-base_path="./uploads/services">   
                                                            <?php
                                                            $proicon = base_url("theme/dist/img/avatar.png");
                                                            $img = _get_post_back($field,"service_icon");
                                                            if(!empty($img)){
                                                                $proicon = base_url("uploads/services/".$img);
                                                            }
                                                            ?>                                 
                                                        <img src="<?php echo $proicon; ?>" class="img-responsive" />                        
                                                        <input type="hidden" name="service_icon" value="<?php echo _get_post_back($field,"service_icon"); ?>" />
                                                        
                                                    </div>
                                            </div>
                                            <div class="col-sm-7">
                                                <label class=""><?php echo _l("Image"); ?></label>
                                                <p>Image Size in Maximum Size 250 X 250px</p>
                                            </div>-->
                                            <div class="form-group">
                                            <label>Service Image: <span class="text-danger">*</span> </label>
                                            <p>We commonded to choose 200X200 px image</p>
                                            <?php if(!empty($field->service_icon)){ ?>
                                            <div class="cat-img pull-right" style="width: 70px; height: 70px;"><img width="100%" height="100%" src="<?php echo base_url('uploads/services/crop/small/'.$field->service_icon); ?>" /></div>
                                            <?php } ?>
                                            <input type="file" name="service_icon" />
                                        </div>
                                            </div>
                                            
                                        </div>
                                        <hr />
                                        <input type="submit" class="btn btn-primary" name="addcatg" value="Add" />
										<a href="<?php echo site_url('services'); ?>" class="btn btn-danger">Cancel</a>
              
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
          </form>
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
<!-- InputMask -->
<script src="<?php echo base_url("theme/plugins/input-mask/jquery.inputmask.js"); ?>"></script>
<script src="<?php echo base_url("theme/plugins/input-mask/jquery.inputmask.date.extensions.js"); ?>"></script>
<script src="<?php echo base_url("theme/plugins/input-mask/jquery.inputmask.extensions.js"); ?>"></script>

<script>
	$(document).ready(function(){
		    $('#time').inputmask('99:99', { 'placeholder': 'HH:MM' });
		});
</script>
<?php include 'third_party/crop/main.php'; ?>    
</body>
</html>

