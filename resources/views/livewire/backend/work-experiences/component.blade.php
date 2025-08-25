<div class="row layout-top-spacing">
    <div class="col-sm-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading m-3">
                <h4 class="card-title">
                    <b>{{$componentName}}</b> <i>/ {{$pageTitle}}</i>
                </h4>
                <a href="javascript:void(0)" class="btn btn-lg btn-border border btn-link btn-responsive" data-toggle="modal" data-target="#theModal">NUEVO</a>
            </div>
            <div class="m-3">
                @include('common.searchbox')
            </div>
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
                <div class="tablet-responsive">
                    <span class="text-center m-3"><i>{{$showing}}</i></span>
                    <table class="tablet table-striped table-bordered mt-1 col-lg-12 col-md-12 col-sm-12 col-xs-12 mt-1">
                        <thead class="text-white bg-dark">
                            <tr class="text-center">
                                <th class="tablet-th text-white">EMPRESA</th>
                                <th class="tablet-th text-white">PUESTO</th>
                                <th class="tablet-th text-white">TIEMPO</th>
                                <th>ESTADO</th> 
                                <th class="tablet-th text-white">ACCION</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($experiences as $experience)
                            <tr class="text-center text-dark">
                                <td>{{$experience->name}}</td>
                                <td>{{$experience->job}}</td>
                                <td>
                                    {{\Carbon\Carbon::parse($experience->from)->format('d-m-Y')}} al {{\Carbon\Carbon::parse($experience->to)->format('d-m-Y')}}
                                </td>
                                <td>
                                    <span class="w-100 {{$showDeleted ? 'text-danger' : 'text-success'}} uppercase">{{$showDeleted ? 'Eliminado' : 'Activo'}}</span>
                                    
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
                                                        <a class="dropdown-item" href="javascript:void(0)"
                                                            wire:click="restoreRow('{{ $experience->id }}')">
                                                            <i class="bi bi-arrow-counterclockwise"></i> Restaurar
                                                        </a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item text-danger" href="javascript:void(0)"
                                                            wire:click="deleteRowPerm('{{ $experience->id }}')">
                                                            <i class="bi bi-trash"></i> Eliminar Completo
                                                        </a>
                                                    @else
                                                        <a class="dropdown-item" href="javascript:void(0)"
                                                            wire:click="Edit({{ $experience->id }})">
                                                            <i class="bi bi-pencil"></i> Editar
                                                        </a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item text-danger" href="javascript:void(0)"
                                                            wire:click="deleteRow('{{ $experience->id }}')">
                                                            <i class="bi bi-trash"></i> Eliminar
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{$experiences->links()}} 
                </div>
            </div>
        </div>
    </div>
    @include('livewire.backend.work-experiences.form')
</div>
@include('livewire.events')