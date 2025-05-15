@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <!-- Welcome Section -->
        <div class="welcome-section mb-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-md-5 position-relative overflow-hidden">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <h1 class="fw-bold text-primary mb-3">Selamat Datang Kembali Super Admin</h1>
                            <p class="lead text-muted mb-0">Kelola platform Partoba dengan efisien dan optimal. Pantau
                                statistik penting dan kelola data dengan mudah.</p>
                        </div>
                        <div class="col-lg-4 d-none d-lg-block text-end">
                            <div class="welcome-illustration">
                                <i class="fas fa-chart-line fa-4x text-primary opacity-75"></i>
                            </div>
                        </div>
                    </div>
                    <div class="welcome-shape-1"></div>
                    <div class="welcome-shape-2"></div>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <span>{{ session('error') }}</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Dashboard Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">Dashboard Overview</h2>
            <div class="date-display text-muted">
                <i class="far fa-calendar-alt me-1"></i> {{ date('l, d F Y') }}
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-4">
            <!-- Total Bahan Pokok -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm dashboard-card">
                    <div class="card-body position-relative p-4">
                        <div class="d-flex justify-content-between mb-3">
                            <div class="card-icon bg-primary-subtle rounded-circle">
                                <i class="fas fa-box"></i>
                            </div>
                            <div class="trend-badge">
                                <span class="badge bg-primary-subtle text-primary">
                                    <i class="fas fa-boxes me-1"></i> Inventory
                                </span>
                            </div>
                        </div>
                        <div class="card-content">
                            <h6 class="text-muted fw-semibold mb-2">Total Bahan Pokok</h6>
                            <h3 class="mb-0 fw-bold">{{ $totalCommodities }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Nilai Persediaan -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm dashboard-card">
                    <div class="card-body position-relative p-4">
                        <div class="d-flex justify-content-between mb-3">
                            <div class="card-icon bg-success-subtle rounded-circle">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div class="trend-badge">
                                <span class="badge bg-success-subtle text-success">
                                    <i class="fas fa-chart-line me-1"></i> Value
                                </span>
                            </div>
                        </div>
                        <div class="card-content">
                            <h6 class="text-muted fw-semibold mb-2">Total Nilai Persediaan</h6>
                            <h3 class="mb-0 fw-bold">Rp {{ number_format($totalInventoryValue, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Recent Activity Section -->
            <div class="row mt-5">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-bold">Aktivitas Terbaru</h5>
                                <a href="#" class="btn btn-sm btn-outline-primary" id="toggleActivities">Lihat Semua</a>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            @if (count($filteredPrices) > 0)
                                <div class="list-group list-group-flush">
                                @php
    // Sort $filteredPrices descending by change_date
    usort($filteredPrices, function ($a, $b) {
        return strtotime($b['change_date']) <=> strtotime($a['change_date']);
    });

    // Setelah diurutkan, ambil 5 aktivitas terbaru
    $allActivities = $filteredPrices;
    $limitedActivities = array_slice($filteredPrices, 0, 5);
@endphp

                                    
                                    <!-- Limited Activities (initially visible) -->
                                    <div id="limitedActivities">
                                        @foreach ($limitedActivities as $activity)
                                            @php
                                                $icon =
                                                    $activity['change_percent'] > 0
                                                        ? 'fa-arrow-up'
                                                        : ($activity['change_percent'] < 0
                                                            ? 'fa-arrow-down'
                                                            : 'fa-minus');
                                                $badgeClass =
                                                    $activity['change_percent'] > 0
                                                        ? 'success-subtle text-success'
                                                        : ($activity['change_percent'] < 0
                                                            ? 'danger-subtle text-danger'
                                                            : 'secondary-subtle text-secondary');
                                            @endphp
                                            <div class="list-group-item py-3 px-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="activity-icon bg-{{ $badgeClass }} rounded-circle me-3">
                                                        <i class="fas {{ $icon }}"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <h6 class="mb-1 fw-semibold">
                                                                {{ $activity['item_name'] }} diperbarui
                                                            </h6>
                                                            @php
    $carbonDate = \Carbon\Carbon::parse($activity['change_date'])->locale('id');
@endphp
<small class="text-muted">
    {{ $carbonDate->translatedFormat('d M Y') }}
</small>


                                                        </div>
                                                        <p class="mb-0 text-muted small">
                                                            Perubahan dari
                                                            Rp{{ number_format($activity['initial_price'], 0, ',', '.') }}
                                                            ke Rp{{ number_format($activity['current_price'], 0, ',', '.') }}
                                                            ({{ round($activity['change_percent'], 2) }}%)
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                    <!-- All Activities (initially hidden) -->
                                    <div id="allActivities" style="display: none;">
                                        @foreach ($allActivities as $activity)
                                            @php
                                                $icon =
                                                    $activity['change_percent'] > 0
                                                        ? 'fa-arrow-up'
                                                        : ($activity['change_percent'] < 0
                                                            ? 'fa-arrow-down'
                                                            : 'fa-minus');
                                                $badgeClass =
                                                    $activity['change_percent'] > 0
                                                        ? 'success-subtle text-success'
                                                        : ($activity['change_percent'] < 0
                                                            ? 'danger-subtle text-danger'
                                                            : 'secondary-subtle text-secondary');
                                            @endphp
                                            <div class="list-group-item py-3 px-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="activity-icon bg-{{ $badgeClass }} rounded-circle me-3">
                                                        <i class="fas {{ $icon }}"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <h6 class="mb-1 fw-semibold">
                                                                {{ $activity['item_name'] }} diperbarui
                                                            </h6>
                                                            @php
    $carbonDate = \Carbon\Carbon::parse($activity['change_date'])->locale('id');
@endphp
<small class="text-muted">
    {{ $carbonDate->translatedFormat('d M Y') }}
</small>


                                                        </div>
                                                        <p class="mb-0 text-muted small">
                                                            Perubahan dari
                                                            Rp{{ number_format($activity['initial_price'], 0, ',', '.') }}
                                                            ke Rp{{ number_format($activity['current_price'], 0, ',', '.') }}
                                                            ({{ round($activity['change_percent'], 2) }}%)
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="p-4 text-center text-muted">
                                    Tidak ada aktivitas terbaru.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>


            <!-- Font Awesome -->
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

            <!-- Custom CSS -->
            <style>
                /* Card Styles */
                .dashboard-card {
                    transition: all 0.3s ease;
                    border-radius: 12px;
                    overflow: hidden;
                }

                .dashboard-card:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08) !important;
                }

                .card-icon {
                    width: 48px;
                    height: 48px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    border-radius: 12px;
                }

                .card-icon i {
                    font-size: 20px;
                }

                /* Welcome Section Styles */
                .welcome-section .card {
                    border-radius: 16px;
                    background: linear-gradient(145deg, #ffffff, #f8f9fa);
                    overflow: hidden;
                    transition: all 0.3s ease;
                }

                .welcome-section .card:hover {
                    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08) !important;
                }

                .welcome-shape-1 {
                    position: absolute;
                    bottom: -30px;
                    right: -30px;
                    width: 200px;
                    height: 200px;
                    background: radial-gradient(circle, rgba(13, 110, 253, 0.08) 0%, rgba(13, 110, 253, 0) 70%);
                    border-radius: 50%;
                    z-index: 0;
                }

                .welcome-shape-2 {
                    position: absolute;
                    top: -30px;
                    right: 20%;
                    width: 100px;
                    height: 100px;
                    background: radial-gradient(circle, rgba(13, 110, 253, 0.05) 0%, rgba(13, 110, 253, 0) 70%);
                    border-radius: 50%;
                    z-index: 0;
                }

                /* Color Utilities */
                .bg-primary-subtle {
                    background-color: rgba(13, 110, 253, 0.15);
                }

                .bg-success-subtle {
                    background-color: rgba(25, 135, 84, 0.15);
                }

                .bg-info-subtle {
                    background-color: rgba(13, 202, 240, 0.15);
                }

                .text-primary {
                    color: #0d6efd !important;
                }

                .text-success {
                    color: #198754 !important;
                }

                .text-info {
                    color: #0dcaf0 !important;
                }

                /* Activity List Styles */
                .activity-icon {
                    width: 36px;
                    height: 36px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }

                .list-group-item {
                    transition: background-color 0.2s ease;
                }

                .list-group-item:hover {
                    background-color: rgba(0, 0, 0, 0.01);
                }

                /* Progress Bar Animation */
                .progress-bar {
                    transition: width 1.5s ease;
                    animation: progressAnimation 1.5s ease-in-out;
                }

                @keyframes progressAnimation {
                    0% {
                        width: 0%;
                    }
                }

                /* Responsive Adjustments */
                @media (max-width: 768px) {
                    .welcome-section .card-body {
                        padding: 2rem !important;
                    }

                    .date-display {
                        display: none;
                    }
                }
            </style>

            <!-- JavaScript for Toggle Activities -->
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const toggleButton = document.getElementById('toggleActivities');
                    const limitedActivities = document.getElementById('limitedActivities');
                    const allActivities = document.getElementById('allActivities');
                    
                    toggleButton.addEventListener('click', function(e) {
                        e.preventDefault();
                        
                        if (limitedActivities.style.display !== 'none') {
                            // Show all activities
                            limitedActivities.style.display = 'none';
                            allActivities.style.display = 'block';
                            toggleButton.textContent = 'Tampilkan Lebih Sedikit';
                        } else {
                            // Show limited activities
                            limitedActivities.style.display = 'block';
                            allActivities.style.display = 'none';
                            toggleButton.textContent = 'Lihat Semua';
                        }
                    });
                });
            </script>
        @endsection
