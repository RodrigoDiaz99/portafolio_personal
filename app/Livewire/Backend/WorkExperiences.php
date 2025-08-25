<?php
/**
 * Componente Livewire para gestión de experiencias laborales
 *
 * Proporciona funcionalidad CRUD completa para experiencias laborales,
 * incluyendo creación, edición, eliminación y búsqueda.
 *
 * @category  LivewireComponent
 * @package   App\Livewire\Backend
 * @author    Mario
 * @copyright 2025
 */

namespace App\Livewire\Backend;
use Livewire\WithPagination;
use App\Models\WorkExperience;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

/**
 * Componente Livewire para administración de experiencias laborales
 *
 * Permite:
 * - Gestionar historial laboral de usuarios
 * - Validar fechas y descripciones
 * - Soft delete y restauración
 * - Búsqueda y paginación
 */
class WorkExperiences extends Component
{
    /** @var string $name Nombre de la empresa */
    public $name;
    
    /** @var string $job Puesto de trabajo */
    public $job;
    
    /** @var string $from Fecha de inicio (desde) */
    public $from;
    
    /** @var string $to Fecha de fin (hasta) */
    public $to;
    
    /** @var string $description Descripción del trabajo */
    public $description;
    
    /** @var int|null $selected_id ID del registro seleccionado */
    public $selected_id;
    
    /** @var string $pageTitle Título de la página */
    public $pageTitle;
    
    /** @var string $componentName Nombre del componente */
    public $componentName;
    
    /** @var string $search Término de búsqueda */
    public $search;
    
    /** @var int $lastItem Último ítem mostrado en paginación */
    public $lastItem;
    
    /** @var int $totalRecord Total de registros */
    public $totalRecord;
    
    /** @var bool $showDeleted Mostrar registros eliminados */
    public $showDeleted = false;
    
    /** @var int $pagination Items por página */
    private $pagination = 24;

    /**
     * Inicializa el componente con valores por defecto
     *
     * @return void
     */
    public function mount()
    {
        $this->pageTitle = 'Listado';
        $this->componentName = 'Experiencia Laboral';
        $this->lastItem = 0;
        $this->totalRecord = 0;
    }

    /**
     * Especifica la vista personalizada para paginación
     *
     * @return string
     */
    public function paginationView()
    {
        return 'livewire.pagination';
    }

    // Método para alternar el estado
    /**
     * Alterna la visualización de registros eliminados
     *
     * @return void
     */
    public function toggleShowDeleted()
    {
        $this->showDeleted = !$this->showDeleted;
    }

    /**
     * Renderiza la vista principal del componente
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        // Realiza una consulta en el modelo WorkExperience según el término de búsqueda proporcionado.
        // Si el campo de búsqueda ($this->search) contiene un valor (longitud mayor a 0),
        // filtra las experiencias laborales por coincidencias en el campo 'name'.
        // En caso contrario, obtiene todas las experiencias laborales ordenadas por ID en orden descendente.
        // La cantidad de registros mostrados en cada página está definida por $this->pagination.
        $experiences = $this->showDeleted 
        ? WorkExperience::onlyTrashed()->where('name', 'like', '%' . $this->search . '%')->orderBy('id', 'desc')->paginate($this->pagination)
        : WorkExperience::where('name', 'like', '%' . $this->search . '%')->orderBy('id', 'desc')->paginate($this->pagination);

        // Obtener el número total de los registros
        $this->totalRecord = $experiences->total();

        // Obtener el número de la última entrada en la página actual
        $this->lastItem = $experiences->lastItem() ?? 0;

        return view('livewire.backend.work-experiences.component', [
            "showing" => 'Viendo '.$this->lastItem ." de ". $this->totalRecord ." registros",
            'experiences' => $experiences
        ])
        ->extends('layouts.backend.app')
        ->section('content');
    }

    /**
     * Almacena una nueva experiencia laboral
     *
     * @param string $modal_state Estado del modal a despachar
     * @return void
     */
    public function Store($modal_state)
    {
    	$rules = [
            'name' => 'required',
            'job' => 'required',
            'from' => 'required',
            'to' => 'required',
            'description' => 'required|max:350'
        ];

    	$messages = [
    		'name.required' => 'Requerido',
            'job.required' => 'Requerido',
            'from.required' => 'Requerido',
            'to.required' => 'Requerido',
            'description.required' => 'Requerido',
            'description.max' => 'Máximo 350 caracteres',
    	];

    	$this->validate($rules, $messages);

    	WorkExperience::create([
            'user_id' => Auth::id(),
            'name' => $this->name,
            'job' => $this->job,
            'from' => $this->from,
            'to' => $this->to,
            'description' => $this->description
        ]);
        $this->dispatch($modal_state);
    	$this->resetUI();
    }

    /**
     * Prepara los datos para editar una experiencia existente
     *
     * @param WorkExperience $experience Modelo a editar
     * @return void
     */
    public function Edit(WorkExperience $experience)
    {
    	$this->selected_id = $experience->id;
    	$this->name = $experience->name;
        $this->job = $experience->job;
        $this->from = $experience->from;
        $this->to = $experience->to;
    	$this->description = $experience->description;
        $this->dispatch('show-modal');
    }

    /**
     * Actualiza una experiencia laboral existente
     *
     * @param string $modal_state Estado del modal a despachar
     * @return void
     */
    public function Update($modal_state)
    {
    	$rules = [
            'name' => "required",
            'job' => 'required',
            'from' => 'required',
            'to' => 'required',
            'description' => 'required|max:350'
        ];

    	$messages = [
    		'name.required' => 'Requerido',
            'job.required' => 'Requerido',
            'from.required' => 'Requerido',
            'to.required' => 'Requerido',
            'description.max' => 'Máximo 350 caracteres',
    	];

    	$this->validate($rules, $messages);
        $user = WorkExperience::find($this->selected_id);
    	$user->update([
            'user_id' => Auth::id(),
            'name' => $this->name,
            'from' => $this->from,
            'to' => $this->to,
            'job' => $this->job,
            'description' => $this->description
        ]);
    	$this->dispatch($modal_state);
    	if($modal_state=='updated-close'){
            $this->resetUI();
        }
    }

    /**
     * Prepara la eliminación de una experiencia laboral
     *
     * @param int $id ID del registro a eliminar
     * @return void
     */
    public function deleteRow($id)
    {
        $this->selected_id = $id;
        $this->dispatch('confirmDelete');
    } 

    /**
     * Prepara la restauración de una experiencia eliminada
     *
     * @param int $id ID del registro a restaurar
     * @return void
     */
    public function restoreRow($id)
    {
        $this->selected_id = $id;
        $this->dispatch('confirmRestore');
    }

    /**
     * Prepara la eliminación permanente de una experiencia
     *
     * @param int $id ID del registro a eliminar permanentemente
     * @return void
     */
    public function deleteRowPerm($id)
    {
        $this->selected_id = $id;
        $this->dispatch('confirmDeletePerm');
    }

    #[On("Destroy")]
    /**
     * Elimina una experiencia laboral (soft delete)
     *
     * @return void
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Si el registro no existe
     */
    public function Destroy()
    {
        $record = WorkExperience::findOrFail($this->selected_id);
        $record->delete();
        $this->dispatch('deleted');
        $this->resetUI();
    }

    /**
     * Restaura una experiencia eliminada
     *
     * @return void
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Si el registro no existe
     */
    public function Restore()
    {
        $record = WorkExperience::onlyTrashed()->findOrFail($this->selected_id);
        $record->restore();

        $this->dispatch('restore');
        $this->resetUI();
    }

    /**
     * Elimina permanentemente una experiencia laboral
     *
     * @return void
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Si el registro no existe
     */
    public function forceDelete()
    {
        $record = WorkExperience::onlyTrashed()->findOrFail($this->selected_id);
        $record->forceDelete(); // Borra permanentemente el registro
        $this->dispatch('deleted');
        $this->resetUI();
    }

    /**
     * Restablece la interfaz de usuario a su estado inicial
     *
     * @return void
     */
    public function resetUI()
    {
        $this->reset([
            'name',
            'job',
            'from',
            'to',
            'description',
            'selected_id',
        ]);
        
        $this->resetValidation();
    }
}
