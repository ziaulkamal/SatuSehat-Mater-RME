<?php

namespace App\Http\Controllers\Module;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ServiceRequestLaboratorium extends Controller
{
    public function store($data) {
        return [
            "resourceType" => "ServiceRequest",
            "identifier" => [
                [
                    "system" => "http://sys-ids.kemkes.go.id/servicerequest/{{Org_id}}",
                    "value" => "00001"
                ]
            ],
            "status" => "active",
            "intent" => "original-order",
            "priority" => "routine",
            "category" => [
                [
                    "coding" => [
                        [
                            "system" => "http://snomed.info/sct",
                            "code" => "108252007",
                            "display" => "Laboratory procedure"
                        ]
                    ]
                ]
            ],
            "code" => [
                "coding" => [
                    [
                        "system" => "http://loinc.org",
                        "code" => "11477-7",
                        "display" => "Microscopic observation [Identifier] in Sputum by Acid fast stain"
                    ]
                ],
                "text" => "Pemeriksaan Sputum BTA"
            ],
            "subject" => [
                "reference" => "Patient/100000030009"
            ],
            "encounter" => [
                "reference" => "Encounter/{{Encounter_uuid}}",
                "display" => "Permintaan BTA Sputum Budi Santoso di hari Selasa, 14 Juni 2022 pukul 09:30 WIB"
            ],
            "occurrenceDateTime" => "2022-06-14T09:30:27+07:00",
            "authoredOn" => "2022-06-13T12:30:27+07:00",
            "requester" => [
                "reference" => "Practitioner/N10000001",
                "display" => "Dokter Bronsig"
            ],
            "performer" => [
                [
                    "reference" => "Practitioner/N10000005",
                    "display" => "Fatma"
                ]
            ],
            "reasonCode" => [
                [
                    "text" => "Periksa jika ada kemungkinan Tuberculosis"
                ]
            ]
        ];
    }

}
