<?php

namespace App\Filament\Admin\Widgets;

use App\Models\User;
use App\Models\Invoice;
use App\Models\Sparepart;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $customerCount = User::where('role', 'user')->count();
        $totalEarnings = Invoice::where('status', 'lunas')->sum('total_amount');
        $lowStockParts = Sparepart::where('stock', '<=', 5)->count();

        return [
            Stat::make('Pelanggan Terdaftar', number_format($customerCount))
                ->description('Jumlah pelanggan aktif di platform')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),
            Stat::make('Total Pendapatan Bengkel', 'Rp ' . number_format($totalEarnings, 0, ',', '.'))
                ->description('Total dari invoice yang sudah lunas')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('primary'),
            Stat::make('Stok Suku Cadang Menipis', number_format($lowStockParts) . ' Item')
                ->description('Suku cadang dengan stok <= 5 pcs')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($lowStockParts > 0 ? 'danger' : 'success'),
        ];
    }
}
