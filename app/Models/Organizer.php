<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organizer extends Model
{
    use HasFactory;

    protected $table = 'organizers';
    protected $guarded = ['id'];

    public function events()
    {
        return $this->hasMany(Event::class, 'organizer_id');
    }
}
