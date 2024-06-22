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


    <!-- BEGIN PAGE BASE CONTENT -->
        @include('patient.patient_profile')
        @if(isset($covid_data) and $covid_data!='')
            <div class="alert alert-block alert-danger fade in">
                <button type="button" class="close" data-dismiss="alert"></button>
                <h4 class="alert-heading">Covid-19 Alert</h4>
                <p>{{$covid_data}}</p>
            </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <div class="row widget-row">
<!--                    <div class="col-md-3">
                        &lt;!&ndash; BEGIN WIDGET THUMB &ndash;&gt;
                        <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                            <a href="{{url('/acutepain/create/'.$painFile_id.'/'.$one_patient->id.'/'.$painFile_statusId)}}"
                               onclick="App.blockUI();"><h4
                                        class="widget-thumb-heading">Acute Pain
                                    <span class="caption-helper font-green-steel ">&nbsp;&nbsp;{{isset($last_acutepain_date)?$last_acutepain_date->visit_date:''}}</span>
                                </h4>
                                <div class="widget-thumb-wrap">
                                    <i class="widget-thumb-icon bg-green font-white fa fa-file"></i>
                                    <div class="widget-thumb-body">
                                        <span class="widget-thumb-subtitle">No of Records</span>
                                        <span class="widget-thumb-body-stat">  {{isset($total_acutepain_count)?$total_acutepain_count:0}} </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        &lt;!&ndash; END WIDGET THUMB &ndash;&gt;
                    </div>-->
                    <div class="col-md-3">
                        <!-- BEGIN WIDGET THUMB -->
                        @php
                            //$url="painFile/view";
                            //$style="style=cursor:not-allowed";
                                //if($total_acutepain_count>0){
                                $style='style=cursor:pointer';
                               $url='/baseline/create/'.$painFile_id.'/'.$one_patient->id.'/'.$painFile_statusId;
                              // }
                        @endphp
                        <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                            <a href="{{url($url)}}" onclick="App.blockUI();"><h4
                                        class="widget-thumb-heading">Baseline
                                    <span class="caption-helper font-green-steel ">&nbsp;&nbsp;{{isset($last_baseline_date)?$last_baseline_date->visit_date:''}}</span>
                                </h4>
                                <div class="widget-thumb-wrap">
                                    <i class="widget-thumb-icon bg-red icon-layers"></i>
                                    <div class="widget-thumb-body">
                                        <span class="widget-thumb-subtitle">No of Records</span>
                                        <span class="widget-thumb-body-stat">  {{isset($total_baseline_count)?$total_baseline_count:0}} </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <!-- END WIDGET THUMB -->
                    </div>
                    <div class="col-md-3">
                        <!-- BEGIN WIDGET THUMB -->
                        @php
                            $url="painFile/view/".$painFile_id.'/'.$one_patient->id.'/'.$painFile_statusId;
                            $style="style=cursor:not-allowed";
                            if($total_baseline_count>0){
                             $style='style=cursor:pointer';
                              $url='/followup/create/'.$painFile_id.'/'.$one_patient->id.'/'.$painFile_statusId;}
                        @endphp
                        <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                            <a href="{{url($url)}}" onclick="App.blockUI();" {{$style}}><h4
                                        class="widget-thumb-heading">Follow Up
                                    <span class="caption-helper font-green-steel ">&nbsp;&nbsp;{{isset($last_followup_date)?$last_followup_date->follow_up_date:''}}</span>
                                </h4>
                                <div class="widget-thumb-wrap">
                                    <i class="widget-thumb-icon bg-purple fa fa-calendar-check-o"></i>
                                    <div class="widget-thumb-body">
                                        <span class="widget-thumb-subtitle">No of Records</span>
                                        <span class="widget-thumb-body-stat"> {{isset($total_followup_count)?$total_followup_count:0}} </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <!-- END WIDGET THUMB -->
                    </div>

                    <div class="col-md-3">
                    @php
                        $url="painFile/view/".$painFile_id.'/'.$one_patient->id.'/'.$painFile_statusId;
                         $style="style=cursor:not-allowed";
                        if($total_followup_count>0||$total_baseline_count>0){
                         $style='style=cursor:pointer';
                          $url='lastfollowup/create/'.$painFile_id.'/'.$one_patient->id.'/'.$painFile_statusId;
                        }
                    @endphp
                    <!-- BEGIN WIDGET THUMB -->
                        <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                            <a href="{{url($url)}}" onclick="App.blockUI();" {{$style}}><h4
                                        class="widget-thumb-heading">Last Visit
                                    <span class="caption-helper font-green-steel ">&nbsp;&nbsp;{{isset($last_lastfollowup_date)?$last_lastfollowup_date->follow_up_date:''}}</span>
                                </h4>
                                <div class="widget-thumb-wrap">
                                    <i class="widget-thumb-icon bg-blue fa fa-database"></i>
                                    <div class="widget-thumb-body">
                                        <span class="widget-thumb-subtitle">No of Records</span>
                                        <span class="widget-thumb-body-stat"> {{isset($total_lastfollowup_count)?$total_lastfollowup_count:0}} </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <!-- END WIDGET THUMB -->
                    </div>
                </div>

                <!-- BEGIN Portlet PORTLET-->


            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN VALIDATION STATES-->
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-comments font-green"></i>
                            <span class="caption-subject font-green sbold uppercase">Consultations</span>
                        </div>
                        <div class="actions">
                            <div class="btn-group">
                                <a href="#form_modal2" class="btn btn-circle green btn-outline" data-toggle="modal"> Add
                                    New Consultation </a>
                            </div>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div id="accordion1" class="panel-group">
                            @php
                                echo $consultation_data;
                            @endphp

                        </div>
                        <div id="form_modal2" class="modal fade" role="dialog" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal"
                                                aria-hidden="true"></button>
                                        <h4 class="modal-title">Add New Consultation</h4>
                                    </div>
                                    <div class="modal-body">
                                        {{Form::open(['url'=>url('consultation'),'class'=>'form-horizontal','method'=>"post","id"=>'consultation_form'])}}
                                        <input type="hidden" id="painFile_id" name="painFile_id"
                                               value="{{$painFile_id}}">
                                        <input type="hidden" id="painFile_statusid" name="painFile_statusid"
                                               value="{{$painFile_statusId}}">
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
                                                <label class="control-label col-md-4">Consultation Title</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" type="text" value="{{''}}"
                                                           id="consultation_title" name="consultation_title"/></div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Consultation Details</label>
                                                <div class="col-md-8">
                                                    <textarea class="form-control" id="consultation_detail"
                                                              name="consultation_detail">{{''}}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        @if($painFile_statusId==17 && auth()->user()->id!=100)<!-- 17=Active file,100=guest user -->
                                        <div class="form-actions">
                                            <div class="row">
                                                <div class="col-md-offset-3 col-md-9">
                                                    <button type="submit" class="btn green">Save</button>
                                                    <button class="btn dark btn-outline" data-dismiss="modal"
                                                            aria-hidden="true">
                                                        Close
                                                    </button>

                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        {{Form::close()}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END VALIDATION STATES-->
            </div>
        </div>
        <!-- END PAGE BASE CONTENT -->
    </div>

    <div class="modal fade" id="patientModal" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title font-green">Add New Appointment</h4>
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
    <!-- END CONTENT BODY -->
@stop

@push('css')
    <link href="{{url('')}}/assets/pages/css/blog.min.css" rel="stylesheet" type="text/css"/>
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

    <script>
        $(document).ready(function () {
            $(".select2").select2({
                width: null
            });
        });
        $('.date-picker').datepicker({
            rtl: App.isRTL(),
            orientation: "left",
            autoclose: true,
            endDate: '0d',
            todayHighlight: true
        });


        function getConsultationComments(id) {
            blockUI('accordion1_' + id);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: '{{url('painFile/comments')}}',

                data: {
                    id: id
                },
                error: function (xhr, status, error) {

                },
                beforeSend: function () {
                },
                complete: function () {
                },
                success: function (data) {
                    unblockUI('accordion1_' + id);
                    if (data.success) {
                        $('#comments' + id).html(data.html);
                    } else {
                        alert('error');
                    }
                }
            });//END $.ajax
        }

        var consultationFormValidation = function () {

            // basic validation
            var handleValidation1 = function () {
                // for more info visit the official plugin documentation:
                // http://docs.jquery.com/Plugins/Validation

                var form1 = $('#consultation_form');
                var error1 = $('.alert-danger', form1);
                var success1 = $('.alert-success', form1);
                // Unique NationalId

                var response2 = true;

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

                        consultation_title: {
                            minlength: 2,
                            required: true
                        },
                        consultation_detail: {
                            minlength: 2,
                            required: true
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

                        consultationSubmit();
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
        consultationFormValidation.init();

        function consultationSubmit() {
            App.blockUI();
            var form1 = $('#consultation_form');
            var error = $('.alert-danger', form1);
            var success = $('.alert-success', form1);

            var action = $('#consultation_form').attr('action');

            var formData = new FormData($('#consultation_form')[0]);
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
                            $('#accordion1').html(data.consult_html);
                            success.show();
                            error.hide();
                            App.scrollTo(success, -200);
                            success.fadeOut(2000);
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

        function addComment(consultations_id) {
            blockUI('comments' + consultations_id);
            var comment = $('#comment' + consultations_id).val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: '{{url('consultation/add-comment')}}',

                data: {
                    consultations_id: consultations_id, comment: comment, painFile_id: $('#painFile_id').val(),
                    painFile_status: $('#painFile_statusid').val()
                },
                error: function (xhr, status, error) {

                },
                beforeSend: function () {
                },
                complete: function () {
                },
                success: function (data) {
                    unblockUI('comments' + consultations_id);
                    if (data.success) {
                        $('#comments' + consultations_id).html(data.comments_html);
                        $('#comment_count' + consultations_id).html(data.comments_count);
                        $('#comment' + consultations_id).val('');
                        //    $('#comments' + consultations_id).html(data.html);
                    } else {
                        alert('error');
                    }
                }
            });//END $.ajax
        }


    </script>
@endpush

