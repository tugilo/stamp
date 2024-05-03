<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    // テーブル名の定義
    protected $table = 'events';

    // Mass Assignmentから保護された属性
    protected $guarded = ['id'];
    
    protected $dates = ['event_date', 'end_date'];
    /**
     * このイベントを主催する組織
     */
    public function organizer()
    {
        return $this->belongsTo(Organizer::class, 'organizer_id');
    }

    /**
     * このイベントの開催会場
     */
    public function venue()
    {
        return $this->belongsTo(Venue::class, 'venue_id');
    }

    /**
     * このイベントが開催されるエリア
     */
    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    /**
     * このイベントが開催される都市
     */
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    /**
     * このイベントに関連する参加情報を取得します。
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function participations()
    {
        return $this->hasMany(EventParticipation::class, 'event_id');
    }
}
