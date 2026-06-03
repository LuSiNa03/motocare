<?php

namespace App\Filament\Pegawai\Resources;

use App\Filament\Pegawai\Resources\ServiceResource\Pages;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static ?string $navigationLabel = 'Pengerjaan Servis';
    protected static ?string $modelLabel = 'Pengerjaan Servis';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Servis')
                    ->schema([
                        Forms\Components\Placeholder::make('booking_info')
                            ->label('Booking')
                            ->content(fn ($record) => $record?->booking ? ('#' . $record->booking->queue_number . ' - ' . ($record->booking->servicePackage?->name ?? 'Servis Reguler') . ' (' . \Carbon\Carbon::parse($record->booking->date)->format('d M Y') . ')') : '-'),
                        Forms\Components\Placeholder::make('vehicle_info')
                            ->label('Kendaraan')
                            ->content(fn ($record) => $record?->vehicle ? ($record->vehicle->brand . ' ' . $record->vehicle->model . ' [' . $record->vehicle->plate_number . ']') : '-'),
                        Forms\Components\Select::make('technician_id')
                            ->label('Teknisi / Mekanik')
                            ->relationship('technician', 'name', fn ($query) => $query->whereIn('role', ['pegawai', 'mekanik']))
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('current_km')
                            ->label('KM Kendaraan Saat Ini')
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('health_score')
                            ->label('Health Score Kendaraan (0-100)')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->nullable(),
                        Forms\Components\Placeholder::make('total_cost_display')
                            ->label('Total Biaya')
                            ->content(fn ($record) => 'Rp ' . number_format($record?->total_cost ?? 0, 0, ',', '.')),
                    ])->columns(2),

                Forms\Components\Section::make('Detail Jasa & Suku Cadang')
                    ->schema([
                        Forms\Components\Repeater::make('details')
                            ->relationship('details')
                            ->schema([
                                Forms\Components\Select::make('type')
                                    ->label('Tipe')
                                    ->options([
                                        'jasa' => 'Jasa',
                                        'sparepart' => 'Suku Cadang (Sparepart)',
                                    ])
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(fn ($state, callable $set) => $state === 'jasa' ? $set('item_id', null) : null),
                                Forms\Components\Select::make('item_id')
                                    ->label('Pilih Sparepart')
                                    ->options(function (Forms\Get $get) {
                                        // Dapatkan branch_id dari booking
                                        $bookingId = $get('../../booking_id');
                                        $branchId = null;
                                        if ($bookingId) {
                                            $booking = \App\Models\Booking::find($bookingId);
                                            if ($booking) {
                                                $branchId = $booking->branch_id;
                                            }
                                        }
                                        $query = \App\Models\Sparepart::query();
                                        if ($branchId) {
                                            $query->where('branch_id', $branchId);
                                        }
                                        return $query->where('stock', '>', 0)->pluck('name', 'id');
                                    })
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if ($state) {
                                            $sparepart = \App\Models\Sparepart::find($state);
                                            if ($sparepart) {
                                                $set('item_name', $sparepart->name);
                                                $set('price', $sparepart->selling_price);
                                            }
                                        }
                                    })
                                    ->visible(fn (Forms\Get $get) => $get('type') === 'sparepart')
                                    ->required(fn (Forms\Get $get) => $get('type') === 'sparepart'),
                                Forms\Components\TextInput::make('item_name')
                                    ->label('Nama Item (Jasa/Sparepart)')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('qty')
                                    ->label('Kuantitas')
                                    ->numeric()
                                    ->default(1)
                                    ->minValue(1)
                                    ->required(),
                                Forms\Components\TextInput::make('price')
                                    ->label('Harga Satuan')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required(),
                            ])
                            ->columns(5)
                            ->columnSpanFull()
                            ->defaultItems(0)
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('booking.queue_number')
                    ->label('No. Antrian')
                    ->badge()
                    ->color('warning'),
                Tables\Columns\TextColumn::make('vehicle.plate_number')
                    ->label('Pelat Nomor')
                    ->description(fn ($record) => $record->vehicle?->brand . ' ' . $record->vehicle?->model),
                Tables\Columns\TextColumn::make('technician.name')
                    ->label('Teknisi')
                    ->default('-')
                    ->sortable(),
                Tables\Columns\TextColumn::make('current_km')
                    ->label('KM')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('health_score')
                    ->label('Health Score')
                    ->suffix('/100')
                    ->sortable()
                    ->default('-'),
                Tables\Columns\TextColumn::make('total_cost')
                    ->label('Total Biaya')
                    ->money('IDR', locale: 'id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('invoice.status')
                    ->label('Status Invoice')
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'lunas' => 'success',
                        'menunggu_pembayaran' => 'warning',
                        default => 'gray',
                    })
                    ->default('Belum Dibuat'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('create_invoice')
                    ->label('Buat Invoice')
                    ->icon('heroicon-o-document-plus')
                    ->color('primary')
                    ->visible(fn (Service $record) => $record->total_cost > 0 && !$record->invoice()->exists())
                    ->requiresConfirmation()
                    ->modalHeading('Buat Invoice untuk Servis ini?')
                    ->modalDescription('Tindakan ini akan membuat invoice tagihan otomatis dan menyelesaikan status booking.')
                    ->action(function (Service $record) {
                        \App\Models\Invoice::create([
                            'service_id' => $record->id,
                            'invoice_number' => 'INV-' . date('Ymd') . '-' . str_pad($record->id, 4, '0', STR_PAD_LEFT),
                            'tax' => $record->total_cost * 0.11,
                            'total_amount' => $record->total_cost * 1.11,
                            'status' => 'menunggu_pembayaran',
                        ]);

                        // Set booking status to selesai
                        if ($record->booking) {
                            $record->booking->update(['status' => 'selesai']);
                        }

                        Notification::make()->title('Invoice berhasil dibuat!')->success()->send();
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServices::route('/'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
