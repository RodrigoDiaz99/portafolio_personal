
<div>
    <link href="{{ asset('panel/prismjs/prism.css') }}" rel="stylesheet" type="text/css" />
    <section class="container mb-0 mt-5">
        <div class="row">
            <article class="col-12 col-md-9 col-lg-9  mt-5 card-posts" id="post-container">
                <div class="mb-0 bg-white" id="post-container">
                    <div class="p-2">
                        <div class="card-header bg-white w-100 mx-auto">
                            <h1 class="text-dark text-uppercase text-center"><strong>{{ $post->title }}</strong></h1>
                        </div>
                        <div class="text-center text-muted p-2 fs-6">
                            <span class="p-2">{{ $post->User->name }}</span> |
                            <span class="p-2">
                            {{ \Carbon\Carbon::parse($post->created_at)->locale(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale())->isoFormat('LL') }}
                            </span>
                            |
                            Me gusta <span class="badge bg-danger">{{ formatCount($post->likes_count) }}</span>
                            |
                            Vistas <span class="badge bg-secondary">{{ formatCount($post->views_count) }}</span>
                        </div>
                        <div wire:ignore>
                            <img data-src="{{ asset('storage/posts/thumbnails/' . $post->thumbnails) }}"
                                data-src="{{ asset('storage/posts/' . $post->image) }}" alt="{{ $post->image }}"
                                class="lazy d-block m-auto"
                                style="max-width: 100%;max-height: 550px;object-fit: contain">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="p-2 card__tag text-center w-75 mx-auto">
                            @foreach ($tags as $slug => $tag)
                            <span class="tag tag-blue text-capitalize"
                                onclick="window.location.href='{{ url('blog/etiqueta/' . $slug) }}'"
                                style="cursor: pointer">
                            {{ $tag }}
                            </span>
                            @endforeach
                        </div>
                        <ul class="redes">
                            <a href="http://www.facebook.com/sharer.php?u=<?php echo url()->full(); ?>"
                                aria-label="Compartir artículo en facebook" target="_BLANK"
                                class="text-decoration-none">
                                <li class="icon facebook bg-primary">
                                    <span class="tooltip">Facebook</span>
                                    <span><i class="fab fa-facebook-f text-white"></i></span>
                                </li>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url=<?php echo url()->full(); ?>"
                                aria-label="Compartir artículo en twitter" target="_BLANK" class="text-decoration-none">
                                <li class="icon twitter" style="background:#1DA1F2">
                                    <span class="tooltip">Twitter</span>
                                    <span><i class="fab fa-twitter text-white"></i></span>
                                </li>
                            </a>
                            <a href="https://www.linkedin.com/shareArticle?url=<?php echo url()->full(); ?>"
                                aria-label="Compartir artículo en linkedin" target="_BLANK"
                                class="text-decoration-none">
                                <li class="icon linkedin" style="background:#2b7cbe">
                                    <span class="tooltip">Linkedin</span>
                                    <span><i class="fab fa-linkedin text-white"></i></span>
                                </li>
                            </a>
                            <a href="https://www.pinterest.com/pin/create/button/?url={{ request()->url() }}&media={{ asset('storage/posts/' . $post->image) }}&description={{ $post->title }}"
                                data-pin-do="buttonBookmark" aria-label="Compartir artículo en pinterest"
                                target="_BLANK" class="text-decoration-none">
                                <li class="icon pinterest bg-danger">
                                    <span class="tooltip">Pinterest</span>
                                    <span><i class="fab fa-pinterest text-white"></i></span>
                                </li>
                            </a>
                            <div class="text-center">
                                <a href="javascript:void(0)"
                                    wire:click="like"
                                    wire:loading.attr="disabled"
                                    aria-label="{{ $liked ? 'Te gusta' : 'Me gusta' }}"
                                    class="text-decoration-none">
                                    <li class="icon like {{ $liked ? 'like-click' : 'like' }}"
                                        style="width:35px; height:35px; border-radius:50%;
                                        display:inline-flex; align-items:center; justify-content:center;"
                                        class="">
                                        <i class="fa-solid fa-heart fs-4"></i>
                                        <span class="tooltip">
                                        {{ $liked ? 'Te gusta' : 'Me gusta' }}
                                        </span>
                                    </li>
                                </a>
                            </div>
                        </ul>
                        <div class="roboto-font mt-5 p-5">
                            {!! $post->body !!}
                        </div>
                    </div>
                </div>
            </article>
            <div class="col-12 col-md-3 col-lg-3 mt-5 mb-5 p-2 text-center">
                <p class="text-capitalize fs-2  border-bottom border-secondary" style="display: inline-block;">
                    Etiquetas
                </p>
                <div class="p-2 card__tag" {{ $liked ? 'wire:ignore' : '' }}>
                @foreach ($tags_all as $tag)
                @if ($tag->Post->count() > 0)
                <!-- Verifica si el tag tiene posts relacionados -->
                <span class="tag tag-blue text-capitalize"
                    onclick="window.location.href='{{ url('blog/etiqueta/' . $tag->slug) }}'"
                    style="cursor: pointer">
                {{ $tag->name }}
                </span>
                @endif
                @endforeach
            </div>
        </div>
</div>
</section>
<section class="container mb-5 p-5">
    @if (count($posts) > 0)
    <div class="text-center">
        <p class="text-capitalize fs-2 border-bottom border-secondary" style="display: inline-block;">
            Artículos relacionados
        </p>
    </div>
    <div class="row no-gutter-row d-flex justify-content-center">
        @forelse($posts as $post)
        <article
            class="blog-card col-12 col-sm-6 col-md-4 col-lg-4 col-xl-4 d-flex justify-content-center flex-column m-2"
            style="cursor: pointer;" onclick="window.location='{{ url('blog/post/' . $post->slug) }}'">
            <div class="card__header d-flex justify-content-center">
                <img data-src="{{ asset('storage/posts/thumbnails/' . $post->thumbnails) }}"
                    alt="{{ $post->thumbnails }}" class="card__image img-fluid lazy" />
            </div>
            <div class="card__body">
                <span class="tag tag-blue text-capitalize">
                <a href="{{ url('post-categoria/' . $post->PostCategory->slug) }}"
                    style="text-decoration: none; color: inherit;">
                <small>{{ $post->PostCategory->name }}</small>
                </a>
                </span>
                <h4>{{ $post->title }}</h4>
                @if ($post->excerpt)
                <p>{{ $post->excerpt }}</p>
                @else
                <p>{{ Str::limit(strip_tags($post->body), 200, '...') }}</p>
                @endif
            </div>
            <div class="card__footer">
                <div class="user">
                    <img data-src="{{ asset('storage/' . $post->user->imagen) }}" alt="user__image"
                        class="user__image lazy">
                    <div class="user__info">
                        <h5>{{ $post->user->name }}</h5>
                        <small>{{ $post->created_at->diffForHumans() }}</small>
                    </div>
                </div>
            </div>
        </article>
        @empty
        <div class="text-center">
            <p class="text-primary h3">Aún no tenemos publicaciones disponibles en nuestro blog.
                ¡Pronto podrás disfrutar de nuevo contenido!
            </p>
            <img class="img-fluid w-50" data-src="{{ asset('storage/posts/blog.jpg') }}" alt="blog.jpg"
                class="lazy">
        </div>
        @endforelse
    </div>
    @endif
    <div class="row">
        @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif
        <div class="col-12 col-lg-10 col-md-10 col-xl-10">
            <div class="comment-section-container">
                <h3 class="mb-4">{{ is_array($comments) ? count($comments) : $comments->count() }} comentarios</h3>
                <!-- Formulario para un nuevo comentario principal -->
                <div class="main-comment-form mb-4">
                    <div class="d-flex align-items-start gap-3">
                        <img src="https://placehold.co/40x40/64748B/FFFFFF?text=TÚ" alt="Tu avatar" class="comment-avatar">
                        <div class="flex-grow-1">
                            {{-- Textarea con wire:focus para mostrar el formulario --}}
                            @error('commentContent')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                            <textarea
                                id="main-comment-textarea"
                                class="form-control"
                                rows="{{ $textareaRows }}"
                                placeholder="Añadir un comentario..."
                                wire:model="commentContent"
                                wire:focus="showForm"
                                required
                                ></textarea>
                            {{-- Campos nombre y email solo si $showNameFields --}}
                            @if ($showNameFields)
                            <div class="mb-3 mt-2">
                                @error('commentName') 
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                                <input wire:model="commentName" type="text" class="form-control" placeholder="Tu nombre" required>
                            </div>
                            <div class="mb-3">
                                @error('commentEmail') 
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                                <input wire:model="commentEmail" type="email" class="form-control" placeholder="Tu correo electrónico" required>
                            </div>
                            @endif
                            {{-- Botones solo si $showButtons --}}
                            @if ($showButtons)
                            <div class="comment-form-actions d-flex gap-2 mt-2" id="main-comment-actions">
                                <button type="button" class="btn btn-secondary btn-sm" wire:click="cancelComment">Cancelar</button>
                                <button wire:click="submitComment" type="button" class="btn btn-primary btn-sm">Comentar</button>
                            </div>
                            @endif
                        </div>
                    </div>
                    {{-- Mensaje flash --}}
                    @if (session()->has('message'))
                    <div class="alert alert-success mt-3">
                        {{ session('message') }}
                    </div>
                    @endif
                </div>
                <!-- Lista de Comentarios -->
                <ul class="comment-thread">
                    @if(count($comments))
                    @foreach($comments as $comment)
                    @include('livewire.frontend.blog.post-comments', ['comment' => $comment])
                    @endforeach
                    @else
                    <div class="alert alert-info">
                        No hay comentarios aún. ¡Sé el primero en comentar!
                    </div>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</section>
@include('livewire.events')
<script src="{{ asset('panel/prismjs/prism.js') }}" async></script>
</div>
