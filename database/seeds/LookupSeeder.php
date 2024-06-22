<?php

use Illuminate\Database\Seeder;

class LookupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lookup = new \App\Lookup();
        $lookup->id = 1;
        $lookup->parent_id = 0;
        $lookup->lookup_details = 'District';
        $lookup->save();

        $lookup = new \App\Lookup();
        $lookup->id = 2;
        $lookup->parent_id = 1;
        $lookup->lookup_details = 'North Gaza';
        $lookup->save();

        $lookup = new \App\Lookup();
        $lookup->id = 3;
        $lookup->parent_id = 1;
        $lookup->lookup_details = 'Gaza';
        $lookup->save();

        $lookup = new \App\Lookup();
        $lookup->id = 4;
        $lookup->parent_id = 1;
        $lookup->lookup_details = 'Middle Area';
        $lookup->save();

        $lookup = new \App\Lookup();
        $lookup->id = 5;
        $lookup->parent_id = 1;
        $lookup->lookup_details = 'Khan Younis';
        $lookup->save();


        $lookup = new \App\Lookup();
        $lookup->id = 6;
        $lookup->parent_id = 1;
        $lookup->lookup_details = 'Rafah';
        $lookup->save();
        /******************/
        $lookup = new \App\Lookup();
        $lookup->id = 7;
        $lookup->parent_id = 0;
        $lookup->lookup_details = 'User Type';
        $lookup->save();

        $lookup = new \App\Lookup();
        $lookup->id = 8;
        $lookup->parent_id = 7;
        $lookup->lookup_details = 'Admin user';
        $lookup->save();

        $lookup = new \App\Lookup();
        $lookup->id = 9;
        $lookup->parent_id = 7;
        $lookup->lookup_details = 'Doctor user';
        $lookup->save();

        $lookup = new \App\Lookup();
        $lookup->id = 10;
        $lookup->parent_id = 7;
        $lookup->lookup_details = 'Nurse user';
        $lookup->save();

        $lookup = new \App\Lookup();
        $lookup->id = 11;
        $lookup->parent_id = 7;
        $lookup->lookup_details = 'Pharmacist user';
        $lookup->save();

        $lookup = new \App\Lookup();
        $lookup->id = 12;
        $lookup->parent_id = 7;
        $lookup->lookup_details = 'Clerk user';
        $lookup->save();
//***********************//
        $lookup = new \App\Lookup();
        $lookup->id = 13;
        $lookup->parent_id = 0;
        $lookup->lookup_details = 'Gender';
        $lookup->save();

        $lookup = new \App\Lookup();
        $lookup->id = 14;
        $lookup->parent_id = 13;
        $lookup->lookup_details = 'Male';
        $lookup->save();

        $lookup = new \App\Lookup();
        $lookup->id = 15;
        $lookup->parent_id = 13;
        $lookup->lookup_details = 'Female';
        $lookup->save();
//*********************//
        $lookup = new \App\Lookup();
        $lookup->id = 16;
        $lookup->parent_id = 0;
        $lookup->lookup_details = 'File Status';
        $lookup->save();

        $lookup = new \App\Lookup();
        $lookup->id = 17;
        $lookup->parent_id = 16;
        $lookup->lookup_details = 'Opened File';
        $lookup->save();

        $lookup = new \App\Lookup();
        $lookup->id = 18;
        $lookup->parent_id = 16;
        $lookup->lookup_details = 'Closed File';
        $lookup->save();
//**********************//
//*********************//
        $lookup = new \App\Lookup();
        $lookup->id = 19;
        $lookup->parent_id = 0;
        $lookup->lookup_details = 'Education';
        $lookup->save();

        $lookup = new \App\Lookup();
        $lookup->id = 20;
        $lookup->parent_id = 19;
        $lookup->lookup_details = 'Comprehensive school (1–10 years)';
        $lookup->save();

        $lookup = new \App\Lookup();
        $lookup->id = 21;
        $lookup->parent_id = 19;
        $lookup->lookup_details = 'Secondary school/vocational school (11–13 years)';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 22;
        $lookup->parent_id = 19;
        $lookup->lookup_details = 'College degree (14–17 years)';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 23;
        $lookup->parent_id = 19;
        $lookup->lookup_details = 'Higher university degree (>17 years)';
        $lookup->save();
//**********************//
//*********************//
        $lookup = new \App\Lookup();
        $lookup->id = 24;
        $lookup->parent_id = 0;
        $lookup->lookup_details = 'Current Work';
        $lookup->save();

        $lookup = new \App\Lookup();
        $lookup->id = 25;
        $lookup->parent_id = 24;
        $lookup->lookup_details = 'Currently no salaried work';
        $lookup->save();

        $lookup = new \App\Lookup();
        $lookup->id = 26;
        $lookup->parent_id = 24;
        $lookup->lookup_details = 'Part time';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 27;
        $lookup->parent_id = 24;
        $lookup->lookup_details = 'Full time';
        $lookup->save();

        $lookup = new \App\Lookup();
        $lookup->id = 28;
        $lookup->parent_id = 24;
        $lookup->lookup_details = 'Unknown';
        $lookup->save();
//**********************//


        $lookup = new \App\Lookup();
        $lookup->id = 29;
        $lookup->parent_id = 0;
        $lookup->lookup_details = 'Injury mechanism';
        $lookup->save();

        $lookup = new \App\Lookup();
        $lookup->id = 30;
        $lookup->parent_id = 29;
        $lookup->lookup_details = 'Gun shot wound';
        $lookup->save();

        $lookup = new \App\Lookup();
        $lookup->id = 31;
        $lookup->parent_id = 29;
        $lookup->lookup_details = 'Traumatic amputation';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 32;
        $lookup->parent_id = 29;
        $lookup->lookup_details = 'Surgical amputation';
        $lookup->save();

        $lookup = new \App\Lookup();
        $lookup->id = 33;
        $lookup->parent_id = 29;
        $lookup->lookup_details = 'Other';
        $lookup->save();
        //**********************//
//**********************//


        $lookup = new \App\Lookup();
        $lookup->id = 34;
        $lookup->parent_id = 0;
        $lookup->lookup_details = 'Injury Status';
        $lookup->save();

        $lookup = new \App\Lookup();
        $lookup->id = 35;
        $lookup->parent_id = 34;
        $lookup->lookup_details = 'Amputation';
        $lookup->save();

        $lookup = new \App\Lookup();
        $lookup->id = 36;
        $lookup->parent_id = 34;
        $lookup->lookup_details = 'Limb reconstruction';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 37;
        $lookup->parent_id = 34;
        $lookup->lookup_details = 'Vascular surgery';
        $lookup->save();

        $lookup = new \App\Lookup();
        $lookup->id = 38;
        $lookup->parent_id = 34;
        $lookup->lookup_details = 'Planned elective surgery';
        $lookup->save();

        $lookup = new \App\Lookup();
        $lookup->id = 39;
        $lookup->parent_id = 34;
        $lookup->lookup_details = 'Other';
        $lookup->save();
        //**********************//
//**********************//


        $lookup = new \App\Lookup();
        $lookup->id = 40;
        $lookup->parent_id = 0;
        $lookup->lookup_details = 'Timing of consultation';
        $lookup->save();

        $lookup = new \App\Lookup();
        $lookup->id = 41;
        $lookup->parent_id = 40;
        $lookup->lookup_details = 'Pre-operatively';
        $lookup->save();

        $lookup = new \App\Lookup();
        $lookup->id = 42;
        $lookup->parent_id = 40;
        $lookup->lookup_details = 'Per-operatively';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 43;
        $lookup->parent_id = 40;
        $lookup->lookup_details = 'Post-operatively';
        $lookup->save();

        $lookup = new \App\Lookup();
        $lookup->id = 44;
        $lookup->parent_id = 40;
        $lookup->lookup_details = 'Other';
        $lookup->save();


        //**********************//
        $lookup = new \App\Lookup();
        $lookup->id = 45;
        $lookup->parent_id = 0;
        $lookup->lookup_details = 'Pain localized';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 46;
        $lookup->parent_id = 45;
        $lookup->lookup_details = 'Yes';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 47;
        $lookup->parent_id = 45;
        $lookup->lookup_details = 'Yes, but also other areas';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 48;
        $lookup->parent_id = 45;
        $lookup->lookup_details = 'No';
        $lookup->save();
        //*******************************
        $lookup = new \App\Lookup();
        $lookup->id = 49;
        $lookup->parent_id = 0;
        $lookup->lookup_details = 'Light touch & Pinprick (needle)';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 50;
        $lookup->parent_id = 49;
        $lookup->lookup_details = 'Decreased or loss of sensation';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 51;
        $lookup->parent_id = 49;
        $lookup->lookup_details = 'Hyperalgesia';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 52;
        $lookup->parent_id = 49;
        $lookup->lookup_details = 'Normal sensation';
        $lookup->save();

        //*****************
        //*******************************
        $lookup = new \App\Lookup();
        $lookup->id = 53;
        $lookup->parent_id = 0;
        $lookup->lookup_details = 'to approximatley 20C-40C';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 54;
        $lookup->parent_id = 53;
        $lookup->lookup_details = 'Decreased or loss of sensation';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 55;
        $lookup->parent_id = 53;
        $lookup->lookup_details = 'Allodynia';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 56;
        $lookup->parent_id = 53;
        $lookup->lookup_details = 'Normal sensation';
        $lookup->save();
        //******************


        $lookup = new \App\Lookup();
        $lookup->id = 57;
        $lookup->parent_id = 0;
        $lookup->lookup_details = 'Pain Duration';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 58;
        $lookup->parent_id = 57;
        $lookup->lookup_details = '< 1 month';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 59;
        $lookup->parent_id = 57;
        $lookup->lookup_details = '1-2 months';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 60;
        $lookup->parent_id = 57;
        $lookup->lookup_details = '2-3 months';
        $lookup->save();

        $lookup = new \App\Lookup();
        $lookup->id = 61;
        $lookup->parent_id = 57;
        $lookup->lookup_details = '3-6 months';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 62;
        $lookup->parent_id = 57;
        $lookup->lookup_details = '6-12 months';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 63;
        $lookup->parent_id = 57;
        $lookup->lookup_details = '12-24 months';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 64;
        $lookup->parent_id = 57;
        $lookup->lookup_details = '2-5 years';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 65;
        $lookup->parent_id = 57;
        $lookup->lookup_details = '> 5 years';
        $lookup->save();
        //***************

        $lookup = new \App\Lookup();
        $lookup->id = 66;
        $lookup->parent_id = 0;
        $lookup->lookup_details = 'Temporal aspects';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 67;
        $lookup->parent_id = 66;
        $lookup->lookup_details = 'continuous (the pain is always present)';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 68;
        $lookup->parent_id = 66;
        $lookup->lookup_details = 'episodic recurrent (there are recurrent pain attacks with pain-free intervals)';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 69;
        $lookup->parent_id = 66;
        $lookup->lookup_details = 'continuous with pain attacks (there are recurrent pain attacks as exacerbations of underlying continuous pain)';
        $lookup->save();

        //***********

        $lookup = new \App\Lookup();
        $lookup->id = 70;
        $lookup->parent_id = 0;
        $lookup->lookup_details = 'Health Rate';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 71;
        $lookup->parent_id = 70;
        $lookup->lookup_details = 'Very good';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 72;
        $lookup->parent_id = 70;
        $lookup->lookup_details = 'Good';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 73;
        $lookup->parent_id = 70;
        $lookup->lookup_details = 'Moderate';
        $lookup->save();
        $lookup->id = 74;
        $lookup->parent_id = 70;
        $lookup->lookup_details = 'Bad';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 75;
        $lookup->parent_id = 70;
        $lookup->lookup_details = 'Very bad';
        $lookup->save();
        //*********************
        $lookup = new \App\Lookup();
        $lookup->id = 76;
        $lookup->parent_id = 0;
        $lookup->lookup_details = 'Treatment effect';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 77;
        $lookup->parent_id = 76;
        $lookup->lookup_details = 'Stopped due to lack of effect';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 78;
        $lookup->parent_id = 76;
        $lookup->lookup_details = 'Stopped due to side effects';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 79;
        $lookup->parent_id = 76;
        $lookup->lookup_details = 'Stopped due to financial aspects';
        $lookup->save();
        $lookup->id = 80;
        $lookup->parent_id = 76;
        $lookup->lookup_details = 'Stopped due to compliance';
        $lookup->save();

        $lookup = new \App\Lookup();
        $lookup->id = 81;
        $lookup->parent_id = 76;
        $lookup->lookup_details = 'Stopped due to interactions with other drugs';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 82;
        $lookup->parent_id = 76;
        $lookup->lookup_details = 'Not tested properly';
        $lookup->save();

       //****************
        $lookup = new \App\Lookup();
        $lookup->id = 83;
        $lookup->parent_id = 0;
        $lookup->lookup_details = 'Physical treatment';
        $lookup->save();

        $lookup = new \App\Lookup();
        $lookup->id = 84;
        $lookup->parent_id = 83;
        $lookup->lookup_details = 'Mirror therapy';
        $lookup->save();

        $lookup = new \App\Lookup();
        $lookup->id = 85;
        $lookup->parent_id = 83;
        $lookup->lookup_details = 'Tactile treatment';
        $lookup->save();

        $lookup = new \App\Lookup();
        $lookup->id = 86;
        $lookup->parent_id = 83;
        $lookup->lookup_details = 'No';
        $lookup->save();

        $lookup = new \App\Lookup();
        $lookup->id = 87;
        $lookup->parent_id = 83;
        $lookup->lookup_details = 'Other';
        $lookup->save();

    //**********************

        $lookup = new \App\Lookup();
        $lookup->id = 88;
        $lookup->parent_id = 0;
        $lookup->lookup_details = 'Compliance';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 89;
        $lookup->parent_id = 88;
        $lookup->lookup_details = 'Good';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 90;
        $lookup->parent_id = 88;
        $lookup->lookup_details = 'Partial';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 91;
        $lookup->parent_id = 88;
        $lookup->lookup_details = 'Poor/None';
        $lookup->save();
//**********************************
        $lookup = new \App\Lookup();
        $lookup->id = 92;
        $lookup->parent_id = 0;
        $lookup->lookup_details = 'Adverse effects';
        $lookup->save();

        $lookup = new \App\Lookup();
        $lookup->id = 93;
        $lookup->parent_id = 92;
        $lookup->lookup_details = 'Constipation';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 94;
        $lookup->parent_id = 92;
        $lookup->lookup_details = 'Diarrhea';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 95;
        $lookup->parent_id = 92;
        $lookup->lookup_details = 'Flatulence';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 96;
        $lookup->parent_id = 92;
        $lookup->lookup_details = 'Abdominal pain';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 97;
        $lookup->parent_id = 92;
        $lookup->lookup_details = 'Vomiting';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 98;
        $lookup->parent_id = 92;
        $lookup->lookup_details = 'Dyspepsia';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 99;
        $lookup->parent_id = 92;
        $lookup->lookup_details = 'Dry Mouth';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 100;
        $lookup->parent_id = 92;
        $lookup->lookup_details = 'Sleepiness / fatigue';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 101;
        $lookup->parent_id = 92;
        $lookup->lookup_details = 'Insomnia';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 102;
        $lookup->parent_id = 92;
        $lookup->lookup_details = 'Headache';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 103;
        $lookup->parent_id = 92;
        $lookup->lookup_details = 'Dizziness';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 104;
        $lookup->parent_id = 92;
        $lookup->lookup_details = 'Malaise / feeling sick';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 105;
        $lookup->parent_id = 92;
        $lookup->lookup_details = 'Tremor';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 106;
        $lookup->parent_id = 92;
        $lookup->lookup_details = 'Paresthesia';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 107;
        $lookup->parent_id = 92;
        $lookup->lookup_details = 'Impotence';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 108;
        $lookup->parent_id = 92;
        $lookup->lookup_details = 'Reduced libido';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 109;
        $lookup->parent_id = 92;
        $lookup->lookup_details = 'Cramps';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 110;
        $lookup->parent_id = 92;
        $lookup->lookup_details = 'Sweat';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 111;
        $lookup->parent_id = 92;
        $lookup->lookup_details = 'Rash';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 112;
        $lookup->parent_id = 92;
        $lookup->lookup_details = 'Change in cognitive function - confusion';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 113;
        $lookup->parent_id = 92;
        $lookup->lookup_details = 'Change in cognitive function - memory difficulties';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 114;
        $lookup->parent_id = 92;
        $lookup->lookup_details = 'Others';
        $lookup->save();
//**********************
        $lookup = new \App\Lookup();
        $lookup->id = 115;
        $lookup->parent_id = 0;
        $lookup->lookup_details = 'Decision';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 116;
        $lookup->parent_id = 115;
        $lookup->lookup_details = 'Continue as before';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 117;
        $lookup->parent_id = 115;
        $lookup->lookup_details = 'Change dosage';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 118;
        $lookup->parent_id = 115;
        $lookup->lookup_details = 'Stop';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 119;
        $lookup->parent_id = 115;
        $lookup->lookup_details = 'Stop and add new drug';
        $lookup->save();
//--------------------
        $lookup = new \App\Lookup();
        $lookup->id = 120;
        $lookup->parent_id = 0;
        $lookup->lookup_details = 'Overall Status';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 121;
        $lookup->parent_id = 120;
        $lookup->lookup_details = 'Very much better';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 122;
        $lookup->parent_id = 120;
        $lookup->lookup_details = 'Much better';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 123;
        $lookup->parent_id = 120;
        $lookup->lookup_details = 'Minimally better';
        $lookup->save();
        $lookup = new \App\Lookup();
        $lookup->id = 124;
        $lookup->parent_id = 120;
        $lookup->lookup_details = 'No change';
        $lookup->save();

        $lookup = new \App\Lookup();
        $lookup->id = 125;
        $lookup->parent_id = 120;
        $lookup->lookup_details = 'Minimally worse';
        $lookup->save();

        $lookup = new \App\Lookup();
        $lookup->id = 126;
        $lookup->parent_id = 120;
        $lookup->lookup_details = 'Much worse';
        $lookup->save();

        $lookup = new \App\Lookup();
        $lookup->id = 127;
        $lookup->parent_id = 120;
        $lookup->lookup_details = 'Very much worse';
        $lookup->save();

    }
}
