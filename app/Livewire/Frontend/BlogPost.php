<?php

namespace App\Livewire\Frontend;
use App\Models\Post;
use App\Models\PostTag;
use Livewire\Component;
use App\Models\User;
use App\Models\PostCategory;
use App\Models\PostComment;

class BlogPost extends Component
{
    public int $postId;
    public $parentId = null;
    #[Session(key: 'liked-post-{postId}')]
    public bool $liked = false;
    public $slug;
    public $tags = []; // Tags seleccionados
    public $tags_all = [];
    public $post_instan;
    public $comments = [];
    public $replyContent = '';
    public $commentContent = '';
    public $commentName = '';
    public $commentEmail = '';
    public $showComments = false;
    public bool $showNameFields = false;
    public bool $showButtons = false;
    public bool $showReplyComments = false;
    public $openComments = [];
    public $selectedCommentId = null;
    public int $textareaReplyRows = 1;
    public int $textareaRows = 1;
    public int $page = 1;
    public int $perPage = 5;
    public bool $hasMoreComments = false;
    public function mount($slug)
    {
        $this->slug = $slug;
        $this->tags = [];
        $post = Post::where('slug', $slug)->firstOrFail();
        $this->postId = $post->id;
        $this->loadComments();
    }

    public function render()
    {
        // Verifica si el post existe por su slug
        if (Post::where("slug", $this->slug)->exists()) {
            // Obtiene el post directamente sin usar caché
            $post = Post::join("post_categories as c", "c.id", "posts.post_category_id")
                ->select("posts.*", "c.name as category", "c.slug as slug_cat")
                ->where("posts.slug", $this->slug)
                ->first();

            $this->post_instan = $post;
            
                
            // Verificar si ya se ha registrado la vista (usando una cookie o IP)
            if (!session()->has('viewed_post_' . $post->id)) {
                // Incrementar el contador de vistas
                $post->incrementViews();
                
                // Marcar que el post ha sido visto en esta sesión
                session(['viewed_post_' . $post->id => true]);
            }
            
            // Obtiene las publicaciones relacionadas directamente sin usar caché
            $posts = Post::where("post_category_id", $post->post_category_id)
                ->join("post_categories as c", "c.id", "posts.post_category_id")
                ->select("posts.*", "c.name as category", "c.slug as cat_slug")
                ->where("posts.publication_status", "=", "Publicado")
                ->whereNotIn("posts.id", [$post->id])
                ->orderBy("id", "desc")
                ->limit(6)
                ->get();
                
            
            // Cargar los tags asociados al post
            $this->tags = $post->PostTag->pluck('name', 'slug')->toArray(); // Supone que la relación se llama 'tags'
            $this->tags_all = PostTag::inRandomOrder()->limit(50)->get();

            return view("livewire.frontend.blog.post", [
                "post" => $post,
                "posts" => $posts,
            ])
                ->extends("layouts.frontend.app")
                ->section("content");
        } else {
            return redirect("posts")->with("status", "Url no existe!!");
        }
    }

    public function toggleComments($commentId)
    {
        if (in_array($commentId, $this->openComments)) {
            // Si ya está abierto, cerrarlo
            $this->openComments = array_diff($this->openComments, [$commentId]);
        } else {
            // Si está cerrado, abrirlo
            $this->openComments[] = $commentId;
        }
    }

    public function showForm()
    {
        if (!auth()->check()) {
            $this->showNameFields = true;
            $this->showButtons = true;
            $this->textareaRows = 3;
        }else{
            $this->showButtons = true;
            $this->textareaRows = 3;
        }

        
    }

    public function cancelComment()
    {
        $this->reset(['commentName', 'commentEmail', 'commentContent']);
        $this->showNameFields = false;
        $this->showButtons = false;
        $this->textareaRows = 1;
    }

    public function loadComments()
    {
        $query = PostComment::with(['user', 'replies.user', 'replies.reactions'])
            ->where('post_id', $this->postId)
            ->whereNull('parent_id')
            ->where('status', 'Aprobado')
            ->orderBy('created_at', 'desc');

        $this->comments = $query->paginate($this->perPage, ['*'], 'page', $this->page)->items();
        $this->hasMoreComments = $query->count() > ($this->page * $this->perPage);
    }

    public function loadMoreComments()
    {
        $this->page++;
        $this->loadComments();
    }

    public function submitComment()
    {
        
        if (!auth()->check()) {
            $rules = [
                'commentContent' => 'required|min:3|max:1000',
                'commentName' => 'required|min:3|max:250',
                'commentEmail' => 'required|email|max:250'
            ];

            $messages = [
                'commentContent.required' => 'Requerido',
                'commentContent.min' => 'Minimo 3',
                'commentContent.max' => 'Maximo 1000',
                
                'commentName.required' => 'Requerido',
                'commentName.min' => 'Minimo 3',
                'commentName.max' => 'Maximo 250',

                'commentEmail.required' => 'Requerido',
                'commentEmail.min' => 'Minimo 3',
                'commentEmail.max' => 'Maximo 250',
            ];
        }else{
            $rules = [
            'commentContent' => 'required|min:3|max:1000',
            ];

            $messages = [
                'commentContent.required' => 'Requerido',
                'commentContent.min' => 'Minimo 3',
                'commentContent.max' => 'Maximo 1000',
            ];
        }
        
        $this->validate($rules, $messages);


        PostComment::create([
            'post_id' => $this->postId,
            'parent_id' => $this->parentId,
            'content' => $this->commentContent,
            'user_id' => auth()->id(),
            'name' => auth()->check() ? null : $this->commentName,
            'email' => auth()->check() ? null : $this->commentEmail,
            'status' => auth()->check() ? 'Aprobado' : 'Pendiente'
        ]);
        

        $this->commentContent = '';
        $this->reset(['commentName', 'commentEmail', 'commentContent']);
        $this->showNameFields = false;
        $this->showButtons = false;
        $this->textareaRows = 1;
        
        $this->page = 1; // Resetear a primera página
        $this->loadComments();
    }

    public function showReplyComment($commentId)
        {
            
            if (!auth()->check()) {
                $this->dispatch('notify', 'Debes iniciar sesión para responder. 📝');
                return;
            }
           
            $this->parentId = $commentId;
            $this->showReplyComments = true;
            $this->textareaReplyRows = 3;
            $this->selectedCommentId = $commentId;
             
        }


    public function cancelReplyComment()
    {
        $this->reset(['replyContent']);
        $this->parentId = null;
        $this->showReplyComments = false;
        $this->textareaReplyRows = 1;
    }

    public function submitReply()
    {
        try {
            $validated = $this->validate([
                'replyContent' => 'required|min:3|max:1000'
            ]);

            $newReply = PostComment::create([
                'post_id' => $this->postId,
                'user_id' => auth()->id(),
                'parent_id' => $this->parentId,
                'content' => $validated['replyContent'],
                'status' => auth()->check() ? 'Aprobado' : 'Pendiente'
            ]);

            if (!$newReply) {
                throw new \Exception('Error al guardar la respuesta');
            }

            

            
            // Forzar recarga completa para garantizar reactividad
            $this->loadComments();
            $this->reset(['parentId', 'replyContent']);
            $this->showReplyComments = false;
            

        } catch (\Exception $e) {
            $this->dispatch('notify', $e->getMessage());
        }
    }

    public function likeComment($commentId)
    {
        if (!auth()->check()) {
            $this->dispatch('notify', 'Debes iniciar sesión para dar Me gusta (👍)');
            return;
        }
        
        $comment = PostComment::find($commentId);
        $comment->like(auth()->user());
        $this->loadComments();
    }

    public function dislikeComment($commentId)
    {
        if (!auth()->check()) {
            $this->dispatch('notify','Debes iniciar sesión para dar No me gusta (👎)');
            return;
        }

        $comment = PostComment::find($commentId);
        $comment->dislike(auth()->user());
        $this->loadComments();
    }

    public function like()
    {
        if (! $this->liked) {
            Post::find($this->postId)->increment('likes_count');
            $this->liked = true;
        }
    }

    function formatLikes(int $n, int $precision = 1): string
    {
        if ($n < 1000) {
            return (string) $n;
        }

        $units = ['', 'K', 'M', 'B', 'T'];
        $power = floor(log($n, 1000));
        $value = $n / (1000 ** $power);

        return round($value, $precision) . $units[$power];
    }

}

