<?php

namespace App\Http\Controllers;

use App\BaselineDiagnostics;
use App\BaselineDoctorConsultation;
use App\BaselineNeuroExamsOther;
use App\BaselineNeuroExamsScars;
use App\BaselineNeurologicalExam;
use App\BaselineNurseConsultation;
use App\BaselinePainDistribution;
use App\BaselinePharmacistConsultation;
use App\BaselinePreviousTreatment;
use App\BaselinePsychologyConsultation;
use App\Diagnostics;
use App\DrugList;
use App\ItemsTb;
use App\PainLocation;
use App\Patient;
use App\PatientDetail;
use App\PcsOption;
use App\PhqOption;
use App\Project;
use App\SideNerve;
use App\User;
use Illuminate\Http\Request;

class BaselineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /* public function __construct()
     {
         parent::__construct();
         $this->data['one_patient'] =  $this->one_patient;
         $this->data['one_painFile'] =  $this->one_painFile;
         $this->data['painFile_status'] =  $this->painFile_status;
         $this->data['districts'] =  $this->districts;
     }*/
    public function index()
    {
        //
    }
    public function test_page()
    {
        return view(baseline_vw() . '.test');

    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($painFile_id = null, $patientid = null, $painFile_status = null)
    {
        // DOCTOR

        $this->data['page_title'] = 'Base Line';
        $this->data['location_link'] = 'painFile/view/' . $painFile_id . '/' . $patientid . '/' . $painFile_status;
        $this->data['location_title'] = 'Pain File';

        if (!isset($painFile_id))
            return redirect()->route('home');
        // $this->data['painFile_id'] = $painFile_id;
        $this->data['one_patient'] = Patient::find($patientid);
        $this->data['painFile_id'] = $painFile_id;
        $this->data['painFile_status'] = ($painFile_status == 17) ? 'Open' : 'Closed';
        $this->data['painFile_statusId'] = $painFile_status;
        $this->data['districts']=get_lookups_list(1);
        $this->data['one_patient_detail'] = PatientDetail::where('pain_file_id', $painFile_id)->first();
        // $this->data['message_data'] = $this->get_message($painFile_id);
        //  dd($painFile_id);
        // dd( $this->data['message_data'] );
        $this->data['education_list'] = get_lookups_list(19);
        $this->data['current_work_list'] = get_lookups_list(24);
        $this->data['pain_duration_list'] = get_lookups_list(57);
        $this->data['temporal_aspect_list'] = get_lookups_list(66);
        $this->data['health_rate_list'] = get_lookups_list(70);
        $this->data['pain_location_list'] = PainLocation::where('isActive', 1)->get();
        $this->data['phq_nervous_list'] = PhqOption::where('isActive', 1)->get();
        $this->data['pcs_list'] = PcsOption::where('isActive', 1)->get();
        $this->data['drug_item'] = ItemsTb::where('isActive', 1)->get();
        $this->data['drug_list'] = DrugList::where('isActive', 1)->get();
        //dd($this->data['drug_list']);
        $this->data['diagnosis_list'] = Diagnostics::where('isActive', 1)->get();
        $this->data['one_baseline_nurse'] = BaselineNurseConsultation::where('pain_file_id', $painFile_id)->first();
        $this->data['one_baseline_pain_dist'] = BaselinePainDistribution::where('pain_file_id', $painFile_id)->get();
        $this->data['treatment_goals_data'] = $this->draw_treatment_goal_nurse($painFile_id, $painFile_status);
        $this->data['treatment_doc_goals_data'] = $this->draw_treatment_goal_doctor($painFile_id, $painFile_status);
        $this->data['htmlqutenza'] = $this->draw_qutenza_table($painFile_id);

        // DOCTOR
        $one_baseline_doctor = BaselineDoctorConsultation::where('pain_file_id', $painFile_id)->first();
        //   $temp=array();
        //     $temp=['0'=>$one_baseline_doctor->injury_mechanism];
        //   $one_baseline_doctor->injury_mechanism=serialize($one_baseline_doctor->injury_mechanism);
        //  $one_baseline_doctor->save();
        $this->data['one_baseline_doctor'] = $one_baseline_doctor;
        if (isset($one_baseline_doctor->injury_mechanism))
            $this->data['one_baseline_injury_mechanism'] = unserialize($one_baseline_doctor->injury_mechanism);
        else
            $this->data['one_baseline_injury_mechanism'] = [];
        //  dd($this->data['one_baseline_injury_mechanism']);
        $this->data['baseline_doctor_visit_date'] = '';
        $baselineDoctor = $this->data['one_baseline_doctor'];
        if (isset($baselineDoctor))
            $this->data['baseline_doctor_visit_date'] = $baselineDoctor->visit_date;
        $this->data['one_baseline_diagnosis'] = BaselineDiagnostics::where('pain_file_id', $painFile_id)->get();
        $this->data['doctor_list'] = User::where('user_type_id', 9)->get();
        $this->data['injury_mechanism_list'] = get_lookups_list(29);
        $this->data['no_treatment_offered_list'] = get_lookups_list(128);
        $this->data['neuro_pain_localized_list'] = get_lookups_list(45);
        $this->data['side_nerves_list'] = get_lookups_list(138);
        $this->data['scars_side_list'] = get_lookups_list(270);
        $this->data['nerve_side_list'] = get_lookups_list(294);
        $this->data['light_touch_list'] = get_lookups_list(49);
        $this->data['warmth_list'] = get_lookups_list(53);
        $this->data['effect_list'] = get_lookups_list(76);
        $this->data['qutenza_list'] = get_lookups_list(369);
        $this->data['physical_treatment_list'] = get_lookups_list(83);

        //  dd($this->data['physical_treatment_list'] );
        $this->data['previousTreatment_data'] = $this->getbaseline_PreviousTreatment($painFile_id, $painFile_status);
        $this->data['treatmentChoice_data'] = $this->draw_treatment_choice_drugs_doctor($painFile_id, $painFile_status);
        $this->data['sideNerve_data'] = $this->getbaseline_sideNerve($painFile_id, $painFile_status);
        $this->data['scars_sideNerve_data'] = $this->getbaseline_scars_sideNerve($painFile_id, $painFile_status);
        $this->data['other_sideNerve_data'] = $this->getbaseline_other_sideNerve($painFile_id, $painFile_status);
        $this->data['qutenza_patient_datatable']  = $this->draw_qutenza_table($painFile_id);
       // $this->data['qutenza_score_datatable']  = $this->draw_qutenza_score_table($painFile_id);
        $this->data['patient_project_datatable']  = $this->draw_patient_project_table($painFile_id);
        $this->data['patient_project_followup_datatable']  = $this->draw_patient_project_followup_table($painFile_id);
        $this->data['project_list']  = Project::where('consequence',367)->get();

        // PHARMACIST
        $this->data['one_baseline_pharmacist'] = BaselinePharmacistConsultation::where('pain_file_id', $painFile_id)->first();
        $this->data['one_psychologist'] = BaselinePsychologyConsultation::where('pain_file_id', $painFile_id)->first();
        $this->data['treatmentChoice_data_pharmacist'] = $this->draw_treatment_choice_drugs_pharm($painFile_id, $painFile_status);
        return view(baseline_vw() . '.baseline')->with($this->data);
    }


    public function getbaseline_PreviousTreatment($painFile_id, $painFile_status)
    {
        $model = BaselinePreviousTreatment::where('pain_file_id', $painFile_id)->get();
        $html = '';
        $i = 1;
        foreach ($model as $raw) {
            $html .= '<tr><td>' . $i++ . '</td>';
            $html .= '<td>' . get_drug_list_desc($raw->drug_id) . '</td>';
            $html .= '<td> ' . get_lookup_desc($raw->effect_id) . '</td>';
            if ($painFile_status == 17)
                $html .= '<td><button type="button" class="btn btn-danger btn-icon-only" onclick="del_prvtreatment_drug(' . $raw->id . ')"><i class="fa fa-minus fa-fw"></i></button></td></tr>';
            else
                $html .= '<td></td></tr>';
        }
        return $html;
    }

    public
    function getbaseline_sideNerve($painFile_id, $painFile_status)
    {
        $side_nerves = BaselineNeurologicalExam::where('pain_file_id', $painFile_id)->get();
        $html = '';
        $i = 1;
        foreach ($side_nerves as $raw) {
            $html .= '<tr><td>' . $i++ . '</td>';
            $html .= '<td>' . get_lookup_desc($raw->side_nerve_id) . '</td>';
            $html .= '<td>' . get_lookup_desc($raw->side_detail_id) . '</td>';
            $html .= '<td> ' . get_lookup_desc($raw->light_touch) . '</td>';
            $html .= '<td> ' . get_lookup_desc($raw->pinprick) . '</td>';
            $html .= '<td> ' . get_lookup_desc($raw->warmth) . '</td>';
            $html .= ' <td> ' . get_lookup_desc($raw->cold) . '</td>';
            if ($painFile_status == 17)
                $html .= '<td><button type="button" class="btn btn-danger btn-icon-only" onclick="del_nerve(' . $raw->id . ')">
<i class="fa fa-minus fa-fw"></i>
</button></td></tr>';
            else
                $html .= '<td></td></tr>';
        }
        return $html;
    }

    public
    function getbaseline_scars_sideNerve($painFile_id, $painFile_status)
    {
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
                $html .= '<td><button type="button" class="btn btn-danger btn-icon-only" onclick="del_scars_nerve(' . $raw->id . ')">
<i class="fa fa-minus fa-fw"></i></button></td></tr>';
            else
                $html .= '<td></td></tr>';
        }
        return $html;
    }

    function getbaseline_other_sideNerve($painFile_id, $painFile_status)
    {
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
        return $html;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public
    function store(Request $request)
    {
        //
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
