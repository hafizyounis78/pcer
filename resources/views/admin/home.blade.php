@extends('admin.layout.index')
@section('content')
    <div class="page-content">
    <!--        <div class="page-head">
            <meta name="csrf-token" content="{{ csrf_token()}}">
            <div class="page-title">
                <h1>Dashboard
                    <small>e-Pain Clinic</small>
                </h1>
            </div>
            &lt;!&ndash; END PAGE TITLE &ndash;&gt;
            &lt;!&ndash; BEGIN PAGE TOOLBAR &ndash;&gt;
            <div class="page-toolbar">

            </div>
        </div>-->
    <!--        @include('admin.layout.breadcrumb')-->


        <div class="page-bar">
            <div class="page-toolbar">


                <div id="reportrange" class="tooltips btn btn-fit-height btn-sm green btn-dashboard-daterange"
                     style="font-size: 14px;">
                    <i class="fa fa-calendar"></i> &nbsp;
                    <span> </span>
                    <b class="fa fa-angle-down"></b>
                </div>


            </div>
        </div>
        <br/>
        <div class="row">
            <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                <div class="dashboard-stat2 ">
                    <div class="display">
                        <div class="number">
                            <h3 class="font-yellow-casablanca">
                                <span id="total_pain_files" data-counter="counterup" class="counter"
                                      data-value="{{$totalPatients}}"></span>
                                <small class="font-green-sharp"></small>
                            </h3>
                            <small>TOTAL PAIN FILES</small>
                        </div>
                        <div class="icon">
                            <i class="icon-pie-chart"></i>
                        </div>
                    </div>
                    <div class="progress-info">
                        <div class="progress">
                            <span style="width: 100%;"
                                  class="progress-bar progress-bar-success bg-yellow-casablanca bg-font-yellow-casablanca">
                                <span class="sr-only">100%</span>
                            </span>
                        </div>
                        <div class="status">
                            <div class="status-title"> Total</div>
                            <div class="status-number"> 100%</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                <div class="dashboard-stat2 ">
                    <div class="display">
                        <div class="number">
                            <h3 class="font-green-turquoise">
                                <span id="total_open" data-counter="counterup" class="counter"
                                      data-value="{{$totalOpen}}">0</span>
                            </h3>
                            <small>OPEN FILES</small>
                        </div>
                        <div class="icon">
                            <i class="icon-like"></i>
                        </div>
                    </div>
                    <div class="progress-info">
                        <div class="progress">
                            <span id="total_open_percent_prog" style="width: {{round($totalOpen/$totalPatients*100)}}%;"
                                  class="progress-bar progress-bar-success bg-green-turquoise bg-font-green-turquoise">
                                <span class="sr-only">85% OPEN</span>
                            </span>
                        </div>
                        <div class="status">
                            <div class="status-title"> OPEN</div>
                            <div id="total_open_percent_status"
                                 class="status-number"> {{round($totalOpen/$totalPatients*100)}}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                <div class="dashboard-stat2 ">
                    <div class="display">
                        <div class="number">
                            <h3 class="font-blue-steel">
                                <span id="total_close" data-counter="counterup" class="counter"
                                      data-value="{{$totalClose}}">0</span>
                            </h3>
                            <small>CLOSE FILES</small>
                        </div>
                        <div class="icon">
                            <i class="icon-close"></i>
                        </div>
                    </div>
                    <div class="progress-info">
                        <div class="progress">
                            <span id="total_close_percent_prog"
                                  style="width: {{round($totalClose/$totalPatients*100)}}%;"
                                  class="progress-bar progress-bar-success bg-blue-steel bg-font-blue-steel">
                                <span class="sr-only"> CLOSE </span>
                            </span>
                        </div>
                        <div class="status">
                            <div class="status-title"> CLOSE</div>
                            <div id="total_close_percent_status"
                                 class="status-number"> {{round($totalClose/$totalPatients*100)}}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                <div class="dashboard-stat2 ">
                    <div class="display">
                        <div class="number">
                            <h3 class="font-yellow-saffron">
                                <span id="total_visits" data-counter="counterup" class="counter"
                                      data-value="{{$totalfollowup+$totalbaseline}}"></span>
                            </h3>
                            <small>TOTAL VISITS</small>
                        </div>
                        <div class="icon">
                            <i class="icon-user"></i>
                        </div>
                    </div>
                    <div class="progress-info">
                        <div class="progress">
                            <span style="width: 100%;" class="progress-bar progress-bar-success bg-yellow-saffron">
                                <span class="sr-only">100% Total</span>
                            </span>
                        </div>
                        <div class="status">
                            <div class="status-title"> Total</div>
                            <div class="status-number"> 100%</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                <div class="dashboard-stat2 ">
                    <div class="display">
                        <div class="number">
                            <h3 class="font-red">
                                <span id="total_baseline" data-counter="counterup" class="counter"
                                      data-value="{{$totalbaseline}}"></span>
                            </h3>
                            <small>BASE LINE</small>
                        </div>
                        <div class="icon">
                            <i class="icon-user"></i>
                        </div>
                    </div>
                    <div class="progress-info">
                        <div class="progress">
                            <span id="total_baseline_percent_status"
                                  style="width: {{round($totalbaseline/($totalfollowup+$totalbaseline)*100)}}%;"
                                  class="progress-bar progress-bar-success bg-red">
                                <span class="sr-only">100% Total</span>
                            </span>
                        </div>
                        <div class="status">
                            <div class="status-title"> Total</div>
                            <div id="total_baseline_percent_status"
                                 class="status-number"> {{round($totalbaseline/($totalfollowup+$totalbaseline)*100)}}%
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                <div class="dashboard-stat2 ">
                    <div class="display">
                        <div class="number">
                            <h3 class="font-purple-medium">
                                <span id="total_followup" data-counter="counterup" class="counter"
                                      data-value="{{$totalfollowup}}"></span>
                            </h3>
                            <small>FOLLOW UP</small>
                        </div>
                        <div class="icon">
                            <i class="icon-user"></i>
                        </div>
                    </div>
                    <div class="progress-info">
                        <div class="progress">
                            <span id="total_followup_percent_prog"
                                  style="width: {{round($totalfollowup/($totalfollowup+$totalbaseline)*100)}}%;"
                                  class="progress-bar progress-bar-success bg-purple-medium bg-font-purple-medium">
                                <span class="sr-only">100% Total</span>
                            </span>
                        </div>
                        <div class="status">
                            <div class="status-title"> Total</div>
                            <div id="total_followup_percent_status"
                                 class="status-number"> {{round($totalfollowup/($totalfollowup+$totalbaseline)*100)}}%
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-xs-12 col-sm-12">
                <div class="portlet light ">
                    <div class="portlet-title">
                        <div class="caption">
                            <span class="caption-subject bold uppercase font-dark">Follow up</span>
                            <span class="caption-helper">Last 12 Month</span>
                        </div>
                        <div class="actions">
                            <!--                            <a class="btn btn-circle btn-icon-only btn-default" href="#">
                                                            <i class="icon-cloud-upload"></i>
                                                        </a>
                                                        <a class="btn btn-circle btn-icon-only btn-default" href="#">
                                                            <i class="icon-wrench"></i>
                                                        </a>
                                                        <a class="btn btn-circle btn-icon-only btn-default" href="#">
                                                            <i class="icon-trash"></i>
                                                        </a>-->
                            <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#"> </a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div id="dashboard_followup" class="CSSAnimationChart"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-xs-12 col-sm-12">
                <div class="portlet light ">
                    <div class="portlet-title">
                        <div class="caption">
                            <span class="caption-subject bold uppercase font-dark">Baseline </span>
                            <span class="caption-helper">Last 12 Month</span>
                        </div>
                        <div class="actions">
                            <!--                            <a class="btn btn-circle btn-icon-only btn-default" href="#">
                                                            <i class="icon-cloud-upload"></i>
                                                        </a>
                                                        <a class="btn btn-circle btn-icon-only btn-default" href="#">
                                                            <i class="icon-wrench"></i>
                                                        </a>
                                                        <a class="btn btn-circle btn-icon-only btn-default" href="#">
                                                            <i class="icon-trash"></i>
                                                        </a>-->
                            <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#"> </a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div id="dashboard_baseline" class="CSSAnimationChart"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-xs-12 col-sm-12">
                <div class="portlet light ">
                    <div class="portlet-title">
                        <div class="caption">
                            <span class="caption-subject bold uppercase font-dark">Closed File</span>
                            <span class="caption-helper">Last 12 Month</span>
                        </div>
                        <div class="actions">
                            <!--                            <a class="btn btn-circle btn-icon-only btn-default" href="#">
                                                            <i class="icon-cloud-upload"></i>
                                                        </a>
                                                        <a class="btn btn-circle btn-icon-only btn-default" href="#">
                                                            <i class="icon-wrench"></i>
                                                        </a>
                                                        <a class="btn btn-circle btn-icon-only btn-default" href="#">
                                                            <i class="icon-trash"></i>
                                                        </a>-->
                            <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#"> </a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div id="dashboard_closed_file" class="CSSAnimationChart"></div>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-lg-4 col-xs-12 col-sm-12">
                <div class="portlet light ">
                    <div class="portlet-title">
                        <div class="caption">
                            <span class="caption-subject bold uppercase font-dark">Total Patients</span>
                            <span class="caption-helper">Group by District</span>
                        </div>
                        <div class="actions">
                            <!--                            <a class="btn btn-circle btn-icon-only btn-default" href="#">
                                                            <i class="icon-cloud-upload"></i>
                                                        </a>
                                                        <a class="btn btn-circle btn-icon-only btn-default" href="#">
                                                            <i class="icon-wrench"></i>
                                                        </a>
                                                        <a class="btn btn-circle btn-icon-only btn-default" href="#">
                                                            <i class="icon-trash"></i>
                                                        </a>-->
                            <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#"> </a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div id="dashboard_district" class="CSSAnimationChart"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-xs-12 col-sm-12">
                <div class="portlet light ">
                    <div class="portlet-title">
                        <div class="caption">
                            <span class="caption-subject bold uppercase font-dark">Total Patient </span>
                            <span class="caption-helper">By Gender</span>
                        </div>
                        <div class="actions">
                            <!--                            <a class="btn btn-circle btn-icon-only btn-default" href="#">
                                                            <i class="icon-cloud-upload"></i>
                                                        </a>
                                                        <a class="btn btn-circle btn-icon-only btn-default" href="#">
                                                            <i class="icon-wrench"></i>
                                                        </a>
                                                        <a class="btn btn-circle btn-icon-only btn-default" href="#">
                                                            <i class="icon-trash"></i>
                                                        </a>-->
                            <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#"> </a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div id="dashboard_gender" class="CSSAnimationChart"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-xs-12 col-sm-12">
                <div class="portlet light ">
                    <div class="portlet-title">
                        <div class="caption">
                            <span class="caption-subject bold uppercase font-dark">Total Patients</span>
                            <span class="caption-helper">Group by Age</span>
                        </div>
                        <div class="actions">
                            <!--                            <a class="btn btn-circle btn-icon-only btn-default" href="#">
                                                            <i class="icon-cloud-upload"></i>
                                                        </a>
                                                        <a class="btn btn-circle btn-icon-only btn-default" href="#">
                                                            <i class="icon-wrench"></i>
                                                        </a>
                                                        <a class="btn btn-circle btn-icon-only btn-default" href="#">
                                                            <i class="icon-trash"></i>
                                                        </a>-->
                            <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#"> </a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div id="dashboard_age" class="CSSAnimationChart"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-xs-12 col-sm-12">
                <div class="portlet light ">
                    <div class="portlet-title">
                        <div class="caption">
                            <span class="caption-subject bold uppercase font-dark">Overall Status</span>
                            <span class="caption-helper">Patients...</span>
                        </div>
                        <div class="actions">
                            <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#"> </a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div id="dashboard_overall" class="chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-xs-12 col-sm-12">
                <div class="portlet light ">
                    <div class="portlet-title">
                        <div class="caption">
                            <span class="caption-subject bold uppercase font-dark">Pain localized area</span>
                            <span class="caption-helper">Area...</span>
                        </div>
                        <div class="actions">
                            <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#"> </a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div id="dashboard_area" class="chart"></div>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-lg-6 col-xs-12 col-sm-12">
                <div class="portlet light ">
                    <div class="portlet-title">
                        <div class="caption">
                            <span class="caption-subject bold uppercase font-dark">Plexus</span>
                            <span class="caption-helper"></span>
                        </div>
                        <div class="actions">
                            <!--                            <a class="btn btn-circle btn-icon-only btn-default" href="#">
                                                            <i class="icon-cloud-upload"></i>
                                                        </a>
                                                        <a class="btn btn-circle btn-icon-only btn-default" href="#">
                                                            <i class="icon-wrench"></i>
                                                        </a>
                                                        <a class="btn btn-circle btn-icon-only btn-default" href="#">
                                                            <i class="icon-trash"></i>
                                                        </a>-->
                            <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#"> </a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div id="dashboard_Plexus" class="CSSAnimationChart"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-xs-12 col-sm-12">
                <div class="portlet light ">
                    <div class="portlet-title">
                        <div class="caption">
                            <span class="caption-subject bold uppercase font-dark">Peripheral</span>
                            <span class="caption-helper"></span>
                        </div>
                        <div class="actions">
                            <!--                            <a class="btn btn-circle btn-icon-only btn-default" href="#">
                                                            <i class="icon-cloud-upload"></i>
                                                        </a>
                                                        <a class="btn btn-circle btn-icon-only btn-default" href="#">
                                                            <i class="icon-wrench"></i>
                                                        </a>
                                                        <a class="btn btn-circle btn-icon-only btn-default" href="#">
                                                            <i class="icon-trash"></i>
                                                        </a>-->
                            <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#"> </a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div id="dashboard_Peripheral" class="CSSAnimationChart"></div>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-lg-6 col-xs-12 col-sm-12">
                <div class="portlet light ">
                    <div class="portlet-title">
                        <div class="caption">
                            <span class="caption-subject bold uppercase font-dark">Physical Treatment</span>
                            <span class="caption-helper"></span>
                        </div>
                        <div class="actions">
                            <!--                            <a class="btn btn-circle btn-icon-only btn-default" href="#">
                                                            <i class="icon-cloud-upload"></i>
                                                        </a>
                                                        <a class="btn btn-circle btn-icon-only btn-default" href="#">
                                                            <i class="icon-wrench"></i>
                                                        </a>
                                                        <a class="btn btn-circle btn-icon-only btn-default" href="#">
                                                            <i class="icon-trash"></i>
                                                        </a>-->
                            <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#"> </a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div id="dashboard_physicalTreatment" class="CSSAnimationChart"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-xs-12 col-sm-12">
                <div class="portlet light ">
                    <div class="portlet-title">
                        <div class="caption">
                            <span class="caption-subject bold uppercase font-dark">Pharmacological Treatment</span>
                            <span class="caption-helper">Click physical treatment chart to see chart</span>
                        </div>
                        <div class="actions">
                            <!--                            <a class="btn btn-circle btn-icon-only btn-default" href="#">
                                                            <i class="icon-cloud-upload"></i>
                                                        </a>
                                                        <a class="btn btn-circle btn-icon-only btn-default" href="#">
                                                            <i class="icon-wrench"></i>
                                                        </a>
                                                        <a class="btn btn-circle btn-icon-only btn-default" href="#">
                                                            <i class="icon-trash"></i>
                                                        </a>-->
                            <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#"> </a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div id="dashboard_PharmaTreatment" class="CSSAnimationChart"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-xs-12 col-sm-12">
                <div class="portlet light ">
                    <div class="portlet-title">
                        <div class="caption ">
                            <span class="caption-subject font-dark bold uppercase">Dermatomes</span>
                            <span class="caption-helper"></span>
                        </div>
                        <div class="actions">
                            <!--                            <a class="btn btn-circle btn-icon-only btn-default" href="#">
                                                            <i class="icon-cloud-upload"></i>
                                                        </a>
                                                        <a class="btn btn-circle btn-icon-only btn-default" href="#">
                                                            <i class="icon-wrench"></i>
                                                        </a>
                                                        <a class="btn btn-circle btn-icon-only btn-default" href="#">
                                                            <i class="icon-trash"></i>
                                                        </a>-->
                            <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#"> </a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div id="dashboard_Dermatomes" class="CSSAnimationChart"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-xs-12 col-sm-12">
                <div class="portlet light ">
                    <div class="portlet-title">
                        <div class="caption ">
                            <span class="caption-subject font-dark bold uppercase">Injury Mechanism</span>
                            <span class="caption-helper"></span>
                        </div>
                        <div class="actions">
                            <!--                            <a class="btn btn-circle btn-icon-only btn-default" href="#">
                                                            <i class="icon-cloud-upload"></i>
                                                        </a>
                                                        <a class="btn btn-circle btn-icon-only btn-default" href="#">
                                                            <i class="icon-wrench"></i>
                                                        </a>
                                                        <a class="btn btn-circle btn-icon-only btn-default" href="#">
                                                            <i class="icon-trash"></i>
                                                        </a>-->
                            <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#"> </a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div id="dashboard_injury_mechanism" class="CSSAnimationChart"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-xs-12 col-sm-12">
                <div class="portlet light ">
                    <div class="portlet-title">
                        <div class="caption">
                            <span class="caption-subject bold uppercase font-dark">Diagnosis</span>
                            <span class="caption-helper">Top 10</span>
                            <span class="badge badge-warning">Click on Chart to see patients list</span>
                        </div>
                        <div class="actions">
                            <!--                            <a class="btn btn-circle btn-icon-only btn-default" href="#">
                                                            <i class="icon-cloud-upload"></i>
                                                        </a>
                                                        <a class="btn btn-circle btn-icon-only btn-default" href="#">
                                                            <i class="icon-wrench"></i>
                                                        </a>
                                                        <a class="btn btn-circle btn-icon-only btn-default" href="#">
                                                            <i class="icon-trash"></i>
                                                        </a>-->
                            <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#"> </a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div id="dashboard_diagnosis" class="CSSAnimationChart"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-xs-12 col-sm-12">
                <div class="portlet light ">
                    <div class="portlet-title">
                        <div class="caption ">
                            <span class="caption-subject font-dark bold uppercase">Drugs</span>
                            <span class="caption-helper">Top 20</span>
                            <span class="badge badge-warning">Click on Chart to see patients list</span>
                        </div>
                        <div class="actions">
                            <!--                            <a class="btn btn-circle btn-icon-only btn-default" href="#">
                                                            <i class="icon-cloud-upload"></i>
                                                        </a>
                                                        <a class="btn btn-circle btn-icon-only btn-default" href="#">
                                                            <i class="icon-wrench"></i>
                                                        </a>
                                                        <a class="btn btn-circle btn-icon-only btn-default" href="#">
                                                            <i class="icon-trash"></i>
                                                        </a>-->
                            <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#"> </a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div id="dashboard_drugs" class="CSSAnimationChart"></div>
                    </div>
                </div>
            </div>
        </div>
        <?php if((auth()->user()->user_type_id == 8 || auth()->user()->user_type_id == 9) && auth()->user()->id != 100){
        ?>
        <div class="row">
            <div class="col-lg-12 col-xs-12 col-sm-12">
                <div class="portlet light ">
                    <div class="portlet-title">
                        <div class="caption ">
                            <span class="caption-subject font-dark bold uppercase">Drugs Cost </span>
                            <span class="caption-helper">Doctor orders</span>
                            <!--                            <span class="badge badge-warning">Click on Chart to see patients list</span>-->
                        </div>
                        <div class="actions">
                            <!--                            <a class="btn btn-circle btn-icon-only btn-default" href="#">
                                                            <i class="icon-cloud-upload"></i>
                                                        </a>
                                                        <a class="btn btn-circle btn-icon-only btn-default" href="#">
                                                            <i class="icon-wrench"></i>
                                                        </a>
                                                        <a class="btn btn-circle btn-icon-only btn-default" href="#">
                                                            <i class="icon-trash"></i>
                                                        </a>-->
                            <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#"> </a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div id="dashboard_drugs_cost" class="CSSAnimationChart"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-xs-12 col-sm-12">
                <div class="portlet light ">
                    <div class="portlet-title">
                        <div class="caption ">
                            <span class="caption-subject font-dark bold uppercase">Doctor Orders </span>
                            <span class="caption-helper">Drug Cost</span>
                            <!--                            <span class="badge badge-warning">Click on Chart to see patients list</span>-->
                        </div>
                        <div class="actions">
                            <!--                            <a class="btn btn-circle btn-icon-only btn-default" href="#">
                                                            <i class="icon-cloud-upload"></i>
                                                        </a>
                                                        <a class="btn btn-circle btn-icon-only btn-default" href="#">
                                                            <i class="icon-wrench"></i>
                                                        </a>
                                                        <a class="btn btn-circle btn-icon-only btn-default" href="#">
                                                            <i class="icon-trash"></i>
                                                        </a>-->
                            <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#"> </a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div id="dashboard_doctor_drugs_cost" class="CSSAnimationChart"></div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        }
        ?>
        <div class="row">
            <div class="col-lg-12 col-xs-12 col-sm-12">
                <div class="portlet light ">
                    <div class="portlet-title">
                        <div class="caption ">
                            <span class="caption-subject font-dark bold uppercase">Pain Scale </span>
                            <span class="caption-helper">Between baseline visit and last visit</span>
                            <!--                            <span class="badge badge-warning">Click on Chart to see patients list</span>-->
                        </div>
                        <div class="actions">
                            <!--                            <a class="btn btn-circle btn-icon-only btn-default" href="#">
                                                            <i class="icon-cloud-upload"></i>
                                                        </a>
                                                        <a class="btn btn-circle btn-icon-only btn-default" href="#">
                                                            <i class="icon-wrench"></i>
                                                        </a>
                                                        <a class="btn btn-circle btn-icon-only btn-default" href="#">
                                                            <i class="icon-trash"></i>
                                                        </a>-->
                            <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#"> </a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div id="dashboard_pain_scale" class="CSSAnimationChart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade bs-modal-lg" id="chart_Modal" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title font-green">Chart Details</h4>
                </div>
                <div class="modal-body">
                    <!-- BEGIN FORM-->
                {{Form::open(['url'=>url(''),'class'=>'form-horizontal','method'=>"post","id"=>'data_form'])}}

                @if(auth()->user()->id!=100)<!--100=guest user -->
                    <div class="form-body">

                        <table class="table table-striped table-bordered table-hover"
                               id="chart_tbl">
                            <thead>
                            <tr>
                                <th>File No.</th>
                                <th>Name</th>
                                <th>Name Ar</th>
                                <th>ID</th>
                                <th>Sex</th>
                                <th>Mobile No.</th>
                                <th> Start date</th>
                                <th> Doctor</th>
                                <th> Source</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                    @endif
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <button type="button" data-dismiss="modal" class="btn  grey-salsa btn-outline">Cancel
                                </button>

                            </div>
                        </div>
                    </div>
                {{Form::close()}}
                <!-- END FORM-->
                </div>
                <!-- <div class="modal-footer">
                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                    <button type="button" class="btn green">Save changes</button>
                </div> -->
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- END CONTENT BODY -->
    @push('css')
        <link href="{{url('/')}}/assets/global/plugins/morris/morris.css" rel="stylesheet" type="text/css"/>
        <link href="{{url('/')}}/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css"
              rel="stylesheet" type="text/css"/>
        <link href="{{url('/')}}/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css"
              rel="stylesheet" type="text/css"/>
        <link href="{{url('/')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css"
              rel="stylesheet" type="text/css"/>
    @endpush
    @push('js')
        {{--  <script src="{{url('/')}}/assets/global/plugins/jquery.min.js" type="text/javascript"></script>--}}
        <script src="{{url('/')}}/assets/global/plugins/morris/morris.min.js" type="text/javascript"></script>
        <script src="{{url('/')}}/assets/global/plugins/morris/raphael-min.js" type="text/javascript"></script>
        <script src="{{url('/')}}/assets/global/plugins/counterup/jquery.waypoints.min.js"
                type="text/javascript"></script>
        <script src="{{url('/')}}/assets/global/plugins/counterup/jquery.counterup.min.js"
                type="text/javascript"></script>
        <script src="{{url('/')}}/assets/global/plugins/amcharts/amcharts/amcharts.js" type="text/javascript"></script>
        <script src="{{url('/')}}/assets/global/plugins/amcharts/amcharts/serial.js" type="text/javascript"></script>
        <script src="{{url('/')}}/assets/global/plugins/amcharts/amcharts/pie.js" type="text/javascript"></script>
        <script src="{{url('/')}}/assets/global/plugins/amcharts/amcharts/radar.js" type="text/javascript"></script>
        <script src="{{url('/')}}/assets/global/plugins/amcharts/amcharts/themes/light.js"
                type="text/javascript"></script>
        <script src="{{url('/')}}/assets/global/plugins/amcharts/amcharts/themes/patterns.js"
                type="text/javascript"></script>
        <script src="{{url('/')}}/assets/global/plugins/amcharts/amcharts/themes/chalk.js"
                type="text/javascript"></script>
        <script src="{{url('/')}}/assets/global/plugins/horizontal-timeline/horizontal-timeline.js"
                type="text/javascript"></script>
        <script src="{{url('/')}}/assets/global/plugins/flot/jquery.flot.min.js" type="text/javascript"></script>
        <script src="{{url('/')}}/assets/global/plugins/flot/jquery.flot.resize.min.js" type="text/javascript"></script>
        <script src="{{url('/')}}/assets/global/plugins/flot/jquery.flot.categories.min.js"
                type="text/javascript"></script>
        <script src="{{url('/')}}/assets/global/plugins/moment.min.js" type="text/javascript"></script>
        <script src="{{url('/')}}/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js"
                type="text/javascript"></script>
        <script src="{{url('/')}}/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"
                type="text/javascript"></script>
        <script src="{{url('/')}}/assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
        <script src="{{url('/')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js"
                type="text/javascript"></script>
        <!--        <script src="{{url('/')}}/assets/pages/scripts/dashboard.js" type="text/javascript"></script>-->
        <script>
            function getMonthDateRange(year, month) {


                // month in moment is 0 based, so 9 is actually october, subtract 1 to compensate
                // array is 'year', 'month', 'day', etc
                var startDate = moment([year, month - 1]);

                // Clone the value before .endOf()
                var endDate = moment(startDate).endOf('month');

                // just for demonstration:
                console.log(startDate.toDate());
                console.log(endDate.toDate());

                // make sure to call toDate() for plain JavaScript date type
                //return { start: startDate, end: endDate };
                return  startDate;
            }
            $(document).ready(function () {
                var currentTime = new Date();
                var curr_year = currentTime.getFullYear();

                $('#reportrange').daterangepicker({
                        opens: (App.isRTL() ? 'left' : 'right'),
                        startDate: moment().subtract('days', 365),
                        endDate: moment(),
                        //minDate: '01/01/2012',
                        //maxDate: '12/31/2014',
                        /* dateLimit: {
                             days: 60
                         },*/
                        showDropdowns: true,
                        showWeekNumbers: true,
                        timePicker: false,
                        timePickerIncrement: 1,
                        timePicker12Hour: true,
                        ranges: {
                            /* 'Today': [moment(), moment()],
                             'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],*/
                            // 'Last 7 Days': [moment().subtract('days', 6), moment()],
                            'Last 15 Days': [moment().subtract('days', 14), moment()],
                            //'Last 30 Days': [moment().subtract('days', 29), moment()],
                            'This Month': [moment().startOf('month'), moment().endOf('month')],
                            'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')],
                            'Last 6 Month': [moment().subtract('month', 6).startOf('month'), moment().subtract('month', 1).endOf('month')],
                            'Last 12 Month': [moment().subtract('month', 12).startOf('month'), moment().subtract('month', 1).endOf('month')],
                            'From May':  [getMonthDateRange(curr_year, 5).startOf('month'), moment().subtract('month').endOf('month')],
                        },
                        buttonClasses: ['btn'],
                        applyClass: 'green',
                        cancelClass: 'default',
                        // format: 'dd/mm/yyyy',

                        separator: ' to ',
                        locale: {
                            format: 'DD/MM/YYYY',
                            applyLabel: 'Apply',
                            fromLabel: 'From',
                            toLabel: 'To',
                            customRangeLabel: 'Custom Range',
                            daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                            monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                            firstDay: 1
                        }
                    },
                    function (start, end) {
                        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                    }
                );
                //Set the initial state of the picker label
                $('#reportrange span').html(moment().subtract('days', 29).format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));
                getCharts();
                $(".applyBtn").click(function () {
                    getCharts();
                });
            });

            function getCharts() {
                var startDate = $('#reportrange').data('daterangepicker').startDate._d;
                var endDate = $('#reportrange').data('daterangepicker').endDate._d;
                var employee_id = $('#employee_id').val();
                var monthNames = [
                    "January", "February", "March", "April", "May", "June", "July",
                    "August", "September", "October", "November", "December"
                ];
                var dayOfWeekNames = [
                    "Sunday", "Monday", "Tuesday",
                    "Wednesday", "Thursday", "Friday", "Saturday"
                ];

                function formatDate(date, patternStr) {
                    if (!patternStr) {
                        patternStr = 'M/d/yyyy';
                    }
                    var day = date.getDate(),
                        month = date.getMonth(),
                        year = date.getFullYear(),
                        hour = date.getHours(),
                        minute = date.getMinutes(),
                        second = date.getSeconds(),
                        miliseconds = date.getMilliseconds(),
                        h = hour % 12,
                        hh = twoDigitPad(h),
                        HH = twoDigitPad(hour),
                        mm = twoDigitPad(minute),
                        ss = twoDigitPad(second),
                        aaa = hour < 12 ? 'AM' : 'PM',
                        EEEE = dayOfWeekNames[date.getDay()],
                        EEE = EEEE.substr(0, 3),
                        dd = twoDigitPad(day),
                        M = month + 1,
                        MM = twoDigitPad(M),
                        MMMM = monthNames[month],
                        MMM = MMMM.substr(0, 3),
                        yyyy = year + "",
                        yy = yyyy.substr(2, 2)
                    ;
                    // checks to see if month name will be used
                    patternStr = patternStr
                        .replace('hh', hh).replace('h', h)
                        .replace('HH', HH).replace('H', hour)
                        .replace('mm', mm).replace('m', minute)
                        .replace('ss', ss).replace('s', second)
                        .replace('S', miliseconds)
                        .replace('dd', dd).replace('d', day)

                        .replace('EEEE', EEEE).replace('EEE', EEE)
                        .replace('yyyy', yyyy)
                        .replace('yy', yy)
                        .replace('aaa', aaa);
                    if (patternStr.indexOf('MMM') > -1) {
                        patternStr = patternStr
                            .replace('MMMM', MMMM)
                            .replace('MMM', MMM);
                    } else {
                        patternStr = patternStr
                            .replace('MM', MM)
                            .replace('M', M);
                    }
                    return patternStr;
                }

                function twoDigitPad(num) {
                    return num < 10 ? "0" + num : num;
                }

                /*  console.log(formatDate(new Date()));
                  console.log(formatDate(new Date(), 'dd-MMM-yyyy')); //OP's request
                  console.log(formatDate(new Date(), 'EEEE, MMMM d, yyyy HH:mm:ss.S aaa'));
                  console.log(formatDate(new Date(), 'EEE, MMM d, yyyy HH:mm'));
                  console.log(formatDate(new Date(), 'yyyy-MM-dd HH:mm:ss.S'));
                  console.log(formatDate(new Date(), 'M/dd/yyyy h:mmaaa'));*/
                // alert('startDate: '+startDate)
                // alert('endDate: '+endDate)
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '{{url('home/general_statistics')}}',
                    type: "get",
                    datatype: 'json',
                    data: {
                        startDate: formatDate(startDate, 'yyyy-M-dd'),
                        endDate: formatDate(endDate, 'yyyy-M-dd'),
                        employee_id: employee_id
                    },
                    success: function (data) {
                        // alert(data.totalPatients)
                        $('#total_pain_files').attr('data-value', data.totalPatients);
                        $('#total_open').attr('data-value', data.totalOpen);
                        $('#total_open_percent_prog').width(((data.totalOpen / data.totalPatients) * 100).toFixed(2));
                        $('#total_open_percent_status').html(((data.totalOpen / data.totalPatients) * 100).toFixed(2));

                        $('#total_close').attr('data-value', data.totalClose);
                        $('#total_close_percent_prog').width(((data.totalClose / data.totalPatients) * 100).toFixed(2));
                        $('#total_close_percent_status').html(((data.totalClose / data.totalPatients) * 100).toFixed(2));

                        $('#total_visits').attr('data-value', parseInt(data.totalbaseline) + parseInt(data.totalfollowup));

                        $('#total_followup').attr('data-value', data.totalfollowup);
                        //alert($('#total_followup').attr('data-value'));
                        $('#total_followup_percent_prog').width(((data.totalfollowup / (parseInt(data.totalbaseline) + parseInt(data.totalfollowup))) * 100).toFixed(2));
                        $('#total_followup_percent_status').html(((data.totalfollowup / (parseInt(data.totalbaseline) + parseInt(data.totalfollowup))) * 100).toFixed(2));

                        $('#total_baseline').attr('data-value', data.totalbaseline);
                        $('#total_baseline_percent_prog').width(((data.totalbaseline / (parseInt(data.totalbaseline) + parseInt(data.totalfollowup))) * 100).toFixed(2));
                        $('#total_baseline_percent_status').html(((data.totalbaseline / (parseInt(data.totalbaseline) + parseInt(data.totalfollowup))) * 100).toFixed(2));
                        // $('.refresh').html(cntr);
                        $('.counter').counterUp({
                            delay: 100,
                            time: 1200
                        });


                    },
                    error: function () {

                    }
                });//Done with employee id
                $.ajax({
                    url: '{{url('home/overallChart')}}',
                    type: "get",
                    datatype: 'json',
                    data: {
                        startDate: formatDate(startDate, 'yyyy-M-dd'),
                        endDate: formatDate(endDate, 'yyyy-M-dd'),
                        employee_id: employee_id
                    },
                    success: function (data) {
                        //  alert(data);
                        //   var jsObj = JSON.parse(data);
                        CreateTotalOverAll(data.chartdata);


                    },
                    error: function () {

                    }
                });//Done with employee id
                $.ajax({
                    url: '{{url('home/FollowupMonthlyChart')}}',
                    type: "get",
                    datatype: 'json',
                    data: {
                        startDate: formatDate(startDate, 'yyyy-M-dd'),
                        endDate: formatDate(endDate, 'yyyy-M-dd'),
                        employee_id: employee_id
                    },
                    success: function (data) {
                        //  alert(data);
                        //   var jsObj = JSON.parse(data);
                        CreateFollowupMonthlyChart(data.chartdata);


                    },
                    error: function () {

                    }
                });//Done with employee id
                $.ajax({
                    url: '{{url('home/BaselineMonthlyChart')}}',
                    type: "get",
                    datatype: 'json',
                    data: {
                        startDate: formatDate(startDate, 'yyyy-M-dd'),
                        endDate: formatDate(endDate, 'yyyy-M-dd'),
                        employee_id: employee_id
                    },
                    success: function (data) {
                        //  alert(data);
                        //   var jsObj = JSON.parse(data);
                        CreateBaselineMonthlyChart(data.chartdata);


                    },
                    error: function () {

                    }
                });//Done with employee id
                $.ajax({
                    url: '{{url('home/ClosedFileMonthlyChart')}}',
                    type: "get",
                    datatype: 'json',
                    data: {
                        startDate: formatDate(startDate, 'yyyy-M-dd'),
                        endDate: formatDate(endDate, 'yyyy-M-dd'),
                        employee_id: employee_id
                    },
                    success: function (data) {
                        //  alert(data);
                        //   var jsObj = JSON.parse(data);
                        CreateClosedFileMonthlyChart(data.chartdata);


                    },
                    error: function () {

                    }
                });//Done with employee id
                $.ajax({
                    url: '{{url('home/totalPatientsByDistricts')}}',
                    type: "get",
                    datatype: 'json',
                    data: {
                        startDate: formatDate(startDate, 'yyyy-M-dd'),
                        endDate: formatDate(endDate, 'yyyy-M-dd'),
                        employee_id: employee_id
                    },
                    success: function (data) {
                        //  alert(data);
                        //   var jsObj = JSON.parse(data);
                        CreatePatientByDistrictChart(data.chartdata);


                    },
                    error: function () {

                    }
                });//Done with employee id
                $.ajax({
                    url: '{{url('home/totalPatientsByAge')}}',
                    type: "get",
                    datatype: 'json',
                    data: {
                        startDate: formatDate(startDate, 'yyyy-M-dd'),
                        endDate: formatDate(endDate, 'yyyy-M-dd'),
                        employee_id: employee_id
                    },
                    success: function (data) {
                        //  alert(data);
                        //   var jsObj = JSON.parse(data);
                        CreatePatientByAgeChart(data.chartdata);


                    },
                    error: function () {

                    }
                });//Done with employee id
                $.ajax({
                    url: '{{url('home/totalPatientsByGender')}}',
                    type: "get",
                    datatype: 'json',
                    data: {
                        startDate: formatDate(startDate, 'yyyy-M-dd'),
                        endDate: formatDate(endDate, 'yyyy-M-dd'),
                        employee_id: employee_id
                    },
                    success: function (data) {
                        //  alert(data);
                        //   var jsObj = JSON.parse(data);
                        getTotalPatientsByGender(data.chartdata);


                    },
                    error: function () {

                    }
                });//Done with employee id
                $.ajax({
                    url: '{{url('home/totalPatientsPhysicalTreatment')}}',
                    type: "get",
                    datatype: 'json',
                    data: {
                        startDate: formatDate(startDate, 'yyyy-M-dd'),
                        endDate: formatDate(endDate, 'yyyy-M-dd'),
                        employee_id: employee_id
                    },
                    success: function (data) {
                        //  alert(data);
                        //   var jsObj = JSON.parse(data);
                        CreatePatientByPhysicalTreatment(data.chartdata);


                    },
                    error: function () {

                    }
                });//Done with employee id


                $.ajax({
                    url: '{{url('home/Top10DiagnosisChart')}}',
                    type: "get",
                    datatype: 'json',
                    data: {
                        startDate: formatDate(startDate, 'yyyy-M-dd'),
                        endDate: formatDate(endDate, 'yyyy-M-dd'),
                        employee_id: employee_id
                    },
                    success: function (data) {
                        //  alert(data);
                        //   var jsObj = JSON.parse(data);
                        CreateTop10DiagnosisChart(data.chartdata);


                    },
                    error: function () {

                    }
                });//Done with employee id
                $.ajax({
                    url: '{{url('home/Top10DrugsChart')}}',
                    type: "get",
                    datatype: 'json',
                    data: {
                        startDate: formatDate(startDate, 'yyyy-M-dd'),
                        endDate: formatDate(endDate, 'yyyy-M-dd'),
                        employee_id: employee_id
                    },
                    success: function (data) {
                        //  alert(data);
                        //   var jsObj = JSON.parse(data);
                        CreateTop10DrugsChart(data.chartdata);


                    },
                    error: function () {

                    }
                });//Done with employee id
                $.ajax({
                    url: '{{url('home/DrugsCostChart')}}',
                    type: "get",
                    datatype: 'json',
                    data: {
                        startDate: formatDate(startDate, 'yyyy-M-dd'),
                        endDate: formatDate(endDate, 'yyyy-M-dd'),
                        employee_id: employee_id
                    },
                    success: function (data) {
                        //  alert(data);
                        //   var jsObj = JSON.parse(data);
                        CreateDrugsCostChart(data.chartdata);


                    },
                    error: function () {

                    }
                });//Done with employee id
                $.ajax({
                    url: '{{url('home/DoctorDrugsCostChart')}}',
                    type: "get",
                    datatype: 'json',
                    data: {
                        startDate: formatDate(startDate, 'yyyy-M-dd'),
                        endDate: formatDate(endDate, 'yyyy-M-dd'),
                        employee_id: employee_id
                    },
                    success: function (data) {
                        //  alert(data);
                        //   var jsObj = JSON.parse(data);
                        CreateDoctorDrugsCostChart(data.chartdata);


                    },
                    error: function () {

                    }
                });//Done with employee id
                $.ajax({
                    url: '{{url('home/PainScaleChart')}}',
                    type: "get",
                    datatype: 'json',
                    data: {
                        startDate: formatDate(startDate, 'yyyy-M-dd'),
                        endDate: formatDate(endDate, 'yyyy-M-dd'),
                        employee_id: employee_id
                    },
                    success: function (data) {
                        //  alert(data);
                        //   var jsObj = JSON.parse(data);
                        CreatePainScaleChart(data.chartdata);


                    },
                    error: function () {

                    }
                });//Done with employee id
                $.ajax({
                    url: '{{url('home/AreaChart')}}',
                    type: "get",
                    datatype: 'json',
                    data: {
                        startDate: formatDate(startDate, 'yyyy-M-dd'),
                        endDate: formatDate(endDate, 'yyyy-M-dd'),
                        employee_id: employee_id
                    },
                    success: function (data) {
                        //  alert(data);
                        //   var jsObj = JSON.parse(data);
                        CreateAreaChart(data.chartdata);


                    },
                    error: function () {

                    }
                });//Done with employee id
                $.ajax({
                    url: '{{url('home/PlexusAreaChart')}}',
                    type: "get",
                    datatype: 'json',
                    data: {
                        startDate: formatDate(startDate, 'yyyy-M-dd'),
                        endDate: formatDate(endDate, 'yyyy-M-dd'),
                        employee_id: employee_id
                    },
                    success: function (data) {
                        //  alert(data);
                        //   var jsObj = JSON.parse(data);
                        CreatePlexusAreaChart(data.chartdata);


                    },
                    error: function () {

                    }
                });//Done with employee id
                $.ajax({
                    url: '{{url('home/DermatomesAreaChart')}}',
                    type: "get",
                    datatype: 'json',
                    data: {
                        startDate: formatDate(startDate, 'yyyy-M-dd'),
                        endDate: formatDate(endDate, 'yyyy-M-dd'),
                        employee_id: employee_id
                    },
                    success: function (data) {
                        //  alert(data);
                        //   var jsObj = JSON.parse(data);
                        CreateDermatomesAreaChart(data.chartdata);


                    },
                    error: function () {

                    }
                });//Done with employee id
                $.ajax({
                    url: '{{url('home/totalPainFilesByInjuryMech')}}',
                    type: "get",
                    datatype: 'json',
                    data: {
                        startDate: formatDate(startDate, 'yyyy-M-dd'),
                        endDate: formatDate(endDate, 'yyyy-M-dd'),
                        employee_id: employee_id
                    },
                    success: function (data) {
                        //  alert(data);
                        //   var jsObj = JSON.parse(data);
                        CreatePainInjuryMachChart(data.chartdata);


                    },
                    error: function () {

                    }
                });//Done with employee id
                $.ajax({
                    url: '{{url('home/PeripheralAreaChart')}}',
                    type: "get",
                    datatype: 'json',
                    data: {
                        startDate: formatDate(startDate, 'yyyy-M-dd'),
                        endDate: formatDate(endDate, 'yyyy-M-dd'),
                        employee_id: employee_id
                    },
                    success: function (data) {
                        //  alert(data);
                        //   var jsObj = JSON.parse(data);
                        CreatePeripheralAreaChart(data.chartdata);


                    },
                    error: function () {

                    }
                });//Done with employee id
            }

            function CreateTotalOverAll(data) {

                var chart = AmCharts.makeChart("dashboard_overall", {
                    "type": "serial",
                    "theme": "light",
                    "autoMargins": false,
                    "marginLeft": 30,
                    "marginRight": 8,
                    "marginTop": 10,
                    "marginBottom": 26,
                    "legend": {
                        "equalWidths": false,
                        //"periodValueText": "total: [[value.sum]]",
                        "position": "top",
                        "valueAlign": "left",
                        "valueWidth": 100
                    },
                    "dataProvider": data,
                    "valueAxes": [{
                        "minimum": 0,
                        "maximum": 10,
                        "gridAlpha": 0.07,
                        "position": "left",
                        "guides": []
                    }],
                    "graphs": [{
                        "bullet": "round",
                        "bulletBorderAlpha": 1,
                        "bulletBorderThickness": 1,
                        "lineThickness": 3,
                        "title": "Over All Results",
                        "valueField": "count"
                    }],
                    "chartCursor": {},
                    "categoryField": "overall_status",
                    "categoryAxis": {
                        "minGridDistance ": 0,
                        "maxGridDistance ": 1000,
                        "startOnAxis": true,
                        "gridAlpha": 1,
                        "labelRotation": 0		// Label Rotation
                    },
                    /*"export": {
                        "enabled": true
                    }*/
                });
                chart.invalidateSize();
                var chart = AmCharts.makeChart("dashboard_overall", {
                    "type": "serial",
                    "theme": "light",
                    "pathToImages": App.getGlobalPluginsPath() + "amcharts/amcharts/images/",
                    "autoMargins": true,

                    "fontFamily": 'Open Sans',
                    "color": '#888',

                    "dataProvider": data,
                    "valueAxes": [{
                        "axisAlpha": 0,
                        "position": "left"
                    }],
                    "startDuration": 1,
                    "graphs": [{
                        "alphaField": "alpha",
                        "balloonText": "<span style='font-size:13px;'> [[category]]: <b>[[value]]</b> [[additional]]</span>",
                        "dashLengthField": "dashLengthColumn",
                        "fillAlphas": .8,
                        "lineAlpha": .8,
                        "title": "Patients",
                        "type": "column",
                        "fillColorsField": "COLOR",
                        "lineColorField": "COLOR",
                        "valueField": "COUNT_PATIENTS"	//Name in json
                    }],
                    "categoryField": "OVERALL_STATUS",	//Name in json
                    "categoryAxis": {
                        "gridPosition": "start",
                        "axisAlpha": 0,
                        "tickLength": 0,
                        "labelRotation": 75		// Label Rotation
                    },
                    "export": {
                        "enabled": true
                    }
                });
            }

            function CreatePatientByDistrictChart(data) {
                var chart = AmCharts.makeChart("dashboard_district", {
                    "type": "pie",
                    "theme": "black",
                    "radius": 100,
                    "fontFamily": 'Open Sans',
                    "fontSize": '11',
                    "color": '#000000',
                    "outlineColor": "#FFFFFF",
                    "dataProvider": data,
                    "valueField": "COUNT_PATIENTS",
                    "titleField": "DISTRICT_NAME",
                    "colorField": "COLOR",

                    /*"exportConfig": {
                        menuItems: [{
                            icon: "assets/ltr/global/plugins/amcharts/amcharts/images/export.png",
                            format: 'png'
                        }]
                    }*/
                });
            }

            function CreatePatientByAgeChart(data) {
                var chart = AmCharts.makeChart("dashboard_age", {
                    "type": "pie",
                    "theme": "black",
                    "radius": 100,
                    "fontFamily": 'Open Sans',
                    "fontSize": '11',
                    "color": '#000000',
                    "outlineColor": "#FFFFFF",
                    "dataProvider": data,
                    "valueField": "COUNT_PATIENTS",
                    "titleField": "AGE_RANGE",
                    "colorField": "COLOR",
                    "listeners": [{
                        "event": "clickSlice",
                        "method": function (e) {
                            //  var desc = document.getElementById("DIAGNOSTIC");
                            //alert('DIAGNOSTIC'.desc);

                        }
                    }]
                    /*"exportConfig": {
                        menuItems: [{
                            icon: "assets/ltr/global/plugins/amcharts/amcharts/images/export.png",
                            format: 'png'
                        }]
                    }*/
                });
            }

            function CreatePatientByPhysicalTreatment(data) {
                var startDate = $('#reportrange').data('daterangepicker').startDate._d;
                var endDate = $('#reportrange').data('daterangepicker').endDate._d;
                var employee_id = $('#employee_id').val();
                var monthNames = [
                    "January", "February", "March", "April", "May", "June", "July",
                    "August", "September", "October", "November", "December"
                ];
                var dayOfWeekNames = [
                    "Sunday", "Monday", "Tuesday",
                    "Wednesday", "Thursday", "Friday", "Saturday"
                ];

                function formatDate(date, patternStr) {
                    if (!patternStr) {
                        patternStr = 'M/d/yyyy';
                    }
                    var day = date.getDate(),
                        month = date.getMonth(),
                        year = date.getFullYear(),
                        hour = date.getHours(),
                        minute = date.getMinutes(),
                        second = date.getSeconds(),
                        miliseconds = date.getMilliseconds(),
                        h = hour % 12,
                        hh = twoDigitPad(h),
                        HH = twoDigitPad(hour),
                        mm = twoDigitPad(minute),
                        ss = twoDigitPad(second),
                        aaa = hour < 12 ? 'AM' : 'PM',
                        EEEE = dayOfWeekNames[date.getDay()],
                        EEE = EEEE.substr(0, 3),
                        dd = twoDigitPad(day),
                        M = month + 1,
                        MM = twoDigitPad(M),
                        MMMM = monthNames[month],
                        MMM = MMMM.substr(0, 3),
                        yyyy = year + "",
                        yy = yyyy.substr(2, 2)
                    ;
                    // checks to see if month name will be used
                    patternStr = patternStr
                        .replace('hh', hh).replace('h', h)
                        .replace('HH', HH).replace('H', hour)
                        .replace('mm', mm).replace('m', minute)
                        .replace('ss', ss).replace('s', second)
                        .replace('S', miliseconds)
                        .replace('dd', dd).replace('d', day)

                        .replace('EEEE', EEEE).replace('EEE', EEE)
                        .replace('yyyy', yyyy)
                        .replace('yy', yy)
                        .replace('aaa', aaa);
                    if (patternStr.indexOf('MMM') > -1) {
                        patternStr = patternStr
                            .replace('MMMM', MMMM)
                            .replace('MMM', MMM);
                    } else {
                        patternStr = patternStr
                            .replace('MM', MM)
                            .replace('M', M);
                    }
                    return patternStr;
                }

                function twoDigitPad(num) {
                    return num < 10 ? "0" + num : num;
                }

                var chart = AmCharts.makeChart("dashboard_physicalTreatment", {
                    "type": "pie",
                    "theme": "black",
                    "radius": 100,
                    "fontFamily": 'Open Sans',
                    "fontSize": '11',
                    "color": '#000000',
                    "outlineColor": "#FFFFFF",
                    "dataProvider": data,
                    "valueField": "COUNT_PATIENTS",
                    "titleField": "PHYSICAL_TREATMENT_NAME",
                    "itemField": "PHYSICAL_TREATMENT",
                    "colorField": "COLOR",
                    "listeners": [{
                        "event": "clickSlice",
                        "method": function (e) {
                            // var chart_item = e.chart.dataProvider[e.index][e.chart.category_id];
                            //var index = e.dataItem.index;
                            var dd = e.dataItem.index;
                            console.log(e.chart.dataProvider[dd]);
                            var item_id = e.chart.dataProvider[dd]['PHYSICAL_TREATMENT'];
                            // alert(e.chart.dataProvider[dd]['PHYSICAL_TREATMENT']);
                            $.ajax({
                                url: '{{url('home/totalPatientsPharmaTreatment')}}',
                                type: "get",
                                datatype: 'json',
                                data: {
                                    startDate: formatDate(startDate, 'yyyy-M-dd'),
                                    endDate: formatDate(endDate, 'yyyy-M-dd'),
                                    employee_id: employee_id,
                                    item_id: item_id
                                },
                                success: function (data) {
                                    //  alert(data);
                                    //   var jsObj = JSON.parse(data);
                                    CreatePharmaTreatment(data.chartdata);


                                },
                                error: function () {

                                }
                            });
                        }
                    }]

                    /*"exportConfig": {
                        menuItems: [{
                            icon: "assets/ltr/global/plugins/amcharts/amcharts/images/export.png",
                            format: 'png'
                        }]
                    }*/
                });

            }

            function CreatePharmaTreatment(data) {
                var chart = AmCharts.makeChart("dashboard_PharmaTreatment", {
                    "type": "pie",
                    "theme": "black",
                    "radius": 100,
                    "fontFamily": 'Open Sans',
                    "fontSize": '11',
                    "color": '#000000',
                    "outlineColor": "#FFFFFF",
                    "dataProvider": data,
                    "valueField": "COUNT_PATIENTS",
                    "titleField": "PHARM_TREATMENT_NAME",
                    "colorField": "COLOR",
                    "listeners": [{
                        "event": "clickSlice",
                        "method": function (e) {
                            //  var desc = document.getElementById("DIAGNOSTIC");
                            //alert('DIAGNOSTIC'.desc);
                            //alert('ffff');
                        }
                    }]
                    /*"exportConfig": {
                        menuItems: [{
                            icon: "assets/ltr/global/plugins/amcharts/amcharts/images/export.png",
                            format: 'png'
                        }]
                    }*/
                });
            }

            function getTotalPatientsByGender(data) {
                var chart = AmCharts.makeChart("dashboard_gender", {
                    "type": "pie",
                    "theme": "black",
                    "radius": 100,
                    "fontFamily": 'Open Sans',
                    "fontSize": '11',
                    "color": '#000000',
                    "outlineColor": "#FFFFFF",

                    "dataProvider": data,
                    "valueField": "COUNT_PATIENTS",
                    "titleField": "GENDER_NAME",
                    "fillColorsField": "COLOR",
                    "lineColorField": "COLOR",
                    /*"exportConfig": {
                        menuItems: [{
                            icon: "assets/ltr/global/plugins/amcharts/amcharts/images/export.png",
                            format: 'png'
                        }]
                    }*/
                });
            }

            function CreateFollowupMonthlyChart(data) {
                var chart = AmCharts.makeChart("dashboard_followup", {
                    "type": "serial",
                    "theme": "light",
                    "pathToImages": App.getGlobalPluginsPath() + "amcharts/amcharts/images/",
                    "autoMargins": true,

                    "fontFamily": 'Open Sans',
                    "color": '#888',

                    "dataProvider": data,
                    "valueAxes": [{
                        "axisAlpha": 0,
                        "position": "left"
                    }],
                    "startDuration": 1,
                    "graphs": [{
                        "alphaField": "alpha",
                        "balloonText": "<span style='font-size:13px;'> [[category]]: <b>[[value]]</b> [[additional]]</span>",
                        "dashLengthField": "dashLengthColumn",
                        "fillAlphas": .8,
                        "lineAlpha": .8,
                        "title": "Patients",
                        "type": "column",
                        "fillColorsField": "COLOR",
                        "lineColorField": "COLOR",
                        "valueField": "COUNT_PATIENTS"	//Name in json
                    }],
                    "categoryField": "DURATION",	//Name in json
                    "categoryAxis": {
                        "gridPosition": "start",
                        "axisAlpha": 0,
                        "tickLength": 0,
                        "labelRotation": 75		// Label Rotation
                    },
                    "export": {
                        "enabled": true
                    }
                });
            }

            function CreateClosedFileMonthlyChart(data) {
                var chart = AmCharts.makeChart("dashboard_closed_file", {
                    "type": "serial",
                    "theme": "light",
                    "pathToImages": App.getGlobalPluginsPath() + "amcharts/amcharts/images/",
                    "autoMargins": true,

                    "fontFamily": 'Open Sans',
                    "color": '#888',

                    "dataProvider": data,
                    "valueAxes": [{
                        "axisAlpha": 0,
                        "position": "left"
                    }],
                    "startDuration": 1,
                    "graphs": [{
                        "alphaField": "alpha",
                        "balloonText": "<span style='font-size:13px;'> [[category]]: <b>[[value]]</b> [[additional]]</span>",
                        "dashLengthField": "dashLengthColumn",
                        "fillAlphas": .8,
                        "lineAlpha": .8,
                        "title": "Patients",
                        "type": "column",
                        "fillColorsField": "COLOR",
                        "lineColorField": "COLOR",
                        "valueField": "COUNT_PATIENTS"	//Name in json
                    }],
                    "categoryField": "DURATION",	//Name in json
                    "categoryAxis": {
                        "gridPosition": "start",
                        "axisAlpha": 0,
                        "tickLength": 0,
                        "labelRotation": 75		// Label Rotation
                    },
                    "export": {
                        "enabled": true
                    }
                });
            }

            function CreateBaselineMonthlyChart(data) {
                var chart = AmCharts.makeChart("dashboard_baseline", {
                    "type": "serial",
                    "theme": "light",
                    "pathToImages": App.getGlobalPluginsPath() + "amcharts/amcharts/images/",
                    "autoMargins": true,

                    "fontFamily": 'Open Sans',
                    "color": '#888',

                    "dataProvider": data,
                    "valueAxes": [{
                        "axisAlpha": 0,
                        "position": "left"
                    }],
                    "startDuration": 1,
                    "graphs": [{
                        "alphaField": "alpha",
                        "balloonText": "<span style='font-size:13px;'> [[category]]: <b>[[value]]</b> [[additional]]</span>",
                        "dashLengthField": "dashLengthColumn",
                        "fillAlphas": .8,
                        "lineAlpha": .8,
                        "title": "Patients",
                        "type": "column",
                        "fillColorsField": "COLOR",
                        "lineColorField": "COLOR",
                        "valueField": "COUNT_PATIENTS"	//Name in json
                    }],
                    "categoryField": "DURATION",	//Name in json
                    "categoryAxis": {
                        "gridPosition": "start",
                        "axisAlpha": 0,
                        "tickLength": 0,
                        "labelRotation": 75		// Label Rotation
                    },
                    "export": {
                        "enabled": true
                    }
                });
            }

            function CreateTop10DiagnosisChart(data) {
                var chart = AmCharts.makeChart("dashboard_diagnosis", {
                    "type": "serial",
                    "theme": "light",
                    "pathToImages": App.getGlobalPluginsPath() + "amcharts/amcharts/images/",
                    "autoMargins": true,

                    "fontFamily": 'Open Sans',
                    "color": '#888',

                    "dataProvider": data,
                    "valueAxes": [{
                        "axisAlpha": 0,
                        "position": "left"
                    }],
                    "startDuration": 1,
                    "graphs": [{
                        "alphaField": "alpha",
                        "balloonText": "<span style='font-size:13px;'> [[category]]: <b>[[value]]</b> [[additional]]</span>",
                        "fillAlphas": 0.9,
                        "lineAlpha": 0.2,
                        "type": "column",
                        "valueField": "COUNT",
                        "fillColorsField": "COLOR",
                        "lineColorField": "COLOR",
                        "title": "Diagnosis",
                    }],
                    "chartCursor": {
                        "categoryBalloonEnabled": false,
                        "balloonPointerOrientation": "vertical",
                        "cursorAlpha": 0,
                        "zoomable": false
                    },
                    /*  "graphs": [{
                          "alphaField": "alpha",
                          "balloonText": "<br><a href='https://google.com/' target='_blank'>Google</a></b><span style='font-size:13px;'> [[category]]: <b>[[value]]</b> [[additional]]</span>",
                          "dashLengthField": "dashLengthColumn",
                          "fillAlphas": .8,
                          "lineAlpha": .8,
                          "title": "Diagnosis",
                          "type": "column",
                          "fillColorsField": "COLOR",
                          "lineColorField": "COLOR",
                          "valueField": "COUNT"	//Name in json
                      }],*/
                    "categoryField": "DIAGNOSTIC",	//Name in json
                    "category_id": "DIAGNOSTIC_ID",	//id in json
                    "categoryAxis": {
                        "gridPosition": "start",
                        "axisAlpha": 0,
                        "tickLength": 0,
                        "labelRotation": 75		// Label Rotation
                    },
                    "export": {
                        "enabled": true
                    },

                });
                chart.addListener("clickGraphItem", function (e) {
                    var chart_item = e.chart.dataProvider[e.index][e.chart.category_id];
                    $('#chart_Modal').modal('show');
                    get_chart_detials(chart_item, 1);//1=diagnosis chart
                    //alert(data);


                });
            }

            function get_chart_detials(chart_item, chart_id) {
                var startDate = $('#reportrange').data('daterangepicker').startDate._d;
                var endDate = $('#reportrange').data('daterangepicker').endDate._d;
                var employee_id = $('#employee_id').val();
                var monthNames = [
                    "January", "February", "March", "April", "May", "June", "July",
                    "August", "September", "October", "November", "December"
                ];
                var dayOfWeekNames = [
                    "Sunday", "Monday", "Tuesday",
                    "Wednesday", "Thursday", "Friday", "Saturday"
                ];

                function formatDate(date, patternStr) {
                    if (!patternStr) {
                        patternStr = 'M/d/yyyy';
                    }
                    var day = date.getDate(),
                        month = date.getMonth(),
                        year = date.getFullYear(),
                        hour = date.getHours(),
                        minute = date.getMinutes(),
                        second = date.getSeconds(),
                        miliseconds = date.getMilliseconds(),
                        h = hour % 12,
                        hh = twoDigitPad(h),
                        HH = twoDigitPad(hour),
                        mm = twoDigitPad(minute),
                        ss = twoDigitPad(second),
                        aaa = hour < 12 ? 'AM' : 'PM',
                        EEEE = dayOfWeekNames[date.getDay()],
                        EEE = EEEE.substr(0, 3),
                        dd = twoDigitPad(day),
                        M = month + 1,
                        MM = twoDigitPad(M),
                        MMMM = monthNames[month],
                        MMM = MMMM.substr(0, 3),
                        yyyy = year + "",
                        yy = yyyy.substr(2, 2)
                    ;
                    // checks to see if month name will be used
                    patternStr = patternStr
                        .replace('hh', hh).replace('h', h)
                        .replace('HH', HH).replace('H', hour)
                        .replace('mm', mm).replace('m', minute)
                        .replace('ss', ss).replace('s', second)
                        .replace('S', miliseconds)
                        .replace('dd', dd).replace('d', day)

                        .replace('EEEE', EEEE).replace('EEE', EEE)
                        .replace('yyyy', yyyy)
                        .replace('yy', yy)
                        .replace('aaa', aaa);
                    if (patternStr.indexOf('MMM') > -1) {
                        patternStr = patternStr
                            .replace('MMMM', MMMM)
                            .replace('MMM', MMM);
                    } else {
                        patternStr = patternStr
                            .replace('MM', MM)
                            .replace('M', M);
                    }
                    return patternStr;
                }

                function twoDigitPad(num) {
                    return num < 10 ? "0" + num : num;
                }

                /*  console.log(formatDate(new Date()));
                  console.log(formatDate(new Date(), 'dd-MMM-yyyy')); //OP's request
                  console.log(formatDate(new Date(), 'EEEE, MMMM d, yyyy HH:mm:ss.S aaa'));
                  console.log(formatDate(new Date(), 'EEE, MMM d, yyyy HH:mm'));
                  console.log(formatDate(new Date(), 'yyyy-MM-dd HH:mm:ss.S'));
                  console.log(formatDate(new Date(), 'M/dd/yyyy h:mmaaa'));*/
                // alert('startDate: '+startDate)
                // alert('endDate: '+endDate)
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                //  alert(data);
                //   var jsObj = JSON.parse(data);

                // alert(view_type);
                $('#chart_tbl').DataTable().destroy();

                $('#chart_tbl').dataTable({

                    'processing': true,
                    'serverSide': true,
                    'ajax': {
                        "type": "post",
                        "url": "{{url('home/chart-details')}}",

                        "data": {
                            'startDate': formatDate(startDate, 'yyyy-M-dd'),
                            'endDate': formatDate(endDate, 'yyyy-M-dd'),
                            'chart_item': chart_item,
                            'chart_id': chart_id
                        }

                    },

                    "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                    "pageLength": 10,
                    'columns': [
                        // {data: 'delChk', name: 'delChk'},
                        {data: 'id', name: 'id', width: '5%', orderable: true},
                        {data: 'patient_name', name: 'patient_name', width: '20%'},
                        {data: 'patient_name_a', name: 'patient_name_a', width: '20%'},
                        {data: 'national_id', name: 'national_id', width: '10%'},
                        {data: 'gender_desc', name: 'gender_desc', width: '10%', orderable: true},
                        {data: 'mobile_no', name: 'mobile_no', width: '10%'},
                        {data: 'start_date', name: 'start_date', width: '10%', orderable: true},
                        {data: 'doctor_name', name: 'doctor_name', width: '10%', orderable: true},
                        {data: 'source', name: 'source', width: '10%', orderable: true},
                    ],
                    "order": [[0, "desc"]], // Order on init. # is the column, starting at 0
                    "dom": "<'row' <'col-md-12'B>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable
                    'buttons': [
                        {
                            'extend': 'excel',
                            'className': 'btn green',
                            'exportOptions': {
                                'columns': [0, 1, 2, 3, 4, 5, 6]
                            }
                        }

                    ],
                    /*  aoColumnDefs: [
                          {bSortable: false, aTargets: ["_all"]}
                      ],*/
                    "language": { // language settings
                        // metronic spesific
                        "metronicGroupActions": "_TOTAL_ records selected:  ",
                        "metronicAjaxRequestGeneralError": "Could not complete request. Please check your internet connection",

                        // data tables spesific
                        "lengthMenu": "<span class='seperator'>|</span>View _MENU_ records",
                        "info": "<span class='seperator'>|</span>Found total _TOTAL_ records",
                        "infoEmpty": "No records found to show",
                        "emptyTable": "No data available in table",
                        "zeroRecords": "No matching records found",
                        "paginate": {
                            "previous": "Prev",
                            "next": "Next",
                            "last": "Last",
                            "first": "First",
                            "page": "Page",
                            "pageOf": "of"
                        }
                    },

                })

            }

            function CreateTop10DrugsChart(data) {
                var chart = AmCharts.makeChart("dashboard_drugs", {
                    "type": "serial",
                    "theme": "light",
                    "pathToImages": App.getGlobalPluginsPath() + "amcharts/amcharts/images/",
                    "autoMargins": true,

                    "fontFamily": 'Open Sans',
                    "color": '#888',

                    "dataProvider": data,
                    "valueAxes": [{
                        "axisAlpha": 0,
                        "position": "left"
                    }],
                    "startDuration": 1,
                    "graphs": [{
                        "alphaField": "alpha",
                        "balloonText": "<span style='font-size:13px;'> [[category]]: <b>[[value]]</b> [[additional]]</span>",
                        "dashLengthField": "dashLengthColumn",
                        "fillAlphas": .8,
                        "lineAlpha": .8,
                        "title": "Diagnosis",
                        "type": "column",
                        "fillColorsField": "COLOR",
                        "lineColorField": "COLOR",
                        "valueField": "COUNT"	//Name in json
                    }],
                    "categoryField": "DRUGS",	//Name in json
                    "category_id": "DRUG_ID",	//id in json
                    "categoryAxis": {
                        "gridPosition": "start",
                        "axisAlpha": 0,
                        "tickLength": 0,
                        "labelRotation": 75		// Label Rotation
                    },
                    "export": {
                        "enabled": true
                    }
                });
                chart.addListener("clickGraphItem", function (e) {
                    var chart_item = e.chart.dataProvider[e.index][e.chart.category_id];
                    $('#chart_Modal').modal('show');
                    get_chart_detials(chart_item, 2);//2=drugs chart
                    //alert(data);


                });
            }

            function CreateDrugsCostChart(data) {
                var chart = AmCharts.makeChart("dashboard_drugs_cost", {
                    "type": "serial",
                    "rotate": true,
                    "theme": "light",
                    "pathToImages": App.getGlobalPluginsPath() + "amcharts/amcharts/images/",
                    "autoMargins": true,

                    "fontFamily": 'Open Sans',
                    "color": '#888',

                    "dataProvider": data,
                    "valueAxes": [{
                        "axisAlpha": 0,
                        "position": "left"
                    }],
                    "startDuration": 1,
                    "graphs": [{
                        "alphaField": "alpha",
                        "balloonText": "<span style='font-size:13px;'> [[category]]: <b>[[value]]</b> [[additional]]</span>",
                        "dashLengthField": "dashLengthColumn",
                        "fillAlphas": .8,
                        "lineAlpha": .8,
                        "title": "Diagnosis",
                        "type": "column",
                        "fillColorsField": "COLOR",
                        "lineColorField": "COLOR",
                        "valueField": "DRUG_COST"	//Name in json
                    }],
                    "categoryField": "DRUGS",	//Name in json
                    "category_id": "DRUG_ID",	//id in json
                    "categoryAxis": {
                        "gridPosition": "start",
                        "axisAlpha": 0,
                        "tickLength": 0,
                        "labelRotation": 75		// Label Rotation
                    },
                    "export": {
                        "enabled": true
                    }
                });
                /* chart.addListener("clickGraphItem", function (e) {
                     var chart_item = e.chart.dataProvider[e.index][e.chart.category_id];
                     $('#chart_Modal').modal('show');
                     get_chart_detials(chart_item, 2);//2=drugs chart
                     //alert(data);


                 });*/
            }

            function CreateDoctorDrugsCostChart(data) {
                var chart = AmCharts.makeChart("dashboard_doctor_drugs_cost", {
                    "type": "serial",
                    // "rotate": true,
                    "theme": "light",
                    "pathToImages": App.getGlobalPluginsPath() + "amcharts/amcharts/images/",
                    "autoMargins": true,

                    "fontFamily": 'Open Sans',
                    "color": '#888',

                    "dataProvider": data,
                    "valueAxes": [{
                        "axisAlpha": 0,
                        "position": "left"
                    }],
                    "startDuration": 1,
                    "graphs": [{
                        "alphaField": "alpha",
                        "balloonText": "<span style='font-size:13px;'> [[category]]: <b>[[value]]</b> [[additional]]</span>",
                        "dashLengthField": "dashLengthColumn",
                        "fillAlphas": .8,
                        "lineAlpha": .8,
                        "title": "Diagnosis",
                        "type": "column",
                        "fillColorsField": "COLOR",
                        "lineColorField": "COLOR",
                        "valueField": "DRUG_COST"	//Name in json
                    }],
                    "categoryField": "USER_NAME",	//Name in json
                    "category_id": "DRUG_ID",	//id in json
                    "categoryAxis": {
                        "gridPosition": "start",
                        "axisAlpha": 0,
                        "tickLength": 0,
                        "labelRotation": 75		// Label Rotation
                    },
                    "export": {
                        "enabled": true
                    }
                });
                /* chart.addListener("clickGraphItem", function (e) {
                     var chart_item = e.chart.dataProvider[e.index][e.chart.category_id];
                     $('#chart_Modal').modal('show');
                     get_chart_detials(chart_item, 2);//2=drugs chart
                     //alert(data);


                 });*/
            }

            function CreatePainScaleChart(data) {
                var chart = AmCharts.makeChart("dashboard_pain_scale", {
                    "type": "serial",
                    // "rotate": true,
                    "theme": "light",
                    "pathToImages": App.getGlobalPluginsPath() + "amcharts/amcharts/images/",
                    "autoMargins": true,

                    "fontFamily": 'Open Sans',
                    "color": '#888',

                    "dataProvider": data,
                    "valueAxes": [{
                        "axisAlpha": 0,
                        "position": "left"
                    }],
                    "startDuration": 1,
                    "graphs": [{
                        "alphaField": "alpha",
                        "balloonText": "<span style='font-size:13px;'> [[category]]: <b>[[value]]</b> [[additional]]</span>",
                        "dashLengthField": "dashLengthColumn",
                        "fillAlphas": .8,
                        "lineAlpha": .8,
                        "title": "Diagnosis",
                        "type": "column",
                        "fillColorsField": "COLOR",
                        "lineColorField": "COLOR",
                        "valueField": "COUNT"	//Name in json
                    }],
                    "categoryField": "PAIN_SCALE",	//Name in json
                    "category_id": "PAIN_SCALE",	//id in json
                    "categoryAxis": {
                        "gridPosition": "start",
                        "axisAlpha": 0,
                        "tickLength": 0,
                        "labelRotation": 75		// Label Rotation
                    },
                    "export": {
                        "enabled": true
                    }
                });
                /* chart.addListener("clickGraphItem", function (e) {
                     var chart_item = e.chart.dataProvider[e.index][e.chart.category_id];
                     $('#chart_Modal').modal('show');
                     get_chart_detials(chart_item, 2);//2=drugs chart
                     //alert(data);


                 });*/
            }

            function CreateAreaChart(data) {
                var chart = AmCharts.makeChart("dashboard_area", {
                    "type": "pie",
                    "theme": "black",
                    "radius": 100,
                    "fontFamily": 'Open Sans',
                    "fontSize": '11',
                    "color": '#000000',
                    "outlineColor": "#FFFFFF",

                    "dataProvider": data,
                    "valueField": "COUNT",
                    "titleField": "SIDE_NERVE",
                    /*"exportConfig": {
                        menuItems: [{
                            icon: "assets/ltr/global/plugins/amcharts/amcharts/images/export.png",
                            format: 'png'
                        }]
                    }*/
                });
            }

            function CreatePlexusAreaChart(data) {
                var chart = AmCharts.makeChart("dashboard_Plexus", {
                    "type": "serial",
                    "theme": "light",
                    "pathToImages": App.getGlobalPluginsPath() + "amcharts/amcharts/images/",
                    "autoMargins": true,

                    "fontFamily": 'Open Sans',
                    "color": '#888',

                    "dataProvider": data,
                    "valueAxes": [{
                        "axisAlpha": 0,
                        "position": "left"
                    }],
                    "startDuration": 1,
                    "graphs": [{
                        "alphaField": "alpha",
                        "balloonText": "<span style='font-size:13px;'> [[category]]: <b>[[value]]</b> [[additional]]</span>",
                        "dashLengthField": "dashLengthColumn",
                        "fillAlphas": .8,
                        "lineAlpha": .8,
                        "title": "Pain Area",
                        "type": "column",
                        "fillColorsField": "COLOR",
                        "lineColorField": "COLOR",
                        "valueField": "COUNT"	//Name in json
                    }],
                    "categoryField": "SIDE_NERVE",	//Name in json
                    "categoryAxis": {
                        "gridPosition": "start",
                        "axisAlpha": 0,
                        "tickLength": 0,
                        "labelRotation": 75		// Label Rotation
                    },
                    "export": {
                        "enabled": true
                    }
                });
            }

            function CreateDermatomesAreaChart(data) {
                var chart = AmCharts.makeChart("dashboard_Dermatomes", {
                    "type": "serial",
                    "theme": "light",
                    "pathToImages": App.getGlobalPluginsPath() + "amcharts/amcharts/images/",
                    "autoMargins": true,

                    "fontFamily": 'Open Sans',
                    "color": '#888',

                    "dataProvider": data,
                    "valueAxes": [{
                        "axisAlpha": 0,
                        "position": "left"
                    }],
                    "startDuration": 1,
                    "graphs": [{
                        "alphaField": "alpha",
                        "balloonText": "<span style='font-size:13px;'> [[category]]: <b>[[value]]</b> [[additional]]</span>",
                        "dashLengthField": "dashLengthColumn",
                        "fillAlphas": .8,
                        "lineAlpha": .8,
                        "title": "Pain Area",
                        "type": "column",
                        "fillColorsField": "COLOR",
                        "lineColorField": "COLOR",
                        "valueField": "COUNT"	//Name in json
                    }],
                    "categoryField": "SIDE_NERVE",	//Name in json
                    "categoryAxis": {
                        "gridPosition": "start",
                        "axisAlpha": 0,
                        "tickLength": 0,
                        "labelRotation": 75		// Label Rotation
                    },
                    "export": {
                        "enabled": true
                    }
                });

            }

            function CreatePeripheralAreaChart(data) {
                var chart = AmCharts.makeChart("dashboard_Peripheral", {
                    "type": "serial",
                    "theme": "light",
                    "pathToImages": App.getGlobalPluginsPath() + "amcharts/amcharts/images/",
                    "autoMargins": true,

                    "fontFamily": 'Open Sans',
                    "color": '#888',

                    "dataProvider": data,
                    "valueAxes": [{
                        "axisAlpha": 0,
                        "position": "left"
                    }],
                    "startDuration": 1,
                    "graphs": [{
                        "alphaField": "alpha",
                        "balloonText": "<span style='font-size:13px;'> [[category]]: <b>[[value]]</b> [[additional]]</span>",
                        "dashLengthField": "dashLengthColumn",
                        "fillAlphas": .8,
                        "lineAlpha": .8,
                        "title": "Pain Area",
                        "type": "column",
                        "fillColorsField": "COLOR",
                        "lineColorField": "COLOR",
                        "valueField": "COUNT"	//Name in json
                    }],
                    "categoryField": "SIDE_NERVE",	//Name in json
                    "categoryAxis": {
                        "gridPosition": "start",
                        "axisAlpha": 0,
                        "tickLength": 0,
                        "labelRotation": 75		// Label Rotation
                    },
                    "export": {
                        "enabled": true
                    }
                });

            }

            function CreatePainInjuryMachChart(data) {
                var chart = AmCharts.makeChart("dashboard_injury_mechanism", {
                    "type": "serial",
                    "theme": "light",
                    "pathToImages": App.getGlobalPluginsPath() + "amcharts/amcharts/images/",
                    "autoMargins": true,

                    "fontFamily": 'Open Sans',
                    "color": '#888',

                    "dataProvider": data,
                    "valueAxes": [{
                        "axisAlpha": 0,
                        "position": "left"
                    }],
                    "startDuration": 1,
                    "graphs": [{
                        "alphaField": "alpha",
                        "balloonText": "<span style='font-size:13px;'> [[category]]: <b>[[value]]</b> [[additional]]</span>",
                        "dashLengthField": "dashLengthColumn",
                        "fillAlphas": .8,
                        "lineAlpha": .8,
                        "title": "Pain Area",
                        "type": "column",
                        "fillColorsField": "COLOR",
                        "lineColorField": "COLOR",
                        "valueField": "COUNT_PATIENTS"	//Name in json
                    }],
                    "categoryField": "INJURY_MECHANISM",	//Name in json
                    "categoryAxis": {
                        "gridPosition": "start",
                        "axisAlpha": 0,
                        "tickLength": 0,
                        "labelRotation": 75		// Label Rotation
                    },
                    "export": {
                        "enabled": true
                    }
                });

            }


            function viewPainFile(painFile_id, patient_id, painFile_status) {
                App.blockUI();
                var url = 'painFile/view/' + painFile_id + '/' + patient_id + '/' + painFile_status;
                window.open('{{url('/')}}/' + url, '_self');

            }

        </script>
    @endpush
@stop
