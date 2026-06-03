<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MotoCare - Motor Terawat, Perjalanan Lebih Aman</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,600,800&display=swap" rel="stylesheet" />
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased font-sans bg-[#F1E2D1] text-[#541A1A]">
    <!-- Navbar -->
    <nav class="bg-[#810B38] text-[#F1E2D1] shadow-lg relative z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex-shrink-0 flex items-center">
                    <span class="font-extrabold text-3xl tracking-tight text-[#DCC3AA]">MotoCare</span>
                </div>
                <div class="hidden md:flex space-x-8 items-center">
                    <a href="#" class="hover:text-[#DCC3AA] font-semibold transition">Home</a>
                    <a href="#" class="hover:text-[#DCC3AA] font-semibold transition">Layanan</a>
                    <a href="{{ route('branches.index') }}" class="hover:text-[#DCC3AA] font-semibold transition">Cabang</a>
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="hover:text-[#DCC3AA] font-semibold transition">Dashboard</a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="bg-[#DCC3AA] text-[#541A1A] px-5 py-2 rounded-full font-bold hover:bg-white transition shadow-md cursor-pointer">
                                    Keluar
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="hover:text-[#DCC3AA] font-semibold transition">Login</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="bg-[#DCC3AA] text-[#541A1A] px-5 py-2 rounded-full font-bold hover:bg-white transition shadow-md">Daftar</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative bg-black overflow-hidden h-[600px] flex items-center">
        <img src="https://images.unsplash.com/photo-1558981403-c5f9899a28bc?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" alt="Motorcycle" class="absolute inset-0 w-full h-full object-cover opacity-50">
        <div class="absolute inset-0 bg-gradient-to-r from-[#541A1A]/90 to-transparent"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full z-10">
            <div class="max-w-xl">
                <h1 class="text-5xl font-extrabold text-[#F1E2D1] leading-tight mb-4">
                    Motor Terawat, <br>
                    <span class="text-[#DCC3AA]">Perjalanan Lebih Aman</span>
                </h1>
                <p class="text-lg text-gray-200 mb-8 font-medium">
                    Platform bengkel cerdas pertama yang memahami kebutuhan kendaraan Anda. Pantau kesehatan motor, kumpulkan poin, dan nikmati servis tanpa antri.
                </p>
                <div class="flex flex-wrap gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="bg-[#810B38] hover:bg-[#541A1A] text-white px-8 py-4 rounded-full font-bold text-lg transition shadow-xl transform hover:-translate-y-1">
                            Ke Dashboard Saya
                        </a>
                        <a href="{{ route('booking.create') }}" class="bg-transparent border-2 border-[#DCC3AA] text-[#DCC3AA] hover:bg-[#DCC3AA] hover:text-[#541A1A] px-8 py-4 rounded-full font-bold text-lg transition shadow-xl">
                            Booking Servis
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="bg-[#810B38] hover:bg-[#541A1A] text-white px-8 py-4 rounded-full font-bold text-lg transition shadow-xl transform hover:-translate-y-1">
                            Daftar Gratis
                        </a>
                        <a href="{{ route('login') }}" class="bg-transparent border-2 border-[#DCC3AA] text-[#DCC3AA] hover:bg-[#DCC3AA] hover:text-[#541A1A] px-8 py-4 rounded-full font-bold text-lg transition shadow-xl">
                            Login
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="py-20 bg-[#F1E2D1]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-extrabold text-[#810B38]">Smart Maintenance Platform</h2>
                <p class="mt-4 text-xl text-[#541A1A] max-w-3xl mx-auto">Kami memadukan teknologi dan keahlian mekanik profesional untuk memberikan pengalaman servis terbaik.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <!-- Feature 1 -->
                <div class="bg-white rounded-2xl p-8 shadow-xl border-t-4 border-[#810B38] transform transition hover:-translate-y-2">
                    <div class="w-14 h-14 bg-[#F1E2D1] rounded-full flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-[#810B38]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#541A1A] mb-3">Vehicle Health Score</h3>
                    <p class="text-gray-600">Sistem cerdas kami akan menganalisa riwayat servis untuk memberikan skor kesehatan akurat pada setiap komponen kendaraan Anda.</p>
                </div>
                <!-- Feature 2 -->
                <div class="bg-white rounded-2xl p-8 shadow-xl border-t-4 border-[#810B38] transform transition hover:-translate-y-2">
                    <div class="w-14 h-14 bg-[#F1E2D1] rounded-full flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-[#810B38]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#541A1A] mb-3">Live Queue & Booking</h3>
                    <p class="text-gray-600">Pesan jadwal servis kapan saja. Pantau progres pengerjaan dan antrian bengkel secara real-time dari handphone Anda.</p>
                </div>
                <!-- Feature 3 -->
                <div class="bg-white rounded-2xl p-8 shadow-xl border-t-4 border-[#810B38] transform transition hover:-translate-y-2">
                    <div class="w-14 h-14 bg-[#F1E2D1] rounded-full flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-[#810B38]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#541A1A] mb-3">Loyalty Rewards</h3>
                    <p class="text-gray-600">Kumpulkan poin dari setiap transaksi servis. Tukarkan dengan diskon sparepart, gratis servis, atau merchandise eksklusif.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-[#541A1A] py-12 border-t-4 border-[#810B38]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="font-extrabold text-3xl tracking-tight text-[#DCC3AA] mb-4 block">MotoCare</span>
            <p class="text-[#F1E2D1] mb-8">© 2026 MotoCare Platform. Hak Cipta Dilindungi.</p>
            <div class="flex justify-center space-x-6">
                <a href="/super-admin" class="text-[#DCC3AA] hover:text-white transition">Admin Panel</a>
                <a href="/pegawai" class="text-[#DCC3AA] hover:text-white transition">Pegawai Panel</a>
                <a href="/dashboard" class="text-[#DCC3AA] hover:text-white transition">Customer Dashboard</a>
            </div>
        </div>
    </footer>
</body>
</html>
