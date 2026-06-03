<?php

namespace App\Filament\Pegawai\Resources\ServiceReminderResource\Pages;

use App\Filament\Pegawai\Resources\ServiceReminderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListServiceReminders extends ListRecords
{
    protected static string $resource = ServiceReminderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
