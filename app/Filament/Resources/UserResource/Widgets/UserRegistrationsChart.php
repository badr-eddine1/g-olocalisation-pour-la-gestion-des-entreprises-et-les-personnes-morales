<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class UserRegistrationsChart extends ChartWidget
{
    protected static ?string $heading = 'Inscriptions des utilisateurs par mois';

    protected function getData(): array
    {
        // Regrouper les utilisateurs par mois d'inscription sur les 6 derniers mois
        $data = User::query()
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $months = [];
        $counts = [];

        foreach ($data as $entry) {
            $months[] = Carbon::create()->month($entry->month)->locale('fr_FR')->isoFormat('MMMM'); // Mois en français
            $counts[] = $entry->count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Utilisateurs inscrits',
                    'data' => $counts,
                    'backgroundColor' => '#3B82F6', // Bleu
                ],
            ],
            'labels' => $months,
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // 'line', 'pie', etc.
    }
}
