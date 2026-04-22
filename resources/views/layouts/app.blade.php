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
                    <li class="nav-item"><a href="{{ route('kisans.index') }}" class="nav-link {{ request()->routeIs('kisans.*') ? 'active' : '' }}"><i class="nav-icon bi bi-person-lines-fill"></i><p>Kisan</p></a></li>
                    <li class="nav-item"><a href="{{ route('arazis.index') }}" class="nav-link {{ request()->routeIs('arazis.*') ? 'active' : '' }}"><i class="nav-icon bi bi-map"></i><p>Arazi</p></a></li>
                    <li class="nav-item"><a href="{{ route('plots.index') }}" class="nav-link {{ request()->routeIs('plots.*') ? 'active' : '' }}"><i class="nav-icon bi bi-pin-map"></i><p>Plot Location</p></a></li>
                    <li class="nav-item"><a href="{{ route('registries.index') }}" class="nav-link {{ request()->routeIs('registries.*') ? 'active' : '' }}"><i class="nav-icon bi bi-journal-text"></i><p>Registry</p></a></li>
                    <li class="nav-item"><a href="{{ route('kisan-bonds.index') }}" class="nav-link {{ request()->routeIs('kisan-bonds.*') ? 'active' : '' }}"><i class="nav-icon bi bi-file-earmark-text"></i><p>Kisan Bond</p></a></li>
                    <li class="nav-item"><a href="{{ route('payments.index') }}" class="nav-link {{ request()->routeIs('payments.*') ? 'active' : '' }}"><i class="nav-icon bi bi-cash-coin"></i><p>Payments</p></a></li>
                    <li class="nav-item"><a href="{{ route('customer-bond-payments.index') }}" class="nav-link {{ request()->routeIs('customer-bond-payments.*') ? 'active' : '' }}"><i class="nav-icon bi bi-receipt"></i><p>Customer Bond Payments</p></a></li>
                    <li class="nav-item"><a href="{{ route('customers.index') }}" class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}"><i class="nav-icon bi bi-people"></i><p>Customers</p></a></li>
                    <li class="nav-item"><a href="{{ route('agents.index') }}" class="nav-link {{ request()->routeIs('agents.*') ? 'active' : '' }}"><i class="nav-icon bi bi-person-badge"></i><p>Agents</p></a></li>
                    <li class="nav-item"><a href="{{ route('investors.index') }}" class="nav-link {{ request()->routeIs('investors.*') ? 'active' : '' }}"><i class="nav-icon bi bi-graph-up-arrow"></i><p>Investors</p></a></li>
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
