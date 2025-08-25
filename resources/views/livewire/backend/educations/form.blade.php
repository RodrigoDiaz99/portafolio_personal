<div wire:ignore.self  class="modal fade" id="theModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true" >
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h5 class="modal-title text-white">
                    <b>{{$componentName}}</b> | {{ $selected_id > 0 ? 'EDITAR' : 'CREAR'}} 
                </h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label class="text-dark">Institución <i class="text-danger">*</i> @error('institution') <span class="text-danger er">{{$message}}</span>@enderror</label>
                            <input type="text" wire:model.lazy='institution' class="form-control" autofocus>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label class="text-dark">Nivel de estudio <i class="text-danger">*</i> @error('study_level') <span class="text-danger er">{{$message}}</span>@enderror</label>
                            <input type="text" wire:model.lazy='study_level' class="form-control" autofocus>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label class="text-dark">Título obtenido <i class="text-danger">*</i> @error('title_obtained') <span class="text-danger er">{{$message}}</span>@enderror</label>
                            <input type="text" wire:model.lazy='title_obtained' class="form-control" autofocus>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="text-dark">Fecha <i class="text-danger">*</i>  @error('date') <span class="text-danger er">{{$message}}</span>@enderror</label>
                            <input type="date" wire:model.lazy='date' class="form-control" maxlength="11" placeholder="Ej: 01-12-1990">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <label class="text-dark">Descripción (opcional)@error('description') <span class="text-danger er">{{$message}}</span>@enderror</label>
                            <div wire:ignore >
                                <textarea wire:model="description" rows="10" class="w-100" maxlength="700" id="description" name="description">
                                </textarea>
                                <span>Máximo </span><span id="characterCount">0 / 700 caracteres</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                @if($selected_id < 1)
                <button type="button" wire:click.prevent="Store('added-close')" wire:loading.attr="disabled" wire:target="image" class="btn btn-sm btn-border border btn-outline-secondary btn-responsive close-modal " >
                <b>GUARDAR Y CERRAR</b>
                </button>
                <button type="button" wire:click.prevent="Store('added')" wire:loading.attr="disabled" wire:target="image" class="btn btn-sm btn-border border btn-outline-primary btn-responsive" >
                <b>GUARDAR Y NUEVO</b>
                </button>
                @else
                <button type="button" wire:click.prevent="Update('updated-close')" wire:loading.attr="disabled" wire:target="image" class="btn btn-sm btn-border border btn-outline-secondary btn-responsive close-modal" >
                <b>ACTUALIZAR Y CERRAR</b>
                </button>
                <button type="button" wire:click.prevent="Update('updated')" wire:loading.attr="disabled" wire:target="image" class="btn btn-sm btn-border border btn-outline-primary btn-responsive" >
                <b>ACTUALIZAR Y CONTINUAR</b>
                </button>
                @endif
                <button type="button" wire:click.prevent="resetUI()" class="btn btn-sm btn-border border btn-outline-dark btn-responsive close-modal" data-dismiss="modal">
                    CERRAR
                </button>
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
    const textarea = document.getElementById('description');  
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