<?php

namespace App\Filament\Widgets;

use App\Enums\EmployeeStatus;
use App\Models\Employee;
use Filament\Widgets\ChartWidget;

class EmployeeBarStatus extends ChartWidget
{
    protected static ?string $heading = 'Employee Status';

    protected int | string | array $columnSpan = 'full';

    // protected static ?string $maxHeight = '300px';

    protected static string $color = 'success';

    protected static ?int $sort = 2;

    protected function getData(): array
    {

        $active = Employee::where('status', EmployeeStatus::ACTIVE)->count();
        $inactive = Employee::where('status', EmployeeStatus::INACTIVE)->count();
        $onLeave = Employee::where('status', EmployeeStatus::ON_LEAVE)->count();

        return [
            'labels' => ['Active', 'In Active', 'On Leave'],
            'datasets' => [
                [
                    'label' => 'Employee Status',
                    'data' => ["{$active}", "{$inactive}", "{$onLeave}"],

                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
