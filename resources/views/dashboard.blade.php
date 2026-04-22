@extends('layouts.app')

@section('content')
    <div class="row g-3">
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-primary">
                <div class="inner">
                    <h3>{{ $totalKisans }}</h3>
                    <p>Total Kisans</p>
                </div>
                <div class="small-box-footer">Farmer records</div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-success">
                <div class="inner">
                    <h3>{{ $totalArazis }}</h3>
                    <p>Total Arazi</p>
                </div>
                <div class="small-box-footer">Land records</div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-warning">
                <div class="inner">
                    <h3>{{ $availableArazis }}</h3>
                    <p>Available Land</p>
                </div>
                <div class="small-box-footer">{{ $soldArazis }} sold</div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-danger">
                <div class="inner">
                    <h3>{{ $totalCustomers }}</h3>
                    <p>Total Customers</p>
                </div>
                <div class="small-box-footer">{{ $totalAgents }} agents</div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-1">
        <div class="col-lg-6">
            <div class="card card-outline card-primary h-100">
                <div class="card-header"><h5 class="card-title mb-0">Land Status</h5></div>
                <div class="card-body">
                    <canvas id="landStatusChart" height="220"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card card-outline card-success h-100">
                <div class="card-header"><h5 class="card-title mb-0">Recent Payments</h5></div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-striped mb-0 align-middle">
                        <thead>
                        <tr>
                            <th>Registry</th>
                            <th>Party</th>
                            <th>Amount</th>
                            <th>Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($recentPayments as $payment)
                            <tr>
                                <td>{{ $payment->registry?->arazi?->plot_number ?? '-' }}</td>
                                <td>{{ $payment->customer?->name ?? $payment->kisan?->name ?? '-' }}</td>
                                <td>{{ number_format((float) $payment->amount, 2) }}</td>
                                <td>{{ optional($payment->payment_date)->format('d-m-Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">No payments found.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const ctx = document.getElementById('landStatusChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Available', 'Sold'],
                    datasets: [{
                        data: [{{ $availableArazis }}, {{ $soldArazis }}],
                        backgroundColor: ['#198754', '#dc3545'],
                    }],
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        },
                    },
                },
            });
        }
    </script>
@endpush
