<?php

namespace App\Http\Controllers;

use App\Models\Field;
use App\Models\Form;
use Illuminate\Http\Request;

class FormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('forms.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    
                if($fieldData['type'] == '3' && isset($fieldData['options'])) {

                    $options = json_decode($fieldData['options'], true);
                    $field->options = $options;

                }
    
                $form->fields()->save($field);

                return response()->json([
                    'success' => true,
                    'message' => 'Data save succesfully'
                ]);

            }

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
        $data = Form::with('fields')->where('id', $id)->first();
        $fields = $data->fields->toArray();
        // echo '<pre>';
        // var_dump($fields);
        // exit;

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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
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
}
