<?php

namespace App\Filament\Pegawai\Resources;

use App\Filament\Pegawai\Resources\ServiceReminderResource\Pages;
use App\Filament\Pegawai\Resources\ServiceReminderResource\RelationManagers;
use App\Models\ServiceReminder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ServiceReminderResource extends Resource
{
    protected static ?string $model = ServiceReminder::class;

    protected static ?string $navigationIcon = 'heroicon-o-bell';

    protected static ?string $navigationLabel = 'Smart Reminders';

    protected static ?string $modelLabel = 'Smart Reminder';

    protected static ?string $pluralModelLabel = 'Smart Reminders';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('vehicle_id')
                    ->relationship('vehicle', 'plate_number')
                    ->required(),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\Select::make('reminder_type')
                    ->options([
                        'km' => 'Batas Kilometer',
                        'date' => 'Tanggal Servis',
                    ])
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'sent' => 'Sent',
                        'failed' => 'Failed',
                    ])
                    ->required(),
                Forms\Components\DateTimePicker::make('sent_at'),
                Forms\Components\Textarea::make('message')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('vehicle.plate_number')
                    ->label('Plat Nomor')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('reminder_type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'km' => 'warning',
                        'date' => 'info',
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'sent' => 'success',
                        'pending' => 'gray',
                        'failed' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('sent_at')
                    ->label('Dikirim Pada')
                    ->dateTime()
                    ->sortable(),
            ])
            ->headerActions([
                Tables\Actions\Action::make('triggerReminders')
                    ->label('Kirim Pengingat Otomatis')
                    ->color('primary')
                    ->icon('heroicon-o-paper-airplane')
                    ->action(function () {
                        \Illuminate\Support\Facades\Artisan::call('app:send-service-reminders');
                        \Filament\Notifications\Notification::make()
                            ->title('Pengingat Servis Diproses')
                            ->body('Sistem telah memeriksa kendaraan dan mengirim notifikasi dummy.')
                            ->success()
                            ->send();
                    })
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('reminder_type')
                    ->options([
                        'km' => 'Batas Kilometer',
                        'date' => 'Tanggal Servis',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'sent' => 'Sent',
                        'failed' => 'Failed',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServiceReminders::route('/'),
            'create' => Pages\CreateServiceReminder::route('/create'),
            'view' => Pages\ViewServiceReminder::route('/{record}'),
            'edit' => Pages\EditServiceReminder::route('/{record}/edit'),
        ];
    }
}
