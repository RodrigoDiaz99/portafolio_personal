<?php
/**
 * PostComments
 *
 * Componente Livewire para la gestión CRUD de comentarios de publicaciones.
 * Permite listar, buscar, editar, eliminar (soft y hard delete), restaurar y paginar comentarios asociados a publicaciones.
 * Incluye validación, control de relaciones y feedback visual para el usuario administrador.
 *
 * Funcionalidades principales:
 * - Listado y búsqueda de comentarios.
 * - Paginación de resultados.
 * - Edición y validación de comentarios.
 * - Eliminación lógica y permanente, y restauración de registros.
 * - Alternancia entre registros activos y eliminados.
 *
 * @category  LivewireComponent
 * @package   App\Livewire\Backend
 * @author    Mario
 * @copyright 2025
 */

namespace App\Livewire\Backend;
use App\Models\PostComment;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;


/**
 * Clase principal del componente Livewire para la gestión de comentarios de publicaciones.
 */
class PostComments extends Component
{
    use WithPagination;

    /**
     * Propiedades públicas para el manejo de datos y estado del componente.
     * @var string $name Nombre del autor del comentario.
     * @var string $email Email del autor.
     * @var string $content Contenido del comentario.
     * @var string $status Estado del comentario (aprobado, pendiente, etc).
     * @var string $post Título del post asociado.
     * @var string $search Término de búsqueda.
     * @var int|null $selected_id ID seleccionado para edición o eliminación.
     * @var string $pageTitle Título de la página.
     * @var string $componentName Nombre del componente.
     * @var int $lastItem Último registro mostrado en la página actual.
     * @var int $totalRecord Total de registros encontrados.
     * @var bool $showDeleted Indica si se muestran registros eliminados.
     * @var int $pagination Cantidad de elementos por página.
     */
    public $name, $email, $content, $status, $post, $search, $selected_id, $pageTitle, $componentName, $lastItem, $totalRecord;
    public $showDeleted = false;
    private $pagination = 24;


    /**
     * Inicializa el componente con valores por defecto.
     */
    public function mount()
    {    
        $this->pageTitle = 'Listado';
        $this->componentName = 'Comentarios';
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
     * Renderiza la vista principal del componente, obteniendo los comentarios según búsqueda y estado.
     * Calcula totales y muestra la paginación.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $comments = $this->showDeleted 
            ? PostComment::onlyTrashed()->where('name', 'like', '%' . $this->search . '%')->orderBy('id', 'desc')->paginate($this->pagination)
            : PostComment::where('name', 'like', '%' . $this->search . '%')->orderBy('id', 'desc')->paginate($this->pagination);

        // Obtener el número total de los registros
        $this->totalRecord = $comments->total();

        // Obtener el número de la última entrada en la página actual
        $this->lastItem = $comments->lastItem() ?? 0;

        return view('livewire.backend.post-comments.component', [
            "showing" => 'Viendo '.$this->lastItem ." de ". $this->totalRecord ." registros",
            'comments' => $comments
        ])
        ->extends('layouts.backend.app')
        ->section('content');
    }


    /**
     * Carga los datos de un comentario para su edición.
     *
     * @param PostComment $comment Instancia del modelo a editar.
     */
    public function Edit(PostComment $comment){
        $this->selected_id = $comment->id;
        $this->name = $comment->name;
        $this->email = $comment->email;
        $this->status = $comment->status;
        $this->post = $comment->Post->title;
        $this->content = $comment->content;
        $this->dispatch('show-modal');
    }


    /**
     * Actualiza el estado de un comentario existente.
     *
     * @param string $modal_state Estado del modal para feedback visual.
     */
    public function Update($modal_state){
        $tag = PostComment::find($this->selected_id);
        $tag->update([
            'status' => $this->status,
        ]);
        $this->dispatch($modal_state);
        if($modal_state=='updated-close'){
            $this->resetUI();
        }
    }


    /**
     * Solicita confirmación para eliminar lógicamente un comentario.
     * @param int $id
     */
    public function deleteRow($id){
        $this->selected_id = $id;
        $this->dispatch('confirmDelete');
    } 

    /**
     * Solicita confirmación para restaurar un comentario eliminado.
     * @param int $id
     */
    public function restoreRow($id){
        $this->selected_id = $id;
        $this->dispatch('confirmRestore');
    }

    /**
     * Solicita confirmación para eliminar permanentemente un comentario.
     * @param int $id
     */
    public function deleteRowPerm($id){
        $this->selected_id = $id;
        $this->dispatch('confirmDeletePerm');
    }


    /**
     * Elimina lógicamente un comentario (soft delete).
     */
    #[On("Destroy")]
    public function Destroy()
    {
        $record = PostComment::findOrFail($this->selected_id);
        $record->delete();
        $this->dispatch('deleted');
        $this->resetUI();
    }

    /**
     * Restaura un comentario previamente eliminado.
     */
    public function Restore()
    {
        $record = PostComment::onlyTrashed()->findOrFail($this->selected_id);
        $record->restore();
        $this->dispatch('restore');
        $this->resetUI();
    }

    /**
     * Elimina permanentemente un comentario (hard delete).
     */
    public function forceDelete()
    {
        $record = PostComment::onlyTrashed()->findOrFail($this->selected_id);
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
            'selected_id',
        ]);
        $this->resetValidation();
    }
}
