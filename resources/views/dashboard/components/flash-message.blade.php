<div class="{{ $class }}">
    @if (session('alert-success'))
        <div id="flash-message" class="alert alert-success text-white">{{ session('alert-success') }}</div>
    @elseif (session('alert-error'))
        <div id="flash-message" class="alert alert-danger text-white">{{ session('alert-error') }}</div>
    @endif
</div>
