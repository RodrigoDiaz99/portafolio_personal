<?php

namespace App\Providers;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        foreach (glob(app_path('Helpers') . '/*.php') as $file) {
            require_once $file;
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        
        // Validar si la tabla 'users' existe antes de consultar
        $user = null;
        try {
            if (\Schema::hasTable('users')) {
                $user = User::where('id', 1)->first();
            }
        } catch (\Exception $e) {
            // Si ocurre un error, no compartir el usuario
            $user = null;
        }
        View::share('user', $user);
     

        View::composer('layouts.backend.app', function ($view) {
            $user = auth()->user();
            $routeName = request()->route()->getName();

            // Mapeo de traducciones o nombres personalizados
            $breadcrumbNames = [
                'admin' => 'Admin',
                'profile' => 'Perfil',
                'social-networks' => 'Redes Sociales',
                'dashboard' => 'Dashboard',
                'skills' => 'Habilidades',
                'work-experiences' => 'Experiencia laboral',
                'educations' => 'Educación',
                'posts' => 'Publicaciones',
                'categories' => 'Categorías',
                'tags' => 'Etiquetas',
                'comments' => 'Comentarios',
                'roles' => 'Roles',
                'users' => 'Usuarios',
                'permissions' => 'Permisos',
                'assign-permissions' => 'Asignar Permisos'
                // Añade aquí más segmentos según lo necesites
            ];

            // Convertir el nombre de la ruta en segmentos de breadcrumb personalizados
            $breadcrumbs = [];
            if ($routeName) {
                foreach (explode('.', $routeName) as $segment) {
                    $breadcrumbs[] = $breadcrumbNames[$segment] ?? ucfirst($segment);
                }
            }

            $view->with([
                'user' => $user,
                'breadcrumbs' => $breadcrumbs
            ]);
        });
    }


}
