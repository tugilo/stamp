<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfoCustomResponse extends Model
{
    use HasFactory;

    protected $table = 'info_custom_responses';
    protected $guarded = ['id'];

    /**
     * 特定の条件下での情報カテゴリのカスタムレスポンスを保存または更新する。
     *
     * @param int $customerId 顧客ID
     * @param array $infoCategoryIds 情報カテゴリIDの配列
     * @param string $customInfoResponse カスタム情報レスポンス
     */
    public static function saveOrUpdate($customerId, $surveyResponseId, $infoCategoryIds, $customInfoResponse)
    {
        // 「その他」のInfoCategory IDを取得
        $otherCategoryId = InfoCategory::where('name', 'その他')->value('id');
    
        // カテゴリ配列に「その他」が含まれ、カスタムレスポンスが提供されている場合
        if (in_array($otherCategoryId, $infoCategoryIds) && !empty($customInfoResponse)) {
            self::updateOrCreate(
                [
                    'customer_id' => $customerId,
                    'survey_response_id' => $surveyResponseId  // ここでIDをセット
                ],
                [
                    'text' => $customInfoResponse
                ]
            );
        }
    }
    
}
