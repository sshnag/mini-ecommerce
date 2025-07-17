@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold">Admin Dashboard</h1>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row g-4">
            <!-- Summary Cards -->
            <div class="col-md-3">
                <div class="dashboard-card text-center">
                    <h6>Total Products</h6>
                    <h3>{{ $totalProducts }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="dashboard-card text-center">
                    <h6>Orders Today</h6>
                    <h3>{{ $ordersToday }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="dashboard-card text-center">
                    <h6>New Users</h6>
                    <h3>{{ $newUsers }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="dashboard-card text-center">
                    <h6>Total Revenue</h6>
                    <h3>${{ number_format($totalRevenue, 2) }}</h3>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <!-- Orders Line Chart -->
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-white border-0">
                        <h5>Orders Over Time</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="ordersChart" height="150"></canvas>
                    </div>
                </div>
            </div>

            <!-- Category Pie Chart -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-white border-0">
                        <h5>Top Product Categories</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="categoryPieChart"></canvas>
                        <ul id="categoryLegend" class="list-unstyled mt-3 chart-legend"></ul>
                    </div>
                </div>
            </div>



        </div>


        <div class="row mt-5">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Recently Added Products</h5>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Created</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($recentProducts as $product)
                                        <tr>
                                            <td>{{ $product->custom_id }}</td>
                                            <td>{{ $product->name }}</td>
                                            <td>{{ $product->category->name ?? 'N/A' }}</td>
                                            <td>{{ $product->created_at->diffForHumans() }}</td>
                                            <td>${{ number_format($product->price, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">No recent products found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-..."
        crossorigin="anonymous"></script>

@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Orders over the last 7 days
        const ordersChart = new Chart(document.getElementById('ordersChart'), {
            type: 'line',
            data: {
                labels: @json($orderLabels),
                datasets: [{
                    label: 'Orders',
                    data: @json($orderCounts),
                    borderColor: '#4F46E5',
                    backgroundColor: 'rgba(99, 102, 241, 0.2)',
                    tension: 0.4,
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Top categories by product count
        const categoryPie = new Chart(document.getElementById('categoryPieChart'), {
            type: 'pie',
            data: {
                labels: @json($categoryLabels),
                datasets: [{
                    data: @json($categoryCounts),
                    backgroundColor: [
                        '#C4B5FD', '#FBCFE8', '#A5F3FC', '#FDE68A', '#BBF7D0'
                    ],
                    borderColor: '#fff',
                    borderWidth: 1
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false // We'll show our own legend
                    }
                }
            }
        });

        // Manually build pie chart legend
        const legendContainer = document.getElementById('categoryLegend');
        const colors = ['#C4B5FD', '#FBCFE8', '#A5F3FC', '#FDE68A', '#BBF7D0'];
        @json($categoryLabels).forEach((label, index) => {
            const li = document.createElement('li');
            li.innerHTML = `<span style="background:${colors[index]}"></span>${label}`;
            legendContainer.appendChild(li);
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


@stop
