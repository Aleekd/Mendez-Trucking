<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Méndez Trucking')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<style>
    :root {
        --bg-primary: #0f1923;
        --bg-secondary: #1a2332;
        --bg-card: #1e2d3d;
        --bg-input: #152231;
        --border-color: #2a3f55;
        --text-primary: #e8edf2;
        --text-secondary: #8899aa;
        --text-muted: #5a6f82;
        --accent-orange: #f97316;
        --accent-orange-hover: #ea580c;
        --accent-blue: #3b82f6;
        --accent-blue-hover: #2563eb;
        --danger: #ef4444;
        --success: #22c55e;
        --warning: #eab308;
        --sidebar-width: 260px;
    }

    body {
        background-color: var(--bg-primary);
        color: var(--text-primary);
        font-family: 'Segoe UI', system-ui, sans-serif;
        overflow-x: hidden;
    }

    /* ===================== SIDEBAR GLOBAL ===================== */
    .sidebar {
        position: fixed; top: 0; left: 0;
        width: var(--sidebar-width); height: 100vh;
        background-color: var(--bg-secondary);
        border-right: 1px solid var(--border-color);
        display: flex; flex-direction: column;
        z-index: 1040; transition: transform 0.3s ease;
    }

    .sidebar-brand {
        padding: 1.25rem; border-bottom: 1px solid var(--border-color);
        display: flex; align-items: center; gap: 0.75rem;
    }

    .sidebar-brand-icon {
        width: 40px; height: 40px;
        background: linear-gradient(135deg, var(--accent-orange), var(--accent-orange-hover));
        border-radius: 10px; display: flex; align-items: center; justify-content: center;
        box-shadow: 0 2px 8px rgba(249, 115, 22, 0.25);
    }
    .sidebar-brand-icon i { font-size: 1.15rem; color: #fff; }

    .sidebar-brand-text h6 { margin: 0; font-size: 0.95rem; font-weight: 700; color: var(--text-primary); }
    .sidebar-brand-text small { font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; }

    .sidebar-nav { flex: 1; overflow-y: auto; padding: 0.75rem 0; }

    .sidebar-section-label {
        font-size: 0.65rem; font-weight: 700; color: var(--text-muted);
        text-transform: uppercase; letter-spacing: 1px; padding: 1rem 1.25rem 0.4rem;
    }

    .sidebar-link {
        display: flex; align-items: center; gap: 0.75rem; padding: 0.6rem 1.25rem;
        color: var(--text-secondary); text-decoration: none; font-size: 0.875rem; font-weight: 500;
        border-left: 3px solid transparent; transition: all 0.2s ease;
    }
    .sidebar-link:hover { color: var(--text-primary); background-color: rgba(255, 255, 255, 0.04); }
    .sidebar-link.active {
        color: var(--accent-orange); background-color: rgba(249, 115, 22, 0.08);
        border-left-color: var(--accent-orange);
    }
    .sidebar-link i { font-size: 1.1rem; width: 20px; text-align: center; }

    .sidebar-footer { padding: 1rem 1.25rem; border-top: 1px solid var(--border-color); }

    .btn-logout {
        display: flex; align-items: center; gap: 0.6rem; width: 100%;
        padding: 0.6rem 0.85rem; background-color: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.2); color: #fca5a5;
        border-radius: 8px; font-size: 0.85rem; font-weight: 600; cursor: pointer;
    }
    .btn-logout:hover { background-color: rgba(239, 68, 68, 0.2); color: #fecaca; }

    /* ===================== LAYOUT PRINCIPAL ===================== */
    .main-content { margin-left: var(--sidebar-width); min-height: 100vh; }
    
    .top-navbar {
        background-color: var(--bg-secondary); border-bottom: 1px solid var(--border-color);
        padding: 0.75rem 1.5rem; display: flex; align-items: center; justify-content: space-between;
        position: sticky; top: 0; z-index: 1030;
    }
    
    .btn-sidebar-toggle {
        display: none; background: none; border: 1px solid var(--border-color);
        color: var(--text-secondary); padding: 0.4rem 0.6rem; border-radius: 6px;
    }

    .top-navbar-title h5 { margin: 0; font-size: 1.05rem; font-weight: 700; }
    .top-navbar-title small { font-size: 0.75rem; color: var(--text-muted); }

    .user-avatar {
        width: 34px; height: 34px; border-radius: 8px; background-color: var(--accent-blue);
        display: flex; align-items: center; justify-content: center; font-weight: 700; color: #fff;
    }

    .page-body { padding: 1.5rem; }

    /* Overlay Móvil */
    .sidebar-overlay {
        display: none; position: fixed; inset: 0;
        background-color: rgba(0, 0, 0, 0.6); z-index: 1035;
    }
    .sidebar-overlay.show { display: block; }

    /* Responsivo */
    @media (max-width: 991.98px) {
        .sidebar { transform: translateX(-100%); }
        .sidebar.show { transform: translateX(0); }
        .main-content { margin-left: 0; }
        .btn-sidebar-toggle { display: block; }
    }
    
    /* Estilos Genéricos para Tarjetas y Tablas */
    .section-card { background-color: var(--bg-card); border: 1px solid var(--border-color); border-radius: 10px; overflow: hidden; }
    .section-card-header { padding: 1rem 1.25rem; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; }
    .section-card-header h6 { margin:0; font-weight: 700; display:flex; gap:0.5rem; align-items:center; }
    
    .table-dark-custom { margin:0; color: var(--text-secondary); font-size: 0.85rem; width: 100%; }
    .table-dark-custom th { background-color: rgba(15, 25, 35, 0.5); border-bottom: 1px solid var(--border-color); padding: 0.7rem 1rem; text-transform: uppercase; font-size: 0.7rem; color: var(--text-muted); }
    .table-dark-custom td { padding: 0.7rem 1rem; border-bottom: 1px solid rgba(42, 63, 85, 0.4); vertical-align: middle; }
</style>
    @stack('styles')
</head>
<body>
    @yield('content')

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const toggleBtn = document.getElementById('sidebarToggle');

        if(toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                sidebar.classList.toggle('show');
                if(overlay) overlay.classList.toggle('show');
            });
        }

        if(overlay) {
            overlay.addEventListener('click', function() {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            });
        }
    });
</script>
</body>
</html>