var getAid = function() {
    $.ajax({ 
        type: "post", 
        url: siturl +"Admin/Account/ajaxServiceUrl",
        data: {appid: $("input[name='appid']").val(),appsecret: $("input[name='appsecret']").val(),mt: parseInt(Math.random()*1000)},
        dataType: "json", 
        success: function (json) {
            if(json.url == '') { 
                bootbox.alert('服务器地址获取失败，请重新获取');
                return;
            }
            $("input[name='serviceurl']").val(json.url);
        }, 
        error: function (XMLHttpRequest, textStatus, errorThrown) { 
            bootbox.alert(errorThrown);
        }
    });
};
            
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
        html.push('<label class="col-sm-4 control-label">公众号描述</label>');
        html.push('<div class="col-sm-8">');
        html.push('<input type="text" name="remark" id="remark" class="form-control" placeholder="公众号描述" maxlength="32">');
        html.push('<input type="hidden" name="id" id="id" value="0">');
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
        html.push('<label class="col-sm-4 control-label">Token');
        html.push('<span class="required">*</span></label>');
        html.push('<div class="col-sm-8">');
        html.push('<input type="text" name="token" id="token" class="form-control" placeholder="英文或数字，3-32位字符" maxlength="32">');
        html.push('<span class="error-span"></span>');
        html.push('</div>');
        html.push('</div>');
        html.push('<div class="form-group">');
        html.push('<label class="col-sm-4 control-label">EncodingAESKey');
        html.push('<span class="required">*</span></label>');
        html.push('<div class="col-sm-8">');
        html.push('<input type="text" name="aeskey" id="aeskey" class="form-control" placeholder="消息加密秘钥，由43位字符组成" maxlength="43">');
        html.push('<span class="error-span"></span>');
        html.push('</div>');
        html.push('</div>');
        html.push('<div class="form-group">');
        html.push('<label class="col-sm-4 control-label">消息加解密方式');
        html.push('<span class="required">*</span></label>');
        html.push('<div class="col-sm-8">');
        html.push('<label class="radio-inline">');
        html.push('<input type="radio" name="encrypt" value="1" data-title="明文模式" class="uniform">');
        html.push('明文模式</label>');
        html.push('<label class="radio-inline">');
        html.push('<input type="radio" name="encrypt" value="2" data-title="兼容模式" class="uniform">');
        html.push('兼容模式</label>');
        html.push('<label class="radio-inline">');
        html.push('<input type="radio" name="encrypt" value="3" data-title="安全模式" class="uniform">');
        html.push('安全模式</label>');
        html.push('<span class="error-span"></span>');
        html.push('</div>');
        html.push('</div>');

        $('.modal-title').html("<h3>绑定微信公众号设置</h3><lable class='text-danger'>修改时请确认从第三项开始与微信官方管理平台中的内容一致</lable>");
        $('.modal-body').html(html.join(""));
        $(".uniform").uniform();
    };
    return {
        init: function() {
            initpopu();
        }
    };
}();

var addWizard = function () {
    return {
        init: function () {
            if (!jQuery().bootstrapWizard) {
                return;
            }
            var wizform = $('#wizForm');
	    var alert_success = $('.alert-success', wizform);
            var alert_error = $('.alert-danger', wizform);
            var server_url = '';
            
            var confirmInput = function() {
                $(".form-control-static").each(function(){
                    var name = $(this).attr("data-display");
                    var txt = '';
                    if(name == 'encrypt') {
                        txt = $('input:radio[name="'+name+'"]:checked').val();
                        txt = encryptType[txt];
                    } else {
                        txt = $("input[name='"+name+"']").val();
                    }
                    $(this).html(txt);
                });
            };
            
            var accountAdd = function() {
                $.ajax({ 
                    type: "post", 
                    url: siturl +"Admin/Account/ajaxAccountAdd",
                    data: {
                        name:$("input[name='name']").val(),
                        appid: $("input[name='appid']").val(),
                        appsecret: $("input[name='appsecret']").val(),
                        remark: $("input[name='name']").val(),
                        token: $("input[name='token']").val(),
                        aeskey: $("input[name='aeskey']").val(),
                        encrypt: $('input:radio[name="encrypt"]:checked').val()
                    },
                    dataType: "json", 
                    success: function (json) {
                        if(json.code == 1) { 
                            bootbox.dialog({
                                message: "新增公众号保存成功，关闭后将跳到公众号列表页！",
                                buttons: {
                                    main: {
                                        label: "返回列表",
                                        className: "btn-primary",
                                        callback: function() {
                                            location.href = siturl + "Admin/Account/index";
                                        }
                                    }
                                }
                            });
                        }
                        else {
                            bootbox.alert(json.msg);
                        }
                    }, 
                    error: function (XMLHttpRequest, textStatus, errorThrown) { 
                        bootbox.alert(errorThrown);
                    }
                });
            }
            
            /*-----------------------------------------------------------------------------------*/
            /*	Validate the form elements
            /*-----------------------------------------------------------------------------------*/
            wizform.validate({
                doNotHideMessage: true,
		errorClass: 'error-span',
                errorElement: 'span',
                rules: {
                    /* Create Account */
		    name: {
                        required: true,
                        maxlength: 12
                    },
                    appid: {
                        required: true,
                        minlength: 15,
                        maxlength: 30
                    },
                    appsecret: {
                        required: true,
                        minlength: 32,
                        maxlength: 32
                    },
                    remark: {
                        maxlength: 32
                    },
                    token: {
                        required: true,
                        minlength: 3,
                        maxlength: 32
                    },
		    aeskey: {
                        required: true,
                        minlength: 43,
                        maxlength: 43
                    }
                },
                messages:{
                    name: "公众号名称为12位内的字符串",
                    appid: "应用ID为20-30位的字符串",
                    appsecret: "应用秘钥为32位字符串",
                    token: "token必须为英文或数字，长度为3-32字符。",
                    aeskey: "消息加密密钥由43位字符组成，字符范围为A-Z，a-z，0-9。"
                },

                invalidHandler: function (event, validator) { 
                    alert_success.hide();
                    alert_error.show();
                },

                highlight: function (element) { 
                    $(element)
                        .closest('.form-group').removeClass('has-success').addClass('has-error'); 
                },

                unhighlight: function (element) { 
                    $(element)
                        .closest('.form-group').removeClass('has-error'); 
                },

                success: function (label) {
                    if (label.attr("for") === "encrypt") {
                        label.closest('.form-group').removeClass('has-error').addClass('has-success');
                        label.remove(); 
                    } else { 
                        label.addClass('valid') 
                        .closest('.form-group').removeClass('has-error').addClass('has-success'); 
                    }
                }
            });

            /*-----------------------------------------------------------------------------------*/
			/*	Initialize Bootstrap Wizard
			/*-----------------------------------------------------------------------------------*/
            $('#formWizard').bootstrapWizard({
                'nextSelector': '.nextBtn',
                'previousSelector': '.prevBtn',
                onNext: function (tab, navigation, index) {
                    alert_success.hide();
                    alert_error.hide();
                    if (wizform.valid() == false) {
                        return false;
                    }
                    var total = navigation.find('li').length;
                    var current = index + 1;
                    $('.stepHeader', $('#formWizard')).text('Step ' + (index + 1) + ' of ' + total);
                    jQuery('li', $('#formWizard')).removeClass("done");
                    var li_list = navigation.find('li');
                    for (var i = 0; i < index; i++) {
                        jQuery(li_list[i]).addClass("done");
                    }
                    if (current == 1) {
                        $('#formWizard').find('.prevBtn').hide();
                    } else {
                        $('#formWizard').find('.prevBtn').show();
                    }
                    if (current >= total) {
                        $('#formWizard').find('.nextBtn').hide();
                        $('#formWizard').find('.submitBtn').show();
                        //bootbox.alert('end form');
                        confirmInput();
                    } else {
                        $('#formWizard').find('.nextBtn').show();
                        $('#formWizard').find('.submitBtn').hide();
                        if(current == 2) { getAid(); }
                    }
                },
                onPrevious: function (tab, navigation, index) {
                    alert_success.hide();
                    alert_error.hide();
                    var total = navigation.find('li').length;
                    var current = index + 1;
                    $('.stepHeader', $('#formWizard')).text('Step ' + (index + 1) + ' of ' + total);
                    jQuery('li', $('#formWizard')).removeClass("done");
                    var li_list = navigation.find('li');
                    for (var i = 0; i < index; i++) {
                        jQuery(li_list[i]).addClass("done");
                    }
                    if (current == 1) {
                        $('#formWizard').find('.prevBtn').hide();
                    } else {
                        $('#formWizard').find('.prevBtn').show();
                    }
                    if (current >= total) {
                        $('#formWizard').find('.nextBtn').hide();
                        $('#formWizard').find('.submitBtn').show();
                    } else {
                        $('#formWizard').find('.nextBtn').show();
                        $('#formWizard').find('.submitBtn').hide();
                    }
                },
		onTabClick: function (tab, navigation, index) {
                    //bootbox.alert('On Tab click is disabled!!');
//                    this.onTabShow(tab, navigation, index);
                    return false;
                },
                onTabShow: function (tab, navigation, index) {
                    var total = navigation.find('li').length;
                    var current = index + 1;
                    var $percent = (current / total) * 100;
                    $('#formWizard').find('.progress-bar').css({
                        width: $percent + '%'
                    });
                }
            });

            $('#formWizard').find('.prevBtn').hide();
            $('#formWizard .submitBtn').click(function () {
                accountAdd();
            }).hide();
        }
    };
}();

function checkAdd() {
    $.ajax({ 
        type: "post", 
        url: siturl +"Admin/Account/ajaxChkAdd",
        data: {mt: parseInt(Math.random()*1000)},
        dataType: "json", 
        success: function (json) {
            if(json.add === 1) { return; }
            $('.prevBtn').attr('disabled', 'disabled');
            $('.nextBtn').attr('disabled', 'disabled');
            $('.submitBtn').attr('disabled', 'disabled');
            bootbox.dialog({
                message: "你目前的账号权限已经不能添加更多的公众号，请联系管理员提升账号等级！",
                buttons: {
                    main: {
                        label: "返回我的列表",
                        className: "btn-primary",
                        callback: function() {
                            location.href = siturl + "Admin/Account/index";
                        }
                    }
                }
            });
        }, 
        error: function (XMLHttpRequest, textStatus, errorThrown) { 
            bootbox.alert(errorThrown);
        }
    });
}
