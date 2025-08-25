<?php

/**
 * Componente Livewire para la gestión de habilidades/habilidades técnicas.
 * Permite crear, editar, eliminar y listar habilidades con porcentaje de dominio.
 * Incluye manejo de imágenes, validaciones y soft deletes.
 *
 * Funcionalidades principales:
 * - CRUD completo de habilidades
 * - Manejo de imágenes en formato WebP
 * - Porcentaje de dominio validado (0-100)
 * - Soft delete y restauración
 * - Búsqueda y paginación
 *
 * @category  LivewireComponent
 * @package   App\Livewire\Backend
 * @author    Mario
 * @copyright 2025
 */

namespace App\Livewire\Backend;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Skill;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;
use Intervention\Image\Exception\NotReadableException;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Clase principal del componente Livewire para gestión de habilidades.
 */
class Skills extends Component
{
    use WithFileUploads;
    use WithPagination;

    /**
     * Propiedades públicas para el manejo de datos y estado del componente.
     * @var string $category Área a la que pertenece la habilidad
     * @var string $ability Nombre de la habilidad
     * @var int $level Porcentaje de dominio (0-100)
     * @var string $description Descripción de la habilidad
     * @var mixed $image Imagen representativa
     * @var int|null $selected_id ID seleccionado para edición
     * @var string $pageTitle Título de la página
     * @var string $componentName Nombre del componente
     * @var string $search Término de búsqueda
     * @var int $lastItem Último ítem mostrado
     * @var int $totalRecord Total de registros
     * @var bool $showDeleted Mostrar eliminados
     * @var int $pagination Items por página
     */

    public $category, $ability,$level, $description,$image, $selected_id, $pageTitle, $componentName,$search,$lastItem,$totalRecord;
    public $showDeleted = false; // Indica si mostrar los eliminados
	private $pagination = 24;

    /**
     * Inicializa el componente con valores por defecto.
     */
    public function mount(){
        $this->pageTitle = 'Listado';
        $this->componentName = 'Habilidades';
        $this->level = 0;
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
     * Alterna la visualización de registros eliminados.
     */
    public function toggleShowDeleted()
    {
        $this->showDeleted = !$this->showDeleted;
    }

    /**
     * Renderiza la vista principal con las habilidades paginadas.
     * @return \Illuminate\View\View
     */
    public function render()
    {
        // Realiza una consulta en el modelo Skill en función del término de búsqueda proporcionado.
        // Si el campo de búsqueda ($this->search) contiene algún valor (longitud mayor a 0),
        // se filtran las habilidades por coincidencia en el campo 'name'.
        // En caso contrario, se obtienen todas las habilidades ordenadas por ID en orden descendente.
        // La cantidad de registros mostrados en cada página está definida por el valor de $this->pagination.
        $skills = $this->showDeleted 
        ? Skill::onlyTrashed()->where('ability', 'like', '%' . $this->search . '%')->orderBy('id', 'desc')->paginate($this->pagination)
        : Skill::where('ability', 'like', '%' . $this->search . '%')->orderBy('id', 'desc')->paginate($this->pagination);

        // Obtener el número total de los registros
        $this->totalRecord = $skills->total();

        // Obtener el número de la última entrada en la página actual
        $this->lastItem = $skills->lastItem() ?? 0;

        return view('livewire.backend.skills.component', [
            "showing" => 'Viendo '.$this->lastItem ." de ". $this->totalRecord ." registros",
            'skills' => $skills
        ])
        ->extends('layouts.backend.app')
        ->section('content');
    }

    /**
     * Almacena una nueva habilidad en la base de datos.
     * @param string $modal_state Estado del modal para feedback visual
     */
    public function Store($modal_state){
    	$rules = [
            'category' => 'required',
            'ability' => 'required|unique:skills,ability',
            'level' => 'required|numeric|min:0.01|max:100',
            'description' => 'max:250',
            'image' => 'nullable',
        ];

    	$messages = [
            'category.required' => 'Requerido',
    		'ability.required' => 'Requerido',
            'ability.unique' => 'Ya esta registrado',
            'level.required' => 'Requerido',
            'level.min' => 'Requerido',
            'level.max' => 'Máximo 100',
            'description.max' => 'Máximo 350 caracteres',
    	];

    	$this->validate($rules, $messages);
        try { 
    	$skill = Skill::create([
            'user_id' => Auth::id(),
            'category' => $this->category,
            'ability' => $this->ability,
            'level' => $this->level,
            'description' => $this->description
        ]);


        // Genera un slug (URL amigable) a partir del nombre proporcionado ($this->ability) y define un nombre de archivo personalizado nulo.
        $slug = Str::of($this->ability)->slug('-');
        $customFileName = null;

        // Verifica si $this->image es un array o un objeto para asegurar que se trate de una imagen válida.
        if (is_array($this->image) || is_object($this->image)) {
            if ($this->image) {
                // Genera un nombre único para la imagen combinando el slug con un identificador único.
                $customFileName = $slug . '-' . uniqid();

                // Lee y escala la imagen a un ancho de 350px.
                $image = Image::read($this->image)->scale(width: 350);

                // Convierte la imagen a formato WebP con una calidad de 100 y la guarda en la ruta especificada.
                $image->toWebp(100)
                    ->save(public_path('storage/users/skills/' . $customFileName . '.webp'));

                // Actualiza el campo de imagen en la base de datos con el nuevo nombre de archivo.
                $oldImage = $skill->image;
                $skill->image = $customFileName . '.webp';
                $skill->save();

                // Si existe una imagen anterior en el sistema de archivos, la elimina para liberar espacio.
                if ($oldImage && file_exists(public_path('storage/users/skills/' . $oldImage))) {
                    unlink(public_path('storage/users/skills/' . $oldImage));
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
     * Prepara la edición de una habilidad existente.
     * @param Skill $skill Instancia del modelo Skill a editar
     */
    public function Edit(Skill $skill){
    	$this->selected_id = $skill->id;
        $this->category = $skill->category;
    	$this->ability = $skill->ability;
        $this->level = $skill->level;
    	$this->description = $skill->description;
        $this->image = $skill->image;
    	$this->dispatch('show-modal');
    }

    /**
     * Actualiza una habilidad existente en la base de datos.
     * @param string $modal_state Estado del modal para feedback visual
     */
    public function Update($modal_state){
    	$rules = [
            'category' => 'required',
            'ability' => "required|unique:skills,ability,{$this->selected_id}",
            'level' => 'required|numeric|min:0.01',
            'description' => 'max:250',
            'image' => 'nullable',
        ];

    	$messages = [
            'category.required' => 'Requerido',
    		'ability.required' => 'Requerido',
            'ability.unique' => 'Ya esta registrado',
            'level.required' => 'Requerido',
            'level.min' => 'Requerido',
            'description.max' => 'Máximo 250 caracteres',
    	];

    	$this->validate($rules, $messages);
        try {
        $skill = Skill::find($this->selected_id);
       
    	$skill->update([
            'category' => $this->category,
            'ability' => $this->ability,
            'level' => $this->level,
            'description' => $this->description           
        ]);

        // Genera un slug (URL amigable) a partir del nombre proporcionado ($this->ability) y define un nombre de archivo personalizado nulo.
        $slug = Str::of($this->ability)->slug('-');
        $customFileName = null;

        // Verifica si $this->image es un array o un objeto para asegurar que se trata de una imagen válida.
        if (is_array($this->image) || is_object($this->image)) {
            // Compara la nueva imagen con la imagen actual del modelo $skill.
            if ($this->image != $skill->image) {
                // Genera un nombre único para la imagen combinando el slug con un identificador único.
                $customFileName = $slug . '-' . uniqid();

                // Lee y escala la imagen a un ancho de 350px.
                $image = Image::read($this->image)->scale(width: 350);

                // Convierte la imagen a formato WebP con una calidad de 100 y la guarda en la ruta especificada.
                $image->toWebp(100)
                    ->save(public_path('storage/users/skills/' . $customFileName . '.webp'));

                // Actualiza el campo de imagen en la base de datos con el nuevo nombre de archivo.
                $oldImage = $skill->image;
                $skill->image = $customFileName . '.webp';
                $skill->save();

                // Si existe una imagen anterior en el sistema de archivos, la elimina para liberar espacio.
                if ($oldImage && file_exists(public_path('storage/users/skills/' . $oldImage))) {
                    unlink(public_path('storage/users/skills/' . $oldImage));
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
     * Prepara la eliminación de una habilidad (soft delete).
     * @param int $id ID de la habilidad a eliminar
     */
    public function deleteRow($id){
        $this->selected_id = $id;
        $this->dispatch('confirmDelete');
    }
    /**
     * Prepara la restauración de una habilidad eliminada.
     * @param int $id ID de la habilidad a restaurar
     */
    public function restoreRow($id){
        $this->selected_id = $id;
        $this->dispatch('confirmRestore');
    }

    /**
     * Prepara la eliminación permanente de una habilidad.
     * @param int $id ID de la habilidad a eliminar permanentemente
     */
    public function deleteRowPerm($id){
        $this->selected_id = $id;
        $this->dispatch('confirmDeletePerm');
    }

    /**
     * Elimina una habilidad (soft delete).
     * Se ejecuta después de confirmación.
     */
    #[On("Destroy")]
    public function Destroy()
    {
        $record = Skill::withTrashed()->findOrFail($this->selected_id);
        $record->delete();
        $this->dispatch('deleted');
        $this->resetUI();
    }

    /**
     * Restaura una habilidad previamente eliminada.
     */
    public function Restore()
    {
        $record = Skill::onlyTrashed()->findOrFail($this->selected_id);
        $record->restore();

        $this->dispatch('restore');
        $this->resetUI();
    }

    /**
     * Elimina permanentemente una habilidad y su imagen asociada.
     */
    public function forceDelete()
    {
        $record = Skill::onlyTrashed()->findOrFail($this->selected_id);
        // Elimina físicamente el registro (borrado permanente)
        $oldImage = $record->image;


        // Verifica y elimina las imágenes
        if ($oldImage && file_exists(public_path('storage/users/skills/' . $oldImage))) {
            unlink(public_path('storage/users/skills/' . $oldImage));
        }

        $record->forceDelete(); // Borra permanentemente el registro
        $this->dispatch('deleted');
        $this->resetUI();
    }


    /**
     * Restablece las propiedades del componente a sus valores iniciales.
     */
    public function resetUI()
    {
        $this->reset([
            'category',
            'ability',
            'level',
            'description',
            'selected_id',
            'image',
        ]);
        $this->dispatch('clear-imagen');
        $this->dispatch('clearPreviews');
        $this->resetValidation();
    }

}

