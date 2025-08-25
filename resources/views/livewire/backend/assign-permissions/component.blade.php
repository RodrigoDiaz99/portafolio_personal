<div class="row layout-top-spacing">
	<div class="col-sm-12">
	   <div class="widget widget-chart-one">
		  <div class="widget-heading m-3">
                <h4 class="card-title">
                    <b>{{$componentName}}</b>
                </h4>
            </div>
            <div class="widget-content">
                <div class="form-inline m-3">
                    <div class="form-group mr-5">
                        <select wire:model.lazy="role" class="form-control">
                            <option value="Seleccione" selected>Seleccione el rol </option>
                            @foreach($roles as $role)
                            <option value="{{$role->id}}" selected>{{$role->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <button wire:click="SyncAll()" type="button" class="btn btn-border border btn-outline-primary btn-responsive inline mr-5">Seleccionar todos</button>
                    <button wire:click='revokeAll({{ $role->id }})' type="button" class="btn btn-border border btn-outline-danger btn-responsive mr-5">Revocar todos</button>
                </div>
                <div class="row mt-3">
                    <div class="col-sm-12">
                        <div class="tablet-responsive ">
                            <table class="tablet table-striped table-bordered mt-1 col-lg-12 col-md-12 col-sm-12 col-xs-12 mt-1">
                                <thead class="text-white bg-dark">
                                    <tr class="text-center">
                                        <th>ID</th>
                                        <th>PERMISO</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($permissions as $permission)
                                    <tr>
                                        <td class="text-center text-dark">{{$permission->id}}</td>
                                        <td>
                                            <div class="form-check form-check-inline">
                                                <button style="font-size: 1rem"
                                                    type="button" 
                                                    id="p{{$permission->id}}" 
                                                    class="btn p-1 {{ $permission->checked == 1 ? 'btn-light' : 'btn-outline-light' }} form-check-input"
                                                    wire:click="syncPermission({{ $permission->checked == 1 ? 0 : 1 }}, '{{$permission->name}}')">

                                                    <i class="fa-{{ $permission->checked == 1 ? 'solid' : 'regular' }} fa-square-check"></i> {{$permission->name}}
                                                </button>
                                            </div>
                                            
                                        </td>
                                        
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{$permissions->links()}} 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
 @script
 <script>
	$wire.on('confirmRevoke',function(message){
		 Swal.fire({
		 title: "Estas seguro?",
		 text: "No podrás revertir esto.!",
		 icon: "question",
		 showCancelButton: true,
		 confirmButtonColor: "#3085d6",
		 cancelButtonColor: "#d33",
		 confirmButtonText: "Si, revocar todo!"
		 }).then((result) => {
			 if (result.isConfirmed) {  
				 $wire.call("RemoveAll");  
			 }
		 });
	 });
 </script>
 @endscript