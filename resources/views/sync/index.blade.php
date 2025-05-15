@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow">
        <div class="card-header text-white" style="background: linear-gradient(135deg, rgb(41, 100, 210) 0%, rgb(34, 108, 177) 100%);">
            <h2 class="card-title text-center mb-0 py-3">
                <i class="fa fa-sync me-2"></i>Sinkronisasi Data
            </h2>
        </div>

        <div class="card-body p-4">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <div class="text-center mb-4">
                <div class="display-1 text-primary mb-3">
                    <i class="fa fa-exchange-alt"></i>
                </div>
                <h3 class="mb-3">Sinkronisasi Data Barang & Harga</h3>
                <p class="lead">
                    Fitur ini akan menyinkronkan data antara tabel <strong>barang</strong> (aplikasi mobile) dan 
                    tabel <strong>price</strong> (aplikasi web).
                </p>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="fa fa-mobile-alt me-2 text-primary"></i>
                                Data Mobile (Barang)
                            </h5>
                            <p class="card-text">
                                Data dari aplikasi mobile Flutter yang disimpan dalam tabel <code>barangs</code>.
                                Memiliki kolom <code>harga_sekarang</code> yang perlu disinkronkan.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="fa fa-globe me-2 text-primary"></i>
                                Data Web (Price)
                            </h5>
                            <p class="card-text">
                                Data dari aplikasi web Laravel yang disimpan dalam tabel <code>prices</code>.
                                Memiliki kolom <code>current_price</code> yang perlu disinkronkan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="alert alert-info">
                <i class="fa fa-info-circle me-2"></i>
                Sinkronisasi akan:
                <ul class="mb-0 mt-2">
                    <li>Memastikan setiap barang di tabel <code>barangs</code> memiliki data di tabel <code>prices</code></li>
                    <li>Memastikan setiap harga di tabel <code>prices</code> memiliki data di tabel <code>barangs</code></li>
                    <li>Menyamakan nilai <code>harga_sekarang</code> dengan <code>current_price</code></li>
                    <li>Mencatat perubahan dalam tabel histori</li>
                </ul>
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('sync.data') }}" class="btn btn-primary btn-lg px-5">
                    <i class="fa fa-sync me-2"></i>Sinkronkan Sekarang
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
