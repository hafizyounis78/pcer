<?php

namespace App\Http\Controllers;

use App\BaselineDoctorConsultation;
use App\Patient;
use App\QutenzaScore;
use App\QutenzaTreatment;
use Illuminate\Http\Request;

class QutenzaTreatmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($painFile_id = null, $patientid = null, $painFile_status = null)
    {

        $this->data['sub_menu'] = 'qutenza-display';
        $this->data['location_title'] = 'Qutenza Patch';
        $this->data['location_link'] = 'qutenza';
        $this->data['title'] = 'Qutenza Patch';
        $this->data['page_title'] = 'Display Qutenza Patch';
        $this->data['one_patient'] = Patient::find($patientid);
        $this->data['painFile_id'] = $painFile_id;
        $this->data['painFile_status'] = ($painFile_status == 17) ? 'Open' : 'Closed';
        $this->data['painFile_statusId'] = $painFile_status;
        $this->data['baseline_doctor_visit_date'] = '';
        $baselineDoctor = BaselineDoctorConsultation::where('pain_file_id', $painFile_id)->first();
        $this->data['one_baseline_doctor'] = $baselineDoctor;
        if (isset($baselineDoctor))
            $this->data['baseline_doctor_visit_date'] = $baselineDoctor->visit_date;
        $this->data['districts'] = get_lookups_list(1);
        return view(qutenza_vw() . '.view')->with($this->data);
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
    public function addQutenza(Request $request)
    {
        // dd($request->all());
        $id = $request->hdnqutenza_id;
        $followup_id = $request->followup_id;
        $allodynia = $request->allodynia;
        $application_time = $request->application_time;
        // $pain_score = $request->qutenza_pain_scale;
        $erythema = $request->erythema;
        $pain = $request->pain;
        $pruritus = $request->pruritus;
        $papules = $request->papules;
        $edema = $request->edema;
        $swelling = $request->swelling;
        $dryness = $request->dryness;
        $nasopharyngitis = $request->nasopharyngitis;
        $bronchitis = $request->bronchitis;
        $sinusitis = $request->sinusitis;
        $nausea = $request->nausea;
        $vomiting = $request->vomiting;
        $skin_pruritus = $request->skin_pruritus;
        $hypertension = $request->hypertension;
        $hypertension_systolic = $request->hypertension_systolic;
        $hypertension_diastolic = $request->hypertension_diastolic;
        //$after_ttt_systolic = $request->after_ttt_systolic;
        // $after_ttt_diastolic = $request->after_ttt_diastolic;
        $painFile_id = $request->painFile_id;

        //  $patient_id = $request->patient_id;
        $visit_date = $request->qutenza_date;
        $visit_type = $request->visit_type;
        //  $switch_value = ['on' => 1, 'off' => 0];
        $qutenza = QutenzaTreatment::find($id);
        if (isset($qutenza)) {
            $qutenza->application_time = isset($application_time) ? $application_time : 0;

            $qutenza->allodynia = isset($allodynia) ? $allodynia : 0;
            $qutenza->erythema = isset($erythema) ? $erythema : 0;
            $qutenza->pain = isset($pain) ? $pain : 0;
            $qutenza->pruritus = isset($pruritus) ? $pruritus : 0;
            $qutenza->papules = isset($papules) ? $papules : 0;
            $qutenza->edema = isset($edema) ? $edema : 0;
            $qutenza->swelling = isset($swelling) ? $swelling : 0;
            $qutenza->dryness = isset($dryness) ? $dryness : 0;
            $qutenza->nasopharyngitis = isset($nasopharyngitis) ? $nasopharyngitis : 0;
            $qutenza->bronchitis = isset($bronchitis) ? $bronchitis : 0;
            $qutenza->sinusitis = isset($sinusitis) ? $sinusitis : 0;
            $qutenza->nausea = isset($nausea) ? $nausea : 0;
            $qutenza->vomiting = isset($vomiting) ? $vomiting : 0;
            $qutenza->skin_pruritus = isset($skin_pruritus) ? $skin_pruritus : 0;
            $qutenza->hypertension = isset($hypertension) ? $hypertension : 0;
            $qutenza->hypertension_systolic = isset($hypertension_systolic) ? $hypertension_systolic : 0;
            $qutenza->hypertension_diastolic = isset($hypertension_diastolic) ? $hypertension_diastolic : 0;
            $qutenza->user_id = auth()->user()->id;
            // $qutenza->after_ttt_systolic = isset($after_ttt_systolic) ? $after_ttt_systolic : 0;
            //$qutenza->after_ttt_diastolic = isset($after_ttt_diastolic) ? $after_ttt_diastolic : 0;
            //  $qutenza->pain_score = isset($pain_score) ? $pain_score : 0;
            if ($qutenza->save()) {
                $html = $this->draw_qutenza_table($painFile_id);
                return response()->json(['success' => true, 'qutenza_html' => $html, 'qutenza_id' => $qutenza->id]);
            }
            return response()->json(['success' => false]);
        } else {
            $qutenza = new QutenzaTreatment();

            $qutenza->pain_file_id = $painFile_id;
            // $qutenza->patient_id = isset($patient_id)?$patient_id:null;;
            $qutenza->followup_id = isset($followup_id) ? $followup_id : null;
            $qutenza->visit_date = isset($visit_date) ? $visit_date : date('Y-m-d');
            $qutenza->visit_type = $visit_type;
            $qutenza->application_time = isset($application_time) ? $application_time : 0;
            $qutenza->allodynia = isset($allodynia) ? $allodynia : 0;
            $qutenza->erythema = isset($erythema) ? $erythema : 0;
            $qutenza->pain = isset($pain) ? $pain : 0;
            $qutenza->pruritus = isset($pruritus) ? $pruritus : 0;
            $qutenza->papules = isset($papules) ? $papules : 0;
            $qutenza->edema = isset($edema) ? $edema : 0;
            $qutenza->swelling = isset($swelling) ? $swelling : 0;
            $qutenza->dryness = isset($dryness) ? $dryness : 0;
            $qutenza->nasopharyngitis = isset($nasopharyngitis) ? $nasopharyngitis : 0;
            $qutenza->bronchitis = isset($bronchitis) ? $bronchitis : 0;
            $qutenza->sinusitis = isset($sinusitis) ? $sinusitis : 0;
            $qutenza->nausea = isset($nausea) ? $nausea : 0;
            $qutenza->vomiting = isset($vomiting) ? $vomiting : 0;
            $qutenza->skin_pruritus = isset($skin_pruritus) ? $skin_pruritus : 0;
            $qutenza->hypertension = isset($hypertension) ? $hypertension : 0;
            $qutenza->hypertension_systolic = isset($hypertension_systolic) ? $hypertension_systolic : 0;
            $qutenza->hypertension_diastolic = isset($hypertension_diastolic) ? $hypertension_diastolic : 0;
            // $qutenza->after_ttt_systolic = isset($after_ttt_systolic) ? $after_ttt_systolic : 0;
            // $qutenza->after_ttt_diastolic = isset($after_ttt_diastolic) ? $after_ttt_diastolic : 0;
            // $qutenza->pain_score = isset($pain_score) ? $pain_score : 0;
            $qutenza->org_id = auth()->user()->org_id;
            $qutenza->created_by = auth()->user()->id;
            $qutenza->user_id = auth()->user()->id;
            if ($qutenza->save()) {
                $html = $this->draw_qutenza_table($painFile_id);
                return response()->json(['success' => true, 'qutenza_html' => $html, 'qutenza_id' => $qutenza->id]);
            }
        }
        return response()->json(['success' => false]);

    }

    public function addQutenza_request(Request $request)
    {
        // dd($request->all());

        $followup_id = $request->followup_id;
        $painFile_id = $request->painFile_id;
        $visit_date = $request->visit_date;
        $visit_type = $request->visit_type;

        $qutenza = new QutenzaTreatment();
        $qutenza->pain_file_id = $painFile_id;
        $qutenza->followup_id = isset($followup_id) ? $followup_id : null;
        $qutenza->visit_date = isset($visit_date) ? $visit_date : date('Y-m-d');
        $qutenza->visit_type = $visit_type;
        $qutenza->org_id = auth()->user()->org_id;
        $qutenza->created_by = auth()->user()->id;
        if ($qutenza->save()) {
            $html = $this->draw_qutenza_table($painFile_id);
            return response()->json(['success' => true, 'qutenza_html' => $html, 'qutenza_id' => $qutenza->id]);
        }

        return response()->json(['success' => false]);

    }

    public
    function get_qutenza(Request $request)
    {
        $qutenza_id = $request->id;
        $qutenza = QutenzaTreatment::find($qutenza_id);
        if (isset($qutenza)) {
            $html = $this->draw_qutenza_score_table($qutenza_id);
            return response()->json(['success' => true, 'qutenza_data' => $qutenza, 'qutenza_score_html' =>$html]);
        }
        return response()->json(['success' => false, 'qutenza_data' => '','qutenza_score_html' =>'']);
    }

    public
    function del_qutenza(Request $request)
    {
        $id = $request->id;
        $painFile_id = $request->painFile_id;
        $qutenza = QutenzaTreatment::find($id);
        if (isset($qutenza)) {
            $qutenza->delete();
            //$qutenza->save();
            $html = $this->draw_qutenza_table($painFile_id);
            return response()->json(['success' => true, 'qutenza_html' => $html, 'qutenza_id' => $qutenza->id]);
        }
    }

    //Qutenza score
    public function addQutenzaScore(Request $request)
    {
        $qutenza_id = $request->hdnqutenza_id;
        $painFile_id = $request->painFile_id;
        $visit_date = $request->qutenza_score_date;
        $visit_type = $request->visit_type;
        $followup_id = $request->followup_id;
      //  $qutenza_id = QutenzaTreatment::where('pain_file_id', $painFile_id)->max('id');
        $week = $request->week;
        $score = $request->qutenza_pain_scale;
        if (isset($qutenza_id)) {
            $qutenza_score = new QutenzaScore();
            $qutenza_score->pain_file_id = $painFile_id;
            $qutenza_score->qutenza_id = $qutenza_id;
            $qutenza_score->followup_id = isset($followup_id) ? $followup_id : null;
            $qutenza_score->visit_date = isset($visit_date) ? $visit_date : date('Y-m-d');
            $qutenza_score->visit_type = $visit_type;
            $qutenza_score->week = $week;
            $qutenza_score->score = $score;
            $qutenza_score->org_id = auth()->user()->org_id;
            $qutenza_score->created_by = auth()->user()->id;
            if ($qutenza_score->save()) {
                $html = $this->draw_qutenza_score_table($qutenza_id);
                return response()->json(['success' => true, 'qutenza_score_html' => $html, 'qutenza_id' => $qutenza_id]);
            }
        }
    }

    function del_qutenza_score(Request $request)
    {
        $id = $request->id;
        $painFile_id = $request->painFile_id;
        $qutenza_id = $request->qutenza_id;
        $qutenza = QutenzaScore::find($id);
        if (isset($qutenza)) {
            $qutenza->delete();
            //$qutenza->save();
            $html = $this->draw_qutenza_score_table($qutenza_id);
            return response()->json(['success' => true, 'qutenza_score_html' => $html,]);
        }
    }

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

    public
    function qutenzaData(Request $request)
    {
        $painFile_id = $request->painFile_id;
        $from = $request->from;
        $to = $request->to;
        $user_id = $request->user_id;
        $model = QutenzaTreatment::query();

        $num = 1;
        return datatables()->of($model)
            ->addColumn('num', function () use (&$num) {// user & as reference to store the privies value
                return $num++;
            })
            ->addColumn('visit_type_desc', function ($model) {// as foreach ($users as $user)
                $visit_type = [1 => 'Baseline', 2 => 'Follow up'];
                return $visit_type($model->visit_type);
            })
            ->addColumn('total_hour', function ($model) {// as foreach ($users as $user)

                if (isset($model->leave_time) && isset($model->attend_time))
                    $hr = (new  Carbon($model->leave_time))->diff(new Carbon($model->attend_time))->format('%h:%I');
                else
                    $hr = 0;
                return $hr;
            })
            ->rawColumns(['action', 'total_hour'])
            ->toJson();
    }
}
