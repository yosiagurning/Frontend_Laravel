@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <style>
            .sticky-header {
                position: sticky;
                top: 0;
                z-index: 999;
                background: linear-gradient(135deg, rgb(41, 100, 210) 0%, rgb(34, 108, 177) 100%);
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            .btn-success {
                background-color: rgb(26, 69, 170) !important;
                border-color: rgb(26, 69, 170) !important;
                color: white !important;
            }

            .btn-success:hover {
                background-color: rgb(26, 69, 170) !important;
                border-color: rgb(26, 69, 170) !important;
            }

            .form-section {
                position: relative;
                border-left: 4px solid rgb(35, 85, 202);
                padding-left: 15px;
                margin-bottom: 25px;
            }

            .form-icon {
                color: rgb(24, 91, 193);
                margin-right: 8px;
            }

            .image-preview {
                border: 2px dashed rgb(28, 97, 200);
                border-radius: 5px;
                padding: 20px;
                text-align: center;
                margin-top: 10px;
                background-color: #f8f9fc;
                transition: all 0.3s;
            }

            .image-preview:hover {
                background-color: #eaecf4;
            }

            #preview-image {
                max-width: 100%;
                max-height: 200px;
                display: none;
                margin: 10px auto;
            }

            .location-input {
                background-color: #f8f9fc;
                border-left: 4px solid rgb(35, 85, 202);
                padding: 10px;
                margin-bottom: 10px;
                border-radius: 5px;
            }

            /* HILANGKAN PANAH INPUT NUMBER */
            input[type=number]::-webkit-inner-spin-button,
            input[type=number]::-webkit-outer-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }

            input[type=number] {
                -moz-appearance: textbox;
            }
        </style>

        <div class="card shadow">
            <div class="card-header text-white sticky-header">
                <h2 class="card-title text-center mb-0 py-3">
                    <i class="fa fa-tags me-2"></i>Tambah Data Harga
                </h2>
            </div>

            <div class="card-body p-4">
                @if ($errors->any())
                    <div class="alert alert-danger border-left-danger shadow-sm">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('prices.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="market_id" value="{{ $market_id }}">
                    <input type="hidden" name="category_id" value="{{ $category_id }}">

                    <div class="form-section">
                        <h5 class="fw-bold mb-3"><i class="fa fa-shopping-basket form-icon"></i>Informasi Barang</h5>

                        <div class="mb-4">
                            <label for="item_name" class="form-label fw-semibold">Nama Barang</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-box"></i></span>
                                <input type="text" id="item_name" name="item_name" class="form-control"
                                    placeholder="Masukkan nama barang" value="{{ old('item_name') }}" required autofocus>
                            </div>
                            <small class="text-muted mt-1 d-block">
                                <i class="fa fa-info-circle"></i> Masukkan nama lengkap barang
                            </small>
                        </div>
                    </div>

                    <div class="form-section">
                        <h5 class="fw-bold mb-3"><i class="fa fa-money-bill form-icon"></i>Informasi Harga</h5>

                        <div class="mb-4">
                            <label for="initial_price" class="form-label fw-semibold">Harga Awal</label>
                            <div class="location-input">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-tag"></i></span>
                                    <input type="number" id="initial_price" name="initial_price" class="form-control"
                                        placeholder="Masukkan harga awal" value="{{ old('initial_price') }}" required>
                                </div>
                                <small class="text-muted mt-1 d-block">
                                    <i class="fa fa-info-circle"></i> Masukkan harga dalam Rupiah (tanpa titik atau koma)
                                </small>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="current_price" class="form-label fw-semibold">Harga Sekarang</label>
                            <div class="location-input">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-tag"></i></span>
                                    <input type="number" id="current_price" name="current_price" class="form-control"
                                        placeholder="Masukkan harga sekarang" value="{{ old('current_price') }}"
                                        onwheel="this.blur()" required>
                                </div>
                                <small class="text-muted mt-1 d-block">
                                    <i class="fa fa-info-circle"></i> Masukkan harga dalam Rupiah (tanpa titik atau koma)
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h5 class="fw-bold mb-3"><i class="fa fa-comment form-icon"></i>Alasan Perubahan</h5>

                        <div class="mb-4">
                            <label for="reason" class="form-label fw-semibold">Alasan Perubahan Harga</label>
                            <div class="location-input">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-comment-alt"></i></span>
                                    <textarea id="reason" name="reason" class="form-control" rows="3"
                                        placeholder="Masukkan alasan perubahan harga...">{{ old('reason') }}</textarea>
                                </div>
                                <small class="text-muted mt-1 d-block">
                                    <i class="fa fa-info-circle"></i> Contoh: Kenaikan harga bahan baku, perubahan biaya
                                    transportasi, dll.
                                </small>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('prices.items', [$market_id, $category_id]) }}"
                        class="btn btn-outline-secondary me-md-2">
                        <i class="fa fa-arrow-left me-1"></i>Kembali
                    </a>
                    <button type="submit" class="btn btn-success shadow">
                        <i class="fa fa-save me-1"></i> Simpan
                    </button>
            </div>

            </form>
        </div>
    </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const formInputs = document.querySelectorAll('.form-control');
            formInputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.closest('.input-group').style.transition = 'all 0.3s';
                    this.closest('.input-group').style.transform = 'translateX(5px)';
                });
                input.addEventListener('blur', function() {
                    this.closest('.input-group').style.transform = 'translateX(0)';
                });
            });
        });

        function disableArrowsAndPage(event) {
            // Tombol yang harus dicegah: panah atas/bawah, PageUp, PageDown
            const forbiddenKeys = ["ArrowUp", "ArrowDown", "PageUp", "PageDown"];
            if (forbiddenKeys.includes(event.key)) {
                event.preventDefault();
                return false;
            }
        };
        document.addEventListener('DOMContentLoaded', function() {
            const formInputs = document.querySelectorAll('.form-control');

            // Tambahkan efek animasi saat focus
            formInputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.closest('.input-group').style.transition = 'all 0.3s';
                    this.closest('.input-group').style.transform = 'translateX(5px)';
                });
                input.addEventListener('blur', function() {
                    this.closest('.input-group').style.transform = 'translateX(0)';
                });
            });

            // Disable scroll dan tombol page/arrow pada input number
            const numberInputs = document.querySelectorAll('input[type=number]');
            numberInputs.forEach(input => {
                input.addEventListener('wheel', function(e) {
                    this.blur(); // agar tidak berubah nilainya
                });

                input.addEventListener('keydown', function(e) {
                    const keysToBlock = ['ArrowUp', 'ArrowDown', 'PageUp', 'PageDown'];
                    if (keysToBlock.includes(e.key)) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
@endsection
