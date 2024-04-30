<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventParticipation extends Model
{
    use HasFactory;

    // テーブル名の定義
    protected $table = 'event_participations';

    // Mass Assignmentから保護された属性
    protected $guarded = ['id'];

    /**
     * この参加情報に関連する顧客
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * この参加情報に関連するイベント
     */
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
}
