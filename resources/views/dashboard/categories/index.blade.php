<x-dashboard-layout>
    <x-slot:content>
        <div class="container-fluid py-4">
            <div class="row my-4">
                <div class="col-lg-12 col-md-6 mb-md-0 mb-4">
                    <div class="card">
                        <div class="card-header pb-0">

                            {{-- Menampilkan alert jika ada session yang dikirim --}}
                            <x-flash-message></x-flash-message>

                            <div class="row align-items-center mb-3">
                                <div class="col-lg-6 col-7">
                                    <h6 class="mb-0">Categories</h6>
                                </div>
                                <div class="col-lg-6 col-5 my-auto text-end">
                                    <a id="create-btn" href="/dashboard/categories/create" class="btn btn-primary d-flex ms-auto mb-0">
                                        <i class="ri-add-large-line"></i>
                                        <span class="ps-1">Create New</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                @include("dashboard.categories.contents.table")
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <x-dashboard-footer></x-dashboard-footer>
        </div>
    </x-slot:content>
</x-dashboard-layout>
