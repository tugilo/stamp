<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    // テーブル名の定義
    protected $table = 'events';

    // Mass Assignmentから保護された属性
    protected $guarded = ['id'];

    /**
     * このイベントを主催する組織
     */
    public function organizer()
    {
        return $this->belongsTo(Organizer::class, 'organizer_id');
    }

    /**
     * このイベントに参加した顧客情報
     */
    public function participants()
    {
        return $this->hasMany(EventParticipation::class, 'event_id');
    }
}
