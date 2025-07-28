<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class UserOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Utilisateurs totaux', User::count())
                ->description('Nombre total')
                ->color('primary'),

            Stat::make('Nouveaux ce mois', User::whereMonth('created_at', now()->month)->count())
                ->description('Inscriptions ce mois-ci')
                ->color('success'),

            Stat::make('Aujourd’hui', User::whereDate('created_at', now()->toDateString())->count())
                ->description('Inscriptions aujourd’hui')
                ->color('info'),
        ];
    }
}
