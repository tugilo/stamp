<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscoveryCustomResponse extends Model
{
    use HasFactory;

    protected $table = 'discovery_custom_responses';
    protected $guarded = ['id'];

    /**
     * 特定の条件下での応答データを保存または更新する
     *
     * @param int $customerId 顧客ID
     * @param int $surveyResponseId アンケート応答ID
     * @param string $customDiscoveryResponse カスタム応答テキスト
     */
    public static function saveOrUpdate($customerId, $surveyResponseId, $customDiscoveryResponse)
    {
        // 「その他」のDiscoveryTrigger IDを取得
        $otherTriggerId = DiscoveryTrigger::where('name', 'その他')->value('id');

        // 応答が「その他」で、カスタム応答が提供されている場合
        if ($surveyResponseId == $otherTriggerId && !empty($customDiscoveryResponse)) {
            self::updateOrCreate(
                [
                    'customer_id' => $customerId,
                    'survey_response_id' => $surveyResponseId
                ],
                [
                    'text' => $customDiscoveryResponse
                ]
            );
        }
    }
}
