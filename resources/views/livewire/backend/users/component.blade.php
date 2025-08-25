<div class="row layout-top-spacing">
	<div class="col-sm-12">
	   <div class="widget widget-chart-one">
		  <div class="widget-heading m-3">
 				<h4 class="card-title">
 					<b>{{$componentName}}</b> <i>/ {{$pageTitle}}</i>
 				</h4>
				@can('Usuario_Crear')
					<a href="javascript:void(0)" class="btn btn-lg btn-border border btn-link btn-responsive" data-toggle="modal" data-target="#theModal" onclick="limpiarImagen()">NUEVO</a>
				@endcan
 			</div>
			@can('Usuario_Buscar')
				<div class="m-3">
					@include('common.searchbox')
				</div>
			@endcan
			<div class="float-right">                      
				<div class="d-flex justify-content-end bg-light p-2">
					<button 
						class="btn p-1 {{ $showDeleted ? 'btn-outline-success' : 'btn-outline-danger' }}" 
						wire:click="toggleShowDeleted">
						{{ $showDeleted ? 'Mostrar Activos' : 'Mostrar Eliminados' }}
					</button>
				</div>
			</div>

 			<div class="widget-content">
 				<div class="tablet-responsive ">
					<span class="text-center m-3"><i>{{$showing}}</i></span>
 					<table class="tablet table-striped table-bordered mt-1 col-lg-12 col-md-12 col-sm-12 col-xs-12 mt-1">
 						<thead class="text-white bg-dark">
 							<tr class="text-center">
                				<th>IMAGEN</th>
 								<th>USUARIO</th>
 								<th>TELEFONO</th>
 								<th>CORREO</th>
 								<th>ROL</th>
 								<th>CUENTA</th>		
								 <th>ESTADO</th>								
 								<th>ACCIONS</th>
 							</tr>
 						</thead>
 						<tbody>
 							@foreach($users as $user)
							 @if (auth()->user()->role === 'Super Admin' || $user->role !== 'Super Admin')
								<tr class="text-center text-dark">
									<td>
										  <img src="{{ asset('storage/' .$user->imagen)}}" alt="Imagen" width="50" class="rounded">
									  </td>
									 <td>{{$user->name}}</td>
									 <td>{{$user->phone}}</td>
									 <td>{{$user->email}}</td>
									 <td>{{$user->role}}</td>
									 <td>
										<span class="w-75 badge {{$user->account_state  == 'Active' ? 'badge-success' : 'badge-danger'}} uppercase">{{$user->account_state == 'Active' ? 'Activo' : 'Inactivo'}}</span>
									</td>
									 <td>
										<span class="w-75 badge {{$showDeleted ? 'badge-danger' : 'badge-success'}} uppercase">{{$showDeleted ? 'Eliminado' : 'Activo'}}</span>
										
									</td> 
									
							

									<td style="padding: 0;" class="w-25">
                                        <div class="dropdown custom-dropdown" style="width: 100%; height: 100%;">
                                            <button class="btn btn-light dropdown-toggle w-100 text-truncate"
                                                type="button" id="dropdownMenuButton" data-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                                Opciones <i class="fas fa-caret-down ml-1"></i>
                                            </button>
                                            <div class="dropdown-menu w-100 animated fadeIn" aria-labelledby="dropdownMenuButton" style="box-shadow: 0 5px 15px rgba(0,0,0,0.1); border: none; border-radius: 8px;">
                                                <div class="select-dropdown">
                                                    @if ($showDeleted)
														@can('Usuario_Restaurar') 
															<a class="dropdown-item" href="javascript:void(0)"
																wire:click="restoreRow('{{ $user->id }}')">
																<i class="bi bi-arrow-counterclockwise"></i> Restaurar
															</a>
														@endcan 
															<div class="dropdown-divider"></div>
														@can('Usuario_Eliminar_Permanente')
															<a class="dropdown-item text-danger" href="javascript:void(0)"
																wire:click="deleteRowPerm('{{ $user->id }}')">
																<i class="bi bi-trash"></i> Eliminar Completo
															</a>
														@endcan 
                                                    @else
														@can('Usuario_Editar')
															<a class="dropdown-item" href="javascript:void(0)"
																wire:click="Edit({{ $user->id }})">
																<i class="bi bi-pencil"></i> Editar
															</a>
														@endcan 
                                                        <div class="dropdown-divider"></div>
														@can('Usuario_Eliminar')
															<a class="dropdown-item text-danger" href="javascript:void(0)"
																wire:click="deleteRow('{{ $user->id }}')">
																<i class="bi bi-trash"></i> Eliminar
															</a>
														@endcan 
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
								 </tr>
								@endif
 							@endforeach
 						</tbody>
 					</table>
 					{{$users->links()}} 
 				</div>
 			</div>
 		</div>
		 @include('livewire.backend.users.form')
 	</div>
 	@include('livewire.backend.users.passwd')


 </div>
 <script type="text/javascript">
    document.addEventListener('livewire:init', () => {
         $(document).ready(function () {    
             $('#select2-dropdown').select2({dropdownParent: $('#roles')}); 
                 $('#select2-dropdown').on('change', function (e) {
                     var roleid = $('#select2-dropdown').select2("val");
                     var rolename = $('#select2-dropdown option:selected').text();
                     @this.set('roleid', roleid);
                     @this.set('rolename', rolename);
                 });
                 
                 window.Livewire.on('reset-role', () => {
					setTimeout(function() {
						$('#select2-dropdown').val('Seleccione').trigger('change');
					}, 100); // Ajusta el tiempo si es necesario
				});

     
         });

		 window.Livewire.on('show-modal-user', () => {
                
				// Mostrar el modal
				$('#theModal').modal('show');

				// Inicializar Select2 con el contenedor del modal
				$('#select2-dropdown').select2({
					dropdownParent: $('#roles')
				});

				// Establecer el valor actual del campo desde Livewire
				setTimeout(() => {
					$('#select2-dropdown').val(@this.get('roleid')).trigger('change'); // Sincroniza el valor
				}, 100); // Pequeño retraso para asegurarse de que el DOM esté listo
			});

			window.Livewire.on('reset-role', () => {
				setTimeout(function() {
					$('#select2-dropdown').val('Seleccione').trigger('change');
				}, 100); // Ajusta el tiempo si es necesario
			});
			window.Livewire.on('reset-imagen', () => {
				limpiarImagen()
			});

	});
	
</script>
@include('livewire.events')