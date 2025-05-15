@extends('layouts.app')

@section('content')
<div class="container py-4">
    <style>
        .form-section {
            position: relative;
            border-left: 4px solid rgb(26, 69, 170);
            padding-left: 15px;
            margin-bottom: 25px;
        }
        .bg-gradient-primary {
            background: linear-gradient(135deg, rgb(26, 69, 170) 0%, rgb(26, 69, 170) 100%);
        }
        .form-icon {
            color: rgb(26, 69, 170);
            margin-right: 8px;
        }
    </style>

    <div class="card shadow">
        <div class="card-header bg-gradient-primary text-white">
            <h2 class="card-title text-center mb-0 py-3">
                <i class="fa fa-tags me-2"></i>Tambah Kategori
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

            <form action="{{ route('categories.store') }}" method="POST">
                @csrf

                <div class="form-section">
                    <h5 class="fw-bold mb-3"><i class="fa fa-info-circle form-icon"></i>Informasi Kategori</h5>

                    <div class="mb-4">
                        <label for="name" class="form-label fw-semibold">Nama Kategori</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label fw-semibold">Deskripsi</label>
                        <textarea class="form-control" id="description" name="description" rows="4" placeholder="Deskripsi singkat kategori..."></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Pilih Pasar</label><br>
                        @foreach ($markets as $market)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="market_ids[]" value="{{ $market['id'] }}">
                                <label class="form-check-label">{{ $market['name'] }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                    <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary me-md-2">
                        <i class="fa fa-arrow-left me-1"></i>Batal
                    </a>
                    <button type="submit" class="btn shadow" style="background-color: rgb(26, 69, 170); border-color: rgb(26, 69, 170); color: white;">
                        <i class="fa fa-save me-1"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
