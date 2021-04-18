<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // $company = Company::create([
        //     'name' => 'Test Company',
        //     'address' => 'Test Company Address'
        // ]);

        // $user             = new User();
        // $user->company_id = $company->id;
        // $user->name       = 'Super Admin';
        // $user->email      = 'admin@admin.com';
        // $user->phone      = '5555555555';
        // $user->password   = Hash::make('12345678');
        // $user->role       = 'admin';
        // $user->is_active  = true;
        // $user->save();

    }
}
