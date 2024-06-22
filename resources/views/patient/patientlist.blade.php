@extends('admin.layout.index')
@section('content')
    <div class="page-content">
        <div class="page-head">
            <meta name="csrf-token" content="{{ csrf_token()}}">
            <div class="page-title">
                <h1>{{isset($page_title)?$page_title:''}}
                    <small>{{isset($page_title_small)?$page_title_small:''}}</small>
                </h1>
            </div>
            <!-- END PAGE TITLE -->
            <!-- BEGIN PAGE TOOLBAR -->
            <div class="page-toolbar">

            </div>
        </div>

    @include('admin.layout.breadcrumb')


    <!-- BEGIN TAB PORTLET-->
        <div class="row ">
            <div class="col-md-12">
                <!-- BEGIN VALIDATION STATES-->
                <div class="portlet light portlet-fit portlet-form bordered">
                    {{--<div class="portlet-title">
                        <div class="caption">
                            <i class="icon-user font-green"></i>
                            <span class="caption-subject font-green sbold uppercase">Search</span>
                        </div>
                    </div>--}}
                    <div class="portlet-body form">
                        {{Form::open(['url'=>'','class'=>'form-horizontal','method'=>"post","id"=>"report_form"])}}
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Patient Id.</label>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <input name="national_id" id="national_id" type="text"
                                                       class="form-control input-left"
                                                       placeholder="" value="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="multiple"
                                               class="control-label col-md-4">Name</label>
                                        <div class="col-md-6">
                                            <input name="name" id="name" type="text"
                                                   class="form-control"
                                                   placeholder="name">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="multiple"
                                               class="control-label col-md-4">Gender</label>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <select id="gender" name="gender"
                                                        class="form-control select2">

                                                    <option value="">All</option>
                                                    <option value="1">Male</option>
                                                    <option value="2">Female</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="multiple" class="control-label col-md-4">Name
                                            Arabic</label>
                                        <div class="col-md-6">
                                            <input name="name_a" id="name_a" type="text"
                                                   class="form-control"
                                                   placeholder="name arabic">
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Date</label>
                                        <div class="col-md-6">
                                            <div class="input-group input-large date-picker input-daterange"
                                                 data-date="" data-date-format="yyyy-mm-dd">
                                                <input type="text" class="form-control" name="from"
                                                       id="from">
                                                <span class="input-group-addon"> to </span>
                                                <input type="text" class="form-control" name="to"
                                                       id="to"></div>
                                            <!-- /input-group -->
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Supervisor</label>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <select id="baseline_doctor" name="baseline_doctor"
                                                        class="form-control select2">
                                                    <option value="">All</option>
                                                    @if (isset($users))
                                                        @foreach ($users as $user) {

                                                        <option value="{{$user->id}}"> {{$user->name }}</option>

                                                        }
                                                        @endforeach
                                                    @endif

                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">File status</label>
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <select id="file_status" name="file_status"
                                                        class="form-control select2">
                                                    <option value="">All</option>
                                                    <option value="17">Open</option>
                                                    <option value="18">Closed</option>
                                                    <option value="514">Excluded</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Current Stage</label>
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <select id="current_stage" name="current_stage"
                                                        class="form-control select2">
                                                    <option value="">All</option>
                                                    <option value="1">Nurse</option>
                                                    <option value="2">Doctor</option>
                                                    <option value="3">Pharmacy</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-3 col-md-9">

                                    <button type="button" id="search_btn" class="btn green">
                                        View
                                    </button>
                                    <button type="button" id="clear_btn" class="btn  grey-salsa btn-outline">
                                        Clear
                                    </button>
                                    @if(auth()->user()->user_type_id==8 && auth()->user()->id!=100)
                                        <div class="btn-group">
                                            <button type="button" class="btn dropdown-toggle blue"
                                                    data-toggle="dropdown">
                                                <i class="fa fa-ellipsis-horizontal"></i> Export
                                                <i class="fa fa-angle-down"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a href="javascript:;" id="baseline_export_btn"> Baseline </a>
                                                </li>
                                                <li>
                                                    <a href="javascript:;" id="followup_export_btn"> Followup </a>
                                                </li>
                                            </ul>
                                        </div>

                                    @endif
                                </div>
                            </div>
                        </div>

                        {{Form::close()}}
                    </div>
                </div>
                <!-- END SAMPLE FORM PORTLET-->

            </div>

        </div>
        <div class="portlet light ">
            <div class="portlet-title tabbable-line">
                <div class="caption">
                    <i class="icon-share font-dark"></i>
                    <span class="caption-subject font-dark bold uppercase">Patient List</span>
                </div>
                <ul class="nav nav-tabs">
                    <li class="active" data-id="1">
                        <a href="#patient_search" data-toggle="tab" onclick="viewReport(1);"> Search </a>
                    </li>
                    <li data-id="2">
                        <a href="#today_appointment" data-toggle="tab" onclick="viewReport(2);"> Appointments </a>
                    </li>
                    {{-- <li>
                         <a href="#today_attent" data-toggle="tab"> Today Attend </a>
                     </li>
                     <li>
                         <a href="#today_missed" data-toggle="tab"> Missed </a>
                     </li>--}}

                </ul>
            </div>
            <div class="portlet-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="patient_search">

                        <div class="row">
                            <div class="col-md-12">
                                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                                <div class="portlet light bordered">

                                    <div class="portlet-body">
                                        <div class="table-toolbar">
                                            <div class="col-md-2">

                                            </div>
                                            <div class="col-md-2">
                                            </div>

                                        </div>
                                        <table class="table table-striped table-bordered table-hover"
                                               id="search_tbl">
                                            <thead>
                                            <tr>
                                                <th>File No.</th>
                                                <th>Name</th>
                                                <th>Name Ar</th>
                                                <th>ID</th>
                                                <th>Sex</th>
                                                <th>Mobile No.</th>
                                                <th>Birth date</th>
                                                <th>File status</th>
                                                <!--                                                <th>File date</th>-->
                                                <th>BaseLine date</th>
                                                <th>Treatment<br/> duration</th>
                                                <th>Doctor</th>
                                                <th>Last Followup date</th>
                                                <th>Control</th>
                                            </tr>
                                            </thead>
                                        </table>
                                        <div class="row">
                                            <!--<div class="col-md-3">
                                                <div class="btn-group">
                                                    <button class="btn red " type="button" id="btn-delete"
                                                            title="">
                                                        <i class="fa fa-times"></i>Delete All
                                                    </button>
                                                </div>
                                            </div>-->
                                            <div class="col-md-6"></div>
                                            <div class="col-md-3">
                                                <div class="col-md-3">

                                                </div>
                                                <div class="col-md-3">

                                                </div>
                                                <div class="col-md-3 ">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END EXAMPLE TABLE PORTLET-->
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane " id="today_appointment">

                        <div class="row">
                            <div class="col-md-12">
                                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                                <div class="portlet light bordered">

                                    <div class="portlet-body">
                                        <div class="form-group">

                                            <div class="input-group">
                                                <div class="icheck-inline">
                                                    <span class="label label-info"> Today </span>

                                                    <label>
                                                        <input type="radio" name="view_type" checked class="icheck"
                                                               value="0"> Appointments </label>
                                                    <label>
                                                        <input type="radio" name="view_type" checked class="icheck"
                                                               value="4"> Attend</label>
                                                    <label>
                                                        <input type="radio" name="view_type" checked class="icheck"
                                                               value="5"> Missed</label>
                                                    &nbsp&nbsp&nbsp
                                                    <span class="label label-success">  All </span>

                                                    <label>
                                                        <input type="radio" name="view_type" checked class="icheck"
                                                               value="1">Appointments </label>
                                                    <label>
                                                        <input type="radio" name="view_type" class="icheck" value="2">
                                                        Attend </label>
                                                    <label>
                                                        <input type="radio" name="view_type" class="icheck" value="3">
                                                        Missed </label>

                                                </div>
                                            </div>
                                        </div>
                                        <table class="table table-striped table-bordered table-hover"
                                               id="today_appointment_tbl">
                                            <thead>
                                            <tr>
                                                <th>File No.</th>
                                                <th>Name</th>
                                                <th>Name Ar</th>
                                                <th>ID</th>
                                                <th>Sex</th>
                                                <th>Mobile No.</th>
                                                <th> Appoint. Date</th>
                                                <th> Time</th>
                                                <th> Attend Date</th>
                                                <th> Time</th>
                                                <th> Type</th>
                                                <th> Not Attend Reason</th>
                                                <th> Treatment<br/>duration</th>
                                                <th> Doctor</th>
                                                <th>Control</th>
                                            </tr>
                                            </thead>
                                        </table>

                                    </div>
                                </div>
                                <!-- END EXAMPLE TABLE PORTLET-->
                            </div>
                        </div>
                    </div>
                    {{--  <div class="tab-pane" id="today_attent">

                      </div>
                      <div class="tab-pane" id="today_missed">
                          <p> Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl
                              ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in
                              vulputate velit
                              esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et
                              accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis
                              dolore te
                              feugait nulla facilisi. </p>
                          <p>
                              <a class="btn purple" href="ui_tabs_accordions_navs.html#portlet_tab2_3" target="_blank">
                                  Activate this tab via URL </a>
                          </p>
                      </div>--}}

                </div>
            </div>
        </div>
        <!-- END TAB PORTLET-->

    </div>
    <div class="modal fade" id="not_attend_modal" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Reason for not attend</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" action="" method="">
                        <input type="hidden" id="not_attend_appoint_id" value="{{''}}">
                        <input type="hidden" id="not_attend_patient_id" value="{{''}}">
                        <div class="form-group">
                            <label class="control-label col-md-3">Reason<span class="required"> * </span>
                            </label>
                            <div class="col-md-6">
                                <select id="reason_for_not_attending" name="reason_for_not_attending"
                                        class="form-control select2">
                                    <option value="">Select...</option>
                                    <?php
                                    foreach ($not_attend_reason_list as $raw)
                                        echo '<option value="' . $raw->id . '">' . $raw->lookup_details . '</option>';

                                    ?>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn green" onclick="save_not_attend();" data-dismiss="modal">Save</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div class="modal fade bs-modal-lg" id="charts_modal" tabindex="-1" role="dialog"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true"></button>
                    <h4 class="modal-title ">Follow Up Charts </h4>
                </div>


                <div class="modal-body">
                    <ul class="nav nav-pills">
                        <li class="active">
                            <a href="#tab_2_1" data-toggle="tab"> Scores </a>
                        </li>
                        <li>
                            <a href="#tab_2_2" data-toggle="tab"> Treatment Goals </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade active in" id="tab_2_1">

                            <div id="dvScoreChart" class="chart" style="height: 525px;">
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab_2_2">

                            <div id="dvGoalsChart" class="chart" style="height: 525px;">
                            </div>
                        </div>

                    </div>


                </div>
                <div class="modal-footer">

                    <button class="btn dark btn-outline" data-dismiss="modal" aria-hidden="true">Close
                    </button>
                </div>

            </div>
        </div>
    </div>
    @push('css')
        <style>

            .datepicker {
                width: 15%;

            }
        </style>
        <link href="{{url('')}}/assets/global/plugins/select2/css/select2.min.css" rel="stylesheet"
              type="text/css"/>
        <link href="{{url('')}}/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet"
              type="text/css"/>
        <link href="{{url('')}}/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css"
              rel="stylesheet" type="text/css"/>
        <link href="{{url('')}}/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css"
              rel="stylesheet" type="text/css"/>



        <link href="{{url('/')}}/assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css"/>
        <link href="{{url('/')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css"
              rel="stylesheet" type="text/css"/>
        <link href="{{url('/')}}/assets/global/plugins/bootstrap-sweetalert/sweetalert.css" rel="stylesheet"
              type="text/css"/>
    @endpush
    @push('js')

        <script src="{{url('')}}/assets/global/plugins/select2/js/select2.full.min.js"
                type="text/javascript"></script>
        <script src="{{url('')}}/assets/global/plugins/moment.min.js" type="text/javascript"></script>
        <script src="{{url('')}}/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js"
                type="text/javascript"></script>
        <script src="{{url('')}}/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"
                type="text/javascript"></script>
        <script src="{{url('/')}}/assets/global/scripts/datatable.js" type="text/javascript"></script>
        <script src="{{url('/')}}/assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
        <script src="{{url('/')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js"
                type="text/javascript"></script>
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <!-- END PAGE LEVEL SCRIPTS -->
        <!-- END PAGE LEVEL PLUGINS -->
        <script src="{{url('/')}}/assets/global/plugins/amcharts/amcharts/amcharts.js" type="text/javascript"></script>
        <script src="{{url('/')}}/assets/global/plugins/amcharts/amcharts/serial.js" type="text/javascript"></script>
        <script src="{{url('/')}}/assets/global/plugins/amcharts/amcharts/pie.js" type="text/javascript"></script>
        <script src="{{url('/')}}/assets/global/plugins/amcharts/amcharts/themes/light.js"
                type="text/javascript"></script>
        <script src="{{url('/')}}/assets/global/plugins/amcharts/amcharts/themes/patterns.js"
                type="text/javascript"></script>
        <script src="{{url('/')}}/assets/global/plugins/amcharts/amcharts/themes/chalk.js"
                type="text/javascript"></script>
        <script src="{{url('/')}}/assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js"
                type="text/javascript"></script>
        <script>

            function show_score_chart(id) {
                blockUI('#dvScoreChart');

                $('#dvNoteNoRecord').addClass('display-hide');
                $("#dvScoreChart").html('');

                var datagoal = [];
                var guidesdata = [];
                var data2 = [];
                var data3 = [];
                // var painFile_id = $('#painFile_id').val()
                //  var painFile_status = $('#painFile_statusid').val()
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '<?php echo e(url('followup/show-charts')); ?>',

                    data: {painFile_id: id},
                    error: function (xhr, status, error) {

                    },
                    beforeSend: function () {
                    },
                    complete: function () {
                    },
                    success: function (data) {

                        creat_score_chart(data.score_data);

                        for (let i = 0; i < data.guidesdata.length; ++i) {
                            guidesdata = {
                                "id": "guide-" + i,
                                "value": data.guidesdata[i].baseline_goal_score,
                                "lineColor": "#26C281",
                                "color": "#26C281",
                                "lineAlpha": 0.7,
                                "lineThickness": 2,
                                "dashLength": 2,
                                "inside": true,
                                "boldLabel": true,
                                "position": "right",
                                "label": data.guidesdata[i].baseline_goal
                            }
                            data3.push(guidesdata);
                        }
                        for (let i = 0; i < data.goals.length; ++i) {
                            //     alert(data.goals[i].baseline_goal);
                            datagoal = {
                                "bullet": "round",
                                "bulletBorderAlpha": 1,
                                "bulletBorderThickness": 1,
                                "lineThickness": 3,
                                "title": data.goals[i].baseline_goal,
                                "valueField": data.goals[i].baseline_goal
                            };

                            data2.push(datagoal);

                        }
                        //   alert(datagoal.length);
                        creat_goal_chart(data.goal_data, data2, data3);
                        unblockUI('#dvScoreChart');
                    }
                });//END $.ajax
            }

            function creat_score_chart(data) {
                /******
                 * Reference: https://www.amcharts.com/docs/v3/tutorials/displaying-chart-guides-in-legend/
                 *******/
                var chart = AmCharts.makeChart("dvScoreChart", {
                    "type": "serial",
                    "theme": "light",
                    "marginRight": 30,
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
                        "title": "Pain Intensity",
                        "valueField": "pain_scale"
                    }, {
                        "bullet": "round",
                        "bulletBorderAlpha": 1,
                        "bulletBorderThickness": 1,
                        "lineThickness": 3,
                        "title": "Pain Bothersomeness",
                        "valueField": "pain_bothersomeness"
                    }, {
                        "bullet": "round",
                        "bulletBorderAlpha": 1,
                        "bulletBorderThickness": 1,
                        "lineThickness": 3,
                        "title": "Pain Intensity During Rest ",
                        "valueField": "pain_intensity_during_rest"
                    }, {
                        "bullet": "round",
                        "bulletBorderAlpha": 1,
                        "bulletBorderThickness": 1,
                        "lineThickness": 3,
                        "title": "Pain Intensity During activity",
                        "valueField": "pain_intensity_during_activity"
                    }
                    ],
                    "chartCursor": {},
                    "categoryField": "visit_date",
                    "categoryAxis": {
                        "minGridDistance ": 0,
                        "maxGridDistance ": 10,
                        "startOnAxis": true,
                        "gridAlpha": 0.07,
                        "labelRotation": 45		// Label Rotation
                    }
                });

                $('#dvScoreChart').closest('.portlet').find('.fullscreen').click(function () {
                    chart.invalidateSize();
                });
            }

            function creat_goal_chart(data, datagoal, guidesData) {
                /******
                 * Reference: https://www.amcharts.com/docs/v3/tutorials/displaying-chart-guides-in-legend/
                 *******/
                //   alert('datagoal[0]'+datagoal[0]["title"]);
                console.log(datagoal);
                var chart = AmCharts.makeChart("dvGoalsChart", {
                    "type": "serial",
                    "theme": "light",
                    "marginRight": 30,
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
                        "marginLeft": 20,
                        "guides": guidesData,
                        /* "listeners": [{
                               "event": "drawn",
                               "method": function() {
                                   var guide2Text = document.querySelector('.tspan');
                                   if (guide2Text) {
                                       guide2Text.setAttribute('y', 20);
                                   }
                               }
                           }]*/
                    }],
                    "graphs":
                    datagoal/*{
                        "bullet": "round",
                        "bulletBorderAlpha": 1,
                        "bulletBorderThickness": 1,
                        "lineThickness": 3,
                        "title": "Goals",
                        "valueField": "baseline_goal"
                    }*/,
                    "chartCursor": {},
                    "categoryField": "visit_date",
                    "categoryAxis": {
                        "startOnAxis": true,
                        "gridAlpha": 0.07,
                        "labelRotation": 45		// Label Rotation
                    }
                });

                $('#dvGoalsChart').closest('.portlet').find('.fullscreen').click(function () {
                    chart.invalidateSize();
                });
            }

            var vstartDate = '';
            var vendDate = '';
            $(document).ready(function () {
                $("#followup_export_btn").on("click", function (e) {
                    e.preventDefault();
                    $('#report_form').attr('action', APP_URL + '/patient/followup-export-excel').submit();
                });
                $("#baseline_export_btn").on("click", function (e) {
                    e.preventDefault();
                    $('#report_form').attr('action', APP_URL + '/patient/baseline-export-excel').submit();
                });
                $("#search_btn").on("click", function () {

                    var ref_this = $('ul li.active');

                    viewReport(ref_this.data('id'));


                });
                $("#clear_btn").on("click", function () {

                    var ref_this = $('ul li.active');
                    clear_form();
                    viewReport(ref_this.data('id'));


                });
                $('input[type=radio][name=view_type]').change(function () {
                    viewReport(2);
                });
                viewReport(1);
                $('.date-picker').datepicker({
                    rtl: App.isRTL(),
                    orientation: "left",
                    autoclose: true,
                    // endDate: '0d',
                    todayHighlight: true
                });
                $('#reportrange').daterangepicker({
                        opens: (App.isRTL() ? 'left' : 'right'),
                        startDate: moment().subtract('days', 29),
                        endDate: moment(),
                        //minDate: '01/01/2012',
                        //maxDate: '12/31/2014',
                        dateLimit: {
                            days: 60
                        },
                        showDropdowns: true,
                        showWeekNumbers: true,
                        timePicker: false,
                        timePickerIncrement: 1,
                        timePicker12Hour: true,
                        ranges: {
                            'Today': [moment(), moment()],
                            'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
                            'Last 7 Days': [moment().subtract('days', 6), moment()],
                            'Last 30 Days': [moment().subtract('days', 29), moment()],
                            'This Month': [moment().startOf('month'), moment().endOf('month')],
                            'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
                        },
                        buttonClasses: ['btn'],
                        applyClass: 'green',
                        cancelClass: 'default',
                        format: 'YYYY-MM-DD',
                        separator: ' to ',
                        locale: {
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
                        $('#reportrange span').html(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
                        vstartDate = start.format('YYYY-MM-DD');
                        vendDate = end.format('YYYY-MM-DD');
                    }
                );
                //Set the initial state of the picker label
                $('#reportrange span').html(moment().subtract('days', 29).format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));
            });
            function viewReport(table) {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                if (table == 1) {//search tab
                    $('#search_tbl').DataTable().destroy();
                    var formData = new FormData();
                    $('#search_tbl').dataTable({

                        'processing': true,
                        'serverSide': true,
                        'ajax': {
                            "type": "post",
                            "url": "{{url('painfile/datalist')}}",

                            "data": {
                                'national_id': $('#national_id').val(),
                                'name': $('#name').val(),
                                'name_a': $('#name_a').val(),
                                'file_status': $('#file_status').val(),
                                'gender': $('#gender').val(),
                                'from': $('#from').val(),
                                'to': $('#to').val(),
                                'start_date': vstartDate,
                                'end_date': vendDate,
                                'table': table,
                                'appointment_loc': $('#baseline_doctor').val(),
                            }
                            ,

                        },

                        'lengthMenu': [50, 100, 200],
                        'columns': [
                            // {data: 'delChk', name: 'delChk'},
                            {data: 'patient_id', name: 'patient_id', width: '5%', orderable: true},
                            {data: 'patient_name', name: 'patient_name', width: '20%'},
                            {data: 'patient_name_a', name: 'patient_name_a', width: '20%'},
                            {data: 'national_id', name: 'national_id', width: '10%'},
                            {data: 'gender_desc', name: 'gender_desc', width: '10%', orderable: true},
                            {data: 'mobile_no', name: 'mobile_no', width: '10%'},
                            {data: 'patient.dob', name: 'patient.dob', width: '10%', orderable: true},
                            {data: 'file_status_desc', name: 'file_status_desc', width: '5%', orderable: true},
                            /*  {data: 'start_date', name: 'start_date', width: '10%', orderable: true},*/
                            {data: 'baseline_date', name: 'baseline_date', width: '10%', orderable: true,},
                            {data: 'treatment_duration', name: 'treatment_duration', width: '10%', orderable: true,},
                            {data: 'baseline_doctor', name: 'baseline_doctor', width: '10%', orderable: true},
                            {data: 'last_followup_date', name: 'last_followup_date', orderable: true, width: '10%'},
                            {data: 'action', name: 'action'},


                        ],

                        "order": [[0, "desc"]], // Order on init. # is the column, starting at 0
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
                } else if (table == 2) {//today appintment tab

                    var view_type = $("input[name='view_type']:checked").val();
                    // alert(view_type);
                    $('#today_appointment_tbl').DataTable().destroy();

                    $('#today_appointment_tbl').dataTable({

                        'processing': true,
                        'serverSide': true,
                        'ajax': {
                            "type": "post",
                            "url": "{{url('appointment/todaylist')}}",

                            "data": {
                                'table': table,
                                'from': $('#from').val(),
                                'to': $('#to').val(),
                                'view_type': view_type,
                                'national_id': $('#national_id').val(),
                                'name': $('#name').val(),
                                'name_a': $('#name_a').val(),
                                'file_status': $('#file_status').val(),
                                'gender': $('#gender').val(),
                                'appointment_loc': $('#baseline_doctor').val(),
                                'current_stage': $('#current_stage').val(),
                            }

                        },

                        "lengthMenu": [[25, 50, -1], [25, 50, "All"]],
                        "pageLength": 100,
                        'columns': [
                            // {data: 'delChk', name: 'delChk'},
                            {data: 'patient_id', name: 'patient_id', width: '5%', orderable: true},
                            {data: 'patient_name', name: 'patient_name', width: '20%'},
                            {data: 'patient_name_a', name: 'patient_name_a', width: '20%'},
                            {data: 'national_id', name: 'national_id', width: '10%'},
                            {data: 'gender_desc', name: 'gender_desc', width: '10%', orderable: true},
                            {data: 'mobile_no', name: 'mobile_no', width: '10%'},
                            {data: 'start_date', name: 'start_date', width: '10%', orderable: true},
                            {data: 'start_time', name: 'start_time', width: '10%', orderable: true},
                            {data: 'attend_date', name: 'attend_date', width: '10%', orderable: true},
                            {data: 'attend_time', name: 'attend_time', width: '10%', orderable: true},
                            {
                                data: 'appointment_type_desc',
                                name: 'appointment_type_desc',
                                width: '5%',
                                orderable: true
                            },
                            {
                                data: 'reason_for_not_attending_desc',
                                name: 'reason_for_not_attending_desc',
                                width: '10%',
                                orderable: true
                            },
                            {data: 'treatment_duration', name: 'treatment_duration', width: '10%', orderable: true},
                            {data: 'baseline_doctor', name: 'baseline_doctor', width: '10%', orderable: true},
                            {data: 'action', name: 'action'},


                        ],
                        "order": [[0, "desc"]], // Order on init. # is the column, starting at 0
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


            }
            function changePainFileStatus(id) {
                swal({
                        title: 'Do you want to change pain file status ?',
                        type: 'warning',
                        allowOutsideClick: true,
                        showConfirmButton: true,
                        showCancelButton: true,
                        confirmButtonClass: 'btn-info',
                        cancelButtonClass: 'btn-danger',
                        closeOnConfirm: true,
                        closeOnCancel: true,
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'Cancel',

                    },
                    function (isConfirm) {
                        if (isConfirm) {

                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });
                            $.ajax({
                                type: "POST",
                                url: '{{url('painFile/change_states')}}',

                                data: {id: id},
                                error: function (xhr, status, error) {

                                },
                                beforeSend: function () {
                                },
                                complete: function () {
                                },
                                success: function (data) {
                                    if (data.success)
                                        viewReport(1);

                                }
                            });//END $.ajax

                        }
                    });


            }
            function delete_patient(id, patient_id) {
                swal({
                        title: 'Are you sure you want to delete this pain file?',
                        type: 'warning',
                        allowOutsideClick: true,
                        showConfirmButton: true,
                        showCancelButton: true,
                        confirmButtonClass: 'btn-info',
                        cancelButtonClass: 'btn-danger',
                        closeOnConfirm: true,
                        closeOnCancel: true,
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'Cancel',

                    },
                    function (isConfirm) {
                        if (isConfirm) {

                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });
                            $.ajax({
                                type: "POST",
                                url: '{{url('painFile/delete_patient')}}',

                                data: {id: id, patient_id: patient_id},
                                error: function (xhr, status, error) {

                                },
                                beforeSend: function () {
                                },
                                complete: function () {
                                },
                                success: function (data) {
                                    if (data.success)
                                        viewReport(1);

                                }
                            });//END $.ajax

                        }
                    });


            }

            function viewPainFile(painFile_id, patient_id, painFile_status) {
                App.blockUI();
                var url = 'painFile/view/' + painFile_id + '/' + patient_id + '/' + painFile_status;
                window.open('{{url('/')}}/' + url, '_self');

            }

            function clear_form() {

                $('#name').val('');
                $('#name_a').val('');
                $('#national_id').val('');
                $('#gender').val('');
                $('#file_status').val('');
                $('#baseline_doctor').val('');
                $('.select2').trigger('change');
                $('#from').val('');
                $('#to').val('');


            }

            function attend(id) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('appointment/attend')}}',

                    data: {
                        id: id,

                    },
                    error: function (xhr, status, error) {

                    },
                    beforeSend: function () {
                    },
                    complete: function () {
                    },
                    success: function (data) {

                        if (data.success) {
                            viewReport(2)
                        } else {
                            alert('error');
                        }
                    }
                });//END $.ajax
            }
            function missed(id,patient_id)
            {
               $('#not_attend_appoint_id').val(id);
               $('#not_attend_patient_id').val(patient_id);

            }
            function save_not_attend(){

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('appointment/not-attend')}}',
                    data: {
                        id: $('#not_attend_appoint_id').val(),
                        patient_id: $('#not_attend_patient_id').val(),
                        reason_for_not_attending: $('#reason_for_not_attending').val(),

                    },
                    success: function (data) {

                        if (data.success) {
                            viewReport(2);
                        } else {
                            alert('error');
                        }
                    }
                });//END $.ajax
            }
        </script>
    @endpush
@stop
