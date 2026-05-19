<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk — SIPETA-TRANS</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center font-[Instrument_Sans] antialiased">

    <div class="w-full max-w-3xl mx-4 rounded-2xl overflow-hidden shadow-2xl flex min-h-[480px]">

        {{-- ── Panel Kiri: Branding ── --}}
        <div class="hidden md:flex flex-col justify-between w-5/12 bg-gradient-to-b from-blue-900 to-blue-800 p-10 text-white">
            <div>
                <div class="w-12 h-12 bg-blue-400 rounded-xl flex items-center justify-center mb-8">
                    <i class="fas fa-map-location-dot text-white text-xl"></i>
                </div>
                <h1 class="text-2xl font-bold leading-tight mb-2">SIPETA-TRANS</h1>
                <p class="text-blue-200 text-sm leading-relaxed">Sistem Informasi Peta Aset Transportasi</p>
            </div>
            <div class="space-y-3">
                <div class="flex items-center gap-3 text-blue-200 text-xs">
                    <i class="fas fa-map-pin w-4 text-center"></i>
                    <span>Pemantauan aset secara real-time</span>
                </div>
                <div class="flex items-center gap-3 text-blue-200 text-xs">
                    <i class="fas fa-shield-halved w-4 text-center"></i>
                    <span>Sistem terproteksi & aman</span>
                </div>
                <div class="flex items-center gap-3 text-blue-200 text-xs">
                    <i class="fas fa-chart-line w-4 text-center"></i>
                    <span>Laporan & analitik terpadu</span>
                </div>
            </div>
        </div>

        {{-- ── Panel Kanan: Form ── --}}
        <div class="flex-1 bg-white flex flex-col justify-center px-8 py-10 md:px-10">

            {{-- Logo mobile --}}
            <div class="flex md:hidden items-center gap-3 mb-8">
                <div class="w-9 h-9 bg-blue-800 rounded-lg flex items-center justify-center">
                    <i class="fas fa-map-location-dot text-white text-sm"></i>
                </div>
                <span class="font-bold text-gray-800">SIPETA-TRANS</span>
            </div>

            <h2 class="text-xl font-bold text-gray-800 mb-1">Selamat Datang</h2>
            <p class="text-gray-500 text-sm mb-7">Masuk untuk mengakses dasbor Anda</p>

            <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
                @csrf

                {{-- Error global --}}
                @if ($errors->any())
                    <div class="flex items-start gap-2 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3">
                        <i class="fas fa-circle-exclamation mt-0.5 shrink-0"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                {{-- Email --}}
                <div class="space-y-1.5">
                    <label for="email" class="block text-sm font-medium text-gray-700">Alamat Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="contoh@instansi.go.id"
                        autocomplete="email"
                        autofocus
                        required
                        class="w-full px-3.5 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition placeholder-gray-400 @error('email') border-red-400 bg-red-50 @enderror"
                    />
                </div>

                {{-- Password --}}
                <div class="space-y-1.5">
                    <label for="password" class="block text-sm font-medium text-gray-700">Kata Sandi</label>
                    <div class="relative">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="••••••••"
                            autocomplete="current-password"
                            required
                            class="w-full px-3.5 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition placeholder-gray-400 pr-10 @error('password') border-red-400 bg-red-50 @enderror"
                        />
                        <button type="button" onclick="togglePwd()" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition">
                            <i class="fas fa-eye text-sm" id="pwdIcon"></i>
                        </button>
                    </div>
                </div>

                {{-- Remember --}}
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="remember" name="remember" class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                    <label for="remember" class="text-sm text-gray-600 cursor-pointer select-none">Ingat saya</label>
                </div>

                {{-- Submit --}}
                <button
                    type="submit"
                    class="w-full bg-blue-800 hover:bg-blue-900 active:bg-blue-950 text-white text-sm font-semibold py-2.5 px-4 rounded-lg transition focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 mt-2"
                >
                    Masuk ke Sistem
                </button>
            </form>

            <p class="text-center text-xs text-gray-400 mt-8">
                Belum punya akun? <a href="{{ route('register') }}" class="text-blue-700 hover:text-blue-800 font-medium">Daftar di sini</a>
            </p>
        </div>
    </div>

    <script>
        function togglePwd() {
            const input = document.getElementById('password');
            const icon  = document.getElementById('pwdIcon');
            const show  = input.type === 'password';
            input.type  = show ? 'text' : 'password';
            icon.className = show ? 'fas fa-eye-slash text-sm' : 'fas fa-eye text-sm';
        }
    </script>
</body>
</html>
