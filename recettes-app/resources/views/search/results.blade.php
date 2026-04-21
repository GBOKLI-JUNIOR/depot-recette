@if($recipes->isEmpty())
    <div class="text-center text-gray-400 py-8">
        Aucune recette trouvée avec ces ingrédients.
    </div>
@else
    <h2 class="text-2xl font-bold mb-6 text-white">Résultats ({{ $recipes->count() }})</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($recipes as $recipe)
            <div class="relative">
                <div class="absolute -top-3 -right-3 bg-[#F4A261] text-[#0D1B2A] font-bold rounded-full w-12 h-12 flex items-center justify-center z-10 border-4 border-[#0D1B2A]">
                    {{ round($recipe->match_percent) }}%
                </div>
                <x-recipe-card :recipe="$recipe" />
            </div>
        @endforeach
    </div>
@endif
