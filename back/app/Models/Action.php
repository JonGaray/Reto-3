<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    protected $fillable = ['name', 'description', 'date_init', 'date_end', 'age', 'languaje', 'start_time', 'capacity', 'price', 'duration', 'center_id', 'category'];
    public function center()
    {
        return $this->belongsTo(Center::class);
    }
    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}
