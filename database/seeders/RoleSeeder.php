<?php
namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [];
        foreach (Role::ROLES as $code => $name) {
            $roles[] = [
                'code' => $code,
                'name' => $name,
            ];
        }
        Role::insert($roles);
    }
}
