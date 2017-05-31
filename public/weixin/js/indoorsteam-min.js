function launchFullscreen(a){if(a.requestFullscreen){a.requestFullscreen()}else{if(a.mozRequestFullScreen){a.mozRequestFullScreen()}else{if(a.msRequestFullscreen){a.msRequestFullscreen()}else{if(a.webkitRequestFullscreen){a.webkitRequestFullScreen()}}}}};function exitFullscreen(){if(document.exitFullscreen){document.exitFullscreen()}else{if(document.mozCancelFullScreen){document.mozCancelFullScreen()}else{if(document.webkitExitFullscreen){document.webkitExitFullscreen()}}}};function fullscreenElement(){return document.fullscreenElement||document.webkitCurrentFullScreenElement||document.mozFullScreenElement||null};
    $(function(){$("#phoneclose").on('click',function(){$("#previewbox").hide()});$("#phone").on('click',function(){if($("#previewbox").css("display")=="block"){$("#previewbox").hide();}else{$("#previewbox").show();}});$(window).on('fullscreenchange webkitfullscreenchange mozfullscreenchange',function(){if(!fullscreenElement()){$('.wxeditor').css({margin:'0'});}});$('.fullshowbox').on('click',function(){$('.wxeditor').css({margin:'50px 0'});launchFullscreen(document.documentElement)});$('.fullhidebox').on('click',function(){$('#wxeditortip,#header').show();exitFullscreen()});var b=["borderTopColor","borderRightColor","borderBottomColor","borderLeftColor"],d=[];$.each(b,function(a){d.push(".itembox .wxqq-"+b[a])});$("#colorpickerbox").ColorPicker({flat:true,color:"#00bbec",onChange:function(a,e,f){$(".itembox .wxqq-bg").css({backgroundColor:"#"+e});$(".itembox .wxqq-color").css({color:"#"+e});$.each(d,function(g){$(d[g]).css(b[g],"#"+e)})}});var c=UE.getEditor("editor",{topOffset:0,autoFloatEnabled:false,autoHeightEnabled:false,autotypeset:{removeEmptyline:true},});c.ready(function(){c.addListener('contentChange',function(){$("#preview").html(c.getContent()+'<div><a style="font-size:12px;color:#607fa6" href="#" target="_blank" id="post-user">阅读原文</a> <em style="color:#8c8c8c;font-style:normal;font-size:12px;">阅读 100000+</em><span class="fr"><a style="font-size:12px;color:#607fa6" href="http://wpa.qq.com/msgrd?v=3&uin=276116565&site=qq&menu=yes" target="_blank">举报</a></span></div>');});$(".itembox").on("click",function(a){c.execCommand("insertHtml","<div>"+$(this).html()+"</div><br />")})});$(".tabs li a").on("click",function(){$(this).addClass("current").parent().siblings().each(function(){$(this).find("a").removeClass("current")});$("#"+$(this).attr("tab")).show().siblings().hide()})});
     
var populayer = null;
$(function () {
 $(window).resize(function(){
		var win_height = $(window).height();
		$('#bdeditor').height(win_height);
		var area_height = win_height -6;
		if(area_height > 800){
			area_height = 800;
		}
		
		$('#editor').height(area_height-40);
		$('#styleselect').height(area_height);
		$('.content').height(area_height-165);
	}).trigger('resize');

$('.clear-editor').click(function(){
		if(confirm('是否确认清空内容，清空后内容将无法恢复')){
			c.setContent('');
		}		
	});
//-------------begin: add by nick------------------
$('.push').click(function(){
            var html = c.getContent();
            var title = $('#title').val();
            var author = $('#author').val();
            var url = $('#source_url').val();
            var mid = $('#pic1').val();
            var digest = $('#digest').val();
            var wxid = $('#wxid').val();
            
            if(wxid == '') {alert('非正常操作');return;}
            if(title == '') {alert('标题不能为空');return;}
            if(author == '') {alert('作者不能为空');return;}
            if(mid == '') {alert('封面图不能为空');return;}
            if(digest == '') {alert('摘要不能为空');return;}
            if(html == '') {alert('发布内容不能为空');return;}
            
            Wind.css('layer');
            Wind.use("layer", function () {
                populayer = layer.msg('正在同步到微信，请稍候...', {time: 0, icon:16, shade: 0.3,shadeClose: false});
            });
//alert(html);return;
    $.ajax({ 
                type: "post", 
                url: "index.php?g=Weixin&m=Material&a=addnews",
                data: {
                	wxid: wxid,
                	title: title,
                	author: author,
                	url: url,
                	mid: mid,
                	digest: digest,
                	content: html
                },
                dataType: "json", 
                success: function (json) {
                    Wind.use("layer", function () {
                        //layer.close(populayer);
                        layer.closeAll();
                    });
                    if(typeof(json.media_id) != 'undefined') {
                        alert('同步成功');
                    } else {
                        alert('同步失败，错误号：'+json.errcode);
                    }
                    
                }, 
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    alert(errorThrown);
                    Wind.use("layer", function () {
                        //layer.close(populayer);
                        layer.closeAll();
                    });
                }
            });
});
//-------------end: add by nick---------------------
	var client = new ZeroClipboard( $('.copy-editor-html') );
	ZeroClipboard.config({
				swfPath: "ueditor/third-party/zeroclipboard/ZeroClipboard.swf"
			});
	client.on( 'ready', function(event) {
		client.on( 'copy', function(event) {
	  		event.clipboardData.setData('text/html', c.getContent());
	  		event.clipboardData.setData('text/plain',c.getContent()); 
		});
        client.on('aftercopy',function(event) {
			alert('正文内容已经复制到剪切板，可粘贴（ctrl+v）到微信公众平台编辑器中使用！');
		 });
	});  
  

    $("#phone").on('click', function () {
         $("#myModal").modal(options)
    });
     $("#wx").on('click', function () {
         $("#wxModal").modal(options)
    });
    $("#kefu").on('click', function () {
         $("#kefu1").modal(options)
    });
	$("#savebox").on('click', function () {
         $("#myModal").modal(options)
    });
	$('#savewx').on('click', function () {
       
   });
   	$('#reguser').on('click', function () {
		
	    $('#loginModal').modal('hide');
		$('#userregModal').modal('show')
       
   });
  
    var b = ["borderTopColor", "borderRightColor", "borderBottomColor", "borderLeftColor"],
        d = [];
    $.each(b, function (a) {
        d.push(".itembox .wxqq-" + b[a])
    });
    $("#colorpickerbox").ColorPicker({
        flat: true,
        color: "#00bbec",
        onChange: function (a, e, f) {
            $(".itembox .wxqq-bg").css({
                backgroundColor: "#" + e
            });
            $(".itembox .wxqq-color").css({
                color: "#" + e
            });
            $.each(d, function (g) {
                $(d[g]).css(b[g], "#" + e)
            })
        }
    });
    /*
    toolbars: [
            ['fullscreen', 'source', 'undo', 'redo', 'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'removeformat', 'autotypeset', 'blockquote', 'pasteplain', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc', 'rowspacingtop', 'rowspacingbottom', 'lineheight', 'indent', 'justifyleft', 'justifycenter', 'justifyright', 'fontfamily', 'fontsize', 'justifyjustify', 'touppercase', 'tolowercase', 'insertimage', 'emotion', 'insertvideo', 'map', 'date', 'time', 'spechars', 'preview', 'searchreplace'],
            ['con', 'title', 'fork', 'guide', 'division', 'other', 'mystyle']
        ],
    */
    var c = UE.getEditor("editor", {
        topOffset: 0,
        autotypeset: {
            removeEmptyline: true
        },
        toolbars: [
            ['fullscreen', 'source', 'undo', 'redo', 'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'removeformat', 'autotypeset', 'blockquote', 'pasteplain', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc', 'rowspacingtop', 'rowspacingbottom', 'lineheight', 'indent', 'justifyleft', 'justifycenter', 'justifyright', 'fontfamily', 'fontsize', 'justifyjustify', 'touppercase', 'tolowercase', 'insertimage', 'emotion', 'map', 'date', 'time', 'spechars', 'preview', 'searchreplace'],
            ['con', 'title', 'fork', 'guide', 'division', 'other', 'mystyle']
        ],
        autoHeightEnabled: false,
        allowDivTransToP: false,
        autoFloatEnabled: true,
        enableAutoSave: false
    });
    
    $(".tabs li a").on("click", function () {
        $(this).addClass("current").parent().siblings().each(function () {
            $(this).find("a").removeClass("current")
        });
        $("#" + $(this).attr("tab")).show().siblings().hide()
    });
 
});

$(document).ready(function(){
	$(".advbox").show();
	$(".advbox").animate({top:"60%"},1000);
	$(".closebtn").click(function(){
		$(".advbox").fadeOut(500);
	})
})

window.onbeforeunload = function(event) { 
(event || window.event).returnValue = "温馨提示：您即将关闭页面，是否确认编辑内容已经复制到微信公众平台后台？"; 
} 