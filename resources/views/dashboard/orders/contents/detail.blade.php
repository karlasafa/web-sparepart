<div class="modal fade" id="orderDetailModal" tabindex="-1" aria-labelledby="orderDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderDetailModalLabel">Order Detail</h5>
                <button type="button" class="border-0 bg-transparent text-lg" data-bs-dismiss="modal">
                    <i class="ri-close-line"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="card bg-transparent shadow-none">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0">
                            <strong>Order ID:</strong>
                            <span id="orderId"></span>
                        </li>
                        <li class="list-group-item px-0">
                            <strong>Customer ID:</strong>
                            <span id="customerId"></span>
                        </li>
                        <li class="list-group-item px-0">
                            <strong>Customer Address:</strong>
                            <span id="customerAddress"></span>
                        </li>
                        <li class="list-group-item px-0">
                            <strong>Payment Total:</strong>
                            <span id="paymentTotal"></span>
                        </li>
                        <li class="list-group-item px-0">
                            <strong>Payment Method:</strong>
                            <span id="paymentMethod"></span>
                        </li>
                        <li class="list-group-item px-0 border-bottom">
                            <strong>Payment Status:</strong>
                            <span id="paymentStatus"></span>
                        </li>
                    </ul>

                    <strong class="my-2">Products:</strong>
                    <div class="row g-1" id="products"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
