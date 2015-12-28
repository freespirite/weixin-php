
<title>案例管理-案例列表</title>

<!-- ajax layout which only needs content area -->
<div class="page-header">
	<h1>
		平台列表
		
	</h1>
</div><!-- /.page-header -->

<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div class="row">
			<div class="col-xs-12">
				<table id="sample-table-1" class="table table-striped table-bordered table-hover">
					<thead>
						<tr>
							<th>案例名称</th>
							<th>案例图</th>
							<th>状态</th>
                                                        <th>排序</th>
							<th>描述</th>
							<th>
								<i class="ace-icon fa fa-clock-o bigger-110 hidden-480"></i>
								创建时间
							<th>操作</th>
						</tr>
					</thead>
<?php foreach($plist as $list) {?>
					<tbody>
						<tr>
							
							<td><?php echo $list['title'];?></td>
                                                        <td><a href="<?php echo $list['link'];?>" target="_blank"><img src="<?php echo C('UCLOUD_CDN').$list['pic'];?>" width="100"></a></td>
							<td>
								<?php if ($list['disable']) { ?>
									<span class="label label-sm label-warning">关闭</span>
								<?php } else { ?>
									<span class="label label-sm label-success">正常</span>
								<?php } ?>
							</td>
                                                        <td><?php echo $list['ord'];?></td>
							<td><?php echo $list['remark'];?></td>
							<td><?php echo $list['createtime'];?></td>
							<td>
								<div class="hidden-sm hidden-xs btn-group">
                                    <a data-url="page/edit" href="#page/edit&id={$list.id}">
                                    <i class="menu-icon fa fa-caret-right"></i>修改</a>
                                    <a href="javascript:del({$list.id},'{$list.pic}')">
                                    <i class="menu-icon fa fa-caret-right"></i>删除</a>
								</div>
							</td>
						</tr>

						
					</tbody>
<?php } ?>
				</table>
				{$page}
			</div><!-- /.span -->
		</div><!-- /.row -->
                
                <div id="modal-table" class="modal fade" tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header no-padding">
						<div class="table-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
								<span class="white">&times;</span>
							</button>
							Results for "Latest Registered Domains
						</div>
					</div>
					
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div>
		<!-- PAGE CONTENT ENDS -->
	</div><!-- /.col -->
</div><!-- /.row -->
<!-- page specific plugin scripts -->
<script type="text/javascript">
	var scripts = [null, null];
	ace.load_ajax_scripts(scripts, function() {
	  //inline scripts related to this page
	});

function del(id, key) {
    if(confirm("确认删除")) {
        $.ajax({ 
                type: "post", 
                url: "{:U('/Admin/Index/del')}",
                data: {id: id, key: key},
                dataType: "json", 
                success: function (json) { 
                    if(json.code == 1) {
                        window.location.reload();
//                        ace.enable_ajax_content(jQuery, {
//                            content_url: function() {
//                                  return 'plist';
//                            },
//                            default_url: 'plist'//default url
//                          });
                    }
                    else {
                        alert('删除失败');
                    }
                }, 
                error: function (XMLHttpRequest, textStatus, errorThrown) { 
                        alert(errorThrown);
                }
        });
    }
}

</script>
