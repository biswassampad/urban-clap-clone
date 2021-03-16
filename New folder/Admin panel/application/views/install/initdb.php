<!DOCTYPE html>
<html>
  <head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Site Settings</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <?php $this->load->view("common/common_css"); ?>
  </head>
  <body class="hold-transition login-page" style="">
    <div class="row">
    <div class="col-md-6 col-md-offset-3">
    <?php echo _get_flash_message(); ?>
    </div>
    </div>
    <?php $this->load->view("common/common_js"); ?>

  </body>
</html>
