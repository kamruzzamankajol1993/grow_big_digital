@extends('admin.layout.master')
@section('title', 'System Overview')

@section('body')
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-primary text-white p-3 rounded-4">
            <h6 class="small text-uppercase opacity-75">Total Services</h6>
            <h2 class="fw-bold mb-0">{{ $totalServices }}</h2>
            <i class="bi bi-gear-wide-connected position-absolute end-0 bottom-0 opacity-25 p-3 fs-1"></i>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-success text-white p-3 rounded-4">
            <h6 class="small text-uppercase opacity-75">Projects Done</h6>
            <h2 class="fw-bold mb-0">{{ $totalPortfolio }}</h2>
            <i class="bi bi-briefcase position-absolute end-0 bottom-0 opacity-25 p-3 fs-1"></i>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-danger text-white p-3 rounded-4">
            <h6 class="small text-uppercase opacity-75">Unread Messages</h6>
            <h2 class="fw-bold mb-0">{{ $unreadMessages }}</h2>
            <i class="bi bi-envelope-exclamation position-absolute end-0 bottom-0 opacity-25 p-3 fs-1"></i>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-warning text-dark p-3 rounded-4">
            <h6 class="small text-uppercase opacity-75">Audit Requests</h6>
            <h2 class="fw-bold mb-0">{{ $unreadAudits }}</h2>
            <i class="bi bi-search position-absolute end-0 bottom-0 opacity-25 p-3 fs-1"></i>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm p-4 rounded-4 h-100">
            <h6 class="fw-bold mb-4">Portfolio Distribution by Service</h6>
            <canvas id="portfolioBarChart" style="height: 300px;"></canvas>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm p-4 rounded-4 mb-4">
            <h6 class="fw-bold mb-3">Live Content Status</h6>
            <div class="d-flex align-items-center mb-3">
                <div class="bg-light p-2 rounded me-3"><i class="bi bi-house-door text-primary"></i></div>
                <div><small class="text-muted d-block">Hero Title</small><strong>{{ Str::limit($heroTitle, 25) }}</strong></div>
            </div>
            <div class="d-flex align-items-center">
                <div class="bg-light p-2 rounded me-3"><i class="bi bi-info-square text-success"></i></div>
                <div><small class="text-muted d-block">About Title</small><strong>{{ Str::limit($whoWeAreTitle, 25) }}</strong></div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white py-3 px-4 fw-bold">Recent Projects</div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="small"><th>Title</th><th>Service</th><th>Media</th></tr>
                    </thead>
                    <tbody>
                        @foreach($recentPortfolio as $p)
                        <tr>
                            <td><span class="small fw-bold">{{ $p->title }}</span></td>
                            <td><span class="badge bg-info bg-opacity-10 text-info">{{ $p->service->name ?? 'N/A' }}</span></td>
                            <td><i class="bi {{ $p->video_link ? 'bi-play-circle text-danger' : 'bi-image text-primary' }}"></i></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white py-3 px-4 fw-bold text-warning">New Audit Inquiries</div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="small"><th>Name</th><th>Email</th><th>Date</th></tr>
                    </thead>
                    <tbody>
                        @foreach($recentAudits as $ra)
                        <tr class="{{ $ra->is_read ? '' : 'bg-warning bg-opacity-10' }}">
                            <td class="small">{{ $ra->name }}</td>
                            <td class="small">{{ $ra->email }}</td>
                            <td class="text-muted" style="font-size: 11px;">{{ $ra->created_at->format('d M') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const pCtx = document.getElementById('portfolioBarChart').getContext('2d');
    new Chart(pCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($pLabels) !!},
            datasets: [{
                label: 'Projects',
                data: {!! json_encode($pValues) !!},
                backgroundColor: '#00a651',
                borderRadius: 8
            }]
        },
        options: { responsive: true, plugins: { legend: { display: false } } }
    });
</script>
@endsection