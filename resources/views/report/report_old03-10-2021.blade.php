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

                {{Form::open(['url'=>url('run_rep'),'class'=>'form-horizontal','method'=>"post","id"=>"report_form"])}}
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
                                            echo '<option value="' . $raw->id . '">' . $raw->name . '</option>';
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
                                        <option value="3">Patients reports</option>
                                        <option value="4">Patients pain intensities report </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 ">
                            <button type="submit" class="btn btn-warning" id="export_pdf_btn">View</button>
                            <button type="button" class="btn btn-success" id="export_excel_btn">Excel</button>
                            <button class="btn btn-danger " type="button" onclick="clearForm()">Clear</button>
                        </div>
                    </div>


                </div>
                {{Form::close()}}

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

            <style>

                .hselect {
                    height: 41px !important;
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

            <script>
                $(document).ready(function () {

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
                    $("#export_excel_btn").on("click", function (e) {

                        e.preventDefault();
                        $('#report_form').attr('action', APP_URL + '/followup/treatment-export-excel').submit();
                    });
                    $("#export_pdf_btn").on("click", function (e) {

                        e.preventDefault();
                        $('#report_form').attr('action', APP_URL + '/run_rep').submit();
                    });
                });


                function run_rep(id) {

                    var fromdate = $('#fromdate').val();
                    var todate = $('#todate').val();
                    var drug_id = $('#drug_id').val();
                    var concentration =$('#concentration').val();
                    alert(drug_id);
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: "POST",
                        url: '{{url('/run_rep')}}',
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
                    $('.select2').trigger('change');
                    $('#fromdate').datepicker('setDate', '');
                    $('#todate').datepicker('setDate', '');
                }


            </script>
    @endpush
@stop
