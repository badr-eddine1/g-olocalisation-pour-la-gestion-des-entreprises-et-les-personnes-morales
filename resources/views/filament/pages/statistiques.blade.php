<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Key Metrics Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- Total Entreprises --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold mb-1 text-gray-800 dark:text-gray-100">Total</h3>
                        <p class="text-3xl font-bold text-orange-600 dark:text-orange-400">{{ $totalEntreprises }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Entreprises</p>
                    </div>
                    <div class="p-3 bg-orange-100 dark:bg-orange-900/20 rounded-lg">
                        <x-heroicon-o-building-office class="w-8 h-8 text-orange-600 dark:text-orange-400" />
                    </div>
                </div>
            </div>

            {{-- Entreprises Actives --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold mb-1 text-gray-800 dark:text-gray-100">Actives</h3>
                        <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $entreprisesActives }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $tauxActivite }}% du total</p>
                    </div>
                    <div class="p-3 bg-green-100 dark:bg-green-900/20 rounded-lg">
                        <x-heroicon-o-check-circle class="w-8 h-8 text-green-600 dark:text-green-400" />
                    </div>
                </div>
            </div>

            {{-- Entreprises Inactives --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold mb-1 text-gray-800 dark:text-gray-100">Inactives</h3>
                        <p class="text-3xl font-bold text-red-600 dark:text-red-400">{{ $entreprisesInactives }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ 100 - $tauxActivite }}% du total</p>
                    </div>
                    <div class="p-3 bg-red-100 dark:bg-red-900/20 rounded-lg">
                        <x-heroicon-o-x-circle class="w-8 h-8 text-red-600 dark:text-red-400" />
                    </div>
                </div>
            </div>

            {{-- Croissance --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold mb-1 text-gray-800 dark:text-gray-100">Croissance</h3>
                        <p class="text-3xl font-bold {{ $croissanceMensuelle > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                            {{ $croissanceMensuelle > 0 ? '+' : '' }}{{ $croissanceMensuelle }}%
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Ce mois</p>
                    </div>
                    <div class="p-3 {{ $croissanceMensuelle > 0 ? 'bg-green-100 dark:bg-green-900/20' : 'bg-red-100 dark:bg-red-900/20' }} rounded-lg">
                        @if($croissanceMensuelle > 0)
                            <x-heroicon-o-arrow-trending-up class="w-8 h-8 {{ $croissanceMensuelle > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}" />
                        @else
                            <x-heroicon-o-arrow-trending-down class="w-8 h-8 {{ $croissanceMensuelle > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}" />
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Charts Section --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Status Pie Chart --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100">Répartition par Statut</h3>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200">
                        {{ $tauxActivite ?? 0 }}% Actives
                    </span>
                </div>
                <div class="h-64">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>

            {{-- Monthly Evolution --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100">Évolution Mensuelle</h3>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $croissanceMensuelle >= 0 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                        {{ $croissanceMensuelle >= 0 ? '↑' : '↓' }} {{ abs($croissanceMensuelle) }}%
                    </span>
                </div>
                <div class="h-64">
                    <canvas id="evolutionChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Top Sectors and Cities --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Top Sectors --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100">Secteurs Principaux</h3>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200">
                        {{ count($secteursPrincipaux) }} Secteurs
                    </span>
                </div>
                <div class="space-y-3">
                    @foreach($secteursPrincipaux as $index => $secteur)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-4 h-4 rounded-full"
                                     style="background-color: {{ ['#F97316', '#8B5CF6', '#06B6D4', '#10B981', '#F59E0B', '#EF4444', '#EC4899', '#6366F1', '#84CC16', '#14B8A6'][$index % 10] }}">
                                </div>
                                <span class="font-medium text-gray-800 dark:text-gray-100">{{ $secteur['nom'] }}</span>
                            </div>
                            <div class="text-right">
                                <div class="font-bold text-gray-800 dark:text-gray-100">{{ $secteur['total'] }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">{{ $secteur['pourcentage'] }}%</div>
                            </div>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="h-2 rounded-full transition-all duration-500"
                                 style="width: {{ $secteur['pourcentage'] }}%; background-color: {{ ['#F97316', '#8B5CF6', '#06B6D4', '#10B981', '#F59E0B', '#EF4444', '#EC4899', '#6366F1', '#84CC16', '#14B8A6'][$index % 10] }}">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Top Cities --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100">Villes Principales</h3>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200">
                        {{ count($villesPrincipales) }} Villes
                    </span>
                </div>
                <div class="space-y-3">
                    @foreach($villesPrincipales as $index => $ville)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-4 h-4 rounded-full"
                                     style="background-color: {{ ['#F59E0B', '#F97316', '#EF4444', '#8B5CF6', '#06B6D4', '#10B981', '#EC4899', '#6366F1', '#84CC16', '#14B8A6'][$index % 10] }}">
                                </div>
                                <span class="font-medium text-gray-800 dark:text-gray-100">{{ $ville['nom'] }}</span>
                            </div>
                            <div class="text-right">
                                <div class="font-bold text-gray-800 dark:text-gray-100">{{ $ville['total'] }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">{{ $ville['pourcentage'] }}%</div>
                            </div>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="h-2 rounded-full transition-all duration-500"
                                 style="width: {{ $ville['pourcentage'] }}%; background-color: {{ ['#F59E0B', '#F97316', '#EF4444', '#8B5CF6', '#06B6D4', '#10B981', '#EC4899', '#6366F1', '#84CC16', '#14B8A6'][$index % 10] }}">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Data Completeness Overview --}}
        @php
            $completude = $this->getAperçuCompletude();
        @endphp
        @if(!empty($completude))
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100">Complétude des Données</h3>
            </div>
            <br>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Informations de Base --}}
                <div>
                    <h4 class="font-semibold mb-3 text-gray-700 dark:text-gray-300">Informations de Base</h4>
                    <div class="space-y-3">
                        @foreach($completude['informationsBase'] as $field => $percentage)
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ ucfirst($field) }}</span>
                                    <span class="text-sm font-medium text-gray-800 dark:text-gray-100">{{ $percentage }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-orange-500 to-amber-500 h-2 rounded-full transition-all duration-500"
                                         style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Contact --}}
                <div>
                    <h4 class="font-semibold mb-3 text-gray-700 dark:text-gray-300">Contact</h4>
                    <div class="space-y-3">
                        @foreach($completude['contact'] as $field => $percentage)
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ ucfirst($field) }}</span>
                                    <span class="text-sm font-medium text-gray-800 dark:text-gray-100">{{ $percentage }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-orange-400 to-orange-500 h-2 rounded-full transition-all duration-500"
                                         style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Légal --}}
                <div>
                    <h4 class="font-semibold mb-3 text-gray-700 dark:text-gray-300">Informations Légales</h4>
                    <div class="space-y-3">
                        @foreach($completude['legal'] as $field => $percentage)
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ strtoupper($field) }}</span>
                                    <span class="text-sm font-medium text-gray-800 dark:text-gray-100">{{ $percentage }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-amber-500 to-yellow-500 h-2 rounded-full transition-all duration-500"
                                         style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Additional Statistics --}}
        @php
            $statsAvancees = $this->getStatistiquesAvancees();
            $companyTypes = $this->getDistributionType();
        @endphp
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- Contact Stats --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border border-gray-200 dark:border-gray-700">
                <div class="flex items-center mb-3">
                    <div class="p-2 bg-orange-100 dark:bg-orange-900/20 rounded-lg mr-3">
                        <x-heroicon-o-envelope class="w-5 h-5 text-orange-600 dark:text-orange-400" />
                    </div>
                    <h4 class="font-semibold text-gray-800 dark:text-gray-100">Contact</h4>
                </div>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Email:</span>
                        <span class="font-bold text-gray-800 dark:text-gray-100">{{ $statsAvancees['entreprisesAvecEmail'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Téléphone:</span>
                        <span class="font-bold text-gray-800 dark:text-gray-100">{{ $statsAvancees['entreprisesAvecTelephone'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Site Web:</span>
                        <span class="font-bold text-gray-800 dark:text-gray-100">{{ $statsAvancees['entreprisesAvecSiteWeb'] }}</span>
                    </div>
                </div>
            </div>

            {{-- Legal Stats --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border border-gray-200 dark:border-gray-700">
                <div class="flex items-center mb-3">
                    <div class="p-2 bg-purple-100 dark:bg-purple-900/20 rounded-lg mr-3">
                        <x-heroicon-o-document-text class="w-5 h-5 text-purple-600 dark:text-purple-400" />
                    </div>
                    <h4 class="font-semibold text-gray-800 dark:text-gray-100">Légal</h4>
                </div>
                <div class="space-y-2 text-sm">
                    @php
                        $statsLegales = $this->getStatistiquesLegales();
                    @endphp
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">RC:</span>
                        <span class="font-bold text-gray-800 dark:text-gray-100">{{ $statsLegales['avecRC'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">ICE:</span>
                        <span class="font-bold text-gray-800 dark:text-gray-100">{{ $statsLegales['avecICE'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Patente:</span>
                        <span class="font-bold text-gray-800 dark:text-gray-100">{{ $statsLegales['avecPatente'] }}</span>
                    </div>
                </div>
            </div>

            {{-- Location Stats --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border border-gray-200 dark:border-gray-700">
                <div class="flex items-center mb-3">
                    <div class="p-2 bg-teal-100 dark:bg-teal-900/20 rounded-lg mr-3">
                        <x-heroicon-o-map-pin class="w-5 h-5 text-teal-600 dark:text-teal-400" />
                    </div>
                    <h4 class="font-semibold text-gray-800 dark:text-gray-100">Localisation</h4>
                </div>
                <div class="space-y-2 text-sm">
                    @php
                        $statsLocalisation = $this->getStatistiquesLocalisation();
                    @endphp
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Adresses:</span>
                        <span class="font-bold text-gray-800 dark:text-gray-100">{{ $statsLocalisation['avecAdresse'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Coordonnées:</span>
                        <span class="font-bold text-gray-800 dark:text-gray-100">{{ $statsLocalisation['avecCoordonnees'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Villes uniques:</span>
                        <span class="font-bold text-gray-800 dark:text-gray-100">{{ $statsLocalisation['villesUniques'] }}</span>
                    </div>
                </div>
            </div>

            {{-- Company Types --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg border border-gray-200 dark:border-gray-700">
                <div class="flex items-center mb-3">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900/20 rounded-lg mr-3">
                        <x-heroicon-o-tag class="w-5 h-5 text-blue-600 dark:text-blue-400" />
                    </div>
                    <h4 class="font-semibold text-gray-800 dark:text-gray-100">Types d'Entreprise</h4>
                </div>
                <div class="space-y-3">
                    @foreach($companyTypes as $index => $type)
                        @if($index < 3) {{-- Show top 3 types --}}
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-4 h-4 rounded-full"
                                         style="background-color: {{ ['#3B82F6', '#6366F1', '#8B5CF6'][$index % 3] }}"></div>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ $type['nom'] }}</span>
                                </div>
                                <div class="text-right">
                                    <span class="text-sm font-bold text-gray-800 dark:text-gray-100">{{ $type['total'] }}</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400 ml-1">{{ $type['pourcentage'] }}%</span>
                                </div>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                                <div class="h-1.5 rounded-full"
                                     style="width: {{ $type['pourcentage'] }}%; background-color: {{ ['#3B82F6', '#6366F1', '#8B5CF6'][$index % 3] }}"></div>
                            </div>
                        @endif
                    @endforeach
                    @if(count($companyTypes) > 3)
                        <div class="text-center mt-2">
                            <span class="text-xs text-gray-500 dark:text-gray-400">+{{ count($companyTypes) - 3 }} autres types</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent entries table -->
        <div class="mt-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition-shadow duration-200">
                <div class="flex items-center mb-4">
                    <div class="p-2 bg-gradient-to-br from-yellow-100 to-amber-100 dark:from-yellow-900/20 dark:to-amber-900/20 rounded-lg mr-3">
                        <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm1-13h-2v6h2V7zm0 8h-2v2h2v-2z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Entreprises Récentes</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gradient-to-r from-purple-50 to-blue-50 dark:from-purple-900/20 dark:to-blue-900/20">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-purple-800 dark:text-purple-300 uppercase tracking-wider">Nom Entreprise</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-purple-800 dark:text-purple-300 uppercase tracking-wider">Ville</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-purple-800 dark:text-purple-300 uppercase tracking-wider">Secteur</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-purple-800 dark:text-purple-300 uppercase tracking-wider">Taille</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-purple-800 dark:text-purple-300 uppercase tracking-wider">Forme Juridique</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-purple-800 dark:text-purple-300 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-purple-800 dark:text-purple-300 uppercase tracking-wider">Status</th>
                                @if(Schema::hasColumn('entreprises', 'created_at'))
                                <th class="px-6 py-3 text-left text-xs font-medium text-purple-800 dark:text-purple-300 uppercase tracking-wider">Date</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach(\App\Models\Entreprise::latest()->limit(8)->get() as $entreprise)
                            <tr class="hover:bg-gradient-to-r hover:from-purple-50 hover:to-blue-50 dark:hover:from-purple-900/10 dark:hover:to-blue-900/10 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ Str::limit($entreprise->nom_entreprise ?? 'N/A', 25) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $entreprise->ville ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ Str::limit($entreprise->secteur ?? 'N/A', 20) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $entreprise->taille_entreprise ?? 'N/A' }}
                                </td>
                                 <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $entreprise->forme_juridique ?? 'N/A' }}
                                </td>
                                 <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $entreprise->email ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($entreprise->en_activite=='oui')
                                        <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium rounded-full bg-gradient-to-r from-green-100 to-teal-100 dark:from-green-800/20 dark:to-teal-800/20 text-green-800 dark:text-green-400 border border-green-200 dark:border-green-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            Active
                                        </span>
                                    @endif
                                    @if($entreprise->en_activite=='non')
                                        <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium rounded-full bg-gradient-to-r from-pink-100 to-red-100 dark:from-pink-800/20 dark:to-red-800/20 text-pink-800 dark:text-pink-400 border border-pink-200 dark:border-pink-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                            </svg>
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                @if(Schema::hasColumn('entreprises', 'created_at'))
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $entreprise->created_at?->format('d/m/Y') ?? 'N/A' }}
                                </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Chart.js Scripts --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Status Pie Chart
            const statusCtx = document.getElementById('statusChart').getContext('2d');
            const statusChart = new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Actives', 'Inactives'],
                    datasets: [{
                        data: [{{ $entreprisesActives }}, {{ $entreprisesInactives }}],
                        backgroundColor: ['#6366F1', '#EF4444'],
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#E5E7EB' : '#374151',
                                padding: 20,
                                font: {
                                    size: 12,
                                    weight: 'bold'
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: window.matchMedia('(prefers-color-scheme: dark)').matches ? 'rgba(0,0,0,0.9)' : 'rgba(255,255,255,0.9)',
                            titleColor: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#E5E7EB' : '#111827',
                            bodyColor: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#E5E7EB' : '#111827',
                            borderColor: '#9CA3AF',
                            borderWidth: 1
                        }
                    },
                    cutout: '60%'
                }
            });

            // Monthly Evolution Chart
            const evolutionCtx = document.getElementById('evolutionChart').getContext('2d');
            @php
                $evolution = $statsAvancees['evolutionMensuelle'] ?? [];
                $months = array_column($evolution, 'mois');
                $totals = array_column($evolution, 'total');
            @endphp
            const evolutionChart = new Chart(evolutionCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($months) !!},
                    datasets: [{
                        label: 'Nouvelles entreprises',
                        data: {!! json_encode($totals) !!},
                        borderColor: '#F97316',
                        backgroundColor: 'rgba(249, 115, 22, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointBackgroundColor: '#F97316',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: window.matchMedia('(prefers-color-scheme: dark)').matches ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)'
                            },
                            ticks: {
                                color: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#E5E7EB' : '#6B7280'
                            }
                        },
                        x: {
                            grid: {
                                color: window.matchMedia('(prefers-color-scheme: dark)').matches ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)'
                            },
                            ticks: {
                                color: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#E5E7EB' : '#6B7280'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            labels: {
                                color: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#E5E7EB' : '#374151'
                            }
                        }
                    }
                }
            });

            // Dark mode toggle for charts
            const darkModeMediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
            darkModeMediaQuery.addListener((e) => {
                const isDarkMode = e.matches;

                // Update all charts' text colors
                [statusChart, evolutionChart].forEach(chart => {
                    chart.options.plugins.legend.labels.color = isDarkMode ? '#E5E7EB' : '#374151';
                    chart.options.plugins.tooltip.backgroundColor = isDarkMode ? 'rgba(0,0,0,0.9)' : 'rgba(255,255,255,0.9)';
                    chart.options.plugins.tooltip.titleColor = isDarkMode ? '#E5E7EB' : '#111827';
                    chart.options.plugins.tooltip.bodyColor = isDarkMode ? '#E5E7EB' : '#111827';

                    if (chart.options.scales) {
                        if (chart.options.scales.x) {
                            chart.options.scales.x.ticks.color = isDarkMode ? '#E5E7EB' : '#6B7280';
                            chart.options.scales.x.grid.color = isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
                        }
                        if (chart.options.scales.y) {
                            chart.options.scales.y.ticks.color = isDarkMode ? '#E5E7EB' : '#6B7280';
                            chart.options.scales.y.grid.color = isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
                        }
                    }

                    chart.update();
                });
            });
        });
    </script>
</x-filament-panels::page>
