<?php

use Illuminate\Database\Seeder;

class UserTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userType1 = new \App\UserType();
        $userType1->name = "Super Admin";
        $userType1->description = "Super Admin";
        $userType1->save();

        $userType2 = new \App\UserType();
        $userType2->name = "Admin";
        $userType2->description = "Admin";
        $userType2->save();

        $userType3 = new \App\UserType();
        $userType3->name = "Editor";
        $userType3->description = "Editor";
        $userType3->save();

        $userType4 = new \App\UserType();
        $userType4->name = "Viewer";
        $userType4->description = "Viewer";
        $userType4->save();
    }
}
