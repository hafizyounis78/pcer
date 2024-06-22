@extends('admin.layout.index')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token()}}">
    <div class="page-content">
        <h1 class="page-title">  {{$title}}
            <small>{{$page_title}}</small>
        </h1>
        <div class="page-bar">
            @include('admin.layout.breadcrumb')

        </div>
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-gift"></i>{{$page_title}}</div>
                {{--<div class="tools">
                    <a href="javascript:;" class="collapse"> </a>
                    <a href="#portlet-config" data-toggle="modal" class="config"> </a>
                    <a href="javascript:;" class="reload"> </a>
                    <a href="javascript:;" class="remove"> </a>
                </div>--}}
            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                {{--  <form action="{{url('admin/user/'.$user_id)}}" class="form-horizontal" method="post">--}}
                {{Form::open(['url'=>url('project'),'class'=>'form-horizontal','method'=>"post","id"=>"project_form"])}}

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
                        <label class="col-md-3 control-label">Project name</label>
                        <div class="col-md-4">
                            <div class="input-icon input-group col-md-12">
                                <i class="fa fa-envelope"></i>

                                <input id="project_name" name="project_name" type="text" class="form-control"
                                       placeholder="project name" value=""></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Question</label>
                        <div class="col-md-4">
                            <div class="input-icon input-group col-md-12">
                                <i class="icon-question"></i>
                                <textarea name="question" id="question" class="form-control"
                                          rows="3" placeholder="Question"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Conclusion</label>
                        <div class="col-md-4">
                            <div class="input-icon input-group col-md-12">
                                <i class="icon-action-redo"></i>
                                <textarea name="conclusion" id="conclusion" class="form-control"
                                          rows="3" placeholder="Conclusion"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="multiple" class="control-label col-md-3">Consequence</label>
                        <div class="col-md-4">
                            <select id="role" name="consequence" class="form-control select2">
                                <option value="">select ..</option>

                                <?php

                                foreach ($consequence_list as $role) {

                                    echo '<option value="' . $role->id . '">' . $role->lookup_details . '</option>';
                                }

                                ?>


                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">Consequence Details</label>
                        <div class="col-md-4">
                            <textarea name="consequence_detail" id="consequence_detail" class="form-control"
                                      rows="3" placeholder="Consequence Details"></textarea>
                        </div>
                    </div>
<!--
                    <div class="form-group last">
                        <label class="control-label col-md-3">Symptoms</label>
                        <div class="col-md-4">
                            <textarea name="symptoms" id="symptoms" class="form-control"
                                      rows="3" placeholder="Symptoms"></textarea>
                        </div>
                    </div>
-->


                </div>
                <div class="form-actions left">
                    <div class="row">
                        <div class="col-md-9">
                            <button type="submit" class="btn green">Save</button>
                            <a href="{{url('/project')}}" class="btn  grey-salsa btn-outline">Cancel</a>
                        </div>
                    </div>
                </div>
            {{-- </form>--}}
            {{Form::close()}}
            <!-- END FORM-->
            </div>
        </div>
    </div>
    @push('css')
        <style>

            .hselect {
                height: 41px !important;
            }
        </style>
        <link href="{{url('')}}/assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css"/>
        <link href="{{url('')}}/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet"
              type="text/css"/>
        <link href="{{url('')}}/assets/global/plugin*s/bootstrap-colorpicker/css/colorpicker.css" rel="stylesheet"
              type="text/css"/>
        <link href="{{url('')}}/assets/global/plugins/jquery-minicolors/jquery.minicolors.css" rel="stylesheet"
              type="text/css"/>
    @endpush
    @push('js')
        <script src="{{url('')}}/assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
        <script src="{{url('')}}/assets/pages/scripts/components-select2.js" type="text/javascript"></script>
        <script src="{{url('')}}/assets/global/plugins/jquery-validation/js/jquery.validate.min.js"
                type="text/javascript"></script>
        <script src="{{url('')}}/assets/global/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js"
                type="text/javascript"></script>
        <script src="{{url('')}}/assets/global/plugins/jquery-minicolors/jquery.minicolors.min.js"
                type="text/javascript"></script>

        <script>
            var projectFormValidation = function () {

                // basic validation
                var handleValidation1 = function () {
                    // for more info visit the official plugin documentation:
                    // http://docs.jquery.com/Plugins/Validation

                    var form1 = $('#project_form');
                    var error1 = $('.alert-danger', form1);
                    var success1 = $('.alert-success', form1);

                    form1.validate({
                        errorElement: 'span', //default input error message container
                        errorClass: 'help-block help-block-error', // default input error message class
                        focusInvalid: false, // do not focus the last invalid input
                        ignore: "",  // validate all fields including form hidden input
                        messages: {
                            select_multi: {
                                maxlength: jQuery.validator.format("Max {0} items allowed for selection"),
                                minlength: jQuery.validator.format("At least {0} items must be selected")
                            }
                        },
                        rules: {
                            project_name: {
                                required: true
                            },
                            question: {
                                required: true
                            }/*,
                            conclusion: {
                                required: true
                            },

                            consequence: {
                                required: true,

                            },
                            consequence_detail: {
                                required: true
                            }*/

                        },

                        messages: { // custom messages for radio buttons and checkboxes
                            /*  password: {
                                  required: "هذه الحقل مطلوب,الرجاء ادخال قيمة",
                                  minlength: "القيمة المدخلة غير مناسبة  ,اقل قيمة متاحة 6 حروف"
                              },

                              email: {
                                  required: "هذه الحقل مطلوب,الرجاء ادخال قيمة",
                                  email: "الرجاء التأكد من القيمة المدخلة, مثال user@admin.com"
                              },
                              confirmPassword: {
                                  required: "هذه الحقل مطلوب,الرجاء ادخال قيمة",
                                  equalTo: "الكلمة المدخلة غير مطابقة لكلمة المرور,يرجى التأكد",

                              },
                              role: {
                                  required: "هذا الحقل مطلوب,الرجاء ادخال قيمة",

                              }*/
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

                            projectSubmit();


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


            projectFormValidation.init();

            function projectSubmit() {

                var form1 = $('#project_form');
                var error = $('.alert-danger', form1);
                var success = $('.alert-success', form1);

                var action = $('#project_form').attr('action');

                var formData = new FormData($('#project_form')[0]);
                $.ajax({
                        url: action,
                        type: 'POST',
                        dataType: 'json',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (data) {
                            if (data.success) {
                                success.show();
                                error.hide();
                                App.scrollTo(success, -200);
                                success.fadeOut(2000);
                                window.location.href = '{{url('/project')}}';
                            } else {
                                success.hide();
                                error.show();
                                App.scrollTo(error, -200);
                                error.fadeOut(2000);
                            }


                        },
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


        </script>
    @endpush
@stop
