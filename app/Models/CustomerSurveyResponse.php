<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerSurveyResponse extends Model
{
    use HasFactory;

    protected $table = 'customer_survey_responses';
    protected $guarded = ['id'];

    /**
     * 顧客とのリレーション
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
