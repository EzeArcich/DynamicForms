<?php

namespace App\Http\Controllers;
use App\Models\Field;

use Illuminate\Http\Request;

class FieldController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
        try {

            $form = Field::find($id);
            $form->delete();
    
            return response()->json([
                'success' => true,
                'message' => 'Field has been deleted'
            ]);
            
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Something goes wrong',
                'errors' => [$e->getMessage()],
            ]);
        }
    }
}
