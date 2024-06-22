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
        @include('patient.patient_profile')
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN VALIDATION STATES-->
                <div class="portlet light portlet-fit portlet-form bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-user font-green"></i>
                            <span class="caption-subject font-green sbold uppercase">Last Followup</span>
                        </div>
                        @if($count_last_followup==0 && $painFile_statusId==17 && auth()->user()->id!=100)
                            <div class="actions">
                                <div class="btn-group">
                                    <a class="btn green btn-outline sbold uppercase" href="#form_modal2"
                                       data-toggle="modal"> Create Follow Up
                                        <i class="fa fa-share"></i>
                                    </a>
                                    {{-- <a href="javascript:;" class="btn btn-circle green btn-outline"> Add New Follow Up </a>--}}
                                </div>
                            </div>
                        @endif
                    </div>
                    <input type="hidden" id="painFile_id" name="painFile_id" value="{{$painFile_id}}">
                    <input type="hidden" id="painFile_statusid" name="painFile_statusid" value="{{$painFile_statusId}}">
                    <div id="form_modal2" class="modal fade" role="dialog" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal"
                                            aria-hidden="true"></button>
                                    <h4 class="modal-title ">New Followup </h4>
                                </div>
                                {{Form::open(['url'=>url('lastfollowup/newfollowup'),'class'=>'form-horizontal','method'=>"post","id"=>"new_followup"])}}
                                <div class="modal-body">
                                    <input type="hidden" id="painFile_id_modal" name="painFile_id"
                                           value="{{$painFile_id}}">
                                    <input type="hidden" id="painFile_statusid_modal" name="painFile_status"
                                           value="{{$painFile_statusId}}">
                                    <input type="hidden" id="patientid" name="patientid" value="{{$patientid}}">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Date</label>
                                        <div class="col-md-8">
                                            <input class="form-control input-medium date-picker" size="16"
                                                   name="new_followup_date" id="new_followup_date"
                                                   type="text" value="{{date('Y-m-d')}}" data-date-format="yyyy-mm-dd"/>
                                        </div>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn green">
                                        Create
                                    </button>
                                    <button class="btn dark btn-outline" data-dismiss="modal" aria-hidden="true">Close
                                    </button>
                                </div>
                                {{Form::close()}}
                            </div>
                        </div>
                    </div>
                    @if($count_last_followup!=0)
                        <div class="portlet-body">

                            <!-- BEGIN FORM-->
                            {{Form::open(['url'=>url('lastfollowup'),'class'=>'form-horizontal','method'=>"post","id"=>"followup_form"])}}

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
                                    <label class="control-label col-md-3">Close Reason<span class="required"> * </span>
                                    </label>
                                    <div class="col-md-4">
                                        <select id="close_reasons" name="close_reasons"
                                                class="form-control select2" onchange="show_hide_lastvisit_form();">
                                            <option value="">Select...</option>
                                            <?php
                                            $selected = '';
                                            foreach ($close_reason_list as $raw) {
                                                $selected = '';
                                                if (isset($one_painFile->close_reasons) && $raw->id == $one_painFile->close_reasons)
                                                    $selected = 'selected="selected"';
                                                echo '<option value="' . $raw->id . '" ' . $selected . '>' . $raw->lookup_details . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Close Reason Details<span
                                                class="required"> * </span>
                                    </label>
                                    <div class="col-md-4">
                                        <select id="close_reasons_details" name="close_reasons_details"
                                                class="form-control select2">
                                            <?php
                                            echo(isset($close_reason_details_list) ? $close_reason_details_list : '');
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div id="dv_last_form">
                                    <div class="form-group">
                                        <label class="control-label col-md-3">In general, how would you rate
                                            your health
                                            today?
                                            <span class="required"> * </span>
                                        </label>
                                        <div class="col-md-4">
                                            <select id="health_rate" name="health_rate"
                                                    class="form-control select2">
                                                <option value="">Select...</option>
                                                <?php
                                                $selected = '';
                                                foreach ($health_rate_list as $raw) {
                                                    $selected = '';
                                                    if (isset($one_last_followup->health_rate) && $raw->id == $one_last_followup->health_rate)
                                                        $selected = 'selected="selected"';
                                                    echo '<option value="' . $raw->id . '" ' . $selected . '>' . $raw->lookup_details . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
<!--                                    <h4 class="form-section font-green"> 1) PHQ-4 </h4>
                                    <p><strong>Over the last two weeks, how often have you been bothered by the
                                            following problems?</strong></p>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Feeling nervous, anxious or on
                                            edge
                                        </label>
                                        <div class="col-md-4">
                                            <select id="phq_nervous" name="phq_nervous"
                                                    class="form-control select2" onchange="calc_PHQ_total_Score();">
                                                <option value="">Select...</option>
                                                <?php
                                                /*$selected = '';
                                                foreach ($phq_nervous_list as $raw) {
                                                    $selected = '';
                                                    if (isset($one_last_followup->phq_nervous) && $raw->id == $one_last_followup->phq_nervous)
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
                                        <div class="col-md-4">
                                            <select id="phq_worry" name="phq_worry" class="form-control select2"
                                                    onchange="calc_PHQ_total_Score();">
                                                <option value="">Select...</option>
                                                <?php
                                                $selected = '';
                                                foreach ($phq_nervous_list as $raw) {
                                                    $selected = '';
                                                    if (isset($one_last_followup->phq_worry) && $raw->id == $one_last_followup->phq_worry)
                                                        $selected = 'selected="selected"';
                                                    echo '<option value="' . $raw->id . '" ' . $selected . '>' . $raw->option . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Little interest or pleasure in
                                            doing
                                            things
                                        </label>
                                        <div class="col-md-4">
                                            <select id="phq_little_interest" name="phq_little_interest"
                                                    class="form-control select2" onchange="calc_PHQ_total_Score();">
                                                <option value="">Select...</option>
                                                <?php
                                                $selected = '';
                                                foreach ($phq_nervous_list as $raw) {
                                                    $selected = '';
                                                    if (isset($one_last_followup->phq_little_interest) && $raw->id == $one_last_followup->phq_little_interest)
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
                                        <div class="col-md-4">
                                            <select id="phq_feelingdown" name="phq_feelingdown"
                                                    class="form-control select2" onchange="calc_PHQ_total_Score();">
                                                <option value="">Select...</option>
                                                <?php
                                                $selected = '';
                                                foreach ($phq_nervous_list as $raw) {
                                                    $selected = '';
                                                    if (isset($one_last_followup->phq_feelingdown) && $raw->id == $one_last_followup->phq_feelingdown)
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
                                        <div class="col-md-4">
                                            <input type="text" name="phq_total_score" class="form-control"
                                                   id="phq_total_score" value="@php
                                                echo ((isset($one_last_followup->phq_nervous)?$one_last_followup->phq_nervous:0)+
                                                 (isset($one_last_followup->phq_worry)?$one_last_followup->phq_worry:0)+
                                                 (isset($one_last_followup->phq_little_interest)?$one_last_followup->phq_little_interest:0)+
                                                 (isset($one_last_followup->phq_feelingdown)?$one_last_followup->phq_feelingdown:0));
                                            @endphp" disabled/>

                                        </div>
                                    </div>
                                    <div class="alert alert-info">
                                        <strong>Info!</strong> PHQ-4 total score ranges from 0 to 12, with
                                        categories of
                                        psychological distress being: <br/>
                                        None 0-2; Mild 3-5; Moderate 6-8; Severe 9-12
                                    </div>
                                    <h4 class="form-section font-green"> 2) PCS-3 </h4>
                                    <p><strong> When I'm in pain : </strong></p>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">I keep thinking about how much it
                                            hurts
                                        </label>
                                        <div class="col-md-4">
                                            <select id="pcs_thinking_hurts" name="pcs_thinking_hurts"
                                                    class="form-control select2" onchange="calc_PCS_total_Score()">

                                                <option value="">Select...</option>
                                                <?php
                                                /*$selected = '';
                                                foreach ($pcs_list as $raw) {
                                                    $selected = '';
                                                    if (isset($one_last_followup->pcs_thinking_hurts) && $raw->id == $one_last_followup->pcs_thinking_hurts)
                                                        $selected = 'selected="selected"';
                                                    echo '<option value="' . $raw->id . '" ' . $selected . '>' . $raw->option . '</option>';
                                                }*/
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">It's awful and I feel that it
                                            overwhelms me
                                        </label>
                                        <div class="col-md-4">
                                            <select id="pcs_overwhelms_pain" name="pcs_overwhelms_pain"
                                                    class="form-control select2" onchange="calc_PCS_total_Score()">
                                                <option value="">Select...</option>
                                                <?php
                                                $selected = '';
                                                foreach ($pcs_list as $raw) {
                                                    $selected = '';
                                                    if (isset($one_last_followup->pcs_overwhelms_pain) && $raw->id == $one_last_followup->pcs_overwhelms_pain)
                                                        $selected = 'selected="selected"';
                                                    echo '<option value="' . $raw->id . '" ' . $selected . '>' . $raw->option . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">I become afraid that the pain will
                                            get worse
                                        </label>
                                        <div class="col-md-4">
                                            <select id="pcs_afraid_pain" name="pcs_afraid_pain"
                                                    class="form-control select2" onchange="calc_PCS_total_Score()">
                                                <option value="">Select...</option>
                                                <?php
                                                $selected = '';
                                                foreach ($pcs_list as $raw) {
                                                    $selected = '';
                                                    if (isset($one_last_followup->pcs_afraid_pain) && $raw->id == $one_last_followup->pcs_afraid_pain)
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
                                        <div class="col-md-4">
                                            <input type="text" name="pcs_score" class="form-control"
                                                   id="pcs_score" value="@php
                                                echo ((isset($one_last_followup->pcs_thinking_hurts)?$one_last_followup->pcs_thinking_hurts:0)+
                                                 (isset($one_last_followup->pcs_overwhelms_pain)?$one_last_followup->pcs_overwhelms_pain:0)+
                                                 (isset($one_last_followup->pcs_afraid_pain)?$one_last_followup->pcs_afraid_pain:0));
                                            @endphp" disabled/></div>
                                    </div>
                                    <div class="alert alert-info">
                                        <strong>Info!</strong> PCS-3 total score ranges from 0 to 12, with a score > 6
                                        points
                                        indicating high level of pain catastrophizing.

                                    </div>-->
<!--                                    <h4 class="form-section font-green"> 3) PCL-5 </h4>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">What is the patients total score
                                            (0-80)?
                                        </label>
                                        <div class="col-md-4">
                                            <input type="text" name="pcl5_score" class="form-control"
                                                   id="pcl5_score" min="0" max="80"
                                                   value="{{isset($one_last_followup->pcl5_score)?$one_last_followup->pcl5_score:''}}"
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
                                    <div class="form-group">
                                        <label class="control-label col-md-3">During the last week,
                                            how has your
                                            usual
                                            pain intensity been on a scale from 0 to 10, with zero meaning “no pain” and 10 meaning “the worst pain imaginable”?

                                        </label>
                                        <div class="col-md-4">
                                            <input type="number" max="10" min="0" name="pain_scale"
                                                   id="pain_scale"
                                                   class="form-control"
                                                   value="{{isset($one_last_followup->pain_scale)?$one_last_followup->pain_scale:''}}"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">During the last week,
                                            how has your
                                            usual
                                            pain bothersomeness been on a scale from 0 to 10, with zero meaning “no pain” and 10 meaning “the worst pain imaginable”?

                                        </label>
                                        <div class="col-md-4">
                                            <input type="number" max="10" min="0"
                                                   name="pain_bothersomeness"
                                                   id="pain_bothersomeness"
                                                   class="form-control"
                                                   value="{{isset($one_last_followup->pain_bothersomeness)?$one_last_followup->pain_bothersomeness:''}}"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">What is your usual
                                            pain intensity during rest on a scale from 0 to 10, with zero meaning “no pain” and 10 meaning “the worst pain imaginable”?

                                        </label>
                                        <div class="col-md-4">
                                            <input type="number" max="10" min="0"
                                                   name="pain_intensity_during_rest"
                                                   id="pain_intensity_during_rest"
                                                   class="form-control"
                                                   value="{{isset($one_last_followup->pain_intensity_during_rest)?$one_last_followup->pain_intensity_during_rest:''}}"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">What is your usual
                                            pain intensity during activity on a scale from 0 to 10, with zero meaning “no pain” and 10 meaning “the worst pain imaginable”?

                                        </label>
                                        <div class="col-md-4">
                                            <input type="number" max="10" min="0"
                                                   name="pain_intensity_during_activity"
                                                   id="pain_intensity_during_activity"
                                                   class="form-control"
                                                   value="{{isset($one_last_followup->pain_intensity_during_activity)?$one_last_followup->pain_intensity_during_activity:''}}"/>
                                        </div>
                                    </div>
                                    <h3 class="form-section font-green"> Treatment Goals </h3>
                                    <hr/>
                                    <div class="alert alert-warning">
                                        Rate the current level of difficulty associated with each activity on a scale
                                        from 0 to 10. "0" represents “unable to perform”, and "10" represents “able
                                        to perform at prior level”.
                                    </div>
                                    <div class="alert alert-danger alert-goal-danger display-hide">
                                        <button class="close"
                                                data-close="alert"></button>
                                        You have some form errors. Please check
                                        below.
                                    </div>
                                    <div class="alert alert-success  alert-goal display-hide">
                                        <button class="close"
                                                data-close="alert"></button>
                                        Your form validation is successful!
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-scrollable table-scrollable-borderless">
                                                <table class="table table-striped table-hover">
                                                    <thead>
                                                    <tr class="uppercase">
                                                        <th> #</th>
                                                        <th>
                                                            Goals
                                                        </th>
                                                        <th>
                                                            Score
                                                        </th>

                                                    </tr>
                                                    </thead>
                                                    <tbody id="tbtreatment_goals">
                                                    @php
                                                        if(isset($treatment_goals_data))
                                                         echo $treatment_goals_data;
                                                    @endphp
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <h3 class="form-section font-green"> PGIC </h3>
                                    <hr/>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">How satisfied are you with the treatment
                                            you have
                                            received
                                            at the Gaza Pain Clinic? a scale from 0 to 6.
                                            <span class="required"> * </span>
                                        </label>
                                        <div class="col-md-4">
                                            <input type="number" max="6" min="0" name="treatment_satisfied"
                                                   class="form-control"
                                                   value="{{isset($one_last_followup->treatment_satisfied)?$one_last_followup->treatment_satisfied:''}}"/>
                                        </div>
                                    </div>
                                    <div class="alert alert-info">
                                        <strong>Info!</strong> Score ranges from 0 to 6<br/>
                                        Very dissatisfied 0; Very satisfied 6
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Since I started my treatment at the Gaza
                                            Pain
                                            Clinic, my overall status is:
                                        </label>
                                        <div class="col-md-4">
                                            <select id="overall_status" name="overall_status"
                                                    class="form-control select2">
                                                <option value="">Select...</option>
                                                <?php
                                                $selected = '';
                                                foreach ($overall_status_list as $raw) {
                                                    $selected = '';
                                                    if (isset($one_last_followup->overall_status) && $raw->id == $one_last_followup->overall_status)
                                                        $selected = 'selected="selected"';
                                                    echo '<option value="' . $raw->id . '" ' . $selected . '>' . $raw->lookup_details . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <h3 class="form-section font-green"> Nurse Messages & Notes </h3>
                                <hr/>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Messages
                                    </label>
                                    <div class="col-md-4">
                                        <input type="text" name="nurse_message" class="form-control"
                                               value="{{isset($one_last_followup->nurse_message)?$one_last_followup->nurse_message:''}}"/>

                                    </div>
                                </div>
                            </div>
                            @if($painFile_statusId==17 && auth()->user()->id!=100)<!-- 17=Active file,100=guest user -->
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" class="btn green">Submit</button>
                                        <button type="button" class="btn grey-salsa btn-outline">Cancel
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endif
                            {{Form::close()}}
                        </div>
                    @endif
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
        <link href="{{url('')}}/assets/global/plugins/bootstrap-sweetalert/sweetalert.css" rel="stylesheet"
              type="text/css"/>
    @endpush
    @push('js')

        <!-- BEGIN PAGE LEVEL PLUGINS -->

        <script src="{{url('/')}}/assets/global/plugins/moment.min.js" type="text/javascript"></script>
        <script src="{{url('/')}}/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js"
                type="text/javascript"></script>
        <script src="{{url('/')}}/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"
                type="text/javascript"></script>
        <script src="{{url('/')}}/assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js"
                type="text/javascript"></script>
        <script src="{{url('/')}}/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"
                type="text/javascript"></script>
        <script src="{{url('/')}}/assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>

        <script src="{{url('')}}/assets/global/plugins/jquery-validation/js/jquery.validate.min.js"
                type="text/javascript"></script>
        <!--  3. bootstrap-sweetalert       =========================-->

        <!--  2.bootstrap-sweetalert  =============================== -->
        <script src="{{url('')}}/assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js"
                type="text/javascript"></script>

        <!-- END PAGE LEVEL PLUGINS -->

        <!-- END THEME LAYOUT SCRIPTS -->

        <script>
            $(document).ready(function () {
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
            // NURSE
            var followupFormValidation = function () {

                // basic validation
                var handleValidation1 = function () {
                    // for more info visit the official plugin documentation:
                    // http://docs.jquery.com/Plugins/Validation
                    //  var followup_id = $('#followup_id').val();
                    var form1 = $('#followup_form');
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
                            rules: {},

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

                                followupSubmit();


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
                // var followup_id = $('#followup_id').val();
                var form1 = $('#followup_form');
                var error = $('.alert-danger', form1);
                var success = $('.alert-success', form1);
                //  var followup_id = $('#followup_id').val();
                var action = form1.attr('action');
                var painFile_id = $('#painFile_id').val()
                var painFile_status = $('#painFile_statusid').val()

                var formData = new FormData(form1[0]);
                formData.append('painFile_id', painFile_id);
                formData.append('painFile_status', painFile_status);

                //  formData.append('followup_id', followup_id);
                $.ajax({
                        url: action,
                        type: 'POST',
                        dataType: 'json',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (data) {
                            if (data.success) {
                                //  alert('data.patient_id' + data.patient_id);
                                success.show();
                                error.hide();
                                swal({
                                        title: 'Do you want to close the patient file ?',
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
                                                url: '{{url('lastfollowup/close-painfile')}}',

                                                data: {
                                                    painFile_id: painFile_id,
                                                    close_reasons: $('#close_reasons').val(),
                                                    close_reasons_details: $('#close_reasons_details').val(),
                                                },
                                                error: function (xhr, status, error) {

                                                },
                                                beforeSend: function () {
                                                },
                                                complete: function () {
                                                },
                                                success: function (data) {

                                                    window.open('{{url('home')}}', '_self');
                                                }
                                            });//END $.ajax

                                        }
                                    });

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

            function update_last_followup_goals(id) {
                var followup_score = $('#followup_score' + id).val();
                if (followup_score >= 0 && followup_score <= 10) {
                    $('#followup_score' + id).removeClass('has-error');
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: "POST",
                        url: '{{url('lastfollowup/update-goal')}}',

                        data: {
                            id: id,
                            followup_score: followup_score,

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
                                $('.alert-goal').show();
                                $('.alert-goal').fadeOut(2000);
                                //alert-treatment+id
                                //$('#tbtreatmentDoctorFollow' + id).html(data.html);
                            } else {
                                alert('error');
                            }
                        }
                    });//END $.ajax
                } else {
                    $('#followup_score' + id).addClass('has-error');
                }
            }

            function show_hide_lastvisit_form() {

                if ($('#close_reasons').val() == 516)
                    $('#dv_last_form').hide();
                else
                    $('#dv_last_form').show();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('lastfollowup/get-close-reason-details')}}',
                    data: {
                        parent_id: $('#close_reasons').val()
                    },
                    success: function (data) {
                        if (data.success) {
                            $('#close_reasons_details').html(data.html);
                        }
                    }
                });//END $.ajax
            }

            //**********************PCL-5
            function get_patient_eval_data() {
                $('#dv_pcl_eval').html('');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{url('lastfollowup/add-pcl')}}',
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
                    url: '{{url('lastfollowup/get-pcl')}}',
                    data: {
                        painFile_id: $('#painFile_id').val()
                    },
                    success: function (data) {
                        if (data.success) {

                            $('#dv_pcl_eval').html(data.followupLastPclEval);
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
                    url: '{{url('lastfollowup/save-pcl-answer')}}',
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
        </script>

    @endpush
@stop
