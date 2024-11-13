@php
    use App\Services\MenuService;
@endphp

<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <!--<div class="navbar-brand-box">

        <a href="index.html" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ env('API_URL') . '/api/images/logo/' . Session::get('logo_url') }}" alt="" height="100">
            </span>
            <span class="logo-lg">
                <img src="{{ env('API_URL') . '/api/images/logo/' . Session::get('logo_url') }}" alt="" height="100">
            </span>
        </a>

        <a href="index.html" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ env('API_URL') . '/api/images/logo/' . Session::get('logo_url') }}" alt="" height="100">
            </span>
            <span class="logo-lg">
                <img src="{{ env('API_URL') . '/api/images/logo/' . Session::get('logo_url') }}" alt="" height="100">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>
    -- >


    <!--
    <div class="dropdown sidebar-user m-1 rounded">
        <button type="button" class="btn material-shadow-none" id="page-header-user-dropdown" data-bs-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            <span class="d-flex align-items-center gap-2">
                <img class="rounded header-profile-user" src="{{ asset('assets/images/users/avatar-1.jpg') }}"
                    alt="Header Avatar">
                <span class="text-start">
                    <span
                        class="d-block fw-medium sidebar-user-name-text">{{ ucwords(Session::get('user.name')) }}</span>
                    <span class="d-block fs-14 sidebar-user-name-sub-text"><i
                            class="ri ri-circle-fill fs-10 text-success align-baseline"></i> <span
                            class="align-middle">Online</span></span>
                </span>
            </span>
        </button>
    </div>
    -->


    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu"></div>
            <ul class="navbar-nav" id="navbar-nav">


                <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                 <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('user') }}">
                        <i class="ri-user-line"></i> <span data-key="t-dashboards">User</span>
                    </a>
                 </li>
                 <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('company') }}">
                        <i class="ri-building-line"></i> <span data-key="t-dashboards">Company</span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>


    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
