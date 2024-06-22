<?php

namespace App\Http\Controllers;

use App\Appointments;
use App\AppointmentVw;
use App\PainFile;
use App\Patient;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        date_default_timezone_set('Asia/Gaza');

        $this->data['page_title'] = 'Appointments';
        //   $this->data['location_link'] = 'painFile/view';
        $this->data['location_title'] = 'Appointment View';
        $this->data['users'] = User::whereIn('user_type_id', [9,10])->orderBy('user_type_id', 'ASC')->get();

        return view(appointment_vw() . '.view')->with($this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUserByDept(Request $request)
    {
        $user_type_id = $request->user_type_id;
        $users = User::where('user_type_id', $user_type_id)->get();
        $html = '<option value="">select..</option>';
        if (isset($users))
            foreach ($users as $user) {
                $html .= '<option value="' . $user->id . '">' . $user->name . '</option>';

            }
        return response()->json(['success' => true, 'html' => $html]);
    }

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

        $painFile = PainFile::find($request->painFile_id);
        // dd($request->all());
        $event_id = $request->event_id;
        if (!isset($event_id)) {

            if (isset($painFile)) {
                $dateTime = new \DateTime($request->appointment_date);
                $dateTime->modify('+15 minutes');
                // dd($painFile->baseline_doctor_id);
                $painFile_id = $request->painFile_id;
                $appointment = new Appointments();
                $appointment->pain_file_id = $painFile_id;
                $appointment->start_date = $request->appointment_date;
                $appointment->end_date = $dateTime;
                $appointment->appointment_type = $request->appointment_type;
                $appointment->appointment_dept = 1;//$request->appointment_dept;
                $appointment->appointment_loc = $request->appointment_loc;
                $appointment->attend_date = $request->attend_date;
                $appointment->comments = $request->comments;
                $appointment->has_appointment = $request->has_appointment;
                $appointment->created_by = auth()->user()->id;
                $appointment->org_id = auth()->user()->org_id;
                if ($appointment->save()) {
                    //  dd( $appointment->start_date);
                    $events = Appointments::where('id', '=', $appointment->id)->get();
                    //    dd($events);
                    $data = array();

                    foreach ($events as $event) {
                        $painFile = PainFile::find($event->pain_file_id);
                        $color = 'gray';
                        if ($event->appointment_type == 1)
                            $color = '#e7505a';
                        else
                            $color = '#8E44AD';
                        $data = ['id' => $event->id, 'pain_file_id' => $event->pain_file_id, 'title' => $painFile->patient_name_a, 'appointment_type' => $event->appointment_type, 'appointment_loc' => $event->appointment_loc, 'has_appointment' => $event->has_appointment, 'comments' => $event->comments, 'start' => $event->start_date, 'end' => $event->end_date, 'backgroundColor' => $color];
                    }
                    return response()->json(['success' => true, 'events' => $data]);
                }
            }
        } else {

            $appointment = Appointments::find($event_id);
            if (isset($appointment)) {


                $appointment->appointment_type = $request->appointment_type;
                $appointment->appointment_dept = 1;//$request->appointment_dept;
                $appointment->appointment_loc = $request->appointment_loc;
                $appointment->comments = $request->comments;
                $appointment->has_appointment = $request->has_appointment;

                if ($appointment->save()) {
                    //  dd( $appointment->start_date);
                    $events = Appointments::where('id', '=', $event_id)->get();
                    //    dd($events);
                    $data = array();
                    foreach ($events as $event) {
                        $painFile = PainFile::find($event->pain_file_id);
                        $color = 'gray';
                        if ($event->appointment_type == 1)
                            $color = '#e7505a';
                        else
                            $color = '#8E44AD';
                        $data = ['id' => $event->id, 'pain_file_id' => $event->pain_file_id, 'title' => $painFile->patient_name_a, 'appointment_type' => $event->appointment_type, 'appointment_loc' => $event->appointment_loc, 'has_appointment' => $event->has_appointment, 'comments' => $event->comments, 'start' => $event->start_date, 'end' => $event->end_date, 'backgroundColor' => $color];
                        $data = ['id' => $event->pain_file_id, 'title' => $painFile->patient_name, 'appointment_type' => $event->appointment_type, 'appointment_loc' => $event->appointment_loc, 'start' => $event->start_date, 'end' => $event->end_date, 'backgroundColor' => $color];
                    }
                    return response()->json(['success' => true, 'events' => $data]);
                }
            }
        }

        return response()->json(['success' => false, 'events' => '']);


    }

    public function get_events(Request $request)
    {
        //   dd('cos emk');
        $start_date = (!empty($request->start)) ? ($request->start) : ('');
        $end_date = (!empty($request->end)) ? ($request->end) : ('');
        // dd($end_date);
        $events = AppointmentVw::whereDate('start_date', '>=', $start_date)->whereDate('start_date', '<', $end_date)->get();
        $data = array();

        foreach ($events as $event) {
            $painFile = PainFile::find($event->pain_file_id);
            $color = 'gray';
            if ($event->appointment_type == 3)
                $icon = 'support';
            else  if ($event->appointment_type == 4)
                $icon = 'bubbles';

            else
                $icon = '';

            $data[] = ['id' => $event->id, 'pain_file_id' => $event->pain_file_id, 'title' => $painFile->patient_name_a,
                'appointment_type' => $event->appointment_type, 'appointment_loc' => $event->appointment_loc, 'has_appointment' => $event->has_appointment,
                'comments' => $event->comments, 'start' => $event->start_date, 'end' => $event->end_date, 'backgroundColor' => $event->user_color,'icon'=>$icon];
        }
        return response()->json(['success' => true, 'events' => $data]);
    }


    public function get_patient_by_name(Request $request)
    {

        $query = trim($_GET['query']);
        $patients = Patient::select('id', 'name_a')->where('org_id', auth()->user()->org_id)
            ->where('name_a', 'LIKE', '%' . $query . '%')
            // ->where('national_id','=',$id)
            ->get();

        $results = array();
//echo 'count'.count($patients);exit;
        if (count($patients) > 0) {
            foreach ($patients as $patient) {

                $painFile = PainFile::where('patient_id', $patient->id)->where('status', 17)->first();

                $doctor_name = '';
                $painFile_id = '';
                if (isset($painFile)) {
                    $painFile_id = $painFile->id;
                    // $doctor = BaselineDoctorConsultation::where('pain_file_id', $painFile_id)->first();
                    //  $user = User::find($doctor->created_by)->first();
                    //  if (isset($user))
                    $doctor_name = $painFile->baseline_doctor;


                    $results[] = [
                        'id' => $patient->id,
                        'name' => $patient->name_a,
                        'painFile_id' => $painFile_id,
                        'doctor_name' => $doctor_name];
                }

            }
            //  dd($results);
            return response()->json($results);
        }
        return response()->json(['success' => false, 'value' => '']);

    }

    public
    function today_list(Request $request)
    {

        $table = $request->table;
        $view_type = $request->view_type;
        $name = $request->name;
        $name_a = $request->name_a;
        $national_id = $request->national_id;
        $from_date = $request->from;
        $to_date = $request->to;
        $file_status = $request->file_status;
        $gender = $request->gender;
        $appointment_loc = $request->appointment_loc;
        $current_stage = $request->current_stage;

        $start_date = (!empty($request->from)) ? $request->from : null;
        $end_date = (!empty($request->to)) ? $request->to : null;
        $painFiles = DB::table('pain_files')
            ->join('patients', 'patients.id', '=', 'pain_files.patient_id')
            ->Join('appointments', 'appointments.pain_file_id', '=', 'pain_files.id')
            ->leftJoin('baseline_doctor_consultations', 'baseline_doctor_consultations.pain_file_id', '=', 'pain_files.id')
            ->select('patients.name', 'patients.name_a', 'patients.national_id', 'patients.gender', 'patients.mobile_no', 'pain_files.patient_id', 'pain_files.status','pain_files.baseline_date','pain_files.close_date',
                'appointments.id', 'appointments.pain_file_id', 'appointments.current_stage', 'appointments.start_date', 'appointments.attend_date', 'baseline_doctor_consultations.created_by as baseline_doctor_id', 'appointments.appointment_type')
            ->where('appointments.deleted_at', '=', null);
        /* ->whereDate('appointments.start_date', '>=', $from_date)
         ->whereDate('appointments.start_date', '<=', $to_date);*/
        if ($view_type == 0) {
            $painFiles = $painFiles->whereDate('appointments.start_date', '=', date('Y-m-d'));
        }
        if ($view_type == 4) {
            $painFiles = $painFiles->whereDate('appointments.start_date', '=', date('Y-m-d'))
                ->where('attend_date', '!=', null);
        }
        if ($view_type == 5) {
            $painFiles = $painFiles->whereDate('appointments.start_date', '=', date('Y-m-d'))
                ->where('attend_date', '=', null);
        }
        if ($view_type == 2) {
            $painFiles = $painFiles->where('attend_date', '!=', null);
        }
        if ($view_type == 3) {
            $painFiles = $painFiles->where('attend_date', '=', null);
        }
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
        if (isset($appointment_loc) && $appointment_loc != null) {

            $painFiles = $painFiles->where('baseline_doctor_consultations.created_by', '=', $appointment_loc);
        }
        if (isset($current_stage) && $current_stage != null) {

            $painFiles = $painFiles->where('current_stage', '=', $current_stage);
        }
        if (isset($gender) && $gender != null) {

            $patient_list = Patient::where('gender', $gender)->pluck('id')->toArray();
            $painFiles = $painFiles->whereIn('patient_id', $patient_list);
        }
        if (isset($from_date) && $from_date != null && $view_type != 0 && $view_type != 4 && $view_type != 5) {

            $painFiles = $painFiles->whereDate('appointments.start_date', '>=', $from_date);
        }
        if (isset($to_date) && $to_date != null && $view_type != 0 && $view_type != 4 && $view_type != 5) {

            $painFiles = $painFiles->whereDate('appointments.start_date', '<=', $to_date);
        }
        $painFiles=$painFiles->orderBy('start_date','Asc');
        //  $painFiles=$painFiles->orderBy('start_time','start_time');
        $num = 1;
        return datatables()->of($painFiles)
            ->addColumn('file_status_desc', function ($model) {// as foreach ($users as $user)
                if ($model->status == 17)
                    return 'Open';
                else if ($model->status == 18)
                    return 'Close';
                else
                    return '';
            })->addColumn('appointment_type_desc', function ($model) {// as foreach ($users as $user)
                if ($model->appointment_type == 1)
                    // return '<label class="bg-red font-white">Base line</label>';
                    return '<span class="label label-sm label-danger"> Base line </span>';
                else if ($model->appointment_type == 2)
                    return '<span class="label label-sm bg-purple"> Follow up </span>';
                // return '<label class="bg-purple font-white">Follow up</label>';
                else
                    return '';
            })
            ->addColumn('current_stage_desc', function ($model) {// as foreach ($users as $user)
                if ($model->current_stage == 1)
                    return 'Waiting Nurse';
                else if ($model->current_stage == 2)
                    return 'Waiting Doctor';
                else if ($model->current_stage == 3)
                    return 'Waiting Pharmacy';
                else
                    return '';
            })
            ->addColumn('gender_desc', function ($model) {// as foreach ($users as $user)
                $gender_list = ['1' => 'Male', '2' => 'Female'];
                return $gender_list[$model->gender];
            })
            ->addColumn('national_id', function ($model) {// as foreach ($users as $user)
                if (isset($model->national_id))
                    return $model->national_id;
            })->addColumn('start_date', function ($model) {// as foreach ($users as $user)
                if (isset($model->start_date))
                    return Carbon::parse($model->start_date)->format('Y-m-d');
            })->addColumn('start_time', function ($model) {// as foreach ($users as $user)
                if (isset($model->start_date))
                    return Carbon::parse($model->start_date)->format('H:i');
            })->addColumn('attend_date', function ($model) {// as foreach ($users as $user)
                if (isset($model->attend_date))
                    return Carbon::parse($model->attend_date)->format('Y-m-d');
                return '';
            })->addColumn('attend_time', function ($model) {// as foreach ($users as $user)
                if (isset($model->attend_date))
                    return Carbon::parse($model->attend_date)->format('H:i');
                return '';
            })
            ->addColumn('action', function ($model) {// as foreach ($users as $user)
                $html = '<div class="col-md-4" ><button  type = "button" class="btn btn-icon-only blue" title="Attend" onclick = "attend(' . $model->id . ')" >
                <i class="fa fa-clone" ></i ></button ></div >';
                if ($model->attend_date == null)
                    return $html;
                else
                    return '';
            })->addColumn('patient_name', function ($model) {// as foreach ($users as $user)
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
            ->addColumn('baseline_doctor', function ($model) {// as foreach ($users as $user)
                if (isset($model->baseline_doctor_id)) {
                    $user = User::find($model->baseline_doctor_id);
                    return '<span class="font-red-haze">' . $user->name . '</span>';
                }
                return '';
            }) ->addColumn('treatment_duration', function ($model) {// as foreach ($users as $user)
                if (isset($model->close_date)){
                    $end_date=strtotime($model->close_date);
                }
                else
                    $end_date=strtotime($model->baseline_date);
                if (isset($model->baseline_date) && $model->baseline_date!='')
                    return (round((time()-strtotime($model->baseline_date) ) / 86400));
                else return 0;

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
            ->rawColumns(['action', 'patient_name', 'patient_name_a', 'baseline_doctor', 'baseline_date', 'appointment_type_desc'])
            ->toJson();


    }

    public
    function today_listold(Request $request)
    {

        $table = $request->table;
        $view_type = $request->view_type;
        $name = $request->name;
        $name_a = $request->name_a;
        $national_id = $request->national_id;
        $from_date = $request->from;
        $to_date = $request->to;
        $file_status = $request->file_status;
        $gender = $request->gender;
        $appointment_loc = $request->appointment_loc;
        $current_stage = $request->current_stage;

        $start_date = (!empty($request->from)) ? $request->from : null;
        $end_date = (!empty($request->to)) ? $request->to : null;
        $painFiles = DB::table('pain_files')
            ->join('patients', 'patients.id', '=', 'pain_files.patient_id')
            ->Join('appointments', 'appointments.pain_file_id', '=', 'pain_files.id')
            ->select('patients.name', 'patients.name_a', 'patients.national_id', 'patients.gender', 'pain_files.patient_id', 'pain_files.status',
                'appointments.id', 'appointments.pain_file_id', 'appointments.current_stage', 'appointments.start_date', 'appointments.attend_date', 'appointments.appointment_loc', 'appointments.appointment_type')
            ->where('appointments.deleted_at', '=', null);
        /* ->whereDate('appointments.start_date', '>=', $from_date)
         ->whereDate('appointments.start_date', '<=', $to_date);*/
        if ($view_type == 0) {
            $painFiles = $painFiles->whereDate('appointments.start_date', '=', date('Y-m-d'));
        }
        if ($view_type == 4) {
            $painFiles = $painFiles->whereDate('appointments.start_date', '=', date('Y-m-d'))
                ->where('attend_date', '!=', null);
        }
        if ($view_type == 5) {
            $painFiles = $painFiles->whereDate('appointments.start_date', '=', date('Y-m-d'))
                ->where('attend_date', '=', null);
        }
        if ($view_type == 2) {
            $painFiles = $painFiles->where('attend_date', '!=', null);
        }
        if ($view_type == 3) {
            $painFiles = $painFiles->where('attend_date', '=', null);
        }
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
        if (isset($appointment_loc) && $appointment_loc != null) {

            $painFiles = $painFiles->where('appointment_loc', '=', $appointment_loc);
        }
        if (isset($current_stage) && $current_stage != null) {

            $painFiles = $painFiles->where('current_stage', '=', $current_stage);
        }
        if (isset($gender) && $gender != null) {

            $patient_list = Patient::where('gender', $gender)->pluck('id')->toArray();
            $painFiles = $painFiles->whereIn('patient_id', $patient_list);
        }
        if (isset($from_date) && $from_date != null && $view_type != 0 && $view_type != 4 && $view_type != 5) {

            $painFiles = $painFiles->whereDate('appointments.start_date', '>=', $from_date);
        }
        if (isset($to_date) && $to_date != null && $view_type != 0 && $view_type != 4 && $view_type != 5) {

            $painFiles = $painFiles->whereDate('appointments.start_date', '<=', $to_date);
        }

        $num = 1;
        return datatables()->of($painFiles)
            ->addColumn('file_status_desc', function ($model) {// as foreach ($users as $user)
                if ($model->status == 17)
                    return 'Open';
                else if ($model->status == 18)
                    return 'Close';
                else
                    return '';
            })->addColumn('appointment_type_desc', function ($model) {// as foreach ($users as $user)
                if ($model->appointment_type == 1)
                    // return '<label class="bg-red font-white">Base line</label>';
                    return '<span class="label label-sm label-danger"> Base line </span>';
                else if ($model->appointment_type == 2)
                    return '<span class="label label-sm bg-purple"> Follow up </span>';
                // return '<label class="bg-purple font-white">Follow up</label>';
                else
                    return '';
            })
            ->addColumn('current_stage_desc', function ($model) {// as foreach ($users as $user)
                if ($model->current_stage == 1)
                    return 'Waiting Nurse';
                else if ($model->current_stage == 2)
                    return 'Waiting Doctor';
                else if ($model->current_stage == 3)
                    return 'Waiting Pharmacy';
                else
                    return '';
            })
            ->addColumn('gender_desc', function ($model) {// as foreach ($users as $user)
                $gender_list = ['1' => 'Male', '2' => 'Female'];
                return $gender_list[$model->gender];
            })
            ->addColumn('national_id', function ($model) {// as foreach ($users as $user)
                if (isset($model->national_id))
                    return $model->national_id;
            })->addColumn('start_date', function ($model) {// as foreach ($users as $user)
                if (isset($model->start_date))
                    return Carbon::parse($model->start_date)->format('Y-m-d');
            })->addColumn('start_time', function ($model) {// as foreach ($users as $user)
                if (isset($model->start_date))
                    return Carbon::parse($model->start_date)->format('H:i');
            })->addColumn('attend_date', function ($model) {// as foreach ($users as $user)
                if (isset($model->attend_date))
                    return Carbon::parse($model->attend_date)->format('Y-m-d');
                return '';
            })->addColumn('attend_time', function ($model) {// as foreach ($users as $user)
                if (isset($model->attend_date))
                    return Carbon::parse($model->attend_date)->format('H:i');
                return '';
            })
            ->addColumn('action', function ($model) {// as foreach ($users as $user)
                $html = '<div class="col-md-4" ><button  type = "button" class="btn btn-icon-only blue" title="Attend" onclick = "attend(' . $model->id . ')" >
                <i class="fa fa-clone" ></i ></button ></div >';
                if ($model->attend_date == null)
                    return $html;
                else
                    return '';
            })->addColumn('patient_name', function ($model) {// as foreach ($users as $user)
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
            ->addColumn('baseline_doctor', function ($model) {// as foreach ($users as $user)
                if (isset($model->appointment_loc)) {
                    $user = User::find($model->appointment_loc);
                    return '<span class="font-red-haze">' . $user->name . '</span>';
                }
                return '';
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
            ->rawColumns(['action', 'patient_name', 'patient_name_a', 'baseline_doctor', 'baseline_date', 'appointment_type_desc'])
            ->toJson();


    }

    public function make_attend(Request $request)
    {
        date_default_timezone_set('Asia/Gaza');
        $id = $request->id;
        $appointment = Appointments::find($id);
        $appointment->attend_date = date('Y-m-d H:i:s');
        if ($appointment->save())
            return response()->json(['success' => true]);
        return response()->json(['success' => false]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    function deleteAppointment(Request $request)
    {
        $id = $request->id;
        $appointment = Appointments::find($id);
        if (isset($appointment)) {
            $appointment->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }

    public function check_available_appoint(Request $request)
    {
        $appointment_loc = $request->appointment_loc;
        $event_date = $request->event_date;
        $newDate = date("Y-m-d", strtotime($event_date));
        // dd($newDate);
        if(isset($appointment_loc)) {
            $user_daily_capacity = User::find($appointment_loc)->daily_capacity;
            $current_capacity = Appointments::where('appointment_loc', $appointment_loc)
                ->whereDate('start_date', $newDate)
                //   ->where(DB::raw("DATE(start_date) = '".$newDate."'"))
                //   $originalDate = "2010-03-21";

                ->count();
            //  dd($current_capacity);
            if ($current_capacity < $user_daily_capacity)
                return response()->json(['success' => true, 'user_daily_capacity' => $user_daily_capacity, 'current_capacity' => $current_capacity]);
            return response()->json(['success' => false,
                'user_daily_capacity' => $user_daily_capacity, 'current_capacity' => $current_capacity]);
        }
    }

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
