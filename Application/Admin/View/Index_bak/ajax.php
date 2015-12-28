<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>Welcome - Admin</title>

		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="../../../back/css/bootstrap.min.css" />
		<link rel="stylesheet" href="../../../back/css/font-awesome.min.css" />

		<!-- text fonts -->
		<link rel="stylesheet" href="../../../back/css/ace-fonts.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="../../../back/css/ace.min.css" id="main-ace-style" />

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="__ADMIN__/css/ace-part2.min.css" />
		<![endif]-->
		<link rel="stylesheet" href="../../../back/css/ace-skins.min.css" />
		<link rel="stylesheet" href="../../../back/css/ace-rtl.min.css" />

		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="__ADMIN__/css/ace-ie.min.css" />
		<![endif]-->

		<!-- ace settings handler -->
		<script src="../../../back/js/ace-extra.min.js"></script>

		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

		<!--[if lte IE 8]>
		<script src="__ADMIN__/js/html5shiv.js"></script>
		<script src="__ADMIN__/js/respond.min.js"></script>
		<![endif]-->
	</head>

	<body class="no-skin">
		<!-- #section:basics/navbar.layout -->
		<div id="navbar" class="navbar navbar-default">
			<script type="text/javascript">
				try{ace.settings.check('navbar' , 'fixed')}catch(e){}
			</script>

			<div class="navbar-container" id="navbar-container">
				<!-- #section:basics/sidebar.mobile.toggle -->
				<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler">
					<span class="sr-only">Toggle sidebar</span>

					<span class="icon-bar"></span>

					<span class="icon-bar"></span>

					<span class="icon-bar"></span>
				</button>

				<!-- /section:basics/sidebar.mobile.toggle -->
				<div class="navbar-header pull-left">
					<!-- #section:basics/navbar.layout.brand -->
					<a href="#" class="navbar-brand">
						<small>
							<i class="fa fa-leaf"></i>
							商城模板平台管理
						</small>
					</a>
					<!-- /section:basics/navbar.layout.brand -->

					<!-- #section:basics/navbar.toggle -->

					<!-- /section:basics/navbar.toggle -->
				</div>

				<!-- #section:basics/navbar.dropdown -->
				<div class="navbar-buttons navbar-header pull-right" role="navigation">
					<ul class="nav ace-nav">
						<!-- #section:basics/navbar.user_menu -->
						<li class="light-blue">
							<a data-toggle="dropdown" href="#" class="dropdown-toggle">
								<!----><img class="nav-user-photo" src="../../../back/avatars/user.jpg" alt="Jason's Photo" />
								<span class="user-info">
									<small>欢迎,</small>{$Think.session.tuiguan_admin}
								</span>

								<i class="ace-icon fa fa-caret-down"></i>
							</a>

							<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
								<li>
									<a href="#" id="editadminpwd">
										<i class="ace-icon fa fa-cog"></i>
										密码修改
									</a>
                                                                    
								</li>

								<li class="divider"></li>

								<li>
									<a href="{:U('Admin/Index/logout')}">
										<i class="ace-icon fa fa-power-off"></i>
										退出
									</a>
								</li>
							</ul>
						</li>

						<!-- /section:basics/navbar.user_menu -->
					</ul>
				</div>

				<!-- /section:basics/navbar.dropdown -->
			</div><!-- /.navbar-container -->
		</div>

		<!-- /section:basics/navbar.layout -->
		<div class="main-container" id="main-container">
			<script type="text/javascript">
				try{ace.settings.check('main-container' , 'fixed')}catch(e){}
			</script>

			<!-- #section:basics/sidebar -->
			<div id="sidebar" class="sidebar responsive">
				<script type="text/javascript">
					try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
				</script>
				
				<ul class="nav nav-list">
					<li class="">
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-list"></i>
							<span class="menu-text"> 商城案例 </span>

							<b class="arrow fa fa-angle-down"></b>
						</a>

						<b class="arrow"></b>

						<ul class="submenu">
                                                        
							<li class="">
								<a data-url="page/plist" href="#page/plist">
									<i class="menu-icon fa fa-caret-right"></i>
									案例列表
								</a>

								<b class="arrow"></b>
							</li>

							<li class="">
                                                                <a data-url="page/add" href="#page/add">
									<i class="menu-icon fa fa-caret-right"></i>
									新增案例
								</a>

								<b class="arrow"></b>
							</li>
                                                        
                                                        
							<li class="" style="display: none">
								<a data-url="page/edit" href="#page/edit">
									<i class="menu-icon fa fa-caret-right"></i>测试
								</a>
								<b class="arrow"></b>
							</li>
						</ul>
					</li>

				</ul><!-- /.nav-list -->

				<!-- #section:basics/sidebar.layout.minimize -->
				<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
					<i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
				</div>

				<!-- /section:basics/sidebar.layout.minimize -->
				<script type="text/javascript">
					try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
				</script>
			</div>

			<!-- /section:basics/sidebar -->
			<div class="main-content">
				<!-- #section:basics/content.breadcrumbs -->
				<div class="breadcrumbs" id="breadcrumbs">
					<script type="text/javascript">
						try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
					</script>

					<ul class="breadcrumb">
						<li>
							<i class="ace-icon fa fa-home home-icon"></i>
							<a href="#">首页</a>
						</li>
					</ul><!-- /.breadcrumb -->

				</div>

				<!-- /section:basics/content.breadcrumbs -->
				<div class="page-content">

					<!-- /section:settings.box -->
					<div class="page-content-area">
						<!-- ajax content goes here -->
					</div><!-- /.page-content-area -->
				</div><!-- /.page-content -->
			</div><!-- /.main-content -->

			<div class="footer">
				<div class="footer-inner">
					<!-- #section:basics/footer -->
					<div class="footer-content">
						<span class="bigger-120">
							<span class="blue bolder">linshopbar</span>
							Application &copy; 2015-2020
						</span>

						&nbsp; &nbsp;
						<!--<span class="action-buttons">
							<a href="#">
								<i class="ace-icon fa fa-twitter-square light-blue bigger-150"></i>
							</a>

							<a href="#">
								<i class="ace-icon fa fa-facebook-square text-primary bigger-150"></i>
							</a>

							<a href="#">
								<i class="ace-icon fa fa-rss-square orange bigger-150"></i>
							</a>
						</span>-->
					</div>

					<!-- /section:basics/footer -->
				</div>
			</div>

			<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
				<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
			</a>
		</div><!-- /.main-container -->

		<!-- basic scripts -->

		<!--[if !IE]> -->
		<script type="text/javascript">
			window.jQuery || document.write("<script src='../../../back/js/jquery.min.js'>"+"<"+"/script>");
		</script>

		<!-- <![endif]-->

		<!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='__ADMIN__/js/jquery1x.min.js'>"+"<"+"/script>");
</script>
<![endif]-->
		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='../../../back/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
		</script>

		<script src="../../../back/js/bootstrap.min.js"></script>
                <script src="../../../back/js/jquery-ui.custom.min.js"></script>
                <script src="../../../back/js/jquery.ui.touch-punch.min.js"></script>
                <script src="../../../back/js/jquery.easypiechart.min.js"></script>
                <script src="../../../back/js/jquery.gritter.min.js"></script>
                <script src="../../../back/js/spin.min.js"></script>
                

		<!-- ace scripts -->
		<script src="../../../back/js/ace-elements.min.js"></script>
		<script src="../../../back/js/ace.min.js"></script>
                <script src="../../../back/js/bootbox.min.js"></script>
                <script type="text/javascript" src="../../../back/js/jquery.validate.min.js"></script>
                <script type="text/javascript" src="../../../back/js/jquery.validate.message.js"></script>
		<script type="text/javascript">
                //Load content via ajax
                jQuery(function($) {
                    if('enable_ajax_content' in ace) {
                          var options = {
                            content_url: function(url) {
                                  //this is for Ace demo only, you should change it
                                  //please refer to documentation for more info

                                  if(!url.match(/^page\//)) return false;

                                  var path = document.location.pathname;

                                  //for Ace HTML demo version, convert ajax.html#page/gallery to > gallery.html and load it
                                  if(path.match(/ajax\.html/)) return path.replace(/ajax\.html/, url.replace(/^page\//, '')+'.html') ;

                                  //for Ace PHP demo version convert "page/dashboard" to "?page=dashboard" and load it
                                  return path + "?" + url.replace(/\//, "=");
                            },
                            default_url: 'page/plist'//default url
                          };
                          ace.enable_ajax_content($, options);
                    }
                  
                    $('#editadminpwd').click(function() {
                        html  = '<p>';
                        html += '<span class="lbl">新密码:&nbsp;&nbsp;</span>';
                        html += '<input type="password" id="adminpwd" name="adminpwd" class="input-sm" maxlength="12" />&nbsp;&nbsp;';
                        html += '<span class="lbl">重复密码:&nbsp;&nbsp;</span>';
                        html += '<input type="password" id="adminpwd2" name="adminpwd2" class="input-sm" maxlength="12" />&nbsp;&nbsp;';
                        html += '<button data-bb-handler="danger" onclick="pwdeditform()" type="button" class="btn btn-sm btn-primary">保存密码</button>';
                        html += '</p>';
                        bootbox.dialog({
                                title: '管理员密码修改',
                                message: html,
                                buttons: {
                                        "success" : {
                                                "label" : '关闭',
                                                "className" : "btn-sm btn-success"
                                            }
                                }
                        });
                    });

                });
                
                function pwdeditform() {
                    var pwd = $('#adminpwd').val();
                    var pwd2 = $('#adminpwd2').val();
                    if(pwd.length < 6 || pwd.length > 12) {
                        $(".modal-title").html("管理员密码修改 &nbsp;&nbsp;<font style='color:red'>新密码长度在6-12位间</font>");
                        return;
                    }
                    if(pwd !== pwd2){
                        $(".modal-title").html("管理员密码修改 &nbsp;&nbsp;<font style='color:red'>两次密码不一致</font>");
                        return;
                    }
                        $.ajax({ 
                                type: "post", 
                                url: "{:U('/Admin/Index/editadmin')}",
                                data: {pwd: $('#adminpwd').val(),},
                                dataType: "json", 
                                success: function (json) { 
                                    $(".modal-title").html("管理员密码修改 &nbsp;&nbsp;<font style='color:blue'>密码修改成功</font>");
                                    $('#adminpwd').val('');$('#adminpwd2').val('');
                                }, 
                                error: function (XMLHttpRequest, textStatus, errorThrown) { 
                                        alert(errorThrown);
                                }
                        });
                    }
		</script>
	</body>
</html>
