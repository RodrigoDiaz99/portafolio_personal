<?php

namespace App\Livewire\Frontend;
use App\Models\Post;
use App\Models\User;
use App\Models\PostTag;
use Livewire\Component;
use App\Models\PostCategory;
use Illuminate\Http\Request;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Redirect;

class Blog extends Component
{
    use WithPagination;
    public $message;
    private $pagination = 45;

    public function mount()
    {
        $this->src = "";
    }

    public function paginationView()
    {
        return "livewire.pagination";
    }

    public function render(Request $request)
    {
    
    $this->src = $request->get("src");
    $message = "";

    if (request()->has('src') && strlen(request()->get('src')) > 0) {
        $src = request()->get('src');
        $posts = Post::select(
                "posts.*", 
                "c.name as category", 
                "c.slug as cat_slug"
            )
            ->leftJoin("post_categories as c", "c.id", "posts.post_category_id")
            ->where("posts.publication_status", "=", 'Publicado')
            ->where(function ($query) use ($src) {
                $query->where("posts.title", "like", "%" . $src . "%")
                      ->orWhere("c.name", "like", "%" . $src . "%");
            })
            ->orderBy("posts.title", "asc")
            ->paginate($this->pagination);
    
    } else {
        $posts = Post::join("post_categories as c", "c.id", "posts.post_category_id")
            ->select("posts.*", "c.name as category", "c.slug as cat_slug")
            ->where("posts.publication_status", "=", 'Publicado')
            ->orderBy("posts.id", "desc")
            ->paginate($this->pagination);
    }
    
        // Obtener categorías
        $categories = PostCategory::orderBy("name", "asc")->get();

        // Retornar la vista
        return view("livewire.frontend.blog.component", [
        "posts" => $posts,
        "categories" => $categories,          
        "message" => $message,
        "search" => $this->src,
        ])
        ->extends("layouts.frontend.app")
        ->section("content");
        }

        public function postCategoria(Request $request, $slug)
        {

            if (PostCategory::where("slug", $slug)->exists()) {
                $cat = PostCategory::where("slug", $slug)->first();
                
                // Intenta obtener las publicaciones de la categoría del cache
                $posts = Post::where("posts.post_category_id", $cat->id)
                ->where(function ($query) {
                    $query->where("posts.publication_status", "Publicado");
                })
                ->join("post_categories as cat", "cat.id", "posts.post_category_id")
                ->select("posts.*", "cat.name as category", "cat.slug as cat_slug")
                ->orderBy("id", "desc")
                ->paginate(6);

                // Intenta obtener las categorías del cache
                $categories = PostCategory::orderBy("name", "asc")->get();


                return view("livewire.frontend.blog.post-categoria", [
                    "posts" => $posts,
                    "cat" => $cat,
                    "categories" => $categories,
                ])
                    ->extends("layouts.frontend.app")
                    ->section("content");
            } else {
                return redirect("/")->with("status", "Url no existe!!");
            }
        }

        public function postTags(Request $request, $slug)
        {
            if (PostTag::where("slug", $slug)->exists()) {
                $tag = PostTag::where("slug", $slug)->first();
                
                $posts = Post::select(
                    "posts.*", 
                    "c.name as category", 
                    "c.slug as cat_slug"
                )
                ->join("post_post_tag as pt", "pt.post_id", "posts.id")
                ->join("post_tags as t", "t.id", "pt.post_tag_id") // Relación correcta
                ->leftJoin("post_categories as c", "c.id", "posts.post_category_id")
                ->where("posts.publication_status", "=", 'Publicado')
                ->where("t.slug",$tag->slug) // Usar LIKE para que encuentre tags con varias palabras
                ->orderBy("posts.title", "asc")
                ->paginate($this->pagination);

                // Intenta obtener las categorías del cache
                $categories = PostCategory::orderBy("name", "asc")->get();

                return view("livewire.frontend.blog.post-tag", [
                    "posts" => $posts,
                    "tag" => $tag,
                    "categories" => $categories,
                ])
                    ->extends("layouts.theme-shop.app")
                    ->section("content");
            } else {
                return redirect("/")->with("status", "Url no existe!!");
            }
        }
}


