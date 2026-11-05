<div>

    <h1>{{ $thread->title }}</h1>
    <p>{{ $thread->description }}</p>

    @foreach ($thread->topics as $topic)
    
    <p>{{ $topic->title }} - {{ $topic->user->username }}</p>

    @endforeach
</div>
