<li class="comment-item">
    <div class="comment">
        <img src="https://placehold.co/40x40/3b82f6/FFFFFF?text=A" alt="Avatar" class="comment-avatar">
        <div class="comment-body">
            <div class="comment-header">
                {{ $comment->user->name ?? $comment->name }}
                @if($comment->user_id === $post['user_id'])
                <span class="badge bg-secondary ms-2">Autor</span>
                @endif
                <span class="comment-date">{{ $comment->created_at->diffForHumans() }}</span>
            </div>
            <p class="comment-text">
                {{ $comment->content }}
            </p>
            <div class="comment-actions">
                <button wire:click="likeComment('{{ $comment['id'] }}')" class="like-btn"><i class="fa-regular fa-thumbs-up"></i></button>
                <span class="likes">{{ $comment->likes_count }}</span>
                <button wire:click="dislikeComment('{{ $comment['id'] }}')" class="like-btn"><i class="fa-regular fa-thumbs-down"></i></button>
                <span class="likes">{{ $comment->dislikes_count }}</span>
                @php
                $count = $comment->replies->count();
                $texto = $count . ' ' . Str::plural('Respuesta', $count);
                @endphp
                <button wire:click="toggleComments('{{ $comment['id'] }}')" class="btn btn-sm btn-light text-primary">
                {{ in_array($comment['id'], $openComments) ? 'Ocultar respuestas (' . $count . ')' : $texto }}
                </button>
                <button wire:click="showReplyComment('{{ $comment['id'] }}')" class="reply-btn">Responder</button>
            </div>
            @if ($showReplyComments && $selectedCommentId == $comment->id)
            <div>
                @error('replyContent') 
                <small class="text-danger">{{ $message }}</small>
                @enderror
                <textarea
                    class="form-control"
                    rows="{{ $textareaReplyRows }}"
                    placeholder="Responder comentario..."
                    wire:model="replyContent"
                    required
                    ></textarea>
                <div class="comment-form-actions d-flex gap-2 mt-2" id="main-comment-actions">
                    <button type="button" class="btn btn-secondary btn-sm" wire:click="cancelReplyComment">Cancelar</button>
                    <button wire:click="submitReply" type="button" class="btn btn-primary btn-sm">Comentar</button>
                </div>
            </div>
            @endif
            {{-- Respuestas anidadas --}}
            @if (in_array($comment->id, $openComments))
            @if($comment->replies->count())
            <ul class="comment-thread mt-3">
                @foreach($comment->replies as $reply)
                @include('livewire.frontend.blog.post-comments', ['comment' => $reply])
                @endforeach
            </ul>
            @endif
            @endif
        </div>
    </div>
</li>
