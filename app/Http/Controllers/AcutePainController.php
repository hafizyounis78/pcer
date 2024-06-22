<?php

namespace App\Http\Controllers;

use App\AcuteNeurologicalExam;
use App\AcutePainLocation;
use App\AcutePainMedicationBeforeInjury;
use App\AcutePainMedicationNow;
use App\AcutePainService;
use App\BaselineDoctorConsultation;
use App\DrugList;
use App\PainLocation;
use App\Patient;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


class AcutePainController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $_param;
    private $painFile_id;
    private $patientid;
    private $painFile_status;

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($painFile_id = null, $patientid = null, $painFile_status = null)
    {
        // dd($painFile_id);

        $this->data['page_title'] = 'Acute Pain';
        $this->data['location_link'] = 'painFile/view/' . $painFile_id . '/' . $patientid . '/' . $painFile_status;
        $this->data['location_title'] = 'Pain File';
        //  $this->painFile_id = $painFileid;
        //    dd( $this->painFile_status);

        //    $patientid = session()->get('patient_id');
        if (!isset($painFile_id))
            return redirect()->route('home');
        //********** Patient profile
        $this->data['painFile_id'] = $painFile_id;
        $this->data['painFile_status'] = ($painFile_status == 17) ? 'Open' : 'Closed';
        $this->data['painFile_statusId'] = $painFile_status;
        $this->data['one_patient'] = Patient::find($patientid);
        $this->data['districts'] = get_lookups_list(1);
        //**********End Patient profile
        $this->data['one_acutePain'] = AcutePainService::where('pain_file_id', $painFile_id)->first();
        // dd($this->data['one_acutePain'] );
        $this->data['baseline_doctor_visit_date'] = '';
        $baselineDoctor = BaselineDoctorConsultation::where('pain_file_id', $painFile_id)->first();
        if (isset($baselineDoctor))
            $this->data['baseline_doctor_visit_date'] = $baselineDoctor->visit_date;
        $this->data['one_pain_location'] = AcutePainLocation::where('pain_file_id', $painFile_id)->get();
        $this->data['one_pain_med_before_inj'] = AcutePainMedicationBeforeInjury::where('pain_file_id', $painFile_id)->get();
        $this->data['one_pain_med_now'] = $this->getAcutePain_MedicationNow($painFile_id, $painFile_status);
        $this->data['one_pain_sideNerve'] = $this->getAcutePain_sideNerve($painFile_id, $painFile_status);
        $this->data['injury_mechanism_list'] = get_lookups_list(29);
        $this->data['status_list'] = get_lookups_list(34);
        $this->data['doctor_list'] = User::where('user_type_id', 9)->get();
        $this->data['timing_of_consultation_list'] = get_lookups_list(40);
        $this->data['pain_location_list'] = PainLocation::where('isActive', 1)->get();
        $this->data['drug_list'] = DrugList::where('isActive', 1)->get();
        $this->data['neuro_pain_localized_list'] = get_lookups_list(45);
        $this->data['side_nerves_list'] = get_lookups_list(138);

        $this->data['light_touch_list'] = get_lookups_list(49);
        $this->data['warmth_list'] = get_lookups_list(53);
        // dd($this->data['status']);
        return view(acutepain_vw() . '.acutepain')->with($this->data);
    }

    public function getAcutePain_MedicationNow($painFile_id, $painFile_status)
    {
        $model = AcutePainMedicationNow::where('pain_file_id', $painFile_id)->get();
        $html = '';
        $i = 1;
        foreach ($model as $raw) {
            $html .= '<tr><td>' . $i++ . '</td>';
            $html .= '<td>' . get_drug_desc($raw->drug_id) . '</td>';
            $html .= '<td> ' . $raw->dosage . '</td>';
            if ($painFile_status == 17)
                $html .= '<td><button type="button" class="btn red" onclick="del_medicationNow(' . $raw->id . ')">-</button></td></tr>';
            else
                $html .= '<td></td></tr>';
        }
        return $html;
    }

    public function getAcutePain_sideNerve($painFile_id, $painFile_status)
    {
        $side_nerves = AcuteNeurologicalExam::where('pain_file_id', $painFile_id)->get();
        $html = '<option value="">Select..</option>';
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
                $html .= '<td><button type="button" class="btn red" onclick="del_nerve(' . $raw->id . ')">-</button></td></tr>';
            else
                $html .= '<td></td></tr>';
        }
        return $html;
    }

    public function get_side_and_nerve_details(Request $request)
    {
        $parent_id = $request->id;
        $details_list = get_lookups_list($parent_id);
        $html = '<option value="">Select..</option>';
        foreach ($details_list as $raw)
            $html .= '<option value="' . $raw->id . '">' . $raw->lookup_details . '</option>';
        return response()->json(['success' => true, 'html' => $html]);

    }

    public function get_sub_side_details(Request $request)
    {
        $parent_id = $request->id;
        $details_list = get_lookups_list($parent_id);
        $html = '<option value="">Select..</option>';
        foreach ($details_list as $raw)
            $html .= '<option value="' . $raw->id . '">' . $raw->lookup_details . '</option>';
        return response()->json(['success' => true, 'html' => $html]);

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
        $acute_pain = AcutePainService::where('pain_file_id', $painFile_id)->count();
        if ($acute_pain == 0) {
            $acute_pain = new AcutePainService();
            $acute_pain->pain_file_id = $painFile_id;
            $acute_pain->visit_date = $request->visit_date;
            $acute_pain->first_war_injury = $request->first_war_injury;
            $acute_pain->injury_mechanism = $request->injury_mechanism;
            $acute_pain->specify_injury_mechanism = $request->specify_injury_mechanism;
            $acute_pain->status = $request->status;
            $acute_pain->specify_status = $request->specify_status;
            $acute_pain->timing_of_consultation = $request->timing_of_consultation;
            $acute_pain->specify_timing_of_consultation = $request->specify_timing_of_consultation;
            $acute_pain->medication_before_injury = $request->medication_before_injury;
            $acute_pain->other_medication_before_injury = $request->other_medication_before_injury;
            $acute_pain->medication_now = $request->medication_now;
            $acute_pain->planned_further_treatment = $request->planned_further_treatment;
            $acute_pain->neuro_history_of_pain = $request->neuro_history_of_pain;
            $acute_pain->neuro_pain_localized = $request->neuro_pain_localized;
            $acute_pain->neuro_stump_distribution_of_pain = $request->neuro_stump_distribution_of_pain;
            $acute_pain->neuro_phantom_type_of_plp = $request->neuro_phantom_type_of_plp;
            $acute_pain->neuro_other_finding = $request->neuro_other_finding;
            $acute_pain->second_doctor_id = $request->second_doctor_id;
            $acute_pain->neuro_other_finding = $request->neuro_other_finding;
            $acute_pain->created_by = auth()->user()->id;
            $acute_pain->org_id = auth()->user()->org_id;
            if ($acute_pain->save()) {
                $med_befor_injurys = $request->get('medication_before_injury_list');
                if (isset($med_befor_injurys))
                    foreach ($med_befor_injurys as $option => $value) {
                        $medication_before_injury = new AcutePainMedicationBeforeInjury();
                        $medication_before_injury->drug_id = $value;
                        $medication_before_injury->pain_file_id = $painFile_id;
                        $medication_before_injury->created_by = auth()->user()->id;
                        $medication_before_injury->org_id = auth()->user()->org_id;
                        $medication_before_injury->save();
                    }
                $pain_locations = $request->get('pain_location');
                if (isset($pain_locations))
                    foreach ($pain_locations as $option => $value) {
                        $pain_location = new AcutePainLocation();
                        $pain_location->location_id = $value;
                        $pain_location->pain_file_id = $painFile_id;
                        $pain_location->created_by = auth()->user()->id;
                        $pain_location->org_id = auth()->user()->org_id;
                        $pain_location->save();
                    }
                return response()->json(['success' => true]);

            }
        } else {
            $acute_pain = AcutePainService::where('pain_file_id', $painFile_id)->first();
            $acute_pain->pain_file_id = $painFile_id;
            $acute_pain->visit_date = $request->visit_date;
            $acute_pain->first_war_injury = $request->first_war_injury;
            $acute_pain->injury_mechanism = $request->injury_mechanism;
            $acute_pain->specify_injury_mechanism = $request->specify_injury_mechanism;
            $acute_pain->status = $request->status;
            $acute_pain->specify_status = $request->specify_status;
            $acute_pain->timing_of_consultation = $request->timing_of_consultation;
            $acute_pain->specify_timing_of_consultation = $request->specify_timing_of_consultation;
            $acute_pain->medication_before_injury = $request->medication_before_injury;
            $acute_pain->other_medication_before_injury = $request->other_medication_before_injury;
            $acute_pain->medication_now = $request->medication_now;
            $acute_pain->planned_further_treatment = $request->planned_further_treatment;
            $acute_pain->neuro_history_of_pain = $request->neuro_history_of_pain;
            $acute_pain->neuro_pain_localized = $request->neuro_pain_localized;
            $acute_pain->neuro_stump_distribution_of_pain = $request->neuro_stump_distribution_of_pain;
            $acute_pain->neuro_phantom_type_of_plp = $request->neuro_phantom_type_of_plp;
            $acute_pain->neuro_other_finding = $request->neuro_other_finding;
            $acute_pain->second_doctor_id = $request->second_doctor_id;
            $acute_pain->neuro_other_finding = $request->neuro_other_finding;
            if ($acute_pain->save()) {
                $del_acutePainMedicationBeforeInjury = AcutePainMedicationBeforeInjury::where('pain_file_id', $painFile_id)->delete();
                $med_befor_injurys = $request->get('medication_before_injury_list');
                if (isset($med_befor_injurys))
                    foreach ($med_befor_injurys as $option => $value) {
                        $medication_before_injury = new AcutePainMedicationBeforeInjury();
                        $medication_before_injury->drug_id = $value;
                        $medication_before_injury->pain_file_id = $painFile_id;
                        $medication_before_injury->created_by = auth()->user()->id;
                        $medication_before_injury->org_id = auth()->user()->org_id;
                        $medication_before_injury->save();
                    }
                $pain_locations = $request->get('pain_location');
                $del_acutePainLocation = AcutePainLocation::where('pain_file_id', $painFile_id)->delete();
                if (isset($pain_locations))
                    foreach ($pain_locations as $option => $value) {
                        $pain_location = new AcutePainLocation();
                        $pain_location->location_id = $value;
                        $pain_location->pain_file_id = $painFile_id;
                        $pain_location->created_by = auth()->user()->id;
                        $pain_location->org_id = auth()->user()->org_id;
                        $pain_location->save();
                    }
                return response()->json(['success' => true]);

            }
        }
        return response()->json(['success' => false, 'html' => '']);

    }

    public function inser_sideNerve(Request $request)
    {
        //dd($request->all());
        $painFile_id = $request->painFile_id;
        $painFile_status = $request->painFile_status;
        $side_nerve = new AcuteNeurologicalExam();
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
            $side_nerves = AcuteNeurologicalExam::where('pain_file_id', $painFile_id)->get();
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
                    $html .= '<td><button type="button" class="btn red" onclick="del_nerve(' . $raw->id . ')">-</button></td></tr>';
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
        $side_nerves = AcuteNeurologicalExam::find($id);
        if (isset($side_nerves)) {
            $side_nerves->delete();
            $side_nerves = AcuteNeurologicalExam::where('pain_file_id', $painFile_id)->get();
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
                    $html .= '<td><button type="button" class="btn red" onclick="del_nerve(' . $raw->id . ')">-</button></td></tr>';
                else
                    $html .= '<td></td></tr>';
            }
            return response()->json(['success' => true, 'html' => $html]);
        } else {
            return response()->json(['success' => false, 'html' => '']);
        }

    }

    public function inser_medicationNow(Request $request)
    {
        //dd($request->all());
        $painFile_id = $request->painFile_id;
        $painFile_status = $request->painFile_status;
        $mediction = new AcutePainMedicationNow();
        $mediction->pain_file_id = $painFile_id;
        $mediction->drug_id = $request->drug_id;
        $mediction->dosage = $request->dosage;
        $mediction->org_id = auth()->user()->org_id;
        $mediction->created_by = auth()->user()->id;

        if ($mediction->save()) {
            $model = AcutePainMedicationNow::where('pain_file_id', $painFile_id)->get();
            $html = '';
            $i = 1;
            foreach ($model as $raw) {
                $html .= '<tr><td>' . $i++ . '</td>';
                $html .= '<td>' . get_drug_desc($raw->drug_id) . '</td>';
                $html .= '<td> ' . $raw->dosage . '</td>';
                if ($painFile_status == 17)
                    $html .= '<td><button type="button" class="btn red" onclick="del_medicationNow(' . $raw->id . ')">-</button></td></tr>';
                else
                    $html .= '<td></td></tr>';
            }
            return response()->json(['success' => true, 'html' => $html]);

        }
        return response()->json(['success' => false, 'html' => '']);


    }

    public function delete_medicationNow(Request $request)
    {
        $id = $request->id;
        $painFile_id = $request->painFile_id;
        $painFile_status = $request->painFile_status;
        $model = AcutePainMedicationNow::find($id);
        if (isset($model)) {
            $model->delete();
            $model = AcutePainMedicationNow::where('pain_file_id', $painFile_id)->get();
            $html = '';
            $i = 1;
            foreach ($model as $raw) {
                $html .= '<tr><td>' . $i++ . '</td>';
                $html .= '<td>' . get_drug_desc($raw->drug_id) . '</td>';
                $html .= '<td> ' . $raw->dosage . '</td>';
                if ($painFile_status == 17)
                    $html .= '<td><button type="button" class="btn red" onclick="del_medicationNow(' . $raw->id . ')">-</button></td></tr>';
                else
                    $html .= '<td></td></tr>';
            }
            return response()->json(['success' => true, 'html' => $html]);
        } else {
            return response()->json(['success' => false, 'html' => '']);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
