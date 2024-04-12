<?php

namespace App\Filament\Widgets;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class Stats extends BaseWidget
{
    protected function getStats(): array
    {
        // return Employee::count();
        return [
            Stat::make('Employee', Employee::count())
                ->icon('heroicon-o-users')
                ->color('primary')
                ->description('Total number of employee'),
            Stat::make('Department', Department::count())
                ->icon('heroicon-o-rocket-launch')
                ->color('success')
                ->description('Total number of department'),
            Stat::make('Position', Position::count())
                ->icon('heroicon-o-user-group')
                ->color('warning')
                ->description('Total number of position'),
        ];
    }
}
