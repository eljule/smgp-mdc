<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\ModuleAccentColor;
use App\Enums\ModuleStatus;
use App\Models\CoreModule;
use Illuminate\Database\Seeder;

class CoreModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            [
                'code' => 'central',
                'name' => 'Dashboard Central y Configuración',
                'description' => 'Núcleo y launcher del SIGM con accesos consolidados, métricas generales y administración de...',
                'icon_url' => '/images/subsystem_central.png',
                'route' => '/admin',
                'accent_color' => ModuleAccentColor::Emerald->value,
                'status' => ModuleStatus::Active->value,
                'status_message' => null,
                'wait_time' => null,
                'order' => 1,
                'is_active' => true,
                'requires_auth' => true,
                'secondary_action' => null,
            ],
            [
                'code' => 'transportes',
                'name' => 'Licencias de Transportes',
                'description' => 'Este subsistema se encuentra temporalmente suspendido por trabajos de mantenimiento y mejoras en los servidores de emisión.',
                'icon_url' => '/images/subsystem_transportes.png',
                'route' => '/transportes',
                'accent_color' => ModuleAccentColor::Amber->value,
                'status' => ModuleStatus::Maintenance->value,
                'status_message' => 'Este subsistema se encuentra temporalmente suspendido por trabajos de mantenimiento y...',
                'wait_time' => 'Hasta nuevo aviso',
                'order' => 2,
                'is_active' => true,
                'requires_auth' => true,
                'secondary_action' => null,
            ],
            [
                'code' => 'itam',
                'name' => 'Inventario y Gestión Activos TI',
                'description' => 'Gestión de activos de hardware y software (ITAM), asignación por dependencias y control de garantías.',
                'icon_url' => '/images/subsystem_itam.png',
                'route' => '/itam',
                'accent_color' => ModuleAccentColor::Blue->value,
                'status' => ModuleStatus::Active->value,
                'status_message' => null,
                'wait_time' => null,
                'order' => 3,
                'is_active' => true,
                'requires_auth' => true,
                'secondary_action' => null,
            ],
            [
                'code' => 'helpdesk',
                'name' => 'Soporte Técnico y Helpdesk',
                'description' => 'Mesa de partes y resolución de incidencias informáticas, seguimiento de tickets y atención a usuarios.',
                'icon_url' => '/images/subsystem_helpdesk.png',
                'route' => '/helpdesk',
                'accent_color' => ModuleAccentColor::Purple->value,
                'status' => ModuleStatus::Active->value,
                'status_message' => null,
                'wait_time' => null,
                'order' => 4,
                'is_active' => true,
                'requires_auth' => true,
                'secondary_action' => [
                    'label' => 'Generar Ticket de Soporte',
                    'route' => '/soporte',
                ],
            ],
        ];

        foreach ($modules as $module) {
            CoreModule::updateOrCreate(
                ['code' => $module['code']],
                $module
            );
        }
    }
}
