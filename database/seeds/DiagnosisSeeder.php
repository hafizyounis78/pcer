<?php

use Illuminate\Database\Seeder;

class DiagnosisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $model = new \App\Diagnostics();
        $model->id = 1;
        $model->name = 'Neuropathic pain';
        $model->save();
        $model = new \App\Diagnostics();
        $model->id = 2;
        $model->name = 'CRPS';
        $model->save();
        $model = new \App\Diagnostics();
        $model->id = 3;
        $model->name = 'PLP & stump pain';
        $model->save();
        $model = new \App\Diagnostics();
        $model->id = 4;
        $model->name = 'Nociceptive pain';
        $model->save();
        $model = new \App\Diagnostics();
        $model->id = 5;
        $model->name = 'Primary pain';
        $model->save();
        $model = new \App\Diagnostics();
        $model->id = 6;
        $model->name = 'Combination of conditions';
        $model->save(); 
        $model = new \App\Diagnostics();
        $model->id = 7;
        $model->name = 'Other';
        $model->save();
    }
}
