<?php

namespace App\Http\Controllers;

use App\Appointments;
use App\FollowupDignostics;
use App\FollowupDoctor;
use App\FollowupTreatment;
use App\FollowupTreatmentGoal;
use App\User;
use Illuminate\Http\Request;

class FollowupDoctorController extends Controller
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $painFile_id=$request->painFile_id;
        $followup = FollowupDoctor::where('pain_file_id',$painFile_id)->where('followup_id', $request->followup_id)->count();
        if ($followup == 0) {
            $followup = new FollowupDoctor();
            $followup->pain_file_id = $painFile_id;
            $followup->followup_id = $request->followup_id;
            $followup->follow_up_date = $request->doc_follow_up_date;
            $followup->second_doctor = $request->second_doctor;
            $followup->treatment_goal_achievements = $request->treatment_goal_achievements;
            $followup->additional_ptsd = $request->additional_ptsd;
            $followup->diagnostic_specify = $request->diagnostic_specify;
            $followup->change_treatment = $request->change_treatment;
            $followup->physical_treatment = $request->physical_treatment;
            $followup->specify_physical_treatment = $request->specify_physical_treatment;
            $followup->last_scheduled_further_treatment = $request->last_scheduled_further_treatment;
            $followup->last_scheduled_why_treatment_ended = $request->last_scheduled_why_treatment_ended;
            $followup->pain_scale = $request->pain_scale;
            $followup->pain_bothersomeness = $request->pain_bothersomeness;
            $followup->pain_intensity_during_rest = $request->pain_intensity_during_rest;
            $followup->pain_intensity_during_activity = $request->pain_intensity_during_activity;
            $followup->doctor_message = $request->doctor_message;
            $followup->followup_doctor_comment = $request->followup_doctor_comment;
            $followup->followup_doctor_lab= $request->followup_doctor_lab;
            $followup->followup_doctor_rad = $request->followup_doctor_rad;
            $followup->created_by = auth()->user()->id;
            $followup->org_id = auth()->user()->org_id;
            if ($followup->save()) {
                if ($request->doc_follow_up_date == date('Y-m-d')) {
                    $appointment = Appointments::where('pain_file_id', $painFile_id)
                        ->whereDate('attend_date', '=', date('Y-m-d'))->first();
                    if (isset($appointment)) {
                        $appointment->current_stage = 3;
                        $appointment->save();
                    }
                }
                $del_PainDistribution = FollowupDignostics::where('pain_file_id', $painFile_id)
                    ->where('followup_id', $request->followup_id)->delete();
                $diagnosis_id = $request->get('diagnosis_id');
                if (isset($diagnosis_id))
                    foreach ($diagnosis_id as $option => $value) {
                        $PainDistribution = new FollowupDignostics();
                        $PainDistribution->diagnostic_id = $value;
                        $PainDistribution->pain_file_id = $painFile_id;
                        $PainDistribution->followup_id = $request->followup_id;
                        $PainDistribution->created_by = auth()->user()->id;
                        $PainDistribution->org_id = auth()->user()->org_id;
                        $PainDistribution->save();
                    }
                return response()->json(['success' => true]);

            }
        } else {
            $followup = FollowupDoctor::where('pain_file_id', $painFile_id)->where('followup_id', $request->followup_id)->first();
            //$followup->follow_up_date = $request->doc_follow_up_date;
            $followup->second_doctor = $request->second_doctor;
            $followup->treatment_goal_achievements = $request->treatment_goal_achievements;
            $followup->additional_ptsd = $request->additional_ptsd;
            $followup->diagnostic_specify = $request->diagnostic_specify;
            $followup->change_treatment = $request->change_treatment;
            $followup->physical_treatment = $request->physical_treatment;
            $followup->specify_physical_treatment = $request->specify_physical_treatment;
            $followup->last_scheduled_further_treatment = $request->last_scheduled_further_treatment;
            $followup->last_scheduled_why_treatment_ended = $request->last_scheduled_why_treatment_ended;
            $followup->pain_scale = $request->pain_scale;
            $followup->pain_bothersomeness = $request->pain_bothersomeness;
            $followup->pain_intensity_during_rest = $request->pain_intensity_during_rest;
            $followup->pain_intensity_during_activity = $request->pain_intensity_during_activity;
            $followup->doctor_message = $request->doctor_message;
            $followup->followup_doctor_comment = $request->followup_doctor_comment;
            $followup->followup_doctor_lab= $request->followup_doctor_lab;
            $followup->followup_doctor_rad = $request->followup_doctor_rad;
            $followup->created_by = auth()->user()->id;
            $followup->org_id = auth()->user()->org_id;
            if ($followup->save()) {
                $del_PainDistribution = FollowupDignostics::where('pain_file_id', $painFile_id)
                    ->where('followup_id', $request->followup_id)->delete();
                $diagnosis_id = $request->get('diagnosis_id');
                if (isset($diagnosis_id))
                    foreach ($diagnosis_id as $option => $value) {
                        $PainDistribution = new FollowupDignostics();
                        $PainDistribution->diagnostic_id = $value;
                        $PainDistribution->pain_file_id = $painFile_id;
                        $PainDistribution->followup_id = $request->followup_id;
                        $PainDistribution->created_by = auth()->user()->id;
                        $PainDistribution->org_id = auth()->user()->org_id;
                        $PainDistribution->save();
                    }
                return response()->json(['success' => true]);

            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function get_followup_doc(Request $request)
    {
        //dd($request->all());
        $painFile_id=$request->painFile_id;
        $painFile_status = $request->painFile_status;
        $followup_id= $request->followup_id;
        $followupDoctor = FollowupDoctor::where('followup_id', $followup_id)->first();
       // dd($followupDoctor);
        $treat_doc_goal = $this->draw_followup_treat_doc_goal($painFile_id,$followup_id,$painFile_status);
        $treatmentFollowup_data =$this->draw_followup_treatment_doctor($painFile_id,$followup_id,$painFile_status );
        $doc_followup_diagnosis = FollowupDignostics::where('pain_file_id',$painFile_id)->where('followup_id', $followup_id)
            ->pluck('diagnostic_id')->toArray();
        $disable=true;
        //dd($followupDoctor);

        // dd($followupDoctor->created_by);
        $doctor_name='';
        if(isset($followupDoctor->created_by) )
            $doctor_name=User::where('id',$followupDoctor->created_by)->first()->name;
        //  dd($doctor_name);
        if(isset($followupDoctor->created_by) && auth()->user()->id != $followupDoctor->created_by)
            $disable=false;

        return response()->json(['success' => true,'disable'=>$disable,'doctor_name'=>$doctor_name, 'followupDoctor' => $followupDoctor, 'treat_doc_goal' => $treat_doc_goal,
            'treatmentFollowup_data' => $treatmentFollowup_data, 'doc_followup_diagnosis' => $doc_followup_diagnosis]);
    }

    public function getFollowup_Treatment($painFile_id, $last_follow_id)
    {
        $model = FollowupTreatment::where('pain_file_id', $painFile_id)
            ->where('followup_id', $last_follow_id)->get();
        $html = '';
        $i = 1;
        foreach ($model as $raw) {
            $html .= '<tr><td>' . $i++ . '</td>';
            $html .= '<td>' . get_drug_desc($raw->drug_id) . '</td>';
            $html .= '<td>' . $raw->drug_specify . '</td>';
            $html .= '<td></td></tr>';
        }
        return $html;
    }



    public function check_available_date(Request $request)
    {
        $followup_date = $request->followup_date;
        $count = FollowupDoctor::whereDate('follow_up_date', $followup_date)->count();
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
     * @param  int $id
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
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
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
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public
    function destroy($id)
    {
        //
    }
}
