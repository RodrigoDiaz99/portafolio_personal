<div wire:ignore.self  class="modal fade" id="theModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true" >
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header header-modal">
        <h5 class="modal-title text-white">
        	
        	<b>{{$componentName}}</b> | {{ $selected_id > 0 ? 'EDITAR' : 'CREAR'}} 
        </h5>
        <h6 class="text-center text-white" wire:loading>POR FAVOR ESPERE</h6>
    </div>
<div class="modal-body">