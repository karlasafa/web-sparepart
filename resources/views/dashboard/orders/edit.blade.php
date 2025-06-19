<x-dashboard-layout>
    <x-slot:content>
        <div class="container-fluid py-4">
            <div class="row my-4">

                {{-- Input untuk menyimpan lokasi redirect setelah muncul alert --}}
                <input type="hidden" id="index-page" value="{{ url('dashboard/orders') }}">

                {{-- Menampilkan alert jika ada session yang dikirim --}}
                <x-flash-message class="px-3"></x-flash-message>

                <div class="col-lg-12 col-md-6 mb-md-0 mb-4">
                    <div class="card">
                        <div class="card-header pb-2">
                            <h6>Edit Order</h6>
                        </div>
                        <form class="card-body px-4 pt-0 pb-2" action="/dashboard/orders/{{ $order->id }}" method="POST">
                            @method('PUT')
                            @csrf
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                @foreach ($statuses as $status)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="status-{{ $status }}" value="{{ $status }}" {{ $status === $order->status ? 'checked' : '' }}>
                                        <label class="form-check-label text-capitalize" for="status-{{ $status }}">
                                            {{ $status }}
                                        </label>
                                    </div>
                                @endforeach
                                @error('status')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary" {{ session('alert-success') ? 'disabled' : '' }}>
                                    <i class="ri-save-line"></i>
                                    <span class="ps-1">Update</span>
                                </button>
                                <a href="/dashboard/orders" class="btn btn-dark">
                                    <i class="ri-arrow-left-line"></i>
                                    <span class="ps-1">Back</span>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <x-dashboard-footer></x-dashboard-footer>
        </div>
    </x-slot:content>
</x-dashboard-layout>
