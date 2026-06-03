<?php

namespace App\Filament\Pegawai\Resources;

use App\Filament\Pegawai\Resources\BookingResource\Pages;
use App\Models\Booking;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Daftar Booking';
    protected static ?string $modelLabel = 'Booking';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('user_id')
                ->relationship('user', 'name')->required()->label('Pelanggan'),
            Forms\Components\Select::make('vehicle_id')
                ->relationship('vehicle', 'plate_number')->required()->label('Kendaraan'),
            Forms\Components\Select::make('branch_id')
                ->relationship('branch', 'name')->required()->label('Cabang'),
            Forms\Components\Select::make('service_package_id')
                ->relationship('servicePackage', 'name')->nullable()->label('Paket Servis'),
            Forms\Components\DatePicker::make('date')->required()->label('Tanggal'),
            Forms\Components\TimePicker::make('time')->required()->label('Waktu'),
            Forms\Components\Select::make('status')
                ->options([
                    'menunggu' => 'Menunggu',
                    'disetujui' => 'Disetujui',
                    'dalam_pengerjaan' => 'Dalam Pengerjaan',
                    'selesai' => 'Selesai',
                    'dibatalkan' => 'Dibatalkan',
                ])->required()->label('Status'),
            Forms\Components\TextInput::make('queue_number')->label('No. Antrian'),
            Forms\Components\Textarea::make('notes')->columnSpanFull()->label('Catatan'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('date', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('queue_number')
                    ->label('No. Antrian')
                    ->badge()
                    ->color('warning')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('vehicle.plate_number')
                    ->label('Kendaraan')
                    ->description(fn ($record) => $record->vehicle?->brand . ' ' . $record->vehicle?->model)
                    ->searchable(),
                Tables\Columns\TextColumn::make('branch.name')
                    ->label('Cabang')
                    ->sortable(),
                Tables\Columns\TextColumn::make('servicePackage.name')
                    ->label('Paket Servis')
                    ->default('-'),
                Tables\Columns\TextColumn::make('date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('time')
                    ->label('Waktu'),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'gray' => 'menunggu',
                        'success' => 'disetujui',
                        'warning' => 'dalam_pengerjaan',
                        'primary' => 'selesai',
                        'danger' => 'dibatalkan',
                    ])
                    ->formatStateUsing(fn ($state) => match($state) {
                        'menunggu' => 'Menunggu',
                        'disetujui' => 'Disetujui',
                        'dalam_pengerjaan' => 'Dalam Pengerjaan',
                        'selesai' => 'Selesai',
                        'dibatalkan' => 'Dibatalkan',
                        default => $state,
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'menunggu' => 'Menunggu',
                        'disetujui' => 'Disetujui',
                        'dalam_pengerjaan' => 'Dalam Pengerjaan',
                        'selesai' => 'Selesai',
                        'dibatalkan' => 'Dibatalkan',
                    ])->label('Filter Status'),
                Tables\Filters\SelectFilter::make('branch_id')
                    ->relationship('branch', 'name')->label('Filter Cabang'),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('Setujui & Buat Servis')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'menunggu')
                    ->form([
                        Forms\Components\Select::make('technician_id')
                            ->label('Pilih Teknisi / Mekanik')
                            ->placeholder('Pilih teknisi yang akan mengerjakan...')
                            ->options(fn () => \App\Models\User::whereIn('role', ['pegawai', 'mekanik'])->pluck('name', 'id'))
                            ->required()
                            ->searchable()
                            ->preload(),
                    ])
                    ->requiresConfirmation()
                    ->modalHeading('Setujui Booking?')
                    ->modalDescription('Tindakan ini akan mengubah status menjadi Disetujui dan membuat record Servis baru secara otomatis.')
                    ->action(function (Booking $record, array $data) {
                        // Cek apakah Service sudah ada
                        if (!$record->service()->exists()) {
                            Service::create([
                                'booking_id' => $record->id,
                                'vehicle_id' => $record->vehicle_id,
                                'technician_id' => $data['technician_id'],
                                'current_km' => $record->vehicle?->init_km ?? 0,
                                'total_cost' => 0,
                            ]);
                        } else {
                            $record->service->update([
                                'technician_id' => $data['technician_id'],
                            ]);
                        }
                        $record->update(['status' => 'disetujui']);
                        Notification::make()->title('Booking disetujui! Record servis berhasil dibuat.')->success()->send();
                    }),
                Tables\Actions\Action::make('reject')
                    ->label('Batalkan')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record) => in_array($record->status, ['menunggu', 'disetujui']))
                    ->requiresConfirmation()
                    ->action(function (Booking $record) {
                        $record->update(['status' => 'dibatalkan']);
                        Notification::make()->title('Booking telah dibatalkan.')->warning()->send();
                    }),
                Tables\Actions\EditAction::make()->icon('heroicon-o-pencil'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}

