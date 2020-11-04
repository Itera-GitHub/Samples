<?php

namespace App\Http\Controllers;

use App\Services\FormBuilders\GenericFormsService;
use Illuminate\Http\Request;

class GenericFormController extends Controller
{
    public function getFormTemplate(Request $request, $formName, GenericFormsService $genericFormsService)
    {
        return response()->json(json_decode($genericFormsService->getPreparedForm($formName)));
    }
}
