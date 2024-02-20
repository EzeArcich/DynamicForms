<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Repositories\FieldValueRepository;
Use App\Models\FieldValue;

class FieldValueController extends Controller
{

    protected $fieldValueRepository;

    public function __construct(FieldValueRepository $fieldValueRepository)
    {
        $this->fieldValueRepository = $fieldValueRepository;
        $this->middleware('role:admin')->except('storeValues');
    }

    public function storeValues(Request $request)
    {
        
        $valuesToUpdate = $request->input('values');

        $updatedValues = $this->fieldValueRepository->store($valuesToUpdate);

        return $updatedValues;

    }
}
