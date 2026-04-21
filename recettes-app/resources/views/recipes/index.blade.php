@extends('layouts.app')

@section('content')
<div class="mb-12 text-center animate-fade-slide">
    <h1 class="text-4xl font-bold mb-4">Mes Recettes</h1>
    <p class="text-gray-400">Découvrez vos meilleures créations culinaires</p>
</div>

<!-- Filtres (Mocks pour l'instant) -->
<div class="flex flex-wrap gap-4 mb-8 justify-center animate-fade-slide" style="animation-delay: 0.1s">
    <button class="bg-[#2ECC9A] text-[#0D1B2A] px-4 py-2 rounded-full font-bold text-sm">Tout</button>
    <button class="bg-gray-800 hover:bg-gray-700 text-white px-4 py-2 rounded-full text-sm transition-colors">Entrée</button>
    <button class="bg-gray-800 hover:bg-gray-700 text-white px-4 py-2 rounded-full text-sm transition-colors">Plat</button>
    <button class="bg-gray-800 hover:bg-gray-700 text-white px-4 py-2 rounded-full text-sm transition-colors">Dessert</button>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
    @foreach($recipes as $index => $recipe)
        <div style="animation-delay: {{ 0.2 + ($index * 0.1) }}s">
            <x-recipe-card :recipe="$recipe" />
        </div>
    @endforeach
</div>
@endsection
