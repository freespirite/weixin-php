<!Doctype html>
<html xmlns=http://www.w3.org/1999/xhtml>
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta charset="utf-8">
	<title><?php echo C('ADM_TITLE'); ?>-登录</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=no">
	<meta name="description" content="">
	<meta name="author" content="">
	<!-- STYLESHEETS --><!--[if lt IE 9]><script src="js/flot/excanvas.min.js"></script><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script><![endif]-->
	<link rel="stylesheet" type="text/css" href="./adm/css/cloud-admin.css" >
	
	<link href="./adm/font-awesome/css/font-awesome.min.css" rel="stylesheet">
	<!-- DATE RANGE PICKER -->
	<link rel="stylesheet" type="text/css" href="./adm/js/bootstrap-daterangepicker/daterangepicker-bs3.css" />
	<!-- UNIFORM -->
	<link rel="stylesheet" type="text/css" href="./adm/js/uniform/css/uniform.default.min.css" />
	<!-- ANIMATE -->
	<link rel="stylesheet" type="text/css" href="./adm/css/animatecss/animate.min.css" />
	<!-- FONTS -->
	<!--<link href='http://fonts.useso.com/css?family=Open+Sans:300,400,600,700' rel='stylesheet' type='text/css'>-->
</head>
<body class="login">	
	<!-- PAGE -->
	<section id="page">
            <!-- HEADER -->
            <!--/HEADER -->
            <!-- LOGIN -->
            <section id="login" class="visible">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4 col-md-offset-4">
                            <div class="login-box-plain">
                                <h2 class="bigintro">账号注册</h2>
                                <div class="divide-40"></div>
                                <form role="form" method="post" onsubmit="return postreg()">
                                  <div class="form-group">
                                      <label for="exampleInputEmail1">邮箱账号
                                          <font style="font-weight: 500;">(忘记密码时通过邮箱找回)</font>
                                      </label>
                                        <i class="fa fa-envelope"></i>
                                        <input type="email" id="account" class="form-control" placeholder="email" maxlength="50" >
                                  </div>
                                  <div class="form-group"> 
                                        <label for="exampleInputPassword1">登录密码</label>
                                        <i class="fa fa-lock"></i>
                                        <input type="password" id="pwd" class="form-control" placeholder="password" maxlength="12" >
                                  </div>
                                  <div class="form-group"> 
                                        <label for="exampleInputPassword1">确认密码</label>
                                        <i class="fa fa-lock"></i>
                                        <input type="password" id="repwd" class="form-control" placeholder="confirm password" maxlength="12" >
                                  </div>
                                  <h3 class="form-title">验证码</h3>
                                  <div class="form-inline"> 
                                      <i class="fa fa-lock"></i>
                                      <input type="text" id="code" placeholder="验证码" style="width:150px;height:30px" maxlength="4">
                                      <img src="{:U('Admin/Index/imgcode')}" id="imgcode" width="100" height="35">
                                  </div>
                                  <div class="form-actions">
                                      <button type="submit" id="btsb" class="btn btn-danger">提交注册</button>
                                  </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!--/LOGIN -->
			
	</section>
	<!--/PAGE -->
	<!-- JAVASCRIPTS -->
	<!-- Placed at the end of the document so the pages load faster -->
	<!-- JQUERY -->
	<script src="./adm/js/jquery/jquery-2.0.3.min.js"></script>
	<!-- JQUERY UI-->
	<script src="./adm/js/jquery-ui-1.10.3.custom/js/jquery-ui-1.10.3.custom.min.js"></script>
	<!-- BOOTSTRAP -->
	<script src="./adm/bootstrap-dist/js/bootstrap.min.js"></script>
	
	
	<!-- UNIFORM -->
	<script type="text/javascript" src="./adm/js/uniform/jquery.uniform.min.js"></script>
        <script type="text/javascript">
        $(function(){
            $('#imgcode').click(function(){
                $(this).attr("src","{:U('Admin/Index/imgcode')}");
            });
        });
        function postreg() {
            var pwd = $('#pwd').val();
            var pwd = $('#repwd').val();
            var account = $('#account').val();
            var code = $('#code').val();
            var nologin = $('#nologin').is(":checked")==true? 1: 0;
            if(account == '') { _alert('请输入登录账号'); return false;}
            if(pwd == '') { _alert('请输入登录密码'); return false;}
            if(code == '') { _alert('请输入验证码'); return false;}
            //$('#btsb').attr('disabled','true');
            $.ajax({ 
                    type: "post", 
                    url: "{:U('Admin/Index/login','','')}",
                    data: {account: account, pwd : pwd, code: code, nologin: nologin},
                    dataType: "json", 
                    success: function (json) { 
                            if(json.code == 1)
                                window.location.href="{:U('Admin/Index/index','','')}";
                            else if(json.code == 2)
                                _alert(json.msg);
                            else {
                                _alert(json.msg);
                            }
                            $('#code').val('');
                            $('#imgcode').attr("src","{:U('Admin/Index/imgcode')}");
                            $('#login').removeAttr('disabled');
                    }, 
                    error: function (XMLHttpRequest, textStatus, errorThrown) { 
                            _alert(errorThrown);
                    }
            });
            return false;
        }
        function _alert(msg) {
            $('.divide-40').html("<font color=red>"+msg+"</font>");
        }
        </script>
	<!-- /JAVASCRIPTS -->
</body>
</html>