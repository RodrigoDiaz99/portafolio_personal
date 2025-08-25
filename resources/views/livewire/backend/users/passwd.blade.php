<div wire:ignore.self  class="modal fade" id="theModalPasswd" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h5 class="modal-title text-white">
                    Actualizar contraseña
                </h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <label class="text-dark">Contraseña actual <i class="text-danger">*</i> @error('current_password') <span class="text-danger er">{{$message}}</span>@enderror</label>
                            <div class="input-group border" wire:ignore>
                                <input type="password" id="current_password" wire:model="current_password" class="form-control form-fond">
                                <div class="input-group-append">
                                    <button type="button" onclick="togglePassword('current_password')" class="btn btn-link">
                                    <i id="current_password_icon" class="fa fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <label class="text-dark">Nueva contraseña <i class="text-danger">*</i> @error('new_password') <span class="text-danger er">{{$message}}</span>@enderror</label>
                            <div class="input-group border" wire:ignore>
                                <input type="password" id="new_password" wire:model="new_password" class="form-control form-fond">
                                <div class="input-group-append">
                                    <button type="button" onclick="togglePassword('new_password')" class="btn btn-link">
                                    <i id="new_password_icon" class="fa fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <label class="text-dark">Confirmar nueva contraseña <i class="text-danger">*</i> @error('new_password_confirmation') <span class="text-danger er">{{$message}}</span>@enderror</label>
                            <div class="input-group border" wire:ignore>
                                <input type="password" id="new_password_confirmation" wire:model="new_password_confirmation" class="form-control form-fond">
                                <div class="input-group-append">
                                    <button type="button" onclick="togglePassword('new_password_confirmation')" class="btn btn-link">
                                    <i id="new_password_confirmation_icon" class="fa fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="UpdatePassword()" wire:loading.attr="disabled" wire:target="image" class="btn btn-sm btn-border border btn-outline-secondary btn-responsive close-modal" >
                <b>ACTUALIZAR</b>
                </button>
                <button type="button" wire:click.prevent="resetUIPassd()" class="btn btn-sm btn-border border btn-outline-dark btn-responsive close-modal close-modal" data-dismiss="modal">
                CERRAR
                </button>
                <span wire:loading wire:target="UpdatePassword">
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
    //Función para alternar la visibilidad de la contraseña en un campo de entrada de tipo "password".
    function togglePassword(inputId) {
    // Obtiene el campo de entrada de la contraseña por su ID
    var passwordField = document.getElementById(inputId);
    
    // Obtiene el ícono asociado al campo de entrada
    var passwordIcon = document.getElementById(inputId + '_icon');
    
    // Alterna la visibilidad de la contraseña y actualiza el ícono
    if (passwordField.type === "password") {
        // Si el campo es de tipo "password", cambia a "text" para mostrar la contraseña
        passwordField.type = "text";
        
        // Cambia el ícono de "fa-eye" (ojo cerrado) a "fa-eye-slash" (ojo abierto)
        passwordIcon.classList.remove("fa-eye");
        passwordIcon.classList.add("fa-eye-slash");
    } else {
        // Si el campo es de tipo "text", cambia a "password" para ocultar la contraseña
        passwordField.type = "password";
        
        // Cambia el ícono de "fa-eye-slash" (ojo abierto) a "fa-eye" (ojo cerrado)
        passwordIcon.classList.remove("fa-eye-slash");
        passwordIcon.classList.add("fa-eye");
        }
    }
</script>
