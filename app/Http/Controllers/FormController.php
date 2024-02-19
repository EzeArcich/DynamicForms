<?php

namespace App\Http\Controllers;

use App\Models\Field;
use App\Models\FieldValue;
use App\Models\Form;
use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;


class FormController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin')->only(['store', 'edit', 'update', 'delete']);
    }
    public function index()
    {
        $userRoles = auth()->user()->getRoleNames();

        return view('forms.index', compact('userRoles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        try {

            $formData = $request->input('formData');
    
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

            return response()->json([
                'success' => false,
                'message' => 'Something goes wrong',
                'errors' => [$e->getMessage()],
            ]);

        }  
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $data = Form::with('fields.values')->where('id', $id)->first();
        $fields = $data->fields->toArray();

        $fields = array_map(function ($field) {
            $field['options'] = json_decode($field['options'] ?? '[]');
            $field['values'] = is_array($field['values']) ? $field['values'] : [];
            return $field;
        }, $fields);

        return view('forms.show', compact('data', 'fields'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Form::with('fields')->where('id', $id)->first();

        if ($data) {

            $formData = [
                'form' => [
                    'id' => $data->id,
                    'name' => $data->name,
                ],
                'fields' => $data->fields->toArray(),
            ];

            return response()->json([
                'success' => true,
                'formData' => $formData,
            ]);

        } else {

            return response()->json([
                'success' => false,
                'message' => "Form not found",
            ]);

        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {

            $formData = $request->input('formData');

    
            $form = Form::find($id);

            $form->name = $formData[0]['formName'];
            $form->update();

    
            foreach (array_slice($formData, 1) as $fieldData) {
                
                $field = Field::find($fieldData['id']);
                if($field) {
                    $field->name = $fieldData['name'];
                    $field->type = $fieldData['type'];

                    if($fieldData['type'] == 'option' && isset($fieldData['options'])) {

                        $optionsData = json_decode($fieldData['options'], true);
                        $newOptions = array_column($optionsData, 'value');


                        $currentOptions = json_decode($field->options, true);
                        $optionsRemove = array_diff_assoc($currentOptions, $newOptions);


                        foreach ($optionsRemove as $optionRemove) {
                            $index = array_search($optionRemove, $currentOptions);
                            unset($currentOptions[$index]);
                        }

                        $field->options = json_encode($newOptions); 

                    }
        
                    $form->fields()->save($field);
                } else {
                    $field = Field::create([
                        'name' => $fieldData['name'],
                        'type' => $fieldData['type'],
                        'form_id' => $form->id,
                    ]);
            
                    if($fieldData['type'] == 'option' && isset($fieldData['options'])) {
                        $optionsData = json_decode($fieldData['options'], true);
                        $options = array_column($optionsData, 'value');            
                        $field->options = json_encode($options);
                        $field->save();    
                    }
            
                    $form->fields()->save($field);      
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Data save succesfully'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Something goes wrong',
                'errors' => [$e->getMessage()],
            ]);

        }  
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {

            $form = Form::find($id);
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

    public function formDeletes()
    {

        return view('forms.toRestore');
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

    public function restoreFormDeletes(string $id)
    {
        $form = Form::withTrashed()->find($id);
        
        if ($form) {

            $form->restore();

            return response()->json(['success' => true, 'message' => 'Formulario restaurado exitosamente.']);

        } else {

            return response()->json(['success' => false, 'message' => 'No se encontró el formulario.'], 404);

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

    public function updateValues(Request $request)
    {

        try {
            $valuesToUpdate = $request->input('values');
    
            foreach ($valuesToUpdate as $fieldId => $fieldData) {
                if ($fieldId == 'undefined') {
                    continue;
                }
    
                $existingValue = FieldValue::where('field_id', $fieldId)->first();
    
                if ($existingValue) {
                    $existingValue->update(['value' => $fieldData['value']]);
                } else {
                    FieldValue::create([
                        'field_id' => $fieldId,
                        'value' => $fieldData['value'],
                        'type' => $fieldData['type']
                    ]);
                }
            }

    
            return response()->json(['success' => true, 'message' => 'Values update successfully']);
        } catch (UnauthorizedException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(), 'error' => $e->getMessage()], 403);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Something went wrong', 'error' => $e->getMessage()]);
        }
    }

    
    

}
