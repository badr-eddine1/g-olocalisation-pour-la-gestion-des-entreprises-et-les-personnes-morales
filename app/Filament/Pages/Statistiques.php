<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Entreprise;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
class Statistiques extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-pie';

    protected static string $view = 'filament.pages.statistiques';
    protected static ?string $slug = 'statistiques';
    protected static ?string $navigationLabel = 'Statistiques';
    protected static ?string $title = 'Statistiques des Entreprises';
    protected static ?string $navigationGroup = 'Gestion';
    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        return (string) Entreprise::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }

    public static function getNavigationIconColor(): ?string
    {
        return 'text-purple-500';
    }
           public static function shouldRegisterNavigation(): bool
{
    return optional(Auth::user())->isGestionnaire() ?? false;
}
    // Public properties
    public $totalEntreprises;
    public $entreprisesActives;
    public $entreprisesInactives;
    public $tauxActivite;
    public $croissanceMensuelle;
    public $secteursPrincipaux;
    public $villesPrincipales;

    public function mount(): void
    {
        $this->loadStatistics();
    }

    public function loadStatistics(): void
    {
        $this->totalEntreprises = Entreprise::count();

        $this->entreprisesActives = Entreprise::where('en_activite', true)
            ->orWhere('en_activite', 'oui')
            ->orWhere('en_activite', 'Oui')
            ->orWhere('en_activite', 1)
            ->count();

        $this->entreprisesInactives = Entreprise::where('en_activite', false)
            ->orWhere('en_activite', 'non')
            ->orWhere('en_activite', 'Non')
            ->orWhere('en_activite', 0)
            ->count();

        $this->tauxActivite = $this->totalEntreprises > 0 ?
            round(($this->entreprisesActives / $this->totalEntreprises) * 100, 1) : 0;

        $this->croissanceMensuelle = $this->calculateMonthlyGrowth();
        $this->secteursPrincipaux = $this->getTopSecteurs();
        $this->villesPrincipales = $this->getTopVilles();
    }

    private function calculateMonthlyGrowth(): float
    {
        try {
            $currentMonth = Entreprise::whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->count();

            $previousMonth = Entreprise::whereMonth('created_at', Carbon::now()->subMonth()->month)
                ->whereYear('created_at', Carbon::now()->subMonth()->year)
                ->count();

            if ($previousMonth > 0) {
                return round((($currentMonth - $previousMonth) / $previousMonth) * 100, 1);
            }
            return $currentMonth > 0 ? 100.0 : 0.0;
        } catch (\Exception $e) {
            return 0.0;
        }
    }

    private function getTopSecteurs(): array
    {
        return Entreprise::whereNotNull('secteur')
            ->where('secteur', '!=', '')
            ->select('secteur', DB::raw('count(*) as total'))
            ->groupBy('secteur')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'nom' => $item->secteur,
                    'total' => $item->total,
                    'pourcentage' => $this->totalEntreprises > 0 ?
                        round(($item->total / $this->totalEntreprises) * 100, 1) : 0
                ];
            })
            ->toArray();
    }

    private function getTopVilles(): array
    {
        return Entreprise::whereNotNull('ville')
            ->where('ville', '!=', '')
            ->select('ville', DB::raw('count(*) as total'))
            ->groupBy('ville')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'nom' => $item->ville,
                    'total' => $item->total,
                    'pourcentage' => $this->totalEntreprises > 0 ?
                        round(($item->total / $this->totalEntreprises) * 100, 1) : 0
                ];
            })
            ->toArray();
    }

    public function debugStatutEntreprises(): array
    {
        return Entreprise::select('en_activite', DB::raw('count(*) as count'))
            ->groupBy('en_activite')
            ->get()
            ->toArray();
    }

    public function getStatistiquesAvancees(): array
    {
        return [
            'entreprisesAvecEmail' => Entreprise::whereNotNull('email')
                ->where('email', '!=', '')
                ->count(),
            'entreprisesAvecTelephone' => Entreprise::whereNotNull('tel')
                ->where('tel', '!=', '')
                ->count(),
            'entreprisesAvecSiteWeb' => Entreprise::whereNotNull('site_web')
                ->where('site_web', '!=', '')
                ->count(),
            'entreprisesAvecAdresse' => Entreprise::whereNotNull('adresse')
                ->where('adresse', '!=', '')
                ->count(),
            'entreprisesAvecCertifications' => Entreprise::whereNotNull('certifications')
                ->where('certifications', '!=', '')
                ->count(),
            'formeJuridique' => $this->getDistributionFormeJuridique(),
            'tailleEntreprise' => $this->getDistributionTaille(),
            'evolutionMensuelle' => $this->getMonthlyEvolution(),
            'repartitionType' => $this->getDistributionType(),
            'debugStatuts' => $this->debugStatutEntreprises(),
        ];
    }

    private function getDistributionFormeJuridique(): array
    {
        return Entreprise::whereNotNull('forme_juridique')
            ->where('forme_juridique', '!=', '')
            ->select('forme_juridique', DB::raw('count(*) as total'))
            ->groupBy('forme_juridique')
            ->orderBy('total', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'nom' => $item->forme_juridique,
                    'total' => $item->total,
                    'pourcentage' => $this->totalEntreprises > 0 ?
                        round(($item->total / $this->totalEntreprises) * 100, 1) : 0
                ];
            })
            ->toArray();
    }

    private function getDistributionTaille(): array
    {
        return Entreprise::whereNotNull('taille_entreprise')
            ->where('taille_entreprise', '!=', '')
            ->select('taille_entreprise', DB::raw('count(*) as total'))
            ->groupBy('taille_entreprise')
            ->orderBy('total', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'nom' => $item->taille_entreprise,
                    'total' => $item->total,
                    'pourcentage' => $this->totalEntreprises > 0 ?
                        round(($item->total / $this->totalEntreprises) * 100, 1) : 0
                ];
            })
            ->toArray();
    }

    private function getMonthlyEvolution(): array
    {
        try {
            $lastMonths = [];
            for ($i = 5; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $count = Entreprise::whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->count();

                $lastMonths[] = [
                    'mois' => $date->format('M Y'),
                    'total' => $count
                ];
            }
            return $lastMonths;
        } catch (\Exception $e) {
            return [];
        }
    }

    private function getDistributionType(): array
    {
        return Entreprise::whereNotNull('type')
            ->where('type', '!=', '')
            ->select('type', DB::raw('count(*) as total'))
            ->groupBy('type')
            ->orderBy('total', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'nom' => $item->type,
                    'total' => $item->total,
                    'pourcentage' => $this->totalEntreprises > 0 ?
                        round(($item->total / $this->totalEntreprises) * 100, 1) : 0
                ];
            })
            ->toArray();
    }

    public function getStatistiquesContact(): array
    {
        return [
            'avecEmail' => Entreprise::whereNotNull('email')->where('email', '!=', '')->count(),
            'avecTel' => Entreprise::whereNotNull('tel')->where('tel', '!=', '')->count(),
            'avecFax' => Entreprise::whereNotNull('fax')->where('fax', '!=', '')->count(),
            'avecSiteWeb' => Entreprise::whereNotNull('site_web')->where('site_web', '!=', '')->count(),
            'avecContact' => Entreprise::whereNotNull('contact')->where('contact', '!=', '')->count(),
        ];
    }

    public function getStatistiquesLocalisation(): array
    {
        return [
            'avecAdresse' => Entreprise::whereNotNull('adresse')->where('adresse', '!=', '')->count(),
            'avecCoordonnees' => Entreprise::whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->where('latitude', '!=', '')
                ->where('longitude', '!=', '')
                ->count(),
            'villesUniques' => Entreprise::whereNotNull('ville')
                ->where('ville', '!=', '')
                ->distinct('ville')
                ->count('ville'),
        ];
    }

    public function getStatistiquesLegales(): array
    {
        return [
            'avecRC' => Entreprise::whereNotNull('rc')->where('rc', '!=', '')->count(),
            'avecICE' => Entreprise::whereNotNull('code_ice')->where('code_ice', '!=', '')->count(),
            'avecCNSS' => Entreprise::whereNotNull('cnss')->where('cnss', '!=', '')->count(),
            'avecIF' => Entreprise::whereNotNull('if')->where('if', '!=', '')->count(),
            'avecPatente' => Entreprise::whereNotNull('patente')->where('patente', '!=', '')->count(),
        ];
    }

    public function getAperçuCompletude(): array
    {
        $total = $this->totalEntreprises;
        if ($total === 0) return [];

        return [
            'informationsBase' => [
                'nom' => round((Entreprise::whereNotNull('nom_entreprise')->where('nom_entreprise', '!=', '')->count() / $total) * 100, 1),
                'secteur' => round((Entreprise::whereNotNull('secteur')->where('secteur', '!=', '')->count() / $total) * 100, 1),
                'ville' => round((Entreprise::whereNotNull('ville')->where('ville', '!=', '')->count() / $total) * 100, 1),
                'taille' => round((Entreprise::whereNotNull('taille_entreprise')->where('taille_entreprise', '!=', '')->count() / $total) * 100, 1),
            ],
            'contact' => [
                'email' => round((Entreprise::whereNotNull('email')->where('email', '!=', '')->count() / $total) * 100, 1),
                'telephone' => round((Entreprise::whereNotNull('tel')->where('tel', '!=', '')->count() / $total) * 100, 1),
                'siteWeb' => round((Entreprise::whereNotNull('site_web')->where('site_web', '!=', '')->count() / $total) * 100, 1),
            ],
            'legal' => [
                'ice' => round((Entreprise::whereNotNull('code_ice')->where('code_ice', '!=', '')->count() / $total) * 100, 1),
                'rc' => round((Entreprise::whereNotNull('rc')->where('rc', '!=', '')->count() / $total) * 100, 1),
                'patente' => round((Entreprise::whereNotNull('patente')->where('patente', '!=', '')->count() / $total) * 100, 1),
            ]
        ];
    }

    public function rafraichirStatistiques(): void
    {
        $this->loadStatistics();
        $this->dispatch('statistiquesUpdated');
    }

    public function getCouleurStatut(bool $estActif): string
    {
        return $estActif ? 'text-green-600 bg-green-100 dark:text-green-400 dark:bg-green-900/20'
                        : 'text-pink-600 bg-pink-100 dark:text-pink-400 dark:bg-pink-900/20';
    }

    public function getIconeStatut(bool $estActif): string
    {
        return $estActif ? 'heroicon-m-check-circle' : 'heroicon-m-x-circle';
    }

    public function getMetricColorClasses(): array
    {
        return [
            'total' => 'text-purple-600 dark:text-purple-400',
            'active' => 'text-green-600 dark:text-green-400',
            'inactive' => 'text-pink-600 dark:text-pink-400',
            'growth' => 'text-blue-600 dark:text-blue-400',
            'sectors' => 'text-yellow-600 dark:text-yellow-400',
            'cities' => 'text-cyan-600 dark:text-cyan-400'
        ];
    }

    public function getGradientClasses(): array
    {
        return [
            'total' => 'bg-gradient-to-br from-purple-500 to-pink-500',
            'active' => 'bg-gradient-to-br from-green-500 to-teal-500',
            'inactive' => 'bg-gradient-to-br from-pink-500 to-red-500',
            'growth' => 'bg-gradient-to-br from-blue-500 to-indigo-500',
            'sectors' => 'bg-gradient-to-br from-yellow-500 to-amber-500',
            'cities' => 'bg-gradient-to-br from-cyan-500 to-blue-500'
        ];
    }

    public function getViewData(): array
    {
        return [
            'totalEntreprises' => $this->totalEntreprises,
            'entreprisesActives' => $this->entreprisesActives,
            'entreprisesInactives' => $this->entreprisesInactives,
            'tauxActivite' => $this->tauxActivite,
            'croissanceMensuelle' => $this->croissanceMensuelle,
            'secteursPrincipaux' => $this->secteursPrincipaux,
            'villesPrincipales' => $this->villesPrincipales,
            'statistiquesAvancees' => $this->getStatistiquesAvancees(),
            'statistiquesContact' => $this->getStatistiquesContact(),
            'statistiquesLocalisation' => $this->getStatistiquesLocalisation(),
            'statistiquesLegales' => $this->getStatistiquesLegales(),
            'aperçuCompletude' => $this->getAperçuCompletude(),
            'metricColors' => $this->getMetricColorClasses(),
            'gradientClasses' => $this->getGradientClasses()
        ];
    }
}
