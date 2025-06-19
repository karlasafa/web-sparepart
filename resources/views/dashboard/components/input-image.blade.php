<label for="picture" class="form-label">{{ Str::ucfirst($name) }}</label>
@if ($previous)
    <img id="prev-image" src="{{ asset('storage/' . $previous) }}" alt="Image Preview"/>
@elseif (session('prev-image'))
    <img id="prev-image" src="{{ asset('storage/' . session('prev-image')) }}" alt="Image Preview"/>
@endif

<img id="preview" alt="Image Preview"/>
<input type="file" class="form-control @error($name) is-invalid @enderror" id="input-image" name="{{ $name }}" onchange="handlePreview(event)">

@error($name)
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
