<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3"
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="/dashboard">
            <img src="{{ asset('img/icon_univ_bsi.png') }}" class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-1 font-weight-bold">Project Toko Online</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse w-auto max-height-vh-100" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link {{ setActiveLink('dashboard', false) }}" href="/dashboard">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ri ri-bar-chart-line"></i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>

            @if (Auth::user()->role === "SuperAdmin")
                <li class="nav-item">
                    <a class="nav-link {{ setActiveLink('dashboard/orders') }}" href="/dashboard/orders">
                        <div
                            class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ri ri-file-list-3-line"></i>
                        </div>
                        <span class="nav-link-text ms-1">Orders</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ setActiveLink('dashboard/users') }}" href="/dashboard/users">
                        <div
                            class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ri ri-group-line"></i>
                        </div>
                        <span class="nav-link-text ms-1">Users</span>
                    </a>
                </li>
            @endif

            <li class="nav-item">
                <a class="nav-link {{ setActiveLink('dashboard/categories') }}" href="/dashboard/categories">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ri ri-archive-2-line"></i>
                    </div>
                    <span class="nav-link-text ms-1">Categories</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ setActiveLink('dashboard/products') }}" href="/dashboard/products">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ri ri-shopping-bag-line"></i>
                    </div>
                    <span class="nav-link-text ms-1">Products</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ setActiveLink('dashboard/product-images') }}" href="/dashboard/product-images">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ri ri-folder-image-line"></i>
                    </div>
                    <span class="nav-link-text ms-1">Product Images</span>
                </a>
            </li>
        </ul>
    </div>
</aside>
