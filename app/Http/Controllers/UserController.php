<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function logout()
    {

        auth()->logout();
        return redirect()->back();
    }

    public function login()
    {
        //$this->clear();
        return redirect()->to('login');
    }

    public function index()
    {
        $this->data['sub_menu'] = 'role';
        $this->data['location_title'] = 'Users List';
        $this->data['location_link'] = 'user';
        $this->data['title'] = 'Users';
        $this->data['page_title'] = 'Users List';
        return view(user_vw() . '.view')->with($this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->data['sub_menu'] = 'role';
        $this->data['location_title'] = 'Users List';
        $this->data['location_link'] = 'user';
        $this->data['title'] = 'USers';
        $this->data['page_title'] = 'Add new user ';

        $this->data['roles'] = get_lookups_list(7);
        return view(user_vw() . '.create')->with($this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $user = New User();
        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->isActive = 1;
        $user->user_type_id = $request->get('user_type_id');
        $user->address = $request->get('address');
        $user->mobile = $request->get('mobile');
        $user->user_color = $request->get('user_color');
        $user->daily_capacity = $request->get('daily_capacity');
        $user->org_id = auth()->user()->org_id;
        if ($request->has('password'))
            $user->password = bcrypt($request->get('password'));

        if ($user->save())
            return response()->json(['success' => true]);
        else
            return response()->json(['success' => false]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      
        $id=auth()->user()->id;
        $this->data['sub_menu'] = 'role';
        $this->data['location_title'] = 'Users List';
        $this->data['location_link'] = 'user';
        $this->data['title'] = 'Users';
        $this->data['page_title'] = 'User Profile';
        $this->data['one_user'] = User::find($id);
       
        return view(user_vw() . '.user_profile')->with($this->data);
    }

    public function availabileEmail(Request $request)
    {
        $email = $request->email;
        $count = User::where('email', '=', $email)
            ->where('org_id', '=', auth()->user()->org_id)
            ->count();
        if ($count >= 1)
            return response()->json(['success' => true]);
        else
            return response()->json(['success' => false]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->data['sub_menu'] = 'role';
        $this->data['location_title'] = 'Users List';
        $this->data['location_link'] = 'user';
        $this->data['title'] = 'Users';
        $this->data['page_title'] = 'Update user';
        $this->data['one_user'] = User::find($id);
        //  dd($this->data['one_user']);
        $this->data['roles'] =get_lookups_list(7);
        //dd($user);
        return view(user_vw() . '.edit')->with($this->data);
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
        //dd($request->all());
        $user = User::find($id);
        if($request->has('name'))
        $user->name = $request->get('name');
        if($request->has('email'))
        $user->email = $request->get('email');
        if ($request->has('password'))
            if ($user->password !=$request->get('password')) {
                //dd('yes');
                $user->password = bcrypt($request->get('password'));
            }
        if($request->has('user_type_id'))
           $user->user_type_id = $request->get('user_type_id');
        if($request->has('address'))
        $user->address = $request->get('address');
        if($request->has('mobile'))
        $user->mobile = $request->get('mobile');
        $user->user_color = $request->get('user_color');
        $user->daily_capacity = $request->get('daily_capacity');
        if ($user->save())
            return response()->json(['success' => true]);
        else
            return response()->json(['success' => false]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */

    public function userData()
    {
        $model = User::query();

        $num = 1;
        return datatables()->of($model)
            ->addColumn('num', function () use (&$num) {// user & as reference to store the privies value
                return $num++;
            })


            ->addColumn('job_title', function ($model) {// as foreach ($users as $user)

                return get_lookup_desc($model->user_type_id);
            })

            ->addColumn('address', function ($model) {// as foreach ($users as $user)
                if (isset($model->userEmployee->address))
                    return $model->userEmployee->address;
                return '';
            })
            ->addColumn('active', function ($model) {
                $i = 1;
                $html = '<div class="col-md-10">';
                if ($model->isActive == 1) {
                    $html.= '<div class="col-md-5" ><i style="font-size: 25px !important;" id="i' . $model->id . '" class="fa fa-user font-green" 
							onclick="updateUserstatus(\'' . $model->id . '\')" style="cursor:pointer"></i></div>';

                } else {
                    $html.= '<div class="col-md-5" ><i style="font-size: 25px !important;" id="i' . $model->id . '" class="fa fa-user font-red-sunglo" 
							onclick="updateUserstatus(\'' . $model->id . '\')" style="cursor:pointer"></i></div>';

                }

                $html .= '</div>';
                return $html;

            })
            ->addColumn('action', function ($table) {// as foreach ($users as $user)
                $html = '<div class="col-md-12">';
                $i = 1;
                /* if ($table->isActive == 1) {
                     $html.= '<div class="col-md-5" ><i style="font-size: 25px !important;" id="i' . $table->id . '" class="fa fa-user font-green"
                             onclick="updateUserstatus(\'' . $table->id . '\')" style="cursor:pointer"></i></div>';

                 } else {
                     $html.= '<div class="col-md-5" ><i style="font-size: 25px !important;" id="i' . $table->id . '" class="fa fa-user font-red-sunglo"
                             onclick="updateUserstatus(\'' . $table->id . '\')" style="cursor:pointer"></i></div>';

                 }*/
                $html .= '<div class="col-md-6"><a href="' . url('/user/' . $table->id . '/edit') . '" type="button" class=" btn btn-icon-only yellow"><i class="fa fa-edit"></i></a> 
                </div>';
                $html .= '<div class="col-md-6" ><button type = "button" class="btn btn-icon-only red" onclick = "deleteUser(' . $table->id . ')" ><i class="fa fa-times" ></i ></button ></div >';
                $html .= '</div>';
                return $html;
            })
            ->rawColumns(['action', 'active'])
            ->toJson();
    }

    /* public function destroy($id)
     {
         $user = User::find($id);
         if (isset($user))
             $user->delete();
         return redirect()->back();
     }*/

    public function getEmployee(Request $request)
    {
        $emp_id = $request->id;
        $emp = Employee::find($emp_id);
        return response()->json(['success' => true, 'data' => $emp]);

    }

    public function activateUser(Request $request)
    {

        $id = $request->id;
        $isActive = $request->isactive;
        $user = User::find($id);
        if (isset($user)) {
            $user->isActive = $isActive;
            $user->save();
        }
        return response()->json(['success' => true, 'user' => $user]);
    }

    public function deleteUser(Request $request)
    {
        $id = $request->id;
        $user = User::find($id);
        if (isset($user))
            $user->delete();
        return redirect()->back();
    }
    function clear()
    {

        //  Artisan::call('cache:clear');
        // Artisan::call('config:clear');
        Artisan::call('config:cache');
        Artisan::call('route:cache');
        Artisan::call('view:clear');


        return "Cleared!";

    }


}
