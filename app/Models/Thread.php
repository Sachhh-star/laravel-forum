<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function topics() {
        return $this->hasMany(Topic::class);
    }
}
