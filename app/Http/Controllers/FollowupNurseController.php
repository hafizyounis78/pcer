<?php

namespace App\Http\Controllers;

use App\Appointments;
use App\FollowupNurse;
use App\FollowupPhysiotherapy;
use App\FollowupTreatmentGoal;
use App\Lookup;
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
            $followup->health_rate = $request->nurse_health_rate;
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
            $followup->health_rate = $request->nurse_health_rate;
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
        $chk_neck_and_shoulder= $this->get_neck_shoulder_ckb_lookups(524, $painFile_id,$request->followup_id);
        $chk_lower_back = $this->get_lower_back_ckb_lookups(536, $painFile_id,$request->followup_id);
//////
        return response()->json(['success' => true, 'followupNurse' => $followupNurse, 'treat_nurse_goal' => $treat_nurse_goal,
        'chk_neck_and_shoulder' => $chk_neck_and_shoulder,'chk_lower_back' => $chk_lower_back]);
    }
    ////////////////////
    function get_neck_shoulder_ckb_lookups($id, $painFile_id,$followup_id)
    {
        $list_value = get_lookups_list($id);
        $physios = FollowupPhysiotherapy::where('pain_file_id', $painFile_id)->where('followup_id', '=', $followup_id)->get();
        $html = '<div class="form-group col-md-12" id="chk_physiotherapy_' . '"><div class="mt-checkbox-list">';
        $class='hide';
        $checked = '';
        $compliance='';
        foreach ($list_value as $row) {
            foreach ($physios as $physio) {
                $checked = '';

                if ($row['id'] == $physio->physiotherapy_program_id) {
                    if ($row['id'] == 527)
                        $class = '';
                    $checked = 'checked';
                    break;
                }
            }
            $html .= '<label class="mt-checkbox mt-checkbox-outline">' . $row['lookup_details'] .
                '<input type="checkbox" value="' . $row['id'] . '" ' . $checked . ' 
            name="checkbox_'.$followup_id.'_' . $row['id'] . '"  id="checkbox_'.$followup_id.'_' . $row['id'] . '" onclick="save_chk(' . $row['id'] .','.$followup_id. ')"/>
                        <span></span></label>';
        }

        $html .= '</div></div>';
        $list_value2 = get_lookups_list(527);
        $html .= '<br><br><div id="dv_streching_exercise_neck_shoulder_'.$followup_id.'" class="col-md-offset-1 '.$class.'">';
        foreach ($list_value2 as $row) {
            foreach ($physios as $physio) {
                $checked = '';
                $compliance='';
                if ($row['id'] == $physio->physiotherapy_program_id) {
                    $compliance=$physio->compliance;
                    $checked = 'checked';
                    break;
                }
            }
            $html .= '<div class="row">
                                <div class=" form-group col-md-2" id="chk_phys_details_">
                                  <div class="mt-checkbox-list">
                                    <label class="mt-checkbox mt-checkbox-outline">' . $row['lookup_details'] . '
                                        <input class="child_chk_527_'.$followup_id.'" type="checkbox" value="' . $row['id'] . '" '.$checked.' 
                                         name="checkbox_'.$followup_id.'_' . $row['id'] . '"  id="checkbox_'.$followup_id.'_' . $row['id'] . '" onclick="save_chk(' . $row['id'] .','.$followup_id. ')"/>
                                        <span> </span>
                                    </label>
                                  </div>
                                </div>
                                <div class="col-md-2">
                                    <select class="form-control child_select_527_'.$followup_id.'" name="compliance_'.$followup_id.'_'. $row['id'] . '" 
                                    id="compliance_'.$followup_id.'_'. $row['id'] . '" onchange="update_ck_compliance(' . $row['id'] .','.$followup_id. ');">
                                        <option value="">Select..</option>
                                        <option value="0" '.(($compliance===0)?'selected':'').'>None</option>
                                        <option value="1" '.(($compliance==1)?'selected':'').'>partial</option>
                                        <option value="2" '.(($compliance==2)?'selected':'').'>good</option>
                                    </select>
                                </div>
                              </div>';
        }
        $html .= '</div>';

        return $html;
    }

    function get_lower_back_ckb_lookups($id,$painFile_id,$followup_id)
    {
        $list_value = get_lookups_list($id);
        $physios = FollowupPhysiotherapy::where('pain_file_id', $painFile_id)->where('followup_id', '=', $followup_id)->get();
        $html = '<div class="form-group col-md-12" id="chk_physiotherapy_' . '"><div class="mt-checkbox-list">';
        $class='hide';
        $checked = '';
        $compliance='';
        foreach ($list_value as $row) {
            foreach ($physios as $physio) {
                $checked = '';

                if ($row['id'] == $physio->physiotherapy_program_id) {
                    if ($row['id'] == 539)
                        $class = '';
                    $checked = 'checked';
                    break;
                }
            }
            $html .= '<label class="mt-checkbox mt-checkbox-outline">' . $row['lookup_details'] .
                '<input type="checkbox" value="' . $row['id'] . '" '.$checked.'
            name="checkbox_'.$followup_id.'_' . $row['id'] . '"  id="checkbox_'.$followup_id.'_' . $row['id'] . '" onclick="save_chk(' . $row['id'] .','.$followup_id. ')"/>
                        <span></span></label>';
        }
        $html .= '</div></div>';
        $list_value2 = get_lookups_list(539);
        $html .= '<br><br><div id="dv_streching_exercise_lower_back_'.$followup_id.'" class="col-md-offset-1 '.$class.'">';
        foreach ($list_value2 as $row) {
            foreach ($physios as $physio) {
                $checked = '';
                $compliance='';
                if ($row['id'] == $physio->physiotherapy_program_id) {
                    $compliance=$physio->compliance;
                    $checked = 'checked';
                    break;
                }
            }
            $html .= '<div class="row">
                                <div class=" form-group col-md-3" id="chk_phys_details_">
                                  <div class="mt-checkbox-list">
                                    <label class="mt-checkbox mt-checkbox-outline">' . $row['lookup_details'] . '
                                        <input class="child_chk_539_'.$followup_id.'" type="checkbox" value="' . $row['id'] . '"  '.$checked.'
            name="checkbox_'.$followup_id.'_' . $row['id'] . '"  id="checkbox_'.$followup_id.'_' . $row['id'] . '" onclick="save_chk(' . $row['id'] .','.$followup_id. ')"/>
                                        <span> </span>
                                    </label>
                                  </div>
                                </div>
                                <div class="col-md-2">
                                    <select class="form-control child_select_539_'.$followup_id.'" name="compliance_'.$followup_id.'_'. $row['id'] . '" 
                                    id="compliance_'.$followup_id.'_'. $row['id'] . '" onchange="update_ck_compliance(' . $row['id'] .','.$followup_id. ');">
                                        <option value="">Select..</option>
                                        <option value="0" '.(($compliance===0)?'selected':'').'>None</option>
                                        <option value="1" '.(($compliance==1)?'selected':'').'>partial</option>
                                        <option value="2" '.(($compliance==2)?'selected':'').'>good</option>
                                    </select>
                                </div>
                              </div>';

            // $html .= '</label>';
        }
        $html .= '</div>';
        return $html;
    }
    //////////////////////
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
////////////////////////////
    public function save_physiotherapy_chk(Request $request)
    {
        $painFile_id = $request->painFile_id;
        $followup_id = $request->followup_id;
        $physiotherapy_id = $request->physiotherapy_id;
        if ($request->checked == 0) {
            $model = FollowupPhysiotherapy::where('physiotherapy_program_id', '=', $physiotherapy_id)
                ->where('followup_id', '=', $followup_id)->first();
            if (isset($model)) {
                $model->delete();
                $ids = Lookup::where('parent_id', $physiotherapy_id)->pluck('id')->toArray();
                $model = FollowupPhysiotherapy::whereIn('physiotherapy_program_id', $ids)
                    ->where('followup_id', '=', $followup_id)->delete();
                return response()->json(['success' => true]);
            } else
                return response()->json(['success' => false]);
        } else if ($request->checked == 1) {
            $model = FollowupPhysiotherapy::where('physiotherapy_program_id', '=', $physiotherapy_id)
                ->where('followup_id', '=', $followup_id)->first();
            if (isset($model)) {
                return response()->json(['success' => true]);
            } else {
                $model = new FollowupPhysiotherapy();
                $model->pain_file_id = $painFile_id;
                $model->followup_id = $followup_id;
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
        $followup_id = $request->followup_id;
        $physiotherapy_id = $request->physiotherapy_id;
        $compliance = $request->compliance;
        $model = FollowupPhysiotherapy::where('physiotherapy_program_id', '=', $physiotherapy_id)
            ->where('followup_id', '=', $followup_id)->first();
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
