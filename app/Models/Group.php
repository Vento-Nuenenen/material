<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $table = 'groups';

    protected $fillable = [
        'name',
        'slug',
        'parent_group_id'
    ];

    public function parentGroup(){
        return $this->hasOne('App\Models\Group', 'parent_group_id');
    }
}
