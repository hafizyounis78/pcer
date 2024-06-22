<?php

namespace App\Http\Controllers;

use App\Appointments;
use App\BaselineNurseConsultation;
use App\BaselinePainDistribution;
use App\BaselineTreatmentGoal;
use App\FollowupTreatmentGoal;
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
            $patient_detail->weekly_hours = $request->weekly_hours;
            $patient_detail->monthly_income = $request->monthly_income;
            $patient_detail->isProvider = $request->isProvider;
            $patient_detail->isOnlyProvider = $request->isOnlyProvider;
            $patient_detail->num_of_family = $request->num_of_family;
            $patient_detail->isSmoke = $request->isSmoke;
            $patient_detail->created_by = auth()->user()->id;
            $patient_detail->org_id = auth()->user()->org_id;
            $patient_detail->save();
        } else {
            $patient_detail = PatientDetail::where('pain_file_id', $painFile_id)->first();
            $patient_detail->no_of_child = $request->no_of_child;
            $patient_detail->education = $request->education;
            $patient_detail->current_work = $request->current_work;
            $patient_detail->weekly_hours = $request->weekly_hours;
            $patient_detail->monthly_income = $request->monthly_income;
            $patient_detail->isProvider = $request->isProvider;
            $patient_detail->isOnlyProvider = $request->isOnlyProvider;
            $patient_detail->num_of_family = $request->num_of_family;
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


    public function insert_treatment_goal(Request $request)
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

    public function delete_treatment_goal(Request $request)
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
