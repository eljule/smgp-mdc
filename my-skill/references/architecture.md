# Arquitectura

## Indice
1. Flujo de capas
2. Estructura de carpetas
3. Donde va cada cosa
4. Rutas
5. Cuando modularizar

## 1. Flujo de capas

```
Vista Blade (Tailwind + Alpine para UI)
        |  usa
Blade components x-ui.*   (aqui se centralizan las clases de Tailwind)
        |
Componente Livewire        (input, validacion, autorizacion)
        |
Action / Service           (logica de negocio)
        |
Modelo Eloquent            (datos)
```

Cada capa solo conoce a la de abajo. Un modelo nunca llama a un componente Livewire; una Action nunca depende de `request()` ni de `$this->dispatch()`.

## 2. Estructura de carpetas

```
app/
├── Actions/<Dominio>/            CreateUser.php, PlaceOrder.php
├── Enums/                        OrderStatus.php, UserRole.php
├── Livewire/
│   ├── <Feature>/                Index.php, Create.php, Edit.php, Show.php
│   ├── Forms/                    UserForm.php (Form Objects)
│   └── Concerns/                 WithSearch.php, WithSorting.php
├── Models/
├── Policies/
├── Services/                     Solo integraciones externas
├── Listeners/ y Events/          Efectos secundarios de negocio
└── Http/Controllers/             Solo webhooks, API, descargas

resources/
├── css/app.css                   @import 'tailwindcss'; @theme; @source
├── js/
│   ├── app.js
│   └── alpine/                   Un archivo por Alpine.data()
└── views/
    ├── components/
    │   ├── ui/                   button, input, select, modal, card, badge, table
    │   └── layouts/              app.blade.php, guest.blade.php
    └── livewire/<feature>/       Espeja app/Livewire

tests/
├── Feature/Livewire/<Feature>/
└── Unit/Actions/
```

## 3. Donde va cada cosa

| Necesito... | Va en... |
|---|---|
| Crear/actualizar/borrar con reglas de negocio | `app/Actions` |
| Validar input de formulario | `app/Livewire/Forms` |
| Decidir quien puede hacer algo | `app/Policies` |
| Estado, rol, tipo con valores fijos | `app/Enums` |
| Llamar a una API de terceros | `app/Services` |
| Enviar email/notificar tras un hecho | Evento + Listener |
| Filtros/orden/paginacion repetidos | `app/Livewire/Concerns` |
| Boton, input, modal, badge, tarjeta | `resources/views/components/ui` |
| Abrir/cerrar, tabs, copiar | `resources/js/alpine` + `x-data` |
| Webhook o endpoint de API | `app/Http/Controllers` |

## 4. Rutas

- Rutas de pagina completa con componente: `Route::get('/users', \App\Livewire\Users\Index::class)->name('users.index');`
- Middleware `auth` y `verified` donde corresponda; el middleware no sustituye a `authorize()`.
- Agrupar por feature con prefijo y nombre: `Route::prefix('users')->name('users.')->group(...)`.
- Si `routes/web.php` supera ~100 lineas, separar por archivo (`routes/web/users.php`) y cargarlos desde `bootstrap/app.php`.

## 5. Cuando modularizar

No antes de tener entre 10 y 15 modulos de negocio; antes es sobreingenieria. Entonces agrupar por dominio:

```
app/Modules/Billing/{Actions,Livewire,Models,Policies,routes.php}
```

Preparacion previa sin costo: agrupar Livewire por feature y Actions por dominio desde el dia uno.
