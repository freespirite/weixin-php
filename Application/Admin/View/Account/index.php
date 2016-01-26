<?php
$aryDaohang = C('MENU_ARRAY')[strtolower(CONTROLLER_NAME)];
?>
<style>
    .error {color: red;}
</style>
<!-- PAGE HEADER-->
<div class="row">
        <div class="col-sm-12">
                <div class="page-header">
                        <!-- STYLER -->

                        <!-- /STYLER -->
                        <!-- BREADCRUMBS -->
                        <ul class="breadcrumb">
                                <li>
                                        <i class="fa fa-home"></i>
                                        <a href="<?php echo U('admin/index/index');?>">首页</a>
                                </li>
                                <li>
                                        <a href="#"><?php echo $aryDaohang['title'];?></a>
                                </li>
                                <li><?php echo $aryDaohang['sub'][ACTION_NAME]['title'];?></li>
                        </ul>
                        <!-- /BREADCRUMBS -->
                        <div class="clearfix">
                                <h3 class="content-title pull-left"><?php echo $aryDaohang['sub'][ACTION_NAME]['title'];?></h3>
                        </div>
                        <div class="description">Form Elements and Features</div>
                </div>
        </div>
</div>
<!-- /PAGE HEADER -->
<!-- FORMS -->
<div class="row">
        <div class="col-md-12">
                <div class="row">
                        <div class="col-md-11">
                                <div class="box border primary">
                                        
                                        <div class="box-body big">
                                                <h3 class="form-title">开发者ID</h3>
                                                <form class="form-horizontal" novalidate="novalidate" method="post" >
                                                  <div class="form-group">
                                                        <label class="col-sm-3 control-label">AppID(应用ID)
                                                        <span class="required">*</span></label>
                                                        <div class="col-sm-9">
                                                            <input type="text" name="appid" id="appid" class="form-control" placeholder="应用ID" maxlength="20">
                                                            <span class="error-span"></span>
                                                        </div>
                                                  </div>
                                                  <div class="form-group">
                                                        <label class="col-sm-3 control-label">AppSecret(应用密钥)
                                                            <span class="required">*</span></label>
                                                        <div class="col-sm-9">
                                                            <input type="text" name="appsecret" id="appsecret" class="form-control" placeholder="应用秘钥" maxlength="32">
                                                          <span class="error-span"></span>
                                                        </div>
                                                  </div>
                                                  <button type="submit" class="btn btn-success">保存</button>
                                                </form>
                                        </div>
                                </div>

                        </div>
                </div>
                <div class="separator"></div>

        </div>
</div>
<!-- /FORMS -->
<div class="separator"></div>
<script type="text/javascript">
$(function(){
    jQuery.validator.addMethod("checkappid", function(value, element) {  
        //alert(element.placeholder);
        //var rs = this.optional(element) || value.length < 18;
//        if(this.optional(element) || (value.length < 18)) {
//            element.style.border-color = "red";
//            return true;
//        }
//alert(this.optional(element));
        return this.optional(element) || (value.length <= 18);
    }, "请输入正确的应用ID"); 
    
    var validate = $(".form-horizontal").validate({
        //debug: true, //调试模式取消submit的默认提交功能    
//        submitHandler: function(form){   //表单提交句柄,为一回调函数，带一个参数：form   
//            alert("提交表单");   
//            //form.submit();   //提交表单   
//        },   

        rules:{
            appid:{
                required: true
            },
            appsecret: {
                required: true
            }
        },
        //如果验证控件没有message，将调用默认的信息
        messages:{
            appid: "应用ID不能为空",
            appsecret: "应用秘钥不能为空"

        }

    });    
    
    
});
</script>