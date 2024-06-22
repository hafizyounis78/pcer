@extends('admin.layout.index')
@section('content')
    <div class="page-content" xmlns="http://www.w3.org/1999/html">
        <meta name="csrf-token" content="{{ csrf_token()}}">
        <h1 class="page-title"> {{$title}}
            <small>{{$location_title}}</small>
        </h1>
        <div class="page-bar">
            @include('admin.layout.breadcrumb')

        </div>
        @if($errors->any())
            <div class="alert alert-danger">
                <button class="close" data-close="alert"></button>
                <h4>{{$errors->first()}}</h4>
            </div>
        @endif
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-gift"></i>Filters
                </div>
                <!--<div class="tools">
                    <a href="javascript:;" class="collapse"> </a>

                </div>-->
            </div>
            <div class="portlet-body form">

                {{Form::open(['url'=>'','class'=>'form-horizontal','method'=>"post","id"=>"report_form"])}}
                <div class="form-body">
                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-md-4">Date </label>
                                <div class="col-md-4">
                                    <div class="input-group input-large date-picker input-daterange"
                                         data-date="10/11/2012" data-date-format="yyyy/mm/dd">
                                        <input type="text" class="form-control" name="fromdate"
                                               id="fromdate">
                                        <span class="input-group-addon"> To </span>
                                        <input type="text" class="form-control" name="todate" id="todate">
                                    </div>
                                    <!-- /input-group -->
                                    {{--  <span class="help-block"> Select date range </span>--}}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-md-4">Drug</label>
                                <div class="col-md-8">

                                    <select id="drug_id" name="drug_id" class="form-control select2">
                                        <option value="0">Select Drug</option>
                                        <?php
                                        foreach ($drug_list as $raw)
                                            echo '<option value="' . $raw->id . '">' . $raw->item_scientific_name . '</option>';
                                        ?>
                                    </select>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{--  <label class="control-label col-md-4">Concentration </label>--}}
                                <div class="col-md-8">
                                    <input type="text" name="concentration" id="concentration"
                                           class="form-control input-small" placeholder="Concentration"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-md-4">Report</label>
                                <div class="col-md-8">
                                    <select id="report_id" class="form-control select2"
                                            name="report_id">
                                        <option value="">Select..</option>
                                        <option value="1">Medicine Report-Follow Up</option>
                                        <option value="2">Medicine Report-Base Line</option>
                                        <option value="5">Medicine Report</option>
                                        <option value="3">Patients reports</option>
                                        <option value="4">Patients pain intensities report</option>
                                        <option value="6">Pain scale</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-md-4">Order Status</label>
                                <div class="col-md-8">
                                    <select id="order_status" class="form-control select2"
                                            name="order_status">
                                        <option value="">All..</option>
                                        <option value="0">Not Confirmed Order</option>
                                        <option value="1">Confirmed Orders</option>

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 ">
                            <button type="button" class="btn btn-warning" id="export_pdf_btn" >
                                View
                            </button>
                            <!--                            <button type="button" class="btn btn-success button-excel2" id="export_excel_btn">Excel</button>-->
                            <button class="btn btn-danger " type="button" onclick="clearForm()">Clear</button>
                        </div>
                    </div>


                </div>
                {{Form::close()}}

            </div>

        </div>
        <div class="portlet light bordered" id="dv_report_tb">
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover  order-column" id="order_tbl">
                    <thead>
                    <tr>
                        <th> #</th>
                        <th> Item code</th>
                        <th> Item name.</th>
                        <th> Order date</th>
                        <th> Order type</th>
                        <th> Order status</th>
                        <th> Quantity</th>
                        <th> Cost</th>
                        <th> Pain file</th>
                        <th> National ID.</th>
                        <th> Name</th>
                        <th> Name a</th>

                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
        <div class="portlet light bordered" id="dv_pain_intensity_tb">
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover  order-column" id="pain_intensity_tbl">
                    <thead>
                    <tr>
                        <th> #</th>
                        <th> Pain File</th>
                        <th> Base Line Pain Scale</th>
                        <th> Last Followup Pain Scale</th>
                        <th> Pain scale %</th>
                        <th> Base Line Date</th>
                        <th> Last Followup Date</th>

                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
        @push('css')
            <style>

                .datepicker {
                    width: 15%;

                }

                .select2 {
                    font-size: 12px;
                    height: 37px;
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
            <link href="{{url('/')}}/assets/global/plugins/datatables/datatables.min.css" rel="stylesheet"
                  type="text/css"/>
            <link href="{{url('/')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css"
                  rel="stylesheet" type="text/css"/>
            <style>

                .hselect {
                    height: 41px !important;
                }
                .buttons-excel {
                    margin-top: -56px !important;
                }


            </style>

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
            <script src="{{url('/')}}/assets/global/plugins/datatables/datatables.min.js"
                    type="text/javascript"></script>
            <script src="{{url('/')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js"
                    type="text/javascript"></script>
            <script>
                $(document).ready(function () {
                    $('#dv_pain_intensity_tb').hide();
                    $('.date-picker').datepicker({
                        rtl: App.isRTL(),
                        orientation: "left",
                        autoclose: true,
                        endDate: '0d',
                        todayHighlight: true
                    });
                    $(".select2, .select2-multiple").select2({
                        width: null
                    });
                    /*$("#export_excel_btn").on("click", function (e) {

                        e.preventDefault();
                        $('#report_form').attr('action', APP_URL + '/followup/treatment-export-excel').submit();
                    });*/
                    $("#export_pdf_btn").on("click", function (e) {
                        if ($('#report_id').val() == 3) {
                            e.preventDefault();
                            $('#report_form').attr('action', APP_URL + '/run_rep').submit();
                        } else
                            view_report()
                    });
                });

                function view_report() {

                    var fromdate = $('#fromdate').val();
                    var todate = $('#todate').val();
                    var drug_id = $('#drug_id').val();
                    var order_status = $('#order_status').val();
                    //var national_id = $('#national_id').val();
                    if ($('#report_id').val() == 3) {
                        e.preventDefault();
                        $('#report_form').attr('action', APP_URL + '/run_rep').submit();
                    }
                    else if ($('#report_id').val() == 6) {
                        $('#dv_pain_intensity_tb').show();
                        $('#dv_report_tb').hide();
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });

                        $('#pain_intensity_tbl').DataTable().destroy();
                        $('#pain_intensity_tbl').dataTable({

                            'processing': true,
                            'serverSide': true,
                            'buttons': [
                                {
                                    'extend': 'excel',
                                    'className': 'btn btn-success green',
                                    'exportOptions': {
                                        'columns': [0, 1, 2, 3, 4, 5, 6, 7,8,9,10]
                                    }
                                }

                            ],
                            "dom": "<'row' <'col-md-12'B>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable
                            "lengthMenu": [[100, 500, 1000,5000, -1], [100, 500, 1000,5000, "All"]],
                            'ajax': {
                                "type": "post",
                                "url": "report/view_report",
                                "data": {
                                    'fromdate': fromdate,
                                    'todate': todate,
                                    'drug_id': drug_id,
                                    'order_status': order_status,
                                    'report_id': $('#report_id').val(),
                                }
                            },

                            'columns': [
                                {data: 'num', name: 'num'},
                                {data: 'pain_file_id', name: 'pain_file_id'},
                                {data: 'baseline_pain_score', name: 'baseline_pain_score'},
                                {data: 'last_followup_pain_score', name: 'last_followup_pain_score'},
                                {data: 'pain_scale_prc', name: 'pain_scale_prc'},
                                {data: 'baseline_create_date', name: 'baseline_create_date'},
                                {data: 'last_followup_create_date', name: 'last_followup_create_date'},

                            ],
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
                        /*  $('.buttons-excel').addClass('hidden');
                          $('.button-excel2').click(function () {
                              $('.buttons-excel').click()
                          });*/
                    }
                    else {
                        $('#dv_pain_intensity_tb').hide();
                        $('#dv_report_tb').show();
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });

                        $('#order_tbl').DataTable().destroy();
                        $('#order_tbl').dataTable({

                            'processing': true,
                            'serverSide': true,
                            'buttons': [
                                {
                                    'extend': 'excel',
                                    'className': 'btn btn-success green',
                                    'exportOptions': {
                                        'columns': [0, 1, 2, 3, 4, 5, 6, 7,8,9,10]
                                    }
                                }

                            ],
                            "dom": "<'row' <'col-md-12'B>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable
                            "lengthMenu": [[100, 500, 1000,5000, -1], [100, 500, 1000,5000, "All"]],
                            'ajax': {
                                "type": "post",
                                "url": "report/view_report",
                                "data": {
                                    'fromdate': fromdate,
                                    'todate': todate,
                                    'drug_id': drug_id,
                                    'order_status': order_status,
                                    'report_id': $('#report_id').val(),
                                }
                            },
                            /*   <th> #</th>
                            <th> Item code</th>
                            <th> Item name.</th>
                            <th> Order date</th>
                            <th> Order type</th>
                            <th> Order status</th>
                            <th> Quantity</th>
                            <th> Pain file</th>
                            <th> National ID.</th>
                            <th> Name</th>
                            <th> Name a</th>*/
                            'columns': [
                                {data: 'num', name: 'num'},
                                {data: 'drug_id', name: 'drug_id'},
                                {data: 'drug_name', name: 'drug_name'},
                                {data: 'date', name: 'date'},
                                {data: 'visit_type_name', name: 'visit_type_name'},
                                {data: 'order_status', name: 'order_status'},
                                {data: 'quantity', name: 'quantity'},
                                {data: 'drug_cost', name: 'drug_cost'},
                                {data: 'pain_file_id', name: 'pain_file_id'},
                                {data: 'national_id', name: 'national_id'},
                                {data: 'patient_name', name: 'patient_name'},
                                {data: 'patient_name_en', name: 'patient_name_en'},

                            ],
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
                        /*  $('.buttons-excel').addClass('hidden');
                          $('.button-excel2').click(function () {
                              $('.buttons-excel').click()
                          });*/
                    }
                }

                function run_rep(id) {

                    var fromdate = $('#fromdate').val();
                    var todate = $('#todate').val();
                    var drug_id = $('#drug_id').val();
                    var concentration = $('#concentration').val();

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: "POST",
                        url: '{{url('report/view_report')}}',
                        data: {
                            id: id,
                            fromdate: fromdate,
                            todate: todate,
                            drug_id: drug_id,
                            concentration: concentration
                        },

                        success: function (data) {
                            if (data.success) {
                                // window.open(url('rep1'))

                            }

                        }


                    });
                }

                function clearForm() {
                    $('#drug_id').val(0);
                    $('#report_id').val('');
                    $('#concentration').val('');
                    $('#confirmed_id').val('');
                    $('.select2').trigger('change');
                    $('#fromdate').datepicker('setDate', '');
                    $('#todate').datepicker('setDate', '');
                }


            </script>
    @endpush
@stop
