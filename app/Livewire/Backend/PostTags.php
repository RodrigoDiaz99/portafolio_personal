<?php
/**
 * PostTags
 *
 * Componente Livewire para la gestión CRUD de etiquetas (tags) de publicaciones.
 * Permite listar, buscar, crear, editar, eliminar (soft y hard delete), restaurar y paginar etiquetas.
 * Incluye validación, control de relaciones y feedback visual para el usuario administrador.
 *
 * Funcionalidades principales:
 * - Listado y búsqueda de etiquetas.
 * - Paginación de resultados.
 * - Creación, edición y validación de etiquetas.
 * - Eliminación lógica y permanente, y restauración de registros.
 * - Alternancia entre registros activos y eliminados.
 *
 * @category  LivewireComponent
 * @package   App\Livewire\Backend
 * @author    Mario
 * @copyright 2025
 */

namespace App\Livewire\Backend;
use App\Models\PostTag;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;


/**
 * Clase principal del componente Livewire para la gestión de etiquetas de publicaciones.
 */
class PostTags extends Component
{
    use WithPagination;

    /**
     * Propiedades públicas para el manejo de datos y estado del componente.
     * @var string $name Nombre de la etiqueta.
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
    public $name, $slug, $search, $selected_id, $pageTitle, $componentName, $lastItem, $totalRecord;
    public $showDeleted = false;
    private $pagination = 24;


    /**
     * Inicializa el componente con valores por defecto.
     */
    public function mount()
    {  
        $this->pageTitle = 'Listado';
        $this->componentName = 'Etiquetas';
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
     * Renderiza la vista principal del componente, obteniendo las etiquetas según búsqueda y estado.
     * Calcula totales y muestra la paginación.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $tags = $this->showDeleted 
            ? PostTag::onlyTrashed()->where('name', 'like', '%' . $this->search . '%')->orderBy('id', 'desc')->paginate($this->pagination)
            : PostTag::where('name', 'like', '%' . $this->search . '%')->orderBy('id', 'desc')->paginate($this->pagination);

        // Obtener el número total de los registros
        $this->totalRecord = $tags->total();

        // Obtener el número de la última entrada en la página actual
        $this->lastItem = $tags->lastItem() ?? 0;

        return view('livewire.backend.post-tags.component', [
            "showing" => 'Viendo '.$this->lastItem ." de ". $this->totalRecord ." registros",
            'tags' => $tags
        ])
        ->extends('layouts.backend.app')
        ->section('content');
    }


    /**
     * Almacena una nueva etiqueta validando los datos y generando el slug.
     *
     * @param string $modal_state Estado del modal para feedback visual.
     */
    public function Store($modal_state)
    {
        $rules = [
            'name' => 'required|unique:post_tags|max:250',
        ];
        $messages = [
            'name.required' => 'Requerido',
            'name.unique' => 'Ya existe esta etiqueta',
            'name.max' => 'Máximo 250 caracteres',
        ];
        $this->validate($rules, $messages);
        try {  
            $slug = Str::of($this->name)->slug('-');
            $tag = PostTag::create([
                'user_id' => Auth::id(),
                'name' => strtolower($this->name),
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
     * Carga los datos de una etiqueta para su edición.
     *
     * @param PostTag $tag Instancia del modelo a editar.
     */
    public function Edit(PostTag $tag){
        $this->selected_id = $tag->id;
        $this->name = $tag->name;
        $this->slug = $tag->slug;
        $this->dispatch('show-modal');
    }


    /**
     * Actualiza una etiqueta existente tras validar los datos y generar el slug.
     *
     * @param string $modal_state Estado del modal para feedback visual.
     */
    public function Update($modal_state){
        $rules = [
            'name' => "required|unique:post_tags,name,{$this->selected_id}",
        ];
        $messages = [
            'name.required' => 'Requerido',
            'name.unique' => 'Ya esta registrado',
        ];
        $this->validate($rules, $messages);
        $tag = PostTag::find($this->selected_id);
        $tag->update([
            'name' => $this->name,
            'slug' => $this->slug,
        ]);
        $this->dispatch($modal_state);
        if($modal_state=='updated-close'){
            $this->resetUI();
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
     * Solicita confirmación para eliminar lógicamente una etiqueta, validando que no tenga posts relacionados.
     * @param int $id
     */
    public function deleteRow($id){
        $this->selected_id = $id;
        $record = PostTag::find($this->selected_id);
        $count = $record->Post->count();
        if($count>0){
            $this->dispatch('error','Tiene publicaciones relacionados');
        }else{
            $this->dispatch('confirmDelete');
        }
    } 


    /**
     * Solicita confirmación para restaurar una etiqueta eliminada.
     * @param int $id
     */
    public function restoreRow($id){
        $this->selected_id = $id;
        $this->dispatch('confirmRestore');
    }

    /**
     * Solicita confirmación para eliminar permanentemente una etiqueta.
     * @param int $id
     */
    public function deleteRowPerm($id){
        $this->selected_id = $id;
        $this->dispatch('confirmDeletePerm');
    }

 

    /**
     * Elimina lógicamente una etiqueta (soft delete).
     */
    public function Destroy()
    {
        $record = PostTag::findOrFail($this->selected_id);
        $record->delete();
        $this->dispatch('deleted');
        $this->resetUI();
    }

    /**
     * Restaura una etiqueta previamente eliminada.
     */
    public function Restore()
    {
        $record = PostTag::onlyTrashed()->findOrFail($this->selected_id);
        $record->restore();
        $this->dispatch('restore');
        $this->resetUI();
    }

    /**
     * Elimina permanentemente una etiqueta (hard delete).
     */
    public function forceDelete()
    {
        $record = PostTag::onlyTrashed()->findOrFail($this->selected_id);
        $record->forceDelete();
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
            'selected_id',
        ]);
        $this->resetValidation();
    }
}