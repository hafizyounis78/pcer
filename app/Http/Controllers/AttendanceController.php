<?php

namespace App\Http\Controllers;

use App\AttendanceSheet;
use App\Employee;
use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       // if (in_array(6, auth()->user()->user_per)) {
            $this->data['sub_menu'] = 'attendance-display';
            $this->data['location_title'] = 'Attendance Sheet';
            $this->data['location_link'] = 'attendance';
            $this->data['title'] = 'Attendance';
            $this->data['page_title'] = 'Display Employees Attendance';
            $this->data['users'] = $this->getUserList();
            return view(attendance_vw() . '.view')->with($this->data);
       // }
        //   return redirect()->back();
      //  return redirect()->to('home');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->data['sub_menu'] = 'attendance-create';
        $this->data['location_title'] = 'New Attendance';
        $this->data['location_link'] = 'attendance';
        $this->data['title'] = 'Attendance';
        $this->data['page_title'] = 'Employees Attendance';

        return view(attendance_vw() . '.create')->with($this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */


    public function attendanceData01(Request $request)
    {
        // dd($date);
        $from = $request->from;
        $to = $request->to;
        $user_id = $request->user_id;
        $model = AttendanceSheet::query();
        if (isset($user_id) && $user_id != null) {
            $model = $model->where('user_id', $user_id);
        }
        if (isset($from) && $from != null) {
            $model = $model->whereDate('attendance_date', '>=', $from);
        }
        if (isset($to) && $to != null) {
            $model = $model->whereDate('attendance_date', '<=', $to);
        }

        $num = 1;
        return datatables()->of($model)
            ->addColumn('num', function () use (&$num) {// user & as reference to store the privies value
                return $num++;
            })
            ->addColumn('title_desc', function ($model) {// as foreach ($users as $user)

                return $this->getLookup($model->title_id);
            })
            ->addColumn('user_name', function ($model) {// as foreach ($users as $user)

                return $model->user_name;
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
    public function attendanceData(Request $request)
    {
       //  dd($request->all());
        $from = $request->from;
        $to = $request->to;
        $user_id = $request->user_id;
        $model = AttendanceSheet::query();
      /*  if (auth()->user()->title_id == 82)//باحث
            $model = $model->where('user_id', '=', auth()->user()->id);
        if (auth()->user()->title_id == 177) {//مشرف ميداني
            $users = User::where('supervised_by', '=', auth()->user()->id)->pluck('id')->toArray();
            array_push($users,auth()->user()->id);
            $model = $model->whereIn('user_id', $users);
        }
        if (auth()->user()->title_id == 178) {//مشرف مكتبي
            $users = User::where('supervised_by', '=', auth()->user()->id)->pluck('id')->toArray();

            array_push($users,auth()->user()->id);
            $model = $model->whereIn('user_id', $users);
        }
*/
        if (isset($user_id) && $user_id != null&& $user_id != 0) {
            $model = $model->where('user_id', $user_id);
        }
        if (isset($from) && $from != null) {
            $model = $model->whereDate('action_date', '>=', $from);
        }
        if (isset($to) && $to != null) {
            $model = $model->whereDate('action_date', '<=', $to);
        }

        $num = 1;
        return datatables()->of($model)
            ->addColumn('num', function () use (&$num) {// user & as reference to store the privies value
                return $num++;
            })
           ->addColumn('action_desc', function ($model) {// as foreach ($users as $user)
$action_desc=[1=>'حضور وانصراف',2=>'مهمة عمل',3=>'مهمة خاصة'];
                return $action_desc[$model->action_type_id];
            })
            ->addColumn('user_name', function ($model) {// as foreach ($users as $user)

                return $model->user_name;
            })
            ->addColumn('total_hour', function ($model) {// as foreach ($users as $user)

                if (isset($model->end_time) && isset($model->start_time))
                    $hr = (new  Carbon($model->end_time))->diff(new Carbon($model->start_time))->format('%h:%I');
                else
                    $hr = 0;
                return $hr;
            })
            ->rawColumns(['action', 'total_hour'])
            ->toJson();
    }
    public function currentDayAttendanceData(Request $request)
    {

        $currentdate = Carbon::now();
        $from = $currentdate;
        $to = $currentdate;
       // $user_id = $request->user_id;
        $model = AttendanceSheet::query();
        /*  if (auth()->user()->title_id == 82)//باحث
              $model = $model->where('user_id', '=', auth()->user()->id);
          if (auth()->user()->title_id == 177) {//مشرف ميداني
              $users = User::where('supervised_by', '=', auth()->user()->id)->pluck('id')->toArray();
              array_push($users,auth()->user()->id);
              $model = $model->whereIn('user_id', $users);
          }
          if (auth()->user()->title_id == 178) {//مشرف مكتبي
              $users = User::where('supervised_by', '=', auth()->user()->id)->pluck('id')->toArray();

              array_push($users,auth()->user()->id);
              $model = $model->whereIn('user_id', $users);
          }
  */

        if (isset($from) && $from != null) {
            $model = $model->whereDate('action_date', '>=', $from);
        }
        if (isset($to) && $to != null) {
            $model = $model->whereDate('action_date', '<=', $to);
        }

        $num = 1;
        return datatables()->of($model)
            ->addColumn('num', function () use (&$num) {// user & as reference to store the privies value
                return $num++;
            })
            ->addColumn('action_desc', function ($model) {// as foreach ($users as $user)
                $action_desc=[1=>'حضور وانصراف',2=>'مهمة عمل',3=>'مهمة خاصة'];
                return $action_desc[$model->action_type_id];
            })
            ->addColumn('user_name', function ($model) {// as foreach ($users as $user)

                return $model->user_name;
            })
            ->addColumn('total_hour', function ($model) {// as foreach ($users as $user)

                if (isset($model->end_time) && isset($model->start_time))
                    $hr = (new  Carbon($model->end_time))->diff(new Carbon($model->start_time))->format('%h:%I');
                else
                    $hr = 0;
                return $hr;
            })
            ->rawColumns(['action', 'total_hour'])
            ->toJson();
    }
    public function save_attendance(Request $request)
    {
        $currentdate = Carbon::now();
        $action_type_id='';
        if ($request->action_type == 1 || $request->action_type == 2)//دخول وخروج
            $action_type_id = 1;
        else if ($request->action_type == 3 || $request->action_type == 4)//مهمة عمل
            $action_type_id = 2;
        else if ($request->action_type == 5 || $request->action_type == 6)//مهمة خاصة
            $action_type_id = 3;
        //  echo $request->action_type;exit;//
        if ($request->action_type == 1)        // Check in دخول
        {

            $attend = AttendanceSheet::where('user_id', $request->idno)
                ->whereDate('action_date', $currentdate->format('Y-m-d'))
                ->where('action_type_id',$action_type_id)
                ->first();

            if (isset($attend)) {
                /* $old_note =$attend->note;
                 $attend->start_time = $currentdate->format('H:i');
               //  $attend->note = $old_note.'--'.$request->note;
                 $attend->mobile_no = $request->mobile_no;
                 $attend->latitude = $request->latitude;
                 $attend->longitude = $request->longitude;
                 $attend->created_by = auth()->user()->id;*/
                // if ($attend->save())
                return response()->json(['status' => true, 'status_code' => 401, 'message' => 'تم تسجيل دخول سابق خلال اليوم']);

                //  return response()->json(['status' => false, 'status_code' => 401, 'message' => 'لم تتم العملية بنجاح', 'data' => $request]);
            } else {

                $attend = new AttendanceSheet();
                $attend->user_id = $request->idno;
                $attend->action_type_id = $action_type_id;
                $attend->action_date = $currentdate->format('Y-m-d');
                $attend->start_time = $currentdate->format('H:i');
                //  $attend->note = $request->note;
                $attend->mobile_no = $request->mobile_no;
               // $attend->latitude = $request->latitude;
             //   $attend->longitude = $request->longitude;
                $attend->created_by = $request->idno;

                if ($attend->save())
                    return response()->json(['status' => true, 'status_code' => 200, 'message' => 'تمت العملية بنجاح']);
            }
            return response()->json(['status' => false, 'status_code' => 401, 'message' => 'لم تتم العملية بنجاح']);


        }
        else if ($request->action_type == 2)  // Check Out خروج
        {
            $attend = AttendanceSheet::where('user_id', $request->idno)
                ->whereDate('action_date', $currentdate->format('Y-m-d'))
                ->where('action_type_id', 1)
                ->whereNotNull('start_time')
                ->first();
            if (isset($attend)) {

                $attend->end_time = $currentdate->format('H:i');
         //       $attend->latitude = $request->latitude;
         //       $attend->longitude = $request->longitude;

                if ($attend->save())
                    return response()->json(['status' => true, 'status_code' => 200, 'message' => 'تمت العملية بنجاح']);

                return response()->json(['status' => false, 'status_code' => 401, 'message' => 'لم تتم العملية بنجاح']);
            } else
                return response()->json(['status' => false, 'status_code' => 401, 'message' => 'عذرا! لا يوجد دخول للموظف في هذا التاريخ']);


        }
        else if ($request->action_type == 3)        //خروج لمهمة عمل
        {

            $attend = AttendanceSheet::where('user_id', $request->idno)
                ->whereDate('action_date', $currentdate->format('Y-m-d'))
                ->where('action_type_id',$action_type_id)
                ->whereNull('end_time')
                ->first();

            if (isset($attend)) {
                /* $old_note =$attend->note;
                 $attend->start_time = $currentdate->format('H:i');
                 $attend->note = $old_note.'--'.$request->note;
                 $attend->mobile_no = $request->mobile_no;
                 $attend->latitude = $request->latitude;
                 $attend->longitude = $request->longitude;
                 $attend->created_by = $request->idno;*/
                // if ($attend->save())
                return response()->json(['status' => true, 'status_code' => 401, 'message' => 'تم تسجيل  خروج مهمة عمل سابقا بدون تسجيل عودة خلال اليوم']);

                //  return response()->json(['status' => false, 'status_code' => 401, 'message' => 'لم تتم العملية بنجاح', 'data' => $request]);
            } else {

                $attend = new AttendanceSheet();
                $attend->user_id = $request->idno;
                $attend->action_type_id = $action_type_id;
                $attend->action_date = $currentdate->format('Y-m-d');
                $attend->start_time = $currentdate->format('H:i');
                //   $attend->note = $request->note;
                $attend->mobile_no = $request->mobile_no;
           //     $attend->latitude = $request->latitude;
           //     $attend->longitude = $request->longitude;
                $attend->created_by = auth()->user()->id;

                if ($attend->save())
                    return response()->json(['status' => true, 'status_code' => 200, 'message' => 'تمت العملية بنجاح']);
            }
            return response()->json(['status' => false, 'status_code' => 401, 'message' => 'لم تتم العملية بنجاح']);


        }
        else if ($request->action_type == 4)  // عودة من مهمة عمل
        {
            $attend = AttendanceSheet::where('user_id', $request->idno)
                ->whereDate('action_date', $currentdate->format('Y-m-d'))
                ->where('action_type_id',$action_type_id)
                ->whereNotNull('start_time')
                ->whereNull('end_time')
                ->first();
            if (isset($attend)) {

                $attend->end_time = $currentdate->format('H:i');
           //     $attend->latitude = $request->latitude;
            //    $attend->longitude = $request->longitude;

                if ($attend->save())
                    return response()->json(['status' => true, 'status_code' => 200, 'message' => 'تمت العملية بنجاح']);

                return response()->json(['status' => false, 'status_code' => 401, 'message' => 'لم تتم العملية بنجاح']);
            }
            else
                return response()->json(['status' => false, 'status_code' => 401, 'message' => 'عذرا! لا يوجد  مهمة عمل سابقه للموظف في هذا اليوم']);


        }
        else if ($request->action_type == 5)        //خروج لمهمة خاصة
        {

            $attend = AttendanceSheet::where('user_id', $request->idno)
                ->whereDate('action_date', $currentdate->format('Y-m-d'))
                ->where('action_type_id',$action_type_id)
                ->whereNull('end_time')
                ->first();

            if (isset($attend)) {
                /* $old_note =$attend->note;
                 $attend->start_time = $currentdate->format('H:i');
                 $attend->note = $old_note.'--'.$request->note;
                 $attend->mobile_no = $request->mobile_no;
                 $attend->latitude = $request->latitude;
                 $attend->longitude = $request->longitude;
                 $attend->created_by = auth()->user()->id;*/
                // if ($attend->save())
                return response()->json(['status' => true, 'status_code' => 401, 'message' => 'تم تسجيل  خروج مهمة خاصة سابقا بدون تسجيل عودة خلال اليوم']);

                //  return response()->json(['status' => false, 'status_code' => 401, 'message' => 'لم تتم العملية بنجاح', 'data' => $request]);
            } else {

                $attend = new AttendanceSheet();
                $attend->user_id = $request->idno;
                $attend->action_type_id = $action_type_id;
                $attend->action_date = $currentdate->format('Y-m-d');
                $attend->start_time = $currentdate->format('H:i');
                //   $attend->note = $request->note;
                $attend->mobile_no = $request->mobile_no;
              //  $attend->latitude = $request->latitude;
             //   $attend->longitude = $request->longitude;
                $attend->created_by = auth()->user()->id;

                if ($attend->save())
                    return response()->json(['status' => true, 'status_code' => 200, 'message' => 'تمت العملية بنجاح']);
            }
            return response()->json(['status' => false, 'status_code' => 401, 'message' => 'لم تتم العملية بنجاح']);


        }
        else if ($request->action_type == 6)  // عودة من مهمة خاصة
        {
            $attend = AttendanceSheet::where('user_id', $request->idno)
                ->whereDate('action_date', $currentdate->format('Y-m-d'))
                ->where('action_type_id',$action_type_id)
                ->whereNotNull('start_time')
                ->whereNull('end_time')
                ->first();
            if (isset($attend)) {

                $attend->end_time = $currentdate->format('H:i');
              //  $attend->latitude = $request->latitude;
             //   $attend->longitude = $request->longitude;

                if ($attend->save())
                    return response()->json(['status' => true, 'status_code' => 200, 'message' => 'تمت العملية بنجاح']);

                return response()->json(['status' => false, 'status_code' => 401, 'message' => 'لم تتم العملية بنجاح']);
            }
            else
                return response()->json(['status' => false, 'status_code' => 401, 'message' => 'عذرا! لا يوجد  مهمة خاصة سابقه للموظف في هذا اليوم']);


        }

        else {
            return response()->json(['status' => false, 'status_code' => 401, 'message' => 'عذرا! نوع الحركة خاطئ']);
        }

    }
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    /*   public function getAttendByDate(Request $request)
       {
           $attend_date=$request->attend_date;
           $attendance=AttendanceSheet::where('date',$attend_date);

       }*/
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
