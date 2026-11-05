<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use Illuminate\Http\Request;

class ThreadController extends Controller
{
    public function index()
    {
        $threads = Thread::latest()->get();

        return view('threads.index', ['threads' => $threads]);
    }

    public function show(Thread $thread)
    {
        return view('threads.show', ['thread' => $thread]);
    }

    public function create()
    {
        return view('threads.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
        ]);

        $thread = Thread::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'user_id' => $request->user()->id,
        ]);

        return redirect()->route('threads.show', $thread);
    }

    public function edit(Request $request, Thread $thread)
    {
        abort_unless($request->user()->canEdit($thread), 403);

        return view('threads.edit', ['thread' => $thread]);
    }

    public function update(Request $request, Thread $thread)
    {
        abort_unless($request->user()->canEdit($thread), 403);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
        ]);

        $thread->update($validated);

        return redirect()->route('threads.show', $thread);
    }

    public function destroy(Thread $thread)
    {
        $thread->delete();

        return redirect()->route('home');
    }
}
