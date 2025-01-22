<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory(3)->create()->each(function ($user) {
            $company = Company::factory()->create();
            $user->companies()->attach($company->id);
            Role::attachRole($user, Role::CODE_ADM_ROLE);
        });
    }
}
