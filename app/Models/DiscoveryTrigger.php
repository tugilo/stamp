<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscoveryTrigger extends Model
{
    use HasFactory;

    protected $table = 'discovery_triggers';
    protected $guarded = ['id'];
}
