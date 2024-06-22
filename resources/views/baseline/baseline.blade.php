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
                <input type="hidden" id="painFile_id" name="painFile_id" value="{{$painFile_id}}">
                <input type="hidden" id="painFile_statusid" name="painFile_statusid" value="{{$painFile_statusId}}">
                <input type="hidden" id="hdnqutenza_id" name="hdnqutenza_id" value="{{''}}">
                <!-- BEGIN VALIDATION STATES-->
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-user font-green"></i>
                            <span class="caption-subject font-green sbold uppercase">Baseline Consultation</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="tabbable-custom nav-justified">
                            <ul class="nav nav-tabs nav-justified">
                                <li class="active">
                                    <a href="#tab_1_1_1" data-toggle="tab"> Nurse Consultation </a>
                                </li>
                                <li>
                                    <a href="#tab_1_1_2" data-toggle="tab"> Doctor Consultation </a>
                                </li>
                                <li>
                                    <a href="#tab_1_1_3" data-toggle="tab"> Pharmacist Consultation </a>
                                </li>
                                <li>
                                    <a href="#tab_1_1_4" data-toggle="tab"> Psychologist Consultation </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                @php
                                    // $baselineDoctor_style='style="pointer-events: none;"';
                                     $baselineDoctor_style='';
                                @endphp
                                @if(isset($one_baseline_doctor->created_by) &&  $one_baseline_doctor->created_by==auth()->user()->id)
                                    @php

                                        $baselineDoctor_style='';
                                    @endphp
                                @elseif(isset($one_baseline_doctor->created_by) &&  $one_baseline_doctor->created_by!=auth()->user()->id)
                                    @php
                                        $baselineDoctor_style='style="pointer-events: none;"';
                                    @endphp
                                @elseif(!isset($one_baseline_doctor->created_by) && auth()->user()->user_type_id==9)
                                    @php

                                        $baselineDoctor_style='';
                                    @endphp

                                @endif

                                <div class="tab-pane active" id="tab_1_1_1">
                                    <!-- BEGIN FORM-->
                                    {{Form::open(['url'=>url('baselineNurse'),'class'=>'form-horizontal','method'=>"post","id"=>'baseline_nurse_form'])}}
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
                                            <div class="col-md-4 input-group">
                                                <input class="form-control date-picker" type="text"
                                                       value="{{isset($one_baseline_nurse->visit_date)?$one_baseline_nurse->visit_date:''}}"
                                                       name="visit_date_nurse"
                                                       data-date-format="yyyy-mm-dd"/>
                                                <span class="help-block"> Select date </span>
                                            </div>
                                        </div>
                                        <h3 class="form-section font-green"> Patient Demographics </h3>
                                        <hr/>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Number of Children
                                            </label>
                                            <div class="col-md-6">
                                                <input type="text" name="no_of_child" class="form-control"
                                                       value="{{isset($one_patient_detail->no_of_child)?$one_patient_detail->no_of_child:''}}"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Education

                                            </label>
                                            <div class="col-md-6">
                                                <select class="form-control" name="education">
                                                    <option value="">Select...</option>
                                                    <?php
                                                    $selected = '';
                                                    foreach ($education_list as $raw) {
                                                        $selected = '';
                                                        if (isset($one_patient_detail->education) && $raw->id == $one_patient_detail->education)
                                                            $selected = 'selected="selected"';
                                                        echo '<option value="' . $raw->id . '" ' . $selected . '>' . $raw->lookup_details . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Current Work

                                            </label>
                                            <div class="col-md-6">
                                                <select class="form-control" name="current_work" id="current_work"
                                                        onchange="show_other_current_work();">
                                                    <option value="">Select...</option>
                                                    <?php
                                                    $selected = '';
                                                    foreach ($current_work_list as $raw) {
                                                        $selected = '';
                                                        if (isset($one_patient_detail->current_work) && $raw->id == $one_patient_detail->current_work)
                                                            $selected = 'selected="selected"';
                                                        echo '<option value="' . $raw->id . '" ' . $selected . '>' . $raw->lookup_details . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div id="dvOtherCurrentWork" class="form-group">
                                            <label class="control-label col-md-3">Others

                                            </label>
                                            <div class="col-md-6">
                                                <input type="text" name="other_current_work" class="form-control"
                                                       value="{{isset($one_patient_detail->other_current_work)?$one_patient_detail->other_current_work:''}}"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Weekly Hours
                                            </label>
                                            <div class="col-md-6">
                                                <input type="text" name="weekly_hours"
                                                       class="form-control"
                                                       value="{{isset($one_patient_detail->weekly_hours)?$one_patient_detail->weekly_hours:''}}"/>
                                            </div>
                                        </div>
                                    <!--                                        <div class="form-group">
                                            <label class="control-label col-md-3">Provider For The Family

                                            </label>
                                            <div class="col-md-6">
                                                <select class="form-control" name="isProvider">
                                                    <option value="">Select...</option>
                                                    <option value="1" @php if(isset($one_patient_detail->isProvider)&& $one_patient_detail->isProvider==1) echo 'selected="selected"'; @endphp>
                                                        Yes
                                                    </option>
                                                    <option value="0" @php if(isset($one_patient_detail->isProvider)&& $one_patient_detail->isProvider==0) echo 'selected="selected"'; @endphp>
                                                        No
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Only Provider

                                            </label>
                                            <div class="col-md-6">
                                                <select class="form-control" name="isOnlyProvider">
                                                    <option value="">Select...</option>
                                                    <option value="1" @php if(isset($one_patient_detail->isOnlyProvider)&& $one_patient_detail->isOnlyProvider==1) echo 'selected="selected"'; @endphp>
                                                        Yes
                                                    </option>
                                                    <option value="0" @php if(isset($one_patient_detail->isOnlyProvider)&& $one_patient_detail->isOnlyProvider==0) echo 'selected="selected"'; @endphp>
                                                        No
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Number of people you are provider
                                                for

                                            </label>
                                            <div class="col-md-6">
                                                <input type="text" name="num_of_family" class="form-control"
                                                       value="{{isset($one_patient_detail->num_of_family)?$one_patient_detail->num_of_family:''}}"/>
                                            </div>
                                        </div>-->
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Patient’s monthly income (NIS)

                                            </label>
                                            <div class="col-md-6">
                                                <input type="text" name="monthly_income" class="form-control"
                                                       value="{{isset($one_patient_detail->monthly_income)?$one_patient_detail->monthly_income:''}}"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Smoking (cigarettes or shisha)?

                                            </label>
                                            <div class="col-md-6">
                                                <select class="form-control" name="isSmoke">
                                                    <option value="">Select...</option>

                                                    <option value="0" @php if(isset($one_patient_detail->isSmoke)&& $one_patient_detail->isSmoke==0) echo 'selected="selected"'; @endphp>
                                                        No
                                                    </option>
                                                    <option value="1" @php if(isset($one_patient_detail->isSmoke)&& $one_patient_detail->isSmoke==1) echo 'selected="selected"'; @endphp>
                                                        Every day
                                                    </option>
                                                    <option value="2" @php if(isset($one_patient_detail->isSmoke)&& $one_patient_detail->isSmoke==2) echo 'selected="selected"'; @endphp>
                                                        Sometimes
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <h3 class="form-section font-green"> Pain Characteristics </h3>
                                        <hr/>
                                        <div class="alert alert-info">
                                            <strong>Info!</strong> Total score = sum of
                                            the
                                            activity scores/number of activities. <br/>
                                            Minimum detectable change for average score
                                            = 2
                                            points. <br/>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Pain Duration

                                            </label>
                                            <div class="col-md-6">
                                                <select class="form-control select2" name="pain_duration">
                                                    <option value="">Select...</option>
                                                    <?php
                                                    $selected = '';
                                                    foreach ($pain_duration_list as $raw) {
                                                        $selected = '';
                                                        if (isset($one_baseline_nurse->pain_duration) && $raw->id == $one_baseline_nurse->pain_duration)
                                                            $selected = 'selected="selected"';
                                                        echo '<option value="' . $raw->id . '" ' . $selected . '>' . $raw->lookup_details . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Temporal Aspects

                                            </label>
                                            <div class="col-md-6">
                                                <select class="form-control select2" name="temporal_aspects">
                                                    <option value="">Select...</option>
                                                    <?php
                                                    $selected = '';
                                                    foreach ($temporal_aspect_list as $raw) {
                                                        $selected = '';
                                                        if (isset($one_baseline_nurse->temporal_aspects) && $raw->id == $one_baseline_nurse->temporal_aspects)
                                                            $selected = 'selected="selected"';
                                                        echo '<option value="' . $raw->id . '" ' . $selected . '>' . $raw->lookup_details . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">During the last week, how has your
                                                usual
                                                pain intensity been on a scale from 0 to 10, with zero meaning “no pain”
                                                and 10 meaning “the worst pain imaginable”?

                                            </label>
                                            <div class="col-md-6">
                                                <input type="number" max="10" min="0" name="pain_scale"
                                                       class="form-control"
                                                       value="{{isset($one_baseline_nurse->pain_scale)?$one_baseline_nurse->pain_scale:''}}"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">During the last week, how has your
                                                usual
                                                pain bothersomeness been on a scale from 0 to 10, with zero meaning “no
                                                pain” and 10 meaning “the worst pain imaginable”?

                                            </label>
                                            <div class="col-md-6">
                                                <input type="number" max="10" min="0" name="pain_bothersomeness"
                                                       class="form-control"
                                                       value="{{isset($one_baseline_nurse->pain_bothersomeness)?$one_baseline_nurse->pain_bothersomeness:''}}"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">What is your usual pain intensity
                                                during rest on a scale from 0 to 10, with zero meaning “no pain” and 10
                                                meaning “the worst pain imaginable”?

                                            </label>
                                            <div class="col-md-6">
                                                <input type="number" max="10" min="0" name="pain_intensity_during_rest"
                                                       class="form-control"
                                                       value="{{isset($one_baseline_nurse->pain_intensity_during_rest)?$one_baseline_nurse->pain_intensity_during_rest:''}}"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">What is your usual pain intensity
                                                during activity on a scale from 0 to 10, with zero meaning “no pain” and
                                                10 meaning “the worst pain imaginable”?

                                            </label>
                                            <div class="col-md-6">
                                                <input type="number" max="10" min="0"
                                                       name="pain_intensity_during_activity"
                                                       class="form-control"
                                                       value="{{isset($one_baseline_nurse->pain_intensity_during_activity)?$one_baseline_nurse->pain_intensity_during_activity:''}}"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Pain Distribution

                                            </label>
                                            <div class="col-md-6">
                                                <select id="location_id" name="location_id[]"
                                                        class="form-control select2-multiple"
                                                        multiple>
                                                    <?php
                                                    $selected = '';
                                                    // print_r($one_pain_location );exit;
                                                    foreach ($pain_location_list as $raw) {
                                                        $selected = '';
                                                        foreach ($one_baseline_pain_dist as $raw2) {
                                                            if ($raw->id == $raw2->location_id)
                                                                $selected = 'selected="selected"';
                                                        }
                                                        echo '<option value="' . $raw->id . '" ' . $selected . '>' . $raw->name . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <h3 class="form-section font-green"> Psychometrics </h3>
                                        <hr/>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">In general, how would you rate
                                                your health
                                                today?

                                            </label>
                                            <div class="col-md-6">
                                                <select id="health_rate" name="health_rate"
                                                        class="form-control select2">
                                                    <option value="">Select...</option>
                                                    <?php
                                                    $selected = '';
                                                    foreach ($health_rate_list as $raw) {
                                                        $selected = '';
                                                        if (isset($one_baseline_nurse->health_rate) && $raw->id == $one_baseline_nurse->health_rate)
                                                            $selected = 'selected="selected"';
                                                        echo '<option value="' . $raw->id . '" ' . $selected . '>' . $raw->lookup_details . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    <!--                                        <h4 class="form-section font-green"> 1) PHQ-4 </h4>
                                        <p><strong>Over the last two weeks, how often have you been bothered by the
                                                following problems?</strong></p>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Feeling nervous, anxious or on
                                                edge
                                            </label>
                                            <div class="col-md-6">
                                                <select id="phq_nervous" name="phq_nervous"
                                                        class="form-control select2" onchange="calc_PHQ_total_Score();">
                                                    <option value="">Select...</option>
                                                    <?php
                                    /*$selected = '';
                                    foreach ($phq_nervous_list as $raw) {
                                        $selected = '';
                                        if (isset($one_baseline_nurse->phq_nervous) && $raw->id == $one_baseline_nurse->phq_nervous)
                                            $selected = 'selected="selected"';
                                        echo '<option value="' . $raw->id . '" ' . $selected . '>' . $raw->option . '</option>';
                                    }*/
                                    ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Not being able to stop or control
                                            worrying
                                        </label>
                                        <div class="col-md-6">
                                            <select id="phq_worry" name="phq_worry" class="form-control select2"
                                                    onchange="calc_PHQ_total_Score();">
                                                <option value="">Select...</option>
<?php
                                    /* $selected = '';
                                     foreach ($phq_nervous_list as $raw) {
                                         $selected = '';
                                         if (isset($one_baseline_nurse->phq_worry) && $raw->id == $one_baseline_nurse->phq_worry)
                                             $selected = 'selected="selected"';
                                         echo '<option value="' . $raw->id . '" ' . $selected . '>' . $raw->option . '</option>';
                                     }*/
                                    ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Little interest or pleasure in
                                            doing
                                            things
                                        </label>
                                        <div class="col-md-6">
                                            <select id="phq_little_interest" name="phq_little_interest"
                                                    class="form-control select2" onchange="calc_PHQ_total_Score();">
                                                <option value="">Select...</option>
<?php
                                    $selected = '';
                                    foreach ($phq_nervous_list as $raw) {
                                        $selected = '';
                                        if (isset($one_baseline_nurse->phq_little_interest) && $raw->id == $one_baseline_nurse->phq_little_interest)
                                            $selected = 'selected="selected"';
                                        echo '<option value="' . $raw->id . '" ' . $selected . '>' . $raw->option . '</option>';
                                    }
                                    ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Feeling down, depressed, or
                                            hopeless
                                        </label>
                                        <div class="col-md-6">
                                            <select id="phq_feelingdown" name="phq_feelingdown"
                                                    class="form-control select2" onchange="calc_PHQ_total_Score();">
                                                <option value="">Select...</option>
<?php
                                    $selected = '';
                                    foreach ($phq_nervous_list as $raw) {
                                        $selected = '';
                                        if (isset($one_baseline_nurse->phq_feelingdown) && $raw->id == $one_baseline_nurse->phq_feelingdown)
                                            $selected = 'selected="selected"';
                                        echo '<option value="' . $raw->id . '" ' . $selected . '>' . $raw->option . '</option>';
                                    }
                                    ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Total Score
                                        </label>
                                        <div class="col-md-6">
                                            <input type="text" name="phq_total_score" class="form-control"
                                                   id="phq_total_score" value="@php
                                        echo ((isset($one_baseline_nurse->phq_nervous)?$one_baseline_nurse->phq_nervous:0)+
                                         (isset($one_baseline_nurse->phq_worry)?$one_baseline_nurse->phq_worry:0)+
                                         (isset($one_baseline_nurse->phq_little_interest)?$one_baseline_nurse->phq_little_interest:0)+
                                         (isset($one_baseline_nurse->phq_feelingdown)?$one_baseline_nurse->phq_feelingdown:0));
                                    @endphp" disabled/>

                                            </div>
                                        </div>
                                        <div class="alert alert-info">
                                            <strong>Info!</strong> PHQ-4 total score ranges from 0 to 12, with
                                            categories of
                                            psychological distress being: <br/>
                                            None 0-2; Mild 3-5; Moderate 6-8; Severe 9-12
                                        </div>-->
                                        <h4 class="form-section font-green"> 2) PCS-3 </h4>
                                        <div class="alert alert-info">
                                            <strong>Info!</strong> PCS-3 total score ranges from 0 to 12, with a score >
                                            6 points indicating high level of pain catastrophizing.<br/>

                                        </div>
                                        <p><strong> When I'm in pain : </strong></p>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">I keep thinking about how much it
                                                hurts<br/>
                                                <span class="font-red"> أظل افكر دائمأ بشدة الألم</span>
                                            </label>
                                            <div class="col-md-6">
                                                <select id="pcs_thinking_hurts" name="pcs_thinking_hurts"
                                                        class="form-control select2" onchange="calc_PCS_total_Score()">

                                                    <option value="">Select...</option>
                                                    <?php
                                                    $selected = '';
                                                    foreach ($pcs_list as $raw) {
                                                        $selected = '';
                                                        if (isset($one_baseline_nurse->pcs_thinking_hurts) && $raw->id == $one_baseline_nurse->pcs_thinking_hurts)
                                                            $selected = 'selected="selected"';
                                                        echo '<option value="' . $raw->id . '" ' . $selected . '>' . $raw->option . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">It's awful and I feel that it
                                                overwhelms me<br/>
                                                <span class="font-red">الألم مروع,وأشعر أنه يثقل كاهلي ويتعبني</span>
                                            </label>
                                            <div class="col-md-6">
                                                <select id="pcs_overwhelms_pain" name="pcs_overwhelms_pain"
                                                        class="form-control select2" onchange="calc_PCS_total_Score()">
                                                    <option value="">Select...</option>
                                                    <?php
                                                    $selected = '';
                                                    foreach ($pcs_list as $raw) {
                                                        $selected = '';
                                                        if (isset($one_baseline_nurse->pcs_overwhelms_pain) && $raw->id == $one_baseline_nurse->pcs_overwhelms_pain)
                                                            $selected = 'selected="selected"';
                                                        echo '<option value="' . $raw->id . '" ' . $selected . '>' . $raw->option . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">I become afraid that the pain will get
                                                worse<br/>
                                                <span class="font-red"> أشعر بالخوف من أن الألم سيصبح أسوء</span>
                                            </label>
                                            <div class="col-md-6">
                                                <select id="pcs_afraid_pain" name="pcs_afraid_pain"
                                                        class="form-control select2" onchange="calc_PCS_total_Score()">
                                                    <option value="">Select...</option>
                                                    <?php
                                                    $selected = '';
                                                    foreach ($pcs_list as $raw) {
                                                        $selected = '';
                                                        if (isset($one_baseline_nurse->pcs_afraid_pain) && $raw->id == $one_baseline_nurse->pcs_afraid_pain)
                                                            $selected = 'selected="selected"';
                                                        echo '<option value="' . $raw->id . '" ' . $selected . '>' . $raw->option . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Total Score
                                            </label>
                                            <div class="col-md-6">
                                                <input type="text" name="pcs_score" class="form-control"
                                                       id="pcs_score" value="@php
                                                    echo ((isset($one_baseline_nurse->pcs_thinking_hurts)?$one_baseline_nurse->pcs_thinking_hurts:0)+
                                                     (isset($one_baseline_nurse->pcs_overwhelms_pain)?$one_baseline_nurse->pcs_overwhelms_pain:0)+
                                                     (isset($one_baseline_nurse->pcs_afraid_pain)?$one_baseline_nurse->pcs_afraid_pain:0));
                                                @endphp" disabled/></div>
                                        </div>


                                    <!--                                        <h4 class="form-section font-green"> 3) PCL-5 </h4>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">What is the patients total score
                                                (0-80)?
                                            </label>
                                            <div class="col-md-6">
                                                <input type="text" name="pcl5_score" class="form-control"
                                                       id="pcl5_score" min="0" max="80"
                                                       value="{{isset($one_baseline_nurse->pcl5_score)?$one_baseline_nurse->pcl5_score:''}}"
                                                       readonly/>

                                            </div>
                                        @if($painFile_statusId==17 && auth()->user()->id!=100)&lt;!&ndash; 17=Active file,100=guest user &ndash;&gt;
                                            <div class="col-md-2">
                                                <a data-toggle="modal" href="#pcl_eval_modal"
                                                   class="btn fa fa-plus green" onclick="get_patient_eval_data();">
                                                </a>
                                            </div>
                                            @endif
                                            </div>
                                            <div class="alert alert-info">
                                                <strong>Info!</strong> PCL-5 total score ranges from 0 to 80, with
                                                categories:
                                                <br/>
                                                > 33 most likely PTSD; ≥ 50 most certainly PTSD
                                            </div>-->
                                    <!--                                        <h3 class="form-section font-green"> Treatment Goals </h3>
                                        <hr/>
                                        <div class="alert alert-warning">
                                            Please write down up to <strong> five IMPORTANT activities </strong>you
                                            are unable to perform or are having difficulty with as a result of your
                                            pain injury i.e. putting socks on. After you have identified these
                                            activities, rate the current level of difficulty associated with each
                                            activity on a scale
                                            from 0 to 10. "0" represents “unable to perform”, and "10" represents
                                            “able to perform at prior level”.
                                        </div>
                                        <div class="alert alert-success alert-nurse-success-goal display-hide">
                                            <button class="close" data-close="alert"></button>
                                            Your form validation is successful!
                                        </div>
                                        <div class="alert alert-danger alert-nurse-danger-goal display-hide">
                                            <button class="close" data-close="alert"></button>
                                            You have some form errors. Please check below.
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-scrollable table-scrollable-borderless">
                                                    <table class="table table-striped table-hover  table-advance">
                                                        <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Goal</th>
                                                            <th>Goal Score</th>
                                                            <th>Current Score</th>
                                                        </tr>
                                                        <tr class="uppercase">
                                                            <td> #</td>
                                                            <td>
                                                                <div class="form-group">
                                                                    <input type="text" name="baseline_nurse_goal"
                                                                           id="baseline_nurse_goal"
                                                                           class="form-control"/>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <select name="baseline_nurse_goal_score"
                                                                        id="baseline_nurse_goal_score"
                                                                        class="form-control select2">
                                                                    <option value="0">0</option>
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
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select name="baseline_nurse_current_goal_score"
                                                                        id="baseline_nurse_current_goal_score"
                                                                        class="form-control select2">
                                                                    <option value="0">0</option>
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
                                                                </select>
                                                            </td>
                                                            <td>
                                                            @if($painFile_statusId==17 && auth()->user()->id!=100)&lt;!&ndash; 17=Active file,100=guest user &ndash;&gt;
                                                                <button type="button"
                                                                        class="btn btn-success btn-icon-only"
                                                                        onclick="save_nurse_treatment_goals();"><i
                                                                            class="fa fa-plus fa-fw"></i>
                                                                </button>
                                                                @endif
                                            </td>
                                        </tr>
                                        </thead>
                                        <tbody id="tbtreatment_goals">
@php
                                        echo $treatment_goals_data;
                                    @endphp
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>-->
                                        <!-- <div class="alert alert-info">
                                             <strong>Info!</strong> Total score = sum of the activity scores/number
                                             of
                                             activities. <br/>
                                             Minimum detectable change for average score = 2 points. <br/>
                                         </div>-->
                                        <h3 class="form-section font-green"> Scanning </h3>
                                        <hr/>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Scanned laboratory results

                                            </label>
                                            <div class="col-md-6">
                                                <select class="form-control" name="lab_scan">
                                                    <option value="">Select...</option>
                                                    <option value="0" @php if(isset($one_baseline_nurse->lab_scan)&& $one_baseline_nurse->lab_scan==0) echo 'selected="selected"'; @endphp>
                                                        No
                                                    </option>
                                                    <option value="1" @php if(isset($one_baseline_nurse->lab_scan)&& $one_baseline_nurse->lab_scan==1) echo 'selected="selected"'; @endphp>
                                                        Yes
                                                    </option>
                                                    <option value="2" @php if(isset($one_baseline_nurse->lab_scan)&& $one_baseline_nurse->lab_scan==2) echo 'selected="selected"'; @endphp>
                                                        Missing report(s)
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Scanned imaging results

                                            </label>
                                            <div class="col-md-6">
                                                <select class="form-control" name="image_scan">
                                                    <option value="">Select...</option>
                                                    <option value="0" @php if(isset($one_baseline_nurse->image_scan)&& $one_baseline_nurse->image_scan==0) echo 'selected="selected"'; @endphp>
                                                        No
                                                    </option>
                                                    <option value="1" @php if(isset($one_baseline_nurse->image_scan)&& $one_baseline_nurse->image_scan==1) echo 'selected="selected"'; @endphp>
                                                        Yes
                                                    </option>
                                                    <option value="2" @php if(isset($one_baseline_nurse->image_scan)&& $one_baseline_nurse->image_scan==2) echo 'selected="selected"'; @endphp>
                                                        Missing report(s)
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <h3 class="form-section font-green"> Physical treatment </h3>
                                        <hr/>

                                        <h5 class="form-section font-green "> Neck and shoulder </h5>

                                        <?php echo $chk_neck_and_shoulder; ?>
                                        <h5 class="form-section font-green "> Lower Back </h5>

                                        <?php echo $chk_lower_back; ?>
                                        <h3 class="form-section font-green"> Qutenza Treatment </h3>
                                        <hr/>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Need Qutenza Treatment

                                            </label>
                                            <div class="col-md-6">
                                                <select class="form-control need_qutenza"
                                                        name="need_qutenza_nurse" id="need_qutenza_nurse">
                                                    <option value="">Select...</option>
                                                    <option value="0">No</option>
                                                    <option value="1">Yes</option>
                                                </select>
                                            </div>
                                        @if($painFile_statusId==17 && auth()->user()->id!=100)<!-- 17=Active file,100=guest user -->
                                            <div class="col-md-2">
                                                <button type="button" class="btn fa fa-plus green"
                                                        onclick="qutenza_request('nurse');">
                                                </button>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-scrollable table-scrollable-borderless">
                                                    <table class="table table-striped table-hover  table-advance">
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

                                        <h3 class="form-section font-green"> Nurse Messages & Notes </h3>
                                        <hr/>
                                        <div class="alert alert-success alert-message-nurse display-hide">
                                            <button class="close" data-close="alert"></button>
                                            Your form validation is successful!
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Messages
                                            </label>
                                            <div class="col-md-7">
                                                <input type="text" id="nurse_message" name="nurse_message"
                                                       class="form-control"
                                                       value="{{''}}"/>

                                            </div>
                                        @if($painFile_statusId==17 && auth()->user()->id!=100)<!-- 17=Active file,100=guest user -->
                                            <div class="col-md-2">
                                                <button type="button" class="btn fa fa-plus green"
                                                        onclick="set_message(2,1);">
                                                </button>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                @if($painFile_statusId==17 && auth()->user()->id!=100)<!-- 17=Active file,100=guest user -->
                                    <div class="form-actions">
                                        <div class="row">
                                            <div class="col-md-offset-3 col-md-9">
                                                @if(isset($one_baseline_nurse->created_by) &&  $one_baseline_nurse->created_by==auth()->user()->id)
                                                    <button type="submit" class="btn btn-success">Submit</button>
                                                @elseif(!isset($one_baseline_nurse->created_by))
                                                    <button type="submit" class="btn btn-success">Submit</button>
                                                @endif
                                                <button type="button" class="btn grey-salsa btn-outline">Cancel
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                {{Form::close()}}
                                <!-- END FORM-->
                                </div>
                                <div class="tab-pane" id="tab_1_1_2">
                                    <!-- BEGIN FORM-->
                                    {{Form::open(['url'=>url('baselineDoctor'),'class'=>'form-horizontal','method'=>"post","id"=>'baseline_doctor_form'])}}
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
                                            <div class="col-md-6">
                                                <input class="form-control date-picker" type="text"
                                                       name="visit_date_doctor"
                                                       data-date-format="yyyy-mm-dd"
                                                       value="{{isset($one_baseline_doctor->visit_date)?$one_baseline_doctor->visit_date:''}}"/>

                                                <span class="help-block"> Select date </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Second Doctor
                                            </label>
                                            <div class="col-md-6">
                                                <select class="form-control select2" name="second_doctor_id">
                                                    <option value="">Select...</option>
                                                    <?php
                                                    $selected = '';
                                                    foreach ($doctor_list as $raw) {
                                                        $selected = '';
                                                        if (isset($one_baseline_doctor->second_doctor_id) && $raw->id == $one_baseline_doctor->second_doctor_id)
                                                            $selected = 'selected="selected"';
                                                        echo '<option value="' . $raw->id . '" ' . $selected . '>' . $raw->name . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <h3 class="form-section font-green"> Patient History </h3>
                                        <hr/>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Triggering event for pain
                                                condition</label>
                                            <div class="col-md-6">
                                                <select class="form-control select2-multiple" id="injury_mechanism"
                                                        name="injury_mechanism[]" multiple
                                                        onchange="show_hide_truma_section();">
                                                    <option value="">Select...</option>
                                                    <?php
                                                    $selected = '';
                                                    foreach ($injury_mechanism_list as $raw) {
                                                        $selected = '';
                                                        if (is_array($one_baseline_injury_mechanism))
                                                            for ($i = 0; $i < count($one_baseline_injury_mechanism); $i++) {
                                                                if ($raw->id == $one_baseline_injury_mechanism[$i])
                                                                    $selected = 'selected="selected"';
                                                            }
                                                        echo '<option value="' . $raw->id . '" ' . $selected . '>' . $raw->lookup_details . '</option>';

                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div id="dvSpecifyInjuryMechanismDR" class="form-group ">
                                            <label class="control-label col-md-3">Specify other trigger
                                            </label>
                                            <div class="col-md-6">
                                                <input type="text" name="specify_injury_mechanism"
                                                       id="specify_injury_mechanism"
                                                       class="form-control"
                                                       value="{{isset($one_baseline_doctor->specify_injury_mechanism)?$one_baseline_doctor->specify_injury_mechanism:''}}"
                                                /></div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Other pains before injury

                                            </label>
                                            <div class="col-md-6">
                                                <select class="form-control" name="other_pains_before_injury"
                                                        id="other_pains_before_injury"
                                                        onchange="show_other_pains_before_injury()">
                                                    <option value="">Select...</option>
                                                    <option value="1" @php if(isset($one_baseline_doctor->other_pains_before_injury)&& $one_baseline_doctor->other_pains_before_injury==1) echo 'selected="selected"'; @endphp>
                                                        Yes
                                                    </option>
                                                    <option value="0" @php if(isset($one_baseline_doctor->other_pains_before_injury)&& $one_baseline_doctor->other_pains_before_injury==0) echo 'selected="selected"'; @endphp>
                                                        No
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div id="dvSpecifyOtherPainsBeforeInjuryDR" class="form-group">
                                            <label class="control-label col-md-3">Specify other
                                            </label>
                                            <div class="col-md-6">
                                                <input type="text" name="specify_other_pains_before_injury"
                                                       id="specify_other_pains_before_injury"
                                                       class="form-control"
                                                       value="{{isset($one_baseline_doctor->specify_other_pains_before_injury)?$one_baseline_doctor->specify_other_pains_before_injury:''}}"
                                                /></div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Other non-pain symptoms

                                            </label>
                                            <div class="col-md-6">
                                                <select class="form-control" name="other_nonpain_symptoms"
                                                        id="other_nonpain_symptoms"
                                                        onchange="show_other_nonpain_symptoms()">
                                                    <option value="">Select...</option>
                                                    <option value="1" @php if(isset($one_baseline_doctor->other_nonpain_symptoms)&& $one_baseline_doctor->other_nonpain_symptoms==1) echo 'selected="selected"'; @endphp>
                                                        Yes
                                                    </option>
                                                    <option value="0" @php if(isset($one_baseline_doctor->other_nonpain_symptoms)&& $one_baseline_doctor->other_nonpain_symptoms==0) echo 'selected="selected"'; @endphp>
                                                        No
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div id="dvSpecifyOtherNonpainSymptoms" class="form-group">
                                            <label class="control-label col-md-3">Specify other
                                            </label>
                                            <div class="col-md-6">
                                                <input type="text" name="specify_other_nonpain_symptoms"
                                                       id="specify_other_nonpain_symptoms"
                                                       class="form-control"
                                                       value="{{isset($one_baseline_doctor->specify_other_nonpain_symptoms)?$one_baseline_doctor->specify_other_nonpain_symptoms:''}}"
                                                /></div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Comorbidities

                                            </label>
                                            <div class="col-md-6">
                                                <select class="form-control" name="comorbidities" id="comorbidities"
                                                        onchange="show_other_comorbidities()">
                                                    <option value="">Select...</option>
                                                    <option value="1" @php if(isset($one_baseline_doctor->comorbidities)&& $one_baseline_doctor->comorbidities==1) echo 'selected="selected"'; @endphp>
                                                        Yes
                                                    </option>
                                                    <option value="0" @php if(isset($one_baseline_doctor->comorbidities)&& $one_baseline_doctor->comorbidities==0) echo 'selected="selected"'; @endphp>
                                                        No
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div id="dvSpecifyComorbidities" class="form-group">
                                            <label class="control-label col-md-3">Specify other
                                            </label>
                                            <div class="col-md-6">
                                                <input type="text" name="specify_comorbidities"
                                                       id="specify_comorbidities" class="form-control"
                                                       value="{{isset($one_baseline_doctor->specify_comorbidities)?$one_baseline_doctor->specify_comorbidities:''}}"
                                                />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Allergies

                                            </label>
                                            <div class="col-md-6">
                                                <select class="form-control" name="allergies" id="allergies"
                                                        onchange="show_other_allergies()">
                                                    <option value="">Select...</option>
                                                    <option value="1" @php if(isset($one_baseline_doctor->allergies)&& $one_baseline_doctor->allergies==1) echo 'selected="selected"'; @endphp>
                                                        Yes
                                                    </option>
                                                    <option value="0" @php if(isset($one_baseline_doctor->allergies)&& $one_baseline_doctor->allergies==0) echo 'selected="selected"'; @endphp>
                                                        No
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div id="dvSpecifyAllergies" class="form-group">
                                            <label class="control-label col-md-3">Specify other
                                            </label>
                                            <div class="col-md-6">
                                                <input type="text" name="specify_allergies"
                                                       id="specify_allergies" class="form-control"
                                                       value="{{isset($one_baseline_doctor->specify_allergies)?$one_baseline_doctor->specify_allergies:''}}"
                                                />
                                            </div>
                                        </div>
                                        <h3 class="form-section font-green"> Previous Treatment </h3>
                                        <hr/>

                                        <div class="form-group">
                                            <label class="control-label col-md-3">Previous Surgery

                                            </label>

                                            <div class="col-md-6">
                                                <select class="form-control" name="previous_surgery"
                                                        id="previous_surgery"
                                                        onchange="show_other_previous_surgerys()">
                                                    <option value="">Select...</option>
                                                    <option value="1" @php if(isset($one_baseline_doctor->previous_surgery)&& $one_baseline_doctor->previous_surgery==1) echo 'selected="selected"'; @endphp>
                                                        Yes
                                                    </option>
                                                    <option value="0" @php if(isset($one_baseline_doctor->previous_surgery)&& $one_baseline_doctor->previous_surgery==0) echo 'selected="selected"'; @endphp>
                                                        No
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div id="dvSpecifyPreviousSurgery" class="form-group">
                                            <label class="control-label col-md-3">Specify other
                                            </label>
                                            <div class="col-md-6">
                                                <input type="text" name="specify_previous_surgery"
                                                       id="specify_previous_surgery"
                                                       class="form-control"
                                                       value="{{isset($one_baseline_doctor->specify_previous_surgery)?$one_baseline_doctor->specify_previous_surgery:''}}"
                                                /></div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Active Rehabilitation

                                            </label>
                                            <div class="col-md-6">
                                                <select class="form-control" name="active_rehabilitation"
                                                        id="active_rehabilitation"
                                                        onchange="show_other_active_rehabilitation()">
                                                    <option value="">Select...</option>
                                                    <option value="1" @php if(isset($one_baseline_doctor->active_rehabilitation)&& $one_baseline_doctor->active_rehabilitation==1) echo 'selected="selected"'; @endphp>
                                                        Yes
                                                    </option>
                                                    <option value="0" @php if(isset($one_baseline_doctor->active_rehabilitation)&& $one_baseline_doctor->active_rehabilitation==0) echo 'selected="selected"'; @endphp>
                                                        No
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div id="dvSpecifyActiveRehabilitation" class="form-group">
                                            <label class="control-label col-md-3">Specify other
                                            </label>
                                            <div class="col-md-6">
                                                <input type="text" name="specify_active_rehabilitation"
                                                       id="specify_active_rehabilitation"
                                                       class="form-control"
                                                       value="{{isset($one_baseline_doctor->specify_active_rehabilitation)?$one_baseline_doctor->specify_active_rehabilitation:''}}"
                                                /></div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Passive Rehabilitation

                                            </label>
                                            <div class="col-md-6">
                                                <select class="form-control" name="passive_rehabilitation"
                                                        id="passive_rehabilitation"
                                                        onchange="show_other_passive_rehabilitation()">
                                                    <option value="">Select...</option>
                                                    <option value="1" @php if(isset($one_baseline_doctor->passive_rehabilitation)&& $one_baseline_doctor->passive_rehabilitation==1) echo 'selected="selected"'; @endphp>
                                                        Yes
                                                    </option>
                                                    <option value="0" @php if(isset($one_baseline_doctor->passive_rehabilitation)&& $one_baseline_doctor->passive_rehabilitation==0) echo 'selected="selected"'; @endphp>
                                                        No
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div id="dvSpecifyPassiveRehabilitation" class="form-group">
                                            <label class="control-label col-md-3">Specify other
                                            </label>
                                            <div class="col-md-6">
                                                <input type="text" name="specify_passive_rehabilitation"
                                                       id="specify_passive_rehabilitation"
                                                       class="form-control"
                                                       value="{{isset($one_baseline_doctor->specify_passive_rehabilitation)?$one_baseline_doctor->specify_passive_rehabilitation:''}}"
                                                /></div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Other Non-Pharmacological
                                                Treatments

                                            </label>
                                            <div class="col-md-6">
                                                <select class="form-control"
                                                        name="nonpharmacological_treatments"
                                                        id="nonpharmacological_treatments"
                                                        onchange="show_other_nonpharmacological_treatments()">
                                                    <option value="">Select...</option>
                                                    <option value="1" @php if(isset($one_baseline_doctor->nonpharmacological_treatments)&& $one_baseline_doctor->nonpharmacological_treatments==1) echo 'selected="selected"'; @endphp>
                                                        Yes
                                                    </option>
                                                    <option value="0" @php if(isset($one_baseline_doctor->nonpharmacological_treatments)&& $one_baseline_doctor->nonpharmacological_treatments==0) echo 'selected="selected"'; @endphp>
                                                        No
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div id="dvSpecifyNonpharmacologicalTreatments" class="form-group">
                                            <label class="control-label col-md-3">Specify other
                                            </label>
                                            <div class="col-md-6">
                                                <input type="text" name="specify_nonpharmacological_treatments"
                                                       id="specify_nonpharmacological_treatments"
                                                       class="form-control"
                                                       value="{{isset($one_baseline_doctor->specify_nonpharmacological_treatments)?$one_baseline_doctor->specify_nonpharmacological_treatments:''}}"
                                                /></div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Drugs

                                            </label>
                                            <div class="col-md-6">
                                                <select class="form-control" name="take_drug" id="take_drug"
                                                        onchange="show_prvTreatdrug_table()">
                                                    <option value="">Select...</option>
                                                    <option value="1" @php if(isset($one_baseline_doctor->take_drug)&& $one_baseline_doctor->take_drug==1) echo 'selected="selected"'; @endphp>
                                                        Yes
                                                    </option>
                                                    <option value="0" @php if(isset($one_baseline_doctor->take_drug)&& $one_baseline_doctor->take_drug==0) echo 'selected="selected"'; @endphp>
                                                        No
                                                    </option>
                                                    <option value="2" @php if(isset($one_baseline_doctor->take_drug)&& $one_baseline_doctor->take_drug==2) echo 'selected="selected"'; @endphp>
                                                        Others
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row" id="dvPreviousTreatmentDrug">
                                            <div class="alert alert-danger alert-danger-drugs  display-hide">
                                                <button class="close" data-close="alert"></button>
                                                You have some form errors. Please check below.
                                            </div>
                                            <div class="alert alert-success alert-success-drugs display-hide">
                                                <button class="close" data-close="alert"></button>
                                                Your form validation is successful!
                                            </div>
                                            <div class="col-md-12">
                                                <div class="table-scrollable table-scrollable-borderless">
                                                    <table class="table table-striped table-hover table-advance">
                                                        <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Drugs</th>
                                                            <th>Treatment Effects</th>
                                                        </tr>
                                                        <tr class="uppercase">
                                                            <td> #</td>
                                                            <td>
                                                                <div class="form-group  col-md-12">
                                                                    <select id="drug_id"
                                                                            class="form-control select2">
                                                                        <option value="">Select Drug</option>
                                                                        <?php
                                                                        foreach ($drug_list as $raw)
                                                                            echo '<option value="' . $raw->id . '">' . $raw->name . '</option>';
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group  col-md-12">
                                                                    <select id="effect_id"
                                                                            class="form-control select2">
                                                                        <option value="">Select Treatment Effect
                                                                        </option>
                                                                        <?php

                                                                        foreach ($effect_list as $raw) {
                                                                            $selected = '';
                                                                            echo '<option value="' . $raw->id . '"  >' . $raw->lookup_details . '</option>';
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </td>
                                                            <td>
                                                            @if($painFile_statusId==17 && auth()->user()->id!=100)<!-- 17=Active file,100=guest user -->
                                                                <button type="button"
                                                                        class="btn btn-success btn-icon-only"
                                                                        onclick="add_previse_drug()" {!!  $baselineDoctor_style !!}>
                                                                    <i
                                                                            class="fa fa-plus fa-fw"></i>
                                                                </button>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="tbprv_treatment">
                                                        @php
                                                            echo $previousTreatment_data
                                                        @endphp
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3">Other Drugs

                                            </label>
                                            <div class="col-md-6">
                                                <input type="text" name="other_drugs" class="form-control"
                                                       value="{{isset($one_baseline_doctor->other_drugs)?$one_baseline_doctor->other_drugs:''}}"
                                                /></div>
                                        </div>

                                        <h3 class="form-section font-green"> Diagnostic algorithm </h3>
                                        <hr/>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Pain due to injury/disease to neural
                                                tissue</label>

                                            <div class="col-md-6">
                                                <select class="form-control" name="pain_dueto_neural_tissue"
                                                        id="pain_dueto_neural_tissue"
                                                        onchange="show_option_pain_dueto_neural_tissue()">
                                                    <option value="">Select...</option>
                                                    <option value="1" @php if(isset($one_baseline_diagnosis_alg->pain_dueto_neural_tissue)&& $one_baseline_diagnosis_alg->pain_dueto_neural_tissue==1) echo 'selected="selected"'; @endphp>
                                                        Yes
                                                    </option>
                                                    <option value="0" @php if(isset($one_baseline_diagnosis_alg->pain_dueto_neural_tissue)&& $one_baseline_diagnosis_alg->pain_dueto_neural_tissue==0) echo 'selected="selected"'; @endphp>
                                                        No
                                                    </option>
                                                </select>
                                            </div>
                                        </div>

                                        <div id="dv_neural_tissue" {!!$dv_neural_tissue!!}>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Patient history of relevant
                                                    neurological lesion/disease</label>

                                                <div class="col-md-6">
                                                    <select class="form-control"
                                                            name="history_of_relevant_neurological_lesion"
                                                            id="history_of_relevant_neurological_lesion"
                                                            onchange="show_option_pain_dueto_neural_pain();show_option_pain_dueto_nonneural_pain();">
                                                        <option value="">Select...</option>
                                                        <option value="1" @php if(isset($one_baseline_diagnosis_alg->history_of_relevant_neurological_lesion)&& $one_baseline_diagnosis_alg->history_of_relevant_neurological_lesion==1) echo 'selected="selected"'; @endphp>
                                                            Yes
                                                        </option>
                                                        <option value="0" @php if(isset($one_baseline_diagnosis_alg->history_of_relevant_neurological_lesion)&& $one_baseline_diagnosis_alg->history_of_relevant_neurological_lesion==0) echo 'selected="selected"'; @endphp>
                                                            No
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Patient history of pain
                                                    distribution
                                                    is neuroanatomically plausible</label>

                                                <div class="col-md-6">
                                                    <select class="form-control"
                                                            name="history_of_pain_distribution_is_neur_plausible"
                                                            id="history_of_pain_distribution_is_neur_plausible"
                                                            onchange="show_option_pain_dueto_neural_pain();show_option_pain_dueto_nonneural_pain();">
                                                        <option value="">Select...</option>
                                                        <option value="1" @php if(isset($one_baseline_diagnosis_alg->history_of_pain_distribution_is_neur_plausible)&& $one_baseline_diagnosis_alg->history_of_pain_distribution_is_neur_plausible==1) echo 'selected="selected"'; @endphp>
                                                            Yes
                                                        </option>
                                                        <option value="0" @php if(isset($one_baseline_diagnosis_alg->history_of_pain_distribution_is_neur_plausible)&& $one_baseline_diagnosis_alg->history_of_pain_distribution_is_neur_plausible==0) echo 'selected="selected"'; @endphp>
                                                            No
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Pain is associated with sensory
                                                    signs
                                                    in the same neuroanatomically plausible distribution on
                                                    examination</label>

                                                <div class="col-md-6">
                                                    <select class="form-control"
                                                            name="pain_associated_with_sensory_signs"
                                                            id="pain_associated_with_sensory_signs"
                                                            onchange="show_option_pain_dueto_neural_pain();show_option_pain_dueto_nonneural_pain();">
                                                        <option value="">Select...</option>
                                                        <option value="1" @php if(isset($one_baseline_diagnosis_alg->pain_associated_with_sensory_signs)&& $one_baseline_diagnosis_alg->pain_associated_with_sensory_signs==1) echo 'selected="selected"'; @endphp>
                                                            Yes
                                                        </option>
                                                        <option value="0" @php if(isset($one_baseline_diagnosis_alg->pain_associated_with_sensory_signs)&& $one_baseline_diagnosis_alg->pain_associated_with_sensory_signs==0) echo 'selected="selected"'; @endphp>
                                                            No
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="dv_neural_pain" {!!$dv_neural_pain!!}>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Neuropathic pain</label>

                                                <div class="col-md-6">
                                                    <select class="form-control" name="neuropathic_pain"
                                                            id="neuropathic_pain"
                                                            onchange="show_option_pain_dueto_nonneural_pain();show_side_and_nerve_section();">
                                                        <option value="">Select...</option>
                                                        <?php
                                                        $selected = '';
                                                        foreach ($neuropathic_pain_list as $raw) {
                                                            $selected = '';
                                                            if (isset($one_baseline_diagnosis_alg->neuropathic_pain) && ($raw->id == $one_baseline_diagnosis_alg->neuropathic_pain))
                                                                $selected = 'selected';
                                                            echo '<option value="' . $raw->id . '" ' . $selected . '>' . $raw->lookup_details . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="dv_nonneural" {!!$dv_nonneural!!}>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Pain due to injury/disease to
                                                    NON-neural tissue</label>

                                                <div class="col-md-6">
                                                    <select class="form-control" name="pain_dueto_nonneural_tissue"
                                                            id="pain_dueto_nonneural_tissue"
                                                            onchange="show_option_pain_dueto_nonneural_tissue();">
                                                        <option value="">Select...</option>
                                                        <option value="1" @php if(isset($one_baseline_diagnosis_alg->pain_dueto_nonneural_tissue)&& $one_baseline_diagnosis_alg->pain_dueto_nonneural_tissue==1) echo 'selected="selected"'; @endphp>
                                                            Yes
                                                        </option>
                                                        <option value="0" @php if(isset($one_baseline_diagnosis_alg->pain_dueto_nonneural_tissue)&& $one_baseline_diagnosis_alg->pain_dueto_nonneural_tissue==0) echo 'selected="selected"'; @endphp>
                                                            No
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="dv_nonneural_tissue" {!!$dv_nonneural_tissue!!}>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Patient history of relevant
                                                    (non-neurological) lesion or disease</label>

                                                <div class="col-md-6">
                                                    <select class="form-control"
                                                            name="history_of_relevant_nonneurological_lesion"
                                                            id="history_of_relevant_nonneurological_lesion"
                                                            onchange="show_pain_dueto_nonneural_tissue();show_pain_dueto_nonneural_tissue_ind();">
                                                        <option value="">Select...</option>
                                                        <option value="1" @php if(isset($one_baseline_diagnosis_alg->history_of_relevant_nonneurological_lesion)&& $one_baseline_diagnosis_alg->history_of_relevant_nonneurological_lesion==1) echo 'selected="selected"'; @endphp>
                                                            Yes
                                                        </option>
                                                        <option value="0" @php if(isset($one_baseline_diagnosis_alg->history_of_relevant_nonneurological_lesion)&& $one_baseline_diagnosis_alg->history_of_relevant_nonneurological_lesion==0) echo 'selected="selected"'; @endphp>
                                                            No
                                                        </option>
                                                    </select>
                                                    <p class="help-block">i.e. inflammation; structural changes;
                                                        mechanical
                                                        factors (static or dynamic); or vascular mechanisms</p>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Patient history of pain
                                                    distribution
                                                    is plausible due to lesion/disease</label>

                                                <div class="col-md-6">
                                                    <select class="form-control"
                                                            name="history_of_pain_distribution_is_plausible"
                                                            id="history_of_pain_distribution_is_plausible"
                                                            onchange="show_pain_dueto_nonneural_tissue();show_pain_dueto_nonneural_tissue_ind();">
                                                        <option value="">Select...</option>
                                                        <option value="1" @php if(isset($one_baseline_diagnosis_alg->history_of_pain_distribution_is_plausible)&& $one_baseline_diagnosis_alg->history_of_pain_distribution_is_plausible==1) echo 'selected="selected"'; @endphp>
                                                            Yes
                                                        </option>
                                                        <option value="0" @php if(isset($one_baseline_diagnosis_alg->history_of_pain_distribution_is_plausible)&& $one_baseline_diagnosis_alg->history_of_pain_distribution_is_plausible==0) echo 'selected="selected"'; @endphp>
                                                            No
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Pain is consistently correlated
                                                    with
                                                    the (non-neurological) lesion/disease
                                                </label>
                                                <div class="col-md-6">
                                                    <select class="form-control"
                                                            name="pain_consistently_correlated_with_nonneurological_lesion"
                                                            id="pain_consistently_correlated_with_nonneurological_lesion"
                                                            onchange="show_pain_dueto_nonneural_tissue();show_pain_dueto_nonneural_tissue_ind();">
                                                        <option value="">Select...</option>
                                                        <option value="1" @php if(isset($one_baseline_diagnosis_alg->pain_consistently_correlated_with_nonneurological_lesion)&& $one_baseline_diagnosis_alg->pain_consistently_correlated_with_nonneurological_lesion==1) echo 'selected="selected"'; @endphp>
                                                            Yes
                                                        </option>
                                                        <option value="0" @php if(isset($one_baseline_diagnosis_alg->pain_consistently_correlated_with_nonneurological_lesion)&& $one_baseline_diagnosis_alg->pain_consistently_correlated_with_nonneurological_lesion==0) echo 'selected="selected"'; @endphp>
                                                            No
                                                        </option>
                                                    </select>
                                                    <p class="help-block">i.e. inflammation; structural changes;
                                                        mechanical
                                                        factors; or vascular mechanisms<br/>e.g. If the knee OA is
                                                        sometimes
                                                        pain free when walking it is not evident that the structural
                                                        changes
                                                        causes pain when loading the knee joint during walking at other
                                                        times</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="dv_nociceptive_pain" {!!$dv_nociceptive_pain!!}>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Nociceptive pain</label>

                                                <div class="col-md-6">
                                                    <select class="form-control" name="nociceptive_pain"
                                                            id="nociceptive_pain"
                                                            onchange="show_pain_dueto_nonneural_tissue_ind();show_side_and_nerve_section();">
                                                        <option value="">Select...</option>
                                                        <?php

                                                        foreach ($nociceptive_pain_list as $raw) {
                                                            $selected = '';
                                                            if (isset($one_baseline_diagnosis_alg->nociceptive_pain) && $raw->id == $one_baseline_diagnosis_alg->nociceptive_pain)
                                                                $selected = 'selected';

                                                            echo '<option value="' . $raw->id . '" ' . $selected . '>' . $raw->lookup_details . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="dv_independent" {!!$dv_independent!!}>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Pain independent of injuries and
                                                    diseases</label>

                                                <div class="col-md-6">
                                                    <select class="form-control"
                                                            name="pain_independent_of_injuries_and_diseases"
                                                            id="pain_independent_of_injuries_and_diseases"
                                                            onchange="show_option_tree3_level1();">
                                                        <option value="">Select...</option>
                                                        <option value="1" @php if(isset($one_baseline_diagnosis_alg->pain_independent_of_injuries_and_diseases)&& $one_baseline_diagnosis_alg->pain_independent_of_injuries_and_diseases==1) echo 'selected="selected"'; @endphp>
                                                            Yes
                                                        </option>
                                                        <option value="0" @php if(isset($one_baseline_diagnosis_alg->pain_independent_of_injuries_and_diseases)&& $one_baseline_diagnosis_alg->pain_independent_of_injuries_and_diseases==0) echo 'selected="selected"'; @endphp>
                                                            No
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="dv_independent_history" {!!$dv_independent_history!!}>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Patient history and examination in
                                                    accordance with the diagnostic Budapest criteria</label>

                                                <div class="col-md-6">
                                                    <select class="form-control"
                                                            name="history_accordance_diagnostic_budapest_criteria"
                                                            id="history_accordance_diagnostic_budapest_criteria"
                                                            onchange="show_option_tree3_level2();">
                                                        <option value="">Select...</option>
                                                        <option value="1" @php if(isset($one_baseline_diagnosis_alg->history_accordance_diagnostic_budapest_criteria)&& $one_baseline_diagnosis_alg->history_accordance_diagnostic_budapest_criteria==1) echo 'selected="selected"'; @endphp>
                                                            Yes
                                                        </option>
                                                        <option value="0" @php if(isset($one_baseline_diagnosis_alg->history_accordance_diagnostic_budapest_criteria)&& $one_baseline_diagnosis_alg->history_accordance_diagnostic_budapest_criteria==0) echo 'selected="selected"'; @endphp>
                                                            No
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="dv_independent_CRPS" {!!$dv_independent_CRPS!!}>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">CRPS</label>

                                                <div class="col-md-6">
                                                    <select class="form-control" name="crps_pain"
                                                            id="crps_pain" onchange="show_side_and_nerve_section();">
                                                        <option value="">Select...</option>
                                                        <?php
                                                        foreach ($crps_pain_list as $raw) {
                                                            $selected = '';
                                                            if (isset($one_baseline_diagnosis_alg->crps_pain) && $raw->id == $one_baseline_diagnosis_alg->crps_pain)
                                                                $selected = 'selected';
                                                            echo '<option value="' . $raw->id . '" ' . $selected . '>' . $raw->lookup_details . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="dv_distribution_regional" {!!$dv_distribution_regional!!}>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">The pain distribution is
                                                    regional
                                                    rather than discrete</label>

                                                <div class="col-md-6">
                                                    <select class="form-control" name="pain_distribution_regional"
                                                            id="pain_distribution_regional"
                                                            onchange="show_option_tree3_level3();">
                                                        <option value="">Select...</option>
                                                        <option value="1" @php if(isset($one_baseline_diagnosis_alg->pain_distribution_regional)&& $one_baseline_diagnosis_alg->pain_distribution_regional==1) echo 'selected="selected"'; @endphp>
                                                            Yes
                                                        </option>
                                                        <option value="0" @php if(isset($one_baseline_diagnosis_alg->pain_distribution_regional)&& $one_baseline_diagnosis_alg->pain_distribution_regional==0) echo 'selected="selected"'; @endphp>
                                                            No
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Pain hypersensitivity in the
                                                    region of
                                                    pain during clinical assessment (allodynia OR painful
                                                    after-sensation
                                                    following assessment of allodynia)</label>

                                                <div class="col-md-6">
                                                    <select class="form-control"
                                                            name="pain_hypersensitivity_in_region_pain_during_assessment"
                                                            id="pain_hypersensitivity_in_region_pain_during_assessment"
                                                            onchange="show_option_tree3_level3();">
                                                        <option value="">Select...</option>
                                                        <option value="1" @php if(isset($one_baseline_diagnosis_alg->pain_hypersensitivity_in_region_pain_during_assessment)&& $one_baseline_diagnosis_alg->pain_hypersensitivity_in_region_pain_during_assessment==1) echo 'selected="selected"'; @endphp>
                                                            Yes
                                                        </option>
                                                        <option value="0" @php if(isset($one_baseline_diagnosis_alg->pain_hypersensitivity_in_region_pain_during_assessment)&& $one_baseline_diagnosis_alg->pain_hypersensitivity_in_region_pain_during_assessment==0) echo 'selected="selected"'; @endphp>
                                                            No
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">History of pain
                                                    hypersensitivity in
                                                    the region of pain due to touch OR pressure OR movement OR
                                                    heat/cold</label>

                                                <div class="col-md-6">
                                                    <select class="form-control"
                                                            name="pain_hypersensitivity_in_region_pain_dueto_touch"
                                                            id="pain_hypersensitivity_in_region_pain_dueto_touch"
                                                            onchange="show_option_tree3_level3();">
                                                        <option value="">Select...</option>
                                                        <option value="1" @php if(isset($one_baseline_diagnosis_alg->pain_hypersensitivity_in_region_pain_dueto_touch)&& $one_baseline_diagnosis_alg->pain_hypersensitivity_in_region_pain_dueto_touch==1) echo 'selected="selected"'; @endphp>
                                                            Yes
                                                        </option>
                                                        <option value="0" @php if(isset($one_baseline_diagnosis_alg->pain_hypersensitivity_in_region_pain_dueto_touch)&& $one_baseline_diagnosis_alg->pain_hypersensitivity_in_region_pain_dueto_touch==0) echo 'selected="selected"'; @endphp>
                                                            No
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="dv_nociplastic_pain" {!!$dv_nociplastic_pain!!}>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Nociplastic pain</label>

                                                <div class="col-md-6">
                                                    <select class="form-control" name="nociplastic_pain"
                                                            id="nociplastic_pain">
                                                        <option value="">Select...</option>
                                                        <?php
                                                        foreach ($nociplastic_pain_list as $raw) {
                                                            $selected = '';
                                                            if (isset($one_baseline_diagnosis_alg->nociplastic_pain) && $raw->id == $one_baseline_diagnosis_alg->nociplastic_pain)
                                                                $selected = 'selected';
                                                            echo '<option value="' . $raw->id . '" ' . $selected . '>' . $raw->lookup_details . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="dv_idiopathic_pain" {!!$dv_idiopathic_pain!!}>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Idiopathic pain</label>

                                                <div class="col-md-6">
                                                    <select class="form-control" name="idiopathic_pain"
                                                            id="idiopathic_pain" onchange="show_side_and_nerve_section();">
                                                        <option value="">Select...</option>
                                                        <?php
                                                        foreach ($idiopathic_pain_list as $raw) {
                                                            $selected = '';
                                                            if (isset($one_baseline_diagnosis_alg->idiopathic_pain) && $raw->id == $one_baseline_diagnosis_alg->idiopathic_pain)
                                                                $selected = 'selected';
                                                            echo '<option value="' . $raw->id . '" ' . $selected . '>' . $raw->lookup_details . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <h3 class="form-section font-green"> Neurological Examination </h3>
                                        <!--                                        <hr/>-->
                                    <!--                                        <div class="form-group">
                                            <label class="control-label col-md-3">A history of pain due to
                                                injury
                                                (accidental or surgical) to one or several well-defined
                                                peripheral
                                                nerves?

                                            </label>
                                            <div class="col-md-6">
                                                <select class="form-control" name="neuro_history_of_pain">
                                                    <option value="">Select...</option>
                                                    <option value="1" @php if(isset($one_baseline_doctor->neuro_history_of_pain)&& $one_baseline_doctor->neuro_history_of_pain==1) echo 'selected="selected"'; @endphp>
                                                        Yes
                                                    </option>
                                                    <option value="0" @php if(isset($one_baseline_doctor->neuro_history_of_pain)&& $one_baseline_doctor->neuro_history_of_pain==0) echo 'selected="selected"'; @endphp>
                                                        No
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                         <div class="form-group">
                                                <label class="control-label col-md-3">Pain localized to the area of
                                                    the
                                                    specific nerve(s)?

                                                </label>
                                                <div class="col-md-6">
                                                    <select id="neuro_pain_localized" name="neuro_pain_localized"
                                                            class="form-control select2"
                                                            onchange="show_side_and_nerve_table();">
                                                        <option value="0" @php if(isset($one_baseline_doctor->neuro_pain_localized)&& $one_baseline_doctor->neuro_pain_localized==0) echo 'selected="selected"'; @endphp>
                                                            No
                                                        </option>
                                                        <option value="1" @php if(isset($one_baseline_doctor->neuro_pain_localized)&& $one_baseline_doctor->neuro_pain_localized==1) echo 'selected="selected"'; @endphp>
                                                            Yes
                                                        </option>
                                                        <option value="2" @php if(isset($one_baseline_doctor->neuro_pain_localized)&& $one_baseline_doctor->neuro_pain_localized==2) echo 'selected="selected"'; @endphp>
                                                            Yes,but also other areas
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>-->
                                        <div class="dvNeroPainArea">
                                            <div class="row ">
                                                <p>Specify side and nerve(s)1</p>
                                                <div class="alert alert-danger alert-danger-side display-hide">
                                                    <button class="close" data-close="alert"></button>
                                                    You have some form errors. Please check below.
                                                </div>
                                                <div class="alert alert-success alert-success-side display-hide">
                                                    <button class="close" data-close="alert"></button>
                                                    Your form validation is successful!
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="table-scrollable table-scrollable-borderless">
                                                        <table class="table table-hover table-light  table-advance">
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
                                                                                class="form-control input-sm"
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
                                                                        <select id="side_detail_id"
                                                                                name="side_detail_id"
                                                                                class="form-control select2 input-sm"
                                                                                onchange="get_sub_side_details();">
                                                                            <option value="">Select..</option>

                                                                        </select>
                                                                        <select id="sub_side_detail_id"
                                                                                name="sub_side_detail_id"
                                                                                class="form-control  input-sm display-hide">
                                                                            <option value="">Select..</option>

                                                                        </select>

                                                                    </div>

                                                                </td>
                                                                <td>
                                                                    <div class="form-group input-group-sm select2-bootstrap-prepend  col-md-12">
                                                                        <select id="light_touch"
                                                                                class="form-control input-sm">
                                                                            <option value="">Select...</option>
                                                                            <?php
                                                                            foreach ($light_touch_list as $raw)
                                                                                echo '<option value="' . $raw->id . '">' . $raw->lookup_details . '</option>';
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group input-group-sm select2-bootstrap-prepend  col-md-12">
                                                                        <select id="pinprick"
                                                                                class="form-control input-sm">
                                                                            <option value="">Select...</option>
                                                                            <?php
                                                                            foreach ($light_touch_list as $raw)
                                                                                echo '<option value="' . $raw->id . '">' . $raw->lookup_details . '</option>';
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group input-group-sm select2-bootstrap-prepend col-md-12">
                                                                        <select id="warmth"
                                                                                class="form-control input-sm">
                                                                            <option value="">Select...</option>
                                                                            <?php
                                                                            foreach ($warmth_list as $raw)
                                                                                echo '<option value="' . $raw->id . '">' . $raw->lookup_details . '</option>';
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group input-group-sm select2-bootstrap-prepend col-md-12">
                                                                        <select id="cold"
                                                                                class="form-control input-sm">
                                                                            <option value="">Select...</option>
                                                                            <?php
                                                                            foreach ($warmth_list as $raw)
                                                                                echo '<option value="' . $raw->id . '">' . $raw->lookup_details . '</option>';
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                @if($painFile_statusId==17 && auth()->user()->id!=100)<!-- 17=Active file,100=guest user -->
                                                                    <button type="button"
                                                                            class="btn btn-success btn-icon-only"
                                                                            onclick="save_side_and_nerve();" {!!  $baselineDoctor_style !!}>
                                                                        <i
                                                                                class="fa fa-plus fa-fw"></i>
                                                                    </button>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            </thead>
                                                            <tbody id="tbNeuroDR">
                                                            @php
                                                                echo $sideNerve_data
                                                            @endphp
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="dvOtherTruama">
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Pain localized to the other
                                                    areas
                                                    (than mentioned above)?
                                                </label>
                                                <div class="col-md-6">
                                                    <select id="neuro_side_and_other" name="neuro_side_and_other"
                                                            class="form-control select2"
                                                            onchange="show_other_side_and_nerve_table();">
                                                        <option value="0" @php if(isset($one_baseline_doctor->neuro_side_and_other)&& $one_baseline_doctor->neuro_side_and_other==0) echo 'selected="selected"'; @endphp>
                                                            No
                                                        </option>
                                                        <option value="1" @php if(isset($one_baseline_doctor->neuro_side_and_other)&& $one_baseline_doctor->neuro_side_and_other==1) echo 'selected="selected"'; @endphp>
                                                            Yes
                                                        </option>
                                                        <option value="2" @php if(isset($one_baseline_doctor->neuro_side_and_other)&& $one_baseline_doctor->neuro_side_and_other==2) echo 'selected="selected"'; @endphp>
                                                            Yes,but also other areas
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <p>Specify side and nerve(s)3</p>
                                            <div class="alert alert-danger alert-danger-side-other display-hide">
                                                <button class="close" data-close="alert"></button>
                                                You have some form errors. Please check below.
                                            </div>
                                            <div class="alert alert-success alert-success-side-other display-hide">
                                                <button class="close" data-close="alert"></button>
                                                Your form validation is successful!
                                            </div>
                                            <div class="row ">
                                                <div class="col-md-12">
                                                    <div class="table-scrollable table-scrollable-borderless">
                                                        <table class="table table-hover table-light  table-advance">
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
                                                                <td width="15%">
                                                                    <div class="form-group input-group-sm select2-bootstrap-prepend  col-md-12">
                                                                        <select id="other_side_nerve_id"
                                                                                class="form-control select2 input-sm">
                                                                            <option value="">Select...</option>
                                                                            <?php
                                                                            foreach ($scars_side_list as $raw)
                                                                                echo '<option value="' . $raw->id . '">' . $raw->lookup_details . '</option>';
                                                                            ?>
                                                                        </select>
                                                                    </div>

                                                                </td>
                                                                <td width="12%">
                                                                    <div class="form-group input-group-sm select2-bootstrap-prepend col-md-12">
                                                                        <select id="other_side_detail_id"
                                                                                name="other_side_detail_id"
                                                                                class="form-control  input-sm">
                                                                            <option value="">Select..</option>
                                                                            <?php
                                                                            foreach ($nerve_side_list as $raw)
                                                                                echo '<option value="' . $raw->id . '">' . $raw->lookup_details . '</option>';
                                                                            ?>
                                                                        </select>
                                                                    </div>

                                                                </td>
                                                                <td>
                                                                    <div class="form-group input-group-sm select2-bootstrap-prepend  col-md-12">
                                                                        <select id="other_light_touch"
                                                                                class="form-control input-sm">
                                                                            <option value="">Select...</option>
                                                                            <?php
                                                                            foreach ($light_touch_list as $raw)
                                                                                echo '<option value="' . $raw->id . '">' . $raw->lookup_details . '</option>';
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group input-group-sm select2-bootstrap-prepend  col-md-12">
                                                                        <select id="other_pinprick"
                                                                                class="form-control input-sm">
                                                                            <option value="">Select...</option>
                                                                            <?php
                                                                            foreach ($light_touch_list as $raw)
                                                                                echo '<option value="' . $raw->id . '">' . $raw->lookup_details . '</option>';
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group input-group-sm select2-bootstrap-prepend col-md-12">
                                                                        <select id="other_warmth"
                                                                                class="form-control input-sm">
                                                                            <option value="">Select...</option>
                                                                            <?php
                                                                            foreach ($warmth_list as $raw)
                                                                                echo '<option value="' . $raw->id . '">' . $raw->lookup_details . '</option>';
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group input-group-sm select2-bootstrap-prepend col-md-12">
                                                                        <select id="other_cold"
                                                                                class="form-control input-sm">
                                                                            <option value="">Select...</option>
                                                                            <?php
                                                                            foreach ($warmth_list as $raw)
                                                                                echo '<option value="' . $raw->id . '">' . $raw->lookup_details . '</option>';
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                @if($painFile_statusId==17 && auth()->user()->id!=100)<!-- 17=Active file,100=guest user -->
                                                                    <button type="button"
                                                                            class="btn btn-success btn-icon-only"
                                                                            onclick="save_other_side_and_nerve();">
                                                                        <i
                                                                                class="fa fa-minus fa-fw"></i>
                                                                    </button>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            </thead>
                                                            <tbody id="tb_other_NeuroDR">
                                                            @php
                                                                echo $other_sideNerve_data
                                                            @endphp
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="traumaSection">
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Pain localized to the area of
                                                    scars/surgery site(s)/trauma site(s)?
                                                </label>
                                                <div class="col-md-6">
                                                    <select id="neuro_side_and_scars" name="neuro_side_and_scars"
                                                            class="form-control select2"
                                                            onchange="show_scars_side_and_nerve_table();">
                                                        <option value="0" @php if(isset($one_baseline_doctor->neuro_side_and_scars)&& $one_baseline_doctor->neuro_side_and_scars==0) echo 'selected="selected"'; @endphp>
                                                            No
                                                        </option>
                                                        <option value="1" @php if(isset($one_baseline_doctor->neuro_side_and_scars)&& $one_baseline_doctor->neuro_side_and_scars==1) echo 'selected="selected"'; @endphp>
                                                            Yes
                                                        </option>
                                                        <option value="2" @php if(isset($one_baseline_doctor->neuro_side_and_scars)&& $one_baseline_doctor->neuro_side_and_scars==2) echo 'selected="selected"'; @endphp>
                                                            Yes,but also other areas
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <p>Specify side and nerve(s)2</p>
                                            <div class="alert alert-danger alert-danger-side-scars display-hide">
                                                <button class="close" data-close="alert"></button>
                                                You have some form errors. Please check below.
                                            </div>
                                            <div class="alert alert-success alert-success-side-scars display-hide">
                                                <button class="close" data-close="alert"></button>
                                                Your form validation is successful!
                                            </div>
                                            <div class="row dvsideandscars">
                                                <div class="col-md-12">
                                                    <div class="table-scrollable table-scrollable-borderless">
                                                        <table class="table table-hover table-light  table-advance">
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
                                                                <td width="15%">
                                                                    <div class="form-group input-group-sm select2-bootstrap-prepend  col-md-12">
                                                                        <select id="scars_side_nerve_id"
                                                                                class="form-control select2 input-sm">
                                                                            <option value="">Select...</option>
                                                                            <?php
                                                                            foreach ($scars_side_list as $raw)
                                                                                echo '<option value="' . $raw->id . '">' . $raw->lookup_details . '</option>';
                                                                            ?>
                                                                        </select>
                                                                    </div>

                                                                </td>
                                                                <td width="12%">
                                                                    <div class="form-group input-group-sm select2-bootstrap-prepend col-md-12">
                                                                        <select id="scars_side_detail_id"
                                                                                name="scars_side_detail_id"
                                                                                class="form-control  input-sm">
                                                                            <option value="">Select..</option>
                                                                            <?php
                                                                            foreach ($nerve_side_list as $raw)
                                                                                echo '<option value="' . $raw->id . '">' . $raw->lookup_details . '</option>';
                                                                            ?>
                                                                        </select>
                                                                    </div>

                                                                </td>
                                                                <td>
                                                                    <div class="form-group input-group-sm select2-bootstrap-prepend  col-md-12">
                                                                        <select id="scars_light_touch"
                                                                                class="form-control input-sm">
                                                                            <option value="">Select...</option>
                                                                            <?php
                                                                            foreach ($light_touch_list as $raw)
                                                                                echo '<option value="' . $raw->id . '">' . $raw->lookup_details . '</option>';
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group input-group-sm select2-bootstrap-prepend  col-md-12">
                                                                        <select id="scars_pinprick"
                                                                                class="form-control input-sm">
                                                                            <option value="">Select...</option>
                                                                            <?php
                                                                            foreach ($light_touch_list as $raw)
                                                                                echo '<option value="' . $raw->id . '">' . $raw->lookup_details . '</option>';
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group input-group-sm select2-bootstrap-prepend col-md-12">
                                                                        <select id="scars_warmth"
                                                                                class="form-control input-sm">
                                                                            <option value="">Select...</option>
                                                                            <?php
                                                                            foreach ($warmth_list as $raw)
                                                                                echo '<option value="' . $raw->id . '">' . $raw->lookup_details . '</option>';
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group input-group-sm select2-bootstrap-prepend col-md-12">
                                                                        <select id="scars_cold"
                                                                                class="form-control input-sm">
                                                                            <option value="">Select...</option>
                                                                            <?php
                                                                            foreach ($warmth_list as $raw)
                                                                                echo '<option value="' . $raw->id . '">' . $raw->lookup_details . '</option>';
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                @if($painFile_statusId==17 && auth()->user()->id!=100)<!-- 17=Active file,100=guest user -->

                                                                    <button type="button"
                                                                            class="btn btn-success btn-icon-only"
                                                                            onclick="save_scars_side_and_nerve();" {!!  $baselineDoctor_style !!}>
                                                                        <i
                                                                                class="fa fa-plus fa-fw"></i>
                                                                    </button>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            </thead>
                                                            <tbody id="tb_scars_NeuroDR">
                                                            @php
                                                                echo $scars_sideNerve_data
                                                            @endphp
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <!---    End--->


                                        <h3 class="form-section font-green"> Diagnostics </h3>
                                        <hr/>
                                    <!--                                        <div class="form-group">
                                            <label class="control-label col-md-3">Diagnosis

                                            </label>
                                            <div class="col-md-8">
                                                <select id="diagnosis_id" name="diagnosis_id[]"
                                                        class="form-control select2-multiple" multiple>

                                                    <?php
                                    foreach ($diagnosis_list as $raw) {
                                        $selected = '';
                                        foreach ($one_baseline_diagnosis as $raw2) {
                                            if ($raw->id == $raw2->diagnostic_id)
                                                $selected = 'selected="selected"';
                                        }
                                        echo '<option value="' . $raw->id . '" ' . $selected . '>' . $raw->name . '</option>';
                                    }

                                    ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">
                                            If other Diagnosis, specify
                                        </label>
                                        <div class="col-md-8">
                                            <input type="text" name="diagnostic_specify_combination"
                                                   class="form-control"
                                                   value="{{isset($one_baseline_doctor->diagnostic_specify_combination)?$one_baseline_doctor->diagnostic_specify_combination:''}}"
                                                /></div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Additional PTSD
                                            </label>
                                            <div class="col-md-8">
                                                <select id="diagnostic_additional_ptsd"
                                                        name="diagnostic_additional_ptsd"
                                                        class="form-control select2">
                                                    <option value="">Select...</option>
                                                    <option value="1" @php if(isset($one_baseline_doctor->diagnostic_additional_ptsd)&& $one_baseline_doctor->diagnostic_additional_ptsd==1) echo 'selected="selected"'; @endphp>
                                                        Yes
                                                    </option>
                                                    <option value="0" @php if(isset($one_baseline_doctor->diagnostic_additional_ptsd)&& $one_baseline_doctor->diagnostic_additional_ptsd==0) echo 'selected="selected"'; @endphp>
                                                        No
                                                    </option>
                                                </select>
                                            </div>
                                        </div>-->
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Comments</label>
                                            <div class="col-md-8">
                                        <textarea name="baseline_doctor_comment" id="baseline_doctor_comment"
                                                  class="form-control"
                                                  rows="3"
                                                  placeholder="Doctor's comments">{{isset($one_baseline_doctor->baseline_doctor_comment)?$one_baseline_doctor->baseline_doctor_comment:''}}</textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Lab. Results</label>
                                            <div class="col-md-8">
                                        <textarea name="baseline_doctor_lab" id="baseline_doctor_lab"
                                                  class="form-control"
                                                  rows="3"
                                                  placeholder="Lab. Results">{{isset($one_baseline_doctor->baseline_doctor_lab)?$one_baseline_doctor->baseline_doctor_lab:''}}</textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Rad. Reports</label>
                                            <div class="col-md-8">
                                        <textarea name="baseline_doctor_rad" id="baseline_doctor_rad"
                                                  class="form-control"
                                                  rows="3"
                                                  placeholder="Rad. Reports">{{isset($one_baseline_doctor->baseline_doctor_rad)?$one_baseline_doctor->baseline_doctor_rad:''}}</textarea>
                                            </div>
                                        </div>
                                        <h3 class="form-section font-red"> Psychological Diagnose </h3>
                                        <hr/>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Diagnose
                                            </label>

                                            <div class="col-md-6">
                                                <select name="psychologic_diagnose"
                                                        class="form-control  select2-multiple" multiple disabled>
                                                    <option value="">Select...</option>
                                                    <?php
                                                    $selected = '';
                                                    foreach ($psychologic_diagnose_list as $raw) {
                                                        $selected = '';
                                                        if ((isset($psy_diagnostic)))
                                                            foreach ($psy_diagnostic as $raw2) {
                                                                if ($raw->id == $raw2->diagnostic_id) {
                                                                    $selected = 'selected="selected"';
                                                                }
                                                            }
                                                        echo '<option value="' . $raw->id . '" ' . $selected . '>' . $raw->lookup_details . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3">
                                                If stump pain. Describe level of amputation and distrbutionof
                                                pain
                                            </label>
                                            <div class="col-md-6">
                                                <input type="text" name="neuro_stump_distribution_of_pain_dr"
                                                       class="form-control"
                                                       value="{{isset($one_baseline_doctor->neuro_stump_distribution_of_pain)?$one_baseline_doctor->neuro_stump_distribution_of_pain:''}}"
                                                /></div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">
                                                If phantom pain. Describe level of amputation and type of PLP
                                            </label>
                                            <div class="col-md-6">
                                                <input type="text" name="neuro_phantom_type_of_plp_dr"
                                                       class="form-control"
                                                       value="{{isset($one_baseline_doctor->neuro_phantom_type_of_plp)?$one_baseline_doctor->neuro_phantom_type_of_plp:''}}"
                                                /></div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">
                                                Other specified findings on the neurological exam
                                            </label>
                                            <div class="col-md-6">
                                                <input type="text" name="neuro_other_finding_dr"
                                                       class="form-control"
                                                       value="{{isset($one_baseline_doctor->neuro_other_finding)?$one_baseline_doctor->neuro_other_finding:''}}"
                                                />
                                            </div>
                                        </div>
                                        <h3 class="form-section font-green"> Treatment Goals </h3>
                                        <hr/>
                                        <div class="alert alert-warning">
                                            Please write down up to <strong> five IMPORTANT activities </strong>you
                                            are unable to perform or are having difficulty with as a result of
                                            your pain
                                            injury i.e. putting socks on. After you have identified these
                                            activities,
                                            rate
                                            the current level of difficulty associated with each activity on a
                                            scale
                                            from 0 to 10. "0" represents “unable to perform”, and "10"
                                            represents “able
                                            to
                                            perform at prior level”.
                                        </div>
                                        <div class="alert alert-success alert-doc-success-goal display-hide">
                                            <button class="close" data-close="alert"></button>
                                            Your form validation is successful!
                                        </div>
                                        <div class="alert alert-danger alert-doc-danger-goal display-hide">
                                            <button class="close" data-close="alert"></button>
                                            You have some form errors. Please check below.
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-scrollable table-scrollable-borderless">
                                                    <table class="table table-striped table-hover  table-advance">
                                                        <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Goal</th>
                                                            <th>Goal Score</th>
                                                            <th>Current Score</th>
                                                        </tr>
                                                        <tr class="uppercase">
                                                            <td> #</td>
                                                            <td>
                                                                <div class="form-group">
                                                                    <input type="text"
                                                                           name="dr_baseline_nurse_goal"
                                                                           id="dr_baseline_nurse_goal"
                                                                           class="form-control"/>
                                                                </div>
                                                            </td>

                                                            <td>
                                                                <select id="dr_baseline_nurse_goal_score"
                                                                        name="dr_baseline_nurse_goal_score"
                                                                        class="form-control select2">
                                                                    <option value="0">0</option>
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
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select id="dr_baseline_nurse_current_goal_score"
                                                                        name="baseline_nurse_current_goal_score"
                                                                        class="form-control select2">
                                                                    <option value="0">0</option>
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
                                                                </select>
                                                            </td>
                                                            <td>
                                                            @if($painFile_statusId==17 && auth()->user()->id!=100)<!-- 17=Active file,100=guest user -->
                                                                <button type="button"
                                                                        class="btn btn-success btn-icon-only"
                                                                        onclick="save_doctor_treatment_goals();" {!!  $baselineDoctor_style !!}>
                                                                    <i
                                                                            class="fa fa-plus fa-fw"></i>
                                                                </button>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="tbtreatment_goals_dr">
                                                        @php
                                                            echo $treatment_doc_goals_data;
                                                        @endphp
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="alert alert-info">
                                            <strong>Info!</strong> Total score = sum of the activity
                                            scores/number
                                            of
                                            activities. <br/>
                                            Minimum detectable change for average score = 2 points. <br/>
                                        </div>

                                        <h3 class="form-section font-green"> Treatment choice(s) </h3>
                                        <hr/>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Physical treatment

                                            </label>
                                            <div class="col-md-6">
                                                <select id="physical_treatment" name="physical_treatment"
                                                        class="form-control select2"
                                                        onchange="show_physical_treatment();">
                                                    <option value="">Select...</option>
                                                    <?php
                                                    $selected = '';
                                                    foreach ($physical_treatment_list as $raw) {
                                                        $selected = '';
                                                        if (isset($one_baseline_doctor->physical_treatment) && $raw->id == $one_baseline_doctor->physical_treatment)
                                                            $selected = 'selected="selected"';

                                                        echo '<option value="' . $raw->id . '" ' . $selected . '>' . $raw->lookup_details . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group" id="dvspecifyphysicaltreatment">
                                            <label class="control-label col-md-3">
                                                If other, specify
                                            </label>
                                            <div class="col-md-6">
                                                <input type="text" name="specify_physical_treatment"
                                                       class="form-control"
                                                       value="{{isset($one_baseline_doctor->specify_physical_treatment)?$one_baseline_doctor->specify_physical_treatment:''}}"
                                                /></div>
                                        </div>
                                        <h3 class="form-section font-green"> Qutenza Treatment </h3>
                                        <hr/>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Need Qutenza Treatment

                                            </label>
                                            <div class="col-md-6">
                                                <select class="form-control need_qutenza"
                                                        name="need_qutenza_doctor" id="need_qutenza_doctor">
                                                    <option value="">Select...</option>
                                                    <option value="0">No</option>
                                                    <option value="1">Yes</option>
                                                </select>
                                            </div>
                                        @if($painFile_statusId==17 && auth()->user()->id!=100)<!-- 17=Active file,100=guest user -->
                                            <div class="col-md-2">
                                                <button type="button" class="btn fa fa-plus green"
                                                        onclick="qutenza_request('doctor');" {!!  $baselineDoctor_style !!}>
                                                </button>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-scrollable table-scrollable-borderless">
                                                    <table class="table table-striped table-hover  table-advance">
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
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Pharmacological Treatment

                                            </label>
                                            <div class="col-md-6">
                                                <select id="pharmacological_treatment"
                                                        name="pharmacological_treatment"
                                                        class="form-control select2"
                                                        onchange="show_dvPharmacologicalTreatment()">
                                                    <option value="">Select...</option>
                                                    <option value="1" @php if(isset($one_baseline_doctor->pharmacological_treatment)&& $one_baseline_doctor->pharmacological_treatment==1) echo 'selected="selected"'; @endphp>
                                                        Yes
                                                    </option>
                                                    <option value="0" @php if(isset($one_baseline_doctor->pharmacological_treatment)&& $one_baseline_doctor->pharmacological_treatment==0) echo 'selected="selected"'; @endphp>
                                                        No
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row" id="dvPharmacologicalTreatmentTable">
                                            <div class="alert alert-danger alert-danger-pharmacological display-hide">
                                                <button class="close" data-close="alert"></button>
                                                You have some form errors. Please check below.
                                            </div>
                                            <div class="alert alert-success alert-success-pharmacological display-hide">
                                                <button class="close" data-close="alert"></button>
                                                Your form validation is successful!
                                            </div>
                                            <div class="col-md-12">
                                                <div class="table-scrollable table-scrollable-borderless">
                                                    <table class="table table-striped table-hover  table-advance">
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

                                                        </tr>
                                                        <tr class="uppercase">
                                                            <td> #</td>
                                                            <td width="40%">
                                                                <div class="form-group col-md-12">
                                                                    <select id="treatment_choice_drug_id"
                                                                            name="treatment_choice_drug_id"
                                                                            class="form-control select2"
                                                                            onchange="get_active_batch_price();">
                                                                        <option value="">Select...</option>
                                                                        <?php

                                                                        foreach ($drug_item as $raw)
                                                                            echo '<option value="' . $raw->id . '">' . $raw->item_scientific_name . '</option>';
                                                                        ?>

                                                                    </select>
                                                                </div>
                                                                <input type="hidden" name="drug_price"
                                                                       id="drug_price" value="{{''}}"/>
                                                                <input type="hidden" name="batch_id"
                                                                       id="batch_id" value="{{''}}"/>
                                                            </td>
                                                            <td>

                                                                <input type="text" name="dosage"
                                                                       id="dosage"
                                                                       class="form-control input-xsmall drug"
                                                                       placeholder="Dosage"
                                                                       onchange="cal_drug_cost();"/>

                                                            </td>
                                                            <td>

                                                                <input type="text" name="frequency"
                                                                       id="frequency"
                                                                       class="form-control input-xsmall drug"
                                                                       placeholder="Frequency"
                                                                       onchange="cal_drug_cost();"/>

                                                            </td>
                                                            <td>

                                                                <input type="text" name="duration"
                                                                       id="duration"
                                                                       class="form-control input-xsmall drug"
                                                                       placeholder="Duration"
                                                                       onchange="cal_drug_cost();"/>

                                                            </td>
                                                            <td>

                                                                <input type="text" name="quantity"
                                                                       id="quantity"
                                                                       class="form-control input-xsmall"
                                                                       placeholder="quantity" value="0.0"
                                                                       disabled/>

                                                            </td>
                                                            <td>

                                                                <input type="text" name="drug_cost"
                                                                       id="drug_cost"
                                                                       class="form-control input-xsmall"
                                                                       placeholder="quantity" value="0.0"
                                                                       disabled/>

                                                            </td>
                                                            <td width="25%">
                                                                <input type="text"
                                                                       id="drug_comments"
                                                                       name="drug_comments"
                                                                       class="form-control"
                                                                       placeholder="Comments"/>
                                                            </td>
                                                            <td>
                                                            @if($painFile_statusId==17 && auth()->user()->id!=100)<!-- 17=Active file,100=guest user -->

                                                                <span class="input-group-btn"><button
                                                                            class="btn btn-success  btn-icon-only"
                                                                            type="button"
                                                                            onclick="add_treatment_choice_drug()"  {!!  $baselineDoctor_style !!}>
                                                                                     <i class="fa fa-plus fa-fw"></i></button></span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="tbtreatmentChoise">
                                                        @php
                                                            echo $treatmentChoice_data
                                                        @endphp
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3">No treatment offered due to

                                            </label>
                                            <div class="col-md-6">
                                                <select class="form-control" id="no_treatment_offered_due_to"
                                                        name="no_treatment_offered_due_to"
                                                        onchange="show_specify_No_treatment_offered_due_to()">
                                                    <option value="">Select...</option>
                                                    <?php
                                                    $selected = '';
                                                    foreach ($no_treatment_offered_list as $raw) {
                                                        $selected = '';
                                                        if (isset($one_baseline_doctor->no_treatment_offered_due_to) && $raw->id == $one_baseline_doctor->no_treatment_offered_due_to)
                                                            $selected = 'selected="selected"';
                                                        echo '<option value="' . $raw->id . '" ' . $selected . '>' . $raw->lookup_details . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div id="dvSpecifyNotreatmentOffered" class="form-group hide">
                                            <label class="control-label col-md-3">Specify other
                                            </label>
                                            <div class="col-md-6">
                                                <input type="text" name="specify_no_treatment_offered_due_to"
                                                       id="specify_no_treatment_offered_due_to"
                                                       class="form-control"
                                                       value="{{isset($one_baseline_doctor->specify_no_treatment_offered_due_to)?$one_baseline_doctor->specify_no_treatment_offered_due_to:''}}"
                                                /></div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Other Treatments
                                            </label>
                                            <div class="col-md-6">
                                                <input type="text" name="other_treatments" class="form-control"
                                                       value="{{isset($one_baseline_doctor->other_treatments)?$one_baseline_doctor->other_treatments:''}}"
                                                /></div>
                                        </div>

                                        <h3 class="form-section font-green"> Projects </h3>
                                        <hr/>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Include Patient in project

                                            </label>
                                            <div class="col-md-6">
                                                <select class="form-control" id="project_id"
                                                        name="project_id">
                                                    <option value="">Select...</option>
                                                    <?php
                                                    $selected = '';
                                                    foreach ($project_list as $raw) {

                                                        echo '<option value="' . $raw->id . '">' . $raw->project_name . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        @if($painFile_statusId==17 && auth()->user()->id!=100)<!-- 17=Active file,100=guest user -->
                                            <div class="col-md-2">
                                                <a data-toggle="modal" href="#add_project_modal"
                                                   class="btn fa fa-plus green" onclick="get_project_info();">
                                                </a>
                                            </div>
                                            @endif
                                        </div>
                                        <hr/>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-scrollable table-scrollable-borderless">
                                                    <table class="table table-striped table-hover  table-advance">
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
                                                                Answer
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
                                                        <tbody class="tbpatient_project" id="tbpatient_project">
                                                        @php

                                                            echo $patient_project_datatable;
                                                        @endphp
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <h3 class="form-section font-green"> Doctor Messages & Notes </h3>
                                        <hr/>
                                        <div class="alert alert-success alert-message-doctor display-hide">
                                            <button class="close" data-close="alert"></button>
                                            Your form validation is successful!
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Messages
                                            </label>
                                            <div class="col-md-7">
                                                <input type="text" id="doctor_message" name="doctor_message"
                                                       class="form-control"
                                                       value="{{''}}"/>
                                            </div>
                                        @if($painFile_statusId==17 && auth()->user()->id!=100)<!-- 17=Active file,100=guest user -->
                                            <div class="col-md-2">
                                                <button type="button" class="btn fa fa-plus green"
                                                        onclick="set_message(1,1);" {!!  $baselineDoctor_style !!}>
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
                                                        class="btn btn-success" {!!  $baselineDoctor_style !!}>
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
                                <!-- END FORM-->
                                </div>
                                <div class="tab-pane" id="tab_1_1_3">
                                    <!-- BEGIN FORM-->
                                    {{Form::open(['url'=>url('baselinePharm'),'class'=>'form-horizontal','method'=>"post","id"=>'baseline_pharm_form'])}}
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
                                            <div class="col-md-6">
                                                <input class="form-control date-picker" type="text"
                                                       name="visit_date_pharmacist"
                                                       data-date-format="yyyy-mm-dd"
                                                       value="{{isset($one_baseline_pharmacist->visit_date)?$one_baseline_pharmacist->visit_date:''}}"/>
                                                <span class="help-block"> Select date </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Laboratory values outside
                                                reference values
                                                that might effect treatment?

                                            </label>
                                            <div class="col-md-6">
                                                <select id="laboratory_outside_reference"
                                                        name="laboratory_outside_reference"
                                                        class="form-control select2"
                                                        onchange="show_laboratory_specify()">
                                                    <option value="">Select...</option>
                                                    <option value="0" @php if(isset($one_baseline_pharmacist->laboratory_outside_reference)&& $one_baseline_pharmacist->laboratory_outside_reference==0) echo 'selected="selected"'; @endphp>
                                                        No
                                                    </option>
                                                    <option value="1" @php if(isset($one_baseline_pharmacist->laboratory_outside_reference)&& $one_baseline_pharmacist->laboratory_outside_reference==1) echo 'selected="selected"'; @endphp>
                                                        Yes
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div id="dvSpecifyLab" class="form-group hide">
                                            <label class="control-label col-md-3">
                                                Specify (e.g. GFR 40)
                                            </label>
                                            <div class="col-md-6">
                                                <input type="text" id="laboratory_specify"
                                                       name="laboratory_specify"
                                                       class="form-control"
                                                       value="{{isset($one_baseline_pharmacist->laboratory_specify)?$one_baseline_pharmacist->laboratory_specify:''}}"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Interactions?

                                            </label>
                                            <div class="col-md-6">
                                                <select id="interactions" name="interactions"
                                                        class="form-control select2" onchange="show_which()">
                                                    <option value="">Select...</option>
                                                    <option value="0" @php if(isset($one_baseline_pharmacist->interactions)&& $one_baseline_pharmacist->interactions==0) echo 'selected="selected"'; @endphp>
                                                        No
                                                    </option>
                                                    <option value="1" @php if(isset($one_baseline_pharmacist->interactions)&& $one_baseline_pharmacist->interactions==1) echo 'selected="selected"'; @endphp>
                                                        Yes
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div id="dvWhich" class="form-group hide">
                                            <label class="control-label col-md-3">
                                                Which?
                                            </label>
                                            <div class="col-md-6">
                                                <input type="text" id="which_interactions"
                                                       name="which_interactions"
                                                       class="form-control"
                                                       value="{{isset($one_baseline_pharmacist->which_interactions)?$one_baseline_pharmacist->which_interactions:''}}"/>
                                            </div>
                                        </div>
                                        <h3 class="form-section font-green"> Treatment choice(s)</h3>
                                        <hr/>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="alert alert-danger alert-danger-pharm display-hide">
                                                    <button class="close" data-close="alert"></button>
                                                    You have some form errors. Please check below.
                                                </div>
                                                <div class="alert alert-success alert-success-pharm display-hide">
                                                    <button class="close" data-close="alert"></button>
                                                    Your form validation is successful!
                                                </div>
                                                <div class="table-scrollable table-scrollable-borderless">
                                                    <table class="table table-striped table-hover  table-advance">
                                                        <thead>
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
                                                            <!--   <th>Concentration</th>-->
                                                            <th>Dosage</th>
                                                            <th>Frequency</th>
                                                            <th>Duration</th>
                                                            <th>َQuantity</th>
                                                            <th>Cost</th>
                                                            <th>Comments</th>
                                                        </tr>
                                                        <tr class="uppercase">
                                                            <td></td>
                                                            <td> #</td>
                                                            <td width="40%">
                                                                <div class="form-group col-md-12">
                                                                    <select id="treatment_choice_drug_id_pharm"
                                                                            name="treatment_choice_drug_id_pharm"
                                                                            class="form-control select2"
                                                                            onchange="get_active_batch_price_pharm();">
                                                                        <option value="">Select...</option>
                                                                        <?php

                                                                        foreach ($drug_item as $raw)
                                                                            echo '<option value="' . $raw->id . '">' . $raw->item_scientific_name . '</option>';
                                                                        ?>

                                                                    </select>
                                                                </div>
                                                                <input type="hidden" name="drug_price_pharm"
                                                                       id="drug_price_pharm" value="{{''}}"/>
                                                                <input type="hidden" name="batch_id_pharm"
                                                                       id="batch_id_pharm" value="{{''}}"/>
                                                            </td>
                                                            <td>

                                                                <input type="text" name="dosage_pharm"
                                                                       id="dosage_pharm"
                                                                       class="form-control input-xsmall drug"
                                                                       placeholder="Dosage"
                                                                       onchange="cal_drug_cost_insert_pharm();"/>

                                                            </td>
                                                            <td>

                                                                <input type="text" name="frequency_pharm"
                                                                       id="frequency_pharm"
                                                                       class="form-control input-xsmall drug"
                                                                       placeholder="Frequency"
                                                                       onchange="cal_drug_cost_insert_pharm();"/>

                                                            </td>
                                                            <td>

                                                                <input type="text" name="duration_pharm"
                                                                       id="duration_pharm"
                                                                       class="form-control input-xsmall drug"
                                                                       placeholder="Duration"
                                                                       onchange="cal_drug_cost_insert_pharm();"/>

                                                            </td>
                                                            <td>

                                                                <input type="text" name="quantity_pharm"
                                                                       id="quantity_pharm"
                                                                       class="form-control input-xsmall"
                                                                       placeholder="quantity" value="0.0"
                                                                       disabled/>

                                                            </td>
                                                            <td>

                                                                <input type="text" name="drug_cost_pharm"
                                                                       id="drug_cost_pharm"
                                                                       class="form-control input-xsmall"
                                                                       placeholder="quantity" value="0.0"
                                                                       disabled/>

                                                            </td>
                                                            <td width="25%">
                                                                <input type="text"
                                                                       id="drug_comments_pharm"
                                                                       name="drug_comments_pharm"
                                                                       class="form-control"
                                                                       placeholder="Comments"/>
                                                            </td>
                                                            <td>
                                                            @if($painFile_statusId==17 && auth()->user()->id!=100)<!-- 17=Active file,100=guest user -->

                                                                <span class="input-group-btn"><button
                                                                            class="btn btn-success  btn-icon-only"
                                                                            type="button"
                                                                            onclick="add_treatment_choice_drug_pharm()">
                                                                                     <i class="fa fa-plus fa-fw"></i></button></span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tbody id="tbTreatmentChoisePharm">
                                                        @php
                                                            echo $treatmentChoice_data_pharmacist;
                                                        @endphp
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @if($painFile_statusId==17 && auth()->user()->id!=100 && auth()->user()->user_type_id==11)<!-- 17=Active file,100=guest user -->
                                                <button type="button" class="btn btn-success"
                                                        id="change_order_status_btn"
                                                        onclick="change_baseline_drug_order_status();">
                                                    Confirm
                                                </button>
                                                @endif
                                            </div>
                                        </div>
                                        <hr/>
                                        <h3 class="form-section font-green"> Qutenza Treatment </h3>
                                        <hr/>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Need Qutenza Treatment

                                            </label>
                                            <div class="col-md-6">
                                                <select class="form-control need_qutenza"
                                                        name="need_qutenza_pharmacist"
                                                        id="need_qutenza_pharmacist">
                                                    <option value="">Select...</option>
                                                    <option value="0">No</option>
                                                    <option value="1">Yes</option>
                                                </select>
                                            </div>
                                        @if($painFile_statusId==17 && auth()->user()->id!=100)<!-- 17=Active file,100=guest user -->
                                            <div class="col-md-2">
                                                <button type="button" class="btn fa fa-plus green"
                                                        onclick="qutenza_request('pharmacist');">
                                                </button>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-scrollable table-scrollable-borderless">
                                                    <table class="table table-striped table-hover  table-advance">
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
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Other Considereations
                                            </label>
                                            <div class="col-md-8">
                                                <input type="text" id="other_considereations"
                                                       name="other_considereations" class="form-control"
                                                       value="{{isset($one_baseline_pharmacist->other_considereations)?$one_baseline_pharmacist->other_considereations:''}}"/>
                                            </div>
                                        </div>
                                        <h3 class="form-section font-green"> Projects </h3>
                                        <hr/>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Include Patient in project

                                            </label>
                                            <div class="col-md-6">
                                                <select class="form-control" id="project_id"
                                                        name="project_id">
                                                    <option value="">Select...</option>
                                                    <?php
                                                    $selected = '';
                                                    foreach ($project_list as $raw) {

                                                        echo '<option value="' . $raw->id . '">' . $raw->project_name . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        @if($painFile_statusId==17 && auth()->user()->id!=100)<!-- 17=Active file,100=guest user -->
                                            <!--                                            <div class="col-md-2">
                                                                                            <button type="button" class="btn fa fa-plus green"
                                                                                                    onclick="add_patient_to_project();">
                                                                                            </button>
                                                                                        </div>-->
                                            @endif
                                        </div>
                                        <hr/>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-scrollable table-scrollable-borderless">
                                                    <table class="table table-striped table-hover  table-advance">
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
                                                                Answer
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
                                                        <tbody class="tbpatient_project" id="tbpatient_project">
                                                        @php

                                                            echo $patient_project_datatable;
                                                        @endphp
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <h3 class="form-section font-green"> Pharmacist Messages & Notes </h3>
                                        <hr/>
                                        <div class="alert alert-success alert-message-pharm display-hide">
                                            <button class="close" data-close="alert"></button>
                                            Your form validation is successful!
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Messages
                                            </label>
                                            <div class="col-md-7">
                                                <input type="text" id="pharmacist_message"
                                                       name="pharmacist_message"
                                                       class="form-control"
                                                       value="{{''}}"/>
                                            </div>
                                        @if($painFile_statusId==17 && auth()->user()->id!=100)<!-- 17=Active file,100=guest user -->
                                            <div class="col-md-2">
                                                <button type="button" class="btn fa fa-plus green"
                                                        onclick="set_message(3,1);">
                                                </button>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                @if($painFile_statusId==17 && auth()->user()->id!=100)<!-- 17=Active file,100=guest user -->
                                    <div class="form-actions">
                                        <div class="row">
                                            <div class="col-md-offset-3 col-md-9">
                                                @if(isset($one_baseline_pharmacist->created_by) &&  $one_baseline_pharmacist->created_by==auth()->user()->id)
                                                    <button type="submit" class="btn btn-success">Submit
                                                    </button>
                                                @elseif(!isset($one_baseline_pharmacist->created_by))
                                                    <button type="submit" class="btn btn-success">Submit
                                                    </button>
                                                @endif
                                                <button type="button" class="btn grey-salsa btn-outline">Cancel
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                {{Form::close()}}
                                <!-- END FORM-->
                                </div>
                                <div class="tab-pane" id="tab_1_1_4">
                                <?php
                                $editable = "";
                                if (auth()->user()->user_type_id != 572)
                                    $editable = "disabled";

                                ?>
                                <!-- BEGIN FORM-->
                                    {{Form::open(['url'=>url('baselinePsychology'),'class'=>'form-horizontal','method'=>"post","id"=>'baseline_psychology_form'])}}
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
                                            <div class="col-md-6">
                                                <input class="form-control date-picker" type="text"
                                                       name="visit_date_psychology"
                                                       data-date-format="yyyy-mm-dd" {{$editable}}
                                                       value="{{isset($one_psychologist->visit_date)?$one_psychologist->visit_date:''}}"/>

                                                <span class="help-block"> Select date </span>
                                            </div>
                                        </div>
                                        <h3 class="form-section font-green"> DASS-21
                                        @if($painFile_statusId==17 && auth()->user()->id!=100 && auth()->user()->user_type_id==572)<!-- 17=Active file,100=guest user -->
                                            <a data-toggle="modal" href="#dass_eval_modal" {{$editable}}
                                            class="btn fa fa-plus green" onclick="get_patient_dass_eval_data();">
                                            </a>
                                            @endif</h3>
                                        <hr/>
                                        <div class="alert alert-info">
                                            <strong>Info!</strong> (DASS-21): The Depression, Anxiety and Stress
                                            Scale 21 Items is a set of three self-report scales designed to measure the
                                            emotional states of depression, anxiety and stress.
                                        </div>
                                        <?php
                                        $degree_class = array(0 => 'label label-success', 1 => 'label label-warning', 2 => 'label bg-yellow-casablanca bg-font-yellow-casablanca', 3 => 'label label-danger', 4 => 'label bg-red-thunderbird bg-font-red-thunderbird');
                                        $degree_name = array(0 => 'Normal', 1 => 'Mild', 2 => 'Moderate', 3 => 'Severe', 4 => 'Extramely Severe');
                                        ?>
                                        <div class="form-group">
                                            <label class="control-label col-md-3"> Depression Score</label>
                                            <div class="col-md-2">
                                                <input type="hidden" name="depression_degree" id="depression_degree"
                                                       {{$editable}}
                                                       value="{{isset($one_psychologist->depression_degree)?$one_psychologist->depression_degree:''}}"/>
                                                <input type="text" name="depression_score" class="form-control"
                                                       {{$editable}}
                                                       id="depression_score"
                                                       value="{{isset($one_psychologist->depression_score)?$one_psychologist->depression_score:''}}"
                                                       readonly/>

                                            </div>
                                            <span class="{{isset($one_psychologist->depression_degree)?$degree_class[$one_psychologist->depression_degree]:''}}"
                                                  id="depression_class"> {{isset($one_psychologist->depression_degree)?$degree_name[$one_psychologist->depression_degree]:''}} </span>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3"> Anxiety Score</label>
                                            <div class="col-md-2">
                                                <input type="hidden" name="anxiety_degree" id="anxiety_degree"
                                                       {{$editable}}
                                                       value="{{isset($one_psychologist->anxiety_degree)?$one_psychologist->anxiety_degree:''}}"/>
                                                <input type="text" name="anxiety_score" class="form-control"
                                                       {{$editable}}
                                                       id="anxiety_score"
                                                       value="{{isset($one_psychologist->anxiety_score)?$one_psychologist->anxiety_score:''}}"
                                                       readonly/>

                                            </div>
                                            <span class="{{isset($one_psychologist->anxiety_degree)?$degree_class[$one_psychologist->anxiety_degree]:''}}"
                                                  id="anxiety_class"> {{isset($one_psychologist->anxiety_degree)?$degree_name[$one_psychologist->anxiety_degree]:''}} </span>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3"> Stress</label>
                                            <div class="col-md-2">
                                                <input type="hidden" class="form-control" name="stress_degree"
                                                       {{$editable}}
                                                       id="stress_degree"
                                                       value="{{isset($one_psychologist->stress_degree)?$one_psychologist->stress_degree:''}}"/>
                                                <input type="text" name="stress_score" class="form-control"
                                                       {{$editable}}
                                                       id="stress_score"
                                                       value="{{isset($one_psychologist->stress_score)?$one_psychologist->stress_score:''}}"
                                                       readonly/>
                                            </div>
                                            <span class="{{isset($one_psychologist->stress_degree)?$degree_class[$one_psychologist->stress_degree]:''}}"
                                                  id="stress_class"> {{isset($one_psychologist->stress_degree)?$degree_name[$one_psychologist->stress_degree]:''}} </span>
                                        </div>
                                        <hr/>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Comments</label>
                                            <div class="col-md-8">
                                                  <textarea name="physical_exam" id="physical_exam" {{$editable}}
                                                  class="form-control"
                                                            rows="6"
                                                            placeholder="Physical examination">{{isset($one_psychologist->physical_exam)?$one_psychologist->physical_exam:''}}</textarea>
                                            </div>
                                        </div>
                                        <h3 class="form-section font-green"> Mental Health Check-In </h3>
                                        <hr/>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Do you have any eating disorder?
                                            </label>
                                            <div class="col-md-6">
                                                <select id="eating_disorder" name="eating_disorder"
                                                        class="form-control select2" {{$editable}}>
                                                    <option value="">Select...</option>
                                                    <option value="1" @php if(isset($one_mental_health->eating_disorder)&& $one_mental_health->eating_disorder==1) echo 'selected="selected"'; @endphp>
                                                        Yes
                                                    </option>
                                                    <option value="0" @php if(isset($one_mental_health->eating_disorder)&& $one_mental_health->eating_disorder==0) echo 'selected="selected"'; @endphp>
                                                        No
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Do you suffer from sleep disturbances?
                                            </label>
                                            <div class="col-md-6">
                                                <select id="sleep_disturbances" name="sleep_disturbances"
                                                        class="form-control select2" {{$editable}}>
                                                    <option value="">Select...</option>
                                                    <option value="1" @php if(isset($one_mental_health->sleep_disturbances)&& $one_mental_health->sleep_disturbances==1) echo 'selected="selected"'; @endphp>
                                                        Yes
                                                    </option>
                                                    <option value="0" @php if(isset($one_mental_health->sleep_disturbances)&& $one_mental_health->sleep_disturbances==0) echo 'selected="selected"'; @endphp>
                                                        No
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Is there a psychopath in the family?
                                            </label>
                                            <div class="col-md-6">
                                                <select id="psychopath_in_family" name="psychopath_in_family"
                                                        class="form-control select2" {{$editable}}
                                                        onchange="show_psychopath_family_relation();">
                                                    <option value="">Select...</option>
                                                    <option value="1" @php if(isset($one_mental_health->psychopath_in_family)&& $one_mental_health->psychopath_in_family==1) echo 'selected="selected"'; @endphp>
                                                        Yes
                                                    </option>
                                                    <option value="0" @php if(isset($one_mental_health->psychopath_in_family)&& $one_mental_health->psychopath_in_family==0) echo 'selected="selected"'; @endphp>
                                                        No
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div id="dv_family_relation" class="form-group">
                                            <label class="control-label col-md-3">Family relationship
                                            </label>
                                            <div class="col-md-6">
                                                <input type="text" id="family_relationship" name="family_relationship"
                                                       class="form-control" {{$editable}}
                                                       value="{{isset($one_mental_health->family_relationship)?$one_mental_health->family_relationship:''}}"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Have you ever had psychological
                                                problems?
                                            </label>
                                            <div class="col-md-6">
                                                <select id="psychological_problems" name="psychological_problems"
                                                        class="form-control select2" {{$editable}}>
                                                    <option value="">Select...</option>
                                                    <option value="1" @php if(isset($one_mental_health->psychological_problems)&& $one_mental_health->psychological_problems==1) echo 'selected="selected"'; @endphp>
                                                        Yes
                                                    </option>
                                                    <option value="0" @php if(isset($one_mental_health->psychological_problems)&& $one_mental_health->psychological_problems==0) echo 'selected="selected"'; @endphp>
                                                        No
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Do you have any social problem?
                                            </label>
                                            <div class="col-md-6">
                                                <select id="social_problem" name="social_problem"
                                                        class="form-control select2" {{$editable}}>
                                                    <option value="">Select...</option>
                                                    <option value="1" @php if(isset($one_mental_health->social_problem)&& $one_mental_health->social_problem==1) echo 'selected="selected"'; @endphp>
                                                        Yes
                                                    </option>
                                                    <option value="0" @php if(isset($one_mental_health->social_problem)&& $one_mental_health->social_problem==0) echo 'selected="selected"'; @endphp>
                                                        No
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">What is the patient's ability to
                                                control his actions?
                                            </label>
                                            <div class="col-md-6">
                                                <select id="ability_control_actions" name="ability_control_actions"
                                                        class="form-control select2" {{$editable}}>
                                                    <option value="">Select...</option>
                                                    <?php
                                                    $selected = '';
                                                    foreach ($ability_control_list as $raw) {
                                                        $selected = '';
                                                        if (isset($one_mental_health->ability_control_actions) && $raw->id == $one_mental_health->ability_control_actions)
                                                            $selected = 'selected="selected"';
                                                        echo '<option value="' . $raw->id . '" ' . $selected . '>' . $raw->lookup_details . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">What is the patient's ability to
                                                control his words?
                                            </label>
                                            <div class="col-md-6">
                                                <select id="ability_control_words" name="ability_control_words"
                                                        class="form-control select2" {{$editable}}>
                                                    <option value="">Select...</option>
                                                    <?php
                                                    $selected = '';
                                                    foreach ($ability_control_list as $raw) {
                                                        $selected = '';
                                                        if (isset($one_mental_health->ability_control_words) && $raw->id == $one_mental_health->ability_control_words)
                                                            $selected = 'selected="selected"';
                                                        echo '<option value="' . $raw->id . '" ' . $selected . '>' . $raw->lookup_details . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Do you have suicidal thoughts?
                                            </label>
                                            <div class="col-md-6">
                                                <select id="suicidal_thoughts" name="suicidal_thoughts"
                                                        class="form-control select2" {{$editable}}>
                                                    <option value="">Select...</option>
                                                    <option value="1" @php if(isset($one_mental_health->suicidal_thoughts)&& $one_mental_health->suicidal_thoughts==1) echo 'selected="selected"'; @endphp>
                                                        Yes
                                                    </option>
                                                    <option value="0" @php if(isset($one_mental_health->suicidal_thoughts)&& $one_mental_health->suicidal_thoughts==0) echo 'selected="selected"'; @endphp>
                                                        No
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Have you been attacked or bullied?
                                            </label>
                                            <div class="col-md-6">
                                                <select id="attacked_or_bullied" name="attacked_or_bullied"
                                                        class="form-control select2" {{$editable}}>
                                                    <option value="">Select...</option>
                                                    <option value="1" @php if(isset($one_mental_health->attacked_or_bullied)&& $one_mental_health->attacked_or_bullied==1) echo 'selected="selected"'; @endphp>
                                                        Yes
                                                    </option>
                                                    <option value="0" @php if(isset($one_mental_health->attacked_or_bullied)&& $one_mental_health->attacked_or_bullied==0) echo 'selected="selected"'; @endphp>
                                                        No
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Are you experiencing bad dreams?
                                            </label>
                                            <div class="col-md-6">
                                                <select id="bad_dreams" name="bad_dreams"
                                                        class="form-control select2" {{$editable}}>
                                                    <option value="">Select...</option>
                                                    <option value="1" @php if(isset($one_mental_health->bad_dreams)&& $one_mental_health->bad_dreams==1) echo 'selected="selected"'; @endphp>
                                                        Yes
                                                    </option>
                                                    <option value="0" @php if(isset($one_mental_health->bad_dreams)&& $one_mental_health->bad_dreams==0) echo 'selected="selected"'; @endphp>
                                                        No
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Do you take opioids?
                                            </label>
                                            <div class="col-md-6">
                                                <select id="opioids" name="opioids"
                                                        class="form-control select2" {{$editable}}>
                                                    <option value="">Select...</option>
                                                    <option value="1" @php if(isset($one_mental_health->opioids)&& $one_mental_health->opioids==1) echo 'selected="selected"'; @endphp>
                                                        Yes
                                                    </option>
                                                    <option value="0" @php if(isset($one_mental_health->opioids)&& $one_mental_health->opioids==0) echo 'selected="selected"'; @endphp>
                                                        No
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <h3 class="form-section font-green"> Psychological Diagnose </h3>
                                        <hr/>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Diagnose
                                            </label>
                                            <div class="col-md-6">
                                                <select id="psychologic_diagnose" name="psychologic_diagnose[]"
                                                        class="form-control select2  select2-multiple"
                                                        multiple {{$editable}}>
                                                    <option value="">Select...</option>
                                                    <?php
                                                    $selected = '';
                                                    foreach ($psychologic_diagnose_list as $raw) {
                                                        $selected = '';
                                                        if ((isset($psy_diagnostic)))
                                                            foreach ($psy_diagnostic as $raw2) {
                                                                if ($raw->id == $raw2->diagnostic_id) {
                                                                    $selected = 'selected="selected"';
                                                                }
                                                            }
                                                        echo '<option value="' . $raw->id . '" ' . $selected . '>' . $raw->lookup_details . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Suggested actions
                                            </label>
                                            <div class="col-md-6">
                                                <select id="suggested_actions" name="suggested_actions"
                                                        class="form-control select2" {{$editable}}
                                                        onchange="show_other_suggested_actions()">
                                                    <option value="">Select...</option>
                                                    <?php
                                                    $selected = '';
                                                    foreach ($suggested_actions_list as $raw) {
                                                        $selected = '';
                                                        if (isset($one_psychologist->suggested_actions) && $raw->id == $one_psychologist->suggested_actions)
                                                            $selected = 'selected="selected"';
                                                        echo '<option value="' . $raw->id . '" ' . $selected . '>' . $raw->lookup_details . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div id="dvSuggestedActions" class="form-group ">
                                            <label class="control-label col-md-3">Other suggested actions
                                            </label>
                                            <div class="col-md-6">
                                                <input type="text" name="suggested_actions_others" {{$editable}}
                                                id="suggested_actions_others"
                                                       class="form-control"
                                                       value="{{isset($one_psychologist->suggested_actions_others)?$one_psychologist->suggested_actions_others:''}}"
                                                /></div>
                                        </div>

                                        <hr/>

                                        <h3 class="form-section font-green"> Psychologist Messages & Notes </h3>
                                        <hr/>
                                        <div class="alert alert-success alert-message-psychology display-hide">
                                            <button class="close" data-close="alert"></button>
                                            Your form validation is successful!
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3">Messages
                                            </label>
                                            <div class="col-md-7">
                                                <input type="text" id="psychology_message"
                                                       name="psychology_message" {{$editable}}
                                                       class="form-control"
                                                       value="{{''}}"/>
                                            </div>
                                        @if($painFile_statusId==17 && auth()->user()->id!=100)<!-- 17=Active file,100=guest user -->
                                            <div class="col-md-2">
                                                <button type="button" class="btn fa fa-plus green"
                                                        onclick="set_message(4,1);" {{$editable}}>
                                                </button>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    @if($painFile_statusId==17 && auth()->user()->id!=100)<!-- 17=Active file,100=guest user -->
                                    <div class="form-actions">
                                        <div class="row">
                                            <div class="col-md-offset-3 col-md-9">
                                                <button type="submit" class="btn btn-success" {{$editable}}>Submit
                                                </button>
                                                <a href="{{'painFile/view'}}" type="button" {{$editable}}
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
                    </div>
                </div>
                <!-- END VALIDATION STATES-->
            </div>
        </div>
        <!-- END PAGE BASE CONTENT -->
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
    <div class="modal fade bs-modal-lg" id="add_project_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="portlet box green-turquoise">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-gift"></i>Include Patient in Project
                            </div>
                            {{--<div class="tools">
                                <a href="javascript:;" class="collapse"> </a>
                                <a href="#portlet-config" data-toggle="modal" class="config"> </a>
                                <a href="javascript:;" class="reload"> </a>
                                <a href="javascript:;" class="remove"> </a>
                            </div>--}}
                        </div>
                        <div class="portlet-body ">
                            <form action="" class="form-horizontal" method="post" id="project_form">
                                <div class="form-body">
                                    <div class="alert alert-danger project-danger  display-hide">
                                        <button class="close"
                                                data-close="alert"></button>
                                        You have some form errors. Please check below.
                                    </div>
                                    <div class="alert alert-success project-success  display-hide">
                                        <button class="close"
                                                data-close="alert"></button>
                                        Your form validation is successful!
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Project name</label>
                                        <div class="col-md-10">
                                            <input name="project_name" type="text"
                                                   class="form-control project_name"
                                                   placeholder="project name" value="" readonly></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Question</label>
                                        <div class="col-md-8">
                                            <input id="project_question" name="project_question" type="text"
                                                   class="form-control project_question"
                                                   placeholder="Question" value="" readonly></div>

                                        <div class="col-md-2">
                                            <select class="form-control answer_project_question"
                                                    id="answer_project_question">
                                                <option value="">Select..</option>
                                                <option value="1">Yes</option>
                                                <option value="0">No</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Chart</label>
                                        <div class="col-md-4">
                                            <select class="form-control pain_project_chart"
                                                    id="pain_project_chart">
                                                <option value="">Select..</option>
                                                <?php
                                                foreach ($project_charts_list as $raw) {
                                                    echo '<option value="' . $raw->id . '">' . $raw->lookup_details . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Doctor Note</label>
                                        <div class="col-md-10">
                                                <textarea name="pain_project_note pain_project_chart"
                                                          id="pain_project_note"
                                                          class="form-control"
                                                          rows="3" placeholder="Graph or Note"></textarea>
                                        </div>
                                    </div>

                                </div>
                                <div class="form-actions">
                                    <div class="row">
                                        <div class="col-md-offset-3 col-md-9">
                                            <button type="button" class="btn green"
                                                    onclick="add_patient_to_project();" {!!  $baselineDoctor_style !!}>
                                                Save
                                            </button>
                                            <button type="button"
                                                    class="btn grey-salsa btn-outline"
                                                    data-dismiss="modal">
                                                Cancel
                                            </button>

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade bs-modal-lg" id="pcl_eval_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="portlet box green-turquoise">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-gift"></i>PCL-5
                            </div>
                            {{--<div class="tools">
                                <a href="javascript:;" class="collapse"> </a>
                                <a href="#portlet-config" data-toggle="modal" class="config"> </a>
                                <a href="javascript:;" class="reload"> </a>
                                <a href="javascript:;" class="remove"> </a>
                            </div>--}}
                        </div>
                        <div class="portlet-body ">
                            <form action="" class="form-horizontal" method="post" id="pcl_form">
                                <div class="form-body">
                                    <div class="alert alert-danger project-danger  display-hide">
                                        <button class="close"
                                                data-close="alert"></button>
                                        You have some form errors. Please check below.
                                    </div>
                                    <div class="alert alert-success project-success  display-hide">
                                        <button class="close"
                                                data-close="alert"></button>
                                        Your form validation is successful!
                                    </div>
                                    <div class="alert alert-info">
                                        <strong>Instructions!</strong>Below is a list of problems that people
                                        sometimes have in response to a very stressful experience.<br/>
                                        Please read each problem carefully and then circle one of the numbers to the
                                        right to indicate how much you have been bothered by that problem in the
                                        past month.
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-scrollable table-scrollable-borderless"
                                                 id="dv_pcl_eval">
                                            </div>
                                        </div>
                                    </div>


                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade bs-modal-lg" id="dass_eval_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="portlet box green-turquoise">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-gift"></i>DASS-21
                            </div>
                        </div>
                        <div class="portlet-body ">
                            <form action="" class="form-horizontal" method="post" id="pcl_form">
                                <div class="form-body">
                                    <div class="alert alert-danger project-danger  display-hide">
                                        <button class="close"
                                                data-close="alert"></button>
                                        You have some form errors. Please check below.
                                    </div>
                                    <div class="alert alert-success project-success  display-hide">
                                        <button class="close"
                                                data-close="alert"></button>
                                        Your form validation is successful!
                                    </div>
                                    <div class="alert alert-info">
                                        <strong>Info!</strong>Each of the three DASS-21 scales contains 7 items
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-scrollable table-scrollable-borderless"
                                                 id="dv_dass_eval">
                                            </div>
                                        </div>
                                    </div>


                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade bs-modal-lg" id="pharm_followup_project_modal" tabindex="-1" role="dialog"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="portlet box blue">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-gift"></i>Followup Patient
                            </div>
                            {{--<div class="tools">
                                <a href="javascript:;" class="collapse"> </a>
                                <a href="#portlet-config" data-toggle="modal" class="config"> </a>
                                <a href="javascript:;" class="reload"> </a>
                                <a href="javascript:;" class="remove"> </a>
                            </div>--}}
                        </div>
                        <div class="portlet-body ">
                            <form action="" class="form-horizontal" method="post" id="pharm_project_form">
                                <div class="form-body">
                                    <div class="alert alert-danger pharm-project-danger  display-hide">
                                        <button class="close"
                                                data-close="alert"></button>
                                        You have some form errors. Please check below.
                                    </div>
                                    <div class="alert alert-success pharm-project-success  display-hide">
                                        <button class="close"
                                                data-close="alert"></button>
                                        Your form validation is successful!
                                    </div>
                                    <input id="pain_project_id" name="pain_project_id" type="hidden"
                                           value="{{''}}">
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Project name</label>
                                        <div class="col-md-10">
                                            <input name="project_name" type="text"
                                                   class="form-control project_name"
                                                   placeholder="project name" value="" readonly></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Question</label>
                                        <div class="col-md-8">
                                            <input name="project_question" type="text"
                                                   class="form-control project_question"
                                                   placeholder="Question" value="" readonly>
                                        </div>

                                        <div class="col-md-2">
                                            <select class="form-control answer_project_question">
                                                <option value="">Select..</option>
                                                <option value="1">Yes</option>
                                                <option value="0">No</option>
                                                <option value="2">Partially</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Chart</label>
                                        <div class="col-md-4">
                                            <select class="form-control pain_project_chart"
                                                    id="pain_project_chart">
                                                <option value="">Select..</option>
                                                <?php
                                                foreach ($project_charts_list as $raw) {
                                                    echo '<option value="' . $raw->id . '">' . $raw->lookup_details . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Doctor Note</label>
                                        <div class="col-md-10">
                                                <textarea name="pain_project_note"
                                                          class="form-control pain_project_note"
                                                          rows="3" placeholder="Graph or Note"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Pharmacy Action</label>
                                        <div class="col-md-4">
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
                                        <div class="col-md-10">
                                                <textarea name="pain_project_note" id="pharm_project_note"
                                                          class="form-control pharm_project_note"
                                                          rows="3" placeholder="Drugs or Note"></textarea>
                                        </div>
                                    </div>

                                </div>
                                <div class="form-actions">
                                    <div class="row">
                                        <div class="col-md-offset-3 col-md-9">
                                            <button type="button" class="btn green"
                                                    onclick="add_pharm_followup_project();">
                                                Save
                                            </button>
                                            <button type="button"
                                                    class="btn grey-salsa btn-outline"
                                                    data-dismiss="modal">
                                                Close
                                            </button>

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade bs-modal-lg" id="project_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!--                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                    <h4 class="modal-title">Project Details</h4>
                                </div>-->
                <div class="modal-body">
                    <div class="portlet box green-turquoise">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-gift"></i>Project Details
                            </div>
                            {{--<div class="tools">
                                <a href="javascript:;" class="collapse"> </a>
                                <a href="#portlet-config" data-toggle="modal" class="config"> </a>
                                <a href="javascript:;" class="reload"> </a>
                                <a href="javascript:;" class="remove"> </a>
                            </div>--}}
                        </div>
                        <div class="portlet-body ">
                            <form action="" class="form-horizontal" method="post">
                                <div class="form-body">
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Project name</label>
                                        <div class="col-md-10">
                                            <input name="project_name" type="text"
                                                   class="form-control font-green-turquoise project_name"
                                                   placeholder="project name" value="" readonly></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Question</label>
                                        <div class="col-md-10">
                                                <textarea name="question"
                                                          class="form-control font-green-turquoise project_question"
                                                          rows="3" placeholder="Question" readonly></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Conclusion</label>
                                        <div class="col-md-10">
                                                <textarea name="conclusion" id="conclusion"
                                                          class="form-control font-green-turquoise"
                                                          rows="3" placeholder="Conclusion" readonly></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="multiple" class="control-label col-md-2">Consequence</label>
                                        <div class="col-md-10">
                                            <input id="consequence" name="consequence" type="text"
                                                   class="form-control font-green-turquoise"
                                                   placeholder="Consequence" value="" readonly></div>

                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-2">Consequence Details</label>
                                        <div class="col-md-10">
                                        <textarea name="consequence_detail" id="consequence_detail"
                                                  class="form-control font-green-turquoise"
                                                  rows="3" placeholder="Consequence Details" readonly></textarea>
                                        </div>
                                    </div>
                                    <!--      <div class="form-group last">
                                        <label class="control-label col-md-2">Symptoms</label>
                                        <div class="col-md-10">
                                        <textarea name="symptoms" id="symptoms" class="form-control font-green-turquoise"
                                      rows="3" placeholder="Symptoms" readonly></textarea>
                                        </div>
                                    </div>-->
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
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
                                                <div class="col-md-6 input-group">
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
                                                <div class="col-md-6">
                                                    <input type="number" min="0"
                                                           name="application_time"
                                                           id="application_time"
                                                           class="form-control" value=""/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Allodynia</label>
                                                <div class="col-md-6">
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
                                                <div class="col-md-6">
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
                                                <div class="col-md-6">
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
                                                <div class="col-md-6">
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
                                                <div class="col-md-6">
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
                                                <div class="col-md-6">
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
                                                <div class="col-md-6">
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
                                                <div class="col-md-6">
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
                                                <div class="col-md-6">
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
                                                <div class="col-md-6">
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
                                                <div class="col-md-6">
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
                                                <div class="col-md-6">
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
                                                <div class="col-md-6">
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
                                                <div class="col-md-6">
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
                                                    <div class="col-md-6">
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
                                            <div class="row" id="hyper_div">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label col-md-3">Blood
                                                            Presure</label>
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
                                                <div class="col-md-6 input-group">
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
                                                <div class="col-md-6">
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
                                                <div class="col-md-6">
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
                                                    <table class="table table-striped table-hover  table-advance">
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
        <link href="{{url('/')}}/assets/global/plugins/clockface/css/clockface.css" rel="stylesheet"
              type="text/css"/>
        <link href="{{url('/')}}/assets/global/plugins/select2/css/select2.min.css" rel="stylesheet"
              type="text/css"/>
        <link href="{{url('/')}}/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet"
              type="text/css"/>
        <link href="{{url('')}}/assets/global/plugins/bootstrap-sweetalert/sweetalert.css" rel="stylesheet"
              type="text/css"/>
        <style>
            tr.disabled {
                pointer-events: none;
            }
        </style>
        <!-- END HEAD -->

    @endpush
    @push('js')

        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="{{url('/')}}/assets/global/plugins/select2/js/select2.full.min.js"
                type="text/javascript"></script>
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
        <script src="{{url('')}}/assets/js/general.js"
                type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->

        <!-- END THEME LAYOUT SCRIPTS -->
        <script src="{{url('')}}/assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js"
                type="text/javascript"></script>
        <script src="{{url('')}}/assets/global/plugins/bootbox/bootbox.min.js" type="text/javascript"></script>
        <script>


            // NURSE
            var BaseLineNurseFormValidation = function () {

                // basic validation
                var handleValidation1 = function () {
                    // for more info visit the official plugin documentation:
                    // http://docs.jquery.com/Plugins/Validation

                    var form1 = $('#baseline_nurse_form');
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
                                visit_date_nurse: {
                                    required: true
                                },
                                /*  education: {
                                      required: true
                                  },
                                  current_work: {
                                      required: true
                                  }, isProvider: {
                                      required: true
                                  }, isOnlyProvider: {
                                      required: true
                                  },
                                  num_of_family: {
                                      required: true
                                  },
                                  monthly_income: {
                                      required: true
                                  },
                                  isSmoke: {
                                      required: true
                                  },
                                  pain_duration: {
                                      required: true
                                  }, temporal_aspects: {
                                      required: true
                                  }, pain_scale: {
                                      number: true,
                                      maxlength: 10,
                                      minlength: 0,
                                      required: true
                                  }, pain_bothersomeness: {
                                      required: true,
                                      number: true,
                                      maxlength: 10,
                                      minlength: 0,
                                  }, pcl5_score: {
                                      required: true,
                                      number: true,
                                      maxlength: 80,
                                      minlength: 0,
                                  }, health_rate: {
                                      required: true
                                  }, lab_scan: {
                                      required: true
                                  }, image_scan: {
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

                                BaseLineNurseSubmit();


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

            BaseLineNurseFormValidation.init();

            function BaseLineNurseSubmit() {
                App.blockUI();
                var form1 = $('#baseline_nurse_form');
                var error = $('.alert-danger', form1);
                var success = $('.alert-success', form1);
                var painFile_id = $('#painFile_id').val()
                var painFile_status = $('#painFile_statusid').val()
                var action = $('#baseline_nurse_form').attr('action');

                var formData = new FormData($('#baseline_nurse_form')[0]);
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

            //------
            // DOCTOR
            var BaseLineDoctorFormValidation = function () {

                // basic validation
                var handleValidation1 = function () {
                    // for more info visit the official plugin documentation:
                    // http://docs.jquery.com/Plugins/Validation

                    var form1 = $('#baseline_doctor_form');
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
                                visit_date_doctor: {
                                    required: true
                                },
                                /*   injury_mechanism: {
                                       required: true
                                   },
                                   other_pains_before_injury: {
                                       required: true
                                   }, other_nonpain_symptoms: {
                                       required: true
                                   }, comorbidities: {
                                       required: true
                                   },
                                   previous_surgery: {
                                       required: true
                                   },
                                   active_rehabilitation: {
                                       required: true
                                   },
                                   passive_rehabilitation: {
                                       required: true
                                   }, take_drug: {
                                       required: true
                                   }, other_drugs: {
                                       required: true
                                   }, neuro_history_of_pain: {
                                       required: true
                                   }, neuro_pain_localized: {
                                       required: true
                                   }, diagnosis_id: {
                                       required: true,
                                       minlength: true
                                   }, physical_treatment: {
                                       required: true
                                   }, nonpharmacological_treatments: {
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

                                BaseLineDoctorSubmit();


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

            BaseLineDoctorFormValidation.init();

            function BaseLineDoctorSubmit() {
                App.blockUI();
                var form1 = $('#baseline_doctor_form');
                var error = $('.alert-danger', form1);
                var success = $('.alert-success', form1);
                var painFile_id = $('#painFile_id').val()
                var painFile_status = $('#painFile_statusid').val()

                var action = $('#baseline_doctor_form').attr('action');

                var formData = new FormData($('#baseline_doctor_form')[0]);
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

            //------
            // Pharm
            var BaseLinePharmFormValidation = function () {

                // basic validation
                var handleValidation1 = function () {
                    // for more info visit the official plugin documentation:
                    // http://docs.jquery.com/Plugins/Validation

                    var form1 = $('#baseline_pharm_form');
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
                                visit_date_pharmacist: {
                                    required: true
                                },
                                /*  laboratory_outside_reference: {
                                      required: true
                                  },
                                  interactions: {
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

                                BaseLinePharmSubmit();


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

            BaseLinePharmFormValidation.init();

            function BaseLinePharmSubmit() {
                App.blockUI();
                var form1 = $('#baseline_pharm_form');
                var error = $('.alert-danger', form1);
                var success = $('.alert-success', form1);
                var painFile_id = $('#painFile_id').val()
                var painFile_status = $('#painFile_statusid').val()

                var action = $('#baseline_pharm_form').attr('action');

                var formData = new FormData($('#baseline_pharm_form')[0]);
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

            // PSYCHOLOGY
            var BaseLinePsychologyFormValidation = function () {

                // basic validation
                var handleValidation1 = function () {
                    // for more info visit the official plugin documentation:
                    // http://docs.jquery.com/Plugins/Validation

                    var form1 = $('#baseline_psychology_form');
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
                                visit_date_psychology: {
                                    required: true
                                },
                                physical_exam: {
                                    required: true
                                },
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

                                BaseLinePsychologySubmit();


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

            BaseLinePsychologyFormValidation.init();

            function BaseLinePsychologySubmit() {
                App.blockUI();
                var form1 = $('#baseline_psychology_form');
                var error = $('.alert-danger', form1);
                var success = $('.alert-success', form1);
                var painFile_id = $('#painFile_id').val()
                var painFile_status = $('#painFile_statusid').val()

                var action = $('#baseline_psychology_form').attr('action');

                var formData = new FormData($('#baseline_psychology_form')[0]);
                formData.append('painFile_id', painFile_id);
                formData.append('painFile_status', painFile_status);

                formData.append('depression_degree', $('#depression_degree').val());
                formData.append('anxiety_degree', $('#anxiety_degree').val());
                formData.append('stress_degree', $('#stress_degree').val());

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

            //------
            //**********nurse Goals
            function save_nurse_treatment_goals() {
                blockUI('tbtreatment_goals');
                var baseline_nurse_goal = $('#baseline_nurse_goal').val();
                var baseline_nurse_goal_score = $('#baseline_nurse_goal_score').val();
                var baseline_nurse_current_goal_score = $('#baseline_nurse_current_goal_score').val();

                $("#baseline_nurse_goal").parent().removeClass('has-error');
                $("#baseline_nurse_goal_score").parent().removeClass('has-error');
                $("#baseline_nurse_current_goal_score").parent().removeClass('has-error');
                if (baseline_nurse_goal == '' || baseline_nurse_goal_score == '' || baseline_nurse_current_goal_score == '') {
                    $('.alert-nurse-danger-goal').show();
                    $('.alert-nurse-danger-goal').fadeOut(2000);
                    if (baseline_nurse_goal == '')
                        $("#baseline_nurse_goal").parent().addClass('has-error');
                    else
                        $("#baseline_nurse_goal").parent().removeClass('has-error');
                    if (baseline_nurse_goal_score == '')
                        $("#baseline_nurse_goal_score").parent().addClass('has-error');
                    else
                        $("#baseline_nurse_goal_score").parent().removeClass('has-error');
                    if (baseline_nurse_current_goal_score == '')
                        $("#baseline_nurse_current_goal_score").parent().addClass('has-error');
                    else
                        $("#baseline_nurse_current_goal_score").parent().removeClass('has-error');

                } else {

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: "POST",
                        url: '{{url('baselineNurse/insert-treatment-goal')}}',

                        data: {
                            baseline_nurse_goal: baseline_nurse_goal,
                            baseline_nurse_goal_score: baseline_nurse_goal_score,
                            baseline_nurse_current_goal_score: baseline_nurse_current_goal_score,
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
                            unblockUI('tbtreatment_goals');
                            if (data.success) {
                                $('#tbtreatment_goals').html(data.nurse_html);
                                $('#tbtreatment_goals_dr').html(data.doctor_html);
                                $('#baseline_nurse_goal').val('');
                                $('#baseline_nurse_goal_score').val('');
                                $('#baseline_nurse_goal_score').trigger('change');
                                $('#baseline_nurse_current_goal_score').val('');
                                $('#baseline_nurse_current_goal_score').trigger('change');
                                $('.alert-nurse-success-goal').show();
                                $('.alert-nurse-success-goal').fadeOut(2000);
                            } else {
                                alert('error');
                            }
                        }
                    });//END $.ajax
                }
            }

            function del_treatment_goals(id) {
                blockUI('tbtreatment_goals');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('baselineNurse/del-treatment-goal')}}',

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
                        unblockUI('tbtreatment_goals');
                        if (data.success) {
                            $('#tbtreatment_goals').html(data.nurse_html);
                            $('#tbtreatment_goals_dr').html(data.doctor_html);

                            $('.alert-nurse-success-goal').show();
                            $('.alert-nurse-success-goal').fadeOut(2000);
                        } else {
                            alert('error');
                        }
                    }
                });//END $.ajax
            }

            function update_treatment_goals(id) {

                blockUI('tbtreatment_goals');

                var baseline_goal = $('#baseline_goal' + id).val();
                var baseline_goal_score = $('#baseline_goal_score' + id).val();
                var baseline_current_score = $('#baseline_current_score' + id).val();


                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('baselineNurse/update-treatment-goals')}}',

                    data: {
                        id: id,
                        baseline_goal: baseline_goal,
                        baseline_goal_score: baseline_goal_score,
                        baseline_current_score: baseline_current_score,
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

                        unblockUI('tbtreatment_goals');
                        if (data.success) {

                            $('#tbtreatment_goals').html(data.nurse_html);
                            $('#tbtreatment_goals_dr').html(data.doctor_html);

                            $('.alert-nurse-success-goal').show();
                            $('.alert-nurse-success-goal').fadeOut(2000);
                        } else {
                            alert('error');
                        }
                    }
                });//END $.ajax
            }

            //**********doctor Goals
            function save_doctor_treatment_goals() {
                blockUI('tbtreatment_goals_dr');
                var baseline_nurse_goal = $('#dr_baseline_nurse_goal').val();
                var baseline_nurse_goal_score = $('#dr_baseline_nurse_goal_score').val();
                var baseline_nurse_current_goal_score = $('#dr_baseline_nurse_current_goal_score').val();

                $("#dr_baseline_nurse_goal").parent().removeClass('has-error');
                $("#dr_baseline_nurse_goal_score").parent().removeClass('has-error');
                if (baseline_nurse_goal == '' || baseline_nurse_goal_score == '' || baseline_nurse_current_goal_score == '') {
                    $('.alert-doc-danger-goal').show();
                    $('.alert-doc-danger-goal').fadeOut(2000);
                    if (baseline_nurse_goal == '')
                        $("#dr_baseline_nurse_goal").parent().addClass('has-error');
                    else
                        $("#dr_baseline_nurse_goal").parent().removeClass('has-error');
                    if (baseline_nurse_goal_score == '')
                        $("#dr_baseline_nurse_goal_score").parent().addClass('has-error');
                    else
                        $("#dr_baseline_nurse_goal_score").parent().removeClass('has-error');
                    if (baseline_nurse_current_goal_score == '')
                        $("#dr_baseline_nurse_current_goal_score").parent().addClass('has-error');
                    else
                        $("#dr_baseline_nurse_current_goal_score").parent().removeClass('has-error');

                } else {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: "POST",
                        url: '{{url('baselineDoctor/insert-treatment-goal')}}',

                        data: {
                            baseline_nurse_goal: baseline_nurse_goal,
                            baseline_nurse_goal_score: baseline_nurse_goal_score,
                            baseline_nurse_current_goal_score: baseline_nurse_current_goal_score,
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
                            unblockUI('tbtreatment_goals_dr');
                            if (data.success) {
                                $('.alert-doc-success-goal').show();
                                $('.alert-doc-success-goal').fadeOut(2000);
                                $('#tbtreatment_goals').html(data.nurse_html);
                                $('#tbtreatment_goals_dr').html(data.doctor_html);
                                $('#dr_baseline_nurse_goal').val('');
                                $('#dr_baseline_nurse_goal_score').val('');
                                $('#dr_baseline_nurse_goal_score').trigger('change');
                                $('#dr_baseline_nurse_current_goal_score').val('');
                                $('#dr_baseline_nurse_current_goal_score').trigger('change');

                            } else {
                                alert('error');
                            }
                        }
                    });//END $.ajax
                }
            }

            function update_doc_treatment_goals(id) {
                blockUI('tbtreatment_goals_dr');


                var baseline_goal = $('#baseline_doc_goal' + id).val();
                var baseline_goal_score = $('#baseline_doc_goal_score' + id).val();
                var baseline_current_score = $('#baseline_doc_current_score' + id).val();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('baselineDoctor/update-treatment-goals')}}',

                    data: {
                        id: id,
                        baseline_goal: baseline_goal,
                        baseline_goal_score: baseline_goal_score,
                        baseline_current_score: baseline_current_score,
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
                        unblockUI('tbtreatment_goals_dr');

                        if (data.success) {

                            $('#tbtreatment_goals').html(data.nurse_html);
                            $('#tbtreatment_goals_dr').html(data.doctor_html);
                            $('.alert-doc-success-goal').show();
                            $('.alert-doc-success-goal').fadeOut(2000);
                        } else {
                            alert('error');
                        }
                    }
                });//END $.ajax
            }

            function del_doc_treatment_goals(id) {
                blockUI('tbtreatment_goals_dr');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('baselineDoctor/del-treatment-goal')}}',

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
                        unblockUI('tbtreatment_goals_dr');
                        if (data.success) {

                            $('#tbtreatment_goals').html(data.nurse_html);
                            $('#tbtreatment_goals_dr').html(data.doctor_html);
                            $('.alert-doc-success-goal').show();
                            $('.alert-doc-success-goal').fadeOut(2000);
                        } else {
                            alert('error');
                        }
                    }
                });//END $.ajax
            }

            //**********end goals

            function show_other_suggested_actions() {


                if ($("#suggested_actions").val() == 494)

                    $("#dvSuggestedActions").removeClass('hide');
                else {
                    $("#suggested_actions_others").val('');
                    $("#dvSuggestedActions").addClass('hide');

                }

            }

            function show_specify_No_treatment_offered_due_to() {


                if ($("#no_treatment_offered_due_to").val() == 136)

                    $("#dvSpecifyNotreatmentOffered").removeClass('hide');
                else {
                    $("#specify_no_treatment_offered_due_to").val('');
                    $("#dvSpecifyNotreatmentOffered").addClass('hide');

                }

            }

            function show_other_pains_before_injury() {


                if ($("#other_pains_before_injury").val() == 1)

                    $("#dvSpecifyOtherPainsBeforeInjuryDR").removeClass('hide');
                else {
                    $("#specify_other_pains_before_injury").val('');
                    $("#dvSpecifyOtherPainsBeforeInjuryDR").addClass('hide');

                }

            }

            function show_other_current_work() {


                if ($("#current_work").val() == 566) {

                    $("#dvOtherCurrentWork").removeClass('hide');
                } else {
                    $("#other_current_work").val('');
                    $("#dvOtherCurrentWork").addClass('hide');

                }

            }

            function show_other_nonpain_symptoms() {


                if ($("#other_nonpain_symptoms").val() == 1)

                    $("#dvSpecifyOtherNonpainSymptoms").removeClass('hide');
                else {
                    $("#specify_other_nonpain_symptoms").val('');
                    $("#dvSpecifyOtherNonpainSymptoms").addClass('hide');

                }

            }

            function show_other_comorbidities() {


                if ($("#comorbidities").val() == 1)

                    $("#dvSpecifyComorbidities").removeClass('hide');
                else {
                    $("#specify_comorbidities").val('');
                    $("#dvSpecifyComorbidities").addClass('hide');

                }

            }

            function show_other_allergies() {


                if ($("#allergies").val() == 1)

                    $("#dvSpecifyAllergies").removeClass('hide');
                else {
                    $("#specify_allergies").val('');
                    $("#dvSpecifyAllergies").addClass('hide');

                }

            }

            function show_other_previous_surgerys() {


                if ($("#previous_surgery").val() == 1)

                    $("#dvSpecifyPreviousSurgery").removeClass('hide');
                else {
                    $("#specify_previous_surgery").val('');
                    $("#dvSpecifyPreviousSurgery").addClass('hide');

                }

            }

            function show_other_active_rehabilitation() {


                if ($("#active_rehabilitation").val() == 1)

                    $("#dvSpecifyActiveRehabilitation").removeClass('hide');
                else {
                    $("#specify_active_rehabilitation").val('');
                    $("#dvSpecifyActiveRehabilitation").addClass('hide');

                }

            }

            function show_other_passive_rehabilitation() {


                if ($("#passive_rehabilitation").val() == 1)

                    $("#dvSpecifyPassiveRehabilitation").removeClass('hide');
                else {
                    $("#specify_passive_rehabilitation").val('');
                    $("#dvSpecifyPassiveRehabilitation").addClass('hide');

                }

            }

            function show_other_nonpharmacological_treatments() {


                if ($("#nonpharmacological_treatments").val() == 1)

                    $("#dvSpecifyNonpharmacologicalTreatments").removeClass('hide');
                else {
                    $("#specify_nonpharmacological_treatments").val('');
                    $("#dvSpecifyNonpharmacologicalTreatments").addClass('hide');

                }

            }

            function show_prvTreatdrug_table() {


                if ($("#take_drug").val() == 1)

                    $("#dvPreviousTreatmentDrug").removeClass('hide');
                else {
                    if ($('#tbprv_treatment > tr').length > 0) {
                        $("#take_drug").val(1);
                        $("#take_drug").trigger('change');
                        //   alert('Please delete added medications');
                    } else
                        $("#dvPreviousTreatmentDrug").addClass('hide');

                }

            }

            function show_dvPharmacologicalTreatment() {


                if ($("#pharmacological_treatment").val() == 1)

                    $("#dvPharmacologicalTreatmentTable").removeClass('hide');
                else {
                    if ($('#tbtreatmentChoise > tr').length > 0) {
                        $("#pharmacological_treatment").val(1);
                        $("#pharmacological_treatment").trigger('change');
                        //   alert('Please delete added medications');
                    } else
                        $("#dvPharmacologicalTreatmentTable").addClass('hide');

                }

            }


            function show_physical_treatment() {
                if ($("#physical_treatment").val() == 87)
                    $("#dvspecifyphysicaltreatment").removeClass('hide');
                else {
                    $("#specify_physical_treatment").val('');
                    $("#dvspecifyphysicaltreatment").addClass('hide');

                }
            }

            function show_side_and_nerve_table() {
                if ($("#neuro_pain_localized").val() == 1 || $("#neuro_pain_localized").val() == 2) {
                    $(".dvsideandnerve").removeClass('hide');

                } else {
                    // alert($('#tblPainMedicationNowBody').length);
                    if ($('#tbNeuroDR > tr').length > 0) {
                        $("#neuro_pain_localized").val(1);
                        $("#neuro_pain_localized").trigger('change');
                        //  alert('Please delete side and nerve');
                    } else {
                        $(".dvsideandnerve").addClass('hide');

                    }

                }
            }



            function show_hide_truma_section() {
                var items = [];

                items = $("#injury_mechanism").val();
                console.log(items);
                if (items != null && items != '')
                    console.log(items.includes("31"));
                if ((items != null && items != '') && (items.includes("31") == true || items.includes("32") == true)) {

                    $(".traumaSection").removeClass('hide');
                } else {
                    del_all_truma_nerve();
                    $(".traumaSection").addClass('hide');

                }
            }
            function show_side_and_nerve_section() {

                if ($("#neuropathic_pain").val() != '' || $("#nociceptive_pain").val() != '' || $("#crps_pain").val() != '' || $("#idiopathic_pain").val() != '') {

                    $(".dvNeroPainArea").removeClass('hide');
                    $(".dvOtherTruama").removeClass('hide');
                }
                else {

                    if ($('#tbNeuroDR > tr').length > 0)
                        del_all_nerve();
                    $(".dvNeroPainArea").addClass('hide');
                    if ($('#tb_other_NeuroDR > tr').length > 0)
                        del_all_other_nerve();
                    $(".dvOtherTruama").addClass('hide');

                }
            }
            function show_other_side_and_nerve_section() {

                if ($("#neuropathic_pain").val() != '' || $("#nociceptive_pain").val() != '' || $("#crps_pain").val() != '' || $("#idiopathic_pain").val() != '') {

                    $(".dvOtherTruama").removeClass('hide');
                } else {

                    if ($('#tb_other_NeuroDR > tr').length > 0)
                        del_all_other_nerve();
                    $(".dvOtherTruama").addClass('hide');


                }
            }

            function show_scars_side_and_nerve_table() {
                if ($("#neuro_side_and_scars").val() == 1 || $("#neuro_side_and_scars").val() == 2)
                    $(".dvsideandscars").removeClass('hide');
                else {
                    // alert($('#tblPainMedicationNowBody').length);
                    if ($('#tb_scars_NeuroDR > tr').length > 0) {
                        $("#neuro_side_and_scars").val(1);
                        $("#neuro_side_and_scars").trigger('change');
                        //  alert('Please delete side and nerve');
                    } else
                        $(".dvsideandscars").addClass('hide');

                }
            }

            function show_other_side_and_nerve_table() {
                if ($("#neuro_side_and_other").val() == 1 || $("#neuro_side_and_other").val() == 2)
                    $(".dvsideandother").removeClass('hide');
                else {
                    // alert($('#tblPainMedicationNowBody').length);
                    if ($('#tb_other_NeuroDR > tr').length > 0) {
                        $("#other_side_nerve_id").val(1);
                        $("#other_side_nerve_id").trigger('change');
                        //  alert('Please delete side and nerve');
                    } else
                        $(".dvsideandother").addClass('hide');

                }
            }

            //dv_diagnosis_rest();
            $(document).ready(function () {
                // show_specify_injury_mechanism();

                show_other_current_work();
                show_other_pains_before_injury();
                show_other_nonpain_symptoms();
                show_other_comorbidities();
                show_other_previous_surgerys();
                show_other_active_rehabilitation();
                show_other_passive_rehabilitation();
                show_other_nonpharmacological_treatments();
                show_prvTreatdrug_table();
                show_dvPharmacologicalTreatment();
                show_physical_treatment();
              //  show_side_and_nerve_table();
                show_scars_side_and_nerve_table();
                show_other_side_and_nerve_table();

                show_laboratory_specify();
                show_which();
                show_other_suggested_actions();
                show_psychopath_family_relation();
                show_side_and_nerve_section();
                show_hide_truma_section();
               // show_other_side_and_nerve_section();
                //show_hide_truma_section();
                //$("#pain_dueto_neural_tissue").trigger('change');


                /*
                                    show_option_pain_dueto_neural_tissue();

                                    show_option_pain_dueto_neural_pain();
                                    show_option_pain_dueto_nonneural_pain();

                                    show_option_tree3_level1();
                                    show_option_tree3_level2();
                                    show_option_tree3_level3();*/

                // clear_qutenza_form();
                show_hypdertension_div();
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

            function calc_PCS_total_Score() {
                var pcs_thinking_hurts = $('#pcs_thinking_hurts').val();
                var pcs_overwhelms_pain = $('#pcs_overwhelms_pain').val();
                var pcs_afraid_pain = $('#pcs_afraid_pain').val();
                $('#pcs_score').val(0);
                var pcs_score = 0;
                if (pcs_thinking_hurts != '')
                    pcs_score += parseInt(pcs_thinking_hurts);
                if (pcs_overwhelms_pain != '')
                    pcs_score += parseInt(pcs_overwhelms_pain);
                if (pcs_afraid_pain != '')
                    pcs_score += parseInt(pcs_afraid_pain);
                $('#pcs_score').val(pcs_score);

            }

            function calc_PHQ_total_Score() {
                var phq_nervous = $('#phq_nervous').val();
                var phq_worry = $('#phq_worry').val();
                var phq_little_interest = $('#phq_little_interest').val();
                var phq_feelingdown = $('#phq_feelingdown').val();
                $('#phq_total_score').val(0);
                var phq_total_score = 0;
                if (phq_nervous != '')
                    phq_total_score += parseInt(phq_nervous);
                if (phq_worry != '')
                    phq_total_score += parseInt(phq_worry);
                if (phq_little_interest != '')
                    phq_total_score += parseInt(phq_little_interest);
                if (phq_feelingdown != '')
                    phq_total_score += parseInt(phq_feelingdown);


                $('#phq_total_score').val(phq_total_score);

            }

            //*****************//
            function add_previse_drug() {
                blockUI('tbprv_treatment');
                var drug_id = $('#drug_id').val();
                var effect_id = $('#effect_id').val();

                $("#drug_id").parent().removeClass('has-error');
                $("#effect_id").parent().removeClass('has-error');
                if (drug_id == '' || effect_id == '') {
                    if (drug_id == '') {
                        $('.alert-danger-drugs').show();
                        $('.alert-danger-drugs').fadeOut(2000);
                        $("#drug_id").parent().addClass('has-error');
                    } else
                        $("#drug_id").parent().removeClass('has-error');
                    if (effect_id == '') {
                        $('.alert-danger-drugs').show();
                        $('.alert-danger-drugs').fadeOut(2000);
                        $("#effect_id").parent().addClass('has-error');
                    } else
                        $("#effect_id").parent().removeClass('has-error');

                }

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('baselineDoctor/insert-prvtreatment-drugs')}}',

                    data: {
                        drug_id: drug_id,
                        effect_id: effect_id,
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
                        unblockUI('tbprv_treatment');
                        if (data.success) {
                            $('.alert-success-drugs').show();
                            $('.alert-success-drugs').fadeOut(2000);
                            $('#tbprv_treatment').html(data.html);
                        } else {
                            alert('error');
                        }
                    }
                });//END $.ajax
            }

            function del_prvtreatment_drug(id) {

                blockUI('tbprv_treatment');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('baselineDoctor/del-prvtreatment-drugs')}}',

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
                        unblockUI('tbprv_treatment');
                        if (data.success) {
                            $('.alert-success-drugs').show();
                            $('.alert-success-drugs').fadeOut(2000);
                            $('#tbprv_treatment').html(data.html);
                        } else {
                            alert('error');
                        }
                    }
                });//END $.ajax
            }

            //*****************//

            function save_side_and_nerve() {
                blockUI('tbNeuroDR');
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
                        url: '{{url('baselineDoctor/side_and_nerve')}}',

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
                            unblockUI('tbNeuroDR');
                            if (data.success) {
                                $('.alert-success-side').show();
                                $('.alert-success-side').fadeOut(2000);
                                $('#tbNeuroDR').html(data.html)
                            } else {
                                alert('error');
                            }
                        }
                    }
                )
                ;//END $.ajax
            }

            function save_scars_side_and_nerve() {
                blockUI('tb_scars_NeuroDR');
                var scars_side_nerve_id = $('#scars_side_nerve_id').val();
                var scars_side_detail_id = $('#scars_side_detail_id').val();
                var light_touch = $('#scars_light_touch').val();
                var pinprick = $('#scars_pinprick').val();
                var warmth = $('#scars_warmth').val();
                var cold = $('#scars_cold').val();
                $("#scars_side_nerve_id").parent().removeClass('has-error');
                if (scars_side_nerve_id == '') {
                    $('.alert-danger-side-scars').show();
                    $('.alert-danger-side-scars').fadeOut(2000);
                    $("#scars_side_nerve_id").parent().addClass('has-error');
                    return;
                } else
                    $("#scars_side_nerve_id").parent().removeClass('has-error');


                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                        type: "POST",
                        url: '{{url('baselineDoctor/side-scars')}}',

                        data: {
                            scars_side_nerve_id: scars_side_nerve_id,
                            scars_side_detail_id: scars_side_detail_id,

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
                            unblockUI('tb_scars_NeuroDR');
                            if (data.success) {
                                $('.alert-success-side-scars').show();
                                $('.alert-success-side-scars').fadeOut(2000);
                                $('#tb_scars_NeuroDR').html(data.html)
                            } else {
                                alert('error');
                            }
                        }
                    }
                )
                ;//END $.ajax
            }

            function save_other_side_and_nerve() {
                blockUI('tb_other_NeuroDR');
                var other_side_nerve_id = $('#other_side_nerve_id').val();
                var other_side_detail_id = $('#other_side_detail_id').val();
                var light_touch = $('#other_light_touch').val();
                var pinprick = $('#other_pinprick').val();
                var warmth = $('#other_warmth').val();
                var cold = $('#other_cold').val();
                $("#other_side_nerve_id").parent().removeClass('has-error');
                if (other_side_nerve_id == '') {
                    $('.alert-danger-side-other').show();
                    $('.alert-danger-side-other').fadeOut(2000);
                    $("#other_side_nerve_id").parent().addClass('has-error');
                    return;
                } else
                    $("#other_side_nerve_id").parent().removeClass('has-error');


                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                        type: "POST",
                        url: '{{url('baselineDoctor/side-other')}}',

                        data: {
                            other_side_nerve_id: other_side_nerve_id,
                            other_side_detail_id: other_side_detail_id,

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
                            unblockUI('tb_other_NeuroDR');
                            if (data.success) {
                                $('.alert-success-side-other').show();
                                $('.alert-success-side-other').fadeOut(2000);
                                $('#tb_other_NeuroDR').html(data.html)
                            } else {
                                alert('error');
                            }
                        }
                    }
                )
                ;//END $.ajax
            }

            function del_nerve(id) {
                blockUI('tbNeuroDR');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('baselineDoctor/del_side_and_nerve')}}',

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
                        unblockUI('tbNeuroDR');
                        if (data.success) {
                            $('.alert-success-side').show();
                            $('.alert-success-side').fadeOut(2000);
                            $('#tbNeuroDR').html(data.html)
                        } else {
                            alert('error');
                        }
                    }
                });//END $.ajax
            }

            function del_all_nerve() {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('baselineDoctor/del_all_side_and_nerve')}}',

                    data: {
                        painFile_id: $('#painFile_id').val(),
                    },
                    error: function (xhr, status, error) {

                    },
                    beforeSend: function () {
                    },
                    complete: function () {
                    },
                    success: function (data) {
                        if (data.success) {
                            $('#tbNeuroDR').html('')
                        } else {
                            alert('error');
                        }

                    }
                });//END $.ajax
            }

            function del_all_other_nerve() {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('baselineDoctor/del_all_other_side_and_nerve')}}',

                    data: {
                        painFile_id: $('#painFile_id').val(),
                    },
                    error: function (xhr, status, error) {

                    },
                    beforeSend: function () {
                    },
                    complete: function () {
                    },
                    success: function (data) {
                        if (data.success) {
                            $('#tb_other_NeuroDR').html('')
                        } else {
                            alert('error');
                        }

                    }
                });//END $.ajax
            }

            function del_all_truma_nerve() {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('baselineDoctor/del_all_truma_side_and_nerve')}}',

                    data: {
                        painFile_id: $('#painFile_id').val(),
                    },
                    error: function (xhr, status, error) {

                    },
                    beforeSend: function () {
                    },
                    complete: function () {
                    },
                    success: function (data) {
                        if (data.success) {
                            $('#tb_other_NeuroDR').html('')
                        } else {
                            alert('error');
                        }

                    }
                });//END $.ajax
            }

            function del_scars_nerve(id) {
                blockUI('tb_scars_NeuroDR');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('baselineDoctor/del_scars-side_and_nerve')}}',

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
                        unblockUI('tb_scars_NeuroDR');
                        if (data.success) {
                            $('.alert-success-side-scars').show();
                            $('.alert-success-side-scars').fadeOut(2000);
                            $('#tb_scars_NeuroDR').html(data.html)
                        } else {
                            alert('error');
                        }
                    }
                });//END $.ajax
            }

            function del_other_nerve(id) {
                blockUI('tb_other_NeuroDR');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('baselineDoctor/del_other-side_and_nerve')}}',

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
                        unblockUI('tb_other_NeuroDR');
                        if (data.success) {
                            $('.alert-success-side-other').show();
                            $('.alert-success-side-other').fadeOut(2000);
                            $('#tb_other_NeuroDR').html(data.html)
                        } else {
                            alert('error');
                        }
                    }
                });//END $.ajax
            }

            //************************//
            /**
             * Treatment Choice Doctor
             */
            function add_treatment_choice_drug() {
                blockUI('tbtreatmentChoise');
                var drug_id = $('#treatment_choice_drug_id').val();
                // var concentration = $('#concentration').val();
                var dosage = $('#dosage').val();
                var frequency = $('#frequency').val();
                var duration = $('#duration').val();
                var drug_comments = $('#drug_comments').val();
                var quantity = $('#quantity').val();
                var batch_id = $('#batch_id').val();
                var drug_cost = $('#drug_cost').val();
                var drug_price = $('#drug_price').val();
//alert('drug_comments'+drug_comments);
                $("#treatment_choice_drug_id").parent().removeClass('has-error');
                // $("#effect_id").parent().removeClass('has-error');
                if (drug_id == '') {

                    $('.alert-danger-pharmacological').show();
                    $('.alert-danger-pharmacological').fadeOut(3000);
                    $("#treatment_choice_drug_id").parent().addClass('has-error');
                    return;
                } else
                    $("#treatment_choice_drug_id").parent().removeClass('has-error');

                //**********concentration
                //  $("#concentration").parent().removeClass('has-error');
                // $("#effect_id").parent().removeClass('has-error');
                /*  if (concentration == '') {
                      $('.alert-danger-pharmacological').show();
                      $('.alert-danger-pharmacological').fadeOut(3000);
                      $("#concentration").parent().addClass('has-error');
                      return;
                  } else
                      $("#concentration_pharm").parent().removeClass('has-error');
                   */
//**********dosage
                $("#dosage").parent().removeClass('has-error');
                // $("#effect_id").parent().removeClass('has-error');
                if (dosage == '' || (isNaN(dosage))) {
                    $('.alert-danger-pharmacological').show();
                    $('.alert-danger-pharmacological').fadeOut(3000);
                    $("#dosage").parent().addClass('has-error');
                    return;
                } else
                    $("#dosage").parent().removeClass('has-error');
//**********frequency
                $("frequency").parent().removeClass('has-error');
                // $("#effect_id").parent().removeClass('has-error');
                if (frequency == '' || (isNaN(frequency))) {
                    $('.alert-danger-pharmacological').show();
                    $('.alert-danger-pharmacological').fadeOut(3000);
                    $("#frequency").parent().addClass('has-error');
                    return;
                } else
                    $("#frequency").parent().removeClass('has-error');
//**********duration
                $("#duration").parent().removeClass('has-error');
                // $("#effect_id").parent().removeClass('has-error');
                if (duration == '' || (isNaN(duration))) {
                    $('.alert-danger-pharmacological').show();
                    $('.alert-danger-pharmacological').fadeOut(3000);
                    $("#duration").parent().addClass('has-error');
                    return;
                } else
                    $("#duration").parent().removeClass('has-error');

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('baselineDoctor/insert-treatment_choice-drugs')}}',

                    data: {
                        drug_id: drug_id,
                        batch_id: batch_id,
                        drug_price: drug_price,
                        dosage: dosage,
                        frequency: frequency,
                        duration: duration,
                        drug_comments: drug_comments,
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
                        unblockUI('tbtreatmentChoise');
                        if (data.success) {
                            $('.alert-success-pharmacological').show();
                            $('.alert-success-pharmacological').fadeOut(2000);
                            $('#tbtreatmentChoise').html(data.doctor_html);
                            $('#tbTreatmentChoisePharm').html(data.pharm_html);
                            $('#treatment_choice_drug_id').val('');
                            $('#treatment_choice_drug_id').trigger('change');
                            // $('#concentration').val('');
                            // $('#total_cost').val();
                            $('#dosage').val('');
                            $('#frequency').val('');
                            $('#duration').val('');
                            $('#drug_comments').val('');
                            $('#drug_cost').val('');
                            $('#drug_price').val('');
                            $('#batch_id').val('');
                            $('#quantity').val('');
                        } else {
                            alert('error');
                        }
                    }
                });//END $.ajax
            }

            function del_treatment_choice_drug(id) {
                blockUI('tbtreatmentChoise');

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('baselineDoctor/del-treatment_choice-drugs')}}',

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
                        unblockUI('tbtreatmentChoise');
                        if (data.success) {
                            $('#tbtreatmentChoise').html(data.doctor_html);
                            $('#tbTreatmentChoisePharm').html(data.pharm_html);
                        } else {
                            alert('error');
                        }
                    }
                });//END $.ajax
            }

            /**
             * Treatment Choice Pharm
             */
            function add_treatment_choice_drug_pharm() {
                blockUI('tbTreatmentChoisePharm');
                var drug_id = $('#treatment_choice_drug_id_pharm').val();
                var dosage = $('#dosage_pharm').val();
                var frequency = $('#frequency_pharm').val();
                var duration = $('#duration_pharm').val();
                var drug_comments = $('#drug_comments_pharm').val();
                var quantity = $('#quantity_pharm').val();
                var batch_id = $('#batch_id_pharm').val();
                var drug_cost = $('#drug_cost_pharm').val();
                var drug_price = $('#drug_price_pharm').val();

                $("#treatment_choice_drug_id_pharm").parent().removeClass('has-error');
                // $("#effect_id").parent().removeClass('has-error');
                if (drug_id == '') {
                    $('.alert-danger-pharm').show();
                    $('.alert-danger-pharm').fadeOut(3000);
                    $("#treatment_choice_drug_id_pharm").parent().addClass('has-error');
                    return;
                } else
                    $("#treatment_choice_drug_id_pharm").parent().removeClass('has-error');
//**********concentration
                //   // $("#effect_id").parent().removeClass('has-error');
                /*  if (concentration == '') {
                      $('.alert-danger-pharm').show();
                      $('.alert-danger-pharm').fadeOut(2000);
                      $("#concentration_pharm").parent().addClass('has-error');
                      return;
                  } else
                      $("#concentration_pharm").parent().removeClass('has-error');*/

//**********dosage
                $("#dosage_pharm").parent().removeClass('has-error');
                // $("#effect_id").parent().removeClass('has-error');
                if (dosage == '' || (isNaN(dosage))) {
                    $('.alert-danger-pharm').show();
                    $('.alert-danger-pharm').fadeOut(2000);
                    $("#dosage_pharm").parent().addClass('has-error');
                    return;
                } else
                    $("#dosage_pharm").parent().removeClass('has-error');
//**********frequency
                $("#frequency_pharm").parent().removeClass('has-error');
                // $("#effect_id").parent().removeClass('has-error');
                if (frequency == '' || (isNaN(frequency))) {
                    $('.alert-danger-pharm').show();
                    $('.alert-danger-pharm').fadeOut(2000);
                    $("#frequency_pharm").parent().addClass('has-error');
                    return;
                } else
                    $("#frequency_pharm").parent().removeClass('has-error');
//**********duration
                $("#duration_pharm").parent().removeClass('has-error');
                // $("#effect_id").parent().removeClass('has-error');
                if (duration == '' || (isNaN(duration))) {
                    $('.alert-danger-pharm').show();
                    $('.alert-danger-pharm').fadeOut(2000);
                    $("#duration_pharm").parent().addClass('has-error');
                    return;
                } else
                    $("#duration_pharm").parent().removeClass('has-error');


                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('baselinePharm/insert-treatment_choice-drugs')}}',

                    data: {
                        drug_id: drug_id,
                        batch_id: batch_id,
                        drug_price: drug_price,
                        dosage: dosage,
                        frequency: frequency,
                        duration: duration,
                        drug_comments: drug_comments,
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
                        unblockUI('tbTreatmentChoisePharm');
                        if (data.success) {
                            $('.alert-success-pharm').show();
                            $('.alert-success-pharm').fadeOut(2000);
                            $('#tbtreatmentChoise').html(data.doctor_html);
                            $('#tbTreatmentChoisePharm').html(data.pharm_html);

                            $('#treatment_choice_drug_id_pharm').val('');
                            $('#treatment_choice_drug_id_pharm').trigger('change');


                            $('#dosage_pharm').val('');
                            $('#frequency_pharm').val('');
                            $('#duration_pharm').val('');
                            $('#drug_comments_pharm').val('');
                            $('#drug_cost_pharm').val('');
                            $('#drug_price_pharm').val('');
                            $('#batch_id_pharm').val('');
                            $('#quantity_pharm').val('');
                        } else {
                            alert('error');
                        }
                    }
                });//END $.ajax
            }

            function del_treatment_choice_drug_pharm(id) {
                blockUI('tbTreatmentChoisePharm');

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('baselinePharm/del-treatment_choice-drugs')}}',

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
                        unblockUI('tbTreatmentChoisePharm');
                        if (data.success) {
                            $('.alert-success-pharm').show();
                            $('.alert-success-pharm').fadeOut(2000);
                            $('#tbtreatmentChoise').html(data.doctor_html);
                            $('#tbTreatmentChoisePharm').html(data.pharm_html);
                        } else {
                            alert('error');
                        }
                    }
                });//END $.ajax
            }

            function update_treatment_choice_drug_dosage_pharm(id) {
                blockUI('tbTreatmentChoisePharm');
                var concentration = $('#' + id + 'concentration').val();
                var dosage = $('#' + id + 'dosage').val();
                var frequency = $('#' + id + 'frequency').val();
                var duration = $('#' + id + 'duration').val();
                var quantity = $('#' + id + 'quantity').val();
                var drug_cost = $('#' + id + 'drug_cost').val();
                // var dosage = $('#' + id + 'dosage').val();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('baselinePharm/update-treatment_choice-dosage')}}',

                    data: {
                        id: id,
                        concentration: concentration,
                        dosage: dosage,
                        frequency: frequency,
                        duration: duration,
                        quantity: quantity,
                        drug_price: $('#' + id + 'drug_price').val(),
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
                        unblockUI('tbTreatmentChoisePharm');
                        if (data.success) {
                            $('#tbtreatmentChoise').html(data.doctor_html);
                            $('#tbTreatmentChoisePharm').html(data.pharm_html);
                            //    $('#' + id + 'dosage').parent().addClass('has-success');
                            $('.alert-success-pharm').show();
                            $('.alert-success-pharm').fadeOut(2000);
                        } else {
                            alert('error');
                        }
                    }
                });//END $.ajax
            }

            /**
             * PHARMACIST
             */
            function show_laboratory_specify() {

                if ($("#laboratory_outside_reference").val() == 1)
                    $("#dvSpecifyLab").removeClass('hide');
                else {
                    $("#laboratory_specify").val('');
                    $("#dvSpecifyLab").addClass('hide');
                }
            }

            function show_which() {

                if ($("#interactions").val() == 1)
                    $("#dvWhich").removeClass('hide');
                else {
                    $("#which_interactions").val('');
                    $("#dvWhich").addClass('hide');

                }
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

                        if (data.success)
                            $('#side_detail_id').html(data.html)
                        else {
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
                    url: '{{url('acutepain/get_sub_side_details')}}',

                    data: {
                        id: side_detail_id,
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

                        if (data.success)
                            $('#sub_side_detail_id').html(data.html)
                        else {
                            alert('error');
                        }
                    }
                });//END $.ajax
            }

            function get_active_batch_price() {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                if ($('#treatment_choice_drug_id').val() != '')
                    $.ajax({
                        type: "POST",
                        url: '{{url('baselineDoctor/get-batch-price')}}',
                        data: {
                            drug_id: $('#treatment_choice_drug_id').val(),

                        },
                        success: function (data) {
                            if (data.success) {
                                if (data.batch_current_quantity > 0) {
                                    $('#dosage').val('');
                                    $('#frequency').val('');
                                    $('#duration').val('');
                                    $('#drug_comments').val('');
                                    $('#drug_cost').val('');
                                    $('#drug_price').val('');
                                    $('#batch_id').val('');
                                    $('#quantity').val('');
                                    $('#drug_price').val(data.batch_cost)
                                    $('#batch_id').val(data.batch_id)
                                    $(".drug").prop("disabled", false);
                                } else {
                                    $('#dosage').val('');
                                    $('#frequency').val('');
                                    $('#duration').val('');
                                    $('#drug_comments').val('');
                                    $('#drug_cost').val('');
                                    $('#drug_price').val('');
                                    $('#batch_id').val('');
                                    $('#quantity').val('');
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
                                $('#dosage').val('');
                                $('#frequency').val('');
                                $('#duration').val('');
                                $('#drug_comments').val('');
                                $('#drug_cost').val('');
                                $('#drug_price').val('');
                                $('#batch_id').val('');
                                $('#quantity').val('');
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
                    });//END $.ajax
            }

            function get_active_batch_price_pharm() {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                if ($('#treatment_choice_drug_id_pharm').val() != '')
                    $.ajax({
                        type: "POST",
                        url: '{{url('baselineDoctor/get-batch-price')}}',
                        data: {
                            drug_id: $('#treatment_choice_drug_id_pharm').val(),

                        },
                        success: function (data) {
                            if (data.success) {
                                if (data.batch_current_quantity > 0) {
                                    $('#dosage_pharm').val('');
                                    $('#frequency_pharm').val('');
                                    $('#duration_pharm').val('');
                                    $('#drug_comments_pharm').val('');
                                    $('#drug_cost_pharm').val('');
                                    $('#drug_price_pharm').val('');
                                    $('#batch_id_pharm').val('');
                                    $('#quantity_pharm').val('');
                                    $('#drug_price_pharm').val(data.batch_cost)
                                    $('#batch_id_pharm').val(data.batch_id)
                                    $(".drug").prop("disabled", false);
                                } else {
                                    $('#dosage_pharm').val('');
                                    $('#frequency_pharm').val('');
                                    $('#duration_pharm').val('');
                                    $('#drug_comments_pharm').val('');
                                    $('#drug_cost_pharm').val('');
                                    $('#drug_price_pharm').val('');
                                    $('#batch_id_pharm').val('');
                                    $('#quantity_pharm').val('');
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
                                $('#dosage_pharm').val('');
                                $('#frequency_pharm').val('');
                                $('#duration_pharm').val('');
                                $('#drug_comments_pharm').val('');
                                $('#drug_cost_pharm').val('');
                                $('#drug_price_pharm').val('');
                                $('#batch_id_pharm').val('');
                                $('#quantity_pharm').val('');
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
                    });//END $.ajax
            }

            function cal_drug_cost() {
                var quantity = 0;
                var cost = 0;
                if ($('#dosage').val() != '' && $('#frequency').val() != '' && $('#duration').val() != '') {
                    quantity = parseFloat($('#dosage').val()) * parseFloat($('#frequency').val()) * parseFloat($('#duration').val());
                    cost = quantity * parseFloat($('#drug_price').val())
                }
                $('#quantity').val(quantity)
                $('#drug_cost').val(cost)
            }

            function cal_drug_cost_insert_pharm() {
                var quantity = 0;
                var cost = 0;
                if ($('#dosage_pharm').val() != '' && $('#frequency_pharm').val() != '' && $('#duration_pharm').val() != '') {
                    quantity = parseFloat($('#dosage_pharm').val()) * parseFloat($('#frequency_pharm').val()) * parseFloat($('#duration_pharm').val());
                    cost = quantity * parseFloat($('#drug_price_pharm').val())
                }
                $('#quantity_pharm').val(quantity)
                $('#drug_cost_pharm').val(cost)
            }

            function cal_drug_cost_pharm(rowid) {
                var quantity = 0;
                var cost = 0;
                if ($('#' + rowid + 'dosage').val() != '' && $('#' + rowid + 'frequency').val() != '' && $('#' + rowid + 'duration').val() != '') {
                    quantity = parseFloat($('#' + rowid + 'dosage').val()) * parseFloat($('#' + rowid + 'frequency').val()) * parseFloat($('#' + rowid + 'duration').val());
                    cost = quantity * parseFloat($('#' + rowid + 'drug_price').val())
                }
                $('#' + rowid + 'quantity').val(quantity)
                $('#' + rowid + 'drug_cost').val(cost)
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

            function change_baseline_drug_order_status() {

                var drug_array = [];
                var painFile_id = $('#painFile_id').val();
                var painFile_statusid = $('#painFile_statusid').val();
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
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'Cancel',

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
                                url: '{{url('baseline/change-drug-order-status')}}',

                                data: {

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
                                        $('#tbtreatmentChoise').html(data.doctor_html);
                                        $('#tbTreatmentChoisePharm').html(data.pharm_html);
                                        $('.alert-success-pharm').show();
                                        $('.alert-success-pharm').fadeOut(2000);
                                    } else {
                                        swal("Cancelled", 'Item :' + data.item_name + ' no active batch or quntity', "error");
                                        $('.alert-danger-pharm').show();
                                        $('.alert-danger-pharm').fadeOut(2000);
                                    }

                                }
                            });//END $.ajax

                        }
                    });
            }

            //***********************Qutenza
            var QutenzaFormValidation = function () {

                // basic validation
                var handleValidation1 = function () {
                    // for more info visit the official plugin documentation:
                    // http://docs.jquery.com/Plugins/Validation
                    var form1 = $('#qutenza_form');
                    var error1 = $('.alert-danger', form1);
                    var success1 = $('.alert-success', form1);

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
                                }, application_time: {
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
                                }, hypertension: {
                                    required: true,
                                },
                                /*
                                                                hypertension_Baseline_systolic: {
                                                                    digits: true,
                                                                    number: true
                                                                },
                                                                hypertension_Baseline_diastolic: {
                                                                    digits: true,
                                                                },*/
                                /*    after_ttt_systolic: {
                                        digits: true,
                                    },
                                    after_ttt_diastolic: {
                                        digits: true,
                                    }*/


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
                var followup_id = 0;
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
                formData.append('visit_type', 1);
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
                                // clear_qutenza_form(followup_id);
                                success.show();
                                error.hide();
                                //  App.scrollTo(success, -200);
                                success.fadeOut(2000);
                                $('.tbqutenza').html(data.qutenza_html);

                            } else {
                                success.hide();
                                error.show();
                                // App.scrollTo(error, -200);
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
                            $('#hypertension_systolic').val(data.qutenza_data.hypertension_systolic);
                            $('#hypertension_diastolic').val(data.qutenza_data.hypertension_diastolic);
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

            function clear_qutenza_form() {

                $('#hdnqutenza_id').val('');
                $('#qutenza_date').val('');
                $('#allodynia').val('');
                $('#application_time').val('');
                $('#pain').val('');
                $('#erythema').val('');
                $('#pruritus').val('');
                $('#papules').val('');
                $('#edema').val('');
                $('#swelling').val('');
                $('#dryness').val('');
                $('#nasopharyngitis').val('');
                $('#bronchitis').val('');
                $('#sinusitis').val('');
                $('#nausea').val('');
                $('#vomiting').val('');
                $('#skin_pruritus').val('');
                $('#hypertension').val('');
                $('#hypertension_systolic').val('');
                $('#hypertension_diastolic').val('');
                //  $('#qutenza_pain_scale').val('');
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

            }

            //***********************Qutenza score
            var QutenzaScoreFormValidation = function () {

                // basic validation
                var handleValidation1 = function () {
                    // for more info visit the official plugin documentation:
                    // http://docs.jquery.com/Plugins/Validation
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
                var followup_id = 0;
                var form1 = $('#qutenza_score_form');
                var error = $('.qutenza-score-danger', form1);
                var success = $('.qutenza-score-success', form1);
                var action = form1.attr('action');
                var painFile_id = $('#painFile_id').val()
                //var painFile_status = $('#painFile_statusid').val()
                var hdnqutenza_id = $('#hdnqutenza_id').val()
                //var qutenza_date = $('#qutenza_score_date').val()
                var formData = new FormData(form1[0]);
                formData.append('followup_id', null);
                formData.append('painFile_id', painFile_id);
                formData.append('visit_type', 1);
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
                                //  clear_qutenza_score_form(followup_id);
                                success.show();
                                error.hide();
                                // App.scrollTo(success, -200);
                                success.fadeOut(2000);
                                $('.tbqutenz_score').html(data.qutenza_score_html);

                            } else {
                                success.hide();
                                error.show();
                                // App.scrollTo(error, -200);
                                error.fadeOut(2000);
                            }


                        },
                        error: function (err) {

                            console.log(err);
                        }

                    }
                )

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

                        }
                    }
                });//END $.ajax
            }

            function show_hypdertension_div() {
                if ($('#hypertension').val() == 1)
                    $('#hyper_div').show();
                else
                    $('#hyper_div').hide();
            }

            function clear_qutenza_score_form() {
                $('#qutenza_pain_scale').val('');
                $('#week').val('');
                $('#week').trigger('change');

            }

            function add_patient_to_project() {
                var form1 = $('#project_form');
                var error = $('.project-danger', form1);
                var success = $('.project-success', form1);

                var painFile_id = $('#painFile_id').val()
                var project_id = $('#project_id').val()
                var answer_project_question = $('#answer_project_question').val()
                var pain_project_chart = $('#pain_project_chart').val()
                var pain_project_note = $('#pain_project_note').val()
                if ($('#answer_project_question').val() == '') {
                    $("#answer_project_question").parent().addClass('has-error');
                    success.hide();
                    error.show();
                    // App.scrollTo(error, -200);
                    error.fadeOut(2000);
                    return;
                } else
                    $("#answer_project_question").parent().removeClass('has-error');

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                if (project_id != '')
                    $.ajax({
                        type: "POST",
                        url: '{{url('add-patient-project')}}',
                        data: {
                            project_id: project_id,
                            painFile_id: painFile_id,
                            answer_project_question: answer_project_question,
                            pain_project_chart: pain_project_chart,
                            pain_project_note: pain_project_note
                        },
                        success: function (data) {
                            if (data.success) {
                                success.show();
                                error.hide();
                                // App.scrollTo(success, -200);
                                success.fadeOut(2000);

                                $('.tbpatient_project').html(data.patient_project_html);
                                $('#add_project_modal').modal('hide');
                            } else {

                                success.hide();
                                error.show();
                                // App.scrollTo(error, -200);
                                error.fadeOut(2000);
                            }
                        }
                    });//END $.ajax
            }

            function qutenza_request(type) {
                var painFile_id = $('#painFile_id').val();
                var need_qutenza = $('#need_qutenza_' + type).val();
                var visit_date = $('#visit_date' + type).val();

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
                            painFile_id: painFile_id, visit_type: 1, visit_date: visit_date
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

            function stop_project(project_id) {
                var painFile_id = $('#painFile_id').val()
                //  var project_id = $('#project_id').val()
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                bootbox.dialog({
                    message: "Are you sure you want to end patient's project?",
                    title: "Warning",
                    buttons: {
                        success: {
                            label: "Yes",
                            className: "green",
                            callback: function () {
                                $.ajax({
                                    type: "POST",
                                    url: '{{url('stop-patient-project')}}',

                                    data: {
                                        project_id: project_id, painFile_id: painFile_id
                                    },
                                    error: function (xhr, status, error) {

                                    },
                                    beforeSend: function () {
                                    },
                                    complete: function () {
                                    },
                                    success: function (data) {
                                        if (data.success) {

                                            $('.tbpatient_project').html(data.patient_project_html);
                                        } else {
                                            alert('error');
                                        }
                                    }
                                });//END $.ajax
                            }
                        },
                        danger: {
                            label: "No",
                            className: "red",
                            callback: function () {

                            }
                        },
                        /* main: {
                             label: "Click ME!",
                             className: "blue",
                             callback: function() {
                                 alert("Primary button");
                             }
                         }*/
                    }
                });

            }


            function add_pharm_followup_project() {
                var form1 = $('#pharm_project_form');
                var error = $('.pharm-project-danger', form1);
                var success = $('.pharm-project-success', form1);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "POST",
                    url: '{{url('project/add-pharm-baseline')}}',

                    data: {
                        pain_project_id: $('#pain_project_id').val(),
                        //   pharm_answer_project_question: $('#pharm_answer_project_question').val(),
                        pharm_project_action: $('#pharm_project_action').val(),
                        pharm_project_note: $('#pharm_project_note').val(),

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

                            // $('#pharm_followup_project_modal').modal('hide');

                        } else {
                            success.hide();
                            error.show();
                            // App.scrollTo(error, -200);
                            error.fadeOut(2000);
                        }
                    }
                });//END $.ajax


            }

            function get_project_info() {

                project_id = $('#project_id').val();
                $('.answer_project_question').val('').trigger('change').prop("disabled", false);
                $('.pain_project_chart').val('').trigger('change').prop("disabled", false);

                $('.pain_project_note').html('').prop("disabled", false);
                $('.project_name').val('');
                $('.project_question').val('');
                //  $('.pharm_project_action').val('').trigger('change');
                // $('.pharm_project_note').html('');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('project-info')}}',

                    data: {
                        project_id: project_id
                    },
                    error: function (xhr, status, error) {

                    },
                    beforeSend: function () {
                    },
                    complete: function () {
                    },
                    success: function (data) {
                        if (data.success) {

                            $('.project_name').val(data.project_data.project_name);
                            $('.project_question').val(data.project_data.question);
                            $('.answer_project_question').val(data.project_data.answer_project_question).trigger('change');
                            $('.pain_project_chart').val(data.project_data.pain_project_chart).trigger('change');
                            $('.pain_project_note').html(data.project_data.pain_project_note);

                        }
                    }
                });//END $.ajax
            }

            function edit_project(id) {

                $('#pain_project_id').val(id);

                $('.answer_project_question').prop("disabled", true);
                $('.pain_project_chart').prop("disabled", true);
                $('.pain_project_note').prop("disabled", true);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('show-project')}}',

                    data: {
                        table_id: id
                    },
                    error: function (xhr, status, error) {

                    },
                    beforeSend: function () {
                    },
                    complete: function () {
                    },
                    success: function (data) {
                        if (data.success) {

                            $('.project_name').val(data.project_data.project_name);
                            $('.project_question').val(data.project_data.question);
                            $('.answer_project_question').val(data.project_data.answer_project_question).trigger('change');
                            $('.pain_project_chart').val(data.project_data.pain_project_chart).trigger('change');
                            $('.pain_project_note').html(data.project_data.pain_project_note);

                            $('.pharm_project_action').val(data.project_data.pharm_project_action).trigger('change');
                            $('.pharm_project_note').html(data.project_data.pharm_project_note);
                        }
                    }
                });//END $.ajax
            }

            function dv_diagnosis_rest() {
                $("#dv_neural_tissue").hide();
                $("#dv_neural_pain").hide();
                $("#dv_nonneural").hide();
                $("#dv_nonneural_tissue").hide();
                $("#dv_nociceptive_pain").hide();
                $("#dv_independent").hide();
                $("#dv_independent_history").hide();
                $("#dv_independent_CRPS").hide();
                $("#dv_distribution_regional").hide();
                $("#dv_nociplastic_pain").hide();
                $("#dv_idiopathic_pain").hide();


                $("#neuropathic_pain").val('').trigger('change');
                $("#history_of_relevant_neurological_lesion").val('').trigger('change');
                $("#history_of_pain_distribution_is_neur_plausible").val('').trigger('change');
                $("#pain_associated_with_sensory_signs").val('').trigger('change');

                $("#pain_dueto_nonneural_tissue").val('').trigger('change');
                $("#history_of_relevant_nonneurological_lesion").val('').trigger('change');
                $("#history_of_pain_distribution_is_plausible").val('').trigger('change');
                $("#pain_consistently_correlated_with_nonneurological_lesion").val('').trigger('change');
                $("#nociceptive_pain").val('').trigger('change');

                $("#pain_independent_of_injuries_and_diseases").val('').trigger('change');

                $("#crps_pain").val('').trigger('change');

                $("#pain_distribution_regional").val('').trigger('change');

                $("#history_accordance_diagnostic_budapest_criteria").val('').trigger('change');


                $("#pain_hypersensitivity_in_region_pain_during_assessment").val('').trigger('change');

                $("#pain_hypersensitivity_in_region_pain_dueto_touch").val('').trigger('change');


                $("#nociplastic_pain").val('').trigger('change');

                $("#idiopathic_pain").val('').trigger('change');

            }

            function dv_diagnosis_t2_rest() {
                // alert('dv_diagnosis_t2_rest');
                $("#dv_nonneural_tissue").hide();
                $("#dv_nociceptive_pain").hide();

                $("#dv_independent").hide();
                $("#dv_independent_history").hide();
                $("#dv_independent_CRPS").hide();

                $("#dv_distribution_regional").hide();
                $("#dv_nociplastic_pain").hide();
                $("#dv_idiopathic_pain").hide();


                //$("#pain_dueto_nonneural_tissue").val('').trigger('change');
                $("#history_of_relevant_nonneurological_lesion").val('').trigger('change');
                $("#history_of_pain_distribution_is_plausible").val('').trigger('change');
                $("#pain_consistently_correlated_with_nonneurological_lesion").val('').trigger('change');
                $("#nociceptive_pain").val('').trigger('change');

                $("#pain_independent_of_injuries_and_diseases").val('').trigger('change');


            }

            function dv_diagnosis_t3_rest() {

                $("#dv_independent_history").hide();
                $("#dv_independent_CRPS").hide();
                $("#dv_distribution_regional").hide();
                $("#dv_nociplastic_pain").hide();
                $("#dv_idiopathic_pain").hide();


                if ($("#crps_pain").val() != '')
                    $("#crps_pain").val('').trigger('change');
                if ($("#pain_distribution_regional").val() != '')
                    $("#pain_distribution_regional").val('').trigger('change');
                if ($("#history_accordance_diagnostic_budapest_criteria").val() != '')
                    $("#history_accordance_diagnostic_budapest_criteria").val('').trigger('change');

                if ($("#pain_hypersensitivity_in_region_pain_during_assessment").val() != '')
                    $("#pain_hypersensitivity_in_region_pain_during_assessment").val('').trigger('change');
                if ($("#pain_hypersensitivity_in_region_pain_dueto_touch").val() != '')
                    $("#pain_hypersensitivity_in_region_pain_dueto_touch").val('').trigger('change');

                if ($("#nociplastic_pain").val() != '')
                    $("#nociplastic_pain").val('').trigger('change');
                if ($("#idiopathic_pain").val() != '')
                    $("#idiopathic_pain").val('').trigger('change');


            }

            function dv_diagnosis_t3_2_rest(docready = '0') {


                $("#dv_independent_CRPS").hide();
                $("#dv_distribution_regional").hide();
                $("#dv_nociplastic_pain").hide();
                $("#dv_idiopathic_pain").hide();

                if (docready == '1') {
                    if ($("#crps_pain").val() != '')
                        $("#crps_pain").val('').trigger('change');

                    if ($("#pain_distribution_regional").val() != '')
                        $("#pain_distribution_regional").val('').trigger('change');
                    if ($("#pain_hypersensitivity_in_region_pain_during_assessment").val() != '')
                        $("#pain_hypersensitivity_in_region_pain_during_assessment").val('').trigger('change');
                    if ($("#pain_hypersensitivity_in_region_pain_dueto_touch").val() != '')
                        $("#pain_hypersensitivity_in_region_pain_dueto_touch").val('').trigger('change');

                    if ($("#nociplastic_pain").val() != '')
                        $("#nociplastic_pain").val('').trigger('change');

                    if ($("#idiopathic_pain").val() != '')
                        $("#idiopathic_pain").val('').trigger('change');
                }

            }

            //*********** tree 1
            function show_option_pain_dueto_neural_tissue() {
                if ($("#pain_dueto_neural_tissue").val() == '1') {
                    $("#dv_neural_tissue").show();
                    $("#dv_neural_pain").hide();
                    $("#dv_nonneural").hide();
                    $("#dv_independent").hide();
                    $("#dv_independent_history").hide();
                    $("#dv_independent_CRPS").hide();
                    $("#dv_distribution_regional").hide();
                    $("#dv_nociplastic_pain").hide();
                    $("#dv_idiopathic_pain").hide();
                } else if ($("#pain_dueto_neural_tissue").val() == '0') {

                    if ($("#history_of_relevant_neurological_lesion").val() != '')
                        $("#history_of_relevant_neurological_lesion").val('').trigger('change');
                    if ($("#history_of_pain_distribution_is_neur_plausible").val() != '')
                        $("#history_of_pain_distribution_is_neur_plausible").val('').trigger('change');
                    if ($("#pain_associated_with_sensory_signs").val() != '')
                        $("#pain_associated_with_sensory_signs").val('').trigger('change');
                    if ($("#neuropathic_pain").val() != '')
                        $("#neuropathic_pain").val('').trigger('change');
                    $("#dv_neural_tissue").hide();
                    $("#dv_neural_pain").hide();
                    $("#dv_nonneural").show();
                } else {
                    dv_diagnosis_rest();
                }
            }


            function show_option_pain_dueto_neural_pain(docready = '0') {
                if ($("#history_of_relevant_neurological_lesion").val() == 1 &&
                    $("#history_of_pain_distribution_is_neur_plausible").val() == 1 &&
                    $("#pain_associated_with_sensory_signs").val() == 1) {
                    $("#dv_neural_pain").show();
                    $("#dv_nonneural").hide();
                    if (docready == '0')
                        $("#pain_dueto_nonneural_tissue").val('').trigger('change');
                } else if ($("#history_of_relevant_neurological_lesion").val() != '' &&
                    $("#history_of_pain_distribution_is_neur_plausible").val() != '' &&
                    $("#pain_associated_with_sensory_signs").val() != '') {
                    $("#dv_neural_pain").hide();
                    $("#dv_nonneural").show();
                    if ($("#neuropathic_pain").val() != '')
                        $("#neuropathic_pain").val('').trigger('change');
                }
            }

            function show_option_pain_dueto_nonneural_pain(docready = '0') {
                if ($("#neuropathic_pain").val() == 407 || $("#neuropathic_pain").val() == 408) {
                    $("#dv_nonneural").show();
                } else if ($("#history_of_relevant_neurological_lesion").val() == 1 &&
                    $("#history_of_pain_distribution_is_neur_plausible").val() == 1 &&
                    $("#pain_associated_with_sensory_signs").val() == 1 && $("#neuropathic_pain").val() == '') {
                    if (docready == '0')
                        if ($("#pain_dueto_nonneural_tissue").val() != '')
                            $("#pain_dueto_nonneural_tissue").val('').trigger('change');
                    $("#dv_nonneural").hide();
                } else if ($("#neuropathic_pain").val() == 409) {
                    if (docready == '0')
                        if ($("#pain_dueto_nonneural_tissue").val() != '') {
                            $("#pain_dueto_nonneural_tissue").val('').trigger('change');
                        }
                    $("#dv_nonneural").hide();
                }
            }

            //*********** tree 2

            function show_option_pain_dueto_nonneural_tissue() {
                if ($("#pain_dueto_nonneural_tissue").val() == '1') {
                    $("#dv_nonneural_tissue").show();

                    $("#dv_independent").hide();
                    $("#dv_independent_history").hide();
                    $("#dv_independent_CRPS").hide();

                    $("#dv_distribution_regional").hide();
                    $("#dv_nociplastic_pain").hide();
                    $("#dv_idiopathic_pain").hide();

                    if ($("#pain_independent_of_injuries_and_diseases").val() != '')
                        $("#pain_independent_of_injuries_and_diseases").val('').trigger('change');
                } else if ($("#pain_dueto_nonneural_tissue").val() == '0') {

                    if ($("#history_of_relevant_nonneurological_lesion").val() != '')
                        $("#history_of_relevant_nonneurological_lesion").val('').trigger('change');
                    if ($("#history_of_pain_distribution_is_plausible").val() != '')
                        $("#history_of_pain_distribution_is_plausible").val('').trigger('change');
                    if ($("#pain_consistently_correlated_with_nonneurological_lesion").val() != '')
                        $("#pain_consistently_correlated_with_nonneurological_lesion").val('').trigger('change');

                    if ($("#nociceptive_pain").val() != '')
                        $("#nociceptive_pain").val('').trigger('change');

                    $("#dv_nonneural_tissue").hide();
                    $("#dv_nociceptive_pain").hide();
                    $("#dv_independent").show();
                } else {
                    // alert('empty');
                    dv_diagnosis_t2_rest();

                }
            }

            function show_pain_dueto_nonneural_tissue(docready = '0') {
                if ($("#history_of_relevant_nonneurological_lesion").val() == 1 &&
                    $("#history_of_pain_distribution_is_plausible").val() == 1 &&
                    $("#pain_consistently_correlated_with_nonneurological_lesion").val() == 1) {

                    $("#dv_nociceptive_pain").show();
                    $("#dv_independent").hide();
                    if (docready == '0')
                        $("#pain_independent_of_injuries_and_diseases").val('').trigger('change');
                } else if ($("#history_of_relevant_nonneurological_lesion").val() != '' &&
                    $("#history_of_pain_distribution_is_plausible").val() != '' &&
                    $("#pain_consistently_correlated_with_nonneurological_lesion").val() != '') {
                    $("#dv_nociceptive_pain").hide();
                    $("#dv_independent").show();
                    if (docready == '0')
                        if ($("#nociceptive_pain").val() != '')
                            $("#nociceptive_pain").val('').trigger('change');
                }
            }

            function show_pain_dueto_nonneural_tissue_ind(docready = '0') {
                if ($("#nociceptive_pain").val() == 411 || $("#nociceptive_pain").val() == 412) {
                    $("#dv_independent").show();

                } else if ($("#history_of_relevant_neurological_lesion").val() == 1 &&
                    $("#history_of_pain_distribution_is_neur_plausible").val() == 1 &&
                    $("#pain_associated_with_sensory_signs").val() == 1 && $("#nociceptive_pain").val() == '') {
                    if ($("#pain_independent_of_injuries_and_diseases").val() != '') {
                        if (docready == '0')
                            $("#pain_independent_of_injuries_and_diseases").val('').trigger('change');
                        $("#dv_independent").hide();
                    }
                } else if ($("#nociceptive_pain").val() == 413) {
                    if ($("#pain_independent_of_injuries_and_diseases").val() != '') {
                        if (docready == '0')
                            $("#pain_independent_of_injuries_and_diseases").val('').trigger('change');
                        $("#dv_independent").hide();
                        alert('hide dv_independent 3');
                    }
                }


            }

            //*********** tree 3

            function show_option_tree3_level1(docready = '0') {
                if ($("#pain_independent_of_injuries_and_diseases").val() == '1') {
                    $("#dv_independent_history").show();

                    $("#dv_independent_CRPS").hide();

                    $("#dv_distribution_regional").hide();
                    $("#dv_nociplastic_pain").hide();
                    $("#dv_idiopathic_pain").hide();
                    if (docready == '0') {
                        if ($("#pain_distribution_regional").val() != '')
                            $("#pain_distribution_regional").val('').trigger('change');
                        if ($("#pain_hypersensitivity_in_region_pain_during_assessment").val() != '')
                            $("#pain_hypersensitivity_in_region_pain_during_assessment").val('').trigger('change');
                        if ($("#pain_hypersensitivity_in_region_pain_dueto_touch").val() != '')
                            $("#pain_hypersensitivity_in_region_pain_dueto_touch").val('').trigger('change');
                        if ($("#nociplastic_pain").val() != '')
                            $("#nociplastic_pain").val('').trigger('change');
                        if ($("#idiopathic_pain").val() != '')
                            $("#idiopathic_pain").val('').trigger('change');
                    }
                } else if ($("#pain_dueto_nonneural_tissue").val() == '0') {
                    if (docready == '0') {
                        if ($("#crps_pain").val() != '')
                            $("#crps_pain").val('').trigger('change');
                        if ($("#pain_distribution_regional").val() != '')
                            $("#pain_distribution_regional").val('').trigger('change');
                        if ($("#history_accordance_diagnostic_budapest_criteria").val() != '')
                            $("#history_accordance_diagnostic_budapest_criteria").val('').trigger('change');
                        if ($("#pain_distribution_regional").val() != '')
                            $("#pain_distribution_regional").val('').trigger('change');
                        if ($("#pain_hypersensitivity_in_region_pain_during_assessment").val() != '')
                            $("#pain_hypersensitivity_in_region_pain_during_assessment").val('').trigger('change');
                        if ($("#pain_hypersensitivity_in_region_pain_dueto_touch").val() != '')
                            $("#pain_hypersensitivity_in_region_pain_dueto_touch").val('').trigger('change');
                        if ($("#nociplastic_pain").val() != '')
                            $("#nociplastic_pain").val('').trigger('change');
                        if ($("#idiopathic_pain").val() != '')
                            $("#idiopathic_pain").val('').trigger('change');
                    }
                    $("#dv_independent_history").hide();
                    $("#dv_independent_CRPS").hide();

                    $("#dv_distribution_regional").hide();
                    $("#dv_nociplastic_pain").hide();
                    $("#dv_idiopathic_pain").hide();
                } else {
                    // alert('empty');
                    dv_diagnosis_t3_rest();

                }
            }

            function show_option_tree3_level2(docready = '0') {
                if ($("#history_accordance_diagnostic_budapest_criteria").val() == '1') {

                    $("#dv_independent_CRPS").show();

                    $("#dv_distribution_regional").hide();
                    $("#dv_nociplastic_pain").hide();
                    $("#dv_idiopathic_pain").hide();
                    if (docready == '0') {
                        if ($("#pain_distribution_regional").val() != '')
                            $("#pain_distribution_regional").val('').trigger('change');
                        if ($("#pain_hypersensitivity_in_region_pain_during_assessment").val() != '')
                            $("#pain_hypersensitivity_in_region_pain_during_assessment").val('').trigger('change');
                        if ($("#pain_hypersensitivity_in_region_pain_dueto_touch").val() != '')
                            $("#pain_hypersensitivity_in_region_pain_dueto_touch").val('').trigger('change');
                        if ($("#nociplastic_pain").val() != '')
                            $("#nociplastic_pain").val('').trigger('change');
                        if ($("#idiopathic_pain").val() != '')
                            $("#idiopathic_pain").val('').trigger('change');
                    }
                } else if ($("#history_accordance_diagnostic_budapest_criteria").val() == '0') {
                    if (docready == '0') {
                        if ($("#crps_pain").val() != '')
                            $("#crps_pain").val('').trigger('change');
                    }
                    $("#dv_distribution_regional").show();

                    $("#dv_independent_CRPS").hide();
                    $("#dv_nociplastic_pain").hide();
                    $("#dv_idiopathic_pain").hide();
                } else {
                    // alert('empty');
                    dv_diagnosis_t3_2_rest();

                }
            }

            function show_option_tree3_level3(docready = '0') {
                if ($("#pain_distribution_regional").val() == 1 &&
                    $("#pain_hypersensitivity_in_region_pain_during_assessment").val() == 1 &&
                    $("#pain_hypersensitivity_in_region_pain_dueto_touch").val() == 1) {

                    $("#dv_nociplastic_pain").show();
                    $("#dv_idiopathic_pain").hide();

                    $("#idiopathic_pain").val('').trigger('change');

                } else if ($("#pain_distribution_regional").val() != '' &&
                    $("#pain_hypersensitivity_in_region_pain_during_assessment").val() != '' &&
                    $("#pain_hypersensitivity_in_region_pain_dueto_touch").val() != '') {
                    $("#dv_nociplastic_pain").hide();
                    $("#dv_idiopathic_pain").show();
                    if (docready == '0') {
                        if ($("#nociplastic_pain").val() != '')
                            $("#nociplastic_pain").val('').trigger('change');
                    }
                }
            }

            function get_patient_eval_data() {
                $('#dv_pcl_eval').html('');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('baselineNurse/add-pcl')}}',
                    data: {
                        painFile_id: $('#painFile_id').val()
                    },
                    success: function (data) {
                        if (data.success) {
                            get_pcl_eval();
                        }
                    }
                });//END $.ajax
            }

            function get_pcl_eval() {
                $('#dv_pcl_eval').html('');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('baselineNurse/get-pcl')}}',
                    data: {
                        painFile_id: $('#painFile_id').val()
                    },
                    success: function (data) {
                        if (data.success) {

                            $('#dv_pcl_eval').html(data.baselinePclEval);
                        }
                    }
                });//END $.ajax
            }

            function save_eval_answer(eval_answer, eval_id) {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('baselineNurse/save-pcl-answer')}}',
                    data: {
                        painFile_id: $('#painFile_id').val(),
                        eval_id: eval_id,
                        eval_answer: eval_answer
                    },
                    success: function (data) {
                        if (data.success) {

                            $('#pcl5_score').val(data.pcl5_score);
                        }
                    }
                });//END $.ajax
            }

            /////////////////////////
            function get_patient_dass_eval_data() {
                $('#dv_dass_eval').html('');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('baselinePsychology/add-dass')}}',
                    data: {
                        painFile_id: $('#painFile_id').val()
                    },
                    success: function (data) {
                        if (data.success) {
                            get_dass_eval();
                        }
                    }
                });//END $.ajax
            }

            function get_dass_eval() {
                $('#dv_pcl_eval').html('');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('baselinePsychology/get-dass')}}',
                    data: {
                        painFile_id: $('#painFile_id').val()
                    },
                    success: function (data) {
                        if (data.success) {

                            $('#dv_dass_eval').html(data.baselineDassEval);
                        }
                    }
                });//END $.ajax
            }

            function save_dass_eval_answer(eval_answer, eval_id) {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('baselinePsychology/save-dass-answer')}}',
                    data: {
                        painFile_id: $('#painFile_id').val(),
                        eval_id: eval_id,
                        eval_answer: eval_answer
                    },
                    success: function (data) {
                        if (data.success) {

                            $('#depression_score').val(data.depression_score);
                            $('#depression_degree').val(data.depression_degree);
                            $('#depression_class').removeClass().addClass(data.depression_class);
                            $('#depression_class').html(data.depression_name);

                            $('#anxiety_score').val(data.anxiety_score);
                            $('#anxiety_degree').val(data.anxiety_degree);
                            $('#anxiety_class').removeClass().addClass(data.anxiety_class);
                            $('#anxiety_class').html(data.anxiety_name);

                            $('#stress_score').val(data.stress_score);
                            $('#stress_degree').val(data.stress_degree);
                            $('#stress_class').removeClass().addClass(data.stress_class);
                            $('#stress_class').html(data.stress_name);
                        }
                    }
                });//END $.ajax
            }

            /////////////////////////
            function show_psychopath_family_relation() {
                if ($('#psychopath_in_family').val() == 1)
                    $("#dv_family_relation").removeClass('hide');
                else {
                    $("#family_relationship").val('');
                    $("#dv_family_relation").addClass('hide');
                }
            }

            function save_chk(id) {
                if ($('#checkbox_' + 527).prop('checked')) // // Returns true if checked, false if unchecked.)
                    $('#dv_streching_exercise_neck_shoulder').removeClass('hide');
                else
                    $('#dv_streching_exercise_neck_shoulder').addClass('hide');

                if ($('#checkbox_' + 539).prop('checked')) // // Returns true if checked, false if unchecked.)

                    $('#dv_streching_exercise_lower_back').removeClass('hide');
                else
                    $('#dv_streching_exercise_lower_back').addClass('hide');

                if ($('#checkbox_' + id).prop('checked')) {// // Returns true if checked, false if unchecked.)
                    var checked = 1;
                } else {
                    var checked = 0;
                }
                $.ajax({
                    type: "POST",
                    url: '{{url('baselineNurse/save-physiotherapy-chk')}}',
                    data: {
                        painFile_id: $('#painFile_id').val(),
                        physiotherapy_id: id,
                        checked: checked
                    },
                    success: function (data) {

                        if (id == 527 && ($('#checkbox_' + 527).prop('checked') == false)) {//to hide and empty child checkbox of 527
                            $('.child_chk_527').prop('checked', false);
                            $('.child_select_527').val('').trigger('change');
                        }
                        if (id == 539 && ($('#checkbox_' + 539).prop('checked') == false)) {//to hide and empty child checkbox of 527
                            $('.child_chk_539').prop('checked', false);
                            $('.child_select_539').val('').trigger('change');
                        }
                        //if (data.success) {
                        //   set_message("تمت العملية بنجاح", true);
                        //  } else
                        //   set_message("لم تتم العملية بنجاح", false);
                    }
                });
            }

            function update_ck_compliance(id) {
                var compliance = $('#compliance' + id).val();
                $.ajax({
                    type: "POST",
                    url: '{{url('baselineNurse/physiotherapy-chk-compliance')}}',
                    data: {
                        painFile_id: $('#painFile_id').val(),
                        physiotherapy_id: id,
                        compliance: compliance
                    },
                    success: function (data) {
                        /* if (data.success) {
                             show_alert_message('Operation accomplished successfully', "success");
                         } else
                             show_alert_message('The operation was not completed successfully', "danger");*/
                    }
                });
            }

        </script>
    @endpush
@stop