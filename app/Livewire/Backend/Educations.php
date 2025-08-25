<?php
/**
 * Educations
 *
 * Componente Livewire para la gestión CRUD de registros de educación del usuario.
 * Permite listar, buscar, crear, editar, eliminar (soft y hard delete), restaurar y paginar registros educativos asociados al usuario autenticado.
 * Incluye soporte para mostrar registros eliminados y validación de datos.
 *
 * Funcionalidades principales:
 * - Listado y búsqueda de registros educativos.
 * - Paginación de resultados.
 * - Creación, edición y validación de registros.
 * - Eliminación lógica y permanente, y restauración de registros.
 * - Alternancia entre registros activos y eliminados.
 *
 * @category  LivewireComponent
 * @package   App\Livewire\Backend
 * @author    Mario
 * @copyright 2025
 */

namespace App\Livewire\Backend;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Education;
use Illuminate\Support\Facades\Auth;


/**
 * Clase principal del componente Livewire para la gestión de registros educativos.
 */
class Educations extends Component
{
    use WithPagination;

    /**
     * Propiedades públicas para el manejo de datos y estado del componente.
     * @var string $institution Nombre de la institución educativa.
     * @var string $study_level Nivel de estudios.
     * @var string $title_obtained Título obtenido.
     * @var string $date Fecha de finalización o periodo.
     * @var string $description Descripción adicional.
     * @var int|null $selected_id ID seleccionado para edición o eliminación.
     * @var string $pageTitle Título de la página.
     * @var string $componentName Nombre del componente.
     * @var string $search Término de búsqueda.
     * @var int $lastItem Último registro mostrado en la página actual.
     * @var int $totalRecord Total de registros encontrados.
     * @var bool $showDeleted Indica si se muestran registros eliminados.
     * @var int $pagination Cantidad de elementos por página.
     */
    public $institution, $study_level, $title_obtained, $date, $description, $selected_id, $pageTitle, $componentName, $search, $lastItem, $totalRecord;
    public $showDeleted = false;
    private $pagination = 24;


    /**
     * Inicializa el componente con valores por defecto.
     */
    public function mount(){
        $this->pageTitle = 'Listado';
        $this->componentName = 'Educación';
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
     * Renderiza la vista principal del componente, obteniendo los registros educativos según búsqueda y estado.
     * Calcula totales y muestra la paginación.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        // Realiza una consulta en el modelo Education en función de la búsqueda proporcionada.
        // Si $showDeleted está activo, muestra solo los eliminados; si no, muestra los activos.
        $educations = $this->showDeleted 
            ? Education::onlyTrashed()->where('institution', 'like', '%' . $this->search . '%')->orderBy('id', 'desc')->paginate($this->pagination)
            : Education::where('institution', 'like', '%' . $this->search . '%')->orderBy('id', 'desc')->paginate($this->pagination);

        // Obtener el número total de los registros
        $this->totalRecord = $educations->total();

        // Obtener el número de la última entrada en la página actual
        $this->lastItem = $educations->lastItem() ?? 0;
        
        return view('livewire.backend.educations.component', [
            "showing" => 'Viendo '.$this->lastItem ." de ". $this->totalRecord ." registros",
            'educations' => $educations
        ])
        ->extends('layouts.backend.app')
        ->section('content');
    }


    /**
     * Almacena un nuevo registro educativo validando los datos y asociándolo al usuario autenticado.
     *
     * @param string $modal_state Estado del modal para feedback visual.
     */
    public function Store($modal_state){
        $rules = [
            'institution' => 'required',
            'title_obtained' => 'required',
            'study_level' => 'required',
            'date' => 'required',
        ];

        $messages = [
            'institution.required' => 'Requerido',
            'title_obtained.required' => 'Requerido',
            'study_level.required' => 'Requerido',
            'date.required' => 'Requerido',
        ];

        $this->validate($rules, $messages);

        Education::create([
            'user_id' => Auth::id(),
            'institution' => $this->institution,
            'study_level' => $this->study_level,
            'title_obtained' => $this->title_obtained,
            'date' => $this->date,
            'description' => $this->description
        ]);
        $this->dispatch($modal_state);
        $this->resetUI();
    }


    /**
     * Carga los datos de un registro educativo para su edición.
     *
     * @param Education $education Instancia del modelo a editar.
     */
    public function Edit(Education $education){
        $this->selected_id = $education->id;
        $this->institution = $education->institution;
        $this->study_level = $education->study_level;
        $this->title_obtained = $education->title_obtained;
        $this->date = $education->date;
        $this->description = $education->description;
        $this->dispatch('show-modal');
    }


    /**
     * Actualiza un registro educativo existente tras validar los datos.
     *
     * @param string $modal_state Estado del modal para feedback visual.
     */
    public function Update($modal_state){
        $rules = [
            'institution' => 'required',
            'title_obtained' => 'required',
            'study_level' => 'required',
            'date' => 'required',
        ];

        $messages = [
            'institution.required' => 'Requerido',
            'title_obtained.required' => 'Requerido',
            'study_level.required' => 'Requerido',
            'date.required' => 'Requerido',
        ];

        $this->validate($rules, $messages);
        $education = Education::find($this->selected_id);
        $education->update([
            'user_id' => Auth::id(),
            'institution' => $this->institution,
            'study_level' => $this->study_level,
            'title_obtained' => $this->title_obtained,
            'date' => $this->date,
            'description' => $this->description
        ]);
        $this->dispatch($modal_state);
        if($modal_state=='updated-close'){
            $this->resetUI();
        }
    }


    /**
     * Solicita confirmación para eliminar lógicamente un registro.
     * @param int $id
     */
    public function deleteRow($id){
        $this->selected_id = $id;
        $this->dispatch('confirmDelete');
    } 

    /**
     * Solicita confirmación para restaurar un registro eliminado.
     * @param int $id
     */
    public function restoreRow($id){
        $this->selected_id = $id;
        $this->dispatch('confirmRestore');
    }

    /**
     * Solicita confirmación para eliminar permanentemente un registro.
     * @param int $id
     */
    public function deleteRowPerm($id){
        $this->selected_id = $id;
        $this->dispatch('confirmDeletePerm');
    }


    /**
     * Elimina lógicamente un registro educativo (soft delete).
     */
    #[On("Destroy")]
    public function Destroy()
    {
        $record = Education::findOrFail($this->selected_id);
        $record->delete();
        $this->dispatch('deleted');
        $this->resetUI();
    }

    /**
     * Restaura un registro educativo previamente eliminado.
     */
    public function Restore()
    {
        $record = Education::onlyTrashed()->findOrFail($this->selected_id);
        $record->restore();
        $this->dispatch('restore');
        $this->resetUI();
    }

    /**
     * Elimina permanentemente un registro educativo (hard delete).
     */
    public function forceDelete()
    {
        $record = Education::onlyTrashed()->findOrFail($this->selected_id);
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
            'institution',
            'study_level',
            'title_obtained',
            'date',
            'description',
            'selected_id',
        ]);
        $this->resetValidation();
    }
}
