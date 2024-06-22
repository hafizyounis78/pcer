<?php

use Illuminate\Database\Seeder;

class DrugSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $drug = new \App\DrugList();
        $drug->id = 1;
        $drug->name = 'Paracetamol';
        $drug->save();

        $drug = new \App\DrugList();
        $drug->id = 2;
        $drug->name = 'Ibuprofen';
        $drug->save();
        $drug = new \App\DrugList();
        $drug->id = 3;
        $drug->name = 'Diclofenac';
        $drug->save();
        $drug = new \App\DrugList();
        $drug->id = 4;
        $drug->name = 'Celecoxib';
        $drug->save();
        $drug = new \App\DrugList();
        $drug->id = 5;
        $drug->name = 'Parecoxib';
        $drug->save();
        $drug = new \App\DrugList();
        $drug->id = 6;
        $drug->name = 'Tramadol';
        $drug->save();
        $drug = new \App\DrugList();
        $drug->id = 7;
        $drug->name = 'Codeine';
        $drug->save();
        $drug = new \App\DrugList();
        $drug->id = 8;
        $drug->name = 'Morphine';
        $drug->save();

        $drug = new \App\DrugList();
        $drug->id = 9;
        $drug->name = 'Buprenorphine';
        $drug->save();
        $drug = new \App\DrugList();
        $drug->id = 10;
        $drug->name = 'Fentanyl';
        $drug->save();
        $drug = new \App\DrugList();
        $drug->id = 11;
        $drug->name = 'Amitriptyline';
        $drug->save();
        $drug = new \App\DrugList();
        $drug->id = 12;
        $drug->name = 'Nortriptyline';
        $drug->save();
        $drug = new \App\DrugList();
        $drug->id = 13;
        $drug->name = 'Duloxetine';
        $drug->save();
        $drug = new \App\DrugList();
        $drug->id = 14;
        $drug->name = 'Venlafaxine';
        $drug->save();
        $drug = new \App\DrugList();
        $drug->id = 15;
        $drug->name = 'Gabapentin';
        $drug->save();
        $drug = new \App\DrugList();
        $drug->id = 16;
        $drug->name = 'Pregabalin';
        $drug->save();
        $drug = new \App\DrugList();
        $drug->id = 17;
        $drug->name = 'Prednisolone';
        $drug->save();
        $drug = new \App\DrugList();
        $drug->id = 18;
        $drug->name = 'Dexamethasone';
        $drug->save();
        $drug = new \App\DrugList();
        $drug->id = 19;
        $drug->name = 'Triamcinolone';
        $drug->save();
        $drug = new \App\DrugList();
        $drug->id = 20;
        $drug->name = 'Methylprednisolone';
        $drug->save();
        $drug = new \App\DrugList();
        $drug->id = 21;
        $drug->name = 'Lidocain';
        $drug->save();
        $drug = new \App\DrugList();
        $drug->id = 22;
        $drug->name = 'Bupivacaine';
        $drug->save();
        $drug = new \App\DrugList();
        $drug->id = 23;
        $drug->name = 'Ketamine';
        $drug->save();
        $drug = new \App\DrugList();
        $drug->id = 24;
        $drug->name = 'Qutenza/capsaicin';
        $drug->save();

    }
}
