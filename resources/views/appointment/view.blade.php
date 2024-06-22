@extends('admin.layout.index')
@section('content')
    @php
        date_default_timezone_set('Asia/Gaza');
    @endphp
    <div class="page-content">
        <!-- BEGIN PAGE HEAD-->
        <div class="page-head">
            <!-- BEGIN PAGE TITLE -->
            <meta name="csrf-token" content="{{ csrf_token()}}">
            <meta charset="UTF-8">
            <div class="page-title">
                <h1>{{isset($page_title)?$page_title:''}}
                    <small>{{isset($page_title_small)?$page_title_small:''}}</small>
                </h1>
            </div>
            <!-- END PAGE TITLE -->
            <!-- BEGIN PAGE TOOLBAR -->
            <div class="page-toolbar">

            </div>
            <!-- END PAGE TOOLBAR -->
        </div>
    @include('admin.layout.breadcrumb')
    <!-- BEGIN PAGE BASE CONTENT -->

        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light portlet-fit  calendar">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class=" icon-layers font-green"></i>
                                <span class="caption-subject font-green sbold uppercase">Calendar</span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="row">

                                <div class="col-md-12 col-sm-12">
                                    <div id="calendar" class="has-toolbar"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT INNER -->
        <!-- END PAGE BASE CONTENT -->

        <div class="modal fade" id="appointmentModal" tabindex="-1" role="basic" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h4 class="modal-title font-green">Add New Appointment</h4>
                    </div>
                    <div class="modal-body">
                        <!-- BEGIN FORM-->
                        {{Form::open(['url'=>url('appointment'),'class'=>'form-horizontal','method'=>"post","id"=>'appointment_form'])}}
                        <div class="form-body">
                            <input type="hidden" id="painFile_id" name="painFile_id" value="{{''}}">
                            <div class="alert alert-danger display-hide">
                                <button class="close" data-close="alert"></button>
                                You have some form errors. Please check below.
                            </div>
                            <div class="alert alert-success display-hide">
                                <button class="close" data-close="alert"></button>
                                Your form validation is successful!
                            </div>
                            <div class="form-group">
                                <lable class="control-label col-md-3">Patient</lable>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input name="name" id="name" type="text"
                                               class="form-control typeahead select2_sample2"
                                               placeholder="Search for patient..." onkeypress=" rest_PainFile();">

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label>
                                        <span class="font-red-haze" name="doctor_name" id="doctor_name">

                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Appintment Date</label>
                                <div class="col-md-6">
                                    <div class="input-group date">
                                        <input type="text" size="16" readonly class="form-control"
                                               name="appointment_date" id="appointment_date">
                                        <!--  <span class="input-group-btn">
                                                              <button class="btn default date-set" type="button">
                                                                  <i class="fa fa-calendar"></i>
                                                              </button>
                                                          </span>-->
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Attend type</label>
                                <div class="col-md-6">
                                    <div class="mt-checkbox-list">
                                        <label class="mt-checkbox mt-checkbox-outline"> Without Appointment
                                            <input type="checkbox" value="1" name="test" id="has_appointment"
                                                   name="has_appointment" checked="false"/>
                                            <span></span>
                                        </label>

                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Appointment Type</label>
                                <div class="col-md-6">
                                    <select class="form-control" id="appointment_type" name="appointment_type">
                                        <option value="">Select...</option>
                                        <option value="1">Baseline</option>
                                        <option value="2">Follow up</option>
                                        <option value="3">Exercise</option>
                                        <option value="4">Psychologist</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Doctor</label>
                                <div class="col-md-6">
                                    <select class="form-control" id="appointment_loc" name="appointment_loc"
                                            onchange="check_available_appointment()">
                                        <option value="">Select...</option>
                                        <?php

                                        foreach ($users as $user) {

                                            echo '<option value="' . $user->id . '">' . $user->name . '</option>';
                                        }

                                        ?>
                                    </select>
                                </div>

                            </div>
                            <div class="form-group" style="padding-left: 165px;">
                                <div class="row number-stats margin-bottom-30">
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <div class="stat-right">

                                            <div class="stat-number">
                                                <div class="title green"> Current</div>
                                                <div class="number" id="current_capacity"> 0</div>
                                            </div>
                                        </div>
                                        <div class="stat-left">
                                            <div class="stat-number">
                                                <div class="title green"> Capacity</div>
                                                <div class="number" id="user_daily_capacity"> 0</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{--     <div class="form-group">
                                   <label class="col-md-3 control-label">Visit To</label>
                                   <div class="col-md-6">
                                       <select class="form-control" onchange="getStaff();" id="appointment_dept"
                                               name="appointment_dept">
                                           <option value="">Select...</option>
                                           <option value="9">Doctor</option>
                                           --}}{{--  <option value="10">Nurse</option>
                                             <option value="11">Pharmacist</option>--}}{{--
                                       </select>
                                   </div>
                               </div>--}}
                            {{--<div class="form-group">
                                <label class="col-md-3 control-label">Health Professional</label>
                                <div class="col-md-6">
                                    <select class="form-control" id="appointment_loc" name="appointment_loc">

                                    </select>
                                </div>
                            </div>--}}
                            <div class="form-group last">
                                <label class="col-md-3 control-label">Note</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="comments" id="comments"/>
                                </div>
                            </div>
                        </div>
                    @if(auth()->user()->id!=100)<!-- 17=Active file,100=guest user -->
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-3 col-md-6">
                                    <button type="submit" class="btn green" id="appoint_submit">Submit</button>
                                    <button type="button" data-dismiss="modal" class="btn dark btn-outline">Close
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
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
        <div class="modal fade" id="updateAppointModal" tabindex="-1" role="basic" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h4 class="modal-title font-green">Add New Appointment</h4>
                    </div>
                    <div class="modal-body">
                        <!-- BEGIN FORM-->
                        {{Form::open(['url'=>url('appointment'),'class'=>'form-horizontal','method'=>"post","id"=>'updateappointment_form'])}}

                        <div class="form-body">
                            <input type="hidden" id="event_id" name="event_id" value="{{''}}">

                            <div class="alert alert-danger display-hide">
                                <button class="close" data-close="alert"></button>
                                You have some form errors. Please check below.
                            </div>
                            <div class="alert alert-success display-hide">
                                <button class="close" data-close="alert"></button>
                                Your form validation is successful!
                            </div>
                            <div class="form-group">
                                <lable class="control-label col-md-3">Patient</lable>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label name="name" id="uname" type="text" class="font-green"
                                               value="{{''}}"></label>

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label>
                                        <span class="font-red-haze" name="doctor_name" id="udoctor_name">

                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Appintment Date</label>
                                <div class="col-md-6">
                                    <div class="input-group date">
                                        <label type="text" size="16" readonly class="font-green"
                                               name="appointment_date" id="uappointment_date"></label>
                                        <!--  <span class="input-group-btn">
                                                              <button class="btn default date-set" type="button">
                                                                  <i class="fa fa-calendar"></i>
                                                              </button>
                                                          </span>-->
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Attend type</label>
                                <div class="col-md-6">
                                    <div class="mt-checkbox-list">
                                        <label class="mt-checkbox mt-checkbox-outline"> Without Appointment
                                            <input type="checkbox" value="1" name="test" id="uhas_appointment"
                                                   name="has_appointment" checked="false"/>
                                            <span></span>
                                        </label>

                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Appointment Type</label>
                                <div class="col-md-6">
                                    <select class="form-control" id="uappointment_type" name="appointment_type">
                                        <option value="">Select...</option>
                                        <option value="1">Baseline</option>
                                        <option value="2">Follow up</option>
                                        <option value="3">Exercise</option>
                                        <option value="4">Psychologist</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Doctor</label>
                                <div class="col-md-6">
                                    <select class="form-control" id="uappointment_loc" name="uappointment_loc"
                                            onchange="ucheck_available_appointment()">
                                        <option value="">Select...</option>
                                        <?php

                                        foreach ($users as $user) {

                                            echo '<option value="' . $user->id . '">' . $user->name . '</option>';
                                        }

                                        ?>
                                    </select>
                                </div>

                            </div>
                            <div class="form-group" style="padding-left: 165px;">
                                <div class="row number-stats margin-bottom-30">
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <div class="stat-right">

                                            <div class="stat-number">
                                                <div class="title green"> Current</div>
                                                <div class="number" id="ucurrent_capacity"> 0</div>
                                            </div>
                                        </div>
                                        <div class="stat-left">
                                            <div class="stat-number">
                                                <div class="title green"> Capacity</div>
                                                <div class="number" id="uuser_daily_capacity"> 0</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{--     <div class="form-group">
                                   <label class="col-md-3 control-label">Visit To</label>
                                   <div class="col-md-6">
                                       <select class="form-control" onchange="getStaff();" id="appointment_dept"
                                               name="appointment_dept">
                                           <option value="">Select...</option>
                                           <option value="9">Doctor</option>
                                           --}}{{--  <option value="10">Nurse</option>
                                             <option value="11">Pharmacist</option>--}}{{--
                                       </select>
                                   </div>
                               </div>--}}
                            {{--<div class="form-group">
                                <label class="col-md-3 control-label">Health Professional</label>
                                <div class="col-md-6">
                                    <select class="form-control" id="appointment_loc" name="appointment_loc">

                                    </select>
                                </div>
                            </div>--}}
                            <div class="form-group last">
                                <label class="col-md-3 control-label">Note</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="comments" id="ucomments"/>
                                </div>
                            </div>
                        </div>
                    @if(auth()->user()->id!=100)<!-- 17=Active file,100=guest user -->
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-3 col-md-6">
                                    <button type="submit" class="btn blue" id="appoint_update">Update</button>
                                    <button type="submit" class="btn red" onclick="deleteAppointment();">Delete
                                    </button>
                                    <button type="button" data-dismiss="modal" class="btn dark ">Close
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
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
    </div>
    @push('css')
        <!-- BEGIN PAGE LEVEL PLUGINS -->

        <link href="{{url('/')}}/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css"
              rel="stylesheet" type="text/css"/>
        <link href="{{url('/')}}/assets/global/plugins/select2/css/select2.min.css" rel="stylesheet"
              type="text/css"/>
        <link href="{{url('/')}}/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet"
              type="text/css"/>
        <link href="{{url('/')}}/assets/global/plugins/fullcalendar/fullcalendar.min.css" rel="stylesheet"
              type="text/css"/>
        <link href="{{url('')}}/assets/global/plugins/typeahead/typeahead.css" rel="stylesheet" type="text/css"/>
        <!-- END HEAD -->

    @endpush
    @push('js')

        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="{{url('/')}}/assets/global/plugins/select2/js/select2.full.min.js"
                type="text/javascript"></script>

        <script src="{{url('/')}}/assets/global/plugins/moment.min.js" type="text/javascript"></script>
        <script src="{{url('')}}/assets/global/plugins/fullcalendar/fullcalendar.min.js"
                type="text/javascript"></script>
        <script src="{{url('')}}/assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
        <script src="{{url('/')}}/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"
                type="text/javascript"></script>

        <script src="{{url('')}}/assets/global/plugins/jquery-validation/js/jquery.validate.min.js"
                type="text/javascript"></script>
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="{{url('')}}/assets/global/plugins/typeahead/handlebars.min.js" type="text/javascript"></script>
        <script src="{{url('')}}/assets/global/plugins/typeahead/typeahead.bundle.min.js"
                type="text/javascript"></script>

        <!-- END PAGE LEVEL PLUGINS -->
        <!-- END PAGE LEVEL PLUGINS -->
        <script src="{{url('')}}/assets/apps/scripts/calendar.js" type="text/javascript"></script>
        <!-- END THEME LAYOUT SCRIPTS -->

        <script>
            var ComponentsTypeahead = function () {

                var handleTwitterTypeahead = function () {
                    var custom = new Bloodhound({
                        datumTokenizer: function (d) {
                            return d.tokens;
                        },
                        queryTokenizer: Bloodhound.tokenizers.whitespace,

                        remote: {
                            url: '{{url('get-patient-name?query=%QUERY')}}',
                            wildcard: '%QUERY'
                        }
                    });

                    custom.initialize();

                    if (App.isRTL()) {
                        $('#name').attr("dir", "rtl");
                    }
                    $('#name').typeahead(null, {
                        name: 'name',
                        displayKey: 'name',
                        source: custom.ttAdapter(),
                        minLength: 1,
                        hint: (App.isRTL() ? false : true),
                        templates: {

                            suggestion: Handlebars.compile([
                                '<h4 class="media-heading">@{{name}}</h4>',
                            ].join(''))
                        }
                    });
                    $('#name').on('typeahead:select', function (evt, item) {

                        $('#doctor_name').html(item.doctor_name);
                        //  alert(item.painFile_id);
                        $('#painFile_id').val(item.painFile_id);


                        // Your Code Here
                    })
//*********

                    //******************
                }
                return {
                    //main function to initiate the module
                    init: function () {
                        handleTwitterTypeahead();

                    }
                };

            }();
            $(document).ready(function () {
                ComponentsTypeahead.init();
                rest_PainFile();

            });


            var appointmentFormValidation = function () {

                // basic validation
                var handleValidation1 = function () {
                    // for more info visit the official plugin documentation:
                    // http://docs.jquery.com/Plugins/Validation

                    var form1 = $('#appointment_form');
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
                                name: {
                                    required: true
                                },
                                appointment_loc: {
                                    required: true
                                }, appointment_type: {
                                    required: true
                                },/*  status: {
                                      required: true
                                  }, timing_of_consultation: {
                                      required: true
                                  },
                                  medication_before_injury: {
                                      required: true
                                  },
                                  medication_now: {
                                      required: true
                                  },
                                  neuro_history_of_pain: {
                                      required: true
                                  },
                                  neuro_pain_localized: {
                                      required: true
                                  }
  */
                            },

                            messages:
                                { // custom messages for radio buttons and checkboxes
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
                                }
                            ,

                            invalidHandler: function (event, validator) { //display error alert on form submit
                                success1.hide();
                                error1.show();
                                App.scrollTo(error1, -200);
                            }
                            ,

                            errorPlacement: function (error, element) { // render error placement for each input type
                                var cont = $(element).parent('.input-group');
                                if (cont) {
                                    cont.after(error);
                                } else {
                                    element.after(error);
                                }
                            }
                            ,

                            highlight: function (element) { // hightlight error inputs

                                $(element)
                                    .closest('.form-group').addClass('has-error'); // set error class to the control group
                            }
                            ,

                            unhighlight: function (element) { // revert the change done by hightlight
                                $(element)
                                    .closest('.form-group').removeClass('has-error'); // set error class to the control group
                            }
                            ,

                            success: function (label) {
                                label
                                    .closest('.form-group').removeClass('has-error'); // set success class to the control group
                            }
                            ,

                            submitHandler: function (form) {

                                appointmentSubmit();


                            }
                        }
                    );


                }


                return {
                    //main function to initiate the module
                    init: function () {


                        handleValidation1();


                    }

                };

            }
            ();


            appointmentFormValidation.init();

            function appointmentSubmit() {
                App.blockUI();
                var form1 = $('#appointment_form');
                var error = $('.alert-danger', form1);
                var success = $('.alert-success', form1);
                var action = $('#appointment_form').attr('action');
                var formData = new FormData($('#appointment_form')[0]);
                if ($('#has_appointment').prop('checked')) {// // Returns true if checked, false if unchecked.)

                    formData.append('has_appointment', 1);
                } else {

                    formData.append('has_appointment', 0);
                }


                // return;

                $.ajax({
                        url: action,
                        type: 'POST',
                        dataType: 'json',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (data) {
                            App.unblockUI();
                            if (data.success) {
                                //  alert('data.patient_id' + data.patient_id);
                                $('#appointmentModal').modal('hide');
                                rest_PainFile();
                                //  $('#calendar').fullCalendar('removeEvents');
                                //   $('#calendar').fullCalendar( 'addEventSource', data.events);
                                $("#calendar").fullCalendar('rerenderEvents', data.events, 'stick');
                                AppCalendar.init();
                                success.show();
                                error.hide();
                                App.scrollTo(success, -200);
                                success.fadeOut(2000);
                                // calendar.render();
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

                    }
                )

            }

            //**************update appointments***********/
            var updareappointmentFormValidation = function () {

                // basic validation
                var handleValidation1 = function () {
                    // for more info visit the official plugin documentation:
                    // http://docs.jquery.com/Plugins/Validation

                    var form1 = $('#updateappointment_form');
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
                                name: {
                                    required: true
                                },
                                appointment_loc: {
                                    required: true
                                }, appointment_type: {
                                    required: true
                                },/*  status: {
                                      required: true
                                  }, timing_of_consultation: {
                                      required: true
                                  },
                                  medication_before_injury: {
                                      required: true
                                  },
                                  medication_now: {
                                      required: true
                                  },
                                  neuro_history_of_pain: {
                                      required: true
                                  },
                                  neuro_pain_localized: {
                                      required: true
                                  }
  */
                            },

                            messages:
                                { // custom messages for radio buttons and checkboxes
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
                                }
                            ,

                            invalidHandler: function (event, validator) { //display error alert on form submit
                                success1.hide();
                                error1.show();
                                App.scrollTo(error1, -200);
                            }
                            ,

                            errorPlacement: function (error, element) { // render error placement for each input type
                                var cont = $(element).parent('.input-group');
                                if (cont) {
                                    cont.after(error);
                                } else {
                                    element.after(error);
                                }
                            }
                            ,

                            highlight: function (element) { // hightlight error inputs

                                $(element)
                                    .closest('.form-group').addClass('has-error'); // set error class to the control group
                            }
                            ,

                            unhighlight: function (element) { // revert the change done by hightlight
                                $(element)
                                    .closest('.form-group').removeClass('has-error'); // set error class to the control group
                            }
                            ,

                            success: function (label) {
                                label
                                    .closest('.form-group').removeClass('has-error'); // set success class to the control group
                            }
                            ,

                            submitHandler: function (form) {

                                updateappointmentSubmit();


                            }
                        }
                    );


                }


                return {
                    //main function to initiate the module
                    init: function () {


                        handleValidation1();


                    }

                };

            }
            ();


            updareappointmentFormValidation.init();

            function updateappointmentSubmit() {
                App.blockUI();
                var form1 = $('#updateappointment_form');
                var error = $('.alert-danger', form1);
                var success = $('.alert-success', form1);
                var action = $('#updateappointment_form').attr('action');
                var formData = new FormData($('#updateappointment_form')[0]);
                if ($('#uhas_appointment').prop('checked')) {// // Returns true if checked, false if unchecked.)

                    formData.append('has_appointment', 1);
                } else {

                    formData.append('has_appointment', 0);
                }


                // return;

                $.ajax({
                        url: action,
                        type: 'POST',
                        dataType: 'json',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (data) {
                            App.unblockUI();
                            if (data.success) {
                                //  alert('data.patient_id' + data.patient_id);
                                $('#updateAppointModal').modal('hide');
                                rest_PainFile();
                                //  $('#calendar').fullCalendar('removeEvents');
                                //   $('#calendar').fullCalendar( 'addEventSource', data.events);
                                $("#calendar").fullCalendar('rerenderEvents', data.events, 'stick');
                                AppCalendar.init();
                                success.show();
                                error.hide();
                                App.scrollTo(success, -200);
                                success.fadeOut(2000);
                                // calendar.render();
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

                    }
                )

            }

            function rest_PainFile() {
                $('#doctor_name').html('');
                $('#painFile_id').val('');
                $('#current_capacity').html(0);
                $('#user_daily_capacity').html(0);
                $('#appointment_loc').val('');
                $('#appointment_loc').trigger('change');
                //  $('#has_appointment').prop('checked', true); // Checks it
                $('#has_appointment').prop('checked', false); // Unchecks it
            }

            function deleteAppointment() {
                var id = $('#event_id').val();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('appointment/delete')}}',

                    data: {id: id},
                    error: function (xhr, status, error) {
                        alert(xhr.responseText);
                    },
                    success: function (data) {
                        if (data.success == true) {
                            AppCalendar.init();
                            $('#updateAppointModal').modal('hide');
                            rest_PainFile();
                        }

                    }
                });//END $.ajax
            }

            function check_available_appointment() {
                var appointment_loc = $('#appointment_loc').val();
                var appointment_date = $('#appointment_date').val();
                $('#current_capacity').html(0);
                $('#user_daily_capacity').html(0);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('appointment/check-appointment')}}',

                    data: {
                        appointment_loc: appointment_loc,
                        event_date: appointment_date
                    },
                    error: function (xhr, status, error) {
                        alert(xhr.responseText);
                    },
                    success: function (data) {
                        $('#current_capacity').html(data.current_capacity);
                        $('#user_daily_capacity').html(data.user_daily_capacity);
                        if (data.success == true) {
                            $('#appoint_submit').prop('disabled', false);
                        } else
                            $('#appoint_submit').prop('disabled', true);

                    }
                });//END $.ajax
            }

            function ucheck_available_appointment() {
                var appointment_loc = $('#uappointment_loc').val();
                var appointment_date = $('#uappointment_date').html();
                $('#ucurrent_capacity').html(0);
                $('#uuser_daily_capacity').html(0);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('appointment/check-appointment')}}',

                    data: {
                        appointment_loc: appointment_loc,
                        event_date: appointment_date
                    },
                    error: function (xhr, status, error) {
                        alert(xhr.responseText);
                    },
                    success: function (data) {
                        $('#ucurrent_capacity').html(data.current_capacity);
                        $('#uuser_daily_capacity').html(data.user_daily_capacity);

                        if (data.success == true) {
                            $('#appoint_update').prop('disabled', false);
                        }
                        else
                            $('#appoint_update').prop('disabled', true);
                    }
                });//END $.ajax
            }

            function displayMessage(message) {
                $(".response").html("<div class='success'>" + message + "</div>");
                setInterval(function () {
                    $(".success").fadeOut();
                }, 1000);
            }
        </script>




    @endpush
@stop