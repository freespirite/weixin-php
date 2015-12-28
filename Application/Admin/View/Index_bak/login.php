<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>案例管理-管理登录</title>

		<meta name="description" content="User login page" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="../../../back/css/bootstrap.min.css" />
		<link rel="stylesheet" href="../../../back/css/font-awesome.min.css" />

		<!-- text fonts -->
		<link rel="stylesheet" href="../../../back/css/ace-fonts.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="../../../back/css/ace.min.css" />

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="../assets/css/ace-part2.min.css" />
		<![endif]-->
		<link rel="stylesheet" href="../../../back/css/ace-rtl.min.css" />

		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="__ADMIN__/css/ace-ie.min.css" />
		<![endif]-->
		<link rel="stylesheet" href="../../../back/css/ace.onpage-help.css" />

		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

		<!--[if lt IE 9]>
		<script src="__ADMIN__/js/html5shiv.js"></script>
		<script src="__ADMIN__/js/respond.min.js"></script>
		<![endif]-->
                <script src='../../../back/js/jquery.min.js'></script>
                <style>
                    #messages {color: red;}
                </style>
	</head>

	<body class="login-layout">
		<div class="main-container">
			<div class="main-content">
				<div class="row">
					<div class="col-sm-10 col-sm-offset-1">
						<div class="login-container">
							<div class="center">
								<h1>
									<!--<i class="ace-icon fa fa-leaf green"></i>-->
									<span class="red">O2O</span>
								</h1>
								<h4 class="blue" id="id-company-text">&copy; 来嗅吧互联网技术服务有限公司</h4>
							</div>

							<div class="space-6"></div>

							<div class="position-relative">
								<div id="login-box" class="login-box visible widget-box no-border">
									<div class="widget-body">
										<div class="widget-main">
											<h4 class="header blue lighter bigger">
												<i class="ace-icon fa fa-coffee green"></i>
												商城案例管理
                                                                                                <span id='messages'></span>
											</h4>

											<div class="space-6"></div>

											<form>
												<fieldset>
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
                                                            <input type="text" id="account" class="form-control" placeholder="账号" />
															<i class="ace-icon fa fa-user"></i>
														</span>
													</label>

													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="password" id="pwd" class="form-control" placeholder="密码" />
															<i class="ace-icon fa fa-lock"></i>
														</span>
													</label>

													

                                                            <div class="clearfix" style="text-align: center;">
                                                                <img src="{:U('Admin/Index/imgcode')}" id="imgcode" width="101" height="35">
                                                                <input type="text" id="code" placeholder="验证码" size="9" maxlength="5" />
                                                                <button type="button" id="login" class="width-35 pull-right btn btn-sm btn-primary">
															<i class="ace-icon fa fa-key"></i>
															<span class="bigger-110">登录</span>
														</button>
													</div>

													
												</fieldset>
											</form>

											

											<div class="space-6"></div>

											
										</div><!-- /.widget-main -->

										
									</div><!-- /.widget-body -->
								</div><!-- /.login-box -->

								

								
							</div><!-- /.position-relative -->

							
						</div>
					</div><!-- /.col -->
				</div><!-- /.row -->
			</div><!-- /.main-content -->
		</div><!-- /.main-container -->

		<!-- basic scripts -->

		<!--[if !IE]> -->
		<script type="text/javascript">
			window.jQuery || document.write("<script src='../../../back/js/jquery.min.js'>"+"<"+"/script>");
		</script>

		<!-- <![endif]-->
		
		<script type="text/javascript">
                        $(function(){
                            $('#imgcode').click(function(){
                                $(this).attr("src","{:U('/Admin/Index/imgcode')}");
                            });
                            $('#login').click(function(){
                                var pwd = $('#pwd').val();
                                var account = $('#account').val();
                                var code = $('#code').val();
                                if(account == '') { _alert('请输入登录账号'); return;}
                                if(pwd == '') { _alert('请输入登录密码'); return;}
                                if(code == '') { _alert('请输入验证码'); return;}
                                $(this).attr('disabled','true');
                                $.ajax({ 
                                        type: "post", 
                                        url: "{:U('/Admin/Index/login','','')}",
                                        data: {account: account, pwd : pwd, code: code},
                                        dataType: "json", 
                                        success: function (json) { 
                                                if(json.code == 1)
                                                    window.location.href="{:U('/Admin/Index/index','','')}";
                                                else if(json.code == 2)
                                                    _alert(json.msg);
                                                else {
                                                    _alert(json.msg);
                                                }
                                                $('#code').val('');
                                                $('#imgcode').attr("src","{:U('/Admin/Index/imgcode')}");
                                                $('#login').removeAttr('disabled');
                                        }, 
                                        error: function (XMLHttpRequest, textStatus, errorThrown) { 
                                                _alert(errorThrown);
                                        }
                                });
                            });
                            
//                            var path = document.location.pathname;
//                            var ary = path.split('/');
//                            var fun = ary[ary.length - 1];
//                            if(fun !== 'login.html' && fun !== 'login') {
//                                window.location.href = "{:U('/Admin/Index/login')}";
//                            }
                        });
                        function _alert(msg) {
                            $('#messages').html(msg);
                        }
		</script>
	</body>
</html>
