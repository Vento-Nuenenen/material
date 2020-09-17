<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRoleSeed = "Admin";
        $adminSlug = Str::slug($adminRoleSeed);
        $role = Role::where('slug', '=', $adminSlug)->first();
        if ($role === null) {
            $role = Role::create([
                'name' => $adminRoleSeed,
                'slug' => $adminSlug,
            ]);
            $role->save();
        }

        $alRoleSeed = "AL";
        $alSlug = Str::slug($alRoleSeed);
        $role = Role::where('slug', '=', $alSlug)->first();
        if ($role === null) {
            $role = Role::create([
                'name' => $alRoleSeed,
                'slug' => $alSlug,
            ]);
            $role->save();
        }

        $leiterRoleSeed = "Leiter";
        $leiterSlug = Str::slug($leiterRoleSeed);
        $role = Role::where('slug', '=', $leiterSlug)->first();
        if ($role === null) {
            $role = Role::create([
                'name' => $leiterRoleSeed,
                'slug' => $leiterSlug,
            ]);
            $role->save();
        }
    }
}
