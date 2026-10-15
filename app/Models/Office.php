<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Office extends Model
{
    use HasFactory;

    protected $table = 'core_offices';

    protected $fillable = [
        'code',
        'acronym',
        'name',
        'parent_name',
        'parent_id',
        'sede',
        'level',
        'is_active',
    ];

    protected $casts = [
        'level' => 'integer',
        'is_active' => 'boolean',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

        public function resolveActiveResponsible(?Collection $people = null, ?Collection $allOffices = null): array
    {
        $currentOffice = $this;
        $traversed = [];
        $visitedIds = [];
        $stopwords = ['DE', 'LA', 'DEL', 'Y', 'EL', 'LOS', 'LAS', 'EN', 'O', 'E', 'OFICINA', 'GENERAL', 'SUBGERENCIA', 'GERENCIA'];

        while ($currentOffice) {
            if (in_array($currentOffice->id, $visitedIds, true)) {
                break;
            }
            $visitedIds[] = $currentOffice->id;
            $traversed[] = $currentOffice->name;
            $acronym = $currentOffice->acronym;
            $person = null;

            if ($people !== null) {
                // Modo optimizado en memoria
                if (!empty($acronym)) {
                    $person = $people->filter(function ($p) use ($acronym) {
                        return ($p->is_active ?? true)
                            && ($p->labor_status === null || $p->labor_status === 'ACTIVO')
                            && str_contains($p->office ?? '', '[' . $acronym . ']');
                    })->sortBy(fn($p) => $this->getPositionPriority($p->position ?? ''))->first();
                }

                if (!$person) {
                    $person = $people->filter(function ($p) use ($currentOffice) {
                        return ($p->is_active ?? true)
                            && ($p->labor_status === null || $p->labor_status === 'ACTIVO')
                            && str_contains(strtoupper($p->office ?? ''), strtoupper($currentOffice->name));
                    })->sortBy(fn($p) => $this->getPositionPriority($p->position ?? ''))->first();
                }

                if (!$person) {
                    $words = array_diff(explode(' ', strtoupper($currentOffice->name)), $stopwords);
                    foreach ($words as $w) {
                        $w = trim($w);
                        if (strlen($w) >= 5) {
                            $p = $people->filter(function ($item) use ($w) {
                                return ($item->is_active ?? true)
                                    && ($item->labor_status === null || $item->labor_status === 'ACTIVO')
                                    && str_contains(strtoupper($item->office ?? ''), $w);
                            })->sortBy(fn($item) => $this->getPositionPriority($item->position ?? ''))->first();

                            if ($p) {
                                $person = $p;
                                break;
                            }
                        }
                    }
                }
            } else {
                // Modo consulta directa a base de datos
                if (!empty($acronym)) {
                    $person = Person::where('is_active', true)
                        ->where(function ($q) {
                            $q->whereNull('labor_status')->orWhere('labor_status', 'ACTIVO');
                        })
                        ->where('office', 'like', "%[{$acronym}]%")
                        ->orderByRaw("CASE 
                            WHEN UPPER(position) LIKE '%ALCALDE%' THEN 1 
                            WHEN UPPER(position) LIKE '%GERENTE MUNICIPAL%' THEN 2 
                            WHEN UPPER(position) LIKE '%SECRETARIO GENERAL%' THEN 3 
                            WHEN UPPER(position) LIKE '%GERENTE%' THEN 4 
                            WHEN UPPER(position) LIKE '%SUBGERENTE%' THEN 5 
                            WHEN UPPER(position) LIKE '%JEFE%' THEN 6 
                            WHEN UPPER(position) LIKE '%DIRECTOR%' THEN 7 
                            WHEN UPPER(position) LIKE '%ENCARGADO%' THEN 8 
                            ELSE 9 END")
                        ->first();
                }

                if (!$person) {
                    $person = Person::where('is_active', true)
                        ->where(function ($q) {
                            $q->whereNull('labor_status')->orWhere('labor_status', 'ACTIVO');
                        })
                        ->where('office', 'like', "%{$currentOffice->name}%")
                        ->orderByRaw("CASE 
                            WHEN UPPER(position) LIKE '%ALCALDE%' THEN 1 
                            WHEN UPPER(position) LIKE '%GERENTE MUNICIPAL%' THEN 2 
                            WHEN UPPER(position) LIKE '%SECRETARIO GENERAL%' THEN 3 
                            WHEN UPPER(position) LIKE '%GERENTE%' THEN 4 
                            WHEN UPPER(position) LIKE '%SUBGERENTE%' THEN 5 
                            WHEN UPPER(position) LIKE '%JEFE%' THEN 6 
                            WHEN UPPER(position) LIKE '%DIRECTOR%' THEN 7 
                            WHEN UPPER(position) LIKE '%ENCARGADO%' THEN 8 
                            ELSE 9 END")
                        ->first();
                }

                if (!$person) {
                    $words = array_diff(explode(' ', strtoupper($currentOffice->name)), $stopwords);
                    foreach ($words as $w) {
                        $w = trim($w);
                        if (strlen($w) >= 5) {
                            $p = Person::where('is_active', true)
                                ->where(function ($q) {
                                    $q->whereNull('labor_status')->orWhere('labor_status', 'ACTIVO');
                                })
                                ->where('office', 'like', "%{$w}%")
                                ->orderByRaw("CASE 
                                    WHEN UPPER(position) LIKE '%ALCALDE%' THEN 1 
                                    WHEN UPPER(position) LIKE '%GERENTE MUNICIPAL%' THEN 2 
                                    WHEN UPPER(position) LIKE '%SECRETARIO GENERAL%' THEN 3 
                                    WHEN UPPER(position) LIKE '%GERENTE%' THEN 4 
                                    WHEN UPPER(position) LIKE '%SUBGERENTE%' THEN 5 
                                    WHEN UPPER(position) LIKE '%JEFE%' THEN 6 
                                    WHEN UPPER(position) LIKE '%DIRECTOR%' THEN 7 
                                    WHEN UPPER(position) LIKE '%ENCARGADO%' THEN 8 
                                    ELSE 9 END")
                                ->first();
                            if ($p) {
                                $person = $p;
                                break;
                            }
                        }
                    }
                }
            }

            if ($person) {
                $isInherited = ($currentOffice->id !== $this->id);
                return [
                    'person' => $person,
                    'name' => trim("{$person->first_name} {$person->paternal_surname} {$person->maternal_surname}"),
                    'phone' => $person->phone ?: 'Anexo 115',
                    'email' => $person->email ?: (strtolower(str_replace(' ', '', $person->first_name)) . '@castilla.gob.pe'),
                    'position' => $person->position,
                    'is_inherited' => $isInherited,
                    'inherited_from' => $isInherited ? $currentOffice->name : null,
                    'inherited_from_acronym' => $isInherited ? $currentOffice->acronym : null,
                    'hierarchy_path' => $traversed,
                ];
            }

            // Escalar a la oficina superior jerárquica de forma segura contra lazy loading
            if ($allOffices && $currentOffice->parent_id) {
                $currentOffice = $allOffices->firstWhere('id', $currentOffice->parent_id);
            } elseif ($currentOffice->parent_id) {
                $currentOffice = Office::find($currentOffice->parent_id);
            } else {
                $currentOffice = null;
            }
        }

        return [
            'person' => null,
            'name' => 'ADMINISTRADOR DEL SISTEMA SIGM',
            'phone' => 'Anexo 115',
            'email' => 'usuario@castilla.gob.pe',
            'position' => 'ADMINISTRACIÓN CENTRAL',
            'is_inherited' => true,
            'inherited_from' => 'Alcaldía / Despacho de Gerencia Municipal',
            'inherited_from_acronym' => 'MDC',
            'hierarchy_path' => $traversed,
        ];
    }

    protected function getPositionPriority(string $position): int
    {
        $pos = strtoupper($position);
        if (str_contains($pos, 'ALCALDE')) return 1;
        if (str_contains($pos, 'GERENTE MUNICIPAL')) return 2;
        if (str_contains($pos, 'SECRETARIO GENERAL')) return 3;
        if (str_contains($pos, 'GERENTE')) return 4;
        if (str_contains($pos, 'SUBGERENTE')) return 5;
        if (str_contains($pos, 'JEFE')) return 6;
        if (str_contains($pos, 'DIRECTOR')) return 7;
        if (str_contains($pos, 'ENCARGADO') || str_contains($pos, 'SUPERVISOR')) return 8;
        return 9;
    }
}
