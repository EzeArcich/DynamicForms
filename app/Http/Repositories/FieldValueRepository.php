<?php

namespace App\Http\Repositories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\FieldValue;

class FieldValueRepository {

    public function store($valuesToUpdate)
    {
        try {
            
            foreach ($valuesToUpdate as $fieldId => $fieldData) {
                if ($fieldId == 'undefined') {
                    continue;
                }

                FieldValue::create([
                    'field_id' => $fieldId,
                    'value' => $fieldData['value'],
                    'type' => $fieldData['type']
                ]);               
            }
  
            return response()->json(['success' => true, 'message' => 'Values update successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Something went wrong', 'error' => $e->getMessage()]);
        }
    }

}