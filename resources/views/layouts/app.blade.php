<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGS — @yield('title', 'Sistema de Gestão de Sistemas')</title>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>
<body>

{{-- Topbar --}}
<nav class="navbar navbar-expand navbar-dark topbar px-3">
    <button class="btn btn-link text-white" id="sidebarToggle" aria-label="Toggle sidebar">
        <i class="fas fa-bars" aria-hidden="true"></i>
    </button>
    <span class="navbar-brand ms-2">SGS</span>

    <div class="ms-auto">
        <div class="dropdown">
            <button class="btn btn-link text-white dropdown-toggle" data-bs-toggle="dropdown">
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
            <strong>SGS</strong><br>
            <small class="text-muted">{{ auth()->user()->role->label() }}</small>
        </div>
        <nav class="sidebar-nav">
            <ul class="nav flex-column px-2">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                       href="{{ route('dashboard') }}">
                        <i class="fas fa-tachometer-alt me-2" aria-hidden="true"></i> Dashboard
                    </a>
                </li>
                @if(auth()->user()->isAdmin())
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.usuarios.*') ? 'active' : '' }}"
                       href="{{ route('admin.usuarios.index') }}">
                        <i class="fas fa-users me-2" aria-hidden="true"></i> Usuários
                    </a>
                </li>
                @endif
            </ul>
        </nav>
    </aside>

    {{-- Conteúdo --}}
    <main class="main-content flex-grow-1 p-4">
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
    </main>
</div>

<script>
document.getElementById('sidebarToggle').addEventListener('click', function () {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('collapsed');
    localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
});

// Restaurar estado
if (localStorage.getItem('sidebarCollapsed') === 'true') {
    document.getElementById('sidebar').classList.add('collapsed');
}
</script>

</body>
</html>