@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
 <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="fw-bold">Admin Dashboard</h1>

    @php
        $notifications = auth()->user()->unreadNotifications;
    @endphp

    <div class="dropdown notification-dropdown ms-auto">
        <button class="btn btn-notification position-relative" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-bell fa-lg"></i>
            @if($notifications->count())
                <span class="badge bg-danger position-absolute top-0 start-100 translate-middle">{{ $notifications->count() }}</span>
            @endif
        </button>

        <div class="dropdown-menu dropdown-menu-end notification-dropdown-menu p-0">
            <div class="notification-header d-flex justify-content-between align-items-center p-3 border-bottom">
                <h6 class="mb-0 fw-bold">Notifications</h6>
                @if($notifications->count())
                <a href="#" class="text-primary mark-all-read" onclick="markAllNotificationsRead()">Mark all as read</a>
                @endif
            </div>

            <div class="notification-list" style="max-height: 400px; overflow-y: auto;">
                @forelse ($notifications as $notification)
                <a href="{{ route('notifications.redirect', $notification->id) }}"
   class="dropdown-item notification-item d-flex align-items-start p-3 border-bottom">

                    <div class="notification-icon me-3">
                        <div class="icon-circle bg-{{ $notification->data['type'] ?? 'primary' }}">
                            <i class="fas fa-{{ $notification->data['icon'] ?? 'bell' }} text-white"></i>
                        </div>
                    </div>
                    <div class="notification-details">
                        <div class="notification-title fw-bold">{{ $notification->data['title'] ?? 'New Notification' }}</div>
                        <div class="notification-message small text-muted">{{ $notification->data['message'] }}</div>
                        <div class="notification-time small text-muted mt-1">
                            {{ $notification->created_at->diffForHumans() }}
                        </div>
                    </div>
                </a>
                @empty
                <div class="text-center p-4">
                    <i class="far fa-bell-slash fa-2x text-muted mb-2"></i>
                    <p class="text-muted mb-0">No new notifications</p>
                </div>
                @endforelse
            </div>

                   </div>
    </div>
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
<script>
 // Notification functions
    function markAsRead(id, event) {
        if (event) event.preventDefault();

        fetch(`/admin/notifications/${id}/read`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        }).then(() => {
            if (event && event.currentTarget.href !== '#') {
                window.location.href = event.currentTarget.href;
            } else {
                location.reload();
            }
        });
    }

    function markAllNotificationsRead() {
        fetch('/admin/notifications/mark-all-read', {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        }).then(() => {
            location.reload();
        });
    }

    // SweetAlert for new order alerts
    @if(session('new_order_alert'))
    Swal.fire({
        title: 'New Order!',
        text: '{{ session('new_order_alert') }}',
        icon: 'success',
        showCancelButton: true,
        confirmButtonText: 'View Order',
        cancelButtonText: 'Dismiss',
        confirmButtonColor: '#4F46E5',
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "{{ route('admin.orders.index') }}";
        }
    });
    @endif
</script>
@stop
