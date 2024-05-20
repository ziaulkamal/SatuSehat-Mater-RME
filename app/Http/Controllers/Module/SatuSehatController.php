<?php

namespace App\Http\Controllers\Module;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Module\Condition;
use App\Http\Controllers\Module\Encounter;
use App\Http\Controllers\Module\Medication;
use App\Http\Controllers\Module\MedicationRequest;
use App\Http\Controllers\Module\Procedure;
use Illuminate\Http\Request;

class SatuSehatController extends BaseController
{
    protected $controllers = [
        'encounter' => Encounter::class,
        'condition' => Condition::class,
        'medicationRequest' => MedicationRequest::class,
        'procedure' => Procedure::class,
        'medication' => Medication::class,
        'serviceLab' => ServiceLab::class,
    ];

    public function compiled($data, $token, $resource, $function)
    {
        if (!isset($this->controllers[$resource])) {
            return response()->json(['error' => 'Resource not found'], 404);
        }

        $controller = app($this->controllers[$resource]);

        if (!method_exists($controller, $function)) {
            return response()->json(['error' => 'Function not found for resource'], 404);
        }


        $datas = json_encode($controller->$function($data), JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

        try {
            [$response, $statusCode] = $this->_postFHIR($datas, $token);
        } catch (\Throwable $e) {
            # code...
        }
    }
}
