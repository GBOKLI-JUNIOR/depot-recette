@props(['recipe'])

<div class="glass-card relative overflow-hidden group cursor-pointer animate-fade-slide p-0">
    @if($recipe->image_path)
        <img src="{{ asset('storage/' . $recipe->image_path) }}" alt="{{ $recipe->title }}" class="w-full h-48 object-cover rounded-t-[20px]">
    @else
        <div class="w-full h-48 bg-gray-800 rounded-t-[20px] flex items-center justify-center text-gray-500">
            Aucune image
        </div>
    @endif
    
    <div class="absolute top-4 right-4 bg-[#F4A261] text-[#0D1B2A] text-xs font-bold px-3 py-1 rounded-full">
        {{ $recipe->category }}
    </div>

    <div class="p-5">
        <h3 class="text-xl font-bold mb-2 text-[#F0F4F8]">{{ $recipe->title }}</h3>
        <p class="text-sm text-gray-400 mb-4 line-clamp-2">{{ $recipe->description }}</p>
        
        <div class="flex justify-between items-center text-sm">
            <div class="flex items-center text-[#2ECC9A]">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ $recipe->prep_time }} min
            </div>
            @if($recipe->total_calories)
            <div class="flex items-center text-[#F4A261] font-bold">
                {{ number_format($recipe->total_calories / $recipe->servings, 0) }} kcal
            </div>
            @endif
        </div>
    </div>
</div>
