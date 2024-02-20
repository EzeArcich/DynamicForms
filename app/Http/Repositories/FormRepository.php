<?php

namespace App\Http\Repositories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Form;
use App\Models\Field;

class FormRepository {

    public function store($formData)
    {
        try {
    
            $form = new Form();
            $form->name = $formData[0]['formName'];
            $form->save();
    
            foreach (array_slice($formData, 1) as $fieldData) {

                $field = new Field();
                $field->name = $fieldData['name'];
                $field->type = $fieldData['type'];
    
                if($fieldData['type'] == 'option' && isset($fieldData['options'])) {
                    $optionsData = json_decode($fieldData['options'], true);
                    $options = array_column($optionsData, 'value');            
                    $field->options = json_encode($options);

                }
    
                $form->fields()->save($field);

            }

            return response()->json([
                'success' => true,
                'message' => 'Data save succesfully'
            ]);

        } catch (\Exception $e) {

            $errorMessage = $e->getMessage();

            if (strpos($errorMessage, 'SQLSTATE[23000]') !== false) {

                    return response()->json([
                        'success' => false,
                        'message' => 'El campo nombre es obligatorio.',
                        'errors' => [$errorMessage],
                    ]);
            }
    
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'errors' => [$errorMessage],
            ]);

        }  
    }

    public function getFormsData()
    {
        $forms = Form::with('fields')->get();

        $forms = $forms->map(function ($form) {

            $form->fields_count = $form->fields->count();
            $form->fields_names = $form->fields->pluck('name')->implode(', ');
            $form->updated_at_formatted = $form->updated_at->format('Y-m-d h:i:s');
            $form->created_at_formatted = $form->created_at->format('Y-m-d h:i:s');
            return $form;

        });

        return response()->json(['data' => $forms]);
    }

    public function getFormById($id)
    {
        $data = Form::with('fields.values')->where('id', $id)->first();

        return $data;
        
    }

    public function getFieldsToShow($fields)
    {
        $fields = array_map(function ($field) {
            $field['options'] = json_decode($field['options'] ?? '[]');
            $field['values'] = is_array($field['values']) ? $field['values'] : [];
            return $field;
        }, $fields);

        return $fields;
    }

    public function destroy($id)
    {
        try {
   
            $form = $this->getFormById($id);
            $form->delete();
    
            return response()->json([
                'success' => true,
                'message' => 'Form has been deleted'
            ]);
            
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Something goes wrong',
                'errors' => [$e->getMessage()],
            ]);
        }
    }


    public function getFormDeletes()
    {
        $softDeletedForms = Form::onlyTrashed()->get();

        $softDeletedForms = $softDeletedForms->map(function ($softDF) {

            $softDF->fields_count = $softDF->fields->count();
            $softDF->fields_names = $softDF->fields->pluck('name')->implode(', ');
            $softDF->updated_at_formatted = $softDF->updated_at->format('Y-m-d h:i:s');
            $softDF->created_at_formatted = $softDF->created_at->format('Y-m-d h:i:s');
            return $softDF;

        });

        return response()->json(['data' => $softDeletedForms]);
    }


    public function update($id, $formData)
    {
        $form = $this->getFormById($id);
        $form->name = $formData[0]['formName'];
        $form->update();

        return $form;
    }

    public function restoreFormDelete($id)
    {
        $form = Form::withTrashed()->find($id);
        
        if ($form) {

            $form->restore();

            return response()->json(['success' => true, 'message' => 'Formulario restaurado exitosamente.']);

        } else {

            return response()->json(['success' => false, 'message' => 'No se encontró el formulario.'], 404);

        }
    }


}