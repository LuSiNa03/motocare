<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight bg-[#810B38] p-4 rounded-lg shadow-md flex justify-between items-center">
            {{ __('Checkout & Pembayaran') }}
            <div class="flex items-center gap-2">
                <a href="{{ route('invoice.pdf', $invoice->id) }}" class="text-sm bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded transition flex items-center gap-1 font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Unduh PDF
                </a>
                <a href="{{ route('dashboard') }}" class="text-sm bg-white/20 hover:bg-white/30 px-3 py-1 rounded transition">Kembali</a>
            </div>
        </h2>
    </x-slot>

    <div class="py-12 bg-[#F1E2D1] min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded shadow-sm mb-6">
                    <p class="text-green-700 font-bold">{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl p-8">
                
                <div class="flex justify-between items-start mb-8 pb-8 border-b border-gray-200">
                    <div>
                        <h3 class="text-3xl font-extrabold text-[#541A1A]">INVOICE</h3>
                        <p class="text-gray-500 font-mono mt-1">#{{ $invoice->invoice_number }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-gray-800">MotoCare Pusat</p>
                        <p class="text-sm text-gray-500">Jl. Raya Bengkel No.1, Bandung</p>
                    </div>
                </div>

                <div class="mb-8 flex justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Ditagihkan Kepada:</p>
                        <p class="font-bold text-gray-800">{{ $invoice->service->booking->user->name }}</p>
                        <p class="text-sm text-gray-600">{{ $invoice->service->vehicle->brand }} {{ $invoice->service->vehicle->model }} ({{ $invoice->service->vehicle->plate_number }})</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500 mb-1">Status Pembayaran:</p>
                        @if($invoice->status === 'lunas')
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full font-bold text-sm">LUNAS</span>
                        @else
                            <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full font-bold text-sm">MENUNGGU PEMBAYARAN</span>
                        @endif
                    </div>
                </div>

                <table class="w-full text-left mb-8">
                    <thead>
                        <tr class="border-b border-gray-300 text-[#541A1A]">
                            <th class="py-3 font-bold">Deskripsi Layanan</th>
                            <th class="py-3 font-bold text-right">Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Asumsi menggunakan total dari service, rincian detail bisa di-loop jika ada -->
                        <tr class="border-b border-gray-100">
                            <td class="py-4">
                                <p class="font-semibold text-gray-800">Jasa Servis & Sparepart ({{ $invoice->service->booking->servicePackage?->name ?? 'Servis Reguler' }})</p>
                            </td>
                            <td class="py-4 text-right font-mono text-gray-700">Rp {{ number_format($invoice->total_amount - $invoice->tax, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="py-4 font-semibold text-gray-600 text-right">Pajak (11%)</td>
                            <td class="py-4 text-right font-mono text-gray-700">Rp {{ number_format($invoice->tax, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="border-t-2 border-gray-300">
                            <td class="py-4 font-bold text-[#810B38] text-right text-xl">TOTAL TAGIHAN</td>
                            <td class="py-4 text-right font-mono font-bold text-[#810B38] text-xl">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>

                @if($invoice->status !== 'lunas')
                    <div class="bg-[#F1E2D1] p-6 rounded-xl flex flex-col items-center">
                        <p class="text-[#541A1A] mb-4 text-center">Silakan selesaikan pembayaran tagihan Anda. (Mode Simulasi)</p>
                        <form action="{{ route('invoice.pay', $invoice->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-[#810B38] hover:bg-[#541A1A] text-white px-8 py-4 rounded-xl text-lg font-bold transition shadow-lg flex items-center gap-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                Bayar Sekarang (Simulasi)
                            </button>
                        </form>
                    </div>
                @else
                    {{-- Ulasan & Rating Section --}}
                    @if(is_null($invoice->service->rating))
                        <div class="mt-8 bg-gray-50 rounded-2xl p-6 border border-gray-100">
                            <h4 class="font-bold text-[#541A1A] text-lg mb-2">Beri Ulasan Servis Anda</h4>
                            <p class="text-gray-500 text-sm mb-4">Ulasan Anda sangat berarti bagi kami untuk meningkatkan kualitas layanan.</p>
                            
                            <form action="{{ route('invoice.review', $invoice->id) }}" method="POST" class="space-y-4">
                                @csrf
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-semibold text-gray-700">Rating:</span>
                                    <div class="flex gap-1" x-data="{ rating: 0, hoverRating: 0 }">
                                        <template x-for="i in 5">
                                            <button 
                                                type="button" 
                                                @click="rating = i" 
                                                @mouseenter="hoverRating = i" 
                                                @mouseleave="hoverRating = 0"
                                                class="focus:outline-none transition-transform active:scale-95 text-gray-300"
                                            >
                                                <svg 
                                                    class="w-8 h-8 cursor-pointer transition-colors" 
                                                    :class="(hoverRating ? i <= hoverRating : i <= rating) ? 'text-yellow-400 fill-yellow-400' : 'text-gray-300'" 
                                                    xmlns="http://www.w3.org/2000/svg" 
                                                    viewBox="0 0 20 20" 
                                                    fill="currentColor"
                                                >
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            </button>
                                        </template>
                                        <input type="hidden" name="rating" :value="rating" required />
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="comment" class="block text-sm font-semibold text-gray-700 mb-1">Komentar:</label>
                                    <textarea 
                                        name="comment" 
                                        id="comment" 
                                        rows="3" 
                                        placeholder="Tulis ulasan Anda di sini..." 
                                        class="w-full rounded-xl border-gray-200 text-gray-700 focus:border-[#810B38] focus:ring-[#810B38] text-sm"
                                    ></textarea>
                                </div>
                                
                                <button type="submit" class="bg-[#810B38] hover:bg-[#541A1A] text-white px-5 py-2.5 rounded-xl font-bold text-sm transition shadow-md">
                                    Kirim Ulasan
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="mt-8 bg-gray-50 rounded-2xl p-6 border border-gray-100">
                            <h4 class="font-bold text-[#541A1A] text-lg mb-2">Ulasan Anda</h4>
                            <div class="flex items-center gap-1 mb-3">
                                @for ($i = 1; $i <= 5; $i++)
                                    <svg 
                                        class="w-6 h-6 {{ $i <= $invoice->service->rating ? 'text-yellow-400 fill-yellow-400' : 'text-gray-300' }}" 
                                        xmlns="http://www.w3.org/2000/svg" 
                                        viewBox="0 0 20 20" 
                                        fill="currentColor"
                                    >
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @endfor
                            </div>
                            @if($invoice->service->comment)
                                <p class="text-gray-700 text-sm bg-white border border-gray-100 rounded-lg p-3 italic">"{{ $invoice->service->comment }}"</p>
                            @else
                                <p class="text-gray-400 text-sm italic">Tidak ada komentar.</p>
                            @endif
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
