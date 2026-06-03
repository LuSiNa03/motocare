<?php

namespace App\Filament\Pegawai\Resources\ServiceResource\Pages;

use App\Filament\Pegawai\Resources\ServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListServices extends ListRecords
{
    protected static string $resource = ServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
