<?php

use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $model = new \App\PainLocation();
        $model->id = 1;
        $model->name = 'Global';
        $model->save();
        $model = new \App\PainLocation();
        $model->id = 2;
        $model->name = 'Phantom';
        $model->save();
        $model = new \App\PainLocation();
        $model->id = 3;
        $model->name = 'Stump';
        $model->save();
        $model = new \App\PainLocation();
        $model->id = 4;
        $model->name = 'Head';
        $model->save();
        $model = new \App\PainLocation();
        $model->id = 5;
        $model->name = 'Jaw Left';
        $model->save();
        $model = new \App\PainLocation();
        $model->id = 6;
        $model->name = 'Jaw Right';
        $model->save();
        $model = new \App\PainLocation();
        $model->id = 7;
        $model->name = 'Neck';
        $model->save();
        $model = new \App\PainLocation();
        $model->id = 8;
        $model->name = 'Upper back';
        $model->save();
        $model = new \App\PainLocation();
        $model->id = 9;
        $model->name = 'Lower back';
        $model->save();
        $model = new \App\PainLocation();
        $model->id = 10;
        $model->name = 'Chest';
        $model->save();
        $model = new \App\PainLocation();
        $model->id = 11;
        $model->name = 'Abdomen';
        $model->save();
        $model = new \App\PainLocation();
        $model->id = 12;
        $model->name = 'Shoulder girdle Left';
        $model->save();
        $model = new \App\PainLocation();
        $model->id = 13;
        $model->name = 'Shoulder girdle Right';
        $model->save();
        $model = new \App\PainLocation();
        $model->id = 14;
        $model->name = 'Shoulder Left';
        $model->save();
        $model = new \App\PainLocation();
        $model->id = 15;
        $model->name = 'Shoulder Right';
        $model->save();
        $model = new \App\PainLocation();
        $model->id = 16;
        $model->name = 'Upper arm Left';
        $model->save();
        $model = new \App\PainLocation();
        $model->id = 17;
        $model->name = 'Upper arm Right';
        $model->save();
        $model = new \App\PainLocation();
        $model->id = 18;
        $model->name = 'Elbow Left';
        $model->save();
        $model = new \App\PainLocation();
        $model->id = 19;
        $model->name = 'Elbow Right';
        $model->save();
        $model = new \App\PainLocation();
        $model->id = 20;
        $model->name = 'Lower arm Left';
        $model->save();
        $model = new \App\PainLocation();
        $model->id = 21;
        $model->name = 'Lower arm Right';
        $model->save();
        $model = new \App\PainLocation();
        $model->id = 22;
        $model->name = 'Wrist and arm Left';
        $model->save();
        $model = new \App\PainLocation();
        $model->id = 23;
        $model->name = 'Wrist and arm Right';
        $model->save();

        $model = new \App\PainLocation();
        $model->id = 24;
        $model->name = 'Hip (buttock/trochanter) Left';
        $model->save();
        $model = new \App\PainLocation();
        $model->id = 25;
        $model->name = 'Hip (buttock/trochanter) Right';
        $model->save();
        $model = new \App\PainLocation();
        $model->id = 26;
        $model->name = 'Upper leg Left';
        $model->save();
        $model = new \App\PainLocation();
        $model->id = 27;
        $model->name = 'Upper leg Right';
        $model->save();
        $model = new \App\PainLocation();
        $model->id = 28;
        $model->name = 'Knee Left';
        $model->save();
        $model = new \App\PainLocation();
        $model->id = 29;
        $model->name = 'Knee Right';
        $model->save();

        $model = new \App\PainLocation();
        $model->id = 30;
        $model->name = 'Lower leg Left';
        $model->save();
        $model = new \App\PainLocation();
        $model->id = 31;
        $model->name = 'Lower leg Right';
        $model->save();

        $model = new \App\PainLocation();
        $model->id = 32;
        $model->name = 'Ankle Left';
        $model->save();

        $model = new \App\PainLocation();
        $model->id = 33;
        $model->name = 'Ankle Right';
        $model->save();

        $model = new \App\PainLocation();
        $model->id = 34;
        $model->name = 'Foot Left';
        $model->save();

        $model = new \App\PainLocation();
        $model->id = 35;
        $model->name = 'Foot Right';
        $model->save();


    }
}
