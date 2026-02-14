@extends('layouts.app')

@section('title', 'Configuración del Sistema')

@section('content')

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    @include('partials.sidebar', ['active' => 'configuracion'])

    <div class="main-content">
        
        <header class="top-navbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn-sidebar-toggle" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                <div class="top-navbar-title">
                    <h5>Configuración</h5>
                    <small>Ajustes de cuenta y perfil</small>
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
            
            <div class="d-flex align-items-center gap-2 text-muted small text-uppercase fw-bold mb-4" style="font-size: 0.75rem; letter-spacing: 1px;">
                <a href="{{ route('dashboard') }}" class="text-decoration-none text-muted hover-orange">Inicio</a>
                <i class="bi bi-chevron-right" style="font-size: 0.7rem;"></i>
                <span class="text-white-50">Configuración</span>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="d-flex flex-column flex-md-row align-items-md-end justify-content-between gap-3 mb-4">
                        <div>
                            <h2 class="fw-bold text-white mb-1">Mi Perfil</h2>
                            <p class="text-muted mb-0 small">Administre su información personal y seguridad.</p>
                        </div>
                        <button type="submit" form="configForm" class="btn-action-primary d-flex align-items-center gap-2">
                            <i class="bi bi-save"></i> Guardar Cambios
                        </button>
                    </div>
                </div>
            </div>

            <form id="configForm" action="#" method="POST">
                @csrf
                <div class="row justify-content-center">
                    
                    <div class="col-lg-8">
                        
                        <div class="section-card mb-4 overflow-hidden">
                            <div class="section-card-header p-4 border-bottom border-dark" style="background-color: rgba(0,0,0,0.2);">
                                <h6 class="mb-0 fw-bold text-white text-uppercase d-flex align-items-center gap-2" style="font-size: 0.85rem; letter-spacing: 1px;">
                                    <i class="bi bi-person-fill text-primary-custom"></i> Información de Cuenta
                                </h6>
                            </div>
                            <div class="section-card-body p-4">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label label-industrial">Nombre Completo</label>
                                        <input type="text" class="form-control input-industrial" value="{{ Auth::user()->name ?? 'Admin Méndez' }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label label-industrial">Correo Electrónico</label>
                                        <input type="email" class="form-control input-industrial" value="{{ Auth::user()->email ?? 'admin@mendeztrucking.com' }}">
                                    </div>
                                    
                                    <div class="col-12 mt-4">
                                        <h6 class="text-primary-custom fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">
                                            <i class="bi bi-shield-lock me-1"></i> Seguridad y Contraseña
                                        </h6>
                                        <hr class="border-secondary opacity-25 my-3">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label label-industrial">Contraseña Actual</label>
                                        <input type="password" class="form-control input-industrial" placeholder="••••••••">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label label-industrial">Nueva Contraseña</label>
                                        <input type="password" class="form-control input-industrial" placeholder="Mín. 8 caracteres">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label label-industrial">Confirmar Nueva</label>
                                        <input type="password" class="form-control input-industrial" placeholder="Repetir contraseña">
                                    </div>

                                    <div class="col-12 mt-4 pt-3 border-top border-secondary border-opacity-25">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-upload-box d-flex align-items-center justify-content-center">
                                                <i class="bi bi-camera text-muted fs-4"></i>
                                            </div>
                                            <div>
                                                <button type="button" class="btn btn-link text-primary-custom text-decoration-none fw-bold text-uppercase p-0 mb-1" style="font-size: 0.75rem;">
                                                    Subir nueva foto
                                                </button>
                                                <p class="text-muted small mb-0" style="font-size: 0.7rem;">JPG, PNG o GIF. Máx 2MB.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </form>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <footer class="mt-4 py-4 border-top border-secondary border-opacity-25 d-flex flex-column flex-md-row justify-content-between align-items-center text-muted small text-uppercase" style="font-size: 0.7rem; letter-spacing: 1px;">
                        <span>© 2024 Méndez Trucking - Logistics System v1.0</span>
                    </footer>
                </div>
            </div>

        </div>
    </div>

@endsection

@push('styles')
<style>
    /* Estilos Industriales para Formularios */
    .label-industrial {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--text-muted);
        margin-bottom: 0.5rem;
    }

    .input-industrial {
        background-color: var(--bg-input);
        border: 1px solid var(--border-color);
        color: var(--text-primary);
        font-size: 0.85rem;
        padding: 0.6rem 0.9rem;
        border-radius: 6px;
    }
    
    .input-industrial:focus {
        background-color: var(--bg-input);
        border-color: var(--accent-orange);
        color: var(--text-primary);
        box-shadow: 0 0 0 2px rgba(249, 115, 22, 0.15);
    }

    /* Caja para subir avatar */
    .avatar-upload-box {
        width: 60px;
        height: 60px;
        background-color: rgba(255,255,255,0.03);
        border: 1px dashed var(--border-color);
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .avatar-upload-box:hover {
        border-color: var(--accent-orange);
        background-color: rgba(249, 115, 22, 0.05);
    }

    .hover-white:hover { color: white !important; }
    .hover-orange:hover { color: var(--accent-orange) !important; }
    
    .text-primary-custom { color: var(--accent-orange); }
</style>
@endpush

@push('scripts')
<script>
    // Simulación visual de guardado
    document.getElementById('configForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = this.querySelector('button[type="submit"]'); // Busca el botón dentro del form si está ahí
        // O busca el botón fuera del form por ID si fuera necesario, pero aquí usaremos el del header
        const mainBtn = document.querySelector('button[form="configForm"]');
        
        const originalContent = mainBtn.innerHTML;
        
        mainBtn.disabled = true;
        mainBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Guardando...';
        
        setTimeout(() => {
            mainBtn.innerHTML = '<i class="bi bi-check-lg me-2"></i> ¡Guardado!';
            mainBtn.classList.remove('btn-action-primary');
            mainBtn.classList.add('btn-success'); // Asegúrate de tener estilo para btn-success o usa bootstrap default
            mainBtn.style.backgroundColor = '#198754';
            mainBtn.style.borderColor = '#198754';
            mainBtn.style.color = '#fff';
            
            setTimeout(() => {
                mainBtn.innerHTML = originalContent;
                mainBtn.classList.remove('btn-success');
                mainBtn.classList.add('btn-action-primary');
                mainBtn.style = ''; // Limpiar estilos inline
                mainBtn.disabled = false;
            }, 2000);
        }, 1200);
    });
</script>
@endpush