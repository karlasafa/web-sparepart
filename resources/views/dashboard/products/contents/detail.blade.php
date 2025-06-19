<div class="modal fade" id="productDetailModal" tabindex="-1" aria-labelledby="productDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productDetailModalLabel">Product Detail</h5>
                <button type="button" class="border-0 bg-transparent text-lg" data-bs-dismiss="modal">
                    <i class="ri-close-line"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="card bg-transparent shadow-none">
                    <img class="card-img-top rounded-0" alt="Gambar Produk" id="productImage">
                    <div class="card-body py-3 px-0">
                        <h5 class="card-title" id="productTitle">Title</h5>
                        <p class="card-text" id="productDescription">Description</p>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0">
                            <strong>Price:</strong>
                            <span id="productPrice"></span>
                        </li>
                        <li class="list-group-item px-0">
                            <strong>Weight:</strong>
                            <span id="productWeight"></span>
                        </li>
                        <li class="list-group-item px-0">
                            <strong>Stock:</strong>
                            <span id="productStock"></span>
                        </li>
                        <li class="list-group-item px-0">
                            <strong>Status:</strong>
                            <span id="productStatus"></span>
                        </li>
                    </ul>
                    <div class="row flex-wrap justify-content-center g-2 mt-3" id="productImages"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
