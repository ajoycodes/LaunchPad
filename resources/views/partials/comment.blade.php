<div class="comment" id="comment-{{ $comment->id }}">
    <div class="comment__avatar">
        @if($comment->user->avatar)
            <img src="{{ Storage::url($comment->user->avatar) }}" alt="{{ $comment->user->name }}">
        @else
            <span>{{ strtoupper(substr($comment->user->name, 0, 1)) }}</span>
        @endif
    </div>

    <div class="comment__body">
        <div class="comment__meta">
            <span class="comment__author">{{ $comment->user->name }}</span>
            <span class="comment__time text-muted">· {{ $comment->created_at->diffForHumans() }}</span>
        </div>

        <div class="comment__text">{{ $comment->body }}</div>

        <div class="comment__actions">
            @auth
                <button class="comment__reply-btn" data-comment-id="{{ $comment->id }}">Reply</button>
            @endauth
            @if(auth()->check() && (auth()->id() === $comment->user_id || auth()->user()->isAdmin()))
                <form method="POST" action="{{ route('comments.destroy', $comment) }}" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="comment__delete-btn"
                            onclick="return confirm('Delete this comment?')">Delete</button>
                </form>
            @endif
        </div>
    </div>
</div>
