<?php

namespace App\Livewire\Backend;

use Livewire\Component;
use App\Models\Post;
use App\Models\PostComment;
use Carbon\Carbon;

class Dashboard extends Component
{
    public $postsCount;
    public $recentPostsCount;
    public $commentsCount;
    public $recentCommentsCount;
    public $viewsCount;
    public $likesCount;
    public $projectsCount;
    public $skillsCount;
    
    public function mount()
    {
        $this->loadPortfolioStats();
        $this->loadBlogStats();
    }

    protected function loadPortfolioStats()
    {
        $this->postsCount = Post::count();
        $this->recentPostsCount = Post::where('created_at', '>', now()->subDays(30))->count();
        $this->commentsCount = PostComment::count();
        $this->recentCommentsCount = PostComment::where('created_at', '>', now()->subWeek())->count();
        
    }

    protected function loadBlogStats()
    {
        // Totales
        $this->viewsCount = Post::sum('views_count');
        $this->likesCount = Post::sum('likes_count');
        
        // Datos para gráficos
        $this->prepareChartData();
    }

    protected function prepareChartData()
    {
        // Datos para gráfico de actividad (últimos 30 días)
        $dates = [];
        $postsData = [];
        $commentsData = [];
        $viewsData = [];
        $likesData = [];
        
        for ($i = 30; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dates[] = $date->format('M d');
            
            $postsData[] = Post::whereDate('created_at', $date)
                ->where('publication_status', 'Publicado')
                ->count();
                
            $commentsData[] = PostComment::whereDate('created_at', $date)->count();
            
            // Sumar vistas y likes por día
            $dailyStats = Post::whereDate('created_at', '<=', $date)
                ->selectRaw('SUM(views_count) as views, SUM(likes_count) as likes')
                ->first();
                
            $viewsData[] = $dailyStats->views ?? 0;
            $likesData[] = $dailyStats->likes ?? 0;
        }

        // Datos para gráfico de posts populares (top 5 por vistas)
        $popularPosts = Post::where('publication_status', 'Publicado')
            ->orderBy('views_count', 'desc')
            ->take(5)
            ->get();

        $this->dates = $dates;
        $this->postsData = $postsData;
        $this->commentsData = $commentsData;
        $this->viewsData = $viewsData;
        $this->likesData = $likesData;
        $this->popularPostsTitles = $popularPosts->pluck('title')->toArray();
        $this->popularPostsViews = $popularPosts->pluck('views_count')->toArray();
        
    }

    public function render()
    {
        return view('livewire.backend.dashboard.dashboard', [
            'postsCount' => $this->postsCount,
            'commentsCount' => $this->commentsCount,
            'recentCommentsCount' => $this->recentCommentsCount,
            'viewsCount' => $this->viewsCount,
            'likesCount' => $this->likesCount,
            'projectsCount' => $this->projectsCount,
            'skillsCount' => $this->skillsCount,
            'dates' => $this->dates ?? [],
            'postsData' => $this->postsData ?? [],
            'commentsData' => $this->commentsData ?? [],
            'likesData' => $this->likesData ?? [],
            'popularPostsTitles' => $this->popularPostsTitles ?? [],
            'popularPostsViews' => $this->popularPostsViews ?? []
        ])
        ->extends('layouts.backend.app')
        ->section('content');
    }

    public function logout()
    {
        auth()->logout();
        return redirect()->route('login');
    }
}