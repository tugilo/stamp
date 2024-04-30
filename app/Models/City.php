<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    // テーブル名の定義
    protected $table = 'cities';

    // Mass Assignmentから保護された属性
    protected $guarded = ['id'];

    /**
     * この都市に関連する郵便番号データ
     */
    public function zips()
    {
        return $this->hasMany(Zip::class, 'city_id');
    }
    public function scopeActiveOrdered($query)
    {
        return $query->where('show_flg', 1)->orderBy('order_no');
    }

}
