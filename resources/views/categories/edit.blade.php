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
        .btn-primary {
            background-color: rgb(26, 69, 170) !important;
            border-color: rgb(26, 69, 170) !important;
            color: white !important;
        }
        .btn-primary:hover {
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
    </style>

    <div class="card shadow">
        <div class="card-header text-white sticky-header">
            <h2 class="card-title text-center mb-0 py-3">
                <i class="fa fa-edit me-2"></i>Edit Kategori
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

            <form action="{{ route('categories.update', $category['id']) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-section">
                    <h5 class="fw-bold mb-3"><i class="fa fa-info-circle form-icon"></i>Informasi Kategori</h5>

                    <div class="mb-4">
                        <label for="name" class="form-label fw-semibold">Nama Kategori</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-tag"></i></span>
                            <input type="text" name="name" id="name" class="form-control" value="{{ $category['name'] }}" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label fw-semibold">Deskripsi</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-align-left"></i></span>
                            <textarea name="description" id="description" class="form-control" rows="4">{{ $category['description'] }}</textarea>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Pilih Pasar</label><br>
                        @foreach ($markets as $market)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="market_ids[]" value="{{ $market['id'] }}"
                                    {{ in_array($market['id'], $selectedMarkets ?? []) ? 'checked' : '' }}>
                                <label class="form-check-label">{{ $market['name'] }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                    <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary me-md-2">
                        <i class="fa fa-arrow-left me-1"></i>Kembali
                    </a>
                    <button type="submit" class="btn btn-primary shadow">
                        <i class="fa fa-save me-1"></i>Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const formInputs = document.querySelectorAll('.form-control');
    formInputs.forEach(input => {
        input.addEventListener('focus', function () {
            this.closest('.input-group').style.transition = 'all 0.3s';
            this.closest('.input-group').style.transform = 'translateX(5px)';
        });
        input.addEventListener('blur', function () {
            this.closest('.input-group').style.transform = 'translateX(0)';
        });
    });
});
</script>
@endsection
