<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerSurveyResponse extends Model
{
    use HasFactory;

    // モデルが使用するテーブル名を指定
    protected $table = 'customer_survey_responses';

    // 代入禁止の属性（主にセキュリティ目的で保護）
    protected $guarded = ['id'];

    /**
     * 顧客とのリレーションを定義
     * このリレーションにより、CustomerSurveyResponse インスタンスから関連する Customer インスタンスに簡単にアクセスできる
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }


    /**
     * 性別とのリレーション
     */
    public function gender()
    {
        return $this->belongsTo(Gender::class, 'gender_id');
    }

    /**
     * 年代グループとのリレーション
     */
    public function ageGroup()
    {
        return $this->belongsTo(AgeGroup::class, 'age_group_id');
    }

    /**
     * 居住地とのリレーション
     */
    public function residence()
    {
        return $this->belongsTo(Residence::class, 'residence_id');
    }

    /**
     * キャンペーン発見きっかけとのリレーション
     */
    public function discoveryTrigger()
    {
        return $this->belongsTo(DiscoveryTrigger::class, 'discovery_trigger_id');
    }

    public function discoveryCustomResponses()
    {
        return $this->hasMany(DiscoveryCustomResponse::class, 'survey_response_id');
    }


    /**
     * info_category_ids 属性のセッター
     * 配列をカンマ区切りの文字列に変換してデータベースに保存するために使用
     *
     * @param array $value 設定する値（配列形式）
     */
    public function setInfoCategoryIdsAttribute($value)
    {
        $this->attributes['info_category_ids'] = implode(',', $value);
    }

    /**
     * info_category_ids 属性のゲッター
     * データベースにカンマ区切りの文字列として保存されている値を配列に変換して返す
     *
     * @param string $value データベースから取得する文字列
     * @return array カンマで分割された文字列を配列に変換したもの
     */
    public function getInfoCategoryIdsAttribute($value)
    {
        return explode(',', $value);
    }
}
