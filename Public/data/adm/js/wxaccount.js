
var wxaccount = function() {
    var initpopu = function() {
        var html = new Array();
        html.push('<div class="form-group">');
        html.push('<label class="col-sm-4 control-label">公众号名称');
        html.push('<span class="required">*</span></label>');
        html.push('<div class="col-sm-8">');
        html.push('<input type="text" name="name" id="name" class="form-control" placeholder="公众号名称" maxlength="10">');
        html.push('<span class="error-span"></span>');
        html.push('</div>');
        html.push('</div>');
        html.push('<div class="form-group">');
        html.push('<label class="col-sm-4 control-label">AppID(应用ID)');
        html.push('<span class="required">*</span></label>');
        html.push('<div class="col-sm-8">');
        html.push('<input type="text" name="appid" id="appid" class="form-control" placeholder="应用ID" maxlength="20">');
        html.push('<span class="error-span"></span>');
        html.push('</div>');
        html.push('</div>');
        html.push('<div class="form-group">');
        html.push('<label class="col-sm-4 control-label">AppSecret(应用密钥)');
        html.push('<span class="required">*</span></label>');
        html.push('<div class="col-sm-8">');
        html.push('<input type="text" name="appsecret" id="appsecret" class="form-control" placeholder="应用秘钥" maxlength="32">');
        html.push('<span class="error-span"></span>');
        html.push('</div>');
        html.push('</div>');
        html.push('<div class="form-group">');
        html.push('<label class="col-sm-4 control-label">公众号描述</label>');
        html.push('<div class="col-sm-8">');
        html.push('<input type="text" name="remark" id="remark" class="form-control" placeholder="公众号描述" maxlength="32">');
        html.push('<input type="hidden" name="wxid" id="wxid" value="0">');
        html.push('<span class="error-span"></span>');
        html.push('</div>');
        html.push('</div>');

        $('.modal-title').html("微信公众号设置");
        $('.modal-body').html(html.join(""));
    };
    
    return {
        init: function() {
            initpopu();
        }
    };
}();
