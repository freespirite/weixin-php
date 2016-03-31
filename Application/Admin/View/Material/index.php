<!-- ISOTOPE -->
<script type="text/javascript" src="__PUBLIC__/adm/js/isotope/jquery.isotope.min.js"></script>
<!-- COLORBOX -->
<script type="text/javascript" src="__PUBLIC__/adm/js/colorbox/jquery.colorbox.min.js"></script>
<!-- COLORBOX -->
<link rel="stylesheet" type="text/css" href="__PUBLIC__/adm/js/colorbox/colorbox.min.css" />
<script src="__PUBLIC__/adm/js/bootstrap-daterangepicker/daterangepicker.min.js"></script>
<!-- SLIMSCROLL -->
<script type="text/javascript" src="__PUBLIC__/adm/js/jQuery-slimScroll-1.3.0/jquery.slimscroll.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/adm/js/jQuery-slimScroll-1.3.0/slimScrollHorizontal.min.js"></script>
<!-- BLOCK UI -->
<script type="text/javascript" src="__PUBLIC__/adm/js/jQuery-BlockUI/jquery.blockUI.min.js"></script>

<!-- PAGE HEADER-->
<div class="row">
        <div class="col-sm-12">
                <div class="page-header">
                        <include file="Common/page_header"/>
                        <div class="description">
                            <!--Form Elements and Features-->
                        </div>
                </div>
        </div>
</div>
<!-- /PAGE HEADER -->

<div class="row">
    <div class="col-md-12">
        <!-- BOX -->
        <div class="box border">
            <div class="box-title"><h4>&nbsp;</h4></div>
            <div class="box-body">
                <div class="tabbable header-tabs">
                  <ul class="nav nav-tabs">
                    <?php
                    //echo __ROOT__;exit;
                    //print_r($aryType);echo $type.' >>>>>>>>>>>> ';exit;
                    $count = count($aryType);
                    $i = 0;
                    foreach($aryType as $key => $val) {
                        $i++;
                        $active = $type == $key? ' class="active"': '';
                    ?>
                      <li{$active}><a href="{:U('material/'.$key)}"><i class="fa {$val[1]}"></i> <span class="hidden-inline-mobile">{$val[0]}</span></a></li>
                    <?php }?>
                  </ul>
                  <div class="tab-content">
                     <div class="tab-pane fade in active" id="box_tab1">
                        <if condition="$type eq 'images'">
                        <include file="Material/index_images"/>
                        <else />
                        <include file="Material/index_index"/>
                        </if>
                     </div>
                     <!--<div class="tab-pane" id="box_tab2">
                       <include file="Material/index_tab2"/>-->
                     </div>
                  </div>
               </div>
            </div>
        </div>
        <!-- /BOX -->
    </div>
</div>
<!-- BOX TABS -->
<div class="separator"></div>