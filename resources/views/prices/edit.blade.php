@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <style>
            /* Modern Professional Styling */
            :root {
                --primary-color: rgb(41, 100, 210);
                --primary-dark: rgb(26, 69, 170);
                --danger-color: #dc3545;
                --danger-dark: #bd2130;
                --border-radius: 0.375rem;
                --transition: all 0.3s ease;
            }

            .sticky-header {
                position: sticky;
                top: 0;
                z-index: 999;
                background: linear-gradient(135deg, var(--primary-color) 0%, rgb(34, 108, 177) 100%);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                border-radius: var(--border-radius) var(--border-radius) 0 0;
            }

            .btn-success {
                background-color: var(--primary-dark) !important;
                border-color: var(--primary-dark) !important;
                color: white !important;
                transition: var(--transition);
            }

            .btn-success:hover {
                background-color: var(--primary-color) !important;
                border-color: var(--primary-color) !important;
                transform: translateY(-2px);
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }

            .btn-export {
                background-color: var(--danger-color) !important;
                border-color: var(--danger-color) !important;
                color: white !important;
                transition: var(--transition);
            }

            .btn-export:hover {
                background-color: var(--danger-dark) !important;
                border-color: var(--danger-dark) !important;
                transform: translateY(-2px);
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }

            .form-section {
                position: relative;
                border-left: 4px solid var(--primary-color);
                padding-left: 15px;
                margin-bottom: 30px;
                transition: var(--transition);
            }

            .form-section:hover {
                border-left-width: 6px;
            }

            .form-icon {
                color: var(--primary-color);
                margin-right: 8px;
            }

            .image-preview {
                border: 2px dashed var(--primary-color);
                border-radius: var(--border-radius);
                padding: 20px;
                text-align: center;
                margin-top: 10px;
                background-color: #f8f9fc;
                transition: var(--transition);
            }

            .image-preview:hover {
                background-color: #eaecf4;
            }

            #preview-image {
                max-width: 100%;
                max-height: 200px;
                display: none;
                margin: 10px auto;
                border-radius: var(--border-radius);
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }

            .location-input {
                background-color: #f8f9fc;
                border-left: 4px solid var(--primary-color);
                padding: 15px;
                margin-bottom: 15px;
                border-radius: var(--border-radius);
                transition: var(--transition);
            }

            .location-input:hover {
                background-color: #f0f2f8;
            }

            .readonly-field {
                background-color: #f2f4f8 !important;
                cursor: not-allowed;
                border-color: #d1d3e2 !important;
            }

            .readonly-notice {
                color: #6c757d;
                font-style: italic;
                font-size: 0.8rem;
                margin-top: 0.25rem;
            }

            .card {
                border: none;
                border-radius: var(--border-radius);
                overflow: hidden;
                transition: var(--transition);
            }

            .card:hover {
                box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            }

            .input-group {
                transition: var(--transition);
            }

            .input-group:focus-within {
                box-shadow: 0 0 0 0.25rem rgba(41, 100, 210, 0.15);
            }

            .input-group-text {
                background-color: #f8f9fc;
                border-color: #d1d3e2;
            }

            .form-control {
                border-color: #d1d3e2;
                transition: var(--transition);
            }

            .form-control:focus {
                border-color: var(--primary-color);
                box-shadow: none;
            }

            /* Perbaikan spinner hilang di input number */
            input[type=number]::-webkit-inner-spin-button,
            input[type=number]::-webkit-outer-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }

            input[type=number] {
                -moz-appearance: textfield;
            }

            /* Alert styling */
            .alert-danger {
                border-left: 4px solid #dc3545;
                background-color: #f8d7da;
                color: #721c24;
            }
        </style>

        <div class="card shadow">
            <div class="card-header text-white sticky-header">
                <h2 class="card-title text-center mb-0 py-3">
                    <i class="fa fa-edit me-2"></i>Edit Data Harga
                </h2>
            </div>

            <div class="card-body p-4">
                @if ($errors->any())
                    <div class="alert alert-danger border-left-danger shadow-sm mb-4">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('prices.update', $price['id']) }}" method="POST">
                    @csrf @method('PUT')

                    <input type="hidden" name="market_id" value="{{ $price['market_id'] }}">
                    <input type="hidden" name="category_id" value="{{ $price['category_id'] }}">
                    <input type="hidden" name="item_id" value="{{ $price['item_id'] }}">

                    <div class="form-section">
                        <h5 class="fw-bold mb-3"><i class="fa fa-shopping-basket form-icon"></i>Informasi Barang</h5>

                        <div class="mb-4">
                            <label for="item_name" class="form-label fw-semibold">Nama Barang</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-box"></i></span>
                                <input type="text" id="item_name" class="form-control readonly-field"
                                    value="{{ $price['item_name'] }}" readonly>
                                <input type="hidden" name="item_name" value="{{ $price['item_name'] }}">
                            </div>
                            <small class="text-muted mt-1 d-block readonly-notice">
                                <i class="fa fa-lock"></i> Nama barang tidak dapat diubah
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
                                    <input type="number" id="initial_price" class="form-control readonly-field"
                                        value="{{ $price['current_price'] }}" readonly>
                                    <input type="hidden" name="initial_price" value="{{ $price['initial_price'] }}">
                                </div>
                                <small class="text-muted mt-1 d-block readonly-notice">
                                    <i class="fa fa-lock"></i> Harga awal tidak dapat diubah
                                </small>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="current_price" class="form-label fw-semibold">Harga Sekarang</label>
                            <div class="location-input">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-money-bill-wave"></i></span>
                                    <input type="number" id="current_price" name="current_price" class="form-control"
                                        placeholder="Masukkan harga sekarang" onwheel="this.blur()"
                                        onkeydown="return disableArrowsAndPage(event)" value="" required>
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
                                        placeholder="Masukkan alasan perubahan harga...">{{ $price['reason'] }}</textarea>
                                </div>
                                <small class="text-muted mt-1 d-block">
                                    <i class="fa fa-info-circle"></i> Contoh: Kenaikan harga bahan baku, perubahan biaya
                                    transportasi, dll.
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary me-md-2">
                            <i class="fa fa-arrow-left me-1"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-success shadow">
                            <i class="fa fa-save me-1"></i>Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animation for form inputs
            const formInputs = document.querySelectorAll('.form-control:not(.readonly-field)');
            formInputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.closest('.input-group').style.transition = 'all 0.3s';
                    this.closest('.input-group').style.transform = 'translateX(5px)';
                });
                input.addEventListener('blur', function() {
                    this.closest('.input-group').style.transform = 'translateX(0)';
                });
            });

            // Hover effect for form sections
            const formSections = document.querySelectorAll('.form-section');
            formSections.forEach(section => {
                section.addEventListener('mouseenter', function() {
                    this.style.transition = 'all 0.3s';
                    this.style.backgroundColor = '#f8f9fc';
                    this.style.borderRadius = '0.375rem';
                });
                section.addEventListener('mouseleave', function() {
                    this.style.backgroundColor = 'transparent';
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
        }
    </script>
@endsection
