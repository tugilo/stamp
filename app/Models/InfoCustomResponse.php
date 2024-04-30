<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfoCustomResponse extends Model
{
    use HasFactory;

    protected $table = 'info_custom_responses';
    protected $guarded = ['id'];
}
