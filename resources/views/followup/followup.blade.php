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
        <div id="accordion1" class="panel-group">

            <div class="panel panel-success">
                <div class="panel-heading">
                @if($painFile_statusId==17 && auth()->user()->id!=100)<!-- 17=Active file,100=guest user -->
                    <h4 class="panel-title">
                        <a class="accordion-toggle accordion-toggle-styled" data-toggle="collapse"
                           data-parent="#accordion1" href="#notification"
                           onclick="get_message();"><i class="icon-bell"></i>
                            Message center </a>
                        <a data-toggle="modal" href="#msgModal" onclick="get_all_message();"><span
                                    class="btn btn-circle btn-xs red  pull-right ">Show all</span> </a>
                    </h4>
                    @endif
                </div>
                <div id="notification" class="panel-collapse collapse">
                    <div class="panel-body">

                        <div class="scroller" data-height="150px" data-always-visible="1" data-rail-visible1="1">

                            <ul class="feeds" id="feeds">


                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light portlet-fit ">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-directions font-green hide"></i>
                            <span class="caption-subject bold font-green uppercase "> Follow Up Visits</span>
                            <span class="caption-helper">Timeline</span>
                        </div>
                        <div class="actions">
                        @if($painFile_statusId==17 && auth()->user()->id!=100)<!-- 17=Active file,100=guest user -->
                            <a class="btn green btn-outline sbold uppercase" href="#form_modal2"
                               data-toggle="modal"> Create New Follow Up
                                <i class="fa fa-share"></i>
                            </a>
                            @endif
                            <a class="btn blue   uppercase" href="#charts_modal"
                               data-toggle="modal" onclick="show_score_chart();"> Score Charts
                                <i class="fa fa-line-chart"></i>
                            </a>

                            {{-- <a href="javascript:;" class="btn btn-circle green btn-outline"> Add New Follow Up </a>--}}

                        </div>
                    </div>
                    <div id="form_modal2" class="modal fade" role="dialog" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal"
                                            aria-hidden="true"></button>
                                    <h4 class="modal-title ">New Follow Up </h4>
                                </div>

                                {{Form::open(['url'=>url('followup/newfollowup'),'class'=>'form-horizontal','method'=>"post","id"=>"followup_form"])}}
                                <div class="modal-body">
                                    <div class="alert alert-danger display-hide">
                                        <button class="close"
                                                data-close="alert"></button>
                                        This follow up date exist. Please check follow up date
                                        below.
                                    </div>
                                    <input type="hidden" id="painFile_id" name="painFile_id" value="{{$painFile_id}}">
                                    <input type="hidden" id="painFile_statusid" name="painFile_statusid"
                                           value="{{$painFile_statusId}}">
                                    <div class="alert alert-success display-hide">
                                        <button class="close"
                                                data-close="alert"></button>
                                        Your form validation is successful!
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Date</label>
                                        <div class="col-md-8 input-group">
                                            <input class="form-control input-medium date-picker" size="16"
                                                   name="new_followup_date" id="new_followup_date"
                                                   type="text" value="{{date('Y-m-d')}}" data-date-format="yyyy-mm-dd"/>
                                        </div>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button id="create_followup_btn" type="button" class="btn green"
                                            onclick="check_available_date();">Create
                                    </button>
                                    <button class="btn dark btn-outline" data-dismiss="modal" aria-hidden="true">Close
                                    </button>
                                </div>
                                {{Form::close()}}

                            </div>
                        </div>
                    </div>

                    <div class="modal fade bs-modal-lg" id="charts_modal" tabindex="-1" role="dialog"
                         aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal"
                                            aria-hidden="true"></button>
                                    <h4 class="modal-title ">Follow Up Charts </h4>
                                </div>


                                <div class="modal-body">
                                    <ul class="nav nav-pills">
                                        <li class="active">
                                            <a href="#tab_2_1" data-toggle="tab"> Scores </a>
                                        </li>
                                        <li>
                                            <a href="#tab_2_2" data-toggle="tab"> Treatment Goals </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane fade active in" id="tab_2_1">

                                            <div id="dvScoreChart" class="chart" style="height: 525px;">
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="tab_2_2">

                                            <div id="dvGoalsChart" class="chart" style="height: 525px;">
                                            </div>
                                        </div>

                                    </div>


                                </div>
                                <div class="modal-footer">

                                    <button class="btn dark btn-outline" data-dismiss="modal" aria-hidden="true">Close
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>

                    <input type="hidden" id="painFile_id" name="painFile_id" value="{{$painFile_id}}">
                    <input type="hidden" id="painFile_statusid" name="painFile_statusid" value="{{$painFile_statusId}}">
                    <input type="hidden" id="hdnqutenza_id" name="hdnqutenza_id" value="{{''}}">
                    <?php
                    if (count($all_followups) > 0)
                    {?>
                    <input type="hidden" id="followup_id" value="{{$last_followups}}">
                    <div class="portlet-body">
                        <div class="cd-horizontal-timeline mt-timeline-horizontal" data-spacing="60">
                            <div class="timeline">
                                <div class="events-wrapper">
                                    <div class="events">
                                        <ol>
                                            <?php

                                            $selected = '';
                                            foreach($all_followups as $row)
                                            {
                                            $selected = '';
                                            $value = 0;
                                            if ($last_followups == $row->id) {
                                                $selected = 'selected';
                                                $value = 1;

                                            }
                                            ?>
                                            <li>
                                                <a href="#0"
                                                   data-date="{{\Carbon\Carbon::parse($row->follow_up_date)->format('d/m/Y')}}"
                                                   data-id="{{$row->id}}" data-uploaded="{{$value}}"
                                                   class="border-after-red bg-after-red  {{$selected}}">
                                                    {{\Carbon\Carbon::parse($row->follow_up_date)->format('d/m')}}</a>
                                            </li>
                                            <?php
                                            }
                                            ?>
                                        </ol>
                                        <span class="filling-line bg-red" aria-hidden="true"></span>
                                    </div>
                                    <!-- .events -->
                                </div>
                                <!-- .events-wrapper -->
                                <ul class="cd-timeline-navigation mt-ht-nav-icon">
                                    <li>
                                        <a href="#0" class="prev inactive btn btn-outline red md-skip">
                                            <i class="fa fa-chevron-left"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#0" class="next btn btn-outline red md-skip">
                                            <i class="fa fa-chevron-right"></i>
                                        </a>
                                    </li>
                                </ul>
                                <!-- .cd-timeline-navigation -->
                            </div>
                            <!-- .timeline -->
                            <div class="events-content">
                                <ol>
                                    <?php
                                    $selected = '';
                                    foreach($all_followups as $row)
                                    {
                                    $selected = '';
                                    $print = 0;
                                    if ($last_followups == $row->id) {
                                        $selected = 'selected';
                                        $print = 1;
                                    }
                                    ?>
                                    <li class="{{$selected}}"
                                        data-date="{{\Carbon\Carbon::parse($row->follow_up_date)->format('d/m/Y')}}">
                                        <div class="mt-title">
                                            <h2 class="mt-content-title uppercase">Follow Up Visit</h2>
                                            <div class="mt-author-datetime font-blue-madison">{{$row->follow_up_date}}

                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="mt-content border-grey-steel">
                                            <div class="tabbable-custom nav-justified">
                                                <ul class="nav nav-tabs nav-justified">
                                                    <li class="active ">
                                                        <a href="#tab_1_1_1_{{$row->id}}" data-toggle="tab"> Doctor
                                                            Consultation </a>
                                                    </li>


                                                    <li>
                                                        <a href="#tab_1_1_2_{{$row->id}}" data-toggle="tab">
                                                            Pharmacist
                                                            Consultation </a>
                                                    </li>


                                                    <li>
                                                        <a href="#tab_1_1_3_{{$row->id}}" data-toggle="tab"> Nurse
                                                            Consultation </a>
                                                    </li>
                                                    <li>
                                                        <a href="#tab_1_1_4_{{$row->id}}" data-toggle="tab">
                                                            Psychologist Consultation</a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="tab-content">
                                                @php
                                                    //  $followupDoctor_style='style="pointer-events: none;"';
                                                         $followupDoctor_style='';
                                                @endphp
                                                @if(isset($one_followup_doctor->created_by) &&  $one_followup_doctor->created_by==auth()->user()->id)
                                                    @php

                                                        $followupDoctor_style='';
                                                    @endphp
                                                @elseif(isset($one_followup_doctor->created_by) &&  $one_followup_doctor->created_by!=auth()->user()->id)
                                                    @php
                                                        $followupDoctor_style='style="pointer-events: none;"';
                                                         //  $followupDoctor_style='';
                                                    @endphp
                                                @elseif(!isset($one_followup_doctor->created_by) && auth()->user()->user_type_id==9)
                                                    @php

                                                        $followupDoctor_style='';
                                                    @endphp

                                                @endif

                                                <div class="tab-pane active"
                                                     id="tab_1_1_1_{{$row->id}}">
                                                    <!-- BEGIN FORM-->

                                                    {{Form::open(['url'=>url('followupDoctor'),'class'=>'form-horizontal','method'=>"post","id"=>"doctor_followup_form$row->id"])}}

                                                    <div class="form-body">
                                                        <div class="alert alert-danger display-hide">
                                                            <button class="close"
                                                                    data-close="alert"></button>
                                                            You have some form errors. Please check
                                                            below.
                                                        </div>
                                                        <div class="alert alert-success display-hide">
                                                            <button class="close"
                                                                    data-close="alert"></button>
                                                            Your form validation is successful!
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-3">Visit
                                                                Date
                                                                <span class="required"> * </span>
                                                            </label>
                                                            <div class="col-md-4 input-group">
                                                                <input class="form-control date-picker"
                                                                       type="text"
                                                                       name="doc_follow_up_date"
                                                                       id="doc_follow_up_date{{$row->id}}"
                                                                       data-date-format="yyyy-mm-dd"
                                                                       readonly="readonly"
                                                                       value="{{(isset($row->follow_up_date)&& $print)?$row->follow_up_date:''}}"/>
                                                                <span class="help-block"> Select date </span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-3">Examined by Doctor
                                                            </label>
                                                            <div class="col-md-4">
                                                                <h4 class=""><span class="label label-info"
                                                                                   id="firstDoctor{{$row->id}}"> {{(isset($doctor_name) && $print)?$doctor_name:''}} </span>
                                                                </h4>
                                                            </div>

                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-3">Second
                                                                Doctor
                                                            </label>
                                                            <div class="col-md-4">
                                                                <select class="form-control select2"
                                                                        name="second_doctor"
                                                                        id="second_doctor{{$row->id}}">
                                                                    <option value="">Select...</option>
                                                                    <?php
                                                                    $selected = '';
                                                                    foreach ($doctor_list as $raw) {
                                                                        $selected = '';
                                                                        if ((isset($one_followup_doctor->second_doctor) && $print) && $raw->id == $one_followup_doctor->second_doctor)
                                                                            $selected = 'selected="selected"';
                                                                        echo '<option value="' . $raw->id . '" ' . $selected . '>' . $raw->name . '</option>';
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="alert alert-warning">
                                                            Rate the current level of difficulty associated with
                                                            each
                                                            activity on a scale
                                                            from 0 to 10. "0" represents “unable to perform”, and
                                                            "10"
                                                            represents “able
                                                            to perform at prior level”.
                                                        </div>
                                                        <h3 class="form-section font-green"> Treatment Goals </h3>
                                                        <hr/>

                                                        <div class="form-group">
                                                            <label class="control-label col-md-3">Comments.
                                                            </label>
                                                            <div class="col-md-4">
                                                                <input type="text"
                                                                       name="treatment_goal_achievements"
                                                                       id="treatment_goal_achievements{{$row->id}}"
                                                                       class="form-control"
                                                                       value="{{(isset($one_followup_doctor->treatment_goal_achievements)&& $print)?$one_followup_doctor->treatment_goal_achievements:''}}"/>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="alert alert-success alert-goal display-hide">
                                                                    <button class="close"
                                                                            data-close="alert"></button>
                                                                    Your form validation is successful!
                                                                </div>
                                                                <div class="table-scrollable table-scrollable-borderless">

                                                                {{-- @if(count($all_followups) == 1)--}}
                                                                @if($painFile_statusId==17 && auth()->user()->id!=100)<!-- 17=Active file,100=guest user -->
                                                                    <a href="javascript:;"
                                                                       onclick="refreshGoals({{$row->id}})"
                                                                       class="btn btn-icon-only blue">
                                                                        <i class="fa fa-refresh"></i>
                                                                    </a>
                                                                    @endif
                                                                    {{--   @endif--}}
                                                                    <table class="table table-striped table-hover">
                                                                        <thead>
                                                                        <tr class="uppercase">
                                                                            <th> #</th>
                                                                            <th width="60%">
                                                                                GOAL
                                                                            </th>
                                                                            <th width="20%">
                                                                                SCORE
                                                                            </th>
                                                                            <th width="20%">
                                                                                COMPLIANCE
                                                                            </th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody id="tbdoctortreatment{{$row->id}}">
                                                                        @php
                                                                            if ($last_followups == $row->id)
                                                                              echo $treat_doc_goal;
                                                                        @endphp
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="alert alert-info">
                                                            <strong>Info!</strong> Total score = sum of
                                                            the
                                                            activity scores/number of activities. <br/>
                                                            Minimum detectable change for average score
                                                            = 2
                                                            points. <br/>
                                                        </div>
                                                        <h3 class="form-section font-green"> Pain
                                                            Characteristics </h3>
                                                        <hr/>

                                                        <div class="form-group">
                                                            <label class="control-label col-md-3">During the last
                                                                week,
                                                                how has your
                                                                usual
                                                                pain intensity been on a scale from 0 to 10,with zero
                                                                meaning “no pain” and 10 meaning “the worst pain
                                                                imaginable”?

                                                            </label>
                                                            <div class="col-md-4">
                                                                <input type="number" max="10" min="0"
                                                                       name="pain_scale"
                                                                       id="pain_scale{{$row->id}}"
                                                                       class="form-control"
                                                                       value="{{(isset($one_followup_doctor->pain_scale) && $print)?$one_followup_doctor->pain_scale:''}}"/>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-3">During the last
                                                                week,
                                                                how has your
                                                                usual
                                                                pain bothersomeness been on a scale from 0 to 10,with
                                                                zero meaning “no pain” and 10 meaning “the worst pain
                                                                imaginable”?

                                                            </label>
                                                            <div class="col-md-4">
                                                                <input type="number" max="10" min="0"
                                                                       name="pain_bothersomeness"
                                                                       id="pain_bothersomeness{{$row->id}}"
                                                                       class="form-control"
                                                                       value="{{(isset($one_followup_doctor->pain_bothersomeness) && $print)?$one_followup_doctor->pain_bothersomeness:''}}"/>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-3">What is your usual
                                                                pain intensity during rest on a scale from 0 to 10,with
                                                                zero meaning “no pain” and 10 meaning “the worst pain
                                                                imaginable”?

                                                            </label>
                                                            <div class="col-md-4">
                                                                <input type="number" max="10" min="0"
                                                                       name="pain_intensity_during_rest"
                                                                       id="pain_intensity_during_rest{{$row->id}}"
                                                                       class="form-control"
                                                                       value="{{(isset($one_followup_doctor->pain_intensity_during_rest) && $print)?$one_followup_doctor->pain_intensity_during_rest:''}}"/>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-3">What is your usual
                                                                pain intensity during activity on a scale from 0 to
                                                                10,with zero meaning “no pain” and 10 meaning “the worst
                                                                pain imaginable”?

                                                            </label>
                                                            <div class="col-md-4">
                                                                <input type="number" max="10" min="0"
                                                                       name="pain_intensity_during_activity"
                                                                       id="pain_intensity_during_activity{{$row->id}}"
                                                                       class="form-control"
                                                                       value="{{(isset($one_followup_doctor->pain_intensity_during_activity) && $print)?$one_followup_doctor->pain_intensity_during_activity:''}}"/>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-3">Since the previous consultation at the Gaza pain clinic, is your pain better, worse or the same?
                                                                <span class="required"> * </span>
                                                            </label>
                                                            <div class="col-md-4">
                                                                <select id="health_rate{{$row->id}}" name="health_rate"
                                                                        class="form-control select2">
                                                                    <option value="">Select...</option>
                                                                    <?php
                                                                    $selected = '';
                                                                    foreach ($health_rate_list as $raw) {
                                                                        $selected = '';
                                                                        if (isset($one_followup_nurse->health_rate) && $raw->id == $one_followup_nurse->health_rate)
                                                                            $selected = 'selected="selected"';
                                                                        echo '<option value="' . $raw->id . '" ' . $selected . '>' . $raw->lookup_details . '</option>';
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <h3 class="form-section font-green">
                                                            Diagnostics </h3>
                                                        <hr/>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-3">Diagnosis

                                                            </label>
                                                            <div class="col-md-8">
                                                                <select id="diagnosis_id{{$row->id}}"
                                                                        name="diagnosis_id[]"
                                                                        class="form-control select2-multiple"
                                                                        multiple>
                                                                    <option value=""></option>
                                                                    <?php
                                                                    foreach ($diagnosis_list as $raw) {
                                                                        $selected = '';
                                                                        if ((isset($one_followup_diagnosis) && $print))
                                                                            foreach ($one_followup_diagnosis as $raw2) {
                                                                                if ($raw->id == $raw2->diagnostic_id && $print)
                                                                                    $selected = 'selected="selected"';
                                                                            }
                                                                        echo '<option value="' . $raw->id . '" ' . $selected . '>' . $raw->name . '</option>';
                                                                    }

                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-3">Need to change the
                                                                diagnosis?<span class="font-red"> (Under construction)</span>

                                                            </label>
                                                            <div class="col-md-6">
                                                                <select id="change_diagnosis{{$row->id}}"
                                                                        name="change_diagnosis"
                                                                        class="form-control select2"
                                                                        onchange="show_dvDiagnosisSpecify({{$row->id}})">
                                                                    <option value="">Select...</option>
                                                                    <option value="0" @php if(isset($one_followup_doctor->change_diagnosis)&& $one_followup_doctor->change_diagnosis==0) echo 'selected="selected"'; @endphp>
                                                                        No
                                                                    </option>
                                                                    <option value="1" @php if(isset($one_followup_doctor->change_diagnosis)&& $one_followup_doctor->change_diagnosis==1) echo 'selected="selected"'; @endphp>
                                                                        Yes
                                                                    </option>

                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div id="dvDiagnosisSpecify{{$row->id}}"class="hide">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-3">
                                                                    If change Diagnosis, specify<span class="font-red"> (Under construction)</span>
                                                                </label>
                                                                <div class="col-md-8">
                                                                    <select id="diagnosis_id{{$row->id}}"
                                                                            name="diagnosis_id[]"
                                                                            class="form-control select2-multiple"
                                                                            multiple>
                                                                        <option value=""></option>
                                                                        <?php
                                                                        foreach ($diagnosis_list as $raw) {
                                                                            $selected = '';
                                                                            if ((isset($one_followup_diagnosis) && $print))
                                                                                foreach ($one_followup_diagnosis as $raw2) {
                                                                                    if ($raw->id == $raw2->new_diagnostic_id && $print)
                                                                                        $selected = 'selected="selected"';
                                                                                }
                                                                            echo '<option value="' . $raw->id . '" ' . $selected . '>' . $raw->name . '</option>';
                                                                        }

                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            <!--                                                                <div class="col-md-8">
                                                                    <input type="text" id="diagnostic_specify{{$row->id}}" name="diagnostic_specify"
                                                                           class="form-control"
                                                                           @if($followup_doctor_count==0)
                                                                value="{{(isset($one_baseline_doctor->diagnostic_specify_combination) && $print)?$one_baseline_doctor->diagnostic_specify_combination:''}}"
                                                                           @else
                                                                value="{{(isset($one_followup_doctor->diagnostic_specify) && $print)?$one_followup_doctor->diagnostic_specify:''}}"

                                                                            @endif
                                                                    />
                                                                </div>-->
                                                            </div>
                                                        </div>
                                                    <!--                                                        <div class="form-group">
                                                            <label class="control-label col-md-3">Additional PTSD</label>
                                                            <div class="col-md-8">
                                                                <select id="additional_ptsd{{$row->id}}"
                                                                        name="additional_ptsd"
                                                                        class="form-control select2">
                                                                    <option value="">Select...</option>
                                                                    @if($followup_doctor_count==0)
                                                        <option value="1" @php if((isset($one_baseline_doctor->diagnostic_additional_ptsd)&& $print)&& $one_baseline_doctor->diagnostic_additional_ptsd==1) echo 'selected="selected"'; @endphp>
                                                                            Yes
                                                                        </option>
                                                                        <option value="0" @php if((isset($one_baseline_doctor->diagnostic_additional_ptsd)&& $print)&& $one_baseline_doctor->diagnostic_additional_ptsd==0) echo 'selected="selected"'; @endphp>
                                                                            No
                                                                        </option>
                                                                    @else
                                                        <option value="1" @php if((isset($one_followup_doctor->additional_ptsd)&& $print)&& $one_followup_doctor->additional_ptsd==1) echo 'selected="selected"'; @endphp>
                                                                            Yes
                                                                        </option>
                                                                        <option value="0" @php if((isset($one_followup_doctor->additional_ptsd)&& $print)&& $one_followup_doctor->additional_ptsd==0) echo 'selected="selected"'; @endphp>
                                                                            No
                                                                        </option>
                                                                    @endif
                                                            </select>
                                                        </div>
                                                    </div>-->
                                                        <div class="form-group">
                                                            <label class="control-label col-md-3">Comments</label>
                                                            <div class="col-md-8">
                                                          <textarea name="followup_doctor_comment"
                                                                    id="followup_doctor_comment{{$row->id}}"
                                                                    class="form-control" rows="3"
                                                                    placeholder="Doctor's comments">@php if(isset($one_followup_doctor->followup_doctor_comment)&& $print) echo $one_followup_doctor->followup_doctor_comment; @endphp</textarea>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-3">Lab. results</label>
                                                            <div class="col-md-8">
                                                          <textarea name="followup_doctor_lab"
                                                                    id="followup_doctor_lab{{$row->id}}"
                                                                    class="form-control" rows="3"
                                                                    placeholder="Lab results">@php if(isset($one_followup_doctor->followup_doctor_lab)&& $print) echo $one_followup_doctor->followup_doctor_lab; @endphp</textarea>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-3">Radiology
                                                                Reports</label>
                                                            <div class="col-md-8">
                                                          <textarea name="followup_doctor_rad"
                                                                    id="followup_doctor_rad{{$row->id}}"
                                                                    class="form-control" rows="3"
                                                                    placeholder="Radiology report">@php if(isset($one_followup_doctor->followup_doctor_rad)&& $print) echo $one_followup_doctor->followup_doctor_rad; @endphp</textarea>
                                                            </div>
                                                        </div>
                                                        <h3 class="form-section font-green"> Treatment
                                                            choice(s) </h3>
                                                        <hr/>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-3">Change
                                                                Treatment
                                                            </label>
                                                            <div class="col-md-4">
                                                                <select id="change_treatment{{$row->id}}"
                                                                        name="change_treatment"
                                                                        class="form-control select2">
                                                                    <option value="">Select...</option>
                                                                    <option value="1" @php if(isset($one_followup_doctor->change_treatment)&& $print && $one_followup_doctor->change_treatment==1) echo 'selected="selected"'; @endphp>
                                                                        Yes
                                                                    </option>
                                                                    <option value="0" @php if(isset($one_followup_doctor->change_treatment)&& $print && $one_followup_doctor->change_treatment==0) echo 'selected="selected"'; @endphp>
                                                                        No
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-3">Physical
                                                                treatment
                                                            </label>
                                                            <div class="col-md-4">
                                                                <select id="physical_treatment{{$row->id}}"
                                                                        name="physical_treatment"
                                                                        class="form-control select2">
                                                                    <option value="">Select...</option>
                                                                    <?php
                                                                    $selected = '';
                                                                    foreach ($physical_treatment_list as $raw) {
                                                                        $selected = '';
                                                                        if ((isset($one_followup_doctor->physical_treatment) && $print) && $raw->id == $one_followup_doctor->physical_treatment)
                                                                            $selected = 'selected="selected"';

                                                                        echo '<option value="' . $raw->id . '" ' . $selected . '>' . $raw->lookup_details . '</option>';
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="alert alert-danger alert-danger-treatment{{$row->id}} display-hide">
                                                            <button class="close" data-close="alert"></button>
                                                            You have some form errors. Please check below.
                                                        </div>
                                                        <div class="alert alert-success alert-success-treatment{{$row->id}} display-hide">
                                                            <button class="close" data-close="alert"></button>
                                                            Your form validation is successful!
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <table class="table table-striped table-hover">
                                                                    <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>Drugs</th>
                                                                        <th>Dosage</th>
                                                                        <th>Frequency</th>
                                                                        <th>Duration</th>
                                                                        <th>Quantity</th>
                                                                        <th>Cost</th>
                                                                        <th>Comments</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                    <tr class="uppercase">
                                                                        <td width="2%"> #</td>
                                                                        <td width="15%">

                                                                            <select id="drug_id{{$row->id}}"
                                                                                    name="drug_id{{$row->id}}"
                                                                                    class="form-control select2 "
                                                                                    onchange="get_active_batch_price({{$row->id}});">
                                                                                <option value="">Select...
                                                                                </option>
                                                                                <?php

                                                                                foreach ($drug_list as $raw)
                                                                                    echo '<option value="' . $raw->id . '">' . $raw->item_scientific_name . '</option>';
                                                                                ?>

                                                                            </select>
                                                                            <input type="hidden"
                                                                                   name="drug_price{{$row->id}}"
                                                                                   id="drug_price{{$row->id}}"
                                                                                   value="{{''}}"/>
                                                                            <input type="hidden"
                                                                                   name="batch_id{{$row->id}}"
                                                                                   id="batch_id{{$row->id}}"
                                                                                   value="{{''}}"/>
                                                                        </td>
                                                                    <!--     <td width="5%">

                                                                           <input type="text"
                                                                                   name="concentration{{$row->id}}"
                                                                                   id="concentration{{$row->id}}"
                                                                                   class="form-control input-xsmall"
                                                                                   placeholder="Concentration"/>

                                                                        </td>-->
                                                                        <td width="5%">

                                                                            <input type="text"
                                                                                   name="dosage{{$row->id}}"
                                                                                   id="dosage{{$row->id}}"
                                                                                   class="form-control input-xsmall drug"
                                                                                   placeholder="Dosage"
                                                                                   onchange="cal_drug_cost_followup({{$row->id}});"/>

                                                                        </td>
                                                                        <td width="5%">

                                                                            <input type="text"
                                                                                   name="frequency{{$row->id}}"
                                                                                   id="frequency{{$row->id}}"
                                                                                   class="form-control input-xsmall drug"
                                                                                   placeholder="Frequency"
                                                                                   onchange="cal_drug_cost_followup({{$row->id}});"/>

                                                                        </td>
                                                                        <td width="5%">

                                                                            <input type="text"
                                                                                   name="duration{{$row->id}}"
                                                                                   id="duration{{$row->id}}"
                                                                                   class="form-control input-xsmall drug"
                                                                                   placeholder="Duration"
                                                                                   onchange="cal_drug_cost_followup({{$row->id}});"/>

                                                                        </td>
                                                                        <td width="5%">
                                                                            <input type="text"
                                                                                   id="quantity{{$row->id}}"
                                                                                   name="quantity{{$row->id}}"
                                                                                   class="form-control"
                                                                                   placeholder="Quntity" readonly/>

                                                                        </td>
                                                                        <td width="5%">
                                                                            <input type="text"
                                                                                   id="drug_cost{{$row->id}}"
                                                                                   name="drug_cost{{$row->id}}"
                                                                                   class="form-control"
                                                                                   placeholder="Drug Cost" readonly/>

                                                                        </td>
                                                                        <td width="20%">
                                                                            <input type="text"
                                                                                   id="drug_comments{{$row->id}}"
                                                                                   name="drug_comments{{$row->id}}"
                                                                                   class="form-control"
                                                                                   placeholder="Comments"
                                                                                   autocomplete="on"/>
                                                                        </td>
                                                                        <td width="5%">
                                                                            @if ($last_followups == $row->id && $painFile_statusId==17 && auth()->user()->id!=100)
                                                                                <span class="input-group-btn"><button
                                                                                            class="btn btn-success  btn-icon-only"
                                                                                            type="button"
                                                                                            {!! $followupDoctor_style !!}
                                                                                            onclick="add_treatment_follow_drug({{$row->id}});">
                                                                                     <i class="fa fa-plus fa-fw"></i></button></span>
                                                                                {{--<button type="button"
                                                                                        class="btn green"
                                                                                        onclick="add_treatment_follow_drug({{$row->id}});">
                                                                                    +
                                                                                </button>--}}
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                    </thead>
                                                                </table>
                                                                <div class="table-scrollable table-scrollable-borderless">
                                                                    <table class="table table-striped table-hover">
                                                                        <thead>
                                                                        <tr>
                                                                            <th>#</th>
                                                                            <th>Drugs</th>
                                                                            <!--       <th>Concentration</th>-->
                                                                            <th>Dosage</th>
                                                                            <th>Frequency</th>
                                                                            <th>Duration</th>
                                                                            <th>Quantitiy</th>
                                                                            <th>Cost</th>
                                                                            <th>Compliance</th>
                                                                            <th>Adverse Effects</th>
                                                                            <th>Decision</th>
                                                                            <th>comment</th>
                                                                            <th>Action</th>
                                                                        </tr>
                                                                        <tbody id="tbtreatmentDoctorFollow{{$row->id}}">
                                                                        @php
                                                                            if ($last_followups == $row->id)
                                                                               echo $treatmentFollowup_data
                                                                        @endphp
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-3">Other
                                                                Treatments
                                                            </label>
                                                            <div class="col-md-4">
                                                                <input type="text"
                                                                       id="specify_physical_treatment{{$row->id}}"
                                                                       name="specify_physical_treatment"
                                                                       class="form-control"
                                                                       value="{{(isset($one_followup_doctor->specify_physical_treatment)&& $print)?$one_followup_doctor->specify_physical_treatment:''}}"/>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-3">If last scheduled
                                                                consultation, specify if any further treatment
                                                                plans</label>
                                                            <div class="col-md-4">
                                                                <input type="text"
                                                                       id="last_scheduled_further_treatment{{$row->id}}"
                                                                       name="last_scheduled_further_treatment"
                                                                       class="form-control"
                                                                       value="{{(isset($one_followup_doctor->last_scheduled_further_treatment)&& $print)?$one_followup_doctor->last_scheduled_further_treatment:''}}"/>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-3">If last scheduled
                                                                consultation,why was treatment ended?</label>
                                                            <div class="col-md-4">
                                                                <input type="text"
                                                                       id="last_scheduled_why_treatment_ended{{$row->id}}"
                                                                       name="last_scheduled_why_treatment_ended"
                                                                       class="form-control"
                                                                       value="{{(isset($one_followup_doctor->last_scheduled_why_treatment_ended)&& $print)?$one_followup_doctor->last_scheduled_why_treatment_ended:''}}"/>
                                                            </div>
                                                        </div>
                                                        <h3 class="form-section font-green"> Qutenza Treatment </h3>
                                                        <hr/>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-3">Need Qutenza Treatment

                                                            </label>
                                                            <div class="col-md-4">
                                                                <select class="form-control need_qutenza"
                                                                        name="doc_need_qutenza"
                                                                        id="doc_need_qutenza{{$row->id}}">
                                                                    <option value="">Select...</option>
                                                                    <option value="0">No</option>
                                                                    <option value="1">Yes</option>
                                                                </select>
                                                            </div>
                                                        @if($painFile_statusId==17 && auth()->user()->id!=100)<!-- 17=Active file,100=guest user -->
                                                            <div class="col-md-2">
                                                                <button type="button"
                                                                        class="btn btn-success btn-icon-only"
                                                                        onclick="qutenza_request('doc',{{$row->id}});" {!! $followupDoctor_style !!}>
                                                                    <i class="fa fa-plus"></i>
                                                                </button>
                                                            </div>
                                                            @endif
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="table-scrollable table-scrollable-borderless">
                                                                    <table class="table table-striped table-hover">
                                                                        <thead>
                                                                        <tr class="uppercase">
                                                                            <th> #</th>
                                                                            <th>Qutenza Code</th>
                                                                            <th>
                                                                                Visit Date
                                                                            </th>
                                                                            <th>
                                                                                Visit type
                                                                            </th>
                                                                            <th>
                                                                                Action
                                                                            </th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody class="tbqutenza" id="tbqutenza">
                                                                        @php

                                                                            echo $qutenza_patient_datatable;
                                                                        @endphp
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <h3 class="form-section font-green"> Projects </h3>
                                                        <hr/>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="table-scrollable table-scrollable-borderless">
                                                                    <table class="table table-striped table-hover">
                                                                        <thead>
                                                                        <tr class="uppercase">
                                                                            <th> #</th>
                                                                            <th>
                                                                                Project Name
                                                                            </th>
                                                                            <th>
                                                                                Start
                                                                            </th>
                                                                            <th>
                                                                                The Answer
                                                                            </th>
                                                                            <th>
                                                                                Chart
                                                                            </th>
                                                                            <th>
                                                                                Note
                                                                            </th>
                                                                            <th>
                                                                                Action
                                                                            </th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody class="tbpatient_project"
                                                                               id="tbpatient_project">
                                                                        @php

                                                                            echo $patient_project_followup_datatable;
                                                                        @endphp
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <!-- <h3 class="form-section font-green"> Projects Symptoms</h3>
                                                        <hr/>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="table-scrollable table-scrollable-borderless">
                                                                    <table class="table table-striped table-hover">
                                                                        <thead>
                                                                        <tr class="uppercase">
                                                                            <th> #</th>
                                                                            <th>
                                                                                Project Name
                                                                            </th>
                                                                            <th>
                                                                                Symptoms
                                                                            </th>
                                                                            <th>
                                                                                User name
                                                                            </th>
                                                                            <th>
                                                                                Date
                                                                            </th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody class="tbpatient_project_followup"
                                                                               id="tbpatient_project_followup">
                                                                        @php

                                                        echo $patient_project_followup_datatable;
                                                    @endphp
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>-->
                                                        <h3 class="form-section font-green"> Doctor Messages &
                                                            Notes </h3>
                                                        <hr/>
                                                        <div class="alert alert-success alert-message-doctor{{$row->id}} display-hide">
                                                            <button class="close" data-close="alert"></button>
                                                            Your form validation is successful!
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-3">Messages
                                                            </label>
                                                            <div class="col-md-7">
                                                                <input type="text" name="doctor_message"
                                                                       id="doctor_message{{$row->id}}"
                                                                       class="form-control"
                                                                       value="{{(isset($one_followup_doctor->doctor_message)&& $print)?$one_followup_doctor->doctor_message :''}}"/>
                                                            </div>
                                                        @if($painFile_statusId==17 && auth()->user()->id!=100)<!-- 17=Active file,100=guest user -->
                                                            <div class="col-md-2">
                                                                <button type="button"
                                                                        class="btn btn-icon-only green"
                                                                        onclick="set_message(1,2);" {!! $followupDoctor_style !!}>
                                                                    <i
                                                                            class="fa fa-plus"></i>
                                                                </button>
                                                            </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    @if($painFile_statusId==17 && auth()->user()->id!=100)<!-- 17=Active file,100=guest user -->
                                                    <div class="form-actions">
                                                        <div class="row">
                                                            <div class="col-md-offset-3 col-md-9">
                                                                <button type="submit"
                                                                        class="btn green" {!! $followupDoctor_style !!}>
                                                                    Submit
                                                                </button>
                                                                <button type="button"
                                                                        class="btn grey-salsa btn-outline"
                                                                        onclick="cancel_consultation(1);">
                                                                    Cancel
                                                                </button>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                    {{Form::close()}}
                                                </div>
                                                <div class="tab-pane " id="tab_1_1_2_{{$row->id}}">
                                                    <!-- BEGIN FORM-->
                                                    {{Form::open(['url'=>url('followupPharm'),'class'=>'form-horizontal','method'=>"post","id"=>"pharm_followup_form$row->id"])}}

                                                    <div class="form-body">
                                                        <div class="alert alert-danger display-hide">
                                                            <button class="close"
                                                                    data-close="alert"></button>
                                                            You have some form errors. Please check
                                                            below.
                                                        </div>
                                                        <div class="alert alert-success display-hide">
                                                            <button class="close"
                                                                    data-close="alert"></button>
                                                            Your form validation is successful!
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-3">Visit
                                                                Date
                                                                <span class="required"> * </span>
                                                            </label>
                                                            <div class="col-md-4 input-group">
                                                                <input class="form-control date-picker"
                                                                       data-date-format="yyyy-mm-dd"
                                                                       type="text"
                                                                       id="pharm_follow_up_date{{$row->id}}"
                                                                       name="pharm_follow_up_date"
                                                                       readonly="readonly"
                                                                       data-value="{{(isset($row->follow_up_date)&& $print)?$row->follow_up_date:''}}"
                                                                       value="{{(isset($row->follow_up_date)&& $print)?$row->follow_up_date:''}}"/>
                                                                <span class="help-block"> Select date </span>
                                                            </div>
                                                        </div>

                                                        <div class="alert alert-danger alert-danger-treatment-pharm{{$row->id}} display-hide">
                                                            <button class="close" data-close="alert"></button>
                                                            You have some form errors. Please check below.
                                                        </div>
                                                        <div class="alert alert-success alert-success-treatment-pharm{{$row->id}} display-hide">
                                                            <button class="close" data-close="alert"></button>
                                                            Your form validation is successful!
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="table-scrollable table-scrollable-borderless">
                                                                    <table class="table table-striped table-hover">
                                                                        <thead>
                                                                        <tr>
                                                                            <th>#</th>
                                                                            <th>Drugs</th>
                                                                            {{--<th colspan="5">Dosage</th>--}}
                                                                            {{-- <th>Concentration</th>--}}
                                                                            <th>Dosage</th>
                                                                            <th>Frequency</th>
                                                                            <th>Duration</th>
                                                                            <th>Quantitiy</th>
                                                                            <th>Cost</th>
                                                                        </tr>
                                                                        <tr class="uppercase">
                                                                            <td width="5%"> #</td>
                                                                            <td width="20%">

                                                                                <select id="pharm_drug_id{{$row->id}}"
                                                                                        class="form-control select2"
                                                                                        onchange="get_active_batch_price_pharm({{$row->id}}) ">
                                                                                    <option value="">
                                                                                        Select...
                                                                                    </option>
                                                                                    <?php

                                                                                    foreach ($drug_list as $raw)
                                                                                        echo '<option value="' . $raw->id . '">' . $raw->item_scientific_name . '</option>';
                                                                                    ?>
                                                                                </select>

                                                                                <input type="hidden"
                                                                                       name="pharm_drug_price{{$row->id}}"
                                                                                       id="pharm_drug_price{{$row->id}}"
                                                                                       value="{{''}}"/>
                                                                                <input type="hidden"
                                                                                       name="pharm_batch_id{{$row->id}}"
                                                                                       id="pharm_batch_id{{$row->id}}"
                                                                                       value="{{''}}"/>
                                                                            </td>
                                                                        <!--      <td>

                                                                                    <input type="text"
                                                                                           id="pharm_concentration{{$row->id}}"
                                                                                           class="form-control input-small"
                                                                                           placeholder="Concentration"/>

                                                                                </td>-->
                                                                            <td width="5%">

                                                                                <input type="text"
                                                                                       id="pharm_dosage{{$row->id}}"
                                                                                       class="form-control input-small pharmdrug"
                                                                                       placeholder="Dosage"
                                                                                       onchange="cal_drug_cost_followup_pharm({{$row->id}});"/>

                                                                            </td>
                                                                            <td width="5%">

                                                                                <input type="text"
                                                                                       id="pharm_frequency{{$row->id}}"
                                                                                       class="form-control input-small pharmdrug"
                                                                                       placeholder="Frequency"
                                                                                       onchange="cal_drug_cost_followup_pharm({{$row->id}});"/>

                                                                            </td>
                                                                            <td width="5%">

                                                                                <input type="text"
                                                                                       id="pharm_duration{{$row->id}}"
                                                                                       class="form-control input-small pharmdrug"
                                                                                       placeholder="Duration"
                                                                                       onchange="cal_drug_cost_followup_pharm({{$row->id}});"/>

                                                                            </td>
                                                                            <td width="5%">

                                                                                <input type="text"
                                                                                       id="pharm_quantity{{$row->id}}"
                                                                                       class="form-control input-small"
                                                                                       placeholder="Quantity"
                                                                                       readonly/>

                                                                            </td>
                                                                            <td width="5%">

                                                                                <input type="text"
                                                                                       id="pharm_drug_cost{{$row->id}}"
                                                                                       class="form-control input-small"
                                                                                       placeholder="Cost" readonly/>

                                                                            </td>
                                                                            <td width="5%">
                                                                            @if($painFile_statusId==17 && auth()->user()->id!=100)<!-- 17=Active file,100=guest user -->
                                                                                <span class="input-group-btn"><button
                                                                                            class="btn btn-success  btn-icon-only"
                                                                                            type="button"
                                                                                            onclick="add_pharm_treatment_drug({{$row->id}});">
                                                                                     <i class="fa fa-plus fa-fw"></i></button></span>
                                                                                {{--<button type="button"
                                                                                        class="btn green"
                                                                                        onclick="add_pharm_treatment_drug({{$row->id}})">
                                                                                    +
                                                                                </button>--}}
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>
                                                                                <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                                                                    <input type="checkbox"
                                                                                           name="checkedAll"
                                                                                           id="checkedAll"
                                                                                           class="checkboxes checkedAll"
                                                                                           value="1"/><span></span></label>
                                                                            </th>
                                                                            <th>#</th>
                                                                            <th>Drugs</th>
                                                                            <!--  <th>Concentration</th>-->
                                                                            <th>Dosage</th>
                                                                            <th>Frequency</th>
                                                                            <th>Duration</th>
                                                                            <th>Quantitiy</th>
                                                                            <th>Cost</th>
                                                                            <th>Compliance</th>
                                                                            <th>Adverse Effects</th>
                                                                            <th>Decision</th>
                                                                            <th>comment</th>
                                                                            <th>Date</th>
                                                                            <th>Action</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody id="tbpharmtreatment{{$row->id}}">
                                                                        @php
                                                                            if ($last_followups == $row->id)

                                                                            echo $Pharm_treatmentFollowup_data;
                                                                        @endphp
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @if($painFile_statusId==17 && auth()->user()->id!=100 && auth()->user()->user_type_id==11)<!-- 17=Active file,100=guest user -->
                                                        <button type="button" class="btn btn-success"
                                                                id="change_order_status_btn{{$row->id}}"
                                                                onclick="change_drug_order_status({{$row->id}});">
                                                            Confirm
                                                        </button>
                                                        @endif
                                                        <div class="form-group">
                                                            <label class="control-label col-md-3">Specify
                                                                Other
                                                                Changes
                                                            </label>
                                                            <div class="col-md-4">
                                                                <input type="text"
                                                                       id="pharm_specify_other_changes{{$row->id}}"
                                                                       name="pharm_specify_other_changes"
                                                                       class="form-control"
                                                                       value="{{(isset($one_followup_pharm->specify_other_changes)&& $print)?$one_followup_pharm->specify_other_changes:''}}"/>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="control-label col-md-3">Specify
                                                                Others Adverse effects
                                                            </label>
                                                            <div class="col-md-4">
                                                                <input type="text"
                                                                       id="specify_other_adverse_effects{{$row->id}}"
                                                                       name="specify_other_adverse_effects"
                                                                       class="form-control"/></div>
                                                        </div>
                                                        <h3 class="form-section font-green"> Qutenza Treatment </h3>
                                                        <hr/>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-3">Need Qutenza
                                                                Treatment</label>
                                                            <div class="col-md-4">
                                                                <select class="form-control need_qutenza"
                                                                        name="pharm_need_qutenza"
                                                                        id="pharm_need_qutenza{{$row->id}}">
                                                                    <option value="">Select...</option>
                                                                    <option value="0">No</option>
                                                                    <option value="1">Yes</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-2">
                                                            @if($painFile_statusId==17 && auth()->user()->id!=100)<!-- 17=Active file,100=guest user -->
                                                                <button type="button"
                                                                        class="btn btn-success btn-icon-only"
                                                                        onclick="qutenza_request('pharm',{{$row->id}});">
                                                                    <i class="fa fa-plus"></i>
                                                                </button>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="table-scrollable table-scrollable-borderless">
                                                                    <table class="table table-striped table-hover">
                                                                        <thead>
                                                                        <tr class="uppercase">
                                                                            <th> #</th>
                                                                            <th>Qutenza Code</th>
                                                                            <th>Visit Date</th>
                                                                            <th>Visit type</th>
                                                                            <th>Action</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody class="tbqutenza" id="tbqutenza">
                                                                        @php

                                                                            echo $qutenza_patient_datatable;
                                                                        @endphp
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <h3 class="form-section font-green"> Projects </h3>
                                                        <hr/>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="table-scrollable table-scrollable-borderless">
                                                                    <table class="table table-striped table-hover">
                                                                        <thead>
                                                                        <tr class="uppercase">
                                                                            <th> #</th>
                                                                            <th>
                                                                                Project Name
                                                                            </th>
                                                                            <th>
                                                                                Start
                                                                            </th>
                                                                            <th>
                                                                                The Answer
                                                                            </th>
                                                                            <th>
                                                                                Chart
                                                                            </th>
                                                                            <th>
                                                                                Note
                                                                            </th>
                                                                            <th>
                                                                                Action
                                                                            </th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody class="tbpatient_project"
                                                                               id="tbpatient_project">
                                                                        @php

                                                                            echo $patient_project_followup_datatable;
                                                                        @endphp
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <h3 class="form-section font-green"> Pharmacist
                                                                    Messages &
                                                                    Notes </h3>
                                                                <hr/>
                                                                <div class="alert alert-success alert-message-pharm{{$row->id}} display-hide">
                                                                    <button class="close"
                                                                            data-close="alert"></button>
                                                                    Your form validation is successful!
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-3">Messages
                                                                    </label>
                                                                    <div class="col-md-7">
                                                                        <input type="text" name="pharm_message"
                                                                               id="pharm_message{{$row->id}}"
                                                                               class="form-control"/>
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                    @if($painFile_statusId==17 && auth()->user()->id!=100)<!-- 17=Active file,100=guest user -->
                                                                        <button type="button"
                                                                                class="btn btn-icon-only green"
                                                                                onclick="set_message(3,2);"><i
                                                                                    class="fa fa-plus"></i>
                                                                        </button>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @if($painFile_statusId==17 && auth()->user()->id!=100)<!-- 17=Active file,100=guest user -->
                                                    <div class="form-actions">
                                                        <div class="row">
                                                            <div class="col-md-offset-3 col-md-9">
                                                                <button type="submit" class="btn green">
                                                                    Submit
                                                                </button>
                                                                <button type="button"
                                                                        class="btn grey-salsa btn-outline"
                                                                        onclick="cancel_consultation(3);">
                                                                    Cancel
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                                {{Form::close()}}

                                                <!-- END FORM-->
                                                </div>

                                                <div class="tab-pane" id="tab_1_1_3_{{$row->id}}">
                                                    <!-- BEGIN FORM-->
                                                    {{Form::open(['url'=>url('followupNurse'),'class'=>'form-horizontal','method'=>"post","id"=>"nurse_followup_form$row->id"])}}

                                                    <div class="form-body">
                                                        <div class="alert alert-danger display-hide">
                                                            <button class="close"
                                                                    data-close="alert"></button>
                                                            You have some form errors. Please check
                                                            below.
                                                        </div>
                                                        <div class="alert alert-success display-hide">
                                                            <button class="close"
                                                                    data-close="alert"></button>
                                                            Your form validation is successful!
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-3">Visit
                                                                Date
                                                                <span class="required"> * </span>
                                                            </label>
                                                            <div class="col-md-4 input-group">
                                                                <input class="form-control date-picker"
                                                                       type="text"
                                                                       name="nurse_follow_up_date"
                                                                       id="nurse_follow_up_date{{$row->id}}"
                                                                       data-date-format="yyyy-mm-dd"
                                                                       readonly="readonly"
                                                                       value="{{(isset($row->follow_up_date)&& $print)?$row->follow_up_date:''}}"/>
                                                                <span class="help-block"> Select date </span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-3">Second
                                                                Nurse
                                                            </label>
                                                            <div class="col-md-4">
                                                                <select class="form-control select2"
                                                                        name="second_nurse"
                                                                        id="second_nurse{{$row->id}}">
                                                                    <option value="">Select...</option>
                                                                    <?php
                                                                    $selected = '';
                                                                    foreach ($nurse_list as $raw) {
                                                                        $selected = '';
                                                                        if ((isset($one_followup_nurse->second_nurse) && $print) && $raw->id == $one_followup_nurse->second_nurse)
                                                                            $selected = 'selected="selected"';
                                                                        echo '<option value="' . $raw->id . '" ' . $selected . '>' . $raw->name . '</option>';
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="alert alert-warning">
                                                            Rate the current level of difficulty associated with
                                                            each
                                                            activity on a scale
                                                            from 0 to 10. "0" represents “unable to perform”,
                                                            and
                                                            "10"
                                                            represents “able
                                                            to perform at prior level”.
                                                        </div>
                                                        <h3 class="form-section font-green"> Treatment
                                                            Goals </h3>
                                                        <hr/>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-3">Comments
                                                            </label>
                                                            <div class="col-md-4">
                                                                <input type="text"
                                                                       name="nurse_treat_goal_achiev"
                                                                       id="nurse_treat_goal_achiev{{$row->id}}"
                                                                       class="form-control"
                                                                       value="{{(isset($one_followup_nurse->treatment_goal_achievements)&& $print)?$one_followup_nurse->treatment_goal_achievements:''}}"/>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="alert alert-success alert-nurse-goal{{$row->id}}  display-hide">
                                                                    <button class="close"
                                                                            data-close="alert"></button>
                                                                    Your form validation is successful!
                                                                </div>
                                                                <div class="table-scrollable table-scrollable-borderless">
                                                                    <table class="table table-striped table-hover">
                                                                        <thead>
                                                                        <tr class="uppercase">
                                                                            <th> #</th>
                                                                            <th width="60%">
                                                                                GOAL
                                                                            </th>
                                                                            <th width="20%">
                                                                                SCORE
                                                                            </th>
                                                                            <th width="20%">
                                                                                COMPLIANCE
                                                                            </th>
                                                                            <th width="20%">
                                                                                Physical treatment
                                                                            </th>
                                                                            <th width="20%">
                                                                                # days on prg.
                                                                            </th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody id="tbtreat_nurse_goal{{$row->id}}">
                                                                        @php
                                                                            if ($last_followups == $row->id)
                                                                                echo $treat_nurse_goal;
                                                                        @endphp
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="alert alert-info">
                                                            <strong>Info!</strong> Total score = sum of
                                                            the
                                                            activity scores/number of activities. <br/>
                                                            Minimum detectable change for average score
                                                            = 2
                                                            points. <br/>
                                                        </div>

                                                        <h3 class="form-section font-green"> Pain
                                                            Characteristics </h3>
                                                        <hr/>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-3">During the
                                                                last
                                                                week,
                                                                how has your
                                                                usual
                                                                pain intensity been on a scale from 0 to 10, with zero
                                                                meaning “no pain” and 10 meaning “the worst pain
                                                                imaginable”?

                                                            </label>
                                                            <div class="col-md-4">
                                                                <input type="number" max="10" min="0"
                                                                       name="pain_scale"
                                                                       id="nurse_pain_scale{{$row->id}}"
                                                                       class="form-control"
                                                                       value="{{(isset($one_followup_nurse->pain_scale) && $print)?$one_followup_nurse->pain_scale:''}}"/>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-3">During the
                                                                last
                                                                week,
                                                                how has your
                                                                usual
                                                                pain bothersomeness been on a scale from 0 to
                                                                10, with zero meaning “no pain” and 10 meaning “the
                                                                worst pain imaginable”?

                                                            </label>
                                                            <div class="col-md-4">
                                                                <input type="number" max="10" min="0"
                                                                       name="pain_bothersomeness"
                                                                       id="nurse_pain_bothersomeness{{$row->id}}"
                                                                       class="form-control"
                                                                       value="{{(isset($one_followup_nurse->pain_bothersomeness) && $print)?$one_followup_nurse->pain_bothersomeness:''}}"/>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-3">What is your
                                                                usual
                                                                pain intensity during rest on a scale from 0 to
                                                                10, with zero meaning “no pain” and 10 meaning “the
                                                                worst pain imaginable”?

                                                            </label>
                                                            <div class="col-md-4">
                                                                <input type="number" max="10" min="0"
                                                                       name="pain_intensity_during_rest"
                                                                       id="nurse_pain_intensity_during_rest{{$row->id}}"
                                                                       class="form-control"
                                                                       value="{{(isset($one_followup_nurse->pain_intensity_during_rest) && $print)?$one_followup_nurse->pain_intensity_during_rest:''}}"/>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-3">What is your
                                                                usual
                                                                pain intensity during activity on a scale from 0
                                                                to
                                                                10, with zero meaning “no pain” and 10 meaning “the
                                                                worst pain imaginable”?

                                                            </label>
                                                            <div class="col-md-4">
                                                                <input type="number" max="10" min="0"
                                                                       name="pain_intensity_during_activity"
                                                                       id="nurse_pain_intensity_during_activity{{$row->id}}"
                                                                       class="form-control"
                                                                       value="{{(isset($one_followup_nurse->pain_intensity_during_activity) && $print)?$one_followup_nurse->pain_intensity_during_activity:''}}"/>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-3">Since the previous consultation at the Gaza pain clinic, is your pain better, worse or the same?
                                                                <span class="required"> * </span>
                                                            </label>
                                                            <div class="col-md-4">
                                                                <select id="nurse_health_rate{{$row->id}}" name="nurse_health_rate"
                                                                        class="form-control select2">
                                                                    <option value="">Select...</option>
                                                                    <?php
                                                                    $selected = '';
                                                                    foreach ($health_rate_list as $raw) {
                                                                        $selected = '';
                                                                        if (isset($one_followup_nurse->health_rate) && $raw->id == $one_followup_nurse->health_rate)
                                                                            $selected = 'selected="selected"';
                                                                        echo '<option value="' . $raw->id . '" ' . $selected . '>' . $raw->lookup_details . '</option>';
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <h3 class="form-section font-green"> Physical treatment </h3>
                                                        <hr/>

                                                        <h5 class="form-section font-green "> Neck and shoulder </h5>
                                                        <div id="chk_neck_and_shoulder{{$row->id}}">
                                                            <?php
                                                            if ($last_followups == $row->id)
                                                                echo $chk_neck_and_shoulder;

                                                            ?>
                                                        </div>
                                                        <h5 class="form-section font-green "> Lower Back </h5>
                                                        <div id="chk_lower_back{{$row->id}}">
                                                            <?php
                                                            if ($last_followups == $row->id)
                                                                echo $chk_lower_back;
                                                            ?>
                                                        </div>
                                                        <h3 class="form-section font-green"> Qutenza
                                                            Treatment </h3>
                                                        <hr/>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-3">Need Qutenza
                                                                Treatment

                                                            </label>
                                                            <div class="col-md-4">
                                                                <select class="form-control need_qutenza"
                                                                        name="nurse_need_qutenza"
                                                                        id="nurse_need_qutenza{{$row->id}}">
                                                                    <option value="">Select...</option>
                                                                    <option value="0">No</option>
                                                                    <option value="1">Yes</option>
                                                                </select>
                                                            </div>
                                                        @if($painFile_statusId==17 && auth()->user()->id!=100)<!-- 17=Active file,100=guest user -->
                                                            <div class="col-md-2">
                                                                <button type="button"
                                                                        class="btn btn-success btn-icon-only"
                                                                        onclick="qutenza_request('nurse',{{$row->id}});">
                                                                    <i class="fa fa-plus"></i>
                                                                </button>
                                                            </div>
                                                            @endif
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="table-scrollable table-scrollable-borderless">
                                                                    <table class="table table-striped table-hover">
                                                                        <thead>
                                                                        <tr class="uppercase">
                                                                            <th> #</th>
                                                                            <th>Qutenza Code</th>
                                                                            <th>
                                                                                Visit Date
                                                                            </th>
                                                                            <th>
                                                                                Visit type
                                                                            </th>
                                                                            <th>
                                                                                Action
                                                                            </th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody class="tbqutenza" id="tbqutenza">
                                                                        @php

                                                                            echo $qutenza_patient_datatable;
                                                                        @endphp
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <h3 class="form-section font-green"> Nurse Messages
                                                                    &
                                                                    Notes </h3>
                                                                <hr/>
                                                                <div class="alert alert-success alert-message-nurse{{$row->id}} display-hide">
                                                                    <button class="close"
                                                                            data-close="alert"></button>
                                                                    Your form validation is successful!
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-3">Messages
                                                                    </label>
                                                                    <div class="col-md-7">
                                                                        <input type="text" name="nurse_message"
                                                                               id="nurse_message{{$row->id}}"
                                                                               class="form-control"
                                                                               value="{{(isset($one_followup_nurse->nurse_message)&& $print)?$one_followup_nurse->nurse_message:''}}"/>
                                                                    </div>
                                                                @if($painFile_statusId==17 && auth()->user()->id!=100)<!-- 17=Active file,100=guest user -->
                                                                    <div class="col-md-2">
                                                                        <button type="button"
                                                                                class="btn btn-icon-only green"
                                                                                onclick="set_message(2,2);"><i
                                                                                    class="fa fa-plus"></i>
                                                                        </button>
                                                                    </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @if($painFile_statusId==17 && auth()->user()->id!=100)<!-- 17=Active file,100=guest user -->
                                                    <div class="form-actions">
                                                        <div class="row">
                                                            <div class="col-md-offset-3 col-md-9">

                                                                <button type="submit" class="btn green">
                                                                    Submit
                                                                </button>
                                                                <button type="button"
                                                                        class="btn grey-salsa btn-outline"
                                                                        onclick="cancel_consultation(2);">
                                                                    Cancel
                                                                </button>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                    {{Form::close()}}
                                                </div>

                                                <div class="tab-pane" id="tab_1_1_4_{{$row->id}}">
                                                    <!-- BEGIN FORM-->
                                                    {{Form::open(['url'=>url('followupPsychology'),'class'=>'form-horizontal','method'=>"post","id"=>"baseline_psychology_form$row->id"])}}
                                                    <?php
                                                    $editable = "";
                                                    if (auth()->user()->user_type_id != 572)
                                                        $editable = "disabled";

                                                    ?>
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
                                                            <label class="control-label col-md-3">Visit Date

                                                            </label>
                                                            <div class="col-md-4">
                                                                <input class="form-control date-picker"
                                                                       type="text"
                                                                       name="visit_date_psychology"
                                                                       id="visit_date_psychology{{$row->id}}"
                                                                       data-date-format="yyyy-mm-dd"
                                                                       value="{{(isset($row->follow_up_date)&& $print)?$row->follow_up_date:''}}" {{$editable}}/>
                                                                <span class="help-block"> Select date </span>
                                                            </div>
                                                        </div>
                                                        <h3 class="form-section font-green"> Treatment today </h3>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-3">Treatment
                                                            </label>

                                                            <div class="col-md-6">
                                                                <select id="treatment_today{{$row->id}}"
                                                                        name="treatment_today[]"
                                                                        class="form-control select2-multiple"
                                                                        multiple {{$editable}}>
                                                                    <!-- <option value="">Select...</option>-->
                                                                    <?php
                                                                    //    echo '$followup_psy_treatment';
                                                                    //  print_r($followup_psy_treatment);exit;
                                                                    $selected = '';
                                                                    foreach ($psychologist_treatment_list as $raw) {
                                                                        $selected = '';
                                                                        if ((isset($followup_psy_treatment) && $print))
                                                                            foreach ($followup_psy_treatment as $raw2) {
                                                                                if ($raw->id == $raw2->treatment_id && $print) {
                                                                                    $selected = 'selected="selected"';
                                                                                }
                                                                            }
                                                                        echo '<option value="' . $raw->id . '" ' . $selected . '>' . $raw->lookup_details . '</option>';
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div id="dv_psychologistTreatment{{$row->id}}"
                                                             class="form-group">
                                                            <label class="control-label col-md-3">Other Treatment
                                                            </label>
                                                            <div class="col-md-6">
                                                                <input type="text"
                                                                       id="other_treatment_today{{$row->id}}"
                                                                       name="other_treatment_today" {{$editable}}
                                                                       class="form-control"
                                                                       value="{{(isset($one_followup_psychology->other_treatment_today) && $print)?$one_followup_psychology->other_treatment_today:''}}"/>
                                                            </div>
                                                        </div>
                                                        <hr/>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-3">Comments</label>
                                                            <div class="col-md-8">
                                                            <textarea name="physical_exam"
                                                                      id="physical_exam{{$row->id}}"
                                                                      class="form-control" {{$editable}}
                                                                      rows="6"
                                                                      placeholder="Physical examination">{{(isset($one_followup_psychology->physical_exam)&& $print)?$one_followup_psychology->physical_exam:''}}</textarea>
                                                            </div>
                                                        </div>
                                                        <h3 class="form-section font-green"> Psychologist Messages &
                                                            Notes </h3>
                                                        <hr/>
                                                        <div class="alert alert-success alert-message-psychology{{$row->id}} display-hide">
                                                            <button class="close" data-close="alert"></button>
                                                            Your form validation is successful!
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="control-label col-md-3">Messages
                                                            </label>
                                                            <div class="col-md-7">
                                                                <input type="text" id="psychology_message{{$row->id}}"
                                                                       name="psychology_message" {{$editable}}
                                                                       class="form-control"
                                                                       value="{{''}}"/>
                                                            </div>
                                                        @if($painFile_statusId==17 && auth()->user()->id!=100)<!-- 17=Active file,100=guest user -->
                                                            <div class="col-md-2">
                                                                <button type="button"
                                                                        class="btn btn-icon-only green"
                                                                        onclick="set_message(4,2);" {{$editable}}><i
                                                                            class="fa fa-plus"></i>
                                                                </button>
                                                            </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    @if($painFile_statusId==17 && auth()->user()->id!=100)<!-- 17=Active file,100=guest user -->
                                                    <div class="form-actions">
                                                        <div class="row">
                                                            <div class="col-md-offset-3 col-md-9">
                                                                <button type="submit"
                                                                        class="btn btn-success" {{$editable}}>
                                                                    Submit
                                                                </button>
                                                                <a href="{{'painFile/view'}}" type="button"
                                                                   class="btn grey-salsa btn-outline">Cancel
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                    {{Form::close()}}
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <?php
                                    }
                                    ?>
                                </ol>
                            </div>
                            <!-- .events-content -->
                        </div>
                    </div>
                    <?php }?>
                </div>

            </div>
        </div>
        <!-- END PAGE BASE CONTENT -->

        <!-- END CONTENT BODY -->
    </div>
    <div class="modal fade" id="msgModal" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="portlet light tasks-widget ">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-share font-dark hide"></i>
                                <span class="caption-subject font-green-steel bold uppercase">Message</span>
                                <span class="caption-helper">All Message...</span>
                            </div>

                        </div>
                        <div class="portlet-body">
                            <div class="task-content">
                                <div class="scroller" style="height: 380px;" data-always-visible="1"
                                     data-rail-visible1="1">
                                    <!-- START TASK LIST -->
                                    <ul class="task-list">

                                    </ul>
                                    <!-- END START TASK LIST -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>

                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- / Project modal -->

    <div class="modal fade bs-modal-lg" id="project_followup_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!--                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                    <h4 class="modal-title">Project Details</h4>
                                </div>-->
                <div class="modal-body">
                    <div class="portlet light">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-user font-green"></i>
                                <span class="caption-subject font-green sbold uppercase">Project Follow up</span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="tabbable-custom nav-justified">
                                <ul class="nav nav-tabs nav-justified">
                                    <li class="active">
                                        <a href="#tab_doctor_followup" data-toggle="tab"> Doctor Follow up</a>
                                    </li>
                                    <li>
                                        <a href="#tab_pharm_followup" data-toggle="tab"> Pharmacy Follow up</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_doctor_followup">
                                        <!-- BEGIN FORM-->
                                        {{--@include('qutenza.qutenza_form')--}}
                                        {{Form::open(['url'=>url('insert-doctor-followup'),'class'=>'form-horizontal','method'=>"post","id"=>"doctor_followup_form"])}}

                                        <div class="form-body">
                                            <input id="pain_project_id" name="pain_project_id" type="hidden"
                                                   value="{{''}}">
                                            <input id="project_id" name="project_id" type="hidden" value="{{''}}">
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Visit
                                                    Date
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-4 input-group">
                                                    <input class="form-control date-picker"
                                                           type="text"
                                                           name="doc_project_followup_date"
                                                           id="doc_project_followup_date"
                                                           data-date-format="yyyy-mm-dd"
                                                           readonly="readonly"
                                                           value="{{(isset($row->follow_up_date)&& $print)?$row->follow_up_date:''}}"/>
                                                    <span class="help-block"> Select date </span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Doctor Action</label>
                                                <div class="col-md-6">
                                                    <select class="form-control doctor_project_action"
                                                            id="doctor_project_action">
                                                        <option value="">Select..</option>
                                                        <?php
                                                        foreach ($doctor_project_action_list as $raw) {
                                                            echo '<option value="' . $raw->id . '">' . $raw->lookup_details . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Doctor Note</label>
                                                <div class="col-md-6">
                                                <textarea name="doctor_project_note" id="doctor_project_note"
                                                          class="form-control doctor_project_note"
                                                          rows="3" placeholder="Drugs or Note"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="alert alert-danger doc-project-danger  display-hide">
                                            <button class="close"
                                                    data-close="alert"></button>
                                            You have some form errors. Please check
                                            below.
                                        </div>
                                        <div class="alert alert-success doc-project-success  display-hide">
                                            <button class="close"
                                                    data-close="alert"></button>
                                            Your form validation is successful!
                                        </div>
                                        @if($painFile_statusId==17 && auth()->user()->id!=100)<!-- 17=Active file,100=guest user -->
                                        <div class="form-actions" id="docProjectDv">
                                            <div class="row">
                                                <div class="col-md-offset-3 col-md-9">
                                                    <button type="button" class="btn green"
                                                            onclick="add_doc_followup_project();">Save
                                                    </button>
                                                    <button type="button"
                                                            class="btn grey-salsa btn-outline"
                                                            data-dismiss="modal">
                                                        Cancel
                                                    </button>

                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        {{Form::close()}}
                                        <hr/>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-scrollable table-scrollable-borderless">
                                                    <table class="table table-striped table-hover">
                                                        <thead>
                                                        <tr class="uppercase">
                                                            <th> #</th>
                                                            <th>
                                                                Visit Date
                                                            </th>
                                                            <th>
                                                                Action
                                                            </th>
                                                            <th>
                                                                Note
                                                            </th>
                                                            <th>
                                                                User
                                                            </th>
                                                            <th>
                                                                Action
                                                            </th>
                                                        </tr>
                                                        </thead>
                                                        <tbody class="tbdoc_project_followup"
                                                               id="tbdoc_project_followup">

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab_pharm_followup">
                                        <!-- BEGIN FORM-->
                                        {{--@include('qutenza.qutenza_form')--}}

                                        {{Form::open(['url'=>url('insert-pharm-followup'),'class'=>'form-horizontal','method'=>"post","id"=>"pharm_followup_form"])}}

                                        <div class="form-body">
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Visit
                                                    Date
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-4 input-group">
                                                    <input class="form-control date-picker"
                                                           type="text"
                                                           name="pharm_project_followup_date"
                                                           id="pharm_project_followup_date"
                                                           data-date-format="yyyy-mm-dd"
                                                           readonly="readonly"
                                                           value="{{(isset($row->follow_up_date)&& $print)?$row->follow_up_date:''}}"/>
                                                    <span class="help-block"> Select date </span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Pharmacy Action</label>
                                                <div class="col-md-6">
                                                    <select class="form-control pharm_project_action"
                                                            id="pharm_project_action">
                                                        <option value="">Select..</option>
                                                        <?php
                                                        foreach ($pharm_project_action_list as $raw) {
                                                            echo '<option value="' . $raw->id . '">' . $raw->lookup_details . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label">Pharmacy Note</label>
                                                <div class="col-md-6">
                                                <textarea name="pharm_project_note" id="pharm_project_note"
                                                          class="form-control pharm_project_note"
                                                          rows="3" placeholder="Drugs or Note"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="alert alert-danger doc-project-danger  display-hide">
                                            <button class="close"
                                                    data-close="alert"></button>
                                            You have some form errors. Please check
                                            below.
                                        </div>
                                        <div class="alert alert-success doc-project-success  display-hide">
                                            <button class="close"
                                                    data-close="alert"></button>
                                            Your form validation is successful!
                                        </div>
                                        @if($painFile_statusId==17 && auth()->user()->id!=100)<!-- 17=Active file,100=guest user -->
                                        <div class="form-actions" id="pharmProjectDv">
                                            <div class="row">
                                                <div class="col-md-offset-3 col-md-9">
                                                    <button type="button" class="btn green"
                                                            onclick="add_pharm_followup_project();">Save
                                                    </button>
                                                    <button type="button"
                                                            class="btn grey-salsa btn-outline" data-dismiss="modal">
                                                        Cancel
                                                    </button>

                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        {{Form::close()}}

                                        <hr/>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-scrollable table-scrollable-borderless">
                                                    <table class="table table-striped table-hover">
                                                        <thead>
                                                        <tr class="uppercase">
                                                            <th> #</th>
                                                            <th>
                                                                Visit Date
                                                            </th>
                                                            <th>
                                                                Action
                                                            </th>
                                                            <th>
                                                                Note
                                                            </th>
                                                            <th>
                                                                User
                                                            </th>
                                                            <th>
                                                                Action
                                                            </th>
                                                        </tr>
                                                        </thead>
                                                        <tbody class="tbpharm_project_followup"
                                                               id="tbpharm_project_followup">

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--                <div class="modal-footer">
                                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                                </div>-->
            </div>
        </div>
    </div>
    <div class="modal fade bs-modal-lg" id="stop_project_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="portlet light">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-user font-green"></i>
                                <span class="caption-subject font-green sbold uppercase">Stop Project</span>
                            </div>
                        </div>
                        <div class="portlet-body">


                            {{Form::open(['url'=>url('insert-doctor-followup'),'class'=>'form-horizontal','method'=>"post","id"=>"stop_project_form"])}}

                            <div class="form-body">
                                <input id="pain_project_id" name="pain_project_id" type="hidden"
                                       value="{{''}}">
                                <input id="project_id" name="project_id" type="hidden" value="{{''}}">
                                <div class="form-group">
                                    <label class="col-md-2 control-label ">End Date<span
                                                class="required"> * </span></label>
                                    <div class="col-md-6 ">
                                        <input class="form-control date-picker"
                                               type="text"
                                               name="doc_project_followup_date"
                                               id="doc_project_followup_date"
                                               data-date-format="yyyy-mm-dd"
                                               readonly="readonly"
                                               value="{{(isset($row->follow_up_date)&& $print)?$row->follow_up_date:''}}"/>
                                        <span class="help-block"> Select date </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Case Study</label>
                                    <div class="col-md-6">
                                        <select class="form-control"
                                                id="case_study">
                                            <option value="">Select..</option>
                                            <?php
                                            foreach ($case_study_list as $raw) {
                                                echo '<option value="' . $raw->id . '">' . $raw->lookup_details . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Conclosion</label>
                                    <div class="col-md-6">
                                        <select class="form-control"
                                                id="conclusion">
                                            <option value="">Select..</option>
                                            <?php
                                            foreach ($project_conclusion_list as $raw) {
                                                echo '<option value="' . $raw->id . '">' . $raw->lookup_details . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Note</label>
                                    <div class="col-md-6">
                                                <textarea name="case_study_note" id="case_study_note"
                                                          class="form-control"
                                                          rows="3" placeholder="Note"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="alert alert-danger stop-project-danger  display-hide">
                                <button class="close"
                                        data-close="alert"></button>
                                You have some form errors. Please check
                                below.
                            </div>
                            <div class="alert alert-success stop-project-success  display-hide">
                                <button class="close"
                                        data-close="alert"></button>
                                Your form validation is successful!
                            </div>
                            @if($painFile_statusId==17 && auth()->user()->id!=100)<!-- 17=Active file,100=guest user -->
                            <div class="form-actions" id="docProjectDv">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="button" class="btn green"
                                                onclick="stop_followup_project();">Save
                                        </button>
                                        <button type="button"
                                                class="btn grey-salsa btn-outline"
                                                data-dismiss="modal">
                                            Cancel
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
    <div class="modal fade bs-modal-lg" id="qutenza_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!--                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                    <h4 class="modal-title">Project Details</h4>
                                </div>-->
                <div class="modal-body">
                    <div class="portlet light">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-user font-green"></i>
                                <span class="caption-subject font-green sbold uppercase">Qutenza Treatment</span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="tabbable-custom nav-justified">
                                <ul class="nav nav-tabs nav-justified">
                                    <li class="active">
                                        <a href="#tab_qutenza_1" data-toggle="tab"> Qutenza Form </a>
                                    </li>
                                    <li>
                                        <a href="#tab_qutenza_2" data-toggle="tab"> Qutenza Score </a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_qutenza_1">
                                        <!-- BEGIN FORM-->
                                        {{--@include('qutenza.qutenza_form')--}}
                                        {{Form::open(['url'=>url('insert-qutenza'),'class'=>'form-horizontal','method'=>"post","id"=>"qutenza_form"])}}

                                        <div class="form-body">


                                            <div class="form-group">
                                                <label class="control-label col-md-3">Visit
                                                    Date
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-4 input-group">
                                                    <input class="form-control date-picker"
                                                           type="text"
                                                           name="qutenza_date"
                                                           id="qutenza_date"
                                                           data-date-format="yyyy-mm-dd"
                                                           readonly="readonly"
                                                           value="{{(isset($row->follow_up_date)&& $print)?$row->follow_up_date:''}}"/>
                                                    <span class="help-block"> Select date </span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Application
                                                    Time</label>
                                                <div class="col-md-4">
                                                    <input type="number" min="0"
                                                           name="application_time"
                                                           id="application_time"
                                                           class="form-control" value=""/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Allodynia</label>
                                                <div class="col-md-4">
                                                    <select class="form-control select2"
                                                            name="allodynia"
                                                            id="allodynia">
                                                        <option value="">Select...</option>
                                                        <option value="0">No</option>
                                                        <?php

                                                        foreach ($qutenza_list as $raw) {

                                                            echo '<option value="' . $raw->id . '">' . $raw->lookup_details . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <h3 class="form-section font-green"> General Disorders and
                                                Administration site condition</h3>
                                            <hr/>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Application site
                                                    erythema</label>
                                                <div class="col-md-4">
                                                    <select class="form-control select2"
                                                            name="erythema"
                                                            id="erythema">
                                                        <option value="">Select...</option>
                                                        <option value="0">No</option>
                                                        <?php

                                                        foreach ($qutenza_list as $raw) {

                                                            echo '<option value="' . $raw->id . '">' . $raw->lookup_details . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Application site
                                                    pain</label>
                                                <div class="col-md-4">
                                                    <select class="form-control select2"
                                                            name="pain"
                                                            id="pain">
                                                        <option value="">Select...</option>
                                                        <option value="0">No</option>
                                                        <?php

                                                        foreach ($qutenza_list as $raw) {

                                                            echo '<option value="' . $raw->id . '">' . $raw->lookup_details . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Application site
                                                    pruritus</label>
                                                <div class="col-md-4">
                                                    <select class="form-control select2"
                                                            name="pruritus"
                                                            id="pruritus">
                                                        <option value="">Select...</option>
                                                        <option value="0">No</option>
                                                        <?php

                                                        foreach ($qutenza_list as $raw) {

                                                            echo '<option value="' . $raw->id . '">' . $raw->lookup_details . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Application site
                                                    papules</label>
                                                <div class="col-md-4">
                                                    <select class="form-control select2"
                                                            name="papules"
                                                            id="papules">
                                                        <option value="">Select...</option>
                                                        <option value="0">No</option>
                                                        <?php

                                                        foreach ($qutenza_list as $raw) {

                                                            echo '<option value="' . $raw->id . '">' . $raw->lookup_details . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Application site
                                                    edema</label>
                                                <div class="col-md-4">
                                                    <select class="form-control select2"
                                                            name="edema"
                                                            id="edema">
                                                        <option value="">Select...</option>
                                                        <option value="0">No</option>
                                                        <?php

                                                        foreach ($qutenza_list as $raw) {

                                                            echo '<option value="' . $raw->id . '">' . $raw->lookup_details . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Application site
                                                    swelling</label>
                                                <div class="col-md-4">
                                                    <select class="form-control select2"
                                                            name="swelling"
                                                            id="swelling">
                                                        <option value="">Select...</option>
                                                        <option value="0">No</option>
                                                        <?php

                                                        foreach ($qutenza_list as $raw) {

                                                            echo '<option value="' . $raw->id . '">' . $raw->lookup_details . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Application site
                                                    dryness</label>
                                                <div class="col-md-4">
                                                    <select class="form-control select2"
                                                            name="dryness"
                                                            id="dryness">
                                                        <option value="">Select...</option>
                                                        <option value="0">No</option>
                                                        <?php

                                                        foreach ($qutenza_list as $raw) {

                                                            echo '<option value="' . $raw->id . '">' . $raw->lookup_details . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <h3 class="form-section font-green"> Infections and
                                                Infestations</h3>
                                            <hr/>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Nasopharyngitis</label>
                                                <div class="col-md-4">
                                                    <select class="form-control select2"
                                                            name="nasopharyngitis"
                                                            id="nasopharyngitis">
                                                        <option value="">Select...</option>
                                                        <option value="0">No</option>
                                                        <option value="1">Yes</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Bronchitis</label>
                                                <div class="col-md-4">
                                                    <select class="form-control select2"
                                                            name="bronchitis"
                                                            id="bronchitis">
                                                        <option value="">Select...</option>
                                                        <option value="0">No</option>
                                                        <option value="1">Yes</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Sinusitis</label>
                                                <div class="col-md-4">
                                                    <select class="form-control select2"
                                                            name="sinusitis"
                                                            id="sinusitis">
                                                        <option value="">Select...</option>
                                                        <option value="0">No</option>
                                                        <option value="1">Yes</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <h3 class="form-section font-green"> Gastrointestinal
                                                Disorders</h3>
                                            <hr/>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Nausea</label>
                                                <div class="col-md-4">
                                                    <select class="form-control select2"
                                                            name="nausea"
                                                            id="nausea">
                                                        <option value="">Select...</option>
                                                        <option value="0">No</option>
                                                        <option value="1">Yes</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Vomiting</label>
                                                <div class="col-md-4">
                                                    <select class="form-control select2"
                                                            name="vomiting"
                                                            id="vomiting">
                                                        <option value="">Select...</option>
                                                        <option value="0">No</option>
                                                        <option value="1">Yes</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <h3 class="form-section font-green"> Skin and Subcutaneous
                                                Tissue Disorder</h3>
                                            <hr/>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Pruritus</label>
                                                <div class="col-md-4">
                                                    <select class="form-control select2"
                                                            name="skin_pruritus"
                                                            id="skin_pruritus">
                                                        <option value="">Select...</option>
                                                        <option value="0">No</option>
                                                        <option value="1">Yes</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <h3 class="form-section font-green"> Hypertension</h3>
                                            <hr/>
                                            <div class="row">
                                                <div class="form-group">
                                                    <label class="control-label col-md-3">Hypertension</label>
                                                    <div class="col-md-4">
                                                        <select class="form-control select2"
                                                                name="hypertension" id="hypertension"
                                                                onchange="show_hypdertension_div();">
                                                            <option value="">Select...</option>
                                                            <option value="0">No</option>
                                                            <option value="1">Yes</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row hyper_div" id="hyper_div">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label col-md-3">Blood Presure</label>
                                                        <!--        <label class="control-label col-md-3">Systolic</label>-->
                                                        <div class="col-md-2">
                                                            <input type="number" min="100" max="200"
                                                                   name="hypertension_systolic"
                                                                   id="hypertension_systolic"
                                                                   class="form-control" value=""/>
                                                            <span class="help-block"> Systolic </span>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <input type="number" min="50" max="160"
                                                                   name="hypertension_diastolic"
                                                                   id="hypertension_diastolic"
                                                                   class="form-control" value=""/>
                                                            <span class="help-block"> Diastolic </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="alert alert-danger qutenza-danger  display-hide">
                                            <button class="close"
                                                    data-close="alert"></button>
                                            You have some form errors. Please check
                                            below.
                                        </div>
                                        <div class="alert alert-success qutenza-success  display-hide">
                                            <button class="close"
                                                    data-close="alert"></button>
                                            Your form validation is successful!
                                        </div>
                                        @if($painFile_statusId==17 && auth()->user()->id!=100)<!-- 17=Active file,100=guest user -->
                                        <div class="form-actions">
                                            <div class="row">
                                                <div class="col-md-offset-3 col-md-9">
                                                    <button type="submit" class="btn green">Save
                                                    </button>
                                                    <button type="button"
                                                            class="btn grey-salsa btn-outline"
                                                            data-dismiss="modal">
                                                        Cancel
                                                    </button>

                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        {{Form::close()}}
                                    </div>
                                    <div class="tab-pane" id="tab_qutenza_2">
                                        <!-- BEGIN FORM-->
                                        {{--@include('qutenza.qutenza_form')--}}

                                        {{Form::open(['url'=>url('insert-qutenz-score'),'class'=>'form-horizontal','method'=>"post","id"=>"qutenza_score_form"])}}

                                        <div class="form-body">
                                            <div class="alert alert-danger qutenza-score-danger  display-hide">
                                                <button class="close"
                                                        data-close="alert"></button>
                                                You have some form errors. Please check
                                                below.
                                            </div>
                                            <div class="alert alert-success qutenza-score-success  display-hide">
                                                <button class="close"
                                                        data-close="alert"></button>
                                                Your form validation is successful!
                                            </div>

                                            <h3 class="form-section font-green"> Pain score / Week</h3>
                                            <hr/>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Visit
                                                    Date
                                                    <span class="required"> * </span>
                                                </label>
                                                <div class="col-md-4 input-group">
                                                    <input class="form-control date-picker"
                                                           type="text"
                                                           name="qutenza_score_date"
                                                           id="qutenza_score_date"
                                                           data-date-format="yyyy-mm-dd"
                                                           readonly="readonly"
                                                           value="{{date('Y-m-d')}}"/>
                                                    <span class="help-block"> Select date </span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Week</label>
                                                <div class="col-md-4">
                                                    <select class="form-control select2"
                                                            name="week"
                                                            id="week">
                                                        <option value="">Select...</option>
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
                                                        <option value="4">4</option>
                                                        <option value="5">5</option>
                                                        <option value="6">6</option>
                                                        <option value="7">7</option>
                                                        <option value="8">8</option>
                                                        <option value="9">9</option>
                                                        <option value="10">10</option>
                                                        <option value="11">11</option>
                                                        <option value="12">12</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3">
                                                    Pain score been on a scale from 0 to 10?

                                                </label>
                                                <div class="col-md-4">
                                                    <input type="number" max="10" min="0"
                                                           name="qutenza_pain_scale"
                                                           id="qutenza_pain_scale"
                                                           class="form-control"
                                                           value=""/>
                                                </div>
                                            </div>
                                        </div>
                                        @if($painFile_statusId==17 && auth()->user()->id!=100)<!-- 17=Active file,100=guest user -->
                                        <div class="form-actions">
                                            <div class="row">
                                                <div class="col-md-offset-3 col-md-9">
                                                    <button type="submit" class="btn green">Save
                                                    </button>
                                                    <button type="button"
                                                            class="btn grey-salsa btn-outline" data-dismiss="modal">
                                                        Cancel
                                                    </button>

                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        {{Form::close()}}

                                        <hr/>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-scrollable table-scrollable-borderless">
                                                    <table class="table table-striped table-hover">
                                                        <thead>
                                                        <tr class="uppercase">
                                                            <th> #</th>
                                                            <th>
                                                                Visit Date
                                                            </th>
                                                            <th>
                                                                Visit type
                                                            </th>
                                                            <th>
                                                                Week
                                                            </th>
                                                            <th>
                                                                Score
                                                            </th>
                                                            <th>
                                                                Qutenza Code.
                                                            </th>
                                                            <th>
                                                                Action
                                                            </th>
                                                        </tr>
                                                        </thead>
                                                        <tbody class="tbqutenz_score" id="tbqutenz_score">

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--                <div class="modal-footer">
                                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                                </div>-->
            </div>
        </div>
    </div>
    <!-- /.Project modal -->
    @push('css')

        <!-- BEGIN PAGE LEVEL PLUGINS -->

        <link href="{{url('/')}}/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css"
              rel="stylesheet"
              type="text/css"/>

        <link href="{{url('/')}}/assets/global/plugins/clockface/css/clockface.css" rel="stylesheet" type="text/css"/>
        <link href="{{url('/')}}/assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css"/>
        <link href="{{url('/')}}/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet"
              type="text/css"/>
        <link href="{{url('')}}/assets/global/plugins/bootstrap-sweetalert/sweetalert.css" rel="stylesheet"
              type="text/css"/>
        <style>
            tr.disabled {
                pointer-events: none;
            }
        </style>
    @endpush
    @push('js')

        <!-- BEGIN PAGE LEVEL PLUGINS -->

        <script src="{{url('/')}}/assets/global/plugins/moment.min.js" type="text/javascript"></script>
        <script src="{{url('/')}}/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"
                type="text/javascript"></script>

        <script src="{{url('/')}}/assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>


        <script src="{{url('')}}/assets/global/plugins/jquery-validation/js/jquery.validate.min.js"
                type="text/javascript"></script>
        <script src="{{url('/')}}/assets/global/plugins/horizontal-timeline/horizontal-timeline.js"
                type="text/javascript"></script>
        <!--  6. amCharts        ========================================= -->
        <script src="{{url('/')}}/assets/global/plugins/amcharts/amcharts/amcharts.js" type="text/javascript"></script>
        <script src="{{url('/')}}/assets/global/plugins/amcharts/amcharts/serial.js" type="text/javascript"></script>
        <script src="{{url('/')}}/assets/global/plugins/amcharts/amcharts/pie.js" type="text/javascript"></script>
        <script src="{{url('/')}}/assets/global/plugins/amcharts/amcharts/themes/light.js"
                type="text/javascript"></script>
        <script src="{{url('/')}}/assets/global/plugins/amcharts/amcharts/themes/patterns.js"
                type="text/javascript"></script>
        <script src="{{url('/')}}/assets/global/plugins/amcharts/amcharts/themes/chalk.js"
                type="text/javascript"></script>
        <script src="{{url('')}}/assets/js/general.js"
                type="text/javascript"></script>
        <script src="{{url('')}}/assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js"
                type="text/javascript"></script>
        <script src="{{url('')}}/assets/global/plugins/bootbox/bootbox.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->

        <!-- END THEME LAYOUT SCRIPTS -->

        <script>

            $(document).ready(function () {
                $(".select2, .select2-multiple").select2({
                    width: null
                });
                $('.date-picker').datepicker({
                    //    rtl: App.isRTL(),
                    //     orientation: "left",
                    autoclose: true,
                    endDate: '0d',
                    todayHighlight: true
                });
                $("input[name='change_diagnosis']").trigger('change');

                // Workaround to fix datepicker position on window scroll
                /* $( document ).scroll(function(){
                     $('#form_modal2 .date-picker').datepicker('place'); //#modal is the id of the modal
                 });
 */
                var followup_id = $('#followup_id').val();
                //   clear_qutenza_form(followup_id);
                show_hypdertension_div();
                //   show_psychopath_treatment(followup_id);
                //  clear_qutenza_score_form();

            });
            // Follow
            var followupFormValidation = function () {

                // basic validation
                var handleValidation1 = function () {

                    var form1 = $('#followup_form');
                    var error1 = $('.alert-danger', form1);
                    var success1 = $('.alert-success', form1);
                    // Unique FollowupDate

                    var response = true;


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
                                new_followup_date: {
                                    required: true,

                                }
                            },

                            messages:
                                { // custom messages for radio buttons and checkboxes

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

                                // followupSubmit();


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

            followupFormValidation.init();

            function followupSubmit() {


                App.blockUI();
                var form1 = $('#followup_form');
                var error = $('.alert-danger', form1);
                var success = $('.alert-success', form1);
                var action = form1.attr('action');
                var painFile_id = $('#painFile_id').val()
                var painFile_status = $('#painFile_statusid').val()
                var formData = new FormData(form1[0]);
                formData.append('painFile_id', painFile_id);
                formData.append('painFile_status', painFile_status);

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

                            success.show();
                            error.hide();
                            App.scrollTo(success, -200);
                            success.fadeOut(2000);
                            window.location.reload(true);

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

                })

            }

            //********************
            // Doctor
            var followupDoctorFormValidation = function () {

                // basic validation
                var handleValidation1 = function () {
                    // for more info visit the official plugin documentation:
                    // http://docs.jquery.com/Plugins/Validation
                    var followup_id = $('#followup_id').val();
                    var form1 = $('#doctor_followup_form' + followup_id);
                    var error1 = $('.alert-danger', form1);
                    var success1 = $('.alert-success', form1);
                    // Unique FollowupDate

                    var response2 = false;
                    $.validator.addMethod(
                        "uniqueDocFollowupDate",
                        function (value, element) {

                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });
                            if (value != '')
                                $.ajax({
                                    type: "POST",
                                    url: '{{url('followupDoctor/availabileFollowupDate')}}',

                                    data: {followup_date: value},
                                    error: function (xhr, status, error) {
                                        alert(xhr.responseText);
                                    },
                                    beforeSend: function () {
                                    },
                                    complete: function () {
                                    },
                                    success: function (data) {
                                        if (data.success == true) {
                                            if (data.count > 0)
                                                response2 = false;
                                            else
                                                response2 = true;
                                            return response2;
                                        } else {
                                            response2 = true;

                                            return response2;
                                        }
                                    }
                                });//END $.ajax

                        },
                        "This Followup Date is all ready exist."
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
                                doc_follow_up_date: {
                                    required: true,
                                    // uniqueDocFollowupDate: true
                                },
                                /* "diagnosis_id[]": {
                                     required: true
                                 },
                                 physical_treatment: {
                                     required: true
                                 }*/
                            },

                            messages:
                                { // custom messages for radio buttons and checkboxes

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

                                doctorfollowupSubmit();


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

            followupDoctorFormValidation.init();

            function doctorfollowupSubmit() {

                App.blockUI();
                var followup_id = $('#followup_id').val();
                var form1 = $('#doctor_followup_form' + followup_id);
                var error = $('.alert-danger', form1);
                var success = $('.alert-success', form1);
                var action = form1.attr('action');
                var painFile_id = $('#painFile_id').val()
                var painFile_status = $('#painFile_statusid').val()
                var formData = new FormData(form1[0]);
                formData.append('followup_id', followup_id);
                formData.append('painFile_id', painFile_id);
                formData.append('painFile_status', painFile_status);
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

            //********************
            // Nurse
            var followupNurseFormValidation = function () {

                // basic validation
                var handleValidation1 = function () {
                    // for more info visit the official plugin documentation:
                    // http://docs.jquery.com/Plugins/Validation
                    var followup_id = $('#followup_id').val();
                    var form1 = $('#nurse_followup_form' + followup_id);
                    var error1 = $('.alert-danger', form1);
                    var success1 = $('.alert-success', form1);
                    // Unique Followup date

                    var response2 = false;
                    $.validator.addMethod(
                        "uniqueNurseFollowupDate",
                        function (value, element) {

                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });
                            if (value != '')
                                $.ajax({
                                    type: "POST",
                                    url: '{{url('followupNurse/availabileFollowupDate')}}',

                                    data: {followup_date: value},
                                    error: function (xhr, status, error) {
                                        alert(xhr.responseText);
                                    },
                                    beforeSend: function () {
                                    },
                                    complete: function () {
                                    },
                                    success: function (data) {
                                        if (data.success == true) {
                                            if (data.count > 0)
                                                response2 = false;
                                            else
                                                response2 = true;
                                            return response2;
                                        } else {
                                            response2 = true;

                                            return response2;
                                        }
                                    }
                                });//END $.ajax

                        },
                        "This Followup Date is all ready exist."
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
                                nurse_follow_up_date: {
                                    required: true
                                }
                            },

                            messages:
                                { // custom messages for radio buttons and checkboxes

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

                                nursefollowupSubmit();


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

            followupNurseFormValidation.init();

            function nursefollowupSubmit() {

                App.blockUI();
                var followup_id = $('#followup_id').val();
                var form1 = $('#nurse_followup_form' + followup_id);
                var error = $('.alert-danger', form1);
                var success = $('.alert-success', form1);
                var action = form1.attr('action');
                var painFile_id = $('#painFile_id').val()
                var painFile_status = $('#painFile_statusid').val()

                var formData = new FormData(form1[0]);
                formData.append('followup_id', followup_id);
                formData.append('painFile_id', painFile_id);
                formData.append('painFile_status', painFile_status);
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

            //************************************
            //Pharm
            var followupPharmFormValidation = function () {

                // basic validation
                var handleValidation1 = function () {
                    // for more info visit the official plugin documentation:
                    // http://docs.jquery.com/Plugins/Validation
                    var followup_id = $('#followup_id').val();
                    var form1 = $('#pharm_followup_form' + followup_id);
                    var error1 = $('.alert-danger', form1);
                    var success1 = $('.alert-success', form1);
                    // Unique Followup

                    var response2 = true;
                    $.validator.addMethod(
                        "uniquePharmFollowupDate",
                        function (value, element) {
                            var followup_id = $('#followup_id').val();
                            var data_value = $('#pharm_follow_up_date' + followup_id).data("value");
                            //alert(data_value);

                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });
                            if (value != '' && data_value != value)
                                $.ajax({
                                    type: "POST",
                                    url: '{{url('followupPharm/availabileFollowupDate')}}',

                                    data: {followup_date: value, followup_id: followup_id},
                                    error: function (xhr, status, error) {
                                        alert(xhr.responseText);
                                    },
                                    beforeSend: function () {
                                    },
                                    complete: function () {
                                    },
                                    success: function (data) {
                                        if (data.success == true) {
                                            return true
                                        } else {
                                            return false
                                        }
                                    }
                                });//END $.ajax
                            else
                                return true;

                        },
                        "This Followup Date is all ready exist."
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
                                pharm_follow_up_date: {
                                    required: true,
                                    //  uniquePharmFollowupDate:false
                                },
                                "adverse_effects[]": {
                                    required: true
                                }
                            },

                            messages:
                                { // custom messages for radio buttons and checkboxes

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

                                pharmfollowupSubmit();


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

            followupPharmFormValidation.init();

            function pharmfollowupSubmit() {
                App.blockUI();
                var followup_id = $('#followup_id').val();
                var form1 = $('#pharm_followup_form' + followup_id);
                var error = $('.alert-danger', form1);
                var success = $('.alert-success', form1);
                var action = form1.attr('action');
                var painFile_id = $('#painFile_id').val()
                var painFile_status = $('#painFile_statusid').val()
                form1.validate();
                var formData = new FormData(form1[0]);
                formData.append('followup_id', followup_id);
                formData.append('painFile_id', painFile_id);
                formData.append('painFile_status', painFile_status);
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

            // PSYCHOLOGIST
            var followupPsychologyFormValidation = function () {

                // basic validation
                var handleValidation1 = function () {
                    // for more info visit the official plugin documentation:
                    // http://docs.jquery.com/Plugins/Validation
                    var followup_id = $('#followup_id').val();
                    var form1 = $('#baseline_psychology_form' + followup_id);
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
                                visit_date_psychology: {
                                    required: true,
                                    // uniqueDocFollowupDate: true
                                },
                                /* "treatment_today[]": {
                                     required: true
                                 },/*
                                 physical_treatment: {
                                     required: true
                                 }*/
                            },

                            messages:
                                { // custom messages for radio buttons and checkboxes

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

                                psychologyFollowupSubmit();


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

            followupPsychologyFormValidation.init();

            function psychologyFollowupSubmit() {

                App.blockUI();
                var followup_id = $('#followup_id').val();
                var form1 = $('#baseline_psychology_form' + followup_id);
                var error = $('.alert-danger', form1);
                var success = $('.alert-success', form1);
                var action = form1.attr('action');
                var painFile_id = $('#painFile_id').val()
                var painFile_status = $('#painFile_statusid').val()
                var formData = new FormData(form1[0]);
                formData.append('followup_id', followup_id);
                formData.append('painFile_id', painFile_id);
                formData.append('painFile_status', painFile_status);
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

            //*********** charts *******************************
            function show_score_chart(id) {
                blockUI('#dvScoreChart');

                $('#dvNoteNoRecord').addClass('display-hide');
                $("#dvScoreChart").html('');

                var datagoal = [];
                var guidesdata = [];
                var data2 = [];
                var data3 = [];
                var painFile_id = $('#painFile_id').val()
                var painFile_status = $('#painFile_statusid').val()
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('followup/show-charts')}}',

                    data: {painFile_id: painFile_id, painFile_status: painFile_status},
                    error: function (xhr, status, error) {

                    },
                    beforeSend: function () {
                    },
                    complete: function () {
                    },
                    success: function (data) {

                        creat_score_chart(data.score_data);

                        for (let i = 0; i < data.guidesdata.length; ++i) {
                            guidesdata = {
                                "id": "guide-" + i,
                                "value": data.guidesdata[i].baseline_goal_score,
                                "lineColor": "#26C281",
                                "color": "#26C281",
                                "lineAlpha": 0.7,
                                "lineThickness": 2,
                                "dashLength": 2,
                                "inside": true,
                                "boldLabel": true,
                                "position": "right",
                                "label": data.guidesdata[i].baseline_goal
                            }
                            data3.push(guidesdata);
                        }
                        for (let i = 0; i < data.goals.length; ++i) {
                            //     alert(data.goals[i].baseline_goal);
                            datagoal = {
                                "bullet": "round",
                                "bulletBorderAlpha": 1,
                                "bulletBorderThickness": 1,
                                "lineThickness": 3,
                                "title": data.goals[i].baseline_goal,
                                "valueField": data.goals[i].baseline_goal
                            };

                            data2.push(datagoal);

                        }
                        //   alert(datagoal.length);
                        creat_goal_chart(data.goal_data, data2, data3);
                        unblockUI('#dvScoreChart');
                    }
                });//END $.ajax
            }

            function creat_score_chart(data) {
                /******
                 * Reference: https://www.amcharts.com/docs/v3/tutorials/displaying-chart-guides-in-legend/
                 *******/
                var chart = AmCharts.makeChart("dvScoreChart", {
                    "type": "serial",
                    "theme": "light",
                    "marginRight": 30,
                    "legend": {
                        "equalWidths": false,
                        //"periodValueText": "total: [[value.sum]]",
                        "position": "top",
                        "valueAlign": "left",
                        "valueWidth": 100
                    },
                    "dataProvider": data,
                    "valueAxes": [{
                        "minimum": 0,
                        "maximum": 10,
                        "gridAlpha": 0.07,
                        "position": "left",
                        "guides": []
                    }],
                    "graphs": [{
                        "bullet": "round",
                        "bulletBorderAlpha": 1,
                        "bulletBorderThickness": 1,
                        "lineThickness": 3,
                        "title": "Pain Intensity",
                        "valueField": "pain_scale"
                    }, {
                        "bullet": "round",
                        "bulletBorderAlpha": 1,
                        "bulletBorderThickness": 1,
                        "lineThickness": 3,
                        "title": "Pain Bothersomeness",
                        "valueField": "pain_bothersomeness"
                    }, {
                        "bullet": "round",
                        "bulletBorderAlpha": 1,
                        "bulletBorderThickness": 1,
                        "lineThickness": 3,
                        "title": "Pain Intensity During Rest ",
                        "valueField": "pain_intensity_during_rest"
                    }, {
                        "bullet": "round",
                        "bulletBorderAlpha": 1,
                        "bulletBorderThickness": 1,
                        "lineThickness": 3,
                        "title": "Pain Intensity During activity",
                        "valueField": "pain_intensity_during_activity"
                    }
                    ],
                    "chartCursor": {},
                    "categoryField": "visit_date",
                    "categoryAxis": {
                        "minGridDistance ": 0,
                        "maxGridDistance ": 10,
                        "startOnAxis": true,
                        "gridAlpha": 0.07,
                        "labelRotation": 45		// Label Rotation
                    }
                });

                $('#dvScoreChart').closest('.portlet').find('.fullscreen').click(function () {
                    chart.invalidateSize();
                });
            }

            function creat_goal_chart(data, datagoal, guidesData) {
                /******
                 * Reference: https://www.amcharts.com/docs/v3/tutorials/displaying-chart-guides-in-legend/
                 *******/
                //   alert('datagoal[0]'+datagoal[0]["title"]);
                console.log(datagoal);
                var chart = AmCharts.makeChart("dvGoalsChart", {
                    "type": "serial",
                    "theme": "light",
                    "marginRight": 30,
                    "legend": {
                        "equalWidths": false,
                        //"periodValueText": "total: [[value.sum]]",
                        "position": "top",
                        "valueAlign": "left",
                        "valueWidth": 100
                    },
                    "dataProvider": data,
                    "valueAxes": [{
                        "minimum": 0,
                        "maximum": 11,
                        "gridAlpha": 0.07,
                        "position": "left",
                        "marginLeft": 20,
                        "guides": guidesData,
                        /* "listeners": [{
                               "event": "drawn",
                               "method": function() {
                                   var guide2Text = document.querySelector('.tspan');
                                   if (guide2Text) {
                                       guide2Text.setAttribute('y', 20);
                                   }
                               }
                           }]*/
                    }],
                    "graphs":
                    datagoal/*{
                        "bullet": "round",
                        "bulletBorderAlpha": 1,
                        "bulletBorderThickness": 1,
                        "lineThickness": 3,
                        "title": "Goals",
                        "valueField": "baseline_goal"
                    }*/,
                    "chartCursor": {},
                    "categoryField": "visit_date",
                    "categoryAxis": {
                        "startOnAxis": true,
                        "gridAlpha": 0.07,
                        "labelRotation": 45		// Label Rotation
                    }
                });

                $('#dvGoalsChart').closest('.portlet').find('.fullscreen').click(function () {
                    chart.invalidateSize();
                });
            }

            //***********************

            function check_available_date() {
                if ($("#new_followup_date").val() != '') {
                    blockUI('create_followup_btn');
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        type: "POST",
                        url: '{{url('followup/availabileFollowupDate')}}',

                        data: {followup_date: $('#new_followup_date').val(), painFile_id: $('#painFile_id').val()},
                        success: function (data) {
                            unblockUI('create_followup_btn');
                            if (data.success == false) {
                                $("#new_followup_date").closest('.form-group').addClass('has-error');
                                $('.alert-danger').show();
                                $('.alert-success').hide();
                                // $('.alert-danger').fadeOut(2000);
                            } else {
                                $("#new_followup_date").closest('.form-group').removeClass('has-error');
                                $('.alert-success').show();
                                $('.alert-danger').hide();

                                followupSubmit();
                            }

                        }

                    });//END $.ajax
                }
            }

            function saveFollowupscore(id) {

                var followup_score = $('#doc_followup_score' + id).val();
                //**************************//

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('followup/update-goal-score')}}',

                    data: {
                        id: id, followup_score: followup_score
                    },
                    error: function (xhr, status, error) {

                    },
                    beforeSend: function () {
                    },
                    complete: function () {
                    },
                    success: function (data) {
                        if (data.success) {
                            $('#nurse_followup_score' + id).val(followup_score);

                            $('.alert-goal').show();
                            $('.alert-goal').fadeOut(2000);
                        } else {
                            alert('error');
                        }
                    }
                });//END $.ajax
            }

            function saveFollowupcompliance(id) {
                var followup_compliance = $('#doc_followup_compliance' + id).val();


                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('followup/update-goal-compliance')}}',

                    data: {
                        id: id, followup_compliance: followup_compliance
                    },
                    error: function (xhr, status, error) {

                    },
                    beforeSend: function () {
                    },
                    complete: function () {
                    },
                    success: function (data) {
                        if (data.success) {

                            $('#nurse_followup_compliance' + id).val(followup_compliance);
                            $('.alert-goal').show();
                            $('.alert-goal').fadeOut(2000);
                        } else {
                            alert('error');
                        }
                    }
                });//END $.ajax
            }

            function refreshGoals(followup_id) {
                // blockUI('tbtreatmentDoctorFollow' + id);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('followup/refresh-treatment-goals')}}',

                    data: {
                        followup_id: followup_id,
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

                        if (data.success) {
                            // alert(data.html)

                            $('#tbdoctortreatment' + followup_id).html(data.treat_doc_goal);
                            $('#tbtreat_nurse_goal' + followup_id).html(data.treat_nurse_goal);

                        } else {
                            alert('error');
                        }
                    }
                });//END $.ajax
            }

            function add_treatment_follow_drug(id) {
                //doctor treatments
                blockUI('tbtreatmentDoctorFollow' + id);
                var drug_id = $('#drug_id' + id).val();
                var batch_id = $('#batch_id' + id).val();
                var drug_price = $('#drug_price' + id).val();
                // var concentration = $('#concentration' + id).val();
                var dosage = $('#dosage' + id).val();
                var frequency = $('#frequency' + id).val();
                var duration = $('#duration' + id).val();
                var quantity = $('#quantity' + id).val();
                var drug_cost = $('#drug_cost' + id).val();
                var drug_comments = $('#drug_comments' + id).val();

                $('#drug_id' + id).parent().removeClass('has-error');
                // $("#effect_id").parent().removeClass('has-error');
                if (drug_id == '') {
                    $('.alert-danger-treatment' + id).show();
                    $('.alert-danger-treatment' + id).fadeOut(2000);
                    $('#drug_id' + id).parent().addClass('has-error');
                } else
                    $('#drug_id' + id).parent().removeClass('has-error');
                if ((dosage == '') || (isNaN(dosage))) {
                    $('.alert-danger-treatment' + id).show();
                    $('.alert-danger-treatment' + id).fadeOut(2000);
                    $('#dosage' + id).parent().addClass('has-error');
                    return;
                } else
                    $('#dosage' + id).parent().removeClass('has-error');
                if (frequency == '' || (isNaN(frequency))) {
                    $('.alert-danger-treatment' + id).show();
                    $('.alert-danger-treatment' + id).fadeOut(2000);
                    $('#frequency' + id).parent().addClass('has-error');
                    return;
                } else
                    $('#frequency' + id).parent().removeClass('has-error');
                if (duration == '' || (isNaN(duration))) {
                    $('.alert-danger-treatment' + id).show();
                    $('.alert-danger-treatment' + id).fadeOut(2000);
                    $('#duration' + id).parent().addClass('has-error');
                    return;
                } else
                    $('#duration' + id).parent().removeClass('has-error');

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('followup/insert-treatment-drug')}}',

                    data: {
                        followup_id: id,
                        drug_id: drug_id,
                        batch_id: batch_id,
                        drug_price: drug_price,
                        dosage: dosage,
                        //       concentration: concentration,
                        frequency: frequency,
                        duration: duration,
                        quantity: quantity,
                        drug_cost: drug_cost,
                        drug_comments: drug_comments,
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
                        unblockUI('tbtreatmentDoctorFollow' + id);
                        if (data.success) {
                            // alert(data.html)
                            $('.alert-success-treatment' + id).show();
                            $('.alert-success-treatment' + id).fadeOut(2000);
                            //alert-treatment+id
                            $('#tbtreatmentDoctorFollow' + id).html(data.doctor_html);
                            $('#tbpharmtreatment' + id).html(data.pharm_html);
                            $(".select2, .select2-multiple").select2({
                                width: null
                            });
                            $('#dosage' + id).val('');
                            $('#frequency' + id).val('');
                            $('#duration' + id).val('');
                            $('#drug_comments' + id).val('');
                            $('#drug_cost' + id).val('');
                            $('#drug_price' + id).val('');
                            $('#batch_id' + id).val('');
                            $('#quantity' + id).val('');
                        } else {
                            alert('error');
                        }
                    }
                });//END $.ajax
            }

            function update_doc_followup_treatment(id, followup_id) {
                blockUI('tbtreatmentDoctorFollow' + id);
                //   var concentration = $('#concentration' + id).val();
                var dosage = $('#dosage' + id).val();
                var frequency = $('#frequency' + id).val();
                var duration = $('#duration' + id).val();
                var quantity = $('#quantity' + id).val();
                var drug_cost = $('#drug_cost' + id).val();
                var drug_price = $('#drug_price' + id).val();
                var compliance = $('#compliance' + id).val();
                var adverse_effects = $('#adverse_effects' + id).val();
                var decision = $('#decision' + id).val();
                var drug_comments = $('#drug_follow_comments' + id).val();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('followup/update-treatment-drug')}}',

                    data: {
                        id: id,
                        followup_id: followup_id,
                        //    concentration: concentration,
                        dosage: dosage,
                        frequency: frequency,
                        duration: duration,
                        quantity: quantity,
                        drug_cost: drug_cost,
                        drug_price: drug_price,
                        drug_comments: drug_comments,
                        compliance: compliance,
                        adverse_effects: adverse_effects,
                        decision: decision,
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
                        unblockUI('tbtreatmentDoctorFollow' + id);
                        if (data.success) {
                            // alert(data.html)
                            $('.alert-success-treatment' + followup_id).show();
                            $('.alert-success-treatment' + followup_id).fadeOut(2000);
                            //alert-treatment+id
                            $('#tbtreatmentDoctorFollow' + followup_id).html(data.doctor_html);
                            $('#tbpharmtreatment' + followup_id).html(data.pharm_html);
                            $(".select2, .select2-multiple").select2({
                                width: null
                            });
                        } else {
                            alert('error');
                        }
                    }
                });//END $.ajax
            }

            function update_pharm_treatment(id) {
                var followup_id = $('#followup_id').val();
                blockUI('tbpharmtreatment' + followup_id);
                //  var concentration = $('#pharm_treat_concentration' + id).val();
                var dosage = $('#pharm_treat_dosage' + id).val();
                var frequency = $('#pharm_treat_frequency' + id).val();
                var duration = $('#pharm_treat_duration' + id).val();
                var quantity = $('#pharm_treat_quantity' + id).val();
                var drug_cost = $('#pharm_treat_drug_cost' + id).val();
                var drug_price = $('#pharm_treat_drug_price' + id).val();
                var compliance = $('#pharm_treat_compliance' + id).val();
                var adverse_effects = $('#pharm_treat_adverse_effects' + id).val();
                var decision = $('#pharm_treat_decision' + id).val();
                var drug_comments = $('#pharm_drug_follow_comments' + id).val();

                // $('#drug_id' + id).parent().removeClass('has-error');
                // // $("#effect_id").parent().removeClass('has-error');
                // if (drug_id == '')
                //     $('#drug_id' + id).parent().addClass('has-error');
                // else
                //     $('#drug_id' + id).parent().removeClass('has-error');


                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('followup/update-pharm-treatment')}}',

                    data: {
                        id: id,
                        // concentration: concentration,
                        dosage: dosage,
                        frequency: frequency,
                        duration: duration,
                        quantity: quantity,
                        drug_cost: drug_cost,
                        drug_price: drug_price,
                        compliance: compliance,
                        adverse_effects: adverse_effects,
                        decision: decision,
                        followup_id: followup_id,
                        painFile_id: $('#painFile_id').val(),
                        painFile_status: $('#painFile_statusid').val(),
                        drug_comments: drug_comments

                    },
                    error: function (xhr, status, error) {

                    },
                    beforeSend: function () {
                    },
                    complete: function () {
                    },
                    success: function (data) {
                        unblockUI('tbpharmtreatment' + followup_id);
                        if (data.success) {

                            /*   if (decision == 117)
                                   $('#pharm_treat_drug_specify' + id).html('<span class="label label-sm label-info">' + dosage + '</span>');*/
                            $('#tbtreatmentDoctorFollow' + followup_id).html(data.doctor_html);

                            $('#tbpharmtreatment' + followup_id).html(data.pharm_html);
                            $(".select2, .select2-multiple").select2({
                                width: null
                            });
                            $('.alert-success-treatment-pharm' + followup_id).show();

                            $('.alert-success-treatment-pharm' + followup_id).fadeOut(2000);
                            //alert-treatment+id
                            // $('#tbtreatmentDoctorFollow' + id).html(data.html);
                        } else {
                            alert('error');
                        }
                    }
                });//END $.ajax
            }

            function add_pharm_treatment_drug(id) {
                blockUI('tbpharmtreatment' + id);
                var drug_id = $('#pharm_drug_id' + id).val();
                var batch_id = $('#pharm_batch_id' + id).val();
                var drug_price = $('#pharm_drug_price' + id).val();
                //   var concentration = $('#pharm_concentration' + id).val();
                var dosage = $('#pharm_dosage' + id).val();
                var frequency = $('#pharm_frequency' + id).val();
                var duration = $('#pharm_duration' + id).val();
                var quantity = $('#pharm_quantity' + id).val();
                var drug_cost = $('#pharm_drug_cost' + id).val();

                $('#pharm_drug_id' + id).parent().removeClass('has-error');
                // $("#effect_id").parent().removeClass('has-error');
                if (drug_id == '') {
                    $('.alert-danger-treatment-pharm' + id).show();
                    $('.alert-danger-treatment-pharm' + id).fadeOut(2000);
                    $('#pharm_drug_id' + id).parent().addClass('has-error');
                    return;
                } else
                    $('#pharm_drug_id' + id).parent().removeClass('has-error');

                if (dosage == '' || (isNaN(dosage))) {
                    $('.alert-danger-treatment-pharm' + id).show();
                    $('.alert-danger-treatment-pharm' + id).fadeOut(2000);
                    $('#pharm_dosage' + id).parent().addClass('has-error');
                    return;
                } else
                    $('#pharm_dosage' + id).parent().removeClass('has-error');
                if (frequency == '' || (isNaN(frequency))) {
                    $('.alert-danger-treatment-pharm' + id).show();
                    $('.alert-danger-treatment-pharm' + id).fadeOut(2000);
                    $('#pharm_frequency' + id).parent().addClass('has-error');
                    return;
                } else
                    $('#pharm_frequency' + id).parent().removeClass('has-error');
                if (duration == '' || (isNaN(duration))) {
                    $('.alert-danger-treatment-pharm' + id).show();
                    $('.alert-danger-treatment-pharm' + id).fadeOut(2000);
                    $('#pharm_duration' + id).parent().addClass('has-error');
                    return;
                } else
                    $('#pharm_duration' + id).parent().removeClass('has-error');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('followup/insert-pharm-treatment-drug')}}',

                    data: {
                        followup_id: id,
                        drug_id: drug_id,
                        batch_id: batch_id,
                        drug_price: drug_price,
                        //   concentration: concentration,
                        dosage: dosage,
                        frequency: frequency,
                        duration: duration,
                        quantity: quantity,
                        drug_cost: drug_cost,
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
                        unblockUI('tbpharmtreatment' + id);
                        if (data.success) {
                            // alert(data.html)

                            $('.alert-success-treatment-pharm' + id).show();
                            $('.alert-success-treatment-pharm' + id).fadeOut(2000);
                            //alert-treatment+id
                            $('#tbtreatmentDoctorFollow' + id).html(data.doctor_html);
                            $('#tbpharmtreatment' + id).html(data.pharm_html);
                            $('#pharm_drug_id' + id).val('');
                            $('#pharm_dosage' + id).val('');
                            $('#pharm_frequency' + id).val('');
                            $('#pharm_duration' + id).val('');
                            $('#pharm_drug_cost' + id).val('');
                            $('#pharm_drug_price' + id).val('');
                            $('#pharm_batch_id' + id).val('');
                            $('#pharm_quantity' + id).val('');
                            $(".select2, .select2-multiple").select2({
                                width: null
                            });
                        } else {
                            alert('error');
                        }
                    }
                });//END $.ajax
            }

            function del_treatment_followup_drug(id, followup_id) {

                blockUI('tbtreatmentDoctorFollow' + followup_id);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('followup/del-treatment-drug')}}',

                    data: {
                        followup_id: followup_id,
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
                        unblockUI('tbtreatmentDoctorFollow' + followup_id);
                        if (data.success) {
                            $('#tbtreatmentDoctorFollow' + followup_id).html(data.doctor_html);
                            $('#tbpharmtreatment' + followup_id).html(data.pharm_html);

                            $('.alert-success-treatment' + followup_id).show();
                            $('.alert-success-treatment' + followup_id).fadeOut(2000);
                            $(".select2, .select2-multiple").select2({
                                width: null
                            });
                        } else {
                            alert('error');
                        }
                    }
                });//END $.ajax


            }

            function get_followup_doctor(followup_id) {

                App.blockUI();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('followup/get-followup-doctor')}}',

                    data: {

                        followup_id: followup_id,
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
                        App.unblockUI();
                        if (data.success) {
                            $('#tab_1_1_1_' + followup_id).css("pointer-events", "");
                            if (data.followupDoctor != null) {

                                if (data.disable == false)
                                    $('#tab_1_1_1_' + followup_id).css("pointer-events", "none");

                                $('#firstDoctor' + followup_id).html(data.doctor_name);
                                $('#doc_follow_up_date' + followup_id).val(data.followupDoctor.follow_up_date);
                                $('#second_doctor' + followup_id).val(data.followupDoctor.second_doctor);
                                $('#treatment_goal_achievements' + followup_id).val(data.followupDoctor.treatment_goal_achievements);
                                $('#change_diagnosis' + followup_id).val(data.followupDoctor.change_diagnosis);
                                $('#change_treatment' + followup_id).val(data.followupDoctor.change_treatment);
                                $('#followup_doctor_comment' + followup_id).html(data.followupDoctor.followup_doctor_comment);
                                $('#followup_doctor_lab' + followup_id).html(data.followupDoctor.followup_doctor_lab);
                                $('#followup_doctor_rad' + followup_id).html(data.followupDoctor.followup_doctor_rad);

                                $('#pain_scale' + followup_id).val(data.followupDoctor.pain_scale);
                                $('#pain_bothersomeness' + followup_id).val(data.followupDoctor.pain_bothersomeness);
                                $('#pain_intensity_during_rest' + followup_id).val(data.followupDoctor.pain_intensity_during_rest);
                                $('#pain_intensity_during_activity' + followup_id).val(data.followupDoctor.pain_intensity_during_activity);

                                $('#physical_treatment' + followup_id).val(data.followupDoctor.physical_treatment);
                                $('#specify_physical_treatment' + followup_id).val(data.followupDoctor.specify_physical_treatment);
                                $('#last_scheduled_further_treatment' + followup_id).val(data.followupDoctor.last_scheduled_further_treatment);
                                $('#doctor_message' + followup_id).val(data.followupDoctor.doctor_message);
                            }
                            //  alert(data.doc_followup_diagnosis)
                            // alert('followup_id'+followup_id)
                            $('#diagnosis_id' + followup_id).val(data.doc_followup_diagnosis);
                            //$('#doc_followup_diagnosis option[value=' + val + ']').attr('selected', true);
                            $('.select2').trigger('change');
                            $('.select2-multiple').trigger('change');

                            $('#tbtreatmentDoctorFollow' + followup_id).html('');
                            $('#tbdoctortreatment' + followup_id).html('');
                            if (data.treatmentFollowup_data != '')
                                $('#tbtreatmentDoctorFollow' + followup_id).html(data.treatmentFollowup_data);
                            if (data.treat_doc_goal != '')
                                $('#tbdoctortreatment' + followup_id).html(data.treat_doc_goal);
                        } else {

                            alert('error');
                        }
                    }
                });//END $.ajax

            }

            function get_followup_nurse(followup_id) {
                App.blockUI();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('followup/get-followup-nurse')}}',

                    data: {
                        painFile_id: $('#painFile_id').val(),
                        painFile_status: $('#painFile_statusid').val(),
                        followup_id: followup_id,

                    },
                    error: function (xhr, status, error) {

                    },
                    beforeSend: function () {
                    },
                    complete: function () {
                    },
                    success: function (data) {
                        App.unblockUI();
                        if (data.success) {
                            if (data.followupNurse != null) {
                                $('#nurse_follow_up_date' + followup_id).val(data.followupNurse.follow_up_date);
                                $('#second_nurse' + followup_id).val(data.followupNurse.second_nurse);
                                $('#nurse_treat_goal_achiev' + followup_id).val(data.followupNurse.treatment_goal_achievements);

                                $('#nurse_message' + followup_id).val(data.followupNurse.nurse_message);
                            }
                            $('.select2').trigger('change');
                            $('#tbtreat_nurse_goal' + followup_id).html('');
                            if (data.treat_nurse_goal != '')
                                $('#tbtreat_nurse_goal' + followup_id).html(data.treat_nurse_goal);
                            if (data.chk_neck_and_shoulder != '')
                                $('#chk_neck_and_shoulder' + followup_id).html(data.chk_neck_and_shoulder);
                            if (data.chk_lower_back != '')
                                $('#chk_lower_back' + followup_id).html(data.chk_lower_back);
                        } else {
                            alert('error');
                        }
                    }
                });//END $.ajax

            }

            function get_followup_pharm(followup_id) {
                App.blockUI();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('followup/get-followup-pharm')}}',

                    data: {

                        followup_id: followup_id,
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
                        App.unblockUI();
                        if (data.success) {
                            if (data.followupPharm != null) {
                                $('pharm_follow_up_date' + followup_id).val(data.followupPharm.follow_up_date);
                                $('#pharm_specify_other_changes' + followup_id).val(data.followupPharm.pharm_specify_other_changes);
                                $('#specify_other_adverse_effects' + followup_id).val(data.followupPharm.specify_other_adverse_effects);
                                $('#pharm_message' + followup_id).val(data.followupPharm.pharm_message);
                            }
                            //alert(data.Pharm_adverse_effects)
                            $('#adverse_effects' + followup_id).val(data.Pharm_adverse_effects);
                            $('.select2').trigger('change');
                            $('.select2-multiple').trigger('change');
                            $('#tbpharmtreatment' + followup_id).html('');
                            if (data.Pharm_treatmentFollowup_data != '')

                                $('#tbpharmtreatment' + followup_id).html(data.Pharm_treatmentFollowup_data);
                            $(".select2, .select2-multiple").select2({
                                width: null
                            });

                        } else {
                            alert('error');
                        }
                    }
                });//END $.ajax

            }

            function get_followup_psychology(followup_id) {
                App.blockUI();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('followup/get-followup-psychology')}}',

                    data: {
                        followup_id: followup_id,
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
                        App.unblockUI();
                        if (data.success) {

                            $('#tab_1_1_4_' + followup_id).css("pointer-events", "");
                            if (data.followupPsychology != null) {
                                if (data.disable == false)
                                    $('#tab_1_1_4_' + followup_id).css("pointer-events", "none");
                                $('#visit_date_psychology' + followup_id).val(data.followupPsychology.follow_up_date);
                                $('#physical_exam' + followup_id).val(data.followupPsychology.physical_exam);
                                $('#treatment_today' + followup_id).val(data.psy_followup_treatment).trigger('change');
                                $('#other_treatment_today' + followup_id).val(data.followupPsychology.other_treatment_today);
                                $('#psychology_message' + followup_id).val(data.followupPsychology.psychology_message);
                            }
                        } else {

                            alert('error');
                        }
                    }
                });//END $.ajax

            }

            function saveFollowupNurseGoal(id) {
                var followup_score = $('#nurse_followup_score' + id).val();
                var followup_compliance = $('#nurse_followup_compliance' + id).val();
                var physical_treatment = $('#nurse_physical_treatment' + id).val();
                var days_on_prg = $('#nurse_days_on_prg' + id).val();
                var followup_id = $('#followup_id').val();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('followup/update-nurse-treatment-goal')}}',

                    data: {
                        id: id,
                        followup_score: followup_score,
                        followup_compliance: followup_compliance,
                        physical_treatment: physical_treatment,
                        days_on_prg: days_on_prg,
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

                        if (data.success) {
                            // alert(data.html)
                            $('#doc_followup_compliance' + id).val(followup_compliance);
                            $('#doc_followup_score' + id).val(followup_score);

                            $('.alert-nurse-goal' + followup_id).show();
                            $('.alert-nurse-goal' + followup_id).fadeOut(2000);
                            //alert-treatment+id
                            //$('#tbtreatmentDoctorFollow' + id).html(data.html);
                        } else {
                            alert('error');
                        }
                    }
                });//END $.ajax
            }

            function cancel_consultation(type_id) {
                var followup_id = $('#followup_id').val();
                var r = confirm('This will cancel consultation,Are you sure?');

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
                        url: '{{url('followup/cancel-consultation')}}',

                        data: {
                            type_id: type_id,
                            followup_id: followup_id,
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

                            if (data.success) {
                                if (type_id == 1) {
                                    var form1 = $('#doctor_followup_form' + followup_id);
                                    $('#doctor_followup_form' + followup_id + ' .select2').val('');
                                    $('#diagnosis_id' + followup_id).val('');
                                } else if (type_id == 2) {
                                    var form1 = $('#nurse_followup_form' + followup_id);
                                    $('#nurse_followup_form' + followup_id + ' .select2').val('');
                                    $('#nurse_followup_form' + followup_id + ' .select2-multiple').val('');
                                } else if (type_id == 3) {
                                    var form1 = $('#pharm_followup_form' + followup_id);
                                    $('#pharm_followup_form' + followup_id + ' .select2').val('');
                                    $('#pharm_followup_form' + followup_id + ' .select2-multiple').val('');
                                }
                                form1[0].reset();

                                $('.select2').trigger('change');
                                $('#diagnosis_id' + followup_id).trigger('change');

                            } else {
                                alert('error');
                            }
                        }
                    });//END $.ajax
                }
            }

            $(document).ready(function () {
                $('.checkedAll').prop('checked', false);//initalization
                $('.checkSingle').prop('checked', false);//initalization
                $(".checkedAll").change(function () {//صرف الدواء
                    //alert($(this).is(':enabled'));
                    if (this.checked) {
                        $(".checkSingle").each(function () {
                            if ($(this).is(':enabled')) {
                                // alert('enabled');
                                this.checked = true;

                            }

                        });
                    } else {
                        $(".checkSingle").each(function () {
                            this.checked = false;
                        });
                    }
                });

                $(".checkSingle").click(function () {
                    //  alert('checkSingle')
                    if ($(this).is(":checked")) {
                        var isAllChecked = 0;

                        $(".checkSingle").each(function () {
                            if (!this.checked)
                                isAllChecked = 1;
                        });

                        if (isAllChecked == 0) {
                            $("#checkedAll").prop("checked", true);
                        }
                    } else {
                        $("#checkedAll").prop("checked", false);
                    }
                });
            });

            function cal_drug_cost_followup(id) {
                //alert(id);
                var quantity = 0;
                var cost = 0;
                if ($('#dosage' + id).val() != '' && $('#frequency' + id).val() != '' && $('#duration' + id).val() != '') {
                    quantity = parseFloat($('#dosage' + id).val()) * parseFloat($('#frequency' + id).val()) * parseFloat($('#duration' + id).val());
                    cost = quantity * parseFloat($('#drug_price' + id).val())
                }

                $('#quantity' + id).val(quantity);
                $('#drug_cost' + id).val(cost);
            }

            /* function cal_drug_cost_followup(id) {
                 var quantity = 0;
                 var cost = 0;
                 if ($('#dosage').val() != '' && $('#frequency').val() != '' && $('#duration').val() != '') {
                     quantity = parseFloat($('#dosage').val()) * parseFloat($('#frequency').val()) * parseFloat($('#duration').val());
                     cost = quantity * parseFloat($('#drug_price').val())
                 }
                 $('#quantity').val(quantity)
                 $('#drug_cost').val(cost)
             }*/
            function change_drug_order_status() {

                var drug_array = [];
                var followup_id = $('#followup_id').val();
                var painFile_id = $('#painFile_id').val();
                var painFile_statusid = $('#painFile_statusid').val();
                blockUI('change_order_status_btn' + followup_id);
                $(".checkSingle").each(function () {
                    if (this.checked)
                        drug_array.push($(this).attr('data-id'));
                });
                swal({
                        title: 'Do you want to confirm the operation? ?',
                        type: 'warning',
                        allowOutsideClick: true,
                        showConfirmButton: true,
                        showCancelButton: true,
                        confirmButtonClass: 'btn-info',
                        cancelButtonClass: 'btn-danger',
                        closeOnConfirm: true,
                        closeOnCancel: true,
                        cancelButtonText: 'Cancel',
                        confirmButtonText: 'Yes',
                    },
                    function (isConfirm) {
                        if (isConfirm) {

                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });
                            $.ajax({
                                type: "POST",
                                url: '{{url('followup/change-drug-order-status')}}',

                                data: {
                                    followup_id: followup_id,
                                    painFile_id: painFile_id,
                                    painFile_status: painFile_statusid,
                                    drug_array: drug_array
                                },
                                error: function (xhr, status, error) {

                                },
                                beforeSend: function () {
                                },
                                complete: function () {
                                },
                                success: function (data) {

                                    if (data.success) {
                                        unblockUI('change_order_status_btn' + followup_id);
                                        // alert(data.html)
                                        $('.alert-success-treatment' + followup_id).show();
                                        $('.alert-success-treatment' + followup_id).fadeOut(2000);
                                        //alert-treatment+id
                                        $('#tbtreatmentDoctorFollow' + followup_id).html(data.doctor_html);
                                        $('#tbpharmtreatment' + followup_id).html(data.pharm_html);
                                        $(".select2, .select2-multiple").select2({
                                            width: null
                                        });
                                    } else {
                                        swal("Cancelled", 'Item :' + data.item_name + ' no active batch or quntity', "error");


                                        //   alert('Item :'+data.item_name+' no active batch or quntity');
                                        $('#tbtreatmentDoctorFollow' + followup_id).html(data.doctor_html);
                                        $('#tbpharmtreatment' + followup_id).html(data.pharm_html);
                                        $(".select2, .select2-multiple").select2({
                                            width: null
                                        });
                                    }
                                }
                            });//END $.ajax

                        }
                    });
            }

            function get_active_batch_price(id) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                if ($('#drug_id' + id).val() != '')
                    $.ajax({
                            type: "POST",
                            url: '{{url('followup/get-batch-price')}}',
                            data: {
                                drug_id: $('#drug_id' + id).val(),

                            },
                            success: function (data) {
                                if (data.success) {
                                    if (data.batch_current_quantity > 0) {
                                        $('#batch_id' + id).val('')
                                        $('#dosage' + id).val('');
                                        $('#frequency' + id).val('');
                                        $('#duration' + id).val('');
                                        $('#quantity' + id).val('');
                                        $('#drug_comments' + id).val('');
                                        $('#drug_cost' + id).val('');
                                        $('#drug_price' + id).val('');
                                        $('#drug_price' + id).val(data.batch_cost)
                                        $('#batch_id' + id).val(data.batch_id)
                                        $(".drug").prop("disabled", false);
                                    } else {
                                        $('#drug_price' + id).val('')
                                        $('#batch_id' + id).val('')
                                        $('#dosage' + id).val('');
                                        $('#frequency' + id).val('');
                                        $('#duration' + id).val('');
                                        $('#quantity' + id).val('');
                                        $('#drug_comments' + id).val('');
                                        $('#drug_cost' + id).val('');
                                        $('#drug_price' + id).val('');
                                        $(".drug").prop("disabled", true);
                                        swal({
                                                title: 'No available quantity for this Drug!',
                                                type: 'warning',
                                                allowOutsideClick: true,
                                                showConfirmButton: true,
                                                //  showCancelButton: true,
                                                confirmButtonClass: 'btn-info',
                                                //  cancelButtonClass: 'btn-danger',
                                                //  closeOnConfirm: true,
                                                //  closeOnCancel: true,
                                                confirmButtonText: 'Ok',
                                                //   cancelButtonText: 'Cancel',

                                            },
                                            function (isConfirm) {
                                                return;
                                            });
                                    }
                                } else {
                                    $('#drug_price' + id).val('')
                                    $('#batch_id' + id).val('')
                                    $('#dosage' + id).val('');
                                    $('#frequency' + id).val('');
                                    $('#duration' + id).val('');
                                    $('#quantity' + id).val('');
                                    $('#drug_comments' + id).val('');
                                    $('#drug_cost' + id).val('');
                                    $('#drug_price' + id).val('');
                                    $(".drug").prop("disabled", true);
                                    swal({
                                            title: 'No Active batch for this Drug ?',
                                            type: 'warning',
                                            allowOutsideClick: true,
                                            showConfirmButton: true,
                                            //  showCancelButton: true,
                                            confirmButtonClass: 'btn-info',
                                            //  cancelButtonClass: 'btn-danger',
                                            //  closeOnConfirm: true,
                                            //  closeOnCancel: true,
                                            confirmButtonText: 'Ok',
                                            //   cancelButtonText: 'Cancel',

                                        },
                                        function (isConfirm) {
                                            return;
                                        });
                                }
                            }
                        }
                    )
                    ;//END $.ajax
            }

            function get_active_batch_price_pharm(id) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                if ($('#pharm_drug_id' + id).val() != '')
                    $.ajax({
                        type: "POST",
                        url: '{{url('followup/get-batch-price')}}',
                        data: {
                            drug_id: $('#pharm_drug_id' + id).val(),

                        },
                        success: function (data) {
                            if (data.success) {
                                if (data.batch_current_quantity > 0) {
                                    $('#pharm_batch_id' + id).val('')
                                    $('#pharm_dosage' + id).val('');
                                    $('#pharm_frequency' + id).val('');
                                    $('#pharm_duration' + id).val('');
                                    $('#pharm_quantity' + id).val('');
                                    $('#pharm_drug_comments' + id).val('');
                                    $('#pharm_drug_cost' + id).val('');
                                    $('#pharm_drug_price' + id).val('');

                                    $(".pharmdrug").prop("disabled", false);
                                    $('#pharm_drug_price' + id).val(data.batch_cost)
                                    $('#pharm_batch_id' + id).val(data.batch_id)
                                } else {
                                    $('#pharm_batch_id' + id).val('')
                                    $('#pharm_dosage' + id).val('');
                                    $('#pharm_frequency' + id).val('');
                                    $('#pharm_duration' + id).val('');
                                    $('#pharm_quantity' + id).val('');
                                    $('#pharm_drug_comments' + id).val('');
                                    $('#pharm_drug_cost' + id).val('');
                                    $('#pharm_drug_price' + id).val('');

                                    $(".pharmdrug").prop("disabled", true);
                                    swal({
                                            title: 'No available quantity for this Drug!',
                                            type: 'warning',
                                            allowOutsideClick: true,
                                            showConfirmButton: true,
                                            //  showCancelButton: true,
                                            confirmButtonClass: 'btn-info',
                                            //  cancelButtonClass: 'btn-danger',
                                            //  closeOnConfirm: true,
                                            //  closeOnCancel: true,
                                            confirmButtonText: 'Ok',
                                            //   cancelButtonText: 'Cancel',

                                        },
                                        function (isConfirm) {
                                            return;
                                        });
                                }
                            } else {
                                $('#pharm_batch_id' + id).val('')
                                $('#pharm_dosage' + id).val('');
                                $('#pharm_frequency' + id).val('');
                                $('#pharm_duration' + id).val('');
                                $('#pharm_quantity' + id).val('');
                                $('#pharm_drug_comments' + id).val('');
                                $('#pharm_drug_cost' + id).val('');
                                $('#pharm_drug_price' + id).val('');

                                $(".pharmdrug").prop("disabled", true);
                                swal({
                                        title: 'No Active batch for this Drug ?',
                                        type: 'warning',
                                        allowOutsideClick: true,
                                        showConfirmButton: true,
                                        //  showCancelButton: true,
                                        confirmButtonClass: 'btn-info',
                                        //  cancelButtonClass: 'btn-danger',
                                        //  closeOnConfirm: true,
                                        //  closeOnCancel: true,
                                        confirmButtonText: 'Ok',
                                        //   cancelButtonText: 'Cancel',

                                    },
                                    function (isConfirm) {
                                        return;
                                    });
                            }
                        }
                    });//END $.ajax
            }

            function cal_drug_cost_followup_pharm(id) {
                var quantity = 0;
                var cost = 0;
                if ($('#pharm_dosage' + id).val() != '' && $('#pharm_frequency' + id).val() != '' && $('#pharm_duration' + id).val() != '') {
                    quantity = parseFloat($('#pharm_dosage' + id).val()) * parseFloat($('#pharm_frequency' + id).val()) * parseFloat($('#pharm_duration' + id).val());
                    cost = quantity * parseFloat($('#pharm_drug_price' + id).val())
                }
                $('#pharm_quantity' + id).val(quantity)
                $('#pharm_drug_cost' + id).val(cost)
            }

            function cal_pharm_quantity_update(id) {//عند التعديل في جدول الصيدلية
                var quantity = 0;
                var cost = 0;
                if ($('#pharm_treat_dosage' + id).val() != '' && $('#pharm_treat_frequency' + id).val() != '' && $('#pharm_treat_duration' + id).val() != '') {
                    quantity = parseFloat($('#pharm_treat_dosage' + id).val()) * parseFloat($('#pharm_treat_frequency' + id).val()) * parseFloat($('#pharm_treat_duration' + id).val());
                    cost = quantity * parseFloat($('#pharm_treat_drug_price' + id).val())
                }
                $('#pharm_treat_quantity' + id).val(quantity)
                $('#pharm_treat_drug_cost' + id).val(cost)
            }

            //***********************Qutenza
            var QutenzaFormValidation = function () {

                // basic validation
                var handleValidation1 = function () {
                    // for more info visit the official plugin documentation:
                    // http://docs.jquery.com/Plugins/Validation
                    var followup_id = $('#followup_id').val();
                    var form1 = $('#qutenza_form');
                    var error1 = $('.qutenza-danger', form1);
                    var success1 = $('.qutenza-success', form1);
                    // Unique FollowupDate

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
                                qutenza_date: {
                                    required: true,
                                    date: true
                                },
                                application_time: {
                                    required: true,
                                    digits: true
                                },
                                allodynia: {
                                    required: true,

                                },
                                erythema: {
                                    required: true,

                                },
                                pain: {
                                    required: true,

                                },
                                pruritus: {
                                    required: true,

                                }, papules: {
                                    required: true,

                                }, edema: {
                                    required: true,

                                }, swelling: {
                                    required: true,

                                }, dryness: {
                                    required: true,

                                }, nasopharyngitis: {
                                    required: true,

                                }, bronchitis: {
                                    required: true,

                                }, sinusitis: {
                                    required: true,

                                }, nausea: {
                                    required: true,

                                },
                                vomiting: {
                                    required: true,
                                },
                                skin_pruritus: {
                                    required: true,
                                },

                                /*  hypertension_systolic: {
                                      digits: true,
                                      number: true
                                  },
                                  hypertension_diastolic: {
                                      digits: true,
                                  },*/


                                /* "diagnosis_id[]": {
                                required: true
                                },
                                physical_treatment: {
                                required: true
                                }*/
                            },

                            messages:
                                { // custom messages for radio buttons and checkboxes

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

                                QutenzaFormSubmit();


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

            QutenzaFormValidation.init();

            function QutenzaFormSubmit() {

                App.blockUI();
                var followup_id = $('#followup_id').val();
                var form1 = $('#qutenza_form');
                var error = $('.qutenza-danger', form1);
                var success = $('.qutenza-success', form1);
                var action = form1.attr('action');
                var painFile_id = $('#painFile_id').val()
                var painFile_status = $('#painFile_statusid').val()
                var hdnqutenza_id = $('#hdnqutenza_id').val()
                var qutenza_date = $('#qutenza_date').val()
                var formData = new FormData(form1[0]);
                formData.append('followup_id', followup_id);
                formData.append('painFile_id', painFile_id);
                formData.append('visit_type', 2);
                formData.append('qutenza_date', qutenza_date);
                formData.append('hdnqutenza_id', hdnqutenza_id);//for edit qutenza
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
                                //clear_qutenza_form(followup_id);
                                success.show();
                                error.hide();
                                //   App.scrollTo(success, -200);
                                success.fadeOut(2000);
                                $('.tbqutenza').html(data.qutenza_html);

                            } else {
                                success.hide();
                                error.show();
                                //  App.scrollTo(error, -200);
                                error.fadeOut(2000);
                            }


                        },
                        error: function (err) {

                            console.log(err);
                        }

                    }
                )

            }

            function del_current_qutenza(id) {
                var painFile_id = $('#painFile_id').val()
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('del-qutenza')}}',

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
                        if (data.success) {

                            $('.tbqutenza').html(data.qutenza_html);
                        } else {
                            alert('error');
                        }
                    }
                });//END $.ajax
            }

            function get_qutenza(id) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('get-qutenza')}}',

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
                        if (data.success) {

                            $('#hdnqutenza_id').val(data.qutenza_data.id);
                            $('#qutenza_date').val(data.qutenza_data.visit_date);
                            $('#allodynia').val(data.qutenza_data.allodynia);
                            $('#erythema').val(data.qutenza_data.erythema);
                            $('#application_time').val(data.qutenza_data.application_time);
                            $('#pain').val(data.qutenza_data.pain);
                            $('#pruritus').val(data.qutenza_data.pruritus);
                            $('#papules').val(data.qutenza_data.papules);
                            $('#edema').val(data.qutenza_data.edema);
                            $('#swelling').val(data.qutenza_data.swelling);
                            $('#dryness').val(data.qutenza_data.dryness);
                            $('#nasopharyngitis').val(data.qutenza_data.nasopharyngitis);
                            $('#bronchitis').val(data.qutenza_data.bronchitis);
                            $('#sinusitis').val(data.qutenza_data.sinusitis);
                            $('#nausea').val(data.qutenza_data.nausea);
                            $('#vomiting').val(data.qutenza_data.vomiting);
                            $('#skin_pruritus').val(data.qutenza_data.skin_pruritus);

                            $('#hypertension').val(data.qutenza_data.hypertension);
                            if (data.qutenza_data.hypertension_systolic != 0)
                                $('#hypertension_systolic').val(data.qutenza_data.hypertension_systolic);
                            else
                                $('#hypertension_systolic').val();
                            if (data.qutenza_data.hypertension_diastolic != 0)
                                $('#hypertension_diastolic').val(data.qutenza_data.hypertension_diastolic);
                            else
                                $('#hypertension_diastolic').val();
                            // $('#after_ttt_systolic').val(data.qutenza_data.after_ttt_systolic);
                            // $('#after_ttt_diastolic').val(data.qutenza_data.after_ttt_diastolic);
                            //$('#qutenza_pain_scale').val(data.qutenza_data.pain_score);
                            $('.tbqutenz_score').html(data.qutenza_score_html);
                            $('#allodynia').trigger('change');

                            $('#pain').trigger('change');
                            $('#erythema').trigger('change');
                            $('#pruritus').trigger('change');
                            $('#papules').trigger('change');
                            $('#edema').trigger('change');
                            $('#swelling').trigger('change');
                            $('#dryness').trigger('change');
                            $('#nasopharyngitis').trigger('change');
                            $('#bronchitis').trigger('change');
                            $('#sinusitis').trigger('change');
                            $('#nausea').trigger('change');
                            $('#vomiting').trigger('change');
                            $('#skin_pruritus').trigger('change');
                            $('#hypertension').trigger('change');


                        } else {
                            alert('error');
                        }
                    }
                });//END $.ajax
            }

            function get_qutenza_old(id, followup_id) {
                alert('get_qutenza');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('get-qutenza')}}',

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
                        if (data.success) {

                            $('#hdnqutenza_id').val(data.qutenza_data.id);
                            $('#allodynia' + followup_id).val(data.qutenza_data.allodynia);
                            $('#erythema' + followup_id).val(data.qutenza_data.erythema);
                            $('#application_time' + followup_id).val(data.qutenza_data.application_time);
                            $('#pain' + followup_id).val(data.qutenza_data.pain);
                            $('#pruritus' + followup_id).val(data.qutenza_data.pruritus);
                            $('#papules' + followup_id).val(data.qutenza_data.papules);
                            $('#edema' + followup_id).val(data.qutenza_data.edema);
                            $('#swelling' + followup_id).val(data.qutenza_data.swelling);
                            $('#dryness' + followup_id).val(data.qutenza_data.dryness);
                            $('#nasopharyngitis' + followup_id).val(data.qutenza_data.nasopharyngitis);
                            $('#bronchitis' + followup_id).val(data.qutenza_data.bronchitis);
                            $('#sinusitis' + followup_id).val(data.qutenza_data.sinusitis);
                            $('#nausea' + followup_id).val(data.qutenza_data.nausea);
                            $('#vomiting' + followup_id).val(data.qutenza_data.vomiting);
                            $('#skin_pruritus' + followup_id).val(data.qutenza_data.skin_pruritus);
                            $('#hypertension_systolic' + followup_id).val(data.qutenza_data.hypertension_systolic);
                            $('#hypertension_diastolic' + followup_id).val(data.qutenza_data.hypertension_diastolic);
                            //  $('#after_ttt_systolic' + followup_id).val(data.qutenza_data.after_ttt_systolic);
                            //  $('#after_ttt_diastolic' + followup_id).val(data.qutenza_data.after_ttt_diastolic);
                            // $('#qutenza_pain_scale' + followup_id).val(data.qutenza_data.pain_score);

                            $('#allodynia' + followup_id).trigger('change');

                            $('#pain' + followup_id).trigger('change');
                            $('#erythema' + followup_id).trigger('change');
                            $('#pruritus' + followup_id).trigger('change');
                            $('#papules' + followup_id).trigger('change');
                            $('#edema' + followup_id).trigger('change');
                            $('#swelling' + followup_id).trigger('change');
                            $('#dryness' + followup_id).trigger('change');
                            $('#nasopharyngitis' + followup_id).trigger('change');
                            $('#bronchitis' + followup_id).trigger('change');
                            $('#sinusitis' + followup_id).trigger('change');
                            $('#nausea' + followup_id).trigger('change');
                            $('#vomiting' + followup_id).trigger('change');
                            $('#skin_pruritus' + followup_id).trigger('change');

                        } else {
                            alert('error');
                        }
                    }
                });//END $.ajax
            }

            function clear_qutenza_form(followup_id) {
                $('#hdnqutenza_id').val('');
                $('#allodynia' + followup_id).val('');
                $('#application_time' + followup_id).val('');
                $('#pain' + followup_id).val('');
                $('#erythema' + followup_id).val('');
                $('#pruritus' + followup_id).val('');
                $('#papules' + followup_id).val('');
                $('#edema' + followup_id).val('');
                $('#swelling' + followup_id).val('');
                $('#dryness' + followup_id).val('');
                $('#nasopharyngitis' + followup_id).val('');
                $('#bronchitis' + followup_id).val('');
                $('#sinusitis' + followup_id).val('');
                $('#nausea' + followup_id).val('');
                $('#vomiting' + followup_id).val('');
                $('#skin_pruritus' + followup_id).val('');
                $('#hypertension' + followup_id).val('');
                $('#hypertension_systolic' + followup_id).val('');
                $('#hypertension_diastolic' + followup_id).val('');
                // $('#qutenza_pain_scale' + followup_id).val('');
                $('#allodynia' + followup_id).trigger('change');

                $('#pain' + followup_id).trigger('change');
                $('#erythema' + followup_id).trigger('change');
                $('#pruritus' + followup_id).trigger('change');
                $('#papules' + followup_id).trigger('change');
                $('#edema' + followup_id).trigger('change');
                $('#swelling' + followup_id).trigger('change');
                $('#dryness' + followup_id).trigger('change');
                $('#nasopharyngitis' + followup_id).trigger('change');
                $('#bronchitis' + followup_id).trigger('change');
                $('#sinusitis' + followup_id).trigger('change');
                $('#nausea' + followup_id).trigger('change');
                $('#vomiting' + followup_id).trigger('change');
                $('#skin_pruritus' + followup_id).trigger('change');
                $('#hypertension' + followup_id).trigger('change');

            }

            //***********************Qutenza score
            var QutenzaScoreFormValidation = function () {

                // basic validation
                var handleValidation1 = function () {
                    // for more info visit the official plugin documentation:
                    // http://docs.jquery.com/Plugins/Validation
                    var followup_id = $('#followup_id').val();
                    var form1 = $('#qutenza_score_form');
                    var error1 = $('.qutenza-score-danger', form1);
                    var success1 = $('.qutenza-score-success', form1);

                    // Unique FollowupDate


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
                                qutenza_score_date: {
                                    required: true,
                                    date: true
                                },
                                week: {
                                    required: true,

                                },
                                qutenza_pain_scale: {
                                    required: true,
                                    digits: true

                                },

                            },

                            messages:
                                { // custom messages for radio buttons and checkboxes

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

                                QutenzaScoreFormSubmit();


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

            QutenzaScoreFormValidation.init();

            function QutenzaScoreFormSubmit() {

                App.blockUI();
                var followup_id = $('#followup_id').val();
                var form1 = $('#qutenza_score_form');
                var error = $('.qutenza-score-danger', form1);
                var success = $('.qutenza-score-success', form1);

                var action = form1.attr('action');
                var painFile_id = $('#painFile_id').val()
                //var painFile_status = $('#painFile_statusid').val()
                var hdnqutenza_id = $('#hdnqutenza_id').val()
                //var qutenza_date = $('#qutenza_score_date').val()
                var formData = new FormData(form1[0]);
                formData.append('followup_id', followup_id);
                formData.append('painFile_id', painFile_id);
                formData.append('visit_type', 2);
                //formData.append('qutenza_date', qutenza_date);
                formData.append('hdnqutenza_id', hdnqutenza_id);//for edit qutenza
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
                                // clear_qutenza_score_form(followup_id);
                                success.show();
                                error.hide();
                                /// App.scrollTo(success, -200);
                                success.fadeOut(2000);
                                $('.tbqutenz_score').html(data.qutenza_score_html);

                            } else {
                                success.hide();
                                error.show();
                                //  App.scrollTo(error, -200);
                                error.fadeOut(2000);
                            }


                        },
                        error: function (err) {

                            console.log(err);
                        }

                    }
                )

            }

            function del_qutenza_score_old(id) {
                var painFile_id = $('#painFile_id').val()
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('del-qutenz-score')}}',

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
                        if (data.success) {

                            $('.tbqutenz_score').html(data.qutenza_score_html);
                        } else {
                            alert('error');
                        }
                    }
                });//END $.ajax
            }

            function del_qutenza_score(id, qutenza_id) {
                var painFile_id = $('#painFile_id').val()
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('del-qutenz-score')}}',

                    data: {
                        id: id, painFile_id: painFile_id, qutenza_id: qutenza_id
                    },
                    error: function (xhr, status, error) {

                    },
                    beforeSend: function () {
                    },
                    complete: function () {
                    },
                    success: function (data) {
                        if (data.success) {

                            $('.tbqutenz_score').html(data.qutenza_score_html);
                        } else {
                            alert('error');
                        }
                    }
                });//END $.ajax
            }

            function show_hypdertension_div() {
                if ($('#hypertension').val() == 1)
                    $('.hyper_div').show();
                else
                    $('.hyper_div').hide();
            }

            function clear_qutenza_score_form() {
                $('.qutenza_pain_scale').val('');
                $('.week').val('');
                $('.week').trigger('change');
            }

            function qutenza_request(type, followup_id) {
                var painFile_id = $('#painFile_id').val();
                var need_qutenza = $('#' + type + '_need_qutenza' + followup_id).val();
                var visit_date = $('#' + type + '_follow_up_date' + followup_id).val();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                if (need_qutenza == 1)
                    $.ajax({
                        type: "POST",
                        url: '{{url('add-patient-qutenza')}}',

                        data: {
                            painFile_id: painFile_id, visit_type: 2, visit_date: visit_date, followup_id: followup_id
                        },
                        error: function (xhr, status, error) {

                        },
                        beforeSend: function () {
                        },
                        complete: function () {
                        },
                        success: function (data) {
                            if (data.success) {

                                $('.tbqutenza').html(data.qutenza_html);
                            } else {
                                alert('error');
                            }
                        }
                    });//END $.ajax
            }



            function get_stop_project_modal_data(id) {

                $('#pain_project_id').val(id);
                $('#project_id').val(project_id);
                $('#case_study').val('').trigger('change');
                $('#conclusion').val('').trigger('change');
                $('#case_study_note').html('');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('project/get-stop-project-data')}}',

                    data: {
                        pain_project_id: id,
                    },
                    error: function (xhr, status, error) {

                    },
                    beforeSend: function () {
                    },
                    complete: function () {
                    },
                    success: function (data) {
                        if (data.success) {

                            $('#case_study').val(data.project_data.case_study).trigger('change');
                            $('#conclusion').val(data.project_data.conclusion).trigger('change');
                            $('#case_study_note').val(data.project_data.case_study_note);
                        } else {
                            alert('error');
                        }
                    }
                });//END $.ajax

            }

            function stop_followup_project() {
                var form1 = $('#stop_project_form');
                var error = $('.stop-project-danger', form1);
                var success = $('.stop-project-success', form1);
                //    $('#case_study').val('').trigger('change');
                //    $('#conclusion').val('').trigger('change');
                //     $('#case_study_note').html('');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('stop-patient-project')}}',

                    data: {
                        pain_project_id: $('#pain_project_id').val(),
                        painFile_id: $('#painFile_id').val(),
                        case_study: $('#case_study').val(),
                        conclusion: $('#conclusion').val(),
                        case_study_note: $('#case_study_note').val(),
                    },
                    error: function (xhr, status, error) {

                    },
                    beforeSend: function () {
                    },
                    complete: function () {
                    },
                    success: function (data) {
                        if (data.success) {

                            success.show();
                            error.hide();
                            // App.scrollTo(success, -200);
                            $('.tbpatient_project').html(data.patient_project_html);
                            success.fadeOut(2000);
                        } else {
                            success.hide();
                            error.show();
                            // App.scrollTo(error, -200);
                            error.fadeOut(2000);
                        }
                    }
                });//END $.ajax

            }

            function edit_followup_project(id, user_type, project_id) {
                var painFile_id = $('#painFile_id').val()
                var followup_id = $('#followup_id').val()
                $('#pain_project_id').val(id);
                $('#project_id').val(project_id);

                if (user_type == 9) {
                    $('#docProjectDv').show();
                    $('#pharmProjectDv').hide();

                } else if (user_type == 11) {
                    $('#docProjectDv').hide();
                    $('#pharmProjectDv').show();
                } else if (user_type == 8) {
                    $('#docProjectDv').show();
                    $('#pharmProjectDv').show();

                } else {
                    $('#docProjectDv').hide();
                    $('#pharmProjectDv').hide();
                }

                $('#doctor_project_action').val('').trigger('change');
                $('#doctor_project_note').html('');
                $('#pharm_project_action').val('').trigger('change');
                $('#pharm_project_note').html('');

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('show-follow-project')}}',

                    data: {
                        table_id: id, painFile_id: painFile_id
                    },
                    error: function (xhr, status, error) {

                    },
                    beforeSend: function () {
                    },
                    complete: function () {
                    },
                    success: function (data) {
                        if (data.success) {

                            $('#tbdoc_project_followup').html(data.doctor_html);
                            $('#tbpharm_project_followup').html(data.pharm_html);
                        }
                    }
                });//END $.ajax
            }

            function add_pharm_followup_project() {

                var form1 = $('#pharm_project_form');
                var error = $('.pharm-project-danger', form1);
                var success = $('.pharm-project-success', form1);
                if ($('#pharm_project_action').val() == '') {
                    $('.pharm-project-danger').show();
                    $('.pharm-project-danger').fadeOut(2000);
                    $('#pharm_project_action').parent().addClass('has-error');
                    return;
                } else
                    $('#pharm_project_action').parent().removeClass('has-error');


                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "POST",
                    url: '{{url('project/add-pharm-followup')}}',

                    data: {
                        pain_file_id: $('#painFile_id').val(),
                        pain_project_id: $('#pain_project_id').val(),
                        project_id: $('#project_id').val(),
                        followup_id: $('#followup_id').val(),
                        visit_date: $('#pharm_project_followup_date').val(),
                        pharm_action: $('#pharm_project_action').val(),
                        pharm_note: $('#pharm_project_note').val(),

                    },
                    error: function (xhr, status, error) {

                    },
                    beforeSend: function () {
                    },
                    complete: function () {
                    },
                    success: function (data) {
                        if (data.success) {
                            success.show();
                            error.hide();
                            // App.scrollTo(success, -200);
                            success.fadeOut(2000);

                            $('#tbpharm_project_followup').html(data.pharm_html);

                        } else {
                            success.hide();
                            error.show();
                            // App.scrollTo(error, -200);
                            error.fadeOut(2000);
                        }
                    }
                });//END $.ajax


            }

            function add_doc_followup_project() {

                var form1 = $('#doctor_followup_form');
                var error = $('.doc-project-danger', form1);
                var success = $('.doc-project-success', form1);
                if ($('#doctor_project_action').val() == '') {
                    $('.doc-project-danger').show();
                    $('.doc-project-danger').fadeOut(2000);
                    $('#doctor_project_action').parent().addClass('has-error');
                    return;
                } else
                    $('#doctor_project_action').parent().removeClass('has-error');


                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "POST",
                    url: '{{url('project/add-doc-followup')}}',

                    data: {
                        pain_file_id: $('#painFile_id').val(),
                        pain_project_id: $('#pain_project_id').val(),
                        project_id: $('#project_id').val(),
                        followup_id: $('#followup_id').val(),
                        visit_date: $('#doc_project_followup_date').val(),
                        doctor_action: $('#doctor_project_action').val(),
                        doctor_note: $('#doctor_project_note').val(),

                    },
                    error: function (xhr, status, error) {

                    },
                    beforeSend: function () {
                    },
                    complete: function () {
                    },
                    success: function (data) {
                        if (data.success) {
                            success.show();
                            error.hide();
                            // App.scrollTo(success, -200);
                            success.fadeOut(2000);

                            $('#tbdoc_project_followup').html(data.doctor_html);

                        } else {
                            success.hide();
                            error.show();
                            // App.scrollTo(error, -200);
                            error.fadeOut(2000);
                        }
                    }
                });//END $.ajax


            }

            function del_followup_project(id, user_type) {
                var form1 = $('#doctor_followup_form');
                var error = $('.doc-project-danger', form1);
                var success = $('.doc-project-success', form1);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('del-project-followup')}}',

                    data: {
                        id: id,
                        user_type: user_type
                    },
                    error: function (xhr, status, error) {

                    },
                    beforeSend: function () {
                    },
                    complete: function () {
                    },
                    success: function (data) {
                        if (data.success) {
                            if (user_type == 9) {
                                success.show();
                                error.hide();
                                // App.scrollTo(success, -200);
                                success.fadeOut(2000);
                                $('#tbdoc_project_followup').html(data.doctor_html);
                            } else if (user_type == 11)
                                $('#tbpharm_project_followup').html(data.pharm_html);

                        } else {
                            success.hide();
                            error.show();
                            error.fadeOut(2000);
                        }
                    }
                });//END $.ajax
            }


            function save_chk(id, followup_id) {

                if ($('#checkbox_' + followup_id + '_' + 527).prop('checked')) // // Returns true if checked, false if unchecked.)

                    $('#dv_streching_exercise_neck_shoulder_' + followup_id).removeClass('hide');
                else
                    $('#dv_streching_exercise_neck_shoulder_' + followup_id).addClass('hide');


                if ($('#checkbox_' + followup_id + '_' + 539).prop('checked')) // // Returns true if checked, false if unchecked.)

                    $('#dv_streching_exercise_lower_back_' + followup_id).removeClass('hide');
                else
                    $('#dv_streching_exercise_lower_back_' + followup_id).addClass('hide');

                if ($('#checkbox_' + followup_id + '_' + id).prop('checked')) {// // Returns true if checked, false if unchecked.)
                    var checked = 1;

                } else {
                    var checked = 0;

                }
                $.ajax({
                    type: "POST",
                    url: '{{url('followup/save-physiotherapy-chk')}}',
                    data: {
                        painFile_id: $('#painFile_id').val(),
                        followup_id: followup_id,
                        physiotherapy_id: id,
                        checked: checked
                    },
                    success: function (data) {
                        if (id == 527 && ($('#checkbox_' + followup_id + '_' + 527).prop('checked') == false)) {//to hide and empty child checkbox of 527

                            $('.child_chk_527_' + followup_id).prop('checked', false);
                            $('.child_select_527_' + followup_id).val('').trigger('change');
                        }
                        if (id == 539 && ($('#checkbox_' + followup_id + '_' + 539).prop('checked') == false)) {//to hide and empty child checkbox of 527

                            $('.child_chk_539_' + followup_id).prop('checked', false);
                            $('.child_select_539_' + followup_id).val('').trigger('change');
                        }
                        //if (data.success) {
                        //   set_message("تمت العملية بنجاح", true);
                        //  } else
                        //   set_message("لم تتم العملية بنجاح", false);
                    }
                });
            }

            function update_ck_compliance(id, followup_id) {

                var compliance = $('#compliance_' + followup_id + '_' + id).val();
                $.ajax({
                    type: "POST",
                    url: '{{url('followup/physiotherapy-chk-compliance')}}',
                    data: {
                        painFile_id: $('#painFile_id').val(),
                        followup_id: followup_id,
                        physiotherapy_id: id,
                        compliance: compliance
                    },
                    success: function (data) {

                        /*  if (data.success) {
                              show_alert_message('Operation accomplished successfully', "success");
                          } else
                              show_alert_message('The operation was not completed successfully', "danger");*/
                    }
                });
            }

            function show_dvDiagnosisSpecify(id) {
                if ($("#change_diagnosis" + id).val() == 1)

                    $("#dvDiagnosisSpecify" + id).removeClass('hide');
                else {
                    $("#diagnostic_specify" + id).val('');
                    $("#dvDiagnosisSpecify" + id).addClass('hide');

                }
            }
        </script>

    @endpush
@stop
