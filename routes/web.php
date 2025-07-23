<?php

use App\Models\Entreprise;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

Route::get('/', function (Request $request) {
    $query = Entreprise::query();

    // Recherche (nom)
    if ($request->filled('search')) {
        $query->where('nom_entreprise', 'like', '%' . $request->search . '%');
    }

    // Ville
    if ($request->filled('ville')) {
        $query->where('ville', $request->ville);
    }

    // Secteur
    if ($request->filled('secteur')) {
        $query->where('secteur', $request->secteur);
    }

    // Forme juridique
    if ($request->filled('forme_juridique')) {
        $query->where('forme_juridique', $request->forme_juridique);
    }

    // Type
    if ($request->filled('type')) {
        $query->where('type', $request->type);
    }

    // Taille entreprise
    if ($request->filled('taille')) {
        $query->where('taille_entreprise', $request->taille);
    }

    // État (en_activite)
    if ($request->filled('etat')) {
        $query->where('en_activite', $request->etat);
    }

    // Clone pour la carte AVANT pagination
    $queryForCarte = clone $query;

    // Pagination pour le tableau
    $entreprises = $query->paginate(10)->withQueryString();

    // Toutes les entreprises filtrées pour la carte
    $entreprisesCarte = $queryForCarte->get();

    // Données distinctes pour alimenter les filtres
    $villes = Entreprise::whereNotNull('ville')->where('ville', '!=', '')
        ->distinct()->orderBy('ville')->pluck('ville');

    $secteurs = Entreprise::whereNotNull('secteur')->where('secteur', '!=', '')
        ->distinct()->orderBy('secteur')->pluck('secteur');

    $formesJuridiques = Entreprise::whereNotNull('forme_juridique')->where('forme_juridique', '!=', '')
        ->distinct()->orderBy('forme_juridique')->pluck('forme_juridique');

    $types = Entreprise::whereNotNull('type')->where('type', '!=', '')
        ->distinct()->orderBy('type')->pluck('type');

    $tailles = Entreprise::whereNotNull('taille_entreprise')->where('taille_entreprise', '!=', '')
        ->distinct()->orderBy('taille_entreprise')->pluck('taille_entreprise');

    // Calcul des statistiques
    $stats = [
        'total' => Entreprise::count(),
        'actives' => Entreprise::where('en_activite', 'oui')->count(),
        'inactives' => Entreprise::where('en_activite', 'non')->count(),
        'par_ville' => Entreprise::select('ville', DB::raw('count(*) as total'))
            ->groupBy('ville')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get(),
        'par_secteur' => Entreprise::select('secteur', DB::raw('count(*) as total'))
            ->groupBy('secteur')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get()
    ];

    return view('welcome', compact(
        'entreprises',
        'entreprisesCarte',
        'villes',
        'secteurs',
        'formesJuridiques',
        'types',
        'tailles',
        'stats'
    ));
});
