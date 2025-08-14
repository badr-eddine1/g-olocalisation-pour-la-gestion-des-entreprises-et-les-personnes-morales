<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>GeoLocalisation - Solution de gestion d'entreprises</title>
    <meta name="description" content="Plateforme de gestion et de localisation des entreprises avec carte interactive">

    <!-- Favicon -->
    <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/2103/2103633.png" type="image/png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#fff8e1',
                            100: '#ffecb3',
                            200: '#ffe082',
                            300: '#ffd54f',
                            400: '#ffca28',
                            500: '#ffc107',
                            600: '#ffb300',
                            700: '#ffa000',
                            800: '#ff8f00',
                            900: '#ff6f00',
                        }
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.5s ease-out',
                        'pulse-slow': 'pulse 3s infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(20px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        },
                    }
                }
            }
        }
    </script>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <!-- Leaflet MarkerCluster CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.Default.css" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .hero-gradient {
            background: linear-gradient(135deg, #ffc107 0%, #ff8f00 100%);
        }

        .hover-scale {
            transition: transform 0.3s ease;
        }

        .hover-scale:hover {
            transform: scale(1.03);
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .map-tooltip {
            background: white;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .scroll-smooth {
            scroll-behavior: smooth;
        }

        .navbar-shadow {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .table-row-hover:hover {
            background-color: rgba(255, 193, 7, 0.1);
        }

        select option[disabled][selected] {
            color: #9CA3AF;
        }

        .filter-sidebar {
            height: calc(100vh - 150px);
            overflow-y: auto;
            position: sticky;
            top: 120px;
        }

        .stats-chart {
            height: 300px;
            position: relative;
        }

        .stat-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .stat-card.active {
            border: 2px solid #ffc107;
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
            animation: fadeIn 0.5s ease-in-out;
        }

        .progress-bar {
            height: 10px;
            background-color: #e5e7eb;
            border-radius: 5px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background-color: #ffc107;
            transition: width 0.8s ease-in-out;
        }

        @media (max-width: 1023px) {
            .filter-sidebar {
                height: auto;
                position: static;
            }
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 pt-20 font-sans scroll-smooth">

    <!-- Navbar -->
    <nav class="bg-white navbar-shadow fixed w-full top-0 z-50 transition-all duration-300">
        <div class="container mx-auto px-6 py-3 flex justify-between items-center">
            <a href="#" class="text-2xl font-bold text-primary-600 flex items-center">
                <i class="fas fa-map-marked-alt mr-2 text-primary-500"></i>
                <span class="bg-gradient-to-r from-primary-600 to-primary-800 bg-clip-text text-transparent">GeoLocalisation</span>
            </a>

            <!-- Mobile menu button -->
            <button id="mobile-menu-button" class="md:hidden focus:outline-none">
                <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

            <!-- Desktop Menu -->
            <div class="hidden md:flex space-x-8 items-center">
                <a href="#home" class="text-gray-700 hover:text-primary-600 transition-colors duration-300 relative group">
                    Accueil
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-primary-500 transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="#entreprises" class="text-gray-700 hover:text-primary-600 transition-colors duration-300 relative group">
                    Entreprises
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-primary-500 transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="#statistiques" class="text-gray-700 hover:text-primary-600 transition-colors duration-300 relative group">
                    Statistiques
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-primary-500 transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="#services" class="text-gray-700 hover:text-primary-600 transition-colors duration-300 relative group">
                    Services
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-primary-500 transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="#contact" class="text-gray-700 hover:text-primary-600 transition-colors duration-300 relative group">
                    Contact
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-primary-500 transition-all duration-300 group-hover:w-full"></span>
                </a>

                <a href="{{ url('admin/login') }}"
                   class="bg-gradient-to-r from-primary-500 to-primary-600 text-white px-4 py-2 rounded-lg shadow hover:shadow-lg transition-all duration-300 hover:from-primary-600 hover:to-primary-700 flex items-center">
                    <i class="fas fa-sign-in-alt mr-2"></i> Se connecter
                </a>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="md:hidden hidden bg-white w-full px-6 py-4 transition-all duration-300">
            <div class="flex flex-col space-y-4">
                <a href="#home" class="text-gray-700 hover:text-primary-600 transition-colors duration-300">Accueil</a>
                <a href="#entreprises" class="text-gray-700 hover:text-primary-600 transition-colors duration-300">Entreprises</a>
                <a href="#statistiques" class="text-gray-700 hover:text-primary-600 transition-colors duration-300">Statistiques</a>
                <a href="#services" class="text-gray-700 hover:text-primary-600 transition-colors duration-300">Services</a>
                <a href="#contact" class="text-gray-700 hover:text-primary-600 transition-colors duration-300">Contact</a>
                <a href="{{ url('admin/login') }}"
                   class="bg-primary-500 text-white px-4 py-2 rounded-lg text-center hover:bg-primary-600 transition">
                    Se connecter
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-gradient text-white pt-24">
        <div class="container mx-auto px-6 py-20 text-center animate-fade-in">
            <div class="max-w-4xl mx-auto">
                <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight animate-slide-up">
                    Géolocalisez vos entreprises en toute simplicité
                </h1>
                <p class="text-xl md:text-2xl mb-8 opacity-90 animate-slide-up animation-delay-100">
                    La solution complète pour gérer et visualiser vos entreprises sur une carte interactive.
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4 animate-slide-up animation-delay-200">
                    <a href="{{ url('admin/login') }}"
                       class="bg-white text-primary-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition-all duration-300 hover:shadow-lg flex items-center justify-center">
                        <i class="fas fa-rocket mr-2"></i> Commencer maintenant
                    </a>
                    <a href="#entreprises"
                       class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-primary-600 transition-all duration-300 flex items-center justify-center">
                        <i class="fas fa-search mr-2"></i> Explorer les entreprises
                    </a>
                </div>
            </div>

            <!-- Stats Section -->
            <div class="mt-16 grid grid-cols-2 md:grid-cols-4 gap-6 max-w-5xl mx-auto animate-slide-up animation-delay-300">
                <div class="bg-white bg-opacity-10 p-6 rounded-xl backdrop-blur-sm border border-white border-opacity-20">
                    <div class="text-3xl font-bold mb-2">{{ $stats['total'] ?? '100+' }}</div>
                    <div class="text-sm opacity-80">Entreprises</div>
                </div>
                <div class="bg-white bg-opacity-10 p-6 rounded-xl backdrop-blur-sm border border-white border-opacity-20">
                    <div class="text-3xl font-bold mb-2">{{ count($villes) ?? '20+' }}</div>
                    <div class="text-sm opacity-80">Villes</div>
                </div>
                <div class="bg-white bg-opacity-10 p-6 rounded-xl backdrop-blur-sm border border-white border-opacity-20">
                    <div class="text-3xl font-bold mb-2">{{ count($secteurs) ?? '15+' }}</div>
                    <div class="text-sm opacity-80">Secteurs</div>
                </div>
                <div class="bg-white bg-opacity-10 p-6 rounded-xl backdrop-blur-sm border border-white border-opacity-20">
                    <div class="text-3xl font-bold mb-2">24/7</div>
                    <div class="text-sm opacity-80">Disponible</div>
                </div>
            </div>
        </div>

        <!-- Wave Divider -->
        <div class="wave-divider">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" class="w-full">
                <path fill="#f9fafb" fill-opacity="1" d="M0,192L48,197.3C96,203,192,213,288,229.3C384,245,480,267,576,250.7C672,235,768,181,864,181.3C960,181,1056,235,1152,234.7C1248,235,1344,181,1392,154.7L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
            </svg>
        </div>
    </section>

    <!-- Liste des entreprises avec filtres et carte -->
    <section id="entreprises" class="py-16 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Nos entreprises partenaires</h2>
                <div class="w-20 h-1 bg-primary-500 mx-auto mb-6"></div>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Découvrez notre réseau d'entreprises réparties à travers le pays. Filtrez par ville ou secteur pour trouver ce que vous cherchez.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Colonne de gauche - Filtres -->
                <div class="lg:col-span-1 filter-sidebar">
                    <!-- Barre de recherche et filtres -->
                    <form method="GET" action="/" id="filter-form" class="bg-gray-50 p-6 rounded-xl shadow-sm">
                        <input type="hidden" name="scroll_position" id="scroll_position" value="{{ request('scroll_position') }}">
                        <div class="space-y-4">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher une entreprise"
                                       class="pl-10 w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            </div>

                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-city text-gray-400"></i>
                                </div>
                                <select name="ville" class="pl-10 w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent appearance-none">
                                    <option value="" disabled selected hidden class="text-gray-400">Sélectionnez une ville</option>
                                    @foreach ($villes as $ville)
                                        <option value="{{ $ville }}" {{ request('ville') == $ville ? 'selected' : '' }}>{{ $ville }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400"></i>
                                </div>
                            </div>

                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-briefcase text-gray-400"></i>
                                </div>
                                <select name="secteur" class="pl-10 w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent appearance-none">
                                    <option value="" disabled selected hidden class="text-gray-400">Sélectionnez un secteur</option>
                                    @foreach ($secteurs as $secteur)
                                        <option value="{{ $secteur }}" {{ request('secteur') == $secteur ? 'selected' : '' }}>{{ $secteur }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400"></i>
                                </div>
                            </div>

                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-balance-scale text-gray-400"></i>
                                </div>
                                <select name="forme_juridique" class="pl-10 w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent appearance-none">
                                    <option value="" disabled selected hidden class="text-gray-400">Forme juridique</option>
                                    @foreach ($formesJuridiques as $forme)
                                        <option value="{{ $forme }}" {{ request('forme_juridique') == $forme ? 'selected' : '' }}>{{ $forme }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400"></i>
                                </div>
                            </div>

                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-tag text-gray-400"></i>
                                </div>
                                <select name="type" class="pl-10 w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent appearance-none">
                                    <option value="" disabled selected hidden class="text-gray-400">Type</option>
                                    @foreach ($types as $type)
                                        <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400"></i>
                                </div>
                            </div>

                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-chart-line text-gray-400"></i>
                                </div>
                                <select name="taille" class="pl-10 w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent appearance-none">
                                    <option value="" disabled selected hidden class="text-gray-400">Taille entreprise</option>
                                    @foreach ($tailles as $taille)
                                        <option value="{{ $taille }}" {{ request('taille') == $taille ? 'selected' : '' }}>{{ $taille }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400"></i>
                                </div>
                            </div>

                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-power-off text-gray-400"></i>
                                </div>
                                <select name="etat" class="pl-10 w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent appearance-none">
                                    <option value="" disabled selected hidden class="text-gray-400">État</option>
                                    <option value="oui" {{ request('etat') == 'oui' ? 'selected' : '' }}>Actif</option>
                                    <option value="non" {{ request('etat') == 'non' ? 'selected' : '' }}>Inactif</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400"></i>
                                </div>
                            </div>

                            <div class="flex gap-2">
                                <button type="submit" class="bg-primary-500 text-white px-6 py-3 rounded-lg hover:bg-primary-600 transition flex-1 flex items-center justify-center gap-2">
                                    <i class="fas fa-filter"></i> Appliquer
                                </button>
                                <a href="{{ url('/') }}" class="bg-gray-200 text-gray-700 px-4 py-3 rounded-lg hover:bg-gray-300 transition flex items-center justify-center">
                                    <i class="fas fa-sync-alt"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Colonne de droite - Carte et résultats -->
                <div class="lg:col-span-3">
                    <div class="grid grid-cols-1 gap-8">
                        <!-- Carte -->
                        <div class="bg-white rounded-xl shadow-md overflow-hidden">
                            <div id="entreprise-map" class="w-full h-[400px] rounded-lg relative z-0"></div>
                            <div class="p-4 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
                                <div class="text-sm text-gray-600">
                                    <i class="fas fa-info-circle mr-1"></i> Utilisez la molette pour zoomer/dézoomer
                                </div>
                                <button id="reset-map-view" class="text-sm bg-white px-3 py-1 rounded border border-gray-300 hover:bg-gray-50 transition">
                                    <i class="fas fa-sync-alt mr-1"></i> Réinitialiser
                                </button>
                            </div>
                        </div>

                        <!-- Résultats -->
                        @php
                            $hasFilters = request('search') || request('ville') || request('secteur') || request('forme_juridique') || request('type') || request('taille') || request('etat');
                        @endphp

                        <!-- Message quand aucun filtre n'est appliqué -->
                        <div id="no-filters-message" class="{{ $hasFilters ? 'hidden' : 'text-center py-12' }}">
                            <div class="bg-gray-50 p-8 rounded-lg max-w-md mx-auto">
                                <i class="fas fa-filter text-4xl text-primary-500 mb-4"></i>
                                <h3 class="text-xl font-semibold mb-2">Appliquez des filtres pour voir les résultats</h3>
                                <p class="text-gray-600 mb-4">Utilisez les filtres à gauche pour afficher les entreprises correspondantes.</p>
                            </div>
                        </div>

                        <!-- Tableau (caché initialement) -->
                        <div id="results-container" class="{{ $hasFilters ? '' : 'hidden' }}">
                            <div class="overflow-x-auto rounded-xl shadow-sm border border-gray-200">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                <div class="flex items-center">
                                                    <i class="fas fa-building mr-2"></i> Nom
                                                </div>
                                            </th>
                                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                <div class="flex items-center">
                                                    <i class="fas fa-map-marker-alt mr-2"></i> Ville
                                                </div>
                                            </th>
                                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                <div class="flex items-center">
                                                    <i class="fas fa-industry mr-2"></i> Secteur
                                                </div>
                                            </th>
                                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                <div class="flex items-center">
                                                    <i class="fas fa-phone-alt mr-2"></i> Téléphone
                                                </div>
                                            </th>
                                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse ($entreprises as $entreprise)
                                            <tr class="table-row-hover transition-colors duration-150">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 h-10 w-10 bg-primary-100 rounded-full flex items-center justify-center">
                                                            <i class="fas fa-building text-primary-600"></i>
                                                        </div>
                                                        <div class="ml-4">
                                                            <div class="text-sm font-medium text-gray-900">{{ $entreprise->nom_entreprise }}</div>
                                                            <div class="text-sm text-gray-500">{{ $entreprise->adresse }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">{{ $entreprise->ville }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-primary-100 text-primary-800">
                                                        {{ $entreprise->secteur }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <a href="tel:{{ $entreprise->tel }}" class="hover:text-primary-600 transition">
                                                        {{ $entreprise->tel }}
                                                    </a>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <a href="#" class="text-primary-600 hover:text-primary-900 mr-3" onclick="showOnMap({{ $entreprise->latitude }}, {{ $entreprise->longitude }})">
                                                        <i class="fas fa-map-marker-alt"></i>
                                                    </a>
                                                    <a href="tel:{{ $entreprise->tel }}" class="text-green-600 hover:text-green-900">
                                                        <i class="fas fa-phone-alt"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                                    <div class="flex flex-col items-center justify-center py-8">
                                                        <i class="fas fa-search fa-3x text-gray-300 mb-4"></i>
                                                        <p class="text-lg">Aucune entreprise trouvée</p>
                                                        <p class="text-sm mt-2">Essayez de modifier vos critères de recherche</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-8 flex flex-col sm:flex-row items-center justify-between">
                                <div class="text-sm text-gray-500 mb-4 sm:mb-0">
                                    Affichage de {{ $entreprises->firstItem() }} à {{ $entreprises->lastItem() }} sur {{ $entreprises->total() }} résultats
                                </div>
                                <div class="flex items-center">
                                    {{ $entreprises->appends(request()->query())->links('pagination::tailwind') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistiques Section -->
    <section id="statistiques" class="py-16 bg-gray-50">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Statistiques avancées</h2>
                <div class="w-20 h-1 bg-primary-500 mx-auto mb-6"></div>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Explorez nos données statistiques pour obtenir des insights sur notre réseau d'entreprises.
                </p>
            </div>

            <!-- Contrôle des statistiques -->
            <div class="mb-8 flex flex-wrap gap-4 justify-center">
                <button id="stats-toggle" class="bg-primary-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-primary-600 transition flex items-center justify-center">
                    <i class="fas fa-chart-bar mr-2"></i> Afficher les statistiques
                </button>
            </div>

            <!-- Section des statistiques (cachée initialement) -->
            <div id="stats-container" class="hidden bg-white rounded-xl shadow-lg p-6 transition-all duration-500">
                <!-- Navigation par onglets -->
                <div class="flex flex-wrap gap-2 mb-8">
                    <button class="tab-btn active px-4 py-2 rounded-lg bg-primary-100 text-primary-700" data-tab="overview">
                        Vue d'ensemble
                    </button>
                    <button class="tab-btn px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200" data-tab="location">
                        Par localisation
                    </button>
                    <button class="tab-btn px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200" data-tab="sector">
                        Par secteur
                    </button>
                    <button class="tab-btn px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200" data-tab="size">
                        Par taille
                    </button>
                    <button class="tab-btn px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200" data-tab="status">
                        Par statut
                    </button>
                </div>

                <!-- Contenu des onglets -->
                <div class="tab-content active" id="overview-content">
                    <div class="grid md:grid-cols-3 gap-8 mb-8">
                        <div class="bg-gradient-to-r from-primary-500 to-primary-600 text-white rounded-xl p-8 shadow-lg">
                            <div class="text-4xl font-bold mb-2">{{ $stats['total'] }}</div>
                            <div class="text-lg">Total d'entreprises</div>
                        </div>

                        <div class="bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl p-8 shadow-lg">
                            <div class="text-4xl font-bold mb-2">{{ $stats['actives'] }}</div>
                            <div class="text-lg">Entreprises actives</div>
                        </div>

                        <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-xl p-8 shadow-lg">
                            <div class="text-4xl font-bold mb-2">{{ $stats['inactives'] }}</div>
                            <div class="text-lg">Entreprises inactives</div>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-8">
                        <div class="bg-gray-50 p-6 rounded-xl">
                            <h3 class="text-xl font-semibold mb-4 flex items-center">
                                <i class="fas fa-city mr-2 text-primary-500"></i> Répartition par ville
                            </h3>
                            <div class="space-y-4">
                                @foreach ($stats['par_ville'] as $ville)
                                <div>
                                    <div class="flex justify-between mb-1">
                                        <span class="font-medium">{{ $ville->ville ?: 'Non spécifié' }}</span>
                                        <span class="text-gray-600">{{ $ville->total }} entreprises</span>
                                    </div>
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: {{ ($ville->total / $stats['par_ville']->max('total')) * 100 }}%"></div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="bg-gray-50 p-6 rounded-xl">
                            <h3 class="text-xl font-semibold mb-4 flex items-center">
                                <i class="fas fa-industry mr-2 text-primary-500"></i> Répartition par secteur
                            </h3>
                            <div class="space-y-4">
                                @foreach ($stats['par_secteur'] as $secteur)
                                <div>
                                    <div class="flex justify-between mb-1">
                                        <span class="font-medium">{{ $secteur->secteur ?: 'Non spécifié' }}</span>
                                        <span class="text-gray-600">{{ $secteur->total }} entreprises</span>
                                    </div>
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: {{ ($secteur->total / $stats['par_secteur']->max('total')) * 100 }}%"></div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-content" id="location-content">
                    <h3 class="text-xl font-semibold mb-4 flex items-center">
                        <i class="fas fa-map-marker-alt mr-2 text-primary-500"></i> Statistiques par localisation
                    </h3>
                    <div class="grid md:grid-cols-2 gap-8">
                        <div class="bg-gray-50 p-6 rounded-xl">
                            <h4 class="text-lg font-medium mb-4">Top 5 des villes</h4>
                            <div class="space-y-4">
                                @foreach ($stats['par_ville'] as $ville)
                                <div class="flex items-center justify-between p-3 bg-white rounded-lg shadow-sm">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-primary-600 font-bold">{{ $loop->iteration }}</span>
                                        </div>
                                        <span class="font-medium">{{ $ville->ville ?: 'Non spécifié' }}</span>
                                    </div>
                                    <span class="bg-primary-100 text-primary-800 px-3 py-1 rounded-full text-sm font-bold">
                                        {{ $ville->total }}
                                    </span>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="bg-gray-50 p-6 rounded-xl">
                            <h4 class="text-lg font-medium mb-4">Répartition par région</h4>
                            <div class="space-y-4">
                                <!-- Exemple de données - à remplacer par vos données réelles -->
                                <div class="flex items-center justify-between">
                                    <span class="font-medium">Casablanca-Settat</span>
                                    <span class="text-gray-600">35%</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 35%"></div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <span class="font-medium">Rabat-Salé-Kénitra</span>
                                    <span class="text-gray-600">25%</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 25%"></div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <span class="font-medium">Marrakech-Safi</span>
                                    <span class="text-gray-600">15%</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 15%"></div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <span class="font-medium">Fès-Meknès</span>
                                    <span class="text-gray-600">12%</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 12%"></div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <span class="font-medium">Autres régions</span>
                                    <span class="text-gray-600">13%</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 13%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-content" id="sector-content">
                    <h3 class="text-xl font-semibold mb-4 flex items-center">
                        <i class="fas fa-industry mr-2 text-primary-500"></i> Statistiques par secteur d'activité
                    </h3>
                    <div class="grid md:grid-cols-2 gap-8">
                        <div class="bg-gray-50 p-6 rounded-xl">
                            <h4 class="text-lg font-medium mb-4">Top 5 des secteurs</h4>
                            <div class="space-y-4">
                                @foreach ($stats['par_secteur'] as $secteur)
                                <div class="flex items-center justify-between p-3 bg-white rounded-lg shadow-sm">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-primary-600 font-bold">{{ $loop->iteration }}</span>
                                        </div>
                                        <span class="font-medium">{{ $secteur->secteur ?: 'Non spécifié' }}</span>
                                    </div>
                                    <span class="bg-primary-100 text-primary-800 px-3 py-1 rounded-full text-sm font-bold">
                                        {{ $secteur->total }}
                                    </span>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="bg-gray-50 p-6 rounded-xl">
                            <h4 class="text-lg font-medium mb-4">Répartition par activité</h4>
                            <div class="space-y-4">
                                <!-- Exemple de données - à remplacer par vos données réelles -->
                                <div class="flex items-center justify-between">
                                    <span class="font-medium">Services</span>
                                    <span class="text-gray-600">32%</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 32%"></div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <span class="font-medium">Commerce</span>
                                    <span class="text-gray-600">28%</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 28%"></div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <span class="font-medium">Industrie</span>
                                    <span class="text-gray-600">22%</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 22%"></div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <span class="font-medium">Agriculture</span>
                                    <span class="text-gray-600">12%</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 12%"></div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <span class="font-medium">Tourisme</span>
                                    <span class="text-gray-600">6%</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 6%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-content" id="size-content">
                    <h3 class="text-xl font-semibold mb-4 flex items-center">
                        <i class="fas fa-chart-pie mr-2 text-primary-500"></i> Statistiques par taille d'entreprise
                    </h3>
                    <div class="grid md:grid-cols-2 gap-8">
                        <div class="bg-gray-50 p-6 rounded-xl">
                            <h4 class="text-lg font-medium mb-4">Répartition par taille</h4>
                            <div class="space-y-4">
                                <!-- Exemple de données - à remplacer par vos données réelles -->
                                <div class="flex items-center justify-between">
                                    <span class="font-medium">PME</span>
                                    <span class="text-gray-600">45%</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 45%"></div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <span class="font-medium">Grandes entreprises</span>
                                    <span class="text-gray-600">30%</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 30%"></div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <span class="font-medium">Start-ups</span>
                                    <span class="text-gray-600">15%</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 15%"></div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <span class="font-medium">Micro-entreprises</span>
                                    <span class="text-gray-600">10%</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 10%"></div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-6 rounded-xl">
                            <h4 class="text-lg font-medium mb-4">Taille par secteur</h4>
                            <div class="space-y-4">
                                <!-- Exemple de données - à remplacer par vos données réelles -->
                                <div class="flex items-center justify-between">
                                    <span class="font-medium">Industrie - GE</span>
                                    <span class="text-gray-600">65%</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 65%"></div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <span class="font-medium">Services - PME</span>
                                    <span class="text-gray-600">72%</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 72%"></div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <span class="font-medium">Commerce - SU</span>
                                    <span class="text-gray-600">38%</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 38%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-content" id="status-content">
                    <h3 class="text-xl font-semibold mb-4 flex items-center">
                        <i class="fas fa-chart-line mr-2 text-primary-500"></i> Statistiques par statut
                    </h3>
                    <div class="grid md:grid-cols-2 gap-8">
                        <div class="bg-gray-50 p-6 rounded-xl">
                            <h4 class="text-lg font-medium mb-4">Répartition par statut</h4>
                            <div class="flex items-center justify-center mb-4">
                                <div class="relative">
                                    <!-- Camembert statique pour l'exemple -->
                                    <div class="w-48 h-48 rounded-full border-8 border-green-500 flex items-center justify-center">
                                        <div class="text-center">
                                            <div class="text-2xl font-bold text-green-600">{{ $stats['actives'] }}</div>
                                            <div class="text-sm">Actives</div>
                                        </div>
                                    </div>
                                    <div class="absolute top-0 right-0 w-24 h-24 rounded-full border-8 border-orange-500"></div>
                                    <div class="absolute bottom-0 left-0 w-24 h-24 rounded-full border-8 border-gray-300"></div>
                                </div>
                            </div>
                            <div class="flex justify-center gap-4">
                                <div class="flex items-center">
                                    <div class="w-4 h-4 bg-green-500 rounded mr-2"></div>
                                    <span>Actives ({{ $stats['actives'] }})</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-4 h-4 bg-orange-500 rounded mr-2"></div>
                                    <span>Inactives ({{ $stats['inactives'] }})</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-6 rounded-xl">
                            <h4 class="text-lg font-medium mb-4">Évolution sur 12 mois</h4>
                            <div class="space-y-4">
                                <!-- Exemple de données - à remplacer par vos données réelles -->
                                <div class="flex items-center justify-between">
                                    <span class="font-medium">Janvier</span>
                                    <span class="text-gray-600">+12 nouvelles entreprises</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 40%"></div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <span class="font-medium">Février</span>
                                    <span class="text-gray-600">+8 nouvelles entreprises</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 27%"></div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <span class="font-medium">Mars</span>
                                    <span class="text-gray-600">+15 nouvelles entreprises</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 50%"></div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <span class="font-medium">Avril</span>
                                    <span class="text-gray-600">+10 nouvelles entreprises</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 33%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-16 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Nos solutions</h2>
                <div class="w-20 h-1 bg-primary-500 mx-auto mb-6"></div>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Découvrez comment notre plateforme peut simplifier la gestion de vos données géographiques.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-gray-50 p-8 rounded-xl card-hover border border-gray-100">
                    <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mb-6 mx-auto">
                        <i class="fas fa-database text-2xl text-primary-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-center mb-4">Gestion centralisée</h3>
                    <p class="text-gray-600 text-center">
                        Un tableau de bord complet pour gérer toutes vos entreprises et leurs coordonnées en un seul endroit.
                    </p>
                </div>

                <div class="bg-gray-50 p-8 rounded-xl card-hover border border-gray-100">
                    <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mb-6 mx-auto">
                        <i class="fas fa-map-marked-alt text-2xl text-primary-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-center mb-4">Visualisation cartographique</h3>
                    <p class="text-gray-600 text-center">
                        Une carte interactive pour visualiser la répartition géographique de vos entreprises avec des outils d'analyse.
                    </p>
                </div>

                <div class="bg-gray-50 p-8 rounded-xl card-hover border border-gray-100">
                    <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mb-6 mx-auto">
                        <i class="fas fa-file-export text-2xl text-primary-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-center mb-4">Export de données</h3>
                    <p class="text-gray-600 text-center">
                        Exportez vos données dans les formats CSV, Excel ou PDF pour les utiliser dans d'autres applications.
                    </p>
                </div>
            </div>

            <div class="mt-12 bg-gradient-to-r from-primary-500 to-primary-600 rounded-xl p-8 text-white">
                <div class="max-w-3xl mx-auto text-center">
                    <h3 class="text-2xl font-bold mb-4">Prêt à simplifier votre gestion d'entreprises ?</h3>
                    <p class="mb-6 opacity-90">
                        Commencez dès maintenant et découvrez comment notre solution peut transformer votre façon de travailler.
                    </p>
                    <a href="{{ url('admin/login') }}"
                       class="inline-block bg-white text-primary-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition shadow-lg">
                        Essayer gratuitement
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-16 bg-gray-50">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Contactez-nous</h2>
                <div class="w-20 h-1 bg-primary-500 mx-auto mb-6"></div>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Vous avez des questions ou besoin d'une démonstration ? Notre équipe est là pour vous aider.
                </p>
            </div>

            <div class="max-w-4xl mx-auto grid md:grid-cols-2 gap-8">
                <div class="bg-white p-8 rounded-xl shadow-sm">
                    <h3 class="text-xl font-semibold mb-6">Envoyez-nous un message</h3>
                    <form>
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom complet</label>
                            <input type="text" id="name" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" id="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                        <div class="mb-4">
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                            <textarea id="message" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"></textarea>
                        </div>
                        <button type="submit" class="w-full bg-primary-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-primary-600 transition">
                            Envoyer le message
                        </button>
                    </form>
                </div>

                <div>
                    <div class="bg-white p-8 rounded-xl shadow-sm mb-6">
                        <h3 class="text-xl font-semibold mb-4">Coordonnées</h3>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 h-10 w-10 bg-primary-100 rounded-full flex items-center justify-center mt-1">
                                    <i class="fas fa-map-marker-alt text-primary-600"></i>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-500">Adresse</h4>
                                    <p class="text-gray-700">123 Rue Example, Ville, Pays</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0 h-10 w-10 bg-primary-100 rounded-full flex items-center justify-center mt-1">
                                    <i class="fas fa-phone-alt text-primary-600"></i>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-500">Téléphone</h4>
                                    <p class="text-gray-700">+212 6 12 34 56 78</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0 h-10 w-10 bg-primary-100 rounded-full flex items-center justify-center mt-1">
                                    <i class="fas fa-envelope text-primary-600"></i>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-500">Email</h4>
                                    <p class="text-gray-700">contact@geolocalisation.com</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-8 rounded-xl shadow-sm">
                        <h3 class="text-xl font-semibold mb-4">Heures d'ouverture</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Lundi - Vendredi</span>
                                <span class="font-medium">9:00 - 18:00</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Samedi</span>
                                <span class="font-medium">10:00 - 16:00</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Dimanche</span>
                                <span class="font-medium">Fermé</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 py-12">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-white text-lg font-semibold mb-4 flex items-center">
                        <i class="fas fa-map-marked-alt mr-2 text-primary-400"></i> GeoLocalisation
                    </h3>
                    <p class="text-sm">
                        La solution complète pour la gestion et la visualisation géographique de vos entreprises.
                    </p>
                    <div class="mt-4 flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transition">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>

                <div>
                    <h3 class="text-white text-lg font-semibold mb-4">Liens rapides</h3>
                    <ul class="space-y-2">
                        <li><a href="#home" class="text-sm hover:text-white transition">Accueil</a></li>
                        <li><a href="#entreprises" class="text-sm hover:text-white transition">Entreprises</a></li>
                        <li><a href="#statistiques" class="text-sm hover:text-white transition">Statistiques</a></li>
                        <li><a href="#services" class="text-sm hover:text-white transition">Services</a></li>
                        <li><a href="#contact" class="text-sm hover:text-white transition">Contact</a></li>
                        <li><a href="{{ url('admin/login') }}" class="text-sm hover:text-white transition">Connexion</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-white text-lg font-semibold mb-4">Services</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-sm hover:text-white transition">Gestion d'entreprises</a></li>
                        <li><a href="#" class="text-sm hover:text-white transition">Cartographie interactive</a></li>
                        <li><a href="#" class="text-sm hover:text-white transition">Analyse géographique</a></li>
                        <li><a href="#" class="text-sm hover:text-white transition">Export de données</a></li>
                        <li><a href="#" class="text-sm hover:text-white transition">API d'intégration</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-white text-lg font-semibold mb-4">Newsletter</h3>
                    <p class="text-sm mb-4">
                        Abonnez-vous à notre newsletter pour recevoir les dernières mises à jour.
                    </p>
                    <form class="flex">
                        <input type="email" placeholder="Votre email" class="px-4 py-2 w-full rounded-l-lg focus:outline-none text-gray-800">
                        <button type="submit" class="bg-primary-500 text-white px-4 py-2 rounded-r-lg hover:bg-primary-600 transition">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-10 pt-6 flex flex-col md:flex-row justify-between items-center">
                <p class="text-sm">
                    &copy; {{ date('Y') }} GeoLocalisation. Tous droits réservés.
                </p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#" class="text-sm hover:text-white transition">Conditions d'utilisation</a>
                    <a href="#" class="text-sm hover:text-white transition">Politique de confidentialité</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button id="back-to-top" class="fixed bottom-6 right-6 bg-primary-500 text-white p-3 rounded-full shadow-lg opacity-0 invisible transition-all duration-300 hover:bg-primary-600">
        <i class="fas fa-arrow-up"></i>
    </button>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<!-- Leaflet MarkerCluster -->
<script src="https://unpkg.com/leaflet.markercluster/dist/leaflet.markercluster.js"></script>

<script>
    // Sauvegarder la position avant soumission du formulaire
    document.getElementById('filter-form').addEventListener('submit', function() {
        document.getElementById('scroll_position').value = window.scrollY;
    });

    // Après chargement de la page, restaurer la position
    document.addEventListener('DOMContentLoaded', function() {
        const scrollPosition = {{ request('scroll_position') ?? 0 }};
        if (scrollPosition > 0) {
            setTimeout(() => {
                window.scrollTo({
                    top: scrollPosition,
                    behavior: 'auto'
                });
            }, 50);
        }

        // Initialisation de la carte
        initMap();

        // Initialisation des statistiques
        initStats();
    });

    // Mobile menu toggle
    document.getElementById('mobile-menu-button').addEventListener('click', function() {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    });

    // Back to top button
    window.addEventListener('scroll', function() {
        const backToTopButton = document.getElementById('back-to-top');
        if (window.pageYOffset > 300) {
            backToTopButton.classList.remove('opacity-0', 'invisible');
            backToTopButton.classList.add('opacity-100', 'visible');
        } else {
            backToTopButton.classList.remove('opacity-100', 'visible');
            backToTopButton.classList.add('opacity-0', 'invisible');
        }
    });

    document.getElementById('back-to-top').addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();

            const targetId = this.getAttribute('href');
            if (targetId === '#') return;

            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                // Close mobile menu if open
                const mobileMenu = document.getElementById('mobile-menu');
                if (!mobileMenu.classList.contains('hidden')) {
                    mobileMenu.classList.add('hidden');
                }

                // Scroll to target
                window.scrollTo({
                    top: targetElement.offsetTop - 80,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Initialize map with clusters
    let map;
    let markersCluster;
    let allMarkers = [];

    function initMap() {
        // Initialisation de la carte centrée sur Maroc
        map = L.map('entreprise-map', {
            zoomControl: false
        }).setView([31.63, -8.0], 6);

        // Add zoom control with custom position
        L.control.zoom({
            position: 'topright'
        }).addTo(map);

        // Fond de carte OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Initialize marker cluster group
        markersCluster = L.markerClusterGroup({
            spiderfyOnMaxZoom: true,
            showCoverageOnHover: false,
            zoomToBoundsOnClick: true,
            maxClusterRadius: 60
        });

        // Custom cluster icon
        markersCluster.on('clusterclick', function (a) {
            a.layer.zoomToBounds();
        });

        // Add cluster group to map
        map.addLayer(markersCluster);

        // Utiliser toutes les entreprises filtrées pour la carte (non paginées)
        var entreprises = @json($entreprisesCarte);

        // Ajout des marqueurs et popups détaillés
        entreprises.forEach(function (entreprise) {
            if (entreprise.latitude && entreprise.longitude) {
                const customIcon = L.divIcon({
                    html: `<div class="bg-primary-500 text-white rounded-full p-2 flex items-center justify-center shadow-lg border-2 border-white">
                              <i class="fas fa-map-marker-alt"></i>
                           </div>`,
                    className: 'bg-transparent border-none',
                    iconSize: [40, 40]
                });

                const marker = L.marker([entreprise.latitude, entreprise.longitude], {
                    icon: customIcon
                }).bindPopup(
                    `<div class="map-tooltip">
                        <h4 class="font-bold text-lg mb-2 text-primary-600">${entreprise.nom_entreprise}</h4>
                        <div class="space-y-1">
                            <p class="flex items-center"><i class="fas fa-city mr-2 text-gray-500"></i> ${entreprise.ville ?? 'Inconnue'}</p>
                            <p class="flex items-center"><i class="fas fa-industry mr-2 text-gray-500"></i> ${entreprise.secteur ?? 'Inconnu'}</p>
                            <p class="flex items-center"><i class="fas fa-phone-alt mr-2 text-gray-500"></i> ${entreprise.tel ?? 'N/A'}</p>
                            <p class="flex items-center"><i class="fas fa-map-marker-alt mr-2 text-gray-500"></i> ${entreprise.adresse ?? 'N/A'}</p>
                        </div>
                    </div>`
                );

                allMarkers.push(marker);
                markersCluster.addLayer(marker);
            }
        });

        // Ajuster la vue pour englober tous les marqueurs
        if (allMarkers.length > 0) {
            const group = new L.featureGroup(allMarkers);
            map.fitBounds(group.getBounds().pad(0.2));
        }

        // Reset map view button
        document.getElementById('reset-map-view').addEventListener('click', function() {
            if (allMarkers.length > 0) {
                const group = new L.featureGroup(allMarkers);
                map.fitBounds(group.getBounds().pad(0.2));
            } else {
                map.setView([31.63, -8.0], 6);
            }
        });
    }

    // Function to show specific marker on map
    function showOnMap(lat, lng) {
        if (!map) return;

        map.setView([lat, lng], 15);

        // Find the marker and open its popup
        allMarkers.forEach(marker => {
            const markerLatLng = marker.getLatLng();
            if (markerLatLng.lat === lat && markerLatLng.lng === lng) {
                marker.openPopup();
            }
        });
    }

    // Gestion des statistiques
    function initStats() {
        // Toggle de la section des statistiques
        const statsToggle = document.getElementById('stats-toggle');
        const statsContainer = document.getElementById('stats-container');

        statsToggle.addEventListener('click', function() {
            statsContainer.classList.toggle('hidden');
            if (statsContainer.classList.contains('hidden')) {
                statsToggle.innerHTML = '<i class="fas fa-chart-bar mr-2"></i> Afficher les statistiques';
            } else {
                statsToggle.innerHTML = '<i class="fas fa-times mr-2"></i> Masquer les statistiques';
            }
        });

        // Navigation par onglets
        const tabBtns = document.querySelectorAll('.tab-btn');
        tabBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Retirer la classe active de tous les boutons
                tabBtns.forEach(b => b.classList.remove('active', 'bg-primary-100', 'text-primary-700'));
                tabBtns.forEach(b => b.classList.add('bg-gray-100', 'text-gray-700'));

                // Ajouter la classe active au bouton cliqué
                this.classList.remove('bg-gray-100', 'text-gray-700');
                this.classList.add('active', 'bg-primary-100', 'text-primary-700');

                // Masquer tous les contenus d'onglet
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.remove('active');
                });

                // Afficher le contenu correspondant
                const tabId = this.getAttribute('data-tab');
                document.getElementById(`${tabId}-content`).classList.add('active');
            });
        });

        // Animation des barres de progression
        document.querySelectorAll('.progress-fill').forEach(bar => {
            const width = bar.style.width;
            bar.style.width = '0';
            setTimeout(() => {
                bar.style.width = width;
            }, 300);
        });
    }
</script>

</body>
</html>
