<?php

namespace App\Http\Repositories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Field;

class FieldRepository {


    public function getFieldById($id)
    {
        $field = Field::find($id);

        return $field;
    }


    public function createField($form, $fieldData)
    {
        // Verificar si 'name' está presente en $fieldData
        if (isset($fieldData['name'])) {
            $field = Field::create([
                'name' => $fieldData['name'],
                'type' => $fieldData['type'],
                'form_id' => $form->id,
            ]);
    
            if ($fieldData['type'] == 'option' && isset($fieldData['options'])) {
                $optionsData = json_decode($fieldData['options'], true);
                $options = array_column($optionsData, 'value');
                $field->options = json_encode($options);
                $field->save();
            }
    
            $form->fields()->save($field);
        } else {
            // Puedes agregar un mensaje de registro o manejar de otra manera si 'name' no está presente
            \Log::error('La clave "name" no está presente en $fieldData:', $fieldData);
        }
    }
    


    public function createOrUpdate($form, $fieldData)
    {

        $field = $this->getFieldById($fieldData['id']);

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

            $this->createField($form, $fieldData);

        }

    }



}