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
                            <i class="icon-star font-green"></i>
                            <span class="caption-subject font-green sbold uppercase">Master Lookup's</span>
                        </div>
                        <div class="actions">
                            <div class="btn-group btn-group-devided" data-toggle="buttons">
                                <button type="button" data-toggle="modal" href="#masterModal"
                                        class="btn btn-transparent green btn-outline btn-circle btn-sm active"
                                        onclick="add_master()">Add Master
                                </button>

                            </div>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <!-- BEGIN FORM-->

                        <div class="form-body">
                            <div class="alert alert-danger display-hide">
                                <button class="close" data-close="alert"></button>
                                You have some form errors. Please check below.
                            </div>
                            <div class="alert alert-success display-hide">
                                <button class="close" data-close="alert"></button>
                                Your form validation is successful!
                            </div>
                            <div class="form-group">
                                <div class="mt-checkbox-inline">
                                    <label class="mt-checkbox">
                                        <input type="checkbox" id="showckb" value="0"> With options
                                        <span></span>
                                    </label>

                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Name</label>
                                <div class="col-md-6 input-group select2-bootstrap-prepend">

                                    <select id="parent_id" name="parent_id" class="form-control select2"
                                            onchange="reloadDetailTable()">
                                        <option value="0">All...</option>

                                        <?php
                                        foreach ($lookups_list as $raw)
                                            echo '<option value="' . $raw->id . '">' . $raw->id . '-' . $raw->lookup_details . '</option>';
                                        ?>


                                    </select>

                                </div>
                            </div>

                        </div>
                    {{-- </form>--}}

                    <!-- END FORM-->
                    </div>

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-dark">
                            <i class="icon-settings font-dark"></i>
                            <span class="caption-subject bold uppercase">Lookup's Option Table</span>
                        </div>
                        <div class="actions">
                            <div class="btn-group btn-group-devided" data-toggle="buttons">
                                <button type="button" data-toggle="modal" href="#masterModal"
                                        class="btn btn-transparent green btn-outline btn-circle btn-sm active"
                                        onclick="add_detail()">Add Options
                                </button>

                            </div>
                        </div>
                    </div>
                    <div class="portlet-body">

                        <table class="table table-striped table-bordered table-hover  order-column"
                               id="optiontb">
                            <thead>
                            <tr>

                                <th>Code</th>
                                <th>Option name</th>
                                <th>Control</th>

                            </tr>
                            </thead>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade bs-modal-lg" id="masterModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true"></button>
                    <h4 class="modal-title">Add Lookup </h4>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <!-- BEGIN VALIDATION STATES-->

                            <!-- BEGIN FORM-->
                            {{--  <form action="{{url('admin/user/'.$user_id)}}" class="form-horizontal" method="post">--}}
                            {{Form::open(['url'=>url('setting/s2-save'),'class'=>'form-horizontal','method'=>"post","id"=>"master_form"])}}
                            <input type="hidden" id="hdn_table_id" name="hdn_table_id" value="{{''}}">
                            <input type="hidden" id="hdn_parent_id" name="hdn_parent_id" value="{{''}}">
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
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Name</label>
                                            <div class="col-md-9">

                                                <input name="lookup_details" id="lookup_details" type="text"
                                                       class="form-control" placeholder="Name" value="">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <button type="submit" class="btn green">Save</button>
                                            <button type="button" onclick="clearForm()"
                                                    class="btn red-intense close-modal" data-dismiss="modal">Cancel
                                            </button>
                                        </div>
                                    </div>
                                </div>


                            </div>

                        {{-- </form>--}}
                        {{Form::close()}}
                        <!-- END FORM-->


                        </div>

                    </div>

                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div class="modal fade bs-modal-lg" id="detailModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true"></button>
                    <h4 class="modal-title">Add Sub Lookup </h4>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <!-- BEGIN SAMPLE TABLE PORTLET-->
                            <div class="portlet box green">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="fa fa-gift"></i>Sub Lookup's
                                    </div>
                                    <div class="tools">
                                        {{-- <a href="javascript:;" class="collapse"> </a>--}}

                                    </div>
                                </div>
                                <div class="portlet-body form">
                                    <!-- BEGIN FORM-->
                                    {{--  <form action="{{url('admin/user/'.$user_id)}}" class="form-horizontal" method="post">--}}
                                    {{Form::open(['url'=>url('/setting/save-lookup-detail'),'class'=>'form-horizontal','method'=>"post","id"=>"detail_form"])}}
                                    <input type="hidden" id="hdn_dtable_id" name="hdn_dtable_id" value="{{''}}">
                                    <input type="hidden" id="hdn_lookup_id" name="hdn_lookup_id" value="{{''}}">
                                    <input type="hidden" id="hdn_cat_type" name="hdn_cat_type" value="{{''}}">

                                    <div class="form-body">
                                        <div class="alert alert-danger display-hide">
                                            <button class="close" data-close="alert"></button>
                                            You have some form errors. Please check below.
                                        </div>
                                        <div class="alert alert-success display-hide">
                                            <button class="close" data-close="alert"></button>
                                            Your form validation is successful!
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Name</label>
                                            <div class="col-md-4">
                                                <div class="col-md-12 ">

                                                    <input name="dlookup_details" id="dlookup_details"
                                                           type="text"
                                                           class="form-control"
                                                           placeholder="Name" value=""></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-actions left">
                                        <div class="row">
                                            <div class=" col-md-9">
                                                <button type="submit" class="btn green">Save</button>
                                                <button type="button" onclick="clearDetailForm()"
                                                        class="btn  red-intense">Cancel
                                                </button>

                                            </div>
                                        </div>
                                    </div>
                                {{-- </form>--}}
                                {{Form::close()}}
                                <!-- END FORM-->
                                </div>
                            </div>

                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close
                    </button>

                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    @push('css')
        <link href="{{url('/')}}/assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css"/>
        {{-- <link href="{{url('/')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap-rtl.css"
               rel="stylesheet" type="text/css"/>--}}
        <link href="{{url('')}}/assets/global/plugins/select2/css/select2.min.css" rel="stylesheet"
              type="text/css"/>
        <link href="{{url('')}}/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet"
              type="text/css"/>
    @endpush
    @push('js')
        <script src="{{url('/')}}/assets/global/scripts/datatable.js" type="text/javascript"></script>
        <script src="{{url('/')}}/assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
        <script src="{{url('/')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js"
                type="text/javascript"></script>
        <script src="{{url('')}}/assets/global/plugins/select2/js/select2.full.min.js"
                type="text/javascript"></script>
        <script src="{{url('')}}/assets/global/plugins/jquery-validation/js/jquery.validate.min.js"
                type="text/javascript"></script>
        <script type="text/javascript">

            $("#showckb").change(function () {
                if (this.checked)
                    $('#showckb').val(1);
                else
                    $('#showckb').val(0);

                refresh_LookupsList()

            });
            $(document).ready(function () {
                reloadDetailTable();
                $(".select2").select2({
                    width: null
                });
            })
            var settingFormValidation = function () {

                // basic validation
                var handleValidation1 = function () {
                    // for more info visit the official plugin documentation:
                    // http://docs.jquery.com/Plugins/Validation

                    var form1 = $('#master_form');
                    var error1 = $('.alert-danger', form1);
                    var success1 = $('.alert-success', form1);
                    // Unique email


                    form1.validate({
                        errorElement: 'span', //default input error message container
                        errorClass: 'help-block help-block-error', // default input error message class
                        focusInvalid: false, // do not focus the last invalid input
                        ignore: "",  // validate all fields including form hidden input

                        rules: {

                            lookup_details: {
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

                            lookupSubmit();


                        }
                    });


                }


                return {
                    //main function to initiate the module
                    init: function () {


                        handleValidation1();


                    }

                };

            }();


            settingFormValidation.init();

            function lookupSubmit() {

                var form1 = $('#master_form');
                var error = $('.alert-danger', form1);
                var success = $('.alert-success', form1);

                var action = $('#master_form').attr('action');

                var formData = new FormData($('#master_form')[0]);

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
                        processData: false,
                        contentType: false,
                        success: function (data) {
                            if (data.success) {
                                if (data.parent_id == 0)
                                    refresh_LookupsList();
                                $('#parent_id').trigger('change');
                                success.show();
                                error.hide();
                                App.scrollTo(success, -200);
                                success.fadeOut(2000);
                                $(".close-modal").click();

                                clearForm();
                                //  setTimeout(function(){  }, 1000);


                                // window.location.href = '{{url('/setting/s2')}}';

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

                    }
                )
                //   });
            }

            function settingDelete(id) {

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
                            url: '{{url('/setting/s2-delete')}}',
                            data: {id: id},

                            success:

                                function (data) {
                                    if (data.success) {

                                        success.show();
                                        error.hide();
                                        App.scrollTo(success, -200);
                                        success.fadeOut(2000);
                                        window.location.href = '{{url('/setting/s2')}}';

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

            function fillForm(id, name) {
                var parent_id = $('#parent_id').val();
                $('#hdn_table_id').val(id);
                $('#hdn_parent_id').val(parent_id);
                $('#lookup_details').val(name);

            }

            function clearForm() {
                $('#hdn_table_id').val('');
                $('#hdn_parent_id').val('');
                $('#lookup_details').val('');
            }

            function add_master() {
                $('#hdn_lookup_id').val('');
                $('#hdn_parent_id').val('');
                $('#lookup_details').val('');
            }

            function add_detail() {
                var parent_id = $('#parent_id').val();
                $('#hdn_lookup_id').val('');
                $('#hdn_parent_id').val(parent_id);
            }

            function reloadDetailTable() {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });


                $('#optiontb').DataTable().destroy();
                $.fn.dataTable.ext.errMode = 'none';
                $('#optiontb').on('error.dt', function (e, settings, techNote, message) {
                    console.log('An error has been reported by DataTables: ', message);
                })

                $('#optiontb').dataTable({

                    'processing': true,
                    'serverSide': true,
                    'ajax': {
                        "type": "post",
                        "url": '{{url('s2-details-data')}}',//"s2-details-data",

                        "data": {
                            'table_id': $('#parent_id').val()
                        }
                        ,

                    },


                    'columns': [
                        {data: 'id', name: 'id'},
                        {data: 'lookup_details', name: 'lookup_details'},
                        {data: 'action', name: 'action'},


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
                    },

                })


            }

            function lookupDelete(id) {

                var form1 = $('#master_form');
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
                        url: '{{url('/setting/s2-delete')}}',
                        data: {id: id},

                        success:

                            function (data) {
                                if (data.success) {

                                    success.show();
                                    error.hide();
                                    App.scrollTo(success, -200);
                                    success.fadeOut(2000);
                                    reloadDetailTable()
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
            }

            function refresh_LookupsList() {

                alert($("#showckb").val());

                child = $("#showckb").val();


                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                        type: "POST",
                        url: '{{url('setting/refresh')}}',
                        data: {child: child},
                        success: function (data) {
                            if (data.success) {
                                $('#parent_id').html('');
                                $('#parent_id').html(data.html);
                                reloadDetailTable();
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
