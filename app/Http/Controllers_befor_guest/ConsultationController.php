<?php

namespace App\Http\Controllers;

use App\ShareConsultation;
use App\ShareConsultationDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ConsultationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $painFile_id=$request->painFile_id;
        $consultation = new ShareConsultation();
        $consultation->pain_file_id = $painFile_id;
        $consultation->consultation_title = $request->consultation_title;
        $consultation->consultation_detail = $request->consultation_detail;
        $consultation->consultation_date = date('Y-m-d');
        $consultation->created_by = auth()->user()->id;
        $consultation->org_id = auth()->user()->org_id;
        if ($consultation->save())
            $consult_html=$this->getConsultationRequest($painFile_id);
            return response()->json(['success' => true,'consult_html'=>$consult_html]);
        return response()->json(['success' => false,'consult_html'=>'']);
    }

    public function add_Comment(Request $request)
    {
        $painFile_id=$request->painFile_id;
        $consultation = new ShareConsultationDetail();
        $consultation->pain_file_id = $painFile_id;
        $consultation->share_consultations_id = $request->consultations_id;
        $consultation->comment_text= $request->comment;
        $consultation->comment_date= date('Y-m-d');
        $consultation->created_by = auth()->user()->id;
        $consultation->org_id = auth()->user()->org_id;
        if ($consultation->save()){
            $comments_html=$this->getConsultationComments($request->consultations_id);
            $comments_count = ShareConsultationDetail::where('share_consultations_id', $request->consultations_id)->count();
            return response()->json(['success' => true,'comments_html'=>$comments_html,'comments_count'=>$comments_count]);
        }
        return response()->json(['success' => false,'comments_html'=>'']);
    }
    public function getConsultationComments($id)
    {

        $consultations = ShareConsultationDetail::where('share_consultations_id', $id)->get();

        $html = '';
        foreach ($consultations as $row) {
            $html .= '<li class="list-group-item bg-grey-steel">
                                                <ul class="list-inline">
                                                    <li>
                                                        <i class="fa fa-user-md font-green"></i><span class="font-grey-cascade">'.$row->user_name.'</span></li>
                                                    <li>
                                                        <i class="fa fa-calendar font-green"></i><span class="font-grey-cascade">'.Carbon::parse($row->comment_date)->format('d-m-Y').'</span></li>
                                                </ul>
                                                <p>'.$row->comment_text.'</p>
                                            </li>';

        }
        return $html;
    }
    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
