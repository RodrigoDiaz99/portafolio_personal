<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Http\Request;
use Livewire\WithPagination;
use App\Models\User;

class Home extends Component
{
    public $name, $message, $src;
    private $pagination = 45;

    use WithPagination;
    
    public function mount()
    {
        $this->src = "";
    }

    public function paginationView()
    {
        return "livewire.pagination";
    }

    public function render()
    {

        return view('livewire.frontend.home.component')
        ->extends('layouts.frontend.app')
        ->section('content');
    }

    public function blog(Request $request)
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
}
