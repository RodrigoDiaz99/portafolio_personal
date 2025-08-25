<?php
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckRole;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ProfileController;
use App\Livewire\Backend\AssignPermissions;
use App\Livewire\Backend\PostCategories;
use App\Livewire\Backend\PostComments;
use App\Livewire\Backend\Dashboard;
use App\Livewire\Backend\Educations;
use App\Livewire\Backend\Permissions;
use App\Livewire\Backend\Posts;
use App\Livewire\Frontend\Blog;
use App\Livewire\Frontend\BlogPost;
use App\Livewire\Backend\Roles;
use App\Livewire\Backend\Skills;
use App\Livewire\Backend\SocialNetworks;
use App\Livewire\Backend\PostTags;
use App\Livewire\Backend\Users;
use App\Livewire\Backend\Profiles;
use App\Livewire\Backend\WorkExperiences;

use App\Livewire\Frontend\Home;





Route::get('/', Home::class);
Route::get('blog', [Home::class, 'blog'])->name('blog');
Route::get('blog/post/{slug}', BlogPost::class)->name('blog.post');
Route::get('blog/categoria/{slug}', [Blog::class, 'postCategoria']);
Route::get('blog/etiqueta/{slug}', [Blog::class, 'postTags']);


Route::get('dashboard', function () {
    return redirect('admin/dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::post('image/upload', [ImageController::class,'upload'])->name('image.upload');
Route::middleware('auth')->group(function () {

    
    
    // Rutas protegidas para super-admin
    Route::middleware([CheckRole::class . ':Super Admin'])->group(function () {
        Route::get('admin/users/roles', Roles::class)->name('admin.users.roles');
        Route::get('admin/users/permissions', Permissions::class)->name('admin.users.permissions');
        Route::get('admin/users/assign-permissions', AssignPermissions::class)->name('admin.users.assign-permissions');
    });
    Route::middleware([CheckRole::class . ':Super Admin,Admin'])->group(function () {
        /** Usuarios */
         Route::get('admin/users', Users::class)->name('admin.users');
     });
    Route::middleware([CheckRole::class . ':Super Admin,Admin,Editor'])->group(function () {
        /** Perfil */
        Route::get('admin/profile/educations', Educations::class)->name('admin.profile.educations');
        Route::get('admin/profile/social-networks', SocialNetworks::class)->name('admin.profile.social-networks');
        Route::get('admin/profile/skills', Skills::class)->name('admin.profile.skills');
        Route::get('admin/profile/work-experiences', WorkExperiences::class)->name('admin.profile.work-experiences');
        /** Posts */
        Route::get('admin/blog/posts', Posts::class)->name('admin.blog.posts');
        Route::get('admin/blog/categories', PostCategories::class)->name('admin.blog.categories');
        Route::get('admin/blog/tags', PostTags::class)->name('admin.blog.tags');
        Route::get('admin/blog/comments', PostComments::class)->name('admin.blog.comments');
    });
    Route::get('admin/profile', Profiles::class)->name('admin.profile');
    Route::get('admin/dashboard', Dashboard::class)->name('admin.dashboard');
    Route::get('admin/logout', [Dashboard::class, 'logout']);

    

    
});

require __DIR__.'/auth.php';
