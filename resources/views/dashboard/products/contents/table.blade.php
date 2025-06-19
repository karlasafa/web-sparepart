<table class="table align-items-center mb-0">
    <thead>
        <tr>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                Name / Category
            </th>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                Price
            </th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                Status
            </th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                Stock
            </th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                Created at
            </th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                Updated at
            </th>
            <th class="text-secondary opacity-7"></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $product)
            <tr>
                <td>
                    <div class="d-flex px-2 py-1">
                        <div>
                            <img src='{{ asset("storage/$product->image") }}' class="avatar avatar-sm me-3 glightbox">
                        </div>
                        <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">{{ $product->title }}</h6>
                            <p class="text-xs text-secondary mb-0">{{ $product->category->title }}</p>
                        </div>
                    </div>
                </td>
                <td>
                    <span class="text-xs font-weight-bold mb-0">{{ rupiah($product->price) }}</span>
                </td>
                <td class="align-middle text-center text-sm">
                    <span class="badge badge-sm {{ $product->status ? 'bg-gradient-success' : 'bg-gradient-secondary' }}">
                        {{ $product->status ? 'Published' : 'Blocked' }}
                    </span>
                </td>
                <td class="align-middle text-center">
                    <span class="text-secondary text-xs font-weight-bold">{{ $product->stock }}</span>
                </td>
                <td class="align-middle text-center">
                    <span class="text-secondary text-xs font-weight-bold">{{ $product->created_at }}</span>
                </td>
                <td class="align-middle text-center">
                    <span class="text-secondary text-xs font-weight-bold">{{ $product->updated_at }}</span>
                </td>
                <td class="align-middle">
                    <div class="d-flex align-items-center gap-2">
                        <button class="btn-detail border-0 badge bg-info" data-id={{ $product->id }} type="button" data-bs-toggle="modal" data-bs-target="#productDetailModal">
                            <i class="ri-eye-line" style="pointer-events: none"></i>
                            <span class="ps-1" style="pointer-events: none">Detail</span>
                        </button>
                        <a href="/dashboard/products/{{ $product->id }}/edit" class="badge text-dark bg-warning">
                            <i class="ri-edit-line"></i>
                            <span class="ps-1">Edit</span>
                        </a>
                        <form action="/dashboard/products/{{ $product->id }}" method="POST">
                            @method('DELETE')
                            @csrf
                            <button type="submit" class="border-0 badge text-white bg-danger" onclick="return confirm('Are you sure?')">
                                <i class="ri-delete-bin-3-line"></i>
                                <span class="ps-1">Delete</span>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
