<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
              <img src="<?php echo _get_current_user_image($this); ?>" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
              <p><?php _get_current_user_name($this); ?></p>
              <a href="#"><i class="fa fa-circle text-success"></i>  <?php echo
$this->lang->line("Online") ?></a>
            </div>
      </div> 
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
         
        
        <?php if (_get_current_user_type_id($this) == 1) { ?>
            <li class="<?php echo _is_active_menu($this, array("admin"), array("dashboard")); ?>">
              <a href="<?php echo site_url("admin/dashboard"); ?>">
                <i class="fa fa-dashboard"></i> <span><?php echo _l("Dashboard"); ?></span> 
              </a>
            </li> 
            <!--<li class="<?php echo _is_active_menu($this, array("Appointment"),
array()); ?>">
                  <a href="<?php echo site_url("appointment"); ?>">
                    <i class="fa fa-list"></i> <span> Appointment </span>  <i class="fa fa-angle-right pull-right"></i><small class="label pull-right bg-green"></small>
                  </a>
            </li>-->
            <li class="treeview <?php echo _is_active_menu($this, array("appointment"),
array()); ?>">
                  <a href="#">
                    <i class="fa fa-book"></i>
                    <span><?php echo _l("Bookings"); ?></span>
                      <i class="fa fa-angle-left pull-right"></i>
                  </a>
                  <ul class="treeview-menu">
                    <li><a href="<?php echo site_url("appointment"); ?>"><i class="fa fa-list"></i> <?php echo
$this->lang->line("All"); ?></a></li>
                    <li><a href="<?php echo site_url("appointment/view/0"); ?>"><i class="fa fa-list"></i> <?php echo
$this->lang->line("Pending"); ?></a></li>
                    <li><a href="<?php echo site_url("appointment/view/1"); ?>"><i class="fa fa-list"></i> <?php echo
$this->lang->line("Assigned"); ?></a></li>
                    <li><a href="<?php echo site_url("appointment/view/2"); ?>"><i class="fa fa-list"></i> <?php echo
$this->lang->line("Started"); ?></a></li>
                    <li><a href="<?php echo site_url("appointment/view/3"); ?>"><i class="fa fa-list"></i> <?php echo
$this->lang->line("Completed"); ?></a></li>
                    <li><a href="<?php echo site_url("appointment/view/4"); ?>"><i class="fa fa-list"></i> <?php echo
$this->lang->line("Canceled"); ?></a></li>
                  </ul>
                </li>
            <li class="<?php echo _is_active_menu($this, array("category"),
array()); ?>">
                  <a href="<?php echo site_url("category"); ?>">
                    <i class="fa fa-cube"></i> <span> <?php echo $this->lang->
line("Categories"); ?> </span> <small class="label pull-right bg-green"></small>
                  </a>
            </li>
            <li class="<?php echo _is_active_menu($this, array("banners"),
array()); ?>">
                  <a href="<?php echo site_url("banners"); ?>">
                    <i class="fa fa-cube"></i> <span> <?php echo $this->lang->
line("Banners"); ?> </span> <small class="label pull-right bg-green"></small>
                  </a>
            </li>
            <li class="<?php echo _is_active_menu($this, array("pros"), array()); ?>">
                  <a href="<?php echo site_url("pros"); ?>">
                    <i class="fa fa-user"></i> <span>  <?php echo $this->lang->
line("Service Man"); ?> </span> <small class="label pull-right bg-green"></small>
                  </a>
            </li>
    		<li class="<?php echo _is_active_menu($this, array("services"), array()); ?>">
                  <a href="<?php echo site_url("services"); ?>">
                    <i class="fa fa-legal"></i> <span>  Services </span> <small class="label pull-right bg-green"></small>
                  </a>
            </li>
            <li class="<?php echo _is_active_menu($this, array("schedule"),
array()); ?>">
                  <a href="<?php echo site_url("schedule"); ?>">
                    <i class="fa fa-clock-o"></i> <span>  Schedule </span><small class="label pull-right bg-green"></small>
                  </a>
            </li>
             <li class="<?php echo _is_active_menu($this, array("appuser"),
array()); ?>">
                  <a href="<?php echo site_url("appuser"); ?>">
                    <i class="fa fa-user-circle"></i> <span>  App User </span><small class="label pull-right bg-green"></small>
                  </a>
            </li>
            <li class="<?php echo _is_active_menu($this, array("offer"), array()); ?>">
                  <a href="<?php echo site_url("offer"); ?>">
                    <i class="fa fa-tags"></i> <span>  Offer </span><small class="label pull-right bg-green"></small>
                  </a>
            </li>
            <li class="<?php echo _is_active_menu($this, array("pageapp"), array
()); ?>">
                  <a href="<?php echo site_url("pageapp"); ?>">
                    <i class="fa fa-file"></i> <span>  Pages </span><small class="label pull-right bg-green"></small>
                  </a>
            </li>
            <li class="<?php echo _is_active_menu($this, array("notification"),
array()); ?>">
                  <a href="<?php echo site_url("notification"); ?>">
                    <i class="fa fa-bell"></i> <span>  Notification </span><small class="label pull-right bg-green"></small>
                  </a>
            </li>
            <li class="<?php echo _is_active_menu($this,array("settings"),array()); ?>   treeview">
													<a href="#">
													<i class="fa fa-cog"></i>
													<span> <?php echo _l("Settings"); ?></span>
													<i class="fa fa-angle-left pull-right"></i>
													</a>
													<ul class="treeview-menu">
														<li class="<?php echo _is_active_menu($this,array("settings"),array("site")); ?>">
															<a href="<?php echo site_url("settings/site"); ?>"><i class="fa fa-plus"></i><?php echo _l("Site Setting"); ?></a>
														</li>
                                                        <li class="<?php echo _is_active_menu($this,array("settings"),array("email")); ?>">
															<a href="<?php echo site_url("settings/email"); ?>"><i class="fa fa-plus"></i><?php echo _l("Email Setting"); ?></a>
														</li>
                                                        <li class="<?php echo _is_active_menu($this,array("settings"),array("fcm")); ?>">
															<a href="<?php echo site_url("settings/fcm"); ?>"><i class="fa fa-plus"></i><?php echo _l("FCM Notification"); ?></a>
														</li>
                                                        
													</ul>
												</li>
        <?php }
 /*  if(_get_current_user_type_id($this)==2){ */ ?>
         <!--   <li class="<?php echo _is_active_menu($this, array("schedule"),
array()); ?>">
                  <a href="<?php echo site_url("schedule"); ?>">
                    <i class="fa fa-list"></i> <span>  Service man </span>  <i class="fa fa-angle-right pull-right"></i><small class="label pull-right bg-green"></small>
                  </a>
            </li>  -->
        <?php /* }  */
$pros = $this->pros_model->get_curren_user_pros();
if (_get_current_user_type_id($this) == 2) { ?>
        <li class="<?php echo _is_active_menu($this, array("pros"), array("dashboard")); ?>">
              <a href="<?php echo site_url("pros/dashboard"); ?>">
                <i class="fa fa-dashboard"></i> <span>Dashboard</span> 
              </a>
            </li> 
            <li class="<?php echo _is_active_menu($this, array("pros"), array()); ?>">
                  <a href="<?php echo site_url("pros/edit/" . $pros->id); ?>">
                    <i class="fa fa-list"></i> <span> Edit Profile </span><small class="label pull-right bg-green"></small>
                  </a>
            </li>
            <li class="treeview <?php echo _is_active_menu($this, array("Appointment"),
array()); ?>">
                  <a href="<?php echo site_url("appointment"); ?>">
                    <i class="fa fa-book"></i> <span> Bookings </span>  <i class="fa fa-angle-left pull-right"></i><small class="label pull-right bg-green"></small>
                  </a>
                  <ul class="treeview-menu">
                    <li><a href="<?php echo site_url("appointment"); ?>"><i class="fa fa-list"></i> <?php echo
$this->lang->line("All"); ?></a></li>
                    <li><a href="<?php echo site_url("appointment/view/0"); ?>"><i class="fa fa-list"></i> <?php echo
$this->lang->line("Pending"); ?></a></li>
                    <li><a href="<?php echo site_url("appointment/view/1"); ?>"><i class="fa fa-list"></i> <?php echo
$this->lang->line("Assigned"); ?></a></li>
                    <li><a href="<?php echo site_url("appointment/view/2"); ?>"><i class="fa fa-list"></i> <?php echo
$this->lang->line("Started"); ?></a></li>
                    <li><a href="<?php echo site_url("appointment/view/3"); ?>"><i class="fa fa-list"></i> <?php echo
$this->lang->line("Completed"); ?></a></li>
                    <li><a href="<?php echo site_url("appointment/view/4"); ?>"><i class="fa fa-list"></i> <?php echo
$this->lang->line("Canceled"); ?></a></li>
                  </ul>
            </li>
        <?php } ?>
        
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>