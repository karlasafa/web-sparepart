<table class="table align-items-center mb-0">
    <thead>
        <tr>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                Order ID
            </th>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                Customer ID
            </th>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                Total
            </th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                Payment Method
            </th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                Status
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
        @foreach ($orders as $order)
            <tr>
                <td>
                    <span class="text-xs font-weight-bold mb-0">{{ $order->id }}</span>
                </td>
                <td>
                    <span class="text-xs font-weight-bold mb-0">{{ $order->customer }}</span>
                </td>
                <td>
                    <span class="text-xs font-weight-bold mb-0">{{ rupiah($order->total) }}</span>
                </td>
                <td class="align-middle text-center">
                    <span class="text-secondary text-xs font-weight-bold">{{ $order->payment_method }}</span>
                </td>
                <td class="align-middle text-center text-sm">
                    @if ($order->status === "success")
                        <span class="badge badge-sm bg-gradient-success">{{ $order->status }}</span>
                    @elseif($order->status === "pending")
                        <span class="badge badge-sm bg-gradient-secondary">{{ $order->status }}</span>
                    @else
                        <span class="badge badge-sm bg-gradient-danger">{{ $order->status }}</span>
                    @endif
                </td>
                <td class="align-middle text-center">
                    <span class="text-secondary text-xs font-weight-bold">{{ $order->created_at }}</span>
                </td>
                <td class="align-middle text-center">
                    <span class="text-secondary text-xs font-weight-bold">{{ $order->updated_at }}</span>
                </td>
                <td class="align-middle">
                    <div class="d-flex align-items-center gap-2">
                        <button class="btn-detail border-0 badge bg-info" data-id={{ $order->id }} type="button" data-bs-toggle="modal" data-bs-target="#orderDetailModal">
                            <i class="ri-eye-line" style="pointer-events: none"></i>
                            <span class="ps-1" style="pointer-events: none">Detail</span>
                        </button>
                        <a href='{{ url("dashboard/orders/$order->id/edit") }}' class="badge text-dark bg-warning">
                            <i class="ri-edit-line"></i>
                            <span class="ps-1">Edit</span>
                        </a>
                        <form action='{{ url("dashboard/orders/$order->id") }}' method="POST">
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
