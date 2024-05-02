<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerPresent extends Model
{
    use HasFactory;

    // テーブル名の定義
    protected $table = 'customer_present';

    // Mass Assignmentから保護された属性
    protected $guarded = ['id'];

    /**
     * このエントリに関連する顧客
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * このエントリに関連するプレゼント
     */
    public function present()
    {
        return $this->belongsTo(Present::class, 'present_id');
    }
}
