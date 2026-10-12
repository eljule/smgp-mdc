<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Office;
use App\Models\Permission;
use App\Models\Person;
use App\Models\Role;
use App\Models\Sede;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CoreModelsTest extends TestCase
{
    use RefreshDatabase;

    public function test_office_model_and_hierarchy(): void
    {
        $parent = Office::create([
            'code' => '02',
            'acronym' => 'ALC',
            'name' => 'ALCALDÍA',
            'sede' => 'PALACIO MUNICIPAL (SEDE PRINCIPAL)',
            'level' => 1,
            'is_active' => true,
        ]);

        $child = Office::create([
            'code' => '02.01',
            'acronym' => 'OSALC',
            'name' => 'OFICINA SECRETARIA DE ALCALDIA',
            'parent_name' => 'ALCALDÍA',
            'parent_id' => $parent->id,
            'sede' => 'PALACIO MUNICIPAL (SEDE PRINCIPAL)',
            'level' => 2,
            'is_active' => true,
        ]);

        $this->assertEquals('ALCALDÍA', $child->parent->name);
        $this->assertCount(1, $parent->children);
        $this->assertEquals('02.01', $parent->children->first()->code);
    }

    public function test_person_model_and_full_name_accessor(): void
    {
        $person = Person::create([
            'type' => 'PERSONAL',
            'document_type' => 'DNI',
            'document_number' => '45821903',
            'first_name' => 'JUAN ALBERTO',
            'paternal_surname' => 'PÉREZ',
            'maternal_surname' => 'MORALES',
            'email' => 'jperez@sigm.gob.pe',
            'phone' => '969123456',
            'position' => 'TÉCNICO DE SOPORTE E ITAM',
            'office' => 'OFICINA DE DESARROLLO TECNOLÓGICO (ODT)',
            'labor_condition' => 'CAS',
            'is_active' => true,
        ]);

        $this->assertEquals('JUAN ALBERTO PÉREZ MORALES', $person->full_name);
        $this->assertTrue($person->is_active);
    }

    public function test_user_model_relations_and_casts(): void
    {
        $person = Person::create([
            'type' => 'PERSONAL',
            'document_type' => 'DNI',
            'document_number' => '08192844',
            'first_name' => 'CARLOS',
            'paternal_surname' => 'MENDOZA',
            'is_active' => true,
        ]);

        $user = User::create([
            'username' => 'cmendoza',
            'password' => Hash::make('secret123'),
            'full_name' => 'Carlos Mendoza',
            'email' => 'cmendoza@castilla.gob.pe',
            'role' => 'Administrador Central',
            'allowed_modules' => ['admin', 'itam'],
            'is_active' => true,
            'person_id' => $person->id,
        ]);

        $this->assertEquals($person->id, $user->person->id);
        $this->assertIsArray($user->allowed_modules);
        $this->assertContains('admin', $user->allowed_modules);
        $this->assertTrue(Hash::check('secret123', $user->password));
    }

    public function test_role_and_permission_models(): void
    {
        $permission = Permission::create([
            'code' => 'admin.users.manage',
            'name' => 'Crear y Gestionar Usuarios',
            'subsystem_code' => 'central_dashboard',
            'category' => 'USUARIOS',
            'is_active' => true,
        ]);

        $role = Role::create([
            'code' => 'admin_central',
            'name' => 'Administrador Central',
            'subsystem_code' => 'GLOBAL',
            'is_system' => true,
            'permission_codes' => ['admin.users.manage'],
            'is_active' => true,
        ]);

        $this->assertEquals('admin.users.manage', $permission->code);
        $this->assertIsArray($role->permission_codes);
        $this->assertTrue($role->is_system);
    }

    public function test_sede_model_and_color_presets(): void
    {
        $sede = Sede::create([
            'name' => 'SEDE AUDITORIO MUNICIPAL',
            'code' => 'AUDITORIO',
            'address' => 'Jr. Ayacucho 300',
            'color' => 'purple',
            'is_active' => true,
        ]);

        $this->assertEquals('SEDE AUDITORIO MUNICIPAL', $sede->name);
        $this->assertEquals('purple', $sede->color);
        $this->assertStringContainsString('purple', $sede->badge_class);
        $this->assertStringContainsString('purple', Sede::getBadgeClasses('SEDE AUDITORIO MUNICIPAL'));
        $this->assertArrayHasKey('purple', Sede::availableColors());
    }
}
