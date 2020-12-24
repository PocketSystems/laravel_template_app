<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $company_id = DB::table('company')->insertGetId([
            'logo' => '',
            'name' => 'Pocket Systems',
            'phone' => '03171015636',
            'email' => 'info@pocketsystems.pk',
            'address' => 'mateen Shoppers gallery',
            'start_date' => date('Y-m-d'),
            'end_date' => date('Y-m-d',strtotime('3020-12-1')),
            'created_at' => date('Y-m-d h:m:s'),
            'updated_at' => date('Y-m-d h:m:s'),


        ]);
        DB::table('users')->insert([
            'company_id' => $company_id,
            'name' => 'usama',
            'email' => 'admin@admin.com',
            'password' => Hash::make('12345678'),
        ]);
    }
}
