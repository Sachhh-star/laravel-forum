<div>
    <h1>{{ $topic->title }}</h1>
    <p>{{ $topic->body }}</p>
    <p>{{ $topic->user->username }}</p>

    <h2>Replies</h2>
    @foreach ($topic->replies as $reply)
    <p>{{ $reply->body }} - {{ $reply->user->username }}</p>
        
    @endforeach

</div>
