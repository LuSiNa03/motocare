<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Form Input QR --}}
        <x-filament::section>
            <x-slot name="heading">Cari Kendaraan via Kode QR</x-slot>
            <x-slot name="description">Masukkan kode QR yang tertera pada stiker kendaraan pelanggan.</x-slot>

            <form wire:submit="lookup" class="flex gap-4 items-end">
                {{ $this->form }}
                <x-filament::button type="submit" color="primary" icon="heroicon-o-magnifying-glass">
                    Cari Kendaraan
                </x-filament::button>
            </form>
        </x-filament::section>

        {{-- Error --}}
        @if($error)
            <x-filament::section>
                <div class="text-danger-600 font-semibold flex gap-2 items-center">
                    <x-heroicon-o-exclamation-circle class="w-5 h-5"/>
                    {{ $error }}
                </div>
            </x-filament::section>
        @endif

        {{-- Hasil Kendaraan --}}
        @if($vehicle)
        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center gap-2">
                    <x-heroicon-o-check-circle class="w-6 h-6 text-success-500"/>
                    Kendaraan Ditemukan
                </div>
            </x-slot>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Info Kendaraan --}}
                <div class="md:col-span-2 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-4">
                            <p class="text-xs text-gray-500 mb-1">Pelat Nomor</p>
                            <p class="text-xl font-bold">{{ $vehicle->plate_number }}</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-4">
                            <p class="text-xs text-gray-500 mb-1">Pemilik</p>
                            <p class="text-xl font-bold">{{ $vehicle->user->name }}</p>
                            <p class="text-sm text-gray-500">{{ $vehicle->user->email }}</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-4">
                            <p class="text-xs text-gray-500 mb-1">Merek / Model</p>
                            <p class="font-semibold">{{ $vehicle->brand }} {{ $vehicle->model }}</p>
                            <p class="text-sm text-gray-500">Tahun {{ $vehicle->year ?? '-' }} • {{ $vehicle->color ?? '-' }}</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-4">
                            <p class="text-xs text-gray-500 mb-1">MotoPoints Pelanggan</p>
                            <p class="text-xl font-bold text-primary-600">{{ number_format($vehicle->user->loyalty_points) }} Pts</p>
                        </div>
                    </div>

                    @if($vehicle->next_service_date || $vehicle->next_service_km)
                    <div class="bg-warning-50 border border-warning-300 rounded-xl p-4">
                        <p class="font-semibold text-warning-700">⚠️ Jadwal Servis Selanjutnya</p>
                        <p class="text-sm text-warning-600 mt-1">
                            @if($vehicle->next_service_date)Tanggal: {{ \Carbon\Carbon::parse($vehicle->next_service_date)->format('d M Y') }}@endif
                            @if($vehicle->next_service_km) &nbsp;|&nbsp; Target KM: {{ number_format($vehicle->next_service_km) }} km@endif
                        </p>
                    </div>
                    @endif

                    {{-- Riwayat Servis --}}
                    <div>
                        <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-3">Riwayat Servis ({{ $vehicle->services->count() }} servis)</h4>
                        @forelse($vehicle->services->take(3) as $service)
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-3 mb-2 flex justify-between items-center">
                            <div>
                                <p class="font-semibold text-sm">{{ $service->booking?->servicePackage?->name ?? 'Servis Umum' }}</p>
                                <p class="text-xs text-gray-500">{{ $service->created_at->format('d M Y') }} • KM: {{ number_format($service->current_km) }}</p>
                            </div>
                            <div class="text-right">
                                @if($service->health_score)
                                    <span class="text-xs bg-success-100 text-success-700 px-2 py-1 rounded-full font-semibold">Score: {{ $service->health_score }}/100</span>
                                @endif
                                <p class="text-sm font-bold mt-1">Rp {{ number_format($service->total_cost, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        @empty
                        <p class="text-sm text-gray-500">Belum ada riwayat servis.</p>
                        @endforelse
                    </div>
                </div>

                {{-- QR Code --}}
                <div class="flex flex-col items-center justify-start gap-4">
                    <div class="bg-white border-2 border-gray-200 rounded-xl p-4 flex flex-col items-center gap-2">
                        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(160)->generate($vehicle->qr_code) !!}
                        <p class="text-xs text-gray-500 font-mono mt-2">{{ $vehicle->qr_code }}</p>
                    </div>
                </div>
            </div>
        </x-filament::section>
        @endif
    </div>
</x-filament-panels::page>
