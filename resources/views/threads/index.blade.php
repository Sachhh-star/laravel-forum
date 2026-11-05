<div>

    <h1>Software Development Forum</h1>
    @foreach ( $threads as $thread )

    <h2><a href="{{ route('threads.show', $thread) }}">{{ $thread->title }}</a></h2>
    <p>{{ Str::limit($thread->description, 80) }}</p>

    @endforeach

</div>
