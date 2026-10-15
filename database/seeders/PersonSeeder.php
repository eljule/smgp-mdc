<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Actions\Admin\GetPeopleCatalogAction;
use App\Models\Person;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PersonSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = file_exists(base_path("personas.json"))
            ? base_path("personas.json")
            : database_path("data/personas.json");

        $isPgsql = DB::getDriverName() === "pgsql";

        if ($isPgsql) {
            DB::statement("SET session_replication_role = replica;");
        } else {
            Schema::disableForeignKeyConstraints();
        }

        // Borrar completamente la información existente en la base de datos
        DB::table("core_people")->delete();

        if (file_exists($jsonPath)) {
            $data = json_decode((string) file_get_contents($jsonPath), true);
            if (is_array($data)) {
                $now = now();
                $records = [];

                foreach ($data as $item) {
                    $id = isset($item["id"]) ? (int) $item["id"] : null;
                    $docRaw = $item["DOCUMENTO"] ?? "";
                    $docType = "DNI";
                    $docNumber = "";

                    if (str_contains($docRaw, ":")) {
                        [$dt, $dn] = explode(":", $docRaw, 2);
                        $docType = trim($dt);
                        $docNumber = trim($dn);
                    } else {
                        $docNumber = trim($docRaw);
                    }

                    $rawName = trim($item["NOMBRES / RAZÓN SOCIAL"] ?? "");
                    $parts = preg_split("/\s+/", $rawName) ?: [];
                    $firstName = "";
                    $paternal = "";
                    $maternal = null;

                    if (count($parts) >= 3) {
                        $maternal = array_pop($parts);
                        $paternal = array_pop($parts);
                        $firstName = implode(" ", $parts);
                    } elseif (count($parts) === 2) {
                        $firstName = $parts[0];
                        $paternal = $parts[1];
                    } elseif (count($parts) === 1) {
                        $firstName = $parts[0];
                    }

                    $contacto = $item["CONTACTO"] ?? [];
                    $email = !empty($contacto["email"]) ? trim($contacto["email"]) : null;
                    $phone = !empty($contacto["telefono"]) ? trim($contacto["telefono"]) : null;

                    $detalle = $item["DETALLE INSTITUCIONAL / TRÁMITE"] ?? [];
                    $cargo = !empty($detalle["cargo"]) ? trim($detalle["cargo"]) : null;
                    $oficina = !empty($detalle["oficina"]) ? trim($detalle["oficina"]) : null;
                    $condicion = !empty($detalle["condicion_laboral"]) ? trim($detalle["condicion_laboral"]) : null;

                    $estado = strtoupper(trim($item["ESTADO"] ?? "ACTIVO"));
                    $isActive = ($estado === "ACTIVO");
                    $clasificacion = strtoupper(trim($item["CLASIFICACIÓN"] ?? "PERSONAL"));

                    $record = [
                        "type" => $clasificacion,
                        "document_type" => $docType,
                        "document_number" => $docNumber,
                        "first_name" => $firstName,
                        "paternal_surname" => $paternal,
                        "maternal_surname" => $maternal,
                        "email" => $email,
                        "phone" => $phone,
                        "address" => "Castilla, Piura",
                        "position" => $cargo,
                        "office" => $oficina,
                        "labor_condition" => $condicion,
                        "entry_date" => "2023-01-01",
                        "business_name" => null,
                        "allows_online_access" => false,
                        "labor_status" => $estado,
                        "departure_date" => null,
                        "cessation_reason" => null,
                        "is_active" => $isActive,
                        "created_at" => $now,
                        "updated_at" => $now,
                    ];

                    if ($id !== null) {
                        $record["id"] = $id;
                    }

                    $records[] = $record;
                }

                if (!empty($records)) {
                    DB::table("core_people")->insert($records);
                }
            }
        } else {
            // Fallback usando GetPeopleCatalogAction::PEOPLE_DATA
            $now = now();
            $records = [];
            foreach (GetPeopleCatalogAction::PEOPLE_DATA as $item) {
                $parts = preg_split("/\s+/", trim($item["fullName"])) ?: [];
                $firstName = "";
                $paternal = "";
                $maternal = null;

                if (count($parts) >= 3) {
                    $maternal = array_pop($parts);
                    $paternal = array_pop($parts);
                    $firstName = implode(" ", $parts);
                } elseif (count($parts) === 2) {
                    $firstName = $parts[0];
                    $paternal = $parts[1];
                } elseif (count($parts) === 1) {
                    $firstName = $parts[0];
                }

                $records[] = [
                    "id" => $item["id"],
                    "type" => $item["type"],
                    "document_type" => $item["documentType"],
                    "document_number" => $item["documentNumber"],
                    "first_name" => $firstName,
                    "paternal_surname" => $paternal,
                    "maternal_surname" => $maternal,
                    "email" => $item["email"] ?? null,
                    "phone" => $item["phone"] ?? null,
                    "address" => "Castilla, Piura",
                    "position" => $item["position"] ?? null,
                    "office" => $item["office"] ?? null,
                    "labor_condition" => $item["laborCondition"] ?? null,
                    "entry_date" => "2023-01-01",
                    "business_name" => null,
                    "allows_online_access" => false,
                    "labor_status" => $item["laborStatus"] ?? "ACTIVO",
                    "departure_date" => null,
                    "cessation_reason" => null,
                    "is_active" => $item["isActive"] ?? true,
                    "created_at" => $now,
                    "updated_at" => $now,
                ];
            }

            if (!empty($records)) {
                DB::table("core_people")->insert($records);
            }
        }

        // Restablecer secuencia en PostgreSQL si corresponde
        if ($isPgsql) {
            DB::statement("SELECT setval(pg_get_serial_sequence(\x27core_people\x27, \x27id\x27), coalesce(max(id), 0) + 1, false) FROM core_people;");
            DB::statement("SET session_replication_role = DEFAULT;");
        } else {
            Schema::enableForeignKeyConstraints();
        }

        // Asegurar consistencia de llaves foráneas en core_users
        $mendoza = Person::where("document_number", "08192844")->first();
        if ($mendoza) {
            DB::table("core_users")->where("username", "root")->update(["person_id" => $mendoza->id]);
        }
        $perez = Person::where("document_number", "45821903")->first();
        if ($perez) {
            DB::table("core_users")->where("username", "jperez")->update(["person_id" => $perez->id]);
        }
        $gomez = Person::where("document_number", "40192837")->first();
        if ($gomez) {
            DB::table("core_users")->where("username", "mgomez")->update(["person_id" => $gomez->id]);
        }
    }
}
