{{-- resources/views/buscar.blade.php --}}
@extends('layouts.app')

@section('title', 'Buscar Disponibilidad')

@section('content')

    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    @include('partials.sidebar', ['active' => 'buscar'])

    <div class="main-content">

        <!-- Top Navbar -->
        <header class="top-navbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn-sidebar-toggle" id="sidebarToggle" aria-label="Abrir menú">
                    <i class="bi bi-list"></i>
                </button>
                <div class="top-navbar-title">
                    <h5>Buscar Herramientas</h5>
                    <small>Consulta disponibilidad en tiempo real</small>
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

        <div class="page-body">

            {{-- ========== BUSCADOR CENTRALIZADO (RF-03) ========== --}}
            <div class="search-hero">
                <div class="search-hero-icon">
                    <i class="bi bi-search"></i>
                </div>
                <h4 class="search-hero-title">¿Qué herramienta necesitas?</h4>
                <p class="search-hero-sub">Escribe el nombre para consultar disponibilidad y cantidad en stock.</p>

                <form method="GET" action="{{ route('buscar.index') }}" class="search-hero-form">
                    <div class="search-input-wrapper">
                        <i class="bi bi-search"></i>
                        <input
                            type="text"
                            class="form-control"
                            name="q"
                            value="{{ request('q') }}"
                            placeholder="Ej: Llave Allen, Aceite 10W40, Tornillos..."
                            autocomplete="off"
                            autofocus
                        >
                        @if(request('q'))
                            <a href="{{ route('buscar.index') }}" class="search-clear" title="Limpiar búsqueda">
                                <i class="bi bi-x-lg"></i>
                            </a>
                        @endif
                    </div>
                    <button type="submit" class="btn-search-submit">
                        <i class="bi bi-search"></i>
                        Buscar
                    </button>
                </form>
            </div>

            {{-- ========== RESULTADOS ========== --}}
            @if(request('q'))
                <div class="search-results-header mb-3">
                    <span>
                        Resultados para "<strong>{{ request('q') }}</strong>"
                        &mdash; {{ $resultados->count() }} encontrado(s)
                    </span>
                </div>

                <div class="row g-3">
                    @forelse($resultados as $item)
                        <div class="col-sm-6 col-lg-4">
                            <div class="search-result-card">
                                <div class="result-card-top">
                                    <div class="result-icon {{ $item->categoria }}">
                                        @switch($item->categoria)
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
                                    <div class="result-status">
                                        @if($item->stock_actual > $item->stock_minimo)
                                            <span class="badge badge-disponible-lg">Disponible</span>
                                        @elseif($item->stock_actual > 0)
                                            <span class="badge badge-bajo-lg">Stock Bajo</span>
                                        @else
                                            <span class="badge badge-agotado-lg">Agotado</span>
                                        @endif
                                    </div>
                                </div>
                                <h6 class="result-name">{{ $item->nombre }}</h6>
                                <span class="result-cat">{{ ucfirst($item->categoria) }}</span>
                                <div class="result-stock">
                                    <div class="result-stock-number {{ $item->stock_actual <= $item->stock_minimo ? 'critical' : '' }}">
                                        {{ $item->stock_actual }}
                                    </div>
                                    <small>disponibles</small>
                                </div>
                                {{-- RF-03: NO muestra quién tiene prestada la herramienta --}}
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="search-empty">
                                <i class="bi bi-emoji-frown"></i>
                                <h6>No se encontraron resultados</h6>
                                <p>Intenta con otro nombre o revisa la ortografía.</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            @else
                {{-- Estado vacío / sugerencias --}}
                <div class="search-suggestions mt-4">
                    <h6 class="suggestions-title">Categorías disponibles</h6>
                    <div class="row g-3">
                        <div class="col-6 col-md-3">
                            <a href="{{ route('buscar.index', ['q' => 'herramientas']) }}" class="suggestion-card">
                                <i class="bi bi-tools" style="color: var(--success);"></i>
                                <span>Herramientas</span>
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="{{ route('buscar.index', ['q' => 'refacciones']) }}" class="suggestion-card">
                                <i class="bi bi-gear-fill" style="color: var(--accent-blue);"></i>
                                <span>Refacciones</span>
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="{{ route('buscar.index', ['q' => 'limpieza']) }}" class="suggestion-card">
                                <i class="bi bi-droplet-fill" style="color: #a855f7;"></i>
                                <span>Limpieza</span>
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="{{ route('buscar.index', ['q' => 'papeleria']) }}" class="suggestion-card">
                                <i class="bi bi-journal-text" style="color: var(--accent-orange);"></i>
                                <span>Papelería</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>

@endsection

@push('styles')
<style>
    /* ===================== SEARCH HERO ===================== */
    .search-hero {
        text-align: center;
        padding: 2rem 1rem;
        margin-bottom: 1.5rem;
    }

    .search-hero-icon {
        width: 60px;
        height: 60px;
        background-color: rgba(59, 130, 246, 0.12);
        border-radius: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        font-size: 1.5rem;
        color: var(--accent-blue);
    }

    .search-hero-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.35rem;
    }

    .search-hero-sub {
        font-size: 0.85rem;
        color: var(--text-muted);
        margin-bottom: 1.5rem;
    }

    .search-hero-form {
        display: flex;
        gap: 0.5rem;
        max-width: 560px;
        margin: 0 auto;
    }

    .search-input-wrapper {
        flex: 1;
        position: relative;
    }

    .search-input-wrapper > i {
        position: absolute;
        left: 0.9rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        font-size: 1rem;
        pointer-events: none;
    }

    .search-input-wrapper .form-control {
        background-color: var(--bg-card);
        border: 1px solid var(--border-color);
        color: var(--text-primary);
        padding: 0.75rem 2.5rem 0.75rem 2.75rem;
        border-radius: 10px;
        font-size: 0.95rem;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .search-input-wrapper .form-control::placeholder { color: var(--text-muted); }

    .search-input-wrapper .form-control:focus {
        background-color: var(--bg-card);
        border-color: var(--accent-blue);
        color: var(--text-primary);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    }

    .search-clear {
        position: absolute;
        right: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        font-size: 0.85rem;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .search-clear:hover { color: var(--text-primary); }

    .btn-search-submit {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.75rem 1.25rem;
        background: linear-gradient(135deg, var(--accent-blue), var(--accent-blue-hover));
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
        box-shadow: 0 3px 12px rgba(59, 130, 246, 0.25);
        white-space: nowrap;
    }

    .btn-search-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 5px 18px rgba(59, 130, 246, 0.35);
    }

    /* ===================== RESULTS HEADER ===================== */
    .search-results-header {
        font-size: 0.85rem;
        color: var(--text-secondary);
    }

    .search-results-header strong { color: var(--accent-orange); }

    /* ===================== RESULT CARDS ===================== */
    .search-result-card {
        background-color: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 10px;
        padding: 1.15rem;
        height: 100%;
        transition: transform 0.2s ease, border-color 0.2s ease;
    }

    .search-result-card:hover {
        transform: translateY(-2px);
        border-color: rgba(255,255,255,0.1);
    }

    .result-card-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.75rem;
    }

    .result-icon {
        width: 38px;
        height: 38px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }

    .result-icon.herramientas { background-color: rgba(34, 197, 94, 0.15); color: var(--success); }
    .result-icon.refacciones  { background-color: rgba(59, 130, 246, 0.15); color: var(--accent-blue); }
    .result-icon.limpieza     { background-color: rgba(168, 85, 247, 0.15); color: #a855f7; }
    .result-icon.papeleria    { background-color: rgba(249, 115, 22, 0.15); color: var(--accent-orange); }

    .badge-disponible-lg { background-color: rgba(34, 197, 94, 0.15); color: var(--success); font-size: 0.7rem; padding: 0.3em 0.6em; border-radius: 5px; font-weight: 600; }
    .badge-bajo-lg       { background-color: rgba(234, 179, 8, 0.15); color: var(--warning); font-size: 0.7rem; padding: 0.3em 0.6em; border-radius: 5px; font-weight: 600; }
    .badge-agotado-lg    { background-color: rgba(239, 68, 68, 0.2); color: #fca5a5; font-size: 0.7rem; padding: 0.3em 0.6em; border-radius: 5px; font-weight: 600; }

    .result-name {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.15rem;
    }

    .result-cat {
        font-size: 0.75rem;
        color: var(--text-muted);
        display: block;
        margin-bottom: 0.75rem;
    }

    .result-stock {
        display: flex;
        align-items: baseline;
        gap: 0.4rem;
    }

    .result-stock-number {
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--text-primary);
        line-height: 1;
    }

    .result-stock-number.critical {
        color: var(--danger);
        text-shadow: 0 0 8px rgba(239, 68, 68, 0.4);
    }

    .result-stock small { font-size: 0.75rem; color: var(--text-muted); }

    /* ===================== EMPTY STATE ===================== */
    .search-empty {
        text-align: center;
        padding: 3rem 1rem;
        color: var(--text-muted);
    }

    .search-empty i { font-size: 2.5rem; display: block; margin-bottom: 0.75rem; }
    .search-empty h6 { font-size: 1rem; color: var(--text-secondary); margin-bottom: 0.35rem; }
    .search-empty p { font-size: 0.85rem; margin-bottom: 0; }

    /* ===================== SUGGESTIONS ===================== */
    .suggestions-title {
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.75rem;
    }

    .suggestion-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
        padding: 1.25rem 1rem;
        background-color: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 10px;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .suggestion-card:hover {
        border-color: rgba(255,255,255,0.12);
        transform: translateY(-2px);
    }

    .suggestion-card i { font-size: 1.5rem; }
    .suggestion-card span { font-size: 0.8rem; font-weight: 600; color: var(--text-secondary); }

    @media (max-width: 575.98px) {
        .search-hero { padding: 1.25rem 0.5rem; }
        .search-hero-form { flex-direction: column; }
        .btn-search-submit { justify-content: center; }
    }
</style>
@endpush

@push('scripts')
<script>
    // Sidebar toggle
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const toggleBtn = document.getElementById('sidebarToggle');

    function openSidebar() { sidebar.classList.add('show'); overlay.classList.add('show'); document.body.style.overflow = 'hidden'; }
    function closeSidebar() { sidebar.classList.remove('show'); overlay.classList.remove('show'); document.body.style.overflow = ''; }

    toggleBtn.addEventListener('click', function() { sidebar.classList.contains('show') ? closeSidebar() : openSidebar(); });
    overlay.addEventListener('click', closeSidebar);
    window.addEventListener('resize', function() { if (window.innerWidth >= 992) closeSidebar(); });
</script>
@endpush
