<!DOCTYPE html>
<html>
<head>
 
  <?php $this->load->view("common/common_css"); ?>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php $this->load->view("common/header"); ?>
  <!-- Left side column. contains the logo and sidebar -->
  <?php $this->load->view("common/sidebar"); ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            User Permission
            <small>Manage Access</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Admin</a></li>
            <li class="active">User Access</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
         <form role="form" action="" method="post">
            <div class="row">
                <!--<div class="col-md-5">
                    <div class="box">
                        <div class="box-header">
                            Add new Boxoffice
                        </div>
                        <div class="box-body">
                        
                           
                              <div class="box-body">
                                
                                <div class="form-group">
                                    <label for="user_type_id">User Type</label>
                                    <select class="form-control select2" name="user_type_id" id="user_type_id" style="width: 100%;">
                                      <?php foreach($user_types as $user_type){
                                        ?>
                                        <option value="<?php echo $user_type->user_type_id; ?>" ><?php echo $user_type->user_type_title; ?></option>
                                        <?php
                                      } ?>
                                    </select>
                                </div>
                                
                              </div>
            
                              <div class="box-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                              </div>
                           
                        </div>
                    </div>
                </div> -->
                <div class="col-md-12">
                <div class="box">
                        <div class="box-header">
                          Set User Access
                        </div>
                        <div class="box-body">
                        <input type="hidden" name="user_type_id" value="<?php echo $user_type_id;?>" />
                        <?php foreach(array_keys($controllers) as $key){
                            echo "<div><label for='$key'><input type='checkbox' name='permission[".$key."][all]' id='$key' ";
                            echo get_is_access($key,"*",$user_access);
                            echo " />".$key."</label></div>";
                            foreach($controllers[$key] as $methods){
                                echo "<div class='col-md-4'><label for='$methods'><input type='checkbox' name='permission[".$key."][$methods]' id='$methods' ";
                                echo get_is_access($key,$methods,$user_access);
                                echo " />".$methods."</label></div>";
                            }
                        } 

                        function get_is_access($controller,$method,$user_access){
                        
                                foreach($user_access as $access){
                                    if($access->class == strtolower($controller) || $access->method == strtolower($method)){
                                        return "checked";
                                    }
                                }
                                return "";
                        }
                        
                        ?>
                        
                        </div>
                        <div class="box-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                              </div>
                </div>
                </div>
                
            </div>
             </form>
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      
      <?php $this->load->view("common/footer"); ?>  
</div>
<!-- ./wrapper -->
  <?php $this->load->view("common/common_js"); ?>  
  
</body>
</html>