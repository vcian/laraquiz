<?php

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            Admin::create([
                "name" => 'Quiz',
                "email" => "quiz@administrator.com",
                "password" => bcrypt('Quiz@123'),
                "email_verified_at" => Carbon::now(),
                'created_at' => Carbon::now()
            ]);
        }catch(Exception $ex){
            Log::error($ex->getMessage());
        }
    }
}
