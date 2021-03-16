<header class="main-header">

    <!-- Logo -->
    <a href="<?php echo site_url(); ?>" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><?php echo $this->config->item("app_name"); ?></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><?php echo $this->config->item("app_name"); ?></span>
    </a>

    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          
           
          <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <span class="hidden-xs"><?php echo _l("Language")." : ".(($this->session->userdata('site_lang') != NULL) ? $this->session->userdata('site_lang') : "english"); ?></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li>
                    <a href="<?php echo site_url("languageswitcher/switchlang/english") ?>">English</a>
                  </li>
                  <li>
                    <a href="<?php echo site_url("languageswitcher/switchlang/arabic") ?>">Arabic</a>
                  </li>
                </ul>
              </li>
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <img src="<?php echo _get_current_user_image($this); ?>" class="user-image" alt="User Image">
                  <span class="hidden-xs"><?php echo _get_current_user_name($this); ?></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                   
                    <img src="<?php echo _get_current_user_image($this); ?>" class="img-circle" alt="User Image">
                    <p>
                      <?php echo _get_current_user_name($this); ?>
                       
                    </p>
                  </li>
                  <!-- Menu Body -->
                 
                  <!-- Menu Footer-->
                  <?php 
                $user_type_id = _get_current_user_type_id($this);
                    if($user_type_id == USER_ADMIN)
                    {
                ?>
                  <li class="user-menu">
                      <a href="<?php echo site_url("users/edit_user/"._get_current_user_id($this)); ?>" ><i class="fa fa-edit"></i> <?php echo _l("Edit Profile")?></a>
                  </li>
                  <?php } else { 
                    $pros = $this->pros_model->get_curren_user_pros();
                    if(!empty($pros)){
                    ?>   
                  <li class="user-menu">
                      <a href="<?php echo site_url("pros/edit/".$pros->id); ?>" ><i class="fa fa-edit"></i> <?php echo _l("Edit Profile")?></a>
                  </li>
                <?php } }?>
                  <li class="user-menu">                 
                      <a href="<?php echo site_url("users/change_password/"._get_current_user_id($this)); ?>" ><i class="fa fa-lock"></i>  <?php echo _l("Change Password")?></a>
                  </li>
                  <li class="user-menu">
                      <a href="<?php echo site_url("admin/signout") ?>" ><i class="fa fa-power-off"></i>  <?php echo _l("Sign Out")?></a>
                    </li>
                  </li>
                </ul>
              </li>
        </ul>
      </div>

    </nav>
  </header>