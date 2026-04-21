<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $recipes = Recipe::with('ingredients')->latest()->get();
        return view('recipes.index', compact('recipes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('recipes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'prep_time' => 'required|integer',
            'servings' => 'required|integer',
            'description' => 'nullable|string',
            'ingredients' => 'required|array',
            'ingredients.*.name' => 'required|string',
            'ingredients.*.quantity' => 'required|numeric',
            'ingredients.*.unit' => 'required|string',
            'image' => 'nullable|image|max:5120',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads', 'public');
        }

        $recipe = Recipe::create([
            'title' => $validated['title'],
            'category' => $validated['category'],
            'prep_time' => $validated['prep_time'],
            'servings' => $validated['servings'],
            'description' => $validated['description'],
            'image_path' => $imagePath,
            'total_calories' => 0, // Will be updated by service later
        ]);

        foreach ($validated['ingredients'] as $ing) {
            $recipe->ingredients()->create($ing);
        }

        app(\App\Services\NutritionCalculator::class)->calculateForRecipe($recipe);

        return redirect()->route('recipes.index')->with('success', 'Recette créée !');
    }

    /**
     * Display the specified resource.
     */
    public function show(Recipe $recipe)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Recipe $recipe)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Recipe $recipe)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Recipe $recipe)
    {
        //
    }
}
