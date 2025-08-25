<div>
 <!-- Header -->
    <header>
        <div class="header-bg"></div>
        <div class="header-content">
            <h1 id="main-title">{{$user->name ?? 'Mi Sitio Web'}}</h1>
            <p class="tagline" id="tagline">{{$user->job_title ?? 'Eslogan'}}</p>
            <a href="#perfil" class="btn-style" id="cta-button">Conóceme</a>
        </div>
        <a href="#perfil" class="scroll-down"><i class="fas fa-chevron-down"></i></a>
    </header>

    <!-- Perfil -->
    <section id="perfil" class="section">
        <div class="container">
            <h2>Mi Perfil</h2>
            <div class="profile">
                <div class="profile-img">
                    <img data-src="{{ asset('storage/' .$user->imagen)}}" alt="{{$user->name ?? 'Mi Sitio Web'}}" class="lazy">
                </div>
                <div class="profile-text">
                    <h3>¡Hola! Soy {{$user->name}}</h3>
                    @foreach(preg_split("/\r\n|\n|\r/", trim($user->bio)) as $line)
                        @if(trim($line) !== '')
                            <p>{{ $line }}</p>
                        @endif
                    @endforeach
                    <a href="#experiencia" class="btn-style">Ver mi experiencia</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Educación -->
    <section id="educacion" class="section">
        <div class="container">
            <h2>Educación</h2>
            <div class="timeline mt-5">
                @foreach($user->Education as $education)
                <div class="timeline-item">
                    <div class="timeline-content">
                        <h3><i class="fa-solid fa-graduation-cap"></i> {{$education->title_obtained}}</h3>
                        <p><i class="fa-solid fa-building-columns"></i> {{$education->institution}}</p>
                        <p><i class="fa-solid fa-file"></i> {{$education->description}}</p>
                    </div>
                    <div class="timeline-date"><i class="fa-solid fa-calendar-check"></i> {{ \Carbon\Carbon::parse($education->date)->year }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Experiencia Laboral -->
    <section id="experiencia" class="section">
        <div class="container">
            <h2>Experiencia Laboral</h2>
            <div class="experience-grid mt-5">
                @foreach($user->WorkExperience as $workExperience)
                <div class="experience-card">
                    <div class="exp-header">
                        <h3>{{$workExperience->job}}</h3>
                        <p>{{$workExperience->name}} | {{ \Carbon\Carbon::parse($workExperience->from)->year }} - {{ \Carbon\Carbon::parse($workExperience->to)->year }}</p>
                    </div>
                    <div class="exp-body">
                        @foreach(preg_split("/\r\n|\n|\r/", trim($workExperience->description)) as $line)
                            @if(trim($line) !== '')
                                <p>{{ $line }}</p>
                            @endif
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Habilidades -->
    <section id="habilidades" class="section">
        <div class="container">
            <h2>Habilidades</h2>
            
            <div class="skills-container mt-5">
                @foreach($user->Skill->groupBy('category') as $category => $skills)
                    <div class="skill-category mb-4">
                        <h3>{{ $category }}</h3>
                        
                        @foreach($skills as $skill)
                            @php
                            $colorClass = '';
                            $randomColors = ['bg-custom-1', 'bg-custom-2', 'bg-custom-3', 'bg-custom-4', 'bg-custom-5', 'bg-custom-6', 'bg-custom-7', 'bg-custom-8', 'bg-custom-9', 'bg-custom-10']; // Colores aleatorios
                            // Selecciona un color aleatorio para cada habilidad
                            $colorClass = $randomColors[array_rand($randomColors)];
                            @endphp
                            
                            <div class="skill-item mb-2">
                                <div class="skill-info d-flex justify-content-between">
                                    <span>{{ $skill->ability }}</span>
                                </div>
                                <div class="progress br-30">
                                    <div class="progress-bar {{$colorClass}}" style="width: {{$skill->level}}%" role="progressbar"  aria-valuenow="{{$skill->level}}" aria-valuemin="0" aria-valuemax="100">
                                        <div class="progress-title"><span>{{ $skill->level }}%</span> </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Redes Sociales -->
    <section id="redes" class="section social-section">
        <div class="container">
            <h2>Conéctate Conmigo</h2>
            <p class="mt-5">Estoy siempre abierto a nuevas oportunidades y colaboraciones. ¡No dudes en contactarme!</p>
            
            <div class="social-links">
                @foreach($user->SocialNetwork as $socialNetwork)
                    <a href="{{$socialNetwork->url}}" class="social-link {{strtolower($socialNetwork->name)}}">
                        <div class="social-icon">
                            <i class="fa-brands fa-{{strtolower($socialNetwork->name)}}"></i>
                        </div>
                        <span>{{$socialNetwork->name}}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

</div>