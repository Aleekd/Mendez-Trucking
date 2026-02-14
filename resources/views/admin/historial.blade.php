@extends('layouts.app')

@section('title', 'Historial de Movimientos')

@section('content')

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    @include('partials.sidebar', ['active' => 'historial'])

    <div class="main-content">
        
        <header class="top-navbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn-sidebar-toggle" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                <div class="top-navbar-title">
                    <h5>Historial</h5>
                    <small>Registro cronológico de actividad</small>
                </div>
            </div>
            <div class="top-navbar-user">
                <div class="user-info text-end">
                    <span>{{ Auth::user()->name ?? 'Admin' }}</span>
                    <small style="color: var(--accent-orange);">{{ strtoupper(Auth::user()->role ?? 'Admin') }}</small>
                </div>
                <div class="user-avatar" style="background-color: var(--bg-card); border: 1px solid var(--border-color);">
                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 2)) }}
                </div>
            </div>
        </header>

        <div class="page-body">
            
            <div class="mb-4">
                <h2 class="fw-bold text-white mb-1">Historial de Movimientos</h2>
                <p class="text-muted small">Registro detallado de préstamos, devoluciones y consumos.</p>
            </div>

            <div class="section-card mb-4">
                <div class="section-card-body p-4">
                    <form action="" method="GET"> <div class="row g-3 align-items-end">
                            
                            <div class="col-md-4">
                                <label class="form-label small fw-bold ">BUSCAR</label>
                                <div class="search-bar-inline w-100">
                                    <i class="bi bi-search"></i>
                                    <input type="text" class="form-control" placeholder="Herramienta o usuario...">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label small fw-bold ">TIPO</label>
                                <select class="form-select custom-select">
                                    <option value="">Todos</option>
                                    <option value="prestamo">Préstamo</option>
                                    <option value="devolucion">Devolución</option>
                                    <option value="consumo">Consumo</option>
                                    <option value="dano">Daño</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small fw-bold ">RANGO DE FECHAS</label>
                                <div class="input-group">
                                    <input type="date" class="form-control custom-input" placeholder="Desde">
                                    <span class="input-group-text custom-addon" style="border-left:0; border-right:0;">a</span>
                                    <input type="date" class="form-control custom-input" placeholder="Hasta">
                                </div>
                            </div>

                            <div class="col-md-2 text-end">
                                <button type="button" class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-2" style="border-color: var(--border-color); color: var(--text-primary); padding: 0.6rem;">
                                    <i class="bi bi-download"></i> Exportar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="section-card overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-dark-custom mb-0 align-middle">
                        <thead>
                            <tr>
                                <th class="ps-4">Fecha</th>
                                <th>Usuario</th>
                                <th>Acción</th>
                                <th>Ítem / Herramienta</th>
                                <th class="pe-4">Cant.</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($movimientos ?? [] as $mov)
                            <tr class="hover-row">
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center gap-2 text-muted-custom">
                                        <i class="bi bi-clock"></i>
                                        <span style="font-size: 0.9rem;">
                                            {{ \Carbon\Carbon::parse($mov->fecha)->diffForHumans() }}
                                        </span>
                                    </div>
                                    <small class="text-muted d-block ps-4" style="font-size: 0.7rem;">
                                        {{ \Carbon\Carbon::parse($mov->fecha)->format('d/m/Y H:i') }}
                                    </small>
                                </td>

                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="user-avatar" style="width:32px; height:32px; font-size:0.75rem; background-color: var(--bg-secondary); border: 1px solid var(--border-color);">
                                            {{ strtoupper(substr($mov->usuario, 0, 2)) }}
                                        </div>
                                        <span class="fw-bold text-white" style="font-size: 0.9rem;">{{ $mov->usuario }}</span>
                                    </div>
                                </td>

                                <td>
                                    @php
                                        // Lógica de colores según el tipo de movimiento
                                        $tipo = strtolower($mov->tipo);
                                        $badgeClass = match($tipo) {
                                            'prestamo' => 'warning',   // Amarillo
                                            'devolucion' => 'success', // Verde
                                            'consumo' => 'info',       // Azul
                                            'dano' => 'danger',        // Rojo
                                            default => 'secondary'
                                        };
                                        $icon = match($tipo) {
                                            'prestamo' => 'bi-arrow-right',
                                            'devolucion' => 'bi-arrow-left',
                                            'consumo' => 'bi-box-seam',
                                            'dano' => 'bi-exclamation-triangle',
                                            default => 'bi-circle'
                                        };
                                        $label = ucfirst($tipo == 'dano' ? 'Daño/Pérdida' : $tipo);
                                    @endphp
                                    
                                    <span class="badge badge-soft-{{ $badgeClass }} d-inline-flex align-items-center gap-1">
                                        <i class="bi {{ $icon }}"></i> {{ $label }}
                                    </span>
                                </td>

                                <td>
                                    <span class="d-block text-white fw-medium">{{ $mov->item }}</span>
                                    @if(isset($mov->detalles))
                                        <small class="text-muted">{{ Str::limit($mov->detalles, 30) }}</small>
                                    @endif
                                </td>

                                <td class="pe-4">
                                    <span class="fw-bold fs-6 {{ $tipo == 'devolucion' ? 'text-success' : 'text-white' }}">
                                        {{ $mov->cantidad }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 ">
                                    <i class="bi bi-calendar-x" style="font-size: 2rem; display: block; margin-bottom: 10px; opacity: 0.5;"></i>
                                    No hay movimientos registrados.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-3 border-top border-dark d-flex justify-content-between align-items-center" style="background-color: rgba(0,0,0,0.2);">
                    <small class="text-muted">Mostrando {{ count($movimientos ?? []) }} registros</small>
                    <div class="btn-group">
                        <button class="btn btn-sm btn-outline-secondary" disabled style="border-color: var(--border-color); color: var(--text-muted);">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-secondary" style="border-color: var(--border-color); color: var(--text-muted);">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
<style>
    /* ===================== ESTILOS "INDUSTRIAL DARK" FIJOS ===================== */
    
    /* Forzamos que la tabla y sus celdas sean SIEMPRE del color de la tarjeta */
    .table-dark-custom {
        background-color: var(--bg-card) !important;
        color: var(--text-primary);
        border-color: var(--border-color);
    }

    .table-dark-custom tbody tr,
    .table-dark-custom tbody td {
        background-color: var(--bg-card) !important; /* Fondo siempre oscuro */
        color: var(--text-primary) !important;       /* Texto siempre claro */
        border-bottom: 1px solid var(--border-color);
        transition: none !important; /* Eliminamos animaciones que causen flash */
    }

    /* Al pasar el mouse, hacemos un cambio MUY sutil (casi imperceptible) */
    .table-dark-custom tbody tr:hover td {
        /* En lugar de blanco, usamos el mismo color oscuro pero un 5% más claro */
        background-color: #253346 !important; 
        color: #ffffff !important;
    }

    /* Inputs y Selects oscuros para los filtros */
    .custom-input, .custom-select {
        background-color: var(--bg-input);
        border: 1px solid var(--border-color);
        color: var(--text-primary);
        font-size: 0.85rem;
        padding: 0.6rem 0.8rem;
    }
    .custom-input:focus, .custom-select:focus {
        background-color: var(--bg-input);
        border-color: var(--accent-orange);
        color: var(--text-primary);
        box-shadow: none;
    }
    .custom-addon {
        background-color: var(--bg-input);
        border: 1px solid var(--border-color);
        color: var(--text-muted);
    }

    /* Badges estilo "Soft" (Colores suaves que no lastiman la vista) */
    .badge-soft-warning { background: rgba(234, 179, 8, 0.1); color: #fbbf24; border: 1px solid rgba(234, 179, 8, 0.2); }
    .badge-soft-success { background: rgba(34, 197, 94, 0.1); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.2); }
    .badge-soft-info    { background: rgba(59, 130, 246, 0.1); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.2); }
    .badge-soft-danger  { background: rgba(239, 68, 68, 0.1); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.2); }
    .badge-soft-secondary { background: rgba(148, 163, 184, 0.1); color: #94a3b8; border: 1px solid rgba(148, 163, 184, 0.2); }

    .badge {
        padding: 0.4em 0.8em;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-radius: 4px; /* Un poco más cuadrado para look industrial */
    }
</style>
@endpush