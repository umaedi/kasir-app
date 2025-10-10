<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    {{-- <div class="app-brand demo">
        <a href="#" class="app-brand-link">
            <img src="{{ asset('assets/images/logo.png') }}" alt="logo" width="50">
            <span class="app-brand-text demo menu-text fw-bolder ms-2" style="color: #2563eb">Samudra</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div> --}}

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-2">
        <!-- Dashboard -->
        <li class="menu-item {{ Request::is('cms/dashboard') ? 'active' : '' }}">
            <a href="{{ route('cms.dashbord') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-building-house"></i>
                <div data-i18n="Analytics">Dashboard</div>
            </a>
        </li>
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">{{ __('cms.finance') }}</span>
        </li>
        <li class="menu-item {{ Request::is('cms/reports/sales-by-product') ? 'active' : '' }}">
            <a href="{{ route('cms.salesByProduct') }}" class="menu-link">
                <i class="menu-icon icon-base bx bx-cart"></i>
                <div data-i18n="Basic">{{ __('cms.sales_sidebar') }}</div>
            </a>
        </li>
        <li class="menu-item {{ Request::is('cms/finance') ? 'active' : '' }}">
            <a href="{{ route('cms.finance') }}" class="menu-link">
                <i class="menu-icon icon-base bx bx-receipt"></i>
                <div data-i18n="Basic">Laporan keuangan</div>
            </a>
        </li>
    </ul>
</aside>
