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
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-gift"></i>{{$title}}</div>
                <div class="tools">
                    <a href="javascript:;" class="collapse"> </a>

                </div>
            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                {{Form::open(['url'=>url('setting/s8-save'),'class'=>'form-horizontal','method'=>"post","id"=>"setting_form"])}}
                <input type="hidden" id="hdn_table_id" name="hdn_table_id" value="{{''}}">
                <div class="form-body">
                    <div class="alert alert-danger display-hide">
                        <button class="close" data-close="alert"></button>
                        يوجد خطأ في ادخال البيانات,يرجى فحص القيم المدخلة
                    </div>
                    <div class="alert alert-success display-hide">
                        <button class="close" data-close="alert"></button>
                        تمت عملية الحفظ بنجاح!
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">الوصف</label>
                        <div class="col-md-4">
                            <div class="input-icon input-group col-md-12 ">
                                <i class="fa fa-paw"></i>
                                <input name="desc" id="desc" type="text" class="form-control"
                                       placeholder="الوصف" value=""></div>
                        </div>
                    </div>
                    <div class="form-group last">
                        <label class="control-label col-md-3">معايير التقييم</label>
                        <div class="col-md-9">
                            <select multiple="multiple" class="multi-select" id="rate"
                                    name="rate[]">
                                @foreach($rates as $rate)
                                    <option value="{{$rate->id}}">{{$rate->desc}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{--   <div class="form-group last">
                           <label class="control-label col-md-3">Grouped Options</label>
                           <div class="col-md-9">
                               <select multiple="multiple" class="multi-select" id="my_multi_select2" name="my_multi_select2[]">
                                   <optgroup label="NFC EAST">
                                       <option>Dallas Cowboys</option>
                                       <option>New York Giants</option>
                                       <option>Philadelphia Eagles</option>
                                       <option>Washington Redskins</option>
                                   </optgroup>
                                   <optgroup label="NFC NORTH">
                                       <option>Chicago Bears</option>
                                       <option>Detroit Lions</option>
                                       <option>Green Bay Packers</option>
                                       <option>Minnesota Vikings</option>
                                   </optgroup>
                                   <optgroup label="NFC SOUTH">
                                       <option>Atlanta Falcons</option>
                                       <option>Carolina Panthers</option>
                                       <option>New Orleans Saints</option>
                                       <option>Tampa Bay Buccaneers</option>
                                   </optgroup>
                                   <optgroup label="NFC WEST">
                                       <option>Arizona Cardinals</option>
                                       <option>St. Louis Rams</option>
                                       <option>San Francisco 49ers</option>
                                       <option>Seattle Seahawks</option>
                                   </optgroup>
                               </select>
                           </div>
                       </div>--}}
                </div>
                <div class="form-actions left">
                    <div class="row">
                        <div class=" col-md-9">
                            <button type="submit" class="btn green">حفظ</button>
                            <a href="{{url('/')}}" class="btn  grey-salsa btn-outline">عودة</a>
                        </div>
                    </div>
                </div>
            {{Form::close()}}
            <!-- END FORM-->
            </div>
        </div>

        <div class="portlet box yellow-casablanca">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-gift"></i>جدول القيم
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse"> </a>

                </div>
            </div>
            <div class="portlet-body form">
                <div class="form-body">
                    <table class="table table-striped table-bordered table-hover  order-column" id="setting_tbl">
                        <thead>
                        <tr>

                            <th> كود</th>
                            <th> الوصف</th>
                            <th>تحكم</th>

                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

    </div>
    @push('css')
        <link href="{{url('')}}/assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css"/>
        <link href="{{url('')}}/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet"
              type="text/css"/>
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <link href="{{url('')}}/assets/global/plugins/bootstrap-select/css/bootstrap-select-rtl.min.css"
              rel="stylesheet" type="text/css"/>
        <link href="{{url('')}}/assets/global/plugins/jquery-multi-select/css/multi-select-rtl.css" rel="stylesheet"
              type="text/css"/>
        <link href="{{url('')}}/assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css"/>
        <link href="{{url('')}}/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet"
              type="text/css"/>
        <!-- END PAGE LEVEL PLUGINS -->
    @endpush
    @push('js')
        <script src="{{url('')}}/assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
        <script src="{{url('')}}/assets/pages/scripts/components-select2.min.js" type="text/javascript"></script>
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="{{url('')}}/assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js"
                type="text/javascript"></script>
        <script src="{{url('')}}/assets/global/plugins/jquery-multi-select/js/jquery.multi-select.js"
                type="text/javascript"></script>
        <script src="{{url('')}}/assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
        <script src="{{url('')}}/assets/pages/scripts/components-multi-select.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        <script>
            $(document).ready(function () {
                $('#setting_tbl').dataTable({

                    'processing': true,
                    'serverSide': true,
                    'ajax': '{{url('/s8-data')}}',
                    'columns': [

                        {data: 'id', name: 'id'},
                        {data: 'desc', name: 'desc'},
                        {data: 'action', name: 'action'}
                    ],
                    "language": {
                        "aria": {
                            "sortAscending": ": activate to sort column ascending",
                            "sortDescending": ": activate to sort column descending"
                        },
                        "emptyTable": "لايوجد بيانات في الجدول للعرض",
                        "info": "عرض _START_ الى  _END_ من _TOTAL_ سجلات",
                        "infoEmpty": "No records found",
                        "infoFiltered": "(filtered1 from _MAX_ total records)",
                        "lengthMenu": "عرض _MENU_",
                        "search": "بحث:",
                        "zeroRecords": "No matching records found",
                        "paginate": {
                            "previous": "Prev",
                            "next": "Next",
                            "last": "Last",
                            "first": "First"
                        }
                    },

                })
            })
        </script>
        <script src="{{url('')}}/assets/global/plugins/jquery-validation/js/jquery.validate.min.js"
                type="text/javascript"></script>
        <script type="text/javascript">
            var ComponentsDropdowns = function () {

                var handleMultiSelect = function () {
                    $('#rate').multiSelect({
                        //   selectableOptgroup: true,

                        afterSelect: function (values) {
                            /* $.ajaxSetup({
                                 headers: {
                                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                 }
                             });
                             if($("#user_id").val()!=0)
                                 $.ajax({
                                     url:'{{url('role/selectUserPer')}}',
                                    type: "POST",
                                    data:  {user_id : $("#user_id").val(),
                                        values : values},
                                    error: function(xhr, status, error) {
                                        //var err = eval("(" + xhr.responseText + ")");
                                        alert(xhr.responseText);
                                    },
                                    beforeSend: function(){},
                                    complete: function(){},
                                    success: function(returndb){
                                        if(returndb != '')
                                            $('#my_multi_select2').multiSelect('deselect', values);
                                    }
                                });//END $.ajax*/
                        },
                        afterDeselect: function (values) {
                            var id=$("#hdn_table_id").val();
                            if(id!='')
                               $.ajax({
                                   url:'{{url('deselectRate')}}',
                                type: "POST",
                                data:  {id : id,
                                    values : values},
                                error: function(xhr, status, error) {
                                    //var err = eval("(" + xhr.responseText + ")");
                                    alert(xhr.responseText);
                                },
                                beforeSend: function(){},
                                complete: function(){},
                                success: function(returndb){
                                    if(returndb != '')
                                        $('#my_multi_select2').multiSelect('select', values);
                                }
                            });//END $.ajax
                        },

                        selectableHeader: "<div class='btn-danger' align='center'><b> غـيـر مـتـاحـة </b></div>",
                        selectionHeader: "<div class='btn-success' align='center'><b> مـتـاحــة </b></div>"
                    });

                }

                return {
                    //main function to initiate the module
                    init: function () {
                        handleMultiSelect();
                    }
                };

            }();
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

                            desc: {
                                required: true,

                            },

                        },

                        messages: { // custom messages for radio buttons and checkboxes
                            desc: {
                                required: "هذه الحقل مطلوب,الرجاء ادخال قيمة",
                            },

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


                }


                return {
                    //main function to initiate the module
                    init: function () {


                        handleValidation1();


                    }

                };

            }();


            settingFormValidation.init();

            function settingSubmit() {
                var values = $('#rate').val();
                //alert(values)
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
                                clearForm();
                                window.location.href = '{{url('setting/s8')}}';

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

                })
                //   });
            }

            function settingDelete(id) {

                var form1 = $('#setting_form');
                var error = $('.alert-danger', form1);
                var success = $('.alert-success', form1);

                var x = '';
                var r = confirm('سيتم حذف القيمة ,هل انت متاكد من ذلك؟');
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
                            url: '{{url('setting/s8-delete')}}',
                            data: {id: id},

                            success:

                                function (data) {
                                    if (data.success) {

                                        success.show();
                                        error.hide();
                                        App.scrollTo(success, -200);
                                        success.fadeOut(2000);
                                        window.location.href = '{{url('setting/s8')}}';

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
                }
                //   });
            }

            function fillForm(id, desc) {
                $('#hdn_table_id').val(id);
                $('#desc').val(desc);
                getEvalsystemRates(id);

                var itemUp = $('#desc');
                App.scrollTo(itemUp, -200);


            }

            function getEvalsystemRates(id) {


                //  alert(id);
                //$('#rate').html('');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                        type: "POST",
                        url: '{{url('getEvalsystemRates')}}',
                        data: {id: id},

                        success:

                            function (data) {
                                if (data.success) {

                                    $('#rate').html(data.rate);
                                    $("#rate").multiSelect('refresh');

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

            function clearForm() {
                $('#hdn_table_id').val('');
                $('#desc').val('');
                $('#rate').html('');
                $("#rate").multiSelect('refresh');

            }
        </script>
    @endpush
@stop
