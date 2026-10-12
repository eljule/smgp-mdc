<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function (): void {
    $this->comment('Transformación Digital - Municipalidad Distrital de Castilla');
})->purpose('Muestra mensaje institucional');
