@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Pilih Pasar</h2>
            <span class="text-muted small">Menampilkan {{ isset($markets) && is_array($markets) ? count($markets) : 0 }}
                pasar</span>
        </div>

        <div class="row g-4">
            @forelse($markets as $market)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm overflow-hidden transition-hover">
                        <div class="position-relative">
                            @if (!empty($market['image_url']))
                                @php
                                    $imageUrl = !empty($market['image_url'])
                                        ? (filter_var($market['image_url'], FILTER_VALIDATE_URL)
                                            ? $market['image_url']
                                            : asset($market['image_url']))
                                        : asset('images/default-market.jpg');
                                @endphp
                                <img src="{{ $imageUrl }}" class="card-img-top" style="height: 200px; object-fit: cover;"
                                    alt="Gambar {{ $market['name'] }}">
                            @else
                                <img src="{{ asset('images/default-market.jpg') }}" class="card-img-top"
                                    style="height: 200px; object-fit: cover;" alt="Default Market">
                            @endif

                            @if (!empty($market['is_popular']) && $market['is_popular'])
                                <span class="position-absolute top-0 end-0 badge bg-danger m-3">Populer</span>
                            @endif
                        </div>

                        <div class="card-body">
                            <h5 class="card-title fw-bold mb-2">{{ $market['name'] }}</h5>
                            <div class="d-flex align-items-start mb-3 text-muted">
                                <i class="bi bi-geo-alt me-2 mt-1"></i>
                                <p class="card-text small mb-0">{{ $market['location'] }}</p>
                            </div>
                        </div>

                        <div class="card-footer bg-white border-0 pt-0">
                            <a href="{{ route('prices.categories', $market['id']) }}"
                                class="btn btn-primary w-100 d-flex align-items-center justify-content-center">
                                Lihat Kategori
                                <i class="bi bi-chevron-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center py-4">
                        <i class="bi bi-info-circle me-2"></i>
                        Tidak ada data pasar ditemukan.
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <style>
        .transition-hover {
            transition: all 0.3s ease;
        }

        .transition-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
        }
    </style>
@endsection
