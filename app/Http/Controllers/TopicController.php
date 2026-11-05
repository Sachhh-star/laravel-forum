<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use App\Models\Topic;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    public function show(Thread $thread, Topic $topic) {
        return view('topics.show', ['thread' => $thread, 'topic' => $topic]);
    }

}
