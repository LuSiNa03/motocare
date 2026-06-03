<?php

namespace App\Filament\Admin\Resources\SparepartResource\Pages;

use App\Filament\Admin\Resources\SparepartResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSparepart extends EditRecord
{
    protected static string $resource = SparepartResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
