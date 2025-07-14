<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
class VisiteurDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.visiteur-dashboard';

    public static function shouldRegisterNavigation(): bool
{
    return optional(Auth::user())->isVisiteur() ?? false;
}

}
