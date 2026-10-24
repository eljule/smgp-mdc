# 🏛️ SIGM-MDC — Sistema Integrado de Gestión Municipal

<div align="center">

![PHP Version](https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-12%20%2F%2013-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Livewire](https://img.shields.io/badge/Livewire-v3-FB70A9?style=for-the-badge&logo=livewire&logoColor=white)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-16-4169E1?style=for-the-badge&logo=postgresql&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/TailwindCSS-v3-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-Ready-2496ED?style=for-the-badge&logo=docker&logoColor=white)
![Tests](https://img.shields.io/badge/Tests-112%20Passed%20(835%20Assertions)-success?style=for-the-badge&logo=checkmarx&logoColor=white)

**Plataforma modular de alto rendimiento para Mesa de Ayuda (Helpdesk), Gestión Patrimonial de Activos TI (ITAM) y Administración Core de Identidades.**

[Características](#-módulos-y-capacidades-principales) • [Arquitectura](#-decisiones-de-arquitectura-y-diseño) • [Instalación Rápida](#-instalación-y-puesta-en-marcha) • [Credenciales Demo](#-credenciales-demo-para-evaluación) • [Testing](#-calidad-de-software-y-pruebas)

</div>

---

## 📌 Visión General del Proyecto

**SIGM-MDC** es un sistema web integral desarrollado para modernizar y optimizar la infraestructura de soporte y la trazabilidad de bienes informáticos en el sector público municipal (Municipalidad Distrital de Castilla). 

El proyecto resuelve la fragmentación operativa tradicional unificando en una sola plataforma:
1. **Atención de incidentes técnicos y solicitudes (Service Desk / Helpdesk)** con balanceo inteligente de carga entre técnicos.
2. **Ciclo de vida y auditoría patrimonial de hardware y software (ITAM)** mediante códigos QR físicos y actas digitales de entrega/devolución/baja.
3. **Gobierno centralizado de identidades, sedes, dependencias y roles (Core Admin)** con seguridad multi-subsistema.

---

## 🚀 Módulos y Capacidades Principales

### 1. 🎫 Mesa de Ayuda y Service Desk (`/helpdesk` | `/soporte`)
* **Cola de Atención Reactiva en Tiempo Real:** Gestión de estados (*Pendiente, En Proceso, Resuelto, Derivado, Anulado*) con trazabilidad y auditoría de cambios.
* **Balanceador de Carga Inteligente:** Motor de asignación automática de tickets que evalúa la carga activa de los técnicos (`least-loaded technician`) para una distribución equitativa.
* **Reasignación Masiva por Contingencia:** Permite transferir cargas completas entre técnicos con registro de bitácora ante emergencias o licencias del personal.
* **Portal Ciudadano y de Servidores:** Formulario ágil de generación de incidencias con consulta de DNI/RUC en tiempo real y selector de oficina/sede.
* **Base de Conocimientos (Knowledge Base):** Artículos técnicos categorizados para autoservicio con contador de visualizaciones.

### 2. 💻 Gestión de Activos de TI — ITAM (`/itam`)
* **Inventario Patrimonial Completo:** Registro de equipos de cómputo, servidores, periféricos, componentes de red y licencias de software con relación padre/hijo (ej. CPU con sus monitores y periféricos asociados).
* **Esquemas Dinámicos JSON:** Especificaciones técnicas personalizadas por categoría (RAM, procesador, disco, socket, etc.) sin alterar el esquema relacional.
* **Escaneo y Trazabilidad por Códigos QR:**
  * Generación automática de etiquetas QR para identificación patrimonial de bienes.
  * Ruta móvil optimizada (`/scan/activo/{code}`) para lectura física en campo desde smartphones o lectores ópticos.
  * Asociación instantánea de un bien escaneado al formulario de nuevo ticket de soporte.
* **Control de Insumos y Suministros:** Gestión de stock crítico (tóners, cables, repuestos) con ajuste de existencias y kardex de movimientos.
* **Módulo de Préstamos y Movimientos:** Flujo de custodia temporal con generación de actas de entrega, recepción y devolución conforme.
* **Bajas y Desincorporación:** Generación de actas formales de inoperatividad y baja patrimonial.

### 3. 🛡️ Administración Central y Seguridad Core (`/admin`)
* **Tabla Maestra de Personas con Buscador Avanzado:**
  * Búsqueda multi-palabra y multi-orden por **DNI** (8 dígitos), **RUC** (11 dígitos), **Nombres y Apellidos** o **Razón Social**.
  * Búsqueda normalizada insensible a tildes, mayúsculas y caracteres latinos (`translate(LOWER(...), 'áéíóúüñ', 'aeiouun')`) en PostgreSQL.
* **Gestión de Sedes y Oficinas:** Jerarquía organizacional multinivel con asignación de responsables y paletas cromáticas institucionales.
* **Matriz Interactiva de Roles y Permisos:** Control de privilegios en tiempo real utilizando **Spatie Laravel-Permission** con soporte de `Teams` por subsistema.
* **Políticas de Seguridad de Cuentas:**
  * Redirección obligatoria a cambio de contraseña provisional en primer inicio de sesión.
  * Bloqueo progresivo y temporizado tras 5 intentos fallidos de autenticación.
  * Desbloqueo y reseteo administrativo de credenciales con bitácora.

### 4. 🌐 Portal Unificado y Lanzador Institucional (`/portal`)
* Dashboard inicial que expone los subsistemas autorizados según el rol del usuario autenticado.
* **Middleware de Contención (`EnsureSubsystemAccess`):** Protección estricta a nivel de rutas; por ejemplo, usuarios con perfil de *Reportante / Personal General* son dirigidos exclusivamente al registro de tickets, bloqueando el acceso a módulos de configuración o inventario.

---

## 🏗️ Decisiones de Arquitectura y Diseño

| Pilar | Decisión Técnica | Justificación e Impacto |
| :--- | :--- | :--- |
| **Arquitectura de Software** | **Action Pattern (SRP)** | La lógica de negocio está desacoplada de controladores y componentes Livewire mediante clases de acción específicas en `app/Actions/`. Facilita tests unitarios aislados y reutilización limpia. |
| **Frontend Dinámico** | **Livewire 3 + Alpine.js** | Reactividad del lado del servidor sin la complejidad de frameworks SPA externos. Estados reactivos, debounce en búsquedas y modales dinámicos sin recargar la página. |
| **Motor de Base de Datos** | **PostgreSQL 16** | Integridad relacional estricta, campos JSONB para esquemas de hardware variables y funciones avanzadas de normalización de texto. |
| **Rendimiento** | **Eager Loading Estricto** | Prevención de problemas de consulta `N+1` en listados complejos mediante relaciones pre-cargadas optimizadas (`with([...])`). |
| **Seguridad** | **Multi-Tenancy por Subsistema** | Uso de Spatie Permission configurado con `team_id` mapeado al `subsystem_id`, aislando roles de Helpdesk de los de ITAM y Administración. |

---

## 🧪 Calidad de Software y Pruebas

El repositorio cuenta con una suite integral de **112 pruebas automatizadas** que cubren flujos críticos de extremo a extremo:

```bash
php artisan test
```

```text
   PASS  Tests\Unit\Models\CoreModelsTest                     (5 tests)
   PASS  Tests\Unit\Models\HelpdeskModelsTest                 (2 tests)
   PASS  Tests\Unit\Models\ItamModelsTest                     (3 tests)
   PASS  Tests\Feature\AuthTest                              (9 tests)
   PASS  Tests\Feature\Livewire\AdminDashboardTest           (26 tests)
   PASS  Tests\Feature\Livewire\HelpdeskComponentsTest       (10 tests)
   PASS  Tests\Feature\Livewire\ItamComponentsTest           (19 tests)
   PASS  Tests\Feature\Livewire\ModuleLauncherTest            (5 tests)
   PASS  Tests\Feature\ScanAssetTest                          (5 tests)
   PASS  Tests\Feature\UserPasswordSecurityTest               (6 tests)
   PASS  Tests\Feature\CoreModuleTest                         (2 tests)

  Tests:    112 passed (835 assertions)
  Duration: 10.31s
```

---

## ⚡ Instalación y Puesta en Marcha (Docker)

El proyecto está completamente dockerizado para ejecutarse con **un solo comando** sin necesidad de configurar PHP o PostgreSQL en el sistema anfitrión.

### Prerrequisitos
* [Git](https://git-scm.com/)
* [Docker Desktop](https://www.docker.com/products/docker-desktop/) o Docker Engine con Docker Compose v2+

### Pasos de Instalación

1. **Clonar el repositorio:**
   ```bash
   git clone https://github.com/eljule/smgp-mdc.git
   cd smgp-mdc
   ```

2. **Configurar variables de entorno:**
   ```bash
   cp .env.example .env
   ```

3. **Levantar los contenedores con Docker Compose:**
   ```bash
   docker compose up -d --build
   ```
   *Esto iniciará la aplicación web (`sigm_app`), el servidor web Nginx (`sigm_webserver` en puerto 8080), PostgreSQL 16 (`sigm_database` en puerto 5434) y pgAdmin 4 (`sigm_pgadmin_portal` en puerto 5052).*

4. **Instalar dependencias y ejecutar migraciones con datos demo:**
   ```bash
   docker exec -it sigm_app composer install
   docker exec -it sigm_app php artisan key:generate
   docker exec -it sigm_app php artisan migrate:fresh --seed
   ```

5. **Acceder a la aplicación:**
   * **URL:** [http://localhost:8080](http://localhost:8080)
   * **pgAdmin:** [http://localhost:5052](http://localhost:5052) (Usuario: `admin@sigm.gob.pe` | Password: `admin123`)

---

## 🔑 Credenciales Demo para Evaluación

La pantalla de login incluye botones de acceso rápido y perfiles precargados para evaluar los diferentes roles de usuario:

| Rol Evaluado | Usuario (`username`) | Contraseña | Alcance y Acceso |
| :--- | :--- | :--- | :--- |
| **Administrador Central** | `root` | `admin123` | Acceso irrestricto a todos los subsistemas (Admin, ITAM, Helpdesk, Permisos). |
| **Técnico de Soporte 1** | `soporte1` | `admin123` | Mesa de Ayuda completa, ITAM, préstamos, asignación de tickets y actas. |
| **Técnico de Soporte 2..4** | `soporte2` .. `soporte4` | `admin123` | Cola de atención técnica y balanceo de carga. |
| **Personal General / Reportante** | `02821801` *(DNI)* | `admin123` | Servidor municipal estándar; acceso restringido únicamente al registro y consulta de tickets propios. |

---

## 📂 Estructura del Proyecto

```text
├── app/
│   ├── Actions/                    # Patrón Action: Lógica de negocio pura (SRP)
│   │   ├── Admin/                  # Catálogos, métricas y filtros avanzados de personas
│   │   ├── Core/                   # Módulos activos y navegación institucional
│   │   ├── Helpdesk/               # Métricas y distribución de carga de trabajo
│   │   └── Itam/                   # Lógica patrimonial, activos y kardex
│   ├── Http/
│   │   ├── Controllers/Auth/       # Controladores de Login y Cambio de Contraseñas
│   │   └── Middleware/             # EnsureSubsystemAccess y filtros de seguridad
│   ├── Livewire/                   # Componentes Reactivos v3
│   │   ├── Admin/                  # Dashboard administrativo, personas, roles, sedes
│   │   ├── Helpdesk/               # Bandeja de tickets, balanceo, nuevo ticket, base de conocimiento
│   │   ├── Itam/                   # Inventario de activos, insumos, préstamos, actas
│   │   └── Portal/                 # Lanzador institucional de módulos
│   └── Models/                     # Eloquent Models con casts y accesores optimizados
├── docker/                         # Configuraciones de Nginx y PHP-FPM
├── database/
│   ├── migrations/                 # Esquemas relacionales en PostgreSQL
│   └── seeders/                    # Catálogo maestro de personal, activos y roles
├── resources/views/                # Vistas Blade con Tailwind CSS y Alpine.js
├── routes/web.php                  # Definición de rutas protegidas por subsistema
└── tests/                          # 92 pruebas automatizadas (Feature y Unit)
```

---

## 👨‍💻 Autor

Desarrollado con dedicación profesional y enfoque en ingeniería de software limpia.
* **GitHub:** [@eljule](https://github.com/eljule)
* **Repositorio:** [smgp-mdc](https://github.com/eljule/smgp-mdc)
