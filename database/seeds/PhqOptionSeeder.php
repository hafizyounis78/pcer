<?php

use Illuminate\Database\Seeder;

class PhqOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*$lookup = new \App\PhqOption();
        $lookup->id = 0;
        $lookup->score = 0;
        $lookup->option = 'Not at all';
        $lookup->created_by=1;
        $lookup->save();*/

        $lookup = new \App\PhqOption();
        $lookup->id =1;
        $lookup->score = 1;
        $lookup->option = 'Several days';
        $lookup->created_by=1;
        $lookup->save();

        $lookup = new \App\PhqOption();
        $lookup->id = 2;
        $lookup->score = 2;
        $lookup->option = 'More than half the days';
        $lookup->created_by=1;
        $lookup->save();



        $lookup = new \App\PhqOption();
        $lookup->id = 3;
        $lookup->score = 3;
        $lookup->option = 'Nearly every day';
        $lookup->created_by=1;
        $lookup->save();


    }
}
