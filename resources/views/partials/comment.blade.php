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

        {{-- Inline reply form (hidden by default, toggled by JS) --}}
        @auth
            <div class="comment__reply-form" id="reply-form-{{ $comment->id }}" style="display:none;">
                <form method="POST" action="{{ route('comments.store', $product) }}" class="comment-form" style="margin-top:var(--space-3); margin-bottom:0;">
                    @csrf
                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                    <input type="hidden" name="is_roast" value="{{ $comment->is_roast ? '1' : '0' }}">
                    <div class="comment-form__inner">
                        <div class="comment-form__avatar">
                            @if(auth()->user()->avatar)
                                <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="">
                            @else
                                <span>{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                            @endif
                        </div>
                        <div class="comment-form__fields">
                            <textarea name="body" rows="2" maxlength="1000"
                                      placeholder="Write a reply…" required></textarea>
                            <div class="comment-form__actions">
                                <button type="button" class="comment__cancel-reply btn-ghost btn-sm"
                                        data-comment-id="{{ $comment->id }}">Cancel</button>
                                <button type="submit" class="btn-accent btn-sm">Reply</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        @endauth

        {{-- Nested replies --}}
        @if($comment->replies->count())
            <div class="comment-replies">
                @foreach($comment->replies as $reply)
                    @include('partials.comment', ['comment' => $reply, 'product' => $product])
                @endforeach
            </div>
        @endif
    </div>
</div>
