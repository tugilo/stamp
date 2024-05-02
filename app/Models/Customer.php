<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    // テーブル名の定義
    protected $table = 'customers';

    // Mass Assignmentの保護
    protected $guarded = ['id'];

    /**
     * 顧客が参加したイベントに関するリレーション
     */
    public function events()
    {
        return $this->hasManyThrough(Event::class, CustomerPresent::class, 'customer_id', 'id', 'id', 'event_id');
    }
    public function eventParticipations()
    {
        return $this->hasMany(EventParticipation::class);
    }

    /**
     * 顧客が受け取ったプレゼントに関するリレーション
     */
    public function presents()
    {
        return $this->hasMany(CustomerPresent::class, 'customer_id');
    }

    /**
     * 顧客が回答したアンケートに関するリレーション
     */
    public function surveyResponses()
    {
        return $this->hasMany(CustomerSurveyResponse::class, 'customer_id');
    }
}
