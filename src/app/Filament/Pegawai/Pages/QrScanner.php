<?php

namespace App\Filament\Pegawai\Pages;

use App\Models\Vehicle;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class QrScanner extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-qr-code';
    protected static ?string $navigationLabel = 'QR Scanner';
    protected static ?string $title = 'QR Scanner Kendaraan';
    protected static string $view = 'filament.pegawai.pages.qr-scanner';
    protected static ?int $navigationSort = 2;

    public ?array $data = [];
    public ?Vehicle $vehicle = null;
    public ?string $error = null;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('qr_code')
                    ->label('Kode QR / ID Kendaraan')
                    ->placeholder('Masukkan kode QR yang tertera pada kartu kendaraan...')
                    ->required()
                    ->extraAttributes(['autofocus' => true]),
            ])
            ->statePath('data');
    }

    public function lookup(): void
    {
        $this->validate();
        $this->error = null;
        $this->vehicle = null;

        $vehicle = Vehicle::where('qr_code', strtoupper(trim($this->data['qr_code'])))
            ->with(['user', 'services.booking', 'services.invoice'])
            ->first();

        if (!$vehicle) {
            $this->error = 'Kendaraan dengan kode QR "' . $this->data['qr_code'] . '" tidak ditemukan.';
            Notification::make()->title('Kendaraan tidak ditemukan')->danger()->send();
            return;
        }

        $this->vehicle = $vehicle;
        Notification::make()->title('Kendaraan ditemukan: ' . $vehicle->plate_number)->success()->send();
    }
}
