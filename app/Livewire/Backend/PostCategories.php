<?php
/**
 * PostCategories
 *
 * Componente Livewire para la gestión CRUD de categorías de publicaciones.
 * Permite listar, buscar, crear, editar, eliminar (soft y hard delete), restaurar y paginar categorías de posts.
 * Incluye validación, control de relaciones y feedback visual para el usuario administrador.
 *
 * Funcionalidades principales:
 * - Listado y búsqueda de categorías.
 * - Paginación de resultados.
 * - Creación, edición y validación de categorías.
 * - Eliminación lógica y permanente, y restauración de registros.
 * - Alternancia entre registros activos y eliminados.
 *
 * @category  LivewireComponent
 * @package   App\Livewire\Backend
 * @author    Mario
 * @copyright 2025
 */

namespace App\Livewire\Backend;

use App\Models\PostCategory;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;


/**
 * Clase principal del componente Livewire para la gestión de categorías de publicaciones.
 */
class PostCategories extends Component
{
    use WithPagination;

    /**
     * Propiedades públicas para el manejo de datos y estado del componente.
     * @var string $name Nombre de la categoría.
     * @var string $description Descripción de la categoría.
     * @var string $slug Slug generado para la URL.
     * @var string $search Término de búsqueda.
     * @var int|null $selected_id ID seleccionado para edición o eliminación.
     * @var string $pageTitle Título de la página.
     * @var string $componentName Nombre del componente.
     * @var int $lastItem Último registro mostrado en la página actual.
     * @var int $totalRecord Total de registros encontrados.
     * @var bool $showDeleted Indica si se muestran registros eliminados.
     * @var int $pagination Cantidad de elementos por página.
     */
    public $name, $description, $slug, $search, $selected_id, $pageTitle, $componentName, $lastItem, $totalRecord;
    public $showDeleted = false;
    private $pagination = 24;


    /**
     * Inicializa el componente con valores por defecto.
     */
    public function mount()
    {     
        $this->pageTitle = 'Listado';
        $this->componentName = 'Categorías';
        $this->categoryid = 'Seleccione';
        $this->lastItem = 0;
        $this->totalRecord = 0;
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
     * Alterna el estado para mostrar u ocultar registros eliminados.
     */
    public function toggleShowDeleted()
    {
        $this->showDeleted = !$this->showDeleted;
    }
    

    /**
     * Renderiza la vista principal del componente, obteniendo las categorías según búsqueda y estado.
     * Calcula totales y muestra la paginación.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $categories = $this->showDeleted 
            ? PostCategory::onlyTrashed()->where('name', 'like', '%' . $this->search . '%')->orderBy('id', 'desc')->paginate($this->pagination)
            : PostCategory::where('name', 'like', '%' . $this->search . '%')->orderBy('id', 'desc')->paginate($this->pagination);

        // Obtener el número total de los registros
        $this->totalRecord = $categories->total();

        // Obtener el número de la última entrada en la página actual
        $this->lastItem = $categories->lastItem() ?? 0;

        return view('livewire.backend.post-categories.component', [
            "showing" => 'Viendo '.$this->lastItem ." de ". $this->totalRecord ." registros",
            'categories' => $categories
        ])
        ->extends('layouts.backend.app')
        ->section('content');
    }


    /**
     * Almacena una nueva categoría validando los datos y generando el slug.
     *
     * @param string $modal_state Estado del modal para feedback visual.
     */
    public function Store($modal_state)
    {
        $rules = [
            'name' => 'required|unique:post_categories|max:250',
        ];

        $messages = [
            'name.required' => 'Requerido',
            'name.unique' => 'Ya existe esta categoria',
            'name.max' => 'Máximo 250 caracteres',
        ];

        $this->validate($rules, $messages);
        try {  
            $slug = Str::of($this->name)->slug('-');
            $category = PostCategory::create([
                'user_id' => Auth::id(),
                'name' => $this->name,
                'description' => $this->description,
                'slug' => $slug
            ]);

            $this->dispatch($modal_state);
            $this->resetUI();
            if($modal_state=='added-close'){
                $this->resetUI();
            }
        } catch (Exception $e) {
            $this->dispatch('error','Error: '. $e->getMessage());
        }
    }


    /**
     * Carga los datos de una categoría para su edición.
     *
     * @param PostCategory $category Instancia del modelo a editar.
     */
    public function Edit(PostCategory $category)
    {
        $this->selected_id = $category->id;
        $this->name = $category->name;
        $this->description = $category->description;
        $this->dispatch('show-modal');
    }


    /**
     * Actualiza una categoría existente tras validar los datos y generar el slug.
     *
     * @param string $modal_state Estado del modal para feedback visual.
     */
    public function Update($modal_state)
    {
        $rules = [
            'name' => "required|unique:post_categories,name,{$this->selected_id}|max:250",
        ];
        $messages = [
            'name.required' => 'Requerido',
            'name.unique' => 'Ya existe esta categoria',
            'name.max' => 'Máximo 250 caracteres',
        ];
        $this->validate($rules, $messages);
        try { 
            $category = PostCategory::find($this->selected_id);
            $slug = Str::of($this->name)->slug('-');
            $category->update([
                'name' => $this->name,
                'description' => $this->description,
                'slug' => $slug
            ]);
            $this->dispatch($modal_state);
            if($modal_state=='updated-close'){
                $this->resetUI();
            }
        } catch (Exception $e) {
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
     * Solicita confirmación para eliminar lógicamente una categoría, validando que no tenga posts relacionados.
     * @param int $id
     */
    public function deleteRow($id){
        $this->selected_id = $id;
        $record = PostCategory::find($this->selected_id);
        $count = $record->Post()->withTrashed()->count();
        if ($count > 0) {
            $this->dispatch('error', 'Tiene publicaciones relacionadas');
        } else {
            $this->dispatch('confirmDelete');
        }
    } 


    /**
     * Solicita confirmación para restaurar una categoría eliminada.
     * @param int $id
     */
    public function restoreRow($id){
        $this->selected_id = $id;
        $this->dispatch('confirmRestore');
    }

    /**
     * Solicita confirmación para eliminar permanentemente una categoría.
     * @param int $id
     */
    public function deleteRowPerm($id){
        $this->selected_id = $id;
        $this->dispatch('confirmDeletePerm');
    }



    /**
     * Elimina lógicamente una categoría (soft delete).
     */
    public function Destroy()
    {
        $record = PostCategory::findOrFail($this->selected_id);
        $record->delete();
        $this->dispatch('deleted');
        $this->resetUI();
    }

    /**
     * Restaura una categoría previamente eliminada.
     */
    public function Restore()
    {
        $record = PostCategory::onlyTrashed()->findOrFail($this->selected_id);
        $record->restore();
        $this->dispatch('restore');
        $this->resetUI();
    }

    /**
     * Elimina permanentemente una categoría (hard delete).
     */
    public function forceDelete()
    {
        $record = PostCategory::onlyTrashed()->findOrFail($this->selected_id);
        $record->forceDelete(); // Borra permanentemente el registro
        $this->dispatch('deleted');
        $this->resetUI();
    }


    /**
     * Restaura el estado de la UI y limpia validaciones.
     */
    public function resetUI()
    {
        $this->reset([
            'name',
            'description',
            'selected_id',
        ]);
        $this->resetValidation();
    }
}