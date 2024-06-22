<?php

namespace App\Http\Controllers;

use App\FollowupAdverseEffect;
use App\FollowupPharmacist;
use App\FollowupTreatment;
use App\FollowupTreatmentAdverseEffect;
use App\ItemsBatchMovementsTb;
use App\ItemsBatchTb;
use App\ItemsTb;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FollowupPharmController extends Controller
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
    public
    function add_treatment_drug(Request $request)

    {
        //dd($request->all());
        $followup_id = $request->followup_id;
        $painFile_id = $request->painFile_id;
        $painFile_status = $request->painFile_status;
        $mediction = new FollowupTreatment();
        $mediction->pain_file_id = $painFile_id;
        $mediction->followup_id = $followup_id;
        $mediction->drug_id = $request->drug_id;
        $mediction->batch_id = $request->batch_id;
        $mediction->drug_price = $request->drug_price;
        //$mediction->concentration = $request->concentration;
        $mediction->dosage = $request->dosage;
        $mediction->frequency = $request->frequency;
        $mediction->duration = $request->duration;
        $mediction->quantity = intval($request->dosage) * intval($request->frequency) * intval($request->duration);;
        $mediction->drug_cost = $request->drug_price * $mediction->quantity;
        $mediction->drug_specify = $request->dosage . 'x' . $request->frequency . 'x' . $request->duration . '=' . $request->quantity;
        //   $mediction->quantity = $request->dosage * $request->frequency * $request->duration;
        $mediction->org_id = auth()->user()->org_id;
        $mediction->created_by = auth()->user()->id;

        if ($mediction->save()) {
            $doctor_html = $this->draw_followup_treatment_doctor($painFile_id, $followup_id, $painFile_status);
            $pharm_html = $this->draw_followup_treatment_pharm($painFile_id, $followup_id, $painFile_status);
            return response()->json(['success' => true, 'doctor_html' => $doctor_html, 'pharm_html' => $pharm_html]);


        }
        return response()->json(['success' => false, 'doctor_html' => '', 'pharm_html' => '']);

    }

    public function update_pharm_treatment(Request $request)
    {
        // dd($request->all());
        $id = $request->id;
        $painFile_id = $request->painFile_id;
        $painFile_status = $request->painFile_status;
        $followup_id = $request->followup_id;


        $compliance = $request->compliance;
        //$adverse_effects = $request->adverse_effects;
        $decision = $request->decision;
        $drug_comments = $request->drug_comments;

        $folowupTreatment = FollowupTreatment::find($id);
        //     $folowupTreatment->concentration = $concentration;

        $folowupTreatment->drug_price = $request->drug_price;
        $folowupTreatment->frequency = $request->frequency;
        $folowupTreatment->duration = $request->duration;
        $folowupTreatment->quantity = intval($request->dosage) * intval($request->frequency) * intval($request->duration);;
        $folowupTreatment->drug_cost = $request->drug_price * $folowupTreatment->quantity;//total cost drug_price*quantity
        $folowupTreatment->compliance = $compliance;
        // $folowupTreatment->adverse_effects = $adverse_effects;
        $folowupTreatment->decision = $decision;
        if ($folowupTreatment->dosage != $request->dosage)
            $folowupTreatment->decision = 117;
        $folowupTreatment->dosage = $request->dosage;
        // if ($decision == 117)
        //   $folowupTreatment->dosage = $dosage;
        $folowupTreatment->drug_specify = $request->dosage . 'x' . $request->frequency . 'x' . $request->duration . '=' . $request->quantity . ':Edited by ' . auth()->user()->name;
        $folowupTreatment->drug_comments = $drug_comments;
        if ($folowupTreatment->save()) {
            $adverseffect = FollowupTreatmentAdverseEffect::where('followup_treatment_id', $folowupTreatment->id)->delete();
            $adverse_effects = $request->get('adverse_effects');
            if (isset($adverse_effects))
                foreach ($adverse_effects as $option => $value) {
                    $adverseffect = new FollowupTreatmentAdverseEffect();
                    $adverseffect->adverse_effects = $value;
                    $adverseffect->followup_treatment_id = $folowupTreatment->id;
                    $adverseffect->created_by = auth()->user()->id;
                    $adverseffect->org_id = auth()->user()->org_id;
                    $adverseffect->save();
                }

            $doctor_html = $this->draw_followup_treatment_doctor($painFile_id, $followup_id, $painFile_status);
            $pharm_html = $this->draw_followup_treatment_pharm($painFile_id, $followup_id, $painFile_status);
            return response()->json(['success' => true, 'doctor_html' => $doctor_html, 'pharm_html' => $pharm_html]);
        }
        return response()->json(['success' => false]);

    }

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
        //  dd($request->all());
        $painFile_id = $request->painFile_id;
        $followup = FollowupPharmacist::where('pain_file_id', $painFile_id)->where('followup_id', $request->followup_id)->count();
        if ($followup == 0) {
            $followup = new FollowupPharmacist();
            $followup->pain_file_id = $painFile_id;
            $followup->followup_id = $request->followup_id;
            $followup->follow_up_date = $request->pharm_follow_up_date;
            $followup->specify_other_adverse_effects = $request->specify_other_adverse_effects;
            $followup->specify_other_changes = $request->pharm_specify_other_changes;
            $followup->pharm_message = $request->pharm_message;
            $followup->created_by = auth()->user()->id;
            $followup->org_id = auth()->user()->org_id;
            if ($followup->save())
                $adverseffect = FollowupAdverseEffect::where('pain_file_id', $painFile_id)
                    ->where('followup_id', $request->followup_id)->delete();
            $adverse_effects = $request->get('adverse_effects');
            if (isset($adverse_effects))
                foreach ($adverse_effects as $option => $value) {
                    $adverseffect = new FollowupAdverseEffect();
                    $adverseffect->adverse_effects = $value;
                    $adverseffect->pain_file_id = $painFile_id;
                    $adverseffect->followup_id = $request->followup_id;
                    $adverseffect->created_by = auth()->user()->id;
                    $adverseffect->org_id = auth()->user()->org_id;
                    $adverseffect->save();
                }
            return response()->json(['success' => true]);

        } else {
            $followup = FollowupPharmacist::where('pain_file_id', $painFile_id)->where('followup_id', $request->followup_id)->first();
            $followup->specify_other_adverse_effects = $request->specify_other_adverse_effects;
            $followup->specify_other_changes = $request->pharm_specify_other_changes;
            $followup->pharm_message = $request->pharm_message;
            if ($followup->save())
                $adverseffect = FollowupAdverseEffect::where('pain_file_id', $painFile_id)
                    ->where('followup_id', $request->followup_id)->delete();
            $adverse_effects = $request->get('adverse_effects');
            if (isset($adverse_effects))
                foreach ($adverse_effects as $option => $value) {
                    $adverseffect = new FollowupAdverseEffect();
                    $adverseffect->adverse_effects = $value;
                    $adverseffect->pain_file_id = $painFile_id;
                    $adverseffect->followup_id = $request->followup_id;
                    $adverseffect->created_by = auth()->user()->id;
                    $adverseffect->org_id = auth()->user()->org_id;
                    $adverseffect->save();
                }
            return response()->json(['success' => true]);
        }
    }

    public function get_followup_pharm(Request $request)
    {
        //dd($request->all());
        $painFile_id = $request->painFile_id;
        $painFile_status = $request->painFile_status;
        $followupPharm = FollowupPharmacist::where('followup_id', $request->followup_id)->first();
        $Pharm_treatmentFollowup_data = $this->draw_followup_treatment_pharm($painFile_id, $request->followup_id, $painFile_status);
        $Pharm_adverse_effects = FollowupAdverseEffect::where('pain_file_id', $painFile_id)
            ->where('followup_id', $request->followup_id)->pluck('adverse_effects')->toArray();
        //  dd($Pharm_adverse_effects);
        return response()->json(['success' => true, 'followupPharm' => $followupPharm, 'Pharm_treatmentFollowup_data' => $Pharm_treatmentFollowup_data, 'Pharm_adverse_effects' => $Pharm_adverse_effects]);
    }

    /* public function getFollowup_PharmTreatment($painFile_id, $last_follow_id)
     {
         $model = FollowupTreatment::where('pain_file_id', session()->get('painFile_id'))
             ->where('followup_id', $last_follow_id)->get();
         $compliance_list = get_lookups_list(88);
         $adverse_effects = get_lookups_list(92);
         $decision = get_lookups_list(115);
         $html = '';
         $i = 1;
         foreach ($model as $raw) {
             $html .= '<tr><td>' . $i++ . '</td>';
             $html .= '<td>' . get_drug_desc($raw->drug_id) . '</td>';
             $html .= '<td>' . $raw->drug_specify . '</td>';
             $html .= '<td><input class="form-control input-xsmall input-sm" value="' . $raw->dosage . '" name="pharm_treat_dosage' . $raw->id . '" id="pharm_treat_dosage' . $raw->id . '"></td>';
             $html .= '<td>';
             $html .= '<select class="form-control input-small input-sm" name="pharm_treat_compliance' . $raw->id . '" id="pharm_treat_compliance' . $raw->id . '">';
             $html .= '<option value=""> Select... </option>';
             foreach ($compliance_list as $list) {
                 $selected = '';
                 if ($raw->compliance == $list->id)
                     $selected = 'selected="selected"';
                 $html .= '<option value="' . $list->id . '"  ' . $selected . '>' . $list->lookup_details . '</option>';
             }
             $html .= '</select></td>';
             $html .= '<td>';
             $html .= '<select class="form-control  input-small input-sm select2-multiple" multiple name="pharm_treat_adverse_effects[]' . $raw->id . '" id="pharm_treat_adverse_effects' . $raw->id . '">';

             foreach ($adverse_effects as $list) {
                 $selected = '';
                 if ($raw->adverse_effects == $list->id)
                     $selected = 'selected="selected"';
                 $html .= '<option value="' . $list->id . '"  ' . $selected . '>' . $list->lookup_details . '</option>';
             }
             $html .= '</select></td>';
             $html .= '<td>';
             $html .= '<select class="form-control input-small input-sm" name="pharm_treat_decision' . $raw->id . '" id="pharm_treat_decision' . $raw->id . '">';
             $html .= '<option value=""> Select... </option>';
             foreach ($decision as $list) {
                 $selected = '';
                 if ($raw->decision == $list->id)
                     $selected = 'selected="selected"';
                 $html .= '<option value="' . $list->id . '"  ' . $selected . '>' . $list->lookup_details . '</option>';
             }
             $html .= '</select></td>';
             if (session()->get('painFile_status') == 17)
                 $html .= '<td><span class="input-group-btn"><button class="btn btn-success" type="button" onclick="update_pharm_treatment(' . $raw->id . ')"><i class="fa fa-check fa-fw"></i></button></span></td>';
             else
                 $html .= '<td></td>';
             $html .= '</tr>';

         }
         return $html;

     }*/

    public function check_available_date(Request $request)
    {
        $followup_date = $request->followup_date;
        $followup_id = $request->followup_id;
        $count = FollowupPharmacist::whereDate('follow_up_date', $followup_date)->where('followup_id', '!=', $followup_id)->count();
        if ($count > 0)
            return response()->json(['success' => false, 'count' => $count]);
        else
            return response()->json(['success' => true, 'count' => $count]);
    }

    public function change_drug_order_status_fn(Request $request)
    {
        /*
         * في حالة لو تم ادخال باتش في زيارة البيز لين
        عند تسجيل زيارة جديدة سيتم نسخ كل الادوية من ضمنها الباتش القديم الذي قد يكون انتهى من المخزن
        ايضا في حالة تسجيل زيارة جديدة ,ستتكرر نفس المشكله

         */
        $painFile_id = $request->painFile_id;
        $painFile_status = $request->painFile_status;
        $followup_id = $request->followup_id;
        $drug_ids = $request->drug_array;
        /* $updated = FollowupTreatment::where('pain_file_id', $painFile_id)
             ->where('followup_id', $followup_id)
             ->whereIn('id', $drug_ids)
             ->update(['order_status' => 1]);*/
        //  dd($updated);
        /*  if ($updated) {*/
        $treatments = FollowupTreatment::where('pain_file_id', $painFile_id)
            ->whereIn('id', $drug_ids)
            ->where('order_status', 0)
            ->get();

        foreach ($treatments as $treatment) {

//******************************اول خطوة هي جلب رقم الباتش الاكتف حتى يتم اجراء العمليات عليه***//
            $item = ItemsTb::where('id', $treatment->drug_id)->first();
            $batch = ItemsBatchTb::where('item_id', $treatment->drug_id)
                ->where('isActive', 1)
                ->where('batch_current_quantity', '>=', $treatment->quantity)->first();//للتاكد من ان الباتش موجود وفعال وبه رصيد كافي للصرف


            if (isset($batch->id)) {
                //*****هنا يتم تعديل رقم الباتش للباتش الاكتف**/
                $treatment->order_status = 1;
                $treatment->batch_id = $batch->id;
                $treatment->drug_price=$batch->batch_piece_price ;
                $treatment->drug_cost=$batch->batch_piece_price*$treatment->quantity;
                $treatment->save();
                //**********************خصم الكمية لرصيد الصنف الاساسي***************//
                $itemStor = ItemsTb::where('id', $treatment->drug_id)->first();
                $itemStor->item_quantity -= $treatment->quantity;//خصم الكمية لرصيد الصنف الاساسي
                $itemStor->save();
                //**********************خصم الكمية لرصيد من الباتش***************//
                $batch = ItemsBatchTb::where('id', $treatment->batch_id)->first();//خصم الكمية من الباتش
                $batch->batch_current_quantity -= $treatment->quantity;
                $batch->save();

                //************تسجيل حركة صرف للصنف في جدول حركات الاصناف*****************//

                $itemMovement = new ItemsBatchMovementsTb();
                $itemMovement->item_id = $treatment->drug_id;
                $itemMovement->batch_id = $treatment->batch_id;//كود الباتش من جدول اصناف المخزن الذي تم اضافة
                $itemMovement->batch_move_date = date('Y-m-d');
                $itemMovement->batch_quantity = $treatment->quantity;
                $itemMovement->batch_move_type = 2;
                $itemMovement->batch_operation_no = $followup_id;
                $itemMovement->batch_operation_type = 3;
                $itemMovement->org_id = auth()->user()->org_id;
                $itemMovement->created_by = auth()->user()->id;
                $itemMovement->save();
            } else {
                $doctor_html = $this->draw_followup_treatment_doctor($painFile_id, $followup_id, $painFile_status);
                $pharm_html = $this->draw_followup_treatment_pharm($painFile_id, $followup_id, $painFile_status);
                return response()->json(['success' => false, 'doctor_html' => $doctor_html, 'pharm_html' => $pharm_html,
                    'item_id' => $treatment->drug_id, 'item_name' => $item->item_scientific_name,
                    'treatment_quantity' => $treatment->quantity]);
            }
        }
        $doctor_html = $this->draw_followup_treatment_doctor($painFile_id, $followup_id, $painFile_status);
        $pharm_html = $this->draw_followup_treatment_pharm($painFile_id, $followup_id, $painFile_status);
        return response()->json(['success' => true, 'doctor_html' => $doctor_html, 'pharm_html' => $pharm_html]);
        /* } else {
             return response()->json(['success' => false]);
         }*/
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
