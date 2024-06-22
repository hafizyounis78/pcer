<?php

namespace App\Http\Controllers;

use App\BaselineDiagnostics;
use App\BaselineDoctorConsultation;
use App\BaselineTreatmentChoice;
use App\BaselineTreatmentGoal;
use App\BaselineTreatmentVw;
use App\Diagnostics;
use App\DrugList;
use App\FollowupAdverseEffect;
use App\FollowupDignostics;
use App\FollowupDoctor;
use App\FollowupNurse;
use App\FollowupPatient;
use App\FollowupPharmacist;
use App\FollowupPhysiotherapy;
use App\FollowupPsychology;
use App\FollowupPsychologyTreatment;
use App\FollowupTreatment;
use App\FollowupTreatmentAdverseEffect;
use App\FollowupTreatmentGoal;
use App\FollowupTreatmentsVw;
use App\ItemsBatchTb;
use App\ItemsTb;
use App\PainFile;
use App\PainIntensityVw;
use App\Patient;
use App\Project;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class FollowupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        dd('index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($painFile_id = null, $patientid = null, $painFile_status = null)
    {


        $this->data['page_title'] = 'Followup';
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

        $this->data['all_followups'] = FollowupPatient::where('pain_file_id', $painFile_id)->orderBy('follow_up_date')->get();
        $this->data['baseline_doctor_visit_date'] = '';
        $baselineDoctor = BaselineDoctorConsultation::where('pain_file_id', $painFile_id)->first();
        $this->data['one_baseline_doctor'] = $baselineDoctor;
        if (isset($baselineDoctor))
            $this->data['baseline_doctor_visit_date'] = $baselineDoctor->visit_date;
        // $this->data['last_followups'] = FollowupPatient::where('pain_file_id', $painFile_id)->max('id');

        $last_follow = FollowupPatient::select('id')->where('pain_file_id', $painFile_id)->orderBy('follow_up_date', 'DESC')->first();


        $last_follow_id = '';
        //dd($last_follow);
        $this->data['last_followups'] = null;
        if (isset($last_follow)) {

            $last_follow_id = $last_follow->id;
            $this->data['last_followups'] = $last_follow->id;
            $one_followup_doctor = FollowupDoctor::where('pain_file_id', $painFile_id)->where('followup_id', $last_follow_id)->first();
            $this->data['one_followup_doctor'] = $one_followup_doctor;
            $this->data['doctor_name'] = '';
            if (isset($one_followup_doctor->created_by))
                $this->data['doctor_name'] = User::where('id', $one_followup_doctor->created_by)->first()->name;
            $this->data['followup_doctor_count'] = FollowupDoctor::where('pain_file_id', $painFile_id)->count();
            $this->data['one_followup_nurse'] = FollowupNurse::where('pain_file_id', $painFile_id)->where('followup_id', $last_follow_id)->first();
            $this->data['one_followup_pharm'] = FollowupPharmacist::where('pain_file_id', $painFile_id)->where('followup_id', $last_follow_id)->first();
            $this->data['one_followup_psychology'] = FollowupPsychology::where('pain_file_id', $painFile_id)->where('followup_id', $last_follow_id)->first();
            // $this->data['previse_followup_psychology'] = FollowupPsychology::where('pain_file_id', $painFile_id)->orderBy('followup_id', 'desc')->first();
            $this->data['followup_psy_treatment'] = FollowupPsychologyTreatment::where('pain_file_id', $painFile_id)->where('followup_id', $last_follow_id)->get();

            // dd($this->data['previse_followup_psychology'] );exit;
            $this->data['one_followup_diagnosis'] = FollowupDignostics::where('pain_file_id', $painFile_id)->where('followup_id', $last_follow_id)->get();
            $this->data['chk_neck_and_shoulder'] = $this->get_neck_shoulder_ckb_lookups(524, $painFile_id, $last_follow_id);
            $this->data['chk_lower_back'] = $this->get_lower_back_ckb_lookups(536, $painFile_id, $last_follow_id);
//dd( $this->data['followup_doctor_count'] );

            // dd( $this->data['one_followup_diagnosis']);
        }

        $this->data['doctor_list'] = User::where('user_type_id', 9)->get();
        $this->data['nurse_list'] = User::where('user_type_id', 10)->get();

        $this->data['treat_doc_goal'] = $this->draw_followup_treat_doc_goal($painFile_id, $last_follow_id, $painFile_status);
        // dd( $this->data['treat_doc_goal']);
        $this->data['treat_nurse_goal'] = $this->draw_followup_treat_nurse_goal($painFile_id, $last_follow_id, $painFile_status);
        $this->data['treatmentFollowup_data'] = $this->draw_followup_treatment_doctor($painFile_id, $last_follow_id, $painFile_status);
        $this->data['Pharm_treatmentFollowup_data'] = $this->draw_followup_treatment_pharm($painFile_id, $last_follow_id, $painFile_status);
        $this->data['Pharm_adverse_effects'] = FollowupAdverseEffect::where('pain_file_id', $painFile_id)->where('followup_id', $last_follow_id)->get();
        $this->data['htmlqutenza'] = $this->draw_qutenza_table($painFile_id);
        $this->data['drug_list'] = ItemsTb::where('isActive', 1)->get();
        $this->data['diagnosis_list'] = Diagnostics::where('isActive', 1)->get();
        $this->data['physical_treatment_list'] = get_lookups_list(83);
        $this->data['adverse_effects_list'] = get_lookups_list(92);
        $this->data['qutenza_patient_datatable'] = $this->draw_qutenza_table($painFile_id);
        $this->data['qutenza_list'] = get_lookups_list(369);
        $this->data['qutenza_score_datatable'] = $this->draw_qutenza_score_table($painFile_id);
        // $this->data['patient_project_datatable']  = $this->draw_patient_project_table($painFile_id);
        $this->data['patient_project_followup_datatable'] = $this->draw_patient_project_followup_table($painFile_id);
        $this->data['project_list'] = Project::where('consequence', 367)->get();
        $this->data['doctor_project_action_list'] = get_lookups_list(389);
        $this->data['pharm_project_action_list'] = get_lookups_list(398);
        //  $this->data['pharm_project_conclusion_list'] = get_lookups_list(393);
        $this->data['project_charts_list'] = get_lookups_list(374);
        $this->data['project_conclusion_list'] = get_lookups_list(393);
        $this->data['case_study_list'] = get_lookups_list(366);
        $this->data['psychologist_treatment_list'] = get_lookups_list(495);
        $this->data['health_rate_list'] = get_lookups_list(575);
        //$this->data['one_acutePain'] = AcutePainService::where('pain_file_id', $painFile_id)->first();
        return view(followup_vw() . '.followup')->with($this->data);
    }


    public
    function update_goal_score(Request $request)
    {
        $id = $request->id;
        //dd($id);
        $followup_score = $request->followup_score;
        $followupGoal = FollowupTreatmentGoal::find($id);
        if (isset($followupGoal)) {
            $followupGoal->followup_score = $followup_score;
            $followupGoal->updated_at = date('Y-m-d');
            if ($followupGoal->save())
                return response()->json(['success' => true]);
            return response()->json(['success' => false]);
        } else
            return response()->json(['success' => false]);


    }

    public
    function update_goal_compliance(Request $request)
    {
        $id = $request->id;
        $followup_compliance = $request->followup_compliance;
        $followupGoal = FollowupTreatmentGoal::find($id);
        if (isset($followupGoal)) {
            $followupGoal->followup_compliance = $followup_compliance;
            $followupGoal->updated_at = date('Y-m-d');
            if ($followupGoal->save())
                return response()->json(['success' => true]);
            return response()->json(['success' => false]);
        } else
            return response()->json(['success' => false]);


    }

    public function refresh_treatment_goal(Request $request)
    {
        $painFile_id = $request->painFile_id;
        $followup_id = $request->followup_id;
        $painFile_status = $request->painFile_status;
        // dd($followup_id);
        $FollwupsGoalsids = FollowupTreatmentGoal::where('pain_file_id', $painFile_id)
            ->where('followup_id', $followup_id)->pluck('baseline_goal_id')->toArray();
        //dd($FollwupsGoalsids);
        $baselineGoalsids = BaselineTreatmentGoal::where('pain_file_id', $painFile_id)
            ->whereNotin('id', $FollwupsGoalsids)
            ->pluck('id')->toArray();
        // dd($baselineGoalsids);
        $baselineGoals = BaselineTreatmentGoal::where('pain_file_id', $painFile_id)
            ->whereIn('id', $baselineGoalsids)->get();
        if (isset($baselineGoals))
            foreach ($baselineGoals as $baselineGoal) {

                $followupGoal = new FollowupTreatmentGoal();
                $followupGoal->pain_file_id = $painFile_id;
                $followupGoal->followup_id = $followup_id;
                $followupGoal->baseline_goal_id = $baselineGoal->id;
                $followupGoal->org_id = auth()->user()->org_id;
                $followupGoal->created_by = auth()->user()->id;
                $followupGoal->save();

            }
        $treat_doc_goal = $this->draw_followup_treat_doc_goal($painFile_id, $followup_id, $painFile_status);
        $treat_nurse_goal = $this->draw_followup_treat_nurse_goal($painFile_id, $followup_id, $painFile_status);
        return response()->json(['success' => true, 'treat_doc_goal' => $treat_doc_goal, 'treat_nurse_goal' => $treat_nurse_goal]);
    }

    public function add_treatment_drug(Request $request)

    {
        //dd($request->all());
        $painFile_id = $request->painFile_id;
        $painFile_status = $request->painFile_status;
        $followup_id = $request->followup_id;
        $mediction = new FollowupTreatment();
        $mediction->pain_file_id = $painFile_id;
        $mediction->followup_id = $followup_id;
        $mediction->drug_id = $request->drug_id;
        $mediction->batch_id = $request->batch_id;
        $mediction->drug_price = $request->drug_price;//piece price
        //   $mediction->drug_specify = $request->drug_specify;
        $mediction->drug_specify = $request->dosage . 'x' . $request->frequency . 'x' . $request->duration . '=' . $request->quantity;
        //     $mediction->concentration = $request->concentration;//become concentration
        $mediction->dosage = $request->dosage;
        $mediction->frequency = $request->frequency;
        $mediction->duration = $request->duration;
        // $mediction->quantity =  $request->quantity;//$request->dosage* $request->frequency * $request->duration;
        $mediction->quantity = intval($request->dosage) * intval($request->frequency) * intval($request->duration);;
        // $mediction->drug_cost =  $request->drug_cost;//cost
        $mediction->drug_cost = $request->drug_price * $mediction->quantity;
        $mediction->drug_comments = $request->drug_comments;
        $mediction->org_id = auth()->user()->org_id;
        $mediction->created_by = auth()->user()->id;

        if ($mediction->save()) {
            $doctor_html = $this->draw_followup_treatment_doctor($painFile_id, $followup_id, $painFile_status);
            $pharm_html = $this->draw_followup_treatment_pharm($painFile_id, $followup_id, $painFile_status);
            return response()->json(['success' => true, 'doctor_html' => $doctor_html, 'pharm_html' => $pharm_html]);
        }
        return response()->json(['success' => false, 'doctor_html' => '', 'pharm_html' => '']);

    }

    public function update_treatment_drug(Request $request)
    {
        // dd($request->all());
        $id = $request->id;
        $painFile_id = $request->painFile_id;
        $painFile_status = $request->painFile_status;
        $followup_id = $request->followup_id;

        $compliance = $request->compliance;
        //$adverse_effects = $request->adverse_effects;
        $decision = $request->decision;

        $folowupTreatment = FollowupTreatment::find($id);
        //  $folowupTreatment->concentration = $concentration;

//******************************اول خطوة هي جلب رقم الباتش الاكتف حتى يتم اجراء العمليات عليه***//
        $item = ItemsTb::where('id', $folowupTreatment->drug_id)->first();
        $batch = ItemsBatchTb::where('item_id', $folowupTreatment->drug_id)
            ->where('isActive', 1)
            ->where('batch_current_quantity', '>', $folowupTreatment->quantity)->first();//للتاكد من ان الباتش موجود وفعال وبه رصيد كافي للصرف


        if (isset($batch->id)) {
            //*****هنا يتم تعديل رقم الباتش للباتش الاكتف**/
            $folowupTreatment->batch_id = $batch->id;

            //****************//
            $folowupTreatment->frequency = $request->frequency;
            $folowupTreatment->duration = $request->duration;
            $folowupTreatment->quantity = intval($request->dosage) * intval($request->frequency) * intval($request->duration);;
            $folowupTreatment->drug_price = $batch->batch_piece_price;
            $folowupTreatment->drug_cost = $batch->batch_piece_price * $folowupTreatment->quantity;
            // $folowupTreatment->drug_price  = $request->drug_price;//piece price
            //  $folowupTreatment->drug_cost  = $request->drug_price*$folowupTreatment->quantity;//total cost drug_price*quantity
            $folowupTreatment->compliance = $compliance;
            // $folowupTreatment->adverse_effects = $adverse_effects;
            $folowupTreatment->decision = $decision;
            $folowupTreatment->drug_comments = $request->drug_comments;
            /* if ($folowupTreatment->dosage != $request->dosage)
                 $folowupTreatment->decision = 117;*/
            $folowupTreatment->dosage = $request->dosage;
            // if ($decision == 117)
            //   $folowupTreatment->dosage = $dosage;
            $folowupTreatment->drug_specify = $request->dosage . 'x' . $request->frequency . 'x' . $request->duration . ':Edited by ' . auth()->user()->name;
            // $folowupTreatment->drug_comments =$folowupTreatment->drug_specify ;
            if ($folowupTreatment->save()) {
                $adverseffect = FollowupTreatmentAdverseEffect::where('followup_treatment_id', $id)->delete();
                $adverse_effects = $request->get('adverse_effects');

                if (isset($adverse_effects))
                    foreach ($adverse_effects as $option => $value) {
                        //     dd($folowupTreatment->id);
                        $adverseffect = new FollowupTreatmentAdverseEffect();
                        $adverseffect->adverse_effects = $value;
                        $adverseffect->followup_treatment_id = $folowupTreatment->id;
                        $adverseffect->created_by = auth()->user()->id;
                        $adverseffect->org_id = auth()->user()->org_id;
                        $adverseffect->save();
                    }

                $doctor_html = $this->draw_followup_treatment_doctor($painFile_id, $followup_id, $painFile_status);
                $pharm_html = $this->draw_followup_treatment_pharm($painFile_id, $followup_id, $painFile_status);
                return response()->json(['success' => true, 'doctor_html' => $doctor_html, 'pharm_html' => $pharm_html]);
            }
        }
        return response()->json(['success' => false]);


    }

    public function export_excel_file(Request $request)
    {
        if ($request->report_id != '' && $request->report_id != 3) {

            $rep = $request->report_id;
            $from = $request->fromdate;
            $to = $request->todate;
            $drug_id = $request->drug_id;
            //  $concentration = $request->concentration;
            $columns = array();
            $data = array();
            $this->data['fromdate'] = $from;
            $this->data['todate'] = $to;
            $this->data['newPatients'] = '';
            $this->data['followupPatients'] = '';
            $this->data['lastFollowupPatients'] = '';
            $this->data['absentPatients'] = '';
            $filename = '';
            if ($rep == 1) {
                $model = FollowupTreatmentsVw::query();

                if ($drug_id != 0) {
                    $model = $model->where('drug_id', '=', $drug_id);
                    //     if ($concentration != '')
                    //       $model = $model->where('concentration', '=', $concentration);
                }
                $model = $model->whereBetween('date', [$from, $to])
                    ->get();
                foreach ($model as $row) {
                    $data[] = [
                        'PatientID' => isset($row->national_id) ? $row->national_id : '',
                        'Name_en' => $row->patient_name_en,
                        'Name' => iconv('UTF-8', 'windows-1256', $row->patient_name),
                        'Date' => $row->date,
                        'Drug' => $row->drug_name,
                        //    'Concentration' => $row->concentration,
                        'Dosage' => $row->dosage,
                        'Frequency' => $row->frequency,
                        'Duration' => $row->duration,
                        'Quantity' => $row->quantity,
                        'Specify' => $row->specify,
                        //    'User'=>    $row->user_name,
                        'Doctor' => $row->doctor_name
                    ];
                }
                // dd($data);
                $columns = ['PatientID', 'Name_en', 'Name', 'Date', 'Drug', 'Dosage', 'Frequency', 'Duration',
                    'Quantity', 'Specify', 'Doctor'];
                //  $columns = DB::getSchemaBuilder()->getColumnListing('followup_treatments_vw');
                $filename = "Export_excel_followup.csv";
            } else if ($rep == 2) {
                $model = BaselineTreatmentVw::query();
                if ($drug_id != 0) {
                    $model = $model->where('drug_id', '=', $drug_id);
                    //  if ($concentration != '')
                    //    $model = $model->where('concentration', '=', $concentration);
                }
                $model = $model->whereBetween('date', [$from, $to])//baseline_treatment_choices.created_at
                ->get();

                foreach ($model as $row) {
                    $data[] = [
                        'PatientID' => isset($row->national_id) ? $row->national_id : '',
                        'Name_en' => $row->patient_name_en,
                        'Name' => iconv('UTF-8', 'windows-1256', $row->patient_name),
                        'Date' => $row->date,
                        'Drug' => $row->drug_name,
                        //  'Concentration' => $row->concentration,
                        'Dosage' => $row->dosage,
                        'Frequency' => $row->frequency,
                        'Duration' => $row->duration,
                        'Quantity' => $row->quantity,
                        'Specify' => $row->specify,
                        //     'User'=>    $row->user_name,
                        'Doctor' => $row->doctor_name
                    ];
                }

                $columns = ['PatientID', 'Name_en', 'Name', 'Date', 'Drug', 'Dosage', 'Frequency', 'Duration',
                    'Quantity', 'Specify', 'Doctor'];
                //  $columns = DB::getSchemaBuilder()->getColumnListing('followup_treatments_vw');
                $filename = "Export_excel_baseline.csv";
            } else if ($rep == 4) {
                $model = PainIntensityVw::get();

                //$model = $model->whereBetween('date', [$from, $to])//baseline_treatment_choices.created_at


                foreach ($model as $row) {
                    $data[] = [
                        'Pain_file_no' => $row->pain_file_id,
                        'baseline_pain_scale' => $row->baseline_pain_scale,
                        'baseline_pain_bothersomeness' => $row->baseline_pain_bothersomeness,
                        'baseline_pain_intensity_during_rest' => $row->baseline_pain_intensity_during_rest,
                        'baseline_pain_intensity_during_activity' => $row->baseline_pain_intensity_during_activity,
                        'followup_last_pain_scale' => $row->followup_last_pain_scale,
                        'followup_last_pain_bothersomeness' => $row->followup_last_pain_bothersomeness,
                        'followup_last_pain_intensity_during_rest' => $row->followup_last_pain_intensity_during_rest,
                        'followup_last_pain_intensity_during_activity' => $row->followup_last_pain_intensity_during_activity,
                        'overall_status' => $row->overall_status,
                        'overall_status_desc' => $row->overall_status_desc
                    ];
                }

                $columns = ['Pain_file_no', 'baseline_pain_scale', 'baseline_pain_bothersomeness', 'baseline_pain_intensity_during_rest',
                    'baseline_pain_intensity_during_activity', 'followup_last_pain_scale', 'followup_last_pain_bothersomeness',
                    'followup_last_pain_intensity_during_rest', 'followup_last_pain_intensity_during_activity',
                    'overall_status', 'overall_status_desc'];
                $filename = "Export_excel_PainIntensityVw.csv";
            }
        } else
            return Redirect::back()->withErrors(['Please Select Report']);


        //   header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Content-Type: application/vnd.ms-excel");


        //    $model = DB::table('followup_export')->select('followup_export.*')->get()->toArray();
        // dd(DB::getQueryLog());
        // $columns = DB::getSchemaBuilder()->getColumnListing('followup_export');


        echo implode(",", array_values($columns)) . "\n";
        $j = 0;
        for ($j = 0; $j < count($data); $j++) {
            //print_r($data[0]);exit;
            for ($i = 0; $i < count($columns); $i++) {
                $cloname = $columns[$i];

                $nestedData[$columns[$i]] = '"' . str_replace("/", "-", $data[$j][$cloname]) . '"';
                // dd($columns[$i].$data[$j][$cloname]);
                //  echo $nestedData[$columns[$i]] ;

            }
            //  dd($nestedData);
            echo implode(",", array_values($nestedData)) . "\n";
        }

    }

    ////////////////////
    function get_neck_shoulder_ckb_lookups($id, $painFile_id, $followup_id)
    {
        $list_value = get_lookups_list($id);
        $physios = FollowupPhysiotherapy::where('pain_file_id', $painFile_id)->where('followup_id', '=', $followup_id)->get();
        $html = '<div class="form-group col-md-12" id="chk_physiotherapy_' . '"><div class="mt-checkbox-list">';
        $class = 'hide';
        $checked = '';
        $compliance = '';
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
            name="checkbox_' . $followup_id . '_' . $row['id'] . '"  id="checkbox_' . $followup_id . '_' . $row['id'] . '" onclick="save_chk(' . $row['id'] . ',' . $followup_id . ')"/>
                        <span></span></label>';
        }

        $html .= '</div></div>';
        $list_value2 = get_lookups_list(527);
        $html .= '<br><br><div id="dv_streching_exercise_neck_shoulder_' . $followup_id . '" class="col-md-offset-1 ' . $class . '">';
        foreach ($list_value2 as $row) {
            foreach ($physios as $physio) {
                $checked = '';
                $compliance = '';
                if ($row['id'] == $physio->physiotherapy_program_id) {
                    $compliance = $physio->compliance;
                    $checked = 'checked';
                    break;
                }
            }
            $html .= '<div class="row">
                                <div class=" form-group col-md-2" id="chk_phys_details_">
                                  <div class="mt-checkbox-list">
                                    <label class="mt-checkbox mt-checkbox-outline">' . $row['lookup_details'] . '
                                        <input class="child_chk_527_' . $followup_id . '" type="checkbox" value="' . $row['id'] . '" ' . $checked . ' 
                                         name="checkbox_' . $followup_id . '_' . $row['id'] . '"  id="checkbox_' . $followup_id . '_' . $row['id'] . '" onclick="save_chk(' . $row['id'] . ',' . $followup_id . ')"/>
                                        <span> </span>
                                    </label>
                                  </div>
                                </div>
                                <div class="col-md-2">
                                    <select class="form-control child_select_527_' . $followup_id . '" name="compliance_' . $followup_id . '_' . $row['id'] . '" 
                                    id="compliance_' . $followup_id . '_' . $row['id'] . '" onchange="update_ck_compliance(' . $row['id'] . ',' . $followup_id . ');">
                                        <option value="">Select..</option>
                                        <option value="0" ' . (($compliance === 0) ? 'selected' : '') . '>None</option>
                                        <option value="1" ' . (($compliance == 1) ? 'selected' : '') . '>partial</option>
                                        <option value="2" ' . (($compliance == 2) ? 'selected' : '') . '>good</option>
                                    </select>
                                </div>
                              </div>';
        }
        $html .= '</div>';

        return $html;
    }

    function get_lower_back_ckb_lookups($id, $painFile_id, $followup_id)
    {
        $list_value = get_lookups_list($id);
        $physios = FollowupPhysiotherapy::where('pain_file_id', $painFile_id)->where('followup_id', '=', $followup_id)->get();
        $html = '<div class="form-group col-md-12" id="chk_physiotherapy_' . '"><div class="mt-checkbox-list">';
        $class = 'hide';
        $checked = '';
        $compliance = '';
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
                '<input type="checkbox" value="' . $row['id'] . '" ' . $checked . '
            name="checkbox_' . $followup_id . '_' . $row['id'] . '"  id="checkbox_' . $followup_id . '_' . $row['id'] . '" onclick="save_chk(' . $row['id'] . ',' . $followup_id . ')"/>
                        <span></span></label>';
        }
        $html .= '</div></div>';
        $list_value2 = get_lookups_list(539);
        $html .= '<br><br><div id="dv_streching_exercise_lower_back_' . $followup_id . '" class="col-md-offset-1 ' . $class . '">';
        foreach ($list_value2 as $row) {
            foreach ($physios as $physio) {
                $checked = '';
                $compliance = '';
                if ($row['id'] == $physio->physiotherapy_program_id) {
                    $compliance = $physio->compliance;
                    $checked = 'checked';
                    break;
                }
            }
            $html .= '<div class="row">
                                <div class=" form-group col-md-3" id="chk_phys_details_">
                                  <div class="mt-checkbox-list">
                                    <label class="mt-checkbox mt-checkbox-outline">' . $row['lookup_details'] . '
                                        <input class="child_chk_539_' . $followup_id . '" type="checkbox" value="' . $row['id'] . '"  ' . $checked . '
            name="checkbox_' . $followup_id . '_' . $row['id'] . '"  id="checkbox_' . $followup_id . '_' . $row['id'] . '" onclick="save_chk(' . $row['id'] . ',' . $followup_id . ')"/>
                                        <span> </span>
                                    </label>
                                  </div>
                                </div>
                                <div class="col-md-2">
                                    <select class="form-control child_select_539_' . $followup_id . '" name="compliance_' . $followup_id . '_' . $row['id'] . '" 
                                    id="compliance_' . $followup_id . '_' . $row['id'] . '" onchange="update_ck_compliance(' . $row['id'] . ',' . $followup_id . ');">
                                        <option value="">Select..</option>
                                        <option value="0" ' . (($compliance === 0) ? 'selected' : '') . '>None</option>
                                        <option value="1" ' . (($compliance == 1) ? 'selected' : '') . '>partial</option>
                                        <option value="2" ' . (($compliance == 2) ? 'selected' : '') . '>good</option>
                                    </select>
                                </div>
                              </div>';

            // $html .= '</label>';
        }
        $html .= '</div>';
        return $html;
    }

    //////////////////////
    public
    function del_treatment_drug(Request $request)
    {
        $followup_id = $request->followup_id;
        $id = $request->id;
        $painFile_id = $request->painFile_id;
        $painFile_status = $request->painFile_status;
        $model = FollowupTreatment::find($id);
        if (isset($model)) {
            $model->delete();
            $doctor_html = $this->draw_followup_treatment_doctor($painFile_id, $followup_id, $painFile_status);
            $pharm_html = $this->draw_followup_treatment_pharm($painFile_id, $followup_id, $painFile_status);
            return response()->json(['success' => true, 'doctor_html' => $doctor_html, 'pharm_html' => $pharm_html]);
        } else {
            return response()->json(['success' => false, 'doctor_html' => '', 'pharm_html' => '']);
        }
    }

    /*function last_followup_create()
    {

        $this->data['page_title'] = 'Followup';
        $this->data['location_link'] = 'painFile/view';
        $this->data['location_title'] = 'Followup';


        $patientid = session()->get('patient_id');
        $this->data['one_patient'] = Patient::find($patientid);
        return view(followup_vw() . '.lastfollowup')->with($this->data);
    }*/

    public
    function new_followup_old(Request $request)
    {
        $followup_date = $request->new_followup_date;
        $painFile_id = $request->painFile_id;
        $painFile_status = $request->painFile_status;

        $followup_count = FollowupPatient::where('pain_file_id', $painFile_id)->count();
        if ($followup_count == 0) {//new first followup
            $followup = new FollowupPatient();
            $followup->pain_file_id = $painFile_id;
            $followup->follow_up_date = $followup_date;
            $followup->org_id = auth()->user()->org_id;
            $followup->created_by = auth()->user()->id;
            if ($followup->save()) {
                $painfile = PainFile::find($painFile_id);
                $painfile->last_followup_date = $followup_date;
                $painfile->save();
                $baselineTreatments = BaselineTreatmentChoice::where('pain_file_id', $painFile_id)->get();
                if (isset($baselineTreatments))
                    foreach ($baselineTreatments as $baselineTreatment) {

                        $followupTreatment = new FollowupTreatment();
                        $followupTreatment->pain_file_id = $painFile_id;
                        $followupTreatment->followup_id = $followup->id;
                        $followupTreatment->drug_id = $baselineTreatment->drug_id;
                        $followupTreatment->batch_id = $baselineTreatment->batch_id;
                        $followupTreatment->drug_specify = $baselineTreatment->drug_specify;
                        //  $followupTreatment->concentration = $baselineTreatment->concentration;
                        $followupTreatment->dosage = $baselineTreatment->dosage;
                        $followupTreatment->frequency = $baselineTreatment->frequency;
                        $followupTreatment->duration = $baselineTreatment->duration;
                        $followupTreatment->quantity = $baselineTreatment->quantity;
                        $followupTreatment->drug_price = $baselineTreatment->drug_price;
                        $followupTreatment->drug_cost = $baselineTreatment->drug_cost;
                        $followupTreatment->org_id = auth()->user()->org_id;
                        $followupTreatment->created_at = $baselineTreatment->created_at;
                        $followupTreatment->created_by = auth()->user()->id;
                        $followupTreatment->save();

                    }
                $baselineGoals = BaselineTreatmentGoal::where('pain_file_id', $painFile_id)->get();
                if (isset($baselineGoals))
                    foreach ($baselineGoals as $baselineGoal) {

                        $followupGoal = new FollowupTreatmentGoal();
                        $followupGoal->pain_file_id = $painFile_id;
                        $followupGoal->followup_id = $followup->id;
                        $followupGoal->baseline_goal_id = $baselineGoal->id;
                        $followupGoal->org_id = auth()->user()->org_id;
                        $followupGoal->created_by = auth()->user()->id;
                        $followupGoal->save();

                    }
                $baselineDiagnostics = BaselineDiagnostics::where('pain_file_id', $painFile_id)->get();
                if (isset($baselineDiagnostics))
                    foreach ($baselineDiagnostics as $baselineDiagnostic) {

                        $followupDiagnostic = new FollowupDignostics();
                        $followupDiagnostic->pain_file_id = $painFile_id;
                        $followupDiagnostic->followup_id = $followup->id;
                        $followupDiagnostic->diagnostic_id = $baselineDiagnostic->diagnostic_id;
                        $followupDiagnostic->org_id = auth()->user()->org_id;
                        $followupDiagnostic->created_by = auth()->user()->id;
                        $followupDiagnostic->save();

                    }
            }
            return response()->json(['success' => true]);

        } else {//new followup
            $last_follow_id = FollowupPatient::where('pain_file_id', $painFile_id)->max('id');
            $followup = new FollowupPatient();
            $followup->pain_file_id = $painFile_id;
            $followup->follow_up_date = $followup_date;
            $followup->org_id = auth()->user()->org_id;
            $followup->created_by = auth()->user()->id;
            if ($followup->save()) {
                $painfile = PainFile::find($painFile_id);
                $painfile->last_followup_date = $followup_date;
                $painfile->save();
                $LastTreatments = FollowupTreatment::where('pain_file_id', $painFile_id)
                    ->where('followup_id', $last_follow_id)->get();
                if (isset($LastTreatments))
                    foreach ($LastTreatments as $LastTreatment) {

                        $followupTreatment = new FollowupTreatment();
                        $followupTreatment->pain_file_id = $painFile_id;
                        $followupTreatment->followup_id = $followup->id;
                        $followupTreatment->drug_id = $LastTreatment->drug_id;
                        $followupTreatment->batch_id = $LastTreatment->batch_id;
                        $followupTreatment->drug_specify = $LastTreatment->drug_specify;
                        //  $followupTreatment->concentration = $LastTreatment->concentration;
                        $followupTreatment->dosage = $LastTreatment->dosage;
                        $followupTreatment->frequency = $LastTreatment->frequency;
                        $followupTreatment->duration = $LastTreatment->duration;
                        $followupTreatment->quantity = $LastTreatment->quantity;
                        $followupTreatment->drug_price = $LastTreatment->drug_price;
                        $followupTreatment->drug_cost = $LastTreatment->drug_cost;
                        $followupTreatment->org_id = auth()->user()->org_id;
                        $followupTreatment->created_by = auth()->user()->id;
                        $followupTreatment->created_at = $LastTreatment->created_at;
                        $followupTreatment->save();

                    }
                $followupGoals = FollowupTreatmentGoal::where('pain_file_id', $painFile_id)
                    ->where('followup_id', $last_follow_id)->get();
                if (isset($followupGoals))
                    foreach ($followupGoals as $followupGoal) {

                        $followupGoal2 = new FollowupTreatmentGoal();
                        $followupGoal2->pain_file_id = $painFile_id;
                        $followupGoal2->followup_id = $followup->id;
                        $followupGoal2->baseline_goal_id = $followupGoal->baseline_goal_id;
                        $followupGoal2->org_id = auth()->user()->org_id;
                        $followupGoal2->created_by = auth()->user()->id;
                        $followupGoal2->save();

                    }
                $lastfollowupDiagnostics = FollowupDignostics::where('pain_file_id', $painFile_id)
                    ->where('followup_id', $last_follow_id)->get();
                if (isset($lastfollowupDiagnostics))
                    foreach ($lastfollowupDiagnostics as $lastfollowupDiagnostic) {

                        $followupDiagnostic = new FollowupDignostics();
                        $followupDiagnostic->pain_file_id = $painFile_id;
                        $followupDiagnostic->followup_id = $followup->id;
                        $followupDiagnostic->diagnostic_id = $lastfollowupDiagnostic->diagnostic_id;
                        $followupDiagnostic->org_id = auth()->user()->org_id;
                        $followupDiagnostic->created_by = auth()->user()->id;
                        $followupDiagnostic->save();

                    }

                return response()->json(['success' => true]);
            }
        }

        return response()->json(['success' => false]);

    }

    public function new_followup(Request $request)
    {
        $followup_date = $request->new_followup_date;
        $painFile_id = $request->painFile_id;
        $painFile_status = $request->painFile_status;
        $count = FollowupPatient::whereDate('follow_up_date', '=', $followup_date)->where('pain_file_id', $painFile_id)->count();
        if ($count > 0)
            return response()->json(['success' => false]);
        else {
            $followup_count = FollowupPatient::where('pain_file_id', $painFile_id)->count();
            if ($followup_count == 0) {//new first followup
                $followup = new FollowupPatient();
                $followup->pain_file_id = $painFile_id;
                $followup->follow_up_date = $followup_date;
                $followup->org_id = auth()->user()->org_id;
                $followup->created_by = auth()->user()->id;
                if ($followup->save()) {
                    $painfile = PainFile::find($painFile_id);
                    $painfile->last_followup_date = $followup_date;
                    $painfile->save();
                    $baselineTreatments = BaselineTreatmentChoice::where('pain_file_id', $painFile_id)->get();
                    if (isset($baselineTreatments))
                        foreach ($baselineTreatments as $baselineTreatment) {

                            $followupTreatment = new FollowupTreatment();
                            $followupTreatment->pain_file_id = $painFile_id;
                            $followupTreatment->followup_id = $followup->id;
                            $followupTreatment->drug_id = $baselineTreatment->drug_id;
                            $followupTreatment->batch_id = 0;//$baselineTreatment->batch_id;
                            $followupTreatment->drug_specify = $baselineTreatment->drug_specify;
                            //  $followupTreatment->concentration = $baselineTreatment->concentration;
                            $followupTreatment->dosage = 0;//$baselineTreatment->dosage;
                            $followupTreatment->frequency = 0;// $baselineTreatment->frequency;
                            $followupTreatment->duration = 0;//$baselineTreatment->duration;
                            $followupTreatment->quantity = 0;//$baselineTreatment->quantity;
                            $followupTreatment->drug_price = 0;//$baselineTreatment->drug_price;
                            $followupTreatment->drug_cost = 0;//$baselineTreatment->drug_cost;
                            $followupTreatment->org_id = auth()->user()->org_id;
                            $followupTreatment->created_at = $baselineTreatment->created_at;
                            $followupTreatment->created_by = auth()->user()->id;
                            $followupTreatment->save();

                        }
                    $baselineGoals = BaselineTreatmentGoal::where('pain_file_id', $painFile_id)->get();
                    if (isset($baselineGoals))
                        foreach ($baselineGoals as $baselineGoal) {

                            $followupGoal = new FollowupTreatmentGoal();
                            $followupGoal->pain_file_id = $painFile_id;
                            $followupGoal->followup_id = $followup->id;
                            $followupGoal->baseline_goal_id = $baselineGoal->id;
                            $followupGoal->org_id = auth()->user()->org_id;
                            $followupGoal->created_by = auth()->user()->id;
                            $followupGoal->save();

                        }
                    $baselineDiagnostics = BaselineDiagnostics::where('pain_file_id', $painFile_id)->get();
                    if (isset($baselineDiagnostics))
                        foreach ($baselineDiagnostics as $baselineDiagnostic) {

                            $followupDiagnostic = new FollowupDignostics();
                            $followupDiagnostic->pain_file_id = $painFile_id;
                            $followupDiagnostic->followup_id = $followup->id;
                            $followupDiagnostic->diagnostic_id = $baselineDiagnostic->diagnostic_id;
                            $followupDiagnostic->org_id = auth()->user()->org_id;
                            $followupDiagnostic->created_by = auth()->user()->id;
                            $followupDiagnostic->save();

                        }
                }
                return response()->json(['success' => true]);

            } else {//new followup
                $last_follow_id = FollowupPatient::where('pain_file_id', $painFile_id)->max('id');
                $followup = new FollowupPatient();
                $followup->pain_file_id = $painFile_id;
                $followup->follow_up_date = $followup_date;
                $followup->org_id = auth()->user()->org_id;
                $followup->created_by = auth()->user()->id;
                if ($followup->save()) {
                    $painfile = PainFile::find($painFile_id);
                    $painfile->last_followup_date = $followup_date;
                    $painfile->save();
                    $LastTreatments = FollowupTreatment::where('pain_file_id', $painFile_id)
                        ->where('followup_id', $last_follow_id)->get();
                    // ->where('followup_id', $last_follow_id)->whereNotIn('decision', [118, 119])->get();
                    if (isset($LastTreatments))
                        foreach ($LastTreatments as $LastTreatment) {

                            $followupTreatment = new FollowupTreatment();
                            $followupTreatment->pain_file_id = $painFile_id;
                            $followupTreatment->followup_id = $followup->id;
                            $followupTreatment->drug_id = $LastTreatment->drug_id;
                            $followupTreatment->batch_id = $LastTreatment->batch_id;
                            $followupTreatment->drug_specify = $LastTreatment->drug_specify;
                            //  $followupTreatment->concentration = $LastTreatment->concentration;
                            $followupTreatment->dosage = $LastTreatment->dosage;
                            $followupTreatment->frequency = $LastTreatment->frequency;
                            $followupTreatment->duration = $LastTreatment->duration;
                            $followupTreatment->quantity = $LastTreatment->quantity;
                            $followupTreatment->drug_price = $LastTreatment->drug_price;
                            $followupTreatment->drug_cost = $LastTreatment->drug_cost;
                            $followupTreatment->org_id = auth()->user()->org_id;
                            $followupTreatment->created_by = auth()->user()->id;
                            $followupTreatment->created_at = $LastTreatment->created_at;
                            $followupTreatment->save();

                        }
                    $followupGoals = FollowupTreatmentGoal::where('pain_file_id', $painFile_id)
                        ->where('followup_id', $last_follow_id)->get();
                    if (isset($followupGoals))
                        foreach ($followupGoals as $followupGoal) {

                            $followupGoal2 = new FollowupTreatmentGoal();
                            $followupGoal2->pain_file_id = $painFile_id;
                            $followupGoal2->followup_id = $followup->id;
                            $followupGoal2->baseline_goal_id = $followupGoal->baseline_goal_id;
                            $followupGoal2->org_id = auth()->user()->org_id;
                            $followupGoal2->created_by = auth()->user()->id;
                            $followupGoal2->save();

                        }
                    $lastfollowupDiagnostics = FollowupDignostics::where('pain_file_id', $painFile_id)
                        ->where('followup_id', $last_follow_id)->get();
                    if (isset($lastfollowupDiagnostics))
                        foreach ($lastfollowupDiagnostics as $lastfollowupDiagnostic) {

                            $followupDiagnostic = new FollowupDignostics();
                            $followupDiagnostic->pain_file_id = $painFile_id;
                            $followupDiagnostic->followup_id = $followup->id;
                            $followupDiagnostic->diagnostic_id = $lastfollowupDiagnostic->diagnostic_id;
                            $followupDiagnostic->org_id = auth()->user()->org_id;
                            $followupDiagnostic->created_by = auth()->user()->id;
                            $followupDiagnostic->save();

                        }
                    $lastPsyTreatments = FollowupPsychologyTreatment::where('pain_file_id', $painFile_id)
                        ->groupBy('treatment_id')->pluck('treatment_id');
                    // dd($lastPsyTreatments );
                    for ($i = 0; $i < count($lastPsyTreatments); $i++) {
                        $Treatment = new FollowupPsychologyTreatment();
                        $Treatment->pain_file_id = $painFile_id;
                        $Treatment->followup_id = $followup->id;
                        $Treatment->treatment_id = $lastPsyTreatments[$i];
                        $Treatment->org_id = auth()->user()->org_id;
                        $Treatment->created_by = auth()->user()->id;
                        $Treatment->save();

                    }
                    /*  $lastPsychologys = FollowupPsychology::where('pain_file_id', $painFile_id)
                          ->where('followup_id', $last_follow_id)->get();
                      foreach ($lastPsychologys as $lastPsychology ) {
                          $FollowupPsychology = new FollowupPsychology();
                          $FollowupPsychology->pain_file_id = $painFile_id;
                          $FollowupPsychology->followup_id = $followup->id;
                          $FollowupPsychology->follow_up_date = $followup_date;
                          $FollowupPsychology->other_treatment_today = $lastPsychology->other_treatment_today;
                          $FollowupPsychology->physical_exam = $lastPsychology->physical_exam;
                          $FollowupPsychology->org_id = auth()->user()->org_id;
                          $FollowupPsychology->created_by = $lastPsychology->created_by;
                          $FollowupPsychology->save();
  
                      }*/

                    return response()->json(['success' => true]);
                }
            }

            return response()->json(['success' => false]);
        }

    }

    public
    function check_available_date(Request $request)
    {
        $followup_date = $request->followup_date;
        $painFile_id = $request->painFile_id;
        $count = FollowupPatient::whereDate('follow_up_date', '=', $followup_date)->where('pain_file_id', $painFile_id)->count();
        if ($count > 0)
            return response()->json(['success' => false]);
        else
            return response()->json(['success' => true]);
    }

    function get_followup_charts(Request $request)
    {
        $painFile_id = $request->painFile_id;

        $sqlQuery = "SELECT * FROM (
SELECT pain_scale,pain_bothersomeness,pain_intensity_during_rest,pain_intensity_during_activity,
    visit_date
FROM baseline_nurse_consultations WHERE pain_file_id=" . $painFile_id . "
 and deleted_at is null
UNION ALL
SELECT pain_scale,pain_bothersomeness,pain_intensity_during_rest,pain_intensity_during_activity,
    follow_up_date as 'visit_date'
FROM followup_nurses WHERE pain_file_id=" . $painFile_id . " and deleted_at is null
UNION ALL
SELECT pain_scale,pain_bothersomeness,pain_intensity_during_rest,pain_intensity_during_activity,
    follow_up_date as 'visit_date'
FROM followup_doctors WHERE pain_file_id=" . $painFile_id . " and deleted_at is null
UNION ALL
SELECT pain_scale,pain_bothersomeness,pain_intensity_during_rest,pain_intensity_during_activity,
    follow_up_date as 'visit_date'
FROM followup_last WHERE pain_file_id=" . $painFile_id . " and deleted_at is null) AS pain_score 
ORDER BY  pain_score.visit_date ASC";
        $results = DB::select(DB::raw($sqlQuery));

        $score_data = array();
        $goal_data = array();
        foreach ($results as $det) {
            $nestedData['pain_scale'] = $det->pain_scale;
            $nestedData['pain_bothersomeness'] = $det->pain_bothersomeness;
            $nestedData['pain_intensity_during_rest'] = $det->pain_intensity_during_rest;
            $nestedData['pain_intensity_during_activity'] = $det->pain_intensity_during_activity;
            $nestedData['visit_date'] = $det->visit_date;
            array_push($score_data, $nestedData);
        }

        $sqlQuery2 = "SELECT * FROM (
SELECT baseline_treatment_goals.id,baseline_goal,'0' as 'followup_id' ,baseline_score as 'score', baseline_nurse_consultations.visit_date
FROM   baseline_treatment_goals ,baseline_nurse_consultations
WHERE  baseline_treatment_goals.pain_file_id=baseline_nurse_consultations.pain_file_id
AND    baseline_treatment_goals.pain_file_id=" . $painFile_id . " and baseline_treatment_goals.deleted_at is null
UNION ALL
SELECT baseline_treatment_goals.id,baseline_treatment_goals.baseline_goal,followup_patients.id as 'followup_id',followup_treatment_goals.followup_score as 'score',followup_patients.follow_up_date as 'visit_date'
FROM   followup_treatment_goals,baseline_treatment_goals,followup_patients
WHERE followup_treatment_goals.baseline_goal_id=baseline_treatment_goals.id
AND    followup_treatment_goals.followup_id =  followup_patients.id
AND  followup_treatment_goals.pain_file_id=" . $painFile_id . " and baseline_treatment_goals.deleted_at is null and followup_treatment_goals.deleted_at is null
UNION ALL
SELECT baseline_treatment_goals.id,baseline_treatment_goals.baseline_goal,followup_last.id as 'followup_id',followup_last_treatment_goals.followup_score as 'score',followup_last.follow_up_date as 'visit_date'
FROM   followup_last_treatment_goals,baseline_treatment_goals,followup_last
WHERE followup_last_treatment_goals.baseline_goal_id=baseline_treatment_goals.id
AND    followup_last_treatment_goals.followup_id =  followup_last.id
AND  followup_last_treatment_goals.pain_file_id=" . $painFile_id . " and baseline_treatment_goals.deleted_at is null and followup_last_treatment_goals.deleted_at is null ) AS goal_score
ORDER BY  goal_score.visit_date,goal_score.id ASC";

        $results2 = DB::select(DB::raw($sqlQuery2));

        $baselineGoalIds = BaselineTreatmentGoal::where('pain_file_id', $painFile_id)->select('baseline_goal', 'baseline_goal_score', 'id')->get();
        // dd($baselineGoalIds);
        $sql = "SELECT GROUP_CONCAT(baseline_goal SEPARATOR '-') as baseline_goal,IFNULL(baseline_goal_score, 0) as baseline_goal_score  FROM baseline_treatment_goals
         where pain_file_id=" . $painFile_id . " and baseline_treatment_goals.deleted_at is null GROUP BY baseline_goal_score";
        $guidesdata = DB::select($sql);
        //print_r($guidesdata);
        //********************
        for ($i = 0; $i < count($results2);) {
            $followup_id = $results2[$i]->followup_id;
            while ($i < count($results2) && $followup_id == $results2[$i]->followup_id) {
                foreach ($baselineGoalIds as $det) {
                    if ($det->id == $results2[$i]->id) {
                        $nestedData2[$det->baseline_goal] = $results2[$i]->score;
                    }
                }
                $i++;

            }
            $nestedData2['visit_date'] = Carbon::parse($results2[$i - 1]->visit_date)->format('Y-m-d');

            array_push($goal_data, $nestedData2);
        }

        return response()->json(['success' => true, 'score_data' => $score_data, 'goals' => $baselineGoalIds, 'goal_data' => $goal_data, 'guidesdata' => $guidesdata]);

    }

    public function cancel_followup_consultation(Request $request)
    {
        $type_id = $request->type_id;//1=doctor,2=nurse,3=pharm
        $followup_id = $request->followup_id;
        if ($type_id == 1) {
            $followupDoctor = FollowupDoctor::where('followup_id', $followup_id)->delete();
        } else if ($type_id == 2) {
            $followupDoctor = FollowupNurse::where('followup_id', $followup_id)->delete();
        } else if ($type_id == 3) {
            $followupDoctor = FollowupPharmacist::where('followup_id', $followup_id)->delete();
        } else
            return response()->json(['success' => false]);
        return response()->json(['success' => true]);

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


    public
    function update(Request $request, $id)
    {
        //
    }

    public
    function destroy($id)
    {
        //
    }
}
