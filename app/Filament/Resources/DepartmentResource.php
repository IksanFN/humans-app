<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DepartmentResource\Pages;
use App\Models\Department;
use App\Traits\DefaultCounterNavigationBadge;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DepartmentResource extends Resource
{
    use DefaultCounterNavigationBadge;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $model = Department::class;

    protected static ?string $navigationGroup = 'Resources Management';

    protected static ?string $navigationIcon = 'heroicon-o-rocket-launch';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(self::getFormField());
    }

    public static function getFormField(): array
    {
        return [
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
            Forms\Components\Textarea::make('description')
                ->columnSpanFull(),
            Forms\Components\Toggle::make('active'),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('active'),
                // Tables\Columns\TextColumn::make('active')
                //     ->formatStateUsing(fn ($state) => $state == 1 ? 'Active' : 'Inactive')
                //     ->badge()
                //     ->color(fn ($state) => $state == '1' ? 'primary' : 'danger'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // TernaryFilter::make('active')
                // ->boolean()
                Filter::make('active')
                    ->toggle()
                    ->modifyQueryUsing(fn (Builder $query) => $query->where('active', true)),
                Filter::make('inactive')
                    ->toggle()
                    ->modifyQueryUsing(fn (Builder $query) => $query->where('active', false)),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageDepartments::route('/'),
        ];
    }
}
