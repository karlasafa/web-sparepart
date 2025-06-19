<x-dashboard-layout>
    <x-slot:content>
        <div class="container-fluid py-4">
            <div class="row my-4">
                <div class="col-lg-12 col-md-6 mb-md-0 mb-4">
                    <div class="alert alert-primary">
                        <h3 class="text-light text-bold">Selamat datang. {{ Auth::user()->name }}</h3>
                        <p class="text-light">Aplikasi Toko Online dengan hak akses yang anda miliki sebagai
                            <b>{{ Auth::user()->role }}</b> ini adalah halaman dashboard dari aplikasi ini</p>
                        <hr class="bg-light">
                        <p class="text-light mb-0">Kuliah..? BSI Aja !!!</p>
                    </div>
                </div>
            </div>
            <x-dashboard-footer></x-dashboard-footer>
        </div>
    </x-slot:content>
</x-dashboard-layout>
