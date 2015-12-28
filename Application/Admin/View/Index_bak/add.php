
<title>案例管理-添加案例</title>

<link rel="stylesheet" href="../../../back/css/jquery-ui.custom.min.css" />
<link rel="stylesheet" href="../../../back/css/chosen.css" />
<link rel="stylesheet" href="../../../back/css/datepicker.css" />
<link rel="stylesheet" href="../../../back/css/bootstrap-timepicker.css" />
<link rel="stylesheet" href="../../../back/css/daterangepicker.css" />
<link rel="stylesheet" href="../../../back/css/bootstrap-datetimepicker.css" />
<link rel="stylesheet" href="../../../back/css/colorpicker.css" />
<link rel="stylesheet" href="../../../back/css/jquery.gritter.css" />

<style>
label.error { font-size: 11px;color: red;}
</style>
<!-- ajax layout which only needs content area -->
<div class="page-header">
	<h1>
		案例新增
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
			请填写下面具体信息
		</small>
	</h1>
</div><!-- /.page-header -->

<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
                <form class="form-horizontal" action="{:U('/admin/Index/add')}"  enctype="multipart/form-data" id="addform" role="form" method="post" target="ifr" >
			<!-- #section:elements.form -->
			<div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">
                                <span class="middle pink">*</span>案例名称 </label>

                            <div class="col-sm-9">
                                <input type="text" id="title" name="title" placeholder="" class="col-xs-10 col-sm-5" maxlength="15" />
                            </div>
			</div>

			<div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">
                                <span class="middle pink">*</span>案例图片 </label>

                            <div class="col-sm-9">
								<!-- <input type="button" id="uploadButton" value="Upload" />
								<div id="uppic"></div> -->
								<input type="file" name="pic" value="select file">
                            </div>
			</div>

			<!-- /section:elements.form -->
			<div class="space-4"></div>
                        
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">
                                <span class="middle pink">*</span>案例地址 </label>

                            <div class="col-sm-9">
                                <input type="text" name="link" id="link" placeholder="" class="col-xs-10 col-sm-5" />
                            </div>
			</div>
                        
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">
                                <span class="middle pink">*</span>案例状态 </label>

                            <div class="col-sm-9">
                                <label>
                                    <input value="0" name="disable" type="radio" checked="checked" />开通 
                                    <input value="1" name="disable" type="radio" />屏蔽
                                    <span class="lbl"></span>
                                </label>
                            </div>
			</div>

			<div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">
                                <span class="middle pink">*</span>案例排序 </label>

                            <div class="col-sm-9">
                                <label>
                                    <input id="ord" value="" onkeyup="this.value=this.value.replace(/\D/g,'')" name="ord" class="ace ace-switch ace-switch-4"  type="input" maxlength="8" />
                                    <span class="lbl"></span>
                                </label>
                            </div>
			</div>
                        
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 描述 </label>
                            <div class="col-sm-9">
                                <textarea class="col-xs-12 col-sm-5" id="remark" name="remark" placeholder="平台相关描述"></textarea>
                            </div>
                        </div>
                        
			<div class="clearfix form-actions">
				<div class="col-md-offset-3 col-md-9">
					<button class="btn btn-info" id="btnlogin" type="submit">
					<i class="ace-icon fa fa-check bigger-110"></i>提交</button>

					&nbsp; &nbsp; &nbsp;
					<button class="btn" type="reset">
					<i class="ace-icon fa fa-undo bigger-110"></i>
					重设</button>
				</div>
			</div>

			<div class="space-24"></div>
		</form>
		<!-- PAGE CONTENT ENDS -->
	</div><!-- /.col -->
</div><!-- /.row -->
<iframe name="ifr" id="ifr" src="" style="display:none" width="0" ></iframe>
<!-- page specific plugin scripts -->

<!--[if lte IE 8]>
  <script src="__ADMIN__/js/excanvas.min.js"></script>
<![endif]-->
<script type="text/javascript">
    var scripts = [null, null];
    ace.load_ajax_scripts(scripts, function() {
        
    });
    function readd() {
        window.location.reload();
    }
    function showlist() {
        url = location.href;
        ary = url.split('#');
        location.href = ary[0]+"#page/plist";
    }
</script>