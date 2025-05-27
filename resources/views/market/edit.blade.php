@extends('layouts.app')

@section('content')
<div class="container py-4">
    <style>
        .header-section {
            /* Menghapus position sticky agar header bisa bergerak dengan scroll */
            position: relative;
            background: linear-gradient(135deg, rgb(41, 100, 210) 0%, rgb(34, 108, 177) 100%);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        /* Alternatif: Header yang smooth hide/show saat scroll */
        .floating-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background: linear-gradient(135deg, rgb(41, 100, 210) 0%, rgb(34, 108, 177) 100%);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            transform: translateY(-100%);
            transition: transform 0.3s ease;
            display: none;
        }

        .floating-header.show {
            transform: translateY(0);
            display: block;
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

        /* Smooth scrolling untuk seluruh halaman */
        html {
            scroll-behavior: smooth;
        }

        /* Padding top untuk kompensasi floating header */
        .content-wrapper {
            padding-top: 20px;
        }
    </style>

    <!-- Floating Header (muncul saat scroll) -->
    <div class="floating-header" id="floatingHeader">
        <div class="container">
            <div class="card-header text-white">
                <h2 class="card-title text-center mb-0 py-3">
                    <i class="fa fa-edit me-2"></i>Edit Pasar
                </h2>
            </div>
        </div>
    </div>

    <div class="card shadow">
        <!-- Header utama yang bergerak dengan scroll -->
        <div class="card-header text-white header-section" id="mainHeader">
            <h2 class="card-title text-center mb-0 py-3">
                <i class="fa fa-edit me-2"></i>Edit Pasar
            </h2>
        </div>

        <div class="card-body p-4 content-wrapper">
            @if ($errors->any())
                <div class="alert alert-danger border-left-danger shadow-sm">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('market.update', $market['id']) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-section">
                    <h5 class="fw-bold mb-3"><i class="fa fa-info-circle form-icon"></i>Informasi Pasar</h5>

                    <div class="mb-4">
                        <label for="name" class="form-label fw-semibold">Nama Pasar</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-store"></i></span>
                            <input type="text" name="name" id="name" class="form-control" value="{{ $market['name'] }}" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="location" class="form-label fw-semibold">Lokasi</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-map-marker-alt"></i></span>
                            <input type="text" name="location" id="location" class="form-control" value="{{ $market['location'] }}" required>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h5 class="fw-bold mb-3"><i class="fa fa-image form-icon"></i>Foto Pasar</h5>

                    <div class="mb-4">
                        <label for="image" class="form-label fw-semibold">Upload Gambar Baru</label>
                        <div class="image-preview">
                            <input type="file" name="image" id="image" class="form-control" accept="image/*" onchange="previewImage(this)">
                            <img id="preview-image" src="#" alt="Preview Gambar" class="img-fluid rounded">
                            <small class="text-muted mt-2 d-block">
                                <i class="fa fa-info-circle"></i> Format: JPG, PNG. Maksimal: 2MB.
                            </small>
                        </div>

                        @if(!empty($market['image']))
                            <div class="mt-3">
                                <p class="mb-1">Gambar saat ini:</p>
                                <img src="{{ asset($market['image_url']) }}" alt="Gambar Pasar" width="120" class="img-thumbnail">
                            </div>
                        @endif
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                    <a href="{{ route('market.index') }}" class="btn btn-outline-secondary me-md-2">
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
function previewImage(input) {
    var preview = document.getElementById('preview-image');
    if (!input.files.length) {
        preview.style.display = 'none';
        preview.src = '#';
        return;
    }
    var reader = new FileReader();
    reader.onload = function(e) {
        preview.src = e.target.result;
        preview.style.display = 'block';
    }
    reader.readAsDataURL(input.files[0]);
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

    // Spinner ketika form disubmit
    const form = document.querySelector('form');
    form.addEventListener('submit', function () {
        const btn = form.querySelector('button[type="submit"]');
        btn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Updating...`;
        btn.disabled = true;
    });

    // Header scroll behavior
    const mainHeader = document.getElementById('mainHeader');
    const floatingHeader = document.getElementById('floatingHeader');
    let lastScrollTop = 0;

    window.addEventListener('scroll', function() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const headerHeight = mainHeader.offsetHeight;
        
        // Jika scroll melewati header utama, tampilkan floating header
        if (scrollTop > headerHeight) {
            floatingHeader.classList.add('show');
        } else {
            floatingHeader.classList.remove('show');
        }

        // Optional: Hide floating header saat scroll ke bawah, show saat scroll ke atas
        if (scrollTop > lastScrollTop && scrollTop > headerHeight + 100) {
            // Scroll ke bawah - sembunyikan floating header
            floatingHeader.style.transform = 'translateY(-100%)';
        } else if (scrollTop < lastScrollTop) {
            // Scroll ke atas - tampilkan floating header
            if (scrollTop > headerHeight) {
                floatingHeader.style.transform = 'translateY(0)';
            }
        }
        
        lastScrollTop = scrollTop;
    });

    // Smooth scroll untuk anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});
</script>
@endsection