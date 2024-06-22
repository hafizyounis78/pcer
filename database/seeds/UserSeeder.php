<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new \App\User();
        $user->national_id='901521823';
        $user->name = "admin";
        $user->email = "admin@admin.com";
        $user->password = bcrypt('admin');
        $user->org_id=3;
        $user->user_type_id=8;
        $user->save();

        $user = new \App\User();
        $user->national_id='123456789';
        $user->name = "Doctor1";
        $user->email = "doctor@doctor.com";
        $user->password = bcrypt('doctor');
        $user->org_id=3;
        $user->user_type_id=9;
        $user->save();

        $user = new \App\User();
        $user->national_id='123456789';
        $user->name = "Nurse1";
        $user->email = "nurse@nurse.com";
        $user->password = bcrypt('nurse');
        $user->org_id=3;
        $user->user_type_id=10;
        $user->save();

        $user = new \App\User();
        $user->national_id='123456789';
        $user->name = "Pharmacist1";
        $user->email = "pharm@pharm.com";
        $user->password = bcrypt('pharm');
        $user->org_id=3;
        $user->user_type_id=11;
        $user->save();

        $user = new \App\User();
        $user->national_id='123456789';
        $user->name = "Clerk1";
        $user->email = "clerk@clerk.com";
        $user->password = bcrypt('clerk');
        $user->org_id=3;
        $user->user_type_id=12;
        $user->save();
    }
}
