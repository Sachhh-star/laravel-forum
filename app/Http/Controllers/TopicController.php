<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use App\Models\Topic;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    public function show(Thread $thread, Topic $topic)
    {
        return view('topics.show', ['thread' => $thread, 'topic' => $topic]);
    }

    public function store(Request $request, Thread $thread)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
        ]);

        $topic = Topic::create([
            'title' => $validated['title'],
            'body' => $validated['body'],
            'thread_id' => $thread->id,
            'user_id' => $request->user()->id,
        ]);

        return redirect()->route('topics.show', [$thread, $topic]);
    }

    public function edit(Request $request, Thread $thread, Topic $topic)
    {
        abort_unless($request->user()->canEdit($topic), 403);

        return view('topics.edit', ['thread' => $thread, 'topic' => $topic]);
    }

    public function update(Request $request, Thread $thread, Topic $topic)
    {
        abort_unless($request->user()->canEdit($topic), 403);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
        ]);

        $topic->update($validated);

        return redirect()->route('topics.show', [$thread, $topic]);
    }

    public function destroy(Thread $thread, Topic $topic)
    {
        $topic->delete();

        return redirect()->route('threads.show', $thread);
    }
}
