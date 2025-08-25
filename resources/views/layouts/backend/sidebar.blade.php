<!--  BEGIN SIDEBAR  -->
<div class="sidebar-wrapper sidebar-theme border-right">
            
  <nav id="sidebar">
      <div class="shadow-bottom"></div>
      <ul class="list-unstyled menu-categories" id="accordionExample">
        <li class="menu">
            <a href="#dashboard" 
               data-active="{{ Request::is('admin/dashboard') ? 'true' : 'false' }}" 
               data-toggle="collapse" 
               aria-expanded="{{ Request::is('admin/dashboard') ? 'true' : 'false' }}" 
               class="dropdown-toggle {{ Request::is('admin/dashboard') ? 'bg-white text-dark' : '' }}">
                
                <div>
                    <i class="fa-solid fa-chart-line"></i>
                    <span>Dashboard</span>
                </div>
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </div>
            </a>
            
            <ul class="collapse submenu list-unstyled {{ Request::is('admin/dashboard') ? 'show' : '' }}" id="dashboard" data-parent="#accordionExample">
                <li class="{{ Request::is('admin/dashboard') ? 'active' : '' }}">
                    <a href="{{ url('admin/dashboard') }}" class="{{ Request::is('admin/dashboard') ? 'text-secondary' : 'text-dark' }}"> Analítica </a>
                </li>
            </ul>
        </li>
        
        @can('Perfil_Index')
        <li class="menu">
            <a href="#profiles" 
               data-toggle="collapse" 
               aria-expanded="{{ Request::is('admin/profile') || Request::is('admin/profile/educations') || Request::is('admin/profile/social-networks') || Request::is('admin/profile/work-experiences') || Request::is('admin/profile/skills') ? 'true' : 'false' }}" 
               class="dropdown-toggle {{ Request::is('admin/profile') || Request::is('admin/profile/educations') || Request::is('admin/profile/social-networks') || Request::is('admin/profile/work-experiences') || Request::is('admin/profile/skills') ? 'bg-white text-dark' : '' }}">
               
                <div>
                    <i class="fa-regular fa-user"></i>
                    <span>Perfil</span>
                </div>
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </div>
            </a>
            
            <ul class="collapse submenu list-unstyled {{ Request::is('admin/profile') || Request::is('admin/profile/educations') || Request::is('admin/profile/social-networks') || Request::is('admin/profile/work-experiences') || Request::is('admin/profile/skills') ? 'show' : '' }}" id="profiles" data-parent="#accordionExample">
                
                <li class="{{ Request::is('admin/profile') ? 'active' : '' }}">
                    <a href="{{ url('admin/profile') }}" class="{{ Request::is('admin/profile') ? 'text-secondary' : 'text-dark' }}">Mi Perfil</a>
                </li>
                @can('Perfil_Editar')
                <li class="{{ Request::is('admin/profile/educations') ? 'active' : '' }}">
                    <a href="{{ url('admin/profile/educations') }}" class="{{ Request::is('admin/profile/educations') ? 'text-secondary' : 'text-dark' }}">Educación </a>
                </li>
                <li class="{{ Request::is('admin/profile/social-networks') ? 'active' : '' }}">
                    <a href="{{ url('admin/profile/social-networks') }}" class="{{ Request::is('admin/profile/social-networks') ? 'text-secondary' : 'text-dark' }}">Redes Sociales</a>
                </li>
                <li class="{{ Request::is('admin/profile/work-experiences') ? 'active' : '' }}">
                    <a href="{{ url('admin/profile/work-experiences') }}" class="{{ Request::is('admin/profile/work-experiences') ? 'text-secondary' : 'text-dark' }}">Experiencia laboral</a>
                </li>
                <li class="{{ Request::is('admin/profile/skills') ? 'active' : '' }}">
                    <a href="{{ url('admin/profile/skills') }}" class="{{ Request::is('admin/profile/skills') ? 'text-secondary' : 'text-dark' }}">Habilidades</a>
                </li>
                @endcan
            </ul>
        </li>
        @endcan
        @hasanyrole('Super Admin|Admin|Editor')
        
        <li class="menu">
            <a href="#blog" 
               data-toggle="collapse" 
               aria-expanded="{{ Request::is('admin/blog/posts') || Request::is('admin/blog/categories') || Request::is('admin/blog/tags') || Request::is('admin/blog/comments') ? 'true' : 'false' }}" 
               class="dropdown-toggle {{ Request::is('admin/blog/posts') || Request::is('admin/blog/categories') || Request::is('admin/blog/tags') || Request::is('admin/blog/comments') ? 'bg-white text-dark' : '' }}">
               
                <div>
                    <i class="fa-solid fa-rss"></i>
                    <span>Blog</span>
                </div>
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </div>
            </a>
            
            <ul class="collapse submenu list-unstyled {{ Request::is('admin/blog/posts') || Request::is('admin/blog/categories') || Request::is('admin/blog/tags') || Request::is('admin/blog/comments') ? 'show' : '' }}" id="blog" data-parent="#accordionExample">
                
                <li class="{{ Request::is('admin/blog/categories') ? 'active' : '' }}">
                    <a href="{{ url('admin/blog/categories') }}" class="{{ Request::is('admin/blog/categories') ? 'text-secondary' : 'text-dark' }}">Categorias</a>
                </li>   
                <li class="{{ Request::is('admin/blog/tags') ? 'active' : '' }}">
                    <a href="{{ url('admin/blog/tags') }}" class="{{ Request::is('admin/blog/tags') ? 'text-secondary' : 'text-dark' }}">Etiquetas</a>
                </li> 
                <li class="{{ Request::is('admin/blog/posts') ? 'active' : '' }}">
                    <a href="{{ url('admin/blog/posts') }}" class="{{ Request::is('admin/blog/posts') ? 'text-secondary' : 'text-dark' }}">Publicaciones</a>
                </li> 
                <li class="{{ Request::is('admin/blog/comments') ? 'active' : '' }}">
                    <a href="{{ url('admin/blog/comments') }}" class="{{ Request::is('admin/blog/comments') ? 'text-secondary' : 'text-dark' }}">Comentarios</a>
                </li>                    
            </ul>
        </li>

        @endhasanyrole
        @hasanyrole('Super Admin|Admin')
  
        <li class="menu">
            <a href="#users" 
               data-toggle="collapse" 
               aria-expanded="{{ Request::is('admin/users/roles') || Request::is('admin/users') || Request::is('admin/users/permissions') || Request::is('admin/users/assign-permissions') ? 'true' : 'false' }}" 
               class="dropdown-toggle {{ Request::is('admin/users/roles') || Request::is('admin/users') || Request::is('users/auth/permissions') || Request::is('admin/users/assign-permissions') ? 'bg-white text-dark' : '' }}">
                <div class="text-sidebar">
                    <i class="fa-solid fa-users"></i>
                    <span>Autenticación</span>
                </div>
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </div>
            </a>
            <ul class="collapse submenu list-unstyled {{ Request::is('admin/users/roles') || Request::is('admin/users') || Request::is('admin/users/permissions') || Request::is('admin/users/assign-permissions') ? 'show' : '' }}" id="users" data-parent="#accordionExample"> 
                <li class="{{ Request::is('admin/users') ? 'active' : '' }}">
                    <a href="{{ url('admin/users') }}" class="{{ Request::is('admin/users') ? 'text-secondary' : 'text-dark' }}">Usuarios</a>
                </li>  
                @role('Super Admin')
                <li class="{{ Request::is('admin/users/roles') ? 'active' : '' }}">
                    <a href="{{ url('admin/users/roles') }}" class="{{ Request::is('admin/users/roles') ? 'text-secondary' : 'text-dark' }}">Roles</a>
                </li>    
                <li class="{{ Request::is('admin/users/permissions') ? 'active' : '' }}">
                    <a href="{{ url('admin/users/permissions') }}" class="{{ Request::is('admin/users/permissions') ? 'text-secondary' : 'text-dark' }}">Permisos</a>
                </li>  
                <li class="{{ Request::is('admin/users/assign-permissions') ? 'active' : '' }}">
                    <a href="{{ url('admin/users/assign-permissions') }}" class="{{ Request::is('admin/users/assign-permissions') ? 'text-secondary' : 'text-dark' }}">Asignar permisos</a>
                </li>      
                @endrole                  
            </ul>
        </li>
        @endcan



           
      </ul>
      
  </nav>

</div>
<!--  END SIDEBAR  -->