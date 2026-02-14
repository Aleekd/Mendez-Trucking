<div class="sidebar-overlay" id="sidebarOverlay"></div>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="sidebar-brand-icon">
            <i class="bi bi-truck"></i>
        </div>
        <div class="sidebar-brand-text">
            <h6>Méndez Trucking</h6>
            <small>Taller Mecánico</small>
        </div>
    </div>

    @php
        $countPrestamos = 0;
        if(isset($prestamosActivos)) {
            // Si es un número (viene del Dashboard)
            if(is_numeric($prestamosActivos)) {
                $countPrestamos = $prestamosActivos;
            } 
            // Si es una colección o lista (viene de Préstamos)
            elseif(is_countable($prestamosActivos)) {
                $countPrestamos = count($prestamosActivos);
            }
        }
        
        $countAlertas = 0;
        if(isset($alertas)) {
            if(is_numeric($alertas)) {
                $countAlertas = $alertas;
            } elseif(is_countable($alertas)) {
                $countAlertas = count($alertas);
            }
        }
    @endphp

    <nav class="sidebar-nav">
        <div class="sidebar-section-label">Principal</div>

        <a href="{{ route('dashboard') }}" class="sidebar-link active">
            <i class="bi bi-grid-1x2-fill"></i> Dashboard
        </a>
        <a href="{{ route('inventario.index') }}" class="sidebar-link">
            <i class="bi bi-box-seam-fill"></i> Inventario
        </a>
        <a href="{{ route('prestamos') }}" class="sidebar-link">
            <i class="bi bi-arrow-left-right"></i> Préstamos
            @if($countPrestamos > 0)
                <span class="badge bg-primary rounded-pill">{{ $countPrestamos }}</span>
            @endif
        </a>
        <a href="{{ route('buscar.index') }}" class="sidebar-link">
            <i class="bi bi-search"></i> Buscar
        </a>

        <div class="sidebar-section-label" style="margin-top: 0.5rem;">Administración</div>

        <a href="{{ route('usuarios.index') }}" class="sidebar-link">
            <i class="bi bi-people-fill"></i> Usuarios
        </a>
        <a href="{{ route('historial.index') }}" class="sidebar-link">
            <i class="bi bi-clock-history"></i> Historial
        </a>
        <a href="{{ route('alertas.index') }}" class="sidebar-link">
            <i class="bi bi-bell-fill"></i> Alertas
            @if($countAlertas > 0)
                <span class="badge bg-danger rounded-pill">{{ $countAlertas }}</span>
            @endif
        </a>
        <a href="{{ route('configuracion') }}" class="sidebar-link">
            <i class="bi bi-gear-fill"></i> Configuración
        </a>
    </nav>

    <div class="sidebar-footer">
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="bi bi-box-arrow-left"></i> Cerrar Sesión
            </button>
        </form>
    </div>
</aside>