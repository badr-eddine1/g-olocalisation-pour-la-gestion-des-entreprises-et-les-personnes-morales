<x-filament::page>

    <div class="flex min-h-screen bg-gray-50">
        {{-- LEFT SIDEBAR --}}
        <div class="w-80 bg-white shadow-lg border-r border-gray-200 overflow-y-auto">
            <div class="p-6 space-y-6 sticky top-0">
                {{-- Header --}}
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-800">Filtres de Recherche</h2>
                    <div class="text-xs bg-orange-100 text-orange-800 px-2 py-1 rounded-full">
                        {{ count($entreprises) }} entreprises
                    </div>
                </div>

                {{-- Search Section --}}
                <div class="pt-4 border-t border-gray-200">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Recherche</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input
                            type="text"
                            placeholder="Nom, ICE, Secteur..."
                            wire:model.live.debounce.500ms="search"
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all shadow-sm"
                        />
                    </div>
                </div>

                {{-- Filters Section --}}
                <div class="space-y-4">
                    {{-- Ville Filter --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ville</label>
                        <div class="relative">
                            <select
                                wire:model.live="ville"
                                class="w-full appearance-none bg-white border border-gray-300 rounded-lg px-3 py-2.5 pr-10 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all shadow-sm"
                            >
                                <option value="">Toutes les villes</option>
                                <optgroup label="Villes principales">
                                    @foreach($moroccanCities as $city)
                                        <option value="{{ $city }}">{{ $city }}</option>
                                    @endforeach
                                </optgroup>
                                @if($availableVilles->count() > count($moroccanCities))
                                    <optgroup label="Autres villes">
                                        @foreach($availableVilles as $villeOption)
                                            @if(!in_array($villeOption, $moroccanCities))
                                                <option value="{{ $villeOption }}">{{ $villeOption }}</option>
                                            @endif
                                        @endforeach
                                    </optgroup>
                                @endif
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Secteur Filter --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Secteur</label>
                        <div class="relative">
                            <select
                                wire:model.live="secteur"
                                class="w-full appearance-none bg-white border border-gray-300 rounded-lg px-3 py-2.5 pr-10 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all shadow-sm"
                            >
                                <option value="">Tous les secteurs</option>
                                @foreach($availableSecteurs as $secteurOption)
                                    <option value="{{ $secteurOption }}">{{ $secteurOption }}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- État Filter --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">État</label>
                        <div class="relative">
                            <select
                                wire:model.live="etat"
                                class="w-full appearance-none bg-white border border-gray-300 rounded-lg px-3 py-2.5 pr-10 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all shadow-sm"
                            >
                                <option value="">Tous les états</option>
                                <option value="oui">Actif</option>
                                <option value="non">Inactif</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Type Filter --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                        <select
                            wire:model.live="type"
                            class="w-full appearance-none bg-white border border-gray-300 rounded-lg px-3 py-2.5 pr-10 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all shadow-sm"
                        >
                            <option value="">Tous les types</option>
                            <option value="PP">Personne Physique (PP)</option>
                            <option value="PM">Personne Morale (PM)</option>
                        </select>
                    </div>

                    {{-- Taille Filter --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Taille</label>
                        <select
                            wire:model.live="taille"
                            class="w-full appearance-none bg-white border border-gray-300 rounded-lg px-3 py-2.5 pr-10 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all shadow-sm"
                        >
                            <option value="">Toutes les tailles</option>
                            <option value="PME">PME</option>
                            <option value="GE">Grande Entreprise</option>
                            <option value="SU">Start-up</option>
                        </select>
                    </div>

                    {{-- Forme Juridique Filter --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Forme Juridique</label>
                        <select
                            wire:model.live="forme_juridique"
                            class="w-full appearance-none bg-white border border-gray-300 rounded-lg px-3 py-2.5 pr-10 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all shadow-sm"
                        >
                            <option value="">Toutes les formes</option>
                            <option value="SA">SA</option>
                            <option value="SARL">SARL</option>
                            <option value="SNC">SNC</option>
                            <option value="SCS">SCS</option>
                            <option value="autre">Autre</option>
                        </select>
                    </div>

                    {{-- Activité Filter --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Activité</label>
                        <select
                            wire:model.live="activite"
                            class="w-full appearance-none bg-white border border-gray-300 rounded-lg px-3 py-2.5 pr-10 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all shadow-sm"
                        >
                            <option value="">Toutes les activités</option>
                            @foreach($availableActivites as $activiteOption)
                                <option value="{{ $activiteOption }}">{{ $activiteOption }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Filter Buttons --}}
                <div class="pt-4 border-t border-gray-200 space-y-3">
                    <button
                        wire:click="applyFilters"
                        class="w-full bg-orange-600 hover:bg-orange-700 text-white font-medium py-2.5 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center space-x-2 shadow-md"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        <span>Appliquer les filtres</span>
                    </button>

                    <button
                        wire:click="resetFilters"
                        class="w-full bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium py-2.5 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center space-x-2 shadow-sm"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        <span>Réinitialiser</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- RIGHT CONTENT --}}
        <div class="flex-1 flex flex-col min-w-0">
            {{-- MAP (50% HEIGHT) --}}
            <div class="flex-1 bg-white shadow-sm border-b border-gray-200" wire:ignore>
                <div id="dashboardMap" class="h-full w-full"></div>
            </div>

            {{-- TABLE (50% HEIGHT) --}}
            <div class="flex-1 bg-white shadow-sm overflow-hidden">
                <div class="p-6 h-full flex flex-col">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">
                            Liste des Entreprises
                        </h3>
                        <div class="flex space-x-2">
                            <button
                                wire:click="exportData"
                                class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                            >
                                <svg class="-ml-0.5 mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Exporter
                            </button>
                            <button
                                wire:click="createNewEntity"
                                class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500"
                            >
                                <svg class="-ml-0.5 mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Nouvelle entreprise
                            </button>
                        </div>
                    </div>

                    <div class="flex-1 overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
                        <div class="h-full overflow-auto">
                            <table class="min-w-full divide-y divide-gray-300">
                                <thead class="bg-gray-50 sticky top-0 z-10">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nom entreprise
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Ville
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Secteur
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Activité
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Contact
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            État
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky right-0 bg-gray-50">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($entreprises as $entreprise)
                                        <tr
                                            class="hover:bg-gray-50 transition-colors duration-150 cursor-pointer"
                                            wire:click="flyToMarker({{ $entreprise->id }})"
                                        >
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $entreprise->nom_entreprise }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $entreprise->ville ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $entreprise->secteur ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $entreprise->activite ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $entreprise->contact ?? '-' }}<br>
                                                <span class="text-xs text-gray-400">{{ $entreprise->tel ?? '' }}</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $entreprise->en_activite === 'oui' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $entreprise->en_activite === 'oui' ? 'Actif' : 'Inactif' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium sticky right-0 bg-white z-10">
                                                <div class="flex space-x-2">
                                                    <button
                                                        wire:click.stop="editEntreprise({{ $entreprise->id }})"
                                                        class="text-orange-600 hover:text-orange-900 transition-colors p-1 rounded hover:bg-orange-50"
                                                        title="Modifier"
                                                    >
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </button>
                                                    <button
                                                        wire:click.stop="deleteEntreprise({{ $entreprise->id }})"
                                                        class="text-red-600 hover:text-red-900 transition-colors p-1 rounded hover:bg-red-50"
                                                        title="Supprimer"
                                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette entreprise ?')"
                                                    >
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                                <div class="flex flex-col items-center">
                                                    <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <p class="mt-2 text-sm font-medium text-gray-700">Aucune entreprise trouvée</p>
                                                    <p class="text-xs text-gray-500">Essayez de modifier vos critères de recherche</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Loading Indicator --}}
    <div wire:loading class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 flex items-center space-x-4 shadow-xl">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-orange-600"></div>
            <span class="text-gray-700 font-medium">Chargement des données...</span>
        </div>
    </div>

    @push('scripts')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
              integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI="
              crossorigin=""/>
        <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"
                integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM="
                crossorigin=""></script>

        <script>
            let map;
            let markers = [];
            let markersCluster;

            function initMap() {
                if (map) {
                    map.remove();
                }

                map = L.map('dashboardMap', {
                    zoomControl: false,
                    preferCanvas: true
                }).setView([31.63, -8.0], 6);

                // Add zoom control with custom position
                L.control.zoom({
                    position: 'topright'
                }).addTo(map);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                    maxZoom: 18
                }).addTo(map);

                // Add scale control
                L.control.scale({
                    position: 'bottomleft',
                    metric: true,
                    imperial: false
                }).addTo(map);

                // Initialiser avec les données existantes
                const initialData = @json($mapData ?? []);
                updateMarkers(initialData);
            }

            function updateMarkers(mapData) {
                // Nettoyer les marqueurs existants
                markers.forEach(marker => {
                    if (map.hasLayer(marker)) {
                        map.removeLayer(marker);
                    }
                });
                markers = [];

                // Ajouter les nouveaux marqueurs
                mapData.forEach(entreprise => {
                    if (entreprise.lat && entreprise.lng) {
                        const isActive = entreprise.en_activite === 'oui';
                        const marker = L.circleMarker([parseFloat(entreprise.lat), parseFloat(entreprise.lng)], {
                            entrepriseId: entreprise.id,
                            radius: 8,
                            fillColor: isActive ? '#EA580C' : '#EF4444',
                            color: '#ffffff',
                            weight: 2,
                            opacity: 1,
                            fillOpacity: 0.8
                        })
                        .addTo(map)
                        .bindPopup(`
                            <div class="p-4 min-w-64 max-w-xs">
                                <div class="flex items-start justify-between">
                                    <h4 class="text-lg font-bold text-gray-900 mb-2">${entreprise.nom || 'N/A'}</h4>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium ${isActive ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                        ${isActive ? 'Actif' : 'Inactif'}
                                    </span>
                                </div>

                                <div class="grid grid-cols-2 gap-2 text-sm">
                                    <div class="col-span-2">
                                        <p class="text-gray-600"><strong class="text-gray-800">ICE:</strong> ${entreprise.code_ice || '-'}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600"><strong class="text-gray-800">Ville:</strong> ${entreprise.ville || '-'}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600"><strong class="text-gray-800">Secteur:</strong> ${entreprise.secteur || '-'}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600"><strong class="text-gray-800">Activité:</strong> ${entreprise.activite || '-'}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600"><strong class="text-gray-800">Taille:</strong> ${entreprise.taille || '-'}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600"><strong class="text-gray-800">Forme:</strong> ${entreprise.forme_juridique || '-'}</p>
                                    </div>
                                    <div class="col-span-2">
                                        <p class="text-gray-600"><strong class="text-gray-800">Adresse:</strong> ${entreprise.adresse || '-'}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600"><strong class="text-gray-800">Tél:</strong> ${entreprise.tel || '-'}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600"><strong class="text-gray-800">Email:</strong> ${entreprise.email || '-'}</p>
                                    </div>
                                    <div class="col-span-2">
                                        <p class="text-gray-600"><strong class="text-gray-800">Site web:</strong> ${entreprise.site_web ? `<a href="${entreprise.site_web}" target="_blank" class="text-orange-600 hover:underline">${entreprise.site_web}</a>` : '-'}</p>
                                    </div>
                                </div>

                                <div class="mt-3 pt-3 border-t border-gray-200 flex justify-end space-x-2">
                                    <button onclick="window.Livewire.dispatch('editEntreprise', { entrepriseId: ${entreprise.id} })" class="px-2 py-1 text-xs bg-orange-100 text-orange-800 rounded hover:bg-orange-200 transition-colors">
                                        Modifier
                                    </button>
                                </div>
                            </div>
                        `);

                        markers.push(marker);
                    }
                });

                // Ajuster la vue si il y a des marqueurs
                if (markers.length > 0) {
                    const group = new L.featureGroup(markers);
                    map.fitBounds(group.getBounds(), {
                        padding: [50, 50],
                        maxZoom: 12
                    });
                } else {
                    // Revenir à la vue par défaut si aucun marqueur
                    map.setView([31.63, -8.0], 6);
                }
            }

            // Initialiser la carte au chargement de la page
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(initMap, 100);
            });

            // Écouter les événements Livewire
            document.addEventListener('livewire:initialized', () => {
                // Événement pour mettre à jour la carte
                Livewire.on('updateMap', (event) => {
                    if (event && event.mapData) {
                        updateMarkers(event.mapData);
                    }
                });

                // Événement pour voler vers un marqueur spécifique
                Livewire.on('flyToMarker', (event) => {
                    if (event && event.entrepriseId) {
                        const marker = markers.find(m => m.options.entrepriseId == event.entrepriseId);
                        if (marker) {
                            map.flyTo(marker.getLatLng(), 15, {
                                animate: true,
                                duration: 1.5
                            });
                            setTimeout(() => {
                                marker.openPopup();
                            }, 1600);
                        }
                    }
                });
            });

            // Redimensionner la carte lors du redimensionnement de la fenêtre
            window.addEventListener('resize', function() {
                if (map) {
                    setTimeout(() => {
                        map.invalidateSize();
                    }, 100);
                }
            });

            // Gérer les changements de taille du conteneur
            const resizeObserver = new ResizeObserver(() => {
                if (map) {
                    setTimeout(() => {
                        map.invalidateSize();
                    }, 100);
                }
            });

            const mapContainer = document.getElementById('dashboardMap');
            if (mapContainer) {
                resizeObserver.observe(mapContainer);
            }
        </script>
    @endpush
</x-filament::page>
