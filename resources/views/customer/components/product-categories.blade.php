<section class="my-5" id="categories">
    <h2 class="text-center">Product Categories</h2>
    <div class="row justify-content-center gap-2 mt-3">
        @foreach ($categories as $category)
            <a href='{{ url("category/$category->id") }}' class="btn {{ $urlCategory ? $urlCategory == $category->id ? 'btn-primary' : 'btn-secondary' : 'btn-secondary' }}">
                {{ $category->title }}
            </a>
        @endforeach
    </div>
</section>
