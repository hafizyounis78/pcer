<?php

namespace App\Http\Controllers;

use App\Alert;
use App\BaselineTreatmentChoice;
use App\BaselineTreatmentGoal;
use App\CurrentMedication;
use App\FollowupTreatment;
use App\FollowupTreatmentAdverseEffect;
use App\FollowupTreatmentGoal;
use App\ItemsBatchTb;
use App\MessageCenter;

//use App\PainFileProjectFollowupVw;
use App\PainFileProjectVw;
use App\PainFileProject;

use App\ProjectFollowupDoctor;
use App\ProjectFollowupPharm;
use App\QutenzaScore;
use App\QutenzaTreatment;
use App\ShareConsultation;
use App\ShareConsultationDetail;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /* protected $one_patient;
     protected $one_painFile;
     protected $painFile_status;
     protected $districts;
     public function __construct()
     {
         //dd(request()->segment(3));
         $painFile_id=request()->segment(3);
         $patientid =request()->segment(4);
         $this->painFile_status =request()->segment(5);
         $this->one_patient = Patient::find($patientid);
         $this->one_painFile = PainFile::find($painFile_id);
         $this->districts=get_lookups_list(1);
     }*/
    public function draw_followup_treat_nurse_goal($painFile_id, $last_follow_id, $painFile_status)
    {
        $model = FollowupTreatmentGoal::where('pain_file_id', $painFile_id)
            ->where('followup_id', $last_follow_id)->get();
        $compliance_list = get_lookups_list(88);
        $physical_treatment_list = get_lookups_list(83);

        $html = '';
        $i = 1;
        foreach ($model as $raw) {
            $html .= '<tr><td>' . $i++ . '</td>';
            $html .= '<td>' . $raw->goal_text . '</td>';
            $html .= '<td><div class="input-group">';
            $html .= '<input id="nurse_followup_score' . $raw->id . '"  value="' . $raw->followup_score . '" class="form-control" type="number" name="nurse_followup_score' . $raw->id . '" placeholder="score" min="0" max="10"></div></td>';
            $html .= '<td><div class="input-group">';
            $html .= '<select class="form-control" id="nurse_followup_compliance' . $raw->id . '" name="nurse_followup_compliance' . $raw->id . '">';
            $html .= '<option value=""> Select... </option>';
            foreach ($compliance_list as $list) {
                $selected = '';
                if ($raw->followup_compliance == $list->id)
                    $selected = 'selected="selected"';
                $html .= '<option value="' . $list->id . '"  ' . $selected . '>' . $list->lookup_details . '</option>';
            }
            $html .= '</select></div ></td>';
            $html .= '<td><div class="input-group">';
            $html .= '<select class="form-control" id="nurse_physical_treatment' . $raw->id . '" name="nurse_physical_treatment' . $raw->id . '">';
            $html .= '<option value=""> Select... </option>';
            foreach ($physical_treatment_list as $list) {
                $selected = '';
                if ($raw->physical_treatment == $list->id)
                    $selected = 'selected="selected"';
                $html .= '<option value="' . $list->id . '"  ' . $selected . '>' . $list->lookup_details . '</option>';
            }
            $html .= '</select></div ></td>';
            $html .= '<td><div class="input-group">';
            $html .= '<input id="nurse_days_on_prg' . $raw->id . '"  value="' . $raw->days_on_prg . '" class="form-control" type="number" name="nurse_days_on_prg' . $raw->id . '" placeholder="score" min="0" max="200"></div></td>';
            if ($painFile_status == 17 && auth()->user()->id != 100) {
                $html .= '<td><span class="input-group-btn" >';
                $html .= '<button  class="btn btn-success btn-icon-only" type = "button" onclick="saveFollowupNurseGoal(' . $raw->id . ');"><i class="fa fa-check fa-fw" ></i ></button></span></td >';
            } else
                $html .= '<td>';
            $html .= '</tr > ';


        }
        return $html;
    }

    public function draw_followup_treat_doc_goal($painFile_id, $last_follow_id, $painFile_status)
    {
        $model = FollowupTreatmentGoal::where('pain_file_id', $painFile_id)
            ->where('followup_id', $last_follow_id)->get();
        $compliance_list = get_lookups_list(88);

        $html = '';
        $i = 1;
        foreach ($model as $raw) {
            $html .= '<tr><td>' . $i++ . '</td>';
            $html .= '<td>' . $raw->goal_text . '</td>';
            $html .= '<td><div class="input-group">';
            $html .= '<input id="doc_followup_score' . $raw->id . '"  value="' . $raw->followup_score . '" class="form-control" type="number" name="doc_followup_score' . $raw->id . '" placeholder="score" min="0" max="10">';
            if ($painFile_status == 17 && auth()->user()->id != 100)
                $html .= '<span class="input-group-btn"><button id="saveFscore" class="btn btn-success btn-icon-only" type="button" onclick="saveFollowupscore(' . $raw->id . ');"><i class="fa fa-check fa-fw"></i></button></span>';

            $html .= '</div></td>';
            $html .= '<td><div class="input-group">';
            $html .= '<select class="form-control" id="doc_followup_compliance' . $raw->id . '" name="doc_followup_compliance' . $raw->id . '">';
            $html .= '<option value=""> Select... </option>';
            foreach ($compliance_list as $list) {
                $selected = '';
                if ($raw->followup_compliance == $list->id)
                    $selected = 'selected="selected"';
                $html .= '<option value="' . $list->id . '"  ' . $selected . '>' . $list->lookup_details . '</option>';
            }
            $html .= '</select>';
            if ($painFile_status == 17 && auth()->user()->id != 100)
                $html .= '<span class="input-group-btn" ><button id = "saveFcompliance" class="btn btn-success btn-icon-only" type = "button" onclick="saveFollowupcompliance(' . $raw->id . ');"><i class="fa fa-check fa-fw" ></i ></button></span>';

            $html .= '</div ></td >';
            $html .= '</tr > ';


        }
        return $html;
    }

    public function draw_treatment_goal_nurse($painFile_id, $painFile_status)
    {
        $model = BaselineTreatmentGoal::where('pain_file_id', $painFile_id)->get();
        $html = '';
        $i = 1;
        foreach ($model as $raw) {
            $html .= '<tr><td>' . $i++ . '</td>';
            $html .= '<td><input type="text" class="form-control" id="baseline_goal' . $raw->id . '" name="baseline_goal' . $raw->id . '"  value="' . $raw->baseline_goal . '" ></td>';
            $html .= '<td><input type="text" class="form-control" id="baseline_goal_score' . $raw->id . '" name="baseline_goal_score' . $raw->id . '"  value="' . $raw->baseline_goal_score . '" ></td>';
            $html .= '<td><input type="text" class="form-control" id="baseline_current_score' . $raw->id . '" name="baseline_current_score' . $raw->id . '"  value="' . $raw->baseline_score . '" ></td>';
            if ($painFile_status == 17 && auth()->user()->id != 100) {
                $html .= '<td><button class="btn btn-success btn-icon-only" type="button" onclick="update_treatment_goals(' . $raw->id . ')">';
                $html .= '<i class="fa fa-check fa-fw"></i></button>';
                $html .= '<button class="btn btn-danger btn-icon-only" type="button" onclick="del_treatment_goals(' . $raw->id . ')">';
                $html .= '<i class="fa fa-minus fa-fw"></i></button></td>';
            } else
                $html .= '<td></td><td></td></tr>';
        }
        return $html;

    }

    public function draw_treatment_goal_doctor($painFile_id, $painFile_status)
    {
        $model = BaselineTreatmentGoal::where('pain_file_id', $painFile_id)->get();
        $html = '';
        $i = 1;
        foreach ($model as $raw) {
            $html .= '<tr><td>' . $i++ . '</td>';
            $html .= '<td><input type="text" class="form-control" id="baseline_doc_goal' . $raw->id . '" name="baseline_doc_goal' . $raw->id . '"  value="' . $raw->baseline_goal . '" ></td>';
            $html .= '<td><input type="text" class="form-control" id="baseline_doc_goal_score' . $raw->id . '" name="baseline_doc_goal_score' . $raw->id . '"  value="' . $raw->baseline_goal_score . '" ></td>';
            $html .= '<td><input type="text" class="form-control" id="baseline_doc_current_score' . $raw->id . '" name="baseline_doc_current_score' . $raw->id . '"  value="' . $raw->baseline_score . '" ></td>';

            if ($painFile_status == 17 && auth()->user()->id != 100) {
                $html .= '<td><button class="btn btn-success btn-icon-only" type="button" onclick="update_doc_treatment_goals(' . $raw->id . ')">';
                $html .= '<i class="fa fa-check fa-fw"></i></button>';
                $html .= '<button class="btn btn-danger btn-icon-only" type="button" onclick="del_doc_treatment_goals(' . $raw->id . ')">';
                $html .= '<i class="fa fa-minus fa-fw"></i></button></td>';

            } else
                $html .= '<td></td><td></td></tr>';
        }
        return $html;

    }

    public function draw_treatment_choice_drugs_doctor($painFile_id, $painFile_status)
    {
        $model = BaselineTreatmentChoice::where('pain_file_id', $painFile_id)->get();
        $html = '';
        //dd($model);
        $i = 1;
        $total_cost = 0;
        foreach ($model as $raw) {
            $total_cost += $raw->drug_cost;
            $html .= '<tr><td>' . $i++ . '</td>';
            $html .= '<td>' . get_drug_desc($raw->drug_id) . '</td>';
            //  $html .= '<td>' . $raw->drug_specify . '</td>';//concentration
            //   $html .= '<td>' . $raw->concentration . '</td>';//concentration
            $html .= '<td>' . $raw->dosage . '</td>';//
            $html .= '<td>' . $raw->frequency . '</td>';
            $html .= '<td>' . $raw->duration . '</td>';
            $html .= '<td>' . $raw->quantity . '</td>';
            $html .= '<td>' . $raw->drug_cost . '</td>';
            $html .= '<td>' . $raw->drug_comments . '</td>';


            if ($painFile_status == 17 && auth()->user()->id != 100) {
                if ($raw->order_status != 1) {//أذا لم يتم صرف الدواء,يمكن الحذف,لكن بعد الصرف لا يمكن الحذف
                    $html .= '<td><span class="input-group-btn"><button class="btn btn-danger btn-icon-only" type="button" onclick="del_treatment_choice_drug(' . $raw->id . ')">';
                    $html .= '<i class="fa fa-minus fa-fw"></i></button></span></td>';
                } else
                    $html .= '<td></td></tr>';
            } else
                $html .= '<td></td></tr>';
        }
        $html .= '<tr><td colspan="6"></td><td class="warning" style="text-align:center">' . $total_cost . '</td><td colspan="2"></td></tr>';

        return $html;
    }

    public function draw_treatment_choice_drugs_pharm($painFile_id, $painFile_status)
    {
        $model = BaselineTreatmentChoice::where('pain_file_id', $painFile_id)->get();
        $html = '';
        $i = 1;
        $total_cost = 0;
        foreach ($model as $raw) {
            $color = 'bg-white';
            $disabled = '';
            if ($raw->order_status == 1) {
                $color = 'success';
                $disabled = 'disabled';
            }
            $total_cost += $raw->drug_cost;
            $html .= '<tr class="' . $color . ' ' . $disabled . '" ><td><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline "> <input type="checkbox" data-id= "' . $raw->id . '" id="drug_' . $raw->id . '" name="checkAll" class="checkboxes checkSingle " value="0" /><span></span></label></td>';
            $html .= '<td>' . $i++ . '</td>';
            $html .= '<td>' . get_drug_desc($raw->drug_id) . ' <span class="label label-sm label-info">' . $raw->drug_specify . '</span>
  <input type="hidden" name="' . $raw->id . 'drug_price" id="' . $raw->id . 'drug_price" value="' . $raw->drug_price . '"/></td>';
            // $html .= '<td><input type="text" id="' . $raw->id . 'concentration" name="' . $raw->id . 'concentration" class="form-control input-xsmall"
            //  value="' . $raw->concentration . '" placeholder="Concentration Mg."/></td>';
            $html .= '<td><input type="text" id="' . $raw->id . 'dosage" name="' . $raw->id . 'dosage" class="form-control input-xsmall" 
            value="' . $raw->dosage . '" placeholder="Dosage" onchange="cal_drug_cost_pharm(' . $raw->id . ');"/></td>';
            $html .= '<td><input type="text" id="' . $raw->id . 'frequency" name="' . $raw->id . 'frequency" class="form-control input-xsmall" 
            value="' . $raw->frequency . '" placeholder="Frequency" onchange="cal_drug_cost_pharm(' . $raw->id . ');"/></td>';

            $html .= '<td><input type="text" id="' . $raw->id . 'duration" name="' . $raw->id . 'duration" class="form-control input-xsmall" 
            value="' . $raw->duration . '" placeholder="Duration" onchange="cal_drug_cost_pharm(' . $raw->id . ');"/></td>';
            $html .= '<td><input type="text" id="' . $raw->id . 'quantity" name="' . $raw->id . 'quantity" readonly class="form-control input-xsmall" 
            value="' . $raw->quantity . '" placeholder="Quantity"/></td>';
            $html .= '<td><input type="text" id="' . $raw->id . 'drug_cost" name="' . $raw->id . 'drug_cost" readonly class="form-control input-xsmall" 
            value="' . $raw->drug_cost . '" placeholder="Cost"/></td>';
            $html .= '<td><input type="text" id="' . $raw->id . 'drug_comments" name="' . $raw->id . 'drug_comments" readonly class="form-control " 
            value="' . $raw->drug_comments . '" placeholder="Comments"/></td>';
            if ($painFile_status == 17 && auth()->user()->id != 100) {
                $html .= '<td><span class="input-group-btn"><button class="btn btn-success btn-icon-only" type="button" onclick="update_treatment_choice_drug_dosage_pharm(' . $raw->id . ')">';
                $html .= '<i class="fa fa-check fa-fw"></i></button></span></td>';
            } else
                $html .= '<td></td>';
            if ($painFile_status == 17 && auth()->user()->id != 100) {

                $html .= '<td><span class="input-group-btn"><button class="btn btn-danger btn-icon-only" type="button" onclick="del_treatment_choice_drug_pharm(' . $raw->id . ')">';
                $html .= '<i class="fa fa-minus fa-fw"></i></button></span></td>';
            } else
                $html .= '<td></td></tr>';
        }
        $html .= '<tr><td colspan="7"></td><td class="warning" style="text-align:center">' . $total_cost . '</td><td colspan="2"></td></tr>';

        return $html;
    }

    public function draw_followup_treatment_pharm($painFile_id, $followup_id, $painFile_status)
    {
        $model = FollowupTreatment::where('pain_file_id', $painFile_id)
            ->where('followup_id', $followup_id)->get();

        $compliance_list = get_lookups_list(88);
        $adverse_effects = get_lookups_list(92);
        $decision = get_lookups_list(115);
        $html = '';
        $i = 1;
        $total_cost = 0;
        foreach ($model as $raw) {
            $color = 'bg-white';
            $disabled = '';
            if ($raw->order_status == 1) {
                $color = 'success';
                $disabled = 'disabled';
            }
            //$color = 'success';
            $total_cost += $raw->drug_cost;
            $html .= '<tr class="' . $color . ' ' . $disabled . '" ><td><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline "> <input type="checkbox" data-id= "' . $raw->id . '" id="drug_' . $raw->id . '" name="checkAll" class="checkboxes checkSingle " value="0" /><span></span></label></td>';
            $html .= '<td>' . $i++ . '</td>';
            $html .= '<td>' . get_drug_desc($raw->drug_id) . (isset($raw->drug_comments) ? '</br><span class="label label-sm label-danger">' . $raw->drug_comments . ' </span>' : " ") . '</td>';
            //   $html .= '<td><input class="form-control input-xsmall input-sm" value="' . $raw->concentration . '" name="pharm_treat_concentration' . $raw->id . '" id="pharm_treat_concentration' . $raw->id . '"></td>';
            $html .= '<td><input class="form-control input-xsmall input-sm" value="' . $raw->dosage . '" name="pharm_treat_dosage' . $raw->id . '" id="pharm_treat_dosage' . $raw->id . '" onchange="cal_pharm_quantity_update(' . $raw->id . ');"></td>';
            $html .= '<td><input class="form-control input-xsmall input-sm" value="' . $raw->frequency . '" name="pharm_treat_frequency' . $raw->id . '" id="pharm_treat_frequency' . $raw->id . '" onchange="cal_pharm_quantity_update(' . $raw->id . ');"></td>';
            $html .= '<td><input class="form-control input-xsmall input-sm" value="' . $raw->duration . '" name="pharm_treat_duration' . $raw->id . '" id="pharm_treat_duration' . $raw->id . '" onchange="cal_pharm_quantity_update(' . $raw->id . ');"></td>';
            $html .= '<td><input class="form-control input-xsmall input-sm" value="' . $raw->quantity . '" name="pharm_treat_quantity' . $raw->id . '" id="pharm_treat_quantity' . $raw->id . '" readonly></td>';
            $html .= '<td><input type="hidden" value="' . $raw->drug_price . '" name="pharm_treat_drug_price' . $raw->id . '" id="pharm_treat_drug_price' . $raw->id . '"><input class="form-control input-xsmall input-sm" value="' . $raw->drug_cost . '" name="pharm_treat_drug_cost' . $raw->id . '" id="pharm_treat_drug_cost' . $raw->id . '" readonly></td>';
            //  $html .= '<td><span class="label label-sm label-warning">' . $raw->quantity . '</span></td>';
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
            $Pharm_adverse_effects = FollowupTreatmentAdverseEffect::where('followup_treatment_id', $raw->id)->get();
            //   dd($Pharm_adverse_effects);
            foreach ($adverse_effects as $list) {
                $selected = '';
                foreach ($Pharm_adverse_effects as $raw2) {
                    if ($list->id == $raw2->adverse_effects)
                        $selected = 'selected="selected"';
                }
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
            $html .= '<td><input class="form-control input-medium" type="text" id="pharm_drug_follow_comments' . $raw->id . '" name="drug_follow_comments' . $raw->id . '" value="' . $raw->drug_comments . '" /></td>';
            $html .= '<td>' . $raw->created_at->format('d-m-Y') . '</td>';
            if ($painFile_status == 17 && auth()->user()->id != 100) {
                $html .= '<td><span class="input-group-btn"><button class="btn btn-success btn-icon-only" type="button" onclick="update_pharm_treatment(' . $raw->id . ')">';
                $html .= '<i class="fa fa-check fa-fw"></i></button></span></td>';
                $html .= '<td><span class="input-group-btn"><button class="btn btn-danger btn-icon-only" type="button" onclick="del_treatment_followup_drug(' . $raw->id . ',' . $followup_id . ')">';
                $html .= '<i class="fa fa-minus fa-fw"></i></button></span></td></tr>';
            } else
                $html .= '<td></td></tr>';

        }
        $html .= '<tr><td colspan="7"></td><td class="warning" style="text-align:center">' . $total_cost . '</td><td colspan="6"></td></tr>';

        return $html;
    }

    public function draw_followup_treatment_doctor($painFile_id, $followup_id, $painFile_status)
    {
        $model = FollowupTreatment::where('pain_file_id', $painFile_id)
            ->where('followup_id', $followup_id)->get();

        $compliance_list = get_lookups_list(88);
        $adverse_effects = get_lookups_list(92);
        $decision = get_lookups_list(115);
        $html = '';
        $i = 1;
        $total_cost = 0;
        foreach ($model as $raw) {
            $color = 'bg-white';
            $disabled = '';
            if ($raw->order_status == 1) {
                $disabled = 'disabled';
                $color = 'success';
            }
            $total_cost += $raw->drug_cost;
            $html .= '<tr class="' . $color . ' ' . $disabled . '" ><td>' . $i++ . '</td>';
            $html .= '<td>' . get_drug_desc($raw->drug_id) . (isset($raw->drug_specify) ? '</br><span class="label label-sm label-danger">' . $raw->drug_specify . ' </span>' : " ") . '</td>';
            //     $html .= '<td><input class="form-control input-xsmall input-sm" value="' . $raw->concentration . '" name="concentration' . $raw->id . '" id="concentration' . $raw->id . '"></td>';
            $html .= '<td><input class="form-control input-xsmall input-sm" value="' . $raw->dosage . '" name="dosage' . $raw->id . '" id="dosage' . $raw->id . '" onchange="cal_drug_cost_followup(' . $raw->id . ')">
            <input type="hidden" value="' . $raw->drug_price . '" name="dosage' . $raw->id . '" id="drug_price' . $raw->id . '"></td>';
            $html .= '<td><input class="form-control input-xsmall input-sm" value="' . $raw->frequency . '" name="frequency' . $raw->id . '" id="frequency' . $raw->id . '" onchange="cal_drug_cost_followup(' . $raw->id . ')"></td>';
            $html .= '<td><input class="form-control input-xsmall input-sm" value="' . $raw->duration . '" name="duration' . $raw->id . '" id="duration' . $raw->id . '" onchange="cal_drug_cost_followup(' . $raw->id . ')"></td>';
            $html .= '<td><input class="form-control input-xsmall input-sm" value="' . $raw->quantity . '" name="quantity' . $raw->id . '" id="quantity' . $raw->id . '" readonly></td>';
            $html .= '<td><input class="form-control input-xsmall input-sm" value="' . $raw->drug_cost . '" name="drug_cost' . $raw->id . '" id="drug_cost' . $raw->id . '" readonly></td>';
            //   $html .= '<td><span class="label label-sm label-warning">' . $raw->quantity . '</span></td>';
            $html .= '<td>';
            $html .= '<select class="form-control input-small input-sm" name="compliance' . $raw->id . '" id="compliance' . $raw->id . '">';
            $html .= '<option value=""> Select... </option>';
            foreach ($compliance_list as $list) {
                $selected = '';
                if ($raw->compliance == $list->id)
                    $selected = 'selected="selected"';
                $html .= '<option value="' . $list->id . '"  ' . $selected . '>' . $list->lookup_details . '</option>';
            }
            $html .= '</select></td>';
            $html .= '<td>';
            $html .= '<select class="form-control  input-small input-sm select2-multiple" multiple name="adverse_effects[]' . $raw->id . '" id="adverse_effects' . $raw->id . '">';
            $Pharm_adverse_effects = FollowupTreatmentAdverseEffect::where('followup_treatment_id', $raw->id)->whereNull('deleted_at')->get();
            //    dd($Pharm_adverse_effects)
            foreach ($adverse_effects as $list) {
                $selected = '';
                foreach ($Pharm_adverse_effects as $raw2) {
                    if ($list->id == $raw2->adverse_effects)
                        $selected = 'selected="selected"';
                }
                $html .= '<option value="' . $list->id . '"  ' . $selected . '>' . $list->lookup_details . '</option>';
            }
            $html .= '</select></td>';
            $html .= '<td>';
            $html .= '<select class="form-control input-small input-sm" name="decision' . $raw->id . '" id="decision' . $raw->id . '">';
            $html .= '<option value=""> Select... </option>';
            foreach ($decision as $list) {
                $selected = '';
                if ($raw->decision == $list->id)
                    $selected = 'selected="selected"';
                $html .= '<option value="' . $list->id . '"  ' . $selected . '>' . $list->lookup_details . '</option>';
            }
            $html .= '</select></td>';
            $html .= '<td><input class="form-control input-medium" type="text" id="drug_follow_comments' . $raw->id . '" name="drug_follow_comments' . $raw->id . '" value="' . $raw->drug_comments . '" /></td>';
            //  $html .= '<td>' . $raw->created_at->format('d-m-Y') . '</td>';
            if ($painFile_status == 17 && auth()->user()->id != 100) {
                $html .= '<td><span class="input-group-btn"><button class="btn btn-success btn-icon-only" type="button" onclick="update_doc_followup_treatment(' . $raw->id . ',' . $followup_id . ')">';
                $html .= '<i class="fa fa-check fa-fw"></i></button></span></td>';
                //   $html .= '<td><button type="button" title="update" class="btn green" onclick="update_doc_followup_treatment(' . $raw->id . ',' . $followup_id . ')">+</button></td>';
                $html .= '<td><span class="input-group-btn"><button class="btn btn-danger btn-icon-only" type="button" onclick="del_treatment_followup_drug(' . $raw->id . ',' . $followup_id . ')">';
                $html .= '<i class="fa fa-minus fa-fw"></i></button></span></td></tr>';
            } else
                $html .= '<td></td></tr>';

        }
        $html .= '<tr><td colspan="6"></td><td class="warning" style="text-align:center">' . $total_cost . '</td><td colspan="6"></td></tr>';

        return $html;
    }

    function getConsultationRequest($painFile_id)
    {
        $consultations = ShareConsultation::where('pain_file_id', $painFile_id)->get();

        $html = '';
        foreach ($consultations as $row) {
            $consult_detail_count = ShareConsultationDetail::where('share_consultations_id', $row->id)->count();
            $html .= '<div class="panel panel-success">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a class="accordion-toggle accordion-toggle-styled" data-toggle="collapse" data-parent="#accordion1"
                                         href="#accordion1_' . $row->id . '" onclick="getConsultationComments(' . $row->id . ')">' . $row->consultation_title . '</a>
                                        <span class="badge badge-warning" id="comment_count' . $row->id . '">' . $consult_detail_count . ' </span>
                                    </h4>
                                </div>
                                <div id="accordion1_' . $row->id . '" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <ul class="list-inline">
                                            <li>
                                                <i class="fa fa-user-md font-green"></i><span class="font-grey-cascade">' . $row->user_name . '</span></li>
                                            <li>
                                                <i class="fa fa-calendar font-green"></i><span class="font-grey-cascade">' . Carbon::parse($row->consultation_date)->format('d-m-Y') . '</span></li>
                                        </ul>
                                        <p>' . $row->consultation_detail . '</p>
                                        <!-- List group -->
                                        <ul class="list-group" id="comment_list' . $row->id . '">
                                            <div id="comments' . $row->id . '"></div>
                                            <li class="list-group-item">
                                                <div class="form-group">
                                                    <label class="bold font-blue">My Comment</label>
                                                    <textarea class="form-control" rows="3" id="comment' . $row->id . '" name="comment' . $row->id . '"></textarea>

                                                </div>';
            if (auth()->user()->id != 100)
                $html .= '<button type="button" class="btn blue btn-block" onclick="addComment(' . $row->id . ')">Send</button>';
            $html .= '</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>';
        }
        return $html;

    }

    public function set_message(Request $request)
    {
        $message = new MessageCenter();
        $message->message = $request->message;
        $message->pain_file_id = $request->painFile_id;
        $message->source_id = $request->source_id;
        $message->type_id = $request->type_id;
        $message->isActive = 1;
        $message->created_by = auth()->user()->id;
        $message->org_id = auth()->user()->org_id;
        if ($message->save())
            return response()->json(['success' => true]);
        return response()->json(['success' => false]);
    }

    public function get_message(Request $request)
    {
        $painFile_id = $request->painFile_id;
        $message_center = MessageCenter::where('pain_file_id', $painFile_id)->where('isActive', 1)->orderBy('created_at')->get();
        $html = '';
        $color = '';
        $type = [1 => 'Doctor', 2 => 'Nurse', 3 => 'Pharmacists', 4 => 'Psychology'];
        $source = [1 => 'Base Line', 2 => 'Follow Up'];
        $color = [1 => ' label-danger', 2 => ' bg-purple'];
        foreach ($message_center as $result) {


            $html .= '<li><div class="col1"><div class="cont"><div class="cont-col1"><div class="label label-success" > <i class="fa fa-bell-o" ></i></div></div>';
            $html .= '<div class="cont-col2"><div class="desc">' . $result->message . '<span class="label ' . $color[$result->source_id] . ' label-sm">' . $source[$result->source_id] . ' ' . $type[$result->type_id] . '</span>';

            $html .= '</div></div></div>';
            $html .= '<div class="cont"><div class="cont-col1"><span class="btn btn-circle btn-xs btn-outline red" onclick="hideMsg(' . $result->id . ')"><i class="fa fa-ban" ></i>Hide</span></div>';
            $html .= '</div></div><div class="col2"> ' . $result->created_at->format('d-m-Y') . ' </div></div></li>';

        }
        return response()->json(['success' => true, 'html' => $html]);
    }

    public function get_all_message(Request $request)
    {
        $painFile_id = $request->painFile_id;
        $message_center = MessageCenter::where('pain_file_id', $painFile_id)->orderBy('created_at')->get();
        $html = '';
        $color = '';
        $type = [1 => 'Doctor', 2 => 'Nurse', 3 => 'Pharmacists', 4 => 'Psychology'];
        $source = [1 => 'Base Line', 2 => 'Follow Up'];
        $color = [1 => ' label-danger', 2 => ' bg-purple'];

        foreach ($message_center as $result) {
            if ($result->isActive == 0) {
                $showtest = 'Show';
                $showcolor = 'green';
                $showicon = "fa fa-check-circle-o";
            } else {
                $showicon = "fa fa-ban";
                $showtest = 'Hide';
                $showcolor = 'red';
            }
            $html .= ' <li><a href="#" class="btn btn-circle btn-xs btn-outline ' . $showcolor . ' pull-right" onclick="hideallMsg(' . $result->id . ')"><i class="' . $showicon . '"></i>' . $showtest . '</span> </a>
                                                <div class="task-title">
                                                    <span class="task-title-sp"> ' . $result->message . '</span>
                                                    <span class="label ' . $color[$result->source_id] . ' label-sm">' . $source[$result->source_id] . ' ' . $type[$result->type_id] . '</span>
                                                    <small class="font-blue">'.$result->created_at->format('d-m-Y') .'</small>
                                                </div>

                                            </li>';
            /* $html .= '<div class="cont-col2"><div class="desc">' . $result->message . '<span class="label ' .$color[$result->source_id] .' label-sm">'. $source[$result->source_id].' '.$type[$result->type_id] . '</span>';

             $html .= '</div></div></div>';
             $html .= '<div class="cont"><div class="cont-col1"><span class="label label-info label-sm" onclick="hideMsg('.$result->id.')">Hide</span></div>';
             $html .= '</div></div><div class="col2"> ' . $result->created_at->format('d-m-Y') . ' </div></div></li>';*/

        }
        return response()->json(['success' => true, 'html' => $html]);
    }

    public function hide_message(Request $request)
    {
        $message_center = MessageCenter::find($request->msg_id);
        if ($message_center) {
            if ($message_center->isActive == 1)
                $message_center->isActive = 0;
            else
                $message_center->isActive = 1;
            if ($message_center->save())
                return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }

    public function get_treat_doc_goal($painFile_id, $last_follow_id)
    {
        $model = FollowupTreatmentGoal::where('pain_file_id', $painFile_id)
            ->where('followup_id', $last_follow_id)->get();
        $compliance_list = get_lookups_list(88);

        $html = '';
        $i = 1;
        foreach ($model as $raw) {
            $html .= '<tr><td>' . $i++ . '</td>';
            $html .= '<td>' . $raw->goal_text . '</td>';
            $html .= '<td>' . $raw->followup_score . '</td>';
            $html .= '<td>' . get_lookup_desc($raw->followup_compliance) . '</td>';
            $html .= '</tr > ';


        }
        return $html;
    }

    public function getUserList()
    {
        $users = User::all();
        return $users;
    }

    public function draw_current_medication_table($patient_id)
    {
        $model = CurrentMedication::where('patient_id', $patient_id)->get();

        $html = '';
        $i = 1;
        $active_status = [0 => 'stopped', 1 => 'active'];
        if (isset($model))
            foreach ($model as $row) {
                $html .= '<tr><td>' . $i++ . '</td>';
                $html .= '<td>' . $row->drug_desc . '</td>';
                $html .= '<td>' . $row->drug_comments . '</td>';
                $html .= '<td>' . $active_status[$row->isActive] . '</td>';

                $html .= '<td>';
                if (auth()->user()->id != 100) {
                    $html .= '<button class="btn btn-success btn-icon-only btn-xs" type="button" onclick="stop_current_medication(' . $row->id . ')"><i class="fa fa-check fa-stop"></i></button>';
                    $html .= '<button class="btn btn-danger btn-icon-only btn-xs" type="button" onclick="del_current_medication(' . $row->id . ')"><i class="fa fa-minus fa-fw"></i></button>';
                }
                $html .= '</td>';
                $html .= '</tr> ';
            }
        return $html;
    }

    public function draw_alerts_table($patient_id)
    {
        $model = Alert::where('patient_id', $patient_id)->orderBy('id', 'desc')->get();

        $html = '';
        $i = 1;
        $color = [1 => 'Green', 2 => 'Yellow', 3 => 'Red'];
        if (isset($model))
            foreach ($model as $row) {
                $html .= '<tr><td>' . $i++ . '</td>';
                $html .= '<td>' . $row->alert_text . '</td>';
                $html .= '<td>' . $color[$row->alert_type] . '</td>';
                //  $html .= '<td>' . $active_status[$row->isActive] . '</td>';
                if ($row->created_by == auth()->user()->id && auth()->user()->id != 100)
                    $html .= '<td><button class="btn btn-danger btn-icon-only btn-xs" type="button" onclick="del_alert(' . $row->id . ')">
                            <i class="fa fa-minus fa-fw"></i></button></td>';
                $html .= '</tr > ';
            }
        return $html;
    }

    public function draw_qutenza_table($painFile_id)
    {
        $model = QutenzaTreatment::where('pain_file_id', $painFile_id)->orderBy('id')->get();

        $html = '';
        $i = 1;
        $visit_Type = [1 => 'Baseline', 2 => 'Followup'];
        if (isset($model))
            foreach ($model as $row) {
                $html .= '<tr><td>' . $i++ . '</td>';
                $html .= '<td>' . $row->id . '</td>';
                $html .= '<td>' . $row->visit_date . '</td>';
                $html .= '<td>' . $visit_Type[$row->visit_type] . '</td>';
                $html .= '<td><a class="btn btn-success btn-icon-only" data-toggle="modal" href="#qutenza_modal" 
                                               title="Click to edit Qutenza form" onclick="get_qutenza(' . $row->id . ',' . $row->followup_id . ')">';
                $html .= '<i class="fa fa-edit"></i></a>';
                if (auth()->user()->id != 100)
                    $html .= '<button class="btn btn-danger btn-icon-only btn-xs" type="button" onclick="del_current_qutenza(' . $row->id . ')"><i class="fa fa-minus fa-fw"></i></button>';
                $html .= '</td>';
                $html .= '</tr > ';
            }
        return $html;
    }

    public function draw_qutenza_score_table($qutenza_id)
    {
        $model = QutenzaScore::where('qutenza_id', $qutenza_id)->orderBy('id')->get();

        $html = '';
        $i = 1;
        $visit_Type = [1 => 'Baseline', 2 => 'Followup'];
        if (isset($model))
            foreach ($model as $row) {
                $html .= '<tr><td>' . $i++ . '</td>';
                $html .= '<td>' . $row->visit_date . '</td>';
                $html .= '<td>' . $visit_Type[$row->visit_type] . '</td>';
                $html .= '<td>' . $row->week . '</td>';
                $html .= '<td>' . $row->score . '</td>';
                $html .= '<td>' . $row->qutenza_id . '</td>';
                $html .= '<td>';
                if (auth()->user()->id != 100)
                    $html .= '<button class="btn btn-danger btn-icon-only btn-xs" type="button" onclick="del_qutenza_score(' . $row->id . ',' . $row->qutenza_id . ')"><i class="fa fa-minus fa-fw"></i></button>';
                $html .= '</td>';
                $html .= '</tr> ';
            }
        return $html;
    }

    public function draw_patient_project_table($painFile_id)
    {
        $model = PainFileProjectVw::where('pain_file_id', $painFile_id)->orderBy('id')->get();

        $html = '';
        $i = 1;


        if (isset($model))
            foreach ($model as $row) {


                $html .= '<tr><td>' . $i++ . '</td>';
                $html .= '<td><a  data-toggle="modal" href="#project_modal" title="Click to see project details">' . $row->project_name . ' </a></td>';
                $html .= '<td>' . $row->start_date . '</td>';
                $html .= '<td>' . (($row->answer_project_question == 1) ? 'Yes' : 'No') . '</td>';
                $html .= '<td>' . get_lookup_desc($row->pain_project_chart) . '</td>';
                $html .= '<td>' . $row->pain_project_note . '</td>';

                if ($row->end_date == '') {
                    $html .= '<td>';
                    if (auth()->user()->id != 100 && (auth()->user()->user_type_id == 8 || auth()->user()->user_type_id == 11)) {
                        $html .= '<a data-toggle="modal" href="#pharm_followup_project_modal"
                                                   class="btn btn-success btn-icon-only btn-xs" onclick="edit_project(' . $row->id . ',' . auth()->user()->user_type_id . ');">
                                                <i class="fa fa-stethoscope"></i></a>';
                    }
                    $html .= '</td>';

                }
                $html .= '</tr> ';
            }
        return $html;
    }

    public function draw_patient_project_followup_table($painFile_id)
    {
        //   $model = PainFileProjectFollowupVw::where('pain_file_id', $painFile_id)->orderBy('id')->get();
        $model = PainFileProjectVw::where('pain_file_id', $painFile_id)->orderBy('id')->get();

        $html = '';
        $i = 1;


        if (isset($model))
            foreach ($model as $row) {


                $html .= '<tr><td>' . $i++ . '</td>';
                $html .= '<td><a  data-toggle="modal" href="#project_modal" title="Click to see project details">' . $row->project_name . ' </a></td>';
                $html .= '<td>' . $row->start_date . '</td>';
                $html .= '<td>' . (($row->answer_project_question == 1) ? 'Yes' : 'No') . '</td>';
                $html .= '<td>' . get_lookup_desc($row->pain_project_chart) . '</td>';
                $html .= '<td>' . $row->pain_project_note . '</td>';
                $html .= '<td>';
                if ($row->end_date == '') {

                    if (auth()->user()->id != 100) {
                        $html .= '<a data-toggle="modal" href="#project_followup_modal"
                                                   class="btn btn-success btn-icon-only btn-xs" onclick="edit_followup_project(' . $row->id . ',' . auth()->user()->user_type_id . ',' . $row->project_id . ');">
                                                <i class="fa fa-stethoscope"></i></a>';
                    }
                }
                if (auth()->user()->id != 100 && (auth()->user()->user_type_id == 8 || auth()->user()->user_type_id == 11)) {
                    $html .= '<a data-toggle="modal" href="#stop_project_modal"
                                                   class="btn btn-warning btn-icon-only btn-xs" onclick="get_stop_project_modal_data(' . $row->id . ');">
                                                <i class="fa fa-stop"></i></a>';

                    $html .= '</td>';

                }
                $html .= '</tr> ';
            }
        return $html;
    }

    public function draw_doc_project_followup_table($painFile_id)
    {

        $model = ProjectFollowupDoctor::where('pain_file_id', $painFile_id)->orderBy('id')->get();

        $html = '';
        $i = 1;


        if (isset($model))
            foreach ($model as $row) {


                $html .= '<tr><td>' . $i++ . '</td>';
                $html .= '<td>' . $row->visit_date . '</td>';
                $html .= '<td>' . get_lookup_desc($row->doctor_action) . '</td>';
                $html .= '<td>' . $row->doctor_note . '</td>';
                $html .= '<td>' . User::find($row->created_by)->name . '</td>';
                if ($row->end_date == '') {
                    $html .= '<td>';
                    if (auth()->user()->id != 100 && (auth()->user()->user_type_id == 8 || auth()->user()->user_type_id == 9)) {
                        $html .= '<a href="javascript:;"
                                                   class="btn btn-danger btn-icon-only btn-xs" onclick="del_followup_project(' . $row->id . ',9);">
                                                <i class="fa fa-minus"></i></a>';
                    }
                    $html .= '</td>';

                }
                $html .= '</tr> ';
            }
        return $html;
    }

    public function draw_pharm_project_followup_table($painFile_id)
    {

        $model = ProjectFollowupPharm::where('pain_file_id', $painFile_id)->orderBy('id')->get();

        $html = '';
        $i = 1;


        if (isset($model))
            foreach ($model as $row) {


                $html .= '<tr><td>' . $i++ . '</td>';
                $html .= '<td>' . $row->visit_date . '</td>';
                $html .= '<td>' . get_lookup_desc($row->pharm_action) . '</td>';
                $html .= '<td>' . $row->pharm_note . '</td>';
                $html .= '<td>' . User::find($row->created_by)->name . '</td>';
                if ($row->end_date == '') {
                    $html .= '<td>';
                    if (auth()->user()->id != 100 && (auth()->user()->user_type_id == 8 || auth()->user()->user_type_id == 11)) {
                        $html .= '<a href="javascript:;"
                                                   class="btn btn-danger btn-icon-only btn-xs" onclick="del_followup_project(' . $row->id . ',11);">
                                                <i class="fa fa-minus"></i></a>';
                    }
                    $html .= '</td>';

                }
                $html .= '</tr> ';
            }
        return $html;
    }

    public function dispense(Request $request)
    {
        $item = ItemsBatchTb::where('item_id', $request->item_id)
            ->where('id', $request->batch_id)
            ->first();
        $item->batch_current_quantity = $item->batch_current_quantity - $request->quantity;
        if ($item->save())
            return response()->json(['success' => true]);
        return response()->json(['success' => false]);
    }

    public function get_batch_price(Request $request)
    {
        $drug_id = $request->drug_id;
        $batch_id = '';
        $batch_cost = 0;
        $batch = ItemsBatchTb::where('item_id', $drug_id)->where('isActive', 1)->first();
        if (isset($batch)) {
            $batch_id = $batch->id;
            $batch_cost = $batch->batch_piece_price;
            $batch_current_quantity = $batch->batch_current_quantity;

            return response()->json(['success' => true, 'batch_id' => $batch_id, 'batch_cost' => $batch_cost, 'batch_current_quantity' => $batch_current_quantity]);
        }
        return response()->json(['success' => false, 'batch_id' => '', 'batch_cost' => 0]);
    }
}
