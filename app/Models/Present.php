<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Present extends Model
{
    use HasFactory;

    protected $table = 'presents';
    protected $guarded = ['id'];

    /**
     * プレゼント種別とのリレーション
     */
    public function presentSyubetsu()
    {
        return $this->belongsTo(PresentSyubetsu::class, 'syubetsu_id');
    }
}
