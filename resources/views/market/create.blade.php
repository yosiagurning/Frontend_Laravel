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
    </style>

    <div class="card shadow">
        <div class="card-header text-white sticky-header">
            <h2 class="card-title text-center mb-0 py-3">
                <i class="fa fa-store me-2"></i>Tambah Pasar Baru
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

            <form action="{{ route('market.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-section">
                    <h5 class="fw-bold mb-3"><i class="fa fa-info-circle form-icon"></i>Informasi Pasar</h5>

                    <div class="mb-4">
                        <label for="name" class="form-label fw-semibold">Nama Pasar</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-store"></i></span>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Masukkan nama pasar" value="{{ old('name') }}" required>
                        </div>
                        <small class="text-muted mt-1 d-block">
                            <i class="fa fa-info-circle"></i> Masukkan nama lengkap pasar
                        </small>
                    </div>

                    <div class="mb-4">
                        <label for="location" class="form-label fw-semibold">Lokasi</label>
                        <div class="location-input">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-map-marker-alt"></i></span>
                                <input type="text" name="location" id="location" class="form-control" placeholder="Masukkan alamat lengkap pasar" value="{{ old('location') }}" required>
                            </div>
                            <small class="text-muted mt-1 d-block">
                                <i class="fa fa-info-circle"></i> Contoh: Jl. Sisingamangaraja No. 7, Balige
                            </small>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h5 class="fw-bold mb-3"><i class="fa fa-image form-icon"></i>Foto Pasar</h5>

                    <div class="mb-4">
                        <label for="image" class="form-label fw-semibold">Upload Gambar</label>
                        <div class="image-preview">
                            <input type="file" name="image" id="image" class="form-control" accept="image/*" onchange="previewImage(this)">
                            <img id="preview-image" src="#" alt="Preview Gambar">
                            <small class="text-muted mt-2 d-block">
                                <i class="fa fa-info-circle"></i> Format yang didukung: JPG, PNG. Ukuran maksimal: 2MB
                            </small>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                    <a href="{{ route('market.index') }}" class="btn btn-outline-secondary me-md-2">
                        <i class="fa fa-arrow-left me-1"></i>Kembali
                    </a>
                    <button type="submit" class="btn btn-success shadow">
                        <i class="fa fa-save me-1"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    var preview = document.getElementById('preview-image');
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.style.display = 'none';
    }
}

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
