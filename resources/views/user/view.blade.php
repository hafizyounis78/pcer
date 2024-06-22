@extends('admin.layout.index')
@section('content')
    <div class="page-content">
        <meta name="csrf-token" content="{{ csrf_token()}}">
        <h1 class="page-title"> {{$title}}
            <small>{{$page_title}}</small>
        </h1>
        <div class="page-bar">
            @include('admin.layout.breadcrumb')

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
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="btn-group">
                                        <a href="{{url('/user/create')}}" class="btn sbold green">Create new user
                                            <i class="fa fa-plus"></i>
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <table class="table table-striped table-bordered table-hover table-checkable order-column"
                               id="users_tbl">
                            <thead>
                            <tr>
                                <th> #</th>
                                <th>Name</th>
                                <th>email</th>
                                <th>National Id.</th>
                                <th>Job</th>
                                <th> Address</th>
                                <th> Mobile</th>
                                <th> Capacity</th>
                                <th> Color</th>
                                <th> Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <!-- END EXAMPLE TABLE PORTLET-->
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
        <script>
            $(document).ready(function () {
                $('#users_tbl').dataTable({

                    'processing': true,
                    'serverSide': true,
                    'ajax': '{{url('/user-data')}}',
                    /*id`, `name`, `email`, ``, ``, `gender`, `address`, `mobile`, `image`, `user_type`, ``, `company_id`, `supervisor_id`, ``*/
                    'columns': [
                        {data: 'num', name: 'num'},
                        {data: 'name', name: 'name'},
                        {data: 'email', name: 'email'},
                        {data: 'national_id', name: 'national_id'},
                        {data: 'job_title', name: 'job_title'},
                        {data: 'address', name: 'address'},
                        {data: 'mobile', name: 'mobile'},
                        {data: 'daily_capacity', name: 'daily_capacity'},
                        {
                            data: 'user_color', name: 'user_color',
                            render: function (data, type, full, meta) {
                                return '<div style="background-color:' + data + '">&nbsp;</div>';

                            }
                        },
                        {data: 'active', name: 'active'},
                        {data: 'action', name: 'action'},],
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
            });

            function deleteUser(id) {
                var x = '';
                var r = confirm('This Action will delete user,are you sure?');
                var currentToken = $('meta[name="csrf-token"]').attr('content');


                if (r == true) {
                    x = 1;
                } else {
                    x = 0;
                }
                if (x == 1) {

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        type: "POST",
                        url: '{{url('user/delete')}}',
                        data: {id: id},

                        success: function (data) {
                            location.reload();
                        },
                        error: function (err) {

                            console.log(err);
                        }

                    })
                }
            }

            function updateUserstatus(id) {
                var isactive = '';
                var newclass = '';
                var itemid = '#i' + id;
                var active_class = 'fa fa-user font-green';
                var unactive_class = 'fa fa-user font-red-sunglo';


                if ($(itemid).attr("class") == active_class) {

                    isactive = 0;
                    newclass = unactive_class;
                } else {
                    isactive = 1;
                    newclass = active_class;
                }
                if (isactive == 0) {
                    var x = '';
                    var r = confirm('This action will deactivate user,are you sure?');
                } else if (isactive == 1) {
                    var x = '';
                    var r = confirm('This action will activate user,are you sure?');
                }
                if (r == true) {
                    x = 1;
                } else {
                    x = 0;
                }
                if (x == 1) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: '{{url('user/activate')}}',
                        type: "POST",
                        data: {id: id, isactive: isactive},
                        error: function (xhr, status, error) {

                        },
                        success: function (data) {
                            if (data.success) {


                                $('#i' + id).removeClass($('#i' + id).attr("class")).addClass(newclass);
                            }
                        }
                    });//END $.ajax
                }

            }
        </script>
    @endpush
@stop
