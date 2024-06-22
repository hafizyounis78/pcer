<?php

use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $model = new \App\Organization();
        $model->id = 1;
        $model->name='Palestine';
        $model->parent = 0;
        $model->save();

        $model = new \App\Organization();
        $model->id = 2;
        $model->name='Gaza Strip';
        $model->parent = 1;
        $model->save();

        $model = new \App\Organization();
        $model->id = 3;
        $model->name='Shifa Hospital';
        $model->parent =2 ;
        $model->save();
    }
}
