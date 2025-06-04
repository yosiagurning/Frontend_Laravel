@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="section-header mb-5 reveal-animation">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <h2 class="fw-bold position-relative section-title">Pilih Kategori</h2>
                <span class="badge bg-primary rounded-pill category-counter">
                    <span id="category-count">{{ count($categories) }}</span> kategori
                </span>
            </div>
            <div class="section-divider mt-3"></div>
        </div>

        <!-- Search Section -->
        <div class="search-section mb-4" data-aos="fade-down">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="search-wrapper">
                        <div class="input-group search-input-group">
                            <span class="input-group-text search-icon">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" 
                                   class="form-control search-input" 
                                   id="categorySearch" 
                                   placeholder="Cari kategori berdasarkan nama..."
                                   autocomplete="off">
                            <button class="btn btn-outline-secondary clear-search" 
                                    type="button" 
                                    id="clearSearch" 
                                    style="display: none;">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories Grid -->
        <div class="row g-4" id="categoriesGrid">
            @foreach ($categories as $category)
                <div class="col-md-6 col-lg-4 category-item" 
                     data-aos="fade-up" 
                     data-aos-delay="{{ $loop->index * 100 }}"
                     data-category-name="{{ strtolower($category['name']) }}">
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

        <!-- No Results Message -->
        <div class="no-results text-center py-5" id="noResults" style="display: none;">
            <div class="no-results-icon mb-3">
                <i class="bi bi-search text-muted" style="font-size: 3rem;"></i>
            </div>
            <h4 class="text-muted mb-2">Kategori tidak ditemukan</h4>
            <p class="text-muted">Coba gunakan kata kunci yang berbeda atau periksa ejaan Anda.</p>
            <button class="btn btn-outline-primary" onclick="clearSearch()">
                <i class="bi bi-arrow-clockwise me-2"></i>Reset Pencarian
            </button>
        </div>

        <a href="{{ route('prices.index') }}" class="btn btn-outline-secondary me-md-2 mt-4">
            <i class="fa fa-arrow-left me-1"></i>Kembali
        </a>
    </div>

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

            /* Search Section Styling */
            .search-section {
                margin-bottom: 2rem;
            }

            .search-wrapper {
                position: relative;
            }

            .search-input-group {
                border-radius: 50px;
                overflow: hidden;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
                transition: all 0.3s ease;
            }

            .search-input-group:focus-within {
                box-shadow: 0 6px 20px rgba(67, 97, 238, 0.2);
                transform: translateY(-2px);
            }

            .search-input {
                border: none;
                padding: 1rem 1.5rem;
                font-size: 1rem;
                background: white;
            }

            .search-input:focus {
                box-shadow: none;
                border: none;
                outline: none;
            }

            .search-icon {
                background: white;
                border: none;
                color: #6c757d;
                padding: 1rem 1.5rem;
            }

            .clear-search {
                border: none;
                background: white;
                color: #6c757d;
                padding: 0.5rem 1rem;
                transition: all 0.3s ease;
            }

            .clear-search:hover {
                color: #dc3545;
                background: white;
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
                transition: all 0.3s ease;
            }

            .category-item.show {
                opacity: 1;
            }

            .category-item.hide {
                opacity: 0;
                transform: scale(0.8);
                pointer-events: none;
            }

            /* No Results Styling */
            .no-results {
                animation: fadeIn 0.5s ease-in-out;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Search Highlight */
            .search-highlight {
                background-color: #fff3cd;
                padding: 2px 4px;
                border-radius: 3px;
                font-weight: bold;
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

                // Search functionality
                const searchInput = document.getElementById('categorySearch');
                const clearButton = document.getElementById('clearSearch');
                const categoryItems = document.querySelectorAll('.category-item');
                const noResults = document.getElementById('noResults');
                const categoryCount = document.getElementById('category-count');
                const totalCategories = categoryItems.length;

                // Search function
                function performSearch() {
                    const searchTerm = searchInput.value.toLowerCase().trim();
                    let visibleCount = 0;

                    categoryItems.forEach(item => {
                        const categoryName = item.getAttribute('data-category-name');
                        const isMatch = categoryName.includes(searchTerm);

                        if (isMatch) {
                            item.style.display = 'block';
                            item.classList.remove('hide');
                            item.classList.add('show');
                            visibleCount++;
                            
                            // Highlight search term
                            highlightSearchTerm(item, searchTerm);
                        } else {
                            item.classList.remove('show');
                            item.classList.add('hide');
                            setTimeout(() => {
                                if (item.classList.contains('hide')) {
                                    item.style.display = 'none';
                                }
                            }, 300);
                        }
                    });

                    // Update counter
                    categoryCount.textContent = visibleCount;

                    // Show/hide no results message
                    if (visibleCount === 0 && searchTerm !== '') {
                        noResults.style.display = 'block';
                    } else {
                        noResults.style.display = 'none';
                    }

                    // Show/hide clear button
                    if (searchTerm !== '') {
                        clearButton.style.display = 'block';
                    } else {
                        clearButton.style.display = 'none';
                    }
                }

                // Highlight search term in category names
                function highlightSearchTerm(item, searchTerm) {
                    if (searchTerm === '') return;

                    const titleElement = item.querySelector('.card-title');
                    const originalText = titleElement.textContent;
                    
                    if (searchTerm.length > 0) {
                        const regex = new RegExp(`(${searchTerm})`, 'gi');
                        const highlightedText = originalText.replace(regex, '<span class="search-highlight">$1</span>');
                        titleElement.innerHTML = highlightedText;
                    }
                }

                // Clear search function
                function clearSearch() {
                    searchInput.value = '';
                    performSearch();
                    searchInput.focus();
                }

                // Event listeners
                searchInput.addEventListener('input', performSearch);
                searchInput.addEventListener('keyup', function(e) {
                    if (e.key === 'Escape') {
                        clearSearch();
                    }
                });

                clearButton.addEventListener('click', clearSearch);

                // Global clear search function
                window.clearSearch = clearSearch;

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

                // Show all categories initially
                setTimeout(() => {
                    categoryItems.forEach(item => {
                        item.classList.add('show');
                    });
                }, 100);
            });
        </script>
    @endpush
@endsection