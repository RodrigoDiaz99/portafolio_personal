<div wire:ignore.self class="modal fade" id="theModal" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="staticBackdropLabel" aria-hidden="true" data-focus="false">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h5 class="modal-title text-white">
                    <b>{{ $componentName }}</b> | {{ $selected_id > 0 ? 'EDITAR' : 'CREAR' }}
                </h5>
            </div>
            <div class="modal-body">
                <div class="position-box">
                    <div class="row">
                        <div class="col-12 col-md-9 col-lg-9">
                            <label class="text-dark">Título <i class="text-danger">*</i> @error('title')
                                    <span class="text-danger er">{{ $message }}</span>
                                @enderror
                            </label>
                            <div class="form-group" wire:ignore>
                                <input type="text" wire:model.defer='title' class="form-control" id="title"
                                    name="title" maxlength="150">
                                <span>Máximo </span><span id="characterCountTitle">0 / 150</span>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-12 col-lg-12">
                                    <div class="form-group">
                                        <label class="text-dark">Resumen (Opcional)@error('excerpt')
                                                <span class="text-danger er">{{ $message }}</span>
                                            @enderror
                                        </label>
                                        <div wire:ignore>
                                            <textarea wire:model.defer="excerpt" rows="3" class="w-100 " maxlength="250" id="excerpt" name="excerpt">
                                         </textarea>
                                            <span>Máximo </span><span id="characterCount">0 / 250 caracteres</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-12 col-md-3 col-lg-3">
                            @if ($thumbnails)
                                <img src="<?php echo asset('storage/posts/thumbnails/' . $thumbnails); ?>" alt=" " class="img-post">
                            @endif

                            <div wire:ignore class="form-group custom-input-file">
                                <label class="filelabel" style="width:252px!important;height:210px!important">
                                    <i class="fa fa-paperclip"></i>
                                    <span class="title" id="miSpan">Agregar una imagen <i class="text-danger">*</i>
                                    </span>
                                    <input class="FileUpload1" id="FileInput" wire:model.defer="image" name="image"
                                        type="file" accept="image/*" />
                                    <img class="preview preview-post" src=" " alt=" " id="miImagen" />
                                </label>
                            </div>
                            @error('image')
                                <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-4 col-lg-4">
                            <div class="form-group" id="catdiv">
                                <label class="text-dark">Categoría <i class="text-danger">*</i> @error('categoryid')
                                        <span class="text-danger er">{{ $message }}</span>
                                    @enderror
                                </label>
                                <div wire:ignore>
                                    <select wire:model="categoryid" class="form-control" style="width: 100%;"
                                        id="select2-dropdown">
                                        <option value="">Seleccione</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 col-lg-3">
                            <div class="form-group">
                                <label class="text-dark">Estado <i class="text-danger">*</i>
                                    @error('publication_status')
                                        <span class="text-danger er">{{ $message }}</span>
                                    @enderror
                                </label>
                                <select wire:model.defer="publication_status" class="small-form-control">
                                    <option value="">Seleccione</option>
                                    <option value="Borrador">Borrador</option>
                                    <option value="Publicado">Publicado</option>
                                    <option value="Pendiente">Pendiente</option>
                                </select>
                            </div>

                        </div>

                        <div class="col-12 col-md-5 col-lg-5">
                            <div class="form-group">
                                <label class="text-dark">Etiquetas</label>
                                <!-- Campo de texto para ingresar tags -->
                                <input type="text" wire:model.live.debounce.500ms="inputTag" wire:keydown.enter="addTag"
                                    placeholder="Escribe una etiqueta y presione enter..."
                                    class="small-form-control w-100">

                                <!-- Mostrar las sugerencias de tags -->
                                @if (!empty($suggestions))
                                    <div class="selected-tags">
                                        @foreach ($suggestions as $tag)
                                            <a href="#" wire:click="addTag('{{ $tag }}')">
                                                <span class="tag-suggestions text-uppercase">{{ $tag }} <i
                                                        class="bi bi-check"></i></span>
                                            </a>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Mostrar los tags seleccionados -->
                                <div class="selected-tags">
                                    @foreach ($tags as $tag)
                                        <span class="tag text-uppercase">
                                            {{ $tag }}
                                            <button wire:click="removeTag('{{ $tag }}')"><i
                                                    class="bi bi-x-circle-fill"></i></button>
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>


                    </div>

                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label for="body" class="text-dark">Contenido <i class="text-danger">*</i>
                                    @error('body')
                                        <span class="text-danger er">{{ $message }}</span>
                                    @enderror
                                </label>

                                
                                <div wire:ignore>
                                    <textarea id="body" wire:model="body">

                                    </textarea>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
            <div class="modal-footer">
                @if ($selected_id < 1)
                    <button type="button" wire:click.prevent="Store('added-close')" wire:loading.attr="disabled"
                        wire:target="image"
                        class="btn btn-border border btn-outline-secondary btn-responsive close-modal ">
                        <b>GUARDAR Y CERRAR</b>
                    </button>
                    <button type="button" wire:click.prevent="Store('added')" wire:loading.attr="disabled"
                        wire:target="image" class="btn btn-border border btn-outline-primary btn-responsive">
                        <b>GUARDAR Y NUEVO</b>
                    </button>
                @else
                    <button type="button" wire:click.prevent="Update('updated-close')" wire:loading.attr="disabled"
                        wire:target="image"
                        class="btn btn-border border btn-outline-secondary btn-responsive close-modal">
                        <b>ACTUALIZAR Y CERRAR</b>
                    </button>
                    <button type="button" wire:click.prevent="Update('updated')" wire:loading.attr="disabled"
                        wire:target="image" class="btn btn-border border btn-outline-primary btn-responsive">
                        <b>ACTUALIZAR Y CONTINUAR</b>
                    </button>
                @endif
                <button type="button" wire:click.prevent="resetUI()"
                    class="btn btn-border border btn-outline-dark btn-responsive close-modal" data-dismiss="modal">
                    CERRAR
                </button>
                <span wire:loading wire:target="CopyPost">
                    <div class="modal fade show" style="display: block;" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-body text-center bg-dark">
                                    <div class="spinner-border text-white" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <h3 class="text-white">Generando...</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-backdrop fade show"></div>
                </span>
                <span wire:loading wire:target="image">
                    <div class="modal fade show" style="display: block;" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-body text-center bg-dark">
                                    <div class="spinner-border text-white" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <h3 class="text-white">Cargando imagen...</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-backdrop fade show"></div>
                </span>
                <span wire:loading wire:target="Store">
                    <div class="modal fade show" style="display: block;" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-body text-center bg-dark">
                                    <div class="spinner-border text-white" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <h3 class="text-white">Guardando...</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-backdrop fade show"></div>
                </span>
                <span wire:loading wire:target="Update">
                    <div class="modal fade show" style="display: block;" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-body text-center bg-dark">
                                    <div class="spinner-border text-white" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <h3 class="text-white">Actualizando...</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-backdrop fade show"></div>
                </span>
            </div>
        </div>
    </div>

</div>
<script src="{{ asset('panel/ckeditor5-build-classic/build/ckeditor.js') }}"></script>
<script>
    // Contador de palabras para el textarea del resumen de las publicaciones
    const title = document.getElementById('title');
    const counter_title = document.getElementById('characterCountTitle');

    // Función para actualizar el contador de caracteres
    function updateCharacterCount() {
        const count = title.value.length;

        counter_title.textContent = count + ' / 150';

        if (count > 150) {
            title.style.borderColor = 'red';
        } else {
            title.style.borderColor = '';
        }
    }

    // Actualiza el contador al cargar el componente
    updateCharacterCount();

    // Evento que se ejecuta cada vez que el usuario escribe en el campo de texto
    title.addEventListener('input', updateCharacterCount);




    // Contador de palabras para el textarea del resumen de las publicaciones
    const excerpt = document.getElementById('excerpt');
    const counter_post = document.getElementById('characterCount');

    // Función para actualizar el contador de caracteres
    function updateCharacterExcerptCount() {
        const count = excerpt.value.length;

        counter_post.textContent = count + ' / 250';

        if (count > 250) {
            excerpt.style.borderColor = 'red';
        } else {
            excerpt.style.borderColor = '';
        }
    }

    // Actualiza el contador al cargar el componente
    updateCharacterExcerptCount();

    // Evento que se ejecuta cada vez que el usuario escribe en el campo de texto
    excerpt.addEventListener('input', updateCharacterExcerptCount);
</script>
