@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto mb-20 animate-fade-slide">
    <h1 class="text-4xl font-bold mb-8 text-center text-[#2ECC9A]">Créer une Recette</h1>

    <div class="glass-card" x-data="{ 
        step: 1, 
        recipe: { title: '', category: 'Plat', prep_time: 30, servings: 2, description: '' },
        ingredients: [{ name: '', quantity: 100, unit: 'g' }]
    }">
        <!-- Progress -->
        <div class="flex justify-between mb-8 relative">
            <div class="absolute top-1/2 left-0 w-full h-1 bg-gray-700 -z-10 transform -translate-y-1/2"></div>
            <div class="absolute top-1/2 left-0 h-1 bg-[#2ECC9A] -z-10 transform -translate-y-1/2 transition-all duration-300" :style="'width: ' + ((step-1)*50) + '%'"></div>
            
            <div class="flex flex-col items-center">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold" :class="step >= 1 ? 'bg-[#2ECC9A] text-[#0D1B2A]' : 'bg-gray-800 text-gray-500'">1</div>
                <span class="text-xs mt-2 text-gray-400">Général</span>
            </div>
            <div class="flex flex-col items-center">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold" :class="step >= 2 ? 'bg-[#2ECC9A] text-[#0D1B2A]' : 'bg-gray-800 text-gray-500'">2</div>
                <span class="text-xs mt-2 text-gray-400">Ingrédients</span>
            </div>
            <div class="flex flex-col items-center">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold" :class="step >= 3 ? 'bg-[#2ECC9A] text-[#0D1B2A]' : 'bg-gray-800 text-gray-500'">3</div>
                <span class="text-xs mt-2 text-gray-400">Finition</span>
            </div>
        </div>

        <form action="{{ route('recipes.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- Etape 1 -->
            <div x-show="step === 1" x-transition>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Titre de la recette</label>
                        <input type="text" name="title" x-model="recipe.title" required class="w-full bg-black/30 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-[#2ECC9A]">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Catégorie</label>
                        <select name="category" x-model="recipe.category" class="w-full bg-black/30 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-[#2ECC9A]">
                            <option value="Entrée">Entrée</option>
                            <option value="Plat">Plat</option>
                            <option value="Dessert">Dessert</option>
                            <option value="Snack">Snack</option>
                        </select>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-1">
                            <label class="block text-sm font-medium mb-1">Temps (min)</label>
                            <input type="number" name="prep_time" x-model="recipe.prep_time" required class="w-full bg-black/30 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-[#2ECC9A]">
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm font-medium mb-1">Portions</label>
                            <input type="number" name="servings" x-model="recipe.servings" required class="w-full bg-black/30 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-[#2ECC9A]">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Description courte</label>
                        <textarea name="description" x-model="recipe.description" rows="3" class="w-full bg-black/30 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-[#2ECC9A]"></textarea>
                    </div>
                </div>
            </div>

            <!-- Etape 2 -->
            <div x-show="step === 2" x-transition style="display: none;">
                <h3 class="text-xl font-bold mb-4">Ingrédients</h3>
                <template x-for="(ing, index) in ingredients" :key="index">
                    <div class="flex gap-2 mb-3 items-end">
                        <div class="flex-1">
                            <label x-show="index === 0" class="block text-sm font-medium mb-1">Nom</label>
                            <input type="text" :name="'ingredients['+index+'][name]'" x-model="ing.name" placeholder="Ex: Poulet" class="w-full bg-black/30 border border-gray-600 rounded-lg px-4 py-2 text-white">
                        </div>
                        <div class="w-24">
                            <label x-show="index === 0" class="block text-sm font-medium mb-1">Qté</label>
                            <input type="number" :name="'ingredients['+index+'][quantity]'" x-model="ing.quantity" class="w-full bg-black/30 border border-gray-600 rounded-lg px-4 py-2 text-white">
                        </div>
                        <div class="w-24">
                            <label x-show="index === 0" class="block text-sm font-medium mb-1">Unité</label>
                            <select :name="'ingredients['+index+'][unit]'" x-model="ing.unit" class="w-full bg-black/30 border border-gray-600 rounded-lg px-4 py-2 text-white">
                                <option value="g">g</option>
                                <option value="ml">ml</option>
                                <option value="u">unité</option>
                                <option value="càs">càs</option>
                            </select>
                        </div>
                        <button type="button" @click="ingredients.splice(index, 1)" class="bg-red-500/20 text-red-400 p-2 rounded-lg hover:bg-red-500/40">
                            X
                        </button>
                    </div>
                </template>
                <button type="button" @click="ingredients.push({name:'', quantity:100, unit:'g'})" class="text-[#2ECC9A] font-bold text-sm mt-2">+ Ajouter un ingrédient</button>
            </div>

            <!-- Etape 3 -->
            <div x-show="step === 3" x-transition style="display: none;">
                <h3 class="text-xl font-bold mb-4">Photo de la recette</h3>
                <div class="border-2 border-dashed border-gray-600 rounded-2xl p-8 text-center bg-black/20 mb-6">
                    <input type="file" name="image" class="block w-full text-sm text-gray-400
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-full file:border-0
                        file:text-sm file:font-semibold
                        file:bg-[#2ECC9A] file:text-[#0D1B2A]
                        hover:file:bg-[#25a980]
                        cursor-pointer
                        mx-auto" />
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-8 flex justify-between pt-6 border-t border-gray-800">
                <button type="button" x-show="step > 1" @click="step--" class="px-6 py-2 rounded-full border border-gray-600 hover:bg-gray-800 transition-colors">Précédent</button>
                <div x-show="step === 1"></div> <!-- Spacer -->
                
                <button type="button" x-show="step < 3" @click="step++" class="px-6 py-2 rounded-full bg-[#2ECC9A] text-[#0D1B2A] font-bold hover:bg-[#25a980] transition-colors">Suivant</button>
                <button type="submit" x-show="step === 3" class="px-6 py-2 rounded-full bg-[#F4A261] text-[#0D1B2A] font-bold hover:bg-[#e09150] transition-colors">Enregistrer</button>
            </div>
        </form>
    </div>
</div>
@endsection
