<link rel="stylesheet" href="__PUBLIC__/adm/kedit/themes/default/default.css" />
<script src="__PUBLIC__/adm/kedit/kindeditor-min.js"></script>
<style>

</style>
<script>
KindEditor.ready(function(K) {
    var el = jQuery(".modal-body");
    var uploadbutton = K.uploadbutton({
        button : K('#uploadButton')[0],
        fieldName : 'imgFile',
        url : '__PUBLIC__/adm/kedit/php/upload_json.php?dir=image',
        afterUpload : function(data) {
            if (data.error === 0) {
                var url = K.formatUrl(data.url, 'absolute');
                K('#url').val(url);
            } else {
                alert(data.message);
            }
            App.unblockUI(el);
        },
        afterError : function(str) {
            App.unblockUI(el);
            alert('自定义错误信息: ' + str);
        }
    });
    uploadbutton.fileBox.change(function(e) {
        uploadbutton.submit();
        App.blockUI(el);
    });
});
</script>
<div class="row">
    <div class="col-md-12">
            <!-- BOX -->
            <div class="box">
                    <div class="box-title">
                       <h4><i class="fa fa-bars"></i>图片列表</h4>
                       &nbsp;<input type="button" id="uploadButton" value="新增图片素材" >
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