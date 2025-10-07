<nav
class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
id="layout-navbar"
>
<div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
    <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
    <i class="bx bx-menu bx-sm"></i>
    </a>
</div>

<div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
    <!-- Search -->
    <div class="navbar-nav align-items-center">
      <div class="nav-item d-flex align-items-center">
          <i class="bx bx-search fs-4 lh-0"></i>
          <input
          type="text"
          class="form-control border-0 shadow-none"
          placeholder="Search..."
          />
      </div>
    </div>
    <!-- /Search -->

    <ul class="navbar-nav flex-row align-items-center ms-auto">
      <ul class="navbar-nav flex-row align-items-center ms-md-auto">
      <!-- Language -->
      <li class="nav-item dropdown-language dropdown me-2 me-xl-0">
        <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="icon-base bx bx-globe icon-md"></i>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li>
            <a onclick="setLang('id')" class="dropdown-item {{ app()->getLocale() === 'id' ? 'active' : '' }}" href="{{ route('cms.language', ['locale' =>'id']) }}" data-language="fr" data-text-direction="ltr">
              <span>Indonesia</span>
            </a>
          </li>
          <li>
            <a onclick="setLang('en')" class="dropdown-item {{ app()->getLocale() === 'en' ? 'active' : '' }}" href="{{ route('cms.language', ['locale' => 'en']) }}" data-language="en" data-text-direction="ltr">
              <span>English</span>
            </a>
          </li>
        </ul>
      </li>
      <!--/ Language -->

      
        <!-- Style Switcher -->
         <li class="nav-item dropdown me-2 me-xl-0">
          <a class="nav-link dropdown-toggle hide-arrow" id="nav-theme" href="javascript:void(0);" data-bs-toggle="dropdown">
            <i class="icon-base bx bx-sun icon-md theme-icon-active"></i>
            <span class="d-none ms-2" id="nav-theme-text">Toggle theme</span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="nav-theme-text">
            <li>
              <button type="button" class="dropdown-item align-items-center active" data-bs-theme-value="light" aria-pressed="false">
                <span><i class="icon-base bx bx-sun icon-md me-3" id="lightIcon" data-icon="sun"></i>Light</span>
              </button>
            </li>
            <li>
              <button type="button" class="dropdown-item align-items-center" data-bs-theme-value="dark" aria-pressed="true">
                <span><i class="icon-base bx bx-moon icon-md me-3" id="darkIcon" data-icon="moon"></i>Dark</span>
              </button>
            </li>
          </ul>
        </li>
        <!-- / Style Switcher-->

      <!-- Notification -->
      <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-2">
        <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
          <span class="position-relative">
            <i class="icon-base bx bx-bell icon-md"></i>
            <span class="badge rounded-pill bg-danger badge-dot badge-notifications border"></span>
          </span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end p-0">
          <li class="dropdown-menu-header border-bottom">
            <div class="dropdown-header d-flex align-items-center py-3">
              <h6 class="mb-0 me-auto">Notification</h6>
              <div class="d-flex align-items-center h6 mb-0">
                <span class="badge bg-label-primary me-2">8 New</span>
              </div>
            </div>
          </li>
          
          <li class="border-top">
            <div class="d-grid p-4">
              <a class="btn btn-primary btn-sm d-flex" href="javascript:void(0);">
                <small class="align-middle">View all notifications</small>
              </a>
            </div>
          </li>
        </ul>
      </li>
      <!--/ Notification -->
  </ul>
    <!-- User -->
    <li class="nav-item navbar-dropdown dropdown-user dropdown">
        <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
        <div class="avatar avatar-online">
            <img src="https://ui-avatars.com/api/?background=2563eb&name={{ auth()->user()->name }}&color=fff" alt class="w-px-40 h-auto rounded-circle" />
        </div>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
        <li>
            <a class="dropdown-item" href="#">
            <div class="d-flex">
                <div class="flex-shrink-0 me-3">
                <div class="avatar avatar-online">
                    <img src="https://ui-avatars.com/api/?background=2563eb&name={{ auth()->user()->name }}&color=fff" alt class="w-px-40 h-auto rounded-circle" />
                </div>
                </div>
                <div class="flex-grow-1">
                <span class="fw-semibold d-block">{{ session('user_data.name') }}</span>
                <small>{{ session('user_data.role') }}</small>
                </div>
            </div>
            </a>
        </li>
        <li>
            <div class="dropdown-divider"></div>
        </li>
        <li>
            <a class="dropdown-item" href="#">
            <i class="bx bx-user me-2"></i>
            <span class="align-middle">My Profile</span>
            </a>
        </li>
        <li>
            <div class="dropdown-divider"></div>
        </li>
        <li>
            <a class="dropdown-item" href="javascript:void()" onclick="logOut()">
            <i class="bx bx-power-off me-2"></i>
            <span class="align-middle">Log Out</span>
            </a>
        </li>
        </ul>
    </li>
    <!--/ User -->
    </ul>
</div>
</nav>