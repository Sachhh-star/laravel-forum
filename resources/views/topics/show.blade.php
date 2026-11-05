<div>
    <h1>{{ $topic->title }}</h1>
    <p>{{ $topic->body }}</p>
    <p>{{ $topic->user->username }}</p>

    <h2>Replies</h2>
    @foreach ($topic->replies as $reply)
    <p>{{ $reply->body }} - {{ $reply->user->username }}</p>
        
    @endforeach

    @auth
    <form method="POST" action="{{ route('replies.store', [$thread, $topic]) }}">
        @csrf

        <textarea name="body" required>{{ old('body') }}</textarea>

        @error('body')
            <p>{{ $message }}</p>
        @enderror

        <button type="submit">Opslaan</button>
    </form>
@endauth

</div>
