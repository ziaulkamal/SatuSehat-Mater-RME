<?php

namespace App\Http\Controllers\Module;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Encounter extends Controller
{

    public function start($data) {
        return [
            "resourceType" => "Encounter",
            "status" => "arrived",
            "class" => [
                "system" => "http://terminology.hl7.org/CodeSystem/v3-ActCode",
                "code" => "AMB",
                "display" => "ambulatory"
            ],
            "subject" => [
                "reference" => "Patient/100000030009",
                "display" => "Budi Santoso"
            ],
            "participant" => [
                [
                    "type" => [
                        [
                            "coding" => [
                                [
                                    "system" => "http://terminology.hl7.org/CodeSystem/v3-ParticipationType",
                                    "code" => "ATND",
                                    "display" => "attender"
                                ]
                            ]
                        ]
                    ],
                    "individual" => [
                        "reference" => "Practitioner/N10000001",
                        "display" => "Dokter Bronsig"
                    ]
                ]
            ],
            "period" => [
                "start" => "2022-06-14T07:00:00+07:00"
            ],
            "location" => [
                [
                    "location" => [
                        "reference" => "Location/b017aa54-f1df-4ec2-9d84-8823815d7228",
                        "display" => "Ruang 1A, Poliklinik Bedah Rawat Jalan Terpadu, Lantai 2, Gedung G"
                    ]
                ]
            ],
            "statusHistory" => [
                [
                    "status" => "arrived",
                    "period" => [
                        "start" => "2022-06-14T07:00:00+07:00"
                    ]
                ]
            ],
            "serviceProvider" => [
                "reference" => "Organization/{{Org_id}}"
            ],
            "identifier" => [
                [
                    "system" => "http://sys-ids.kemkes.go.id/encounter/{{Org_id}}",
                    "value" => "P20240001"
                ]
            ]
        ];

    }

    public function in_progress($data) {
        return [
            "resourceType" => "Encounter",
            "id" => "{{Encounter_uuid}}",
            "identifier" => [
                [
                    "system" => "http://sys-ids.kemkes.go.id/encounter/{{Org_id}}",
                    "value" => "P20240001"
                ]
            ],
            "status" => "in-progress",
            "class" => [
                "system" => "http://terminology.hl7.org/CodeSystem/v3-ActCode",
                "code" => "AMB",
                "display" => "ambulatory"
            ],
            "subject" => [
                "reference" => "Patient/100000030009",
                "display" => "Budi Santoso"
            ],
            "participant" => [
                [
                    "type" => [
                        [
                            "coding" => [
                                [
                                    "system" => "http://terminology.hl7.org/CodeSystem/v3-ParticipationType",
                                    "code" => "ATND",
                                    "display" => "attender"
                                ]
                            ]
                        ]
                    ],
                    "individual" => [
                        "reference" => "Practitioner/N10000001",
                        "display" => "Dokter Bronsig"
                    ]
                ]
            ],
            "period" => [
                "start" => "2022-06-14T07:00:00+07:00",
                "end" => "2022-06-14T09:00:00+07:00"
            ],
            "location" => [
                [
                    "location" => [
                        "reference" => "Location/ef011065-38c9-46f8-9c35-d1fe68966a3e",
                        "display" => "Ruang 1A, Poliklinik Rawat Jalan"
                    ]
                ]
            ],
            "statusHistory" => [
                [
                    "status" => "arrived",
                    "period" => [
                        "start" => "2022-06-14T07:00:00+07:00",
                        "end" => "2022-06-14T08:00:00+07:00"
                    ]
                ],
                [
                    "status" => "in-progress",
                    "period" => [
                        "start" => "2022-06-14T08:00:00+07:00",
                        "end" => "2022-06-14T09:00:00+07:00"
                    ]
                ]
            ],
            "serviceProvider" => [
                "reference" => "Organization/{{Org_id}}"
            ]
        ];

    }

    public function finished($data) {
        return [
            "resourceType" => "Encounter",
            "id" => "{{Encounter_uuid}}",
            "identifier" => [
                [
                    "system" => "http://sys-ids.kemkes.go.id/encounter/{{Org_id}}",
                    "value" => "P20240001"
                ]
            ],
            "status" => "finished",
            "class" => [
                "system" => "http://terminology.hl7.org/CodeSystem/v3-ActCode",
                "code" => "AMB",
                "display" => "ambulatory"
            ],
            "subject" => [
                "reference" => "Patient/100000030009",
                "display" => "Budi Santoso"
            ],
            "participant" => [
                [
                    "type" => [
                        [
                            "coding" => [
                                [
                                    "system" => "http://terminology.hl7.org/CodeSystem/v3-ParticipationType",
                                    "code" => "ATND",
                                    "display" => "attender"
                                ]
                            ]
                        ]
                    ],
                    "individual" => [
                        "reference" => "Practitioner/N10000001",
                        "display" => "Dokter Bronsig"
                    ]
                ]
            ],
            "period" => [
                "start" => "2022-06-14T07:00:00+07:00",
                "end" => "2022-06-14T09:00:00+07:00"
            ],
            "location" => [
                [
                    "location" => [
                        "reference" => "Location/ef011065-38c9-46f8-9c35-d1fe68966a3e",
                        "display" => "Ruang 1A, Poliklinik Rawat Jalan"
                    ]
                ]
            ],
            "diagnosis" => [
                [
                    "condition" => [
                        "reference" => "Condition/4bbbe654-14f5-4ab3-a36e-a1e307f67bb8",
                        "display" => "Tuberculosis of lung, confirmed by sputum microscopy with or without culture"
                    ],
                    "use" => [
                        "coding" => [
                            [
                                "system" => "http://terminology.hl7.org/CodeSystem/diagnosis-role",
                                "code" => "DD",
                                "display" => "Discharge diagnosis"
                            ]
                        ]
                    ],
                    "rank" => 1
                ],
                [
                    "condition" => [
                        "reference" => "Condition/666970c2-d79f-4242-89f9-d0ffab9e36cf",
                        "display" => "Non-insulin-dependent diabetes mellitus without complications"
                    ],
                    "use" => [
                        "coding" => [
                            [
                                "system" => "http://terminology.hl7.org/CodeSystem/diagnosis-role",
                                "code" => "DD",
                                "display" => "Discharge diagnosis"
                            ]
                        ]
                    ],
                    "rank" => 2
                ]
            ],
            "statusHistory" => [
                [
                    "status" => "arrived",
                    "period" => [
                        "start" => "2022-06-14T07:00:00+07:00",
                        "end" => "2022-06-14T08:00:00+07:00"
                    ]
                ],
                [
                    "status" => "in-progress",
                    "period" => [
                        "start" => "2022-06-14T08:00:00+07:00",
                        "end" => "2022-06-14T09:00:00+07:00"
                    ]
                ],
                [
                    "status" => "finished",
                    "period" => [
                        "start" => "2022-06-14T09:00:00+07:00",
                        "end" => "2022-06-14T09:00:00+07:00"
                    ]
                ]
            ],
            "serviceProvider" => [
                "reference" => "Organization/{{Org_id}}"
            ]
        ];
    }


}
