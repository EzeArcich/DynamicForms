<?php

namespace Database\Seeders;

use App\Models\DateValue;
use App\Models\Field;
use App\Models\Form;
use App\Models\NumberValue;
use App\Models\OptionValue;
use App\Models\TextValue;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FormTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $form = Form::create([
            'name' => 'employees'
        ]);
    
        $fields = [
            ['name' => 'name', 'type' => 'text'],
            ['name' => 'age', 'type' => 'number'],
            ['name' => 'address', 'type' => 'text'],
            ['name' => 'genre', 'type' => 'option', 'options' => json_encode(['male', 'female'])],
            ['name' => 'birthdate', 'type' => 'date'],
        ];
    
        foreach ($fields as $field) {
            $newField = Field::create([
                'form_id' => $form->id,
                'name' => $field['name'],
                'type' => $field['type'],
                'options' => $field['type'] === 'option' ? $field['options'] : null,
            ]);
        
            switch ($newField->type) {
                case 'text':
                    $newField->values()->create([
                        'value' => 'Example Text Value',
                        'type' => $field['type'],
                    ]);
                    break;
                case 'number':
                    $newField->values()->create([
                        'value' => 42,
                        'type' => $field['type'],
                    ]);
                    break;
                case 'date':
                    $newField->values()->create([
                        'value' => '2024-02-18',
                        'type' => $field['type'],
                    ]);
                    break;
                case 'option':
                    $newField->values()->create([
                        'value' => 'male',
                        'type' => $field['type'],
                    ]);
                    break;
            }
        }
    }
    
    
}
