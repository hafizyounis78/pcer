<?php

namespace App\Http\Controllers;

use App\Alert;
use App\BaselineDoctorConsultation;
use App\CurrentMedication;
use App\PainFile;
use App\Patient;
use App\TempBaselineInjuryMechanism;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function findPatient(Request $request)
    {
        $national_id = $request->national_id;
        $patient = Patient::where('national_id', $national_id)->first();

        $html = '';
        if (isset($patient)) {
            $painFiles = PainFile::where('patient_id', $patient->id)->get();
            $open_painFile_count = PainFile::where('patient_id', $patient->id)->where('status', 17)->get()->count();
            //dd($open_painFile_count);
            $html = '';
            if (isset($painFiles))
                foreach ($painFiles as $painFile) {

                    if ($painFile->status == 17) {
                        $class = 'icon-arrow-right font-blue ';
                        $title = "Open";
                    } else {
                        $class = 'fa fa-check-circle-o font-red';
                        $title = "Closed";
                    }
                    $html .= '<tr>
                        <td class="table-status">
                            <a href="javascript:;" title="' . $title . '">
                                <i class="' . $class . '"></i>
                            </a>
                        </td>
                        <td class="table-date font-blue">
                            <a href="javascript:;">' . $painFile->start_date . '</a>
                        </td>
                        <td class="table-title">
                            <h3>
                                <a href="javascript:;">' . $patient->name . '</a>
                            </h3>

                        </td>
                        <td class="table-desc ">' . (isset($painFile->baseline_doctor) ? $painFile->baseline_doctor : '') . '
                           </td>
                        <td class="table-desc"> ' . $patient->national_id . '</td>
                        <td class="table-download">
                            <a onclick="viewPainFile(' . $painFile->id . ',' . $patient->id . ',' . $painFile->status . ')" href="#" title="View file">
                                <i class="icon-doc font-green-soft"></i>
                            </a>';
                    if ($painFile->status == 18 && $open_painFile_count == 0)
                        $html .= ' <a onclick="newPainFile(' . $patient->id . ')" href="#" title="New File"><i class="icon-user-follow font-blue"></i></a>';
                    $html .= '</td></tr>';
                }
            return response()->json(['success' => true, 'data' => $html]);
        }
        return response()->json(['success' => false, 'data' => '']);


    }

    public function patient_setId(Request $request)
    {
        $url = $request->url;
        // dd($url);
        session()->put('national_id', '');
        $national_id = $request->national_id;

        session()->put('national_id', $national_id);
        //   dd($id);
        return Redirect::to($url);


    }


    public function index()
    {

        $this->data['sub_menu'] = 'Search';
        return view(patient_vw() . '.search')->with($this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($national_id = null)
    {
        //dd($national_id);
        // $this->data['location_link']='#';
        //  $this->data['location_title']='Pain File';
        $this->data['page_title'] = 'Patient File';
        $this->data['page_title_small'] = 'Open New File';
        $this->data['national_id'] = $national_id;
        $this->data['districts'] = get_lookups_list(1);
        return view(patient_vw() . '.register')->with($this->data);
    }

    public function list()
    {
        dd('hi');
        $this->data['page_title'] = 'Patient File';
        $this->data['page_title_small'] = 'Open New File';
        return view(patient_vw() . '.patientlist')->with($this->data);
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
        $patient_id = $request->patient_id;
        if (isset($patient_id))
            $patient = Patient::where('id', $patient_id)->first();
        if (isset($patient)) {
            $patient->national_id = $request->national_id;
            $patient->name = $request->name;
            $patient->name_a = $request->name_a;
            $patient->dob = $request->dob;
            $patient->gender = $request->gender;
            $patient->mobile_no = $request->mobile_no;
            $patient->district = $request->district;
            $patient->address = $request->address;
            if ($patient->save()) {
                $painFile = PainFile::where('patient_id', $patient->id)->orderBy('id', 'desc')->first();
                //->where('status', 17)->first();

                return response()->json(['success' => true, 'painFile_id' => $painFile->id, 'patient_id' => $patient->id, 'painFile_status' => $painFile->status]);
            }
        } else {
            $patient = new Patient();
            $patient->national_id = $request->national_id;
            $patient->name = $request->name;
            $patient->name_a = $request->name_a;
            $patient->dob = $request->dob;
            $patient->gender = $request->gender;
            $patient->mobile_no = $request->mobile_no;
            $patient->district = $request->district;
            $patient->address = $request->address;
            $patient->org_id = auth()->user()->org_id;
            $patient->created_by = auth()->user()->id;

            if ($patient->save()) {
                $painFile = new PainFile();
                $painFile->patient_id = $patient->id;
                $painFile->start_date = $request->start_date;
                $painFile->status = 17;
                $painFile->created_by = auth()->user()->id;
                $painFile->org_id = auth()->user()->org_id;
                if ($painFile->save())
                    return response()->json(['success' => true, 'painFile_id' => $painFile->id, 'patient_id' => $patient->id, 'painFile_status' => $painFile->status,]);
                return response()->json(['success' => false]);
            }
        }
        return response()->json(['success' => false]);

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function availabileNationalId(Request $request)
    {
        $id = $request->id;
        $count = Patient::where('national_id', '=', $id)
            //->where('org_id', '=', auth()->user()->org_id)
            ->count();
        if ($count >= 1)
            return response()->json(['success' => true]);
        else
            return response()->json(['success' => false]);
    }

    public function get_patient_data(Request $request)
    {
        $id = $request->id;
        $painFile_id = $request->painFile_id;
        //  dd($painFile_id);
        $patient = Patient::find($id);
        if (isset($patient)) {
            $painFile = PainFile::find($painFile_id);


            return response()->json(['success' => true, 'patient' => $patient, 'painFile' => $painFile]);
        } else
            return response()->json(['success' => false, 'patient' => '', 'painFile' => '']);
    }

    public function get_current_medication_data(Request $request)
    {
        $patient_id = $request->patient_id;
        $current_medication_table = $this->draw_current_medication_table($patient_id);
        return response()->json(['success' => true, 'current_medication_table' => $current_medication_table]);
    }

    public function add_current_medication(Request $request)
    {
        $patient_id = $request->patient_id;
        $drug_desc = $request->drug_desc;
        $drug_comments = $request->drug_comments;
        $currentMedication = new CurrentMedication();
        $currentMedication->drug_desc = $drug_desc;
        $currentMedication->drug_comments = $drug_comments;
        $currentMedication->patient_id = $patient_id;
        $currentMedication->org_id = auth()->user()->org_id;
        $currentMedication->created_by = auth()->user()->id;
        if ($currentMedication->save()) {
            $current_medication_table = $this->draw_current_medication_table($patient_id);
            return response()->json(['success' => true, 'current_medication_table' => $current_medication_table]);
        } else
            return response()->json(['success' => false, 'current_medication_table' => '']);
    }


    public function change_current_medication_status(Request $request)
    {
        $id = $request->id;
        $patient_id = $request->patient_id;
        $medication = CurrentMedication::find($id);
        if (isset($medication)) {

            $medication->isActive = 0;
            $medication->save();

            $current_medication_table = $this->draw_current_medication_table($patient_id);
            return response()->json(['success' => true, 'current_medication_table' => $current_medication_table]);
        }
        return response()->json(['success' => false, 'current_medication_table' => '']);


    }

    public function delete_current_medication(Request $request)
    {
        $id = $request->id;
        $patient_id = $request->patient_id;
        $medication = CurrentMedication::find($id);
        if (isset($medication)) {

            $medication->delete();
            $current_medication_table = $this->draw_current_medication_table($patient_id);
            return response()->json(['success' => true, 'current_medication_table' => $current_medication_table]);
        }
        return response()->json(['success' => false, 'current_medication_table' => '']);


    }

    public
    function show($id)
    {

        /*session()->put('painFile_id', null);
        session()->put('painFile_status', null);
        session()->put('patient_id', null);*/
        $this->data['users'] = User::whereIn('user_type_id', [9, 10,572])->get();
        $this->data['not_attend_reason_list'] = get_lookups_list(518);
        $this->data['page_title'] = 'Patient List';
        //  $this->data['page_title_small'] = 'Open New File';
        return view(patient_vw() . '.patientlist')->with($this->data);
    }

    public
    function followup_export_excel()
    {


//*** follow up
        /*  $this->create_v_followup_treatment_goals();
          $this->create_v_followup_dignostics();
          $this->create_v_followup_last_treatment_goals();
          $this->create_v_followup_treatment_adverse_effects();
          $this->create_v_followup_treat_with_advers();
          $this->create_v_followup_treatments();
         $this->create_followup_export_vw();*/
        $this->export_followup_excel_file();
    }

    public
    function export_followup_excel_file()
    {

        $filename = "Export_excel_followup.csv";
        //   header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Content-Type: application/vnd.ms-excel");


        $model = DB::table('followup_export')->select('followup_export.*')->get()->toArray();
        // dd(DB::getQueryLog());
        $columns = DB::getSchemaBuilder()->getColumnListing('followup_export');


        echo implode(",", array_values($columns)) . "\n";
        $i = 0;
        foreach ($model as $row) {
            //   print_r(array_keys($row));
            for ($i = 0; $i < count($columns); $i++) {
                $cloname = $columns[$i];
                $nestedData[$columns[$i]] = '"' . str_replace("/", "-", $row->$cloname) . '"';
                //  echo $nestedData[$columns[$i]] ;

            }
            //  dd($nestedData);
            echo implode(",", array_values($nestedData)) . "\n";
        }

    }

//**************

    public
    function baseline_export_excel()
    {
        $this->create_v_baseline_treatment_goals();
        $this->create_v_baseline_neurological_exams();
        $this->create_v_baseline_neuro_exams_scars();
        $this->create_v_baseline_neuro_exams_other();
        $this->create_v_baseline_pain_distributions();
        $this->create_v_baseline_diagnostics();
        $this->create_v_baseline_previous_treatment();
        $this->create_v_baseline_treatment_choices();
        $this->create_v_baseline_injury_mechanism();

        $this->create_baseline_export_vw();
        $this->export_baseline_excel_file();

    }

    public
    function create_v_baseline_treatment_goals()
    {

        $baselineGoalMax = DB::query()->fromSub(function ($query) {
            $query->from('baseline_treatment_goals')->select(DB::raw('count(id) as total'))->whereNull('deleted_at')->groupBy('pain_file_id');
        }, '')->max('total');
        //  dd($sql);
        $sql = 'SELECT pain_file_id as tr_pain_file_id';

        for ($i = 0; $i < $baselineGoalMax;) {
            $sql .= ',(select  baseline_goal from baseline_treatment_goals a where a.deleted_at is null and a.pain_file_id=outbas.pain_file_id order by id limit ' . $i . ',1 ) as baseline_goal' . ++$i;
            $sql .= ',(select  baseline_goal_score from baseline_treatment_goals a where a.deleted_at is null and a.pain_file_id=outbas.pain_file_id order by id limit ' . --$i . ',1 ) as baseline_goal_score' . ++$i;
            $sql .= ',(select  baseline_score from baseline_treatment_goals a where a.deleted_at is null and a.pain_file_id=outbas.pain_file_id order by id limit ' . --$i . ',1 ) as baseline_score' . ++$i;
        }

        //

        $sql .= ' FROM baseline_treatment_goals outbas WHERE outbas.deleted_at is null group by pain_file_id order by id';
        // echo $sql;
        $sqlQuery2 = 'CREATE OR REPLACE VIEW v_baseline_treatment_goals as ' . $sql;
        $results2 = DB::select(DB::raw($sqlQuery2));

        //dd($results2);

    }

    public
    function create_v_baseline_neurological_exams()
    {

        $baselineGoalMax = DB::query()->fromSub(function ($query) {
            $query->from('baseline_neurological_exams')->select(DB::raw('count(id) as total'))->whereNull('deleted_at')->groupBy('pain_file_id');
        }, '')->max('total');
        //  dd($sql);
        $sql = 'SELECT pain_file_id as nex_pain_file_id';

        for ($i = 0; $i < $baselineGoalMax;) {
            $sql .= ',(select  side_nerve_id from baseline_neurological_exams a where a.deleted_at is null and a.pain_file_id=outbas.pain_file_id order by id limit ' . $i . ',1 ) as side_nerve_id' . ++$i;
            $sql .= ',(select  side_detail_id from baseline_neurological_exams a where a.deleted_at is null and a.pain_file_id=outbas.pain_file_id order by id limit ' . --$i . ',1 ) as side_detail_id' . ++$i;
            $sql .= ',(select  sub_side_detail_id from baseline_neurological_exams a where a.deleted_at is null and a.pain_file_id=outbas.pain_file_id order by id limit ' . --$i . ',1 ) as sub_side_detail_id' . ++$i;
            $sql .= ',(select  light_touch from baseline_neurological_exams a where a.deleted_at is null and a.pain_file_id=outbas.pain_file_id order by id limit ' . --$i . ',1 ) as light_touch' . ++$i;
            $sql .= ',(select  pinprick from baseline_neurological_exams a where a.deleted_at is null and a.pain_file_id=outbas.pain_file_id order by id limit ' . --$i . ',1 ) as pinprick' . ++$i;
            $sql .= ',(select  warmth from baseline_neurological_exams a where a.deleted_at is null and a.pain_file_id=outbas.pain_file_id order by id limit ' . --$i . ',1 ) as warmth' . ++$i;
            $sql .= ',(select  cold from baseline_neurological_exams a where a.deleted_at is null and a.pain_file_id=outbas.pain_file_id order by id limit ' . --$i . ',1 ) as cold' . ++$i;
        }

        //

        $sql .= ' FROM baseline_neurological_exams outbas WHERE outbas.deleted_at is null group by pain_file_id order by id';
        // echo $sql;
        $sqlQuery2 = 'CREATE OR REPLACE VIEW v_baseline_neurological_exams as ' . $sql;
        $results2 = DB::select(DB::raw($sqlQuery2));


        // dd($results3);

    }

    public
    function create_v_baseline_pain_distributions()
    {

        $baselineGoalMax = DB::query()->fromSub(function ($query) {
            $query->from('baseline_pain_distributions')->select(DB::raw('count(id) as total'))->whereNull('deleted_at')->groupBy('pain_file_id');
        }, '')->max('total');
        //  dd($sql);
        $sql = 'SELECT pain_file_id as pd_pain_file_id';

        for ($i = 0; $i < $baselineGoalMax;) {
            $sql .= ',(select  location_id from baseline_pain_distributions a where a.deleted_at is null and a.pain_file_id=outbas.pain_file_id order by id limit ' . $i . ',1 ) as location_id' . ++$i;
        }

        //

        $sql .= ' FROM baseline_pain_distributions outbas WHERE outbas.deleted_at is null group by pain_file_id order by id';
        // echo $sql;
        $sqlQuery2 = 'CREATE OR REPLACE VIEW v_baseline_pain_distributions as ' . $sql;
        $results2 = DB::select(DB::raw($sqlQuery2));


        // dd($results3);

    }

    public
    function create_v_baseline_diagnostics()
    {

        $baselineGoalMax = DB::query()->fromSub(function ($query) {
            $query->from('baseline_diagnostics')->select(DB::raw('count(id) as total'))->whereNull('deleted_at')->groupBy('pain_file_id');
        }, '')->max('total');
        //  dd($sql);
        $sql = 'SELECT pain_file_id as d_pain_file_id';

        for ($i = 0; $i < $baselineGoalMax;) {
            $sql .= ',(select  diagnostic_id from baseline_diagnostics a where a.deleted_at is null and a.pain_file_id=outbas.pain_file_id order by id limit ' . $i . ',1 ) as diagnostic_id' . ++$i;
        }

        //

        $sql .= ' FROM baseline_diagnostics outbas WHERE outbas.deleted_at is null group by pain_file_id order by id';
        // echo $sql;
        $sqlQuery2 = 'CREATE OR REPLACE VIEW v_baseline_diagnostics as ' . $sql;
        $results2 = DB::select(DB::raw($sqlQuery2));


        //  dd($results3);

    }

    public
    function create_v_baseline_previous_treatment()
    {

        $baselineGoalMax = DB::query()->fromSub(function ($query) {
            $query->from('baseline_previous_treatment')->select(DB::raw('count(id) as total'))->whereNull('deleted_at')->groupBy('pain_file_id');
        }, '')->max('total');
        //  dd($sql);
        $sql = 'SELECT pain_file_id as pt_pain_file_id';

        for ($i = 0; $i < $baselineGoalMax;) {
            $sql .= ',(select  drug_id from baseline_previous_treatment a where a.deleted_at is null and a.pain_file_id=outbas.pain_file_id order by id limit ' . $i . ',1 ) as previous_treat_drug_id' . ++$i;
            $sql .= ',(select  effect_id from baseline_previous_treatment a where a.deleted_at is null and a.pain_file_id=outbas.pain_file_id order by id limit ' . --$i . ',1 ) as previous_treat_effect_id' . ++$i;
        }

        //

        $sql .= ' FROM baseline_previous_treatment outbas WHERE outbas.deleted_at is null group by pain_file_id order by id';
        // echo $sql;
        $sqlQuery2 = 'CREATE OR REPLACE VIEW v_baseline_previous_treatment as ' . $sql;
        $results2 = DB::select(DB::raw($sqlQuery2));

    }

    public
    function create_v_baseline_treatment_choices()
    {

        $baselineGoalMax = DB::query()->fromSub(function ($query) {
            $query->from('baseline_treatment_choices')->select(DB::raw('count(id) as total'))->whereNull('deleted_at')->groupBy('pain_file_id');
        }, '')->max('total');
        //  dd($sql);
        $sql = 'SELECT pain_file_id as tc_pain_file_id';

        for ($i = 0; $i < $baselineGoalMax;) {
            $sql .= ',(select  drug_id from baseline_treatment_choices a where a.deleted_at is null and a.pain_file_id=outbas.pain_file_id order by id limit ' . $i . ',1 ) as treatment_choices_drug_id' . ++$i;
            $sql .= ',(select  concentration from baseline_treatment_choices a where a.deleted_at is null and a.pain_file_id=outbas.pain_file_id order by id limit ' . --$i . ',1 ) as treatment_choices_concentration' . ++$i;
            $sql .= ',(select  dosage from baseline_treatment_choices a where a.deleted_at is null and a.pain_file_id=outbas.pain_file_id order by id limit ' . --$i . ',1 ) as treatment_choices_dosage' . ++$i;
            $sql .= ',(select  frequency from baseline_treatment_choices a where a.deleted_at is null and a.pain_file_id=outbas.pain_file_id order by id limit ' . --$i . ',1 ) as treatment_choices_frequency' . ++$i;
            $sql .= ',(select  duration from baseline_treatment_choices a where a.deleted_at is null and a.pain_file_id=outbas.pain_file_id order by id limit ' . --$i . ',1 ) as treatment_choices_duration' . ++$i;
            $sql .= ',(select  quantity from baseline_treatment_choices a where a.deleted_at is null and a.pain_file_id=outbas.pain_file_id order by id limit ' . --$i . ',1 ) as treatment_choices_quantity' . ++$i;
            $sql .= ',(select  drug_specify from baseline_treatment_choices a where a.deleted_at is null and a.pain_file_id=outbas.pain_file_id order by id limit ' . --$i . ',1 ) as treatment_choices_drug_specify' . ++$i;
            // $sql .= ',(select  dosage from baseline_treatment_choices a where a.deleted_at is null and a.pain_file_id=outbas.pain_file_id order by id limit ' . --$i . ',1 ) as treatment_choices_dosage' . ++$i;
        }

        //

        //

        $sql .= ' FROM baseline_treatment_choices outbas WHERE outbas.deleted_at is null group by pain_file_id order by id';
        // echo $sql;
        $sqlQuery2 = 'CREATE OR REPLACE VIEW v_baseline_treatment_choices as ' . $sql;
        $results2 = DB::select(DB::raw($sqlQuery2));
    }

    public
    function create_v_baseline_injury_mechanism()
    {
        $temp = array();
        $temp_array_length = array();
        $temp_max_length = 0;
        $sql = '';
        $baseline_Doctor = BaselineDoctorConsultation::select('injury_mechanism', 'pain_file_id', 'created_at')->orderBy('id')->get();
        foreach ($baseline_Doctor as $row) {
            $temp = unserialize($row->injury_mechanism);
            if (count($temp) > $temp_max_length)
                $temp_max_length = count($temp);

        }
        $temp_baseline_injury_mechanism = TempBaselineInjuryMechanism::truncate();

        foreach ($baseline_Doctor as $row) {
            $temp = unserialize($row->injury_mechanism);
            //  $temp_array_length=count($temp);

            for ($i = 0; $i < $temp_max_length; ++$i) {
                $injury_mechanism = 0;
                if (isset($temp[$i])) {
                    $injury_mechanism = $temp[$i];
                }
                $temp_baseline_injury_mechanism = new TempBaselineInjuryMechanism();
                $temp_baseline_injury_mechanism->pain_file_id = $row->pain_file_id;
                $temp_baseline_injury_mechanism->injury_mechanism = $injury_mechanism;
                $temp_baseline_injury_mechanism->created_at = $row->created_at;
                $temp_baseline_injury_mechanism->save();
            }
        }

        $baselineGoalMax = DB::query()->fromSub(function ($query) {
            $query->from('temp_baseline_injury_mechanism')->select(DB::raw('count(id) as total'))->groupBy('pain_file_id');
        }, '')->max('total');
        //  dd($sql);
        $sql = 'SELECT pain_file_id as im_pain_file_id';

        for ($i = 0; $i < $baselineGoalMax;) {
            $sql .= ',(select  injury_mechanism from temp_baseline_injury_mechanism a where a.pain_file_id=outbas.pain_file_id order by id limit ' . $i . ',1 ) as injury_mechanism' . ++$i;
        }

        //

        $sql .= ' FROM temp_baseline_injury_mechanism outbas group by pain_file_id  order by id';
        // echo $sql;
        //exit;
        $sqlQuery2 = 'CREATE OR REPLACE VIEW v_baseline_injury_mechanism as ' . $sql;
        $results2 = DB::select(DB::raw($sqlQuery2));
    }

    public
    function create_baseline_export_vw()
    {
        $sql = " create or replace view baseline_export as 
        select patients.id as patient_id,
patients.dob,
patients.gender,patients.district,
patient_details.no_of_child,patient_details.education,patient_details.current_work,patient_details.weekly_hours,
patient_details.monthly_income,patient_details.isProvider,patient_details.isOnlyProvider,patient_details.num_of_family,
patient_details.isSmoke,pain_files.id,
pain_files.start_date,
pain_files.close_date,
pain_files.status,
baseline_doctor_consultations.visit_date,
baseline_doctor_consultations.specify_injury_mechanism,
baseline_doctor_consultations.other_pains_before_injury,baseline_doctor_consultations.other_nonpain_symptoms,baseline_doctor_consultations.specify_other_pains_before_injury,baseline_doctor_consultations.comorbidities,baseline_doctor_consultations.specify_comorbidities,baseline_doctor_consultations.allergies,baseline_doctor_consultations.specify_allergies,baseline_doctor_consultations.previous_surgery,baseline_doctor_consultations.specify_previous_surgery,baseline_doctor_consultations.active_rehabilitation,baseline_doctor_consultations.specify_active_rehabilitation,baseline_doctor_consultations.passive_rehabilitation,baseline_doctor_consultations.nonpharmacological_treatments,baseline_doctor_consultations.specify_nonpharmacological_treatments,baseline_doctor_consultations.take_drug,baseline_doctor_consultations.other_drugs,baseline_doctor_consultations.neuro_history_of_pain,baseline_doctor_consultations.neuro_pain_localized,baseline_doctor_consultations.neuro_stump_distribution_of_pain,baseline_doctor_consultations.neuro_phantom_type_of_plp,baseline_doctor_consultations.neuro_other_finding,baseline_doctor_consultations.diagnostic_specify_combination,baseline_doctor_consultations.physical_treatment,baseline_doctor_consultations.specify_physical_treatment,baseline_doctor_consultations.no_treatment_offered_due_to,baseline_doctor_consultations.specify_no_treatment_offered_due_to,baseline_doctor_consultations.pharmacological_treatment,baseline_doctor_consultations.other_treatments,
v_baseline_injury_mechanism.*,
baseline_nurse_consultations.pain_duration,baseline_nurse_consultations.temporal_aspects,baseline_nurse_consultations.pain_scale,baseline_nurse_consultations.pain_bothersomeness,baseline_nurse_consultations.pain_intensity_during_rest,baseline_nurse_consultations.pain_intensity_during_activity,baseline_nurse_consultations.health_rate,baseline_nurse_consultations.phq_nervous,baseline_nurse_consultations.phq_worry,baseline_nurse_consultations.phq_little_interest,baseline_nurse_consultations.phq_feelingdown,baseline_nurse_consultations.pcs_thinking_hurts,baseline_nurse_consultations.pcs_overwhelms_pain,baseline_nurse_consultations.pcs_afraid_pain,baseline_nurse_consultations.pcl5_score,
baseline_pharmacist_consultations.laboratory_outside_reference,baseline_pharmacist_consultations.laboratory_specify,baseline_pharmacist_consultations.interactions,baseline_pharmacist_consultations.which_interactions,baseline_pharmacist_consultations.other_considereations,
v_baseline_treatment_goals.*,
v_baseline_previous_treatment.*,
v_baseline_treatment_choices.*,
v_baseline_pain_distributions.*,
v_baseline_neurological_exams.*,
v_baseline_neurological_exams_scars.*,
v_baseline_neurological_exams_others.*,
v_baseline_diagnostics.*

FROM patients,pain_files
LEFT JOIN patient_details ON pain_files.id=patient_details.pain_file_id
LEFT JOIN baseline_doctor_consultations ON pain_files.id=baseline_doctor_consultations.pain_file_id
LEFT JOIN v_baseline_injury_mechanism ON pain_files.id=v_baseline_injury_mechanism.im_pain_file_id
LEFT JOIN baseline_nurse_consultations ON pain_files.id=baseline_nurse_consultations.pain_file_id
LEFT JOIN baseline_pharmacist_consultations ON pain_files.id=baseline_pharmacist_consultations.pain_file_id
LEFT JOIN v_baseline_treatment_goals ON pain_files.id=v_baseline_treatment_goals.tr_pain_file_id
LEFT JOIN v_baseline_previous_treatment ON pain_files.id=v_baseline_previous_treatment.pt_pain_file_id
LEFT JOIN v_baseline_treatment_choices ON pain_files.id=v_baseline_treatment_choices.tc_pain_file_id
LEFT JOIN v_baseline_pain_distributions ON pain_files.id=v_baseline_pain_distributions.pd_pain_file_id
LEFT JOIN v_baseline_neurological_exams ON pain_files.id=v_baseline_neurological_exams.nex_pain_file_id
LEFT JOIN v_baseline_neurological_exams_scars ON pain_files.id=v_baseline_neurological_exams_scars.scars_pain_file_id
LEFT JOIN v_baseline_neurological_exams_others ON pain_files.id=v_baseline_neurological_exams_others.other_pain_file_id
LEFT JOIN v_baseline_diagnostics ON pain_files.id=v_baseline_diagnostics.d_pain_file_id
WHERE patients.id=pain_files.patient_id";

        $results3 = DB::select(DB::raw($sql));
    }

    public
    function create_v_baseline_neuro_exams_scars()
    {

        $baselineGoalMax = DB::query()->fromSub(function ($query) {
            $query->from('baseline_neuro_exams_scars')->select(DB::raw('count(id) as total'))->whereNull('deleted_at')->groupBy('pain_file_id');
        }, '')->max('total');
        //  dd($sql);
        $sql = 'SELECT pain_file_id as scars_pain_file_id';
//dd($baselineGoalMax);
        for ($i = 0; $i < $baselineGoalMax;) {
            $sql .= ',(select  scars_side_nerve_id from baseline_neuro_exams_scars a where a.deleted_at is null and a.pain_file_id=outbas.pain_file_id order by id limit ' . $i . ',1 ) as scars_side_nerve_id' . ++$i;
            $sql .= ',(select  scars_side_detail_id from baseline_neuro_exams_scars a where a.deleted_at is null and a.pain_file_id=outbas.pain_file_id order by id limit ' . --$i . ',1 ) as scars_side_detail_id' . ++$i;
            $sql .= ',(select  light_touch from baseline_neuro_exams_scars a where a.deleted_at is null and a.pain_file_id=outbas.pain_file_id order by id limit ' . --$i . ',1 ) as scars_light_touch' . ++$i;
            $sql .= ',(select  pinprick from baseline_neuro_exams_scars a where a.deleted_at is null and a.pain_file_id=outbas.pain_file_id order by id limit ' . --$i . ',1 ) as scars_pinprick' . ++$i;
            $sql .= ',(select  warmth from baseline_neuro_exams_scars a where a.deleted_at is null and a.pain_file_id=outbas.pain_file_id order by id limit ' . --$i . ',1 ) as scars_warmth' . ++$i;
            $sql .= ',(select  cold from baseline_neuro_exams_scars a where a.deleted_at is null and a.pain_file_id=outbas.pain_file_id order by id limit ' . --$i . ',1 ) as scars_cold' . ++$i;
        }

        //

        $sql .= ' FROM baseline_neuro_exams_scars outbas WHERE outbas.deleted_at is null group by pain_file_id order by id';
        // echo $sql;
        $sqlQuery2 = 'CREATE OR REPLACE VIEW v_baseline_neurological_exams_scars as ' . $sql;
        $results2 = DB::select(DB::raw($sqlQuery2));


        // dd($results3);

    }

    public
    function create_v_baseline_neuro_exams_other()
    {

        $baselineGoalMax = DB::query()->fromSub(function ($query) {
            $query->from('baseline_neuro_exams_others')->select(DB::raw('count(id) as total'))->whereNull('deleted_at')->groupBy('pain_file_id');
        }, '')->max('total');
        //  dd($sql);
        $sql = 'SELECT pain_file_id as other_pain_file_id';

        for ($i = 0; $i < $baselineGoalMax;) {
            $sql .= ',(select  other_side_nerve_id from baseline_neuro_exams_others a where a.deleted_at is null and a.pain_file_id=outbas.pain_file_id order by id limit ' . $i . ',1 ) as other_side_nerve_id' . ++$i;
            $sql .= ',(select  other_side_detail_id from baseline_neuro_exams_others a where a.deleted_at is null and a.pain_file_id=outbas.pain_file_id order by id limit ' . --$i . ',1 ) as other_side_detail_id' . ++$i;
            $sql .= ',(select  light_touch from baseline_neuro_exams_others a where a.deleted_at is null and a.pain_file_id=outbas.pain_file_id order by id limit ' . --$i . ',1 ) as other_light_touch' . ++$i;
            $sql .= ',(select  pinprick from baseline_neuro_exams_others a where a.deleted_at is null and a.pain_file_id=outbas.pain_file_id order by id limit ' . --$i . ',1 ) as other_pinprick' . ++$i;
            $sql .= ',(select  warmth from baseline_neuro_exams_others a where a.deleted_at is null and a.pain_file_id=outbas.pain_file_id order by id limit ' . --$i . ',1 ) as other_warmth' . ++$i;
            $sql .= ',(select  cold from baseline_neuro_exams_others a where a.deleted_at is null and a.pain_file_id=outbas.pain_file_id order by id limit ' . --$i . ',1 ) as other_cold' . ++$i;
        }

        //

        $sql .= ' FROM baseline_neuro_exams_others outbas WHERE outbas.deleted_at is null group by pain_file_id order by id';
        // echo $sql;
        $sqlQuery2 = 'CREATE OR REPLACE VIEW v_baseline_neurological_exams_others as ' . $sql;
        $results2 = DB::select(DB::raw($sqlQuery2));


        // dd($results3);

    }

//************

    public
    function export_baseline_excel_file()
    {
        $filename = "Export_excel_baseline.csv";
        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Content-Type: application/vnd.ms-excel");
        $model = DB::table('baseline_export')->select('baseline_export.*')->get()->toArray();

        $columns = DB::getSchemaBuilder()->getColumnListing('baseline_export');


        echo implode(",", array_values($columns)) . "\n";
        $i = 0;
        foreach ($model as $row) {
            //   print_r(array_keys($row));
            for ($i = 0; $i < count($columns); $i++) {
                $cloname = $columns[$i];
                $nestedData[$columns[$i]] = '"' . str_replace("/", "-", $row->$cloname) . '"';
                //  echo $nestedData[$columns[$i]] ;

            }
            //  dd($nestedData);
            echo implode(",", array_values($nestedData)) . "\n";
        }

    }

    public
    function create_v_followup_treatment_goals()
    {

        $baselineGoalMax = DB::query()->fromSub(function ($query) {
            $query->from('followup_treatment_goals')->select(DB::raw('count(id) as total'))->whereNull('deleted_at')->groupBy('followup_id');
        }, '')->max('total');
        //  dd($sql);
        $sql = 'SELECT pain_file_id as foll_goal_pain_file_id,followup_id as foll_goal_followup_id';

        for ($i = 0; $i < $baselineGoalMax;) {
            $sql .= ',(select  baseline_goal_id from followup_treatment_goals a where a.deleted_at is null and a.followup_id=outbas.followup_id order by id limit ' . $i . ',1 ) as baseline_goal_id' . ++$i;
            $sql .= ',(select  followup_score from followup_treatment_goals a where a.deleted_at is null and a.followup_id=outbas.followup_id order by id limit ' . --$i . ',1 ) as followup_score' . ++$i;
            $sql .= ',(select  followup_compliance from followup_treatment_goals a where a.deleted_at is null and a.followup_id=outbas.followup_id order by id limit ' . --$i . ',1 ) as followup_compliance' . ++$i;
            $sql .= ',(select  physical_treatment from followup_treatment_goals a where a.deleted_at is null and a.followup_id=outbas.followup_id order by id limit ' . --$i . ',1 ) as physical_treatment' . ++$i;
            $sql .= ',(select  days_on_prg from followup_treatment_goals a where a.deleted_at is null and a.followup_id=outbas.followup_id order by id limit ' . --$i . ',1 ) as days_on_program' . ++$i;
        }

        //

        $sql .= ' FROM followup_treatment_goals outbas WHERE outbas.deleted_at is null group by followup_id,pain_file_id order by pain_file_id,id';
        // echo $sql;
        $sqlQuery2 = 'CREATE OR REPLACE VIEW v_followup_treatment_goals as ' . $sql;
        $results2 = DB::select(DB::raw($sqlQuery2));


    }

    public
    function create_v_followup_dignostics()
    {

        $baselineGoalMax = DB::query()->fromSub(function ($query) {
            $query->from('followup_dignostics')->select(DB::raw('count(id) as total'))->whereNull('deleted_at')->groupBy('followup_id');
        }, '')->max('total');
        //  dd($sql);
        $sql = 'SELECT pain_file_id as foll_digno_pain_file_id,followup_id as foll_digno_followup_id';

        for ($i = 0; $i < $baselineGoalMax;) {
            $sql .= ',(select  diagnostic_id from followup_dignostics a where a.deleted_at is null and a.followup_id=outbas.followup_id order by id limit ' . $i . ',1 ) as diagnostic_id' . ++$i;

        }

        //

        $sql .= ' FROM followup_dignostics outbas WHERE outbas.deleted_at is null group by followup_id,pain_file_id order by pain_file_id,id';
        // echo $sql;
        $sqlQuery2 = 'CREATE OR REPLACE VIEW v_followup_dignostics as ' . $sql;
        $results2 = DB::select(DB::raw($sqlQuery2));


    }

    public
    function create_v_followup_last_treatment_goals()
    {

        $baselineGoalMax = DB::query()->fromSub(function ($query) {
            $query->from('followup_last_treatment_goals')->select(DB::raw('count(id) as total'))->whereNull('deleted_at')->groupBy('followup_id');
        }, '')->max('total');
        //  dd($sql);
        $sql = 'SELECT pain_file_id as foll_last_goal_pain_file_id,followup_id as foll_last_goal_followup_id';

        for ($i = 0; $i < $baselineGoalMax;) {
            $sql .= ',(select  baseline_goal_id from followup_treatment_goals a where a.deleted_at is null and a.followup_id=outbas.followup_id order by id limit ' . $i . ',1 ) as last_follow_baseline_goal_id' . ++$i;
            $sql .= ',(select  followup_score from followup_treatment_goals a where a.deleted_at is null and a.followup_id=outbas.followup_id order by id limit ' . --$i . ',1 ) as last_follow_followup_score' . ++$i;

        }

        //

        $sql .= ' FROM followup_last_treatment_goals outbas WHERE outbas.deleted_at is null group by followup_id,pain_file_id order by pain_file_id,id';
        // echo $sql;
        $sqlQuery2 = 'CREATE OR REPLACE VIEW v_followup_last_treatment_goals as ' . $sql;
        $results2 = DB::select(DB::raw($sqlQuery2));


    }

    public
    function create_v_followup_treatment_adverse_effects()
    {

        $baselineGoalMax = DB::query()->fromSub(function ($query) {
            $query->from('followup_treatment_adverse_effects')->select(DB::raw('count(id) as total'))->whereNull('deleted_at')->groupBy('followup_treatment_id');
        }, '')->max('total');
        //  dd($sql);
        $sql = 'SELECT followup_treatment_id';

        for ($i = 0; $i < $baselineGoalMax;) {
            $sql .= ',(select  adverse_effects from followup_treatment_adverse_effects a where a.deleted_at is null and a.followup_treatment_id=outbas.followup_treatment_id order by id limit ' . $i . ',1 ) as adverse_effects' . ++$i;

        }

        //

        $sql .= ' FROM followup_treatment_adverse_effects outbas WHERE outbas.deleted_at is null group by followup_treatment_id order by id';
        // echo $sql;
        $sqlQuery2 = 'CREATE OR REPLACE VIEW v_followup_treatment_adverse_effects as ' . $sql;
        $results2 = DB::select(DB::raw($sqlQuery2));


    }

    public
    function create_v_followup_treat_with_advers()
    {

        $sqlQuery2 = 'CREATE OR REPLACE VIEW v_followup_treat_with_advers
AS
select followup_treatments.*,v_followup_treatment_adverse_effects.*
FROM followup_treatments LEFT JOIN v_followup_treatment_adverse_effects
ON followup_treatments.id = v_followup_treatment_adverse_effects.followup_treatment_id
WHERE followup_treatments.deleted_at is null';
        $results2 = DB::select(DB::raw($sqlQuery2));


    }

    public
    function create_v_followup_treatments()
    {

        $baselineGoalMax = DB::query()->fromSub(function ($query) {
            $query->from('v_followup_treat_with_advers')->select(DB::raw('count(id) as total'))->whereNull('deleted_at')->groupBy('followup_id');
        }, '')->max('total');
        //  dd($sql);
        $advMax = DB::query()->fromSub(function ($query) {
            $query->from('followup_treatment_adverse_effects')->select(DB::raw('count(id) as total'))->whereNull('deleted_at')->groupBy('followup_treatment_id');
        }, '')->max('total');
        $sql = 'SELECT pain_file_id as foll_treat_pain_file_id,followup_id as foll_treat_followup_id';

        for ($i = 0; $i < $baselineGoalMax;) {
            $sql .= ',(select  drug_id from v_followup_treat_with_advers a where a.deleted_at is null and a.followup_id=outbas.followup_id order by id limit ' . $i . ',1 ) as drug_id' . ++$i;
            $sql .= ',(select  concentration from v_followup_treat_with_advers a where a.deleted_at is null and a.followup_id=outbas.followup_id order by id limit ' . --$i . ',1 ) as concentration' . ++$i;
            $sql .= ',(select  dosage from v_followup_treat_with_advers a where a.deleted_at is null and a.followup_id=outbas.followup_id order by id limit ' . --$i . ',1 ) as dosage' . ++$i;
            $sql .= ',(select  frequency from v_followup_treat_with_advers a where a.deleted_at is null and a.followup_id=outbas.followup_id order by id limit ' . --$i . ',1 ) as frequency' . ++$i;
            $sql .= ',(select  duration from v_followup_treat_with_advers a where a.deleted_at is null and a.followup_id=outbas.followup_id order by id limit ' . --$i . ',1 ) as duration' . ++$i;
            $sql .= ',(select  quantity from v_followup_treat_with_advers a where a.deleted_at is null and a.followup_id=outbas.followup_id order by id limit ' . --$i . ',1 ) as quantity' . ++$i;
            $sql .= ',(select  drug_specify from v_followup_treat_with_advers a where a.deleted_at is null and a.followup_id=outbas.followup_id order by id limit ' . --$i . ',1 ) as drug_specify' . ++$i;
            $sql .= ',(select  drug_comments from v_followup_treat_with_advers a where a.deleted_at is null and a.followup_id=outbas.followup_id order by id limit ' . --$i . ',1 ) as drug_comments' . ++$i;
            $sql .= ',(select  compliance from v_followup_treat_with_advers a where a.deleted_at is null and a.followup_id=outbas.followup_id order by id limit ' . --$i . ',1 ) as compliance' . ++$i;
            $sql .= ',(select  decision from v_followup_treat_with_advers a where a.deleted_at is null and a.followup_id=outbas.followup_id order by id limit ' . --$i . ',1 ) as decision' . ++$i;
            $k = $i;
            for ($j = 1; $j <= $advMax;) {

                $sql .= ',(select  adverse_effects' . $j . ' from v_followup_treat_with_advers a where a.deleted_at is null and a.followup_id=outbas.followup_id order by id limit ' . $k . ',1 ) as adverse_effects' . $j++ . 'treat' . $k;
            }
        }

        //

        $sql .= ' FROM v_followup_treat_with_advers outbas WHERE outbas.deleted_at is null group by followup_id,pain_file_id order by pain_file_id,id';
        // echo $sql;
        $sqlQuery2 = 'CREATE OR REPLACE VIEW v_followup_treatments as ' . $sql;
        //dd($sqlQuery2);
        $results2 = DB::select(DB::raw($sqlQuery2));


    }

    public
    function create_followup_export_vw()
    {
        $sql = " CREATE OR REPLACE VIEW followup_export as SELECT pain_files.id as pain_file_id,followup_patients.id as followup_id,followup_patients.follow_up_date,
followup_doctors.treatment_goal_achievements as doctor_treatment_goal_achievements,
followup_doctors.pain_scale AS doctor_pain_scale,
followup_doctors.pain_bothersomeness AS doctor_pain_bothersomeness,
followup_doctors.pain_intensity_during_rest AS doctor_pain_intensity_during_rest,
followup_doctors.pain_intensity_during_activity AS doctor_pain_intensity_during_activity,
followup_doctors.change_diagnosis,
followup_doctors.diagnostic_specify,
followup_doctors.additional_ptsd,
followup_doctors.change_treatment,
followup_doctors.physical_treatment,
followup_doctors.specify_physical_treatment,
followup_doctors.last_scheduled_further_treatment,
followup_doctors.last_scheduled_why_treatment_ended,

followup_nurses.treatment_goal_achievements as nurse_treatment_goal_achievements,
followup_nurses.pain_scale AS nurse_pain_scale,
followup_nurses.pain_bothersomeness AS nurse_pain_bothersomeness,
followup_nurses.pain_intensity_during_rest AS nurse_pain_intensity_during_rest,
followup_nurses.pain_intensity_during_activity AS nurse_pain_intensity_during_activity,

followup_pharmacists.specify_other_adverse_effects AS nurse_specify_other_adverse_effects,
followup_pharmacists.specify_other_changes,

followup_last.pain_scale AS last_pain_scale,
followup_last.pain_bothersomeness AS last_pain_bothersomeness,
followup_last.pain_intensity_during_rest AS last_pain_intensity_during_rest,
followup_last.pain_intensity_during_activity AS last_pain_intensity_during_activity,


health_rate,phq_nervous,phq_worry,phq_little_interest,
phq_feelingdown,pcs_thinking_hurts,
pcs_overwhelms_pain,
pcs_afraid_pain,
pcl5_score,
treatment_satisfied,
overall_status,
v_followup_dignostics.*,
v_followup_treatments.*,
v_followup_treatment_goals.*,
v_followup_last_treatment_goals.*

FROM pain_files 
LEFT JOIN followup_last 	ON pain_files.id=followup_last.pain_file_id
LEFT JOIN v_followup_last_treatment_goals ON pain_files.id=v_followup_last_treatment_goals.foll_last_goal_pain_file_id,
followup_patients
LEFT JOIN followup_doctors 	ON followup_patients.id=followup_doctors.followup_id
LEFT JOIN followup_nurses 	ON followup_patients.id=followup_nurses.followup_id
LEFT JOIN followup_pharmacists 	ON followup_patients.id=followup_pharmacists.followup_id
LEFT JOIN v_followup_dignostics ON followup_patients.id=v_followup_dignostics.foll_digno_followup_id
LEFT JOIN v_followup_treatments ON followup_patients.id=v_followup_treatments.foll_treat_followup_id
LEFT JOIN v_followup_treatment_goals ON followup_patients.id=v_followup_treatment_goals.foll_goal_followup_id

WHERE pain_files.id=followup_patients.pain_file_id
ORDER BY `pain_file_id` ASC";

        $results3 = DB::select(DB::raw($sql));
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

    function get_moi_person_info(Request $request)
    {
        //echo 'get_moi_person_info';exit;
        //   dd($request->all());
        $id = $request->national_id;

        $jason_result = $this->call_api($id);

        //  return response()->json(['success' => true, 'patient' => $jason_result]);
        return $jason_result;
    }

    function call_api($id)
    {
        //   echo '$id'.$id;
        $headers = array('Content-Type: application/json');

        $curl_handle = curl_init();

        curl_setopt($curl_handle, CURLOPT_URL, 'http://10.10.10.52/moi_api/index.php/API/patientid?id=' . $id . '&format=json');
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_handle, CURLOPT_USERAGENT, 'newpatient');

        curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($curl_handle);
        //  print_r($result);exit;
        curl_close($curl_handle);

        //return json_encode($result);
        return $result;
    }

    //*****************************************//
    public function get_alerts_data(Request $request)
    {
        $patient_id = $request->patient_id;
        $alert_table = $this->draw_alerts_table($patient_id);
        return response()->json(['success' => true, 'alert_table' => $alert_table]);
    }

    public function get_curr_patient_alert(Request $request)
    {
        $patient_id = $request->patient_id;
        $alerts = Alert::where('patient_id', $patient_id)->orderBy('id', 'desc')->get();
        $alert_html = '';

        foreach ($alerts as $alert)
            if ($alert->alert_type == 1)
                $alert_html .= ' <div class="alert alert-success" id="patient_alert"><i class="fa fa-tag"></i>&nbsp;' . $alert->alert_text . ' &nbsp;&nbsp;&nbsp;<i class="fa fa-user-md"></i>&nbsp;' . $alert->user_name . '&nbsp;&nbsp;&nbsp; <i class="fa fa-calendar"></i>&nbsp;' . $alert->created_at . ' </div>';
            else if ($alert->alert_type == 2)
                $alert_html .= ' <div class="alert alert-warning" id="patient_alert"><i class="fa fa-tag"></i>&nbsp;' . $alert->alert_text . ' &nbsp;&nbsp;&nbsp; <i class="fa fa-user-md"></i>&nbsp;' . $alert->user_name . '&nbsp;&nbsp;&nbsp;<i class="fa fa-calendar"></i>&nbsp;' . $alert->created_at . '</div>';
            else if ($alert->alert_type == 3)
                $alert_html .= ' <div class="alert alert-danger" id="patient_alert"><i class="fa fa-tag"></i>&nbsp;' . $alert->alert_text . ' &nbsp;&nbsp;&nbsp;<i class="fa fa-user-md"></i>&nbsp;' . $alert->user_name . '&nbsp;&nbsp;&nbsp;<i class="fa fa-calendar"></i>&nbsp;' . $alert->created_at . ' </div>';
        return response()->json(['success' => true, 'alert_html' => $alert_html]);
    }

    public function add_alert(Request $request)
    {
        $patient_id = $request->patient_id;
        $alert_text = $request->alert_text;
        $alert_type = $request->alert_type;
        $alert = new Alert();
        $alert->alert_text = $alert_text;
        $alert->alert_type = $alert_type;
        $alert->patient_id = $patient_id;
        $alert->org_id = auth()->user()->org_id;
        $alert->created_by = auth()->user()->id;
        if ($alert->save()) {
            $alert_table = $this->draw_alerts_table($patient_id);
            return response()->json(['success' => true, 'alert_table' => $alert_table]);
        } else
            return response()->json(['success' => false, 'alert_table' => '']);
    }

    public function delete_alert(Request $request)
    {
        $id = $request->id;
        $patient_id = $request->patient_id;
        $alert = Alert::find($id);
        if (isset($alert)) {

            $alert->delete();
            $alert_table = $this->draw_alerts_table($patient_id);
            return response()->json(['success' => true, 'alert_table' => $alert_table]);
        }
        return response()->json(['success' => false, 'alert_table' => '']);


    }
}

