@extends('layouts.frontend.app')
@section('content')
    <!-- blog -->
    @if (count($posts) > 0 or $cat)
        <section class="container blog" id="blog">
            <!-- Header con Publicidad de Google AdSense -->
            <div class="text-center mb-4">
                <!-- Bloque de código de Google AdSense -->
            </div>
            <div class="box-container-art" style="margin-top: 8rem;">
                <div class="mt-5 d-flex flex-column align-items-center">
                    <form class="d-flex align-items-center" action="{{ url('blog') }}">
                        <div class="search">
                            <input name="src" placeholder="Buscar artículos..." type="text">

                            <button type="submit"><i class="fas fa-search"></i></button>
                        </div>
                    </form>
                </div>
                <div class="row">
                    <div class="col-12 mb-3 mt-3">
                        <div class="card__category">
                            @forelse($categories as $category)
                                @if ($category->Post->count() > 0)
                                    <span class="tag tag-blue text-capitalize"
                                        onclick="window.location.href='{{ url('blog/categoria/' . $category->slug) }}'"
                                        style="cursor: pointer">
                                        {{ $category->name }}
                                    </span>
                                @endif
                            @empty
                                <p class="text-danger text-center h4">Aún sin categorías.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <p class="text-capitalize fs-2  border-bottom border-secondary" style="display: inline-block;">
                        {{ $cat->name }}
                    </p>
                </div>

                <div class="row no-gutter-row d-flex justify-content-center">
                    @forelse($posts as $post)
                        <article class="col-12 col-md-4 col-lg-4 col-xl-4 col-xl-3 mt-3" style="cursor: pointer;"
                            onclick="window.location='{{ url('blog/post/' . $post->slug) }}'">
                            <div class="blog-card ">
                                <div class="card__header d-flex justify-content-center">
                                    <img data-src="{{ asset('storage/posts/thumbnails/' . $post->thumbnails) }}"
                                        alt="{{ $post->thumbnails }}" class="card__image img-fluid lazy" />
                                </div>
                                <div class="card__body">
                                    <span class="tag tag-blue text-capitalize">
                                        <a href="{{ url('blog/categoria/' . $post->cat_slug) }}"
                                            style="text-decoration: none; color: inherit;">
                                            <small>{{ $post->category }}</small>
                                        </a>
                                    </span>
                                    <h4>{{ $post->title }}</h4>
                                    @if ($post->excerpt)
                                        <p>{{ Str::limit(strip_tags($post->excerpt), 250, '...') }}</p>
                                    @else
                                        <p>{{ Str::limit(strip_tags($post->body), 190, '...') }}</p>
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
            </div>
        </section>
    @endif
    <!-- blog ends -->
    <div class="container">
        <div class="mt-5 row">
            <div class="col-12 d-flex justify-content-center">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
    </section>
@endsection
