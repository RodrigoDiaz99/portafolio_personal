<div wire:ignore.self  class="modal fade" id="theModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h5 class="modal-title text-white">
                    <b>{{$componentName}}</b> | {{ $selected_id > 0 ? 'EDITAR' : 'CREAR'}} 
                </h5>
            </div>
            <div class="modal-body">
                <div class="position-box">
                    <div class="row">
                        <div class="col-12 col-md-8 col-lg-8">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="text-dark">Nombre <i class="text-danger">*</i> @error('name') <span class="text-danger er">{{$message}}</span>@enderror</label>
                                    <input type="text" wire:model.lazy='name' class="form-control">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="text-dark">Correo <i class="text-danger">*</i> @error('email') <span class="text-danger er">{{$message}}</span>@enderror</label>
                                    <input type="text" wire:model.lazy='email' class="form-control  ">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="text-dark">Título profesional <i class="text-danger">*</i> @error('job_title') <span class="text-danger er">{{$message}}</span>@enderror</label>
                                    <input type="text" wire:model.lazy='job_title' class="form-control  ">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-lg-4">
                            @if ($image)
                                <img src="<?php echo asset('storage/users/profile/' . $image)?>" alt=" " class="img-user">
                            @endif
                             
                            <div  wire:ignore class="form-group custom-input-file" >
                                <label class="filelabel" style="width:210px!important;height:210px!important">
                                <i class="fa fa-paperclip"></i>
                                <span class="title" id="miSpan">Agregar una imagen <i class="text-danger">*</i> </span>
                                <input class="FileUpload1" id="FileInput" wire:model.lazy="image" name="image" type="file" accept="image/*"  />
                                <img class="preview preview-user" src=" " alt=" " id="miImagen"/>                             
                                </label>				
                            </div>
                            @error('image') <span class="text-danger er">{{$message}}</span>@enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label class="text-dark">Teléfono <i class="text-danger">*</i> @error('phone') <span class="text-danger er">{{$message}}</span>@enderror</label>
                                <input type="text" wire:model.lazy='phone' class="form-control  ">
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label class="text-dark">Fecha de nacimiento <i class="text-danger">*</i> @error('birthdate') <span class="text-danger er">{{$message}}</span>@enderror</label>
                                <input type="date" wire:model.lazy='birthdate' class="form-control  ">
                            </div>
                        </div>
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label class="text-dark">Dirección <i class="text-danger">*</i> @error('address') <span class="text-danger er">{{$message}}</span>@enderror</label>
                                <input type="text" wire:model.lazy='address' class="form-control  ">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-12">
                            <div class="form-group">
                                <label class="text-dark">Biografía @error('bio') <span class="text-danger er">{{$message}}</span>@enderror</label>
                                <div wire:ignore >
                                    <textarea wire:model.lazy="bio" rows="5" class="w-100 form-control" maxlength="700" id="bio" name="bio">
                                    </textarea>
                                    <span>Máximo </span><span id="characterCount">0 / 700 caracteres</span>
                                </div>
                                @error('bio')
                                <span class="text-danger er">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="Update('updated-user-close')" wire:loading.attr="disabled" wire:target="image" class="btn btn-sm btn-border border btn-outline-secondary btn-responsive close-modal" >
                <b>ACTUALIZAR Y CERRAR</b>
                </button>
                <button type="button" wire:click.prevent="Update('updated-user')" wire:loading.attr="disabled" wire:target="image" class="btn btn-sm btn-border border btn-outline-primary btn-responsive" >
                <b>ACTUALIZAR Y CONTINUAR</b>
                </button>
                <button type="button" wire:click.prevent="resetUI()" class="btn btn-sm btn-border border btn-outline-dark btn-responsive close-modal" data-dismiss="modal">
                    CERRAR
                </button>
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
<script>
    document.addEventListener('livewire:init', () => {
    // Contador de palabras para los textarea
    const textarea = document.getElementById('bio');  
    const counter = document.getElementById('characterCount');
 
    // Evento que se ejecuta cada vez que el usuario escribe en el campo de texto
    textarea.addEventListener('input', function() {
        // Obtiene la longitud del texto ingresado en el textarea
        const count = textarea.value.length;

        // Muestra el contador de caracteres en el formato "X / 700"
        counter.textContent = count + ' / 700';

        // Cambia el color del borde del textarea si se excede el límite de caracteres
        if (count > 700) {
            textarea.style.borderColor = 'red'; // Borde rojo si se excede el límite
        } else {
            textarea.style.borderColor = ''; // Vuelve al borde original si no se excede el límite
        }
        });
    });
</script>