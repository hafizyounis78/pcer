<div id="dvalert">

</div>
@php
    if(auth()->user()->id!=100)
        $patient_name=$one_patient->name;
    else
        $patient_name='**********';
@endphp
<div class="m-heading-1 border-green m-bordered">
    <div class="col-md-12">
        <div class="col-md-3">
            <strong>Patient Name: </strong><span class="font-blue-madison">
                        <a class="  purple " data-toggle="modal" href="#patientModal"
                           title="Click to change patient data"
                           onclick="get_patient_data({{$one_patient->id.','.$painFile_id}})">{{$patient_name}}</a></span>
        </div>
        <div class="col-md-2">
            <strong>Age: </strong>
            <small class="font-blue-madison">
                {{\Carbon\Carbon::parse($one_patient->dob)->diff(\Carbon\Carbon::now())->format('%y years')}}</small>
        </div>
        <div class="col-md-3">
            <strong>First Consultation: </strong>
            <small class="font-blue-madison">
                {{$baseline_doctor_visit_date}}</small>
        </div>
        <div class="col-md-3">
            <strong>File No: </strong><span class="font-blue-madison">{{$painFile_id}}</span>
&
            <strong>File Status: </strong><span class="font-blue-madison">{{$painFile_status}}</span>
        </div>
        <div class="col-md-1">
            <a class="btn btn-circle btn-xs yellow-lemon" data-toggle="modal" href="#patientBastTreatModal"
               title="Click to view patient current medication"
               onclick="get_patient_currt_med({{$one_patient->id}})">
                <i class="fa fa-medkit"></i>
            </a>
            <a class="btn btn-circle btn-xs red" data-toggle="modal" href="#patientAlertModal"
               title="Click to Add new alert" onclick="get_patient_alerts({{$one_patient->id}})">
                <i class="fa fa-bell"></i>
            </a>
        </div>
    </div>
    <br/>
</div>
<div class="modal fade bs-modal-lg" id="patientModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title font-green">Patient Profile</h4>
            </div>
            <div class="modal-body">
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
                    <input type="hidden" id="patient_id" name="patient_id" value="{{''}}">
                    <div class="form-group">
                        <label class="control-label col-md-3">Registration Date
                            <span class="required"> * </span>
                        </label>
                        <div class="col-md-4 input-group">
                            <input class="form-control date-picker" type="text" value="" name="start_date"
                                   id="start_date"
                                   data-date-format="yyyy-mm-dd" disabled/>
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
                        <div class="col-md-4 input-group ">
                            <input type="text" name="name" id="name" data-required="1" class="form-control"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">Patient Name عربي
                            <span class="required"> * </span>
                        </label>
                        <div class="col-md-4 input-group ">
                            <input type="text" name="name_a" id="name_a" data-required="1" class="form-control"/>
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
                            <input class="form-control date-picker input-group" type="text" value="" name="dob"
                                   id="dob"
                                   data-date-format="yyyy-mm-dd"/>
                            <span class="help-block"> Select date </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">Mobile No.
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

                                    echo '<option value="' . $district->id . '">' . $district->lookup_details . '</option>';
                                }

                                ?>

                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">Address</label>
                        <div class="col-md-4 input-group">
                                <textarea class="form-control" rows="3" id="address"
                                          name="address">{{''}}</textarea>
                        </div>
                    </div>
                </div>
            @if($painFile_statusId==17 && auth()->user()->id!=100)<!-- 17=Active file,100=guest user -->
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="submit" class="btn green">Save</button>
                            <button type="button" data-dismiss="modal" class="btn  grey-salsa btn-outline">Cancel
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
<div class="modal fade" id="patientBastTreatModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title font-green">Patient Current Medication</h4>
            </div>
            <div class="modal-body">
                <!-- BEGIN FORM-->
                {{Form::open(['url'=>'','class'=>'form-horizontal','method'=>"post","id"=>'medication_form'])}}
                <div class="form-body">

                    <input type="hidden" id="hdnpatient_id" name="hdnpatient_id" value="{{$one_patient->id}}">
                    <!--                    <h3 class="form-section font-green"> Treatment choice(s) </h3>-->


                    <div class="row" id="currentMedicationTable">
                        <div class="alert alert-danger alert-danger-current-treatment display-hide">
                            <button class="close" data-close="alert"></button>
                            You have some form errors. Please check below.
                        </div>
                        <div class="alert alert-success alert-success-current-treatment display-hide">
                            <button class="close" data-close="alert"></button>
                            Your form validation is successful!
                        </div>
                        <div class="col-md-12">
                            <div class="table-scrollable table-scrollable-borderless">
                                <table class="table table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Drugs</th>
                                        <th>Comments</th>
                                        <th>Status</th>
                                        <th>Action</th>

                                    </tr>
                                    <tr class="uppercase">
                                        <td> #</td>
                                        <td width="30%">
                                            <input type="text" name="drug_desc"
                                                   id="drug_desc"
                                                   class="form-control input-xsmall"
                                                   placeholder="Drug"/>
                                        </td>
                                        <td width="30%">
                                            <input type="text"
                                                   id="curr_drug_comments"
                                                   name="curr_drug_comments"
                                                   class="form-control"
                                                   placeholder="Comments"/>

                                        </td>
                                        <td width="20%"></td>
                                        <td width="20%">
                                        @if($painFile_statusId==17 && auth()->user()->id!=100)<!-- 17=Active file,100=guest user -->
                                            <span class="input-group-btn"><button
                                                        class="btn btn-success  btn-icon-only"
                                                        type="button"
                                                        onclick="add_current_medication()">  <i
                                                            class="fa fa-plus fa-fw"></i></button></span>
                                            @endif
                                        </td>
                                    </tr>
                                    </thead>
                                    <tbody id="tbCurrentMedication">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!--                <div class="form-actions">
                                    <div class="row">
                                        <div class="col-md-offset-3 col-md-9">
                                            <button type="submit" class="btn green">Save</button>
                                            <button type="button" data-dismiss="modal" class="btn  grey-salsa btn-outline">Cancel
                                            </button>

                                        </div>
                                    </div>
                                </div>-->
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
<div class="modal fade" id="patientAlertModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title font-green">Alerts</h4>
            </div>
            <div class="modal-body">
                <!-- BEGIN FORM-->
                {{Form::open(['url'=>'','class'=>'form-horizontal','method'=>"post","id"=>'alert_form'])}}
                <div class="form-body">

                    <input type="hidden" id="hdnpatient_id" name="hdnpatient_id" value="{{$one_patient->id}}">
                    <!--                    <h3 class="form-section font-green"> Treatment choice(s) </h3>-->


                    <div class="row" id="currentMedicationTable">
                        <div class="alert alert-danger alert-danger-alert display-hide">
                            <button class="close" data-close="alert"></button>
                            You have some form errors. Please check below.
                        </div>
                        <div class="alert alert-success alert-success-alert display-hide">
                            <button class="close" data-close="alert"></button>
                            Your form validation is successful!
                        </div>
                        <div class="col-md-12">
                            <div class="table-scrollable table-scrollable-borderless">
                                <table class="table table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Alert</th>
                                        <th>Type</th>
                                        <th>Action</th>

                                    </tr>
                                    <tr class="uppercase">
                                        <td> #</td>
                                        <td width="70%">
                                            <input type="text" name="alert_text"
                                                   id="alert_text"
                                                   class="form-control" placeholder="Alert"/>
                                        </td>
                                        <td width="10%">
                                            <select class="bs-select form-control" data-show-subtext="true"
                                                    id="alert_type">
                                                <option value="1"
                                                        data-content=" <span class='label lable-sm label-success'>Green </span>">
                                                    Green
                                                </option>
                                                <option value="2"
                                                        data-content=" <span class='label lable-sm label-warning'>Yellow </span>">
                                                    Yellow
                                                </option>
                                                <option value="3"
                                                        data-content=" <span class='label lable-sm label-danger'>Red </span>">
                                                    Red
                                                </option>

                                            </select>

                                        </td>
                                        <td width="20%">
                                        @if($painFile_statusId==17 && auth()->user()->id!=100)<!-- 17=Active file,100=guest user -->
                                            <span class="input-group-btn"><button
                                                        class="btn btn-success  btn-icon-only"
                                                        type="button"
                                                        onclick="add_alert()">  <i
                                                            class="fa fa-plus fa-fw"></i></button></span>
                                            @endif

                                        </td>
                                    </tr>
                                    </thead>
                                    <tbody id="tbAlerts">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!--                <div class="form-actions">
                                    <div class="row">
                                        <div class="col-md-offset-3 col-md-9">
                                            <button type="submit" class="btn green">Save</button>
                                            <button type="button" data-dismiss="modal" class="btn  grey-salsa btn-outline">Cancel
                                            </button>

                                        </div>
                                    </div>
                                </div>-->
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
    <link href="{{url('')}}/assets/global/plugins/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet"
          type="text/css"/>
@endpush
@push('js')
    <script src="{{url('')}}/assets/global/plugins/jquery-validation/js/jquery.validate.min.js"
            type="text/javascript"></script>
    <script src="{{url('')}}/assets/global/plugins/select2/js/select2.full.min.js"
            type="text/javascript"></script>
    <script src="{{url('')}}/assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js"
            type="text/javascript"></script>

    <script>
        $(document).ready(function () {
            get_curr_patient_alert();
            $('.bs-select').selectpicker({
                iconBase: 'fa',
                tickIcon: 'fa-check',
                container: 'body',

            });
        });

        function get_patient_data(id, painFile_id) {

            blockUI('.m-heading-1');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: '{{url('patient/get-patient-data')}}',

                data: {
                    id: id, painFile_id: painFile_id
                },
                error: function (xhr, status, error) {

                },
                beforeSend: function () {
                },
                complete: function () {
                },
                success: function (data) {
                    unblockUI('.m-heading-1');
                    if (data.success) {

                        $('#patient_id').val(data.patient.id);
                        $('#start_date').val(data.painFile.start_date);
                        $('#national_id').val(data.patient.national_id);
                        $('#name').val(data.patient.name);
                        $('#name_a').val(data.patient.name_a);
                        $('#gender').val(data.patient.gender);
                        $('#dob').val(data.patient.dob);
                        $('#mobile_no').val(data.patient.mobile_no);
                        $('#district').val(data.patient.district);
                        $('.select2').trigger('change');
                        $('#address').html(data.patient.address);
                    } else {
                        alert('error');
                    }
                }
            });//END $.ajax
        }

        function get_patient_currt_med(patient_id) {
            blockUI('.m-heading-1');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: '{{url('patient/get-patient-current-medication')}}',

                data: {
                    patient_id: patient_id
                },
                success: function (data) {
                    unblockUI('.m-heading-1');
                    if (data.success) {
                        $('#tbCurrentMedication').html(data.current_medication_table);
                    } else {
                        alert('error');
                    }
                }
            });//END $.ajax
        }

        function add_current_medication() {
            blockUI('tbCurrentMedication');
            var drug_desc = $('#drug_desc').val();
            var drug_comments = $('#curr_drug_comments').val();

            $("#drug_desc").parent().removeClass('has-error');
            // $("#effect_id").parent().removeClass('has-error');
            if (drug_desc == '') {

                $('.alert-danger-current-treatment').show();
                $('.alert-danger-current-treatment').fadeOut(3000);
                $("#drug_desc").parent().addClass('has-error');
                return;
            } else
                $("#drug_desc").parent().removeClass('has-error');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: '{{url('patient/insert-current-medication')}}',

                data: {
                    drug_desc: drug_desc,
                    drug_comments: drug_comments,
                    patient_id: $('#hdnpatient_id').val()
                },
                error: function (xhr, status, error) {

                },
                beforeSend: function () {
                },
                complete: function () {
                },
                success: function (data) {
                    unblockUI('tbCurrentMedication');
                    if (data.success) {
                        $('.alert-success-current-treatment').show();
                        $('.alert-success-current-treatment').fadeOut(2000);
                        $('#tbCurrentMedication').html(data.current_medication_table);

                        $('#drug_desc').val('');
                        $('#curr_drug_comments').val('');
                    } else {
                        alert('error');
                    }
                }
            });//END $.ajax
        }

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
                            //   uniqueNationalId: true
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

        //*******************alerts*******************
        function get_patient_alerts(patient_id) {
            blockUI('.m-heading-1');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: '{{url('patient/get-patient-alerts')}}',

                data: {
                    patient_id: patient_id
                },
                success: function (data) {
                    unblockUI('.m-heading-1');
                    if (data.success) {
                        $('#tbAlerts').html(data.alert_table);
                    } else {
                        alert('error');
                    }
                }
            });//END $.ajax
        }

        function get_curr_patient_alert() {
            var patient_id = $('#hdnpatient_id').val()
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: '{{url('patient/get-curr-patient-alert')}}',

                data: {
                    patient_id: patient_id
                },
                success: function (data) {

                    if (data.success) {
                        $('#dvalert').html(data.alert_html);
                    } else {
                        alert('error');
                    }
                }
            });//END $.ajax
        }

        function add_alert() {
            // blockUI('tbCurrentMedication');
            var alert_text = $('#alert_text').val();
            var alert_type = $('#alert_type').val();

            $("#alert_text").parent().removeClass('has-error');
            // $("#effect_id").parent().removeClass('has-error');
            if (alert_text == '') {

                $('.alert-danger-alert').show();
                $('.alert-danger-alert').fadeOut(3000);
                $("#alert_text").parent().addClass('has-error');
                return;
            } else
                $("#alert_text").parent().removeClass('has-error');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: '{{url('patient/insert-alert')}}',

                data: {
                    alert_text: alert_text,
                    alert_type: alert_type,
                    patient_id: $('#hdnpatient_id').val()
                },
                error: function (xhr, status, error) {

                },
                beforeSend: function () {
                },
                complete: function () {
                },
                success: function (data) {
                    // unblockUI('tbCurrentMedication');
                    if (data.success) {
                        get_curr_patient_alert();
                        $('.alert-success-alert').show();
                        $('.alert-success-alert').fadeOut(2000);
                        $('#tbAlerts').html(data.alert_table);

                        $('#alert_text').val('');
                        $('#alert_type').val('');
                        $('#alert_type').trigger('change');
                    } else {
                        alert('error');
                    }
                }
            });//END $.ajax
        }

        function del_alert(id) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: '{{url('patient/delete-alert')}}',

                data: {
                    id: id,
                    patient_id: $('#hdnpatient_id').val()
                },
                error: function (xhr, status, error) {

                },
                beforeSend: function () {
                },
                complete: function () {
                },
                success: function (data) {

                    if (data.success) {
                        get_curr_patient_alert();
                        $('.alert-success-alert').show();
                        $('.alert-success-alert').fadeOut(2000);
                        $('#tbAlerts').html(data.alert_table);

                        $('#alert_text').val('');
                        $('#alert_type').val('');
                        $('#alert_type').trigger('change');
                    } else {
                        alert('error');
                    }
                }
            });//END $.ajax
        }

        function del_current_medication(id) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: '{{url('patient/delete-current-medication')}}',

                data: {
                    id: id,
                    patient_id: $('#hdnpatient_id').val()
                },
                error: function (xhr, status, error) {

                },
                beforeSend: function () {
                },
                complete: function () {
                },
                success: function (data) {
                    unblockUI('tbCurrentMedication');
                    if (data.success) {
                        $('.alert-success-current-treatment').show();
                        $('.alert-success-current-treatment').fadeOut(2000);
                        $('#tbCurrentMedication').html(data.current_medication_table);

                        $('#drug_desc').val('');
                        $('#curr_drug_comments').val('');
                    } else {
                        alert('error');
                    }
                }
            });//END $.ajax
        }

        function stop_current_medication(id) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: '{{url('patient/stop-current-medication')}}',

                data: {
                    id: id,
                    patient_id: $('#hdnpatient_id').val()
                },
                error: function (xhr, status, error) {

                },
                beforeSend: function () {
                },
                complete: function () {
                },
                success: function (data) {
                    unblockUI('tbCurrentMedication');
                    if (data.success) {
                        $('.alert-success-current-treatment').show();
                        $('.alert-success-current-treatment').fadeOut(2000);
                        $('#tbCurrentMedication').html(data.current_medication_table);

                        $('#drug_desc').val('');
                        $('#curr_drug_comments').val('');
                    } else {
                        alert('error');
                    }
                }
            });//END $.ajax
        }
    </script>
@endpush