<table class="table align-items-center mb-0">
    <thead>
        <tr>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                Product Name / ID
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
        @foreach ($productImages as $productImage)
            <tr>
                <td>
                    <div class="d-flex px-2 py-1">
                        <div>
                            <img src='{{ asset("storage/$productImage->source") }}' class="avatar avatar-sm me-3 glightbox" alt="">
                        </div>
                        <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">{{ $productImage->product->title }}</h6>
                            <p class="text-xs text-secondary mb-0">{{ $productImage->product->id }}</p>
                        </div>
                    </div>
                </td>
                <td class="align-middle text-center">
                    <span class="text-secondary text-xs font-weight-bold">{{ $productImage->created_at }}</span>
                </td>
                <td class="align-middle text-center">
                    <span class="text-secondary text-xs font-weight-bold">{{ $productImage->updated_at }}</span>
                </td>
                <td class="align-middle">
                    <div class="d-flex align-items-center gap-2">
                        <a href="/dashboard/product-images/{{ $productImage->id }}/edit" class="badge text-dark bg-warning">
                            <i class="ri-edit-line"></i>
                            <span class="ps-1">Edit</span>
                        </a>
                        <form action="/dashboard/product-images/{{ $productImage->id }}" method="POST">
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
