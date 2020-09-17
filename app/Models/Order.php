<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'name',
        'provision_date',
        'return_date',
        'group_id'
    ];

    public function group(){
        return $this->hasOne('App\Models\Group');
    }
}
