<?php

namespace App\Http\Controllers;

use App\BaselineDiagnostics;
use App\BaselineDoctorConsultation;
use App\BaselineNeurologicalExam;
use App\BaselineTreatmentChoice;
use App\BaslineLastFollowupVw;
use App\FollowupDoctor;
use App\FollowupLast;
use App\FollowupPatient;
use App\FollowupTreatment;
use App\OverallBaselineVw;
use App\PainFile;

use App\PatientPainBaselineVw;
use App\TempBaselineInjuryMechanism;
use Illuminate\Http\Request;


use App\User;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

        $this->middleware('auth');


    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //  $this->create_v_baseline_injury_mechanism();
        $this->data['sub_menu'] = 'home';
        $this->data['totalPatients'] = 1;// $this->getTotalPatients();
        $this->data['totalfollowup'] = 1;// $this->getTotalFollowup();
        $this->data['totalbaseline'] = 1;//$this->getTotalbaseline();
        $this->data['totalOpen'] = 1;//$this->getTotalPatients(17);
        $this->data['totalClose'] = 1;// $this->getTotalPatients(18);
        $this->data['employee_list'] = User::all();

        return view(admin_vw() . '.home')->with($this->data);


    }

    public function get_general_statistics(Request $request)
    {
        $employee_id = $request->employee_id;
        $start_date = $request->startDate;
        $end_date = $request->endDate;

        $totalPatients = $this->getTotalPatients_by_employee_all($employee_id, $start_date, $end_date);
        $totalfollowup = $this->getTotalFollowup_by_userId($employee_id,$start_date,$end_date);
        $totalbaseline = $this->getTotalbaseline_by_userId($employee_id,$start_date,$end_date);
        $totalOpen = $this->getTotalPatients_by_employee($employee_id, 17,$start_date,$end_date);
        $totalClose = $this->getTotalPatients_by_employee($employee_id, 18,$start_date,$end_date);
        return response()->json(['success' => true, 'totalPatients' => $totalPatients,
            'totalfollowup' => $totalfollowup, 'totalbaseline' => $totalbaseline, 'totalOpen' => $totalOpen, 'totalClose' => $totalClose]);
    }

    public function getTotalPatients_by_employee($employee_id, $status,$start_date,$end_date)
    {
        if ($employee_id <> '' && $employee_id != null) {
            $PainFilesCount = PatientPainBaselineVw::where('doctor_id', $employee_id)->where('status', $status)->get();
            //  $closePainFilesCount = PatientPainBaselineVw::where('doctor_id', $employee_id)->where('status', 18)->count();
        } else {
            $PainFilesCount = PatientPainBaselineVw::where('status', $status)->get();
            //   $closePainFilesCount = PatientPainBaselineVw::where('status', 18)->count();
        }
        if ($start_date <> '' && $start_date != null) {
            $PainFilesCount = $PainFilesCount->where('visit_date', '>=', $start_date)
                ->where('visit_date', '<=', $end_date);
        }
        $PainFilesCount = $PainFilesCount->count();
        return $PainFilesCount;

    }

    public function getTotalPatients_by_employee_all($employee_id = null, $start_date, $end_date)
    {
        if ($employee_id <> '' && $employee_id != null) {
            $PainFilesCount = PatientPainBaselineVw::where('doctor_id', $employee_id);
            //  $closePainFilesCount = PatientPainBaselineVw::where('doctor_id', $employee_id)->where('status', 18)->count();
        } else {
            $PainFilesCount = PatientPainBaselineVw::query();
            //   $closePainFilesCount = PatientPainBaselineVw::where('status', 18)->count();
        }
       //echo '$PainFilesCount';
       // dd($PainFilesCount);
        if ($start_date <> '' && $start_date != null) {
            $PainFilesCount = $PainFilesCount->where('start_date', '>=', $start_date)
                ->where('start_date', '<=', $end_date);
        }

        $PainFilesCount = $PainFilesCount->count();
        return $PainFilesCount;

    }

    public function getTotalPatients($status = null)
    {
        if ($status == null) {
            $painFiles = PainFile::all();
            return $painFiles->count();
        } else {
            $painFiles = PainFile::where('status', $status);
            return $painFiles->count();
        }
    }

    public function getTotalPatients_by_userId($status = null)
    {
        if ($status == null) {
            $painFiles = PainFile::all();
            return $painFiles->count();
        } else {
            $painFiles = PainFile::where('status', $status);
            return $painFiles->count();
        }
    }

    public function getTotalPatientsByDistricts(Request $request)
    {
        date_default_timezone_set('Asia/Gaza');
        // dd($request->all());
        $start_date = $request->startDate;
        $end_date = $request->endDate;
        $employee_id = $request->employee_id;
        $color = array("1" => "#04D215", "2" => "#36D7B7", "3" => "#0D8ECF", "4" => "#0D52D1", "5" => "#2A0CD0");
        $patients = DB::table('patients')
            ->join('lookup', 'patients.district', '=', 'lookup.id')
            ->join('pain_files', 'patients.id', '=', 'pain_files.patient_id')
            ->join('baseline_doctor_consultations', 'pain_files.id', '=', 'baseline_doctor_consultations.pain_file_id')
            ->select(DB::raw('count(*) as count,district,lookup.lookup_details as district_name'))
            ->whereDate('patients.created_at', '>=', $start_date)
            ->whereDate('patients.created_at', '<=', $end_date);
        if ($employee_id != null && $employee_id != '')
            $patients = $patients->where('baseline_doctor_consultations.created_by', $employee_id);

        $patients = $patients->groupBy('district')->get();

        //dd($patients);
        $overAll = array();
        $output = array();
        $i = 1;
        foreach ($patients as $row) {

            //  $output[] = $row->overall_status;
            $output['DISTRICT_NAME'] = $row->district_name;
            $output['COUNT_PATIENTS'] = $row->count;
            $output['COLOR'] = $color[$i++];
            array_push($overAll, $output);
        }
        //  dd($overAll);
        return response()->json(['success' => true, 'chartdata' => $overAll]);
    }

    public function getTotalPatientsByGender(Request $request)
    {
        date_default_timezone_set('Asia/Gaza');
        // dd($request->all());
        $start_date = $request->startDate;
        $end_date = $request->endDate;
        $employee_id = $request->employee_id;
        $color = array("1" => "#0D52D1", "2" => "#2A0CD0");
        $patients = DB::table('patients')
            ->join('pain_files', 'patients.id', '=', 'pain_files.patient_id')
            ->join('baseline_doctor_consultations', 'pain_files.id', '=', 'baseline_doctor_consultations.pain_file_id')
            ->select(DB::raw('count(*) as count,gender'))
            ->whereDate('patients.created_at', '>=', $start_date)
            ->whereDate('patients.created_at', '<=', $end_date);
        /*  ->groupBy('gender')
          ->get();*/
        if ($employee_id != null && $employee_id != '')
            $patients = $patients->where('baseline_doctor_consultations.created_by', $employee_id);

        $patients = $patients->groupBy('gender')->get();
        // dd($patients);
        $overAll = array();
        $output = array();
        $i = 1;
        foreach ($patients as $row) {
//echo $row->gender;exit;
            //  $output[] = $row->overall_status;
            $gender_name = [1 => 'Male', 2 => 'Female'];
            $output['GENDER_NAME'] = $gender_name[$row->gender];
            $output['COUNT_PATIENTS'] = $row->count;
            $output['COLOR'] = $color[$i++];
            array_push($overAll, $output);
        }
        //  dd($overAll);
        return response()->json(['success' => true, 'chartdata' => $overAll]);
    }

    public function getTotalPatientsByAge(Request $request)
    {
        date_default_timezone_set('Asia/Gaza');
        // dd($request->all());
        $start_date = $request->startDate;
        $end_date = $request->endDate;
        $employee_id = $request->employee_id;
        $color = array("1" => "#FF0F00", "2" => "#FF6600", "3" => "#FF9E01", "4" => "#FCD202", "5" => "#F8FF01", "6" => "#B0DE09", "7" => "#04D215",
            "8" => "#36D7B7", "9" => "#0D8ECF", "10" => "#0D52D1", "11" => "#2A0CD0", "12" => "#8A0CCF", "13" => "#D85F7D", "15" => "#E84860",
            "16" => "#fbcebd");
        $sql = "SELECT
                        CASE
                            WHEN age < 20 THEN '(Under 20)'
                            WHEN age BETWEEN 20 and 29 THEN '(20 - 29)'
                            WHEN age BETWEEN 30 and 39 THEN '(30 - 39)'
                            WHEN age BETWEEN 40 and 49 THEN '(40 - 49)'
                            WHEN age BETWEEN 50 and 59 THEN '(50 - 59)'
                            WHEN age BETWEEN 60 and 69 THEN '(60 - 69)'
                            WHEN age BETWEEN 70 and 79 THEN '(70 - 79)'
                            WHEN age >= 80 THEN '(Over 80)'
                            WHEN age IS NULL THEN 'Not Filled In (NULL)'
                        END as AGE_RANGE,
                        COUNT(*) AS COUNT_PATIENTS,
                         CASE
                            WHEN age < 20 THEN 1
                            WHEN age BETWEEN 20 and 29 THEN 2
                            WHEN age BETWEEN 30 and 39 THEN 3
                            WHEN age BETWEEN 40 and 49 THEN 4
                            WHEN age BETWEEN 50 and 59 THEN 5
                            WHEN age BETWEEN 60 and 69 THEN 6
                            WHEN age BETWEEN 70 and 79 THEN 7
                            WHEN age >= 80 THEN 8
                            WHEN age IS NULL THEN 9
                        END as AGE_ORDER
                        
                        FROM (SELECT TIMESTAMPDIFF(YEAR, dob,start_date ) AS age FROM pain_patient_vw,baseline_doctor_consultations bdc where bdc.pain_file_id=pain_patient_vw.id";


        if ($employee_id != null && $employee_id != '')
            $sql = $sql . " and bdc.created_by = " . $employee_id;
        $sql = $sql . ") as patient 
                     GROUP BY AGE_RANGE
                     ORDER BY AGE_ORDER";
        $results = DB::select(DB::raw($sql));
        //  dd($results);
        // dd($patients);
        $overAll = array();
        $output = array();
        $i = 1;
        foreach ($results as $row) {
//echo $row->gender;exit;
            //  $output[] = $row->overall_status;
            $gender_name = [1 => 'Male', 2 => 'Female'];
            $output['AGE_RANGE'] = $row->AGE_RANGE;
            $output['COUNT_PATIENTS'] = $row->COUNT_PATIENTS;
            $output['COLOR'] = $color[$i++];
            array_push($overAll, $output);
        }
        //  dd($overAll);
        return response()->json(['success' => true, 'chartdata' => $overAll]);
    }

    public function getTotalPatientsPhysicalTreatment(Request $request)
    {
        date_default_timezone_set('Asia/Gaza');
        // dd($request->all());
        $start_date = $request->startDate;
        $end_date = $request->endDate;
        $employee_id = $request->employee_id;
        $color = array("1" => "#FF0F00", "2" => "#FF6600", "3" => "#FF9E01", "4" => "#FCD202", "5" => "#F8FF01", "6" => "#B0DE09", "7" => "#04D215",
            "8" => "#36D7B7", "9" => "#0D8ECF", "10" => "#0D52D1", "11" => "#2A0CD0", "12" => "#8A0CCF", "13" => "#D85F7D", "15" => "#E84860",
            "16" => "#fbcebd");

        $patients = DB::table('patients_pain_baseline_vw')
            ->join('lookup', 'patients_pain_baseline_vw.physical_treatment', '=', 'lookup.id')
            ->select(DB::raw('count(*) as count,physical_treatment,lookup.lookup_details as physical_treatment_name'))
            ->whereDate('baseline_date', '>=', $start_date)
            ->whereDate('baseline_date', '<=', $end_date);
        /*->groupBy('physical_treatment')
        ->get();*/

        if ($employee_id != null && $employee_id != '')
            $patients = $patients->where('doctor_id', $employee_id);

        $patients = $patients->groupBy('physical_treatment')->get();
        // dd($patients);
        $overAll = array();
        $output = array();
        $i = 1;
        foreach ($patients as $row) {
//echo $row->gender;exit;
            //  $output[] = $row->overall_status;

            $output['PHYSICAL_TREATMENT_NAME'] = $row->physical_treatment_name;
            $output['PHYSICAL_TREATMENT'] = $row->physical_treatment;
            $output['COUNT_PATIENTS'] = $row->count;
            $output['COLOR'] = $color[$i++];
            array_push($overAll, $output);
        }
        //  dd($overAll);
        return response()->json(['success' => true, 'chartdata' => $overAll]);
    }

    public function getTotalPatientsPharmaTreatment(Request $request)
    {
        date_default_timezone_set('Asia/Gaza');
        // dd($request->all());
        $start_date = $request->startDate;
        $end_date = $request->endDate;
        $item_id = $request->item_id;
        $color = array("0" => "#FF0F00", "1" => "#FCD202", "2" => "#FF0F00", "3" => "#FCD202", "4" => "#F8FF01");

        $patients = DB::table('patients_pain_baseline_vw')
            ->select(DB::raw('count(*) as count,pharmacological_treatment'))
            ->where('physical_treatment', $item_id)
            ->whereDate('baseline_date', '>=', $start_date)
            ->whereDate('baseline_date', '<=', $end_date)
            ->groupBy('pharmacological_treatment')
            ->get();

        // dd($patients);
        $overAll = array();
        $output = array();
        $i = 1;
        foreach ($patients as $row) {
//echo $row->gender;exit;
            //  $output[] = $row->overall_status;
            $pharm_name = [1 => 'Yes', 0 => 'No'];
            $output['PHARM_TREATMENT_NAME'] = $pharm_name[$row->pharmacological_treatment];
            $output['COUNT_PATIENTS'] = $row->count;
            $output['COLOR'] = $color[$row->pharmacological_treatment];
            array_push($overAll, $output);
        }
        //  dd($overAll);
        return response()->json(['success' => true, 'chartdata' => $overAll]);
    }

    public function getTotalPainFilesByInjuryMech(Request $request)
    {
        date_default_timezone_set('Asia/Gaza');
        // dd($request->all());
        $start_date = $request->startDate;
        $end_date = $request->endDate;
        $employee_id = $request->employee_id;
        $color = array("1" => "#FF0F00", "2" => "#FF6600", "3" => "#FF9E01", "4" => "#FCD202", "5" => "#F8FF01", "6" => "#B0DE09", "7" => "#04D215",
            "8" => "#36D7B7", "9" => "#0D8ECF", "10" => "#0D52D1", "11" => "#2A0CD0", "12" => "#8A0CCF", "13" => "#D85F7D", "15" => "#E84860",
            "16" => "#fbcebd");
        $patients = DB::table('temp_baseline_injury_mechanism')
            ->select(DB::raw('count(*) as COUNT_PATIENT,injury_mechanism'))
            ->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date);
        /*->groupBy('injury_mechanism')
        ->orderBy('COUNT_PATIENT', 'desc')
        ->get();*/
        if ($employee_id != null && $employee_id != '')
            $patients = $patients->where('created_by', $employee_id);

        $patients = $patients->groupBy('injury_mechanism')->orderBy('COUNT_PATIENT', 'desc')->get();
        /* $sql='SELECT COUNT(*) AS COUNT_PATIENT,injury_mechanism
                 FROM (
                 SELECT im_pain_file_id,injury_mechanism1 AS injury_mechanism
                 FROM v_baseline_injury_mechanism
                 UNION
                 SELECT im_pain_file_id,injury_mechanism2 AS injury_mechanism
                 FROM v_baseline_injury_mechanism
                 UNION
                 SELECT im_pain_file_id,injury_mechanism3 AS injury_mechanism
                 FROM v_baseline_injury_mechanism) injury_mechanism
                 WHERE injury_mechanism>0
                 GROUP BY injury_mechanism
                 ORDER BY COUNT_PATIENT DESC
                 ';*/
        // $results = DB::select(DB::raw($sql));
        //  dd($results);
        $overAll = array();
        $output = array();
        $i = 1;
        foreach ($patients as $row) {
            if ($row->injury_mechanism != 0) {
                $output['INJURY_MECHANISM'] = get_lookup_desc($row->injury_mechanism);
                $output['INJURY_MECHANISM_CD'] = $row->injury_mechanism;

                $output['COUNT_PATIENTS'] = $row->COUNT_PATIENT;
                $output['COLOR'] = $color[$i++];
                array_push($overAll, $output);
            }
        }
        //  dd($overAll);
        return response()->json(['success' => true, 'chartdata' => $overAll]);
    }

    public
    function create_v_baseline_injury_mechanism()
    {
        $temp = array();
        $temp_array_length = array();
        $temp_max_length = 0;
        $sql = '';
        $baseline_Doctor = BaselineDoctorConsultation::select('injury_mechanism', 'pain_file_id', 'created_by', 'created_at')->orderBy('id')->get();
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
                $temp_baseline_injury_mechanism->created_by = $row->created_by;
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

    public function getTotalFollowup()
    {
        $followups = FollowupPatient::all();
        return $followups->count();
    }

    public function getTotalFollowup_by_userId($employee_id,$start_date,$end_date)
    {
        if ($employee_id != null && $employee_id != '')
            $followups = FollowupDoctor::where('created_by', $employee_id)->get();
        else
            $followups = FollowupDoctor::query();
        if ($start_date <> '' && $start_date != null) {
            $followups = $followups->where('follow_up_date', '>=', $start_date)
                ->where('follow_up_date', '<=', $end_date);
        }
        $followups = $followups->count();
        return $followups;
    }

    public function getTotalbaseline()
    {
        $baseline = BaselineDoctorConsultation::all();
        return $baseline->count();
    }

    public function getTotalbaseline_by_userId($employee_id,$start_date,$end_date)
    {
        if ($employee_id != null && $employee_id != '')
            $baseline = BaselineDoctorConsultation::where('created_by', $employee_id)->get();
        else
            $baseline = BaselineDoctorConsultation::query();

        if ($start_date <> '' && $start_date != null) {
            $baseline = $baseline->where('visit_date', '>=', $start_date)
                ->where('visit_date', '<=', $end_date);
        }
        $baseline = $baseline->count();
        return $baseline;
    }

    public function getOverall(Request $request)
    {
        date_default_timezone_set('Asia/Gaza');
        // dd($request->all());
        $start_date = $request->startDate;
        $end_date = $request->endDate;
        $employee_id = $request->employee_id;
        $sources = OverallBaselineVw::select(DB::raw('overall_status,count(*) as count'))
            ->where('overall_status', '!=', null)
            ->whereDate('follow_up_date', '>=', $start_date)
            ->whereDate('follow_up_date', '<=', $end_date);
        //->groupBy('overall_status');
        //->orderBy('count', 'DESC')
        //->get();
        //  ->toSql();
        if ($employee_id != null && $employee_id != '')
            $sources = $sources->where('doctor_id', $employee_id);

        $sources = $sources->groupBy('overall_status')->orderBy('count', 'DESC')->get();

        $overAll = array();
        $output = array();
        foreach ($sources as $row) {

            //  $output[] = $row->overall_status;
            $output['OVERALL_STATUS'] = get_lookup_desc($row->overall_status);
            $output['COUNT_PATIENTS'] = $row->count;
            array_push($overAll, $output);
        }
        //  dd($overAll);
        return response()->json(['success' => true, 'chartdata' => $overAll]);
    }

    public function getFollowupMonthly(Request $request)
    {

        $start_date = $request->startDate;
        $end_date = $request->endDate;
        $employee_id = $request->employee_id;
        $color = array("1" => "#FF0F00", "2" => "#FF6600", "3" => "#FF9E01", "4" => "#FCD202", "5" => "#F8FF01", "6" => "#B0DE09", "7" => "#04D215",
            "8" => "#36D7B7", "9" => "#0D8ECF", "10" => "#0D52D1", "11" => "#2A0CD0", "12" => "#8A0CCF", "13" => "#D85F7D", "15" => "#E84860",
            "16" => "#fbcebd");
        $followupSources = FollowupDoctor::select(DB::raw('count(*) as count,DATE_FORMAT(follow_up_date, "%Y/%m") as duration'))
            ->whereDate('follow_up_date', '>=', $start_date)
            ->whereDate('follow_up_date', '<=', $end_date);
        /* ->groupBy('duration')
         ->orderBy('duration', 'DESC')
         ->take(12)
         ->get();*/
        if ($employee_id != null && $employee_id != '')
            $followupSources = $followupSources->where('created_by', $employee_id);

        $followupSources = $followupSources->groupBy('duration')->orderBy('duration', 'DESC')->take(12)->get();
        //  dd($baselineSources);
        $overAll = array();
        $output = array();
        $i = 1;
        foreach ($followupSources as $row) {

            //  $output[] = $row->overall_status;
            $output['DURATION'] = $row->duration;
            $output['COUNT_PATIENTS'] = $row->count;
            $output['COLOR'] = $color[$i++];
            array_push($overAll, $output);
        }
        //  dd($overAll);
        return response()->json(['success' => true, 'chartdata' => $overAll]);
    }

    public function getClosedFileMonthly(Request $request)
    {
        date_default_timezone_set('Asia/Gaza');
        // dd($request->all());
        $start_date = $request->startDate;
        $end_date = $request->endDate;
        $employee_id = $request->employee_id;
        $color = array("1" => "#FF9E01", "2" => "#FCD202", "3" => "#F8FF01", "4" => "#B0DE09", "5" => "#04D215",
            "6" => "#36D7B7", "7" => "#0D8ECF", "8" => "#0D52D1", "9" => "#2A0CD0", "10" => "#8A0CCF", "11" => "#D85F7D", "12" => "#E84860");
        /*  $followupSources = FollowupLast::select(DB::raw('count(*) as count,DATE_FORMAT(follow_up_date, "%Y/%m") as duration'))
              ->whereDate('follow_up_date', '>=', $start_date)
              ->whereDate('follow_up_date', '<=', $end_date);
    */
        $followupSources = DB::table('followup_last')
            ->join('baseline_doctor_consultations', 'followup_last.id', '=', 'baseline_doctor_consultations.pain_file_id')
            ->select(DB::raw('count(*) as count,DATE_FORMAT(follow_up_date, "%Y/%m") as duration'))
            ->whereDate('follow_up_date', '>=', $start_date)
            ->whereDate('follow_up_date', '<=', $end_date);


        if ($employee_id != null && $employee_id != '')
            $followupSources = $followupSources->where('baseline_doctor_consultations.created_by', $employee_id);

        $followupSources = $followupSources->groupBy('duration')->orderBy('duration', 'DESC')->take(12)->get();
        //  dd($baselineSources);
        $overAll = array();
        $output = array();
        $i = 1;
        foreach ($followupSources as $row) {

            //  $output[] = $row->overall_status;
            $output['DURATION'] = $row->duration;
            $output['COUNT_PATIENTS'] = $row->count;
            $output['COLOR'] = $color[$i++];
            array_push($overAll, $output);
        }
        //  dd($overAll);
        return response()->json(['success' => true, 'chartdata' => $overAll]);
    }

    public function getBaselineMonthly(Request $request)
    {
        date_default_timezone_set('Asia/Gaza');
        // dd($request->all());
        $start_date = $request->startDate;
        $end_date = $request->endDate;
        $employee_id = $request->employee_id;
        $color = array("1" => "#FCD202", "2" => "#F8FF01", "3" => "#B0DE09", "4" => "#04D215",
            "5" => "#36D7B7", "6" => "#0D8ECF", "7" => "#0D52D1", "8" => "#2A0CD0", "9" => "#8A0CCF", "10" => "#D85F7D", "11" => "#E84860",
            "12" => "#fbcebd");

        $baselineSources = BaselineDoctorConsultation::select(DB::raw('count(*) as count,DATE_FORMAT(visit_date, "%Y/%m") as duration'))
            ->whereDate('visit_date', '>=', $start_date)
            ->whereDate('visit_date', '<=', $end_date);
        /*  ->groupBy('duration')
          ->orderBy('duration', 'DESC')
          ->take(12)
          ->get();
   */
        if ($employee_id != null && $employee_id != '')
            $baselineSources = $baselineSources->where('created_by', $employee_id);

        $baselineSources = $baselineSources->groupBy('duration')->orderBy('duration', 'DESC')->take(12)->get();

        $overAll = array();
        $output = array();
        $i = 1;
        foreach ($baselineSources as $row) {

            //  $output[] = $row->overall_status;
            $output['DURATION'] = $row->duration;
            $output['COUNT_PATIENTS'] = $row->count;
            $output['COLOR'] = $color[$i++];
            array_push($overAll, $output);
        }
        //  dd($overAll);
        return response()->json(['success' => true, 'chartdata' => $overAll]);
    }

    public function getTop10Diagnosis(Request $request)
    {
        date_default_timezone_set('Asia/Gaza');
        // dd($request->all());
        $start_date = $request->startDate;
        $end_date = $request->endDate;
        $employee_id = $request->employee_id;
        $color = array("1" => "#FF0F00", "2" => "#FF6600", "3" => "#FF9E01", "4" => "#FCD202", "5" => "#F8FF01", "6" => "#B0DE09", "7" => "#04D215",
            "8" => "#36D7B7", "9" => "#0D8ECF", "10" => "#0D52D1", "11" => "#2A0CD0", "12" => "#8A0CCF", "13" => "#D85F7D", "15" => "#E84860",
            "16" => "#fbcebd");
        $baselineDiagnostic = BaselineDiagnostics::select(DB::raw('count(*) as count,diagnostic_id'))
            ->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date);
        /* ->groupBy('diagnostic_id')
         ->orderBy('count', 'DESC')
         ->take(10)
         ->get();*/

        if ($employee_id != null && $employee_id != '')
            $baselineDiagnostic = $baselineDiagnostic->where('created_by', $employee_id);

        $baselineDiagnostic = $baselineDiagnostic->groupBy('diagnostic_id')->orderBy('count', 'DESC')->take(10)->get();
        //  dd($baselineSources);
        $overAll = array();
        $output = array();
        $i = 1;
        foreach ($baselineDiagnostic as $row) {

            //  $output[] = $row->overall_status;
            $output['DIAGNOSTIC'] = get_diagnosis_desc($row->diagnostic_id);
            $output['DIAGNOSTIC_ID'] = $row->diagnostic_id;
            $output['COUNT'] = $row->count;
            $output['COLOR'] = $color[$i++];
            array_push($overAll, $output);
        }
        //  dd($overAll);
        return response()->json(['success' => true, 'chartdata' => $overAll]);
    }

    public function getTop10Drugs(Request $request)
    {
        date_default_timezone_set('Asia/Gaza');
        // dd($request->all());
        $start_date = $request->startDate;
        $end_date = $request->endDate;
        $employee_id = $request->employee_id;
        $color = array("1" => "#FF0F00", "2" => "#FF6600", "3" => "#FF9E01", "4" => "#FCD202", "5" => "#F8FF01", "6" => "#B0DE09", "7" => "#04D215",
            "8" => "#36D7B7", "9" => "#0D8ECF", "10" => "#0D52D1", "11" => "#2A0CD0", "12" => "#8A0CCF", "13" => "#D85F7D", "14" => "#E84850", "15" => "#e87150",
            "16" => "#e8b350", "17" => "#e8d450", "18" => "#b0e850", "19" => "#50e897", "20" => "#50e5e8");
        $created_by = '';
        if ($employee_id != null && $employee_id != '')
            $created_by = " AND created_by=" . $employee_id;
        $sql = "SELECT drug_id,COUNT(DISTINCT(cnt)) as cnt FROM
                (SELECT  DISTINCT(pain_file_id) AS cnt ,drug_id
                 FROM `followup_treatments` 
                 WHERE DATE(`created_at`) >='" . $start_date . "'
                 AND DATE(`created_at`) <='" . $end_date . "'
                 " . $created_by . "
                 AND order_status=1
                 AND `followup_treatments`.`deleted_at` IS NULL 
                 UNION 
                 SELECT DISTINCT(pain_file_id) AS cnt,drug_id
                 FROM `baseline_treatment_choices` 
                WHERE DATE(`created_at`) >='" . $start_date . "'
                 AND DATE(`created_at`) <='" . $end_date . "'
                 " . $created_by . "
                 AND order_status=1
                 AND `baseline_treatment_choices`.`deleted_at` IS NULL 
                 ) top_drugs
                 GROUP BY drug_id
                 ORDER BY cnt desc LIMIT 20
               ";
        //  echo $sql;exit;
        $results = DB::select(DB::raw($sql));
        //  print_r($results);
        /*  $baselineTreatment = BaselineTreatmentChoice::select(DB::raw('DISTINCT drug_id,count(drug_id) as count'))
              ->whereDate('created_at', '>=', $start_date)
              ->whereDate('created_at', '<=', $end_date)
              ->groupBy('drug_id');
            // ->orderBy('count', 'DESC');
          $followupTreatment = FollowupTreatment::select(DB::raw('DISTINCT drug_id,count(drug_id) as count'))
              ->whereDate('created_at', '>=', $start_date)
              ->whereDate('created_at', '<=', $end_date)
              ->distinct()
              ->groupBy('drug_id')
              ->union($baselineTreatment)
              ->orderBy('count', 'DESC')
              ->take(20)
              ->get();*/
        //  dd($baselineSources);
        $overAll = array();
        $output = array();
        $i = 1;
        foreach ($results as $row) {

            //  $output[] = $row->overall_status;
            $output['DRUGS'] = get_drug_desc($row->drug_id);
            $output['DRUG_ID'] = $row->drug_id;
            $output['COUNT'] = $row->cnt;
            $output['COLOR'] = $color[$i++];
            array_push($overAll, $output);
        }
        //  dd($overAll);
        return response()->json(['success' => true, 'chartdata' => $overAll]);
    }

    public function getPainScale(Request $request)
    {
        date_default_timezone_set('Asia/Gaza');
        // dd($request->all());
        $start_date = $request->startDate;
        $end_date = $request->endDate;
        $employee_id = $request->employee_id;
        $color = array("1" => "#FF0F00", "2" => "#FF6600", "3" => "#FF9E01", "4" => "#FCD202", "5" => "#F8FF01", "6" => "#B0DE09", "7" => "#04D215",
            "8" => "#36D7B7", "9" => "#0D8ECF", "10" => "#0D52D1", "11" => "#2A0CD0", "12" => "#8A0CCF", "13" => "#D85F7D", "14" => "#E84850", "15" => "#e87150",
            "16" => "#e8b350", "17" => "#e8d450", "18" => "#b0e850", "19" => "#50e897", "20" => "#50e5e8");
        $created_by = '';
        if ($employee_id != null && $employee_id != '')
            $created_by = " AND created_by=" . $employee_id;


        $baselineSources = BaslineLastFollowupVw::select(DB::raw('count(*) as count,pain_scale_prc as pain_scale'))
            ->whereDate('last_followup_create_date', '>=', $start_date)
            ->whereDate('last_followup_create_date', '<=', $end_date)
            ->whereNotNull('pain_scale_prc');

        if ($employee_id != null && $employee_id != '')
            $baselineSources = $baselineSources->where('created_by', $employee_id);

        $baselineSources = $baselineSources->groupBy('pain_scale')->orderBy('pain_scale', 'ASC')->get();


        $overAll = array();
        $output = array();
        $i = 1;
        foreach ($baselineSources as $row) {

            //  $output[] = $row->overall_status;

            $output['PAIN_SCALE'] = $row->pain_scale;
            $output['COUNT'] = $row->count;
            $output['COLOR'] = $color[$i++];
            array_push($overAll, $output);
        }
        //  dd($overAll);
        return response()->json(['success' => true, 'chartdata' => $overAll]);
    }

    public function getDrugsCostChart(Request $request)
    {
        date_default_timezone_set('Asia/Gaza');
        // dd($request->all());
        $start_date = $request->startDate;
        $end_date = $request->endDate;
        $employee_id = $request->employee_id;
        $color = array("1" => "#FF0F00", "2" => "#FF6600", "3" => "#FF9E01", "4" => "#FCD202", "5" => "#F8FF01", "6" => "#B0DE09", "7" => "#04D215",
            "8" => "#36D7B7", "9" => "#0D8ECF", "10" => "#0D52D1", "11" => "#2A0CD0", "12" => "#8A0CCF", "13" => "#D85F7D", "14" => "#E84850", "15" => "#e87150",
            "16" => "#e8b350", "17" => "#e8d450", "18" => "#b0e850", "19" => "#50e897", "20" => "#50e5e8");
        $created_by1 = '';
        $created_by2 = '';
        if ($employee_id != null && $employee_id != '') {
            $created_by1 = " AND followup_doctors.created_by=" . $employee_id;
            $created_by2 = " AND baseline_treatment_choices.created_by=" . $employee_id;
        }
        $sql = "SELECT drug_id,SUM(drug_cost) AS drug_cost FROM
                (SELECT  drug_id,drug_cost
                 FROM followup_treatments,followup_doctors 
                 WHERE followup_treatments.followup_id=followup_doctors.followup_id
                 AND followup_treatments.pain_file_id=followup_doctors.pain_file_id
                 AND DATE(followup_doctors.created_at) >='" . $start_date . "'
                 AND DATE(followup_doctors.created_at) <='" . $end_date . "'
                 " . $created_by1 . "
                 AND order_status=1
                 AND followup_treatments.deleted_at IS NULL 
                 UNION 
                 SELECT drug_id,drug_cost
                 FROM `baseline_treatment_choices` 
                WHERE DATE(`created_at`) >='" . $start_date . "'
                 AND DATE(`created_at`) <='" . $end_date . "'
                 " . $created_by2 . "
                 AND order_status=1
                 AND `baseline_treatment_choices`.`deleted_at` IS NULL 
                 ) top_drugs
                 GROUP BY drug_id
                 ORDER BY drug_cost desc 
               ";
        //  echo $sql;exit;
        $results = DB::select(DB::raw($sql));
        //  print_r($results);
        /*  $baselineTreatment = BaselineTreatmentChoice::select(DB::raw('DISTINCT drug_id,count(drug_id) as count'))
              ->whereDate('created_at', '>=', $start_date)
              ->whereDate('created_at', '<=', $end_date)
              ->groupBy('drug_id');
            // ->orderBy('count', 'DESC');
          $followupTreatment = FollowupTreatment::select(DB::raw('DISTINCT drug_id,count(drug_id) as count'))
              ->whereDate('created_at', '>=', $start_date)
              ->whereDate('created_at', '<=', $end_date)
              ->distinct()
              ->groupBy('drug_id')
              ->union($baselineTreatment)
              ->orderBy('count', 'DESC')
              ->take(20)
              ->get();*/
        //  dd($baselineSources);
        $overAll = array();
        $output = array();
        $i = 1;
        foreach ($results as $row) {
            if ($i == 20)
                $i = 1;
            if (round($row->drug_cost, 2) >= 1) {
                //  $output[] = $row->overall_status;
                $output['DRUGS'] = get_drug_desc($row->drug_id);
                $output['DRUG_ID'] = $row->drug_id;
                $output['DRUG_COST'] = round($row->drug_cost, 2);
                $output['COLOR'] = $color[$i++];
                array_push($overAll, $output);
            }
        }
        //  dd($overAll);
        return response()->json(['success' => true, 'chartdata' => $overAll]);
    }

    public function getDoctorDrugsCostChart(Request $request)
    {
        date_default_timezone_set('Asia/Gaza');
        // dd($request->all());
        $start_date = $request->startDate;
        $end_date = $request->endDate;
        $employee_id = $request->employee_id;
        $color = array("1" => "#FF0F00", "2" => "#FF6600", "3" => "#FF9E01", "4" => "#FCD202", "5" => "#F8FF01", "6" => "#B0DE09", "7" => "#04D215",
            "8" => "#36D7B7", "9" => "#0D8ECF", "10" => "#0D52D1", "11" => "#2A0CD0", "12" => "#8A0CCF", "13" => "#D85F7D", "14" => "#E84850", "15" => "#e87150",
            "16" => "#e8b350", "17" => "#e8d450", "18" => "#b0e850", "19" => "#50e897", "20" => "#50e5e8");
        $created_by1 = '';
        $created_by2 = '';
        if ($employee_id != null && $employee_id != '') {
            $created_by1 = " AND followup_doctors.created_by=" . $employee_id;
            $created_by2 = " AND baseline_treatment_choices.created_by=" . $employee_id;
        }
        $sql = "SELECT created_by,SUM(drug_cost) AS drug_cost FROM
                (SELECT  followup_doctors.created_by,drug_cost
                 FROM `followup_treatments` ,followup_doctors 
                 WHERE followup_treatments.followup_id=followup_doctors.followup_id
                 AND followup_treatments.pain_file_id=followup_doctors.pain_file_id
                 AND DATE(followup_doctors.created_at) >='" . $start_date . "'
                 AND DATE(followup_doctors.created_at) <='" . $end_date . "'
                 " . $created_by1 . "
                 AND order_status=1
                 AND `followup_treatments`.`deleted_at` IS NULL 
                 UNION 
                 SELECT created_by,drug_cost
                 FROM `baseline_treatment_choices` 
                WHERE DATE(`created_at`) >='" . $start_date . "'
                 AND DATE(`created_at`) <='" . $end_date . "'
                 " . $created_by2 . "
                 AND order_status=1
                 AND `baseline_treatment_choices`.`deleted_at` IS NULL 
                 ) top_drugs
                 GROUP BY created_by
                 ORDER BY drug_cost desc 
               ";
        //  echo $sql;exit;
        $results = DB::select(DB::raw($sql));
        //  print_r($results);
        /*  $baselineTreatment = BaselineTreatmentChoice::select(DB::raw('DISTINCT drug_id,count(drug_id) as count'))
              ->whereDate('created_at', '>=', $start_date)
              ->whereDate('created_at', '<=', $end_date)
              ->groupBy('drug_id');
            // ->orderBy('count', 'DESC');
          $followupTreatment = FollowupTreatment::select(DB::raw('DISTINCT drug_id,count(drug_id) as count'))
              ->whereDate('created_at', '>=', $start_date)
              ->whereDate('created_at', '<=', $end_date)
              ->distinct()
              ->groupBy('drug_id')
              ->union($baselineTreatment)
              ->orderBy('count', 'DESC')
              ->take(20)
              ->get();*/
        //  dd($baselineSources);
        $overAll = array();
        $output = array();
        $i = 1;
        foreach ($results as $row) {
            if ($i == 20)
                $i = 1;
            if (round($row->drug_cost, 2) >= 1) {
                //  $output[] = $row->overall_status;
                $output['USER_NAME'] = User::find($row->created_by)->name;
                $output['USER_ID'] = $row->created_by;
                $output['DRUG_COST'] = round($row->drug_cost, 2);
                $output['COLOR'] = $color[$i++];
                array_push($overAll, $output);
            }
        }
        //  dd($overAll);
        return response()->json(['success' => true, 'chartdata' => $overAll]);
    }

    public function getAreaChart(Request $request)
    {
        date_default_timezone_set('Asia/Gaza');
        // dd($request->all());
        $start_date = $request->startDate;
        $end_date = $request->endDate;
        $employee_id = $request->employee_id;
        /* $color = array("1" => "#FF0F00", "2" => "#FF6600", "3" => "#FF9E01", "4" => "#FCD202", "5" => "#F8FF01", "6" => "#B0DE09", "7" => "#04D215",
             "8" => "#36D7B7", "9" => "#0D8ECF", "10" => "#0D52D1", "11" => "#2A0CD0", "12" => "#8A0CCF", "13" => "#D85F7D", "14" => "#E84860",
             "15" => "#fbcebd");*/

        $model = BaselineNeurologicalExam::select(DB::raw('count(*) as count,side_nerve_id'))
            ->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date);
        /* ->groupBy('side_nerve_id')
         ->orderBy('count', 'DESC')
         ->take(10)
         ->get();*/
        if ($employee_id != null && $employee_id != '')
            $model = $model->where('created_by', $employee_id);

        $model = $model->groupBy('side_nerve_id')->orderBy('count', 'DESC')->take(10)->get();
        //  dd($baselineSources);
        $overAll = array();
        $output = array();
        $i = 1;
        foreach ($model as $row) {

            //  $output[] = $row->overall_status;
            $output['SIDE_NERVE'] = get_lookup_desc($row->side_nerve_id);
            $output['COUNT'] = $row->count;
            //   $output['COLOR'] = $color[$i++];
            array_push($overAll, $output);
        }
        //  dd($overAll);
        return response()->json(['success' => true, 'chartdata' => $overAll]);
    }

    public function getPlexusAreaChart(Request $request)
    {
        date_default_timezone_set('Asia/Gaza');
        // dd($request->all());
        $start_date = $request->startDate;
        $end_date = $request->endDate;
        $employee_id = $request->employee_id;
        $color = array("1" => "#FF0F00", "2" => "#FF6600", "3" => "#FF9E01", "4" => "#FCD202", "5" => "#F8FF01", "6" => "#B0DE09", "7" => "#04D215",
            "8" => "#36D7B7", "9" => "#0D8ECF", "10" => "#0D52D1", "11" => "#2A0CD0", "12" => "#8A0CCF", "13" => "#D85F7D", "14" => "#E84860",
            "15" => "#fbcebd");

        $model = BaselineNeurologicalExam::select(DB::raw('count(*) as count,side_nerve_id,side_detail_id'))
            ->where('side_nerve_id', 139)
            ->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date);
        /*->groupBy('side_nerve_id', 'side_detail_id')
        ->orderBy('count', 'DESC')
        ->take(50)
        ->get();*/

        if ($employee_id != null && $employee_id != '')
            $model = $model->where('created_by', $employee_id);

        $model = $model->groupBy('side_nerve_id')->orderBy('count', 'DESC')->take(50)->get();
        //  dd($baselineSources);
        $overAll = array();
        $output = array();
        $i = 1;
        foreach ($model as $row) {

            //  $output[] = $row->overall_status;
            $output['SIDE_NERVE'] = get_lookup_desc($row->side_nerve_id) . '-' . get_lookup_desc($row->side_detail_id);
            $output['COUNT'] = $row->count;
            $output['COLOR'] = $color[$i++];
            array_push($overAll, $output);
            if ($i == 15)
                $i = 1;
        }
        //  dd($overAll);
        return response()->json(['success' => true, 'chartdata' => $overAll]);
    }

    public function getDermatomesAreaChart(Request $request)
    {
        date_default_timezone_set('Asia/Gaza');
        // dd($request->all());
        $start_date = $request->startDate;
        $end_date = $request->endDate;
        $employee_id = $request->employee_id;
        $color = array("1" => "#FF0F00", "2" => "#FF6600", "3" => "#FF9E01", "4" => "#FCD202", "5" => "#F8FF01", "6" => "#B0DE09", "7" => "#04D215",
            "8" => "#36D7B7", "9" => "#0D8ECF", "10" => "#0D52D1", "11" => "#2A0CD0", "12" => "#8A0CCF", "13" => "#D85F7D", "14" => "#E84860",
            "15" => "#fbcebd");

        $model = BaselineNeurologicalExam::select(DB::raw('count(*) as count,side_nerve_id,side_detail_id'))
            ->where('side_nerve_id', 140)
            ->where('light_touch', '!=', 52)
            ->where('pinprick', '!=', 52)
            ->where('warmth', '!=', 52)
            ->where('cold', '!=', 52)
            ->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date);
        /*->groupBy('side_nerve_id', 'side_detail_id')
        ->orderBy('count', 'DESC')
        ->take(50)
        ->get();*/

        if ($employee_id != null && $employee_id != '')
            $model = $model->where('created_by', $employee_id);

        $model = $model->groupBy('side_nerve_id', 'side_detail_id')->orderBy('count', 'DESC')->take(50)->get();
        //  dd($baselineSources);
        $overAll = array();
        $output = array();
        $i = 1;
        foreach ($model as $row) {

            //  $output[] = $row->overall_status;
            $output['SIDE_NERVE'] = get_lookup_desc($row->side_nerve_id) . '-' . get_lookup_desc($row->side_detail_id);
            $output['COUNT'] = $row->count;
            $output['COLOR'] = $color[$i++];
            array_push($overAll, $output);
            if ($i == 15)
                $i = 1;
        }
        //  dd($overAll);
        return response()->json(['success' => true, 'chartdata' => $overAll]);
    }

    public function getPeripheralAreaChart(Request $request)
    {
        date_default_timezone_set('Asia/Gaza');
        // dd($request->all());
        $start_date = $request->startDate;
        $end_date = $request->endDate;
        $employee_id = $request->employee_id;
        $color = array("1" => "#FF0F00", "2" => "#FF6600", "3" => "#FF9E01", "4" => "#FCD202", "5" => "#F8FF01", "6" => "#B0DE09", "7" => "#04D215",
            "8" => "#36D7B7", "9" => "#0D8ECF", "10" => "#0D52D1", "11" => "#2A0CD0", "12" => "#8A0CCF", "13" => "#D85F7D", "14" => "#E84860",
            "15" => "#fbcebd");

        $model = BaselineNeurologicalExam::select(DB::raw('count(*) as count,side_nerve_id,side_detail_id'))
            ->where('side_nerve_id', 141)
            ->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date);
        /* ->groupBy('side_nerve_id', 'side_detail_id')
         ->orderBy('count', 'DESC')
         ->take(50)
         ->get();*/
        if ($employee_id != null && $employee_id != '')
            $model = $model->where('created_by', $employee_id);

        $model = $model->groupBy('side_nerve_id', 'side_detail_id')->orderBy('count', 'DESC')->take(50)->get();
        //  dd($baselineSources);
        $overAll = array();
        $output = array();
        $i = 1;
        foreach ($model as $row) {

            //  $output[] = $row->overall_status;
            $output['SIDE_NERVE'] = get_lookup_desc($row->side_nerve_id) . '-' . get_lookup_desc($row->side_detail_id);
            $output['COUNT'] = $row->count;
            $output['COLOR'] = $color[$i++];
            array_push($overAll, $output);
            if ($i == 15)
                $i = 1;
        }
        //  dd($overAll);
        return response()->json(['success' => true, 'chartdata' => $overAll]);
    }

    //*************************************//
    public function get_chart_details(Request $request)
    {
        date_default_timezone_set('Asia/Gaza');
        // dd($request->all());
        $start_date = $request->startDate;
        $end_date = $request->endDate;
        $chart_item = $request->chart_item;
        $chart_id = $request->chart_id;
        if ($chart_id == 1) {//diagnosis chart
            $sql = "SELECT  bvw.* ,'1' as visit_type
                  FROM  baseline_diagnostics bd,patients_pain_baseline_vw bvw
                  WHERE bd.pain_file_id=bvw.pain_file_id
                  AND   DATE(baseline_date) >='" . $start_date . "'
                  AND   DATE(baseline_date) <='" . $end_date . "'
                  AND   bd.diagnostic_id =" . $chart_item . "
                  AND  bd.deleted_at IS NULL
                  ORDER BY pain_file_id desc
               ";
        } else if ($chart_id == 2) {//drugs chart
            $sql = "SELECT drug_tb.* FROM
                (SELECT bvw.id,bvw.national_id,bvw.name, bvw.name_a, bvw.dob, 
bvw.gender,bvw.mobile_no, bvw.district, bvw.city, bvw.address,
bvw.pain_file_id, bvw.patient_id, bvw.start_date, bvw.close_date, bvw.status,
bvw.baseline_date, bvw.last_followup_date,bvw.doctor_name,'' as followup_id,bvw.baseline_date as visit_date,'1' as visit_type
                 FROM followup_treatments ft,patients_pain_baseline_vw bvw
                 WHERE ft.pain_file_id=bvw.pain_file_id
                 AND DATE(`baseline_date`) >='" . $start_date . "'
                 AND DATE(`baseline_date`) <='" . $end_date . "'
                 AND ft.drug_id =" . $chart_item . "
                 AND ft.deleted_at IS NULL 
                 UNION 
                 SELECT fvw.id,fvw.national_id,fvw.name, fvw.name_a, fvw.dob, 
fvw.gender,fvw.mobile_no, fvw.district, fvw.city, fvw.address,
fvw.pain_file_id, fvw.patient_id, fvw.start_date, fvw.close_date, fvw.status,
fvw.baseline_date, fvw.last_followup_date,fvw.doctor_name,fvw.followup_id,fvw.follow_up_date as visit_date,'2' as visit_type 
                 FROM baseline_treatment_choices bt,patients_pain_followup_vw fvw
                 WHERE bt.pain_file_id=fvw.pain_file_id
                 AND DATE(`follow_up_date`) >='" . $start_date . "'
                 AND DATE(`follow_up_date`) <='" . $end_date . "'
                 AND bt.drug_id =" . $chart_item . "
                 AND bt.deleted_at IS NULL 
                ) drug_tb
                 ORDER BY drug_tb.pain_file_id desc";
        }

        //echo $sql;exit;
        $results = DB::select(DB::raw($sql));
        //print_r($results);

        $num = 1;
        return datatables()->of($results)
            ->addColumn('num', function () use (&$num) {// user & as reference to store the privies value
                return $num++;
            })
            ->addColumn('gender_desc', function ($model) {// as foreach ($users as $user)
                $gender_list = ['1' => 'Male', '2' => 'Female'];
                return $gender_list[$model->gender];
            })
            ->addColumn('source', function ($model) {// as foreach ($users as $user)
                $gender_list = ['1' => 'Baseline', '2' => 'Follow up'];
                return $gender_list[$model->visit_type];
            })
            ->addColumn('patient_name', function ($model) {// as foreach ($users as $user)
                $html = ' <a onclick="viewPainFile(' . $model->pain_file_id . ',' . $model->patient_id . ',' . $model->status . ')" href="#">
                                ' . $model->name . '
                            </a>';

                return $html;
            })->addColumn('patient_name_a', function ($model) {// as foreach ($users as $user)
                $html = ' <a onclick="viewPainFile(' . $model->pain_file_id . ',' . $model->patient_id . ',' . $model->status . ')" href="#">
                                ' . $model->name_a . '
                            </a>';

                return $html;
            })
            ->rawColumns(['patient_name', 'patient_name_a'])
            ->toJson();
    }

}