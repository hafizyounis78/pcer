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
    <!-- BEGIN VALIDATION STATES-->
        <div class="portlet light portlet-fit portlet-form bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-star font-green"></i>
                    <span class="caption-subject font-green sbold uppercase">Table</span>
                </div>
            </div>
            <div class="portlet-body">
                <!-- BEGIN FORM-->
                {{Form::open(['url'=>url('setting/table-save'),'class'=>'form-horizontal','method'=>"post","id"=>"setting_form"])}}
                <input type="hidden" value="{{''}}" id="hdn_table_id" name="hdn_table_id">
                <input type="hidden" value="{{''}}" id="hdn_id" name="hdn_id">
                <div class="form-body">
                    <div class="alert alert-danger display-hide">
                        <button class="close" data-close="alert"></button>
                        You have some form errors. Please check below.
                    </div>
                    <div class="alert alert-success display-hide">
                        <button class="close" data-close="alert"></button>
                        Your form validation is successful!
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Name</label>
                                <div class="col-md-9 select2-bootstrap-prepend">

                                    <select id="table_id" name="table_id" class="form-control select2"
                                            onchange="reloadTable()">
                                        <option value="">select...</option>
                                        <option value="1">Drugs</option>
                                        <option value="2">Diagnosis</option>
                                        <option value="3">Pain Locations</option>
                                        <option value="4">PCS options</option>
                                        <option value="5">PHQ options</option>
                                    </select>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {{--  <label class="col-md-3 control-label">Name</label>--}}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <input name="name" id="name" type="text" class="form-control "
                                               placeholder="Name" value=""></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <button type="submit" class="btn green">Save</button>
                                <button type="button" onclick="clearForm()" class="btn  red-intense">New</button>
                            </div>
                        </div>
                    </div>
                    <br>
                    <br>

                </div>

            {{-- </form>--}}
            {{Form::close()}}
            <!-- END FORM-->
            </div>
        </div>

        <div class="portlet light portlet-fit portlet-form bordered">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-settings font-dark"></i>
                    <span class="caption-subject  sbold uppercase">Table Options </span>
                </div>

            </div>
            <div class="portlet-body">
                <div class="form-body">
                    <table class="table table-striped table-bordered table-hover  order-column"
                           id="setting_tbl">
                        <thead>
                        <tr>
                            <th>id</th>
                            <th> Name</th>
                            <th>Control</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @push('css')
        <link href="{{url('')}}/assets/global/plugins/select2/css/select2.min.css" rel="stylesheet"
              type="text/css"/>
        <link href="{{url('')}}/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet"
              type="text/css"/>
        <link href="{{url('/')}}/assets/global/plugins/datatables/datatables.min.css" rel="stylesheet"
              type="text/css"/>
    @endpush
    @push('js')
        <script src="{{url('')}}/assets/global/plugins/select2/js/select2.full.min.js"
                type="text/javascript"></script>
        <script src="{{url('')}}/assets/global/plugins/jquery-validation/js/jquery.validate.min.js"
                type="text/javascript"></script>
        <script src="{{url('/')}}/assets/global/scripts/datatable.js" type="text/javascript"></script>
        <script src="{{url('/')}}/assets/global/plugins/datatables/datatables.min.js"
                type="text/javascript"></script>
        <script src="{{url('/')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js"
                type="text/javascript"></script>
        <script>
            $(document).ready(function () {
                reloadTable();
            });

            function reloadTable() {
                var table_id = $('#table_id').val();
                $('#hdn_table_id').val(table_id);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $('#setting_tbl').DataTable().clear();
                $('#setting_tbl').DataTable().destroy();
                $.fn.dataTable.ext.errMode = 'none';
                $('#setting_tbl').on('error.dt', function (e, settings, techNote, message) {
                    console.log('An error has been reported by DataTables: ', message);
                });
            if(table_id!='') {
                $('#setting_tbl').dataTable({
                    'processing': true,
                    'serverSide': true,
                    'ajax': {
                        "type": "post",
                        "url": '{{url('setting/table-data')}}',
                        "data": {'table_id': table_id},
                    },
                    'columns': [

                        {data: 'id', name: 'id'},
                        {data: 'name', name: 'name'},
                        {data: 'action', name: 'action'}
                    ],
                    "language": {
                        "aria": {
                            "sortAscending": ": activate to sort column ascending",
                            "sortDescending": ": activate to sort column descending"
                        },
                        "emptyTable": "No data available in table",
                        "info": "Showing _START_ to _END_ of _TOTAL_ records",
                        "infoEmpty": "No records found",
                        "infoFiltered": "(filtered1 from _MAX_ total records)",
                        "lengthMenu": "Show _MENU_",
                        "search": "Search:",
                        "zeroRecords": "No matching records found",
                        "paginate": {
                            "previous": "Prev",
                            "next": "Next",
                            "last": "Last",
                            "first": "First"
                        }
                    }

                })
            }
            }


            var settingFormValidation = function () {

                // basic validation
                var handleValidation1 = function () {
                    // for more info visit the official plugin documentation:
                    // http://docs.jquery.com/Plugins/Validation

                    var form1 = $('#setting_form');
                    var error1 = $('.alert-danger', form1);
                    var success1 = $('.alert-success', form1);
                    // Unique email


                    form1.validate({
                        errorElement: 'span', //default input error message container
                        errorClass: 'help-block help-block-error', // default input error message class
                        focusInvalid: false, // do not focus the last invalid input
                        ignore: "",  // validate all fields including form hidden input

                        rules: {

                            name: {
                                required: true,

                            },
                        },

                        messages: { // custom messages for radio buttons and checkboxes

                        },

                        invalidHandler: function (event, validator) { //display error alert on form submit
                            success1.hide();
                            error1.show();
                            App.scrollTo(error1, -200);
                        },

                        errorPlacement: function (error, element) { // render error placement for each input type
                            var cont = $(element).parent('.input-group');
                            if (cont) {
                                cont.after(error);
                            } else {
                                element.after(error);
                            }
                        },

                        highlight: function (element) { // hightlight error inputs

                            $(element)
                                .closest('.form-group').addClass('has-error'); // set error class to the control group
                        },

                        unhighlight: function (element) { // revert the change done by hightlight
                            $(element)
                                .closest('.form-group').removeClass('has-error'); // set error class to the control group
                        },

                        success: function (label) {
                            label
                                .closest('.form-group').removeClass('has-error'); // set success class to the control group
                        },

                        submitHandler: function (form) {

                            settingSubmit();


                        }
                    });


                };


                return {
                    //main function to initiate the module
                    init: function () {


                        handleValidation1();


                    }

                };

            }();


            settingFormValidation.init();

            function settingSubmit() {

                var form1 = $('#setting_form');
                var error = $('.alert-danger', form1);
                var success = $('.alert-success', form1);

                var action = $('#setting_form').attr('action');

                var formData = new FormData($('#setting_form')[0]);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                        url: action,
                        type: 'POST',
                        dataType: 'json',
                        data: formData,

                        processData:
                            false,
                        contentType:
                            false,
                        success:

                            function (data) {
                                if (data.success) {

                                    success.show();
                                    error.hide();
                                    App.scrollTo(success, -200);
                                    success.fadeOut(2000);
                                    reloadTable();
                                    clearForm();
                                } else {
                                    success.hide();
                                    error.show();
                                    App.scrollTo(error, -200);
                                    error.fadeOut(2000);
                                }


                            }

                        ,
                        error: function (err) {

                            console.log(err);
                        }

                    }
                )
                //   });
            }

            function settingDelete(id,table_id) {

                var form1 = $('#setting_form');
                var error = $('.alert-danger', form1);
                var success = $('.alert-success', form1);
                var x = '';
                var r = confirm('This value will be permanently deleted,Are you sure?');
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
                            url: '{{url('setting/table-delete')}}',
                            data: {id: id,table_id:table_id},

                            success:

                                function (data) {
                                    if (data.success) {

                                        success.show();
                                        error.hide();
                                        App.scrollTo(success, -200);
                                        success.fadeOut(2000);
                                        reloadTable();
                                        clearForm();

                                    } else {
                                        success.hide();
                                        error.show();
                                        App.scrollTo(error, -200);
                                        error.fadeOut(2000);
                                    }


                                }

                            ,
                            error: function (err) {

                                console.log(err);
                            }
                            /*  error:function(err){
                                  console.log(err);

                              }*/
                        })
                }
                //   });
            }

            function fillForm(id, table_id, name) {
                $('#hdn_id').val(id);
                $('#hdn_table_id').val(table_id);
                $('#name').val(name);
                var itemUp = $('#name');
                App.scrollTo(itemUp, -200);


            }

            function clearForm() {
                //  $('#hdn_table_id').val('');
                $('#hdn_id').val('');
                $('#name').val('');
            }
            function activeLookup(id,table_id) {

                var form1 = $('#setting_form');
                var error = $('.alert-danger', form1);
                var success = $('.alert-success', form1);
                var x = '';
                var r = confirm('Are you sure to active/deactive this option?');
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
                            url: '{{url('/setting/toggle-active')}}',
                            data: {id: id,table_id:table_id},

                            success:function (data) {
                                if (data.success) {
                                    if($('#btnactive'+id).hasClass('yellow-lemon')) {
                                        $('#btnactive' + id).addClass('grey-silver');
                                        $('#btnactive'+id).removeClass('yellow-lemon');
                                    }
                                    else {
                                        $('#btnactive' + id).addClass('yellow-lemon');
                                        $('#btnactive' + id).removeClass('grey-silver');
                                    }

                                    success.show();
                                    error.hide();
                                    //  App.scrollTo(success, -200);
                                    success.fadeOut(2000);


                                }
                                else {
                                    success.hide();
                                    error.show();
                                    App.scrollTo(error, -200);
                                    error.fadeOut(2000);
                                }


                            }

                            ,
                            error: function (err) {

                                console.log(err);
                            }
                            /*  error:function(err){
                                  console.log(err);

                              }*/
                        }
                    )
                    //   });
                }
            }
        </script>
    @endpush
@stop
