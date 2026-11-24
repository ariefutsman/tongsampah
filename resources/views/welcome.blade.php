<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Bank Sampah Digital') }}</title>
        <meta name="description" content="Digitalisasi pencatatan setoran sampah, saldo, dan penarikan bank sampah — transparan, cepat, dan mudah dipantau.">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-white text-slate-900 antialiased overflow-x-hidden">
        <div class="min-h-screen flex flex-col">
            <!-- Nav -->
            <header class="w-full">
                <div class="mx-auto max-w-6xl px-4 sm:px-6 py-4 sm:py-5 flex items-center justify-between gap-3">
                    <a href="/" class="flex items-center gap-2 sm:gap-2.5 shrink-0">
                        <span class="flex h-8 w-8 sm:h-9 sm:w-9 items-center justify-center rounded-lg bg-emerald-700 text-white shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="h-4 w-4 sm:h-5 sm:w-5">
                                <path d="M7 19l-3-3 3-3M17 5l3 3-3 3M4 16h6a2 2 0 0 0 2-2V9M20 8h-6a2 2 0 0 0-2 2v8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <span class="font-semibold text-slate-900 tracking-tight text-sm sm:text-base whitespace-nowrap">Bank Sampah Digital</span>
                    </a>

                    @if (Route::has('admin.login'))
                        <nav class="flex items-center gap-1.5 sm:gap-2 shrink-0">
                            @auth
                                <a href="{{ url('/dashboard') }}"
                                   class="inline-flex items-center rounded-lg bg-emerald-700 px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm font-medium text-white shadow-sm transition hover:bg-emerald-800 whitespace-nowrap">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('admin.login') }}"
                                   class="inline-flex items-center rounded-lg px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm font-medium text-slate-700 transition hover:bg-slate-100 whitespace-nowrap">
                                    Masuk
                                </a>
                                @if (Route::has('admin.register'))
                                    <a href="{{ route('admin.register') }}"
                                       class="inline-flex items-center rounded-lg bg-emerald-700 px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm font-medium text-white shadow-sm transition hover:bg-emerald-800 whitespace-nowrap">
                                        Daftar
                                    </a>
                                @endif
                            @endauth
                        </nav>
                    @endif
                </div>
            </header>

            <!-- Hero -->
            <main class="flex-1">
                <section class="mx-auto max-w-6xl px-6 pt-14 pb-20 lg:pt-20 lg:pb-28">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-14 items-center">
                        <div class="min-w-0">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-600"></span>
                                Mengelola sampah, menabung nilai
                            </span>

                            <h1 class="mt-5 text-4xl sm:text-5xl font-semibold tracking-tight text-slate-900 leading-[1.1]">
                                Sistem Bank Sampah
                                <span class="text-emerald-700">yang lebih transparan.</span>
                            </h1>

                            <p class="mt-5 text-base sm:text-lg text-slate-600 leading-relaxed max-w-xl">
                                Catat setoran sampah, pantau saldo tabungan, dan ajukan penarikan — semuanya
                                tercatat rapi dan bisa diakses kapan saja, baik oleh nasabah, petugas, maupun pengelola.
                            </p>

                            <div class="mt-8 flex flex-wrap items-center gap-3">
                                @auth
                                    <a href="{{ url('/dashboard') }}"
                                       class="inline-flex items-center rounded-lg bg-emerald-700 px-5 py-3 text-sm font-medium text-white shadow-sm transition hover:bg-emerald-800">
                                        Buka Dashboard
                                    </a>
                                @else
                                    <a href="{{ route('admin.login') }}"
                                       class="inline-flex items-center rounded-lg bg-emerald-700 px-5 py-3 text-sm font-medium text-white shadow-sm transition hover:bg-emerald-800">
                                        Masuk ke Dashboard
                                    </a>
                                    <a href="{{ route('admin.register') }}"
                                       class="inline-flex items-center rounded-lg px-5 py-3 text-sm font-medium text-slate-700 ring-1 ring-inset ring-slate-200 transition hover:bg-slate-50">
                                        Daftar sebagai Nasabah
                                    </a>
                                @endauth
                            </div>

                            <div class="mt-10 flex items-center gap-8 text-sm text-slate-500">
                                <div>
                                    <p class="text-2xl font-semibold text-slate-900">100%</p>
                                    <p>Tercatat digital</p>
                                </div>
                                <div class="h-8 w-px bg-slate-200"></div>
                                <div>
                                    <p class="text-2xl font-semibold text-slate-900">&lt; 2 detik</p>
                                    <p>Waktu respons transaksi</p>
                                </div>
                            </div>
                        </div>

                        <!-- Illustration card -->
                        <div class="relative min-w-0">
                            <div class="absolute -inset-4 rounded-3xl bg-gradient-to-br from-emerald-100 to-emerald-50 blur-2xl"></div>
                            <div class="relative rounded-2xl border border-slate-200 bg-white p-6 shadow-xl shadow-slate-200/50">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-slate-500">Saldo Tabungan</p>
                                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-50 text-emerald-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="h-4 w-4">
                                            <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                        </svg>
                                    </span>
                                </div>
                                <p class="mt-2 text-3xl font-semibold text-slate-900">Rp 128.500</p>
                                <p class="mt-1 text-sm text-emerald-600">+Rp 12.500 dari setoran terakhir</p>

                                <div class="mt-6 space-y-3">
                                    <div class="flex items-center justify-between rounded-lg bg-slate-50 px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="h-4 w-4"><path d="M12 19V5M5 12l7-7 7 7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                            </span>
                                            <div>
                                                <p class="text-sm font-medium text-slate-800">Plastik PET (Botol)</p>
                                                <p class="text-xs text-slate-500">5 kg &middot; hari ini</p>
                                            </div>
                                        </div>
                                        <p class="text-sm font-medium text-emerald-700">+Rp 12.500</p>
                                    </div>
                                    <div class="flex items-center justify-between rounded-lg bg-slate-50 px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-200 text-slate-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="h-4 w-4"><path d="M12 5v14M5 12l7 7 7-7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                            </span>
                                            <div>
                                                <p class="text-sm font-medium text-slate-800">Penarikan Saldo</p>
                                                <p class="text-xs text-slate-500">Menunggu diproses</p>
                                            </div>
                                        </div>
                                        <p class="text-sm font-medium text-slate-500">Rp 50.000</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Features -->
                <section class="border-t border-slate-100 bg-slate-50/60">
                    <div class="mx-auto max-w-6xl px-6 py-16 lg:py-20">
                        <div class="max-w-2xl">
                            <h2 class="text-2xl sm:text-3xl font-semibold tracking-tight text-slate-900">
                                Semua yang dibutuhkan, dalam satu sistem
                            </h2>
                            <p class="mt-3 text-slate-600">
                                Dari input setoran di lapangan sampai laporan bulanan untuk pengelola.
                            </p>
                        </div>

                        <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                            @php
                                $features = [
                                    ['icon' => 'M12 19V5M5 12l7-7 7 7', 'title' => 'Input Setoran', 'desc' => 'Petugas mencatat setoran sampah dan nilainya otomatis terhitung ke saldo nasabah.'],
                                    ['icon' => 'M3 3v18h18M7 15l4-4 3 3 5-6', 'title' => 'Pantau Saldo', 'desc' => 'Nasabah dapat memantau saldo tabungan dan riwayat transaksi kapan saja.'],
                                    ['icon' => 'M12 5v14M5 12l7 7 7-7', 'title' => 'Ajukan Penarikan', 'desc' => 'Pengajuan penarikan saldo diverifikasi petugas sebelum saldo dicairkan.'],
                                    ['icon' => 'M4 6h16M4 12h16M4 18h7', 'title' => 'Laporan & Kategori', 'desc' => 'Kelola kategori harga sampah dan lihat laporan periodik dengan mudah.'],
                                ];
                            @endphp

                            @foreach ($features as $feature)
                                <div class="rounded-xl border border-slate-200 bg-white p-5 transition hover:shadow-md hover:shadow-slate-200/60">
                                    <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-50 text-emerald-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="h-5 w-5">
                                            <path d="{{ $feature['icon'] }}" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </span>
                                    <h3 class="mt-4 font-medium text-slate-900">{{ $feature['title'] }}</h3>
                                    <p class="mt-1.5 text-sm text-slate-600 leading-relaxed">{{ $feature['desc'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>
            </main>

            <!-- Footer -->
            <footer class="border-t border-slate-100">
                <div class="mx-auto max-w-6xl px-6 py-8 flex flex-col sm:flex-row items-center justify-between gap-3 text-sm text-slate-500">
                    <p>&copy; {{ date('Y') }} Bank Sampah Digital. Semua hak dilindungi.</p>
                    <p>Dibangun dengan Laravel</p>
                </div>
            </footer>
        </div>
    </body>
</html>
