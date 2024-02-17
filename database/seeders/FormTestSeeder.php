<?php

namespace Database\Seeders;

use App\Models\Field;
use App\Models\Form;
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
            'name' => 'employes'
        ]);

        

        $fields = [
            ['name' => 'name', 'type' => 'text'],

            ['name' => 'age', 'type' => 'number'],

            ['name' => 'address', 'type' => 'text'],

            ['name' => 'genre', 'type' => 'option', 'options' => json_encode(['male', 'female'])],

            ['name' => 'birthdate', 'type' => 'date'],
        ];

        foreach ($fields as $field) {
            Field::create([
                'form_id' => $form->id,
                'name' => $field['name'],
                'type' => $field['type'],
                'options' => $field['type'] === 'option' ? $field['options'] : null,
            ]);
        }
    }
}
