<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
        <li class="breadcrumb-item text-sm">
            <a class="opacity-5 text-dark" href="{{ $currentSegment !== 'dashboard' ? '/dashboard' : '#' }}">Dashboard</a>
        </li>

        @if ($currentSegment !== 'dashboard')
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">
                {{ Str::title($currentSegment) }}
            </li>
        @endif
    </ol>
</nav>
