<div class="container">
    <div class="row">
        <div class="col-xl-4 col-lg-6 col-md-5 col-sm-12 layout-top-spacing">
            <div class="user-profile layout-spacing border-top border-secondary">
                <div class="widget-content widget-content-area">
                    <div class="d-flex justify-content-between">
                        <h3 class="">Perfil</h3>
                        <a href="javascript:void(0)" wire:click="Edit({{$user->id}})" class="mt-3 text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3">
                                <path d="M12 20h9"></path>
                                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                            </svg>
                        </a>
                    </div>
                    <div class="text-center" >
                        <img src="{{ asset('storage/' .$user->imagen)}}" alt="Imagen" width="100" class="img-fluid img-thumbnail">
                        <p class="h5">{{ $user->name }}</p>
                    </div>
                    <div class="user-info-list">
                        <div class="mb-2">
                            <ul class="contacts-block list-unstyled">
                                <li class="contacts-block__item">
                                    <i class="fa-solid fa-user h3"></i>
                                    {{ $user->role }}
                                </li>
                                <li class="contacts-block__item">
                                    <i class="fa-solid fa-user-tie h3"></i>
                                    {{ $user->job_title }}
                                </li>
                                <li class="contacts-block__item">
                                    <i class="fa-solid fa-cake-candles h3"></i>
                                    {{ \Carbon\Carbon::parse($user->birthdate)->format('M d, Y') }}
                                </li>
                                <li class="contacts-block__item">
                                    <i class="fa-solid fa-map-location-dot h3"></i>
                                    {{ $user->address }}
                                </li>
                                <li class="contacts-block__item">
                                    <a href="mailto:example@mail.com">
                                        <i class="fa-solid fa-envelope h3"></i>
                                        {{ $user->email }}
                                    </a>
                                </li>
                                <li class="contacts-block__item">
                                    <i class="fa-solid fa-phone h3"></i>
                                    {{ $user->phone }}
                                </li>
                                
                            </ul>
                            <div class="redes p-0 m-1">
                                @foreach($user->SocialNetwork as $socialNetwork)                       
                               
                                <a class="icon {{strtolower($socialNetwork->name)}}" href="{{$socialNetwork->url}}" target="_BLANK" aria-label="{{strtolower($socialNetwork->name)}}">               
                                <span class="tooltip">{{$socialNetwork->name}}</span>
                                <span><i class="bi bi-{{strtolower($socialNetwork->name)}}"></i></span>
                                </a>
                               
                                @endforeach
                            </div>
                            <div class="text-center mt-5">
                                <button class="btn btn-secondary" wire:click="EditPassd({{$user->id}})">Actualizar contraseña</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('livewire.backend.profile.form')
        </div>
        @include('livewire.backend.profile.passwd')
        <div class="col-xl-8 col-lg-6 col-md-7 col-sm-12 layout-top-spacing">
            <div class="bio layout-spacing border-top border-secondary">
                <div class="widget-content widget-content-area">
                    <h3 class="">Biografía</h3>
                    <p>{{ $user->bio }}</p>
                </div>
            </div>
            @if(count($user->workExperience) > 0)
            <div class="work-experience layout-spacing border-top border-secondary">
                <div class="widget-content widget-content-area">
                    <div class="d-flex justify-content-between">
                        <h3 class="">Educación</h3>
                        <a href="{{ url('admin/profile/educations') }}" class="mt-2 text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3">
                                <path d="M12 20h9"></path>
                                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                            </svg>
                        </a>
                    </div>
                    @foreach($user->Education as $education)
                    <div class="timeline-alter">
                        <div class="item-timeline">
                            <div class="t-meta-date">
                                <p class="">{{\Carbon\Carbon::parse($education->date)->format('Y')}}</p>
                            </div>
                            <div class="t-dot">
                            </div>
                            <div class="t-text">
                                <p><span class="text-uppercase">{{$education->institution}}</span> / {{$education->study_level}}</p>
                                <p><i>{{$education->title_obtained}}</i></p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            @if(count($user->workExperience) > 0)
            <div class="work-experience layout-spacing border-top border-secondary">
                <div class="widget-content widget-content-area">
                    <div class="d-flex justify-content-between">
                        <h3>Experiencia laboral</h3>
                        <a href="{{ url('admin/profile/work-experiences') }}" class="mt-2 text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3">
                                <path d="M12 20h9"></path>
                                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                            </svg>
                        </a>
                    </div>
                    @foreach($user->WorkExperience as $experience)
                    <div class="timeline-alter">
                        <div class="item-timeline">
                            <div class="t-meta-date">
                                <p class="">{{\Carbon\Carbon::parse($experience->from)->format('Y')}} - {{\Carbon\Carbon::parse($experience->to)->format('Y')}}</p>
                            </div>
                            <div class="t-dot">
                            </div>
                            <div class="t-text">
                                <p class="text-uppercase">{{$experience->name}}</p>
                                <p><i>{{$experience->job}}</i></p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            @can('Perfil_Editar') 
            <div class="skills layout-spacing border-top border-secondary">
                <div class="widget-content widget-content-area" wire:ignore>
                    <div class="d-flex justify-content-between">
                        <h3 class="">Habilidades</h3>
                        <a href="{{ url('admin/profile/skills') }}" class="mt-2 text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3">
                                <path d="M12 20h9"></path>
                                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                            </svg>
                        </a>
                    </div>
                    @foreach($user->Skill as $skill)
                    @php
                    $colorClass = '';
                    $randomColors = ['bg-custom-1', 'bg-custom-2', 'bg-custom-3', 'bg-custom-4', 'bg-custom-5', 'bg-custom-6', 'bg-custom-7', 'bg-custom-8', 'bg-custom-9', 'bg-custom-10']; // Colores aleatorios
                    // Selecciona un color aleatorio para cada habilidad
                    $colorClass = $randomColors[array_rand($randomColors)];
                    @endphp
                    <p>{{$skill->ability}}</p>
                    <div class="progress br-30">
                        <div class="progress-bar {{$colorClass}}" style="width: {{$skill->level}}%" role="progressbar"  aria-valuenow="{{$skill->level}}" aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-title"><span>{{ $skill->level }}%</span> </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endcan
        </div>
    </div>
</div>
@include('livewire.events')
@script
<script>
    window.Livewire.on('updated-user-close', () => {
        Swal.fire({
            position: "top-end",
            icon: "success",
            title: "¡Actualización exitosa!",
            showConfirmButton: false,
            timer: 1500
        });
        $('#theModal').modal('hide');
        $wire.call("resetUI"); 
        
    });

    window.Livewire.on('updated-user', () => {
        Swal.fire({
            position: "top-end",
            icon: "success",
            title: "¡Actualización exitosa!",
            showConfirmButton: false,
            timer: 1500
        });
        
    });
   $wire.on('confirmDelete',function(message){
        Swal.fire({
        title: "Estas seguro?",
        text: "No podrás revertir esto.!",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, eliminar!"
        }).then((result) => {
            if (result.isConfirmed) {  
                $wire.call("Destroy");  
            }
        });
    });
</script>
@endscript