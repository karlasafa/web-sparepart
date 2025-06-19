<div class="row justify-content-center mt-3">
    @foreach ($products as $product)
        <div class="col-6 col-lg-4">
            <div class="card mb-4">
                <img src='{{ asset("storage/$product->image") }}' class="card-img-top" alt="{{ $product->title }}">
                <div class="card-body">
                    <h5 class="card-title text-truncate">{{ $product->title }}</h5>
                    <p class="card-text">{{ rupiah($product->price) }}</p>
                    <div class="d-flex gap-2">
                        @auth
                            <button type="button" class="btn btn-primary addToCartBtn" data-id="{{ $product->id }}">
                                <i class="ri-shopping-cart-line pe-1"></i> Add to cart
                            </button>
                        @endauth
                        @guest
                            <a href="login" class="btn btn-primary">
                                <i class="ri-shopping-cart-line pe-1"></i> Add to cart
                            </a>
                        @endguest

                        <a href='{{ url("product/$product->id") }}' class="btn btn-outline-secondary">
                            <i class="ri-information-2-line pe-1"></i> Detail
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
