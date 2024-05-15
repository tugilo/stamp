<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerSurveyResponse extends Model
{
    use HasFactory;

    protected $table = 'customer_survey_responses';
    protected $guarded = ['id'];

    // 顧客とのリレーション
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    // 性別とのリレーション
    public function gender()
    {
        return $this->belongsTo(Gender::class, 'gender_id');
    }

    // 年代グループとのリレーション
    public function ageGroup()
    {
        return $this->belongsTo(AgeGroup::class, 'age_group_id');
    }

    // 居住地とのリレーション
    public function residence()
    {
        return $this->belongsTo(Residence::class, 'residence_id');
    }

    // キャンペーン発見きっかけとのリレーション
    public function discoveryTrigger()
    {
        return $this->belongsTo(DiscoveryTrigger::class, 'discovery_trigger_id');
    }

    // キャンペーン発見きっかけのカスタムレスポンスとのリレーション
    public function discoveryCustomResponses()
    {
        return $this->hasMany(DiscoveryCustomResponse::class, 'survey_response_id');
    }

    // 情報カテゴリのカスタムレスポンスとのリレーション
    public function infoCustomResponses()
    {
        return $this->hasMany(InfoCustomResponse::class, 'survey_response_id');
    }

    // info_category_ids を配列で取得・設定
    public function getInfoCategoryIdsAttribute($value)
    {
        return explode(',', $value);
    }

    public function setInfoCategoryIdsAttribute($value)
    {
        $this->attributes['info_category_ids'] = is_array($value) ? implode(',', $value) : $value;
    }

    // カテゴリ名の取得
    public function getInfoCategoryNamesAttribute()
    {
        return InfoCategory::whereIn('id', $this->info_category_ids)->get()->pluck('name')->toArray();
    }
    
    // 「その他」のカスタム情報テキストの取得
    public function getCustomInfoTextAttribute()
    {
        if (in_array('その他', $this->info_category_names)) {
            return $this->infoCustomResponses->first()->text ?? 'N/A';
        }
        return 'N/A';
    }
}
