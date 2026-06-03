<?php

namespace App\Filament\Admin\Resources\SparepartResource\Pages;

use App\Filament\Admin\Resources\SparepartResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSpareparts extends ListRecords
{
    protected static string $resource = SparepartResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
