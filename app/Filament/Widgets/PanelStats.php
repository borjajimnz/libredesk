<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PanelStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Users', '192.1k'),
            Stat::make('Places', '21%'),
            Stat::make('Desktops', '3:12'),
        ];
    }
}
