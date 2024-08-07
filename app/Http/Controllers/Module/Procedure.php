<?php

namespace App\Http\Controllers\Module;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Procedure extends Controller
{
    public function store($data) {
        return [
            "resourceType" => "Procedure",
            "status" => "completed",
            "category" => [
                "coding" => [
                    [
                        "system" => "http://snomed.info/sct",
                        "code" => "103693007",
                        "display" => "Diagnostic procedure"
                    ]
                ],
                "text" => "Diagnostic procedure"
            ],
            "code" => [
                "coding" => [
                    [
                        "system" => "http://hl7.org/fhir/sid/icd-9-cm",
                        "code" => "87.44",
                        "display" => "Routine chest x-ray, so described"
                    ]
                ]
            ],
            "subject" => [
                "reference" => "Patient/100000030009",
                "display" => "Budi Santoso"
            ],
            "encounter" => [
                "reference" => "Encounter/{{Encounter_uuid}}",
                "display" => "Tindakan Rontgen Dada Budi Santoso pada Selasa tanggal 14 Juni 2022"
            ],
            "performedPeriod" => [
                "start" => "2022-06-14T13:31:00+01:00",
                "end" => "2022-06-14T14:27:00+01:00"
            ],
            "performer" => [
                [
                    "actor" => [
                        "reference" => "Practitioner/N10000001",
                        "display" => "Dokter Bronsig"
                    ]
                ]
            ],
            "reasonCode" => [
                [
                    "coding" => [
                        [
                            "system" => "http://hl7.org/fhir/sid/icd-10",
                            "code" => "A15.0",
                            "display" => "Tuberculosis of lung, confirmed by sputum microscopy with or without culture"
                        ]
                    ]
                ]
            ],
            "bodySite" => [
                [
                    "coding" => [
                        [
                            "system" => "http://snomed.info/sct",
                            "code" => "302551006",
                            "display" => "Entire Thorax"
                        ]
                    ]
                ]
            ],
            "note" => [
                [
                    "text" => "Rontgen thorax melihat perluasan infiltrat dan kavitas."
                ]
            ]
        ];
    }

}
