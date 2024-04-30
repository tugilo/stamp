<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zip extends Model
{
    use HasFactory;

    protected $table = 'zips';
    protected $guarded = ['id'];

    /**
     * 都市とのリレーション
     */
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }
}
