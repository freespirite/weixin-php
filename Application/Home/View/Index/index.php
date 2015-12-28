<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>来嗅吧案例演示</title>
<meta name="keywords" content="来嗅吧" />
<meta name="description" content="来嗅吧" />
<meta content="本页版权归来嗅吧所有。all rights reserved" name="copyright" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta name="Author" content="HYZ:276750838" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
<link href="images/favicon.ico" rel="shortcut icon" />
<link type="text/css" rel="stylesheet" href="__HOME__/css/h.css?v=20151020v13">

</head>

<body>
<div id="h" class="h">
    <!--s 头部-->
    <header>
    	<div class="logo"><img src="__HOME__/images/logo.jpg" width="100%"></div>
    </header>
	<section>
    	<div class="content">
        	<div class="profile">
            	<h5>思你所想，给你所需</h5>
                <p>来嗅吧O2O商城，汇集数码、美妆、零售、服饰等各行各业客户，为其打造独立移动商城，引领移动商城新潮流。来嗅吧O2O商城等你来体验！</p>
            	<a href="http://fenxiao.lineshopbar.com" class="btn">成为伙伴</a>
            </div>
            <div class="list">
        		<h5>来嗅吧商城案例</h5>
                <ul>
                <?php
                $count = count($list);
                for($i=0;$i < $count; $i+=2) { 
                
                ?>
                    <li class="box-orient-h">
                        <div class="box-flex phone">
                            <div class="phone-img"><a href="<?php echo $list[$i]['link'];?>"><img src="<?php echo C('UCLOUD_CDN').$list[$i]['pic'];?>" width="100%"></a></div>
                            <div class="phone-txt">
                                <span><?php echo $list[$i]['title'];?></span>
                            </div>
                        </div>
                        <?php if(isset($list[$i+1])) {?>
                        <div class="box-flex phone">
                            <div class="phone-img"><a href="<?php echo $list[$i+1]['link'];?>"><img src="<?php echo C('UCLOUD_CDN').$list[$i+1]['pic'];?>" width="100%"></a></div>
                            <div class="phone-txt">
                                <span><?php echo $list[$i+1]['title'];?></span>
                            </div>
                        </div>
                        <?php }?>
                    </li>
                <?php } ?>
                </ul>
            </div>
        </div>
    </section>
</div>
</body>
<script type="text/javascript" src="__HOME__/js/zepto.min.js"></script>
<script type="text/javascript">
var len=$(".phone").length;
var liLen=$("li").length;
if(len%2==1){
	$(".phone").eq(len-1).removeClass("box-flex");
	$(".phone").eq(len-1).css({"width":0.5*($("li").width()-10)});
}
</script>
</html>
