<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $nuenenenGroupSeed = "Nünenen";
        $nuenenenSlug = Str::slug($nuenenenGroupSeed);
        $parentGroup = Group::where('slug', '=', $nuenenenSlug)->first();
        if ($parentGroup === null) {
            $parentGroup = Group::create([
                'name' => $nuenenenGroupSeed,
                'slug' => $nuenenenSlug,
            ]);
            $parentGroup->save();
        }

        $aetnaGroupSeed = "Aetna";
        $aetnaSlug = Str::slug($aetnaGroupSeed);
        $childGroup = Group::where('slug', '=', $aetnaSlug)->first();
        if ($childGroup === null) {
            $childGroup = Group::create([
                'name' => $aetnaGroupSeed,
                'slug' => $aetnaSlug,
                'parent_group_id' => $parentGroup->id,
            ]);
            $childGroup->save();
        }
    }
}
