{{-- resources/views/inventario.blade.php --}}
@extends('layouts.app')

@section('title', 'Inventario')

@section('content')

    <!-- Sidebar Overlay (Mobile) -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- ============ SIDEBAR ============ -->
    @include('partials.sidebar', ['active' => 'inventario'])

    <!-- ============ MAIN CONTENT ============ -->
    <div class="main-content">

        <!-- Top Navbar -->
        <header class="top-navbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn-sidebar-toggle" id="sidebarToggle" aria-label="Abrir menú">
                    <i class="bi bi-list"></i>
                </button>
                <div class="top-navbar-title">
                    <h5>Inventario</h5>
                    <small>Gestión de artículos del taller</small>
                </div>
            </div>
            <div class="top-navbar-user">
                <div class="user-info text-end">
                    <span>{{ Auth::user()->name ?? 'Usuario' }}</span>
                    <small>{{ Auth::user()->role ?? 'Operador' }}</small>
                </div>
                <div class="user-avatar">
                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}{{ strtoupper(substr(explode(' ', Auth::user()->name ?? 'U U')[1] ?? '', 0, 1)) }}
                </div>
            </div>
        </header>

        <!-- Page Body -->
        <div class="page-body">

            {{-- Mensajes de éxito --}}
            @if(session('success'))
                <div class="alert alert-success-custom mb-3 d-flex align-items-center gap-2" role="alert">
                    <i class="bi bi-check-circle-fill"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close btn-close-custom ms-auto" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            @endif

            {{-- Header con botón Agregar --}}
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                <div>
                    <span class="text-secondary-custom" style="font-size: 0.85rem;">
                        Total de artículos: <strong class="text-primary-custom">{{ $articulos->total() ?? 0 }}</strong>
                    </span>
                </div>
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('inventario.index') }}" class="btn-action-primary">
                        <i class="bi bi-plus-lg"></i>
                        Agregar Artículo
                    </a>
                @endif
            </div>

            {{-- Pestañas de Categorías (RF-04) --}}
            <div class="category-tabs mb-3">
                <a href="{{ route('inventario.index', ['categoria' => 'todas']) }}"
                   class="category-tab {{ (!request('categoria') || request('categoria') === 'todas') ? 'active' : '' }}">
                    <i class="bi bi-grid-3x3-gap-fill"></i>
                    Todas
                </a>
                <a href="{{ route('inventario.index', ['categoria' => 'herramientas']) }}"
                   class="category-tab {{ request('categoria') === 'herramientas' ? 'active' : '' }}">
                    <i class="bi bi-tools"></i>
                    Herramientas
                </a>
                <a href="{{ route('inventario.index', ['categoria' => 'refacciones']) }}"
                   class="category-tab {{ request('categoria') === 'refacciones' ? 'active' : '' }}">
                    <i class="bi bi-gear-fill"></i>
                    Refacciones
                </a>
                <a href="{{ route('inventario.index', ['categoria' => 'limpieza']) }}"
                   class="category-tab {{ request('categoria') === 'limpieza' ? 'active' : '' }}">
                    <i class="bi bi-droplet-fill"></i>
                    Limpieza
                </a>
                <a href="{{ route('inventario.index', ['categoria' => 'papeleria']) }}"
                   class="category-tab {{ request('categoria') === 'papeleria' ? 'active' : '' }}">
                    <i class="bi bi-journal-text"></i>
                    Papelería
                </a>
            </div>

            {{-- Barra de búsqueda rápida --}}
            <div class="search-bar-inline mb-3">
                <i class="bi bi-search"></i>
                <input type="text" id="searchInventario" class="form-control" placeholder="Buscar en inventario..." autocomplete="off">
            </div>

            {{-- Tabla de Inventario --}}
            <div class="section-card">
                <div class="section-card-body">
                    <div class="table-responsive">
                        <table class="table table-dark-custom" id="tablaInventario">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Artículo</th>
                                    <th>Categoría</th>
                                    <th>Stock Actual</th>
                                    <th>Stock Mínimo</th>
                                    <th>Estado</th>
                                    @if(Auth::user()->role === 'admin')
                                        <th class="text-end">Acciones</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($articulos as $articulo)
                                    <tr class="fila-inventario">
                                        <td>{{ $articulo->id }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="item-icon {{ $articulo->categoria }}">
                                                    @switch($articulo->categoria)
                                                        @case('herramientas')
                                                            <i class="bi bi-tools"></i>
                                                            @break
                                                        @case('refacciones')
                                                            <i class="bi bi-gear-fill"></i>
                                                            @break
                                                        @case('limpieza')
                                                            <i class="bi bi-droplet-fill"></i>
                                                            @break
                                                        @case('papeleria')
                                                            <i class="bi bi-journal-text"></i>
                                                            @break
                                                        @default
                                                            <i class="bi bi-box-seam"></i>
                                                    @endswitch
                                                </div>
                                                <div>
                                                    <strong class="text-primary-custom" style="font-size: 0.85rem;">{{ $articulo->nombre }}</strong>
                                                    @if($articulo->descripcion)
                                                        <br><small class="text-muted-custom">{{ Str::limit($articulo->descripcion, 40) }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-categoria badge-{{ $articulo->categoria }}">
                                                {{ ucfirst($articulo->categoria) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="fw-bold {{ $articulo->stock_actual <= $articulo->stock_minimo ? 'text-danger-glow' : 'text-primary-custom' }}">
                                                {{ $articulo->stock_actual }}
                                            </span>
                                        </td>
                                        <td class="text-muted-custom">{{ $articulo->stock_minimo }}</td>
                                        <td>
                                            @if($articulo->stock_actual <= 0)
                                                <span class="badge badge-status badge-agotado">Agotado</span>
                                            @elseif($articulo->stock_actual <= $articulo->stock_minimo)
                                                <span class="badge badge-status badge-critico">Crítico</span>
                                            @else
                                                <span class="badge badge-status badge-disponible">Disponible</span>
                                            @endif
                                        </td>
                                        @if(Auth::user()->role === 'admin')
                                            <td class="text-end">
                                                <div class="d-flex align-items-center justify-content-end gap-2">
                                                    <a href="{{ route('inventario.index', $articulo->id) }}" class="btn-table-action btn-edit" title="Editar">
                                                        <i class="bi bi-pencil-fill"></i>
                                                    </a>
                                                    <form method="POST" action="{{ route('inventario.index', $articulo->id) }}" class="d-inline" onsubmit="return confirm('¿Eliminar este artículo?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn-table-action btn-delete" title="Eliminar">
                                                            <i class="bi bi-trash-fill"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ Auth::user()->role === 'admin' ? 7 : 6 }}" class="text-center py-4" style="color: var(--text-muted);">
                                            <i class="bi bi-inbox" style="font-size: 2rem; display: block; margin-bottom: 0.5rem;"></i>
                                            No se encontraron artículos en esta categoría
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Paginación --}}
            @if($articulos->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $articulos->appends(request()->query())->links() }}
                </div>
            @endif

        </div>
    </div>

@endsection

@push('styles')
<style>
    /* ===================== CATEGORY TABS ===================== */
    .category-tabs {
        display: flex;
        gap: 0.5rem;
        overflow-x: auto;
        padding-bottom: 0.25rem;
        -webkit-overflow-scrolling: touch;
    }

    .category-tabs::-webkit-scrollbar { height: 3px; }
    .category-tabs::-webkit-scrollbar-track { background: transparent; }
    .category-tabs::-webkit-scrollbar-thumb { background: var(--border-color); border-radius: 3px; }

    .category-tab {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.55rem 1rem;
        background-color: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        color: var(--text-secondary);
        text-decoration: none;
        font-size: 0.8rem;
        font-weight: 600;
        white-space: nowrap;
        transition: all 0.2s ease;
    }

    .category-tab:hover {
        background-color: rgba(255, 255, 255, 0.05);
        color: var(--text-primary);
    }

    .category-tab.active {
        background-color: rgba(249, 115, 22, 0.12);
        border-color: var(--accent-orange);
        color: var(--accent-orange);
    }

    .category-tab i { font-size: 0.9rem; }

    /* ===================== SEARCH BAR ===================== */
    .search-bar-inline {
        position: relative;
    }

    .search-bar-inline i {
        position: absolute;
        left: 0.9rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        font-size: 0.9rem;
        pointer-events: none;
    }

    .search-bar-inline .form-control {
        background-color: var(--bg-card);
        border: 1px solid var(--border-color);
        color: var(--text-primary);
        padding: 0.65rem 0.9rem 0.65rem 2.5rem;
        border-radius: 8px;
        font-size: 0.85rem;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .search-bar-inline .form-control::placeholder { color: var(--text-muted); }

    .search-bar-inline .form-control:focus {
        background-color: var(--bg-card);
        border-color: var(--accent-blue);
        color: var(--text-primary);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    }

    /* ===================== ACTION BUTTON ===================== */
    .btn-action-primary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.6rem 1.1rem;
        background: linear-gradient(135deg, var(--accent-orange), var(--accent-orange-hover));
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
        box-shadow: 0 3px 12px rgba(249, 115, 22, 0.25);
    }

    .btn-action-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 5px 18px rgba(249, 115, 22, 0.35);
        color: #fff;
    }

    /* ===================== SUCCESS ALERT ===================== */
    .alert-success-custom {
        background-color: rgba(34, 197, 94, 0.1);
        border: 1px solid rgba(34, 197, 94, 0.25);
        color: #86efac;
        border-radius: 8px;
        font-size: 0.85rem;
        padding: 0.75rem 1rem;
    }

    .btn-close-custom {
        filter: invert(1) grayscale(100%) brightness(200%);
        opacity: 0.5;
        font-size: 0.7rem;
    }

    /* ===================== ITEM ICON ===================== */
    .item-icon {
        width: 32px;
        height: 32px;
        border-radius: 7px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        flex-shrink: 0;
    }

    .item-icon.herramientas { background-color: rgba(34, 197, 94, 0.15); color: var(--success); }
    .item-icon.refacciones  { background-color: rgba(59, 130, 246, 0.15); color: var(--accent-blue); }
    .item-icon.limpieza     { background-color: rgba(168, 85, 247, 0.15); color: #a855f7; }
    .item-icon.papeleria    { background-color: rgba(249, 115, 22, 0.15); color: var(--accent-orange); }

    /* ===================== BADGES ===================== */
    .badge-categoria {
        font-size: 0.68rem;
        font-weight: 600;
        padding: 0.3em 0.6em;
        border-radius: 5px;
    }

    .badge-herramientas { background-color: rgba(34, 197, 94, 0.15); color: var(--success); }
    .badge-refacciones  { background-color: rgba(59, 130, 246, 0.15); color: var(--accent-blue); }
    .badge-limpieza     { background-color: rgba(168, 85, 247, 0.15); color: #a855f7; }
    .badge-papeleria    { background-color: rgba(249, 115, 22, 0.15); color: var(--accent-orange); }

    .badge-status {
        font-size: 0.68rem;
        font-weight: 600;
        padding: 0.3em 0.6em;
        border-radius: 5px;
    }

    .badge-disponible { background-color: rgba(34, 197, 94, 0.15); color: var(--success); }
    .badge-critico    { background-color: rgba(239, 68, 68, 0.15); color: var(--danger); }
    .badge-agotado    { background-color: rgba(239, 68, 68, 0.25); color: #fca5a5; }

    /* ===================== TABLE ACTIONS ===================== */
    .btn-table-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 6px;
        border: 1px solid var(--border-color);
        background: transparent;
        color: var(--text-secondary);
        font-size: 0.8rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-edit:hover {
        background-color: rgba(59, 130, 246, 0.15);
        border-color: var(--accent-blue);
        color: var(--accent-blue);
    }

    .btn-delete:hover {
        background-color: rgba(239, 68, 68, 0.15);
        border-color: var(--danger);
        color: var(--danger);
    }

    .text-primary-custom { color: var(--text-primary); }
    .text-secondary-custom { color: var(--text-secondary); }
    .text-muted-custom { color: var(--text-muted); }
    .text-danger-glow { color: var(--danger); text-shadow: 0 0 6px rgba(239, 68, 68, 0.4); }

    @media (max-width: 575.98px) {
        .category-tab { padding: 0.5rem 0.75rem; font-size: 0.75rem; }
        .btn-action-primary { padding: 0.55rem 0.9rem; font-size: 0.8rem; }
    }
</style>
@endpush

@push('scripts')
<script>
    // Búsqueda en tiempo real dentro de la tabla
    document.getElementById('searchInventario').addEventListener('input', function() {
        const query = this.value.toLowerCase();
        document.querySelectorAll('.fila-inventario').forEach(function(row) {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(query) ? '' : 'none';
        });
    });

    // Sidebar toggle
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const toggleBtn = document.getElementById('sidebarToggle');

    function openSidebar() {
        sidebar.classList.add('show');
        overlay.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
    function closeSidebar() {
        sidebar.classList.remove('show');
        overlay.classList.remove('show');
        document.body.style.overflow = '';
    }

    toggleBtn.addEventListener('click', function() {
        sidebar.classList.contains('show') ? closeSidebar() : openSidebar();
    });
    overlay.addEventListener('click', closeSidebar);
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 992) closeSidebar();
    });
</script>
@endpush
