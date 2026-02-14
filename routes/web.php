<?php

use Illuminate\Support\Facades\Route;

// --- LOGIN ---
Route::get('/login', function () { return view('auth.login'); })->name('login');
Route::post('/login', function () { return view('auth.login'); })->name('login');
Route::post('/logout', function () { return view('auth.login'); })->name('login');
// --- DASHBOARD (INDEX) ---
Route::get('/dashboard', function () {
    // Definimos datos de prueba como OBJETOS para evitar el error "non-object"
    $stats = (object)[
        'total_herramientas' => 148,
        'herramientas_prestadas' => 12,
        'stock_critico' => 5,
        'movimientos_hoy' => 23
    ];

    // RF-06: Alertas de Stock Crítico
    $alertas = collect([
        (object)['nombre' => 'Aceite 10W-40', 'stock_actual' => 2, 'stock_minimo' => 10, 'categoria' => 'Refacciones'],
        (object)['nombre' => 'Filtro de aire', 'stock_actual' => 1, 'stock_minimo' => 5, 'categoria' => 'Refacciones'],
    ]);

    // RF-10: Log de Movimientos
    $movimientos = collect([
        (object)['usuario' => 'Juan Pérez', 'accion' => 'Préstamo', 'objeto' => 'Llave de torque', 'hora' => '10:30 AM'],
        (object)['usuario' => 'Miguel G.', 'accion' => 'Consumo', 'objeto' => 'Aceite 10W-40', 'hora' => '11:15 AM'],
    ]);

    return view('admin.index', compact('stats', 'alertas', 'movimientos'));
})->name('dashboard');

// --- VISTA DE INVENTARIO ---
Route::get('/inventario', function () {
    
    // TRUCO: Creamos un usuario "falso" en memoria para que la vista no falle
    // y puedas ver los botones de Admin (Agregar/Editar)
    $adminUser = new \App\Models\User();
    $adminUser->name = 'Carlos Méndez';
    $adminUser->role = 'admin'; // <--- Esto activa los permisos en el Blade
    \Illuminate\Support\Facades\Auth::setUser($adminUser);

    // Datos de prueba para el Inventario (RF-04)
    $articulos = collect([
        (object)['id' => 1, 'nombre' => 'Llave de Impacto 1/2"', 'categoria' => 'herramientas', 'stock_actual' => 5, 'stock_minimo' => 2, 'descripcion' => 'Marca Dewalt'],
        (object)['id' => 2, 'nombre' => 'Aceite 10W-40', 'categoria' => 'refacciones', 'stock_actual' => 20, 'stock_minimo' => 10, 'descripcion' => 'Botellas 1L'],
        (object)['id' => 3, 'nombre' => 'Trapo Industrial', 'categoria' => 'limpieza', 'stock_actual' => 50, 'stock_minimo' => 15, 'descripcion' => 'Kg'],
        (object)['id' => 4, 'nombre' => 'Hojas Blancas', 'categoria' => 'papeleria', 'stock_actual' => 500, 'stock_minimo' => 100, 'descripcion' => 'Paquete'],
    ]);

    // Simulamos paginación
    $articulos = new \Illuminate\Pagination\LengthAwarePaginator(
        $articulos, $articulos->count(), 15, 1, ['path' => url('/inventario')]
    );

    // Variables para el Sidebar (evita errores en el menú)
    $prestamosActivos = 3;
    $alertas = collect([]);

    return view('admin.inventario', compact('articulos', 'prestamosActivos', 'alertas'));
})->name('inventario.index');



// Ejemplo para Buscar (Ruta común)
Route::get('/buscar', function () {
    return view('common.buscar');
})->name('buscar');


// --- VISTA DE PRÉSTAMOS ---
Route::get('/prestamos', function () {
    // 1. Datos para el selector de USUARIOS (RF-07)
    $usuarios = collect([
        (object)['id' => 1, 'name' => 'Roberto Luna', 'role' => 'Mecánico'],
        (object)['id' => 2, 'name' => 'Miguel Gallegos', 'role' => 'Mecánico'],
        (object)['id' => 3, 'name' => 'Ana Soto', 'role' => 'Almacén'],
    ]);

    // 2. Datos para el selector de HERRAMIENTAS (RF-04)
    // CORRECCIÓN: Renombrado de $loanableItems a $herramientas
    $herramientas = collect([
        (object)['id' => 101, 'nombre' => 'Llave de Impacto 1/2"', 'stock_actual' => 5],
        (object)['id' => 102, 'nombre' => 'Gato Hidráulico 3T', 'stock_actual' => 2],
        (object)['id' => 103, 'nombre' => 'Scanner OBD2', 'stock_actual' => 1],
    ]);

    // 3. Datos para la tabla de PRÉSTAMOS ACTIVOS (RF-09)
    // CORRECCIÓN: Renombrado de $activeLoans a $prestamosActivos
    $prestamosActivos = collect([
        (object)[
            'id' => 1,
            'cantidad' => 1,
            'created_at' => now()->subHours(2), // Hace 2 horas
            'usuario' => (object)['name' => 'Roberto Luna'],
            'herramienta' => (object)['nombre' => 'Llave de Impacto 1/2"']
        ],
        (object)[
            'id' => 2,
            'cantidad' => 2,
            'created_at' => now()->subMinutes(45), // Hace 45 min
            'usuario' => (object)['name' => 'Miguel Gallegos'],
            'herramienta' => (object)['nombre' => 'Torquímetro Digital']
        ]
    ]);

    return view('admin.prestamos', compact('usuarios', 'herramientas', 'prestamosActivos'));
})->name('prestamos'); // Nombre corto para evitar errores

// --- VISTA DE USUARIOS (RF-01) ---
Route::get('/usuarios', function () {
    // Datos de prueba para que la tabla no esté vacía
    $users = collect([
        (object)[
            'name' => 'Carlos Méndez', 
            'role' => 'admin', 
            'email' => 'admin@mendez.com', 
            'created_at' => now()->subMonths(6)
        ],
        (object)[
            'name' => 'Roberto Luna', 
            'role' => 'operador', 
            'email' => 'roberto@mendez.com', 
            'created_at' => now()->subDays(15)
        ],
        (object)[
            'name' => 'Miguel Gallegos', 
            'role' => 'operador', 
            'email' => 'miguel@mendez.com', 
            'created_at' => now()->subDays(2)
        ],
    ]);

    return view('admin.usuarios', compact('users'));
})->name('usuarios.index'); // <--- ¡ESTE NOMBRE ES LA CLAVE!


// --- VISTA DE HISTORIAL (RF-10) ---
Route::get('/historial', function () {
    // Datos de prueba simulando una consulta mixta (Préstamos + Consumos)
    $movimientos = collect([
        (object)[
            'id' => 1,
            'tipo' => 'prestamo', // prestamo, devolucion, consumo, daño
            'usuario' => 'Carlos Méndez',
            'item' => 'Scanner OBD2',
            'cantidad' => 1,
            'fecha' => now()->subMinutes(10),
            'detalles' => 'Para diagnóstico unidad 45'
        ],
        (object)[
            'id' => 2,
            'tipo' => 'consumo',
            'usuario' => 'Miguel Gallegos',
            'item' => 'Aceite 10W-40 (Litro)',
            'cantidad' => 3,
            'fecha' => now()->subHours(2),
            'detalles' => 'Cambio de aceite Express'
        ],
        (object)[
            'id' => 3,
            'tipo' => 'devolucion',
            'usuario' => 'Roberto Luna',
            'item' => 'Gato Hidráulico 3T',
            'cantidad' => 1,
            'fecha' => now()->subDay(),
            'detalles' => 'Entregado en buen estado'
        ],
        (object)[
            'id' => 4,
            'tipo' => 'dano',
            'usuario' => 'Roberto Luna',
            'item' => 'Llave de Cruz',
            'cantidad' => 1,
            'fecha' => now()->subDays(2),
            'detalles' => 'Se barrió el dado de 1/2"'
        ],
        (object)[
            'id' => 5,
            'tipo' => 'prestamo',
            'usuario' => 'Roberto Luna',
            'item' => 'Gato Hidráulico 3T',
            'cantidad' => 1,
            'fecha' => now()->subDays(3),
            'detalles' => '-'
        ],
    ]);

    return view('admin.historial', compact('movimientos'));
})->name('historial.index');

// --- VISTA DE ALERTAS (RF-06) ---
Route::get('/alertas', function () {
    // Simulamos artículos que están sufriendo (Stock bajo o cero)
    $alertas = collect([
        (object)[
            'id' => 1,
            'nombre' => 'Aceite 10W-40',
            'categoria' => 'refacciones',
            'stock_actual' => 0,    // ¡CRÍTICO!
            'stock_minimo' => 10,
            'descripcion' => 'Garrafa de 5L'
        ],
        (object)[
            'id' => 2,
            'nombre' => 'Filtro de Aire T680',
            'categoria' => 'refacciones',
            'stock_actual' => 1,    // ¡MUY BAJO!
            'stock_minimo' => 5,
            'descripcion' => 'Kenworth original'
        ],
        (object)[
            'id' => 3,
            'nombre' => 'Guantes de Nitrilo',
            'categoria' => 'limpieza',
            'stock_actual' => 2,    // BAJO
            'stock_minimo' => 20,
            'descripcion' => 'Caja con 100 pares'
        ],
        (object)[
            'id' => 4,
            'nombre' => 'Llave 10mm',
            'categoria' => 'herramientas',
            'stock_actual' => 0,    // PERDIDA/ROTA
            'stock_minimo' => 3,
            'descripcion' => 'Crome Vanadium'
        ],
    ]);

    // Calculamos métricas para las tarjetas de arriba
    $totalAlertas = $alertas->count();
    $criticos = $alertas->where('stock_actual', 0)->count();
    $bajos = $alertas->where('stock_actual', '>', 0)->count();

    return view('admin.alertas', compact('alertas', 'totalAlertas', 'criticos', 'bajos'));
})->name('alertas.index');

Route::get('/buscar', function () {
    return view('common.buscar', ['items' => []]); 
})->name('buscar.index');


// --- CONFIGURACIÓN ---
Route::get('/configuracion', function () {
    // Aquí podrías cargar datos reales de la DB en el futuro
    // Por ahora, solo necesitamos pasar las variables globales para el sidebar
    $prestamosActivos = 3; 
    $alertas = collect([1,2]); // Solo para que aparezca el badge en el menú

    return view('admin.configuracion', compact('prestamosActivos', 'alertas'));
})->name('configuracion');



Route::get('/consumos/registrar', function () { return "Consumos"; })->name('consumos.crear');
Route::get('/consumos/create', function () { return "Consumos Create"; })->name('consumos.create');