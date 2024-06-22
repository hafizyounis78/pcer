@extends('admin.layout.index')
@section('content')
    <div class="page-content">
        <div class="page-head">
            <meta name="csrf-token" content="{{ csrf_token()}}">
            <div class="page-title">
                <h1>Search Patient

                </h1>
            </div>
            <!-- END PAGE TITLE -->
            <!-- BEGIN PAGE TOOLBAR -->
            <div class="page-toolbar">

            </div>
        </div>


        <div class="search-page search-content-4">
            <div class="search-bar bordered">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="input-group">
                            <input type="text" class="form-control"
                                   placeholder="Search for..." id="national_id" name="national_id">
                            <span class="input-group-btn">
                                <button class="btn green-soft uppercase bold" type="button" onclick="search_patient()">Search</button>
                            </span>
                        </div>
                    </div>
                    <div class="col-lg-4 extra-buttons">
                        <button class="btn grey-steel uppercase bold" type="button" onclick="clearForm();">Reset
                            Search
                        </button>
                    </div>
                </div>
            </div>
            <div class="search-table table-responsive hide" id="dvSearchPatient">
                <table class="table table-bordered table-striped table-condensed" id="patientTable">
                    <thead class="bg-blue">
                    <tr>
                        <th>
                            <a href="javascript:;">Status</a>
                        </th>
                        <th>
                            <a href="javascript:;">Date</a>
                        </th>
                        <th>
                            <a href="javascript:;">Patient Name</a>
                        </th>
                        <th>
                            <a href="javascript:;">Doctor</a>
                        </th>
                        <th>
                            <a href="javascript:;">Patient Id</a>
                        </th>
                        <th>
                            <a href="javascript:;">View</a>
                        </th>
                    </tr>
                    </thead>
                    <tbody id="patienttb">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- END CONTENT BODY -->

    @push('css')
        <link href="{{url('')}}/assets/pages/css/search.min.css" rel="stylesheet" type="text/css"/>
    @endpush
    @push('js')
        <script src="{{url('/')}}/assets/global/plugins/bootbox/bootbox.min.js" type="text/javascript"></script>

        <script>
            $(document).ready(function () {

                $("#national_id").keyup(function (event) {
                    if (event.keyCode === 13) {
                        search_patient();
                    }
                });
            });

            function search_patient() {

                var national_id = $('#national_id').val();
                //      alert(national_id);
                if (national_id != '') {
                    App.blockUI();
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        type: "POST",
                        url: '{{url('patient/search')}}',
                        data: {national_id: national_id},

                        success: function (data) {
                            App.unblockUI();
                            $("#dvSearchPatient").removeClass('hide');
                            if (data.success)
                                $('#patienttb').html(data.data)
                            else {
                                $("#dvSearchPatient").addClass('hide');
                                $('#patienttb').html('');
                                bootbox.dialog({
                                    message: "This patient is not exsit. Would you like to go to registration?",
                                    title: "Custom title",
                                    buttons: {
                                        success: {
                                            label: "Go to Registration!",
                                            className: "green",
                                            callback: function () {

                                                var url = 'patient/register'
                                                window.location.href = "{{url('patient/create/')}}" + '/' + national_id;
                                            }
                                        },
                                        danger: {
                                            label: "Cancel!",
                                            className: "red",
                                            callback: function () {

                                            }
                                        }
                                    }
                                });
                            }

                        },
                        error: function (err) {

                            console.log(err);
                        }

                    })
                } else
                    $("#dvSearchPatient").addClass('hide');
                $('#patienttb').html('');
            }

            function viewPainFile(painFile_id, patient_id, painFile_status) {
                App.blockUI();
                App.blockUI();
                var url = 'painFile/view/' + painFile_id + '/' + patient_id + '/' + painFile_status;
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "POST",
                    url: '{{url('painFile/set-id')}}',
                    data: {
                        painFile_id: painFile_id,
                        painFile_status: painFile_status,
                        patient_id: patient_id,
                        url: url
                    },

                    success: function (data) {
                        //  App.unblockUI();
                        window.open('{{url('/')}}/' + url, '_self');
                    },
                    error: function (err) {

                        console.log(err);
                    }

                })
            }

            function newPainFile(patient_id) {
                App.blockUI();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                bootbox.dialog({
                    message: " Would you like to open new pain file?",
                    title: "Warning",
                    buttons: {
                        success: {
                            label: "Yes!",
                            className: "green",
                            callback: function () {

                                $.ajax({
                                    type: "POST",
                                    url: '{{url('painFile/new-file')}}',
                                    data: {patient_id: patient_id},

                                    success: function (data) {
                                        App.unblockUI();
                                        if (data.success) {
                                            var url = 'painFile/view/' + data.painFile_id + '/' + patient_id + '/' + data.painFile_status;
                                            window.open('{{url('/')}}/' + url, '_self');
                                        }
                                        else
                                        {
                                            bootbox.dialog({
                                                message: data.msg,
                                                title: "Error",
                                                buttons: {
                                                    danger: {
                                                        label: "Ok",
                                                        className: "red",
                                                        callback: function () {

                                                        }
                                                    },
                                                }
                                            });

                                        }
                                    },
                                    error: function (err) {
                                        App.unblockUI();
                                        console.log(err);
                                    }

                                })
                            }
                        },
                        danger: {
                            label: "Cancel!",
                            className: "red",
                            callback: function () {
                                App.unblockUI();
                            }
                        }
                    }
                });
            }

            function clearForm() {
                $("#dvSearchPatient").addClass('hide');
                $('#patienttb').html('');
                $('#national_id').val('');

            }

        </script>

    @endpush
@stop
