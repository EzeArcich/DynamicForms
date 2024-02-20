<?php

namespace App\Http\Controllers;

use App\Models\Field;
use App\Models\FieldValue;
use App\Models\Form;
use App\Http\Repositories\FormRepository;
use App\Http\Repositories\FieldRepository;
use App\Http\Repositories\FieldValueRepository;
use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;


class FormController extends Controller
{
    protected $formRepository;
    protected $fieldRepository;
    protected $fieldValueRepository;
    
    public function __construct(FormRepository $formRepository, FieldRepository $fieldRepository, FieldValueRepository $fieldValueRepository)
    {

        $this->middleware('role:admin')->except('index', 'getFormsData', 'show');
        $this->formRepository = $formRepository;
        $this->fieldRepository = $fieldRepository;
        $this->fieldValueRepository = $fieldValueRepository;

    }


    public function index()
    {
        $userRoles = auth()->user()->getRoleNames();

        return view('forms.index', compact('userRoles'));
    }


    public function getFormsData()
    {
        $responseFD = $this->formRepository->getFormsData();

        return $responseFD;
    }


    public function store(Request $request)
    {

        $formData = $request->input('formData');


        $responseStore = $this->formRepository->store($formData);

        return $responseStore;
        
    }


    public function show(string $id)
    {
        $userRoles = auth()->user()->getRoleNames();
        $data = $this->formRepository->getFormById($id);

        $fields = $data->fields->toArray();
        $fields = $this->formRepository->getFieldsToShow($fields);

        return view('forms.show', compact('data', 'fields', 'userRoles'));

    }


    public function destroy(string $id)
    {
        $data = $this->formRepository->destroy($id);

        return $data;
    }

    public function getFormDeletes()
    {
        $data = $this->formRepository->getFormDeletes();

        return $data;

    }
    

    public function formDeletes()
    {
        return view('forms.toRestore');
    }


    public function edit(string $id)
    {
        $data = $this->formRepository->getFormById($id);

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


    public function update(Request $request, string $id)
    {
        try {

            $formData = $request->input('formData');

            $form = $this->formRepository->update($id, $formData);

            array_shift($formData);
    
            foreach ($formData as $fieldData) {
                
                $this->fieldRepository->createOrUpdate($form, $fieldData);

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


    public function restoreFormDeletes(string $id)
    {

        $form = $this->formRepository->restoreFormDelete($id);

        return $form;

    }


    public function getTableInfo()
    {
        $formId = 2;

        $forms = Form::with(['fields', 'fields.values'])
            ->where('id', $formId)
            ->whereHas('fields.values')
            ->take(1)
            ->get();

        return response()->json($forms);
    }

    
}
