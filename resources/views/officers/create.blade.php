@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <style>
            .form-section {
                position: relative;
                border-left: 4px solid rgb(35, 85, 202);
                padding-left: 15px;
                margin-bottom: 25px;
            }

            .bg-gradient-success {
                background: linear-gradient(135deg, rgb(41, 100, 210) 0%, rgb(34, 108, 177) 100%);
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
            
            .invalid-feedback {
                display: none;
                color: #dc3545;
                font-size: 0.875em;
                margin-top: 0.25rem;
            }
            
            input:invalid + .invalid-feedback {
                display: block;
            }
            
            .is-invalid {
                border-color: #dc3545;
                padding-right: calc(1.5em + 0.75rem);
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
                background-repeat: no-repeat;
                background-position: right calc(0.375em + 0.1875rem) center;
                background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
            }
        </style>

        <div class="card shadow">
            <div class="card-header bg-gradient-success text-white">
                <h2 class="card-title text-center mb-0 py-3">
                    <i class="fa fa-user-tie me-2"></i>Tambah Petugas Pasar
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

@if (session('error'))
    <div class="alert alert-danger d-flex align-items-center mb-3">
        <i class="fas fa-exclamation-circle me-2"></i>
        <div>{{ session('error') }}</div>
    </div>
@endif

                <form action="{{ route('officers.store') }}" method="POST" enctype="multipart/form-data" id="officerForm">
                    @csrf

                    <div class="form-section">
                        <h5 class="fw-bold mb-3"><i class="fa fa-id-badge form-icon"></i>Data Petugas</h5>

                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Nama</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="nik" class="form-label fw-semibold">NIK</label>
                            <input type="text" name="nik" id="nik" class="form-control" required maxlength="16" 
                                   inputmode="numeric" oninput="validateNIK(this)">
                            <div class="invalid-feedback" id="nikFeedback">
                                NIK harus terdiri dari 16 angka.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label fw-semibold">No Telepon</label>
                            <input type="text" name="phone" id="phone" class="form-control" required maxlength="13" 
                                   inputmode="numeric" oninput="validatePhone(this)">
                            <div class="invalid-feedback" id="phoneFeedback">
                                Nomor telepon maksimal 13 angka.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="username" class="form-label fw-semibold">Username</label>
                            <input type="text" name="username" id="username" class="form-control" required minlength="5">
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Password</label>
                            <div class="input-group">
                                <input type="password" name="password" id="password" class="form-control" required
                                    minlength="8">
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">
                                    <i id="toggleIcon" class="fas fa-eye"></i>
                                </button>
                            </div>
                            <small class="text-muted"><i class="fa fa-info-circle"></i> Minimal 8 karakter</small>
                        </div>
                    </div>

                    <div class="form-section">
                        <h5 class="fw-bold mb-3"><i class="fa fa-store-alt form-icon"></i>Penempatan Pasar</h5>

                        <div class="mb-3">
                            <label for="market_id" class="form-label fw-semibold">Pilih Pasar</label>
                            <select name="market_id" id="market_id" class="form-control" required>
                                <option value="" disabled selected>-- Pilih Pasar --</option>
                                @foreach ($markets as $market)
                                    <option value="{{ $market->id }}">{{ $market->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-section">
                        <h5 class="fw-bold mb-3"><i class="fa fa-image form-icon"></i>Foto Petugas</h5>

                        <div class="mb-3">
                            <label for="image" class="form-label fw-semibold">Upload Foto</label>
                            <div class="image-preview">
                                <input type="file" name="image" id="image" class="form-control" accept="image/*"
                                    onchange="previewImage(this)">
                                <img id="preview-image" src="#" alt="Preview Gambar">
                                <small class="text-muted mt-2 d-block">
                                    <i class="fa fa-info-circle"></i> Format: JPG, PNG, max 2MB.
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <a href="{{ route('officers.index') }}" class="btn btn-outline-secondary me-md-2">
                            <i class="fa fa-arrow-left me-1"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-custom-blue shadow" id="submitBtn">
                            <i class="fa fa-save me-1"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
        function togglePassword() {
            const passwordField = document.getElementById("password");
            const toggleIcon = document.getElementById("toggleIcon");

            if (passwordField.type === "password") {
                passwordField.type = "text";
                toggleIcon.classList.remove("fa-eye");
                toggleIcon.classList.add("fa-eye-slash");
            } else {
                passwordField.type = "password";
                toggleIcon.classList.remove("fa-eye-slash");
                toggleIcon.classList.add("fa-eye");
            }
        }

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
        
        function validateNIK(input) {
            // Only allow digits
            input.value = input.value.replace(/[^0-9]/g, '');
            
            // Check if length is exactly 16
            if (input.value.length !== 16 && input.value.length > 0) {
                input.classList.add('is-invalid');
                document.getElementById('nikFeedback').style.display = 'block';
            } else {
                input.classList.remove('is-invalid');
                document.getElementById('nikFeedback').style.display = 'none';
            }
        }
        
        function validatePhone(input) {
            // Only allow digits
            input.value = input.value.replace(/[^0-9]/g, '');
            
            // Check if length is more than 13
            if (input.value.length > 13) {
                input.value = input.value.substring(0, 13);
                input.classList.add('is-invalid');
                document.getElementById('phoneFeedback').style.display = 'block';
            } else {
                input.classList.remove('is-invalid');
                document.getElementById('phoneFeedback').style.display = 'none';
            }
        }
        
        // Form validation before submit
        document.getElementById('officerForm').addEventListener('submit', function(event) {
            const nikInput = document.getElementById('nik');
            const phoneInput = document.getElementById('phone');
            let isValid = true;
            
            // Validate NIK
            if (nikInput.value.length !== 16) {
                nikInput.classList.add('is-invalid');
                document.getElementById('nikFeedback').style.display = 'block';
                isValid = false;
            }
            
            // Validate Phone (optional, as we're already limiting input to 13 characters)
            if (phoneInput.value.length > 13) {
                phoneInput.classList.add('is-invalid');
                document.getElementById('phoneFeedback').style.display = 'block';
                isValid = false;
            }
            
            if (!isValid) {
                event.preventDefault();
                // Scroll to the first invalid field
                const firstInvalid = document.querySelector('.is-invalid');
                if (firstInvalid) {
                    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
    </script>


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">


    <style>
        .btn-custom-blue {
            background-color: rgb(35, 85, 202);
            color: #fff;
            border: none;
            transition: background-color 0.3s ease;
        }

        .btn-custom-blue:hover {
            background-color: rgb(26, 69, 170);
        }
    </style>
@endsection