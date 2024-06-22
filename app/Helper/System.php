<?php

function admin_vw()
{
    return 'admin';
}

function user_vw()
{
    return 'user';
}

function patient_vw()
{
    return 'patient';
}
function appointment_vw()
{
    return 'appointment';
}
function report_vw()
{
    return 'report';
}
function followup_vw()
{
    return 'followup';
}
function acutepain_vw()
{
    return 'acutepain';
}

function baseline_vw()
{
    return 'baseline';
}

function admin_lookup_vw()
{
    return 'admin.lookup';
}


function setting_vw()
{
    return 'setting';
}
function attendance_vw()
{
    return 'attendance';
}
function qutenza_vw()
{
    return 'qutenza';
}
function project_vw()
{
    return 'project';
}
function get_lookups_list($parent_id)
{
    $lookups = \App\Lookup::select('id','lookup_details')
        ->where('parent_id',$parent_id)->where('isActive',1)->orderBy('id','Asc')->get();
    if (isset($lookups))
        return $lookups;
    return null;
}
function get_lookup_desc($id)
{
    $lookups = \App\Lookup::select('lookup_details')->where('id',$id)->first();
    if (isset($lookups))
        return $lookups->lookup_details;
    return '';
}

function get_drug_desc($id)
{
    $lookups = \App\ItemsTb::select('item_scientific_name as name')->where('id',$id)->first();
    if (isset($lookups))
        return $lookups->name;
    return '';
}
function get_drug_list_desc($id)
{
    $lookups = \App\DrugList::select('name')->where('id',$id)->first();
    if (isset($lookups))
        return $lookups->name;
    return '';
}
function get_diagnosis_desc($id)
{
    $lookups = \App\Diagnostics::select('name')->where('id',$id)->first();
    if (isset($lookups))
        return $lookups->name;
    return '';
}

