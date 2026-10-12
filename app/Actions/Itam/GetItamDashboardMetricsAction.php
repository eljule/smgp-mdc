<?php

declare(strict_types=1);

namespace App\Actions\Itam;

use App\Models\Itam\Asset;
use App\Models\Itam\AssetCategory;
use App\Models\Itam\AssetLoan;
use App\Models\Itam\MaintenanceOrder;
use App\Models\Itam\Supply;

final class GetItamDashboardMetricsAction
{
    /**
     * Compute comprehensive metrics for ITAM Dashboard.
     *
     * @return array<string, mixed>
     */
    public function handle(): array
    {
        $totalAssets = Asset::count();
        $operativos = Asset::where('status', 'OPERATIVO')->count();
        $enMantenimiento = Asset::where('status', 'EN_MANTENIMIENTO')->count();
        $enCustodia = Asset::where('status', 'EN_CUSTODIA')->count();
        $enPrestamo = Asset::where('status', 'EN_PRESTAMO')->count();
        $enDesuso = Asset::where('status', 'EN_DESUSO')->count();
        $paraBaja = Asset::whereIn('status', ['PARA_BAJA', 'BAJA'])->count();

        $disponibilidad = $totalAssets > 0 ? (int) round(($operativos / $totalAssets) * 100) : 100;

        // Insumos críticos: stock <= 2 o stock <= min_stock
        $criticalSuppliesCount = Supply::whereRaw('stock <= min_stock OR stock <= 2')->count();

        // Mantenimientos pendientes: estado distinto de COMPLETADO
        $pendingMaintenance = MaintenanceOrder::where('status', '!=', 'COMPLETADO')->count();

        // Préstamos retrasados: estado ACTIVO y fecha esperada vencida
        $delayedLoans = AssetLoan::where('status', 'ACTIVO')
            ->where('expected_return_date', '<', now())
            ->count();

        // Categorías con conteo real de activos
        $categories = AssetCategory::withCount('assets')
            ->orderBy('name')
            ->get();

        // 6 Estados operativos estándar
        $statuses = [
            ['label' => 'Operativo', 'key' => 'OPERATIVO', 'count' => $operativos, 'color' => '#16a34a'],
            ['label' => 'En Mantenimiento', 'key' => 'EN_MANTENIMIENTO', 'count' => $enMantenimiento, 'color' => '#d97706'],
            ['label' => 'En Custodia / Almacén', 'key' => 'EN_CUSTODIA', 'count' => $enCustodia, 'color' => '#2563eb'],
            ['label' => 'En Préstamo Temporal', 'key' => 'EN_PRESTAMO', 'count' => $enPrestamo, 'color' => '#6366f1'],
            ['label' => 'En Desuso', 'key' => 'EN_DESUSO', 'count' => $enDesuso, 'color' => '#64748b'],
            ['label' => 'Para Baja Técnica', 'key' => 'PARA_BAJA', 'count' => $paraBaja, 'color' => '#e11d48'],
        ];

        return [
            'totalAssets' => $totalAssets,
            'operativos' => $operativos,
            'enMantenimiento' => $enMantenimiento,
            'enCustodia' => $enCustodia,
            'enPrestamo' => $enPrestamo,
            'enDesuso' => $enDesuso,
            'paraBaja' => $paraBaja,
            'disponibilidad' => $disponibilidad,
            'criticalSuppliesCount' => $criticalSuppliesCount,
            'pendingMaintenance' => $pendingMaintenance,
            'delayedLoans' => $delayedLoans,
            'categories' => $categories,
            'statuses' => $statuses,
        ];
    }
}
