<?php

namespace App\Filament\Resources\LeaveRequestResource\Widgets;

use App\Enums\LeaveRequestStatus;
use App\Models\LeaveRequest;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LeaveRequestStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Pending', LeaveRequest::where('status', LeaveRequestStatus::PENDING)->count())
                ->description('The number of pending leave request')
                ->icon('heroicon-m-exclamation-circle')
                ->chart([1,2,3,4,5,6,7])
                ->color('warning'),
            Stat::make('Approved', LeaveRequest::where('status', LeaveRequestStatus::APPROVED)->count())
                ->description('The number of approved leave request')
                ->icon('heroicon-m-check-circle')
                ->chart([1,2,3,4,5,6,7])
                ->color('success'),
            Stat::make('Rejected', LeaveRequest::where('status', LeaveRequestStatus::REJECTED)->count())
                ->description('The number of rejected leave request')
                ->icon('heroicon-m-minus-circle')
                ->chart([1,2,3,4,5,6,7])
                ->color('danger'),
        ];
    }
}
