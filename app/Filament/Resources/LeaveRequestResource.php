<?php

namespace App\Filament\Resources;

use App\Enums\LeaveRequestStatus;
use App\Enums\LeaveRequestType;
use App\Filament\Resources\EmployeeResource\RelationManagers\LeaveRequestsRelationManager;
use App\Filament\Resources\LeaveRequestResource\Pages;
use App\Filament\Resources\LeaveRequestResource\Widgets\LeaveRequestStats;
use App\Models\LeaveRequest;
use App\Traits\DefaultCounterNavigationBadge;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class LeaveRequestResource extends Resource
{
    use DefaultCounterNavigationBadge;
    protected static ?string $model = LeaveRequest::class;

    protected static ?string $navigationGroup = 'Employee Management';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationIcon = 'heroicon-o-inbox-arrow-down';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(self::getFormFields());
    }

    public static function getFormFields(): array
    {
        return [
            Forms\Components\Select::make('employee_id')
                ->relationship('employee', 'name')
                ->prefixIcon('heroicon-o-user')
                ->label('Employee Name')
                ->live()
                ->preload()
                ->searchable()
                ->required()
                ->columnSpanFull()
                ->hiddenOn(LeaveRequestsRelationManager::class),

            Forms\Components\Group::make([
                Forms\Components\DatePicker::make('start_date')
                    ->native(false)
                    ->required(),
                Forms\Components\DatePicker::make('end_date')
                    ->native(false)
                    ->required(),
                Forms\Components\Select::make('type')
                    ->enum(LeaveRequestType::class)
                    ->required()
                    ->options(LeaveRequestType::class),

                Forms\Components\Select::make('status')
                    ->required()
                    ->enum(LeaveRequestStatus::class)
                    ->options(LeaveRequestStatus::class),
            ])->columns(2)->columnSpan(2),

            Forms\Components\MarkdownEditor::make('reason')
                ->columnSpanFull(),
        ];
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
                Tables\Actions\Action::make('Approve')
                    ->requiresConfirmation()
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->visible(fn (LeaveRequest $record) => $record->status == LeaveRequestStatus::PENDING)
                    ->action(function (LeaveRequest $record) {
                        $record->approve();
                    })->after(function () {
                        Notification::make()
                            ->success()
                            ->title('Approved!')
                            ->body('Leave Request has been approved!')
                            ->send();
                    }),
                Tables\Actions\Action::make('Reject')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->icon('heroicon-m-minus-circle')
                    ->visible(fn (LeaveRequest $record) => $record->status == LeaveRequestStatus::PENDING)
                    ->action(function (LeaveRequest $record) {
                        $record->reject();
                    })->after(function () {
                        Notification::make()
                            ->danger()
                            ->title('Rejected!')
                            ->body('Leave Request has been rejected!')
                            ->send();
                    })
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    BulkAction::make('Approve Selected')
                        ->color('success')
                        ->requiresConfirmation()
                        ->icon('heroicon-m-check-circle')
                        ->action(fn (Collection $record) => $record->each->approve())
                        ->after(function () {
                            Notification::make()
                                ->success()
                                ->title('Rejected!')
                                ->body('Leave Request has been approved!')
                                ->send();
                        }),
                    BulkAction::make('Reject Selected')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->icon('heroicon-m-minus-circle')
                        ->action(fn (Collection $record) => $record->each->reject())
                        ->after(function () {
                            Notification::make()
                                ->warning()
                                ->title('Rejected!')
                                ->body('Leave Request has been rejected!')
                                ->send();
                        })
                ]),
            ]);
    }

    public static function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('employee.name')
                ->hiddenOn(LeaveRequestsRelationManager::class)
                ->sortable(),
            Tables\Columns\TextColumn::make('start_date')
                ->date()
                ->sortable(),
            Tables\Columns\TextColumn::make('end_date')
                ->date()
                ->sortable(),
            Tables\Columns\TextColumn::make('type')
                ->searchable(),
            Tables\Columns\TextColumn::make('status')
                ->badge()
                ->icon(fn ($state) => $state->getIcon())
                ->color(fn ($state) => $state->getColor())
                ->searchable(),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeaveRequests::route('/'),
            // 'create' => Pages\CreateLeaveRequest::route('/create'),
            // 'edit' => Pages\EditLeaveRequest::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            LeaveRequestStats::class,
        ];
    }
    
}
