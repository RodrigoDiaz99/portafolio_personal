<?php

/**
 * Componente Livewire para la gestión de roles de usuario.
 * Permite crear, editar, eliminar y listar roles del sistema.
 * Utiliza el paquete Spatie Permission para la gestión de roles y permisos.
 *
 * Funcionalidades principales:
 * - Listado paginado de roles con búsqueda
 * - Creación de nuevos roles
 * - Edición de roles existentes
 * - Eliminación de roles (con validación de relaciones)
 * - Validación y feedback visual
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
 * Clase principal del componente Livewire para la gestión de roles.
 */
class Roles extends Component
{
    use WithPagination;

    /**
     * Propiedades públicas para el manejo de datos y estado del componente.
     * @var string $name Nombre del rol
     * @var string $search Término de búsqueda
     * @var int|null $selected_id ID seleccionado para edición
     * @var string $pageTitle Título de la página
     * @var string $componentName Nombre del componente
     * @var int $pagination Número de elementos por página
     * @var int $lastItem Último elemento mostrado en la página actual
     * @var int $totalRecord Total de registros
     */
	public $name, $search, $selected_id, $pageTitle, $componentName;
	private $pagination = 24;

	   /**
	    * Inicializa el componente con valores por defecto.
	    */
	   public function mount(){
	       $this->pageTitle = 'Listado';
	       $this->componentName = 'Roles';
	       $this->lastItem = 0;
	       $this->totalRecord = 0;
	   }

    /**
     * Especifica la vista personalizada para la paginación.
     * @return string Ruta de la vista de paginación
     */
    public function paginationView()
    {
        return 'livewire.pagination';
    }

    /**
     * Renderiza la vista principal del componente con los roles paginados.
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $roles = strlen($this->search) > 0
        ? Role::where('name', 'like', '%' . $this->search . '%')->paginate($this->pagination)
        : Role::orderBy('id', 'asc')->paginate($this->pagination);

        // Obtener el número total de los registros
        $this->totalRecord = $roles->total();

        // Obtener el número de la última entrada en la página actual
        $this->lastItem = $roles->lastItem() ?? 0;

        return view('livewire.backend.roles.component', [
            "showing" => 'Viendo '.$this->lastItem ." de ". $this->totalRecord ." registros",
            'roles' => $roles
        ])
        ->extends('layouts.backend.app')
        ->section('content');
    }

    /**
     * Almacena un nuevo rol en la base de datos.
     * @param string $modal_state Estado del modal para feedback visual
     */
    public function Store($modal_state){
    	$rules = ['name' => 'required|unique:roles,name'];

    	$messages = [
    		'name.required' => 'Requerido',
    		'name.unique' => 'El rol ya existe'
    	];

    	$this->validate($rules, $messages);

		try { 
			Role::create(['name' => $this->name]);
			$this->dispatch($modal_state);
			$this->resetUI();
		} catch (Exception $e) {
        	$this->dispatch('error','Error: '. $e->getMessage());
        }
    }

    /**
     * Prepara la edición de un rol existente.
     * @param Role $role Instancia del modelo Role a editar
     */
    public function Edit(Role $role){
    	$this->selected_id = $role->id;
    	$this->name = $role->name;
    	$this->dispatch('show-modal');
    }

    /**
     * Actualiza un rol existente en la base de datos.
     * @param string $modal_state Estado del modal para feedback visual
     */
    public function Update($modal_state){
    	$rules = ['name' => "required|unique:roles,name, {$this->selected_id}"];

    	$messages = [
    		'name.required' => 'Requerido',
    		'name.unique' => 'El rol ya existe'
    	];

    	$this->validate($rules, $messages);
		try {
            $tag = Role::find($this->selected_id);
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
     * Prepara la eliminación de un rol verificando relaciones.
     * @param int $id ID del rol a eliminar
     */
    public function deleteRow($id){
        $this->selected_id = $id;
        $record = Role::find($this->selected_id);
        $count = $record->users()->count();

        if ($count > 0) {
            $this->dispatch('error', 'Tiene usuarios relacionados');
        } else {
            $this->dispatch('confirmDelete');
        }
        
    } 

    #[On("Destroy")]
    /**
     * Elimina definitivamente un rol de la base de datos.
     * Se ejecuta después de confirmar que no tiene usuarios relacionados.
     */
    public function Destroy()
    {
        $record = Role::findOrFail($this->selected_id);
        $record->delete();
        $this->dispatch('deleted');
        $this->resetUI();
    }


    /**
     * Restablece las propiedades del componente a sus valores iniciales.
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
