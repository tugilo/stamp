<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfoCategory extends Model
{
    use HasFactory;

    protected $table = 'info_categories';
    protected $guarded = ['id'];
}
