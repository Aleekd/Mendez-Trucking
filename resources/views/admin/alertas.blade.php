@extends('layouts.app')

@section('title', 'Alertas de Stock')

@section('content')

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    @include('partials.sidebar', ['active' => 'alertas'])

    <div class="main-content">
        
        <header class="top-navbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn-sidebar-toggle" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                <div class="top-navbar-title">
                    <h5>Alertas de Stock</h5>
                    <small>Monitoreo de inventario crítico</small>
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
            
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="stat-card border-start border-4 border-secondary" style="border-left-width: 4px !important;">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <small class="text-muted fw-bold text-uppercase">Total Alertas</small>
                                <h3 class="fw-bold text-white mb-0">{{ $totalAlertas ?? 0 }}</h3>
                            </div>
                            <div class="stat-card-icon" style="background-color: rgba(108, 117, 125, 0.2); color: #adb5bd;">
                                <i class="bi bi-bell-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="stat-card border-start border-4 border-danger" style="border-left-width: 4px !important;">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <small class="fw-bold text-uppercase text-danger">Críticos / Agotados</small>
                                <h3 class="fw-bold text-danger mb-0">{{ $criticos ?? 0 }}</h3>
                            </div>
                            <div class="stat-card-icon red">
                                <i class="bi bi-x-circle-fill"></i>
                            </div>
                        </div>
                        <small class="text-danger fst-italic mt-2 d-block" style="font-size: 0.7rem;">Requiere acción inmediata</small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="stat-card border-start border-4 border-warning" style="border-color: #ffc107 !important; border-left-width: 4px !important;">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <small class="fw-bold text-uppercase text-warning">Stock Bajo</small>
                                <h3 class="fw-bold text-warning mb-0">{{ $bajos ?? 0 }}</h3>
                            </div>
                            <div class="stat-card-icon" style="background-color: rgba(255, 193, 7, 0.15); color: #ffc107;">
                                <i class="bi bi-dash-circle-fill"></i>
                            </div>
                        </div>
                        <small class="text-warning fst-italic mt-2 d-block" style="font-size: 0.7rem;">Bajo el umbral mínimo</small>
                    </div>
                </div>
            </div>

            <div class="section-card overflow-hidden">
                <div class="section-card-header p-3 border-bottom border-dark d-flex justify-content-between align-items-center" style="background-color: rgba(0,0,0,0.2);">
                    <h6 class="mb-0 fw-bold text-white d-flex align-items-center gap-2">
                        <i class="bi bi-list-ul"></i> INVENTARIO EN RIESGO
                    </h6>
                    <button class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-2" style="border-color: var(--border-color); color: var(--text-secondary);">
                        <i class="bi bi-download"></i> Exportar
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-dark-custom align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Artículo</th>
                                <th>Categoría</th>
                                <th class="text-center">Min. Requerido</th>
                                <th class="text-center">Stock Actual</th>
                                <th>Estado</th>
                                <th class="text-end pe-4">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($alertas ?? [] as $item)
                                @php
                                    // Lógica visual: Si stock es 0 es Crítico (Rojo), si no, es Bajo (Amarillo)
                                    $isCritical = $item->stock_actual == 0;
                                    $rowClass = $isCritical ? 'row-critical' : '';
                                    $textClass = $isCritical ? 'text-danger fw-bold' : 'text-warning fw-bold';
                                    $barColor = $isCritical ? 'bg-danger' : 'bg-warning';
                                    $badgeClass = $isCritical ? 'bg-danger' : 'bg-warning text-dark';
                                    $badgeText = $isCritical ? 'AGOTADO' : 'BAJO STOCK';
                                    
                                    // Cálculo de porcentaje para la barra (simulado)
                                    $percent = $item->stock_minimo > 0 ? ($item->stock_actual / $item->stock_minimo) * 100 : 0;
                                @endphp

                                <tr class="{{ $rowClass }}">
                                    <td class="ps-4 py-3">
                                        <div class="d-flex flex-column">
                                            <span class="{{ $isCritical ? 'text-danger fw-bold' : 'text-white fw-bold' }}">
                                                {{ $item->nombre }}
                                            </span>
                                            <small class="text-muted" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                                                {{ Str::limit($item->descripcion ?? 'Sin descripción', 20) }}
                                            </small>
                                        </div>
                                    </td>

                                    <td>
                                        <span class="badge" style="background-color: var(--bg-input); color: var(--text-muted); font-weight: normal; border: 1px solid var(--border-color);">
                                            @switch(strtolower($item->categoria))
                                                @case('herramientas') <i class="bi bi-tools me-1"></i> @break
                                                @case('refacciones') <i class="bi bi-gear-wide-connected me-1"></i> @break
                                                @case('limpieza') <i class="bi bi-droplet-fill me-1"></i> @break
                                                @default <i class="bi bi-box-seam me-1"></i>
                                            @endswitch
                                            {{ ucfirst($item->categoria) }}
                                        </span>
                                    </td>

                                    <td class="text-center font-monospace text-muted small">
                                        {{ $item->stock_minimo }} uds.
                                    </td>

                                    <td class="text-center" style="width: 15%;">
                                        <div class="d-flex flex-column align-items-center">
                                            <span class="{{ $textClass }} fs-5 lh-1">{{ $item->stock_actual }}</span>
                                            <div class="progress mt-1 w-75" style="height: 4px; background-color: rgba(255,255,255,0.1);">
                                                <div class="progress-bar {{ $barColor }}" role="progressbar" style="width: {{ $percent }}%"></div>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <span class="badge {{ $badgeClass }} {{ $isCritical ? 'animate-pulse' : '' }}" style="font-size: 0.65rem; padding: 0.35em 0.6em;">
                                            {{ $badgeText }}
                                        </span>
                                    </td>

                                    <td class="text-end pe-4">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('inventario.index', $item->id) }}" class="btn-action-primary py-1 px-2" style="font-size: 0.75rem; text-decoration: none;">
                                                <i class="bi bi-plus-circle me-1"></i> Reabastecer
                                            </a>
                                            <button class="btn btn-sm btn-outline-secondary py-1 px-2" style="border-color: var(--border-color); color: var(--text-muted);">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-check2-circle text-success" style="font-size: 2.5rem; display: block; margin-bottom: 10px;"></i>
                                        <span class="fw-bold text-success">¡Todo en orden!</span><br>
                                        <small>No hay alertas de stock bajo en este momento.</small>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="section-card-footer p-3 d-flex justify-content-between align-items-center border-top border-dark" style="background-color: rgba(0,0,0,0.2);">
                    <small class="text-muted">Mostrando {{ count($alertas ?? []) }} alertas</small>
                    <div class="btn-group">
                        <button class="btn btn-sm btn-outline-secondary" disabled style="border-color: var(--border-color); color: var(--text-muted);">Anterior</button>
                        <button class="btn btn-sm btn-outline-secondary" style="border-color: var(--border-color); color: var(--text-muted);">Siguiente</button>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection

@push('styles')
<style>
    /* ===================== TABLA OSCURA BLINDADA ===================== */
    
    /* 1. Fondo oscuro base */
    .table-dark-custom {
        background-color: var(--bg-card) !important;
        color: var(--text-primary);
        border-color: var(--border-color);
    }

    .table-dark-custom tbody tr,
    .table-dark-custom tbody td {
        background-color: var(--bg-card) !important;
        color: var(--text-primary) !important;
        border-bottom: 1px solid var(--border-color);
        transition: none !important;
    }

    /* 2. Hover Sutil */
    .table-dark-custom tbody tr:hover td {
        background-color: #253346 !important; 
        color: #ffffff !important;
    }

    /* 3. CORRECCIÓN: Filas Críticas (Rojo) */
    /* Quitamos el box-shadow que causaba las líneas entre columnas */
    tr.row-critical td {
        background-color: rgba(220, 53, 69, 0.08) !important; /* Fondo rojo muy suave y parejo */
        box-shadow: none !important; /* <--- ESTO ELIMINA LAS LÍNEAS MOLESTAS */
    }

    /* OPCIONAL: Si quieres una sola línea elegante al puro principio de la fila */
    tr.row-critical td:first-child {
        border-left: 3px solid #dc3545 !important;
    }
    
    /* Animación del badge */
    @keyframes pulse-red {
        0% { opacity: 1; }
        50% { opacity: 0.6; }
        100% { opacity: 1; }
    }
    .animate-pulse {
        animation: pulse-red 2s infinite;
    }

    .stat-card {
        background-color: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 1.25rem;
    }
</style>
@endpush