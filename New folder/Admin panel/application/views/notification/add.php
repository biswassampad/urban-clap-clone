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
  <?php  $this->load->view("common/sidebar"); ?>
  <div class="content-wrapper">
                 <section class="content-header">
                    <h1>
                       Notification
                        <small></small>
                    </h1>
                    
                </section>
                <section class="content">
                    <div class="row">
                        <div class="col-md-6">
                            <?php  if(isset($error)){ echo $error; }
                                    echo $this->session->flashdata('message'); ?>
                          
                            <div class="box box-primary">
                                <div class="box-header">
                                    <h3 class="box-title">Add Notification</h3>
                                </div>
                                
                                <form action="" method="post" enctype="multipart/form-data">
                                    
                                    <div class="box-body">
                                            <label class="">Subject :<span class="text-danger">*</span></label>
                                            <div class="form-group">
                                                    <input type="text" name="subject" class="form-control" placeholder="Notification title"  data-validation="required"  />        
                                            </div>
                                            
                                            <label class="">Notification :<span class="text-danger">*</span></label>
                                            <div class="form-group">
                                                    <textarea name="descri" class="form-control" rows="8" placeholder=" Place some text here"  data-validation="required"  ></textarea>        
                                            </div>  
                                            <label class="">Image :<span class="text-danger">*</span></label>
                                            <input type="file" name="file" data-validation="mime size" 
		 data-validation-allowing="jpg, png, gif" 
		 data-validation-max-size="2M"
          data-validation-optional="true" />
                                    </div>
                                    <div class="box-footer">
                                        <input type="submit" class="btn btn-primary" name="notification" value="Send" />
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                </section>
 </div>
    <?php $this->load->view("common/footer"); ?>
  <div class="control-sidebar-bg"></div>
</div>
<?php $this->load->view("common/common_js"); ?>
</body>
</html>

