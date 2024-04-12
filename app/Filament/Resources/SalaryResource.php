<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\RelationManagers\SalariesRelationManager;
use App\Filament\Resources\SalaryResource\Pages;
use App\Models\Salary;
use App\Traits\DefaultCounterNavigationBadge;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;

class SalaryResource extends Resource
{
    use DefaultCounterNavigationBadge;

    protected static ?string $model = Salary::class;

    protected static ?string $navigationGroup = 'Employee Management';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('employee_id')
                    ->relationship('employee', 'name')
                    ->live()
                    ->preload()
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->prefix('Rp')
                    ->mask(RawJs::make('$money($input)')),
                Forms\Components\DatePicker::make('effective_date')
                    ->native(false)
                    ->default(now()->addMonth(6)->startOfMonth())
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(self::getTableColumns())
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('employee.name')
                ->hiddenOn(SalariesRelationManager::class)
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('amount')
                ->numeric()
                ->prefix('Rp ')
                ->sortable(),
            Tables\Columns\TextColumn::make('effective_date')
                ->date()
                ->sortable(),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSalaries::route('/'),
        ];
    }
}
