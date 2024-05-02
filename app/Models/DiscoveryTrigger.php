<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * DiscoveryTrigger モデル
 *
 * アンケートの「発見トリガー」情報を管理します。これには、ユーザーがキャンペーンやサービスを
 * どのようにして知ったかについてのデータが含まれます（例: 広告、SNS、友人の推薦など）。
 */
class DiscoveryTrigger extends Model
{
    use HasFactory;

    /**
     * このモデルが使用するテーブル名。
     *
     * @var string
     */
    protected $table = 'discovery_triggers';

    /**
     * マスアサインメントから保護される属性の配列。
     * 'id'フィールドは自動でインクリメントされるため、外部からの代入を防ぎます。
     *
     * @var array
     */
    protected $guarded = ['id'];

    // 他の関連するモデルや追加のメソッドがあればここに記述します
}
