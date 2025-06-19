<table class="table align-items-center mb-0">
    <thead>
        <tr>
            <th
                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                Name / Email</th>
            <th
                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                Role</th>
            <th
                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                Status</th>
                <th
                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                Phone</th>
            <th
                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                Created at</th>
            <th
                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                Updated at</th>
            <th class="text-secondary opacity-7"></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
        <tr>
            <td>
                <div class="d-flex px-2 py-1">
                    <div>
                        <img src='{{ asset("storage/$user->picture") }}' class="avatar avatar-sm me-3 glightbox" alt="">
                    </div>
                    <div class="d-flex flex-column justify-content-center">
                        <h6 class="mb-0 text-sm">{{ $user->name }}</h6>
                        <p class="text-xs text-secondary mb-0">{{ $user->email }}</p>
                    </div>
                </div>
            </td>
            <td>
                <p class="text-xs font-weight-bold mb-0">{{ $user->role }}</p>
            </td>
            <td class="align-middle text-center text-sm">
                <span class="badge badge-sm {{ $user->status ? 'bg-gradient-success' : 'bg-gradient-secondary' }}">
                    {{ $user->status ? 'Active' : 'Not Active' }}
                </span>
            </td>
            <td class="align-middle text-center">
                <span class="text-secondary text-xs font-weight-bold">{{ $user->phone }}</span>
            </td>
            <td class="align-middle text-center">
                <span class="text-secondary text-xs font-weight-bold">{{ $user->created_at }}</span>
            </td>
            <td class="align-middle text-center">
                <span class="text-secondary text-xs font-weight-bold">{{ $user->updated_at }}</span>
            </td>
            <td class="align-middle">
                <div class="d-flex align-items-center gap-2">
                    <a href="/dashboard/users/{{ $user->id }}/edit" class="badge text-dark bg-warning">
                        <i class="ri-edit-line"></i>
                        <span class="ps-1">Edit</span>
                    </a>
                    <form action="/dashboard/users/{{ $user->id }}" method="POST">
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
