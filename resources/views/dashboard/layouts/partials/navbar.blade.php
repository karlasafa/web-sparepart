<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur"
    navbar-scroll="true">
    <div class="container-fluid py-1 px-3">
        <x-breadcrumb></x-breadcrumb>
        <div class="collapse navbar-collapse justify-content-end mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
            <ul class="navbar-nav justify-content-end gap-4">
                <li class="nav-item d-flex align-items-center">
                    <a href="/dashboard/profile" class="nav-link text-body p-0">
                        <i class="ri-user-line cursor-pointer me-sm-1"></i>
                        <span class="d-sm-inline d-none">Profile</span>
                    </a>
                </li>

                <li class="nav-item d-flex align-items-center">
                    <form action="/logout" method="POST">
                        @csrf
                        <button class="nav-link text-body font-weight-bold border-0 bg-transparent px-0" type="submit">
                            <i class="ri-logout-box-r-line me-sm-1"></i>
                            <span class="d-sm-inline d-none">Sign Out</span>
                        </button>
                    </form>
                </li>
                <li class="nav-item d-xl-none d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                        <div class="sidenav-toggler-inner">
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
