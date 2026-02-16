@extends('admin.master.master')

@section('title')
Dashboard
@endsection

@section('css')
<style>
    /* --- Card Icon --- */
    .summary-card .card-body {
        padding: 1.25rem;
    }
    .summary-card-icon {
        flex-shrink: 0;
        margin-right: 1rem;
    }
    .summary-card-icon i {
        width: 32px; 
        height: 32px;
        stroke-width: 2.5;
    }
    .summary-card h6 {
        font-size: 0.85rem;
        margin-bottom: 0.25rem !important;
    }
    .summary-card h4 {
        font-size: 1.35rem;
        font-weight: 600;
        margin-bottom: 0 !important;
    }
    .chart-container {
        position: relative;
        height: 300px; /* Standard height for charts */
        width: 100%;
    }
</style>
@endsection

@section('body')
<main class="main-content">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <h2 class="mb-0">Dashboard Overview</h2>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-4 col-xl-2"> 
                <div class="card h-100 shadow-sm border-0 summary-card">
                    <div class="card-body d-flex align-items-center">
                        <div class="summary-card-icon text-primary"><i data-feather="box"></i></div>
                        <div><h6 class="text-muted mb-1">Products</h6><h4 class="mb-0">{{ $totalProducts }}</h4></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-xl-2">
                <div class="card h-100 shadow-sm border-0 summary-card">
                    <div class="card-body d-flex align-items-center">
                        <div class="summary-card-icon text-warning"><i data-feather="clock"></i></div>
                        <div><h6 class="text-muted mb-1">Pending</h6><h4 class="mb-0">{{ $pendingRequests }}</h4></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-xl-2">
                <div class="card h-100 shadow-sm border-0 summary-card">
                    <div class="card-body d-flex align-items-center">
                        <div class="summary-card-icon text-success"><i data-feather="check-circle"></i></div>
                        <div><h6 class="text-muted mb-1">Accepted</h6><h4 class="mb-0">{{ $acceptedRequests }}</h4></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-xl-2">
                <div class="card h-100 shadow-sm border-0 summary-card">
                    <div class="card-body d-flex align-items-center">
                        <div class="summary-card-icon text-danger"><i data-feather="x-circle"></i></div>
                        <div><h6 class="text-muted mb-1">Cancelled</h6><h4 class="mb-0">{{ $cancelledRequests }}</h4></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-xl-2">
                <div class="card h-100 shadow-sm border-0 summary-card">
                    <div class="card-body d-flex align-items-center">
                        <div class="summary-card-icon text-info"><i data-feather="users"></i></div>
                        <div><h6 class="text-muted mb-1">Customers</h6><h4 class="mb-0">{{ $totalCustomers }}</h4></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-xl-2"> 
                <div class="card h-100 shadow-sm border-0 summary-card">
                    <div class="card-body d-flex align-items-center">
                        <div class="summary-card-icon text-secondary"><i data-feather="briefcase"></i></div>
                        <div><h6 class="text-muted mb-1">Companies</h6><h4 class="mb-0">{{ $totalBrands }}</h4></div>
                    </div>
                </div>
            </div> 
        </div>

        <div class="row g-4 mb-4">
            <div class="col-lg-8">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0">Request History (Last 30 Days)</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="requestsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0">Top Companies by Request</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="brandsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0">Top 10 Requested Products</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="topProductsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0">Top 10 Customers (By Requests)</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="topCustomersChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Latest 10 Requests</h5>
                        <a href="{{ route('order.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Total Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestRequests as $order)
                                <tr>
                                    <td><span class="fw-bold">{{ $order->invoice_no }}</span></td>
                                    <td>
                                        <div>{{ $order->customer->name ?? 'Unknown' }}</div>
                                        <small class="text-muted">{{ $order->customer->phone ?? '' }}</small>
                                    </td>
                                    <td>{{ $order->created_at->format('d M, Y') }}</td>
                                    <td>
                                        @php
                                            $statusColor = match(strtolower($order->status)) {
                                                'accepted' => 'success',
                                                'cancelled' => 'danger',
                                                'pending' => 'secondary',
                                                'waiting' => 'info',
                                                default => 'primary'
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $statusColor }}">{{ ucfirst($order->status) }}</span>
                                    </td>
                                    <td>{{ number_format($order->total_amount, 2) }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="text-center py-3">No requests found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // --- 1. Requests History (Line Chart) ---
    const ctxRequests = document.getElementById('requestsChart').getContext('2d');
    new Chart(ctxRequests, {
        type: 'line',
        data: {
            labels: {!! json_encode($dates) !!},
            datasets: [{
                label: 'Requests',
                data: {!! json_encode($counts) !!},
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.1)',
                borderWidth: 2,
                tension: 0.3,
                fill: true,
                pointRadius: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0 } },
                x: { grid: { display: false } }
            },
            plugins: { legend: { display: false } }
        }
    });

    // --- 2. Top Brands (Doughnut Chart) ---
    const ctxBrands = document.getElementById('brandsChart').getContext('2d');
    new Chart(ctxBrands, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($brandNames) !!},
            datasets: [{
                data: {!! json_encode($brandRequests) !!},
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796', '#5a5c69', '#2c9faf', '#e155a0', '#2e59d9'],
                borderWidth: 2,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 12, padding: 15 } }
            }
        }
    });

    // --- 3. Top Products (Horizontal Bar Chart) ---
    const ctxTopProducts = document.getElementById('topProductsChart').getContext('2d');
    new Chart(ctxTopProducts, {
        type: 'bar',
        data: {
            labels: {!! json_encode($topProductNames) !!}, // Product Names
            datasets: [{
                label: 'Requests',
                data: {!! json_encode($topProductRequests) !!}, // Counts
                backgroundColor: '#36b9cc',
                borderRadius: 4,
                barPercentage: 0.6
            }]
        },
        options: {
            indexAxis: 'y', // Makes it horizontal
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: { beginAtZero: true, ticks: { precision: 0 } },
                y: { grid: { display: false } }
            },
            plugins: { legend: { display: false } }
        }
    });

    // --- 4. Top Customers (Horizontal Bar Chart) ---
    const ctxTopCustomers = document.getElementById('topCustomersChart').getContext('2d');
    new Chart(ctxTopCustomers, {
        type: 'bar',
        data: {
            labels: {!! json_encode($topCustomerNames) !!}, // Customer Names
            datasets: [{
                label: 'Requests',
                data: {!! json_encode($topCustomerRequests) !!}, // Counts
                backgroundColor: '#f6c23e',
                borderRadius: 4,
                barPercentage: 0.6
            }]
        },
        options: {
            indexAxis: 'y', // Makes it horizontal
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: { beginAtZero: true, ticks: { precision: 0 } },
                y: { grid: { display: false } }
            },
            plugins: { legend: { display: false } }
        }
    });
</script>
@endsection