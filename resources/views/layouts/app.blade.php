<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGS — @yield('title', 'Sistema de Gestão de Sistemas')</title>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
</head>
<body>

{{-- Topbar --}}
<nav class="navbar navbar-expand navbar-dark topbar px-3">
    <button class="btn btn-link text-white" id="sidebarToggle" aria-label="Toggle sidebar">
        <i class="fas fa-bars" aria-hidden="true"></i>
    </button>
    <img src="{{ asset('images/logo.png') }}" alt="SGS" class="navbar-logo ms-2">

    <div class="ms-auto">
        <div class="dropdown">
            <button class="btn btn-link text-white dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                {{-- <img src="{{ asset('images/user.png') }}" alt="Avatar" class="user-avatar" aria-hidden="true"> --}}
                {{ auth()->user()->name }}
                <small class="text-muted">({{ auth()->user()->role->label() }})</small>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                        <i class="fas fa-user me-2" aria-hidden="true"></i> Perfil
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="fas fa-sign-out-alt me-2" aria-hidden="true"></i> Sair
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="wrapper d-flex">
    {{-- Sidebar --}}
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand p-3 text-center">
            <img src="{{ asset('images/user.png') }}" alt="Avatar" class="sidebar-logo">
            <small class="text-muted mt-1 sidebar-role">{{ auth()->user()->role->label() }}</small>
        </div>
        <nav class="sidebar-nav">
            <ul class="nav flex-column px-2">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                       href="{{ route('dashboard') }}">
                        <i class="fas fa-tachometer-alt me-2" aria-hidden="true"></i><span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('aplicacoes.*') ? 'active' : '' }}"
                       href="{{ route('aplicacoes.index') }}">
                        <i class="fas fa-server me-2" aria-hidden="true"></i><span>Aplicações</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('historico.*') ? 'active' : '' }}"
                       href="{{ route('historico.index') }}">
                        <i class="fas fa-history me-2" aria-hidden="true"></i><span>Histórico</span>
                    </a>
                </li>
                @if(auth()->user()->isAdmin())
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.usuarios.*') ? 'active' : '' }}"
                       href="{{ route('admin.usuarios.index') }}">
                        <i class="fas fa-users me-2" aria-hidden="true"></i><span>Usuários</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.sistemas-operacionais.*') ? 'active' : '' }}"
                       href="{{ route('admin.sistemas-operacionais.index') }}">
                        <i class="fas fa-desktop me-2" aria-hidden="true"></i><span>Sist. Operacionais</span>
                    </a>
                </li>
                @endif
            </ul>
        </nav>
    </aside>

    {{-- Conteúdo --}}
    <main class="main-content flex-grow-1">
        <div class="content-wrapper">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                </div>
            @endif

            @yield('content')
        </div>

        <footer class="footer">
            <span>SGS &mdash; Sistema de Gestão de Sistemas &bull; v2.0.0</span>
        </footer>
    </main>
</div>

<script>
document.getElementById('sidebarToggle').addEventListener('click', function () {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('collapsed');
    localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
});

if (localStorage.getItem('sidebarCollapsed') === 'true') {
    document.getElementById('sidebar').classList.add('collapsed');
}
</script>

</body>
</html>