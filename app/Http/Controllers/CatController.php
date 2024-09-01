<?php

namespace App\Http\Controllers;

use App\Models\Cat;
use Illuminate\Http\Request;

class CatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Cat::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer',
            'breed' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'image' => 'nullable|string',
        ]);

        $cat = Cat::create($validatedData);

        return response()->json($cat, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Cat $cat)
    {
        return response()->json($cat);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cat $cat)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer',
            'breed' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'image' => 'nullable|string',
            'adopted' => 'nullable|boolean',
        ]);

        $cat->update($validatedData);

        return response()->json($cat);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cat $cat)
    {
        $cat->delete();

        return response()->json([
            'message' => 'Cat deleted successfully',
        ], 200);
    }
}
