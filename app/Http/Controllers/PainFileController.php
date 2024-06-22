<?php

namespace App\Http\Controllers;

use App\AcutePainService;
use App\BaselineDoctorConsultation;
use App\BaselineTreatmentChoice;
use App\FollowupLast;
use App\FollowupPatient;
use App\FollowupTreatment;
use App\PainFile;
use App\Patient;
use App\ShareConsultationDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;

class PainFileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function painFile_View($painFile_id = null, $patientid = null, $painFile_status = null)
    {
        // dd('$patientid :'.$patientid);

        //  $painFile_id = session()->get('painFile_id');
        //  $patientid = session()->get('patient_id');
        //  $painFile_status = session()->get('painFile_status');
        //dd($painFile_id);
        // $this->data['location_link']='#';
        //  $this->data['location_title']='Pain File';
        if (!isset($painFile_id))
            return redirect()->route('home');
        $this->data['page_title'] = 'Pain File';
        $this->data['page_title_small'] = '';
        $this->data['total_acutepain_count'] = AcutePainService::where('pain_file_id', $painFile_id)->count();
        $this->data['last_acutepain_date'] = AcutePainService::where('pain_file_id', $painFile_id)->orderBy('visit_date', 'desc')->first();
        $this->data['total_baseline_count'] = BaselineDoctorConsultation::where('pain_file_id', $painFile_id)->count();
        $this->data['last_baseline_date'] = BaselineDoctorConsultation::where('pain_file_id', $painFile_id)->orderBy('visit_date', 'desc')->first();
        $this->data['total_followup_count'] = FollowupPatient::where('pain_file_id', $painFile_id)->count();
        $this->data['last_followup_date'] = FollowupPatient::where('pain_file_id', $painFile_id)->orderBy('follow_up_date', 'desc')->first();
        $this->data['total_lastfollowup_count'] = FollowupLast::where('pain_file_id', $painFile_id)->count();
        $this->data['last_lastfollowup_date'] = FollowupLast::where('pain_file_id', $painFile_id)->orderBy('follow_up_date', 'desc')->first();
        $this->data['consultation_data'] = $this->getConsultationRequest($painFile_id);
        // dd($this->data['total_followup_count']);
        $this->data['one_patient'] = Patient::find($patientid);
        $this->data['painFile_id'] = $painFile_id;
        $this->data['painFile_status'] = ($painFile_status == 17) ? 'Open' : 'Closed';
        //$this->data['covid_data'] = $this->check_covid_patient($this->data['one_patient']->national_id);
        $this->data['painFile_statusId'] = $painFile_status;
        $this->data['baseline_doctor_visit_date'] = '';
        $baselineDoctor = BaselineDoctorConsultation::where('pain_file_id', $painFile_id)->first();
        $this->data['districts'] = get_lookups_list(1);

        if (isset($baselineDoctor))
            $this->data['baseline_doctor_visit_date'] = $baselineDoctor->visit_date;

        $this->data['painFile_Status'] = ($painFile_status == 17) ? 'Open' : 'Closed';

        $this->data['page'] = 'Pain File';

        return view(patient_vw() . '.patientfile')->with($this->data);

    }

    public function check_covid_patient($patientid)
    {
        // dd($patientid);
        $patientId = $patientid;//مصاب 426171310
        $token = 'cov192021@#';

        $url = 'apps.moh.gov.ps/phc/api/covidinfectedcontact.php';

        $ch = curl_init();
        // curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ['patientId' => $patientId, 'token' => $token]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


        $response = curl_exec($ch);
        $err = curl_error($ch);  //if you need
        curl_close($ch);

        $result = json_decode($response, true);

        return $result['msg'];


    }

    public function getConsultationComments(Request $request)
    {
        $id = $request->id;

        $consultations = ShareConsultationDetail::where('share_consultations_id', $id)->get();

        $html = '';
        foreach ($consultations as $row) {
            $html .= '<li class="list-group-item bg-grey-steel">
                                                <ul class="list-inline">
                                                    <li>
                                                        <i class="fa fa-user-md font-green"></i><span class="font-grey-cascade">' . $row->user_name . '</span></li>
                                                    <li>
                                                        <i class="fa fa-calendar font-green"></i><span class="font-grey-cascade">' . Carbon::parse($row->comment_date)->format('d-m-Y') . '</span></li>
                                                </ul>
                                                <p>' . $row->comment_text . '</p>
                                            </li>';

        }
        return response()->json(['success' => true, 'html' => $html]);
    }


    public
    function painFile_setId(Request $request)
    {
        $url = $request->url;
        //dd($request->all());
        session()->put('painFile_id', '');
        session()->put('painFile_status', '');
        session()->put('patient_id', '');
        $pinfileid = $request->painFile_id;
        $painFile_status = $request->painFile_status;
        $patientid = $request->patient_id;

        session()->put('painFile_id', $pinfileid);
        session()->put('painFile_status', $painFile_status);
        session()->put('patient_id', $patientid);

        return Redirect::to($url);


    }

    public function new_painFile(Request $request)
    {
        $patient_id = $request->patient_id;
        $patient = Patient::where('id', $patient_id)->first();

        if (isset($patient)) {

            $painFiles = PainFile::where('patient_id', $patient_id)->get();
            // dd($painFiles);
            if (isset($painFiles)) {

                foreach ($painFiles as $painFile) {
                    if ($painFile->status == 17)
                        return response()->json(['success' => false, 'msg' => 'The Patient have one opened pain file, One open file allowed only']);
                }
                $newpainFiles = new PainFile();
                $newpainFiles->patient_id = $patient_id;
                $newpainFiles->start_date = date('Y-m-d');
                $newpainFiles->status = 17;
                $newpainFiles->created_by = auth()->user()->id;
                $newpainFiles->org_id = auth()->user()->org_id;
                if ($newpainFiles->save())
                    return response()->json(['success' => true, 'painFile_id' => $newpainFiles->id, 'patient_id' => $patient_id, 'painFile_status' => $newpainFiles->status]);
                return response()->json(['success' => false, 'msg' => 'Can\'t open pain file for this patient.']);
            }
        }
        return response()->json(['success' => false, 'msg' => 'Can\'t open pain file for this patient.']);

    }

    /* public
     function search_patient_list(Request $request)
     {

         $name = $request->name;
         $name_a = $request->name_a;
         $national_id = $request->national_id;
         $from_date = $request->from;
         $to_date = $request->to;
         $file_status = $request->file_status;
         $gender = $request->gender;
         $start_date = $request->start_date;
         $end_date = $request->end_date;
         $appointment_loc = $request->appointment_loc;

         //dd($request->all());
         $painFiles = PainFile::with('patient')->select('pain_files.*');


         if (isset($national_id) && $national_id != null) {
             $patient_list = Patient::where('national_id', '=', $national_id)->pluck('id')->toArray();
             $painFiles = $painFiles->whereIn('patient_id', $patient_list);
         }
         if (isset($name) && $name != null) {


             $patient_list = Patient::where('name', 'like', '%' . $name . '%')->pluck('id')->toArray();
             $painFiles = $painFiles->whereIn('patient_id', $patient_list);

         }
         if (isset($name_a) && $name_a != null) {


             $patient_list = Patient::where('name_a', 'like', '%' . $name_a . '%')->pluck('id')->toArray();
             $painFiles = $painFiles->whereIn('patient_id', $patient_list);

         }
         if (isset($file_status) && $file_status != null) {

             $painFiles = $painFiles->where('status', '=', $file_status);
         }
         if (isset($gender) && $gender != null) {

             $patient_list = Patient::where('gender', $gender)->pluck('id')->toArray();
             $painFiles = $painFiles->whereIn('patient_id', $patient_list);
         }
         if (isset($from_date) && $from_date != null) {

             $painFiles = $painFiles->whereDate('start_date', '>=', $from_date);
         }
         if (isset($to_date) && $to_date != null) {

             $painFiles = $painFiles->whereDate('start_date', '<=', $to_date);
         }
         if (isset($start_date) && $start_date != '') {

             $painFiles = $painFiles->whereDate('last_followup_date', '>=', $start_date)
                 ->whereDate('last_followup_date', '<=', $end_date);

         }
         if (isset($appointment_loc) && $appointment_loc != null) {

             $patient_list = BaselineDoctorConsultation::where('created_by', $appointment_loc)->pluck('pain_file_id')->toArray();

             $painFiles = $painFiles->whereIn('id', $patient_list);
         }

         //  $painFiles = $painFiles->orderBy('patient_id');
         $num = 1;
         return datatables()->of($painFiles)
             ->addColumn('delChk', function ($item) {
                 return '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"> <input type="checkbox" data-id= "' . $item->id . '" id="' . $item->id . '" class="checkboxes" value="1" /><span></span></label>';
             })
             ->addColumn('num', function () use (&$num) {// user & as reference to store the privies value
                 return $num++;
             })
             ->addColumn('file_status_desc', function ($model) {// as foreach ($users as $user)
                 if ($model->status == 17)
                     return 'Open';
                 else if ($model->status == 18)
                     return 'Close';
                 else
                     return '';
             })
             ->addColumn('gender_desc', function ($model) {// as foreach ($users as $user)
                 $gender_list = ['1' => 'Male', '2' => 'Female'];
                 return $gender_list[$model->patient->gender];
             })
             ->addColumn('last_followup_date', function ($model) {// as foreach ($users as $user)
                 if (isset($model->last_followup_date))
                     return $model->last_followup_date;
             })
             ->addColumn('baseline_date', function ($model) {// as foreach ($users as $user)
                 if (isset($model->baseline_date))
                     return $model->baseline_date;

             })->addColumn('national_id', function ($model) {// as foreach ($users as $user)
                 if (isset($model->national_id))
                     return $model->national_id;
             })
             ->addColumn('action', function ($model) {// as foreach ($users as $user)
                 $html = '<div class="col-md-4" ><button type = "button" class="btn btn-icon-only red" title="Delete" onclick = "deletePainFile(' . $model->id . ')" >
                 <i class="fa fa-times" ></i ></button ></div >';

                 return '';
             })->addColumn('patient_name', function ($model) {// as foreach ($users as $user)
                 $html = ' <a onclick="viewPainFile(' . $model->id . ',' . $model->patient_id . ',' . $model->status . ')" href="#">
                                 ' . $model->patient->name . '
                             </a>';

                 return $html;
             })->addColumn('patient_name_a', function ($model) {// as foreach ($users as $user)
                 $html = ' <a onclick="viewPainFile(' . $model->id . ',' . $model->patient_id . ',' . $model->status . ')" href="#">
                                 ' . $model->patient->name_a . '
                             </a>';

                 return $html;
             })
             ->addColumn('baseline_doctor', function ($model) {// as foreach ($users as $user)
                 if (isset($model->baseline_doctor))
                     return '<span class="font-red-haze">' . $model->baseline_doctor . '</span>';
                 return '';
             })->addColumn('action', function ($model) {// as foreach ($users as $user)

                     return '<a class="btn blue btn-sm   uppercase" href="#charts_modal"
                                data-toggle="modal" onclick="show_score_chart('.$model->id.');">
                                 <i class="fa fa-line-chart"></i>
                             </a>';

             })
             ->filterColumn('name', function ($query, $keyword) {
                 $patient_list = Patient::where('name', 'like', '%' . $keyword . '%')->pluck('id')->toArray();
                 $query->whereIn('patient_id', $patient_list);

             })->filterColumn('name_a', function ($query, $keyword) {
                 $patient_list = Patient::where('name_a', 'like', '%' . $keyword . '%')->pluck('id')->toArray();
                 $query->whereIn('patient_id', $patient_list);

             })
             ->filterColumn('national_id', function ($query, $keyword) {
                 $patient_list = Patient::where('national_id', 'like', '%' . $keyword . '%')->pluck('id')->toArray();
                 $query->whereIn('patient_id', $patient_list);

             })
             // ->orderByNullsLast()
             ->orderColumn('patient_id', 'patient_id $1')
             ->orderColumn('national_id', 'patient_id $1')
             ->orderColumn('last_followup_date', 'last_followup_date $1')
             ->orderColumn('baseline_date', 'baseline_date $1')
             ->rawColumns(['action', 'delChk', 'patient_name', 'patient_name_a', 'baseline_doctor', 'baseline_date'])
             ->toJson();

     }*/
    public
    function search_patient_list(Request $request)
    {

        $name = $request->name;
        $name_a = $request->name_a;
        $national_id = $request->national_id;
        $from_date = $request->from;
        $to_date = $request->to;
        $file_status = $request->file_status;
        $gender = $request->gender;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $appointment_loc = $request->appointment_loc;

        //dd($request->all());
        $painFiles = PainFile::with('patient')->select('pain_files.*');


        if (isset($national_id) && $national_id != null && auth()->user()->id != 100) {
            $patient_list = Patient::where('national_id', '=', $national_id)->pluck('id')->toArray();
            $painFiles = $painFiles->whereIn('patient_id', $patient_list);
        }
        if (isset($name) && $name != null && auth()->user()->id != 100) {


            $patient_list = Patient::where('name', 'like', '%' . $name . '%')->pluck('id')->toArray();
            $painFiles = $painFiles->whereIn('patient_id', $patient_list);

        }
        if (isset($name_a) && $name_a != null && auth()->user()->id != 100) {


            $patient_list = Patient::where('name_a', 'like', '%' . $name_a . '%')->pluck('id')->toArray();
            $painFiles = $painFiles->whereIn('patient_id', $patient_list);

        }
         if (isset($file_status) && $file_status != null &&($from_date == null  && $to_date == null )) {

             $painFiles = $painFiles->where('status', '=', $file_status);
         }
        if (isset($gender) && $gender != null) {

            $patient_list = Patient::where('gender', $gender)->pluck('id')->toArray();
            $painFiles = $painFiles->whereIn('patient_id', $patient_list);
        }
        if (isset($from_date) && $from_date != null && ($file_status == 17 || $file_status == '')) {

            $painFiles = $painFiles->whereDate('baseline_date', '>=', $from_date);
            if (isset($file_status) && $file_status != null)
                $painFiles = $painFiles->where('status', '=', $file_status);
        }
        if (isset($to_date) && $to_date != null && ($file_status == 17 || $file_status == '')) {

            $painFiles = $painFiles->whereDate('baseline_date', '<=', $to_date);
        }
        if (isset($from_date) && $from_date != null && (isset($file_status) && ($file_status == 18))) {

            $painFiles = $painFiles->whereDate('last_followup_date', '>=', $from_date);
            if (isset($file_status) && $file_status != null)
                $painFiles = $painFiles->where('status', '=', $file_status);
        }
        if (isset($to_date) && $to_date != null && (isset($file_status) && ($file_status == 18))) {

            $painFiles = $painFiles->whereDate('last_followup_date', '<=', $to_date);
        }
       /* if (isset($start_date) && $start_date != '') {

            $painFiles = $painFiles->whereDate('last_followup_date', '>=', $start_date)
                ->whereDate('last_followup_date', '<=', $end_date);

        }*/
        if (isset($appointment_loc) && $appointment_loc != null) {

            $patient_list = BaselineDoctorConsultation::where('created_by', $appointment_loc)->pluck('pain_file_id')->toArray();

            $painFiles = $painFiles->whereIn('id', $patient_list);
        }

        //  $painFiles = $painFiles->orderBy('patient_id');
        $num = 1;
        return datatables()->of($painFiles)
            ->addColumn('delChk', function ($item) {
                return '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"> <input type="checkbox" data-id= "' . $item->id . '" id="' . $item->id . '" class="checkboxes" value="1" /><span></span></label>';
            })
            ->addColumn('num', function () use (&$num) {// user & as reference to store the privies value
                return $num++;
            })
            ->addColumn('file_status_desc', function ($model) {// as foreach ($users as $user)
                if ($model->status == 17)
                    return 'Open';
                else if ($model->status == 18)
                    return 'Close';
                else
                    return '';
            })
            ->addColumn('gender_desc', function ($model) {// as foreach ($users as $user)
                $gender_list = ['1' => 'Male', '2' => 'Female'];
                return $gender_list[$model->patient->gender];
            })
            ->addColumn('last_followup_date', function ($model) {// as foreach ($users as $user)
                if (isset($model->last_followup_date))
                    return $model->last_followup_date;
            })
            ->addColumn('baseline_date', function ($model) {// as foreach ($users as $user)
                if (isset($model->baseline_date))
                    return $model->baseline_date;

            })->addColumn('treatment_duration', function ($model) {// as foreach ($users as $user)
                if (isset($model->close_date)) {
                    $end_date = strtotime($model->close_date);
                }
                else
                    $end_date = strtotime($model->baseline_date);
                if (isset($model->baseline_date) && $model->baseline_date != '' && $model->last_followup_date == '')
                    return (round((time() - strtotime($model->baseline_date)) / 86400));

                if (isset($model->baseline_date) && $model->baseline_date != '' && $model->last_followup_date != '')
                    //return (round((time() - strtotime($model->baseline_date)) / 86400));
                    return (int)(round((strtotime($model->last_followup_date) - strtotime($model->baseline_date)) / 86400));
                else return 0;

            })
            ->addColumn('national_id', function ($model) {// as foreach ($users as $user)
                if (isset($model->national_id))
                    if (auth()->user()->id != 100)//100=guest user
                        return $model->national_id;
                    else
                        return '**********';
            })
            ->addColumn('mobile_no', function ($model) {// as foreach ($users as $user)
                if (isset($model->mobile_no))
                    if (auth()->user()->id != 100)//100=guest user
                        return $model->mobile_no;
                    else
                        return '**********';
            })
            ->addColumn('patient_name', function ($model) {// as foreach ($users as $user)
                if (auth()->user()->id != 100)//100=guest user
                    $patient_name = $model->patient->name;
                else
                    $patient_name = '**********';
                $html = ' <a onclick="viewPainFile(' . $model->id . ',' . $model->patient_id . ',' . $model->status . ')" href="#">
                                ' . $patient_name . '
                            </a>';

                return $html;
            })->addColumn('patient_name_a', function ($model) {// as foreach ($users as $user)
                if (auth()->user()->id != 100)//100=guest user
                    $patient_name = $model->patient->name_a;
                else
                    $patient_name = '**********';
                $html = ' <a onclick="viewPainFile(' . $model->id . ',' . $model->patient_id . ',' . $model->status . ')" href="#">
                                ' . $patient_name . '
                            </a>';

                return $html;
            })
            ->addColumn('baseline_doctor', function ($model) {// as foreach ($users as $user)
                if (isset($model->baseline_doctor))
                    return '<span class="font-red-haze">' . $model->baseline_doctor . '</span>';
                return '';
            })->addColumn('action', function ($model) {// as foreach ($users as $user)
                $html = '';
                if ((auth()->user()->user_type_id == 8 || auth()->user()->id == 2) && auth()->user()->id != 100) {

                    if ($model->status == 18)
                        $html = '<button type = "button" class="btn btn-icon-only green" title="Re-Open file" onclick = "changePainFileStatus(' . $model->id . ')" >
                <i class="fa fa-folder-open" ></i ></button >';
                    else
                        $html = '<button type = "button" class="btn btn-icon-only red" title="Close file" onclick = "changePainFileStatus(' . $model->id . ')" >
                <i class="fa fa-folder" ></i ></button >';
                    //if ($model->last_followup_date == '' && $model->baseline_date == '')
                    /*    $html .= '<button type = "button" class="btn btn-icon-only yellow" title="Delete file" onclick = "delete_patient(' . $model->id . ',' . $model->patient_id . ')" >
                <i class="fa fa-eraser" ></i ></button >';*/
                }
                $html .= '<a class="btn blue btn-sm   uppercase" href="#charts_modal"
                               data-toggle="modal" onclick="show_score_chart(' . $model->id . ');">
                                <i class="fa fa-line-chart"></i>
                            </a>';
                return $html;

            })
            ->filterColumn('name', function ($query, $keyword) {
                $patient_list = Patient::where('name', 'like', '%' . $keyword . '%')->pluck('id')->toArray();
                $query->whereIn('patient_id', $patient_list);

            })->filterColumn('name_a', function ($query, $keyword) {
                $patient_list = Patient::where('name_a', 'like', '%' . $keyword . '%')->pluck('id')->toArray();
                $query->whereIn('patient_id', $patient_list);

            })
            ->filterColumn('national_id', function ($query, $keyword) {
                $patient_list = Patient::where('national_id', 'like', '%' . $keyword . '%')->pluck('id')->toArray();
                $query->whereIn('patient_id', $patient_list);

            })
            // ->orderByNullsLast()
            ->orderColumn('patient_id', 'patient_id $1')
            ->orderColumn('national_id', 'patient_id $1')
            ->orderColumn('last_followup_date', 'last_followup_date $1')
            ->orderColumn('baseline_date', 'baseline_date $1')
            ->rawColumns(['action', 'delChk', 'patient_name', 'patient_name_a', 'baseline_doctor', 'baseline_date'])
            ->toJson();

    }

    public
    function change_painfile_states(Request $request)
    {
        $painFileid = $request->id;
        $painFiles = PainFile::find($painFileid);
        if (isset($painFiles)) {
            if ($painFiles->status == 17)
                $painFiles->status = 18;
            else
                $painFiles->status = 17;
        }
        if ($painFiles->save())
            return response()->json(['success' => true]);
        return response()->json(['success' => false]);

    }

    public
    function delete_ckd_painfile()
    {
        dd('delete ckd');
    }

    public
    function index()
    {
        //  dd('index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public
    function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
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
     * @return Response
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
     * @return Response
     */
    public
    function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
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
     * @return Response
     */

    public
    function destroy($id)
    {
        //
    }

    public
    function get_visit_medicine($operation_no = null, $operation_type = null)
    {

        if ($operation_type == 2)//baseline visit
        {

            $this->data['one_visit'] = BaselineDoctorConsultation::where('id', $operation_no)->first();

            $one_visit = $this->data['one_visit'];

            if (isset($one_visit)) {

                $this->data['medicines'] = BaselineTreatmentChoice::where('pain_file_id', $one_visit->pain_file_id)->get();

            }
        } else//followup visit
        {
            $this->data['one_visit'] = FollowupPatient::where('id', $operation_no)->first();
            $one_visit = $this->data['one_visit'];
            if (isset($one_visit)) {
                $this->data['medicines'] = FollowupTreatment::where('followup_id', $one_visit->id, 'pain_file_id', $one_visit->pain_file_id)->get();
            }
        }
        if (!isset($one_visit->pain_file_id))
            return redirect()->route('home');
        $one_painFile = PainFile::where('id', $one_visit->pain_file_id)->first();

        $this->data['one_patient'] = Patient::find($one_painFile->patient_id);
        $this->data['baseline_doctor_visit_date'] = BaselineDoctorConsultation::where('pain_file_id', $one_visit->pain_file_id)->first()->visit_date;
        $this->data['baseline_doctor_name'] = BaselineDoctorConsultation::where('pain_file_id', $one_visit->pain_file_id)->first()->visit_date;
        $this->data['districts'] = get_lookups_list(1);
        $this->data['painFile_id'] = $one_visit->pain_file_id;
        $this->data['painFile_status'] = ($one_visit->pain_file_id == 17) ? 'Open' : 'Closed';
        $this->data['sub_menu'] = 'Medical prescriptions';
        $this->data['location_title'] = 'Store';
        $this->data['location_link'] = 'store';
        $this->data['title'] = 'store';
        $this->data['page_title'] = 'Prescription view';

        return view(patient_vw() . '.prescription')->with($this->data);

    }

    public
    function delete_patient_file(Request $request)
    {
        //dd($request->all());
        $painFileid = $request->id;
        $patient_id = $request->patient_id;
        $painFile = PainFile::find($painFileid);
        if (isset($painFile)) {
            $painFile->delete();
            $patient = Patient::find($patient_id);
            $patient->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }
}
