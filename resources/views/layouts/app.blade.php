<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Harga Pasar</title>
    <link rel="icon" href="{{ asset('images/Partoba(Font Putih).png') }}" type="image/png">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root {
            --primary-color: #0066da;
            --primary-dark: #004db3;
            --primary-light: #4d94ff;
            --secondary-color: #f8f9fa;
            --text-light: #ffffff;
            --text-dark: #333333;
            --sidebar-width: 280px;
            --sidebar-width-collapsed: 80px;
            --topbar-height: 70px;
            --transition-speed: 0.3s;
        }
        
        html, body {
            height: 100%;
            overflow: hidden; /* Prevent body scroll */
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            opacity: 0;
            animation: fadeIn 0.4s ease-out forwards 0.2s;
            margin: 0;
            padding: 0;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        /* Professional Preloader */
        .preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.5s ease, visibility 0.5s ease;
        }
        
        .preloader.fade-out {
            opacity: 0;
            visibility: hidden;
        }
        
        .loader-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        
        .loader-logo {
            width: 80px;
            height: 80px;
            margin-bottom: 20px;
            opacity: 0.9;
        }
        
        .loader-progress {
            width: 200px;
            height: 3px;
            background-color: #f0f0f0;
            border-radius: 3px;
            overflow: hidden;
            position: relative;
        }
        
        .loader-progress-bar {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, var(--primary-color), var(--primary-dark));
            border-radius: 3px;
            transition: width 0.4s ease-out;
        }
        
        .loader-text {
            margin-top: 15px;
            font-size: 14px;
            color: #666;
            font-weight: 500;
        }
        
        /* Sidebar Styles - FIXED POSITION */
        .sidebar {
            width: var(--sidebar-width);
            position: fixed; /* Changed from absolute to fixed */
            top: 0;
            left: 0;
            height: 100vh; /* Full viewport height */
            background: linear-gradient(180deg, var(--primary-color), var(--primary-dark));
            color: white;
            z-index: 1000;
            transition: width var(--transition-speed), transform var(--transition-speed);
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.3) transparent;
        }
        
        .sidebar::-webkit-scrollbar {
            width: 5px;
        }
        
        .sidebar::-webkit-scrollbar-thumb {
            background-color: rgba(255, 255, 255, 0.3);
            border-radius: 10px;
        }
        
        .sidebar .logo {
            text-align: center;
            padding: 20px 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 120px;
        }
        
        .sidebar img {
            max-width: 90%;
            height: auto;
            display: block;
            margin: 0 auto;
        }
        
        .sidebar-nav {
            padding: 0;
            list-style: none;
            margin-top: 20px;
        }
        
        .sidebar a {
            padding: 14px 20px;
            text-decoration: none;
            font-size: 16px;
            color: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.2s ease;
            border-left: 4px solid transparent;
            margin: 4px 0;
        }
        
        .sidebar a i {
            font-size: 18px;
            width: 24px;
            text-align: center;
        }
        
        .sidebar a.active {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            font-weight: 600;
            border-left: 4px solid white;
        }
        
        .sidebar a:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(4px);
        }
        
        /* Content Area - ADJUSTED FOR FIXED SIDEBAR */
        .content {
            margin-left: var(--sidebar-width); /* Space for fixed sidebar */
            height: 100vh; /* Full viewport height */
            background-color: #f8f9fa;
            position: relative;
            display: flex;
            flex-direction: column;
            overflow: hidden; /* Prevent content overflow */
            transition: margin-left var(--transition-speed);
        }
        
        /* Topbar Styles - FIXED POSITION */
        .topbar {
            background-color: white;
            padding: 15px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(0, 0, 0, 0.08);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            position: fixed; /* Fixed position */
            top: 0;
            left: var(--sidebar-width); /* Start after sidebar */
            right: 0; /* Extend to right edge */
            height: var(--topbar-height);
            z-index: 900;
            transition: left var(--transition-speed);
        }
        
        .topbar-left {
            display: flex;
            align-items: center;
        }
        
        .menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 24px;
            color: var(--text-dark);
            cursor: pointer;
            margin-right: 15px;
        }
        
        .page-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--text-dark);
            margin: 0;
        }
        
        .user-menu {
            position: relative;
            padding: 8px 15px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
            color: var(--text-dark);
            border-radius: 50px;
            transition: all 0.2s ease;
        }
        
        .user-menu:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }
        
        .user-menu i {
            font-size: 18px;
            color: var(--primary-color);
        }
        
        .user-dropdown {
            display: none;
            position: absolute;
            right: 0;
            top: 50px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
            min-width: 200px;
            z-index: 1000;
            text-align: left;
            overflow: hidden;
            animation: fadeIn 0.2s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .user-dropdown button {
            width: 100%;
            padding: 12px 20px;
            border: none;
            background: none;
            text-align: left;
            font-size: 15px;
            color: var(--text-dark);
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.2s ease;
        }
        
        .user-dropdown button:hover {
            background: #f0f4f8;
        }
        
        .user-dropdown button i {
            color: #dc3545;
            font-size: 16px;
            width: 20px;
            text-align: center;
        }
        
        .user-info {
            padding: 15px 20px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.08);
            background-color: #f8f9fa;
        }
        
        .user-info .user-name {
            font-weight: 600;
            font-size: 16px;
            color: var(--text-dark);
        }
        
        .user-info .user-role {
            font-size: 13px;
            color: #6c757d;
        }
        
        /* Main Content Area - SCROLLABLE */
        .main-content {
    margin-top: var(--topbar-height); /* Space for fixed topbar */
    padding: 25px 25px 80px 25px; /* Add bottom padding for footer space */
    background-color: #f8f9fa;
    flex: 1;
    overflow-y: auto; /* Enable scrolling for content */
    height: calc(100vh - var(--topbar-height)); /* Adjust height */
}
        
        /* Professional Loading Overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background-color: transparent;
            z-index: 9998;
            overflow: hidden;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s;
        }
        
        .loading-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        .loading-bar {
            height: 100%;
            width: 0;
            background: linear-gradient(90deg, var(--primary-color), var(--primary-dark));
            position: absolute;
            top: 0;
            left: 0;
            animation: loading-animation 2s infinite ease-in-out;
            border-radius: 0 2px 2px 0;
        }
        
        @keyframes loading-animation {
            0% { width: 0; left: 0; }
            50% { width: 30%; left: 30%; }
            100% { width: 0; left: 100%; }
        }
        
        /* Modern Professional Spinner for navigation */
        .nav-spinner {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(5px);
            z-index: 9997;
            display: none;
            justify-content: center;
            align-items: center;
        }
        
        .nav-spinner.active {
            display: flex;
        }
        
        .modern-spinner {
            position: relative;
            width: 80px;
            height: 80px;
            display: flex;
            justify-content: center;
            align-items: center;
            perspective: 800px;
        }
        
        .spinner-ring {
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 3px solid transparent;
            animation: spinner-rotate 1.5s ease infinite;
        }
        
        .spinner-ring:nth-child(1) {
            border-top-color: var(--primary-color);
            animation-delay: 0s;
        }
        
        .spinner-ring:nth-child(2) {
            width: 80%;
            height: 80%;
            border-right-color: var(--primary-light);
            animation-delay: 0.2s;
            animation-direction: reverse;
        }
        
        .spinner-ring:nth-child(3) {
            width: 60%;
            height: 60%;
            border-bottom-color: var(--primary-dark);
            animation-delay: 0.4s;
        }
        
        .spinner-core {
            width: 20px;
            height: 20px;
            background: linear-gradient(135deg, var(--primary-light), var(--primary-dark));
            border-radius: 50%;
            box-shadow: 0 0 15px rgba(0, 102, 218, 0.5);
            animation: pulse 1s ease-in-out infinite alternate;
        }
        
        .spinner-text {
            position: absolute;
            bottom: -30px;
            font-size: 14px;
            font-weight: 500;
            color: var(--primary-dark);
            text-align: center;
            animation: fade-in-out 1.5s ease-in-out infinite;
        }
        
        @keyframes spinner-rotate {
            0% { transform: rotateY(0deg) rotateX(0deg); }
            25% { transform: rotateY(180deg) rotateX(0deg); }
            50% { transform: rotateY(180deg) rotateX(180deg); }
            75% { transform: rotateY(0deg) rotateX(180deg); }
            100% { transform: rotateY(0deg) rotateX(0deg); }
        }
        
        @keyframes pulse {
            from { transform: scale(0.8); opacity: 0.8; }
            to { transform: scale(1.2); opacity: 1; }
        }
        
        @keyframes fade-in-out {
            0%, 100% { opacity: 0.7; }
            50% { opacity: 1; }
        }
        
        /* Card Styling */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s, box-shadow 0.2s;
            opacity: 0;
            transform: translateY(10px);
            animation: fadeInUp 0.5s forwards;
            animation-delay: calc(var(--animation-order) * 0.1s);
        }
        
        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            font-weight: 600;
        }
        
        /* Professional Skeleton Loading */
        .skeleton-loader {
            width: 100%;
            display: none;
        }
        
        .skeleton-loader.active {
            display: block;
        }
        
        .skeleton-item {
            background: #f0f0f0;
            border-radius: 4px;
            margin-bottom: 10px;
            overflow: hidden;
            position: relative;
        }
        
        .skeleton-item::after {
            content: "";
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            transform: translateX(-100%);
            background-image: linear-gradient(
                90deg,
                rgba(255, 255, 255, 0) 0,
                rgba(255, 255, 255, 0.2) 20%,
                rgba(255, 255, 255, 0.5) 60%,
                rgba(255, 255, 255, 0)
            );
            animation: shimmer 2s infinite;
        }
        
        @keyframes shimmer {
            100% {
                transform: translateX(100%);
            }
        }
        
        .skeleton-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .skeleton-header {
            height: 24px;
            width: 40%;
            margin-bottom: 15px;
        }
        
        .skeleton-text {
            height: 12px;
            margin-bottom: 10px;
        }
        
        .skeleton-text.sm {
            width: 30%;
        }
        
        .skeleton-text.md {
            width: 70%;
        }
        
        .skeleton-text.lg {
            width: 100%;
        }
        
        .skeleton-button {
            height: 36px;
            width: 120px;
            border-radius: 4px;
            margin-top: 15px;
        }
        
        .skeleton-image {
            height: 200px;
            border-radius: 4px;
        }
        
        .skeleton-circle {
            height: 50px;
            width: 50px;
            border-radius: 50%;
        }
        
        .skeleton-table {
            width: 100%;
        }
        
        .skeleton-table-row {
            display: flex;
            margin-bottom: 10px;
        }
        
        .skeleton-table-cell {
            flex: 1;
            height: 20px;
            margin-right: 10px;
        }
        
        /* Page Transition */
        .page-transition {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background-color: var(--primary-color);
            z-index: 9999;
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.3s ease-in-out;
        }
        
        .page-transition.active {
            transform: scaleX(1);
        }
        
        /* Footer Styles - REMOVED STICKY POSITIONING */
        .content-footer {
    position: fixed;
    bottom: 0;
    left: var(--sidebar-width); /* Start after sidebar */
    right: 0; /* Extend to right edge */
    background-color: white;
    padding: 15px 25px;
    text-align: center;
    border-top: 1px solid rgba(0, 0, 0, 0.08);
    box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
    font-size: 14px;
    color: #6c757d;
    z-index: 800; /* Below topbar but above content */
    transition: left var(--transition-speed); /* Smooth transition when sidebar toggles */
}

        
        /* Responsive Design */
        @media (max-width: 992px) {
            .content-footer {
        left: 0; /* Full width on mobile */
    }
    
    /* When sidebar is active on mobile, adjust footer */
    .sidebar.active ~ .content .content-footer {
        left: 0; /* Keep footer full width even when sidebar is open */
    }
            .sidebar {
                transform: translateX(-100%);
            }
            
            .content {
                margin-left: 0;
            }
            
            .topbar {
                left: 0; /* Full width on mobile */
            }
            
            .menu-toggle {
                display: block;
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 999;
            }
            
            .overlay.active {
                display: block;
            }
            
            .toast-container {
                z-index: 1080 !important;
                pointer-events: none;
            }
            
            .toast {
                pointer-events: auto;
            }
        }
        
        /* Table responsive improvements */
        .table-responsive {
            max-height: calc(100vh - 300px); /* Limit table height */
            overflow-y: auto;
        }
        
        /* Ensure modals appear above fixed elements */
        .modal {
            z-index: 1060;
        }
        
        .modal-backdrop {
            z-index: 1050;
        }
    </style>
</head>
<body>
    <!-- Professional Preloader - Only shown on first visit -->
    <div class="preloader" id="preloader">
        <div class="loader-container">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="loader-logo">
            <div class="loader-progress">
                <div class="loader-progress-bar" id="progressBar"></div>
            </div>
            <div class="loader-text" id="loaderText">Memuat sistem...</div>
        </div>
    </div>
    
    <!-- Page Transition -->
    <div class="page-transition" id="pageTransition"></div>
    
    <!-- Loading Overlay for AJAX requests -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-bar"></div>
    </div>
    
    <!-- Modern Navigation Spinner - For page navigation after first load -->
    <div class="nav-spinner" id="navSpinner">
        <div class="modern-spinner">
            <div class="spinner-ring"></div>
            <div class="spinner-ring"></div>
            <div class="spinner-ring"></div>
            <div class="spinner-core"></div>
            <div class="spinner-text">Memuat halaman...</div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="logo">
            <img src="{{ asset('images/Partoba(Font Putih).png') }}" alt="Logo">
        </div>
        <ul class="sidebar-nav">
            <li>
                <a href="{{ route('dashboard') }}" class="{{ request()->is('/') ? 'active' : '' }}" data-nav-link>
                    <i class="fa fa-home"></i> 
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ url('market') }}" class="{{ request()->is('market') ? 'active' : '' }}" data-nav-link>
                    <i class="fa fa-building"></i> 
                    <span>Data Pasar</span>
                </a>
            </li>
            <li>
                <a href="{{ url('categories') }}" class="{{ request()->is('categories') ? 'active' : '' }}" data-nav-link>
                    <i class="bi bi-pie-chart-fill"></i> 
                    <span>Kategori Barang</span>
                </a>
            </li>
            <li>
                <a href="{{ url('officers') }}" class="{{ request()->is('officers') ? 'active' : '' }}" data-nav-link>
                    <i class="bi bi-person"></i> 
                    <span>Petugas Pasar</span>
                </a>
            </li>
            <li>
                <a href="{{ url('data-harga/prices') }}" class="{{ request()->is('data-harga/prices') ? 'active' : '' }}" data-nav-link>
                    <i class="fa fa-dollar-sign"></i> 
                    <span>Data Harga</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Overlay for mobile -->
    <div class="overlay" id="overlay" onclick="toggleSidebar()"></div>

    <!-- Main Content -->
    <div class="content" id="content">
        <div class="topbar">
            <div class="topbar-left">
                <button class="menu-toggle" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="page-title">Admin Harga Pasar</h1>
            </div>
            <div class="user-menu" onclick="toggleDropdown(event)">
                <span>Super Admin</span>
                <i class="fa fa-user-circle"></i>

                <div class="user-dropdown" id="userDropdown">
                    <div class="user-info">
                        <div class="user-name">Super Admin</div>
                        <div class="user-role">Administrator</div>
                    </div>
                    <form id="logout-form" action="{{ route('auth.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="fa fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="main-content" id="mainContent">
            <!-- Professional Skeleton Loader -->
            <div class="skeleton-loader" id="skeletonLoader">
                <!-- Dashboard Skeleton -->
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <div class="skeleton-card">
                            <div class="skeleton-item skeleton-header"></div>
                            <div class="skeleton-item skeleton-text lg"></div>
                            <div class="skeleton-item skeleton-text md"></div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <!-- Stats Cards Skeleton -->
                    <div class="col-md-3 mb-4">
                        <div class="skeleton-card">
                            <div class="skeleton-item skeleton-text md"></div>
                            <div class="skeleton-item skeleton-text lg" style="height: 30px;"></div>
                            <div class="skeleton-item skeleton-text sm"></div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="skeleton-card">
                            <div class="skeleton-item skeleton-text md"></div>
                            <div class="skeleton-item skeleton-text lg" style="height: 30px;"></div>
                            <div class="skeleton-item skeleton-text sm"></div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="skeleton-card">
                            <div class="skeleton-item skeleton-text md"></div>
                            <div class="skeleton-item skeleton-text lg" style="height: 30px;"></div>
                            <div class="skeleton-item skeleton-text sm"></div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="skeleton-card">
                            <div class="skeleton-item skeleton-text md"></div>
                            <div class="skeleton-item skeleton-text lg" style="height: 30px;"></div>
                            <div class="skeleton-item skeleton-text sm"></div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <!-- Table Skeleton -->
                    <div class="col-md-8 mb-4">
                        <div class="skeleton-card">
                            <div class="skeleton-item skeleton-header"></div>
                            <div class="skeleton-table">
                                <div class="skeleton-table-row">
                                    <div class="skeleton-item skeleton-table-cell"></div>
                                    <div class="skeleton-item skeleton-table-cell"></div>
                                    <div class="skeleton-item skeleton-table-cell"></div>
                                </div>
                                <div class="skeleton-table-row">
                                    <div class="skeleton-item skeleton-table-cell"></div>
                                    <div class="skeleton-item skeleton-table-cell"></div>
                                    <div class="skeleton-item skeleton-table-cell"></div>
                                </div>
                                <div class="skeleton-table-row">
                                    <div class="skeleton-item skeleton-table-cell"></div>
                                    <div class="skeleton-item skeleton-table-cell"></div>
                                    <div class="skeleton-item skeleton-table-cell"></div>
                                </div>
                                <div class="skeleton-table-row">
                                    <div class="skeleton-item skeleton-table-cell"></div>
                                    <div class="skeleton-item skeleton-table-cell"></div>
                                    <div class="skeleton-item skeleton-table-cell"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Chart Skeleton -->
                    <div class="col-md-4 mb-4">
                        <div class="skeleton-card">
                            <div class="skeleton-item skeleton-header"></div>
                            <div class="skeleton-item skeleton-image"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Actual Content -->
            <div id="actualContent" style="display: none;">
                @yield('content')
            </div>
            
            <!-- Footer moved inside main-content for proper scrolling -->
            <div class="content-footer">
                &copy; {{ date('Y') }} PARTOBA - Dinas Koperasi, UKM, Perindustrian dan Perdagangan Kabupaten Toba.
                All rights reserved.
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Check if this is the first visit
        function isFirstVisit() {
            if (sessionStorage.getItem('hasVisited')) {
                return false;
            } else {
                sessionStorage.setItem('hasVisited', 'true');
                return true;
            }
        }
        
        // Professional Preloader with Progress Bar - Only on first visit
        document.addEventListener('DOMContentLoaded', function() {
            const preloader = document.getElementById('preloader');
            const progressBar = document.getElementById('progressBar');
            const loaderText = document.getElementById('loaderText');
            const skeletonLoader = document.getElementById('skeletonLoader');
            const actualContent = document.getElementById('actualContent');
            const navSpinner = document.getElementById('navSpinner');
            
            // Only show full preloader on first visit
            if (isFirstVisit()) {
                preloader.style.display = 'flex';
                
                // Simulate loading progress
                let progress = 0;
                const interval = setInterval(function() {
                    progress += Math.random() * 10;
                    if (progress >= 100) {
                        progress = 100;
                        clearInterval(interval);
                        
                        // Update loader text
                        loaderText.textContent = 'Mempersiapkan dashboard...';
                        
                        // Hide preloader after a short delay
                        setTimeout(function() {
                            preloader.classList.add('fade-out');
                            setTimeout(function() {
                                preloader.style.display = 'none';
                                
                                // Show skeleton loader for a professional transition
                                skeletonLoader.classList.add('active');
                                
                                // Simulate content loading
                                setTimeout(function() {
                                    skeletonLoader.classList.remove('active');
                                    actualContent.style.display = 'block';
                                    
                                    // Initialize card animations
                                    initCardAnimations();
                                }, 800);
                            }, 500);
                        }, 500);
                    }
                    
                    // Update progress bar width
                    progressBar.style.width = progress + '%';
                    
                    // Update loader text based on progress
                    if (progress < 30) {
                        loaderText.textContent = 'Memuat sistem...';
                    } else if (progress < 60) {
                        loaderText.textContent = 'Mengambil data...';
                    } else if (progress < 90) {
                        loaderText.textContent = 'Mempersiapkan antarmuka...';
                    }
                }, 150);
            } else {
                // On subsequent visits, hide preloader and show content directly
                preloader.style.display = 'none';
                
                // Show skeleton briefly
                skeletonLoader.classList.add('active');
                
                // Show content after a short delay
                setTimeout(function() {
                    skeletonLoader.classList.remove('active');
                    actualContent.style.display = 'block';
                    initCardAnimations();
                }, 300);
            }
        });
        
        // Initialize card animations
        function initCardAnimations() {
            const cards = document.querySelectorAll('.card');
            cards.forEach((card, index) => {
                card.style.setProperty('--animation-order', index);
            });
        }
        
        // Toggle sidebar on mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            const topbar = document.querySelector('.topbar');
            
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
            
            // Adjust topbar position on mobile when sidebar is toggled
            if (window.innerWidth <= 992) {
                if (sidebar.classList.contains('active')) {
                    topbar.style.left = '280px';
                } else {
                    topbar.style.left = '0';
                }
            }
        }

        // Toggle user dropdown
        function toggleDropdown(event) {
            event.stopPropagation();
            const dropdown = document.getElementById("userDropdown");
            dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
        }

        // Close dropdown when clicking outside
        document.addEventListener("click", function(event) {
            const dropdown = document.getElementById("userDropdown");
            const userMenu = document.querySelector(".user-menu");
            if (!userMenu.contains(event.target)) {
                dropdown.style.display = "none";
            }
        });

        // Set active menu item based on current page
        document.addEventListener("DOMContentLoaded", function() {
            const currentPath = window.location.pathname;
            const menuItems = document.querySelectorAll('.sidebar a');
            
            menuItems.forEach(item => {
                const href = item.getAttribute('href').replace(/^https?:\/\/[^\/]+/, '');
                if (currentPath === href || currentPath.startsWith(href + '/')) {
                    item.classList.add('active');
                }
            });
        });
        
        // Professional page navigation with loading indicator
        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('[data-nav-link]');
            const pageTransition = document.getElementById('pageTransition');
            const navSpinner = document.getElementById('navSpinner');
            
            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const href = this.getAttribute('href');
                    
                    // Show page transition bar at the top
                    pageTransition.classList.add('active');
                    
                    // Show spinner for navigation after first visit
                    if (!isFirstVisit()) {
                        navSpinner.classList.add('active');
                    }
                    
                    // Navigate after animation
                    setTimeout(() => {
                        window.location.href = href;
                    }, 300);
                });
            });
        });
        
        // Show loading overlay for AJAX requests
        function showLoading() {
            document.getElementById('loadingOverlay').classList.add('active');
        }
        
        function hideLoading() {
            document.getElementById('loadingOverlay').classList.remove('active');
        }
        
        // Add loading indicator to all forms
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form:not(#logout-form)');
            
            forms.forEach(form => {
                form.addEventListener('submit', function() {
                    showLoading();
                });
            });
        });
        
        // Intercept AJAX requests to show loading indicator
        (function() {
            const originalXHR = window.XMLHttpRequest;
            
            function newXHR() {
                const xhr = new originalXHR();
                
                xhr.addEventListener('loadstart', function() {
                    showLoading();
                }, false);
                
                xhr.addEventListener('readystatechange', function() {
                    showLoading();
                }, false);
                
                xhr.addEventListener('loadend', function() {
                    hideLoading();
                }, false);
                
                return xhr;
            }
            
            window.XMLHttpRequest = newXHR;
        })();
        
        // Handle window resize for responsive behavior
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            const topbar = document.querySelector('.topbar');
            
            if (window.innerWidth > 992) {
                // Desktop view
                sidebar.classList.remove('active');
                document.getElementById('overlay').classList.remove('active');
                topbar.style.left = 'var(--sidebar-width)';
            } else {
                // Mobile view
                topbar.style.left = '0';
            }
        });
    </script>
    @stack('scripts')
    @yield('scripts')
</body>
</html>