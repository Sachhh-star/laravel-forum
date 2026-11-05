<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use App\Models\Thread;
use App\Models\Topic;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
    public function store(Request $request, Thread $thread, Topic $topic)
    {
        $validated = $request->validate([
            'body' => ['required', 'string'],
        ]);

        Reply::create([
            'body' => $validated['body'],
            'topic_id' => $topic->id,
            'user_id' => $request->user()->id,
        ]);

        return redirect()->route('topics.show', [$thread, $topic]);
    }
}
