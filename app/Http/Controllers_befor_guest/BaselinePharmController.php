<?php

namespace App\Http\Controllers;

use App\BaselineDoctorConsultation;
use App\BaselinePharmacistConsultation;
use App\BaselineTreatmentChoice;
use App\ItemsBatchMovementsTb;
use App\ItemsBatchTb;
use App\ItemsTb;
use Illuminate\Http\Request;

class BaselinePharmController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        $painFile_id = $request->painFile_id;
        $baseline = BaselinePharmacistConsultation::where('pain_file_id', $painFile_id)->count();
        if ($baseline == 0) {
            $baseline = new BaselinePharmacistConsultation();
            $baseline->pain_file_id = $painFile_id;
            $baseline->visit_date = $request->visit_date_pharmacist;
            $baseline->laboratory_outside_reference = $request->laboratory_outside_reference;
            $baseline->laboratory_specify = $request->laboratory_specify;
            $baseline->interactions = $request->interactions;
            $baseline->which_interactions = $request->which_interactions;
            $baseline->other_considereations = $request->other_considereations;
            $baseline->pharmacist_message = $request->pharmacist_message;
            $baseline->created_by = auth()->user()->id;
            $baseline->org_id = auth()->user()->org_id;
            if ($baseline->save()) {
                return response()->json(['success' => true]);

            }
        } else {
            $baseline = BaselinePharmacistConsultation::where('pain_file_id', $painFile_id)->first();
            $baseline->visit_date = $request->visit_date_pharmacist;
            $baseline->laboratory_outside_reference = $request->laboratory_outside_reference;
            $baseline->laboratory_specify = $request->laboratory_specify;
            $baseline->interactions = $request->interactions;
            $baseline->which_interactions = $request->which_interactions;
            $baseline->other_considereations = $request->other_considereations;
            $baseline->pharmacist_message = $request->pharmacist_message;
            $baseline->created_by = auth()->user()->id;
            $baseline->org_id = auth()->user()->org_id;
            if ($baseline->save()) {
                return response()->json(['success' => true]);

            }
        }
        return response()->json(['success' => false]);

    }
    public function insert_treatment_choice_drugs(Request $request)
    {
        //dd($request->all());
        //  print_r($request->all());
        // echo $request->drug_id;exit;
        $painFile_id = $request->painFile_id;
        $painFile_status = $request->painFile_status;
        $mediction = new BaselineTreatmentChoice();
        $mediction->pain_file_id = $painFile_id;
        $mediction->drug_id = $request->drug_id;
        $mediction->batch_id = $request->batch_id;
        $mediction->drug_price = $request->drug_price;
        $mediction->drug_specify = $request->dosage . 'x' . $request->frequency . 'x' . $request->duration;
        // $mediction->concentration = $request->concentration;//become concentration
        $mediction->dosage = $request->dosage;
        $mediction->frequency = $request->frequency;
        $mediction->duration = $request->duration;
        //  $mediction->quantity =$request->quantity; //intval($request->dosage) * $request->frequency * $request->duration;
        $mediction->quantity =intval($request->dosage) * intval($request->frequency)* intval($request->duration);
        //  $mediction->drug_cost = $request->drug_cost;
        $mediction->drug_cost =$request->drug_price* $mediction->quantity;
        $mediction->drug_comments = $request->drug_comments;
        $mediction->org_id = auth()->user()->org_id;
        $mediction->created_by = auth()->user()->id;

        if ($mediction->save()) {

            $doctor_html = $this->draw_treatment_choice_drugs_doctor($painFile_id, $painFile_status);
            $pharm_html = $this->draw_treatment_choice_drugs_pharm($painFile_id, $painFile_status);

            //*****************************************************************************
            return response()->json(['success' => true, 'pharm_html' => $pharm_html, 'doctor_html' => $doctor_html]);
        }
        return response()->json(['success' => false, 'pharm_html' => '', 'doctor_html' => '']);

    }
    public function insert_treatment_choice_drugs_old(Request $request)
    {
        //   dd($request->all());
        $painFile_id = $request->painFile_id;
        $painFile_status = $request->painFile_status;
        $mediction = new BaselineTreatmentChoice();
        $mediction->pain_file_id = $painFile_id;
        $mediction->drug_id = $request->drug_id;
        $mediction->dosage = $request->dosage;
        $mediction->org_id = auth()->user()->org_id;
        $mediction->created_by = auth()->user()->id;

        if ($mediction->save()) {
            $model = BaselineTreatmentChoice::where('pain_file_id', $painFile_id)->get();
            $doctor_html = $this->draw_treatment_choice_drugs_doctor($painFile_id, $painFile_status);
            $pharm_html = $this->draw_treatment_choice_drugs_pharm($painFile_id, $painFile_status);
            return response()->json(['success' => true, 'pharm_html' => $pharm_html, 'doctor_html' => $doctor_html]);

        }
        return response()->json(['success' => false, 'pharm_html' => '', 'doctor_html' => '']);

    }

    public function delete_treatment_choice_drugs(Request $request)
    {
        $id = $request->id;
        $painFile_id = $request->painFile_id;
        $painFile_status = $request->painFile_status;
        $model = BaselineTreatmentChoice::find($id);
        if (isset($model)) {
            $model->delete();
            $doctor_html = $this->draw_treatment_choice_drugs_doctor($painFile_id, $painFile_status);
            $pharm_html = $this->draw_treatment_choice_drugs_pharm($painFile_id, $painFile_status);
            return response()->json(['success' => true, 'pharm_html' => $pharm_html, 'doctor_html' => $doctor_html]);
        } else {
            return response()->json(['success' => false, 'pharm_html' => '', 'doctor_html' => '']);
        }

    }

    public function update_treatment_choice_drugs_dosage(Request $request)
    {
        $id = $request->id;
        $painFile_id = $request->painFile_id;
        $painFile_status = $request->painFile_status;
        $model = BaselineTreatmentChoice::find($id);
        if (isset($model)) {
            // $model->concentration = $request->concentration;
            $model->dosage = $request->dosage;
            $model->frequency = $request->frequency;
            $model->duration = $request->duration;
            $model->quantity = intval($request->dosage) * intval($request->frequency) * intval($request->duration);
            //  $model->drug_cost = $request->drug_cost;
            $model->drug_cost = $request->drug_price * $model->quantity;
            $model->drug_specify = $request->dosage . 'x' . $request->frequency . 'x' .
                $request->duration . '=' . $request->quantity . ' :Edited by ' . auth()->user()->name;
            $model->save();
            $doctor_html = $this->draw_treatment_choice_drugs_doctor($painFile_id, $painFile_status);
            $pharm_html = $this->draw_treatment_choice_drugs_pharm($painFile_id, $painFile_status);
            return response()->json(['success' => true, 'pharm_html' => $pharm_html, 'doctor_html' => $doctor_html]);
        } else {
            return response()->json(['success' => false, 'pharm_html' => '', 'doctor_html' => '']);
        }

    }

    public function update_treatment_choice_drugs_dosage_old(Request $request)
    {
        $id = $request->id;
        $painFile_id = $request->painFile_id;
        $painFile_status = $request->painFile_status;
        $model = BaselineTreatmentChoice::find($id);
        if (isset($model)) {
            // $model->concentration = $request->concentration;
            $model->dosage = $request->dosage;
            $model->frequency = $request->frequency;
            $model->duration = $request->duration;
            $model->quantity = intval($request->dosage) * $request->frequency * $request->duration;
            $model->drug_specify = $request->dosage . 'x' . $request->frequency . 'x' .
                $request->duration . ':Edited by ' . auth()->user()->name;
            $model->save();
            $doctor_html = $this->draw_treatment_choice_drugs_doctor($painFile_id, $painFile_status);
            $pharm_html = $this->draw_treatment_choice_drugs_pharm($painFile_id, $painFile_status);
            return response()->json(['success' => true, 'pharm_html' => $pharm_html, 'doctor_html' => $doctor_html]);
        } else {
            return response()->json(['success' => false, 'pharm_html' => '', 'doctor_html' => '']);
        }

    }

    public function create()
    {
        //
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

    public function change_drug_order_status_fn(Request $request)
    {

        $painFile_id = $request->painFile_id;
        $painFile_status = $request->painFile_status;
        //    $followup_id = $request->followup_id;
        $drug_ids = $request->drug_array;
        $baseline_id = BaselineDoctorConsultation::where('pain_file_id', $painFile_id)->first()->id;
        /*$updated = BaselineTreatmentChoice::where('pain_file_id', $painFile_id)
            // ->where('followup_id', $followup_id)
            ->whereIn('id', $drug_ids)
            ->update(['order_status' => 1]);*/
        //  dd($updated);
        //  if ($updated) {
        $treatments = BaselineTreatmentChoice::where('pain_file_id', $painFile_id)
            ->whereIn('id', $drug_ids)
            // ->where('order_status', 0)
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

                $batch = ItemsBatchTb::where('id', $treatment->batch_id)->first();//خصم الكمية من الباتش
                $batch->batch_current_quantity -= $treatment->quantity;
                $batch->save();

                //************تسجيل حركة توريد للصنف في جدول حركات الاصناف*****************//

                $itemMovement = new ItemsBatchMovementsTb();
                $itemMovement->item_id = $treatment->drug_id;
                $itemMovement->batch_id = $treatment->batch_id;//كود الباتش من جدول اصناف المخزن الذي تم اضافة
                $itemMovement->batch_move_date = date('Y-m-d');
                $itemMovement->batch_quantity = $treatment->quantity;
                $itemMovement->batch_move_type = 2;
                $itemMovement->batch_operation_no = $baseline_id;
                $itemMovement->batch_operation_type = 2;
                $itemMovement->org_id = auth()->user()->org_id;
                $itemMovement->created_by = auth()->user()->id;
                $itemMovement->save();
            } else {
                $doctor_html = $this->draw_treatment_choice_drugs_doctor($painFile_id, $painFile_status);
                $pharm_html = $this->draw_treatment_choice_drugs_pharm($painFile_id, $painFile_status);
                return response()->json(['success' => false, 'doctor_html' => $doctor_html, 'pharm_html' => $pharm_html,
                    'item_id' => $treatment->drug_id, 'item_name' => $item->item_scientific_name,
                    'treatment_quantity' => $treatment->quantity]);
            }
        }
        $doctor_html = $this->draw_treatment_choice_drugs_doctor($painFile_id, $painFile_status);
        $pharm_html = $this->draw_treatment_choice_drugs_pharm($painFile_id, $painFile_status);
        return response()->json(['success' => true, 'doctor_html' => $doctor_html, 'pharm_html' => $pharm_html]);
    }
}
