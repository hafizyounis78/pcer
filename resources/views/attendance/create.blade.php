@extends('admin.layout.index')
@section('content')
    <div class="page-content">
        <h1 class="page-title"> {{$title}}
            <small>{{$page_title}}</small>
        </h1>
        <div class="page-bar">
            @include('admin.layout.breadcrumb')

        </div>
        <meta name="csrf-token" content="{{ csrf_token()}}">
        <div class="row">
            <div class="col-md-12 ">
                <!-- BEGIN SAMPLE FORM PORTLET-->
                <div class="portlet light ">
                    <div class="portlet-title">
                        <div class="caption font-red-sunglo">
                            <i class="icon-settings font-red-sunglo"></i>
                            <span class="caption-subject bold uppercase">{{$page_title}}</span>
                        </div>
                        <div class="actions">

                        </div>
                    </div>
                    <div class="portlet-body form">
                        {{Form::open(['url'=>url('attendance'),'class'=>'form-horizontal','role'=>'form','method'=>"post","id"=>"attendance_form"])}}
                        <div class="form-body">
                            <div class="alert alert-danger display-hide">
                                <button class="close" data-close="alert"></button>
                                يوجد خطأ في ادخال البيانات,يرجى فحص القيم المدخلة
                            </div>
                            <div class="alert alert-success display-hide">
                                <button class="close" data-close="alert"></button>
                                تمت عملية الحفظ بنجاح!
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">ID.</label>
                                <div class="col-md-4">


                                    <input id="idno" type="text" class="form-control" placeholder="Enter text">

                                    {{--<span class="help-block"> A block of help text. </span>--}}

                                </div>
                                <div class="col-md-4">
                                    <a id="modalbtn" class="btn red btn-outline sbold hide" data-toggle="modal"
                                       href="#movement_type">Save</a>
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="form-actions left">
                        <div class="row">
                            {{-- <div class="col-md-9">
                                 <button type="submit" class="btn  green">Save</button>
                                 <a href="{{url('/attendance')}}"
                                    class="btn  grey-salsa btn-outline">Cancel</a>
                             </div>--}}
                        </div>
                    </div>
                    {{Form::close()}}

                </div>
            </div>
            <!-- END SAMPLE FORM PORTLET-->

        </div>
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN SAMPLE TABLE PORTLET-->
                <div class="portlet light ">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-settings font-red"></i>
                            <span class="caption-subject font-red sbold uppercase">Movement Table</span>
                        </div>

                    </div>
                    <div class="portlet-body">
                        <div class="table-scrollable">
                            <table class="table table-hover table-light" id="attendance_tbl">
                                <thead>
                                <tr>
                                    <th> #</th>
                                    <th> Name</th>
                                    <th> Type</th>
                                    <th> Date</th>
                                    <th> Time In</th>
                                    <th> Time Out</th>
                                    <th> Total Houres</th>

                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- END SAMPLE TABLE PORTLET-->
            </div>
        </div>
        <div id="movement_type" class="modal fade" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h4 class="modal-title">ePCER</h4>
                    </div>
                    <div class="modal-body">
                        <div class="scroller" style="height:300px" data-always-visible="1" data-rail-visible1="1">
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Movement Type</label>
                                    <div class="col-md-9">
                                        <div class="mt-radio-list">
                                            <label class="mt-radio mt-radio-outline">
                                                <input type="radio" name="action_type" id="action_type1"
                                                       value="1">In
                                                <span></span>
                                            </label>
                                            <label class="mt-radio mt-radio-outline">
                                                <input type="radio" name="action_type" id="action_type2"
                                                       value="2">Out
                                                <span></span>
                                            </label>
                                            <label class="mt-radio mt-radio-outline">
                                                <input type="radio" name="action_type" id="action_type3"
                                                       value="3">Work permission out
                                                <span></span>
                                            </label>
                                            <label class="mt-radio mt-radio-outline">
                                                <input type="radio" name="action_type" id="action_type4"
                                                       value="4">Work permission back
                                                <span></span>
                                            </label>
                                            <label class="mt-radio mt-radio-outline">
                                                <input type="radio" name="action_type" id="action_type5"
                                                       value="5">Private permission out
                                                <span></span>
                                            </label>
                                            <label class="mt-radio mt-radio-outline">
                                                <input type="radio" name="action_type" id="action_type6"
                                                       value="6">Private permission back
                                                <span></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn dark btn-outline">Close</button>
                        <button type="button" data-dismiss="modal" class="btn green" onclick="save_movement();">Save
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @push('css')



        <link href="{{url('/')}}/assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css"/>
        <link href="{{url('/')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css"
              rel="stylesheet" type="text/css"/>
    @endpush
    @push('js')
        <script src="{{url('/')}}/assets/global/scripts/datatable.js" type="text/javascript"></script>
        <script src="{{url('/')}}/assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
        <script src="{{url('/')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js"
                type="text/javascript"></script>
        <script type="text/javascript" language="javascript">
            function convertEnterToTab(e) {
                if ($('#idno').val() != '')

                    if (e.keyCode == 13) {

                        e.keyCode = 9;
                        //trigger second button
                        $("#modalbtn").click();
                    }
            }

            //   alert($('#idno').val());


            document.onkeydown = convertEnterToTab;

            function save_movement() {
                var idno = $('#idno').val();
                var action_type = $('input[name="action_type"]:checked').val();
                // alert(action_type);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('attendance/insert-attendance')}}',

                    data: {
                        idno: idno,
                        action_type: action_type
                    },
                    success: function (data) {
                        //   unblockUI('tbtreatment_goals');


                        alert(data.message);
                        view_Report();
                        $('#idno').val('');
                    }
                });//END $.ajax
            }

            function view_Report() {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $('#attendance_tbl').DataTable().destroy();

                //  var form = $('#report_form');
                // alert(attend_date)
                $('#attendance_tbl').dataTable({

                    'processing': true,
                    'serverSide': true,
                    buttons: [
                        {
                            extend: 'excel',
                            className: 'btn yellow btn-outline ',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6, 7]
                            }
                        }

                    ],
                    "dom": "<'row' <'col-md-12'B>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable

                    'ajax': {
                        "type": "post",
                      //  "url": "current-attendance-data",
                        "url": '{{url('current-attendance-data')}}',
                        "data": {}
                        ,

                    },


                    'columns': [
                        {data: 'num', name: 'num'},
                        //   {data: 'user_id', name: 'user_id'},
                        {data: 'user_name', name: 'user_name'},
                        {data: 'action_desc', name: 'action_desc'},
                        {data: 'action_date', name: 'action_date'},
                        {data: 'start_time', name: 'start_time'},
                        {data: 'end_time', name: 'end_time'},
                        {data: 'total_hour', name: 'total_hour'},

                    ],
                    "language": {
                        "aria": {
                            "sortAscending": ": activate to sort column ascending",
                            "sortDescending": ": activate to sort column descending"
                        },
                        "emptyTable": "لايوجد بيانات في الجدول للعرض",
                        "info": "عرض _START_ الى  _END_ من _TOTAL_ سجلات",
                        "infoEmpty": "No records found",
                        "infoFiltered": "(filtered1 from _MAX_ total records)",
                        "lengthMenu": "عرض _MENU_",
                        "search": "بحث:",
                        "zeroRecords": "No matching records found",
                        "paginate": {
                            "previous": "Prev",
                            "next": "Next",
                            "last": "Last",
                            "first": "First"
                        }
                    },

                })

                $('.buttons-excel').addClass('hidden');
                $('.button-excel2').click(function () {
                    $('.buttons-excel').click()
                });

            }

            $(document).ready(function () {
                //  $.noConflict();
                view_Report();
            });
        </script>
    @endpush;

@stop
