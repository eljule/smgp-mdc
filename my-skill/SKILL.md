---
name: laravel-livewire-strict
description: 'Reglas estrictas y flujo de trabajo para construir, modificar, revisar o refactorizar software con Laravel 13 + Livewire 3 + Alpine.js + Tailwind CSS 4. Usar SIEMPRE que el usuario pida crear o editar un modulo, CRUD, componente Livewire, formulario, tabla, modal, vista Blade, Action, migracion, politica, test o estilo en este stack, o pida revisar o auditar codigo del proyecto, aunque no mencione reglas ni estandares. Tambien aplica cuando mencione Livewire, Alpine, Tailwind, Blade components, Pest, Pint o Larastan.'
---

# Laravel + Livewire + Alpine + Tailwind: fabricacion estricta

Este skill convierte las convenciones del proyecto en un proceso de fabricacion repetible. El objetivo es que cualquier pieza de codigo que se genere sea predecible, segura, testeable y facil de mantener, sin importar quien (o que sesion) la escriba. Las reglas existen porque en este stack los errores caros son silenciosos: un metodo publico de Livewire sin autorizacion es un endpoint abierto, una clase Tailwind concatenada simplemente no se compila, y logica de negocio dentro de un componente no se puede reutilizar ni testear aislada.

Responder siempre en el idioma del usuario. Codigo, nombres de clases y comentarios tecnicos en ingles; textos de interfaz segun el idioma del producto.

## Stack y versiones

| Capa | Tecnologia | Nota |
|---|---|---|
| Backend | Laravel 13 | Verificar requisito de PHP en la documentacion oficial |
| Reactividad servidor | Livewire 3 | NO usar sintaxis de Livewire 2 |
| Interactividad UI | Alpine.js | Viene incluido en Livewire 3. NO instalarlo aparte |
| Estilos | Tailwind CSS 4 | Configuracion en CSS (`@theme`), plugin `@tailwindcss/vite` |
| Calidad | Pest, Pint, Larastan | Obligatorios |

Antes de escribir codigo en un proyecto existente, ejecutar `composer show laravel/framework livewire/livewire` y revisar `package.json` para confirmar versiones reales. Si difieren de la tabla, avisar al usuario antes de continuar.

## Flujo obligatorio (en este orden)

Seguir estos pasos para cualquier tarea de construccion. No saltar pasos; si uno no aplica, decirlo explicitamente en una linea.

1. **Entender y delimitar.** Reformular la tarea en 2-3 lineas: que entidad, que acciones, quien puede hacerlas. Si falta algo que cambia el diseno (roles, reglas de negocio, campos), preguntar una sola vez antes de construir.
2. **Revisar lo existente.** Buscar si ya existe una Action, componente `x-ui.*`, trait de `Livewire/Concerns` o Enum que resuelva parte de la tarea. Reutilizar antes de crear.
3. **Plan de archivos.** Listar los archivos que se crearan o tocaran, por capa, siguiendo `references/architecture.md`. Mostrar el plan al usuario si la tarea toca mas de 3 archivos.
4. **Construir de adentro hacia afuera:** migracion y modelo, Enum, Policy, Action, Form Object, componente Livewire, vista Blade, rutas, tests.
5. **Verificar.** Correr `vendor/bin/pint`, `vendor/bin/phpstan analyse` y `vendor/bin/pest`. Una tarea no esta terminada hasta pasar la lista de `references/checklist.md`.
6. **Reportar.** Resumir que se creo, que decisiones se tomaron y que quedo pendiente. No presentar como terminado algo que no se pudo verificar; decir que no se ejecuto.

## Ejecucion en el IDE (agente)

Este skill corre dentro de un agente con acceso al proyecto y a la terminal, asi que no basta con describir lo que haria:

- **Explorar antes de escribir.** Leer `composer.json`, `package.json`, `routes/`, `app/Livewire/` y `resources/views/components/ui/` para detectar lo que ya existe (paso 2 del flujo).
- **Generar archivos con Artisan** cuando exista el comando (`php artisan make:livewire`, `make:model -m`, `make:policy`, `make:test --pest`) y despues ajustar el contenido a las plantillas de `references/`. Para Actions y Enums, crear el archivo directamente.
- **Ejecutar la verificacion tu mismo** en la terminal: `vendor/bin/pint`, `vendor/bin/phpstan analyse`, `vendor/bin/pest` y `npm run build`. Si algo falla, corregir y repetir hasta que pase. Reportar la salida real, no una suposicion.
- **Pedir confirmacion antes de** comandos destructivos (`migrate:fresh`, `db:wipe`, borrar archivos), instalar dependencias o modificar `.env`.
- **Leer las referencias por ruta relativa** a esta carpeta (`references/livewire.md`, etc.) solo cuando la tarea las necesite.
- **Entregar un plan primero** (paso 3) y esperar aprobacion cuando la tarea toque mas de 3 archivos o implique migraciones.

## Reglas no negociables

Cada regla trae su motivo para poder aplicarla con criterio en casos limite.

### Arquitectura

- **Componentes Livewire delgados.** Solo reciben input, validan, autorizan, llaman a una Action y devuelven la vista. Motivo: la logica dentro del componente no se reutiliza desde API, comandos o jobs, y obliga a testear via UI.
- **Una Action = una operacion de negocio** (`app/Actions/<Dominio>/<Verbo><Sustantivo>.php`, metodo `handle()`). Las operaciones con varias escrituras van en `DB::transaction`.
- **Services solo para integraciones externas** (pagos, APIs, almacenamiento). No usar Services como cajon de sastre.
- **Sin consultas Eloquent complejas en Blade.** Las vistas reciben datos listos.
- **Enums en lugar de strings magicos** para estados, roles y tipos. El Enum puede exponer `label()` y `variant()`; los componentes UI deciden las clases.
- **Estructura por feature**: `app/Livewire/<Feature>/{Index,Create,Edit,Show}.php` con vista espejo en `resources/views/livewire/<feature>/`.

### Livewire 3

- **Autorizar en cada metodo publico que muta o expone datos** con `$this->authorize()`. Los metodos publicos son endpoints invocables desde el cliente. Tambien autorizar en `mount()` y en acciones de borrado, aunque la ruta ya tenga middleware.
- **Validar siempre** antes de persistir, con Form Objects (`App\Livewire\Forms`). Nunca confiar en que el input "ya viene validado" por la UI.
- **`#[Locked]` en IDs y propiedades que el cliente no debe modificar.** Las propiedades publicas son editables desde el navegador.
- **Nada sensible en propiedades publicas** (tokens, precios calculados, flags de permiso). Usar `#[Computed]` o recalcular en servidor.
- **`#[Computed]` para datos derivados y consultas**, no colecciones grandes en propiedades publicas (se serializan en cada request).
- **`wire:key` unico en cada elemento dentro de `@foreach`** con componentes o estado. Sin esto el DOM se desincroniza.
- **Un solo elemento raiz por vista de componente.**
- **`wire:model` es diferido por defecto en v3**; usar `.live` solo cuando se necesite tiempo real y `.live.debounce.300ms` en busquedas.
- **Eventos:** `$this->dispatch()` y `#[On]`. Prohibido `emit`, `emitTo`, `$listeners` (Livewire 2). Efectos secundarios de negocio (emails, logs) via eventos de Laravel + Listeners, no via eventos Livewire.
- **Paginacion y filtros reutilizables** en traits de `app/Livewire/Concerns` (`WithSearch`, `WithSorting`), con `#[Url]` en filtros.
- **Archivos subidos:** validar tipo y tamano con `WithFileUploads` y reglas explicitas; nunca guardar con el nombre original.
- **Navegacion:** `wire:navigate` en enlaces internos, `redirectRoute(..., navigate: true)` en redirecciones.
- Detalle y plantillas en `references/livewire.md`.

### Alpine.js

- **No cargar Alpine por CDN ni npm.** Livewire 3 ya lo incluye; duplicarlo produce "Multiple instances of Alpine running".
- **Alpine solo para estado de interfaz** (abrir/cerrar, tabs, copiar al portapapeles, transiciones). Si toca base de datos o reglas de negocio, es Livewire.
- **`x-data` largos van a `resources/js/alpine/*.js`** y se registran con `Alpine.data()` dentro de `alpine:init`. En Blade solo `x-data="nombre"` o literales de 1-2 propiedades.
- **Comunicacion con Livewire:** `$wire` y `$wire.entangle()` (`@entangle` es legado). Preferir eventos del navegador para desacoplar.
- **`x-cloak` en todo lo que arranca oculto**, con la regla CSS `[x-cloak]{display:none!important}`.
- Detalle en `references/frontend.md`.

### Tailwind CSS 4

- **Las clases largas se escriben una sola vez** en `resources/views/components/ui/*.blade.php`. Si un grupo de clases aparece 3 veces, se convierte en componente. Motivo: cambiar el diseno debe ser editar un archivo, no 40 vistas.
- **Nunca construir clases por concatenacion** (`bg-{{ $color }}-500`). Tailwind escanea texto estatico; la clase no se genera. Usar mapas completos (`$variants = ['primary' => 'bg-brand-500 ...']`).
- **Tokens de diseno en `@theme`** (colores, fuentes, radios). Prohibido valores arbitrarios repetidos como `text-[#4f46e5]`; definir el token.
- **`@apply` solo para casos puntuales** (contenido HTML que no se controla). No usarlo para recrear componentes.
- **Modo oscuro y responsive desde el inicio** (`dark:`, `sm:`/`md:`), no como parche posterior.
- **Accesibilidad minima:** `focus:` visible en todo elemento interactivo, `label` asociado a cada input, `aria-*` en modales y menus.

### Seguridad y datos

- Mass assignment explicito: `$fillable` o `$guarded` definido; nunca `$request->all()` a `create()`.
- Escapar salida con `{{ }}`. `{!! !!}` solo con HTML sanitizado y comentario que lo justifique.
- Evitar N+1: `with()` en consultas que recorren relaciones; activar `Model::preventLazyLoading()` en entornos no productivos.
- Migraciones: indices en llaves foraneas y columnas de filtro, `foreignId()->constrained()`. Nunca editar una migracion ya desplegada; crear una nueva.
- Secretos solo en `.env` y leidos via `config()`. Nunca `env()` fuera de archivos de config.

### Calidad

- **Pest** para tests. Todo componente Livewire con `Livewire::test()`; toda Action con test unitario o de feature.
- **Pint** (estilo), **Larastan** (nivel 5 o superior, subiendo con el tiempo), **tipos estrictos**: `declare(strict_types=1);`, tipos de parametros y retorno en todo metodo.
- Nombres: Actions con verbo (`CancelOrder`), componentes por operacion en singular (`Create`, `Edit`, `Index`), componentes UI en `x-ui.*`.
- Commits pequenos con mensajes convencionales (`feat(orders): ...`, `fix(users): ...`).

## Prohibiciones rapidas

Rechazar o corregir de inmediato si aparece cualquiera de estas:

- Logica de negocio o consultas pesadas dentro de un componente Livewire o una vista Blade.
- Metodo publico de Livewire sin `authorize()` o sin validacion.
- `emit`, `$listeners`, `@entangle`, `wire:model.defer`, `wire:model.lazy` (sintaxis de v2).
- Alpine instalado por separado; `x-data` de mas de ~5 lineas en linea en Blade.
- Clases Tailwind concatenadas dinamicamente; estilos inline (`style=""`) para cosas que Tailwind resuelve.
- Strings magicos para estados/roles; `env()` fuera de config; `$request->all()` en `create()`.
- Codigo sin tests, o declarar "listo" sin haber corrido las verificaciones.
- Dependencias nuevas (composer/npm) sin justificarlas y pedir confirmacion.

## Cuando el usuario pide algo que rompe una regla

No negarse ni obedecer en silencio. Explicar en una o dos lineas que regla se rompe y por que importa, ofrecer la alternativa conforme a las reglas y, si el usuario insiste, implementarlo marcando el desvio con un comentario `// DEVIATION:` y anotandolo en el reporte final. Las reglas protegen al proyecto, pero el usuario decide.

## Modo revision

Si el usuario pide revisar o auditar codigo existente, no reescribir de inmediato. Recorrer `references/checklist.md`, y devolver hallazgos agrupados por severidad:

- **Critico:** seguridad (autorizacion, validacion, datos expuestos), perdida de datos.
- **Alto:** logica en capa equivocada, N+1, falta de tests en flujos clave.
- **Medio:** sintaxis legada, clases Tailwind duplicadas, nombres inconsistentes.
- **Bajo:** estilo y orden.

Cada hallazgo con archivo, linea (si se conoce), problema, motivo y correccion propuesta.

## Referencias (leer segun la tarea)

- `references/architecture.md`: estructura de carpetas, flujo de capas, modularizacion, rutas. Leer al planificar archivos.
- `references/livewire.md`: plantillas de componente, Form Object, Action, Policy, trait de tabla. Leer al crear cualquier componente.
- `references/frontend.md`: Alpine, Tailwind v4, plantillas de componentes `x-ui.*`, layout. Leer al tocar vistas.
- `references/checklist.md`: definicion de terminado y checklist de revision. Leer siempre antes de reportar una tarea como completa.
