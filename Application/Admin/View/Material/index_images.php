<link rel="stylesheet" href="__PUBLIC__/adm/kedit/themes/default/default.css" />
<script src="__PUBLIC__/adm/kedit/kindeditor-min.js"></script>
<script src="__PUBLIC__/adm/kedit/lang/zh_CN.js"></script>

<?php
//echo json_encode($aryWx);exit;
?>
<script>
//var wxAccount = {'apid01' : 'first','apid02' : 'second','apid03' : 'third'};
var wxAccount = <?php echo json_encode($aryWx);?>;
KindEditor.ready(function(K) {
    var editor = K.editor({
            allowFileManager : false,
            uploadJson : "{:U('material/upload',array('type'=>'image'))}"
    });
    K('#wxupload').click(function() {
        editor.loadPlugin('image', function() {
            editor.plugin.imageDialog({
                showRemote : false,
                imageUrl : K('#url3').val(),
                clickFn : function(url, title, width, height, border, align) {
                    K('#url3').val(url);
                    editor.hideDialog();
                }
            });
        });
    });

});
</script>

<div class="row">
    <div class="col-md-12">
            <!-- BOX -->
            <div class="box">
                    <div class="box-title">
                       <h4><i class="fa fa-bars"></i>图片列表</h4>
                       &nbsp;<button id="wxupload" class="btn btn-xs btn-success"><i class="fa fa-plus-square"></i> 新建图文素材</button>
                    </div>
                    <div class="box-body clearfix">
                       <div id="filter-controls" class="btn-group">
                          <div class="hidden-xs">
                              <a href="#" class="btn btn-xs btn-default" data-filter="*">全部</a>
                              <a href="#" class="btn btn-xs btn-info" data-filter=".category_1">Android Apps</a>
                              <a href="#" class="btn btn-xs btn-info" data-filter=".category_2">iPhone Apps</a>
                              <a href="#" class="btn btn-xs btn-info" data-filter=".category_3">Windows Apps</a>
                              <a href="#" class="btn btn-xs btn-info" data-filter=".category_4">Web Apps</a>
                          </div>
                          <div class="visible-xs">
                               <select id="e1" class="form-control">
                                    <option value="*">All</option>
                                    <option value=".category_1">Android Apps</option>
                                    <option value=".category_2">iPhone Apps</option>
                                    <option value=".category_3">Windows Apps</option>
                                    <option value=".category_4">Web Apps</option>
                                </select>
                          </div>
                       </div>
                            <div id="filter-items" class="row">
                                <div class="col-md-3 category_1 item">
                                        <div class="filter-content">
                                                <img id="demo01" src="__PUBLIC__/adm/img/gallery/1.png" alt="" class="img-responsive" />
                                                <div class="hover-content">
                                                        <h4>Image Title</h4>
                                                        <a class="btn btn-success hover-link">
                                                                <i class="fa fa-edit fa-1x"></i>
                                                        </a>
                                                        <a class="btn btn-warning hover-link colorbox-button" id="demo02" href="__PUBLIC__/adm/img/gallery/1.png" title="Image Title">
                                                                <i class="fa fa-search-plus fa-1x"></i>
                                                        </a>
                                                </div>
                                        </div>
                                </div>
                                
                                    <div class="col-md-3 category_1 item">
                                            <div class="filter-content">
                                                    <img src="__PUBLIC__/adm/img/gallery/1.png" alt="" class="img-responsive" />
                                                    <div class="hover-content">
                                                            <h4>Image Title</h4>
                                                            <a class="btn btn-success hover-link">
                                                                    <i class="fa fa-edit fa-1x"></i>
                                                            </a>
                                                            <a class="btn btn-warning hover-link colorbox-button" href="__PUBLIC__/adm/img/gallery/1.png" title="Image Title">
                                                                    <i class="fa fa-search-plus fa-1x"></i>
                                                            </a>
                                                    </div>
                                            </div>
                                    </div>
                                    <div class="col-md-3 category_2 item">
                                            <div class="filter-content">
                                                    <img src="__PUBLIC__/adm/img/gallery/2.jpg" alt="" class="img-responsive" />
                                                    <div class="hover-content">
                                                            <h4>Image Title</h4>
                                                            <a class="btn btn-success hover-link">
                                                                    <i class="fa fa-edit fa-1x"></i>
                                                            </a>
                                                            <a class="btn btn-warning hover-link colorbox-button" href="__PUBLIC__/adm/img/gallery/2.jpg" title="Image Title">
                                                                    <i class="fa fa-search-plus fa-1x"></i>
                                                            </a>
                                                    </div>
                                            </div>
                                    </div>
                                    <div class="col-md-3 category_3 item">
                                            <div class="filter-content">
                                                    <img src="__PUBLIC__/adm/img/gallery/3.png" alt="" class="img-responsive" />
                                                    <div class="hover-content">
                                                            <h4>Image Title</h4>
                                                            <a class="btn btn-success hover-link">
                                                                    <i class="fa fa-edit fa-1x"></i>
                                                            </a>
                                                            <a class="btn btn-warning hover-link colorbox-button" href="__PUBLIC__/adm/img/gallery/3.png" title="Image Title">
                                                                    <i class="fa fa-search-plus fa-1x"></i>
                                                            </a>
                                                    </div>
                                            </div>
                                    </div>
                                    <div class="col-md-3 category_4 item">
                                            <div class="filter-content">
                                                    <img src="__PUBLIC__/adm/img/gallery/4.png" alt="" class="img-responsive" />
                                                    <div class="hover-content">
                                                            <h4>Image Title</h4>
                                                            <a class="btn btn-success hover-link">
                                                                    <i class="fa fa-edit fa-1x"></i>
                                                            </a>
                                                            <a class="btn btn-warning hover-link colorbox-button" href="__PUBLIC__/adm/img/gallery/4.png" title="Image Title">
                                                                    <i class="fa fa-search-plus fa-1x"></i>
                                                            </a>
                                                    </div>
                                            </div>
                                    </div>
                                    <div class="col-md-3 category_1 item">
                                            <div class="filter-content">
                                                    <img src="__PUBLIC__/adm/img/gallery/5.png" alt="" class="img-responsive" />
                                                    <div class="hover-content">
                                                            <h4>Image Title</h4>
                                                            <a class="btn btn-success hover-link">
                                                                    <i class="fa fa-edit fa-1x"></i>
                                                            </a>
                                                            <a class="btn btn-warning hover-link colorbox-button" href="__PUBLIC__/adm/img/gallery/5.png" title="Image Title">
                                                                    <i class="fa fa-search-plus fa-1x"></i>
                                                            </a>
                                                    </div>
                                            </div>
                                    </div>
                                    <div class="col-md-3 category_2 item">
                                            <div class="filter-content">
                                                    <img src="__PUBLIC__/adm/img/gallery/8.png" alt="" class="img-responsive" />
                                                    <div class="hover-content">
                                                            <h4>Image Title</h4>
                                                            <a class="btn btn-success hover-link">
                                                                    <i class="fa fa-edit fa-1x"></i>
                                                            </a>
                                                            <a class="btn btn-warning hover-link colorbox-button" href="__PUBLIC__/adm/img/gallery/8.png" title="Image Title">
                                                                    <i class="fa fa-search-plus fa-1x"></i>
                                                            </a>
                                                    </div>
                                            </div>
                                    </div>
                                    <div class="col-md-3 category_4 item">
                                            <div class="filter-content">
                                                    <img src="__PUBLIC__/adm/img/gallery/7.jpg" alt="" class="img-responsive" />
                                                    <div class="hover-content">
                                                            <h4>Image Title</h4>
                                                            <a class="btn btn-success hover-link">
                                                                    <i class="fa fa-edit fa-1x"></i>
                                                            </a>
                                                            <a class="btn btn-warning hover-link colorbox-button" href="__PUBLIC__/adm/img/gallery/7.jpg" title="Image Title">
                                                                    <i class="fa fa-search-plus fa-1x"></i>
                                                            </a>
                                                    </div>
                                            </div>
                                    </div>
                                    <div class="col-md-3 category_4 item">
                                            <div class="filter-content">
                                                    <img src="__PUBLIC__/adm/img/gallery/2.jpg" alt="" class="img-responsive" />
                                                    <div class="hover-content">
                                                            <h4>Image Title</h4>
                                                            <a class="btn btn-success hover-link">
                                                                    <i class="fa fa-edit fa-1x"></i>
                                                            </a>
                                                            <a class="btn btn-warning hover-link colorbox-button" href="__PUBLIC__/adm/img/gallery/2.jpg" title="Image Title">
                                                                    <i class="fa fa-search-plus fa-1x"></i>
                                                            </a>
                                                    </div>
                                            </div>
                                    </div>
                            </div>
                    </div>
            </div>
            <!-- /BOX -->
    </div>
</div>