<div wire:ignore.self  class="modal fade" id="theModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h5 class="modal-title text-white">
                    <b>{{$componentName}}</b> | {{ $selected_id > 0 ? 'VER' : 'CREAR'}} 
                </h5>
                <h6 class="text-center text-white" wire:loading>POR FAVOR ESPERE</h6>
            </div>
            <div class="modal-body">
                <div class="position-box">
                    
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="form-group">
                               <div class="card">
                                <div class="card-header">
                                    <h6><strong>Nombre:</strong> {{ $name }}</h6>
                                    <h6><strong>Correo:</strong> {{ $email }}</h6>
                                    <h6><strong>Post:</strong> {{ $post }}</h6>
                                    <h6 class="d-flex align-items-center w-50">
                                        <strong>Estado:</strong>
                                        <select id="status" wire:model="status" class="small-form-control w-50 ml-2" aria-label="Default select Metodo de pago" name="status">
                                            <option value="Aprobado" {{ $status == 'Aprobado' ? 'selected' : '' }}>Aprobado</option>
                                            <option value="Pendiente" {{ $status == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                                            <option value="No Aprobado" {{ $status == 'No Aprobado' ? 'selected' : '' }}>No Aprobado</option>
                                        </select>
                                    </h6>                                    
                                </div>
                                <div class="card-body text-dark">
                                    <span style="line-height: 2;font-size: 1rem;">{!! $content !!}</span>
                                </div>
                                
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                
                <button type="button" wire:click.prevent="Update('updated-close')" wire:loading.attr="disabled" wire:target="image" class="btn btn-sm btn-border border btn-outline-secondary btn-responsive close-modal" >
                <b>GUARDAR Y CERRAR</b>
                </button>
                <button type="button" wire:click.prevent="Update('updated')" wire:loading.attr="disabled" wire:target="image" class="btn btn-sm btn-border border btn-outline-primary btn-responsive" >
                <b>GUARDAR Y CONTINUAR</b>
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
