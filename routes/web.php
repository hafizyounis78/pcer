<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', 'UserController@login');//->middleware("throttle:3,2");

Auth::routes();
Route::get('home', 'HomeController@index')->name('home');
Route::get('home/overallChart', 'HomeController@getOverall');
Route::get('home/FollowupMonthlyChart', 'HomeController@getFollowupMonthly');
Route::get('home/ClosedFileMonthlyChart', 'HomeController@getClosedFileMonthly');
Route::get('home/BaselineMonthlyChart', 'HomeController@getBaselineMonthly');
Route::get('home/totalPatientsByDistricts', 'HomeController@getTotalPatientsByDistricts');
Route::get('home/totalPatientsByGender', 'HomeController@getTotalPatientsByGender');
Route::get('home/totalPainFilesByInjuryMech', 'HomeController@getTotalPainFilesByInjuryMech');
Route::get('home/Top10DiagnosisChart', 'HomeController@getTop10Diagnosis');
Route::get('home/Top10DrugsChart', 'HomeController@getTop10Drugs');
Route::get('home/DrugsCostChart', 'HomeController@getDrugsCostChart');
Route::get('home/DoctorDrugsCostChart', 'HomeController@getDoctorDrugsCostChart');
Route::get('home/PainScaleChart', 'HomeController@getPainScale');
Route::get('home/general_statistics', 'HomeController@get_general_statistics');
Route::get('home/AreaChart', 'HomeController@getAreaChart');
Route::get('home/totalPatientsByAge', 'HomeController@getTotalPatientsByAge');
Route::get('home/PlexusAreaChart', 'HomeController@getPlexusAreaChart');
Route::get('home/totalPatientsPhysicalTreatment', 'HomeController@getTotalPatientsPhysicalTreatment');
Route::get('home/totalPatientsPharmaTreatment', 'HomeController@getTotalPatientsPharmaTreatment');
Route::get('home/DermatomesAreaChart', 'HomeController@getDermatomesAreaChart');
Route::get('home/PeripheralAreaChart', 'HomeController@getPeripheralAreaChart');
Route::post('home/chart-details', 'HomeController@get_chart_details');
Route::get('clear', 'SettingController@clear');
Route::group(['middleware' => ['auth']], function () {

    Route::get('logout', 'UserController@logout');
    Route::resource('user', 'UserController');
    Route::get('user-data', 'UserController@userData');
    Route::post('user/availabileEmail', 'UserController@availabileEmail');
    Route::post('user/getEmployee', 'UserController@getEmployee');
    Route::post('user/delete', 'UserController@deleteUser');
    Route::post('user/activate', 'UserController@activateUser');
    Route::get('user/clear', 'SettingController@clear');
    Route::get('user/profile', 'UserController@show');
    //respondant
    Route::resource('patient', 'PatientController');
    Route::post('patient/search', 'PatientController@findPatient');
    Route::get('patient/create/{id}', 'PatientController@create');
    Route::post('patient/set-id', 'PatientController@patient_setId');
    Route::post('patient/get-patient-data', 'PatientController@get_patient_data');
    Route::post('patient/get-patient-current-medication', 'PatientController@get_current_medication_data');
    Route::post('patient/insert-current-medication', 'PatientController@add_current_medication');
    Route::post('patient/delete-current-medication', 'PatientController@delete_current_medication');
    Route::post('patient/stop-current-medication', 'PatientController@change_current_medication_status');

    Route::post('patient/insert-alert', 'PatientController@add_alert');
    Route::post('patient/get-patient-alerts', 'PatientController@get_alerts_data');
    Route::post('patient/delete-alert', 'PatientController@delete_alert');
    Route::post('patient/get-curr-patient-alert', 'PatientController@get_curr_patient_alert');

    Route::get('patient/register', 'PatientController@register');
    Route::post('patient/get_moi_info', 'PatientController@get_moi_person_info');
    Route::post('patient/list', 'PatientController@list');
    Route::post('patient/availabileNationalId', 'PatientController@availabileNationalId');
    Route::post('patient/availabileNationalId', 'PatientController@availabileNationalId');
    Route::post('patient/followup-export-excel', 'PatientController@followup_export_excel');
    Route::post('patient/baseline-export-excel', 'PatientController@baseline_export_excel');

//respondant
    Route::resource('painfile', 'PainFileController');
    Route::post('painFile/set-id', 'PainFileController@painFile_setId');
    Route::post('painFile/new-file', 'PainFileController@new_painFile');
    Route::get('painFile/view/{painid}/{pid}/{sid}', 'PainFileController@painFile_View');
    Route::post('painfile/datalist', 'PainFileController@search_patient_list');
    Route::post('painFile/change_states', 'PainFileController@change_painfile_states');
    // Route::post('painFile/delete-selected-painfile', 'PatientController@delete_ckd_painfile');
    Route::post('painFile/delete_patient', 'PainFileController@delete_patient_file');
    Route::post('painFile/comments', 'PainFileController@getConsultationComments');
    Route::post('general/set_message', 'Controller@set_message');
    Route::post('general/hide_message', 'Controller@hide_message');
    Route::post('general/get-message', 'Controller@get_message');
    Route::post('general/get-all-message', 'Controller@get_all_message');
//acutePAin
    Route::resource('acutepain', 'AcutePainController');
    Route::get('acutepain/create/{painFile_id}/{patientid}/{painFile_status}', 'AcutePainController@create');
    Route::post('acutepain/side_and_nerve', 'AcutePainController@inser_sideNerve');
    Route::post('acutepain/del_side_and_nerve', 'AcutePainController@delete_side_and_nerve');
    Route::post('acutepain/get_nerve_details', 'AcutePainController@get_side_and_nerve_details');
    Route::post('acutepain/get_sub_side_details', 'AcutePainController@get_sub_side_details');
    Route::post('acutepain/medicationNow', 'AcutePainController@inser_medicationNow');
    Route::post('acutepain/del_medicationNow', 'AcutePainController@delete_medicationNow');

    //BaseLine
    Route::resource('baseline', 'BaselineController');
    Route::get('baseline/create/{painFile_id}/{patientid}/{painFile_status}', 'BaselineController@create');
    //BaseLine Nurese
    Route::resource('baselineNurse', 'BaselineNurseController');
    Route::post('baselineNurse/insert-treatment-goal', 'BaselineNurseController@insert_treatment_goal');
    Route::post('baselineNurse/del-treatment-goal', 'BaselineNurseController@delete_treatment_goal');
    Route::post('baselineNurse/update-treatment-goals', 'BaselineNurseController@update_treatment_goal');
    Route::post('baselineNurse/add-pcl', 'BaselineNurseController@insert_pcl_for_patient');
    Route::post('baselineNurse/get-pcl', 'BaselineNurseController@get_pcl_patient_eval');
    Route::post('baselineNurse/save-pcl-answer', 'BaselineNurseController@save_pcl_answers');
    Route::post('baselineNurse/save-physiotherapy-chk', 'BaselineNurseController@save_physiotherapy_chk');
    Route::post('baselineNurse/physiotherapy-chk-compliance', 'BaselineNurseController@update_physio_chk_compliance');
    Route::get('test', 'BaselineController@test_page');

    //BaseLine Doctor
    Route::resource('baselineDoctor', 'BaselineDoctorController');
    Route::post('baselineDoctor/insert-prvtreatment-drugs', 'BaselineDoctorController@insert_prvtreatment_drugs');
    Route::post('baselineDoctor/del-prvtreatment-drugs', 'BaselineDoctorController@delete_prvtreatment_drugs');
    Route::post('baselineDoctor/insert-treatment_choice-drugs', 'BaselineDoctorController@insert_treatment_choice_drugs');
    Route::post('baselineDoctor/del-treatment_choice-drugs', 'BaselineDoctorController@delete_treatment_choice_drugs');
    Route::post('baselineDoctor/side_and_nerve', 'BaselineDoctorController@inser_sideNerve');
    Route::post('baselineDoctor/del_side_and_nerve', 'BaselineDoctorController@delete_side_and_nerve');
    Route::post('baselineDoctor/del_all_side_and_nerve', 'BaselineDoctorController@delete_all_side_and_nerve');
    Route::post('baselineDoctor/del_all_other_side_and_nerve', 'BaselineDoctorController@delete_all_other_side_and_nerve');
    Route::post('baselineDoctor/del_all_truma_side_and_nerve', 'BaselineDoctorController@del_all_truma_side_and_nerve');
    Route::post('baselineDoctor/get-batch-price', 'Controller@get_batch_price');//جديد خاص بالمخازن

    Route::post('baselineDoctor/side-scars', 'BaselineDoctorController@inser_scars_side');
    Route::post('baselineDoctor/del_scars-side_and_nerve', 'BaselineDoctorController@delete_scars_side_and_nerve');

    Route::post('baselineDoctor/side-other', 'BaselineDoctorController@inser_other_side');
    Route::post('baselineDoctor/del_other-side_and_nerve', 'BaselineDoctorController@delete_other_side_and_nerve');

    Route::post('baselineDoctor/insert-treatment-goal', 'BaselineDoctorController@insert_treatment_goal');
    Route::post('baselineDoctor/del-treatment-goal', 'BaselineDoctorController@delete_treatment_goal');
    Route::post('baselineDoctor/update-treatment-goals', 'BaselineDoctorController@update_treatment_goal');
    /* Route::post('acutepain/medicationNow', 'AcutePainController@inser_medicationNow');
     Route::post('acutepain/del_medicationNow', 'AcutePainController@delete_medicationNow');*/
    //Pharm
    Route::resource('baselinePharm', 'BaselinePharmController');
    Route::post('baselinePharm/insert-treatment_choice-drugs', 'BaselinePharmController@insert_treatment_choice_drugs');
    Route::post('baselinePharm/del-treatment_choice-drugs', 'BaselinePharmController@delete_treatment_choice_drugs');
    Route::post('baselinePharm/update-treatment_choice-dosage', 'BaselinePharmController@update_treatment_choice_drugs_dosage');
    Route::post('baseline/change-drug-order-status', 'BaselinePharmController@change_drug_order_status_fn');
//Setting
    Route::get('setting/s2', 'SettingController@s2');
    Route::get('setting/table', 'SettingController@settingTable');

    Route::post('s2-details-data', 'SettingController@s2_detials_data');
    Route::post('setting/table-data', 'SettingController@getTableData');
    Route::post('setting/table-save', 'SettingController@saveTable');
    Route::post('setting/table-delete', 'SettingController@deleteTable');
    Route::post('setting/s2-save', 'SettingController@s2_save');
    Route::post('setting/s2-delete', 'SettingController@s2_delete');
    Route::post('setting/refresh', 'SettingController@get_lookup_list');
    Route::post('setting/toggle-active', 'SettingController@changeItemStatus');

    /* Route::get('setting/sidenerve', 'SettingController@sidenerve');
     Route::get('setting/sideNerve-data', 'SettingController@sidenerve_data');
     Route::post('setting/sideNerve-save', 'SettingController@sidenerve_save');
     Route::post('setting/sideNerve-delete', 'SettingController@sidenerve_delete');*/

    //Followup
    Route::resource('followup', 'FollowupController');
    Route::get('followup/create/{painFile_id}/{patientid}/{painFile_status}', 'FollowupController@create');
    Route::post('followup/update-goal-compliance', 'FollowupController@update_goal_compliance');
    Route::post('followup/update-goal-score', 'FollowupController@update_goal_score');
    Route::post('followup/get-batch-price', 'Controller@get_batch_price');//جديد خاص بالمخازن
    Route::post('followup/insert-treatment-drug', 'FollowupController@add_treatment_drug');
    Route::post('followup/update-treatment-drug', 'FollowupController@update_treatment_drug');
    Route::post('followup/del-treatment-drug', 'FollowupController@del_treatment_drug');
    Route::post('followup/newfollowup', 'FollowupController@new_followup');
    Route::post('followup/availabileFollowupDate', 'FollowupController@check_available_date');
    Route::post('followup/cancel-consultation', 'FollowupController@cancel_followup_consultation');

    Route::post('followup/get-followup-doctor', 'FollowupDoctorController@get_followup_doc');
    Route::post('followup/get-followup-nurse', 'FollowupNurseController@get_followup_nurse');
    Route::post('followup/get-followup-pharm', 'FollowupPharmController@get_followup_pharm');
    Route::post('followup/get-followup-psychology', 'FollowupPsychologyController@get_followup_psychology');

    Route::post('followup/update-pharm-treatment', 'FollowupPharmController@update_pharm_treatment');
    Route::post('followup/insert-pharm-treatment-drug', 'FollowupPharmController@add_treatment_drug');
    Route::post('followup/update-nurse-treatment-goal', 'FollowupNurseController@update_treatment_goal');
    Route::post('followup/refresh-treatment-goals', 'FollowupController@refresh_treatment_goal');
    Route::post('followup/show-charts', 'FollowupController@get_followup_charts');
    Route::post('followup/treatment-export-excel', 'FollowupController@export_excel_file');
    Route::post('followup/change-drug-order-status', 'FollowupPharmController@change_drug_order_status_fn');
    Route::post('followup/save-physiotherapy-chk', 'FollowupNurseController@save_physiotherapy_chk');
    Route::post('followup/physiotherapy-chk-compliance', 'FollowupNurseController@update_physio_chk_compliance');

    Route::resource('lastfollowup', 'LastFollowupController');
    Route::get('lastfollowup/create/{painFile_id}/{patientid}/{painFile_status}', 'LastFollowupController@create');
    Route::post('lastfollowup/update-goal', 'LastFollowupController@update_last_followup_goal');
    Route::post('lastfollowup/newfollowup', 'LastFollowupController@new_lastfollowup');
    Route::post('lastfollowup/close-painfile', 'LastFollowupController@close_painfile');
    Route::post('lastfollowup/add-pcl', 'LastFollowupController@insert_pcl_for_patient');
    Route::post('lastfollowup/get-pcl', 'LastFollowupController@get_pcl_patient_eval');
    Route::post('lastfollowup/save-pcl-answer', 'LastFollowupController@save_pcl_answers');
    Route::post('lastfollowup/get-close-reason-details', 'LastFollowupController@get_close_reason_details_list_by_parent_id');

    //FollowupDoctore
    Route::resource('followupDoctor', 'FollowupDoctorController');
    //  Route::post('followupDoctor/availabileFollowupDate', 'FollowupDoctorController@check_available_date');


    Route::resource('followupPharm', 'FollowupPharmController');
    //  Route::post('followupPharm/availabileFollowupDate', 'FollowupPharmController@check_available_date');

    Route::resource('followupNurse', 'FollowupNurseController');
    //   Route::post('followupNurse/availabileFollowupDate', 'FollowupNurseController@check_available_date');
    //consultation
    Route::resource('consultation', 'ConsultationController');
    Route::post('consultation/add-comment', 'ConsultationController@add_Comment');

    //Appointments
    Route::resource('appointment', 'AppointmentController');
    Route::post('appointment/get-staff', 'AppointmentController@getUserByDept');
    Route::post('appointment/get-events', 'AppointmentController@get_events');
    Route::post('appointment/check-appointment', 'AppointmentController@check_available_appoint');
    Route::post('appointment/todaylist', 'AppointmentController@today_list');
    Route::post('appointment/attend', 'AppointmentController@make_attend');
    Route::post('appointment/not-attend', 'AppointmentController@save_not_attend');
    Route::post('appointment/delete', 'AppointmentController@deleteAppointment');
    Route::get('get-patient-name', 'AppointmentController@get_patient_by_name');

    //Reports
    Route::resource('reports', 'ReportsController');
    Route::post('run_rep', 'ReportsController@run_rep');
    Route::post('report/view_report', 'ReportsController@view_report');

    //attendance
    Route::resource('attendance', 'AttendanceController');
    Route::post('attendance-data', 'AttendanceController@attendanceData');
    Route::post('current-attendance-data', 'AttendanceController@currentDayAttendanceData');
    Route::post('attendance/insert-attendance', 'AttendanceController@save_attendance');

    //Qutenza

    Route::post('get-qutenza', 'QutenzaTreatmentController@get_qutenza');
    Route::post('del-qutenza', 'QutenzaTreatmentController@del_qutenza');
    Route::post('insert-qutenza', 'QutenzaTreatmentController@addQutenza');
    Route::post('add-patient-qutenza', 'QutenzaTreatmentController@addQutenza_request');

    //Qutenza score
    Route::post('insert-qutenz-score', 'QutenzaTreatmentController@addQutenzaScore');
    Route::post('del-qutenz-score', 'QutenzaTreatmentController@del_qutenza_score');

    //Projects
    Route::resource('project', 'ProjectController');
    Route::get('project-data', 'ProjectController@projectData');
    Route::post('add-patient-project', 'ProjectController@addPatientProject');
    Route::post('stop-patient-project', 'ProjectController@stopPatientProject');
    Route::post('project-info', 'ProjectController@getProjectInfo');
    Route::post('show-project', 'ProjectController@getProject');
    Route::post('show-follow-project', 'ProjectController@getFollowupProject');
    Route::post('project/delete', 'ProjectController@delete_project');
    Route::post('project/add-symptoms', 'ProjectController@addPatientSymptoms');
    Route::post('project/add-pharm-baseline', 'ProjectController@addPharmProjectBaseline');
    Route::post('project/add-pharm-followup', 'ProjectController@addPharmProjectFollowup');
    Route::post('project/add-doc-followup', 'ProjectController@addDoctorProjectFollowup');
    Route::post('project/add-followup', 'ProjectController@addProjectFollowup');
    Route::post('project/patient-list', 'ProjectController@get_project_patient_list');
    Route::post('project/get-stop-project-data', 'ProjectController@get_stop_project_modal_data');
    Route::post('del-project-followup', 'ProjectController@del_project_followup');
    //Psychology Doctor
    Route::resource('baselinePsychology', 'BaselinePsychologyController');
    Route::post('baselinePsychology/add-dass', 'BaselinePsychologyController@insert_dass_for_patient');
    Route::post('baselinePsychology/get-dass', 'BaselinePsychologyController@get_dass_patient_eval');
    Route::post('baselinePsychology/save-dass-answer', 'BaselinePsychologyController@save_dass_answers');
    //Psychology Followup
    Route::resource('followupPsychology', 'FollowupPsychologyController');
});
