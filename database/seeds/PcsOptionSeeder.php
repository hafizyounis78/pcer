<?php

use Illuminate\Database\Seeder;

class PcsOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      /*  $lookup = new \App\PcsOption();
        $lookup->id = 0;
        $lookup->score = 0;
        $lookup->option = 'Not at all';
        $lookup->created_by=1;
        $lookup->save();*/

        $lookup = new \App\PcsOption();
        $lookup->id =1;
        $lookup->score = 1;
        $lookup->option = 'To a slight degree';
        $lookup->created_by=1;
        $lookup->save();

        $lookup = new \App\PcsOption();
        $lookup->id = 2;
        $lookup->score = 2;
        $lookup->option = 'To a moderate degree';
        $lookup->created_by=1;
        $lookup->save();



        $lookup = new \App\PcsOption();
        $lookup->id = 3;
        $lookup->score = 3;
        $lookup->option = 'To a great degree';
        $lookup->created_by=1;
        $lookup->save();

        $lookup = new \App\PcsOption();
        $lookup->id = 4;
        $lookup->score = 4;
        $lookup->option = 'All the time';
        $lookup->created_by=1;
        $lookup->save();

    }
}
