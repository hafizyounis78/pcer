@extends('admin.layout.index')
@section('content')

    <div class="page-content">
        <!-- BEGIN PAGE HEAD-->
        <div class="page-head">
            <!-- BEGIN PAGE TITLE -->
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
            <!-- END PAGE TOOLBAR -->
        </div>
    @include('admin.layout.breadcrumb')
    <!-- BEGIN PAGE BASE CONTENT -->
        @include('patient.patient_profile')
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN VALIDATION STATES-->
                <div class="portlet light portlet-fit portlet-form bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-user font-green"></i>
                            <span class="caption-subject font-green sbold uppercase">Acute Pain Service</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <!-- BEGIN FORM-->
                        {{Form::open(['url'=>url('acutepain'),'class'=>'form-horizontal','method'=>"post","id"=>'acutePain_form'])}}
                        <div class="form-body">
                            <div class="alert alert-danger display-hide">
                                <button class="close" data-close="alert"></button>
                                You have some form errors. Please check below.
                            </div>
                            <div class="alert alert-success display-hide">
                                <button class="close" data-close="alert"></button>
                                Your form validation is successful!
                            </div>
                            <input type="hidden" id="painFile_id" name="painFile_id" value="{{$painFile_id}}">
                            <input type="hidden" id="painFile_statusid" name="painFile_statusid"
                                   value="{{$painFile_statusId}}">
                            <div class="form-group">
                                <label class="control-label col-md-3">Visit Date
                                    <span class="required"> * </span>
                                </label>
                                <div class="col-md-4 input-group">
                                    <input class="form-control date-picker" type="text"
                                           value="{{isset($one_acutePain->visit_date)?$one_acutePain->visit_date:''}}"
                                           name="visit_date"
                                           data-date-format="yyyy-mm-dd"/>
                                    <span class="help-block"> Select date </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">First war injury
                                </label>
                                <div class="col-md-4 input-group">
                                    <select class="form-control" name="first_war_injury">
                                        <option value="">Select...</option>
                                        <option value="1" @php if(isset($one_acutePain->first_war_injury)&& $one_acutePain->first_war_injury==1) echo 'selected="selected"'; @endphp>
                                            Yes
                                        </option>
                                        <option value="0" @php if(isset($one_acutePain->first_war_injury)&& $one_acutePain->first_war_injury==0) echo 'selected="selected"'; @endphp>
                                            No
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Injury etiology
                                </label>
                                <div class="col-md-4 input-group">
                                    <select class="form-control select2" id="injury_mechanism" name="injury_mechanism"
                                            onchange="show_specify_injury_mechanism()">
                                        <option value="">Select...</option>
                                        <?php
                                        $selected = '';
                                        foreach ($injury_mechanism_list as $raw) {
                                            $selected = '';
                                            if (isset($one_acutePain->injury_mechanism) && $raw->id == $one_acutePain->injury_mechanism)
                                                $selected = 'selected="selected"';
                                            echo '<option value="' . $raw->id . '" ' . $selected . '>' . $raw->lookup_details . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div id="dvSpecifyInjuryMechanism" class="form-group hide">
                                <label class="control-label col-md-3">Specify other
                                </label>
                                <div class="col-md-4 input-group">
                                    <input type="text" id="specify_injury_mechanism" name="specify_injury_mechanism"
                                           class="form-control"
                                           value="{{isset($one_acutePain->specify_injury_mechanism)?$one_acutePain->specify_injury_mechanism:''}}"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Status
                                </label>
                                <div class="col-md-4 input-group">
                                    <select class="form-control select2" id="status" name="status"
                                            onchange="show_specify_status()">
                                        <option value="">Select...</option>
                                        <?php
                                        $selected = '';
                                        foreach ($status_list as $raw) {
                                            $selected = '';
                                            if (isset($one_acutePain->status) && $raw->id == $one_acutePain->status)
                                                $selected = 'selected="selected"';
                                            echo '<option value="' . $raw->id . '" ' . $selected . '>' . $raw->lookup_details . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div id="dvSpecifyStatus" class="form-group hide">
                                <label class="control-label col-md-3">Specify other
                                </label>
                                <div class="col-md-4 input-group">
                                    <input type="text" id="specify_status" name="specify_status" class="form-control"
                                           value="{{isset($one_acutePain->specify_status)?$one_acutePain->specify_status:''}}"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Timing of consultation
                                </label>
                                <div class="col-md-4 input-group">
                                    <select class="form-control" id="timing_of_consultation"
                                            name="timing_of_consultation"
                                            onchange="show_specify_timing_of_consultation()">
                                        <option value="">Select...</option>
                                        <?php
                                        $selected = '';
                                        foreach ($timing_of_consultation_list as $raw) {
                                            $selected = '';
                                            if (isset($one_acutePain->timing_of_consultation) && $raw->id == $one_acutePain->timing_of_consultation)
                                                $selected = 'selected="selected"';
                                            echo '<option value="' . $raw->id . '" ' . $selected . '>' . $raw->lookup_details . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div id="dvTimingOfConsultation" class="form-group hide">
                                <label class="control-label col-md-3">Specify other
                                </label>
                                <div class="col-md-4 input-group">
                                    <input type="text" id="specify_timing_of_consultation"
                                           name="specify_timing_of_consultation" class="form-control"
                                           value="{{isset($one_acutePain->specify_timing_of_consultation)?$one_acutePain->specify_timing_of_consultation:''}}"/>

                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Pain location
                                </label>
                                <div class="col-md-4 input-group">
                                    <select id="pain_location" name="pain_location[]"
                                            class="form-control select2-multiple" multiple>

                                        <?php
                                        $selected = '';
                                        // print_r($one_pain_location );exit;
                                        foreach ($pain_location_list as $raw) {
                                            $selected = '';
                                            foreach ($one_pain_location as $raw2) {
                                                if ($raw->id == $raw2->location_id)
                                                    $selected = 'selected="selected"';
                                            }
                                            echo '<option value="' . $raw->id . '" ' . $selected . '>' . $raw->name . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Pain Medication before the injury
                                </label>
                                <div class="col-md-4 input-group">
                                    <select id="medication_before_injury" name="medication_before_injury"
                                            class="form-control "
                                            onchange="show_Medication_before_injury()">
                                        <option value="">Select...</option>
                                        <option value="1" @php if(isset($one_acutePain->medication_before_injury)&& $one_acutePain->medication_before_injury==1) echo 'selected="selected"'; @endphp>
                                            Yes
                                        </option>
                                        <option value="0" @php if(isset($one_acutePain->medication_before_injury)&& $one_acutePain->medication_before_injury==0) echo 'selected="selected"'; @endphp>
                                            No
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div id="dvMidectionBeforInjury">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Other Pain Medication before the injury
                                    </label>
                                    <div class="col-md-4 input-group">
                                        <select id="medication_before_injury_list"
                                                name="medication_before_injury_list[]"
                                                class="form-control select2-multiple"
                                                multiple>

                                            <?php
                                            $selected = '';
                                            foreach ($drug_list as $raw) {
                                                $selected = '';
                                                foreach ($one_pain_med_before_inj as $raw2) {
                                                    if ($raw->id == $raw2->drug_id)
                                                        $selected = 'selected="selected"';
                                                }
                                                echo '<option value="' . $raw->id . '" ' . $selected . '>' . $raw->name . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Other medication before injury
                                    </label>
                                    <div class="col-md-4 input-group">
                                        <input type="text" id="other_medication_before_injury"
                                               name="other_medication_before_injury"
                                               class="form-control"
                                               value="{{isset($one_acutePain->other_medication_before_injury)?$one_acutePain->other_medication_before_injury:''}}"/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Pain medication now
                                </label>
                                <div class="col-md-4 input-group">
                                    <select class="form-control select2" id="medication_now" name="medication_now"
                                            onchange="show_pain_medication_now_table()">
                                        <option value="">Select...</option>
                                        <option value="1" @php if(isset($one_acutePain->medication_now)&& $one_acutePain->medication_now==1) echo 'selected="selected"'; @endphp>
                                            Yes
                                        </option>
                                        <option value="0" @php if(isset($one_acutePain->medication_now)&& $one_acutePain->medication_now==0) echo 'selected="selected"'; @endphp>
                                            No
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div id="dvPainMedicationNowTable" class="form-group hide">
                                <div class="alert alert-danger alert-danger-mednow display-hide">
                                    <button class="close" data-close="alert"></button>
                                    You have some form errors. Please check below.
                                </div>
                                <div class="alert alert-success alert-success-mednow display-hide">
                                    <button class="close" data-close="alert"></button>
                                    Your form validation is successful!
                                </div>
                                <div class="col-md-9">
                                    <div class="table-scrollable table-scrollable-borderless">
                                        <table id="tblPainMedicationNow" class="table table-hover table-light">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Druge</th>
                                                <th>Dosage</th>
                                            </tr>
                                            <tr class="uppercase">
                                                <th>
                                                    <div class="form-group"> #</div>
                                                </th>
                                                <td>
                                                    <div class="form-group input-group-sm select2-bootstrap-prepend  col-md-12">
                                                        <select id="drug_id"
                                                                class="form-control select2  input-sm input-xsmall ">
                                                            <option value="">Select...</option>
                                                            <?php
                                                            foreach ($drug_list as $raw)
                                                                echo '<option value="' . $raw->id . '">' . $raw->name . '</option>';
                                                            ?>
                                                        </select>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group col-md-12 ">
                                                        <input type="text" id="dosage"
                                                               class="form-control  input-sm"
                                                               placeholder="Dosage"/>
                                                    </div>
                                                </td>
                                                <td>
                                                @if($painFile_statusId==17 && auth()->user()->id!=100)<!-- 17=Active file,100=guest user -->
                                                        <div class="form-group col-md-12">
                                                            <button type="button" class="btn green"
                                                                    onclick="save_PainMedicationNow();">+
                                                            </button>
                                                        </div>
                                                    @endif
                                                </td>
                                            </tr>
                                            </thead>
                                            <tbody id="tblPainMedicationNowBody">
                                            @php
                                                if(isset($one_pain_med_now))
                                                echo $one_pain_med_now;
                                            @endphp

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Planned further treatment, specify
                                </label>
                                <div class="col-md-4 input-group">
                                    <input type="text" name="planned_further_treatment"
                                           class="form-control"
                                           value="{{isset($one_acutePain->planned_further_treatment)?$one_acutePain->planned_further_treatment:''}}"/>
                                </div>
                            </div>
                            <h3 class="form-section font-green">Neurological Examination</h3>

                            <div class="form-group">
                                <label class="control-label col-md-3">A history of pain due to injury (accidental or
                                    surgical) to one or several well-defined peripheral nerves?
                                </label>
                                <div class="col-md-4 input-group">
                                    <select class="form-control" name="neuro_history_of_pain">
                                        <option value="">Select...</option>
                                        <option value="1" @php if(isset($one_acutePain->neuro_history_of_pain)&& $one_acutePain->neuro_history_of_pain==1) echo 'selected="selected"'; @endphp>
                                            Yes
                                        </option>
                                        <option value="0" @php if(isset($one_acutePain->neuro_history_of_pain)&& $one_acutePain->neuro_history_of_pain==0) echo 'selected="selected"'; @endphp>
                                            No
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Pain localized to the area of the specific
                                    nerve(s)?
                                </label>
                                <div class="col-md-4 input-group">
                                    <select id="neuro_pain_localized" name="neuro_pain_localized"
                                            class="form-control select2" onchange="show_side_and_nerve_table();">

                                        <option value="0" @php if(isset($one_acutePain->neuro_pain_localized)&& $one_acutePain->neuro_pain_localized==0) echo 'selected="selected"'; @endphp>
                                            No
                                        </option>
                                        <option value="1" @php if(isset($one_acutePain->neuro_pain_localized)&& $one_acutePain->neuro_pain_localized==1) echo 'selected="selected"'; @endphp>
                                            Yes
                                        </option>
                                        <option value="2" @php if(isset($one_acutePain->neuro_pain_localized)&& $one_acutePain->neuro_pain_localized==2) echo 'selected="selected"'; @endphp>
                                            Yes,but also other areas
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group dvsideandnerve hide">
                                <label class="control-label col-md-3">
                                    Specify side and nerve(s)
                                </label>
                            </div>
                            <div class="alert alert-danger alert-danger-side display-hide">
                                <button class="close" data-close="alert"></button>
                                You have some form errors. Please check below.
                            </div>
                            <div class="alert alert-success alert-success-side display-hide">
                                <button class="close" data-close="alert"></button>
                                Your form validation is successful!
                            </div>
                            <div class="row dvsideandnerve hide">
                                <div class="col-md-12">
                                    <div class="table-scrollable">
                                        <table class="table table-hover table-light" >
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Area</th>
                                                <th>Side</th>
                                                <th>Light Touch</th>
                                                <th>Pinprick</th>
                                                <th>Warmth</th>
                                                <th>Cold</th>
                                                <th>Control</th>
                                            </tr>
                                            <tr class="uppercase">
                                                <td> #</td>
                                                <td width="20%">
                                                    <div class="form-group input-group-sm select2-bootstrap-prepend  col-md-12">
                                                        <select id="side_nerve_id"
                                                                class="form-control  input-sm"
                                                                onchange="get_nerve_details()">
                                                            <option value="">Select...</option>
                                                            <?php
                                                            foreach ($side_nerves_list as $raw)
                                                                echo '<option value="' . $raw->id . '">' . $raw->lookup_details . '</option>';
                                                            ?>
                                                        </select>
                                                    </div>
                                                </td>
                                                <td width="20%">
                                                    <div class="form-group input-group-sm select2-bootstrap-prepend  col-md-12">
                                                        <select id="side_detail_id" name="side_detail_id"
                                                                class="form-control  input-sm"
                                                                onchange="get_sub_side_details();">
                                                            <option value="">Select...</option>

                                                        </select>
                                                        <select id="sub_side_detail_id" name="sub_side_detail_id"
                                                                class="form-control  input-sm display-hide">
                                                            <option value="">Select..</option>

                                                        </select>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group col-md-12 ">
                                                        <select class="form-control input-sm" name="light_touch"
                                                                id="light_touch">
                                                            <option value="">Select..</option>
                                                            <?php
                                                            foreach ($light_touch_list as $raw)
                                                                echo '<option value="' . $raw->id . '">' . $raw->lookup_details . '</option>';
                                                            ?>
                                                        </select>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group col-md-12 ">
                                                        <select class="form-control input-sm" name="pinprick"
                                                                id="pinprick">
                                                            <option value="">Select..</option>
                                                            <?php
                                                            foreach ($light_touch_list as $raw)
                                                                echo '<option value="' . $raw->id . '">' . $raw->lookup_details . '</option>';
                                                            ?>
                                                        </select>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group col-md-12 ">
                                                        <select class="form-control input-sm" name="warmth" id="warmth">
                                                            <option value="">Select..</option>
                                                            <?php
                                                            foreach ($warmth_list as $raw)
                                                                echo '<option value="' . $raw->id . '">' . $raw->lookup_details . '</option>';
                                                            ?>
                                                        </select>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group  col-md-12">
                                                        <select class="form-control input-sm" name="cold" id="cold">
                                                            <option value="">Select..</option>
                                                            <?php
                                                            foreach ($warmth_list as $raw)
                                                                echo '<option value="' . $raw->id . '">' . $raw->lookup_details . '</option>';
                                                            ?>
                                                        </select>
                                                    </div>
                                                </td>
                                                <td>
                                                @if($painFile_statusId==17 && auth()->user()->id!=100)<!-- 17=Active file,100=guest user -->
                                                        <button type="button" class="btn green"
                                                                onclick="save_side_and_nerve();">+
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                            </thead>
                                            <tbody id="side_and_nerve_table_body">
                                            @php
                                                if(isset($one_pain_sideNerve))
                                                echo $one_pain_sideNerve;
                                            @endphp

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">
                                    If stump pain. Describe level of amputation and distrbutionof pain
                                </label>
                                <div class="col-md-4 input-group">
                                    <input type="text" name="neuro_stump_distribution_of_pain"
                                           class="form-control"
                                           value="{{isset($one_acutePain->neuro_stump_distribution_of_pain)?$one_acutePain->neuro_stump_distribution_of_pain:''}}"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">
                                    If phantom pain. Describe level of amputation and type of PLP
                                </label>
                                <div class="col-md-4 input-group">
                                    <input type="text" name="neuro_phantom_type_of_plp"
                                           class="form-control"
                                           value="{{isset($one_acutePain->neuro_phantom_type_of_plp)?$one_acutePain->neuro_phantom_type_of_plp:''}}"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">
                                    Other specified findings on the neurological exam
                                </label>
                                <div class="col-md-4 input-group">
                                    <input type="text" name="neuro_other_finding"
                                           class="form-control"
                                           value="{{isset($one_acutePain->neuro_other_finding)?$one_acutePain->neuro_other_finding:''}}"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Second Doctor
                                </label>
                                <div class="col-md-4 input-group">
                                    <select id="second_doctor_id" name="second_doctor_id" class="form-control select2">
                                        <option value="">Select...</option>
                                        <?php
                                        $selected = '';
                                        foreach ($doctor_list as $raw) {
                                            $selected = '';
                                            if (isset($one_acutePain->second_doctor_id) && $raw->id == $one_acutePain->second_doctor_id)
                                                $selected = 'selected="selected"';
                                            echo '<option value="' . $raw->id . '" ' . $selected . '>' . $raw->name . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    @if($painFile_statusId==17 && auth()->user()->id!=100)<!-- 17=Active file,100=guest user -->
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" class="btn green">Submit</button>
                                        <button type="button" class="btn grey-salsa btn-outline">Cancel</button>
                                    </div>
                                </div>
                            </div>
                    @endif
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
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <link href="{{url('/')}}/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css"
              rel="stylesheet"
              type="text/css"/>
        <link href="{{url('/')}}/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css"
              rel="stylesheet"
              type="text/css"/>
        <link href="{{url('/')}}/assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css"
              rel="stylesheet"
              type="text/css"/>
        <link href="{{url('/')}}/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css"
              rel="stylesheet" type="text/css"/>
        <link href="{{url('/')}}/assets/global/plugins/clockface/css/clockface.css" rel="stylesheet" type="text/css"/>
        <link href="{{url('/')}}/assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css"/>
        <link href="{{url('/')}}/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet"
              type="text/css"/>

        <!-- END HEAD -->

    @endpush
    @push('js')

        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="{{url('/')}}/assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
        <script src="{{url('/')}}/assets/global/plugins/moment.min.js" type="text/javascript"></script>
        <script src="{{url('/')}}/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js"
                type="text/javascript"></script>
        <script src="{{url('/')}}/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"
                type="text/javascript"></script>
        <script src="{{url('/')}}/assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js"
                type="text/javascript"></script>
        <script src="{{url('/')}}/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"
                type="text/javascript"></script>


        <script src="{{url('')}}/assets/global/plugins/jquery-validation/js/jquery.validate.min.js"
                type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->

        <!-- END THEME LAYOUT SCRIPTS -->

        <script>


            var acutepainFormValidation = function () {

                // basic validation
                var handleValidation1 = function () {
                    // for more info visit the official plugin documentation:
                    // http://docs.jquery.com/Plugins/Validation

                    var form1 = $('#acutePain_form');
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
                                visit_date: {
                                    required: true
                                },
                                /*  first_war_injury: {
                                      required: true
                                  }, injury_mechanism: {
                                      required: true
                                  }, status: {
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

                                acutepainSubmit();


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


            acutepainFormValidation.init();

            function acutepainSubmit() {
                App.blockUI();
                var form1 = $('#acutePain_form');
                var error = $('.alert-danger', form1);
                var success = $('.alert-success', form1);

                var action = $('#acutePain_form').attr('action');

                var formData = new FormData($('#acutePain_form')[0]);
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

                    }
                )

            }

            function save_side_and_nerve() {
                blockUI('side_and_nerve_table_body');
                var side_nerve_id = $('#side_nerve_id').val();
                var side_detail_id = $('#side_detail_id').val();
                var sub_side_detail_id = $('#sub_side_detail_id').val();
                var light_touch = $('#light_touch').val();
                var pinprick = $('#pinprick').val();
                var warmth = $('#warmth').val();
                var cold = $('#cold').val();
                $("#side_nerve_id").parent().removeClass('has-error');
                if (side_nerve_id == '') {
                    $('.alert-danger-side').show();
                    $('.alert-danger-side').fadeOut(2000);
                    $("#side_nerve_id").parent().addClass('has-error');
                    return;
                } else
                    $("#side_nerve_id").parent().removeClass('has-error');


                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "POST",
                    url: '{{url('acutepain/side_and_nerve')}}',

                    data: {
                        side_nerve_id: side_nerve_id,
                        side_detail_id: side_detail_id,
                        sub_side_detail_id: sub_side_detail_id,
                        light_touch: light_touch,
                        pinprick: pinprick,
                        warmth: warmth,
                        cold: cold,
                        painFile_id: $('#painFile_id').val(),
                        painFile_status: $('#painFile_statusid').val()
                    },
                    error: function (xhr, status, error) {

                    },
                    beforeSend: function () {
                    },
                    complete: function () {
                    },
                    success: function (data) {
                        unblockUI('side_and_nerve_table_body');
                        if (data.success) {
                            $('.alert-success-side').show();
                            $('.alert-success-side').fadeOut(2000);
                            $('#side_and_nerve_table_body').html(data.html);
                        } else {
                            alert('error');
                        }
                    }
                });//END $.ajax
            }

            function get_nerve_details() {
                var side_nerve_id = $('#side_nerve_id').val();
                if (side_nerve_id == 141)
                    $('#sub_side_detail_id').removeClass('display-hide');
                else {
                    $('#sub_side_detail_id').addClass('display-hide');

                }
                $('#side_detail_id').html('');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('acutepain/get_nerve_details')}}',

                    data: {
                        id: side_nerve_id,

                    },
                    error: function (xhr, status, error) {

                    },
                    beforeSend: function () {
                    },
                    complete: function () {
                    },
                    success: function (data) {

                        if (data.success) {
                            $('#side_detail_id').html(data.html)
                        } else {
                            alert('error');
                        }
                    }
                });//END $.ajax
            }

            function get_sub_side_details() {
                var side_detail_id = $('#side_detail_id').val();
                if (side_detail_id == 141)

                    $('#sub_side_detail_id').html('');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '<?php echo e(url('acutepain/get_sub_side_details')); ?>',

                    data: {
                        id: side_detail_id
                    },
                    error: function (xhr, status, error) {

                    },
                    beforeSend: function () {
                    },
                    complete: function () {
                    },
                    success: function (data) {

                        if (data.success)
                            $('#sub_side_detail_id').html(data.html)
                        else {
                            alert('error');
                        }
                    }
                });//END $.ajax
            }

            function del_nerve(id) {
                blockUI('side_and_nerve_table_body');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('acutepain/del_side_and_nerve')}}',

                    data: {
                        id: id,
                        painFile_id: $('#painFile_id').val(),
                        painFile_status: $('#painFile_statusid').val()
                    },
                    error: function (xhr, status, error) {

                    },
                    beforeSend: function () {
                    },
                    complete: function () {
                    },
                    success: function (data) {
                        unblockUI('side_and_nerve_table_body');
                        if (data.success) {
                            $('.alert-success-side').show();
                            $('.alert-success-side').fadeOut(2000);
                            $('#side_and_nerve_table_body').html(data.html)
                        } else {
                            alert('error');
                        }
                    }
                });//END $.ajax
            }

            function save_PainMedicationNow() {
                blockUI('tblPainMedicationNowBody');
                var drug_id = $('#drug_id').val();
                var dosage = $('#dosage').val();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $("#dosage").parent().removeClass('has-error');
                $("#drug_id").parent().removeClass('has-error');
                if (drug_id == '' || dosage == '') {
                    $('.alert-danger-mednow').show();
                    $('.alert-danger-mednow').fadeOut(2000);
                    if (drug_id == '')
                        $("#drug_id").parent().addClass('has-error');
                    else
                        $("#drug_id").parent().removeClass('has-error');
                    if (dosage == '')
                        $("#dosage").parent().addClass('has-error');
                    else
                        $("#dosage").parent().removeClass('has-error');
                    return;
                }
                $.ajax({
                    type: "POST",
                    url: '{{url('acutepain/medicationNow')}}',

                    data: {

                        drug_id: drug_id,
                        dosage: dosage,
                        painFile_id: $('#painFile_id').val(),
                        painFile_status: $('#painFile_statusid').val()
                    },
                    error: function (xhr, status, error) {

                    },
                    beforeSend: function () {
                    },
                    complete: function () {
                    },
                    success: function (data) {
                        unblockUI('tblPainMedicationNowBody');
                        if (data.success) {
                            $('.alert-success-mednow').show();
                            $('.alert-success-mednow').fadeOut(2000);
                            $('#tblPainMedicationNowBody').html(data.html)
                        } else {
                            alert('error');
                        }
                    }
                });//END $.ajax


            }

            function del_medicationNow(id) {
                blockUI('tblPainMedicationNowBody');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('acutepain/del_medicationNow')}}',

                    data: {
                        id: id,
                        painFile_id: $('#painFile_id').val(),
                        painFile_status: $('#painFile_statusid').val()
                    },
                    error: function (xhr, status, error) {

                    },
                    beforeSend: function () {
                    },
                    complete: function () {
                    },
                    success: function (data) {
                        unblockUI('tblPainMedicationNowBody');
                        if (data.success) {
                            $('.alert-success-mednow').show();
                            $('.alert-success-mednow').fadeOut(2000);
                            $('#tblPainMedicationNowBody').html(data.html)
                        } else {
                            alert('error');
                        }
                    }
                });//END $.ajax
            }

            function show_specify_injury_mechanism() {


                if ($("#injury_mechanism").val() == 33)

                    $("#dvSpecifyInjuryMechanism").removeClass('hide');
                else {
                    $("#specify_injury_mechanism").val('');
                    $("#dvSpecifyInjuryMechanism").addClass('hide');

                }

            }

            function show_specify_status() {
                if ($("#status").val() == 39)
                    $("#dvSpecifyStatus").removeClass('hide');
                else {
                    $("#specify_status").val('');
                    $("#dvSpecifyStatus").addClass('hide');

                }
            }

            function show_specify_timing_of_consultation() {
                if ($("#timing_of_consultation").val() == 44)
                    $("#dvTimingOfConsultation").removeClass('hide');
                else {
                    $("#specify_timing_of_consultation").val('');
                    $("#dvTimingOfConsultation").addClass('hide');

                }
            }

            function show_pain_medication_now_table() {
                if ($("#medication_now").val() == 1)
                    $("#dvPainMedicationNowTable").removeClass('hide');
                else {
                    // alert($('#tblPainMedicationNowBody > tr').length);
                    if ($('#tblPainMedicationNowBody >tr').length > 0) {
                        $("#medication_now").val(1);
                        $("#medication_now").trigger('change');
                        //   alert('Please delete added medications');
                    } else
                        $("#dvPainMedicationNowTable").addClass('hide');

                }
            }

            function show_Medication_before_injury() {
                if ($("#medication_before_injury").val() == 1)
                    $("#dvMidectionBeforInjury").removeClass('hide');
                else {

                    $("#medication_before_injury_list").val('');
                    $("#medication_before_injury_list").trigger('change');

                    $("#other_medication_before_injury").val('');
                    $("#dvMidectionBeforInjury").addClass('hide');

                }
            }

            function show_side_and_nerve_table() {
                if ($("#neuro_pain_localized").val() == 1 || $("#neuro_pain_localized").val() == 2)
                    $(".dvsideandnerve").removeClass('hide');
                else {
                    // alert($('#tblPainMedicationNowBody').length);
                    if ($('#side_and_nerve_table_body > tr').length > 0) {
                        $("#neuro_pain_localized").val(1);
                        $("#neuro_pain_localized").trigger('change');
                        //  alert('Please delete side and nerve');
                    } else
                        $(".dvsideandnerve").addClass('hide');

                }
            }

            $(document).ready(function () {
                show_specify_injury_mechanism();
                show_specify_status();
                show_specify_timing_of_consultation();
                show_pain_medication_now_table();
                show_Medication_before_injury();
                show_side_and_nerve_table();
                /* $('#pain_location').val('');
                 $('#medication_before_injury').val('');

                 $('#medication_before_injury_list').val('');
                 $('.select2').val('');
                 $('.form-control').val('');
 */
                // $('.select2').trigger('change');
                $('.date-picker').datepicker({
                    rtl: App.isRTL(),
                    orientation: "left",
                    autoclose: true,
                    endDate: '0d',
                    todayHighlight: true
                });
                $(".select2, .select2-multiple").select2({
                    width: null
                });
            });

        </script>
    @endpush
@stop