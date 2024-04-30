<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscoveryCustomResponse extends Model
{
    use HasFactory;

    protected $table = 'discovery_custom_responses';
    protected $guarded = ['id'];
}
