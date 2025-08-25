<?php
/**
 * Posts
 *
 * Componente Livewire para la gestión CRUD de publicaciones (posts).
 * Permite listar, buscar, crear, editar, eliminar (soft y hard delete), restaurar y paginar publicaciones.
 * Incluye manejo de imágenes, tags, categorías, validación, control de relaciones y feedback visual para el usuario administrador.
 *
 * Funcionalidades principales:
 * - Listado y búsqueda de publicaciones.
 * - Paginación de resultados.
 * - Creación, edición y validación de publicaciones.
 * - Manejo de imágenes y miniaturas.
 * - Gestión de tags y categorías.
 * - Eliminación lógica y permanente, y restauración de registros.
 * - Alternancia entre registros activos y eliminados.
 *
 * @category  LivewireComponent
 * @package   App\Livewire\Backend
 * @author    Mario
 * @copyright 2025
 */

namespace App\Livewire\Backend;
use Parsedown;
use App\Models\Post;
use App\Models\User;
use App\Models\PostTag;
use Livewire\Component;
use Illuminate\Support\Str;
use App\Models\PostCategory;
use Livewire\Attributes\On; 
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Exception\NotReadableException;
use Intervention\Image\Laravel\Facades\Image;


/**
 * Clase principal del componente Livewire para la gestión de publicaciones.
 */
class Posts extends Component
{
    use WithFileUploads;
    use WithPagination;

    /**
     * Propiedades públicas para el manejo de datos y estado del componente.
     * @var string $title Título de la publicación.
     * @var string $slug Slug generado para la URL.
     * @var int|string $categoryid ID de la categoría seleccionada.
     * @var string $categoryname Nombre de la categoría.
     * @var string $body Contenido de la publicación.
     * @var array $categories Listado de categorías.
     * @var mixed $image Imagen principal.
     * @var mixed $thumbnails Miniatura de la imagen.
     * @var string $excerpt Extracto de la publicación.
     * @var string $publication_status Estado de publicación.
     * @var string $search Término de búsqueda.
     * @var int|null $selected_id ID seleccionado para edición o eliminación.
     * @var string $componentName Nombre del componente.
     * @var string $pageTitle Título de la página.
     * @var int $lastItem Último registro mostrado en la página actual.
     * @var int $totalRecord Total de registros encontrados.
     * @var bool $showDeleted Indica si se muestran registros eliminados.
     * @var array $tags Tags seleccionados.
     * @var string $inputTag Texto actual del input de tags.
     * @var array $suggestions Sugerencias de tags.
     * @var array $postData Datos del post.
     * @var int $pagination Cantidad de elementos por página.
     */
    public $title, $slug, $categoryid, $categoryname, $body, $categories, $image, $thumbnails, $excerpt, $publication_status, $search, $selected_id, $componentName, $pageTitle;
    public $lastItem, $totalRecord, $showDeleted = false;
    public $tags = [];
    public $inputTag = '';
    public $suggestions = [];
    public $postData = [];
    private $pagination = 48;

    /**
     * Inicializa el componente con valores por defecto.
     */
    public function mount()
    {
        $this->pageTitle = 'Listado';
        $this->componentName = 'Publicaciones';
        $this->categoryid = 'Seleccione';
        $this->body = "";
        $this->publication_status = 'Seleccione';
        $this->categories = [];
        $this->lastItem = 0;
        $this->totalRecord = 0;
        $this->tags = [];
    }


    /**
     * Define la vista de paginación personalizada para Livewire.
     * @return string
     */
    public function paginationView()
    {
        return 'livewire.pagination';
    }


    /**
     * Actualiza las sugerencias de tags según el input del usuario.
     */
    public function updatedInputTag()
    {
        if (!empty($this->inputTag)) {
            $this->suggestions = PostTag::where('name', 'like', '%' . $this->inputTag . '%')
                ->limit(5)
                ->pluck('name')
                ->toArray();
        } else {
            $this->suggestions = [];
        }
    }


    /**
     * Agrega un tag a la lista de tags seleccionados.
     * @param string $tagName
     */
    public function addTag($tagName)
    {
        if (!in_array($tagName, $this->tags)) {
            $this->tags[] = $tagName;
        }
        $this->inputTag = '';
        $this->suggestions = [];
    }


    /**
     * Elimina un tag de la lista de tags seleccionados.
     * @param string $tagName
     */
    public function removeTag($tagName)
    {
        $this->tags = array_filter($this->tags, fn($tag) => $tag !== $tagName);
    }


    /**
     * Alterna el estado para mostrar u ocultar registros eliminados.
     */
    public function toggleShowDeleted()
    {
        $this->showDeleted = !$this->showDeleted;
    }


    /**
     * Renderiza la vista principal del componente, obteniendo los posts según búsqueda y estado.
     * Calcula totales y muestra la paginación.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $data = $this->showDeleted 
            ? Post::onlyTrashed()
                ->where('title', 'like', '%' . $this->search . '%')
                ->orderBy('id', 'desc')
                ->paginate($this->pagination)
            : Post::where('title', 'like', '%' . $this->search . '%')
                ->orderBy('id', 'desc')
                ->paginate($this->pagination);

        $this->categories = PostCategory::orderBy('name', 'asc')->get();

        // Obtener el número total de los posts
        $this->totalRecord = $data->total();

        // Obtener el número de la última entrada en la página actual
        $this->lastItem = $data->lastItem() ?? 0;

        return view('livewire.backend.posts.component', [
            'posts' => $data,
            "showing" => 'Viendo '.$this->lastItem ." de ". $this->totalRecord ." registros",
        ])
        ->extends('layouts.backend.app')
        ->section('content');
    }


    /**
     * Almacena una nueva publicación validando los datos, generando slug, guardando imágenes y asociando tags.
     *
     * @param string $modal_state Estado del modal para feedback visual.
     */
    public function Store($modal_state){
        $rules = [
            'title' => 'required|unique:posts,title|max:150',
            'categoryid' => 'required|not_in:Seleccione',
            'publication_status' => 'required|not_in:Seleccione',
            'body' => 'required',
            'image' => 'required'
        ];
        $messages = [
            'title.required' => 'Requerido',
            'title.unique' => 'Ya existe este título',
            'title.max' => 'Máximo 150 caracteres',
            'categoryid.required' => 'Requerido',
            'categoryid.not_in' => 'Requerido',
            'publication_status.required' => 'Requerido',
            'publication_status.not_in' => 'Requerido',
            'body.required' => 'Requerido',
            'image.required' => 'Requerido',
        ];
        $this->validate($rules, $messages);
        $slug = Str::of($this->title)->slug('-');
        try { 
            $post = Post::create([
                'user_id' => Auth::id(),
                'title' => $this->title,
                'slug' => $slug,
                'body' => $this->body,
                'excerpt' => $this->excerpt,
                'post_category_id' => $this->categoryid,
                'publication_status' => $this->publication_status,
            ]);
            if ($post) {
                // Convertir nombres de etiquetas a IDs o crear las que no existan
                $tagIds = [];
                foreach ($this->tags as $tagName) {
                    $tag = PostTag::firstOrCreate(['name' => $tagName]);
                    $tagIds[] = $tag->id;
                }
                // Asociar los tags con el post
                $post->PostTag()->sync($tagIds);
            }
            // Manejo de imágenes
            $customFileName = null;
            $customFileNameThumb = null;
            if (is_array($this->image) || is_object($this->image)) {
                if ($this->image != $post->image) {
                    $oldImage = $post->image;
                    $oldThumbnail = $post->thumbnails;
                    $customFileName = $slug . '-' . uniqid();
                    $customFileNameThumb = $slug . '-' . uniqid();
                    Image::read($this->image)
                        ->scale(width: 1280)
                        ->toJpg(100)
                        ->save(public_path('storage/posts/' . $customFileName . '.jpg'));
                    Image::read($this->image)
                        ->scale(width: 1280)
                        ->toWebp(50)
                        ->save(public_path('storage/posts/thumbnails/' . $customFileNameThumb . '.webp'));
                    $post->image = $customFileName . '.jpg';
                    $post->thumbnails = $customFileNameThumb . '.webp';
                    $post->save();
                    if ($oldImage && file_exists(public_path('storage/posts/' . $oldImage))) {
                        unlink(public_path('storage/posts/' . $oldImage));
                    }
                    if ($oldThumbnail && file_exists(public_path('storage/posts/thumbnails/' . $oldThumbnail))) {
                        unlink(public_path('storage/posts/thumbnails/' . $oldThumbnail));
                    }
                }
            }
            $this->dispatch($modal_state);
            $this->resetUI();
        } catch (NotReadableException $e) {
            $this->dispatch('error','Error: '. $e->getMessage());
        }
    }


    /**
     * Carga los datos de un post para su edición, incluyendo tags y despacha eventos para la UI.
     * @param Post $post Instancia del modelo a editar.
     */
    public function Edit(Post $post)
    {
        $this->selected_id = $post->id;
        $this->categoryid = $post->post_category_id;
        $this->title = $post->title;
        $this->excerpt = $post->excerpt;
        $this->body = $post->body;
        $this->image = $post->image;
        $this->thumbnails = $post->thumbnails;
        $this->slug = $post->slug;
        $this->publication_status = $post->publication_status;
        $this->tags = $post->PostTag->pluck('name')->toArray();
        $this->dispatch('edit-description', $this->body);
        $this->dispatch('show-modal-post');
    }



    /**
     * Actualiza una publicación existente tras validar los datos, guardar imágenes y asociar tags.
     *
     * @param string $modal_state Estado del modal para feedback visual.
     */
    public function Update($modal_state)
    {
        $rules = [
            'title' => "required|unique:posts,title,{$this->selected_id}|max:150",
            'categoryid' => 'required|not_in:Seleccione',
            'body' => 'required',
            'image' => 'required'
        ];
        $messages = [
            'title.required' => 'Requerido',
            'title.unique' => 'Ya existe este título',
            'title.max' => 'Máximo 150 caracteres',
            'categoryid.required' => 'Requerido',
            'categoryid.not_in' => 'Requerido',
            'body.required' => 'Requerido',
            'image.required' => 'Requerida',
        ];
        $this->validate($rules, $messages);
        $slug = Str::of($this->title)->slug('-');
        try {
            $post = Post::find($this->selected_id);
            $post->update([
                'user_id' => Auth::id(),
                'title' => $this->title,
                'slug' => $slug,
                'body' => $this->body,
                'excerpt' => $this->excerpt,
                'post_category_id' => $this->categoryid,
                'publication_status' => $this->publication_status,
            ]);
            if ($post) {
                $tagIds = [];
                foreach ($this->tags as $tagName) {
                    $tag = PostTag::firstOrCreate(['name' => $tagName]);
                    $tagIds[] = $tag->id;
                }
                $post->PostTag()->sync($tagIds);
            }
            $customFileName = null;
            $customFileNameThumb = null;
            if (is_array($this->image) || is_object($this->image)) {
                if ($this->image != $post->image) {
                    $imagen = $this->image;
                    $customFileName = $slug . '-' . uniqid();
                    $customFileNameThumb = $slug . '-' . uniqid();
                    Image::read($imagen)
                        ->scale(width: 1280)
                        ->toJpg(100)
                        ->save(public_path('storage/posts/' . $customFileName . '.jpg'));
                    Image::read($imagen)
                        ->scale(width: 1280)
                        ->toWebp(50)
                        ->save(public_path('storage/posts/thumbnails/' . $customFileNameThumb . '.webp'));
                    $oldImage = $post->image;
                    $oldThumb = $post->thumbnails;
                    $post->image = $customFileName . '.jpg';
                    $post->thumbnails = $customFileNameThumb . '.webp';
                    $post->save();
                    if ($oldImage && file_exists(public_path('storage/posts/' . $oldImage))) {
                        unlink(public_path('storage/posts/' . $oldImage));
                    }
                    if ($oldThumb && file_exists(public_path('storage/posts/thumbnails/' . $oldThumb))) {
                        unlink(public_path('storage/posts/thumbnails/' . $oldThumb));
                    }
                }
            } else {
                $this->dispatch('post-error', 'Error: No es una imagen');
            }
            $this->dispatch($modal_state);
            if ($modal_state == 'updated-close') {
                $this->resetUI();
            }
        } catch (NotReadableException $e) {
            $this->dispatch('error','Error: '. $e->getMessage());
        }
    }


    /**
     * Listeners de eventos Livewire para acciones de eliminación y restauración.
     */
    protected $listeners = [
        'Destroy' => 'Destroy',
        'Restore' => 'Restore',
        'forceDelete' => 'forceDelete',
    ];


    /**
     * Solicita confirmación para eliminar lógicamente una publicación.
     * @param int $id
     */
    public function deleteRow($id){
        $this->selected_id = $id;
        $this->dispatch('confirmDelete');
    }

    /**
     * Solicita confirmación para restaurar una publicación eliminada.
     * @param int $id
     */
    public function restoreRow($id){
        $this->selected_id = $id;
        $this->dispatch('confirmRestore');
    }

    /**
     * Solicita confirmación para eliminar permanentemente una publicación.
     * @param int $id
     */
    public function deleteRowPerm($id){
        $this->selected_id = $id;
        $this->dispatch('confirmDeletePerm');
    }

    

    /**
     * Elimina lógicamente una publicación (soft delete).
     */
    public function Destroy()
    {
        $data = Post::withTrashed()->findOrFail($this->selected_id);
        $data->delete();
        $this->dispatch('deleted');
        $this->resetUI();
    }

    /**
     * Restaura una publicación previamente eliminada.
     */
    public function Restore()
    {
        $data = Post::onlyTrashed()->findOrFail($this->selected_id);
        $data->restore();
        $this->dispatch('restore');
        $this->resetUI();
    }

    /**
     * Elimina permanentemente una publicación (hard delete) y sus imágenes asociadas.
     */
    public function forceDelete()
    {
        $data = Post::onlyTrashed()->findOrFail($this->selected_id);
        $oldImage = $data->image;
        $oldThumb = $data->thumbnails;
        if ($oldImage && file_exists(public_path('storage/posts/' . $oldImage))) {
            unlink(public_path('storage/posts/' . $oldImage));
        }
        if ($oldThumb && file_exists(public_path('storage/posts/thumbnails/' . $oldThumb))) {
            unlink(public_path('storage/posts/thumbnails/' . $oldThumb));
        }
        $data->forceDelete();
        $this->dispatch('deleted');
        $this->resetUI();
    }


    /**
     * Restaura el estado de la UI y limpia validaciones, reseteando campos y despachando eventos para la vista.
     */
    public function resetUI()
    {
        $this->dispatch('resetTitleExcerpt');
        $this->dispatch('reset-category');
        $this->dispatch('clear-description');
        $this->dispatch('clear-imagen');
        $this->dispatch('clearPreviews');
        $this->reset(['categoryid']);
        $this->categoryid = 'Seleccione';
        $this->image = null;
        $this->categories = [];
        $this->tags = [];
        $this->reset([
            'title',
            'slug',
            'categoryid',
            'categoryname',
            'body',
            'categories',
            'image',
            'thumbnails',
            'excerpt',
            'search',
            'selected_id',
            'publication_status',
            'tags'
        ]);
        $this->publication_status = 'Seleccione';
        $this->resetValidation();
    }
}