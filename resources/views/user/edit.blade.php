
@extends('admin.layout.index')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token()}}">
    <div class="page-content">
        <h1 class="page-title"> {{$title}}
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
                {{Form::open(['url'=>url('/user/'.$one_user->id),'class'=>'form-horizontal','method'=>"post","id"=>"user_form"])}}

                {{method_field('put')}}
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
                            <div class="input-icon input-group col-md-12">
                                <i class="icon-user"></i>
                                <input name="name" type="text"  class="form-control "
                                       placeholder="Name"  value="{{$one_user->name}}"> </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label">E-mail</label>
                        <div class="col-md-4">
                            <div class="input-icon input-group col-md-12">

                                        <i class="fa fa-envelope"></i>

                                <input id="email" name="email" type="email" class="form-control "
                                       placeholder="email" data-id="{{$one_user->email}}" value="{{$one_user->email}}"> </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label">Password</label>
                        <div class="col-md-4">
                            <div class="input-icon input-group col-md-12">
                                <input id="password" name="password" type="password" class="form-control"
                                       placeholder="Password" value="{{$one_user->password}}">

                                                                            <i class="fa fa-user"></i>

                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Confirm Password</label>
                        <div class="col-md-4">
                            <div class="input-icon input-group col-md-12">
                                <input name="confirmPassword" type="password" class="form-control "
                                       placeholder="" value="{{$one_user->password}}">
                                <i class="fa fa-key"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="multiple" class="control-label col-md-3">User Type</label>
                        <div class="col-md-4">
                            <select id="user_type_id" name="user_type_id" class="form-control select2">
                                <option value="">select ..</option>

                                <?php

                                foreach ($roles as $role) {
                                    $selected = '';
                                    if($role->id == $one_user->user_type_id)
                                        $selected = 'selected';

                                    echo '<option value="' . $role->id . '" '.$selected.'>' . $role->lookup_details . '</option>';
                                }

                                ?>


                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">address</label>
                        <div class="col-md-4">
                            <div class="input-icon input-group col-md-12">
                                <i class="icon-home"></i>
                                <input name="address" type="text" class="form-control "
                                       placeholder="address"  value="{{$one_user->address}}"> </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Mobile</label>
                        <div class="col-md-4">
                            <div class="input-icon input-group col-md-12">
                                <i class="fa fa-mobile-phone"></i>
                                <input name="mobile" type="text" class="form-control"
                                       placeholder="Mobile"    value="{{$one_user->mobile}}"> </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Daily capacity</label>
                        <div class="col-md-4">
                            <div class="input-icon">
                                <i class="fa fa-mobile-phone"></i>
                                <input id="daily_capacity" name="daily_capacity" type="text" class="form-control"
                                       placeholder="Mobile" value="{{$one_user->daily_capacity}}"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Color</label>
                        <div class="col-md-4">
                            <div class="input-icon">
                                <i class="fa fa-paint-brush"></i>
                                <input type="color" id="user_color" name="user_color" class="form-control"  value="{{$one_user->user_color}}"></div>

                        </div>
                    </div>



                </div>
                <div class="form-actions left">
                    <div class="row">
                        <div class=" col-md-9">
                            <button type="submit" class="btn green">Save</button>
                            <a href="{{url('/user')}}" class="btn grey-salsa btn-outline">Cancel</a>
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
        <link href="{{url('')}}/assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css"/>
        <link href="{{url('')}}/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet"
              type="text/css"/>


    @endpush
@push('js')
    <script src="{{url('')}}/assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
    <script src="{{url('')}}/assets/pages/scripts/components-select2.js" type="text/javascript"></script>
    <script src="{{url('')}}/assets/global/plugins/jquery-validation/js/jquery.validate.min.js"
            type="text/javascript"></script>

<script>

    var userFormValidation = function () {

        // basic validation
        var handleValidation1 = function () {
            // for more info visit the official plugin documentation:
            // http://docs.jquery.com/Plugins/Validation

            var form1 = $('#user_form');
            var error1 = $('.alert-danger', form1);
            var success1 = $('.alert-success', form1);
            // Unique email
            var response = true;
            $.validator.addMethod(
                "uniqueEmail",
                function (value, element) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    if($('#email').attr("data-id")==value)
                    {
                        return true;
                    }
                    $.ajax({
                        type: "POST",
                        url: '{{url('user/availabileEmail')}}',

                        data: {email: value},
                        error: function (xhr, status, error) {
                            alert(xhr.responseText);
                        },
                        beforeSend: function () {
                        },
                        complete: function () {
                        },
                        success: function (data) {
                            if (data.success == true)
                                response = false;
                            else
                                response = true;
                        }
                    });//END $.ajax
                    return response;
                },
                "This E-mail not Available,please check your email"
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

                    email: {
                        required: true,
                        email: true,
                        uniqueEmail: true
                    },
                    password: {
                        required: true,
                        minlength: 6,

                    },
                    confirmPassword: {
                        required: true,
                        equalTo: "#password"
                    },
                    user_type_id: {
                        required: true,

                    }

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

                        userSubmit();


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


    userFormValidation.init();

    function userSubmit() {

        var form1 = $('#user_form');
        var error = $('.alert-danger', form1);
        var success = $('.alert-success', form1);
        var action = $('#user_form').attr('action');

        var formData = new FormData($('#user_form')[0]);
        $.ajax({
                url: action,
                type: 'POST',
                dataType: 'json',
                data: formData,
                processData: false,
                contentType: false,
                success: function (data) {
                    if (data.success)
                    {
                        success.show();
                        error.hide();
                        App.scrollTo(success, -200);
                        success.fadeOut(2000);
                        window.location.href = '{{url('/user')}}';
                    }else {
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
    $(document).ready(function() {
     //   $('#user_color').minicolors('value', file_color);//val(file_color);
        $("#user_color").trigger('change');
    });

</script>
@endpush

@stop
