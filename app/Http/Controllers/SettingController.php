<?php

namespace App\Http\Controllers;

use App\Diagnostics;
use App\DrugList;
use App\Lookup;
use App\PainLocation;
use App\PcsOption;
use App\PhqOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SettingController extends Controller
{
    public function s2()
    {
        $this->data['sub_menu'] = 'Setting';
        $this->data['location_title'] = 'Setting';
        $this->data['location_link'] = '/setting/view';
        $this->data['title'] = 'Setting';
        $this->data['page_title'] = "System Lookup's";
        $this->data['portlet_title'] = 'New';
        $this->data['lookups_list'] = Lookup::where('parent_id', 0)->get();
        return view(setting_vw() . '.view')->with($this->data);
    }

    public function settingTable()
    {
        $this->data['sub_menu'] = 'Setting';
        $this->data['location_title'] = 'Setting';
        $this->data['location_link'] = '/setting/table';
        $this->data['title'] = 'Setting';
        $this->data['page_title'] = "Table Lookup's";
        $this->data['portlet_title'] = 'New';
        return view(setting_vw() . '.table')->with($this->data);
    }

    public function get_lookup_list(Request $request)
    {
        $withChild = $request->child;
        if ($withChild == 0)
            $lookups = Lookup::where('parent_id', 0)->get();
        else
            $lookups = Lookup::all();
        $html = '';
        foreach ($lookups as $raw) {
            $html .= '<option value="' . $raw->id . '">' . $raw->id . '-' . $raw->lookup_details . '</option>';

        }
        return response()->json(['success' => true, 'html' => $html]);//return redirect()->to(role_vw() . '/');//
    }


    public function s2_save(Request $request)
    {
        // dd($request->all());
        $table_id = $request->hdn_table_id;
        $parent_id = isset($request->hdn_parent_id) ? $request->hdn_parent_id : 0;

        if ($table_id == '') {
            // dd($parent_id);
            $table = new Lookup();
            $table->lookup_details = $request->lookup_details;
            $table->parent_id = $parent_id;

        } else {
            $table = Lookup::where('id', $table_id)->where('parent_id', $parent_id)->first();
            $table->lookup_details = $request->lookup_details;
        }
        if ($table->save()) {
            return response()->json(['success' => true, 'parent_id' => $parent_id]);//return redirect()->to(role_vw() . '/');//

        } else
            return response()->json(['success' => false]);

    }

    public function s2_detials_data(Request $request)
    {
        $id = $request->table_id;

        $table = Lookup::where('parent_id', $id);

        $num = 1;
        return datatables()->of($table)
            ->addColumn('num', function () use (&$num) {// user & as reference to store the privies value
                return $num++;
            })
            ->addColumn('action', function ($table) {// as foreach ($users as $user)
                $html= '
                <div class="col-md-12">
                <div class="col-md-2">
                <a  data-toggle="modal" href="#masterModal" onclick="fillForm(' . $table->id . ',\'' . $table->lookup_details . '\')" type="button" class=" btn btn-icon-only green"><i class="fa fa-edit"></i></a> 
                </div><div class="col-md-2">
                <button type="button" class="btn btn-icon-only red" onclick="lookupDelete(' . $table->id . ')"><i class="fa fa-times"></i></button></div>
                <div class="col-md-2">';
                if($table->isActive==1)
                    $colorClass='yellow-lemon';
                else
                    $colorClass='grey-silver';
                $html.='<button id="btnactive'.$table->id.'" onclick="activeLookup(' . $table->id .',6'.')" type="button" class=" btn btn-icon-only  '.$colorClass.'"><i class="fa fa-circle-o-notch"></i></button>
                 
                </div>
                </div>';
                return $html;
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function getTableData(Request $request)
    {
        $table_id = $request->table_id;

        if ($table_id == 1)
            $table = DrugList::query();
        else if ($table_id == 2)
            $table = Diagnostics::query();
        else if ($table_id == 3)
            $table = PainLocation::query();
        else if ($table_id == 4)
            $table = PcsOption::select('option as name', 'id','isActive')->get();
        else if ($table_id == 5)
            $table = PhqOption::select('option as name', 'id','isActive')->get();
        else
            return response()->json(['success' => false]);

        $num = 1;
        return datatables()->of($table)
            ->addColumn('num', function () use (&$num) {// user & as reference to store the privies value
                return $num++;
            })
            //   ->addColumn('action', function ($table) {// as foreach ($users as $user)
            ->addColumn('action', function ($table) use ($table_id) {
                $html='
                <div class="col-md-12">
                <div class="col-md-2">
                <a  data-toggle="modal" href="#masterModal" onclick="fillForm(' . $table->id . ',' . $table_id . ',\'' . $table->name . '\')" type="button" class=" btn btn-icon-only green"><i class="fa fa-edit"></i></a> 
                </div><div class="col-md-2">
                <button type="button" class="btn btn-icon-only red" onclick="settingDelete(' . $table->id . ','.$table_id.')"><i class="fa fa-times"></i></button></div><div class="col-md-2">';
                if($table->isActive==1)
                    $colorClass='yellow-lemon';
                else
                    $colorClass='grey-silver';
                $html.='<button id="btnactive'.$table->id.'" onclick="activeLookup(' . $table->id .','.$table_id.')" type="button" class=" btn btn-icon-only  '.$colorClass.'"><i class="fa fa-circle-o-notch"></i></button>
                </div></div>';
                return $html;

            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function changeItemStatus(Request $request)
    {
        //dd($request->all());
        $id = $request->id;
        $table_id = $request->table_id;
        if($table_id==1) {
            $table = DrugList::find($id);
            if ($table)
                if ($table->isActive == 0)
                    $table->isActive = 1;
                else
                    $table->isActive = 0;
        }
        if($table_id==2) {
            $table = Diagnostics::find($id);
            if ($table)
                if ($table->isActive == 0)
                    $table->isActive = 1;
                else
                    $table->isActive = 0;
        }
        if($table_id==3) {
            $table = PainLocation::find($id);
            if ($table)
                if ($table->isActive == 0)
                    $table->isActive = 1;
                else
                    $table->isActive = 0;
        }
        if($table_id==4) {
            $table = PcsOption::find($id);
            if ($table)
                if ($table->isActive == 0)
                    $table->isActive = 1;
                else
                    $table->isActive = 0;
        }
        if($table_id==5) {
            $table = PhqOption::find($id);
            if ($table)
                if ($table->isActive == 0)
                    $table->isActive = 1;
                else
                    $table->isActive = 0;
        }
        else if($table_id==6) {
            $table = Lookup::find($id);
            if ($table)
                if ($table->isActive == 0)
                    $table->isActive = 1;
                else
                    $table->isActive = 0;
        }
        if ($table->save()) {
            return response()->json(['success' => true]);
        }


    }
    public function s2_delete(Request $request)
    {
        //dd($request->all());
        $id = $request->id;
        $table = Lookup::find($id);
        if ($table)
            if ($table->delete()) {
                $dtable = Lookup::where('id', $id)->delete();
                return response()->json(['success' => true]);
            }

    }

    public function sidenerve()
    {
        $this->data['sub_menu'] = 'Setting';
        $this->data['location_title'] = 'Setting';
        $this->data['location_link'] = '/setting/view';
        $this->data['title'] = 'Setting';
        $this->data['page_title'] = "Side's And Nerve's";
        $this->data['portlet_title'] = 'New';
        return view(setting_vw() . '.sidenerve')->with($this->data);
    }

    public function saveTable(Request $request)
    {
        $id = $request->hdn_id;
        $table_id = $request->hdn_table_id;
        if ($table_id == 1) {
            if ($id == '') {
                $model = new  DrugList();
                $model->name = $request->name;
            } else {
                $model = DrugList::find($id);
                $model->name = $request->name;
            }
        }
        else if ($table_id == 2) {
            if ($id == '') {
                $model = new  Diagnostics();
                $model->name = $request->name;
            } else {
                $model = Diagnostics::find($id);
                $model->name = $request->name;
            }
        }
        else if ($table_id == 3) {
            if ($id == '') {
                $model = new  PainLocation();
                $model->name = $request->name;
            } else {
                $model = PainLocation::find($id);
                $model->name = $request->name;
            }
        }
        else if ($table_id == 4) {
            if ($id == '') {
                $model = new  PcsOption();
                $model->option = $request->name;
            } else {
                $model = PcsOption::find($id);
                $model->option = $request->name;
            }
        }
        else if ($table_id == 5) {
            if ($id == '') {
                $model = new  PhqOption();
                $model->option = $request->name;
            } else {
                $model = PhqOption::find($id);
                $model->option = $request->name;
                $model->option = $request->name;
            }
        }

        if ($model->save()) {
            return response()->json(['success' => true,'model'=>$model]);//return redirect()->to(role_vw() . '/');//

        } else
            return response()->json(['success' => false]);

    }

    public function deleteTable(Request $request)
    {
        $id = $request->id;
        $table_id = $request->table_id;
        if ($table_id == 1)
        $model = DrugList::find($id);
        else if ($table_id == 2)

        $model = Diagnostics::find($id);
        else if ($table_id == 3)
            $model = PainLocation::find($id);

        else if ($table_id == 4)
        $model = PcsOption::find($id);

        else if ($table_id == 5)
        $model = PhqOption::find($id);
        else
            return response()->json(['success' => false]);

        if ($model)
            if ($model->delete())
                return response()->json(['success' => true]);

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
