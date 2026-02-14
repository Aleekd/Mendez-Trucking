@extends('layouts.app')

@section('title', 'Méndez Trucking — Dashboard')

@push('styles')
<style>
    :root {
        --bg-primary: #0f1923;
        --bg-secondary: #1a2332;
        --bg-card: #1e2d3d;
        --bg-input: #152231;
        --border-color: #2a3f55;
        --border-focus: #3b82f6;
        --text-primary: #e8edf2;
        --text-secondary: #8899aa;
        --text-muted: #5a6f82;
        --accent-blue: #3b82f6;
        --accent-blue-hover: #2563eb;
        --accent-orange: #f97316;
        --accent-orange-hover: #ea580c;
        --danger: #ef4444;
        --success: #22c55e;
        --warning: #eab308;
        --sidebar-width: 260px;
    }

    * { box-sizing: border-box; }

    body {
        background-color: var(--bg-primary);
        color: var(--text-primary);
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        margin: 0;
        min-height: 100vh;
        overflow-x: hidden;
    }


    /* ===================== MAIN CONTENT ===================== */
    .main-content {
        margin-left: var(--sidebar-width);
        min-height: 100vh;
    }

    /* Top Navbar */
    .top-navbar {
        background-color: var(--bg-secondary);
        border-bottom: 1px solid var(--border-color);
        padding: 0.75rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: sticky;
        top: 0;
        z-index: 1030;
    }

    .btn-sidebar-toggle {
        display: none;
        background: none;
        border: 1px solid var(--border-color);
        color: var(--text-secondary);
        padding: 0.4rem 0.6rem;
        border-radius: 6px;
        font-size: 1.1rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-sidebar-toggle:hover {
        background-color: rgba(255,255,255,0.05);
        color: var(--text-primary);
    }

    .top-navbar-title h5 {
        margin: 0;
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--text-primary);
    }

    .top-navbar-title small {
        font-size: 0.75rem;
        color: var(--text-muted);
    }

    .top-navbar-user {
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    .user-avatar {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        background-color: var(--accent-blue);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        font-weight: 700;
        color: #fff;
    }

    .user-info span {
        display: block;
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--text-primary);
        line-height: 1.2;
    }

    .user-info small {
        font-size: 0.7rem;
        color: var(--text-muted);
    }

    /* Page Body */
    .page-body {
        padding: 1.5rem;
    }

    /* ===================== STAT CARDS ===================== */
    .stat-card {
        background-color: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 10px;
        padding: 1.25rem;
        height: 100%;
        transition: transform 0.2s ease, border-color 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        border-color: rgba(255,255,255,0.1);
    }

    .stat-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.75rem;
    }

    .stat-card-header small {
        font-size: 0.75rem;
        color: var(--text-secondary);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .stat-card-icon {
        width: 38px;
        height: 38px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }

    .stat-card-icon.orange {
        background-color: rgba(249, 115, 22, 0.15);
        color: var(--accent-orange);
    }

    .stat-card-icon.red {
        background-color: rgba(239, 68, 68, 0.15);
        color: var(--danger);
    }

    .stat-card-icon.blue {
        background-color: rgba(59, 130, 246, 0.15);
        color: var(--accent-blue);
    }

    .stat-card-icon.green {
        background-color: rgba(34, 197, 94, 0.15);
        color: var(--success);
    }

    .stat-card-value {
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--text-primary);
        line-height: 1;
        margin-bottom: 0.25rem;
    }

    .stat-card-sub {
        font-size: 0.75rem;
        color: var(--text-muted);
    }

    /* ===================== QUICK ACTIONS ===================== */
    .quick-action-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.6rem;
        padding: 1rem 1.25rem;
        border-radius: 10px;
        font-size: 0.95rem;
        font-weight: 700;
        border: none;
        width: 100%;
        cursor: pointer;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
        text-decoration: none;
    }

    .quick-action-btn:hover {
        transform: translateY(-2px);
    }

    .quick-action-btn:active {
        transform: translateY(0);
    }

    .quick-action-btn.orange {
        background: linear-gradient(135deg, var(--accent-orange), var(--accent-orange-hover));
        color: #fff;
        box-shadow: 0 4px 14px rgba(249, 115, 22, 0.3);
    }

    .quick-action-btn.orange:hover {
        box-shadow: 0 6px 20px rgba(249, 115, 22, 0.4);
        color: #fff;
    }

    .quick-action-btn.blue {
        background: linear-gradient(135deg, var(--accent-blue), var(--accent-blue-hover));
        color: #fff;
        box-shadow: 0 4px 14px rgba(59, 130, 246, 0.3);
    }

    .quick-action-btn.blue:hover {
        box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
        color: #fff;
    }

    .quick-action-btn i {
        font-size: 1.3rem;
    }

    /* ===================== SECTION CARD ===================== */
    .section-card {
        background-color: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 10px;
        overflow: hidden;
    }

    .section-card-header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .section-card-header h6 {
        margin: 0;
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .section-card-body {
        padding: 0;
    }

    /* ===================== ALERT LIST ===================== */
    .alert-item {
        display: flex;
        align-items: center;
        gap: 0.85rem;
        padding: 0.85rem 1.25rem;
        border-bottom: 1px solid rgba(42, 63, 85, 0.5);
        transition: background-color 0.15s ease;
    }

    .alert-item:last-child {
        border-bottom: none;
    }

    .alert-item:hover {
        background-color: rgba(255, 255, 255, 0.02);
    }

    .alert-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .alert-dot.critical {
        background-color: var(--danger);
        box-shadow: 0 0 8px rgba(239, 68, 68, 0.5);
    }

    .alert-dot.warning {
        background-color: var(--warning);
        box-shadow: 0 0 8px rgba(234, 179, 8, 0.4);
    }

    .alert-item-text {
        flex: 1;
        min-width: 0;
    }

    .alert-item-text strong {
        display: block;
        font-size: 0.85rem;
        color: var(--text-primary);
        font-weight: 600;
    }

    .alert-item-text small {
        font-size: 0.75rem;
        color: var(--text-muted);
    }

    .alert-item-stock {
        font-size: 0.8rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .stock-critical { color: var(--danger); }
    .stock-warning  { color: var(--warning); }

    /* ===================== TABLE ===================== */
    .table-dark-custom {
        margin-bottom: 0;
        color: var(--text-secondary);
        font-size: 0.85rem;
    }

    .table-dark-custom thead th {
        background-color: rgba(15, 25, 35, 0.5);
        border-bottom: 1px solid var(--border-color);
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-muted);
        padding: 0.7rem 1rem;
        white-space: nowrap;
    }

    .table-dark-custom tbody td {
        padding: 0.7rem 1rem;
        border-bottom: 1px solid rgba(42, 63, 85, 0.4);
        vertical-align: middle;
    }

    .table-dark-custom tbody tr:last-child td {
        border-bottom: none;
    }

    .table-dark-custom tbody tr:hover {
        background-color: rgba(255, 255, 255, 0.02);
    }

    .badge-action {
        font-size: 0.7rem;
        font-weight: 600;
        padding: 0.3em 0.65em;
        border-radius: 5px;
    }

    .badge-prestamo {
        background-color: rgba(59, 130, 246, 0.15);
        color: var(--accent-blue);
    }

    .badge-devolucion {
        background-color: rgba(34, 197, 94, 0.15);
        color: var(--success);
    }

    .badge-consumo {
        background-color: rgba(249, 115, 22, 0.15);
        color: var(--accent-orange);
    }

    /* ===================== OVERLAY (Mobile) ===================== */
    .sidebar-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background-color: rgba(0, 0, 0, 0.6);
        z-index: 1035;
    }

    .sidebar-overlay.show {
        display: block;
    }

    /* ===================== RESPONSIVE ===================== */
    @media (max-width: 991.98px) {
        .sidebar {
            transform: translateX(-100%);
        }

        .sidebar.show {
            transform: translateX(0);
        }

        .main-content {
            margin-left: 0;
        }

        .btn-sidebar-toggle {
            display: flex;
            align-items: center;
            justify-content: center;
        }
    }

    @media (max-width: 575.98px) {
        .page-body {
            padding: 1rem;
        }

        .stat-card {
            padding: 1rem;
        }

        .stat-card-value {
            font-size: 1.5rem;
        }

        .quick-action-btn {
            padding: 0.85rem 1rem;
            font-size: 0.9rem;
        }

        .top-navbar {
            padding: 0.6rem 1rem;
        }

        .user-info {
            display: none;
        }
    }
</style>
@endpush

@section('content')
<!-- Sidebar Overlay (Mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- ============ SIDEBAR ============ -->
@include('partials.sidebar')

<!-- ============ MAIN CONTENT ============ -->
<div class="main-content">

    <!-- Top Navbar -->
    <header class="top-navbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn-sidebar-toggle" id="sidebarToggle" aria-label="Abrir menú">
                <i class="bi bi-list"></i>
            </button>
            <div class="top-navbar-title">
                <h5>Dashboard</h5>
                <small>Resumen general del taller</small>
            </div>
        </div>
        <div class="top-navbar-user">
            <div class="user-info text-end">
                <span>{{ Auth::user()->name ?? 'Usuario' }}</span>
                <small>{{ Auth::user()->role ?? 'Administrador' }}</small>
            </div>
            <div class="user-avatar">
                {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 2)) }}
            </div>
        </div>
    </header>

    <!-- Page Body -->
    <div class="page-body">

        <!-- ===== STAT CARDS ROW ===== -->
        <div class="row g-3 mb-4">

            <!-- Herramientas Totales -->
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-card-header">
                        <small>Total Herramientas</small>
                        <div class="stat-card-icon green">
                            <i class="bi bi-tools"></i>
                        </div>
                    </div>
                    <div class="stat-card-value">{{ $totalHerramientas ?? 148 }}</div>
                    <div class="stat-card-sub">En inventario</div>
                </div>
            </div>

            <!-- Herramientas Prestadas -->
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-card-header">
                        <small>Prestadas</small>
                        <div class="stat-card-icon orange">
                            <i class="bi bi-wrench"></i>
                        </div>
                    </div>
                    <div class="stat-card-value">{{ $herramientasPrestadas ?? 12 }}</div>
                    <div class="stat-card-sub">Herramientas activas</div>
                </div>
            </div>

            <!-- Alertas Stock -->
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-card-header">
                        <small>Stock Crítico</small>
                        <div class="stat-card-icon red">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                    </div>
                    <div class="stat-card-value">{{ $stockCritico ?? 5 }}</div>
                    <div class="stat-card-sub">Requieren reposición</div>
                </div>
            </div>

            <!-- Movimientos Hoy -->
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-card-header">
                        <small>Movimientos Hoy</small>
                        <div class="stat-card-icon blue">
                            <i class="bi bi-arrow-left-right"></i>
                        </div>
                    </div>
                    <div class="stat-card-value">{{ $movimientosHoy ?? 23 }}</div>
                    <div class="stat-card-sub">Préstamos y consumos</div>
                </div>
            </div>

        </div>

        <!-- ===== QUICK ACTIONS ===== -->
        <div class="row g-3 mb-4">
            <div class="col-6">
                <a href="{{ route('prestamos') }}" class="quick-action-btn orange">
                    <i class="bi bi-plus-circle-fill"></i>
                    Nuevo Préstamo
                </a>
            </div>
            <div class="col-6">
                <a href="{{ route('consumos.crear') }}" class="quick-action-btn blue">
                    <i class="bi bi-dash-circle-fill"></i>
                    Registrar Consumo
                </a>
            </div>
        </div>

        <!-- ===== ALERTS + TABLE ROW ===== -->
        <div class="row g-3">

            <!-- Alertas Criticas -->
            <div class="col-lg-5">
                <div class="section-card h-100">
                    <div class="section-card-header">
                        <h6>
                            <i class="bi bi-exclamation-triangle-fill" style="color: var(--danger);"></i>
                            Alertas Críticas
                        </h6>
                        <span class="badge bg-danger">{{ count($alertasCriticas ?? []) }}</span>
                    </div>
                    <div class="section-card-body">

                        @forelse($alertasCriticas ?? [] as $alerta)
                        <div class="alert-item">
                            <span class="alert-dot {{ $alerta->stock == 0 ? 'critical' : 'warning' }}"></span>
                            <div class="alert-item-text">
                                <strong>{{ $alerta->nombre }}</strong>
                                <small>Mínimo: {{ $alerta->minimo }} &bull; Categoría: {{ $alerta->categoria }}</small>
                            </div>
                            <span class="alert-item-stock {{ $alerta->stock == 0 ? 'stock-critical' : 'stock-warning' }}">
                                {{ $alerta->stock }} {{ $alerta->stock == 1 ? 'ud.' : 'uds.' }}
                            </span>
                        </div>
                        @empty
                        <div class="alert-item">
                            <span class="alert-dot critical"></span>
                            <div class="alert-item-text">
                                <strong>Aceite 10W-40</strong>
                                <small>Mínimo: 10 &bull; Categoría: Lubricantes</small>
                            </div>
                            <span class="alert-item-stock stock-critical">2 uds.</span>
                        </div>

                        <div class="alert-item">
                            <span class="alert-dot critical"></span>
                            <div class="alert-item-text">
                                <strong>Filtro de aire K&N</strong>
                                <small>Mínimo: 5 &bull; Categoría: Filtros</small>
                            </div>
                            <span class="alert-item-stock stock-critical">1 ud.</span>
                        </div>

                        <div class="alert-item">
                            <span class="alert-dot critical"></span>
                            <div class="alert-item-text">
                                <strong>Pastillas de freno</strong>
                                <small>Mínimo: 8 &bull; Categoría: Frenos</small>
                            </div>
                            <span class="alert-item-stock stock-critical">0 uds.</span>
                        </div>

                        <div class="alert-item">
                            <span class="alert-dot warning"></span>
                            <div class="alert-item-text">
                                <strong>Líquido de frenos DOT4</strong>
                                <small>Mínimo: 6 &bull; Categoría: Líquidos</small>
                            </div>
                            <span class="alert-item-stock stock-warning">4 uds.</span>
                        </div>

                        <div class="alert-item">
                            <span class="alert-dot warning"></span>
                            <div class="alert-item-text">
                                <strong>Guantes de nitrilo (Caja)</strong>
                                <small>Mínimo: 3 &bull; Categoría: EPP</small>
                            </div>
                            <span class="alert-item-stock stock-warning">1 ud.</span>
                        </div>
                        @endforelse

                    </div>
                </div>
            </div>

            <!-- Movimientos Recientes -->
            <div class="col-lg-7">
                <div class="section-card h-100">
                    <div class="section-card-header">
                        <h6>
                            <i class="bi bi-clock-history" style="color: var(--accent-blue);"></i>
                            Movimientos Recientes
                        </h6>
                        <a href="{{ route('historial.index') }}" style="font-size: 0.78rem; color: var(--accent-blue); text-decoration: none;">Ver todo</a>
                    </div>
                    <div class="section-card-body">
                        <div class="table-responsive">
                            <table class="table table-dark-custom">
                                <thead>
                                    <tr>
                                        <th>Usuario</th>
                                        <th>Acción</th>
                                        <th>Objeto</th>
                                        <th>Hora</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($movimientosRecientes ?? [] as $movimiento)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="user-avatar" style="width:28px;height:28px;font-size:0.65rem;border-radius:6px;background-color:{{ $movimiento->usuario_color ?? 'var(--accent-blue)' }};">
                                                    {{ strtoupper(substr($movimiento->usuario_nombre, 0, 2)) }}
                                                </div>
                                                {{ $movimiento->usuario_nombre }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-action badge-{{ strtolower($movimiento->tipo) }}">
                                                {{ $movimiento->tipo }}
                                            </span>
                                        </td>
                                        <td>{{ $movimiento->objeto }}</td>
                                        <td style="white-space:nowrap;">{{ $movimiento->hora }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="user-avatar" style="width:28px;height:28px;font-size:0.65rem;border-radius:6px;background-color:var(--accent-blue);">RL</div>
                                                Roberto L.
                                            </div>
                                        </td>
                                        <td><span class="badge badge-action badge-prestamo">Préstamo</span></td>
                                        <td>Llave de torque 1/2"</td>
                                        <td style="white-space:nowrap;">14:32</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="user-avatar" style="width:28px;height:28px;font-size:0.65rem;border-radius:6px;background-color:var(--accent-orange);">MG</div>
                                                Miguel G.
                                            </div>
                                        </td>
                                        <td><span class="badge badge-action badge-consumo">Consumo</span></td>
                                        <td>Aceite 10W-40 (2L)</td>
                                        <td style="white-space:nowrap;">13:58</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="user-avatar" style="width:28px;height:28px;font-size:0.65rem;border-radius:6px;background-color:var(--success);">JP</div>
                                                Juan P.
                                            </div>
                                        </td>
                                        <td><span class="badge badge-action badge-devolucion">Devolución</span></td>
                                        <td>Gato hidráulico 3T</td>
                                        <td style="white-space:nowrap;">13:15</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="user-avatar" style="width:28px;height:28px;font-size:0.65rem;border-radius:6px;background-color:#8b5cf6;">AS</div>
                                                Ana S.
                                            </div>
                                        </td>
                                        <td><span class="badge badge-action badge-prestamo">Préstamo</span></td>
                                        <td>Multímetro Fluke</td>
                                        <td style="white-space:nowrap;">12:47</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="user-avatar" style="width:28px;height:28px;font-size:0.65rem;border-radius:6px;background-color:var(--accent-blue);">RL</div>
                                                Roberto L.
                                            </div>
                                        </td>
                                        <td><span class="badge badge-action badge-consumo">Consumo</span></td>
                                        <td>Guantes de nitrilo (x2)</td>
                                        <td style="white-space:nowrap;">11:30</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="user-avatar" style="width:28px;height:28px;font-size:0.65rem;border-radius:6px;background-color:var(--warning);">DM</div>
                                                Diego M.
                                            </div>
                                        </td>
                                        <td><span class="badge badge-action badge-devolucion">Devolución</span></td>
                                        <td>Compresor portátil</td>
                                        <td style="white-space:nowrap;">10:05</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div><!-- /.page-body -->

</div><!-- /.main-content -->
@endsection

@push('scripts')
<script>
   
</script>
@endpush