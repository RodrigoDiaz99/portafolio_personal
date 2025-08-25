<?php
/**
 * Componente Livewire para gestión de redes sociales
 *
 * Proporciona funcionalidad CRUD completa para redes sociales del usuario,
 * incluyendo creación, edición, eliminación y búsqueda.
 *
 * @category  LivewireComponent
 * @package   App\Livewire\Backend
 * @author    Mario
 * @copyright 2025
 */

namespace App\Livewire\Backend;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SocialNetwork;


/**
 * Componente Livewire para administración de redes sociales
 *
 * Permite:
 * - Listar redes sociales con paginación
 * - Buscar redes por nombre
 * - Crear/editar/eliminar registros
 * - Validación de campos requeridos
 */
class SocialNetworks extends Component
{
    use WithPagination;
    
    /** @var string $name Nombre de la red social */
    public $name;

    /** @var string $url Url de la red social */
    public $url;
    
    /** @var string $username Nombre de usuario en la red social */
    public $username;
    
    /** @var int|null $selected_id ID del registro seleccionado para edición */
    public $selected_id;
    
    /** @var string $pageTitle Título de la página */
    public $pageTitle;
    
    /** @var string $componentName Nombre del componente */
    public $componentName;
    
    /** @var array $options Lista de redes sociales disponibles */
    public $options;
    
    /** @var int $lastItem Último ítem mostrado en paginación */
    public $lastItem;
    
    /** @var int $totalRecord Total de registros */
    public $totalRecord;
    
    /** @var string $search Término de búsqueda */
    public $search = '';
    
    /** @var int $pagination Número de ítems por página */
    private $pagination = 24;

    /**
     * Inicializa el componente estableciendo valores por defecto
     *
     * @return void
     */
    public function mount()
    {
        $this->pageTitle = 'Listado';
        $this->componentName = 'Redes Sociales';
        $this->name = 'Seleccione';
        $this->options = [
            'X-Twitter'   => 'https://x.com/',
            'Facebook'    => 'https://facebook.com/',
            'Linkedin'    => 'https://linkedin.com/in/',
            'Instagram'   => 'https://instagram.com/',
            'Telegram'    => 'https://t.me/',
            'Pinterest'   => 'https://pinterest.com/',
            'GitHub'      => 'https://github.com/',
            'YouTube'     => 'https://youtube.com/c/',
            'Reddit'      => 'https://reddit.com/user/',
            'Behance'     => 'https://www.behance.net/',
            'Dribbble'    => 'https://dribbble.com/',
            'DeviantArt'  => 'https://www.deviantart.com/',
            'ArtStation'  => 'https://www.artstation.com/',
            'Ko-fi'       => 'https://ko-fi.com/',
            'TikTok'      => 'https://www.tiktok.com/@',
            'Threads'     => 'https://www.threads.net/@',
            'Snapchat'    => 'https://www.snapchat.com/add/',
            'Medium'      => 'https://medium.com/@',
            'Slack'       => 'https://join.slack.com/',
            'Vimeo'       => 'https://vimeo.com/',
            'Discord'     => 'https://discord.com/users/',
        ];

        $this->lastItem = 0;
        $this->totalRecord = 0;    
    }   

    /**
     * Especifica la vista personalizada para la paginación
     *
     * @return string
     */
    public function paginationView()
    {
        return 'livewire.pagination';
    }

    /**
     * Renderiza el componente con los datos de redes sociales
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        // Realiza una consulta en el modelo SocialNetwork basada en el término de búsqueda proporcionado.
        // Si el campo de búsqueda ($this->search) contiene un valor (longitud mayor a 0),
        // filtra las redes sociales por coincidencias en el campo 'name'.
        // En caso contrario, obtiene todas las redes sociales ordenadas por ID en orden descendente.
        // La cantidad de registros mostrados en cada página está determinada por $this->pagination.
        $networks = strlen($this->search) > 0
        ? SocialNetwork::where('name', 'like', '%' . $this->search . '%')->paginate($this->pagination)
        : SocialNetwork::orderBy('id', 'desc')->paginate($this->pagination);

        // Obtener el número total de los registros
        $this->totalRecord = $networks->total();

        // Obtener el número de la última entrada en la página actual
        $this->lastItem = $networks->lastItem() ?? 0;

        return view('livewire.backend.social-networks.component', [
            "showing" => 'Viendo '.$this->lastItem ." de ". $this->totalRecord ." registros",
            'networks' => $networks
        ])
        ->extends('layouts.backend.app')
        ->section('content');
    }

    /**
     * Almacena una nueva red social
     *
     * @param string $modal_state Estado del modal a despachar
     * @return void
     * @throws \Exception Si ocurre un error durante el almacenamiento
     */
    public function Store($modal_state)
    {
        
    	$rules = [
            'name' => 'required|not_in:Seleccione|unique:social_networks,name',
            'username' => 'required',
        ];

    	$messages = [
    		'name.required' => 'Requerido',
            'name.not_in' => 'Requerido',
            'name.unique' => 'Ya esta registrado',
            'username.required' => 'Requerido',
    	];

    	$this->validate($rules, $messages);
        try {
            if (isset($this->options[$this->name])) {
                $this->url = $this->options[$this->name];
            } else {
                $this->url = null;
            }

            SocialNetwork::create([
                'user_id' => Auth::id(),
                'name' => $this->name,
                'url' => $this->url,
                'username' => $this->username
            ]);
            $this->dispatch($modal_state);
            $this->resetUI();
        } catch (Exception $e) {
            $this->dispatch('error','Error: '. $e->getMessage());
        }
    }

    /**
     * Prepara los datos para editar una red social existente
     *
     * @param SocialNetwork $network Modelo de red social a editar
     * @return void
     */
    public function Edit(SocialNetwork $network)
    {
     
    	$this->selected_id = $network->id;
    	$this->name = $network->name;
        $this->url = $network->url;
    	$this->username = $network->username;
    	$this->dispatch('show-modal');
        
    }

    /**
     * Actualiza una red social existente
     *
     * @param string $modal_state Estado del modal a despachar
     * @return void
     * @throws \Exception Si ocurre un error durante la actualización
     */
    public function Update($modal_state)
    {
    	$rules = [
            'name' => "required|not_in:Seleccione|unique:social_networks,name,{$this->selected_id}",
            'username' => 'required',
        ];

    	$messages = [
    		'name.required' => 'Requerido',
            'name.not_in' => 'Requerido',
            'name.unique' => 'Ya esta registrado',
            'username.required' => 'Requerido',
    	];

    	$this->validate($rules, $messages);
        try {
            $network = SocialNetwork::find($this->selected_id);
            if (isset($this->options[$this->name])) {
                $this->url = $this->options[$this->name];
            } else {
                $this->url = null;
            }
            
            $network->update([
                'user_id' => Auth::id(),
                'name' => $this->name,
                'url' => $this->url,
                'username' => $this->username
            ]);
            $this->dispatch($modal_state);
            if($modal_state=='updated-close'){
                $this->resetUI();
            }
    	
        } catch (Exception $e) {
            $this->dispatch('error','Error: '. $e->getMessage());
        }
    }

    /**
     * Prepara la eliminación de una red social
     *
     * @param int $id ID del registro a eliminar
     * @return void
     */
    public function deleteRow($id)
    {
        $this->selected_id = $id;
        $this->dispatch('confirmDelete');
    }

    #[On("Destroy")]
    /**
     * Elimina permanentemente una red social
     *
     * @return void
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Si el registro no existe
     */
    public function Destroy()
    {
        $record = SocialNetwork::findOrFail($this->selected_id);
        $record->delete();
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
            'url',
            'username',
            'selected_id',
        ]);
    
        $this->resetValidation();
    }
}
