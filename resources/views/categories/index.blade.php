@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-primary">Kategori Barang</h5>
                <a href="{{ route('categories.create') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-plus-circle me-1"></i> Tambah Kategori
                </a>
            </div>
        </div>
        <div class="card-body">            
            <!-- Form Pencarian -->
            <div class="mb-4">
                <form action="{{ route('categories.index') }}" method="GET">
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0" 
                               placeholder="Cari kategori..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary px-4">
                            Cari
                        </button>
                        @if(request('search'))
                            <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">Reset</a>
                        @endif
                    </div>
                </form>
            </div>
            
            @if(isset($categories) && is_array($categories) && count($categories) > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle border">
                    <thead class="table-light">
                        <tr>
                            <th class="fw-semibold">No</th>
                            <th class="fw-semibold">Nama Kategori</th>
                            <th class="fw-semibold">Deskripsi</th>
                            <th class="fw-semibold">Pasar Terkait</th>
                            <th class="fw-semibold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $index => $category)
                            <tr id="row-{{ $category['id'] }}">
                                <td>{{ $loop->iteration }}</td>
                                <td class="fw-medium">{{ $category['name'] }}</td>
                                <td>{{ $category['description'] ?? 'Tidak ada deskripsi' }}</td>
                                <td>
                                    @if (!empty($category['markets']) && count($category['markets']) > 0)
                                        <div class="markets-container">
                                            @foreach ($category['markets'] as $market)
                                                <span class="badge bg-light text-dark border me-1 mb-1 py-2 px-3">
                                                    <i class="fas fa-store-alt me-1 text-primary"></i> {{ $market['name'] }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-muted fst-italic">
                                            <i class="fas fa-info-circle me-1"></i> Tidak ada pasar
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('categories.edit', $category['id']) }}" 
                                        class="btn btn-warning btn-sm" 
                                        title="Edit Kategori">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm delete-btn"
                                            data-id="{{ $category['id'] }}" 
                                            data-name="{{ $category['name'] }}"
                                            title="Hapus Kategori">
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
                <div class="alert alert-info d-flex align-items-center">
                    <i class="fas fa-info-circle fs-4 me-3"></i>
                    <div>Tidak ada kategori barang yang ditemukan.</div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white py-3">
                <h5 class="modal-title" id="deleteConfirmationModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>Konfirmasi Penghapusan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <div class="text-center mb-3">
                    <div class="display-1 text-danger mb-3">
                        <i class="fas fa-trash-alt"></i>
                    </div>
                    <p class="fs-5">Anda yakin ingin menghapus kategori:</p>
                    <h4 class="fw-bold" id="categoryName"></h4>
                </div>
                <div class="alert alert-warning">
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="fas fa-exclamation-triangle text-warning fs-4"></i>
                        </div>
                        <div>
                            Data yang dihapus tidak dapat dikembalikan!
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Batal
                </button>
                <button type="button" id="confirmDeleteBtn" class="btn btn-danger px-4">
                    <i class="fas fa-trash me-1"></i> Hapus
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notification for Category Update -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1080">
    <div id="categoryToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-success text-white">
            <i class="fas fa-check-circle me-2"></i>
            <strong class="me-auto">Sukses</strong>
            <small>Baru saja</small>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="toastMessage">
            <!-- Pesan akan diganti via JavaScript -->
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Card styling */
    .card {
        border-radius: 0.5rem;
        overflow: hidden;
        border: none;
    }
    
    /* Table styling */
    .table {
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .table th {
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
        color: #495057;
    }
    
    .table td {
        vertical-align: middle;
        padding: 0.75rem 1rem;
    }
    
    /* Pasar terkait styling */
    .markets-container {
        display: flex;
        flex-wrap: wrap;
        gap: 0.25rem;
    }
    
    /* Pastikan backdrop modal menutupi seluruh halaman */
    .modal-backdrop.show {
        position: fixed !important;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh !important;
        z-index: 1050;
        opacity: 0.7;
        background-color: rgba(0, 0, 0, 0.6) !important;
    }

    /* Modal styling */
    .modal {
        z-index: 1060;
    }

    /* Toast notification fixed bottom right */
    #categoryToast {
        min-width: 300px;
        max-width: 400px;
        background-color: #198754;
        color: white;
    }

    /* Button styling */
    .btn {
        border-radius: 0.25rem;
        font-weight: 500;
    }
    
    .btn-sm {
        padding: 0.375rem 0.75rem;
    }
    
    /* Alert styling */
    .alert {
        border-radius: 0.5rem;
        border-left: 4px solid;
    }
    
    .alert-success {
        border-left-color: #198754;
    }
    
    .alert-info {
        border-left-color: #0dcaf0;
    }
    
    .alert-danger {
        border-left-color: #dc3545;
    }
    
    .alert-warning {
        border-left-color: #ffc107;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi modal
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
    
    // Inisialisasi toast
    const categoryToast = new bootstrap.Toast(document.getElementById('categoryToast'), {
        delay: 5000
    });
    
    let currentCategoryId = null;
    let currentCategoryRow = null;

    // Tangani klik tombol hapus
    $(document).on('click', '.delete-btn', function() {
        const categoryId = $(this).data('id');
        const name = $(this).data('name');
        
        // Simpan ID kategori dan referensi barisnya
        currentCategoryId = categoryId;
        currentCategoryRow = $(this).closest('tr');
        
        // Set data di modal
        $('#categoryName').text(name);
        
        // Tampilkan modal
        deleteModal.show();
    });

    // Tangani tombol hapus di modal
    $('#confirmDeleteBtn').on('click', function() {
        if (!currentCategoryId || !currentCategoryRow) return;
        
        const submitButton = $(this);
        const originalHtml = submitButton.html();
        
        submitButton.html(
            '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Menghapus...'
        );
        submitButton.prop('disabled', true);
        
        // Kirim request hapus
        $.ajax({
            url: `/categories/${currentCategoryId}`,
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                _method: 'DELETE'
            },
            success: function(data) {
                // Sembunyikan modal
                submitButton.html('<i class="fas fa-trash me-1"></i> Hapus');
                submitButton.prop('disabled', false);
                deleteModal.hide();
                
                // Tampilkan toast notification
                $('#toastMessage').text(data.message || 'Kategori berhasil dihapus');
                categoryToast.show();
                
                // Hapus baris dari tabel tanpa reload
                currentCategoryRow.fadeOut(400, function() {
                    $(this).remove();
                    
                    // Periksa jika tabel kosong
                    if ($('tbody tr').length === 0) {
                        $('.table-responsive').html(`
                            <div class="alert alert-info d-flex align-items-center">
                                <i class="fas fa-info-circle fs-4 me-3"></i>
                                <div>Tidak ada kategori barang yang ditemukan.</div>
                            </div>
                        `);
                    }
                });
            },
            error: function(xhr) {
                // Tampilkan pesan error
                const error = xhr.responseJSON?.message || 'Gagal menghapus kategori';
                
                // Sembunyikan modal
                deleteModal.hide();
                
                // Tampilkan alert error
                $('.card-body').prepend(`
                    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                        <i class="fas fa-exclamation-circle me-2 fs-5"></i>
                        <div>
                            ${error}
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);
                
                // Reset tombol
                submitButton.html(originalHtml);
                submitButton.prop('disabled', false);
            }
        });
    });
    
    // Cek jika ada parameter success di URL (untuk notifikasi edit)
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('success') && urlParams.get('action') === 'update') {
        $('#toastMessage').text('Kategori berhasil diperbarui');
        categoryToast.show();
    }
});
</script>
@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toastEl = document.getElementById('categoryToast');
        const toastBody = document.getElementById('toastMessage');
        if (toastBody) {
            toastBody.innerText = @json(session('success'));
        }
        const toast = new bootstrap.Toast(toastEl);
        toast.show();
    });
</script>
@endif

@endpush
