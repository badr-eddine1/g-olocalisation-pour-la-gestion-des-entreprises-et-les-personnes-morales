<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Filament\Widgets\ChartWidget;

class UserRegistrationsChart extends ChartWidget
{
    protected static ?string $heading = 'Inscriptions des utilisateurs par mois';

    protected function getData(): array
    {
        // Définir la période des 6 derniers mois
        $start = now()->subMonths(5)->startOfMonth();
        $end = now()->endOfMonth();

        // Récupérer les données groupées par mois avec année
        $data = User::query()
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Créer un tableau complet des 6 derniers mois
        $period = CarbonPeriod::create($start, '1 month', $end);
        $counts = [];
        $labels = [];

        foreach ($period as $date) {
            $key = $date->format('Y-m');
            $counts[$key] = 0;
            $labels[] = $date->locale('fr_FR')->isoFormat('MMMM YYYY');
        }

        // Remplir les données existantes
        foreach ($data as $entry) {
            $key = Carbon::create($entry->year, $entry->month)->format('Y-m');
            $counts[$key] = $entry->count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Utilisateurs inscrits',
                    'data' => array_values($counts),
                    'backgroundColor' => '#3B82F6',
                    'borderColor' => '#2563EB',
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    // Méthode rendue publique pour respecter l'interface
    public function getDescription(): ?string
    {
        return 'Évolution des inscriptions sur les 6 derniers mois';
    }
}
