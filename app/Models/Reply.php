<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['body', 'user_id', 'topic_id'])]

class Reply extends Model
{
    public function topic() {
        return $this->belongsTo(Topic::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
