<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight bg-[#810B38] p-4 rounded-lg shadow-md flex justify-between items-center">
            {{ __('Lokasi Cabang Bengkel MotoCare') }}
            <a href="{{ route('dashboard') }}" class="text-sm bg-white/20 hover:bg-white/30 px-3 py-1 rounded transition">Kembali</a>
        </h2>
    </x-slot>

    <!-- Leaflet.js CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

    <div class="py-12 bg-[#F1E2D1] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl p-8 border-t-4 border-[#810B38]">
                <div class="mb-6">
                    <h3 class="text-2xl font-bold text-[#541A1A]">Peta Cabang Interaktif</h3>
                    <p class="text-gray-600 mt-1">Temukan cabang MotoCare terdekat untuk perawatan sepeda motor Anda.</p>
                </div>

                <!-- Map Container -->
                <div id="map" class="w-full h-[500px] rounded-2xl shadow-inner border-2 border-[#DCC3AA]" style="z-index: 1;"></div>
            </div>

            <!-- Branch Cards List -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @foreach($branches as $branch)
                <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100 flex flex-col justify-between hover:shadow-2xl transition duration-300">
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="p-2 bg-[#F1E2D1] text-[#810B38] rounded-full">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </span>
                            <h4 class="text-xl font-bold text-[#541A1A]">{{ $branch->name }}</h4>
                        </div>
                        <p class="text-gray-600 text-sm mb-2"><strong>Alamat:</strong> {{ $branch->address ?? '-' }}</p>
                        <p class="text-gray-600 text-sm"><strong>Telepon:</strong> {{ $branch->phone ?? '-' }}</p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-gray-100 flex gap-2">
                        @if($branch->latitude && $branch->longitude)
                        <button 
                            onclick="focusBranch({{ $branch->latitude }}, {{ $branch->longitude }}, '{{ $branch->name }}')"
                            class="flex-1 bg-[#DCC3AA] hover:bg-[#541A1A] text-[#541A1A] hover:text-white px-4 py-2 rounded-lg text-sm font-bold transition text-center"
                        >
                            Lihat di Peta
                        </button>
                        <a 
                            href="https://www.google.com/maps/search/?api=1&query={{ $branch->latitude }},{{ $branch->longitude }}" 
                            target="_blank" 
                            class="flex-1 bg-[#810B38] hover:bg-[#541A1A] text-white px-4 py-2 rounded-lg text-sm font-bold transition text-center"
                        >
                            Petunjuk Arah
                        </a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </div>

    <!-- Leaflet.js JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        // Initialize Map centered around Bandung region
        const map = L.map('map').setView([-6.9175, 107.6191], 12);

        // OSM Tile Layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // Branch markers data from PHP
        const branches = @json($branches);
        const markers = {};

        branches.forEach(branch => {
            if (branch.latitude && branch.longitude) {
                const lat = parseFloat(branch.latitude);
                const lng = parseFloat(branch.longitude);
                
                const marker = L.marker([lat, lng]).addTo(map)
                    .bindPopup(`
                        <div style="font-family: sans-serif;">
                            <h4 style="margin: 0 0 5px 0; color: #810B38;">${branch.name}</h4>
                            <p style="margin: 0 0 10px 0; font-size: 12px; color: #555;">${branch.address || ''}</p>
                            <a href="https://www.google.com/maps/search/?api=1&query=${lat},${lng}" target="_blank" style="display: inline-block; background-color: #810B38; color: #fff; padding: 4px 8px; border-radius: 4px; font-size: 11px; text-decoration: none; font-weight: bold;">Rute Navigasi</a>
                        </div>
                    `);
                
                markers[`${lat}_${lng}`] = marker;
            }
        });

        // Function to focus Map on selected branch
        function focusBranch(lat, lng, name) {
            map.setView([lat, lng], 15);
            const key = `${lat}_${lng}`;
            if (markers[key]) {
                markers[key].openPopup();
            }
            // Scroll to map smoothly
            document.getElementById('map').scrollIntoView({ behavior: 'smooth' });
        }
    </script>
</x-app-layout>
