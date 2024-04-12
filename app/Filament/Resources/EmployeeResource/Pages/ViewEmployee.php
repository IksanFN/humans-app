<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;
use App\Filament\Resources\EmployeeResource;
use Filament\Actions\ActionGroup;

class ViewEmployee extends ViewRecord
{
    protected static string $resource = EmployeeResource::class;

    public function getHeaderActions(): array
    {
        return [
            ActionGroup::make([
                EditAction::make('Edit'),
                DeleteAction::make('Edit'),
            ])
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return $this->record->name;
    }
}
