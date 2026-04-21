<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ClaudeVisionService;

class ImageAnalysisController extends Controller
{
    public function index()
    {
        return view('analysis.index');
    }

    public function analyzeFood(Request $request, ClaudeVisionService $visionService)
    {
        $request->validate([
            'image' => 'required|image|max:5120',
        ]);

        $imagePath = $request->file('image')->store('uploads', 'public');

        $result = $visionService->analyzeFood($imagePath);

        return response()->json($result);
    }
}
