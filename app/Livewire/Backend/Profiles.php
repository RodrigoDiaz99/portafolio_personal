<?php

/**
 * Componente Livewire para la gestión del perfil de usuario.
 * Permite visualizar, editar datos personales, actualizar la contraseña y la imagen de perfil.
 * Incluye validación, manejo de imágenes y feedback visual para el usuario.
 *
 * Funcionalidades principales:
 * - Visualización y edición de datos personales.
 * - Actualización de contraseña con validación.
 * - Actualización de imagen de perfil.
 * - Validación y feedback visual.
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

/**
 * Clase principal del componente Livewire para la gestión del perfil de usuario.
 */
class Profiles extends Component
{
    use WithFileUploads;

    /**
     * Propiedades públicas para el manejo de datos y estado del componente.
     * @var string $name Nombre del usuario.
     * @var string $phone Teléfono del usuario.
     * @var string $email Correo electrónico.
     * @var mixed $image Imagen de perfil.
     * @var string $current_password Contraseña actual.
     * @var string $new_password Nueva contraseña.
     * @var string $new_password_confirmation Confirmación de la nueva contraseña.
     * @var int|null $selected_id ID seleccionado para edición.
     * @var string $bio Biografía del usuario.
     * @var string $job_title Puesto de trabajo.
     * @var string $address Dirección.
     * @var string $birthdate Fecha de nacimiento.
     * @var string $pageTitle Título de la página.
     * @var string $componentName Nombre del componente.
     * @var string $search Término de búsqueda.
     */
    public $name, $phone, $email, $image, $current_password, $new_password, $new_password_confirmation, $selected_id, $bio, $job_title, $address, $birthdate;
    public $pageTitle, $componentName, $search;

    /**
     * Inicializa el componente con valores por defecto.
     */
    public function mount()
    {
        $this->componentName = 'Mi Perfil';
    }

    /**
     * Renderiza la vista principal del componente, mostrando los datos del usuario autenticado.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $user = auth()->user();
        return view('livewire.backend.profile.component',[
            'user' => $user
        ])
        ->extends('layouts.backend.app')
        ->section('content');
    }

    /**
     * Carga los datos del usuario para su edición.
     * @param User $user Instancia del modelo a editar.
     */
    public function Edit(User $user){
        $this->selected_id = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->job_title = $user->job_title;
        $this->birthdate = $user->birthdate;
        $this->address = $user->address;
        $this->image = $user->image;
        $this->bio = $user->bio;
        $this->dispatch('show-modal');
    }

    /**
     * Prepara la edición de la contraseña del usuario.
     * @param User $user Instancia del modelo a editar.
     */
    public function EditPassd(User $user){
        $this->selected_id = $user->id;
        $this->dispatch('show-modal-pass');
    }

    /**
     * Actualiza la contraseña del usuario autenticado tras validar los datos y la contraseña actual.
     */
    public function UpdatePassword(){
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
        $user = auth()->user();
        if (!Hash::check($this->current_password, $user->password)) {
            $this->dispatch('error-password');   
            return;
        }else{
            $user->password = Hash::make($this->new_password);
            $user->save();
            $this->dispatch('updated-password');
            $this->resetUI();
        }
    }

    /**
     * Actualiza los datos personales y la imagen de perfil del usuario.
     *
     * @param string $modal_state Estado del modal para feedback visual.
     */
    public function Update($modal_state){
        $rules = [
            'email' => "required|unique:users,email,{$this->selected_id}",
            'email' => 'email',
            'name' => 'required|min:3',
            'phone' => 'required|numeric',
            'job_title' => 'required',
        ];
        $messages = [
            'name.required' => 'Requerido',
            'name.min' => 'Debe tener almenos 3 carateres',
            'email.required' => 'Requerido',
            'email.unique' => 'El correo ya existe en el sistema',
            'email.email' => 'Correo no valido',
            'phone.required' => 'Requerido',
            'phone.numeric' => 'Es solo números',
            'job_title.required' => 'Requerido',
        ];
        $this->validate($rules,$messages);
        try { 
            $user = User::find($this->selected_id);
            $user->update([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'job_title' => $this->job_title,
                'birthdate' => $this->birthdate,
                'address' => $this->address,
                'bio' => $this->bio
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
                    // Lee la imagen y la escala a un ancho de 500px.
                    $image = Image::read($this->image)->scale(width: 500);
                    // Convierte la imagen a formato WebP con una calidad de 65 y la guarda en la ruta especificada.
                    $image->toWebp(100)
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
     * Restaura el estado de la UI y limpia validaciones.
     */
    public function resetUI()
    {
        $this->reset([
            'name',
            'bio',
            'email',
            'selected_id',
            'image',
            'phone',
            'job_title',
            'current_password',
            'new_password',
            'new_password_confirmation'
        ]);
        $this->resetValidation();
    }
}

