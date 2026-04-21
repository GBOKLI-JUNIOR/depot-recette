<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function index()
    {
        return view('search.index');
    }

    public function search(Request $request)
    {
        $tags = $request->input('ingredients', []);
        
        if (empty($tags)) {
            return response()->json(['recipes' => []]);
        }

        $recipes = Recipe::select('recipes.*', 
            DB::raw('COUNT(DISTINCT i.id) / r_total.total * 100 AS match_percent'))
            ->join('ingredients as i', 'i.recipe_id', 'recipes.id')
            ->join(DB::raw('(SELECT recipe_id, COUNT(*) as total FROM ingredients GROUP BY recipe_id) as r_total'), 'r_total.recipe_id', '=', 'recipes.id')
            ->whereIn('i.name', $tags)
            ->groupBy('recipes.id', 'r_total.total')
            ->orderByDesc('match_percent')
            ->get();

        return response()->json([
            'html' => view('search.results', compact('recipes'))->render()
        ]);
    }
}
