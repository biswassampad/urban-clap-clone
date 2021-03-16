
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Admin | Log in</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport" />
    <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo base_url("theme/bower_components/bootstrap/dist/css/bootstrap.min.css"); ?>">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url("theme/bower_components/font-awesome/css/font-awesome.min.css"); ?>">
  <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" />
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url("theme/dist/css/AdminLTE.min.css"); ?>" />
    <!-- iCheck -->
    <link rel="stylesheet" href="<?php echo base_url("theme/plugins/iCheck/square/blue.css"); ?>" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="hold-transition login-page" >
  <div class="login-box">
      <div class="login-logo">
        <a href="#"><?php echo $this->config->item("app_name"); ?></a>
      </div><!-- /.login-logo -->
    <div class="login-box-body">
        <h3 class="login-box-msg">Request Password</h3>
        <div class="card card-container">
                            
                                          <div  id="form-olvidado">
                                            <h4 class="">
                                              Reset Password
                                            </h4>
                                             <?php if(isset($error)){ echo $error; }
                echo $this->session->flashdata("message") ?>
                                            <form accept-charset="UTF-8" role="form" id="login-recordar" method="post">
                                              <fieldset>
                                                <div class="form-group">
                                                  <input class="form-control" placeholder="New Password" name="n_password" type="password" required="">
                                                </div>
                                                <div class="form-group">
                                                  <input class="form-control" placeholder="Re-enter Password" name="r_password" type="password" required="">
                                                </div>
                                                <button type="submit" class="btn btn-primary" id="btn-olvidado">
                                                  Reset Password
                                                </button>
                                               <span>
                                                
                                                <?php 
                                                echo anchor("","Account Access","text-muted class='pull-right'")
                                                ?>
                                                </span>  
                                                
                                              </fieldset>
                                            </form>
                                            </div> 

                            </div><!-- /card-container --> 
      
         

      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

    <script src="<?php echo base_url("theme/bower_components/jquery/dist/jquery.min.js"); ?>"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="<?php echo base_url("theme/bower_components/bootstrap/dist/js/bootstrap.min.js"); ?>"></script>

    <!-- iCheck -->
    <script src="<?php echo base_url("theme/plugins/iCheck/icheck.min.js"); ?>"></script>
    <script>
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });
      });
    </script>
  </body>
</html>

