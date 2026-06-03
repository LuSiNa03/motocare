<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight bg-[#810B38] p-4 rounded-lg shadow-md">
            {{ __('Customer Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-[#F1E2D1] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            @if (session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded shadow-sm">
                    <p class="text-green-700 font-bold">{{ session('success') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded shadow-sm">
                    <ul class="list-disc pl-5 text-red-700 font-semibold">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @php
                $unpaidInvoices = \App\Models\Invoice::whereHas('service.booking', function($query) {
                    $query->where('user_id', Auth::id());
                })->where('status', 'menunggu_pembayaran')->get();
            @endphp

            @if($unpaidInvoices->isNotEmpty())
                @foreach($unpaidInvoices as $inv)
                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded shadow-sm flex justify-between items-center">
                    <div>
                        <h4 class="text-yellow-800 font-bold">Tagihan Belum Dibayar</h4>
                        <p class="text-yellow-700 text-sm">Anda memiliki tagihan servis sejumlah Rp {{ number_format($inv->total_amount, 0, ',', '.') }}. Segera selesaikan pembayaran Anda.</p>
                    </div>
                    <a href="{{ route('invoice.show', $inv->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded font-bold text-sm transition">Bayar Sekarang</a>
                </div>
                @endforeach
            @endif

            <!-- Profil & Poin -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border-t-4 border-[#810B38]">
                <div class="p-8 text-[#541A1A] flex flex-col md:flex-row justify-between items-center">
                    <div>
                        <h3 class="text-2xl font-bold mb-2">Halo, {{ Auth::user()->name }}!</h3>
                        <p class="text-gray-600">Membership Tier: <span class="font-bold text-[#DCC3AA] bg-[#541A1A] px-3 py-1 rounded-full text-sm">GOLD</span></p>
                    </div>
                    <div class="mt-4 md:mt-0 text-center md:text-right">
                        <p class="text-sm text-gray-500">MotoPoints Anda</p>
                        <p class="text-4xl font-extrabold text-[#810B38]">{{ number_format(Auth::user()->loyalty_points) }} <span class="text-lg">Pts</span></p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Kendaraan Saya -->
                <div class="col-span-1 md:col-span-2 space-y-8">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border-t-4 border-[#810B38] p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-xl font-bold text-[#541A1A]">Kendaraan Saya</h3>
                            <button onclick="document.getElementById('modal-tambah-kendaraan').classList.remove('hidden')"
                                class="bg-[#810B38] hover:bg-[#541A1A] text-white px-4 py-2 rounded-lg text-sm font-bold transition shadow-md">
                                + Tambah Kendaraan
                            </button>
                        </div>

                        <!-- Modal Tambah Kendaraan -->
                        <div id="modal-tambah-kendaraan" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
                            <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-lg mx-4">
                                <div class="flex justify-between items-center mb-6">
                                    <h4 class="text-xl font-bold text-[#541A1A]">Tambah Kendaraan Baru</h4>
                                    <button onclick="document.getElementById('modal-tambah-kendaraan').classList.add('hidden')"
                                        class="text-gray-400 hover:text-gray-600 text-2xl font-bold">&times;</button>
                                </div>
                                <form method="POST" action="{{ route('vehicle.store') }}">
                                    @csrf
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-1">Merek *</label>
                                            <input type="text" name="brand" required placeholder="Honda"
                                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#810B38] focus:border-[#810B38] outline-none">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-1">Model *</label>
                                            <input type="text" name="model" required placeholder="Beat"
                                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#810B38] focus:border-[#810B38] outline-none">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-1">No. Polisi *</label>
                                            <input type="text" name="plate_number" required placeholder="B 1234 ABC"
                                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#810B38] focus:border-[#810B38] outline-none">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-1">Tahun</label>
                                            <input type="number" name="year" placeholder="{{ date('Y') }}" min="1990" max="{{ date('Y') + 1 }}"
                                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#810B38] focus:border-[#810B38] outline-none">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-1">Warna</label>
                                            <input type="text" name="color" placeholder="Merah"
                                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#810B38] focus:border-[#810B38] outline-none">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-1">KM Awal</label>
                                            <input type="number" name="init_km" placeholder="0" min="0"
                                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#810B38] focus:border-[#810B38] outline-none">
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">No. Mesin</label>
                                        <input type="text" name="engine_number" placeholder="Opsional"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#810B38] focus:border-[#810B38] outline-none">
                                    </div>
                                    @error('plate_number')
                                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                    <div class="flex gap-3 mt-6">
                                        <button type="button"
                                            onclick="document.getElementById('modal-tambah-kendaraan').classList.add('hidden')"
                                            class="flex-1 border-2 border-gray-300 text-gray-700 px-4 py-2 rounded-lg font-bold text-sm hover:bg-gray-50 transition">
                                            Batal
                                        </button>
                                        <button type="submit"
                                            class="flex-1 bg-[#810B38] hover:bg-[#541A1A] text-white px-4 py-2 rounded-lg font-bold text-sm transition shadow-md">
                                            Simpan Kendaraan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Card Kendaraan -->
                        @forelse(Auth::user()->vehicles as $vehicle)
                        <div class="border border-gray-200 rounded-xl p-4 flex flex-col md:flex-row gap-4 items-center hover:shadow-md transition mb-4">
                            <div class="w-24 h-24 bg-gray-100 rounded-lg flex items-center justify-center shrink-0 overflow-hidden">
                                @if($vehicle->qr_code)
                                    {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(80)->generate($vehicle->qr_code) !!}
                                @else
                                    <svg class="w-12 h-12 text-[#810B38]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                @endif
                            </div>
                            <div class="flex-grow">
                                <h4 class="font-bold text-lg text-[#541A1A]">{{ $vehicle->brand }} {{ $vehicle->model }} ({{ $vehicle->plate_number }})</h4>
                                <p class="text-sm text-gray-500 mb-2">Tahun {{ $vehicle->year ?? '-' }} • {{ $vehicle->color ?? '-' }}</p>
                                <div class="flex flex-wrap gap-2">
                                    <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full font-semibold">
                                        Health Score: {{ $vehicle->services()->latest()->first()?->health_score ?? 'N/A' }}/100
                                    </span>
                                    @if($vehicle->next_service_date || $vehicle->next_service_km)
                                        @php
                                            $daysLeft = $vehicle->next_service_date ? \Carbon\Carbon::parse($vehicle->next_service_date)->diffInDays(now()) : 0;
                                        @endphp
                                        <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full font-semibold">
                                            Smart Reminder: 
                                            @if($vehicle->next_service_date)
                                                {{ \Carbon\Carbon::parse($vehicle->next_service_date)->format('d M Y') }}
                                            @endif
                                            @if($vehicle->next_service_km)
                                                / {{ number_format($vehicle->next_service_km) }} km
                                            @endif
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="shrink-0 flex flex-col gap-2">
                                <button class="bg-[#DCC3AA] hover:bg-[#541A1A] hover:text-white text-[#541A1A] px-4 py-2 rounded-lg text-sm font-bold transition shadow">Lihat Detail</button>
                                <form method="POST" action="{{ route('vehicle.destroy', $vehicle->id) }}" onsubmit="return confirm('Hapus kendaraan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full bg-red-100 hover:bg-red-600 hover:text-white text-red-600 px-4 py-2 rounded-lg text-sm font-bold transition shadow">Hapus</button>
                                </form>
                            </div>
                        </div>
                        @empty
                        <p class="text-gray-500">Belum ada kendaraan yang terdaftar.</p>
                        @endforelse
                    </div>

                    <!-- Riwayat Servis -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border-t-4 border-[#810B38] p-6">
                        <h3 class="text-xl font-bold text-[#541A1A] mb-6">Riwayat Servis Terakhir</h3>
                        @php
                            $recentServices = \App\Models\Service::whereHas('booking', fn($q) => $q->where('user_id', Auth::id()))
                                ->with(['booking.branch', 'booking.servicePackage', 'technician'])
                                ->latest()->take(3)->get();
                        @endphp
                        <div class="space-y-4">
                            @forelse($recentServices as $svc)
                            <div class="border-l-4 {{ $loop->first ? 'border-[#810B38]' : 'border-gray-300' }} pl-4 py-2">
                                <p class="text-sm text-gray-500">{{ $svc->created_at->format('d M Y') }} • {{ $svc->booking?->branch?->name ?? '-' }}</p>
                                <p class="font-bold text-[#541A1A]">{{ $svc->booking?->servicePackage?->name ?? 'Servis Reguler' }}</p>
                                <p class="text-sm text-gray-600">
                                    @if($svc->technician) Teknisi: {{ $svc->technician->name }} • @endif
                                    Rp {{ number_format($svc->total_cost, 0, ',', '.') }}
                                </p>
                            </div>
                            @empty
                            <p class="text-gray-500 text-sm">Belum ada riwayat servis.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Booking Aktif -->
                <div class="col-span-1 space-y-8">
                    @php
                        $activeBooking = \App\Models\Booking::where('user_id', Auth::id())
                            ->whereIn('status', ['menunggu', 'disetujui', 'dalam_pengerjaan'])
                            ->with('branch', 'servicePackage')
                            ->latest()->first();
                    @endphp
                    <div class="bg-[#810B38] text-white overflow-hidden shadow-xl sm:rounded-2xl p-6">
                        <h3 class="text-xl font-bold mb-4">Booking Aktif</h3>
                        @if($activeBooking)
                        <div class="bg-white/10 rounded-xl p-4 mb-4">
                            <p class="text-sm text-white/70">Jadwal Selanjutnya</p>
                            <p class="font-bold text-lg text-[#DCC3AA]">{{ \Carbon\Carbon::parse($activeBooking->date)->format('d M Y') }}, {{ substr($activeBooking->time, 0, 5) }}</p>
                            <p class="text-sm">{{ $activeBooking->branch?->name ?? '-' }}</p>
                            @if($activeBooking->servicePackage)
                            <p class="text-xs text-white/60 mt-1">{{ $activeBooking->servicePackage->name }}</p>
                            @endif
                            <div class="mt-4 pt-4 border-t border-white/20">
                                <p class="text-sm">No. Antrian</p>
                                <p class="text-3xl font-extrabold text-[#DCC3AA]">{{ $activeBooking->queue_number ?? '-' }}</p>
                                <span class="text-xs bg-white/20 px-2 py-0.5 rounded-full mt-1 inline-block">
                                    {{ match($activeBooking->status) {
                                        'menunggu' => '⏳ Menunggu Konfirmasi',
                                        'disetujui' => '✅ Disetujui',
                                        'dalam_pengerjaan' => '🔧 Dalam Pengerjaan',
                                        default => $activeBooking->status
                                    } }}
                                </span>
                            </div>
                        </div>
                        @else
                        <div class="bg-white/10 rounded-xl p-4 mb-4 text-center">
                            <p class="text-white/70 text-sm">Tidak ada booking aktif.</p>
                        </div>
                        @endif
                        <a href="{{ route('booking.create') }}" class="block text-center w-full bg-[#DCC3AA] text-[#541A1A] hover:bg-white px-4 py-3 rounded-lg font-bold transition shadow-md">Booking Servis Baru</a>
                    </div>

                    <!-- Promo Eksklusif -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border-t-4 border-[#DCC3AA] p-6">
                        <h3 class="text-lg font-bold text-[#541A1A] mb-2">Promo Member</h3>
                        <p class="text-sm text-gray-600 mb-4">Tukarkan 1,000 poin Anda untuk gratis servis CVT bulan ini!</p>
                        <a href="{{ route('rewards.index') }}" class="block text-center w-full border-2 border-[#810B38] text-[#810B38] hover:bg-[#810B38] hover:text-white px-4 py-2 rounded-lg font-bold transition">Klaim Promo</a>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>
