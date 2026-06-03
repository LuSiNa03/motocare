<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight bg-[#810B38] p-4 rounded-lg shadow-md flex justify-between items-center">
            {{ __('Booking Servis Online') }}
            <a href="{{ route('dashboard') }}" class="text-sm bg-white/20 hover:bg-white/30 px-3 py-1 rounded transition">Kembali</a>
        </h2>
    </x-slot>

    <div class="py-12 bg-[#F1E2D1] min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border-t-4 border-[#810B38] p-8">
                
                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Terdapat kesalahan pada input Anda:</h3>
                                <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('booking.store') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Pilih Kendaraan -->
                    <div>
                        <label for="vehicle_id" class="block text-sm font-medium text-[#541A1A]">Pilih Kendaraan Anda <span class="text-red-500">*</span></label>
                        <select id="vehicle_id" name="vehicle_id" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-[#810B38] focus:border-[#810B38] sm:text-sm rounded-md">
                            <option value="" disabled selected>-- Pilih Kendaraan --</option>
                            @forelse($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}" {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                    {{ $vehicle->brand }} {{ $vehicle->model }} ({{ $vehicle->plate_number }})
                                </option>
                            @empty
                                <option value="" disabled>Anda belum mendaftarkan kendaraan.</option>
                            @endforelse
                        </select>
                    </div>

                    <!-- Pilih Cabang -->
                    <div>
                        <label for="branch_id" class="block text-sm font-medium text-[#541A1A]">Pilih Cabang MotoCare <span class="text-red-500">*</span></label>
                        <select id="branch_id" name="branch_id" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-[#810B38] focus:border-[#810B38] sm:text-sm rounded-md">
                            <option value="" disabled selected>-- Pilih Cabang --</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }} - {{ $branch->address }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Pilih Paket Servis (Opsional) -->
                    <div>
                        <label for="service_package_id" class="block text-sm font-medium text-[#541A1A]">Paket Servis (Opsional)</label>
                        <select id="service_package_id" name="service_package_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-[#810B38] focus:border-[#810B38] sm:text-sm rounded-md">
                            <option value="" selected>-- Tanpa Paket (Konsultasi di Tempat) --</option>
                            @foreach($packages as $package)
                                <option value="{{ $package->id }}" {{ old('service_package_id') == $package->id ? 'selected' : '' }}>
                                    {{ $package->name }} - Rp {{ number_format($package->price, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tanggal dan Waktu -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="date" class="block text-sm font-medium text-[#541A1A]">Tanggal <span class="text-red-500">*</span></label>
                            <input type="date" name="date" id="date" required min="{{ date('Y-m-d') }}" value="{{ old('date') }}" class="mt-1 focus:ring-[#810B38] focus:border-[#810B38] block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>
                        <div>
                            <label for="time" class="block text-sm font-medium text-[#541A1A]">Waktu <span class="text-red-500">*</span></label>
                            <input type="time" name="time" id="time" required value="{{ old('time') }}" class="mt-1 focus:ring-[#810B38] focus:border-[#810B38] block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>
                    </div>

                    <!-- Catatan -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-[#541A1A]">Catatan / Keluhan (Opsional)</label>
                        <div class="mt-1">
                            <textarea id="notes" name="notes" rows="3" class="shadow-sm focus:ring-[#810B38] focus:border-[#810B38] mt-1 block w-full sm:text-sm border border-gray-300 rounded-md" placeholder="Contoh: Suara mesin kasar, rem kurang pakem...">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-200 flex justify-end">
                        <button type="submit" class="bg-[#810B38] hover:bg-[#541A1A] text-white px-6 py-3 rounded-lg text-sm font-bold transition shadow-md w-full md:w-auto flex justify-center items-center">
                            Konfirmasi Booking
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
