</div>
    <div class="modal-footer">
        <div class="mx-auto d-none" wire:loading.class="loader-upload d-flex" wire:target="image">
            <label>Cargando imagen...</label>
        </div>
        @if($selected_id < 1)
        <button type="button" wire:click.prevent="Store()" class="btn btn-border border btn-outline-info btn-responsive close-modal " wire:loading.attr="disabled" wire:target="image">
            <b>GUARDAR</b>
        </button>
        @else
        <button type="button" wire:click.prevent="Update()"  class="btn btn-border border btn-outline-info btn-responsive close-modal " wire:loading.attr="disabled" wire:target="image">
            <b>ACTUALIZAR</b>
        </button>
        @endif
        <button type="button" wire:click.prevent="resetUI()" class="btn btn-border border btn-link btn-responsive close-modal" data-dismiss="modal" >
            CERRAR
        </button>

        <span wire:loading wire:target="image"><div class="modal fade show" style="display: block;" tabindex="-1" role="dialog">
			<div class="modal-dialog modal-dialog-centered">
	  
				<div class="modal-content">
					<div class="modal-body text-center bg-dark">
						<div class="spinner-border text-white" role="status">
							<span class="sr-only">Loading...</span>
						</div>
						<h3 class="text-white">Subiendo Imágenes...</h3>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-backdrop fade show"></div> 
	  </span>
	  
		 <span wire:loading wire:target="Store"><div class="modal fade show" style="display: block;" tabindex="-1" role="dialog">
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
	  <span wire:loading wire:target="Update"><div class="modal fade show" style="display: block;" tabindex="-1" role="dialog">
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