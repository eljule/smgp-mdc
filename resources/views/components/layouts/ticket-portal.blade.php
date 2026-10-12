<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Portal Ágil de Reporte de Incidencias TI - Municipalidad Distrital de Castilla">
    <title>{{ $title ?? 'Registrar Falla en su Puesto de Trabajo - SIGM Castilla' }}</title>

    <!-- Script anti-parpadeo para tema oscuro -->
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="flex flex-col min-h-screen bg-[#f1f5f3] dark:bg-slate-950 text-slate-800 antialiased dark:text-slate-100 font-sans selection:bg-emerald-600 selection:text-white">

    {{ $slot }}

    @livewireScripts
</body>
</html>
