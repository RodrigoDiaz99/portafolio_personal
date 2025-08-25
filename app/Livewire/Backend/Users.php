<?php
/**
 * Componente Livewire para gestión de usuarios
 *
 * Proporciona funcionalidad CRUD completa para usuarios del sistema,
 * incluyendo manejo de roles, imágenes de perfil y estados de cuenta.
 *
 * @category  LivewireComponent
 * @package   App\Livewire\Backend
 * @author    Mario
 * @copyright 2025
 */

namespace App\Livewire\Backend;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Exception\NotReadableException;
use Intervention\Image\Laravel\Facades\Image;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

/**
 * Componente Livewire para administración de usuarios
 *
 * Permite:
 * - Crear/editar/eliminar usuarios
 * - Asignar roles con Spatie Permission
 * - Manejar imágenes de perfil en WebP
 * - Cambiar contraseñas
 * - Soft delete y restauración
 */
class Users extends Component
{
    use WithFileUploads;
    use WithPagination;

    /** @var string $name Nombre completo del usuario */
    public $name;
    
    /** @var string|null $phone Teléfono del usuario */
    public $phone;
    
    /** @var string $email Email del usuario */
    public $email;
    
    /** @var mixed $image Imagen de perfil del usuario */
    public $image;
    
    /** @var string|null $password Contraseña para nuevo usuario */
    public $password;
    
    /** @var string|null $current_password Contraseña actual para cambio */
    public $current_password;
    
    /** @var string|null $new_password Nueva contraseña */
    public $new_password;
    
    /** @var string|null $new_password_confirmation Confirmación de nueva contraseña */
    public $new_password_confirmation;
    
    /** @var int|null $selected_id ID del usuario seleccionado */
    public $selected_id;
    
    /** @var string|null $bio Biografía del usuario */
    public $bio;
    
    /** @var string|null $job_title Puesto de trabajo */
    public $job_title;
    
    /** @var string|null $address Dirección */
    public $address;
    
    /** @var string|null $birthdate Fecha de nacimiento */
    public $birthdate;
    
    /** @var string $roleid ID del rol asignado */
    public $roleid;
    
    /** @var string|null $rolename Nombre del rol */
    public $rolename;
    
    /** @var string $account_state Estado de la cuenta */
    public $account_state;
    
    /** @var bool $showDeleted Mostrar registros eliminados */
    public $showDeleted = false;
    
    /** @var string $pageTitle Título de la página */
    public $pageTitle;
    
    /** @var string $componentName Nombre del componente */
    public $componentName;
    
    /** @var string $search Término de búsqueda */
    public $search;
    
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
        $this->componentName = 'Usuarios';
        $this->roleid = 'Seleccione';
        $this->lastItem = 0;
        $this->totalRecord = 0;
        $this->selected_id = 0;
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
        $users = $this->showDeleted 
        ? User::onlyTrashed()->where('name', 'like', '%' . $this->search . '%')->orderBy('id', 'desc')->paginate($this->pagination)
        : User::where('name', 'like', '%' . $this->search . '%')->orderBy('id', 'desc')->paginate($this->pagination);

        // Obtener el número total de los registros
        $this->totalRecord = $users->total();

        // Obtener el número de la última entrada en la página actual
        $this->lastItem = $users->lastItem() ?? 0;

        return view('livewire.backend.users.component', [
            "showing" => 'Viendo '.$this->lastItem ." de ". $this->totalRecord ." registros",
            'users' => $users,
            'roles' => Role::orderBy('id','asc')->get()
        ])
        ->extends('layouts.backend.app')
        ->section('content');
    }

    /**
     * Almacena un nuevo usuario en la base de datos
     *
     * @param string $modal_state Estado del modal a despachar
     * @return void
     * @throws \Intervention\Image\Exception\NotReadableException Si hay error procesando la imagen
     */
    public function Store($modal_state)
    {

    	$rules = [
    		'name' => 'required|min:3',
    		'email' => 'required|unique:users|email',
    		'account_state' => 'required|not_in:Seleccione',
    		'roleid' => 'required|not_in:Seleccione',
    		//'password' => 'required|min:8',
            
            

    	];
    	$messages = [
    		'name.required' => 'Requerido',
    		'name.min' => 'Debe tener almenos 3 carateres',
    		'email.required' => 'Requerido',
    		'email.unique' => 'Ya existe en el sistema',
    		'email.email' => 'No valido',
    		'account_state.required' => 'Requerido',
    		'account_state.not_in' => 'Requerido',
    		'roleid.required' => 'Requerido',
    		'roleid.not_in' => 'Requerido',
    		//'password.required' => 'Requerido',
    		//'password.min' => 'Debe contener almenos 8 carateres',
    	];

    	$this->validate($rules,$messages);
        try { 
    	$user = User::create([
    		'name' => $this->name,
    		'email' => $this->email,
            'job_title' => $this->job_title,
    		'password' => Hash::make($this->password),
    		'role' => $this->roleid,
    		'account_state' => $this->account_state
    	]);

        $user->syncRoles($this->roleid);
        $slug = Str::of($this->name)->slug('-');
        $customFileName = null;
    	// Verifica si $this->image es un array o un objeto, lo que indica que se trata de una imagen válida.
        if (is_array($this->image) || is_object($this->image)) {
            // Compara si la nueva imagen es diferente a la imagen actual del usuario.
           
                // Genera un nombre único para la imagen combinando el slug con un identificador único.
                $customFileName = $slug . '-' . uniqid();

                // Lee la imagen y la escala a un ancho de 350px.
                $image = Image::read($this->image)->scale(width: 350);

                // Convierte la imagen a formato WebP con una calidad de 65 y la guarda en la ruta especificada.
                $image->toWebp(65)
                    ->save(public_path('storage/users/profile/' . $customFileName . '.webp'));

                // Guarda el nombre de la nueva imagen en el campo 'image' de la base de datos.
                $user->image = $customFileName . '.webp';
                $user->save();


        }
        $this->dispatch($modal_state);
        $this->resetUI();
        
        } catch (NotReadableException $e) {
            $this->dispatch('error','Error: '. $e->getMessage());
        }
    }

    /**
     * Prepara los datos para editar un usuario existente
     *
     * @param User $user Modelo de usuario a editar
     * @return void
     */
    public function Edit(User $user)
    {
    	$this->selected_id = $user->id;
    	$this->name = $user->name;
    	$this->email = $user->email;
        $this->phone = $user->phone;
        $this->address = $user->address;
        $this->birthdate = $user->birthdate;
        $this->job_title = $user->job_title;
        $this->image = $user->image;
        $this->roleid = $user->role;
    	$this->account_state = $user->account_state;
        $this->dispatch('show-modal-user');
    }

    /**
     * Actualiza un usuario existente
     *
     * @param string $modal_state Estado del modal a despachar
     * @return void
     * @throws \Intervention\Image\Exception\NotReadableException Si hay error procesando la imagen
     */
    public function Update($modal_state)
    {
    	$rules = [
            'name' => 'required',
    		'email' => "required|unique:users,email,{$this->selected_id}",
            'email' => 'email',  				
            'account_state' => 'required',

    	];
    	$messages = [
    		'name.required' => 'Requerido',
    		'name.min' => 'Debe tener almenos 3 carateres',
    		'email.required' => 'Requerido',
    		'email.unique' => 'El correo ya existe en el sistema',
    		'email.email' => 'Correo no valido',
            'account_state.required' => 'Requerido',
    	];

    	$this->validate($rules,$messages);
        try { 
        $user = User::find($this->selected_id);
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'account_state' => $this->account_state,
        ]);

    	// Genera un slug (URL amigable) a partir del nombre proporcionado ($this->name) y define un nombre de archivo personalizado nulo.
        $slug = Str::of($this->name)->slug('-');
        $customFileName = null;

        // Verifica si $this->image es un array o un objeto, lo que indica que se trata de una imagen válida.
        if (is_array($this->image) || is_object($this->image)) {
            // Compara si la nueva imagen es diferente a la imagen actual del usuario.
            if ($this->image != $user->image) {
                // Genera un nombre único para la imagen combinando el slug con un identificador único.
                $customFileName = $slug . '-' . uniqid();

                // Lee la imagen y la escala a un ancho de 350px.
                $image = Image::read($this->image)->scale(width: 350);

                // Convierte la imagen a formato WebP con una calidad de 65 y la guarda en la ruta especificada.
                $image->toWebp(65)
                    ->save(public_path('storage/users/profile/' . $customFileName . '.webp'));

                // Guarda el nombre de la nueva imagen en el campo 'image' de la base de datos.
                $oldImage = $user->image;
                $user->image = $customFileName . '.webp';
                $user->save();

                // Si la imagen anterior existe en el sistema de archivos, la elimina para liberar espacio.
                if ($oldImage && file_exists(public_path('storage/users/profile/' . $oldImage))) {
                    unlink(public_path('storage/users/profile/' . $oldImage));
                }
            }
        }

        $this->dispatch($modal_state);
        if($modal_state=='updated-close'){
            $this->resetUI();
            
        }
        
        } catch (NotReadableException $e) {
            $this->dispatch('error','Error: '. $e->getMessage());
        }

    }

    /**
     * Prepara el formulario para cambiar contraseña
     *
     * @param int $id ID del usuario
     * @return void
     */
    public function EditPassd($id)
    {
    	$this->selected_id = $id;
        $this->dispatch('show-modal-pass');
    }

    /**
     * Actualiza la contraseña de un usuario
     *
     * @return void
     */
    public function UpdatePassword()
    {
        $rules = [
    		'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
            'new_password_confirmation' => 'required|min:8'

    	];
    	$messages = [
    		'current_password.required' => 'Requerido',
    		'new_password.required' => 'Requerido',
            'new_password.min' => 'Debe tener almenos 8 carateres',
            'new_password.confirmed' => 'La confirmación de la nueva contraseña no coincide',
            'new_password_confirmation.required' => 'Requerido',
            'new_password_confirmation.min' => 'Debe tener almenos 8 carateres',

    	];

    	$this->validate($rules,$messages);
       


        $user_admin = auth()->user();
        $user = User::find($this->selected_id);

        if (!Hash::check($this->current_password, $user_admin->password)) {
            $this->dispatch('error-password');   
            return;
        }else{

            $user->password = Hash::make($this->new_password);
            $user->save();

            $this->dispatch('updated-password');
            $this->resetUIPassd();
        }   
        
    }

    /**
     * Prepara la eliminación de un usuario
     *
     * @param int $id ID del usuario a eliminar
     * @return void
     */
    public function deleteRow($id)
    {
        $user = User::find($id); // Busca el usuario por su ID

        // Verifica si el usuario existe
        if (!$user) {
            $this->dispatch('error', 'El usuario no existe');
            return; // Detener el proceso
        }

        // Verifica si el usuario actual está intentando eliminarse a sí mismo
        if (auth()->id() === $id) {
            $this->dispatch('error', 'No puedes eliminar tu propia cuenta');
            return; // Detener el proceso
        }

        // Verifica si el usuario tiene el rol de "Super Admin"
        if ($user->role === 'Super Admin') { // Cambia 'role' al nombre del campo que almacena el rol
            $this->dispatch('error', 'No puedes eliminar a un usuario con el rol de Super Admin');
            return; // Detener el proceso
        }

        // Establece el ID seleccionado y lanza el evento de confirmación
        $this->selected_id = $id;
        $this->dispatch('confirmDelete');
    }


    /**
     * Prepara la restauración de un usuario eliminado
     *
     * @param int $id ID del usuario a restaurar
     * @return void
     */
    public function restoreRow($id)
    {
        $this->selected_id = $id;
        $this->dispatch('confirmRestore');
    }

    /**
     * Prepara la eliminación permanente de un usuario
     *
     * @param int $id ID del usuario a eliminar permanentemente
     * @return void
     */
    public function deleteRowPerm($id)
    {
        $this->selected_id = $id;
        $this->dispatch('confirmDeletePerm');
    }

    #[On("Destroy")]
    /**
     * Elimina un usuario (soft delete)
     *
     * @return void
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Si el usuario no existe
     */
    public function Destroy()
    {
        $record = User::withTrashed()->findOrFail($this->selected_id);
        $record->delete();
        $this->dispatch('deleted');
        $this->resetUI();
    }

    /**
     * Restaura un usuario eliminado
     *
     * @return void
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Si el usuario no existe
     */
    public function Restore()
    {
        $record = User::onlyTrashed()->findOrFail($this->selected_id);
        $record->restore();

        $this->dispatch('restore');
        $this->resetUI();
    }

    /**
     * Elimina permanentemente un usuario
     *
     * @return void
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Si el usuario no existe
     */
    public function forceDelete()
    {
        $record = User::onlyTrashed()->findOrFail($this->selected_id);
        // Elimina físicamente el registro (borrado permanente)
        $oldImage = $record->image;


        // Verifica y elimina las imágenes
        if ($oldImage && file_exists(public_path('storage/users/profile/' . $oldImage))) {
            unlink(public_path('storage/users/profile/' . $oldImage));
        }

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
        
        $this->dispatch('reset-role');
        $this->dispatch('reset-imagen');
        $this->roleid = 'Seleccione';
        $this->selected_id = 0;
        $this->reset([
            'name',
            'bio',
            'email',
            'address',
            'image',
            'phone',
            'job_title',
            'birthdate',
            'password',
            'current_password',
            'new_password',
            'new_password_confirmation',
            'roleid',
            'account_state'
        ]);
        
        $this->resetValidation();
    }

    /**
     * Restablece el formulario de cambio de contraseña
     *
     * @return void
     */
    public function resetUIPassd()
    {
        

        $this->reset([
            'current_password',
            'new_password',
            'new_password_confirmation',
        ]);
        
        $this->resetValidation();
    }
}
