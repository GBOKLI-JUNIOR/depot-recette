@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto animate-fade-slide" x-data="{
    loading: false,
    preview: null,
    result: null,
    error: null,
    handleFile(e) {
        let file = e.target.files[0];
        if (!file) return;
        
        this.preview = URL.createObjectURL(file);
        this.upload(file);
    },
    async upload(file) {
        this.loading = true;
        this.result = null;
        this.error = null;
        
        let formData = new FormData();
        formData.append('image', file);
        
        try {
            let res = await fetch('/analyze-image', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: formData
            });
            let data = await res.json();
            if (data.error) {
                this.error = data.error;
            } else {
                this.result = data;
            }
        } catch (e) {
            this.error = 'Une erreur s\'est produite lors de l\'analyse.';
        }
        this.loading = false;
    }
}">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold mb-4 text-transparent bg-clip-text bg-gradient-to-r from-[#2ECC9A] to-[#F4A261]">Analyse IA</h1>
        <p class="text-gray-400">Prenez en photo votre plat, l'IA devine la recette et les calories.</p>
    </div>

    <div class="glass-card mb-8">
        <div class="border-2 border-dashed border-gray-600 rounded-2xl p-12 text-center relative hover:border-[#2ECC9A] transition-colors cursor-pointer group"
             onclick="document.getElementById('file-upload').click()">
            
            <input type="file" id="file-upload" class="hidden" @change="handleFile" accept="image/*">
            
            <div x-show="!preview" class="text-gray-400 group-hover:text-[#2ECC9A] transition-colors">
                <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <p class="font-bold mb-2">Cliquez ou glissez une image ici</p>
                <p class="text-sm opacity-70">JPG, PNG, WEBP (Max 5Mo)</p>
            </div>

            <img x-show="preview" :src="preview" class="mx-auto rounded-xl max-h-[300px] object-contain" />
            
            <div x-show="loading" class="absolute inset-0 bg-black/60 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                <div class="text-center">
                    <svg class="animate-spin h-10 w-10 text-[#2ECC9A] mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <p class="text-[#2ECC9A] font-bold">L'IA de Claude analyse votre plat...</p>
                </div>
            </div>
        </div>

        <div x-show="error" class="mt-6 bg-red-500/20 text-red-400 p-4 rounded-xl border border-red-500/30" x-text="error"></div>

        <div x-show="result" x-transition class="mt-8 bg-black/30 rounded-xl p-6 border border-gray-700">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h3 class="text-2xl font-bold text-white mb-1" x-text="result.dish_name"></h3>
                    <p class="text-sm text-gray-400" x-show="result.confidence_score">
                        Confiance de l'IA: <span x-text="(result.confidence_score * 100).toFixed(0) + '%'"></span>
                    </p>
                </div>
                <div class="bg-[#F4A261]/20 text-[#F4A261] px-4 py-2 rounded-xl text-center border border-[#F4A261]/30">
                    <span class="block text-2xl font-black leading-none" x-text="result.total_calories"></span>
                    <span class="text-xs font-bold uppercase">kcal</span>
                </div>
            </div>

            <h4 class="font-bold text-[#2ECC9A] mb-4">Ingrédients détectés</h4>
            <div class="grid grid-cols-2 gap-3">
                <template x-for="ing in result.ingredients" :key="ing.name">
                    <div class="bg-black/50 p-3 rounded-lg flex justify-between items-center">
                        <span class="text-white capitalize" x-text="ing.name"></span>
                        <span class="text-gray-400 text-sm font-mono"><span x-text="ing.estimated_grams"></span>g</span>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
@endsection
