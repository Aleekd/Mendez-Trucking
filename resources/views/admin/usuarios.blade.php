@extends('layouts.app')

@section('title', 'Gestión de Usuarios')

@section('content')

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    @include('partials.sidebar', ['active' => 'usuarios'])

    <div class="main-content">
        
        <header class="top-navbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn-sidebar-toggle" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                <div class="top-navbar-title">
                    <h5>Usuarios</h5>
                    <small>Control de acceso</small>
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

        <div class="container-fluid py-4 px-md-4">
            
            <div class="row mb-4 align-items-end">
                <div class="col-12">
                    <h2 class="h3 fw-bold text-white mb-1">Gestión de Usuarios</h2>
                    <p class="text-secondary mb-0 small">Administra los accesos y roles del personal de la planta.</p>
                </div>
            </div>

            <div class="row g-3 mb-4 align-items-center">
                <div class="col-12 col-md-5 col-lg-6">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" id="searchInput" class="form-control" placeholder="Buscar por nombre...">
                    </div>
                </div>
                <div class="col-12 col-md-3 col-lg-3">
                    <select class="form-select" id="roleFilter">
                        <option value="">Todos los Roles</option>
                        <option value="admin">Administrador</option>
                        <option value="mecanico">Mecánico</option>
                        <option value="almacen">Almacén</option>
                    </select>
                </div>
                <div class="col-12 col-md-4 col-lg-3 text-md-end">
                    <button class="btn btn-action-primary w-100 w-md-auto" data-bs-toggle="modal" data-bs-target="#modalCrearUsuario">
                        <i class="bi bi-plus-lg me-2"></i> Nuevo Usuario
                    </button>
                </div>
            </div>

            <div class="section-card">
                <div class="table-responsive">
                    <table class="table table-dark-custom align-middle mb-0">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 50%;">Nombre</th>
                                <th scope="col" style="width: 30%;">Rol</th>
                                <th class="text-center" scope="col" style="width: 20%;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="userTableBody">
                            @forelse($users ?? [] as $user)
                            <tr class="user-row" data-role="{{ strtolower($user->role) }}">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-frame me-3 d-flex align-items-center justify-content-center fw-bold" 
                                             style="background-color: var(--bg-primary); color: var(--text-muted);">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                        <span class="fw-bold text-white">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td>
                                    @if(strtolower($user->role) == 'admin')
                                        <span class="badge badge-industrial badge-admin">ADMIN</span>
                                    @elseif(strtolower($user->role) == 'mecanico')
                                        <span class="badge badge-industrial badge-mecanico">MECÁNICO</span>
                                    @elseif(strtolower($user->role) == 'almacen')
                                        <span class="badge badge-industrial badge-almacen">ALMACÉN</span>
                                    @else
                                        <span class="badge badge-industrial badge-mecanico">{{ strtoupper($user->role) }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="action-btn" title="Editar"><i class="bi bi-pencil-square"></i></button>
                                        <button class="action-btn delete" title="Eliminar"><i class="bi bi-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-5 text-muted">
                                    <i class="bi bi-people" style="font-size: 2rem; display: block; margin-bottom: 10px; opacity: 0.5;"></i>
                                    No se encontraron usuarios.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="pagination-container px-4 py-3 d-flex align-items-center justify-content-between">
                    <span class="small text-secondary fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">MOSTRANDO {{ count($users ?? []) }} USUARIOS</span>
                    <div class="btn-group" role="group">
                        <button class="btn btn-sm btn-dark border-secondary text-muted" disabled>Anterior</button>
                        <button class="btn btn-sm btn-dark border-secondary text-muted">Siguiente</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalCrearUsuario" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background-color: var(--bg-card); border: 1px solid var(--border-color); color: var(--text-primary);">
                <div class="modal-header" style="border-bottom: 1px solid var(--border-color);">
                    <h6 class="modal-title fw-bold">Nuevo Usuario</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="#" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small ">Nombre</label>
                            <input type="text" class="form-control" style="background: var(--bg-input); border-color: var(--border-color); color: white;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small ">Rol</label>
                            <select class="form-select" style="background: var(--bg-input); border-color: var(--border-color); color: white;">
                                <option value="mecanico">Mecánico</option>
                                <option value="admin">Administrador</option>
                                <option value="almacen">Almacén</option>
                            </select>
                        </div>
                        <button type="submit" class="btn-action-primary w-100 mt-2">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
<style>
    /* ===================== ESTILOS ESPECÍFICOS DE ESTA VISTA ===================== */
    /* Boton de agregar*/
    /* ===================== BOTÓN NARANJA DE ALTO CONTRASTE ===================== */
    .btn-action-primary {
        /* Degradado naranja para que se vea vivo */
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%) !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        color: #ffffff !important;
        
        /* Tipografía fuerte */
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.85rem;
        
        /* Espaciado */
        padding: 0.7rem 1.5rem;
        border-radius: 6px;
        
        /* EFECTO GLOW (Resplandor) para que destaque en lo oscuro */
        box-shadow: 0 4px 15px rgba(249, 115, 22, 0.35);
        transition: all 0.3s ease;
    }

    .btn-action-primary:hover {
        /* Al pasar el mouse, sube un poco y brilla más */
        background: linear-gradient(135deg, #fb923c 0%, #f97316 100%) !important;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(249, 115, 22, 0.6);
        color: #fff !important;
    }

    .btn-action-primary:active {
        transform: translateY(0);
        box-shadow: 0 2px 10px rgba(249, 115, 22, 0.4);
    }
    
    /* Ajuste para el icono dentro del botón */
    .btn-action-primary i {
        font-size: 1.1em;
        vertical-align: text-bottom;
    }
    /* Input Group Oscuro */
    .input-group-text {
        background-color: var(--bg-input);
        border: 1px solid var(--border-color);
        color: var(--text-muted);
    }
    
    .form-control, .form-select {
        background-color: var(--bg-input);
        border: 1px solid var(--border-color);
        color: var(--text-primary);
    }
    
    .form-control:focus, .form-select:focus {
        background-color: var(--bg-input);
        border-color: var(--accent-orange);
        color: var(--text-primary);
        box-shadow: 0 0 0 0.25rem rgba(249, 115, 22, 0.15);
    }

    /* Tabla Industrial */
    .table-dark-custom {
        margin-bottom: 0;
        border-color: var(--border-color);
    }
    .table-dark-custom thead th {
        background-color: rgba(13, 17, 23, 0.5);
        color: var(--text-muted);
        text-transform: uppercase;
        font-size: 0.7rem;
        font-weight: 800;
        letter-spacing: 0.1em;
        padding: 12px 16px;
        border-bottom: 2px solid var(--border-color);
    }
    .table-dark-custom tbody td {
        background-color: transparent; /* Usa el fondo de la card */
        color: var(--text-primary);
        padding: 12px 16px;
        border-bottom: 1px solid var(--border-color);
    }
    .table-dark-custom tbody tr:hover td {
        background-color: rgba(255, 255, 255, 0.03);
    }

    /* Badges Industriales */
    .badge-industrial {
        font-weight: 800;
        padding: 0.4em 0.8em;
        font-size: 0.7rem;
        letter-spacing: 0.5px;
    }
    .badge-admin {
        background-color: rgba(249, 115, 22, 0.15);
        color: #f97316;
        border: 1px solid rgba(249, 115, 22, 0.5);
    }
    .badge-mecanico {
        background-color: rgba(139, 148, 158, 0.15);
        color: #8b949e;
        border: 1px solid rgba(139, 148, 158, 0.5);
    }
    .badge-almacen {
        background-color: rgba(56, 139, 253, 0.15);
        color: #58a6ff;
        border: 1px solid rgba(56, 139, 253, 0.5);
    }

    /* Botones de Acción */
    .action-btn {
        background: none;
        border: none;
        color: var(--text-muted);
        padding: 6px;
        transition: all 0.2s;
        border-radius: 4px;
    }
    .action-btn:hover {
        color: var(--accent-orange);
        background-color: rgba(249, 115, 22, 0.1);
    }
    .action-btn.delete:hover {
        color: #ef4444;
        background-color: rgba(239, 68, 68, 0.1);
    }

    /* Footer */
    .pagination-container {
        border-top: 1px solid var(--border-color);
        background-color: rgba(0,0,0,0.2);
    }

    /* Avatar Frame */
    .avatar-frame {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        border: 2px solid var(--border-color);
        object-fit: cover;
    }
</style>
@endpush

@push('scripts')
<script>
    // Filtro simple por Rol
    document.getElementById('roleFilter').addEventListener('change', function() {
        let role = this.value.toLowerCase();
        let rows = document.querySelectorAll('#userTableBody tr');
        rows.forEach(row => {
            let rowRole = row.getAttribute('data-role');
            row.style.display = (role === '' || rowRole === role) ? '' : 'none';
        });
    });

    // Búsqueda simple
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let val = this.value.toLowerCase();
        document.querySelectorAll('#userTableBody tr').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(val) ? '' : 'none';
        });
    });
</script>
@endpush