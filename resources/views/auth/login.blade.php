<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Méndez Trucking — Iniciar Sesión</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        /* =============================================
           MÉNDEZ TRUCKING — LOGIN
           Estilo: Industrial Dark Mode
           Paleta: #0f1923 · #1a2332 · #3b82f6 · #f97316
           ============================================= */

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
        }

        * {
            box-sizing: border-box;
        }

        body {
            background-color: var(--bg-primary);
            color: var(--text-primary);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 1rem;
            position: relative;
            overflow: hidden;
        }

        /* ---- Ambient background glow ---- */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                radial-gradient(ellipse at 20% 50%, rgba(59, 130, 246, 0.06) 0%, transparent 60%),
                radial-gradient(ellipse at 80% 20%, rgba(249, 115, 22, 0.04) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }

        /* ---- Subtle grid pattern ---- */
        body::after {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(42, 63, 85, 0.15) 1px, transparent 1px),
                linear-gradient(90deg, rgba(42, 63, 85, 0.15) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
            z-index: 0;
        }

        .login-wrapper {
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 1;
        }

        .login-card {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 2rem 1.75rem;
            box-shadow:
                0 4px 6px rgba(0, 0, 0, 0.3),
                0 20px 40px rgba(0, 0, 0, 0.2);
        }

        /* ---- Brand Header ---- */
        .brand-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .brand-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, var(--accent-orange), var(--accent-orange-hover));
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            box-shadow: 0 4px 16px rgba(249, 115, 22, 0.3);
        }

        .brand-icon i {
            font-size: 1.75rem;
            color: #fff;
        }

        .brand-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
            letter-spacing: -0.025em;
        }

        .brand-subtitle {
            font-size: 0.85rem;
            color: var(--text-muted);
            font-weight: 400;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        /* ---- Form Styles ---- */
        .form-label-custom {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.4rem;
        }

        .input-group-custom .form-control,
        .input-group-custom .input-group-text {
            background-color: var(--bg-input);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            font-size: 0.95rem;
            padding: 0.7rem 0.9rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .input-group-custom .form-control::placeholder {
            color: var(--text-muted);
        }

        .input-group-custom .form-control:focus {
            background-color: var(--bg-input);
            border-color: var(--border-focus);
            color: var(--text-primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }

        .input-group-custom .form-control:focus + .input-group-text,
        .input-group-custom .form-control:focus ~ .input-group-text {
            border-color: var(--border-focus);
        }

        .input-group-custom .input-group-text {
            color: var(--text-muted);
            border-left: none;
            cursor: pointer;
            transition: color 0.2s ease, border-color 0.2s ease;
        }

        .input-group-custom .input-group-text:hover {
            color: var(--text-secondary);
        }

        .input-group-custom .form-control.is-invalid {
            border-color: var(--danger);
        }

        /* ---- Icon prefix ---- */
        .input-icon-prefix {
            border-right: none;
            border-left: 1px solid var(--border-color);
            cursor: default;
        }

        .input-with-prefix {
            border-left: none;
        }

        .input-with-prefix:focus {
            border-left: none;
        }

        /* ---- Submit Button ---- */
        .btn-login {
            background: linear-gradient(135deg, var(--accent-orange), var(--accent-orange-hover));
            border: none;
            color: #fff;
            font-weight: 600;
            font-size: 0.95rem;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            width: 100%;
            transition: transform 0.15s ease, box-shadow 0.15s ease, opacity 0.15s ease;
            letter-spacing: 0.25px;
        }

        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(249, 115, 22, 0.35);
            color: #fff;
            opacity: 0.95;
        }

        .btn-login:active {
            transform: translateY(0);
            box-shadow: 0 2px 8px rgba(249, 115, 22, 0.3);
        }

        .btn-login:focus-visible {
            outline: 2px solid var(--accent-orange);
            outline-offset: 2px;
        }

        /* ---- Error Alert ---- */
        .alert-login-error {
            background-color: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.25);
            color: #fca5a5;
            border-radius: 8px;
            font-size: 0.875rem;
            padding: 0.75rem 1rem;
        }

        .alert-login-error .bi {
            color: var(--danger);
        }

        .alert-login-error ul {
            margin-bottom: 0;
            padding-left: 1rem;
        }

        .alert-login-error li {
            padding: 0.1rem 0;
        }

        /* ---- Footer ---- */
        .login-footer {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        .login-footer span {
            color: var(--accent-orange);
        }

        /* ---- Divider ---- */
        .divider-line {
            border: none;
            border-top: 1px solid var(--border-color);
            margin: 1.5rem 0;
            opacity: 0.5;
        }

        /* ---- Mobile Responsive ---- */
        @media (max-width: 480px) {
            .login-card {
                padding: 1.5rem 1.25rem;
                border-radius: 10px;
            }

            .brand-icon {
                width: 56px;
                height: 56px;
                border-radius: 14px;
            }

            .brand-icon i {
                font-size: 1.5rem;
            }

            .brand-title {
                font-size: 1.3rem;
            }
        }
    </style>
</head>
<body>

    <div class="login-wrapper">
        <div class="login-card">

            <!-- ========== BRAND HEADER ========== -->
            <div class="brand-header">
                <div class="brand-icon">
                    <i class="bi bi-truck"></i>
                </div>
                <h1 class="brand-title">Méndez Trucking</h1>
                <p class="brand-subtitle">Taller Mecánico &mdash; Sistema Interno</p>
            </div>

            <!-- ========== ERROR MESSAGES (Blade) ========== -->
            @if ($errors->any())
                <div class="alert alert-login-error mb-3" role="alert">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <strong>Error de autenticación</strong>
                    </div>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- ========== LOGIN FORM ========== -->
            <form method="POST" action="{{ route('login') }}" novalidate>
                @csrf

                <!-- Username -->
                <div class="mb-3">
                    <label for="username" class="form-label form-label-custom">
                        Usuario
                    </label>
                    <div class="input-group input-group-custom">
                        <span class="input-group-text input-icon-prefix" id="username-icon">
                            <i class="bi bi-person-fill"></i>
                        </span>
                        <input
                            type="text"
                            class="form-control input-with-prefix @error('username') is-invalid @enderror"
                            id="username"
                            name="username"
                            value="{{ old('username') }}"
                            placeholder="Ingresa tu usuario"
                            autocomplete="username"
                            required
                            autofocus
                            aria-describedby="username-icon"
                        >
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="form-label form-label-custom">
                        Contraseña
                    </label>
                    <div class="input-group input-group-custom">
                        <span class="input-group-text input-icon-prefix" id="password-icon">
                            <i class="bi bi-lock-fill"></i>
                        </span>
                        <input
                            type="password"
                            class="form-control input-with-prefix @error('password') is-invalid @enderror"
                            id="password"
                            name="password"
                            placeholder="Ingresa tu contraseña"
                            autocomplete="current-password"
                            required
                            aria-describedby="password-icon"
                        >
                        <span
                            class="input-group-text"
                            id="togglePassword"
                            role="button"
                            tabindex="0"
                            aria-label="Mostrar u ocultar contraseña"
                            title="Mostrar/Ocultar contraseña"
                        >
                            <i class="bi bi-eye-fill" id="toggleIcon"></i>
                        </span>
                    </div>
                </div>

                <!-- Submit -->
                <button type="submit" class="btn btn-login">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión
                </button>
            </form>

            <hr class="divider-line">

            <!-- Footer -->
            <div class="login-footer">
                <i class="bi bi-shield-lock me-1"></i>
                Acceso restringido &mdash; Solo personal autorizado<br>
                <span>&copy; {{ date('Y') }} Méndez Trucking</span>
            </div>

        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Toggle Password Visibility -->
    <script>
        const toggleBtn = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');

        function toggleVisibility() {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            toggleIcon.classList.toggle('bi-eye-fill', !isPassword);
            toggleIcon.classList.toggle('bi-eye-slash-fill', isPassword);
            toggleBtn.setAttribute(
                'aria-label',
                isPassword ? 'Ocultar contraseña' : 'Mostrar contraseña'
            );
        }

        toggleBtn.addEventListener('click', toggleVisibility);
        toggleBtn.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                toggleVisibility();
            }
        });
    </script>

</body>
</html>
