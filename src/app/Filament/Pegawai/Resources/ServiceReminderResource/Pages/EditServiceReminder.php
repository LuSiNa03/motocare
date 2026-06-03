<?php

namespace App\Filament\Pegawai\Resources\ServiceReminderResource\Pages;

use App\Filament\Pegawai\Resources\ServiceReminderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditServiceReminder extends EditRecord
{
    protected static string $resource = ServiceReminderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
