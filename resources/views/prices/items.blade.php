@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold m-0">
                        <i class="fas fa-tag me-2 text-primary"></i>Daftar Harga Barang
                    </h4>
                    <a href="{{ route('prices.create', [$market_id, $category_id]) }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-1"></i> Tambah Harga
                    </a>
                </div>

                <!-- Alert Messages -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Search & Filter Form -->
                <div class="card bg-light border-0 mb-4">
                    <div class="card-body">
                        <form action="{{ route('prices.items', [$market_id, $category_id]) }}" method="GET">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-search text-muted me-1"></i>Cari Nama Barang
                                    </label>
                                    <input type="text" name="search" value="{{ request('search') }}"
                                        class="form-control shadow-none" placeholder="Contoh: Cabai">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-exchange-alt text-muted me-1"></i>Arah Perubahan Harga
                                    </label>
                                    <select name="direction" class="form-select shadow-none">
                                        <option value="">Semua</option>
                                        <option value="naik" {{ request('direction') == 'naik' ? 'selected' : '' }}>
                                            <i class="fas fa-arrow-up text-danger"></i> Naik
                                        </option>
                                        <option value="turun" {{ request('direction') == 'turun' ? 'selected' : '' }}>
                                            <i class="fas fa-arrow-down text-success"></i> Turun
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-tags text-muted me-1"></i>Kategori Harga
                                    </label>
                                    <select name="range" class="form-select shadow-none">
                                        <option value="">Semua</option>
                                        <option value="murah" {{ request('range') == 'murah' ? 'selected' : '' }}>
                                            Murah (&lt; 10rb)
                                        </option>
                                        <option value="sedang" {{ request('range') == 'sedang' ? 'selected' : '' }}>
                                            Sedang (10rb - 50rb)
                                        </option>
                                        <option value="mahal" {{ request('range') == 'mahal' ? 'selected' : '' }}>
                                            Mahal (&gt; 50rb)
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-primary flex-grow-1" type="submit">
                                            <i class="fas fa-filter me-1"></i> Filter
                                        </button>
                                        <a href="{{ route('prices.items', [$market_id, $category_id]) }}"
                                            class="btn btn-outline-secondary">
                                            <i class="fas fa-undo me-1"></i> Reset
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Export Buttons -->
                <div class="d-flex flex-wrap gap-2 mb-4">
                    <a href="{{ route('prices.export', [$market_id, $category_id]) }}?start_date={{ request('start_date') }}&end_date={{ request('end_date') }}"
                        class="btn btn-outline-success">
                        <i class="fas fa-file-excel me-1"></i> Export Excel
                    </a>
                    <a href="{{ route('prices.export.pdf', [$market_id, $category_id]) }}?start_date={{ request('start_date') }}&end_date={{ request('end_date') }}"
                        class="btn btn-outline-danger">
                        <i class="fas fa-file-pdf me-1"></i> Export PDF
                    </a>
                </div>

                <!-- Data Table -->
                @if (count($prices) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="fw-semibold">Nama Barang</th>
                                    <th class="fw-semibold">Harga Awal(KG)</th>
                                    <th class="fw-semibold">Harga Sekarang(KG)</th>
                                    <th class="fw-semibold">Persentase</th>
                                    <th class="fw-semibold">Alasan</th>
                                    <th class="fw-semibold">Terakhir Diperbarui</th>
                                    <th class="fw-semibold text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($prices as $price)
                                    <tr>
                                        <td class="fw-medium">{{ $price['item_name'] }}</td>
                                        <td>Rp{{ number_format($price['initial_price']) }}</td>
                                        <td class="fw-bold">Rp{{ number_format($price['current_price']) }}</td>
                                        <td>
                                            @php
                                                $changePercent = round($price['change_percent'], 2);
                                                // Swap the badge classes and icons
                                                $badgeClass =
                                                    $changePercent > 0
                                                        ? 'success'
                                                        : ($changePercent < 0
                                                            ? 'danger'
                                                            : 'secondary');
                                                $icon =
                                                    $changePercent > 0
                                                        ? 'fa-arrow-up'
                                                        : ($changePercent < 0
                                                            ? 'fa-arrow-down'
                                                            : 'fa-minus');
                                            @endphp
                                            <span
                                                class="badge bg-{{ $badgeClass }} bg-opacity-10 text-{{ $badgeClass }} px-2 py-1">
                                                <i class="fas {{ $icon }} me-1"></i>{{ abs($changePercent) }}%
                                            </span>
                                        </td>
                                        <td>
                                            @if ($price['reason'])
                                                <span class="text-truncate d-inline-block" style="max-width: 150px;"
                                                    data-bs-toggle="tooltip" title="{{ $price['reason'] }}">
                                                    {{ $price['reason'] }}
                                                </span>
                                            @else
                                                <span class="text-muted fst-italic">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="text-muted">
                                                <i class="far fa-clock me-1"></i>
                                                {{ \Carbon\Carbon::parse($price['updated_at'])->translatedFormat('d M Y H:i') }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-1">
                                            <a href="{{ route('prices.edit', ['id' => $price['id']]) }}"
   class="btn btn-sm btn-outline-warning edit-btn"
   data-updated="{{ \Carbon\Carbon::parse($price['updated_at'])->toIso8601String() }}"
   data-name="{{ $price['item_name'] }}"
   data-id="{{ $price['id'] }}"
   data-url="{{ route('prices.edit', ['id' => $price['id']]) }}"
   data-bs-toggle="tooltip"
   title="Edit">
   <i class="fas fa-edit"></i>
</a>

                                                <a href="{{ route('prices.chart', $price['item_id']) }}"
                                                    class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip"
                                                    title="Lihat Grafik">
                                                    <i class="fas fa-chart-line"></i>
                                                </a>
                                                <form action="{{ route('prices.destroy', $price['id']) }}" method="POST"
                                                    class="d-inline delete-form">
                                                    @csrf @method('DELETE')
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-danger delete-btn"
                                                        data-id="{{ $price['id'] }}"
                                                        data-name="{{ $price['item_name'] }}"
                                                        data-bs-toggle="tooltip" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach


                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info d-flex align-items-center">
                        <i class="fas fa-info-circle me-2"></i>
                        <span>Belum ada data harga untuk kategori ini.</span>
                    </div>
                @endif
                <a href="{{ route('prices.categories', $market_id) }}" class="btn btn-outline-primary me-md-2">
                    <i class="fa fa-chevron-left me-1"></i> Kembali ke Kategori
                </a>
            </div>
        </div>
    </div>


    @push('scripts')
        <!-- SweetAlert2 CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
        <!-- SweetAlert2 JS -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        
        <script>
            document.querySelectorAll('.edit-btn').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault(); // cegah redirect dulu

        const updatedAt = new Date(this.dataset.updated);
        const now = new Date();
        const todayReset = new Date();
        todayReset.setHours(8, 0, 0, 0); // jam 08.00 pagi hari ini

        if (updatedAt > todayReset && now < new Date(todayReset.getTime() + 24 * 60 * 60 * 1000)) {
            const jamTersisa = Math.ceil((todayReset.getTime() + 24 * 60 * 60 * 1000 - now.getTime()) / (1000 * 60 * 60));
            Swal.fire({
                icon: 'warning',
                title: 'Tidak Bisa Edit!',
                html: `Harga barang <strong>${this.dataset.name}</strong> sudah diperbarui hari ini.<br>
                       Silakan coba lagi dalam <b>${jamTersisa} jam</b>.`,
                confirmButtonText: 'OK'
            });
        } else {
            // Arahkan ke halaman edit jika belum diedit hari ini
            window.location.href = this.dataset.url;
        }
    });
});

            // Initialize tooltips
            document.addEventListener('DOMContentLoaded', function() {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl)
                });

                // Professional delete confirmation with SweetAlert2
                document.querySelectorAll('.delete-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const itemId = this.getAttribute('data-id');
                        const itemName = this.getAttribute('data-name');
                        const form = this.closest('form');
                        
                        Swal.fire({
                            title: 'Konfirmasi Hapus',
                            html: `Anda yakin ingin menghapus data <strong>${itemName}</strong>?<br>
                                  <span class="text-danger"><small>Tindakan ini tidak dapat dibatalkan</small></span>`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#dc3545',
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: 'Ya, Hapus!',
                            cancelButtonText: 'Batal',
                            reverseButtons: true,
                            focusCancel: true
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Show loading state
                                Swal.fire({
                                    title: 'Menghapus...',
                                    html: 'Mohon tunggu sebentar',
                                    allowOutsideClick: false,
                                    didOpen: () => {
                                        Swal.showLoading();
                                    }
                                });
                                
                                // Submit the form
                                form.submit();
                            }
                        });
                    });
                });
                
                // Show success message with SweetAlert2 if coming from a redirect with success message
                @if(session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: '{{ session('success') }}',
                        timer: 3000,
                        timerProgressBar: true
                    });
                @endif
                @if(session('error'))
    <script>
        Swal.fire({
            icon: 'warning',
            title: 'Peringatan!',
            text: '{{ session('error') }}',
            timer: 5000
        });
    </script>
@endif

            });
        </script>
    @endpush
@endsection
