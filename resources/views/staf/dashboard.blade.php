<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Staf
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p class="font-medium">Selamat datang, {{ auth()->user()->name }}.</p>

                    <p class="mt-2 text-sm text-gray-600">
                        Anda masuk sebagai staf Staf Batalyon.
                    </p>

                    <p class="mt-4 text-sm text-gray-600">
                        Halaman ini masih tahap awal. Jadwal piket gerbang dan info dasar
                        akan ditambahkan di Fase 7.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
