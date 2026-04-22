@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-20 animate-fade-slide">
    <div class="glass-card">
        <h2 class="text-3xl font-bold mb-6 text-center text-[#2ECC9A]">Inscription</h2>

        @if ($errors->any())
            <div class="bg-red-500/20 text-red-400 p-4 rounded-xl border border-red-500/30 mb-6">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium mb-1 text-gray-300">Nom</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                       class="w-full bg-black/30 border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#2ECC9A] transition-colors">
            </div>

            <div>
                <label for="email" class="block text-sm font-medium mb-1 text-gray-300">Adresse Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                       class="w-full bg-black/30 border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#2ECC9A] transition-colors">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium mb-1 text-gray-300">Mot de passe</label>
                <input id="password" type="password" name="password" required
                       class="w-full bg-black/30 border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#2ECC9A] transition-colors">
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium mb-1 text-gray-300">Confirmer le mot de passe</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                       class="w-full bg-black/30 border border-gray-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#2ECC9A] transition-colors">
            </div>

            <div class="flex items-center justify-between mt-6">
                <a class="text-sm text-gray-400 hover:text-[#2ECC9A] transition-colors" href="{{ route('login') }}">
                    Déjà un compte ? Se connecter
                </a>

                <button type="submit" class="bg-[#2ECC9A] text-[#0D1B2A] px-6 py-2 rounded-full font-bold hover:bg-[#25a980] transition-colors">
                    S'inscrire
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
