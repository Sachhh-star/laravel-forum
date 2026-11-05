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

    public function edit(Request $request, Thread $thread, Topic $topic, Reply $reply)
    {
        abort_unless($request->user()->canEdit($reply), 403);

        return view('replies.edit', ['thread' => $thread, 'topic' => $topic, 'reply' => $reply]);
    }

    public function update(Request $request, Thread $thread, Topic $topic, Reply $reply)
    {
        abort_unless($request->user()->canEdit($reply), 403);

        $validated = $request->validate([
            'body' => ['required', 'string'],
        ]);

        $reply->update($validated);

        return redirect()->route('topics.show', [$thread, $topic]);
    }

    public function destroy(Thread $thread, Topic $topic, Reply $reply)
    {
        $reply->delete();

        return redirect()->route('topics.show', [$thread, $topic]);
    }
}
