<table class="table align-items-center mb-0">
    <thead>
        <tr>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                Title
            </th>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                Created at
            </th>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                Updated at
            </th>
            <th class="text-secondary opacity-7"></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($categories as $category)
        <tr>
            <td class="align-middle">
                <span class="text-secondary text-xs font-weight-bold">{{ $category->title }}</span>
            </td>
            <td class="align-middle">
                <span class="text-secondary text-xs font-weight-bold">{{ $category->created_at }}</span>
            </td>
            <td class="align-middle">
                <span class="text-secondary text-xs font-weight-bold">{{ $category->updated_at }}</span>
            </td>
            <td class="align-middle">
                <div class="d-flex align-items-center gap-2">
                    <a href="/dashboard/categories/{{ $category->id }}/edit" class="badge text-dark bg-warning">
                        <i class="ri-edit-line"></i>
                        <span class="ps-1">Edit</span>
                    </a>
                    <form action="/dashboard/categories/{{ $category->id }}" method="POST">
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
