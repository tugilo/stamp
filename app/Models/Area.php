<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $table = 'areas';
    protected $guarded = ['id'];

    public function scopeActiveOrdered($query)
    {
        return $query->where('show_flg', 1)->orderBy('order_no');
    }

}
