<x-filament-panels::page.simple>
    {{-- ✅ Ton logo personnalisé --}}
    <div class="flex justify-center mb-6">
        <img src="{{ asset('https://img.phonandroid.com/2017/04/donnees-geolocalisation.jpg') }}" alt="Mon Application" class="h-20">
    </div>

    {{-- ✅ Texte ou sous-titre optionnel --}}
    <div class="text-center mb-4">
        <h1 class="text-2xl font-bold text-gray-800">Bienvenue</h1>
        <p class="text-gray-600">Connectez-vous pour accéder à votre espace</p>
    </div>

    {{-- ✅ Formulaire de connexion --}}
    <x-filament-panels::form id="form" wire:submit="authenticate" class="bg-white shadow-lg rounded-xl p-6">
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />
    </x-filament-panels::form>

    {{-- ✅ Lien inscription (si activée) --}}
    @if (filament()->hasRegistration())
        <div class="mt-4 text-center text-sm text-gray-600">
            {{ __('filament-panels::pages/auth/login.actions.register.before') }}
            {{ $this->registerAction }}
        </div>
    @endif
</x-filament-panels::page.simple>
