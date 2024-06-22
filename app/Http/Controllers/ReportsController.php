<?php

namespace App\Http\Controllers;


use App\BaselineTreatmentVw;
use App\BaslineLastFollowupVw;
use App\DrugList;
use App\FollowupTreatmentsVw;
use App\ItemsTb;
use App\TreatmentVw;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class ReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $this->data['sub_menu'] = 'systemReport-display';
        $this->data['location_title'] = 'System Reports';
        $this->data['location_link'] = 'reports';
        $this->data['title'] = 'Reports';
        $this->data['drug_list'] = ItemsTb::where('isActive', 1)->get();
        //  $this->data['page_title'] = ' System Reports ';
        //       $this->data['accountTypes'] = Lookup::where('lookup_cat_id', 8)->get();//نو الزيارة
        // $this->data['users'] = User::all();
        return view(report_vw() . '.report')->with($this->data);
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

    public function run_rep(Request $request)
    {

        // dd('ffff');
        //    dd($request->all());
        if ($request->report_id != '') {

            $rep = $request->report_id;
            $from = $request->fromdate;
            $to = $request->todate;
            $drug_id = $request->drug_id;
            $concentration = $request->concentration;
            $this->data['fromdate'] = $from;
            $this->data['todate'] = $to;
            $this->data['newPatients'] = '';
            $this->data['followupPatients'] = '';
            $this->data['lastFollowupPatients'] = '';
            $this->data['absentPatients'] = '';
            if ($rep == 1) {
                //    $result = FollowupPatient::with('followTreatments')->whereBetween('follow_up_date', [$from, $to])->get();
                /** $result = DB::table('followup_patients')
                 * ->join('followup_treatments', 'followup_treatments.followup_id', '=', 'followup_patients.id')
                 * ->join('pain_files', 'pain_files.id', '=', 'followup_patients.pain_file_id')
                 * ->join('patients', 'patients.id', '=', 'pain_files.patient_id')
                 * ->join('drug_lists', 'drug_lists.id', '=', 'followup_treatments.drug_id')
                 * ->select('patients.national_id', 'patients.name_a as patient_name', 'followup_patients.follow_up_date as date',
                 * 'drug_lists.name as drug', 'followup_treatments.dosage as dosage', 'followup_treatments.drug_specify as drug_specify')
                 * ->whereNull('patients.deleted_at')
                 * ->whereNull('pain_files.deleted_at')
                 * ->whereNull('followup_patients.deleted_at')
                 * ->whereNull('followup_treatments.deleted_at');
                 * //    dd($result);
                 */

                $result = FollowupTreatmentsVw::query();

                if ($drug_id != 0) {
                    $result = $result->where('drug_id', '=', $drug_id);
                    if ($concentration != '')
                        $result = $result->where('concentration', '=', $concentration);
                }
                $result = $result->whereBetween('date', [$from, $to])
                    ->get();

                foreach ($result as $row) {
                    $data[] = [
                        'national_id' => $row->national_id,
                        'patient_name' => $row->patient_name,
                        'date' => $row->date,
                        'drug_name' => $row->drug_name,
                        'concentration' => $row->concentration,
                        'dosage' => $row->dosage,
                        'frequency' => $row->frequency,
                        'duration' => $row->duration,
                        'quantity' => $row->quantity,
                        'specify' => $row->specify,
                        'drug_specify' => $row->drug_specify,
                    ];
                }
                $html = ''; //  dd($data);
                $i = 0;
                $total = 0;
                if (isset($data))
                    foreach ($data as $row) {

                        $html .= '<tr><td>' . ++$i . '</td>';
                        $html .= '<td>' . (isset($row['national_id']) ? $row['national_id'] : '') . '</td>';
                        $html .= '<td>' . (isset($row['patient_name']) ? $row['patient_name'] : '') . '</td>';
                        $html .= '<td>' . (isset($row['date']) ? $row['date'] : '') . '</td>';
                        $html .= '<td>' . (isset($row['drug_name']) ? $row['drug_name'] : '') . '</td>';
                        $html .= '<td>' . (isset($row['concentration']) ? $row['concentration'] : '') . '</td>';
                        $html .= '<td>' . (isset($row['dosage']) ? $row['dosage'] : '') . '</td>';
                        $html .= '<td>' . (isset($row['frequency']) ? $row['frequency'] : '') . '</td>';
                        $html .= '<td>' . (isset($row['duration']) ? $row['duration'] : '') . '</td>';
                        $html .= '<td>' . (isset($row['quantity']) ? $row['quantity'] : '') . '</td>';
                        $html .= '<td>' . (isset($row['drug_specify']) ? $row['drug_specify'] : '') . '</td></tr>';
                        $total += $row['quantity'];

                    }
                // dd($html);
                if ($drug_id != 0) {
                    $html .= '<tr><td colspan="8"></td><td>Total</td>';
                    $html .= '<td>' . $total . '</td><td></td></tr>';
                }
                $this->data['model'] = $html;

                return view(report_vw() . '.rep1', $this->data);
                // dd($this->data);
                //  $pdf = \niklasravnsborg\LaravelPdf\Facades\Pdf::loadView(report_vw() . '.rep1', $this->data);
                //  return $pdf->stream('document.pdf');

            } else if ($rep == 2) {
                $result = BaselineTreatmentVw::query();
                /* $result = DB::table('pain_files')
                     ->join('baseline_treatment_choices', 'baseline_treatment_choices.pain_file_id', '=', 'pain_files.id')
                     ->join('patients', 'patients.id', '=', 'pain_files.patient_id')
                     ->join('drug_lists', 'drug_lists.id', '=', 'baseline_treatment_choices.drug_id')
                     ->selec-t('patients.national_id', 'patients.name_a as patient_name', 'baseline_treatment_choices.created_at as date',
                         'drug_lists.name as drug', 'baseline_treatment_choices.dosage as dosage', 'baseline_treatment_choices.drug_specify as specify')
                     ->whereNull('patients.deleted_at')
                     ->whereNull('pain_files.deleted_at')
                     ->whereNull('baseline_treatment_choices.deleted_at');*/
                if ($drug_id != 0) {
                    $result = $result->where('drug_id', '=', $drug_id);
                    if ($concentration != '')
                        $result = $result->where('concentration', '=', $concentration);
                }
                $result = $result->whereBetween('date', [$from, $to])//baseline_treatment_choices.created_at
                ->get();
                //dd($result);
                foreach ($result as $row) {
                    $data[] = [
                        'national_id' => $row->national_id,
                        'patient_name' => $row->patient_name,
                        'date' => $row->date,
                        'drug_name' => $row->drug_name,
                        'concentration' => $row->concentration,
                        'dosage' => $row->dosage,
                        'frequency' => $row->frequency,
                        'duration' => $row->duration,
                        'quantity' => $row->quantity,
                        'specify' => $row->specify,
                    ];
                }
                $html = ''; //  dd($data);
                $i = 0;
                $total = 0;
                if (isset($data))
                    foreach ($data as $row) {

                        $html .= '<tr><td>' . ++$i . '</td>';
                        $html .= '<td>' . (isset($row['national_id']) ? $row['national_id'] : '') . '</td>';
                        $html .= '<td>' . (isset($row['patient_name']) ? $row['patient_name'] : '') . '</td>';
                        $html .= '<td>' . (isset($row['date']) ? Carbon::parse($row['date'])->format('d/m/Y') : '') . '</td>';
                        $html .= '<td>' . (isset($row['drug_name']) ? $row['drug_name'] : '') . '</td>';
                        $html .= '<td>' . (isset($row['concentration']) ? $row['concentration'] : '') . '</td>';
                        $html .= '<td>' . (isset($row['dosage']) ? $row['dosage'] : '') . '</td>';
                        $html .= '<td>' . (isset($row['frequency']) ? $row['frequency'] : '') . '</td>';
                        $html .= '<td>' . (isset($row['duration']) ? $row['duration'] : '') . '</td>';
                        $html .= '<td>' . (isset($row['quantity']) ? $row['quantity'] : '') . '</td>';
                        $html .= '<td>' . (isset($row['specify']) ? $row['specify'] : '') . '</td></tr>';
                        $total += $row['quantity'];
                    }
                if ($drug_id != 0) {
                    $html .= '<tr><td colspan="8"></td><td>Total</td>';
                    $html .= '<td>' . $total . '</td><td></td></tr>';
                }
                $this->data['model'] = $html;
                $this->data['model'] = $html;

                // return view(report_vw() . '.rep1', $this->data);
                //dd($this->data);
                $pdf = \niklasravnsborg\LaravelPdf\Facades\Pdf::loadView(report_vw() . '.rep2', $this->data);
                return $pdf->stream('document.pdf');

            }
            else if ($rep == 3)
            {
                $newPatients = DB::table('pain_files')
                    ->join('patients', 'patients.id', '=', 'pain_files.patient_id')
                    ->select('patients.national_id', 'patients.name_a as patient_name', 'patients.name as patient_name_e', 'start_date as date')
                    ->whereNull('patients.deleted_at')
                    ->whereNull('pain_files.deleted_at')
                    ->whereBetween('start_date', [$from, $to])
                    ->get();
                $followupPatients = DB::table('pain_files')
                    ->join('patients', 'patients.id', '=', 'pain_files.patient_id')
                    ->select('patients.national_id', 'patients.name_a as patient_name', 'patients.name as patient_name_e', 'last_followup_date as date')
                    ->whereNull('patients.deleted_at')
                    ->whereNull('pain_files.deleted_at')
                    ->whereBetween('last_followup_date', [$from, $to])
                    ->get();
                $lastFollowupPatients = DB::table('pain_files')
                    ->join('patients', 'patients.id', '=', 'pain_files.patient_id')
                    ->select('patients.national_id', 'patients.name_a as patient_name', 'patients.name as patient_name_e', 'close_date as date')
                    ->whereNull('patients.deleted_at')
                    ->whereNull('pain_files.deleted_at')
                    ->whereBetween('close_date', [$from, $to])
                    ->get();

                $absentPatients = DB::table('pain_files')
                    ->join('patients', 'patients.id', '=', 'pain_files.patient_id')
                    ->join('appointments', 'appointments.pain_file_id', '=', 'pain_files.id')
                    ->select('patients.national_id', 'patients.name_a as patient_name', 'patients.name as patient_name_e', 'appointments.start_date as date')
                    ->whereNull('patients.deleted_at')
                    ->whereNull('pain_files.deleted_at')
                    ->whereBetween('appointments.start_date', [$from, $to])
                    ->whereNull('appointments.attend_date')
                    ->get();
                foreach ($newPatients as $row) {
                    $newdata[] = [
                        'national_id' => $row->national_id,
                        'patient_name' => $row->patient_name,
                        'patient_name_e' => $row->patient_name_e,
                        'date' => $row->date
                    ];
                }
                foreach ($followupPatients as $row) {
                    $followupdata[] = [
                        'national_id' => $row->national_id,
                        'patient_name' => $row->patient_name,
                        'patient_name_e' => $row->patient_name_e,
                        'date' => $row->date
                    ];
                }
                foreach ($lastFollowupPatients as $row) {
                    $lastFollowupdata[] = [
                        'national_id' => $row->national_id,
                        'patient_name' => $row->patient_name,
                        'patient_name_e' => $row->patient_name_e,
                        'date' => $row->date
                    ];
                }
                foreach ($absentPatients as $row) {
                    $absentdata[] = [
                        'national_id' => $row->national_id,
                        'patient_name' => $row->patient_name,
                        'patient_name_e' => $row->patient_name_e,
                        'date' => $row->date
                    ];
                }
                $html_new_patients = '';
                $html_followup_Patients = '';
                $html_lastFollowup_Patients = '';
                $html_absent_Patients = '';
                $i = 0;
                $total = 0;
                if (isset($newdata)) {
                    foreach ($newdata as $row) {
                        $html_new_patients .= '<tr><td style="text-align:center">' . ++$i . '</td>';
                        $html_new_patients .= '<td style="text-align:center">' . (isset($row['patient_name']) ? $row['patient_name'] : $row['patient_name_e']) . '</td>';
                        $html_new_patients .= '<td style="text-align:center">' . (isset($row['date']) ? $row['date'] : '') . '</td></tr>';
                    }

                    $html_new_patients .= '<tr><td style="text-align:center">' . $i . '</td>';
                    $html_new_patients .= '<td colspan="2"><b>Total</b></td></tr>';
                }
                $i = 0;
                if (isset($followupdata)) {
                    foreach ($followupdata as $row) {
                        $html_followup_Patients .= '<tr><td  style="text-align:center">' . ++$i . '</td>';
                        $html_followup_Patients .= '<td style="text-align:center">' . (isset($row['patient_name']) ? $row['patient_name'] : $row['patient_name_e']) . '</td>';
                        $html_followup_Patients .= '<td style="text-align:center">' . (isset($row['date']) ? $row['date'] : '') . '</td></tr>';

                    }
                    $html_followup_Patients .= '<tr><td style="text-align:center">' . $i . '</td>';
                    $html_followup_Patients .= '<td colspan="2"><b>Total</b></td></tr>';
                }
                $i = 0;
                if (isset($lastFollowupdata)) {
                    foreach ($lastFollowupdata as $row) {
                        $html_lastFollowup_Patients .= '<tr><td style="text-align:center">' . ++$i . '</td>';
                        $html_lastFollowup_Patients .= '<td style="text-align:center">' . (isset($row['patient_name']) ? $row['patient_name'] : $row['patient_name_e']) . '</td>';
                        $html_lastFollowup_Patients .= '<td style="text-align:center">' . (isset($row['date']) ? $row['date'] : '') . '</td></tr>';

                    }
                    $html_lastFollowup_Patients .= '<tr><td style="text-align:center">' . $i . '</td>';
                    $html_lastFollowup_Patients .= '<td colspan="2"><b>Total</b></td></tr>';
                }
                $i = 0;
                if (isset($absentdata)) {
                    foreach ($absentdata as $row) {
                        $html_absent_Patients .= '<tr><td style="text-align:center">' . ++$i . '</td>';
                        $html_absent_Patients .= '<td style="text-align:center">' . (isset($row['patient_name']) ? $row['patient_name'] : $row['patient_name_e']) . '</td>';
                        $html_absent_Patients .= '<td style="text-align:center">' . (isset($row['date']) ? $row['date'] : '') . '</td></tr>';

                    }
                    $html_absent_Patients .= '<tr><td style="text-align:center">' . $i . '</td>';
                    $html_absent_Patients .= '<td colspan="2"><b>Total</b></td></tr>';
                }
                $this->data['newPatients'] = $html_new_patients;
                $this->data['followupPatients'] = $html_followup_Patients;
                $this->data['lastFollowupPatients'] = $html_lastFollowup_Patients;
                $this->data['absentPatients'] = $html_absent_Patients;

                // return view(report_vw() . '.rep3', $this->data);
                //dd($this->data);
                $pdf = \niklasravnsborg\LaravelPdf\Facades\Pdf::loadView(report_vw() . '.rep3', $this->data);
                return $pdf->stream('document.pdf');

            }
        } else
            return Redirect::back()->withErrors(['Please Select Report']);

    }

    public function view_report(Request $request)
    {
        $rep = $request->report_id;
        $fromdate = $request->fromdate;
        $todate = $request->todate;
        $drug_id = $request->drug_id;
        //  $national_id = $request->national_id;
        $order_status = $request->order_status;
        if ($rep == 1)
            $model = FollowupTreatmentsVw::query();
        else if ($rep == 2)
            $model = BaselineTreatmentVw::query();
        else if($rep==6)
            $model=BaslineLastFollowupVw::query();
        else
            $model = TreatmentVw::query();

        if($rep==6){
            if ($fromdate != '' && $todate != '') {
                $model = $model->whereDate('last_followup_create_date', '>=', $fromdate);
                $model = $model->whereDate('last_followup_create_date', '<=', $todate);
            }
        }
        else {
            if ($drug_id != '' && $drug_id != 0)
                $model = $model->where('drug_id', $drug_id);
            if ($order_status != '')
                $model = $model->where('order_status', $order_status);
            /*if ($batch_id != '' && $batch_id != 0)
                $model = $model->where('batch_id', $batch_id);*/

            if ($fromdate != '' && $todate != '') {
                $model = $model->whereDate('date', '>=', $fromdate);
                $model = $model->whereDate('date', '<=', $todate);
            }
        }
        /* if ($national_id != '' && $national_id != 0)
             $model = $model->where('national_id', $national_id);*/
        $num = 1;
        return datatables()->of($model)
            ->addColumn('num', function () use (&$num) {//
                return $num++;
            })
            ->toJson();
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
