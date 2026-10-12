# Checklist: definicion de terminado y revision

Recorrer esta lista antes de reportar una tarea como completa y al revisar codigo existente. Marcar cada punto como cumplido, no aplica (con motivo) o pendiente. No declarar "listo" con puntos pendientes sin decirlo.

## A. Arquitectura
- [ ] El componente Livewire no contiene logica de negocio; delega en una Action.
- [ ] Cada Action hace una sola operacion, usa `DB::transaction` si hay varias escrituras y no depende de request/sesion/Livewire.
- [ ] Estados, roles y tipos usan Enum.
- [ ] Archivos en la carpeta correcta segun `architecture.md`; vista espejo en `resources/views/livewire/`.
- [ ] No se duplico algo que ya existia (Action, componente UI, trait).

## B. Livewire 3
- [ ] `authorize()` en `mount()` y en cada metodo publico que lee datos protegidos o muta.
- [ ] Validacion con Form Object antes de persistir.
- [ ] `#[Locked]` en IDs y propiedades que el cliente no debe cambiar.
- [ ] Sin datos sensibles en propiedades publicas.
- [ ] Consultas en `#[Computed]`; sin colecciones grandes en propiedades.
- [ ] `wire:key` en cada elemento de `@foreach`.
- [ ] Un unico elemento raiz en la vista.
- [ ] Ninguna sintaxis de v2 (ver tabla en `livewire.md`).
- [ ] `wire:model.live` solo donde hace falta, con debounce en busquedas.
- [ ] Estados de carga con `wire:loading` en acciones lentas.

## C. Alpine.js
- [ ] No hay Alpine duplicado (CDN/npm).
- [ ] Alpine solo maneja estado de interfaz.
- [ ] `x-data` complejos extraidos a `resources/js/alpine/`.
- [ ] `x-cloak` en elementos que inician ocultos.
- [ ] Modales y menus con `role`, `aria-*` y cierre con Escape.

## D. Tailwind CSS 4
- [ ] Sin clases construidas por concatenacion.
- [ ] Clases repetidas 3+ veces convertidas en componente `x-ui.*`.
- [ ] Colores y fuentes desde tokens de `@theme`.
- [ ] Sin `@apply` innecesario ni `style=""` para cosas resolubles con utilidades.
- [ ] Responsive y `dark:` considerados.
- [ ] Foco visible y `label` asociado a cada input.

## E. Seguridad y datos
- [ ] `$fillable`/`$guarded` definido; sin `$request->all()` en `create()`.
- [ ] Salida escapada (`{{ }}`); cualquier `{!! !!}` justificado.
- [ ] Sin N+1 (`with()` / `preventLazyLoading`).
- [ ] Migracion nueva (no se edito una desplegada), con indices y llaves foraneas.
- [ ] `env()` solo en archivos de `config/`.
- [ ] Subida de archivos con validacion de tipo y tamano.

## F. Calidad
- [ ] `declare(strict_types=1);` y tipos en parametros y retornos.
- [ ] Tests Pest: camino feliz, validacion fallida y acceso no autorizado.
- [ ] `vendor/bin/pint` sin cambios pendientes.
- [ ] `vendor/bin/phpstan analyse` sin errores nuevos.
- [ ] `vendor/bin/pest` en verde.
- [ ] Dependencias nuevas justificadas y confirmadas con el usuario.

## G. Reporte final (formato)

```
## Resumen
Que se construyo, en 2-3 lineas.

## Archivos
- ruta/archivo.php: proposito

## Decisiones
- Decision y motivo.

## Verificacion
- Pint: ejecutado / no ejecutado
- Larastan: ejecutado / no ejecutado
- Pest: X pasaron / no ejecutado

## Pendiente o desviaciones
- Lo que falta, o reglas marcadas con // DEVIATION: y por que.
```

Si no se pudo ejecutar algo (por ejemplo, PHP no esta instalado o falla el entorno), decirlo tal cual y entregar los comandos para que el usuario los corra.
