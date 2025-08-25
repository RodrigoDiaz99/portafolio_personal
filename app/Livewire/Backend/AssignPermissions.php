<?php
/**
 * AssignPermissions
 *
 * Componente Livewire para la administración de permisos de roles en el panel de administración.
 * Permite asignar, revocar y sincronizar permisos de manera individual o masiva a los roles definidos en el sistema,
 * utilizando el paquete Spatie Permission. Incluye paginación y feedback visual para el usuario administrador.
 *
 * Funcionalidades principales:
 * - Listar todos los permisos y roles disponibles.
 * - Asignar todos los permisos a un rol.
 * - Asignar o revocar permisos individuales.
 * - Revocar todos los permisos de un rol.
 * - Sincronización visual y lógica de los permisos asignados.
 *
 * @category  LivewireComponent
 * @package   App\Livewire\Backend;
 * @author    Mario
 * @copyright 2025
 */

namespace App\Livewire\Backend;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Livewire\WithPagination;
use DB;
/**
 * Clase principal del componente Livewire para la gestión de permisos por rol.
 */
class AssignPermissions extends Component
{
    use WithPagination;

    /**
     * @var string|int $role Rol seleccionado para asignación de permisos.
     * @var string $componentName Nombre del componente para la vista.
     * @var array $selected_id IDs seleccionados para revocación masiva.
     * @var array $old_permissions Permisos previos (no usado actualmente).
     * @var int $pagination Cantidad de elementos por página.
     */
    public $role, $componentName, $selected_id = [], $old_permissions = [];
    private $pagination = 24;
    public function mount(){
        $this->role = 'Seleccione';
        $this->componentName = 'Asignar permisos';
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
     * Renderiza la vista principal del componente, obteniendo los permisos y roles,
     * y marcando los permisos asignados al rol seleccionado.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        // Consulta los permisos y marca si están asignados al rol seleccionado
        $permissions = Permission::select('name', 'id', DB::raw("0 as checked"))
            ->when($this->role !== 'Seleccione', function ($query) {
                return $query->selectRaw("(
                    SELECT COUNT(*) 
                    FROM role_has_permissions 
                    WHERE permissions.id = role_has_permissions.permission_id 
                    AND role_has_permissions.role_id = {$this->role}
                ) as checked");
            })
            ->orderBy('id', 'asc')
            ->paginate($this->pagination);

        // Si hay un rol seleccionado, transforma la colección para marcar los permisos asignados
        if ($this->role != 'Seleccione') {
            $assignedPermissions = Role::find($this->role)->permissions->pluck('id')->toArray();
            $permissions->transform(function ($permission) use ($assignedPermissions) {
                $permission->checked = in_array($permission->id, $assignedPermissions) ? 1 : 0;
                return $permission;
            });
        }

        // Segunda comprobación para asegurar la consistencia de los permisos marcados
        if ($this->role != 'Seleccione') {
            $role = Role::find($this->role); // Cargar el rol solo una vez
            foreach ($permissions as $permission) {
                $hasPermission = $role->hasPermissionTo($permission->name);
                $permission->checked = $hasPermission ? 1 : 0; // Asignar directamente el valor de checked
            }
        }

        return view('livewire.backend.assign-permissions.component', [
            'roles' => Role::all(),
            'permissions' => $permissions
        ])
        ->extends('layouts.backend.app')
        ->section('content');
    }

    /**
     * Asigna todos los permisos disponibles al rol seleccionado.
     * Lanza un evento de feedback visual.
     */
    public function SyncAll()
    {
        if ($this->role == 'Seleccione') {
            $this->dispatch('error','Seleccione un role valido');
            return;
        }
        $role = Role::find($this->role);
        $permisos = Permission::pluck('id')->toArray();
        $role->syncPermissions($permisos);
        $this->dispatch('assigned',"Todos los permisos asignados");
    }

    /**
     * Asigna o revoca un permiso individual al rol seleccionado.
     *
     * @param bool $state Estado del checkbox (true=asignar, false=revocar)
     * @param string $permisoName Nombre del permiso a asignar o revocar
     */
    public function syncPermission($state, $permisoName)
    {
        if ($this->role !== 'Seleccione') {
            $roleName = Role::find($this->role);
            if ($state) { // Asignar permiso
                $roleName->givePermissionTo($permisoName);
                $this->dispatch('assigned', 'Permiso asignado');
            } else { // Revocar permiso
                $roleName->revokePermissionTo($permisoName);
                $this->dispatch('revoked', 'Permiso revocado');
            }
        } else {
            $this->dispatch('error', 'Seleccione un rol');
        }
    }

    /**
     * Prepara la revocación de todos los permisos del rol seleccionado.
     * Lanza un evento de confirmación para el usuario.
     *
     * @param array $id IDs seleccionados para revocación
     */
    public function revokeAll($id){
        $this->selected_id = $id;
        $this->dispatch('confirmRevoke');    
    } 

    /**
     * Revoca todos los permisos del rol seleccionado.
     * Lanza un evento de feedback visual.
     *
     * @return void
     */
    #[On("RemoveAll")]
    public function RemoveAll()
    {
        if ($this->role == 'Seleccione') {
            $this->dispatch('error','Seleccione un role valido');
            return;
        }
        $role = Role::find($this->role);
        $role->syncPermissions([0]);
        $this->dispatch('removeall',"Todos los permisos se han revocado");
    }
}
