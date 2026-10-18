<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\LoginController;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Helpdesk\NewTicket as HelpdeskNewTicket;
use App\Livewire\Helpdesk\Tickets as HelpdeskTickets;
use App\Livewire\Itam\Dashboard as ItamDashboard;
use App\Livewire\Portal\Maintenance as ModuleMaintenance;
use App\Livewire\Portal\ModuleLauncher;
use App\Livewire\Transportes\Dashboard as TransportesDashboard;
use App\Models\Itam\Asset;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - SIGM Municipalidad Distrital de Castilla
|--------------------------------------------------------------------------
*/

// Rutas de Autenticación Institucional Segura (POST /login sin exponer credenciales en URL)
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('/login', [LoginController::class, 'showLoginForm']);
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout.post');

// Consulta Pública de DNI y Códigos QR de Oficinas para Mesa de Ayuda
Route::get('/api/helpdesk/dni-lookup', [LoginController::class, 'lookupDni'])->name('helpdesk.dni.lookup');
Route::get('/api/helpdesk/office-qr', [LoginController::class, 'officeTicketQr'])->name('helpdesk.office.qr');

// Portal Lanzador Institucional de Módulos (Livewire 3)
Route::get('/portal', ModuleLauncher::class)->name('portal.launcher');

// Alias /launcher por conveniencia
Route::get('/launcher', ModuleLauncher::class)->name('portal.home');

// Portal de Tickets de Soporte TI
Route::get('/soporte', HelpdeskTickets::class)->name('soporte.portal');
Route::get('/soporte/nuevo-ticket', HelpdeskNewTicket::class)->name('soporte.nuevo');
Route::get('/mantenimiento', ModuleMaintenance::class)->name('portal.maintenance');

// Rutas del Subsistema Central de Administración
Route::get('/admin', AdminDashboard::class)->name('subsystems.central');
Route::get('/admin/personas', AdminDashboard::class)->name('admin.people');
Route::get('/admin/usuarios', AdminDashboard::class)->name('admin.users');
Route::get('/admin/roles', AdminDashboard::class)->name('admin.roles');
Route::get('/admin/permisos', AdminDashboard::class)->name('admin.permissions');
Route::get('/admin/asignar-permisos', AdminDashboard::class)->name('admin.matrix');
Route::get('/admin/oficinas', AdminDashboard::class)->name('admin.offices');
Route::get('/admin/subsistemas', AdminDashboard::class)->name('admin.subsystems');

// Rutas del Subsistema ITAM (Gestión de Infraestructura y Activos TI)
Route::get('/itam', ItamDashboard::class)->name('subsystems.itam');
Route::redirect('/itam/activos', '/itam?tab=inventory')->name('itam.assets');
Route::redirect('/itam/insumos', '/itam?tab=supplies')->name('itam.supplies');
Route::redirect('/itam/prestamos', '/itam?tab=loans')->name('itam.loans');
Route::redirect('/itam/mantenimiento', '/itam?tab=maintenance')->name('itam.maintenance');
Route::redirect('/itam/movimientos', '/itam?tab=movements')->name('itam.movements');
Route::redirect('/itam/software', '/itam?tab=software')->name('itam.software');
Route::redirect('/itam/auditoria', '/itam?tab=audits')->name('itam.audits');

// Rutas del Subsistema Helpdesk (Mesa de Ayuda y Soporte TI)
Route::get('/helpdesk', HelpdeskTickets::class)->name('subsystems.helpdesk');
Route::get('/helpdesk/tickets', HelpdeskTickets::class)->name('helpdesk.tickets');
Route::redirect('/helpdesk/kb', '/helpdesk?tab=knowledge')->name('helpdesk.kb');

// Rutas de Otros Subsistemas Municipales
Route::get('/transportes', TransportesDashboard::class)->name('subsystems.transportes');

// Ficha Pública Móvil del Activo Tecnológico (Escaneo QR)
Route::get('/scan/activo/{code}', function (string $code) {
    $asset = Asset::with([
        'category',
        'brand',
        'model',
        'assignedPerson',
        'parentAsset.brand',
        'parentAsset.model',
        'childAssets.category',
        'childAssets.brand',
        'childAssets.model',
        'software',
        'maintenanceOrders' => fn ($q) => $q->latest()->limit(5),
    ])
        ->where('computer_code', $code)
        ->orWhere('patrimonial_code', $code)
        ->orWhere('serial_number', $code)
        ->firstOrFail();

    return view('scan.asset', compact('asset'));
})->name('scan.asset');
