<?php

namespace App\Http\Controllers;

use App\Appointments;
use App\BaselineNurseConsultation;
use App\BaselinePainDistribution;
use App\BaselinePclEvaluation;
use App\BaselinePhysiotherapy;
use App\BaselineTreatmentGoal;
use App\FollowupTreatmentGoal;
use App\Lookup;
use App\PatientDetail;
use Illuminate\Http\Request;

class BaselineNurseController extends Controller
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $painFile_id = $request->painFile_id;
        $patient_detail_count = PatientDetail::where('pain_file_id', $painFile_id)->count();
        if ($patient_detail_count == 0) {
            $patient_detail = new PatientDetail();
            $patient_detail->pain_file_id = $painFile_id;
            $patient_detail->no_of_child = $request->no_of_child;
            $patient_detail->education = $request->education;
            $patient_detail->current_work = $request->current_work;
            $patient_detail->other_current_work = $request->other_current_work;
            $patient_detail->weekly_hours = $request->weekly_hours;
            $patient_detail->monthly_income = $request->monthly_income;
         /*   $patient_detail->isProvider = $request->isProvider;
            $patient_detail->isOnlyProvider = $request->isOnlyProvider;
            $patient_detail->num_of_family = $request->num_of_family;*/
            $patient_detail->isSmoke = $request->isSmoke;
            $patient_detail->created_by = auth()->user()->id;
            $patient_detail->org_id = auth()->user()->org_id;
            $patient_detail->save();
        } else {
            $patient_detail = PatientDetail::where('pain_file_id', $painFile_id)->first();
            $patient_detail->no_of_child = $request->no_of_child;
            $patient_detail->education = $request->education;
            $patient_detail->current_work = $request->current_work;
            $patient_detail->other_current_work = $request->other_current_work;
            $patient_detail->weekly_hours = $request->weekly_hours;
            $patient_detail->monthly_income = $request->monthly_income;
          /*  $patient_detail->isProvider = $request->isProvider;
            $patient_detail->isOnlyProvider = $request->isOnlyProvider;
            $patient_detail->num_of_family = $request->num_of_family;*/
            $patient_detail->isSmoke = $request->isSmoke;
            $patient_detail->save();

        }
        $baseline = BaselineNurseConsultation::where('pain_file_id', $painFile_id)->count();
        if ($baseline == 0) {
            $baseline = new BaselineNurseConsultation();
            $baseline->pain_file_id = $painFile_id;
            $baseline->visit_date = $request->visit_date_nurse;
            $baseline->pain_duration = $request->pain_duration;
            $baseline->temporal_aspects = $request->temporal_aspects;
            $baseline->pain_scale = $request->pain_scale;
            $baseline->pain_intensity_during_rest = $request->pain_intensity_during_rest;
            $baseline->pain_intensity_during_activity = $request->pain_intensity_during_activity;
            $baseline->pain_bothersomeness = $request->pain_bothersomeness;
            $baseline->health_rate = $request->health_rate;
            $baseline->phq_nervous = $request->phq_nervous;
            $baseline->phq_worry = $request->phq_worry;
            $baseline->phq_little_interest = $request->phq_little_interest;
            $baseline->phq_feelingdown = $request->phq_feelingdown;
            $baseline->pcs_thinking_hurts = $request->pcs_thinking_hurts;
            $baseline->pcs_overwhelms_pain = $request->pcs_overwhelms_pain;
            $baseline->pcs_afraid_pain = $request->pcs_afraid_pain;
            $baseline->pcl5_score = $request->pcl5_score;
            $baseline->lab_scan = $request->lab_scan;
            $baseline->image_scan = $request->image_scan;
            $baseline->nurse_message = $request->nurse_message;
            $baseline->created_by = auth()->user()->id;
            $baseline->org_id = auth()->user()->org_id;
            if ($baseline->save()) {
                $location_ids = $request->get('location_id');
                if (isset($location_ids))
                    foreach ($location_ids as $option => $value) {
                        $PainDistribution = new BaselinePainDistribution();
                        $PainDistribution->location_id = $value;
                        $PainDistribution->pain_file_id = $painFile_id;
                        $PainDistribution->created_by = auth()->user()->id;
                        $PainDistribution->org_id = auth()->user()->org_id;
                        $PainDistribution->save();
                    }
                $appointment = Appointments::where('pain_file_id', $painFile_id)
                    ->whereDate('attend_date', '=', date('Y-m-d'))->first();
                if (isset($appointment)) {
                    $appointment->current_stage = 2;
                    $appointment->save();
                }
                return response()->json(['success' => true]);

            }
        } else {
            $baseline = BaselineNurseConsultation::where('pain_file_id', $painFile_id)->first();
            $baseline->pain_file_id = $painFile_id;
            $baseline->visit_date = $request->visit_date_nurse;
            $baseline->pain_duration = $request->pain_duration;
            $baseline->temporal_aspects = $request->temporal_aspects;
            $baseline->pain_scale = $request->pain_scale;
            $baseline->pain_bothersomeness = $request->pain_bothersomeness;
            $baseline->pain_intensity_during_rest = $request->pain_intensity_during_rest;
            $baseline->pain_intensity_during_activity = $request->pain_intensity_during_activity;
            $baseline->health_rate = $request->health_rate;
            $baseline->phq_nervous = $request->phq_nervous;
            $baseline->phq_worry = $request->phq_worry;
            $baseline->phq_little_interest = $request->phq_little_interest;
            $baseline->phq_feelingdown = $request->phq_feelingdown;
            $baseline->pcs_thinking_hurts = $request->pcs_thinking_hurts;
            $baseline->pcs_overwhelms_pain = $request->pcs_overwhelms_pain;
            $baseline->pcs_afraid_pain = $request->pcs_afraid_pain;
            $baseline->pcl5_score = $request->pcl5_score;
            $baseline->lab_scan = $request->lab_scan;
            $baseline->image_scan = $request->image_scan;
            $baseline->nurse_message = $request->nurse_message;
            if ($baseline->save()) {
                $del_PainDistribution = BaselinePainDistribution::where('pain_file_id', $painFile_id)->delete();
                $location_ids = $request->get('location_id');
                if (isset($location_ids))
                    foreach ($location_ids as $option => $value) {
                        $PainDistribution = new BaselinePainDistribution();
                        $PainDistribution->location_id = $value;
                        $PainDistribution->pain_file_id = $painFile_id;
                        $PainDistribution->created_by = auth()->user()->id;
                        $PainDistribution->org_id = auth()->user()->org_id;
                        $PainDistribution->save();
                    }

                return response()->json(['success' => true]);

            }
        }
        return response()->json(['success' => false, 'html' => '']);

    }

    public function insert_pcl_for_patient(Request $request)
    {
        $painFile_id = $request->painFile_id;
        $patient_detail_count = BaselinePclEvaluation::where('pain_file_id', $painFile_id)->count();
        if ($patient_detail_count == 0) {
            $questions = Lookup::where('parent_id', '=', '428')->get();
            foreach ($questions as $question) {

                $evaluation = new BaselinePclEvaluation();
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
        $evaluation = BaselinePclEvaluation::where('id', '=', $request->eval_id)->first();
        if (isset($evaluation)) {

            $evaluation->eval_answer = $request->eval_answer;
            if ($evaluation->save()) {

                $pcl_evaluation_sum = BaselinePclEvaluation::where('pain_file_id', $painFile_id)->sum('eval_answer');

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
        $baselinePclEvals = BaselinePclEvaluation::join('lookup', 'baseline_pcl_evaluations.eval_question_id', '=', 'lookup.id')
            ->where('pain_file_id', $request->painFile_id)
            ->orderBy('baseline_pcl_evaluations.eval_question_order')
            ->get(['baseline_pcl_evaluations.id', 'eval_question_id', 'lookup.lookup_details as eval_question_desc', 'eval_answer']);

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
        return response()->json(['success' => true, 'baselinePclEval' => $html]);
    }


////////////////////////////
    public function save_physiotherapy_chk(Request $request)
    {
        $painFile_id = $request->painFile_id;
        $physiotherapy_id = $request->physiotherapy_id;
        if ($request->checked == 0) {
            $model = BaselinePhysiotherapy::where('physiotherapy_program_id', '=', $physiotherapy_id)
                ->where('pain_file_id', '=', $painFile_id)->first();
            if (isset($model)) {
                $model->delete();
                $ids = Lookup::where('parent_id', $physiotherapy_id)->pluck('id')->toArray();
                $model = BaselinePhysiotherapy::whereIn('physiotherapy_program_id', $ids)
                    ->where('pain_file_id', '=', $painFile_id)->delete();
                return response()->json(['success' => true]);
            } else
                return response()->json(['success' => false]);
        } else if ($request->checked == 1) {
            $model = BaselinePhysiotherapy::where('physiotherapy_program_id', '=', $physiotherapy_id)
                ->where('pain_file_id', '=', $painFile_id)->first();
            if (isset($model)) {
                return response()->json(['success' => true]);
            } else {
                $model = new BaselinePhysiotherapy();
                $model->pain_file_id = $painFile_id;
                $model->physiotherapy_program_id = $physiotherapy_id;
                $model->created_by = auth()->user()->id;
                $model->org_id = auth()->user()->org_id;
                if ($model->save())
                    return response()->json(['success' => true]);
                else
                    return response()->json(['success' => false]);
            }
        } else
            return response()->json(['success' => false]);
    }

    public
    function update_physio_chk_compliance(Request $request)
    {
        $painFile_id = $request->painFile_id;
        $physiotherapy_id = $request->physiotherapy_id;
        $compliance = $request->compliance;
        $model = BaselinePhysiotherapy::where('physiotherapy_program_id', '=', $physiotherapy_id)
            ->where('pain_file_id', '=', $painFile_id)->first();
        if (isset($model)) {
            $model->compliance = $compliance;
            if ($model->save())
                return response()->json(['success' => true]);
            else
                return response()->json(['success' => false]);
        }
        return response()->json(['success' => false]);
    }

////////////////////////////
    public
    function insert_treatment_goal(Request $request)
    {
        $painFile_id = $request->painFile_id;
        $painFile_status = $request->painFile_status;
        $mediction = new BaselineTreatmentGoal();
        $mediction->pain_file_id = $painFile_id;
        $mediction->baseline_goal = $request->baseline_nurse_goal;

        $mediction->baseline_goal_score = $request->baseline_nurse_goal_score;
        $mediction->baseline_score = $request->baseline_nurse_current_goal_score;
        $mediction->org_id = auth()->user()->org_id;
        $mediction->created_by = auth()->user()->id;
        if ($mediction->save()) {
            $doctor_html = $this->draw_treatment_goal_doctor($painFile_id, $painFile_status);
            $nurse_html = $this->draw_treatment_goal_nurse($painFile_id, $painFile_status);
            return response()->json(['success' => true, 'nurse_html' => $nurse_html, 'doctor_html' => $doctor_html]);
        }
        return response()->json(['success' => false, 'nurse_html' => '', 'doctor_html' => '']);


    }

    public
    function delete_treatment_goal(Request $request)
    {
        $id = $request->id;
        $painFile_id = $request->painFile_id;
        $painFile_status = $request->painFile_status;
        $model = BaselineTreatmentGoal::find($id);
        if (isset($model)) {
            $model->delete();
            $followup_treatment_goals = FollowupTreatmentGoal::where('baseline_goal_id', $id)->delete();

            $doctor_html = $this->draw_treatment_goal_doctor($painFile_id, $painFile_status);
            $nurse_html = $this->draw_treatment_goal_nurse($painFile_id, $painFile_status);
            return response()->json(['success' => true, 'nurse_html' => $nurse_html, 'doctor_html' => $doctor_html]);

        } else {
            return response()->json(['success' => false, 'nurse_html' => '', 'doctor_html' => '']);
        }

    }

    public
    function update_treatment_goal(Request $request)
    {
        $id = $request->id;
        $painFile_id = $request->painFile_id;
        $painFile_status = $request->painFile_status;
        $model = BaselineTreatmentGoal::find($id);
        if (isset($model)) {
            $model->baseline_goal = $request->baseline_goal;
            $model->baseline_goal_score = $request->baseline_goal_score;
            $model->baseline_score = $request->baseline_current_score;
            $model->save();
            $doctor_html = $this->draw_treatment_goal_doctor($painFile_id, $painFile_status);
            $nurse_html = $this->draw_treatment_goal_nurse($painFile_id, $painFile_status);
            return response()->json(['success' => true, 'nurse_html' => $nurse_html, 'doctor_html' => $doctor_html]);

        } else {
            return response()->json(['success' => false, 'nurse_html' => '', 'doctor_html' => '']);
        }

    }

    public
    function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public
    function edit($id)
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
    public
    function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public
    function destroy($id)
    {
        //
    }
}
