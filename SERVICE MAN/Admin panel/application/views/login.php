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
    <div class="login-box" style="margin-bottom: 0px !important;margin-top: 0px !important;padding-top: 7%;">
      <div class="login-logo">
        <a href="#"><?php echo  (isset($site_name) && $site_name != NULL && $site_name != "") ? $site_name : $this->config->item("app_name"); ?></a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg"> <?php echo _l("Sign in to your account")?></p>
        <?php if(isset($error) && $error!=""){
                            echo $error;
                        }
                        echo $this->session->flashdata('message'); ?>
                        
        <form action="" method="post">
          <div class="form-group has-feedback">
            <input type="email" name="email" class="form-control"   value="<?php echo get_cookie("c_email"); ?>"  placeholder="Email" required="" />
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" name="password" class="form-control" placeholder="Password"  value="<?php echo get_cookie("c_password"); ?>"  required="" />
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="row">
            <div class="col-xs-8">
              <div class="checkbox icheck">
                <label>
                  <input type="checkbox" name="remember" <?php if(get_cookie("remiber_me") == "on"){ echo "checked"; } ?>  />  <?php echo _l("Remember Me")?>
                </label>
              </div>
            </div><!-- /.col -->
            <div class="col-xs-4">
              <button type="submit" class="btn btn-primary btn-block btn-flat"> <?php echo _l("Sign In")?></button>
            </div><!-- /.col -->
          </div>
        </form>

      
        

      </div><!-- /.login-box-body -->
      <div class=""><a href="<?php echo site_url("users/forgot"); ?>">Forgot password ?</a></div>
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
