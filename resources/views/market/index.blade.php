@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Data Pasar</h5>
                <a href="{{ route('market.create') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-plus-circle me-1"></i> Tambah Pasar
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- Form Pencarian -->
            <div class="mb-4">
                <form action="{{ route('market.index') }}" method="GET">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Cari pasar..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i> Cari
                        </button>
                        @if(request('search'))
                            <a href="{{ route('market.index') }}" class="btn btn-secondary">Reset</a>
                        @endif
                    </div>
                </form>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(isset($markets) && is_array($markets) && count($markets) > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="fw-semibold">Nama Pasar</th>
                                <th class="fw-semibold">Lokasi</th>
                                <th class="fw-semibold">Koordinat</th>
                                <th class="fw-semibold">Gambar</th>
                                <th class="fw-semibold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($markets as $market)
                            <tr id="row-{{ $market['id'] }}">
                                <td class="fw-medium">{{ $market['name'] ?? 'Tidak tersedia' }}</td>
                                <td>
                                    <span class="d-inline-block text-truncate" style="max-width: 200px;" title="{{ $market['location'] ?? 'Tidak tersedia' }}">
                                        {{ $market['location'] ?? 'Tidak tersedia' }}
                                    </span>
                                </td>
                                <td>
                                    @if(!empty($market['latitude']) && !empty($market['longitude']))
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                            <div>
                                                <small class="d-block text-muted">Lat: {{ $market['latitude'] }}</small>
                                                <small class="d-block text-muted">Long: {{ $market['longitude'] }}</small>
                                            </div>
                                        </div>
                                    @else
                                        <span class="badge bg-secondary">Belum diatur</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $imageUrl = !empty($market['image_url']) 
                                            ? (filter_var($market['image_url'], FILTER_VALIDATE_URL) 
                                                ? $market['image_url'] 
                                                : asset($market['image_url']))
                                            : asset('images/no-image.png');
                                    @endphp
                                    <div class="position-relative">
                                        <img src="{{ $imageUrl }}" alt="Gambar Pasar" class="img-thumbnail" 
                                             style="width: 60px; height: 60px; object-fit: cover;">
                                        <a href="{{ $imageUrl }}" target="_blank" class="position-absolute top-0 end-0 bg-white rounded-circle p-1" 
                                           style="transform: translate(25%, -25%);" title="Lihat gambar">
                                            <i class="fas fa-search-plus text-primary" style="font-size: 10px;"></i>
                                        </a>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('market.edit', $market['id']) }}" class="btn btn-warning btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('market.location', $market['id']) }}" class="btn btn-info btn-sm" title="Atur Lokasi">
                                            <i class="fas fa-map-pin"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm delete-btn"
                                            data-id="{{ $market['id'] }}" 
                                            data-name="{{ $market['name'] }}"
                                            title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> Data pasar tidak tersedia.
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteConfirmationModalLabel">Konfirmasi Penghapusan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Anda yakin ingin menghapus pasar <strong id="marketName"></strong>?</p>
                <p class="text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Data yang dihapus tidak dapat dikembalikan!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" id="confirmDeleteBtn" class="btn btn-danger">
                    <i class="fas fa-trash me-1"></i> Hapus
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Pastikan modal di atas semua elemen */
    .modal {
        z-index: 1060;
    }

    /* Pastikan backdrop modal tidak menutupi elemen lain */
    .modal-backdrop {
        z-index: 1050;
    }

    /* Tombol loading */
    .btn-loading .spinner-border {
        vertical-align: text-top;
        width: 1rem;
        height: 1rem;
    }

    .table th {
        font-size: 0.9rem;
    }

    .table td {
        vertical-align: middle;
    }

    .img-thumbnail {
        transition: transform 0.2s;
    }
    .img-thumbnail:hover {
        transform: scale(1.05);
    }
    #marketToast {
    min-width: 300px;
    max-width: 400px;
    background-color: #198754;
    color: white;
}
.toast {
    border: none;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    border-radius: 0.5rem;
    overflow: hidden;
}

</style>
@endpush
<!-- Toast Notification for Market Delete -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1080">
    <div id="marketToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-success text-white">
            <i class="fas fa-check-circle me-2"></i>
            <strong class="me-auto">Sukses</strong>
            <small>Baru saja</small>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="marketToastMessage">
            <!-- Pesan akan diganti via JS -->
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi modal
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
    let currentMarketId = null;
    let currentMarketRow = null;
    let isProcessing = false; // Tambahkan flag untuk menandai proses sedang berjalan

    // Tangani klik tombol hapus
    $(document).on('click', '.delete-btn', function() {
        if (isProcessing) return; // Jika sedang proses, abaikan klik baru
        
        const marketId = $(this).data('id');
        const name = $(this).data('name');
        
        // Simpan ID pasar dan referensi barisnya
        currentMarketId = marketId;
        currentMarketRow = $(this).closest('tr');
        
        // Set data di modal
        $('#marketName').text(name);
        
        // Tampilkan modal
        deleteModal.show();
    });

    // Tangani tombol hapus di modal
    $('#confirmDeleteBtn').on('click', function() {
        if (!currentMarketId || !currentMarketRow || isProcessing) return;
        
        const submitButton = $(this);
        isProcessing = true; // Set flag sedang proses
        submitButton.html(
            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menghapus...'
        );
        submitButton.prop('disabled', true);
        
        // Kirim request hapus
        $.ajax({
            url: `/market/${currentMarketId}`,
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                _method: 'DELETE'
            },
            success: function(data) {
    // Tampilkan toast sukses
    $('#marketToastMessage').text(data.message || 'Pasar berhasil dihapus');
    const toast = new bootstrap.Toast(document.getElementById('marketToast'), {
        delay: 5000
    });
    toast.show();

    // Hapus baris dari tabel
    currentMarketRow.fadeOut(400, function() {
        $(this).remove();

        if ($('tbody tr').length === 0) {
            $('.table-responsive').html(`
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> Data pasar tidak tersedia.
                </div>
            `);
        }
    });
},

            error: function(xhr) {
                // Tampilkan pesan error
                const error = xhr.responseJSON?.message || 'Gagal menghapus pasar';
                $('.card-body').prepend(`
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        ${error}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);
            },
            complete: function() {
                // Reset tombol dan flag
                submitButton.html('<i class="fas fa-trash me-1"></i> Hapus');
                submitButton.prop('disabled', false);
                isProcessing = false;
                
                // Sembunyikan modal
                deleteModal.hide();
                
                // Reset variabel
                currentMarketId = null;
                currentMarketRow = null;
            }
        });
    });

    // Reset state ketika modal ditutup
    $('#deleteConfirmationModal').on('hidden.bs.modal', function () {
        if (isProcessing) return;
        
        const submitButton = $('#confirmDeleteBtn');
        submitButton.html('<i class="fas fa-trash me-1"></i> Hapus');
        submitButton.prop('disabled', false);
        isProcessing = false;
        currentMarketId = null;
        currentMarketRow = null;
    });
});
</script>
@endpush