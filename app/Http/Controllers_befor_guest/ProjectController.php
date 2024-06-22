<?php

namespace App\Http\Controllers;

use App\PainFileProject;
use App\PainFileProjectFollowup;
use App\PainFileProjectSymptom;
use App\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->data['sub_menu'] = 'Project';
        $this->data['location_title'] = 'Projects List';
        $this->data['location_link'] = 'project';
        $this->data['title'] = 'Projects';
        $this->data['page_title'] = 'Projects List';
        return view(project_vw() . '.view')->with($this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->data['sub_menu'] = 'Project';
        $this->data['location_title'] = 'Projects List';
        $this->data['location_link'] = 'project';
        $this->data['title'] = 'Projects';
        $this->data['page_title'] = 'Add new project ';
        $this->data['consequence_list'] = get_lookups_list(366);
        return view(project_vw() . '.create')->with($this->data);
    }

    public function projectData()

    {
        $model = Project::query();

        $num = 1;
        return datatables()->of($model)
            ->addColumn('num', function () use (&$num) {// user & as reference to store the privies value
                return $num++;
            })
            ->addColumn('consequence_desc', function ($model) {// as foreach ($users as $user)

                return get_lookup_desc($model->consequence);
            })
            ->addColumn('action', function ($table) {// as foreach ($users as $user)
                $html = '<div class="col-md-12">';
                $i = 1;
                $html .= '<div class="col-md-4"><a href="' . url('/project/' . $table->id . '/edit') . '" type="button" class=" btn btn-icon-only yellow"><i class="fa fa-edit"></i></a> 
                </div>';
                $html .= '<div class="col-md-4" ><a data-toggle="modal" href="#patients_Modal" title="Click to see patients list" class="btn btn-icon-only green" onclick = "get_patient_list(' . $table->id . ')" ><i class="fa fa-user" ></i ></a ></div >';
                $html .= '<div class="col-md-4" ><button type = "button" class="btn btn-icon-only red" onclick = "deleteProject(' . $table->id . ')" ><i class="fa fa-times" ></i ></button ></div >';
                $html .= '</div>';
                return $html;
            })
            ->rawColumns(['action', 'active', 'consequence_desc'])
            ->toJson();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $project = new Project();
        $project->project_name = $request->get('project_name');
        $project->question = $request->get('question');
        $project->conclusion = $request->get('conclusion');
        $project->consequence = $request->get('consequence');
        $project->consequence_detail = $request->get('consequence_detail');
        //  $project->symptoms = $request->get('symptoms');
        $project->org_id = auth()->user()->org_id;
        $project->created_by = auth()->user()->id;
        if ($project->save())
            return response()->json(['success' => true]);
        else
            return response()->json(['success' => false]);
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
        $this->data['sub_menu'] = 'Project';
        $this->data['location_title'] = 'Projects List';
        $this->data['location_link'] = 'project';
        $this->data['title'] = 'Projects';
        $this->data['page_title'] = 'Edit project ';
        $this->data['consequence_list'] = get_lookups_list(366);
        $this->data['one_project'] = Project::find($id);
        return view(project_vw() . '.edit')->with($this->data);
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

        $project = Project::find($id);
        $project->project_name = $request->get('project_name');
        $project->question = $request->get('question');
        $project->conclusion = $request->get('conclusion');
        $project->consequence = $request->get('consequence');
        $project->consequence_detail = $request->get('consequence_detail');
        //  $project->symptoms = $request->get('symptoms');
        if ($project->save())
            return response()->json(['success' => true]);
        else
            return response()->json(['success' => false]);
    }

    public function addPatientProject(Request $request)
    {
        $patientProject = new PainFileProject();
        $patientProject->pain_file_id = $request->painFile_id;
        $patientProject->project_id = $request->project_id;
        $patientProject->start_date = date('Y-m-d');
        $patientProject->org_id = auth()->user()->org_id;
        $patientProject->created_by = auth()->user()->id;
        if ($patientProject->save()) {
            $html = $this->draw_patient_project_table($request->painFile_id);
            return response()->json(['success' => true, 'patient_project_html' => $html]);
        } else
            return response()->json(['success' => false, 'patient_project_html' => '']);
    }

    public function stopPatientProject(Request $request)
    {
        $id = $request->project_id;
        $project = PainFileProject::find($id);
        if (isset($project))
            if ($project->end_date == '') {

                $project->end_date = date('Y-m-d');
               // $project->patient_score = $request->project_score;
                $project->end_by = auth()->user()->id;
                if ($project->save()) {
                    $html = $this->draw_patient_project_table($request->painFile_id);
                    return response()->json(['success' => true, 'patient_project_html' => $html]);
                } else
                    return response()->json(['success' => false, 'patient_project_html' => '']);
            }
        return response()->json(['success' => false, 'patient_project_html' => '']);
    }

    public function addPatientSymptoms(Request $request)
    {
        //dd($request->all());
        $project = new PainFileProjectFollowup();
        $project->pain_file_id =  $request->painFile_id;
        $project->project_id =  $request->project_id;
        $project->symptoms = $request->symptoms;
        $project->org_id = auth()->user()->org_id;
        $project->created_by = auth()->user()->id;
        if ($project->save()) {
            $html = $this->draw_patient_project_followup_table($request->painFile_id);
            return response()->json(['success' => true, 'patient_project_followup_html' => $html]);
        } else
            return response()->json(['success' => false, 'patient_project_followup_html' => '']);

        return response()->json(['success' => false, 'patient_project_followup_html' => '']);
    }

    public function getProject(Request $request)
    {
        $id = $request->project_id;
        $data = [];
        $project = Project::find($id);

        if (isset($project))
            $data = [
                'project_name' => $project->project_name,
                'question' => $project->question,
                'conclusion' => $project->conclusion,
                'consequence' => get_lookup_desc($project->consequence),
                'consequence_detail' => $project->consequence_detail,
                //  'symptoms' => $project->symptoms,
            ];

        return response()->json(['success' => true, 'project_data' => $data]);
    }
    public function get_project_patient_list(Request $request)
    {
        $id = $request->project_id;
        $model= PainFileProjectSymptom::where('project_id',$id)->get();




        $num = 1;
        return datatables()->of($model)
            ->addColumn('num', function () use (&$num) {// user & as reference to store the privies value
                return $num++;
            })
            ->addColumn('gender_desc', function ($model) {// as foreach ($users as $user)
                $gender_list = ['1' => 'Male', '2' => 'Female'];
                return $gender_list[$model->gender];
            })

           ->addColumn('patient_name', function ($model) {// as foreach ($users as $user)
                $html = ' <a onclick="viewPainFile(' . $model->id . ',' . $model->patient_id . ',' . $model->status . ')" href="#">
                                ' . $model->name . '
                            </a>';

                return $html;
            })
            ->addColumn('patient_name_a', function ($model) {// as foreach ($users as $user)
                $html = ' <a onclick="viewPainFile(' . $model->id . ',' . $model->patient_id . ',' . $model->status . ')" href="#">
                                ' . $model->name_a . '
                            </a>';

                return $html;
            })
            ->rawColumns(['patient_name', 'patient_name_a'])
            ->toJson();
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function delete_project(Request $request)
    {
        $res = Project::destroy($request->id);
        if ($res)
            return response()->json(['success' => true]);
        return response()->json(['success' => false]);
    }

    public function destroy($id)
    {
        //
    }
}
