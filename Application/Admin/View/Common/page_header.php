<?php
$aryDaohang = C('MENU_ARRAY')[strtolower(CONTROLLER_NAME)];
?>
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
<!-- /BREADCRUMBS 
<div class="clearfix">
    <h3 class="content-title pull-left"><?php echo $aryDaohang['sub'][ACTION_NAME]['title'];?></h3>
</div>-->