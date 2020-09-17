<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $table = 'items';

    protected $fillable = [
        'name',
        'quantity',
        'pack_quantity',
        'category_id',
        'group_id',
    ];

    public function group(){
        return $this->hasOne('App\Models\Group');
    }

    public function category(){
        return $this->hasOne('App\Models\Category');
    }
}
