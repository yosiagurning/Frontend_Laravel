@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Kelola Petugas Pasar</h5>
                    <a href="{{ route('officers.create') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-user-plus me-1"></i> Tambah Petugas
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Form Pencarian -->
                <div class="mb-4">
                    <form action="{{ route('officers.index') }}" method="GET">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Cari Nama Petugas"
                                value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i> Cari
                            </button>
                            @if (request('search'))
                                <a href="{{ route('officers.index') }}" class="btn btn-secondary">Reset</a>
                            @endif
                        </div>
                    </form>
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (count($officers) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="fw-semibold">Nama</th>
                                    <th class="fw-semibold">NIK</th>
                                    <th class="fw-semibold">No Telepon</th>
                                    <th class="fw-semibold">Username</th>
                                    <th class="fw-semibold text-center">Foto</th>
                                    <th class="fw-semibold text-center">Status</th>
                                    <th class="fw-semibold text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($officers as $officer)
                                    <tr>
                                        <td class="fw-medium">{{ $officer->name }}</td>
                                        <td>
                                            <span class="text-monospace">{{ $officer->nik }}</span>
                                        </td>
                                        <td>
                                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $officer->phone) }}"
                                                class="text-decoration-none whatsapp-link" target="_blank"
                                                title="Chat via WhatsApp">
                                                <i class="fab fa-whatsapp text-success me-1"></i>
                                                {{ $officer->phone }}
                                            </a>
                                        </td>
                                        <td>
                                            <span class="text-primary">{{ $officer->username }}</span>
                                        </td>
                                        <td class="text-center">
                                            @if ($officer->image_url)
                                                <div class="position-relative d-inline-block">
                                                    <img src="{{ asset($officer->image_url) }}"
                                                        alt="Foto {{ $officer->name }}"
                                                        class="rounded-circle border shadow-sm" width="60"
                                                        height="60" style="object-fit: cover;">
                                                    </a>
                                                </div>
                                            @else
                                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                                                    style="width: 60px; height: 60px; margin: 0 auto;">
                                                    <i class="fas fa-user text-secondary" style="font-size: 24px;"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span
                                                class="badge rounded-pill {{ $officer->is_active ? 'bg-success' : 'bg-danger' }} px-3 py-2">
                                                <i
                                                    class="fas {{ $officer->is_active ? 'fa-check-circle' : 'fa-times-circle' }} me-1"></i>
                                                {{ $officer->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-1">
                                                <a href="{{ route('officers.edit', $officer->id) }}"
                                                    class="btn btn-warning btn-sm" title="Edit Petugas">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('officers.toggle', $officer->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                        class="btn btn-sm {{ $officer->is_active ? 'btn-secondary' : 'btn-success' }}"
                                                        title="{{ $officer->is_active ? 'Nonaktifkan' : 'Aktifkan' }} Petugas">
                                                        <i
                                                            class="fas {{ $officer->is_active ? 'fa-user-slash' : 'fa-user-check' }}"></i>
                                                    </button>
                                                </form>
                                                <!-- Tombol hapus tanpa form -->
                                                <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                    data-id="{{ $officer->id }}" data-name="{{ $officer->name }}"
                                                    title="Hapus Petugas">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-info" role="alert">
                            Tidak ada petugas yang ditemukan.
                        </div>
                @endif
            </div>
        </div>
    </div>
    <!-- Modal Konfirmasi Hapus -->
    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteConfirmationModalLabel">Konfirmasi Penghapusan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Anda yakin ingin menghapus petugas <strong id="officerName"></strong>?</p>
                    <p class="text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Data yang dihapus tidak dapat
                        dikembalikan!</p>
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

        .text-monospace {
            font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        }

        .rounded-circle {
            transition: transform 0.2s;
        }

        .rounded-circle:hover {
            transform: scale(1.05);
        }

        /* Styling untuk link WhatsApp */
        .whatsapp-link {
            display: inline-flex;
            align-items: center;
            color: #075e54;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .whatsapp-link:hover {
            color: #128C7E;
            transform: translateY(-1px);
        }

        .whatsapp-link i {
            font-size: 1.2rem;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi modal
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
            let currentOfficerId = null;
            let currentOfficerRow = null;

            // Tangani klik tombol hapus
            $(document).on('click', '.delete-btn', function() {
                const officerId = $(this).data('id');
                const name = $(this).data('name');

                // Simpan ID petugas dan referensi barisnya
                currentOfficerId = officerId;
                currentOfficerRow = $(this).closest('tr');

                // Set data di modal
                $('#officerName').text(name);

                // Tampilkan modal
                deleteModal.show();
            });

            // Tangani tombol hapus di modal
            $('#confirmDeleteBtn').on('click', function() {
                if (!currentOfficerId || !currentOfficerRow) return;

                const submitButton = $(this);
                submitButton.html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menghapus...'
                );
                submitButton.prop('disabled', true);

                // Kirim request hapus
                $.ajax({
                    url: `/officers/${currentOfficerId}`,
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        _method: 'DELETE'
                    },
                    success: function(data) {
                        // Tampilkan pesan sukses
                        $('.card-body').prepend(`
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        ${data.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);

                        // Hapus baris dari tabel tanpa reload
                        currentOfficerRow.fadeOut(400, function() {
                            $(this).remove();

                            // Periksa jika tabel kosong
                            if ($('tbody tr').length === 0) {
                                $('.table-responsive').html(`
                            <div class="alert alert-info" role="alert">
                                Tidak ada petugas yang ditemukan.
                            </div>
                        `);
                            }
                        });

                        // Sembunyikan modal
                        // Reset tombol
submitButton.html('<i class="fas fa-trash me-1"></i> Hapus');
submitButton.prop('disabled', false);

// Sembunyikan modal
deleteModal.hide();

                    },
                    error: function(xhr) {
                        // Tampilkan pesan error
                        const error = xhr.responseJSON?.message || 'Gagal menghapus petugas';
                        $('.card-body').prepend(`
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        ${error}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);

                        // Reset tombol
                        submitButton.html('<i class="fas fa-trash me-1"></i> Hapus');
                        submitButton.prop('disabled', false);

                        // Sembunyikan modal
                        deleteModal.hide();
                    }
                });
            });
        });
    </script>
@endpush
