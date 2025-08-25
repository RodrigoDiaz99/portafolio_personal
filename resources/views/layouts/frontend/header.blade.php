<!-- Navbar -->
<nav id="navbar" class="navbar-default">
    <a href="{{ url('/') }}" class="logo"><img data-src="{{ asset('storage/' .$user->imagen)}}" alt="Imagen" width="50" class="rounded lazy"> {{$user->name ?? 'Mi Sitio Web'}}</a>
    
    <!-- Botón hamburguesa para móvil -->
    <div class="menu-toggle" id="mobile-menu">
        <span class="bar"></span>
        <span class="bar"></span>
        <span class="bar"></span>
    </div>
    
    <ul class="nav-links mobile-menu">
        <li><a href="/#perfil">Perfil</a></li>
        <li><a href="/#educacion">Educación</a></li>
        <li><a href="/#experiencia">Experiencia</a></li>
        <li><a href="/#habilidades">Habilidades</a></li>
        <li><a href="{{ url('blog') }}">Blog</a></li>
        <li><a href="/#redes">Contacto</a></li>
    </ul>
</nav>
