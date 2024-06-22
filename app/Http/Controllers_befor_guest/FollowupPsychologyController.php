<?php

namespace App\Http\Controllers;

use App\FollowupPsychology;
use App\User;
use Illuminate\Http\Request;

class FollowupPsychologyController extends Controller
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $painFile_id=$request->painFile_id;
        $followup = FollowupPsychology::where('pain_file_id',$painFile_id)->where('followup_id', $request->followup_id)->count();
        if ($followup == 0) {
            $followup = new FollowupPsychology();
            $followup->pain_file_id = $painFile_id;
            $followup->followup_id = $request->followup_id;
            $followup->follow_up_date = $request->visit_date_psychology;
            $followup->physical_exam = $request->physical_exam;
            $followup->psychology_message = $request->psychology_message;
            $followup->created_by = auth()->user()->id;
            $followup->org_id = auth()->user()->org_id;
            if ($followup->save()) {
                return response()->json(['success' => true]);
                if ($request->visit_date_psychology == date('Y-m-d')) {
                    $appointment = Appointments::where('pain_file_id', $painFile_id)
                        ->whereDate('attend_date', '=', date('Y-m-d'))->first();
                    if (isset($appointment)) {
                        $appointment->current_stage = 3;
                        $appointment->save();
                    }
                }
            }
        } else {
            $followup = FollowupPsychology::where('pain_file_id', $painFile_id)->where('followup_id', $request->followup_id)->first();
            $followup->physical_exam = $request->physical_exam;
            $followup->psychology_message = $request->psychology_message;
            $followup->created_by = auth()->user()->id;
            $followup->org_id = auth()->user()->org_id;
            if ($followup->save()) {
                return response()->json(['success' => true]);

            }
        }
    }
    public function get_followup_psychology(Request $request)
    {
        //dd($request->all());
       // $painFile_id=$request->painFile_id;
       // $painFile_status = $request->painFile_status;
        $disable=true;
        $followup_id= $request->followup_id;
        $followupPsychology = FollowupPsychology::where('followup_id', $followup_id)->first();


        $doctor_name='';
        if(isset($followupPsychology->created_by) )
            $doctor_name=User::where('id',$followupPsychology->created_by)->first()->name;
        //  dd($doctor_name);
        if(isset($followupPsychology->created_by) && auth()->user()->id != $followupPsychology->created_by)
            $disable=false;

        return response()->json(['success' => true,'disable'=>$disable,'doctor_name'=>$doctor_name, 'followupPsychology' => $followupPsychology]);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
