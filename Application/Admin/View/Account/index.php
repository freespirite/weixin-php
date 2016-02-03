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
                                <button id="addedit" class="btn btn-primary">绑定公众号</button>
                            </div>
                        </div>

                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-11">
                        <!-- BOX -->
                        <div class="box border blue">
                            <div class="box-title">
                                <h4><i class="fa fa-table"></i>我的公众号列表</h4>
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
                                    <?php 
                                    foreach($list as $row) {
                                    ?>
                                      <tr id="_list<?php echo $row['id'];?>">
                                        <td><?php echo $row['name'];?></td>
                                        <td><?php echo $row['appid'];?></td>
                                        <td><?php echo $row['remark'];?></td>
                                        <td>删除</td>
                                      </tr>
                                    <?php
                                    }
                                    ?>
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
        submitHandler: function(form) {   //表单提交句柄,为一回调函数，带一个参数：form   
            //alert("提交表单");
            $.ajax({ 
                type: "post", 
                url: "{:U('Admin/Account/index','','')}",
                data: {name: $('#name').val(), appid : $('#appid').val(), appsecret: $('#appsecret').val(), remark: $('#remark').val()},
                dataType: "json", 
                success: function (json) { 
                    if(json.code == 1) {
                        bootbox.alert("公众号绑定成功！");
                        showlist();
                        listClear();
                    }
                    else if(json.code == 2)  {
                        bootbox.alert("公众号修改成功！");
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
    showlist();
    $("#addedit").click(function(){
        $("#box-config").modal("show");
    });
});

function showlist() {
    $.ajax({ 
        type: "post", 
        url: "{:U('Admin/Account/ajaxList','','')}",
        data: {mt: parseInt(Math.random()*1000)},
        dataType: "json", 
        success: function (json) {
            var str = new Array();
            for(var i=0; i<json.length; i++) {
                str.push("<tr>");
                str.push("<td>" + json[i].name  + "</td>");
                str.push("<td>" + json[i].appid + "</td>");
                str.push("<td>" + dateFormat(json[i].createtime,1) + "</td>");
                str.push("<td>" + json[i].remark + "</td>");
                str.push("<td><button class='btn btn-xs btn-warning' onclick='wxedit("+json[i]+")'>修改</button> ");
                str.push("<button class='btn btn-xs btn-danger' onclick='wxdel("+json[i].id+")'>删除</button></td>");
                str.push("</tr>");
            }
            if(str.length > 0) {
                $('.table > tbody').html(str.join(""));
            }
        }, 
        error: function (XMLHttpRequest, textStatus, errorThrown) { 
                bootbox.alert(errorThrown);
        }
    });
}
function listClear() {
    $('#name').val(''); $('#appid').val('');
    $('#appsecret').val('');$('#remark').val('');
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

function wxedit(data) {
    
}
function wxdel(id) {
//    bootbox.confirm("公众号修改成功！");
    bootbox.confirm("删除后该公众号下的内容将无法关联，确认删除?", function(result){
        if(result) { bootbox.alert("["+id+"]公众号删除成功！"); }
    });
}
</script>