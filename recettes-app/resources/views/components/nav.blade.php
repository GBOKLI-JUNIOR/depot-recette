<nav x-data="{ scrolled: false }" 
     @scroll.window="scrolled = (window.pageYOffset > 20)"
     :class="{ 'glass-card border-none rounded-none !py-4': scrolled, 'bg-transparent py-6': !scrolled }"
     class="fixed top-0 w-full z-50 transition-all duration-300">
    <div class="container mx-auto px-6 flex justify-between items-center">
        <a href="{{ route('recipes.index') }}" class="text-2xl font-bold flex items-center gap-2 text-[#2ECC9A]">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            NutriChef IA
        </a>
        <div class="hidden md:flex space-x-8 text-sm font-medium">
            <a href="{{ route('recipes.index') }}" class="hover:text-[#2ECC9A] transition-colors">Mes Recettes</a>
            <a href="/search" class="hover:text-[#2ECC9A] transition-colors">Frigo Magique</a>
            <a href="/analyze-image" class="hover:text-[#2ECC9A] transition-colors">Analyse IA</a>
        </div>
        <a href="{{ route('recipes.create') }}" class="bg-[#2ECC9A] hover:bg-[#25a980] text-[#0D1B2A] px-5 py-2 rounded-full font-bold transition-transform transform hover:scale-95">
            + Nouvelle Recette
        </a>
    </div>
</nav>
