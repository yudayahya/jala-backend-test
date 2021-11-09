<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">JALA</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ $title == 'Dashboard' ? 'active' : '' }}">
        <a class="nav-link" href="/">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Tabel -->
    <li class="nav-item {{ $title == 'Product' ? 'active' : '' }}">
        <a class="nav-link" href="/product">
            <i class="fas fa-fw fa-clipboard-list"></i>
            <span>Product</span></a>
    </li>
    @role('Super Admin')
        <li class="nav-item {{ $title == 'Purchase Order' || $title == 'Riwayat Purchase Order' ? 'active' : '' }}">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePurchase"
                aria-expanded="true" aria-controls="collapsePurchase">
                <i class="fas fa-fw fa-truck-loading"></i>
                <span>Purchase Order</span>
            </a>
            <div id="collapsePurchase"
                class="collapse {{ $title == 'Purchase Order' || $title == 'Riwayat Purchase Order' ? 'show' : '' }}"
                aria-labelledby="headingPurchase" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item {{ $title == 'Purchase Order' ? 'active' : '' }}" href="/purchase">Purchase
                        Order</a>
                    <a class="collapse-item {{ $title == 'Riwayat Purchase Order' ? 'active' : '' }}"
                        href="/purchase/history">Riwayat</a>
                </div>
            </div>
        </li>
        <li class="nav-item {{ $title == 'Pending Sale Order' ? 'active' : '' }}">
            <a class="nav-link" href="/sale/pending">
                <i class="fas fa-fw fa-hand-holding-usd"></i>
                <span>Pending Sale Order <span id="pending-count"></span></span></a>
        </li>
    @endrole
    <li class="nav-item {{ $title == 'Sale Order' || $title == 'Riwayat Sale Order' ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSale" aria-expanded="true"
            aria-controls="collapseSale">
            <i class="fas fa-fw fa-receipt"></i>
            <span>Sale Order</span>
        </a>
        <div id="collapseSale"
            class="collapse {{ $title == 'Sale Order' || $title == 'Riwayat Sale Order' ? 'show' : '' }}"
            aria-labelledby="headingSale" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ $title == 'Sale Order' ? 'active' : '' }}" href="/sale">
                    @role('Super Admin')
                        Sale Order
                    @else
                        Pending Sale Order
                    @endrole
                </a>
                <a class="collapse-item {{ $title == 'Riwayat Sale Order' ? 'active' : '' }}"
                    href="/sale/history">Riwayat</a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
