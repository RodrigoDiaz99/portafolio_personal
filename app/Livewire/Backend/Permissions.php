<?php
/**
 * Permissions
 *
 * Componente Livewire para la gestión CRUD de permisos del sistema.
 * Permite listar, buscar, crear, editar, eliminar y paginar permisos utilizando el paquete Spatie Permission.
 * Incluye validación, control de relaciones y feedback visual para el usuario administrador.
 *
 * Funcionalidades principales:
 * - Listado y búsqueda de permisos.
 * - Paginación de resultados.
 * - Creación, edición y validación de permisos.
 * - Eliminación de permisos con control de relaciones.
 *
 * @category  LivewireComponent
 * @package   App\Livewire\Backend
 * @author    Mario
 * @copyright 2025
 */

namespace App\Livewire\Backend;

use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Livewire\WithPagination;
use App\Models\User;


/**
 * Clase principal del componente Livewire para la gestión de permisos.
 */
class Permissions extends Component
{
    use WithPagination;

    /**
     * Propiedades públicas para el manejo de datos y estado del componente.
     * @var string $name Nombre del permiso.
     * @var string $search Término de búsqueda.
     * @var int|null $selected_id ID seleccionado para edición o eliminación.
     * @var string $pageTitle Título de la página.
     * @var string $componentName Nombre del componente.
     * @var int $pagination Cantidad de elementos por página.
     */
    public $name, $search, $selected_id, $pageTitle, $componentName;
    private $pagination = 24;


    /**
     * Inicializa el componente con valores por defecto.
     */
    public function mount(){
        $this->pageTitle = 'Listado';
        $this->componentName = 'Permisos';
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
     * Renderiza la vista principal del componente, obteniendo los permisos según búsqueda y paginación.
     * Calcula totales y muestra la paginación.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $permissions = strlen($this->search) > 0
            ? Permission::where('name', 'like', '%' . $this->search . '%')->paginate($this->pagination)
            : Permission::orderBy('id', 'asc')->paginate($this->pagination);

        // Obtener el número total de los registros
        $this->totalRecord = $permissions->total();

        // Obtener el número de la última entrada en la página actual
        $this->lastItem = $permissions->lastItem() ?? 0;

        return view('livewire.backend.permissions.component', [
            "showing" => 'Viendo '.$this->lastItem ." de ". $this->totalRecord ." registros",
            'permissions' => $permissions
        ])
        ->extends('layouts.backend.app')
        ->section('content');
    }


    /**
     * Almacena un nuevo permiso validando los datos.
     *
     * @param string $modal_state Estado del modal para feedback visual.
     */
    public function Store($modal_state){
        $rules = ['name' => 'required|unique:roles,name'];

        $messages = [
            'name.required' => 'Requerido',
            'name.unique' => 'El permiso ya existe'
        ];

        $this->validate($rules, $messages);

        try { 
            Permission::create(['name' => $this->name]);
            $this->dispatch($modal_state);
            $this->resetUI();
        } catch (Exception $e) {
            $this->dispatch('error','Error: '. $e->getMessage());
        }
    }


    /**
     * Carga los datos de un permiso para su edición.
     *
     * @param Permission $permission Instancia del modelo a editar.
     */
    public function Edit(Permission $permission){
        $this->selected_id = $permission->id;
        $this->name = $permission->name;
        $this->dispatch('show-modal');
    }


    /**
     * Actualiza un permiso existente tras validar los datos.
     *
     * @param string $modal_state Estado del modal para feedback visual.
     */
    public function Update($modal_state){
        $rules = ['name' => "required|unique:permissions,name, {$this->selected_id}"];

        $messages = [
            'name.required' => 'Requerido',
            'name.unique' => 'El permiso ya existe'
        ];

        $this->validate($rules, $messages);
        try {
            $tag = Permission::find($this->selected_id);
            $tag->update([
                'name' => $this->name,
            ]);
            $this->dispatch($modal_state);
            if($modal_state=='updated-close'){
                $this->resetUI();
            }
        } catch (Exception $e) {
            $this->emit('role-error','Error: '. $e->getMessage());
        }
    }


    /**
     * Solicita confirmación para eliminar un permiso, validando que no tenga usuarios relacionados.
     * @param int $id
     */
    public function deleteRow($id){
        $this->selected_id = $id;
        $record = Permission::find($this->selected_id);
        $count = $record->users()->count();

        if ($count > 0) {
            $this->dispatch('error', 'Tiene usuarios relacionados');
        } else {
            $this->dispatch('confirmDelete');
        }
    } 


    /**
     * Elimina un permiso del sistema.
     */
    #[On("Destroy")]
    public function Destroy()
    {
        $record = Permission::findOrFail($this->selected_id);
        $record->delete();
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
