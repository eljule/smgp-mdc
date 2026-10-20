<?php

declare(strict_types=1);

namespace App\Actions\Admin;

use App\Models\Person;
use Illuminate\Support\Facades\DB;

final class GetPeopleCatalogAction
{
    public const PEOPLE_DATA = [
        [
            'id' => 1,
            'type' => 'PERSONAL',
            'documentType' => 'DNI',
            'documentNumber' => '02821801',
            'fullName' => 'CARLOS EDUARDO ROJAS MATIAS',
            'email' => null,
            'phone' => null,
            'position' => 'PROCURADURADOR PUBLICA MUNICIPAL',
            'office' => '[PPM] PROCURADURÍA PÚBLICA MUNICIPAL',
            'laborCondition' => 'CAS',
            'isActive' => true,
            'laborStatus' => 'ACTIVO',
        ],
        [
            'id' => 2,
            'type' => 'PERSONAL',
            'documentType' => 'DNI',
            'documentNumber' => '02852121',
            'fullName' => 'EMMA FLOR SAAVEDRA PACHERREZ',
            'email' => null,
            'phone' => null,
            'position' => 'JEFE DEL ORGANO DE CONTROL INSTITUCIONAL',
            'office' => '[OCI] ÓRGANO DE CONTROL INSTITUCIONAL',
            'laborCondition' => 'CAS',
            'isActive' => true,
            'laborStatus' => 'ACTIVO',
        ],
        [
            'id' => 3,
            'type' => 'PERSONAL',
            'documentType' => 'DNI',
            'documentNumber' => '43268255',
            'fullName' => 'ROBERTO PABLO BARRERA ZUÑIGA',
            'email' => null,
            'phone' => null,
            'position' => 'SUBGERENTE DE SERENAZGO',
            'office' => '[SGS] SUBGERENCIA DE SERENAZGO',
            'laborCondition' => 'CAS - CONFIANZA',
            'isActive' => true,
            'laborStatus' => 'ACTIVO',
        ],
        [
            'id' => 4,
            'type' => 'PERSONAL',
            'documentType' => 'DNI',
            'documentNumber' => '43465876',
            'fullName' => 'RICARDO FELIX MARTINEZ CÓNDOR',
            'email' => null,
            'phone' => null,
            'position' => 'GERENTE DE SEGURIDAD CIUDADANA',
            'office' => '[GSC] GERENCIA SEGURIDAD CIUDADANA',
            'laborCondition' => 'CAS - CONFIANZA',
            'isActive' => true,
            'laborStatus' => 'ACTIVO',
        ],
        [
            'id' => 5,
            'type' => 'PERSONAL',
            'documentType' => 'DNI',
            'documentNumber' => '02830165',
            'fullName' => 'GUSTAVO ADOLFO PAREDES SHIMOKAWA',
            'email' => null,
            'phone' => null,
            'position' => 'SUBGERENTE DE TRANSPORTE,TRANSITO Y VIALIDAD',
            'office' => '[STTV] SUBGERENCIA DE TRANSPORTE, TRÁNSITO Y VIALIDAD',
            'laborCondition' => 'CAS - CONFIANZA',
            'isActive' => true,
            'laborStatus' => 'ACTIVO',
        ],
        [
            'id' => 6,
            'type' => 'PERSONAL',
            'documentType' => 'DNI',
            'documentNumber' => '16680162',
            'fullName' => 'JOSE FERNANDO RAMIREZ MORENO',
            'email' => null,
            'phone' => null,
            'position' => 'JEFE DE LA UNIDAD DE ORNATO PARQUES Y JARDINES',
            'office' => '[UPJ] UNIDAD DE PARQUES Y JARDINES',
            'laborCondition' => 'CAS',
            'isActive' => true,
            'laborStatus' => 'ACTIVO',
        ],
        [
            'id' => 7,
            'type' => 'PERSONAL',
            'documentType' => 'DNI',
            'documentNumber' => '02657504',
            'fullName' => 'WILLIAM OSWALDO ROJAS MERINO',
            'email' => null,
            'phone' => null,
            'position' => 'JEFE DE LA UNIDAD DE LIMPIEZA PUBLICA',
            'office' => '[ULP] UNIDAD DE LIMPIEZA PÚBLICA',
            'laborCondition' => 'CAS',
            'isActive' => true,
            'laborStatus' => 'ACTIVO',
        ],
        [
            'id' => 8,
            'type' => 'PERSONAL',
            'documentType' => 'DNI',
            'documentNumber' => '03386696',
            'fullName' => 'JUAN RUPERTO VILELA ESPINOZA',
            'email' => null,
            'phone' => null,
            'position' => 'SUBGERENTE DE GESTION AMBIENTAL',
            'office' => '[SGGA] SUBGERENCIA DE GESTIÓN AMBIENTAL',
            'laborCondition' => 'CAS - CONFIANZA',
            'isActive' => true,
            'laborStatus' => 'ACTIVO',
        ],
        [
            'id' => 9,
            'type' => 'PERSONAL',
            'documentType' => 'DNI',
            'documentNumber' => '02783056',
            'fullName' => 'LUIS RICARDO LLACSAHUANGA TIMOTEO',
            'email' => null,
            'phone' => null,
            'position' => 'GERENCIA DE SERVICIOS PUBLICOS',
            'office' => '[GSP] GERENCIA DE SERVICIOS PÚBLICOS',
            'laborCondition' => 'CAS - CONFIANZA',
            'isActive' => true,
            'laborStatus' => 'ACTIVO',
        ],
        [
            'id' => 10,
            'type' => 'PERSONAL',
            'documentType' => 'DNI',
            'documentNumber' => '40774696',
            'fullName' => 'DORIS LOPEZ GARCIA',
            'email' => null,
            'phone' => null,
            'position' => 'JEFE DE ULE',
            'office' => '[ULE] OFICINA DE LA UNIDAD LOCAL DE EMPADRONAMIENTO',
            'laborCondition' => 'CAS',
            'isActive' => true,
            'laborStatus' => 'ACTIVO',
        ],
        [
            'id' => 11,
            'type' => 'PERSONAL',
            'documentType' => 'DNI',
            'documentNumber' => '40677673',
            'fullName' => 'JACK TERREROS GARAY',
            'email' => null,
            'phone' => null,
            'position' => 'SUBGERENTE DE REGISTRO CIVIL, POBLACIÓN Y SALUD',
            'office' => '[SRCPPS] SUBGERENCIA DE REGISTRO CIVIL, POBLACIÓN Y PROMOCIÓN DE LA SALUD',
            'laborCondition' => 'CAS - CONFIANZA',
            'isActive' => true,
            'laborStatus' => 'ACTIVO',
        ],
        [
            'id' => 12,
            'type' => 'PERSONAL',
            'documentType' => 'DNI',
            'documentNumber' => '44385851',
            'fullName' => 'KAREN STHEFANY FEBRE ZEGARRA',
            'email' => null,
            'phone' => null,
            'position' => 'COORDINADOR OMAPED',
            'office' => '[OMAPED] OFICINA MUNICIPAL DE ATENCIÓN A LA PERSONA CON DISCAPACIDAD',
            'laborCondition' => 'CAS',
            'isActive' => true,
            'laborStatus' => 'ACTIVO',
        ],
        [
            'id' => 13,
            'type' => 'PERSONAL',
            'documentType' => 'DNI',
            'documentNumber' => '02874839',
            'fullName' => 'CAROL ELAINE MORALES REBAZA',
            'email' => null,
            'phone' => null,
            'position' => 'COORDINADOR DE DEMUNA',
            'office' => '[DEMUNA] DEFENSORÍA MUNICIPAL DEL NIÑO Y DEL ADOLESCENTE',
            'laborCondition' => 'CAS',
            'isActive' => true,
            'laborStatus' => 'ACTIVO',
        ],
        [
            'id' => 14,
            'type' => 'PERSONAL',
            'documentType' => 'DNI',
            'documentNumber' => '44432136',
            'fullName' => 'ROXANA MARLENY NEYRA ALVARADO',
            'email' => null,
            'phone' => null,
            'position' => 'COORDINADORA DEL CENTRO INTEGRAL DE ATENCIÓN AL ADULTO MAYOR - CIAM',
            'office' => '[CIAM] CENTRO INTEGRAL DE ATENCIÓN AL ADULTO MAYOR',
            'laborCondition' => 'CAS',
            'isActive' => true,
            'laborStatus' => 'ACTIVO',
        ],
        [
            'id' => 15,
            'type' => 'PERSONAL',
            'documentType' => 'DNI',
            'documentNumber' => '47190033',
            'fullName' => 'HILARION PALACIOS SEMINARIO',
            'email' => null,
            'phone' => null,
            'position' => 'SUBGERENTE DE INCLUSION SOCIAL',
            'office' => '[SGIS] SUBGERENCIA DE INCLUSIÓN SOCIAL',
            'laborCondition' => 'CAS - CONFIANZA',
            'isActive' => true,
            'laborStatus' => 'ACTIVO',
        ],
        [
            'id' => 16,
            'type' => 'PERSONAL',
            'documentType' => 'DNI',
            'documentNumber' => '03130676',
            'fullName' => 'GLADYS RAQUEL CÓRDOVA ALCARAZO',
            'email' => null,
            'phone' => null,
            'position' => 'SUBGERENTE DE EDUCACION,CULTURA DEPORTE Y RECREACION',
            'office' => '[SECDR] SUBGERENCIA DE EDUCACIÓN, CULTURA, DEPORTE Y RECREACIÓN',
            'laborCondition' => 'CAS - CONFIANZA',
            'isActive' => true,
            'laborStatus' => 'ACTIVO',
        ],
        [
            'id' => 17,
            'type' => 'PERSONAL',
            'documentType' => 'DNI',
            'documentNumber' => '02868204',
            'fullName' => 'VICTOR ELIAS AZABACHE MONZON',
            'email' => null,
            'phone' => null,
            'position' => 'GERENTE DE DESARROLLO HUMANO',
            'office' => '[GDH] GERENCIA DE DESARROLLO HUMANO',
            'laborCondition' => 'CAS - CONFIANZA',
            'isActive' => true,
            'laborStatus' => 'ACTIVO',
        ],
        [
            'id' => 18,
            'type' => 'PERSONAL',
            'documentType' => 'DNI',
            'documentNumber' => '42260564',
            'fullName' => 'DAVID ANIBAL GUEVARA BENITES',
            'email' => null,
            'phone' => null,
            'position' => 'SUBGERENTE DE PROMOCION TURISTICA EMPRESARIAL E INVERSIONES',
            'office' => '[SGPTEI] SUBGERENCIA DE PROMOCIÓN TURÍSTICA, EMPRESARIAL E INVERSIONES',
            'laborCondition' => 'CAS - CONFIANZA',
            'isActive' => true,
            'laborStatus' => 'ACTIVO',
        ],
        [
            'id' => 19,
            'type' => 'PERSONAL',
            'documentType' => 'DNI',
            'documentNumber' => '02866917',
            'fullName' => 'JOSE ZACARIAS CASTILLO ROMERO',
            'email' => null,
            'phone' => null,
            'position' => 'SUBGERENTE DE COMERCIALIZACION',
            'office' => '[SGC] SUBGERENCIA DE COMERCIALIZACIÓN',
            'laborCondition' => 'CAS',
            'isActive' => true,
            'laborStatus' => 'ACTIVO',
        ],
        [
            'id' => 20,
            'type' => 'PERSONAL',
            'documentType' => 'DNI',
            'documentNumber' => '02780042',
            'fullName' => 'MIGUEL ERNESTO RUIZ GOMEZ',
            'email' => null,
            'phone' => null,
            'position' => 'GERENTE DE DESARROLLO ECONOMICO LOCAL',
            'office' => '[GDEL] GERENCIA DE DESARROLLO ECONÓMICO LOCAL',
            'laborCondition' => 'CAS - CONFIANZA',
            'isActive' => true,
            'laborStatus' => 'ACTIVO',
        ],
        [
            'id' => 21,
            'type' => 'PERSONAL',
            'documentType' => 'DNI',
            'documentNumber' => '02659686',
            'fullName' => 'MARTIN NICOLAS ADANAQUE YARLEQUE',
            'email' => null,
            'phone' => null,
            'position' => 'SUBGERENTE DE GESTION DEL RIESGO DE DESASTRES',
            'office' => '[SGGRD] SUBGERENCIA DE GESTIÓN DEL RIESGO DE DESASTRES',
            'laborCondition' => 'CAS - CONFIANZA',
            'isActive' => true,
            'laborStatus' => 'ACTIVO',
        ],
        [
            'id' => 22,
            'type' => 'PERSONAL',
            'documentType' => 'DNI',
            'documentNumber' => '70068007',
            'fullName' => 'AIDA MARLENE CHIRITO FLORES',
            'email' => null,
            'phone' => null,
            'position' => 'SUBGERENTE DE SANEAMIENTO FISICO LEGAL',
            'office' => '[SGSFL] SUBGERENCIA DE SANEAMIENTO FÍSICO LEGAL',
            'laborCondition' => 'CAS - CONFIANZA',
            'isActive' => true,
            'laborStatus' => 'ACTIVO',
        ],
        [
            'id' => 23,
            'type' => 'PERSONAL',
            'documentType' => 'DNI',
            'documentNumber' => '02897042',
            'fullName' => 'HERNAN CORNEJO RUIZ',
            'email' => null,
            'phone' => null,
            'position' => 'SUBGERENTE DE CATASTRO',
            'office' => '[SGC] SUBGERENCIA DE CATASTRO',
            'laborCondition' => 'CAS - CONFIANZA',
            'isActive' => true,
            'laborStatus' => 'ACTIVO',
        ],
        [
            'id' => 24,
            'type' => 'PERSONAL',
            'documentType' => 'DNI',
            'documentNumber' => '43274346',
            'fullName' => 'WALTER OLGUER ESCOBAR RUGEL',
            'email' => null,
            'phone' => null,
            'position' => 'SUBGERENTE DE OBRAS',
            'office' => '[SGO] SUBGERENCIA DE OBRAS',
            'laborCondition' => 'CAS - CONFIANZA',
            'isActive' => true,
            'laborStatus' => 'ACTIVO',
        ],
        [
            'id' => 25,
            'type' => 'PERSONAL',
            'documentType' => 'DNI',
            'documentNumber' => '46549781',
            'fullName' => 'JUANA ELIZABETH ELIAS GIRON',
            'email' => null,
            'phone' => null,
            'position' => 'SUBGERENTE DE ESTUDIOS Y PROYECTOS DE INVERSIÓN PÚBLICA',
            'office' => '[SEPIP] SUBGERENCIA DE ESTUDIOS Y PROYECTOS DE INVERSIÓN PÚBLICA',
            'laborCondition' => 'CAS - CONFIANZA',
            'isActive' => true,
            'laborStatus' => 'ACTIVO',
        ],
        [
            'id' => 26,
            'type' => 'PERSONAL',
            'documentType' => 'DNI',
            'documentNumber' => '48525061',
            'fullName' => 'ANA ISABEL GUERRERO FLORES',
            'email' => null,
            'phone' => null,
            'position' => 'SUBGERENTE DE FORMULACION DE PROYECTOS DE INVERSIÓN PÚBLICA',
            'office' => '[SFPIP] SUBGERENCIA DE FORMULACIÓN DE PROYECTOS DE INVERSIÓN PÚBLICA',
            'laborCondition' => 'CAS - CONFIANZA',
            'isActive' => true,
            'laborStatus' => 'ACTIVO',
        ],
        [
            'id' => 27,
            'type' => 'PERSONAL',
            'documentType' => 'DNI',
            'documentNumber' => '47880494',
            'fullName' => 'JORDY FABIÁN PANTA SALDARRIAGA',
            'email' => null,
            'phone' => null,
            'position' => 'GERENTE DE DESARROLLO URBANO - RURAL INFRAESTRUCTURA',
            'office' => '[GDURI] GERENCIA DE DESARROLLO URBANO-RURAL E INFRAESTRUCTURA',
            'laborCondition' => 'CAS - CONFIANZA',
            'isActive' => true,
            'laborStatus' => 'ACTIVO',
        ],
        [
            'id' => 28,
            'type' => 'PERSONAL',
            'documentType' => 'DNI',
            'documentNumber' => '48431189',
            'fullName' => 'GEISSLER MILAGROS GAONA JIMENEZ',
            'email' => null,
            'phone' => null,
            'position' => 'SUBGERENTE DE FISCALIZACION',
            'office' => '[SGF] SUBGERENCIA DE FISCALIZACIÓN',
            'laborCondition' => 'CAS - CONFIANZA',
            'isActive' => true,
            'laborStatus' => 'ACTIVO',
        ],
        [
            'id' => 29,
            'type' => 'PERSONAL',
            'documentType' => 'DNI',
            'documentNumber' => '03662224',
            'fullName' => 'BENNY ESTUARDO GONZALES CORDOVA',
            'email' => null,
            'phone' => null,
            'position' => 'EJECUTOR COACTIVO',
            'office' => '[EC] EJECUTORÍA COACTIVA',
            'laborCondition' => 'NOMBRADO D.L. 276',
            'isActive' => true,
            'laborStatus' => 'ACTIVO',
        ],
        [
            'id' => 30,
            'type' => 'PERSONAL',
            'documentType' => 'DNI',
            'documentNumber' => '45997806',
            'fullName' => 'MILAGROS DEL ROSARIO CRUZ RAMOS',
            'email' => null,
            'phone' => null,
            'position' => 'SUBGERENTE DE RECAUDACION',
            'office' => '[SGR] SUBGERENCIA DE RECAUDACIÓN',
            'laborCondition' => 'CAS - CONFIANZA',
            'isActive' => true,
            'laborStatus' => 'ACTIVO',
        ],
        [
            'id' => 31,
            'type' => 'PERSONAL',
            'documentType' => 'DNI',
            'documentNumber' => '42747895',
            'fullName' => 'CINTHYA KATTY FERRO COLUMBUS',
            'email' => null,
            'phone' => null,
            'position' => 'GERENTE DE ADMINISTRACION TRIBUTARIA',
            'office' => '[GAT] GERENCIA DE ADMINISTRACIÓN TRIBUTARIA',
            'laborCondition' => 'NOMBRADO D.L. 276',
            'isActive' => true,
            'laborStatus' => 'ACTIVO',
        ],
        [
            'id' => 32,
            'type' => 'PERSONAL',
            'documentType' => 'DNI',
            'documentNumber' => '45138140',
            'fullName' => 'HECTOR ARON RIVERA CALVA',
            'email' => null,
            'phone' => null,
            'position' => 'JEFE DE LA OFICINA DE MODERNIZACION INSTITUCIONAL Y ESTADISTICA',
            'office' => '[OMIE] OFICINA DE MODERNIZACIÓN INSTITUCIONAL Y ESTADÍSTICA',
            'laborCondition' => 'CAS - CONFIANZA',
            'isActive' => true,
            'laborStatus' => 'ACTIVO',
        ],
        [
            'id' => 33,
            'type' => 'PERSONAL',
            'documentType' => 'DNI',
            'documentNumber' => '16792978',
            'fullName' => 'MIGUEL RUIZ TOCTO',
            'email' => null,
            'phone' => null,
            'position' => 'JEFE DE LA OFICINA GENERAL DE PLANEAMIENTO Y PRESUPUESTO',
            'office' => '[OGPP] OFICINA GENERAL DE PLANEAMIENTO Y PRESUPUESTO',
            'laborCondition' => 'CAS - CONFIANZA',
            'isActive' => true,
            'laborStatus' => 'ACTIVO',
        ],
        [
            'id' => 34,
            'type' => 'PERSONAL',
            'documentType' => 'DNI',
            'documentNumber' => '46363924',
            'fullName' => 'KARINA RAQUEL FACHO GUERRERO',
            'email' => null,
            'phone' => null,
            'position' => 'JEFE DE LA OFICINA GENERAL DE ASESORIA JURIDICA',
            'office' => '[OGAJ] OFICINA GENERAL DE ASESORÍA JURÍDICA',
            'laborCondition' => 'CAS - CONFIANZA',
            'isActive' => true,
            'laborStatus' => 'ACTIVO',
        ],
        [
            'id' => 35,
            'type' => 'PERSONAL',
            'documentType' => 'DNI',
            'documentNumber' => '10783864',
            'fullName' => 'JAIME HENRY SANDOVAL NIEVES',
            'email' => null,
            'phone' => null,
            'position' => 'JEFE DE LA OFICINA DE DESARROLLO TECNOLOGICO',
            'office' => '[ODT] OFICINA DE DESARROLLO TECNOLÓGICO',
            'laborCondition' => 'CAS - CONFIANZA',
            'isActive' => true,
            'laborStatus' => 'ACTIVO',
        ],
        [
            'id' => 36,
            'type' => 'PERSONAL',
            'documentType' => 'DNI',
            'documentNumber' => '40404645',
            'fullName' => 'JULIO CÉSAR GUZMÁN RONDOY',
            'email' => null,
            'phone' => null,
            'position' => 'JEFE DE LA OFICINA DE TESORERIA',
            'office' => '[OT] OFICINA DE TESORERÍA',
            'laborCondition' => 'CAS - CONFIANZA',
            'isActive' => true,
            'laborStatus' => 'ACTIVO',
        ],
        [
            'id' => 37,
            'type' => 'PERSONAL',
            'documentType' => 'DNI',
            'documentNumber' => '43556955',
            'fullName' => 'DANI DANIEL VILLEGAS PURIZACA',
            'email' => null,
            'phone' => null,
            'position' => 'JEFE DE LA OFICINA DE CONTABILIDAD Y COSTOS',
            'office' => '[OCC] OFICINA DE CONTABILIDAD Y COSTOS',
            'laborCondition' => 'CAS - CONFIANZA',
            'isActive' => true,
            'laborStatus' => 'ACTIVO',
        ],
        [
            'id' => 38,
            'type' => 'PERSONAL',
            'documentType' => 'DNI',
            'documentNumber' => '05641940',
            'fullName' => 'JULIO CESAR BAYONA YARLEQUE',
            'email' => null,
            'phone' => null,
            'position' => 'JEFE DE LA UNIDAD DE ALMACEN',
            'office' => '[OACG] OFICINA DE ALMACEN CENTRAL GENERAL',
            'laborCondition' => 'CAS - CONFIANZA',
            'isActive' => true,
            'laborStatus' => 'ACTIVO',
        ],
        [
            'id' => 39,
            'type' => 'PERSONAL',
            'documentType' => 'DNI',
            'documentNumber' => '00328619',
            'fullName' => 'JUAN KEHUARUCHO SAAVEDRA',
            'email' => null,
            'phone' => null,
            'position' => 'JEFE DE LA OFICINA DE ABASTECIMIENTO Y CONTROL PATRIMONIAL',
            'office' => '[OACP] OFICINA DE ABASTECIMIENTOS Y CONTROL PATRIMONIAL',
            'laborCondition' => 'CAS - CONFIANZA',
            'isActive' => true,
            'laborStatus' => 'ACTIVO',
        ],
        [
            'id' => 40,
            'type' => 'PERSONAL',
            'documentType' => 'DNI',
            'documentNumber' => '41461309',
            'fullName' => 'GUILLERMO MARTÍN RODRIGUEZ PALACIOS',
            'email' => null,
            'phone' => null,
            'position' => 'JEFE DE LA OFICINA DE CONTROL DE PERSONAL',
            'office' => '[OR] OFICINA DE REGIDORES',
            'laborCondition' => 'CAS - CONFIANZA',
            'isActive' => true,
            'laborStatus' => 'ACTIVO',
        ],
        [
            'id' => 41,
            'type' => 'PERSONAL',
            'documentType' => 'DNI',
            'documentNumber' => '02833465',
            'fullName' => 'JULIO ALBERTO LIZANO HUAMAN',
            'email' => null,
            'phone' => null,
            'position' => 'JEFE DE LA OFICINA GENERAL DE ADMINISTRACION Y FINANZAS',
            'office' => '[OGAF] OFICINA GENERAL DE ADMINISTRACIÓN Y FINANZAS',
            'laborCondition' => 'CAS - CONFIANZA',
            'isActive' => true,
            'laborStatus' => 'ACTIVO',
        ],
        [
            'id' => 42,
            'type' => 'PERSONAL',
            'documentType' => 'DNI',
            'documentNumber' => '46797120',
            'fullName' => 'JHON DEYVI INGA LITANO',
            'email' => null,
            'phone' => null,
            'position' => 'JEFE DE LA OFICINA DE COMUNICACION SOCIAL Y RELACIONES PUBLICAS',
            'office' => '[OCSRP] OFICINA DE COMUNICACIÓN SOCIAL Y RELACIONES PÚBLICAS',
            'laborCondition' => 'CAS - CONFIANZA',
            'isActive' => true,
            'laborStatus' => 'ACTIVO',
        ],
        [
            'id' => 43,
            'type' => 'PERSONAL',
            'documentType' => 'DNI',
            'documentNumber' => '42712478',
            'fullName' => 'CESAR IVAN MAZA SEMINARIO',
            'email' => null,
            'phone' => null,
            'position' => 'JEFE DE LA OFICINA DE GESTION DOCUMENTARIA Y ATENCION AL CIUDADANO',
            'office' => '[OGDAC] OFICINA DE GESTIÓN DOCUMENTARIA Y ATENCIÓN AL CIUDADANO',
            'laborCondition' => 'CAS - CONFIANZA',
            'isActive' => true,
            'laborStatus' => 'ACTIVO',
        ],
        [
            'id' => 44,
            'type' => 'PERSONAL',
            'documentType' => 'DNI',
            'documentNumber' => '42697940',
            'fullName' => 'PABLO MIGUEL GIRÓN ESPINOZA',
            'email' => null,
            'phone' => null,
            'position' => 'SECRETARIO GENERAL',
            'office' => '[SG] SECRETARÍA GENERAL',
            'laborCondition' => 'CAS - CONFIANZA',
            'isActive' => true,
            'laborStatus' => 'ACTIVO',
        ],
        [
            'id' => 45,
            'type' => 'PERSONAL',
            'documentType' => 'DNI',
            'documentNumber' => '43139417',
            'fullName' => 'ROGGER MICHAEL RAMIREZ ROMAN',
            'email' => null,
            'phone' => null,
            'position' => 'GERENTE MUNICIPAL',
            'office' => '[GM] GERENCIA MUNICIPAL',
            'laborCondition' => 'CAS - CONFIANZA',
            'isActive' => true,
            'laborStatus' => 'ACTIVO',
        ],
        [
            'id' => 46,
            'type' => 'PERSONAL',
            'documentType' => 'DNI',
            'documentNumber' => '02665436',
            'fullName' => 'JULIO CESAR VASQUEZ ALBERCA',
            'email' => null,
            'phone' => null,
            'position' => 'ALCALDE',
            'office' => '[ALC] ALCALDÍA',
            'laborCondition' => 'SIN MODALIDAD',
            'isActive' => true,
            'laborStatus' => 'ACTIVO',
        ],
    ];

    /**
     * Normalize string for accent-insensitive and case-insensitive comparison.
     */
    public static function normalizeString(string $text): string
    {
        $accents = ['á', 'é', 'í', 'ó', 'ú', 'ü', 'ñ', 'Á', 'É', 'Í', 'Ó', 'Ú', 'Ü', 'Ñ'];
        $clean   = ['a', 'e', 'i', 'o', 'u', 'u', 'n', 'a', 'e', 'i', 'o', 'u', 'u', 'n'];

        return mb_strtolower(str_replace($accents, $clean, trim($text)));
    }

    /**
     * Retrieve paginated people catalog with filters.
     * Queries the database if records exist, otherwise uses built-in master dataset.
     *
     * @return array<string, mixed>
     */
    public function handle(
        string $filterType = 'TODOS',
        string $search = '',
        string $office = '',
        int $page = 1,
        int $perPage = 10
    ): array {
        if (Person::count() > 0) {
            $query = Person::query();

            if ($filterType === 'PERSONAL') {
                $query->where('type', 'PERSONAL');
            } elseif ($filterType === 'ADMINISTRADO') {
                $query->where('type', 'ADMINISTRADO');
            }

            $driver = DB::connection()->getDriverName();
            $isPgsql = $driver === 'pgsql';

            $cleanSearch = self::normalizeString($search);
            if ($cleanSearch !== '') {
                $words = array_values(array_filter(preg_split('/\s+/', $cleanSearch)));

                if (! empty($words)) {
                    $query->where(function ($q) use ($words, $isPgsql) {
                        foreach ($words as $word) {
                            $pattern = "%{$word}%";
                            $q->where(function ($sub) use ($pattern, $isPgsql) {
                                if ($isPgsql) {
                                    $colExpr = fn (string $col) => "translate(LOWER(COALESCE({$col}, '')), 'áéíóúüñ', 'aeiouun')";
                                    $fullNameExpr = "translate(LOWER(CONCAT(COALESCE(first_name, ''), ' ', COALESCE(paternal_surname, ''), ' ', COALESCE(maternal_surname, ''))), 'áéíóúüñ', 'aeiouun')";
                                    $reverseNameExpr = "translate(LOWER(CONCAT(COALESCE(paternal_surname, ''), ' ', COALESCE(maternal_surname, ''), ' ', COALESCE(first_name, ''))), 'áéíóúüñ', 'aeiouun')";

                                    $sub->whereRaw("{$colExpr('document_number')} LIKE ?", [$pattern])
                                        ->orWhereRaw("{$colExpr('document_type')} LIKE ?", [$pattern])
                                        ->orWhereRaw("{$colExpr('first_name')} LIKE ?", [$pattern])
                                        ->orWhereRaw("{$colExpr('paternal_surname')} LIKE ?", [$pattern])
                                        ->orWhereRaw("{$colExpr('maternal_surname')} LIKE ?", [$pattern])
                                        ->orWhereRaw("{$colExpr('business_name')} LIKE ?", [$pattern])
                                        ->orWhereRaw("{$colExpr('position')} LIKE ?", [$pattern])
                                        ->orWhereRaw("{$colExpr('office')} LIKE ?", [$pattern])
                                        ->orWhereRaw("{$fullNameExpr} LIKE ?", [$pattern])
                                        ->orWhereRaw("{$reverseNameExpr} LIKE ?", [$pattern]);
                                } else {
                                    $fullNameExpr = "COALESCE(first_name, '') || ' ' || COALESCE(paternal_surname, '') || ' ' || COALESCE(maternal_surname, '')";
                                    $reverseNameExpr = "COALESCE(paternal_surname, '') || ' ' || COALESCE(maternal_surname, '') || ' ' || COALESCE(first_name, '')";

                                    $sub->where('document_number', 'like', $pattern)
                                        ->orWhere('document_type', 'like', $pattern)
                                        ->orWhere('first_name', 'like', $pattern)
                                        ->orWhere('paternal_surname', 'like', $pattern)
                                        ->orWhere('maternal_surname', 'like', $pattern)
                                        ->orWhere('business_name', 'like', $pattern)
                                        ->orWhere('position', 'like', $pattern)
                                        ->orWhere('office', 'like', $pattern)
                                        ->orWhereRaw("({$fullNameExpr}) LIKE ?", [$pattern])
                                        ->orWhereRaw("({$reverseNameExpr}) LIKE ?", [$pattern]);
                                }
                            });
                        }
                    });
                }
            }

            $trimmedOffice = trim($office);
            if ($trimmedOffice !== '') {
                if (preg_match('/^\[(.*?)\]\s*(.*)$/', $trimmedOffice, $matches)) {
                    $acronym = trim($matches[1]);
                    $name = trim($matches[2]);
                    $query->where(function ($q) use ($trimmedOffice, $acronym, $name, $isPgsql) {
                        if ($isPgsql) {
                            $expr = "translate(LOWER(COALESCE(office, '')), 'áéíóúüñ', 'aeiouun')";
                            $cleanOffice = '%' . self::normalizeString($trimmedOffice) . '%';
                            $cleanAcronym = '%[' . self::normalizeString($acronym) . ']%';
                            $cleanName = '%' . self::normalizeString($name) . '%';
                            $q->whereRaw("{$expr} LIKE ?", [$cleanOffice])
                                ->orWhereRaw("{$expr} LIKE ?", [$cleanAcronym])
                                ->orWhereRaw("{$expr} LIKE ?", [$cleanName]);
                        } else {
                            $q->where('office', 'like', "%{$trimmedOffice}%")
                                ->orWhere('office', 'like', "%[{$acronym}]%")
                                ->orWhere('office', 'like', "%{$name}%");
                        }
                    });
                } else {
                    if ($isPgsql) {
                        $expr = "translate(LOWER(COALESCE(office, '')), 'áéíóúüñ', 'aeiouun')";
                        $cleanOffice = '%' . self::normalizeString($trimmedOffice) . '%';
                        $query->whereRaw("{$expr} LIKE ?", [$cleanOffice]);
                    } else {
                        $query->where('office', 'like', "%{$trimmedOffice}%");
                    }
                }
            }

            $totalCounts = [
                'total' => Person::count(),
                'personal' => Person::where('type', 'PERSONAL')->count(),
                'administrados' => Person::where('type', 'ADMINISTRADO')->count(),
            ];

            $perPage = max(1, $perPage);
            $paginator = $query->orderBy('id', 'asc')->paginate($perPage, ['*'], 'page', $page);

            $items = collect($paginator->items())->map(function (Person $p) {
                $fullName = trim("{$p->first_name} {$p->paternal_surname} {$p->maternal_surname}");
                if (empty($fullName) && ! empty($p->business_name)) {
                    $fullName = $p->business_name;
                }

                return [
                    'id' => $p->id,
                    'type' => $p->type ?? 'PERSONAL',
                    'documentType' => $p->document_type ?? 'DNI',
                    'documentNumber' => $p->document_number ?? '',
                    'firstName' => $p->first_name ?? '',
                    'paternalSurname' => $p->paternal_surname ?? '',
                    'maternalSurname' => $p->maternal_surname ?? '',
                    'businessName' => $p->business_name ?? '',
                    'fullName' => $fullName,
                    'email' => $p->email,
                    'phone' => $p->phone,
                    'address' => $p->address,
                    'position' => $p->position,
                    'office' => $p->office,
                    'laborCondition' => $p->labor_condition ?? 'CAS',
                    'entryDate' => $p->entry_date,
                    'allowsOnlineAccess' => (bool) $p->allows_online_access,
                    'isActive' => (bool) $p->is_active,
                    'laborStatus' => $p->labor_status ?? ($p->is_active ? 'ACTIVO' : 'CESADO'),
                    'departureDate' => $p->departure_date,
                    'cessationReason' => $p->cessation_reason,
                ];
            })->toArray();

            return [
                'items' => $items,
                'total' => $paginator->total(),
                'currentPage' => $paginator->currentPage(),
                'perPage' => $paginator->perPage(),
                'totalPages' => $paginator->lastPage(),
                'from' => $paginator->firstItem() ?? 0,
                'to' => $paginator->lastItem() ?? 0,
                'counts' => $totalCounts,
            ];
        }

        // Fallback for unseeded test environments
        $all = self::PEOPLE_DATA;

        $filtered = array_filter($all, function (array $person) use ($filterType): bool {
            if ($filterType === 'PERSONAL') {
                return $person['type'] === 'PERSONAL';
            }
            if ($filterType === 'ADMINISTRADO') {
                return $person['type'] === 'ADMINISTRADO';
            }

            return true;
        });

        $cleanSearch = self::normalizeString($search);
        if ($cleanSearch !== '') {
            $words = array_values(array_filter(preg_split('/\s+/', $cleanSearch)));
            if (! empty($words)) {
                $filtered = array_filter($filtered, function (array $person) use ($words): bool {
                    $haystack = self::normalizeString(
                        ($person['documentNumber'] ?? '') . ' ' .
                        ($person['documentType'] ?? '') . ' ' .
                        ($person['fullName'] ?? '') . ' ' .
                        ($person['businessName'] ?? '') . ' ' .
                        ($person['position'] ?? '') . ' ' .
                        ($person['office'] ?? '')
                    );

                    foreach ($words as $w) {
                        if (! str_contains($haystack, $w)) {
                            return false;
                        }
                    }

                    return true;
                });
            }
        }

        $trimmedOffice = trim($office);
        if ($trimmedOffice !== '') {
            $cleanOffice = self::normalizeString($trimmedOffice);
            if (preg_match('/^\[(.*?)\]\s*(.*)$/', $trimmedOffice, $matches)) {
                $cleanAcronym = self::normalizeString($matches[1]);
                $cleanName = self::normalizeString($matches[2]);
                $filtered = array_filter($filtered, function (array $person) use ($cleanOffice, $cleanAcronym, $cleanName): bool {
                    $po = self::normalizeString($person['office'] ?? '');

                    return str_contains($po, $cleanOffice) || str_contains($po, "[{$cleanAcronym}]") || str_contains($po, $cleanName);
                });
            } else {
                $filtered = array_filter($filtered, function (array $person) use ($cleanOffice): bool {
                    $po = self::normalizeString($person['office'] ?? '');

                    return str_contains($po, $cleanOffice);
                });
            }
        }

        $total = count($filtered);
        $perPage = max(1, $perPage);
        $totalPages = max(1, (int) ceil($total / $perPage));
        $page = max(1, min($page, $totalPages));

        $offset = ($page - 1) * $perPage;
        $paginated = array_slice(array_values($filtered), $offset, $perPage);

        $from = $total > 0 ? $offset + 1 : 0;
        $to = min($offset + $perPage, $total);

        return [
            'items' => $paginated,
            'total' => $total,
            'currentPage' => $page,
            'perPage' => $perPage,
            'totalPages' => $totalPages,
            'from' => $from,
            'to' => $to,
            'counts' => [
                'total' => 46,
                'personal' => 46,
                'administrados' => 0,
            ],
        ];
    }
}
