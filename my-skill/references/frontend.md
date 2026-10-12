# Frontend: Alpine.js + Tailwind CSS 4

## Indice
1. Configuracion de Tailwind 4
2. Alpine ordenado
3. Componentes `x-ui.*` base
4. Integracion Livewire + Alpine
5. Errores comunes

## 1. Configuracion de Tailwind 4

```bash
npm install tailwindcss @tailwindcss/vite
```

```js
// vite.config.js
import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
    plugins: [
        laravel({ input: ['resources/css/app.css', 'resources/js/app.js'], refresh: true }),
        tailwindcss(),
    ],
})
```

```css
/* resources/css/app.css */
@import 'tailwindcss';

@source '../../vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php';
@source '../../storage/framework/views/*.php';
@source '../**/*.blade.php';
@source '../**/*.js';

@theme {
    --font-sans: 'Inter', ui-sans-serif, system-ui, sans-serif;
    --color-brand-500: #4f46e5;
    --color-brand-600: #4338ca;
}

[x-cloak] { display: none !important; }
```

En v4 no existe `tailwind.config.js` por defecto: los tokens viven en `@theme`. Los plugins se cargan con `@plugin`.

## 2. Alpine ordenado

Livewire 3 incluye Alpine; no importarlo ni cargarlo por CDN.

```js
// resources/js/alpine/dropdown.js
export default () => ({
    open: false,
    toggle() { this.open = !this.open },
    close() { this.open = false },
})
```

```js
// resources/js/app.js
import dropdown from './alpine/dropdown'

document.addEventListener('alpine:init', () => {
    Alpine.data('dropdown', dropdown)
})
```

```blade
<div x-data="dropdown" @click.outside="close">
    <button @click="toggle" :aria-expanded="open">Menu</button>
    <div x-show="open" x-cloak x-transition>...</div>
</div>
```

## 3. Componentes `x-ui.*` base

Crear estos antes de construir pantallas: `button`, `input`, `label`, `error`, `select`, `textarea`, `card`, `badge`, `modal`, `table`, `alert`, `pagination`.

### button

```blade
@props(['variant' => 'primary', 'size' => 'md'])

@php
    $variants = [
        'primary' => 'bg-brand-500 text-white hover:bg-brand-600',
        'secondary' => 'bg-gray-100 text-gray-900 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-100',
        'danger' => 'bg-red-600 text-white hover:bg-red-700',
    ];
    $sizes = [
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-6 py-3 text-base',
    ];
@endphp

<button {{ $attributes->class([
    'inline-flex items-center justify-center rounded-md font-medium transition',
    'focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-500 focus-visible:ring-offset-2',
    'disabled:cursor-not-allowed disabled:opacity-50',
    $variants[$variant],
    $sizes[$size],
]) }}>
    {{ $slot }}
</button>
```

### input con etiqueta y error

```blade
@props(['label', 'name', 'type' => 'text'])

<div class="space-y-1">
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
        {{ $label }}
    </label>
    <input id="{{ $name }}" type="{{ $type }}"
        {{ $attributes->class([
            'block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500',
            'dark:border-gray-700 dark:bg-gray-900',
            'border-red-500' => $errors->has($name),
        ]) }}
        aria-invalid="{{ $errors->has($name) ? 'true' : 'false' }}">
    @error($name)
        <p class="text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
```

Uso: `<x-ui.input label="Nombre" name="form.name" wire:model="form.name" />`.

### badge alimentado por un Enum

```blade
@props(['variant' => 'neutral'])

@php
    $variants = [
        'neutral' => 'bg-gray-100 text-gray-700',
        'success' => 'bg-green-100 text-green-800',
        'warning' => 'bg-amber-100 text-amber-800',
        'danger' => 'bg-red-100 text-red-800',
    ];
@endphp

<span {{ $attributes->class(['inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium', $variants[$variant]]) }}>
    {{ $slot }}
</span>
```

El Enum expone `variant()` y `label()`; el badge decide las clases.

### modal (Alpine + Tailwind, controlado desde Livewire)

```blade
@props(['name'])

<div x-data="{ open: false }"
     x-on:open-modal.window="if ($event.detail === '{{ $name }}') open = true"
     x-on:close-modal.window="if ($event.detail === '{{ $name }}') open = false"
     x-on:keydown.escape.window="open = false"
     x-show="open" x-cloak x-transition.opacity
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
     role="dialog" aria-modal="true">
    <div @click.outside="open = false"
         class="w-full max-w-md rounded-lg bg-white p-6 shadow-xl dark:bg-gray-900">
        {{ $slot }}
    </div>
</div>
```

Abrir/cerrar desde Livewire con `$this->dispatch('open-modal', 'confirm-delete')`. El modal nunca contiene logica de negocio.

## 4. Integracion Livewire + Alpine

- Leer o escribir estado de Livewire desde Alpine: `$wire.form.name`, `$wire.save()`.
- Sincronizar una propiedad: `x-data="{ value: $wire.entangle('value').live }"` solo cuando sea imprescindible; preferir eventos.
- Estados de carga con Livewire, no con Alpine: `wire:loading`, `wire:loading.attr="disabled"`, `wire:target="save"`.
- Confirmaciones: `wire:confirm="Seguro?"` para casos simples; modal para casos con contenido.
- Contenido que Livewire re-renderiza y Alpine debe conservar: `wire:ignore` en el contenedor del widget de terceros (graficos, editores).

## 5. Errores comunes

| Error | Correccion |
|---|---|
| `"bg-{{ $color }}-500"` | Mapa completo de clases en `@php` |
| Alpine cargado por CDN/npm | Quitarlo; ya viene con Livewire |
| Parpadeo al cargar | `x-cloak` + regla CSS |
| Lista que se desincroniza | `wire:key` unico en cada item |
| `x-data` de 30 lineas en Blade | Mover a `resources/js/alpine/` |
| Mismo bloque de clases en 3 vistas | Crear componente `x-ui.*` |
| Valores arbitrarios repetidos `text-[#4f46e5]` | Token en `@theme` |
| Hacer fetch desde Alpine a la base de datos | Llamar a un metodo Livewire |

## Herramientas

- `prettier` + `prettier-plugin-tailwindcss` para ordenar clases.
- Tailwind CSS IntelliSense en el editor.
- Si se adopta una libreria de componentes (Flux UI, WireUI, daisyUI), envolverla dentro de `components/ui` para poder cambiarla sin tocar las vistas.
