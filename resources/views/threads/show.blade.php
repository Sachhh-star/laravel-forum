<div>

    <h1>{{ $thread->title }}</h1>
    <p>{{ $thread->description }}</p>

    @foreach ($thread->topics as $topic)
    
    <p><a href="{{ route('topics.show', [$thread, $topic]) }}">{{ $topic->title }}</a> {{ $topic->user->username }}</p>

    @endforeach
</div>
