<div class="row layout-top-spacing">
    <div class="col-sm-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading m-3">
                <h4 class="card-title compo-title">
                    <b>{{ $componentName }}</b> <i>/ {{ $pageTitle }}</i>
                </h4>
                <a href="javascript:void(0)" class="btn btn-lg btn-border border btn-link btn-responsive"
                    data-toggle="modal" data-target="#theModal" onclick="limpiarImagen()">NUEVO</a>
            </div>
            <div class="m-3">
                @include('common.searchbox')
            </div>
            <div class="float-right">
                <div class="d-flex justify-content-end bg-light p-2">
                    <button class="btn p-1 {{ $showDeleted ? 'btn-outline-success' : 'btn-outline-danger' }}"
                        wire:click="toggleShowDeleted">
                        {{ $showDeleted ? 'Mostrar Activos' : 'Mostrar Eliminados' }}
                    </button>
                </div>
            </div>
            <div class="widget-content">
                <div class="tablet-responsive">
                    <span class="text-center m-3"><i>{{ $showing }}</i></span>
                    <table class="tablet table-striped table-bordered mt-1 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <thead class="text-white bg-dark">
                            <tr class="text-center">
                                <th>TITULO</th>
                                <th>CREADO</th>
                                <th>MODIFICADO</th>
                                <th>CATEGORIA</th>
                                <th>VISTAS</th>
                                <th>VISIBILIDAD</th>
                                <th class="tablet-th text-white" wire:ignore>ESTADO
                                    <i class="bi bi-info-circle-fill" data-toggle="tooltip" data-placement="top"
                                        title="Indica si el registro está eliminado o activo"></i>
                                </th>
                                <th>ACCION</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($posts as $post)
                                <tr class="text-dark text-center">
                                    <td class="w-25">{{ $post->title }}</td>
                                    <td class="text-center">
                                        {{ \Carbon\Carbon::parse($post->created_at)->format('d-m-Y') }}</td>
                                    <td class="text-center">
                                        {{ \Carbon\Carbon::parse($post->updated_at)->format('d-m-Y') }}</td>
                                    <td>{{ $post->PostCategory->name }}</td>
                                    <td>{{ $post->views_count }}</td>
                                    <td>
                                        <span
                                            class="badge 
                                        {{ $post->publication_status == 'Publicado'
                                            ? 'badge-success'
                                            : ($post->publication_status == 'Pendiente'
                                                ? 'badge-warning'
                                                : 'badge-secondary') }} uppercase">
                                            {{ $post->publication_status == 'Publicado'
                                                ? 'Publicado'
                                                : ($post->publication_status == 'Pendiente'
                                                    ? 'Pendiente'
                                                    : 'Borrador') }}
                                        </span>

                                    </td>
                                    <td>
                                        <span
                                            class="badge {{ $showDeleted ? 'badge-danger' : 'badge-success' }} uppercase">{{ $showDeleted ? 'Eliminado' : 'Activo' }}
                                        </span>
                                    </td>
                                    <td style="padding: 0;">
                                        <div class="dropdown" style="width: 100%; height: 100%;">
                                            <button class="btn btn-light dropdown-toggle w-100 text-truncate"
                                                type="button" id="dropdownMenuButton" data-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                                Opciones <i class="fas fa-caret-down"></i>
                                            </button>
                                            <div class="dropdown-menu w-100" aria-labelledby="dropdownMenuButton">
                                                @if ($showDeleted)
                                                    <a class="dropdown-item" href="javascript:void(0)"
                                                        wire:click.prevent='restoreRow({{ $post->id }})'>
                                                        <i class="bi bi-arrow-counterclockwise"></i> Restaurar
                                                    </a>
                                                    <a class="dropdown-item" href="javascript:void(0)"
                                                        wire:click.prevent='deleteRowPerm({{ $post->id }})'>
                                                        <i class="bi bi-archive"></i> Eliminar Permanentemente
                                                    </a>
                                                @else
                                                    <a class="dropdown-item" href="javascript:void(0)"
                                                        wire:click.prevent="Edit({{ $post->id }})">
                                                        <i class="bi bi-pencil"></i> Editar
                                                    </a>
                                                    <a class="dropdown-item" href="javascript:void(0)"
                                                        wire:click.prevent='deleteRow({{ $post->id }})'>
                                                        <i class="bi bi-archive"></i> Eliminar
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $posts->links() }}
                </div>
            </div>
        </div>
        <span wire:loading wire:target="Destroy">
            <div class="modal fade show" style="display: block;" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body text-center bg-dark">
                            <div class="spinner-border text-white" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <h3 class="text-white">Eliminando...</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-backdrop fade show"></div>
        </span>
        <span wire:loading wire:target="indexingUrl">
            <div class="modal fade show" style="display: block;" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body text-center bg-dark">
                            <div class="spinner-border text-white" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <h3 class="text-white">Enviando Solicitud...</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-backdrop fade show"></div>
        </span>
    </div>
    @include('livewire.backend.posts.form')
</div>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        /**
         * Este script configura e integra un componente Select2 con Livewire en un proyecto Laravel.
         * 
         * Funcionalidades principales:
         * 1. Inicializar el plugin Select2 en un elemento de tipo dropdown.
         *    - El dropdown se renderiza dentro de un contenedor específico, útil para modales.
         * 2. Escuchar los cambios en el dropdown de Select2.
         *    - Captura tanto el valor seleccionado como el texto asociado.
         *    - Sincroniza estos valores con las propiedades del componente Livewire.
         * 3. Escuchar eventos emitidos desde Livewire.
         *    - Al recibir el evento "reset-category", el dropdown se resetea a su estado inicial.
         */
        $(document).ready(function() {
            $('#select2-dropdown').select2({
                dropdownParent: $('#catdiv')
            });
            $('#select2-dropdown').on('change', function(e) {
                var catid = $('#select2-dropdown').select2("val");
                var catname = $('#select2-dropdown option:selected').text();
                @this.set('categoryid', catid);
                @this.set('categoryname', catname);
            });

            window.Livewire.on('reset-category', () => {
                setTimeout(() => {
                        $('#select2-dropdown').val(null).trigger(
                            'change'); // Limpia y actualiza el campo Select2
                    },
                    100
                ); // Ajusta el tiempo si es necesario para esperar la actualización del DOM
            });
        });

        let editor;

        // Se inicializa el editor de texto enriquecido (CKEditor) en el elemento con el id 'body'
        ClassicEditor
            .create(document.querySelector('#body'), {
                // Configuración para la carga de imágenes
                simpleUpload: {
                    // URL del endpoint para subir imágenes (ruta de Livewire)
                    uploadUrl: '{{ route('image.upload') }}',
                    headers: {
                        // Incluye el token CSRF en los encabezados para garantizar la seguridad
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        // Acepta solo respuestas en formato JSON
                        'accept': 'application/json'
                    }
                },
            })
            .then(function(leditor) {
                // Se asigna el editor inicializado a la variable 'editor'
                editor = leditor;

                // Escucha los cambios en el contenido del editor y actualiza el valor de 'content' en el componente Livewire
                leditor.model.document.on('change:data', () => {
                    @this.set('body', leditor
                        .getData()); // Envía el contenido del editor a Livewire
                });
            })
            .catch(error => {
                // Maneja cualquier error que ocurra durante la inicialización del editor
                console.error(error);
            });

        window.Livewire.on('edit-description', Msg => {
            editor.setData(String(Msg));
        });

        window.Livewire.on('clear-description', Msg => {
            editor.setData(''); // Establece el contenido recibido en el editor
        });

        window.Livewire.on('show-modal-post', () => {

            // Mostrar el modal
            $('#theModal').modal('show');

            // Inicializar Select2 con el contenedor del modal
            $('#select2-dropdown').select2({
                dropdownParent: $('#catdiv')
            });

            // Establecer el valor actual del campo desde Livewire
            setTimeout(() => {
                $('#select2-dropdown').val(@this.get('categoryid')).trigger(
                    'change'); // Sincroniza el valor
                updateCharacterCount();
                updateCharacterExcerptCount();
            }, 100); // Pequeño retraso para asegurarse de que el DOM esté listo
        });

        window.Livewire.on('reset-category', () => {
            setTimeout(() => {
                $('#select2-dropdown').val(null).trigger(
                    'change'); // Limpia y actualiza el campo Select2
            }, 100);
        });
        window.Livewire.on('clear-imagen', () => {
            limpiarImagen();
        });

        // Evento que limpia el contenido del editor cuando se recibe el evento 'clear-description' desde Livewire
        window.Livewire.on('clear-description', () => {
            editor.setData(''); // Resetea el contenido del editor
            resetTitle()
        });
        window.Livewire.on('resetTitleExcerpt', function() {
            title.value = ''; // Limpia el campo
            updateCharacterCount(); // Reinicia el contador

            excerpt.value = ''; // Limpia el campo
            updateCharacterExcerptCount(); // Reinicia el contador
        });


    });
</script>
@include('livewire.events')

