<?php

namespace App\Http\Controllers;

use App\Appointments;
use App\BaselineDassEvaluation;
use App\BaselineMentalHealth;
use App\BaselinePharmacistConsultation;
use App\BaselinePsychologyConsultation;
use App\BaselinePsychologyDiagnose;
use App\DassEvaluationQuestion;
use Illuminate\Http\Request;

class BaselinePsychologyController extends Controller
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

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $painFile_id = $request->painFile_id;
        $baseline = BaselinePsychologyConsultation::where('pain_file_id', $painFile_id)->count();
        if ($baseline == 0) {
            $baseline = new BaselinePsychologyConsultation();
            $baseline->pain_file_id = $painFile_id;
            $baseline->visit_date = $request->visit_date_psychology;
            $baseline->depression_score = $request->depression_score;
            $baseline->depression_degree = $request->depression_degree;
            $baseline->anxiety_score = $request->anxiety_score;
            $baseline->anxiety_degree = $request->anxiety_degree;
            $baseline->stress_score = $request->stress_score;
            $baseline->stress_degree = $request->stress_degree;
            // $baseline->psychologic_diagnose = $request->psychologic_diagnose;
            $baseline->suggested_actions = $request->suggested_actions;
            $baseline->suggested_actions_others = $request->suggested_actions_others;
            $baseline->physical_exam = $request->physical_exam;
            $baseline->created_by = auth()->user()->id;
            $baseline->org_id = auth()->user()->org_id;
            if ($baseline->save()) {
                $mental_health = BaselineMentalHealth::where('pain_file_id', $painFile_id)->count();
                if ($mental_health == 0) {
                    $mental_health = new BaselineMentalHealth();
                    $mental_health->pain_file_id = $painFile_id;
                    $mental_health->eating_disorder = $request->eating_disorder;
                    $mental_health->sleep_disturbances = $request->sleep_disturbances;
                    $mental_health->psychopath_in_family = $request->psychopath_in_family;
                    $mental_health->family_relationship = $request->family_relationship;
                    $mental_health->psychological_problems = $request->psychological_problems;
                    $mental_health->social_problem = $request->social_problem;
                    $mental_health->ability_control_actions = $request->ability_control_actions;
                    $mental_health->ability_control_words = $request->ability_control_words;
                    $mental_health->suicidal_thoughts = $request->suicidal_thoughts;
                    $mental_health->attacked_or_bullied = $request->attacked_or_bullied;
                    $mental_health->bad_dreams = $request->bad_dreams;
                    $mental_health->opioids = $request->opioids;
                    $mental_health->created_by = auth()->user()->id;
                    $mental_health->org_id = auth()->user()->org_id;
                    if (!$mental_health->save())
                        return response()->json(['success' => false]);
                }
                else {
                    $mental_health = BaselineMentalHealth::where('pain_file_id', $painFile_id)->first();;
                    $mental_health->eating_disorder = $request->eating_disorder;
                    $mental_health->sleep_disturbances = $request->sleep_disturbances;
                    $mental_health->psychopath_in_family = $request->psychopath_in_family;
                    $mental_health->family_relationship = $request->family_relationship;
                    $mental_health->psychological_problems = $request->psychological_problems;
                    $mental_health->social_problem = $request->social_problem;
                    $mental_health->ability_control_actions = $request->ability_control_actions;
                    $mental_health->ability_control_words = $request->ability_control_words;
                    $mental_health->suicidal_thoughts = $request->suicidal_thoughts;
                    $mental_health->attacked_or_bullied = $request->attacked_or_bullied;
                    $mental_health->bad_dreams = $request->bad_dreams;
                    $mental_health->opioids = $request->opioids;
                    if (!$mental_health->save())
                        return response()->json(['success' => false]);
                }
                //--------------- Diagnose

                $del_psychologyDiagnose = BaselinePsychologyDiagnose::where('pain_file_id', $painFile_id)->delete();
                $psychologic_diagnose = $request->get('psychologic_diagnose');
                if (isset($psychologic_diagnose))
                    foreach ($psychologic_diagnose as $option => $value) {
                        $psy_diagnose = new BaselinePsychologyDiagnose();
                        $psy_diagnose->diagnostic_id = $value;
                        $psy_diagnose->pain_file_id = $painFile_id;
                        $psy_diagnose->created_by = auth()->user()->id;
                        $psy_diagnose->org_id = auth()->user()->org_id;
                        $psy_diagnose->save();
                    }
                //--------------- End Diagnose
                $appointment = Appointments::where('pain_file_id', $painFile_id)
                    ->whereDate('attend_date', '=', date('Y-m-d'))->first();
                if (isset($appointment)) {
                    $appointment->current_stage = 3;
                    $appointment->save();
                }
                return response()->json(['success' => true]);

            }
        }
        else {
            $baseline = BaselinePsychologyConsultation::where('pain_file_id', $painFile_id)->first();
            $baseline->visit_date = $request->visit_date_psychology;
            $baseline->depression_score = $request->depression_score;
            $baseline->depression_degree = $request->depression_degree;
            $baseline->anxiety_score = $request->anxiety_score;
            $baseline->anxiety_degree = $request->anxiety_degree;
            $baseline->stress_score = $request->stress_score;
            $baseline->stress_degree = $request->stress_degree;
           // $baseline->psychologic_diagnose = $request->psychologic_diagnose;
            $baseline->suggested_actions = $request->suggested_actions;
            $baseline->suggested_actions_others = $request->suggested_actions_others;
            $baseline->physical_exam = $request->physical_exam;
            if ($baseline->save()) {
                $mental_health = BaselineMentalHealth::where('pain_file_id', $painFile_id)->count();
                if ($mental_health == 0)
                {
                    $mental_health = new BaselineMentalHealth();
                    $mental_health->pain_file_id = $painFile_id;
                    $mental_health->eating_disorder = $request->eating_disorder;
                    $mental_health->sleep_disturbances = $request->sleep_disturbances;
                    $mental_health->psychopath_in_family = $request->psychopath_in_family;
                    $mental_health->family_relationship = $request->family_relationship;
                    $mental_health->psychological_problems = $request->psychological_problems;
                    $mental_health->social_problem = $request->social_problem;
                    $mental_health->ability_control_actions = $request->ability_control_actions;
                    $mental_health->ability_control_words = $request->ability_control_words;
                    $mental_health->suicidal_thoughts = $request->suicidal_thoughts;
                    $mental_health->attacked_or_bullied = $request->attacked_or_bullied;
                    $mental_health->bad_dreams = $request->bad_dreams;
                    $mental_health->opioids = $request->opioids;
                    $mental_health->created_by = auth()->user()->id;
                    $mental_health->org_id = auth()->user()->org_id;
                    if (!$mental_health->save())
                        return response()->json(['success' => false]);
                }
                else {
                    $mental_health = BaselineMentalHealth::where('pain_file_id', $painFile_id)->first();;
                    $mental_health->eating_disorder = $request->eating_disorder;
                    $mental_health->sleep_disturbances = $request->sleep_disturbances;
                    $mental_health->psychopath_in_family = $request->psychopath_in_family;
                    $mental_health->family_relationship = $request->family_relationship;
                    $mental_health->psychological_problems = $request->psychological_problems;
                    $mental_health->social_problem = $request->social_problem;
                    $mental_health->ability_control_actions = $request->ability_control_actions;
                    $mental_health->ability_control_words = $request->ability_control_words;
                    $mental_health->suicidal_thoughts = $request->suicidal_thoughts;
                    $mental_health->attacked_or_bullied = $request->attacked_or_bullied;
                    $mental_health->bad_dreams = $request->bad_dreams;
                    $mental_health->opioids = $request->opioids;
                    if (!$mental_health->save())
                        return response()->json(['success' => false]);
                }
                //--------------------Diagnosis
                $del_psychologyDiagnose = BaselinePsychologyDiagnose::where('pain_file_id', $painFile_id)->delete();
                $psychologic_diagnose = $request->get('psychologic_diagnose');
                if (isset($psychologic_diagnose))
                    foreach ($psychologic_diagnose as $option => $value) {
                        $psy_diagnose = new BaselinePsychologyDiagnose();
                        $psy_diagnose->diagnostic_id = $value;
                        $psy_diagnose->pain_file_id = $painFile_id;
                        $psy_diagnose->created_by = auth()->user()->id;
                        $psy_diagnose->org_id = auth()->user()->org_id;
                        $psy_diagnose->save();
                    }
                //----End   //--------------------Diagnosis
                 return response()->json(['success' => true]);
            }
        }
        return response()->json(['success' => false]);
    }

    public
    function insert_dass_for_patient(Request $request)
    {
        $painFile_id = $request->painFile_id;
        $patient_detail_count = BaselineDassEvaluation::where('pain_file_id', $painFile_id)->count();
        if ($patient_detail_count == 0) {
            $questions = DassEvaluationQuestion::get();
            foreach ($questions as $question) {

                $evaluation = new BaselineDassEvaluation();
                $evaluation->pain_file_id = $painFile_id;
                $evaluation->eval_question_id = $question->eval_question_id;
                $evaluation->eval_question_order = $question->eval_question_order;
                $evaluation->created_by = auth()->user()->id;
                $evaluation->org_id = auth()->user()->org_id;
                $evaluation->save();
            }
            return response()->json(['success' => true]);
        } else if ($patient_detail_count > 0)
            return response()->json(['success' => true]);
        return response()->json(['success' => false]);
    }

    public
    function save_dass_answers(Request $request)
    {
        $painFile_id = $request->painFile_id;
        $eval_id = $request->eval_id;
        // dd($request->eval_answer);
        $evaluation = BaselineDassEvaluation::where('id', '=', $request->eval_id)->first();
        if (isset($evaluation)) {

            $evaluation->eval_answer = $request->eval_answer;
            if ($evaluation->save()) {
                $depression_degree = '';
                $anxiety_degree = '';
                $stress_degree = '';
                //------------------depression
                $depression_score_sum = BaselineDassEvaluation::join('dass_evaluation_questions', 'baseline_dass_evaluations.eval_question_id', '=', 'dass_evaluation_questions.eval_question_id')
                    ->where('eval_question_class', '=', 'D')
                    ->where('pain_file_id', $painFile_id)->sum('eval_answer');
                $depression_score_sum *= 2;
                // dd($depression_score_sum);
                if ($depression_score_sum <= 9)
                    $depression_degree = 0;
                else if ($depression_score_sum >= 10 && $depression_score_sum <= 13)
                    $depression_degree = 1;
                else if ($depression_score_sum >= 14 && $depression_score_sum <= 20)
                    $depression_degree = 2;
                else if ($depression_score_sum >= 21 && $depression_score_sum <= 27)
                    $depression_degree = 3;
                else if ($depression_score_sum >= 28)
                    $depression_degree = 4;

                //--------------------anxiety
                $anxiety_score_sum = BaselineDassEvaluation::join('dass_evaluation_questions', 'baseline_dass_evaluations.eval_question_id', '=', 'dass_evaluation_questions.eval_question_id')
                    ->where('eval_question_class', '=', 'A')
                    ->where('pain_file_id', $painFile_id)->sum('eval_answer');
                $anxiety_score_sum *= 2;
                if ($anxiety_score_sum <= 7)
                    $anxiety_degree = 0;
                else if ($anxiety_score_sum >= 8 && $anxiety_score_sum <= 9)
                    $anxiety_degree = 1;
                else if ($anxiety_score_sum >= 10 && $anxiety_score_sum <= 14)
                    $anxiety_degree = 2;
                else if ($anxiety_score_sum >= 15 && $anxiety_score_sum <= 19)
                    $anxiety_degree = 3;
                else if ($anxiety_score_sum >= 20)
                    $anxiety_degree = 4;

                //**********************stress

                $stress_score_sum = BaselineDassEvaluation::join('dass_evaluation_questions', 'baseline_dass_evaluations.eval_question_id', '=', 'dass_evaluation_questions.eval_question_id')
                    ->where('eval_question_class', '=', 'S')
                    ->where('pain_file_id', $painFile_id)->sum('eval_answer');
                $stress_score_sum *= 2;
                if ($stress_score_sum <= 14)
                    $stress_degree = 0;
                else if ($stress_score_sum >= 15 && $stress_score_sum <= 18)
                    $stress_degree = 1;
                else if ($stress_score_sum >= 19 && $stress_score_sum <= 25)
                    $stress_degree = 2;
                else if ($stress_score_sum >= 26 && $stress_score_sum <= 30)
                    $stress_degree = 3;
                else if ($stress_score_sum >= 34)
                    $stress_degree = 4;

                $degree_class = array(0 => 'label label-success', 1 => 'label label-warning', 2 => 'label bg-yellow-casablanca bg-font-yellow-casablanca', 3 => 'label label-danger', 4 => 'label bg-red-thunderbird bg-font-red-thunderbird');
                $degree_name = array(0 => 'Normal', 1 => 'Mild', 2 => 'Moderate', 3 => 'Severe', 4 => 'Extramely Severe');

                return response()->json(['success' => true,
                    'depression_score' => $depression_score_sum, 'depression_degree' => $depression_degree, 'depression_class' => $degree_class[$depression_degree], 'depression_name' => $degree_name[$depression_degree],
                    'anxiety_score' => $anxiety_score_sum, 'anxiety_degree' => $anxiety_degree, 'anxiety_class' => $degree_class[$anxiety_degree], 'anxiety_name' => $degree_name[$anxiety_degree],
                    'stress_score' => $stress_score_sum, 'stress_degree' => $stress_degree, 'stress_class' => $degree_class[$stress_degree], 'stress_name' => $degree_name[$stress_degree]]);
                //  }
            }
            return response()->json(['success' => false]);

        }
    }

    public
    function get_dass_patient_eval(Request $request)
    {
        $baselinePclEvals = BaselineDassEvaluation::join('dass_evaluation_questions', 'baseline_dass_evaluations.eval_question_id', '=', 'dass_evaluation_questions.eval_question_id')
            ->where('pain_file_id', $request->painFile_id)
            ->orderBy('baseline_dass_evaluations.eval_question_order')
            ->get(['baseline_dass_evaluations.id', 'baseline_dass_evaluations.eval_question_id', 'eval_question_class', 'eval_question as eval_question_desc', 'eval_answer']);

        $html = '';

        $html = '';
        $html .= '<table class="table table-striped- table-bordered table-hover" > ';
        $html .= '<tr>';
        $html .= '<th width="50%">Question:</th>';
        $html .= '<th>0</th>';
        $html .= '<th>1</th>';
        $html .= '<th>2</th>';
        $html .= '<th>3</th>';
        $html .= '<th>4</th>';
        $html .= ' </tr> ';
        foreach ($baselinePclEvals as $det) {
            $html .= ' <tr>';
            $html .= ' <td>' . $det['eval_question_id'] . '- ' . '(' . $det['eval_question_class'] . ')&nbsp;' . $det['eval_question_desc'] . ' </td> ';
            $html .= '<td ><label class="kt-radio-inline" >
								<input type = "radio"
									   name = "EVAL_ANSWER_ID' . $det['eval_question_id'] . '"  
									   id = "EVAL_ANSWER_ID0" 
									   value = "0"  
									   ' . ($det['eval_answer'] == '0' ? "checked='checked'" : '') . ' onclick="save_dass_eval_answer(0,' . $det['id'] . ' );">
								<span ></span >
							</label >&nbsp;</td > ';
            $html .= '<td ><label class="kt-radio-inline" >
								<input type = "radio"
									   name = "EVAL_ANSWER_ID' . $det['eval_question_id'] . '"  
									   id = "EVAL_ANSWER_ID1" 
									   value = "1" 
									   ' . ($det['eval_answer'] == 1 ? "checked='checked'" : "") . '  onclick="save_dass_eval_answer(1,' . $det['id'] . ' );" > 
								<span ></span >
							</label >&nbsp;</td > ';
            $html .= '<td ><label class="kt-radio-inline" >
								<input type = "radio"
									   name = "EVAL_ANSWER_ID' . $det['eval_question_id'] . '"  
									   id = "EVAL_ANSWER_ID2" 
									   value = "2"  
									   ' . ($det['eval_answer'] == 2 ? "checked='checked'" : "") . '  onclick="save_dass_eval_answer(2,' . $det['id'] . ' );"> 
								<span ></span >
							</label >&nbsp;</td > ';
            $html .= '<td ><label class="kt-radio-inline" >
								<input type = "radio"
									   name = "EVAL_ANSWER_ID' . $det['eval_question_id'] . '"  
									   id = "EVAL_ANSWER_ID3" 
									   value = "3"  
									   ' . ($det['eval_answer'] == 3 ? "checked='checked'" : "") . '  onclick="save_dass_eval_answer(3,' . $det['id'] . ' );" > 
								<span ></span >
							</label >&nbsp; </td > ';
            $html .= '<td ><label class="kt-radio-inline" >
								<input type = "radio"
									   name = "EVAL_ANSWER_ID' . $det['eval_question_id'] . '"  
									   id = "EVAL_ANSWER_ID4" 
									   value = "4" 
									   ' . ($det['eval_answer'] == 4 ? "checked='checked'" : "") . '  onclick="save_dass_eval_answer(4,' . $det['id'] . ' );"> 
								<span ></span >
							</label >&nbsp; </td > ';

            $html .= '</tr > ';
        }
        $html .= '</table>';
        return response()->json(['success' => true, 'baselineDassEval' => $html]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
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
