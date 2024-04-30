<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // テーブル名の定義
    protected $table = 'users';

    // Mass Assignmentから保護された属性
    protected $guarded = ['id'];

    /**
     * シリアライズから隠すべき属性
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * 属性の型キャスト
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * ユーザーのロールとのリレーション
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
