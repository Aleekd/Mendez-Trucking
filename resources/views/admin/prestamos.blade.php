{{-- resources/views/prestamos.blade.php --}}
@extends('layouts.app')

@section('title', 'Préstamos')

@section('content')

    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    @include('partials.sidebar', ['active' => 'prestamos'])

    <div class="main-content">

        <!-- Top Navbar -->
        <header class="top-navbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn-sidebar-toggle" id="sidebarToggle" aria-label="Abrir menú">
                    <i class="bi bi-list"></i>
                </button>
                <div class="top-navbar-title">
                    <h5>Préstamos</h5>
                    <small>Gestión de préstamos de herramientas</small>
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

            {{-- Mensajes de éxito/error --}}
            @if(session('success'))
                <div class="alert alert-success-custom mb-3 d-flex align-items-center gap-2" role="alert">
                    <i class="bi bi-check-circle-fill"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close btn-close-custom ms-auto" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-error-custom mb-3" role="alert">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <strong>Error</strong>
                    </div>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row g-3">

                {{-- ========== FORMULARIO NUEVO PRÉSTAMO (RF-07) ========== --}}
                <div class="col-lg-4">
                    <div class="section-card h-100">
                        <div class="section-card-header">
                            <h6>
                                <i class="bi bi-plus-circle-fill" style="color: var(--accent-orange);"></i>
                                Nuevo Préstamo
                            </h6>
                        </div>
                        <div class="section-card-body" style="padding: 1.25rem;">
                            <form method="POST" action="{{ route('prestamos') }}">
                                @csrf

                                {{-- Seleccionar Usuario --}}
                                <div class="mb-3">
                                    <label for="user_id" class="form-label form-label-dark">Usuario</label>
                                    <select class="form-select form-select-dark" id="user_id" name="user_id" required>
                                        <option value="" selected disabled>Seleccionar usuario...</option>
                                        @foreach($usuarios as $usuario)
                                            <option value="{{ $usuario->id }}" {{ old('user_id') == $usuario->id ? 'selected' : '' }}>
                                                {{ $usuario->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Seleccionar Herramienta --}}
                                <div class="mb-3">
                                    <label for="herramienta_id" class="form-label form-label-dark">Herramienta</label>
                                    <select class="form-select form-select-dark" id="herramienta_id" name="herramienta_id" required>
                                        <option value="" selected disabled>Seleccionar herramienta...</option>
                                        @foreach($herramientas as $herr)
                                            <option value="{{ $herr->id }}" {{ old('herramienta_id') == $herr->id ? 'selected' : '' }}>
                                                {{ $herr->nombre }} (Disp: {{ $herr->stock_actual }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Cantidad --}}
                                <div class="mb-4">
                                    <label for="cantidad" class="form-label form-label-dark">Cantidad</label>
                                    <input type="number" class="form-control form-control-dark" id="cantidad" name="cantidad" min="1" value="{{ old('cantidad', 1) }}" required>
                                </div>

                                <button type="submit" class="btn-action-primary w-100">
                                    <i class="bi bi-send-fill"></i>
                                    Registrar Préstamo
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- ========== LISTA PRÉSTAMOS ACTIVOS (RF-09) ========== --}}
                <div class="col-lg-8">
                    <div class="section-card h-100">
                        <div class="section-card-header">
                            <h6>
                                <i class="bi bi-arrow-left-right" style="color: var(--accent-blue);"></i>
                                Préstamos Activos
                            </h6>
                            @if(isset($prestamosActivos))
                                <span class="badge bg-primary rounded-pill">{{ count($prestamosActivos) }}</span>
                            @endif
                        </div>
                        <div class="section-card-body">
                            <div class="table-responsive">
                                <table class="table table-dark-custom">
                                    <thead>
                                        <tr>
                                            <th>Usuario</th>
                                            <th>Herramienta</th>
                                            <th>Cant.</th>
                                            <th>Fecha Salida</th>
                                            <th class="text-end">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($prestamosActivos ?? [] as $prestamo)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="user-avatar" style="width:28px;height:28px;font-size:0.65rem;border-radius:6px;">
                                                            {{ strtoupper(substr($prestamo->usuario->name, 0, 1)) }}
                                                        </div>
                                                        {{ $prestamo->usuario->name }}
                                                    </div>
                                                </td>
                                                <td>{{ $prestamo->herramienta->nombre }}</td>
                                                <td><span class="fw-bold">{{ $prestamo->cantidad }}</span></td>
                                                <td>{{ $prestamo->created_at->format('d/m/Y H:i') }}</td>
                                                <td class="text-end">
                                                    <button type="button"
                                                        class="btn-devolver"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalDevolver"
                                                        data-prestamo-id="{{ $prestamo->id }}"
                                                        data-usuario="{{ $prestamo->usuario->name }}"
                                                        data-herramienta="{{ $prestamo->herramienta->nombre }}"
                                                        data-cantidad="{{ $prestamo->cantidad }}">
                                                        <i class="bi bi-arrow-return-left"></i>
                                                        Devolver
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-4" style="color: var(--text-muted);">
                                                    <i class="bi bi-hand-thumbs-up" style="font-size: 2rem; display: block; margin-bottom: 0.5rem; color: var(--success);"></i>
                                                    No hay préstamos activos
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- ========== MODAL DEVOLVER (RF-08) ========== --}}
    <div class="modal fade" id="modalDevolver" tabindex="-1" aria-labelledby="modalDevolverLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-dark">
                <div class="modal-header">
                    <h6 class="modal-title" id="modalDevolverLabel">
                        <i class="bi bi-arrow-return-left" style="color: var(--accent-blue);"></i>
                        Confirmar Devolución
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form method="POST" id="formDevolver" action="">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <div class="devolver-info mb-3">
                            <div class="devolver-info-item">
                                <small>Usuario</small>
                                <strong id="modalUsuario">-</strong>
                            </div>
                            <div class="devolver-info-item">
                                <small>Herramienta</small>
                                <strong id="modalHerramienta">-</strong>
                            </div>
                            <div class="devolver-info-item">
                                <small>Cantidad</small>
                                <strong id="modalCantidad">-</strong>
                            </div>
                        </div>

                        {{-- Checkbox Dañada/Rota (RF-08) --}}
                        <div class="form-check form-check-damage">
                            <input class="form-check-input" type="checkbox" id="danada" name="danada" value="1">
                            <label class="form-check-label" for="danada">
                                <i class="bi bi-exclamation-diamond-fill text-warning me-1"></i>
                                Herramienta dañada o rota
                            </label>
                        </div>

                        <div class="mb-0 mt-3" id="observacionesWrapper" style="display: none;">
                            <label for="observaciones" class="form-label form-label-dark">Observaciones del daño</label>
                            <textarea class="form-control form-control-dark" id="observaciones" name="observaciones" rows="2" placeholder="Describe el daño..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn-action-primary">
                            <i class="bi bi-check-lg"></i>
                            Confirmar Devolución
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('styles')
<style>
    /* ===================== FORM DARK STYLES ===================== */
    .form-label-dark {
        font-size: 0.78rem;
        font-weight: 600;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-bottom: 0.35rem;
    }

    .form-control-dark,
    .form-select-dark {
        background-color: var(--bg-input);
        border: 1px solid var(--border-color);
        color: var(--text-primary);
        font-size: 0.9rem;
        padding: 0.6rem 0.85rem;
        border-radius: 7px;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .form-control-dark::placeholder { color: var(--text-muted); }

    .form-control-dark:focus,
    .form-select-dark:focus {
        background-color: var(--bg-input);
        border-color: var(--accent-blue);
        color: var(--text-primary);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    }

    .form-select-dark option {
        background-color: var(--bg-secondary);
        color: var(--text-primary);
    }

    /* ===================== BTN DEVOLVER ===================== */
    .btn-devolver {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.4rem 0.75rem;
        background-color: rgba(59, 130, 246, 0.12);
        border: 1px solid rgba(59, 130, 246, 0.3);
        color: var(--accent-blue);
        border-radius: 6px;
        font-size: 0.78rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-devolver:hover {
        background-color: rgba(59, 130, 246, 0.2);
        border-color: var(--accent-blue);
    }

    /* ===================== MODAL DARK ===================== */
    .modal-dark {
        background-color: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        color: var(--text-primary);
    }

    .modal-dark .modal-header {
        border-bottom: 1px solid var(--border-color);
        padding: 1rem 1.25rem;
    }

    .modal-dark .modal-title {
        font-size: 0.95rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .modal-dark .modal-body { padding: 1.25rem; }

    .modal-dark .modal-footer {
        border-top: 1px solid var(--border-color);
        padding: 0.85rem 1.25rem;
        display: flex;
        gap: 0.5rem;
        justify-content: flex-end;
    }

    .btn-modal-cancel {
        padding: 0.5rem 1rem;
        background: transparent;
        border: 1px solid var(--border-color);
        color: var(--text-secondary);
        border-radius: 7px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-modal-cancel:hover {
        background-color: rgba(255,255,255,0.05);
        color: var(--text-primary);
    }

    /* ===================== DEVOLVER INFO ===================== */
    .devolver-info {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.75rem;
        background-color: var(--bg-input);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 0.85rem;
    }

    .devolver-info-item small {
        display: block;
        font-size: 0.68rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-bottom: 0.15rem;
    }

    .devolver-info-item strong {
        font-size: 0.85rem;
        color: var(--text-primary);
    }

    /* ===================== CHECKBOX DAMAGE ===================== */
    .form-check-damage {
        background-color: rgba(239, 68, 68, 0.06);
        border: 1px solid rgba(239, 68, 68, 0.15);
        border-radius: 8px;
        padding: 0.75rem 0.85rem 0.75rem 2.25rem;
    }

    .form-check-damage .form-check-input {
        background-color: var(--bg-input);
        border-color: var(--border-color);
        width: 1.1rem;
        height: 1.1rem;
        margin-top: 0.15rem;
    }

    .form-check-damage .form-check-input:checked {
        background-color: var(--danger);
        border-color: var(--danger);
    }

    .form-check-damage .form-check-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    /* Reuse shared styles */
    .alert-success-custom {
        background-color: rgba(34, 197, 94, 0.1);
        border: 1px solid rgba(34, 197, 94, 0.25);
        color: #86efac;
        border-radius: 8px;
        font-size: 0.85rem;
        padding: 0.75rem 1rem;
    }

    .alert-error-custom {
        background-color: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.25);
        color: #fca5a5;
        border-radius: 8px;
        font-size: 0.85rem;
        padding: 0.75rem 1rem;
    }

    .alert-error-custom ul { margin-bottom: 0; padding-left: 1rem; }

    .btn-close-custom {
        filter: invert(1) grayscale(100%) brightness(200%);
        opacity: 0.5;
        font-size: 0.7rem;
    }

    .btn-action-primary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
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
        cursor: pointer;
    }

    .btn-action-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 5px 18px rgba(249, 115, 22, 0.35);
        color: #fff;
    }

    @media (max-width: 575.98px) {
        .devolver-info { grid-template-columns: 1fr; }
    }
</style>
@endpush

@push('scripts')
<script>
    // Llenar datos del modal al hacer click en "Devolver"
    const modalDevolver = document.getElementById('modalDevolver');
    modalDevolver.addEventListener('show.bs.modal', function(event) {
        const btn = event.relatedTarget;
        const prestamoId = btn.getAttribute('data-prestamo-id');

        document.getElementById('modalUsuario').textContent = btn.getAttribute('data-usuario');
        document.getElementById('modalHerramienta').textContent = btn.getAttribute('data-herramienta');
        document.getElementById('modalCantidad').textContent = btn.getAttribute('data-cantidad');

        // Actualizar action del formulario
        const form = document.getElementById('formDevolver');
        form.action = '/prestamos/' + prestamoId + '/devolver';
    });

    // Toggle observaciones si está dañada
    document.getElementById('danada').addEventListener('change', function() {
        document.getElementById('observacionesWrapper').style.display = this.checked ? 'block' : 'none';
    });

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
