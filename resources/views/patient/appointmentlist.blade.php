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
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN VALIDATION STATES-->
                <div class="portlet light portlet-fit portlet-form bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-user font-green"></i>
                            <span class="caption-subject font-green sbold uppercase">Search</span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        {{Form::open(['url'=>url('lawsuit/pdf'),'class'=>'form-horizontal','method'=>"post","id"=>"report_form"])}}
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
                                        <label for="multiple" class="control-label col-md-4">Name</label>
                                        <div class="col-md-6">
                                            <input name="name" id="name" type="text" class="form-control"
                                                   placeholder="name">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="multiple" class="control-label col-md-4">Gender</label>
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
                                        <label for="multiple" class="control-label col-md-4">Name Arabic</label>
                                        <div class="col-md-6">
                                            <input name="name_a" id="name_a" type="text" class="form-control"
                                                   placeholder="name arabic">
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">File Date</label>
                                        <div class="col-md-6">
                                            <div class="input-group input-large date-picker input-daterange"
                                                 data-date="" data-date-format="yyyy-mm-dd">
                                                <input type="text" class="form-control" name="from" id="from">
                                                <span class="input-group-addon"> to </span>
                                                <input type="text" class="form-control" name="to" id="to"></div>
                                            <!-- /input-group -->
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">File status</label>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <select id="file_status" name="file_status"
                                                        class="form-control select2">
                                                    <option value="">All</option>
                                                    <option value="17">Open</option>
                                                    <option value="18">Closed</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                          {{--  <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group ">
                                        <label class="control-label col-md-4">Last Visit</label>
                                        <div class="col-md-4">
                                            <div id="reportrange" class="btn default">
                                                <i class="fa fa-calendar"></i> &nbsp;
                                                <span> </span>
                                                <b class="fa fa-angle-down"></b>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                </div>
                            </div>--}}

                        </div>
                        <div class="form-actions ">
                            <div class="row">
                                <div class="col-md-offset-3 col-md-9">
                                    <button type="button" class="btn green" onclick="viewReport();">View</button>
                                    <button type="button" class="btn  grey-salsa btn-outline" onclick="clear_form();">
                                        Clear
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{Form::close()}}
                    </div>
                </div>
                <!-- END SAMPLE FORM PORTLET-->

            </div>

        </div>
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-dark">
                            <i class="icon-settings font-dark"></i>
                            <span class="caption-subject bold uppercase"> {{$page_title}}</span>
                        </div>

                    </div>
                    <div class="portlet-body">
                        <div class="table-toolbar">
                            <div class="col-md-2">

                            </div>
                            <div class="col-md-2">
                            </div>

                        </div>
                        <table class="table table-striped table-bordered table-hover"
                               id="report_tbl">
                            <thead>
                            <tr>
                            <!--  <th>
                                    <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"
                                           style="padding-right:10px">
                                        <input type="checkbox" class="group-checkable"
                                               data-set="#report1_tbl .checkboxes"/>
                                        <span></span>
                                    </label>
                                </th>-->
                                <th>File No.</th>
                                <th>Name</th>
                                <th>Name Ar</th>
                                <th >ID</th>
                                <th >Sex</th>
                                <th >Birth date</th>
                                <th >File status</th>
                                <th>File date</th>
                                <th >BaseLine date</th>
                                <th >Doctor</th>
                                <th >Last Followup date</th>
                                {{-- <th>Control</th>--}}
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
        <link href="{{url('')}}/assets/ global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css"
              rel="stylesheet" type="text/css"/>



        <link href="{{url('/')}}/assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css"/>
        <link href="{{url('/')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css"
              rel="stylesheet" type="text/css"/>
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


        <script>
            var  vstartDate='';
            var vendDate='' ;
            $(document).ready(function () {

                viewReport();
                $('.date-picker').datepicker({
                    rtl: App.isRTL(),
                    orientation: "left",
                    autoclose: true,
                    endDate: '0d',
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
                        vstartDate = start.format('YYYY-MM-DD') ;
                        vendDate = end.format('YYYY-MM-DD');
                    }
                );
                //Set the initial state of the picker label
                $('#reportrange span').html(moment().subtract('days', 29).format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));
            });

            function viewReport() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $('#report_tbl').DataTable().destroy();
                var formData = new FormData();
                formData.append('court_file_no', $('#court_file_no').val());
                formData.append('judge', $('#judge').val());

                //  var form = $('#report_form');

                $('#report_tbl').dataTable({

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
                        }
                        ,

                    },


                    'columns': [
                        // {data: 'delChk', name: 'delChk'},
                        {data: 'patient_id', name: 'patient_id',width:'5%',orderable: true},
                        {data: 'patient_name', name: 'patient_name',width:'20%'},
                        {data: 'patient_name_a', name: 'patient_name_a',width:'20%'},
                        {data: 'national_id', name: 'national_id',width:'10%'},
                        {data: 'gender_desc', name: 'gender_desc',width:'10%',orderable: true},
                        {data: 'patient.dob', name: 'patient.dob',width:'10%',orderable: true},
                        {data: 'file_status_desc', name: 'file_status_desc',width:'5%',orderable: true},
                        {data: 'start_date', name: 'start_date',width:'10%',orderable: true},
                        {data: 'baseline_date', name: 'baseline_date',width:'10%',orderable: true,},
                        {data: 'baseline_doctor', name: 'baseline_doctor',width:'10%',orderable: true},
                        {data: 'last_followup_date', name: 'last_followup_date',orderable: true,width:'10%'},
                        /*  {data: 'action', name: 'action'},*/


                    ],
                   "order": [[ 0, "desc" ]], // Order on init. # is the column, starting at 0
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


            function deletePainFile(id) {
                swal({
                        title: 'Do you want to delete the pain file ?',
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
                                url: '{{url('painFile/delete-painfile')}}',

                                data: {},
                                error: function (xhr, status, error) {

                                },
                                beforeSend: function () {
                                },
                                complete: function () {
                                },
                                success: function (data) {

                                    viewReport();
                                }
                            });//END $.ajax

                        }
                    });


            }

            $('#btn-delete').on('click', function (e) {
                    e.preventDefault();
                    var x = '';

                    var idArray = [];
                    var table = $('#report_tbl').DataTable();
                    table.$('input[type="checkbox"]').each(function () {
                        if (this.checked) {
                            idArray.push($(this).attr('data-id'));
                        }

                    });
                    //   alert('checked :' +idArray);
                    if (idArray.length > 0) {
                        swal({
                                title: 'Do you want to delete the selected files ?',
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
                                        url: '{{url('painFile/delete-selected-painfile')}}',

                                        data: {},
                                        error: function (xhr, status, error) {

                                        },
                                        beforeSend: function () {
                                        },
                                        complete: function () {
                                        },
                                        success: function (data) {

                                            viewReport();
                                        }
                                    });//END $.ajax

                                }
                            });
                    }
                }
            );


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
                $('.select2').trigger('change');
                $('#from').val('');
                $('#to').val('');
                viewReport();


            }

            /*   $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
                   console.log(picker.startDate.format('YYYY-MM-DD'));
                   console.log(picker.endDate.format('YYYY-MM-DD'));
                   alert('start : '+picker.startDate.format('YYYY-MM-DD'));
                   alert('endDate : '+picker.endDate.format('YYYY-MM-DD'));
               });*/
        </script>
    @endpush
@stop
