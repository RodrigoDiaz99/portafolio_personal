<div wire:ignore.self  class="modal fade" id="theModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-secondary">
        <h5 class="modal-title text-white">
        	<b>{{$componentName}}</b> | {{ $selected_id > 0 ? 'EDITAR' : 'CREAR'}}
        </h5>
      </div>
      <div class="modal-body">

        <div class="row">
          <div class="col-12 col-md-12 col-lg-12" >
              <div class="form-group">
                  <label for="name" class="text-dark">Nombre <i class="text-danger">*</i> @error('name') <span
                      class="text-danger er">{{$message}}</span>@enderror</label>
                  <input type="text" class="form-control" id="name" name="name" wire:model='name'>
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