<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;

class CompaniesTableSeeder extends Seeder
{
    public function run()
    {
        $user = User::first();

        Company::create([
            'title' => 'Example Company',
            'phone' => '9876543210',
            'description' => 'This is a test company',
            'user_id' => $user->id,
        ]);
    }
}
