<?php

declare(strict_types=1);

namespace Tests\Unit\Actions;

use App\Actions\Core\GetActiveModulesAction;
use App\Enums\ModuleAccentColor;
use App\Enums\ModuleStatus;
use App\Models\CoreModule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetActiveModulesActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_retrieves_only_active_modules_sorted_by_order(): void
    {
        CoreModule::create([
            'code' => 'mod_inactive',
            'name' => 'Módulo Inactivo',
            'description' => 'No debe aparecer',
            'icon_url' => '/images/icon.png',
            'route' => '/inactivo',
            'accent_color' => ModuleAccentColor::Emerald,
            'status' => ModuleStatus::Active,
            'order' => 1,
            'is_active' => false,
            'requires_auth' => true,
        ]);

        $mod2 = CoreModule::create([
            'code' => 'mod_2',
            'name' => 'Segundo Módulo',
            'description' => 'Módulo con orden 2',
            'icon_url' => '/images/icon2.png',
            'route' => '/dos',
            'accent_color' => ModuleAccentColor::Blue,
            'status' => ModuleStatus::Active,
            'order' => 2,
            'is_active' => true,
            'requires_auth' => true,
        ]);

        $mod1 = CoreModule::create([
            'code' => 'mod_1',
            'name' => 'Primer Módulo',
            'description' => 'Módulo con orden 1',
            'icon_url' => '/images/icon1.png',
            'route' => '/uno',
            'accent_color' => ModuleAccentColor::Emerald,
            'status' => ModuleStatus::Active,
            'order' => 1,
            'is_active' => true,
            'requires_auth' => true,
        ]);

        $action = new GetActiveModulesAction;
        $result = $action->handle();

        $this->assertCount(2, $result);
        $this->assertSame($mod1->code, $result->first()->code);
        $this->assertSame($mod2->code, $result->last()->code);
    }

    public function test_filters_modules_by_search_term(): void
    {
        CoreModule::create([
            'code' => 'central',
            'name' => 'Dashboard Central',
            'description' => 'Administración general',
            'icon_url' => '/images/central.png',
            'route' => '/admin',
            'accent_color' => ModuleAccentColor::Emerald,
            'status' => ModuleStatus::Active,
            'order' => 1,
            'is_active' => true,
            'requires_auth' => true,
        ]);

        CoreModule::create([
            'code' => 'itam',
            'name' => 'Inventario TI',
            'description' => 'Gestión de hardware y software',
            'icon_url' => '/images/itam.png',
            'route' => '/itam',
            'accent_color' => ModuleAccentColor::Blue,
            'status' => ModuleStatus::Active,
            'order' => 2,
            'is_active' => true,
            'requires_auth' => true,
        ]);

        $action = new GetActiveModulesAction;

        $searchByName = $action->handle('Inventario');
        $this->assertCount(1, $searchByName);
        $this->assertSame('itam', $searchByName->first()->code);

        $searchByDesc = $action->handle('hardware');
        $this->assertCount(1, $searchByDesc);
        $this->assertSame('itam', $searchByDesc->first()->code);

        $searchEmpty = $action->handle('Inexistente');
        $this->assertCount(0, $searchEmpty);
    }
}
