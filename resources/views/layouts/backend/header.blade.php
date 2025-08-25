

<!--  BEGIN NAVBAR  -->
<div class="header-container fixed-top">
  <header class="header navbar navbar-expand-sm">
      <ul class="navbar-item theme-brand flex-row  text-center">
          <li class="nav-item theme-logo">          
                <a href="index.html" class="nav-link text-white"> BLOG MINIMAL </a>           
          </li>
          <li class="nav-item theme-text">           
          </li>
      </ul>
      <ul class="navbar-item flex-row ml-md-auto">
        <li class="nav-item dropdown user-profile-dropdown">
            <a href="javascript:void(0);" class="nav-link dropdown-toggle user" id="userProfileDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                <div class="avatar-icon bg-dark text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                    <i class="bi bi-person-circle"></i>
                </div>
            </a>
            <div class="dropdown-menu position-absolute" aria-labelledby="userProfileDropdown">
                <div class="dropdown-item">
                    <a class="" href="{{ url('admin/profile') }}"><i class="bi bi-person-circle"></i> Perfil</a>
                </div>
                <div class="dropdown-item">
                    <a class="" href="{{ url('admin/logout') }}"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</a>
                </div>
            </div>
        </li>
    </ul>

  </header>
</div>
<!--  END NAVBAR  -->

<!--  BEGIN NAVBAR  -->
<div class="sub-header-container mt-2">
  <header class="header navbar navbar-expand-sm">
      <a href="javascript:void(0);" class="sidebarCollapse" data-placement="bottom"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg></a>

      <ul class="navbar-nav flex-row">
        <li>
            <div class="page-header">
                <nav class="breadcrumb-one" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        @foreach($breadcrumbs as $key => $breadcrumb)
                            <li class="breadcrumb-item {{ $key === array_key_last($breadcrumbs) ? 'active' : '' }}">
                                @if($key === array_key_last($breadcrumbs))
                                    <span>{{ $breadcrumb }}</span>
                                @else
                                    <a href="{{ url('admin/dashboard') }}">{{ $breadcrumb }}</a>
                                @endif
                            </li>
                        @endforeach
                    </ol>
                </nav>
            </div>
        </li>
    </ul>
    
    
      <ul class="navbar-nav flex-row ml-auto ">
          <li class="nav-item more-dropdown">
              <div class="dropdown  custom-dropdown-icon">
                  <a class="dropdown-toggle btn" href="javascript:void(0)" role="button" id="customDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span>Ajustes</span> <i class="bi bi-caret-down-fill"></i></a>

                  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="customDropdown">
                      <a class="dropdown-item" data-value="Ajustes" href="javascript:void(0);">Ajustes</a>
                      
                  </div>
              </div>
          </li>
      </ul>
  </header>
</div>
<!--  END NAVBAR  -->