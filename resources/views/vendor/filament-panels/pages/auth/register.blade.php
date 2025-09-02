<x-filament-panels::page.simple>
    {{-- ✅ Ton logo perso en haut --}}
    <div class="flex justify-center mb-6">
        <img src="{{ asset('https://img.phonandroid.com/2017/04/donnees-geolocalisation.jpg') }}" alt="Mon Application" class="h-20">
    </div>

    {{-- ✅ Texte de bienvenue --}}
    <div class="text-center mb-4">
        <h1 class="text-2xl font-bold text-gray-800">Créer un compte</h1>
        <p class="text-gray-600">Rejoignez notre plateforme en quelques clics</p>
    </div>

    {{-- ✅ Formulaire d’inscription --}}
    <x-filament-panels::form id="form" wire:submit="register" class="bg-white shadow-lg rounded-xl p-6">
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />
    </x-filament-panels::form>

    {{-- ✅ Lien retour vers login --}}
    @if (filament()->hasLogin())
        <div class="mt-4 text-center text-sm text-gray-600">
            {{ __('Déjà inscrit ?') }}
            {{ $this->loginAction }}
        </div>
    @endif
</x-filament-panels::page.simple>
