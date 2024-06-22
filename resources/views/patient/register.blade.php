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
                            <span class="caption-subject font-green sbold uppercase">Basic Information</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <!-- BEGIN FORM-->
                        {{Form::open(['url'=>url('patient'),'class'=>'form-horizontal','method'=>"post","id"=>'patient_form'])}}
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
                                <label class="control-label col-md-3">Registration Date
                                    <span class="required"> * </span>
                                </label>
                                <div class="col-md-4 input-group">
                                    <input class="form-control date-picker" type="text" value="" name="start_date"
                                           data-date-format="yyyy-mm-dd" value="{{date('Y-d-m')}}"/>
                                    <span class="help-block"> Select date </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Patient ID
                                    <span class="required"> * </span>
                                </label>
                                <div class="col-md-4 input-group">
                                    <input type="text" name="national_id" id="national_id" data-required="1"
                                           class="form-control" value="{{(isset($national_id)?$national_id:'')}}"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Patient Name
                                    <span class="required"> * </span>
                                </label>
                                <div class="col-md-4 input-group">
                                    <input type="text" name="name" id="name" data-required="1" class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Patient Name عربي
                                    <span class="required"> * </span>
                                </label>
                                <div class="col-md-4 input-group">
                                    <input type="text" name="name_a" id="name_a" data-required="1" class="form-control"
                                           value="{{''}}" readonly/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Sex
                                    <span class="required"> * </span>
                                </label>
                                <div class="col-md-4 input-group">
                                    <select class="form-control select2" name="gender" id="gender">
                                        <option value="">Select...</option>
                                        <option value="1">Male</option>
                                        <option value="2">Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Birth Date
                                    <span class="required"> * </span>
                                </label>
                                <div class="col-md-4 input-group">
                                    <input class="form-control date-picker input-group" type="text"
                                           value="{{''}}"
                                           name="dob" id="dob"
                                           data-date-format="yyyy-mm-dd"/>
                                    <span class="help-block"> Select date </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Mobile No.
                                    <span class="required"> * </span>
                                </label>
                                <div class="col-md-4 input-group">
                                    <input type="text" name="mobile_no" id="mobile_no" data-required="1"
                                           class="form-control" value="{{''}}"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">District</label>
                                <div class="col-md-4  input-group">
                                    <select id="district" class="form-control select2 hselect" name="district">
                                        <option value="">اختر ..</option>
                                        <?php
                                        foreach ($districts as $district) {

                                            echo '<option value="' . $district->id . '" >' . $district->lookup_details . '</option>';
                                        }

                                        ?>

                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Address</label>
                                <div class="col-md-4 input-group">
                                    <textarea class="form-control" rows="3" id="address"
                                              name="address">"{{''}}"</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-3 col-md-9">
                                    <button type="submit" class="btn green">Save</button>
                                    <a href="{{url('/home')}}" class="btn  grey-salsa btn-outline">Cancel</a>

                                </div>
                            </div>
                        </div>
                    {{Form::close()}}
                    <!-- END FORM-->
                    </div>
                </div>
                <!-- END VALIDATION STATES-->
            </div>
        </div>
        <!-- END PAGE BASE CONTENT -->
    </div>

    @push('css')

        <link href="{{url('/')}}/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css"
              rel="stylesheet" type="text/css"/>
        <link href="{{url('')}}/assets/global/plugins/select2/css/select2.min.css" rel="stylesheet"
              type="text/css"/>
        <link href="{{url('')}}/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet"
              type="text/css"/>
    @endpush
    @push('js')
        <script src="{{url('/')}}/assets/global/plugins/moment.min.js" type="text/javascript"></script>
        <script src="{{url('/')}}/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"
                type="text/javascript"></script>

        <script src="{{url('')}}/assets/global/plugins/jquery-validation/js/jquery.validate.min.js"
                type="text/javascript"></script>
        <script src="{{url('')}}/assets/global/plugins/select2/js/select2.full.min.js"
                type="text/javascript"></script>
        <script src="{{url('')}}/assets/pages/scripts/components-select2.js"
                type="text/javascript"></script>
        <script>
            $(document).ready(function () {
                $(".select2").select2({
                    width: null
                });
                get_patient_moi_info();
                $('#national_id').blur(function () {
                    get_patient_moi_info();
                });
            });

            function get_patient_moi_info() {
                if (!IsValidId($('#national_id').val())) {
                    alert('Error ID Number');
                    $('#patient_form')[0].reset();
                    return;
                }
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '{{url('patient/get_moi_info')}}',
                    beforeSend: function (xhr) {
                        xhr.overrideMimeType("text/plain; charset=utf-8");
                    },
                    type: 'POST',
                    data: {national_id: $('#national_id').val()},
                    success: function (data) {
                        //     var obj=jQuery.parseJSON(data);
                        var responsed_data = jQuery.parseJSON(data);

                        var patient_name = responsed_data.P_FNAME + ' ' + responsed_data.P_SNAME + ' ' + responsed_data.P_TNAME + ' ' + responsed_data.P_LNAME;
                        $('#name_a').val(patient_name);
                        $('#gender').val(responsed_data.P_SEX_CODE_CD);
                        $('#gender').trigger('change');

                        if (responsed_data.P_REGION_CODE_CD == 869)
                            $('#district').val(2);
                        else if (responsed_data.P_REGION_CODE_CD == 867)
                            $('#district').val(3);
                        else if (responsed_data.P_REGION_CODE_CD == 870)
                            $('#district').val(4);
                        else if (responsed_data.P_REGION_CODE_CD == 868)
                            $('#district').val(5);
                        else if (responsed_data.P_REGION_CODE_CD == 866)
                            $('#district').val(6);

                        $('#district').trigger('change');
                        //P_CITY.'-'.$moi_patient_data->P_STR
                        $('#address').val(responsed_data.P_CITY + '-' + responsed_data.P_STR);

                        var original_date = responsed_data.P_BIRTH_DT;

                        var dateParts = original_date.split("-");
                        if (dateParts.length == 0) {
                            var dateParts = original_date.split("/");
                            if (dateParts.length == 0) {
                                var dateParts = original_date.split(".");
                                if (dateParts.length == 0) {
                                    $('#dob').datepicker('setDate', original_date);
                                } else
                                    $('#dob').datepicker('setDate',dateParts[2] + "-" + dateParts[1] + "-" + dateParts[0]);
                            } else
                                $('#dob').datepicker('setDate',dateParts[2] + "-" + dateParts[1] + "-" + dateParts[0]);

                        } else
                            $('#dob').datepicker('setDate',dateParts[2] + "-" + dateParts[1] + "-" + dateParts[0]);


                    }
                });

            }

            function IsValidId(id) {
                var A;
                var B = 0;
                for (var i = 0; i <= 7; i++) {
                    A = parseInt(id.substring(i, i + 1));
                    if ((i + 1) % 2 == 0) {
                        A = A * 2;
                    }
                    if (A > 9) {
                        A = A - 9;
                    }
                    B = B + A;
                }
                B = B % 10;
                B = (10 - B) % 10;
                if (B != parseInt(id.substring(8))) {
                    return false;
                } else {
                    return true;
                }
            }

            $('.date-picker').datepicker({
                rtl: App.isRTL(),
                orientation: "left",
                autoclose: true,
                endDate: '0d',
                todayHighlight: true
            });

            var patientFormValidation = function () {

                // basic validation
                var handleValidation1 = function () {
                    // for more info visit the official plugin documentation:
                    // http://docs.jquery.com/Plugins/Validation

                    var form1 = $('#patient_form');
                    var error1 = $('.alert-danger', form1);
                    var success1 = $('.alert-success', form1);
                    // Unique NationalId

                    var response2 = true;
                    $.validator.addMethod(
                        "uniqueNationalId",
                        function (value, element) {
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });
                            if (value != '')
                                $.ajax({
                                    type: "POST",
                                    url: '{{url('patient/availabileNationalId')}}',

                                    data: {id: value},
                                    error: function (xhr, status, error) {
                                        alert(xhr.responseText);
                                    },
                                    beforeSend: function () {
                                    },
                                    complete: function () {
                                    },
                                    success: function (data) {
                                        if (data.success == true)
                                            response2 = false;
                                        else
                                            response2 = true;
                                    }
                                });//END $.ajax
                            return response2;
                        },
                        "This patient is all ready exist."
                    );
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
                            national_id: {
                                digits: true,
                                minlength: 9,
                                maxlength: 9,
                                required: true,
                                uniqueNationalId: true
                            },
                            name: {
                                minlength: 2,
                                required: true
                            },
                            gender: {
                                required: true
                            }, dob: {
                                required: true
                            }
                            , start_date: {
                                required: true
                            },
                            mobile_no: {
                                required: true,
                                digits: true,
                                maxlength: 10

                            },

                        },

                        messages: { // custom messages for radio buttons and checkboxes
                            /*name: {
                                required: "هذه الحقل مطلوب,الرجاء ادخال قيمة",
                                minlength: "القيمة المدخلة غير مناسبة ,الرجاء ادخال قيمة أكبر من حرفني"
                            },
                            national_id: {
                                digits: "الرجاء ادخال ارقام فقط",
                                minlength: "القيمة المدخلة غير مناسبة ,الرجاء ادخال 9 ارقام",
                                maxlength: "القيمة المدخلة غير مناسبة ,الرجاء ادخال 9 ارقام",
                                required: "هذه الحقل مطلوب,الرجاء ادخال قيمة",
                                uniqueNationalId: "رقم الهوية موجود مسبقاً ,يرجى التأكد من القيم المدخلة"
                            },

                            gender: {
                                required: "This field required ,please select value",
                                email: "الرجاء التأكد من القيمة المدخلة, مثال user@admin.com"
                            },
                            mobile: {
                                required: "هذه الحقل مطلوب,الرجاء ادخال قيمة",
                                digits: "الرجاء ادخال ارقام فقط",
                                maxlength: 'تأكد من الرقم المدخل, 10 ارقام فقط '

                            },
                            */
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

                            patientSubmit();


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


            patientFormValidation.init();

            function patientSubmit() {
                App.blockUI();
                var form1 = $('#patient_form');
                var error = $('.alert-danger', form1);
                var success = $('.alert-success', form1);

                var action = $('#patient_form').attr('action');

                var formData = new FormData($('#patient_form')[0]);
                form1.validate();
                $.ajax({
                        url: action,
                        type: 'POST',
                        dataType: 'json',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (data) {
                            if (data.success) {
                                App.unblockUI();
                                //  alert('data.patient_id'+data.patient_id);
                                success.show();
                                error.hide();
                                App.scrollTo(success, -200);
                                success.fadeOut(2000);
                                var url = 'painFile/view/' + data.painFile_id + '/' + data.patient_id + '/' + data.painFile_status;
                                window.open('{{url('/')}}/' + url, '_self');
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