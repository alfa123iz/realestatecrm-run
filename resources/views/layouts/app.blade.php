<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Kisan Land Management System' }}</title>
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @stack('styles')
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
<div class="app-wrapper">
    <nav class="app-header navbar navbar-expand bg-body">
        <div class="container-fluid">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                        <i class="bi bi-list"></i>
                    </a>
                </li>
                <li class="nav-item d-none d-md-block">
                    <a href="{{ route('dashboard') }}" class="nav-link">Kisan Land Management System</a>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard') }}" title="Dashboard">
                        <i class="bi bi-speedometer2"></i>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <div class="sidebar-brand">
            <a href="{{ route('dashboard') }}" class="brand-link text-decoration-none">
                <span class="brand-text fw-semibold">Kisan LMS</span>
            </a>
        </div>

        <div class="sidebar-wrapper">
            <nav class="mt-2">
                <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation" aria-label="Main navigation">
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-speedometer2"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('kisans.*') || request()->routeIs('kisan-bonds.*') || request()->routeIs('investors.*') || request()->routeIs('registries.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('kisans.*') || request()->routeIs('kisan-bonds.*') || request()->routeIs('investors.*') || request()->routeIs('registries.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-person-lines-fill"></i>
                            <p>
                                Kishan
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item"><a href="{{ route('kisans.index') }}" class="nav-link {{ request()->routeIs('kisans.index') ? 'active' : '' }}"><i class="nav-icon bi bi-circle"></i><p>Kishan Entry</p></a></li>
                            <li class="nav-item"><a href="{{ route('customer-bond-payments.create') }}" class="nav-link {{ request()->routeIs('customer-bond-payments.create') ? 'active' : '' }}"><i class="nav-icon bi bi-circle"></i><p>Cheque Entry form</p></a></li>
                            <li class="nav-item {{ request()->routeIs('investors.*') || request()->routeIs('kisan-bonds.*') || request()->routeIs('customer-bond-payments.*') || request()->routeIs('payments.*') ? 'menu-open' : '' }}">
                                <a href="#" class="nav-link {{ request()->routeIs('investors.*') || request()->routeIs('kisan-bonds.*') || request()->routeIs('customer-bond-payments.*') || request()->routeIs('payments.*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>
                                        Invester(%)
                                        <i class="nav-arrow bi bi-chevron-right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item"><a href="{{ route('kisan-bonds.index') }}" class="nav-link {{ request()->routeIs('kisan-bonds.*') ? 'active' : '' }}"><i class="nav-icon bi bi-dot"></i><p>Invester Bond</p></a></li>
                                    <li class="nav-item"><a href="{{ route('customer-bond-payments.index') }}" class="nav-link {{ request()->routeIs('customer-bond-payments.*') ? 'active' : '' }}"><i class="nav-icon bi bi-dot"></i><p>Invester Recipt</p></a></li>
                                    <li class="nav-item"><a href="{{ route('payments.index') }}" class="nav-link {{ request()->routeIs('payments.*') ? 'active' : '' }}"><i class="nav-icon bi bi-dot"></i><p>Invester Payment Details</p></a></li>
                                </ul>
                            </li>
                            <li class="nav-item"><a href="{{ route('kisan-bonds.index') }}" class="nav-link {{ request()->routeIs('kisan-bonds.index') ? 'active' : '' }}"><i class="nav-icon bi bi-circle"></i><p>Kishan Bond</p></a></li>
                            <li class="nav-item"><a href="{{ route('kisan-bonds.create') }}" class="nav-link {{ request()->routeIs('kisan-bonds.create') ? 'active' : '' }}"><i class="nav-icon bi bi-circle"></i><p>Kishan Bond UPDATE</p></a></li>
                            <li class="nav-item"><a href="{{ route('customer-bond-payments.index') }}" class="nav-link {{ request()->routeIs('customer-bond-payments.index') ? 'active' : '' }}"><i class="nav-icon bi bi-circle"></i><p>Kishan Recipt Update</p></a></li>
                            <li class="nav-item"><a href="{{ route('payments.create') }}" class="nav-link {{ request()->routeIs('payments.create') ? 'active' : '' }}"><i class="nav-icon bi bi-circle"></i><p>Kishan Payment</p></a></li>
                            <li class="nav-item"><a href="{{ route('investors.index') }}" class="nav-link {{ request()->routeIs('investors.index') ? 'active' : '' }}"><i class="nav-icon bi bi-circle"></i><p>Invester Entry</p></a></li>
                            <li class="nav-item"><a href="{{ route('registries.index') }}" class="nav-link {{ request()->routeIs('registries.*') ? 'active' : '' }}"><i class="nav-icon bi bi-circle"></i><p>Arazi Bond</p></a></li>
                            <li class="nav-item"><a href="{{ route('registries.create') }}" class="nav-link {{ request()->routeIs('registries.create') ? 'active' : '' }}"><i class="nav-icon bi bi-circle"></i><p>Arazi Bond Entry</p></a></li>
                            <li class="nav-item"><a href="{{ route('kisans.create') }}" class="nav-link {{ request()->routeIs('kisans.create') ? 'active' : '' }}"><i class="nav-icon bi bi-circle"></i><p>ADD kishan for mention payment</p></a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a href="{{ route('arazis.index') }}" class="nav-link {{ request()->routeIs('arazis.*') ? 'active' : '' }}"><i class="nav-icon bi bi-map"></i><p>Arazi</p></a></li>
                    <li class="nav-item {{ request()->routeIs('payments.*') || request()->routeIs('customer-bond-payments.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('payments.*') || request()->routeIs('customer-bond-payments.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-receipt"></i>
                            <p>
                                Reciept
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item {{ request()->routeIs('customer-bond-payments.create') || request()->routeIs('payments.create') || request()->routeIs('payments.index') ? 'menu-open' : '' }}">
                                <a href="#" class="nav-link {{ request()->routeIs('customer-bond-payments.create') || request()->routeIs('payments.create') || request()->routeIs('payments.index') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-dot"></i>
                                    <p>
                                        Recipt Entry
                                        <i class="nav-arrow bi bi-chevron-right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item"><a href="{{ route('customer-bond-payments.create') }}" class="nav-link {{ request()->routeIs('customer-bond-payments.create') ? 'active' : '' }}"><i class="nav-icon bi bi-circle"></i><p>Recipt Entry</p></a></li>
                                    <li class="nav-item"><a href="{{ route('payments.create') }}" class="nav-link {{ request()->routeIs('payments.create') ? 'active' : '' }}"><i class="nav-icon bi bi-circle"></i><p>Extra Payment</p></a></li>
                                    <li class="nav-item"><a href="{{ route('payments.index') }}" class="nav-link {{ request()->routeIs('payments.index') ? 'active' : '' }}"><i class="nav-icon bi bi-circle"></i><p>EMI DETAILS</p></a></li>
                                </ul>
                            </li>
                            <li class="nav-item"><a href="{{ route('customer-bond-payments.index') }}" class="nav-link {{ request()->routeIs('customer-bond-payments.index') ? 'active' : '' }}"><i class="nav-icon bi bi-dot"></i><p>Recipt Edit or Delete</p></a></li>
                            <li class="nav-item"><a href="{{ route('payments.print') }}" class="nav-link {{ request()->routeIs('payments.print') ? 'active' : '' }}"><i class="nav-icon bi bi-dot"></i><p>Print Recipt</p></a></li>
                            <li class="nav-item"><a href="{{ route('payments.index') }}" class="nav-link {{ request()->routeIs('payments.index') ? 'active' : '' }}"><i class="nav-icon bi bi-dot"></i><p>Delete Recipt</p></a></li>
                            <li class="nav-item"><a href="{{ route('customer-bond-payments.index') }}" class="nav-link {{ request()->routeIs('customer-bond-payments.index') ? 'active' : '' }}"><i class="nav-icon bi bi-dot"></i><p>Cheque Bounce</p></a></li>
                            <li class="nav-item"><a href="{{ route('payments.index') }}" class="nav-link {{ request()->routeIs('payments.index') ? 'active' : '' }}"><i class="nav-icon bi bi-dot"></i><p>User Recipt Details</p></a></li>
                            <li class="nav-item"><a href="{{ route('payments.index') }}" class="nav-link {{ request()->routeIs('payments.index') ? 'active' : '' }}"><i class="nav-icon bi bi-dot"></i><p>User Recived Amount Details</p></a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a href="{{ route('plots.index') }}" class="nav-link {{ request()->routeIs('plots.*') ? 'active' : '' }}"><i class="nav-icon bi bi-pin-map"></i><p>Plot Location</p></a></li>
                    <li class="nav-item"><a href="{{ route('customers.index') }}" class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}"><i class="nav-icon bi bi-people"></i><p>Customers</p></a></li>
                    <li class="nav-item"><a href="{{ route('agents.index') }}" class="nav-link {{ request()->routeIs('agents.*') ? 'active' : '' }}"><i class="nav-icon bi bi-person-badge"></i><p>Agents</p></a></li>
                    <li class="nav-item"><a href="{{ route('partners.index') }}" class="nav-link {{ request()->routeIs('partners.*') ? 'active' : '' }}"><i class="nav-icon bi bi-diagram-3"></i><p>Partners</p></a></li>
                    <li class="nav-item"><a href="{{ route('arazi-documents.index') }}" class="nav-link {{ request()->routeIs('arazi-documents.*') ? 'active' : '' }}"><i class="nav-icon bi bi-file-earmark-arrow-up"></i><p>Documents</p></a></li>
                </ul>
            </nav>
        </div>
    </aside>

    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h3 class="mb-0">{{ $title ?? 'Dashboard' }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid pb-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </main>

    <footer class="app-footer">
        <div class="float-end d-none d-sm-inline">Kisan Land Management System</div>
        <strong>Copyright &copy; {{ date('Y') }} <a href="{{ route('dashboard') }}" class="text-decoration-none">Kisan LMS</a>.</strong>
        All rights reserved.
    </footer>
</div>

<script src="{{ asset('vendor/adminlte/js/adminlte.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@stack('scripts')
</body>
</html>
