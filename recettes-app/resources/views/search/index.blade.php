@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto animate-fade-slide">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold mb-4 text-[#F4A261]">Frigo Magique</h1>
        <p class="text-gray-400">Entrez les ingrédients que vous avez, nous trouvons les recettes.</p>
    </div>

    <div class="glass-card mb-8" x-data="{ 
        tags: [], 
        input: '', 
        resultsHTML: '',
        loading: false,
        async search() {
            if (this.tags.length === 0) return;
            this.loading = true;
            try {
                let formData = new FormData();
                this.tags.forEach(tag => formData.append('ingredients[]', tag));
                
                let res = await fetch('/search', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: formData
                });
                let data = await res.json();
                this.resultsHTML = data.html;
            } catch (e) {
                console.error(e);
            }
            this.loading = false;
        }
    }">
        <div class="flex flex-wrap gap-2 mb-4 p-4 bg-black/30 rounded-xl min-h-[60px] border border-gray-700">
            <template x-for="(tag, i) in tags" :key="i">
                <span class="bg-[#2ECC9A]/20 text-[#2ECC9A] px-3 py-1 rounded-full flex items-center text-sm font-bold">
                    <span x-text="tag" class="mr-2"></span>
                    <button @click="tags.splice(i, 1); search()" class="hover:text-white">&times;</button>
                </span>
            </template>
            <input x-model="input" 
                   @keydown.enter.prevent="if(input.trim()){ tags.push(input.trim()); input=''; search(); }"
                   placeholder="Ajouter un ingrédient et appuyer sur Entrée..." 
                   class="bg-transparent border-none outline-none text-white flex-1 min-w-[200px]" />
        </div>
        
        <div class="text-center">
            <button @click="search" class="bg-[#2ECC9A] text-[#0D1B2A] font-bold px-8 py-3 rounded-full hover:bg-[#25a980] transition-colors" :class="{ 'opacity-50 cursor-not-allowed': loading }">
                <span x-show="!loading">Trouver des recettes</span>
                <span x-show="loading">Recherche en cours...</span>
            </button>
        </div>

        <div class="mt-12" x-html="resultsHTML"></div>
    </div>
</div>
@endsection
