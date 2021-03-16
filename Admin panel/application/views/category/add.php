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
        <?php echo _l("Categories"); ?>
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
                <form action="<?php echo site_url("category/add"); ?>" id="form" method="post" enctype="multipart/form-data">
                    
                        <input type="hidden" name="id" value="<?php if(!empty($field) && !empty($field->id)){ echo $field->id; } ?>" />
                                        <div class="form-group">
                                            <label class=""><?php echo _l("Title"); ?> : <span class="text-danger">*</span></label>
                                            <input type="text" name="title" class="form-control" value="<?php echo _get_post_back($field,'title'); ?>" placeholder="<?php echo _l("Title"); ?>" required="" />
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class=""><?php echo _l("Description"); ?> :</label>
                                            <textarea name="description" class="textarea" placeholder="<?php echo _l("Description"); ?>" style="width: 100%; height: 100px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"><?php echo _get_post_back($field,'description'); ?></textarea>
                                        </div>
                                        <div class="row">
                                         <div class="col-sm-5">
                                              <!--     <div class="bus-product thumbnail avatar-view" data-height="250" data-width="250" data-base_path="./uploads">   
                                                            <?php
                                                            $proicon = base_url("theme/dist/img/avatar.png");
                                                            $img = _get_post_back($field,"image");
                                                            if(!empty($img)){
                                                                $proicon = base_url("uploads/".$img);
                                                            }
                                                            ?>                                 
                                                        <img src="<?php echo $proicon; ?>" class="img-responsive" />                        
                                                        <input type="hidden" name="image" value="<?php echo _get_post_back($field,"image"); ?>" />
                                                        
                                                    </div>-->
                                            
                                            <div class="form-group">
                                            <label>Category Image: <span class="text-danger">*</span> </label>
                                            <p>We recommoded 680x300 for batter resolution</p>
                                            <?php if(!empty($field->image)){ ?>
                                            <div class="cat-img pull-right" style="width: 70px; height: 70px;"><img width="100%" height="100%" src="<?php echo base_url('uploads/category/crop/small/'.$field->image); ?>" /></div>
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
										<a href="<?php echo site_url('category'); ?>" class="btn btn-danger">Cancel</a>
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
</body>
</html>

