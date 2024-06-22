<?php

namespace App\Http\Controllers;

use App\Appointments;
use App\BaselinePharmacistConsultation;
use App\BaselinePsychologyConsultation;
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
     * @param  \Illuminate\Http\Request  $request
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
            $baseline->physical_exam  = $request->physical_exam;
            $baseline->created_by = auth()->user()->id;
            $baseline->org_id = auth()->user()->org_id;
            if ($baseline->save()) {

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
            $baseline->physical_exam = $request->physical_exam;
            if ($baseline->save()) {
                return response()->json(['success' => true]);

            }
        }
        return response()->json(['success' => false]);
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
