# Livewire 3: plantillas y patrones

## Indice
1. Componente delgado
2. Form Object
3. Action
4. Policy
5. Componente Index con busqueda y paginacion
6. Trait reutilizable
7. Sintaxis v2 prohibida y su reemplazo

## 1. Componente delgado

```php
<?php

declare(strict_types=1);

namespace App\Livewire\Users;

use App\Actions\Users\CreateUser;
use App\Livewire\Forms\UserForm;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Create extends Component
{
    public UserForm $form;

    public function mount(): void
    {
        $this->authorize('create', User::class);
    }

    public function save(CreateUser $createUser): void
    {
        $this->authorize('create', User::class);
        $this->form->validate();

        $createUser->handle($this->form->all());

        session()->flash('status', __('User created'));
        $this->redirectRoute('users.index', navigate: true);
    }

    public function render(): View
    {
        return view('livewire.users.create');
    }
}
```

Patron: `mount` autoriza, cada accion autoriza y valida, delega a la Action, redirige o despacha evento.

## 2. Form Object

```php
<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class UserForm extends Form
{
    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|email|max:255|unique:users,email')]
    public string $email = '';
}
```

Para edicion con `unique` que ignore el registro actual, usar `rules()` en lugar de atributos y recibir el modelo con un metodo `setUser(User $user)`.

## 3. Action

```php
<?php

declare(strict_types=1);

namespace App\Actions\Users;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class CreateUser
{
    /** @param array{name: string, email: string} $data */
    public function handle(array $data): User
    {
        return DB::transaction(function () use ($data): User {
            $user = User::create($data);

            UserCreated::dispatch($user); // efectos secundarios via Listener

            return $user;
        });
    }
}
```

Reglas: sin dependencia de `request()`, sesion ni Livewire; recibe datos ya validados; devuelve el modelo o un valor tipado.

## 4. Policy

```php
public function update(User $actor, User $target): bool
{
    return $actor->can('users.update') || $actor->is($target);
}
```

Registrar y usar siempre con `$this->authorize('update', $user)` en el componente. En Blade, `@can` solo oculta UI; no sustituye la autorizacion del servidor.

## 5. Componente Index con busqueda y paginacion

```php
<?php

declare(strict_types=1);

namespace App\Livewire\Users;

use App\Livewire\Concerns\WithSearch;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
class Index extends Component
{
    use WithPagination;
    use WithSearch;

    #[Locked]
    public ?int $deletingId = null;

    #[Computed]
    public function users(): LengthAwarePaginator
    {
        return User::query()
            ->with('roles')
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->latest()
            ->paginate(15);
    }

    public function delete(int $id, \App\Actions\Users\DeleteUser $deleteUser): void
    {
        $user = User::findOrFail($id);
        $this->authorize('delete', $user);

        $deleteUser->handle($user);
    }

    public function render()
    {
        return view('livewire.users.index');
    }
}
```

Vista: acceder con `$this->users`, y en cada fila `wire:key="user-{{ $user->id }}"`.

## 6. Trait reutilizable

```php
<?php

declare(strict_types=1);

namespace App\Livewire\Concerns;

use Livewire\Attributes\Url;

trait WithSearch
{
    #[Url(as: 'q', except: '')]
    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }
}
```

## 7. Sintaxis v2 prohibida y su reemplazo

| Prohibido (v2) | Usar (v3) |
|---|---|
| `$this->emit('x')` / `emitTo` | `$this->dispatch('x')` |
| `protected $listeners = [...]` | `#[On('x')]` sobre el metodo |
| `wire:model.defer` | `wire:model` (diferido por defecto) |
| `wire:model` en tiempo real | `wire:model.live` |
| `wire:model.lazy` | `wire:model.blur` |
| `@entangle('x')` | `$wire.entangle('x')` |
| `$this->dispatchBrowserEvent()` | `$this->dispatch()` |
| `@livewireStyles/@livewireScripts` manuales | Inyeccion automatica (salvo desactivada en config) |
| Propiedad publica con modelo sin proteger | `#[Locked]` en IDs |
| `mount()` sin autorizar | `authorize()` en `mount()` |

## Pruebas

```php
use App\Livewire\Users\Create;
use Livewire\Livewire;

it('creates a user', function () {
    $admin = User::factory()->admin()->create();

    Livewire::actingAs($admin)
        ->test(Create::class)
        ->set('form.name', 'Ana')
        ->set('form.email', 'ana@example.com')
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('users.index'));
});

it('forbids creation without permission', function () {
    Livewire::actingAs(User::factory()->create())
        ->test(Create::class)
        ->assertForbidden();
});
```

Probar siempre: camino feliz, validacion fallida, y acceso no autorizado.
