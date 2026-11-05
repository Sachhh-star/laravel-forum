<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Thread;

class ThreadController extends Controller
{
    public function index() {
        $threads = Thread::latest()->get();
        return view('threads.index', ['threads' => $threads]);
    }

    public function show(Thread $thread) {
        return view('threads.show', ['thread' => $thread]);
    }

}
