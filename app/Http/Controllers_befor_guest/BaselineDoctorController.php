<?php

namespace App\Http\Controllers;

use App\Appointments;
use App\BaselineDiagnostics;
use App\BaselineDoctorConsultation;
use App\BaselineNeuroExamsOther;
use App\BaselineNeuroExamsScars;
use App\BaselineNeurologicalExam;
use App\BaselinePreviousTreatment;
use App\BaselineTreatmentChoice;
use App\BaselineTreatmentGoal;
use App\FollowupTreatmentGoal;
use App\ItemsBatchTb;
use App\ItemsTb;
use App\PainFile;
use Illuminate\Http\Request;

class BaselineDoctorController extends Controller
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
        //dd($request->all());
        $painFile_id = $request->painFile_id;
        $baseline = BaselineDoctorConsultation::where('pain_file_id', $painFile_id)->count();
        if ($baseline == 0) {
            $baseline = new BaselineDoctorConsultation();
            $baseline->pain_file_id = $painFile_id;
            $baseline->visit_date = $request->visit_date_doctor;
            $baseline->injury_mechanism = serialize($request->injury_mechanism);
            $baseline->specify_injury_mechanism = $request->specify_injury_mechanism;
            $baseline->other_pains_before_injury = $request->other_pains_before_injury;
            $baseline->specify_other_pains_before_injury = $request->specify_other_pains_before_injury;
            $baseline->other_nonpain_symptoms = $request->other_nonpain_symptoms;
            $baseline->specify_other_nonpain_symptoms = $request->specify_other_nonpain_symptoms;
            $baseline->comorbidities = $request->comorbidities;
            $baseline->specify_comorbidities = $request->specify_comorbidities;
            $baseline->allergies = $request->allergies;
            $baseline->specify_allergies = $request->specify_allergies;
            $baseline->previous_surgery = $request->previous_surgery;
            $baseline->specify_previous_surgery = $request->specify_previous_surgery;
            $baseline->no_treatment_offered_due_to = $request->no_treatment_offered_due_to;
            $baseline->specify_no_treatment_offered_due_to = $request->specify_no_treatment_offered_due_to;
            $baseline->active_rehabilitation = $request->active_rehabilitation;
            $baseline->specify_active_rehabilitation = $request->specify_active_rehabilitation;
            $baseline->passive_rehabilitation = $request->passive_rehabilitation;
            $baseline->specify_passive_rehabilitation = $request->specify_passive_rehabilitation;
            $baseline->nonpharmacological_treatments = $request->nonpharmacological_treatments;
            $baseline->specify_nonpharmacological_treatments = $request->specify_nonpharmacological_treatments;
            $baseline->take_drug = $request->take_drug;
            $baseline->other_drugs = $request->other_drugs;
            $baseline->neuro_history_of_pain = $request->neuro_history_of_pain;
            $baseline->neuro_pain_localized = $request->neuro_pain_localized;
            $baseline->neuro_side_and_scars = $request->neuro_side_and_scars;
            $baseline->neuro_side_and_other = $request->neuro_side_and_other;
            $baseline->neuro_stump_distribution_of_pain = $request->neuro_stump_distribution_of_pain_dr;
            $baseline->neuro_phantom_type_of_plp = $request->neuro_phantom_type_of_plp_dr;
            $baseline->neuro_other_finding = $request->neuro_other_finding_dr;
            $baseline->diagnostic_additional_ptsd = $request->diagnostic_additional_ptsd;
            $baseline->diagnostic_specify_combination = $request->diagnostic_specify_combination;
            $baseline->physical_treatment = $request->physical_treatment;
            $baseline->specify_physical_treatment = $request->specify_physical_treatment;
            $baseline->pharmacological_treatment = $request->pharmacological_treatment;
            $baseline->other_treatments = $request->other_treatments;
            $baseline->doctor_message = $request->doctor_message;
            $baseline->baseline_doctor_comment = $request->baseline_doctor_comment;
            $baseline->baseline_doctor_lab = $request->baseline_doctor_lab;
            $baseline->baseline_doctor_rad = $request->baseline_doctor_rad;
            $baseline->second_doctor_id = $request->second_doctor_id;
            $baseline->created_by = auth()->user()->id;
            $baseline->org_id = auth()->user()->org_id;
            if ($baseline->save()) {
                $painFile = PainFile::find($painFile_id);
                $painFile->baseline_date = $request->visit_date_doctor;
                $painFile->save();
                $del_PainDistribution = BaselineDiagnostics::where('pain_file_id', $painFile_id)->delete();
                $diagnosis_id = $request->get('diagnosis_id');
                if (isset($diagnosis_id))
                    foreach ($diagnosis_id as $option => $value) {
                        $PainDistribution = new BaselineDiagnostics();
                        $PainDistribution->diagnostic_id = $value;
                        $PainDistribution->pain_file_id = $painFile_id;
                        $PainDistribution->created_by = auth()->user()->id;
                        $PainDistribution->org_id = auth()->user()->org_id;
                        $PainDistribution->save();
                    }
                $appointment = Appointments::where('pain_file_id', $painFile_id)
                    ->whereDate('attend_date', '=', date('Y-m-d'))->first();
                if (isset($appointment)) {
                    $appointment->current_stage = 3;
                    $appointment->save();
                }
                return response()->json(['success' => true]);

            }
        } else {
            $baseline = BaselineDoctorConsultation::where('pain_file_id', $painFile_id)->first();
            $baseline->visit_date = $request->visit_date_doctor;
            $baseline->injury_mechanism = serialize($request->injury_mechanism);
            $baseline->specify_injury_mechanism = $request->specify_injury_mechanism;
            $baseline->other_pains_before_injury = $request->other_pains_before_injury;
            $baseline->specify_other_pains_before_injury = $request->specify_other_pains_before_injury;
            $baseline->other_nonpain_symptoms = $request->other_nonpain_symptoms;
            $baseline->specify_other_nonpain_symptoms = $request->specify_other_nonpain_symptoms;
            $baseline->allergies = $request->allergies;
            $baseline->specify_allergies = $request->specify_allergies;
            $baseline->comorbidities = $request->comorbidities;
            $baseline->specify_comorbidities = $request->specify_comorbidities;
            $baseline->previous_surgery = $request->previous_surgery;
            $baseline->specify_previous_surgery = $request->specify_previous_surgery;
            $baseline->active_rehabilitation = $request->active_rehabilitation;
            $baseline->specify_active_rehabilitation = $request->specify_active_rehabilitation;
            $baseline->passive_rehabilitation = $request->passive_rehabilitation;
            $baseline->specify_passive_rehabilitation = $request->specify_passive_rehabilitation;
            $baseline->no_treatment_offered_due_to = $request->no_treatment_offered_due_to;
            $baseline->specify_no_treatment_offered_due_to = $request->specify_no_treatment_offered_due_to;
            $baseline->nonpharmacological_treatments = $request->nonpharmacological_treatments;
            $baseline->take_drug = $request->take_drug;
            $baseline->other_drugs = $request->other_drugs;
            $baseline->specify_nonpharmacological_treatments = $request->specify_nonpharmacological_treatments;
            $baseline->neuro_history_of_pain = $request->neuro_history_of_pain;
            $baseline->neuro_pain_localized = $request->neuro_pain_localized;
            $baseline->neuro_side_and_scars = $request->neuro_side_and_scars;
            $baseline->neuro_side_and_other = $request->neuro_side_and_other;
            $baseline->neuro_stump_distribution_of_pain = $request->neuro_stump_distribution_of_pain_dr;
            $baseline->neuro_phantom_type_of_plp = $request->neuro_phantom_type_of_plp_dr;
            $baseline->neuro_other_finding = $request->neuro_other_finding_dr;
            $baseline->diagnostic_additional_ptsd = $request->diagnostic_additional_ptsd;
            $baseline->diagnostic_specify_combination = $request->diagnostic_specify_combination;
            $baseline->physical_treatment = $request->physical_treatment;
            $baseline->specify_physical_treatment = $request->specify_physical_treatment;
            $baseline->pharmacological_treatment = $request->pharmacological_treatment;
            $baseline->other_treatments = $request->other_treatments;
            $baseline->doctor_message = $request->doctor_message;
            $baseline->baseline_doctor_comment = $request->baseline_doctor_comment;
            $baseline->baseline_doctor_lab = $request->baseline_doctor_lab;
            $baseline->baseline_doctor_rad = $request->baseline_doctor_rad;
            $baseline->second_doctor_id = $request->second_doctor_id;
            $baseline->created_by = auth()->user()->id;
            $baseline->org_id = auth()->user()->org_id;
            if ($baseline->save()) {
                $painFile = PainFile::find($painFile_id);
                $painFile->baseline_date = $request->visit_date_doctor;
                $painFile->save();
                $del_PainDistribution = BaselineDiagnostics::where('pain_file_id', $painFile_id)->delete();
                $diagnosis_id = $request->get('diagnosis_id');
                if (isset($diagnosis_id))
                    foreach ($diagnosis_id as $option => $value) {
                        $PainDistribution = new BaselineDiagnostics();
                        $PainDistribution->diagnostic_id = $value;
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

    public function insert_prvtreatment_drugs(Request $request)
    {
        //dd($request->all());
        $painFile_id = $request->painFile_id;
        $painFile_status = $request->painFile_status;
        $mediction = new BaselinePreviousTreatment();
        $mediction->pain_file_id = $painFile_id;
        $mediction->drug_id = $request->drug_id;
        $mediction->effect_id = $request->effect_id;
        $mediction->org_id = auth()->user()->org_id;
        $mediction->created_by = auth()->user()->id;

        if ($mediction->save()) {
            $model = BaselinePreviousTreatment::where('pain_file_id', $painFile_id)->get();
            $html = '';
            $i = 1;
            foreach ($model as $raw) {
                $html .= '<tr><td>' . $i++ . '</td>';
                $html .= '<td>' . get_drug_list_desc($raw->drug_id) . '</td>';
                $html .= '<td> ' . get_lookup_desc($raw->effect_id) . '</td>';
                if ($painFile_status == 17)
                    $html .= '<td><button type="button" class="btn red" onclick="del_prvtreatment_drug(' . $raw->id . ')">-</button></td></tr>';
                else
                    $html .= '<td></td></tr>';
            }
            return response()->json(['success' => true, 'html' => $html]);

        }
        return response()->json(['success' => false, 'html' => '']);

    }

    public function delete_prvtreatment_drugs(Request $request)
    {
        $id = $request->id;
        $painFile_id = $request->painFile_id;
        $painFile_status = $request->painFile_status;
        $model = BaselinePreviousTreatment::find($id);
        if (isset($model)) {
            $model->delete();
            $model = BaselinePreviousTreatment::where('pain_file_id', $painFile_id)->get();
            $html = '';
            $i = 1;
            foreach ($model as $raw) {
                $html .= '<tr><td>' . $i++ . '</td>';
                $html .= '<td>' . get_drug_desc($raw->drug_id) . '</td>';
                $html .= '<td> ' . get_lookup_desc($raw->effect_id) . '</td>';
                if ($painFile_status == 17)
                    $html .= '<td><button type="button" class="btn red" onclick="del_prvtreatment_drug(' . $raw->id . ')">-</button></td></tr>';
                else
                    $html .= '<td></td></tr>';
            }
            return response()->json(['success' => true, 'html' => $html]);
        } else {
            return response()->json(['success' => false, 'html' => '']);
        }

    }

    /**
     * Treatment
     * (Insert - Delete)
     */
    public function insert_treatment_choice_drugs(Request $request)
    {
        //dd($request->all());
      //  print_r($request->all());
       // echo $request->drug_id;exit;
        $painFile_id = $request->painFile_id;
        $painFile_status = $request->painFile_status;
        $mediction = new BaselineTreatmentChoice();
        $mediction->pain_file_id = $painFile_id;
        $mediction->drug_id = $request->drug_id;
        $mediction->batch_id = $request->batch_id;
        $mediction->drug_price = $request->drug_price;
        $mediction->drug_specify = $request->dosage . 'x' . $request->frequency . 'x' . $request->duration;
        // $mediction->concentration = $request->concentration;//become concentration
        $mediction->dosage = $request->dosage;
        $mediction->frequency = $request->frequency;
        $mediction->duration = $request->duration;
      //  $mediction->quantity =$request->quantity; //intval($request->dosage) * $request->frequency * $request->duration;
        $mediction->quantity =intval($request->dosage) * intval($request->frequency)* intval($request->duration);
      //  $mediction->drug_cost = $request->drug_cost;
        $mediction->drug_cost =$request->drug_price* $mediction->quantity;
        $mediction->drug_comments = $request->drug_comments;
        $mediction->org_id = auth()->user()->org_id;
        $mediction->created_by = auth()->user()->id;

        if ($mediction->save()) {

            $doctor_html = $this->draw_treatment_choice_drugs_doctor($painFile_id, $painFile_status);
            $pharm_html = $this->draw_treatment_choice_drugs_pharm($painFile_id, $painFile_status);

            //*****************************************************************************
            return response()->json(['success' => true, 'pharm_html' => $pharm_html, 'doctor_html' => $doctor_html]);
        }
        return response()->json(['success' => false, 'pharm_html' => '', 'doctor_html' => '']);

    }

    public function delete_treatment_choice_drugs(Request $request)
    {
        $id = $request->id;
        $painFile_id = $request->painFile_id;
        $painFile_status = $request->painFile_status;
        $model = BaselineTreatmentChoice::find($id);
        if (isset($model)) {
            $model->delete();
            $doctor_html = $this->draw_treatment_choice_drugs_doctor($painFile_id, $painFile_status);
            $pharm_html = $this->draw_treatment_choice_drugs_pharm($painFile_id, $painFile_status);
            return response()->json(['success' => true, 'pharm_html' => $pharm_html, 'doctor_html' => $doctor_html]);
        } else {
            return response()->json(['success' => false, 'pharm_html' => '', 'doctor_html' => '']);
        }

    }

    /**/
    public function inser_sideNerve(Request $request)
    {
        //dd($request->all());
        $painFile_id = $request->painFile_id;
        $painFile_status = $request->painFile_status;
        $side_nerve = new BaselineNeurologicalExam();
        $side_nerve->pain_file_id = $painFile_id;
        $side_nerve->side_nerve_id = $request->side_nerve_id;
        $side_nerve->side_detail_id = $request->side_detail_id;
        $side_nerve->sub_side_detail_id = $request->sub_side_detail_id;
        $side_nerve->light_touch = $request->light_touch;
        $side_nerve->pinprick = $request->pinprick;
        $side_nerve->warmth = $request->warmth;
        $side_nerve->cold = $request->cold;
        $side_nerve->created_by = auth()->user()->id;
        $side_nerve->org_id = auth()->user()->org_id;


        if ($side_nerve->save()) {
            $side_nerves = BaselineNeurologicalExam::where('pain_file_id', $painFile_id)->get();
            $html = '';
            $i = 1;
            foreach ($side_nerves as $raw) {
                $html .= '<tr><td>' . $i++ . '</td>';
                $html .= '<td>' . get_lookup_desc($raw->side_nerve_id) . '</td>';
                if (isset($raw->sub_side_detail_id))
                    $html .= '<td>' . get_lookup_desc($raw->side_detail_id) . ' / ' . get_lookup_desc($raw->sub_side_detail_id) . '</td>';
                else
                    $html .= '<td>' . get_lookup_desc($raw->side_detail_id) . '</td>';

                $html .= '<td> ' . get_lookup_desc($raw->light_touch) . '</td>';
                $html .= '<td> ' . get_lookup_desc($raw->pinprick) . '</td>';
                $html .= '<td> ' . get_lookup_desc($raw->warmth) . '</td>';
                $html .= ' <td> ' . get_lookup_desc($raw->cold) . '</td>';
                if ($painFile_status == 17)
                    $html .= '<td><button type="button" class="btn btn-danger btn-icon-only" onclick="del_nerve(' . $raw->id . ')">
<i class="fa fa-minus fa-fw"></i></button></td></tr>';
                else
                    $html .= '<td></td></tr>';
            }
            return response()->json(['success' => true, 'html' => $html]);

        }
        return response()->json(['success' => false, 'html' => '']);


    }

    public function inser_scars_side(Request $request)
    {
        //dd($request->all());
        $painFile_id = $request->painFile_id;
        $painFile_status = $request->painFile_status;
        $side_nerve = new BaselineNeuroExamsScars();
        $side_nerve->pain_file_id = $painFile_id;
        $side_nerve->scars_side_nerve_id = $request->scars_side_nerve_id;
        $side_nerve->scars_side_detail_id = $request->scars_side_detail_id;
        $side_nerve->light_touch = $request->light_touch;
        $side_nerve->pinprick = $request->pinprick;
        $side_nerve->warmth = $request->warmth;
        $side_nerve->cold = $request->cold;
        $side_nerve->created_by = auth()->user()->id;
        $side_nerve->org_id = auth()->user()->org_id;


        if ($side_nerve->save()) {
            $side_nerves = BaselineNeuroExamsScars::where('pain_file_id', $painFile_id)->get();
            $html = '';
            $i = 1;
            foreach ($side_nerves as $raw) {
                $html .= '<tr><td>' . $i++ . '</td>';
                $html .= '<td>' . get_lookup_desc($raw->scars_side_nerve_id) . '</td>';

                $html .= '<td>' . get_lookup_desc($raw->scars_side_detail_id) . '</td>';

                $html .= '<td> ' . get_lookup_desc($raw->light_touch) . '</td>';
                $html .= '<td> ' . get_lookup_desc($raw->pinprick) . '</td>';
                $html .= '<td> ' . get_lookup_desc($raw->warmth) . '</td>';
                $html .= ' <td> ' . get_lookup_desc($raw->cold) . '</td>';
                if ($painFile_status == 17)
                    $html .= '<td><button type="button" class="btn btn-danger btn-icon-only" onclick="del_scars_nerve(' . $raw->id . ')"><i class="fa fa-minus fa-fw"></i></button></td></tr>';
                else
                    $html .= '<td></td></tr>';
            }
            return response()->json(['success' => true, 'html' => $html]);

        }
        return response()->json(['success' => false, 'html' => '']);


    }

    public function inser_other_side(Request $request)
    {
        //dd($request->all());
        $painFile_id = $request->painFile_id;
        $painFile_status = $request->painFile_status;
        $side_nerve = new BaselineNeuroExamsOther();
        $side_nerve->pain_file_id = $painFile_id;
        $side_nerve->other_side_nerve_id = $request->other_side_nerve_id;
        $side_nerve->other_side_detail_id = $request->other_side_detail_id;
        $side_nerve->light_touch = $request->light_touch;
        $side_nerve->pinprick = $request->pinprick;
        $side_nerve->warmth = $request->warmth;
        $side_nerve->cold = $request->cold;
        $side_nerve->created_by = auth()->user()->id;
        $side_nerve->org_id = auth()->user()->org_id;


        if ($side_nerve->save()) {
            $side_nerves = BaselineNeuroExamsOther::where('pain_file_id', $painFile_id)->get();
            $html = '';
            $i = 1;
            foreach ($side_nerves as $raw) {
                $html .= '<tr><td>' . $i++ . '</td>';
                $html .= '<td>' . get_lookup_desc($raw->other_side_nerve_id) . '</td>';

                $html .= '<td>' . get_lookup_desc($raw->other_side_detail_id) . '</td>';

                $html .= '<td> ' . get_lookup_desc($raw->light_touch) . '</td>';
                $html .= '<td> ' . get_lookup_desc($raw->pinprick) . '</td>';
                $html .= '<td> ' . get_lookup_desc($raw->warmth) . '</td>';
                $html .= ' <td> ' . get_lookup_desc($raw->cold) . '</td>';
                if ($painFile_status == 17)
                    $html .= '<td><button type="button" class="btn btn-danger btn-icon-only" onclick="del_other_nerve(' . $raw->id . ')">
<i class="fa fa-minus fa-fw"></i></button></td></tr>';
                else
                    $html .= '<td></td></tr>';
            }
            return response()->json(['success' => true, 'html' => $html]);

        }
        return response()->json(['success' => false, 'html' => '']);


    }

    public function delete_side_and_nerve(Request $request)
    {
        $id = $request->id;
        $painFile_id = $request->painFile_id;
        $painFile_status = $request->painFile_status;
        $side_nerves = BaselineNeurologicalExam::find($id);
        if (isset($side_nerves)) {
            $side_nerves->delete();
            $side_nerves = BaselineNeurologicalExam::where('pain_file_id', $painFile_id)->get();
            $html = '';
            $html = '';
            $i = 1;
            foreach ($side_nerves as $raw) {
                $html .= '<tr><td>' . $i++ . '</td>';
                $html .= '<td>' . get_lookup_desc($raw->side_nerve_id) . '</td>';
                if (isset($raw->sub_side_detail_id))
                    $html .= '<td>' . get_lookup_desc($raw->side_detail_id) . ' / ' . get_lookup_desc($raw->sub_side_detail_id) . '</td>';
                else
                    $html .= '<td>' . get_lookup_desc($raw->side_detail_id) . '</td>';

                $html .= '<td> ' . get_lookup_desc($raw->light_touch) . '</td>';
                $html .= '<td> ' . get_lookup_desc($raw->pinprick) . '</td>';
                $html .= '<td> ' . get_lookup_desc($raw->warmth) . '</td>';
                $html .= ' <td> ' . get_lookup_desc($raw->cold) . '</td>';
                if ($painFile_status == 17)
                    $html .= '<td><button type="button" class="btn btn-danger btn-icon-only" onclick="del_nerve(' . $raw->id . ')"><i class="fa fa-minus fa-fw"></i></button></td></tr>';
                else
                    $html .= '<td></td></tr>';
            }
            return response()->json(['success' => true, 'html' => $html]);
        } else {
            return response()->json(['success' => false, 'html' => '']);
        }

    }

    public function delete_scars_side_and_nerve(Request $request)
    {
        $id = $request->id;
        $painFile_id = $request->painFile_id;
        $painFile_status = $request->painFile_status;
        $side_nerves = BaselineNeuroExamsScars::find($id);
        if (isset($side_nerves)) {
            $side_nerves->delete();
            $side_nerves = BaselineNeuroExamsScars::where('pain_file_id', $painFile_id)->get();
            $html = '';
            $html = '';
            $i = 1;
            foreach ($side_nerves as $raw) {
                $html .= '<tr><td>' . $i++ . '</td>';
                $html .= '<td>' . get_lookup_desc($raw->scars_side_nerve_id) . '</td>';
                $html .= '<td>' . get_lookup_desc($raw->scars_side_detail_id) . '</td>';

                $html .= '<td> ' . get_lookup_desc($raw->light_touch) . '</td>';
                $html .= '<td> ' . get_lookup_desc($raw->pinprick) . '</td>';
                $html .= '<td> ' . get_lookup_desc($raw->warmth) . '</td>';
                $html .= ' <td> ' . get_lookup_desc($raw->cold) . '</td>';
                if ($painFile_status == 17)
                    $html .= '<td><button type="button" class="btn btn-danger btn-icon-only" onclick="del_scars_nerve(' . $raw->id . ')"><i class="fa fa-minus fa-fw"></i></button></td></tr>';
                else
                    $html .= '<td></td></tr>';

            }
            return response()->json(['success' => true, 'html' => $html]);
        } else {
            return response()->json(['success' => false, 'html' => '']);
        }

    }

    public function delete_other_side_and_nerve(Request $request)
    {
        $id = $request->id;
        $painFile_id = $request->painFile_id;
        $painFile_status = $request->painFile_status;
        $side_nerves = BaselineNeuroExamsOther::find($id);
        if (isset($side_nerves)) {
            $side_nerves->delete();
            $side_nerves = BaselineNeuroExamsOther::where('pain_file_id', $painFile_id)->get();
            $html = '';
            $html = '';
            $i = 1;
            foreach ($side_nerves as $raw) {
                $html .= '<tr><td>' . $i++ . '</td>';
                $html .= '<td>' . get_lookup_desc($raw->other_side_nerve_id) . '</td>';
                $html .= '<td>' . get_lookup_desc($raw->other_side_detail_id) . '</td>';

                $html .= '<td> ' . get_lookup_desc($raw->light_touch) . '</td>';
                $html .= '<td> ' . get_lookup_desc($raw->pinprick) . '</td>';
                $html .= '<td> ' . get_lookup_desc($raw->warmth) . '</td>';
                $html .= ' <td> ' . get_lookup_desc($raw->cold) . '</td>';
                if ($painFile_status == 17)
                    $html .= '<td><button type="button" class="btn btn-danger btn-icon-only" onclick="del_other_nerve(' . $raw->id . ')"><i class="fa fa-minus fa-fw"></i></button></td></tr>';

                else
                    $html .= '<td></td></tr>';
            }
            return response()->json(['success' => true, 'html' => $html]);
        } else {
            return response()->json(['success' => false, 'html' => '']);
        }

    }


    public function insert_treatment_goal(Request $request)
    {
        //dd($request->all());
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
            // $followup_treatment_goals->save();
            $doctor_html = $this->draw_treatment_goal_doctor($painFile_id, $painFile_status);
            $nurse_html = $this->draw_treatment_goal_nurse($painFile_id, $painFile_status);
            return response()->json(['success' => true, 'nurse_html' => $nurse_html, 'doctor_html' => $doctor_html]);


        } else {
            return response()->json(['success' => false, 'nurse_html' => '', 'doctor_html' => '']);
        }

    }

    public function update_treatment_goal(Request $request)
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



}
