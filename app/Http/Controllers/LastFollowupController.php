<?php

namespace App\Http\Controllers;

use App\BaselineDoctorConsultation;
use App\BaselineTreatmentChoice;
use App\BaselineTreatmentGoal;
use App\FollowupLast;
use App\FollowupLastPclEvaluation;
use App\FollowupLastTreatmentGoal;
use App\FollowupPatient;
use App\FollowupTreatmentGoal;
use App\Lookup;
use App\PainFile;
use App\Patient;
use App\PcsOption;
use App\PhqOption;
use Illuminate\Http\Request;

class LastFollowupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($painFile_id = null, $patientid = null, $painFile_status = null)
    {
        //dd(43344);
        $this->data['page_title'] = '6 Months Follow-up';
        $this->data['location_link'] = 'painFile/view/' . $painFile_id . '/' . $patientid . '/' . $painFile_status;
        $this->data['location_title'] = 'Pain File';
        if (!isset($painFile_id))
            return redirect()->route('home');

        //********** Patient profile
        $this->data['painFile_id'] = $painFile_id;
        $this->data['painFile_status'] = ($painFile_status == 17) ? 'Open' : 'Closed';
        $this->data['painFile_statusId'] = $painFile_status;
        $this->data['one_patient'] = Patient::find($patientid);
        $this->data['districts'] = get_lookups_list(1);
        //**********End Patient profile

        $this->data['patientid'] = $patientid;

        $this->data['count_last_followup'] = FollowupLast::where('pain_file_id', $painFile_id)->count();
        //  dd($this->data['count_last_followup']);
        $this->data['one_last_followup'] = FollowupLast::where('pain_file_id', $painFile_id)->first();
        $this->data['one_painFile'] = PainFile::where('id', $painFile_id)->first();
        //dd( $this->data['last_followups']);
        $last_follow = $this->data['one_last_followup'];
        if (isset($last_follow)) {
            $last_follow_id = $last_follow->id;
            $this->data['treatment_goals_data'] = $this->get_treatment_goal($painFile_id, $last_follow_id, $painFile_status);
            $this->data['close_reason_details_list']=$this->get_close_reason_details_list($this->data['one_painFile']->close_reasons ,$this->data['one_painFile']->close_reasons_details);
        } else
            $this->data['close_reason_details_list'] = $this->get_close_reason_details_list(null,null);
        $this->data['baseline_doctor_visit_date'] = '';
        $baselineDoctor = BaselineDoctorConsultation::where('pain_file_id', $painFile_id)->first();
        if (isset($baselineDoctor))
            $this->data['baseline_doctor_visit_date'] = $baselineDoctor->visit_date;
        $this->data['one_patient'] = Patient::find($patientid);
        $this->data['health_rate_list'] = get_lookups_list(70);
        $this->data['close_reason_list'] = get_lookups_list(515);

        $this->data['phq_nervous_list'] = PhqOption::all();
        $this->data['pcs_list'] = PcsOption::all();
        $this->data['overall_status_list'] = get_lookups_list(120);

        return view(followup_vw() . '.lastfollowup')->with($this->data);
    }

    public function get_treatment_goal($painFile_id, $last_follow_id, $painFile_status)
    {
        $model = FollowupLastTreatmentGoal::where('pain_file_id', $painFile_id)
            ->where('followup_id', $last_follow_id)->get();
        $html = '';
        $i = 1;
        foreach ($model as $raw) {
            $html .= '<tr><td width="10%">' . $i++ . '</td>';
            $html .= '<td width="60%">' . $raw->goal_text . '</td>';
            $html .= '<td width="20%"> <div class="form-group"> <input id="followup_score' . $raw->id . '" name="followup_score' . $raw->id . '" class="form-control " max="10" min="0" value="' . $raw->followup_score . '"></div></td>';
            if ($painFile_status == 17)
                $html .= '<td width="10%"><div class="input-group"><button type="button" class="btn green" onclick="update_last_followup_goals(' . $raw->id . ')">+</button></div></td>';
            else
                $html .= '<td></td>';
            $html .= '</tr>';
        }
        return $html;
    }


    public function store(Request $request)
    {
        $painFile_id = $request->painFile_id;
        $followup = FollowupLast::where('pain_file_id', $painFile_id)->first();
        if (isset($followup)) {
            $followup->pain_scale = $request->pain_scale;
            $followup->pain_bothersomeness = $request->pain_bothersomeness;
            $followup->pain_intensity_during_rest = $request->pain_intensity_during_rest;
            $followup->pain_intensity_during_activity = $request->pain_intensity_during_activity;
            $followup->health_rate = $request->health_rate;
            $followup->phq_nervous = $request->phq_nervous;
            $followup->phq_worry = $request->phq_worry;
            $followup->phq_little_interest = $request->phq_little_interest;
            $followup->phq_feelingdown = $request->phq_feelingdown;
            $followup->pcs_thinking_hurts = $request->pcs_thinking_hurts;
            $followup->pcs_overwhelms_pain = $request->pcs_overwhelms_pain;
            $followup->pcs_afraid_pain = $request->pcs_afraid_pain;
            $followup->pcl5_score = $request->pcl5_score;
            $followup->overall_status = $request->overall_status;
            $followup->treatment_satisfied = $request->treatment_satisfied;
            $followup->nurse_message = $request->nurse_message;
            $followup->created_by = auth()->user()->id;
            $followup->org_id = auth()->user()->org_id;
            if ($followup->save())
                return response()->json(['success' => true]);
            return response()->json(['success' => false]);
        }
        return response()->json(['success' => false]);
    }

    public function update_last_followup_goal(Request $request)
    {
        $id = $request->id;
        $goal = FollowupLastTreatmentGoal::find($id);
        if (isset($goal)) {
            $goal->followup_score = $request->followup_score;
            if ($goal->save())
                return response()->json(['success' => true]);
            return response()->json(['success' => false]);
        }
    }

    public function new_lastfollowup(Request $request)
    {
        //dd($request->all());
        $followup_date = $request->new_followup_date;
        $pain_file_id = $request->painFile_id;
        $painFile_status = $request->painFile_status;
        $patientid = $request->patientid;
        $followup_count = FollowupLast::where('pain_file_id', $pain_file_id)->count();
        //dd($followup_count);
        if ($followup_count == 0) {
            $followup = new FollowupLast();
            $followup->pain_file_id = $pain_file_id;
            $followup->follow_up_date = $followup_date;
            $followup->org_id = auth()->user()->org_id;
            $followup->created_by = auth()->user()->id;
            if ($followup->save()) {
                $painfile = PainFile::find($pain_file_id);
                $painfile->last_followup_date = $followup_date;
                $painfile->save();
                $baselineGoals = BaselineTreatmentGoal::where('pain_file_id', $pain_file_id)->get();
                if (isset($baselineGoals))
                    foreach ($baselineGoals as $baselineGoal) {

                        $followupGoal = new FollowupLastTreatmentGoal();
                        $followupGoal->pain_file_id = $pain_file_id;
                        $followupGoal->followup_id = $followup->id;
                        $followupGoal->baseline_goal_id = $baselineGoal->id;
                        $followupGoal->org_id = auth()->user()->org_id;
                        $followupGoal->created_by = auth()->user()->id;
                        $followupGoal->save();

                    }
            }
            return redirect()->to('lastfollowup/create/' . $pain_file_id . '/' . $patientid . '/' . $painFile_status);

        }
        return redirect()->to('lastfollowup/create/' . $pain_file_id . '/' . $patientid . '/' . $painFile_status);

    }

    public function close_painfile(Request $request)
    {
        $pain_file_id = $request->painFile_id;
        $painFile = PainFile::where('id', $pain_file_id)->first();

        if (isset($painFile))
            // if ($total_followup_count == 0)
            if ($request->close_reasons == 516)
                $painFile->status = 514;
            else
                $painFile->status = 18;
        $painFile->close_reasons = $request->close_reasons;
        $painFile->close_reasons_details = $request->close_reasons_details;
        if ($painFile->save())
            // return redirect('');
            return response()->json(['success' => true]);
        return response()->json(['success' => false]);

    }

    public function insert_pcl_for_patient(Request $request)
    {
        $painFile_id = $request->painFile_id;
        $patient_detail_count = FollowupLastPclEvaluation::where('pain_file_id', $painFile_id)->count();
        if ($patient_detail_count == 0) {
            $questions = Lookup::where('parent_id', '=', '428')->get();
            foreach ($questions as $question) {

                $evaluation = new FollowupLastPclEvaluation();
                $evaluation->pain_file_id = $painFile_id;
                $evaluation->eval_question_id = $question->id;
                $evaluation->eval_question_order = $question->lookup_order;
                $evaluation->created_by = auth()->user()->id;
                $evaluation->org_id = auth()->user()->org_id;
                $evaluation->save();
            }
            return response()->json(['success' => true]);
        } else if ($patient_detail_count > 0)
            return response()->json(['success' => true]);
        return response()->json(['success' => false]);
    }

    public function save_pcl_answers(Request $request)
    {
        $painFile_id = $request->painFile_id;
        $eval_id = $request->eval_id;
        // dd($request->eval_answer);
        $evaluation = FollowupLastPclEvaluation::where('id', '=', $request->eval_id)->first();
        if (isset($evaluation)) {

            $evaluation->eval_answer = $request->eval_answer;
            if ($evaluation->save()) {

                $pcl_evaluation_sum = FollowupLastPclEvaluation::where('pain_file_id', $painFile_id)->sum('eval_answer');

                // $baseline = BaselineNurseConsultation::where('pain_file_id', $painFile_id)->first();

                // $baseline->pcl5_score = $pcl_evaluation_sum;
                // if ($baseline->save()) {
                return response()->json(['success' => true, 'pcl5_score' => $pcl_evaluation_sum]);
                //  }
            }
            return response()->json(['success' => false]);

        }
    }

    public function get_pcl_patient_eval(Request $request)
    {
        $baselinePclEvals = FollowupLastPclEvaluation::join('lookup', 'followup_last_pcl_evaluations.eval_question_id', '=', 'lookup.id')
            ->where('pain_file_id', $request->painFile_id)
            ->orderBy('followup_last_pcl_evaluations.eval_question_order')
            ->get(['followup_last_pcl_evaluations.id', 'eval_question_id', 'lookup.lookup_details as eval_question_desc', 'eval_answer']);

        $html = '';

        $html = '';
        $html .= '<table class="table table-striped- table-bordered table-hover" > ';
        $html .= '<tr>';
        $html .= '<th width="50%">In the past month, how much were you bothered by:</th>';
        $html .= '<th>Not at all</th>';
        $html .= '<th>A little bit</th>';
        $html .= '<th>Moderately</th>';
        $html .= '<th>Quite a bit</th>';
        $html .= '<th>Extremely</th>';
        $html .= ' </tr> ';
        foreach ($baselinePclEvals as $det) {
            $html .= ' <tr>';
            $html .= ' <td>' . $det['eval_question_desc'] . ' </td> ';
            $html .= '<td ><label class="kt-radio-inline" >
								<input type = "radio"
									   name = "EVAL_ANSWER_ID' . $det['eval_question_id'] . '"  
									   id = "EVAL_ANSWER_ID0" 
									   value = "0"  
									   ' . ($det['eval_answer'] == '0' ? "checked='checked'" : '') . ' onclick="save_eval_answer(0,' . $det['id'] . ' );">
								<span ></span >
							</label >&nbsp;</td > ';
            $html .= '<td ><label class="kt-radio-inline" >
								<input type = "radio"
									   name = "EVAL_ANSWER_ID' . $det['eval_question_id'] . '"  
									   id = "EVAL_ANSWER_ID1" 
									   value = "1" 
									   ' . ($det['eval_answer'] == 1 ? "checked='checked'" : "") . '  onclick="save_eval_answer(1,' . $det['id'] . ' );" > 
								<span ></span >
							</label >&nbsp;</td > ';
            $html .= '<td ><label class="kt-radio-inline" >
								<input type = "radio"
									   name = "EVAL_ANSWER_ID' . $det['eval_question_id'] . '"  
									   id = "EVAL_ANSWER_ID2" 
									   value = "2"  
									   ' . ($det['eval_answer'] == 2 ? "checked='checked'" : "") . '  onclick="save_eval_answer(2,' . $det['id'] . ' );"> 
								<span ></span >
							</label >&nbsp;</td > ';
            $html .= '<td ><label class="kt-radio-inline" >
								<input type = "radio"
									   name = "EVAL_ANSWER_ID' . $det['eval_question_id'] . '"  
									   id = "EVAL_ANSWER_ID3" 
									   value = "3"  
									   ' . ($det['eval_answer'] == 3 ? "checked='checked'" : "") . '  onclick="save_eval_answer(3,' . $det['id'] . ' );" > 
								<span ></span >
							</label >&nbsp; </td > ';
            $html .= '<td ><label class="kt-radio-inline" >
								<input type = "radio"
									   name = "EVAL_ANSWER_ID' . $det['eval_question_id'] . '"  
									   id = "EVAL_ANSWER_ID4" 
									   value = "4" 
									   ' . ($det['eval_answer'] == 4 ? "checked='checked'" : "") . '  onclick="save_eval_answer(4,' . $det['id'] . ' );"> 
								<span ></span >
							</label >&nbsp; </td > ';

            $html .= '</tr > ';
        }
        $html .= '</table>';
        return response()->json(['success' => true, 'followupLastPclEval' => $html]);
    }

    public function get_close_reason_details_list($parent_id, $close_reasons_details)
    {
        $close_reason_list = get_lookups_list($parent_id);
        $html = '<option value="">Select...</option>';
        foreach ($close_reason_list as $raw) {
            $selected = '';
            if (isset($close_reasons_details) && $raw->id == $close_reasons_details)
                $selected = 'selected="selected"';
            $html .= '<option value="' . $raw->id . '" ' . $selected . '>' . $raw->lookup_details . '</option>';
        }
        return $html;
    }
    public function get_close_reason_details_list_by_parent_id(Request $request)
    {
        $close_reason_list = get_lookups_list($request->parent_id);
        $html = '<option value="">Select...</option>';
        foreach ($close_reason_list as $raw) {
            $html .= '<option value="' . $raw->id . '">' . $raw->lookup_details . '</option>';
        }
        return response()->json(['success' => true, 'html' => $html]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
