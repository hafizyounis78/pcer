<?php

namespace App\Http\Controllers;

use App\Appointments;
use App\FollowupNurse;
use App\FollowupTreatmentGoal;
use Illuminate\Http\Request;

class FollowupNurseController extends Controller
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
        //    dd($request->all());
        $painFile_id = $request->painFile_id;
        $followup = FollowupNurse::where('pain_file_id', $painFile_id)->where('followup_id', $request->followup_id)->count();
        if ($followup == 0) {
            $followup = new FollowupNurse();
            $followup->pain_file_id = $painFile_id;
            $followup->followup_id = $request->followup_id;
            $followup->follow_up_date = $request->nurse_follow_up_date;
            $followup->second_nurse = $request->second_nurse;
            $followup->treatment_goal_achievements = $request->nurse_treat_goal_achiev;
            $followup->pain_scale = $request->pain_scale;
            $followup->pain_bothersomeness = $request->pain_bothersomeness;
            $followup->pain_intensity_during_rest = $request->pain_intensity_during_rest;
            $followup->pain_intensity_during_activity = $request->pain_intensity_during_activity;
            $followup->created_by = auth()->user()->id;
            $followup->org_id = auth()->user()->org_id;
            if ($followup->save()) {
                if ($request->nurse_follow_up_date == date('Y-m-d')) {
                    $appointment = Appointments::where('pain_file_id', $painFile_id)
                        ->whereDate('attend_date', '=', date('Y-m-d'))->first();
                    if (isset($appointment)) {
                        $appointment->current_stage = 2;
                        $appointment->save();
                    }
                }
                return response()->json(['success' => true]);
            }
            return response()->json(['success' => false]);
        } else {
            $followup = FollowupNurse::where('pain_file_id', $painFile_id)->where('followup_id', $request->followup_id)->first();
            $followup->second_nurse = $request->second_nurse;
            $followup->treatment_goal_achievements = $request->nurse_treat_goal_achiev;
            $followup->pain_scale = $request->pain_scale;
            $followup->pain_bothersomeness = $request->pain_bothersomeness;
            $followup->pain_intensity_during_rest = $request->pain_intensity_during_rest;
            $followup->pain_intensity_during_activity = $request->pain_intensity_during_activity;
            $followup->nurse_message = $request->nurse_message;
            $followup->created_by = auth()->user()->id;
            $followup->updated_at = date('Y-m-d');

            if ($followup->save())
                return response()->json(['success' => true]);
            return response()->json(['success' => false]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update_treatment_goal(Request $request)
    {
        // dd($request->all());
        $id = $request->id;
        //dd($id);
        $followup_score = $request->followup_score;
        $followup_compliance = $request->followup_compliance;
        $physical_treatment = $request->physical_treatment;
        $days_on_prg = $request->days_on_prg;

        $followupGoal = FollowupTreatmentGoal::find($id);
        if (isset($followupGoal)) {
            $followupGoal->followup_score = $followup_score;
            $followupGoal->followup_compliance = $followup_compliance;
            $followupGoal->physical_treatment = $physical_treatment;
            $followupGoal->days_on_prg = $days_on_prg;
            $followupGoal->updated_at = date('Y-m-d');
            if ($followupGoal->save())
                return response()->json(['success' => true]);
            return response()->json(['success' => false]);
        } else
            return response()->json(['success' => false]);
    }

    public function get_followup_nurse(Request $request)
    {
        //dd($request->all());
        $painFile_id = $request->painFile_id;
        $painFile_status = $request->painFile_status;
        $followupNurse = FollowupNurse::where('followup_id', $request->followup_id)->first();
        $treat_nurse_goal = $this->get_treat_nurse_goal($painFile_id, $request->followup_id, $painFile_status);

        return response()->json(['success' => true, 'followupNurse' => $followupNurse, 'treat_nurse_goal' => $treat_nurse_goal]);
    }

    public function get_treat_nurse_goal($painFile_id, $last_follow_id, $painFile_status)
    {
        return $this->draw_followup_treat_nurse_goal($painFile_id, $last_follow_id, $painFile_status);
    }

    public function check_available_date(Request $request)
    {
        $followup_date = $request->followup_date;
        $count = FollowupNurse::whereDate('follow_up_date', $followup_date)->count();
        return response()->json(['success' => true, 'count' => $count]);
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
