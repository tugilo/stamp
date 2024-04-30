<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresentSyubetsu extends Model
{
    use HasFactory;

    protected $table = 'present_syubetsu';
    protected $guarded = ['id'];
}
