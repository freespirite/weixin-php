<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta charset="utf-8">
	<title><?php echo C('ADM_TITLE'); ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=no">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="stylesheet" type="text/css" href="__PUBLIC__/adm/css/cloud-admin.css" >
	<link rel="stylesheet" type="text/css"  href="__PUBLIC__/adm/css/themes/default.css" id="skin-switcher" >
	<link rel="stylesheet" type="text/css"  href="__PUBLIC__/adm/css/responsive.css" >
	<!-- STYLESHEETS --><!--[if lt IE 9]><script src="__PUBLIC__/adm/js/flot/excanvas.min.js"></script><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script><![endif]-->
	<link href="__PUBLIC__/adm/font-awesome/css/font-awesome.min.css" rel="stylesheet">
	<!-- ANIMATE -->
	<link rel="stylesheet" type="text/css" href="__PUBLIC__/adm/css/animatecss/animate.min.css" />
	<!-- DATE RANGE PICKER -->
	<link rel="stylesheet" type="text/css" href="__PUBLIC__/adm/js/bootstrap-daterangepicker/daterangepicker-bs3.css" />
	<!-- TODO -->
	<link rel="stylesheet" type="text/css" href="__PUBLIC__/adm/js/jquery-todo/css/styles.css" />
	<!-- FULL CALENDAR -->
	<link rel="stylesheet" type="text/css" href="__PUBLIC__/adm/js/fullcalendar/fullcalendar.min.css" />
	<!-- GRITTER -->
	<link rel="stylesheet" type="text/css" href="__PUBLIC__/adm/js/gritter/css/jquery.gritter.css" />
	<!-- FONTS -->
	<link href='http://fonts.useso.com/css?family=Open+Sans:300,400,600,700' rel='stylesheet' type='text/css'>
        
        <!-- JQUERY -->
	<script src="__PUBLIC__/adm/js/jquery/jquery-2.0.3.min.js"></script>
	<!-- JQUERY UI-->
	<script src="__PUBLIC__/adm/js/jquery-ui-1.10.3.custom/js/jquery-ui-1.10.3.custom.min.js"></script>
	<!-- BOOTSTRAP -->
	<script src="__PUBLIC__/adm/bootstrap-dist/js/bootstrap.min.js"></script>
	<!-- BOOTBOX -->
	<script type="text/javascript" src="__PUBLIC__/adm/js/bootbox/bootbox.min.js"></script>
		
	<!-- DATE RANGE PICKER -->
	<script src="__PUBLIC__/adm/js/bootstrap-daterangepicker/moment.min.js"></script>
	
	<script src="__PUBLIC__/adm/js/bootstrap-daterangepicker/daterangepicker.min.js"></script>
	<!-- SLIMSCROLL -->
	<script type="text/javascript" src="__PUBLIC__/adm/js/jQuery-slimScroll-1.3.0/jquery.slimscroll.min.js"></script>
	<!-- SLIMSCROLL -->
	<script type="text/javascript" src="__PUBLIC__/adm/js/jQuery-slimScroll-1.3.0/jquery.slimscroll.min.js"></script>
        <script type="text/javascript" src="__PUBLIC__/adm/js/jQuery-slimScroll-1.3.0/slimScrollHorizontal.min.js"></script>
	<!-- BLOCK UI -->
	<script type="text/javascript" src="__PUBLIC__/adm/js/jQuery-BlockUI/jquery.blockUI.min.js"></script>
	<!-- SPARKLINES -->
	<script type="text/javascript" src="__PUBLIC__/adm/js/sparklines/jquery.sparkline.min.js"></script>
	<!-- EASY PIE CHART -->
	<script src="__PUBLIC__/adm/js/jquery-easing/jquery.easing.min.js"></script>
	<script type="text/javascript" src="__PUBLIC__/adm/js/easypiechart/jquery.easypiechart.min.js"></script>
        
        <!-- UNIFORM -->
	<script type="text/javascript" src="__PUBLIC__/adm/js/uniform/jquery.uniform.min.js"></script>
	<!-- WIZARD -->
	<script src="__PUBLIC__/adm/js/bootstrap-wizard/jquery.bootstrap.wizard.min.js"></script>
	<!-- WIZARD -->
	<script src="__PUBLIC__/adm/js/jquery-validate/jquery.validate.min.js"></script>
	<!-- FLOT CHARTS -->
	<script src="__PUBLIC__/adm/js/flot/jquery.flot.min.js"></script>
	<script src="__PUBLIC__/adm/js/flot/jquery.flot.time.min.js"></script>
        <script src="__PUBLIC__/adm/js/flot/jquery.flot.selection.min.js"></script>
	<script src="__PUBLIC__/adm/js/flot/jquery.flot.resize.min.js"></script>
        <script src="__PUBLIC__/adm/js/flot/jquery.flot.pie.min.js"></script>
        <script src="__PUBLIC__/adm/js/flot/jquery.flot.stack.min.js"></script>
        <script src="__PUBLIC__/adm/js/flot/jquery.flot.crosshair.min.js"></script>
	<!-- TODO -->
	<script type="text/javascript" src="__PUBLIC__/adm/js/jquery-todo/js/paddystodolist.js"></script>
	<!-- TIMEAGO -->
	<script type="text/javascript" src="__PUBLIC__/adm/js/timeago/jquery.timeago.min.js"></script>
	<!-- FULL CALENDAR -->
	<script type="text/javascript" src="__PUBLIC__/adm/js/fullcalendar/fullcalendar.min.js"></script>
	<!-- COOKIE -->
	<script type="text/javascript" src="__PUBLIC__/adm/js/jQuery-Cookie/jquery.cookie.min.js"></script>
	<!-- GRITTER -->
	<script type="text/javascript" src="__PUBLIC__/adm/js/gritter/js/jquery.gritter.min.js"></script>
</head>
<body>
	<!-- HEADER -->
	<header class="navbar clearfix" id="header">
            <div class="container">
                <div class="navbar-brand">
                    <!-- COMPANY LOGO -->
                    <a href="index.html">
                        <img src="__PUBLIC__/adm/img/logo/logo.png" alt="Cloud Admin Logo" class="img-responsive" height="30" width="120">
                    </a>
                    <!-- /COMPANY LOGO -->

                    <!-- SIDEBAR COLLAPSE -->
                    <div id="sidebar-collapse" class="sidebar-collapse btn">
                            <i class="fa fa-bars" data-icon1="fa fa-bars" data-icon2="fa fa-bars" ></i>
                    </div>
                    <!-- /SIDEBAR COLLAPSE -->
                </div>
                <!-- NAVBAR LEFT -->
                <ul class="nav navbar-nav pull-left hidden-xs" id="navbar-left">
                        <li class="dropdown">
                        </li>
                </ul>
                <!-- /NAVBAR LEFT -->
                <!-- BEGIN TOP NAVIGATION MENU -->					
                <ul class="nav navbar-nav pull-right">
                    <!-- BEGIN USER LOGIN DROPDOWN -->
                    <li class="dropdown user" id="header-user">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <span class="username"><?php echo session(C('ADMIN_SESSION'))['name'];?></span>
                            <i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo U('admin/index/logout');?>"><i class="fa fa-power-off"></i>退出</a></li>
                        </ul>
                    </li>
                    <!-- END USER LOGIN DROPDOWN -->
                </ul>
                <!-- END TOP NAVIGATION MENU -->
            </div>
	</header>
	<!--/HEADER -->
	
	<!-- PAGE -->
	<section id="page">
                    <!-- SIDEBAR -->
                    <include file="Common/main_menu"/>
                    <!-- /SIDEBAR -->
		<div id="main-content">
			<!-- SAMPLE BOX CONFIGURATION MODAL FORM-->
			<div class="modal fade" id="box-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
                                    <form class="form-horizontal" novalidate="novalidate" method="post" >
				    <div class="modal-content">
					<div class="modal-header">
					  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					  <h4 class="modal-title">Box Settings</h4>
					</div>
					<div class="modal-body">
                                                
					</div>
					<div class="modal-footer">
					  <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
					  <button type="submit" class="btn btn-primary">保存</button>
					</div>
				    </div>
                                    </form>
				</div>
			</div>
			<!-- /SAMPLE BOX CONFIGURATION MODAL FORM-->
			<div class="container">
				<div class="row">
					<div id="content" class="col-lg-12">
						
						<!-- /BEGIN CONTENT-->
                                                {__CONTENT__}
						<!-- /END CONTENT-->
						
						<div class="footer-tools">
							<span class="go-top">
								<i class="fa fa-chevron-up"></i> Top
							</span>
						</div>
					</div><!-- /CONTENT-->
				</div>
			</div>
		</div>
	</section>
	<!--/PAGE -->
	<!-- JAVASCRIPTS -->
	<!-- CUSTOM SCRIPT -->
	<script src="__PUBLIC__/adm/js/script.js"></script>
	<script>
            jQuery(document).ready(function() {
                <?php
                if(isset($pageSet)) {
                    echo 'App.setPage("'.$pageSet.'");';  //Set current page
                }
                ?>
                App.setActive("<?php echo $controller;?>","<?php echo $action;?>");
                App.init(); //Initialise plugins and elements
                    
            });
	</script>
	<!-- /JAVASCRIPTS -->
</body>
</html>