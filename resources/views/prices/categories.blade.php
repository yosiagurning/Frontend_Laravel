    @extends('layouts.app')

    @section('content')
        <div class="container py-5">
            <div class="section-header mb-5 reveal-animation">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <h2 class="fw-bold position-relative section-title">Pilih Kategori</h2>
                    <span class="badge bg-primary rounded-pill category-counter">{{ count($categories) }} kategori</span>
                </div>
                <div class="section-divider mt-3"></div>
            </div>

            <div class="row g-4">
                @foreach ($categories as $category)
                    <div class="col-md-6 col-lg-4 category-item" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <div class="card h-100 border-0 shadow-sm category-card">
                            <div class="card-body p-4">
                                <div class="category-icon-wrapper mb-3">
                                    <i class="bi bi-tag-fill category-icon"></i>
                                </div>

                                <h5 class="card-title fw-bold mb-3">{{ $category['name'] }}</h5>

                                <p class="card-text text-muted mb-3">
                                    {{ $category['description'] ?? 'Tidak ada deskripsi tersedia' }}
                                </p>

                                @if (!empty($category['item_count']))
                                    <div class="item-count mb-3">
                                        <span class="badge bg-light text-dark">
                                            <i class="bi bi-box me-1"></i>
                                            {{ $category['item_count'] }} item tersedia
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <div class="card-footer bg-white border-0 p-4 pt-0">
                                <div class="d-grid gap-2">
                                    <a href="{{ route('prices.items', [$market_id, $category['id']]) }}"
                                        class="btn btn-primary btn-action">
                                        <span>Lihat Harga</span>
                                        <i class="bi bi-arrow-right-circle ms-2 btn-icon"></i>
                                    </a>
                                    <a href="{{ route('prices.chart.category', $category['id']) }}"
                                        class="btn btn-outline-info btn-action-secondary">
                                        <i class="bi bi-graph-up me-2"></i>
                                        <span>Lihat Grafik Kategori</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <a href="{{ route('prices.index') }}" class="btn btn-outline-secondary me-md-2">
            <i class="fa fa-arrow-left me-1"></i>Kembali
        </a>

        @push('styles')
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css">
            <style>
                /* Section Styling */
                .section-title {
                    font-size: 2rem;
                    color: #333;
                }

                .section-title::after {
                    content: '';
                    display: block;
                    width: 50px;
                    height: 3px;
                    background: linear-gradient(90deg, #4361ee, #7209b7);
                    margin-top: 8px;
                }

                .section-divider {
                    height: 1px;
                    background: linear-gradient(90deg, rgba(67, 97, 238, 0.3), rgba(114, 9, 183, 0.1));
                    width: 100%;
                }

                /* Category Counter */
                .category-counter {
                    font-size: 0.9rem;
                    padding: 0.5rem 1rem;
                    background: linear-gradient(90deg, #4361ee, #7209b7);
                    border: none;
                }

                /* Card Styling */
                .category-card {
                    border-radius: 12px;
                    overflow: hidden;
                    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                    border-bottom: 4px solid transparent;
                }

                .category-card:hover {
                    transform: translateY(-10px);
                    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1) !important;
                    border-bottom: 4px solid #4361ee;
                }

                .category-icon-wrapper {
                    width: 50px;
                    height: 50px;
                    border-radius: 12px;
                    background: linear-gradient(135deg, #4361ee, #7209b7);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }

                .category-icon {
                    color: white;
                    font-size: 1.5rem;
                }

                .item-count {
                    font-size: 0.85rem;
                }

                .item-count .badge {
                    padding: 0.5rem 0.8rem;
                    border-radius: 50px;
                }

                /* Button Styling */
                .btn-action {
                    padding: 0.8rem 1.5rem;
                    border-radius: 50px;
                    font-weight: 600;
                    position: relative;
                    overflow: hidden;
                    z-index: 1;
                    transition: all 0.3s ease;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }

                .btn-action::before {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 0%;
                    height: 100%;
                    background: rgba(255, 255, 255, 0.1);
                    z-index: -1;
                    transition: all 0.5s ease;
                }

                .btn-action:hover::before {
                    width: 100%;
                }

                .btn-action:hover .btn-icon {
                    transform: translateX(5px);
                }

                .btn-icon {
                    transition: transform 0.3s ease;
                }

                .btn-action-secondary {
                    border-radius: 50px;
                    font-weight: 500;
                    transition: all 0.3s ease;
                }

                .btn-action-secondary:hover {
                    background-color: rgba(13, 202, 240, 0.1);
                    transform: translateY(-2px);
                }

                /* Animation Classes */
                .reveal-animation {
                    animation: revealFromBottom 0.8s ease-out forwards;
                }

                @keyframes revealFromBottom {
                    0% {
                        opacity: 0;
                        transform: translateY(30px);
                    }

                    100% {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }

                .category-item {
                    opacity: 0;
                }
            </style>
        @endpush

        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Initialize AOS animation library
                    AOS.init({
                        duration: 800,
                        easing: 'ease-out-cubic',
                        once: true
                    });

                    // Add hover effect for cards
                    const cards = document.querySelectorAll('.category-card');

                    cards.forEach(card => {
                        card.addEventListener('mouseenter', function() {
                            this.classList.add('card-hover');
                        });

                        card.addEventListener('mouseleave', function() {
                            this.classList.remove('card-hover');
                        });
                    });
                });
            </script>
        @endpush
    @endsection
