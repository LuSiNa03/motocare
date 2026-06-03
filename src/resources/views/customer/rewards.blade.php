<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight bg-[#810B38] p-4 rounded-lg shadow-md flex justify-between items-center">
            {{ __('Katalog Penukaran Poin') }}
            <a href="{{ route('dashboard') }}" class="text-sm bg-white/20 hover:bg-white/30 px-3 py-1 rounded transition">Kembali</a>
        </h2>
    </x-slot>

    <div class="py-12 bg-[#F1E2D1] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @if (session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded shadow-sm">
                    <p class="text-green-700 font-bold">{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded shadow-sm">
                    <p class="text-red-700 font-bold">{{ session('error') }}</p>
                </div>
            @endif
            
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl p-8 border-t-4 border-[#DCC3AA] flex justify-between items-center">
                <div>
                    <h3 class="text-2xl font-bold text-[#541A1A]">MotoPoints Anda</h3>
                    <p class="text-gray-600 mt-2">Tukarkan poin Anda dengan berbagai penawaran menarik di bawah ini.</p>
                </div>
                <div class="text-right">
                    <p class="text-5xl font-extrabold text-[#810B38]">{{ number_format(Auth::user()->loyalty_points) }} <span class="text-xl">Pts</span></p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($rewards as $reward)
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition-shadow border border-gray-100 flex flex-col">
                    <img src="{{ $reward['image'] }}" alt="{{ $reward['name'] }}" class="w-full h-48 object-cover">
                    <div class="p-6 flex-grow flex flex-col justify-between">
                        <div>
                            <h4 class="text-xl font-bold text-[#541A1A] mb-2">{{ $reward['name'] }}</h4>
                            <p class="text-gray-600 text-sm mb-4">{{ $reward['description'] }}</p>
                        </div>
                        <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between items-center">
                            <span class="font-bold text-[#810B38] text-lg">{{ number_format($reward['points_required']) }} Pts</span>
                            
                            <form action="{{ route('rewards.redeem') }}" method="POST">
                                @csrf
                                <input type="hidden" name="reward_id" value="{{ $reward['id'] }}">
                                <input type="hidden" name="points_required" value="{{ $reward['points_required'] }}">
                                <input type="hidden" name="reward_name" value="{{ $reward['name'] }}">
                                <button type="submit" class="bg-[#DCC3AA] hover:bg-[#541A1A] text-[#541A1A] hover:text-white px-4 py-2 rounded-lg text-sm font-bold transition shadow" onclick="return confirm('Tukar {{ $reward['points_required'] }} poin untuk mendapatkan {{ $reward['name'] }}?');">
                                    Tukar Poin
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>
