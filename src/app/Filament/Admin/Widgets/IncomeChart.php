<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Invoice;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class IncomeChart extends ChartWidget
{
    protected static ?string $heading = 'Laporan Pendapatan Bulanan';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $year = Carbon::now()->year;
        
        $data = Invoice::where('status', 'lunas')
            ->whereYear('created_at', $year)
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_amount) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->all();

        $monthlyRevenue = [];
        $months = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
            7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
        ];

        for ($i = 1; $i <= 12; $i++) {
            $monthlyRevenue[] = floatval($data[$i] ?? 0);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pendapatan (Rp)',
                    'data' => $monthlyRevenue,
                    'borderColor' => '#810B38',
                    'backgroundColor' => 'rgba(129, 11, 56, 0.1)',
                    'fill' => true,
                ],
            ],
            'labels' => array_values($months),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
