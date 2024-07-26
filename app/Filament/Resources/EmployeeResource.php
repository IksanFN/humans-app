<?php

namespace App\Filament\Resources;

use App\Enums\EmployeeStatus;
use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers\LeaveRequestsRelationManager;
use App\Filament\Resources\EmployeeResource\RelationManagers\SalariesRelationManager;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EmployeeResource extends Resource
{
    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $model = Employee::class;

    protected static ?string $navigationGroup = 'Employee Management';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('department_id')
                    ->relationship('department', 'name')
                    ->label('Department Name')
                    ->searchable()
                    ->live()
                    ->preload()
                    ->editOptionForm(fn () => DepartmentResource::getFormField())
                    ->options(Department::query()->whereActive(true)->pluck('name', 'id'))
                    ->required(),
                Forms\Components\Select::make('position_id')
                    ->label('Position Name')
                    ->searchable()
                    ->live()
                    ->preload()
                    ->createOptionForm(fn () => PositionResource::getFormField())
                    ->relationship('position', 'name')
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->prefixIcon('heroicon-o-envelope'),
                Forms\Components\DatePicker::make('joined')
                    ->native(false)
                    ->required(),
                Forms\Components\Select::make('status')
                    ->enum(EmployeeStatus::class)
                    ->options(EmployeeStatus::class)
                    ->required(),
            ]);
    }

    // Eager Loading
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('position:id,name');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('joined', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->description(fn (Employee $record) => $record->email)
                    ->searchable(),
                Tables\Columns\TextColumn::make('department.name')
                    ->description(fn ($record) => 'Position: ' . $record->position->name)
                    ->sortable(),
                Tables\Columns\TextColumn::make('joined')
                    ->date()
                    ->formatStateUsing(fn ($state) => $state->format('d F Y'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->icon(fn ($state) => $state->getIcon())
                    ->color(fn ($state) => $state->getColor()),
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
                Tables\Filters\SelectFilter::make('status')
                    ->options(EmployeeStatus::class),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])->icon('heroicon-o-bars-3'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            SalariesRelationManager::class,
            LeaveRequestsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
            // 'view' => Pages\ViewEmployee::route('/{record}'),
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make()->schema([
                TextEntry::make('name'),
                TextEntry::make('email'),
                TextEntry::make('department.name'),
                TextEntry::make('position.name'),
                TextEntry::make('joined')
                    ->date()
                    ->formatStateUsing(fn ($state) => $state->format('d F Y')),
                TextEntry::make('status')
                    ->badge()
                    ->color(fn ($state) => $state->getColor())
                    ->icon(fn ($state) => $state->getIcon()),
            ])->columns(2)->columnSpanFull()

        ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::$model::where('status', EmployeeStatus::ACTIVE)->count();
    }
}
