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
                                <li>
                                    <?php echo $aryDaohang['sub'][ACTION_NAME]['title'];?> 
                                </li>
                        </ul>
                        <!-- /BREADCRUMBS -->
                        <div class="clearfix">
                            <h3 class="content-title pull-left"><?php echo $aryDaohang['sub'][ACTION_NAME]['title'];?></h3>
                        </div>
                        <div class="description">
                            <!--Form Elements and Features-->
                            绑定公众号后可以进行内容发布，回复管理和自定义菜单设置等一系列公众号接口操作！
                        </div>
                </div>
        </div>
</div>
<!-- /PAGE HEADER -->
<!-- FORMS -->
<div class="row">
        <div class="col-md-12">
<!--                <div class="row">
                    <div class="col-md-11">
                        <div class="box border primary">

                            <div class="box-body big">
                                <h3 class="form-title">开发者ID</h3>
                                <button onclick="wxadd()" class="btn btn-primary">绑定公众号</button>
                            </div>
                        </div>

                    </div>
                </div>-->

                <div class="row">
                    <div class="col-md-11">
                        <!-- BOX -->
                        <div class="box border blue">
                            <div class="box-title">
                                <h4><i class="fa fa-table"></i>我的公众号列表</h4>
                                <button onclick="wxadd()" class="btn btn-xs btn-default">新增绑定公众号</button>
                            </div>
                            <div class="box-body">
                                <table class="table table-striped">
                                    <thead>
                                      <tr>
                                        <th>名称</th>
                                        <th>APPID</th>
                                        <th>创建时间</th>
                                        <th>描述</th>
                                        <th>操作</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                    
                                    </tbody>
                                </table>

                            </div>
                        </div>
                        <!-- /BOX -->
                    </div>
                </div>
        </div>
</div>
<!-- /FORMS -->
<div class="separator"></div>
<script src="__PUBLIC__/adm/js/wxaccount.js"></script>
<script type="text/javascript">
var cache = null;
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
        submitHandler: function(form) {   //表单提交句柄,为一回调函数，带一个参数：form   
            //alert("提交表单");
            $.ajax({ 
                type: "post", 
                url: "{:U('Admin/Account/index','','')}",
                data: {id: $('#id').val(),name: $('#name').val(), appid : $('#appid').val(), appsecret: $('#appsecret').val(), remark: $('#remark').val()},
                dataType: "json", 
                success: function (json) { 
                    if(json.code === 1) {
                        $("#box-config").modal("hide");
                        bootbox.alert("公众号保存成功！");
                        showlist();
                        listClear();
                    }
                    else {
                        bootbox.alert(json.msg);
                    }
                }, 
                error: function (XMLHttpRequest, textStatus, errorThrown) { 
                        bootbox.alert(errorThrown);
                }
            });
            hideModal();
        },

        rules:{
            name: {required: true},
            appid:{required: true},
            appsecret: {required: true}
        },
        //如果验证控件没有message，将调用默认的信息
        messages:{
            name: "公众号名称不能为空",
            appid: "应用ID不能为空",
            appsecret: "应用秘钥不能为空"
        }
    });
    wxaccount.init();
//    $("#box-config").modal("show");
    showlist();
});

function showlist() {
    $.ajax({ 
        type: "post", 
        url: "{:U('Admin/Account/ajaxList','','')}",
        data: {mt: parseInt(Math.random()*1000)},
        dataType: "json", 
        success: function (json) {
            var str = new Array();
            //json.add === 1? $('.box-title h5').show(): $('.box-title h5').hide();
            if(json.data.length <= 0) { cache = null;return; }
            cache = json.data;
            for(var i=0; i<json.data.length; i++) {
                str.push("<tr>");
                str.push("<td>" + json.data[i].name  + "</td>");
                str.push("<td>" + json.data[i].appid + "</td>");
                str.push("<td>" + dateFormat(json.data[i].createtime,1) + "</td>");
                str.push("<td>" + json.data[i].remark + "</td>");
                str.push("<td><button class='btn btn-xs btn-warning' onclick='wxedit("+json.data[i].id+")'>修改</button> ");
                str.push("<button class='btn btn-xs btn-danger' onclick='wxdel("+json.data[i].id+")'>删除</button></td>");
                str.push("</tr>");
            }
            $('.table > tbody').html(str.join(""));
            
        }, 
        error: function (XMLHttpRequest, textStatus, errorThrown) { 
                bootbox.alert(errorThrown);
        }
    });
}
function listClear() {
    $('#name').val(''); $('#appid').val('');
    $('#appsecret').val('');$('#remark').val('');
    $('#id').val('0');
    //$('#appid').removeAttr('readonly');
}
function dateFormat(timestamp,n){
    update = new Date(timestamp*1000);//时间戳要乘1000
    year   = update.getFullYear();
    month  = (update.getMonth()+1<10)?('0'+(update.getMonth()+1)):(update.getMonth()+1);
    day    = (update.getDate()<10)?('0'+update.getDate()):(update.getDate());
    hour   = (update.getHours()<10)?('0'+update.getHours()):(update.getHours());
    minute = (update.getMinutes()<10)?('0'+update.getMinutes()):(update.getMinutes());
    second = (update.getSeconds()<10)?('0'+update.getSeconds()):(update.getSeconds());
    if(n==1){
  return (year+'-'+month+'-'+day+' '+hour+':'+minute+':'+second);
 }else if(n==2){
  return (year+'-'+month+'-'+day);
 }else{
  return 0;
 }
}

function wxadd() {
    listClear();
    $("#box-config").modal("show");
}
function wxedit(id) {
    if(cache === null) { return; }
    for(var i in cache) {
        if(parseInt(cache[i].id) !== id) { continue; } 
        $('#name').val(cache[i].name); 
        $('#appid').val(cache[i].appid);
        $('#appsecret').val(cache[i].appsecret);
        $('#remark').val(cache[i].remark);
        $('#id').val(cache[i].id);
        //$('#appid').attr('readonly', 'readonly');
        $("#box-config").modal("show");
        return;
    }
}
function wxdel(id) {
    bootbox.dialog({
        message: "<lab class='text-danger'>删除后该公众号下的内容将无法关联，请谨慎操作?</lab>",
        title: "<h4 class='text-danger'>警告</h4>",
        buttons: {
            danger: {
                label: "确认删除",
                className: "btn-danger",
                callback: function() {
                    $.ajax({ 
                        type: "post", 
                        url: "{:U('Admin/Account/ajaxWxDel','','')}",
                        data: {id: id},
                        dataType: "json", 
                        success: function (json) {
                            if(json.code === 1) {
                                bootbox.alert("公众号删除成功！");
                                $('.box-title h5').show();
                                showlist();
                            }
                            else {
                                bootbox.alert(json.msg);
                            }
                        }, 
                        error: function (XMLHttpRequest, textStatus, errorThrown) { 
                                bootbox.alert(errorThrown);
                        }
                    });
                    hideModal();
                }
            },
            main: {
                    label: "取消",
                    className: "btn-primary",
                    callback: function() {
                        $(".bootbox").modal("hide");
                    }
            }
        }
    });
}

function hideModal() {
    setTimeout(function() {
        $(".bootbox").modal("hide");
    }, 3000);
}
</script>