<!-- PAGE HEADER-->
<div class="row">
    <div class="col-sm-12">
        <div class="page-header">
            <!-- STYLER -->

            <!-- /STYLER -->
            <!-- BREADCRUMBS -->
            <ul class="breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="index.html">Home</a>
                </li>
                <li>Dashboard - shared on mycodes.net</li>
            </ul>
            <!-- /BREADCRUMBS -->
            <div class="clearfix">
                <h3 class="content-title pull-left">Dashboard</h3>
                <!-- DATE RANGE PICKER -->
                <span class="date-range pull-right">
                    <div class="btn-group">
                        <a class="js_update btn btn-default" href="#">Today</a>
                        <a class="js_update btn btn-default" href="#">Last 7 Days</a>
                        <a class="js_update btn btn-default hidden-xs" href="#">Last month</a>

                        <a id="reportrange" class="btn reportrange">
                            <i class="fa fa-calendar"></i>
                            <span></span>
                            <i class="fa fa-angle-down"></i>
                        </a>
                    </div>
                </span>
                <!-- /DATE RANGE PICKER -->
            </div>
            <div class="description">Overview, Statistics and more</div>
        </div>
    </div>
</div>
<!-- /PAGE HEADER -->

<!-- DASHBOARD CONTENT -->
<div class="row">
        <!-- COLUMN 1 -->
        <div class="col-md-6">
                <div class="row">
                  <div class="col-lg-6">
                         <div class="dashbox panel panel-default">
                                <div class="panel-body">
                                   <div class="panel-left red">
                                                <i class="fa fa-instagram fa-3x"></i>
                                   </div>
                                   <div class="panel-right">
                                                <div class="number">6718</div>
                                                <div class="title">Likes</div>
                                                <span class="label label-success">
                                                        26% <i class="fa fa-arrow-up"></i>
                                                </span>
                                   </div>
                                </div>
                         </div>
                  </div>
                  <div class="col-lg-6">
                         <div class="dashbox panel panel-default">
                                <div class="panel-body">
                                   <div class="panel-left blue">
                                                <i class="fa fa-twitter fa-3x"></i>
                                   </div>
                                   <div class="panel-right">
                                                <div class="number">2724</div>
                                                <div class="title">Followers</div>
                                                <span class="label label-warning">
                                                        5% <i class="fa fa-arrow-down"></i>
                                                </span>
                                   </div>
                                </div>
                         </div>
                  </div>
                </div>
                <div class="row">
                        <div class="col-md-12">
                                <div class="quick-pie panel panel-default">
                                        <div class="panel-body">
                                                <div class="col-md-4 text-center">
                                                        <div id="dash_pie_1" class="piechart" data-percent="59">
                                                                <span class="percent"></span>
                                                        </div>
                                                        <a href="#" class="title">New Visitors <i class="fa fa-angle-right"></i></a>
                                                </div>
                                                <div class="col-md-4 text-center">
                                                        <div id="dash_pie_2" class="piechart" data-percent="73">
                                                                <span class="percent"></span>
                                                        </div>
                                                        <a href="#" class="title">Bounce Rate <i class="fa fa-angle-right"></i></a>
                                                </div>
                                                <div class="col-md-4 text-center">
                                                        <div id="dash_pie_3" class="piechart" data-percent="90">
                                                                <span class="percent"></span>
                                                        </div>
                                                        <a href="#" class="title">Brand Popularity <i class="fa fa-angle-right"></i></a>
                                                </div>
                                        </div>
                                </div>
                        </div>
           </div>
        </div>
        <!-- /COLUMN 1 -->

        <!-- COLUMN 2 -->
        <div class="col-md-6">
                <div class="box solid grey">
                        <div class="box-title">
                                <h4><i class="fa fa-dollar"></i>Revenue</h4>
                                <div class="tools">
                                        <span class="label label-danger">
                                                20% <i class="fa fa-arrow-up"></i>
                                        </span>
                                        <a href="#box-config" data-toggle="modal" class="config">
                                                <i class="fa fa-cog"></i>
                                        </a>
                                        <a href="javascript:;" class="reload">
                                                <i class="fa fa-refresh"></i>
                                        </a>
                                        <a href="javascript:;" class="collapse">
                                                <i class="fa fa-chevron-up"></i>
                                        </a>
                                        <a href="javascript:;" class="remove">
                                                <i class="fa fa-times"></i>
                                        </a>
                                </div>
                        </div>
                        <div class="box-body">
                                <div id="chart-revenue" style="height:240px"></div>
                        </div>
                </div>
        </div>
        <!-- /COLUMN 2 -->
</div>
<!-- /DASHBOARD CONTENT -->

