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
                                        <a href="{{url('/project/create')}}" class="btn sbold green">Create new project
                                            <i class="fa fa-plus"></i>
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <table class="table table-striped table-bordered table-hover table-checkable order-column"
                               id="projects_tbl">
                            <thead>
                            <tr>
                                <th> #</th>
                                <th>Name</th>
                                <th>Question</th>
                                <th>Conclusion</th>
                                <th>Consequence</th>
                                <th>Conseq. detail</th>
                                <!--                                <th> Symptoms</th>-->
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
    <div class="modal fade bs-modal-lg" id="patients_Modal" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title font-green">Patients List</h4>
                </div>
                <div class="modal-body">
                    <!-- BEGIN FORM-->
                    {{Form::open(['url'=>url(''),'class'=>'form-horizontal','method'=>"post","id"=>'data_form'])}}
                    <div class="form-body">

                        <table class="table table-striped table-bordered table-hover"
                               id="patient_tbl">
                            <thead>
                            <tr>
                                <th>File No.</th>
                                <th>Name</th>
                                <th>Name Ar</th>
                                <th>ID</th>
                                <th>Sex</th>
                                <th>Mobile No.</th>
                                <th> Baseline Start</th>
                                <th> Doctor</th>
                                <th> Project name</th>
                                <th> Project start</th>
                                <th> Project end</th>
                                <th> Symptoms</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
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
                $('#projects_tbl').dataTable({

                    'processing': true,
                    'serverSide': true,
                    'ajax': '{{url('/project-data')}}',
                    /*id`, `name`, `email`, ``, ``, `gender`, `address`, `mobile`, `image`, `user_type`, ``, `company_id`, `supervisor_id`, ``*/
                    'columns': [
                        {data: 'num', name: 'num'},
                        {data: 'project_name', name: 'project_name'},
                        {data: 'question', name: 'question'},
                        {data: 'conclusion', name: 'conclusion'},
                        {data: 'consequence_desc', name: 'consequence_desc'},
                        {data: 'consequence_detail', name: 'consequence_detail'},
                        //     {data: 'symptoms', name: 'symptoms'},
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

            function deleteProject(id) {
                var x = '';
                var r = confirm('This Action will delete project,are you sure?');
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
                        url: '{{url('project/delete')}}',
                        data: {id: id},

                        success: function (data) {
                            if (data)
                                location.reload();
                            else
                                alert('Not able to delete Project');
                        },
                        error: function (err) {

                            console.log(err);
                        }

                    })
                }
            }

            function get_patient_list(project_id) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });


                $('#patient_tbl').DataTable().destroy();

                $('#patient_tbl').dataTable({

                    'processing': true,
                    'serverSide': true,
                    'ajax': {
                        "type": "post",
                        "url": "{{url('project/patient-list')}}",

                        "data": {

                            'project_id': project_id
                        }

                    },

                    "lengthMenu": [[50, 100, 500, -1], [50, 100, 500, "All"]],
                    "pageLength": 10,
                  /*
                  <th>File No.</th>
                                <th>Name</th>
                                <th>Name Ar</th>
                                <th>ID</th>
                                <th>Sex</th>
                                <th>Mobile No.</th>
                                <th> Baseline Start</th>
                                <th> Doctor</th>
                                <th> Project name</th>
                                <th> Project start</th>
                                <th> Project end</th>
                                <th> Symptoms</th>*/
                    'columns': [
                        // {data: 'delChk', name: 'delChk'},
                        {data: 'id', name: 'id', width: '5%', orderable: true},
                        {data: 'patient_name', name: 'patient_name', width: '5%'},
                        {data: 'patient_name_a', name: 'patient_name_a', width: '5%'},
                        {data: 'national_id', name: 'national_id', width: '10%'},
                        {data: 'gender_desc', name: 'gender_desc', width: '10%', orderable: true},
                        {data: 'mobile_no', name: 'mobile_no', width: '5%'},
                        {data: 'start_date', name: 'start_date', width: '10%', orderable: true},
                        {data: 'created_by_name', name: 'created_by_name', width: '10%', orderable: true},
                        {data: 'project_name', name: 'project_name', width: '10%', orderable: true},
                        {data: 'project_start_date', name: 'project_start_date', width: '10%', orderable: true},
                        {data: 'project_end_date', name: 'project_end_date', width: '10%', orderable: true},
                        {data: 'symptoms', name: 'symptoms', width: '10%', orderable: true},
                    ],
                    "order": [[0, "desc"]], // Order on init. # is the column, starting at 0
                    "dom": "<'row' <'col-md-12'B>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable
                    'buttons': [
                        {
                            'extend': 'excel',
                            'className': 'btn green',
                            'exportOptions': {
                                'columns': [0, 1, 2, 3, 4, 5, 6,7,8,9,10,11]
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

        </script>
    @endpush
@stop
