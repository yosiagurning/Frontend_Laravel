<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <title>PARTOBA - Pasar Ramah Harga Mudah</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #0575E6;
            --primary-light: #3498db;
            --secondary-color: #021B79;
            --accent-color: #336699;
            --text-color: #333333;
            --light-bg: #f8f9fa;
            --white: #ffffff;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
            --border-radius: 10px;
            --card-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);

            /* New Theme Variables */
            --bg-main: #f8f9fa;
            --bg-card: #ffffff;
            --border-color: #e9ecef;
            --table-header-bg: #f8f9fa;
            --text-primary: #212529;
            --text-secondary: #6c757d;
        }

        html {
            scroll-behavior: smooth;
            scroll-padding-top: 80px;
        }

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: var(--light-bg);
            color: var(--text-color);
            line-height: 1.6;
            overflow-x: hidden;
        }

        .container {
            max-width: 1200px;
            margin: auto;
            padding: 20px;
        }

        /* Preloader */
        .preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(145deg, var(--primary-color), var(--secondary-color));
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: opacity 0.5s ease, visibility 0.5s ease;
        }

        .preloader.fade-out {
            opacity: 0;
            visibility: hidden;
        }

        .loader {
            width: 80px;
            height: 80px;
            border: 5px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: var(--white);
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Header & Navigation */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding: 10px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--shadow);
            z-index: 100;
            transition: all 0.4s ease;
        }

        .header.scrolled {
            padding: 5px 0;
            background: rgba(255, 255, 255, 0.98);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .header-container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .logo img {
            width: 180px;
            margin: -40px -30px -40px 0px;
            transition: var(--transition);
        }

        .header.scrolled .logo img {
            width: 160px;
        }

        .nav {
            display: flex;
            align-items: center;
        }

        .nav a {
            margin: 0 15px;
            text-decoration: none;
            color: var(--text-color);
            font-weight: 500;
            padding: 8px 15px;
            transition: var(--transition);
            border-radius: 5px;
            position: relative;
        }

        .nav a:not(.btn-login)::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 50%;
            background: var(--primary-color);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav a:hover::after {
            width: 70%;
        }

        .nav a:hover {
            color: var(--primary-color);
        }

        .btn-login {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: var(--white) !important;
            padding: 10px 25px !important;
            border-radius: 30px !important;
            font-weight: 600 !important;
            box-shadow: 0 4px 15px rgba(5, 117, 230, 0.3);
            transition: all 0.3s ease !important;
            border: none;
            outline: none;
            cursor: pointer;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, var(--primary-light), var(--primary-color));
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(5, 117, 230, 0.4);
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 24px;
            color: var(--text-color);
            cursor: pointer;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(2, 27, 121, 0.8)), url('{{ asset('images/toba-background.JPEG') }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: var(--white);
            padding: 160px 20px 100px;
            text-align: center;
            margin-top: 0;
            clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%);
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255, 255, 255, 0.1)" d="M0,192L48,197.3C96,203,192,213,288,229.3C384,245,480,267,576,250.7C672,235,768,181,864,181.3C960,181,1056,235,1152,234.7C1248,235,1344,181,1392,154.7L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
            background-size: cover;
            background-position: center;
            opacity: 0.8;
            z-index: 0;
            mix-blend-mode: overlay;
        }

        .hero-content h1 {
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
            position: relative;
        }

        .hero-content h1::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: linear-gradient(90deg, #fff, rgba(255, 255, 255, 0.5), #fff);
            border-radius: 3px;
        }

        .hero-content p {
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.6);
        }

        .hero-ornament {
            position: absolute;
            width: 150px;
            height: 150px;
            background-image: url('{{ asset('images/toba-ornament.png') }}');
            background-size: contain;
            background-repeat: no-repeat;
            opacity: 0.2;
            z-index: 0;
        }

        .hero-ornament.top-left {
            top: 20px;
            left: 20px;
            transform: rotate(-15deg);
        }

        .hero-ornament.bottom-right {
            bottom: 60px;
            right: 20px;
            transform: rotate(15deg);
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .hero h1 {
            font-size: 2.8rem;
            margin-bottom: 20px;
            font-weight: 700;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
            animation: fadeInUp 1s ease;
        }

        .hero p {
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto 30px;
            opacity: 0.9;
            animation: fadeInUp 1.2s ease;
        }

        .btn-download {
            display: inline-block;
            padding: 14px 35px;
            background: var(--white);
            color: var(--primary-color);
            text-decoration: none;
            border-radius: 30px;
            font-weight: 600;
            transition: all 0.4s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            position: relative;
            overflow: hidden;
            z-index: 1;
            animation: fadeInUp 1.4s ease;
        }

        .btn-download::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0%;
            height: 100%;
            background: linear-gradient(90deg, var(--primary-light), var(--primary-color));
            transition: all 0.4s ease;
            z-index: -1;
        }

        .btn-download:hover {
            color: var(--white);
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .btn-download:hover::before {
            width: 100%;
        }

        .btn-download i {
            margin-right: 8px;
        }

        /* Search Box */
        .search-container {
            position: relative;
            max-width: 600px;
            margin: 40px auto;
            transition: all 0.3s ease;
        }

        .search-container:focus-within {
            transform: scale(1.02);
        }

        #searchBox {
            width: 100%;
            padding: 16px 25px;
            border: 1px solid rgba(224, 224, 224, 0.6);
            border-radius: 30px;
            font-size: 16px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            background: var(--white);
            color: var(--text-color);
        }

        #searchBox:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 5px 20px rgba(5, 117, 230, 0.15);
        }

        .search-icon {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-color);
            font-size: 18px;
        }

        /* Section Headers */
        .section-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .section-header h2 {
            font-size: 2.2rem;
            color: var(--accent-color);
            position: relative;
            display: inline-block;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }

        .section-header h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-color), var(--primary-light));
            border-radius: 3px;
        }

        .section-header p {
            color: #666;
            max-width: 700px;
            margin: 0 auto;
            font-size: 1.1rem;
        }

        /* Komoditi Table */
        .table-container {
            background: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            overflow: hidden;
            margin-bottom: 40px;
            transition: all 0.3s ease;
            max-height: 600px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: var(--primary-light) var(--bg-card);
        }

        .table-container:hover {
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        /* Scrollbar styling for webkit browsers */
        .table-container::-webkit-scrollbar {
            width: 8px;
        }

        .table-container::-webkit-scrollbar-track {
            background: var(--bg-card);
        }

        .table-container::-webkit-scrollbar-thumb {
            background-color: var(--primary-light);
            border-radius: 4px;
        }

        .komoditi {
            width: 100%;
            border-collapse: collapse;
        }

        .komoditi th,
        .komoditi td {
            padding: 16px;
            text-align: left;
            border-bottom: 1px solid #eaeaea;
            transition: all 0.2s ease;
        }

        .komoditi th {
            background-color: var(--table-header-bg);
            font-weight: 600;
            color: var(--accent-color);
            position: sticky;
            top: 0;
            z-index: 10;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            user-select: none;
        }

        .komoditi th:hover {
            background-color: #e9eef5;
        }

        .komoditi tr {
            transition: all 0.2s ease;
        }

        .komoditi tbody tr:hover {
            background-color: #f9f9f9;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .komoditi td:nth-child(3) {
            font-weight: 600;
            color: #2a9d8f;
        }

        /* Disable AOS animations for table rows to prevent flickering */
        .komoditi tbody tr {
            opacity: 1 !important;
            transform: none !important;
        }

        /* Sort icons */
        .sort-icons {
            margin-left: 5px;
            font-size: 0.8em;
            display: inline-block;
            vertical-align: middle;
        }

        .sort-icons .asc,
        .sort-icons .desc {
            color: #aaa;
            margin-left: 2px;
            transition: all 0.2s ease;
        }

        .sort-icons.active-asc .asc {
            color: var(--primary-color);
            font-weight: bold;
        }

        .sort-icons.active-desc .desc {
            color: var(--primary-color);
            font-weight: bold;
        }

        /* Show/Hide Table Controls */
        .table-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .table-controls .view-all-btn {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .table-controls .view-all-btn:hover {
            background: var(--primary-light);
            transform: translateY(-2px);
        }

        .table-controls .view-all-btn i {
            transition: transform 0.3s ease;
        }

        .table-controls .view-all-btn.expanded i {
            transform: rotate(180deg);
        }

        .rows-info {
            color: var(--text-secondary);
            font-size: 14px;
        }

        /* Enhanced Pagination Styles */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
            gap: 10px;
        }

        .pagination button {
            padding: 8px 15px;
            background: var(--white);
            border: 1px solid #eaeaea;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .pagination button:hover:not(:disabled) {
            background: var(--primary-color);
            color: var(--white);
            border-color: var(--primary-color);
        }

        .pagination button:disabled {
            cursor: not-allowed;
            opacity: 0.5;
        }

        .pagination .page-info {
            background: #f3f6fb;
            border-radius: 5px;
            color: var(--accent-color);
            font-weight: 500;
        }

        /* Tentang Section */
        .tentang {
            background-color: var(--white);
            padding: 40px;
            text-align: justify;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            margin-top: 20px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .tentang::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--primary-color), var(--primary-light));
        }

        .tentang:hover {
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            transform: translateY(-5px);
        }

        .tentang h2 {
            color: var(--accent-color);
            text-align: center;
            margin-bottom: 25px;
            font-size: 1.8rem;
        }

        .tentang p {
            line-height: 1.8;
            color: var(--text-color);
            font-size: 16px;
            margin-bottom: 15px;
        }

        .tentang strong {
            color: var(--primary-color);
        }

        /* Gallery Section */
        .gallery-section {
            padding: 60px 0;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            padding: 20px 0;
        }

        .gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            height: 250px;
            cursor: pointer;
            transition: all 0.5s ease;
        }

        .gallery-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.5s ease;
        }

        .gallery-item:hover img {
            transform: scale(1.1);
        }

        .gallery-item .description {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            padding: 15px;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.8), transparent);
            color: white;
            transform: translateY(100%);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }

        .gallery-item:hover .description {
            transform: translateY(0);
        }

        /* Lightbox */
        .lightbox {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .lightbox.active {
            opacity: 1;
            display: flex;
        }

        .lightbox img {
            max-width: 90%;
            max-height: 90%;
            border-radius: 10px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.5);
            transform: scale(0.9);
            transition: all 0.3s ease;
        }

        .lightbox.active img {
            transform: scale(1);
        }

        .close {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 35px;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .close:hover {
            transform: rotate(90deg);
            color: var(--primary-color);
        }

        /* Footer - Removed animations */
        footer {
            background: linear-gradient(145deg, var(--primary-color), rgba(2, 40, 138, 0.9), var(--secondary-color));
            color: var(--white);
            text-align: center;
            padding: 50px 0 30px;
            margin-top: 40px;
            position: relative;
        }

        .footer-content {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer-logo {
            margin-bottom: 20px;
        }

        .footer-logo img {
            width: 150px;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .footer-links a {
            color: var(--white);
            margin: 0 15px;
            text-decoration: none;
            opacity: 0.8;
            transition: var(--transition);
        }

        .footer-links a:hover {
            opacity: 1;
        }

        .social-links {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }

        .social-links a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            color: var(--white);
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            background: var(--white);
            color: var(--primary-color);
        }

        .footer-text {
            opacity: 0.7;
            font-size: 14px;
        }

        /* Back to top button */
        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background: var(--primary-color);
            color: var(--white);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 99;
            box-shadow: 0 4px 15px rgba(5, 117, 230, 0.3);
        }

        .back-to-top.active {
            opacity: 1;
            visibility: visible;
        }

        .back-to-top:hover {
            background: var(--secondary-color);
            transform: translateY(-5px);
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .fade-in {
            animation: fadeIn 1s ease;
        }

        /* Market Lightbox Styles */
        .market-lightbox-content {
            background: white;
            border-radius: 10px;
            max-width: 800px;
            width: 90%;
            overflow: hidden;
            display: flex;
            flex-direction: row;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.5);
            position: relative;
            max-height: 90vh;
        }
        
        .market-image {
            width: 40%;
            height: auto;
            overflow: hidden;
        }
        
        .market-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .market-info {
            width: 60%;
            padding: 25px;
            overflow-y: auto;
        }
        
        .market-info h3 {
            color: var(--primary-color);
            font-size: 24px;
            margin-bottom: 15px;
            font-weight: 600;
        }
        
        .market-description h4 {
            color: var(--accent-color);
            font-size: 18px;
            margin-bottom: 10px;
            font-weight: 500;
            padding-left: 10px;
            border-left: 4px solid var(--primary-color);
        }
        
        .history-text {
            line-height: 1.8;
            color: var(--text-color);
            padding: 15px;
            background-color: rgba(5, 117, 230, 0.05);
            border-radius: 8px;
            border-left: 3px solid var(--primary-color);
            font-size: 16px;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .hero h1 {
                font-size: 2.2rem;
            }
        }

        @media (max-width: 768px) {
            .header-container {
                padding: 10px 20px;
            }

            .mobile-menu-btn {
                display: block;
            }

            .nav {
                position: fixed;
                top: 70px;
                left: 0;
                width: 100%;
                background: var(--white);
                flex-direction: column;
                align-items: center;
                padding: 20px 0;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
                transform: translateY(-150%);
                transition: all 0.4s ease;
                z-index: 99;
            }

            .nav.active {
                transform: translateY(0);
            }

            .nav a {
                margin: 10px 0;
                width: 80%;
                text-align: center;
            }

            .hero {
                padding: 150px 20px 70px;
            }

            .hero h1 {
                font-size: 1.8rem;
            }

            .hero p {
                font-size: 1rem;
            }

            .komoditi th,
            .komoditi td {
                padding: 12px;
                font-size: 14px;
            }

            .tentang {
                padding: 25px;
            }

            .gallery-grid {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            }

            .market-lightbox-content {
                flex-direction: column;
            }
            
            .market-image {
                width: 100%;
                height: 200px;
            }
            
            .market-info {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .hero h1 {
                font-size: 1.5rem;
            }

            .table-container {
                overflow-x: auto;
            }

            .komoditi {
                min-width: 500px;
            }

            .gallery-grid {
                grid-template-columns: 1fr;
            }

            .btn-download {
                padding: 12px 25px;
                font-size: 14px;
            }

            .section-header h2 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>

<body>
    <!-- Preloader -->
    <div class="preloader">
        <div class="loader"></div>
    </div>

    <!-- Back to top button -->
    <div class="back-to-top">
        <i class="fas fa-arrow-up"></i>
    </div>

    <!-- Navbar -->
    <header class="header">
        <div class="header-container">
            <div class="logo">
                <a href="#beranda">
                    <img src="{{ asset('images/logo.png') }}" alt="PARTOBA Logo">
                </a>
            </div>
            <button class="mobile-menu-btn">
                <i class="fas fa-bars"></i>
            </button>
            <nav class="nav">
                <a href="#beranda">Beranda</a>
                <a href="#pasar">Pasar</a>
                <a href="#komoditi">Komoditi</a>
                <a href="#tentang">Tentang</a>
                <a href="#galeri">Galeri</a>
                <a href="login" class="btn-login">
                    <i class="fas fa-user"></i> Login
                </a>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="beranda">
        <div class="hero-ornament top-left"></div>
        <div class="hero-ornament bottom-right"></div>
        <div class="container hero-content">
            <h1 data-aos="fade-up" data-aos-delay="100">Kabupaten Toba mulai menerapkan Sistem Informasi Harga Berbasis Website</h1>
            <p data-aos="fade-up" data-aos-delay="200">Melalui aplikasi PARTOBA kita bisa Menemukan Harga Bahan Pokok Dan Barang Penting Secara Real-Time</p>
            <a href="" 
            class="btn-download" data-aos="fade-up" data-aos-delay="300">
                <i class="fab fa-google-play"></i> Unduh Sekarang
            </a>
        </div>
    </section>

    <!-- Pasar Section -->
    <section id="pasar" class="container">
        <div class="section-header" data-aos="fade-up">
            <h2>Pasar di Kabupaten Toba</h2>
            <p>Jelajahi berbagai pasar tradisional di Kabupaten Toba dan pelajari sejarah serta keunikan masing-masing pasar</p>
        </div>

        <div class="gallery-grid">
            <div class="gallery-item" data-aos="zoom-in" data-aos-delay="100" data-market-id="1">
                <img src="{{ asset('images/Balige.jpg') }}" alt="Pasar Balige">
                <div class="description">
                    <i class="fas fa-store-alt mr-2"></i> Pasar Balige
                </div>
            </div>
            <div class="gallery-item" data-aos="zoom-in" data-aos-delay="200" data-market-id="2">
                <img src="{{ asset('images/lAGUBOTI.jpg') }}" alt="Pasar Laguboti">
                <div class="description">
                    <i class="fas fa-store-alt mr-2"></i> Pasar Laguboti
                </div>
            </div>
            <div class="gallery-item" data-aos="zoom-in" data-aos-delay="100" data-market-id="4">
                <img src="{{ asset('images/parsoburan.jpg') }}" alt="Pasar Parsoburan">
                <div class="description">
                    <i class="fas fa-store-alt mr-2"></i> Pasar Parsoburan
                </div>
            </div>
            <div class="gallery-item" data-aos="zoom-in" data-aos-delay="200" data-market-id="5">
                <img src="{{ asset('images/ajibata.jpeg') }}" alt="Pasar Ajibata">
                <div class="description">
                    <i class="fas fa-store-alt mr-2"></i> Pasar Ajibata
                </div>
            </div>
            <div class="gallery-item" data-aos="zoom-in" data-aos-delay="300" data-market-id="6">
                <img src="{{ asset('images/Lumbanjulu.jpg') }}" alt="Pasar Lumban Julu">
                <div class="description">
                    <i class="fas fa-store-alt mr-2"></i> Pasar Lumban Julu
                </div>
            </div>
            <div class="gallery-item" data-aos="zoom-in" data-aos-delay="100" data-market-id="7">
                <img src="{{ asset('images/porsea2.jpg') }}" alt="Pasar Porsea">
                <div class="description">
                    <i class="fas fa-store-alt mr-2"></i> Pasar Porsea
                </div>
            </div>
            <div class="gallery-item" data-aos="zoom-in" data-aos-delay="200" data-market-id="8">
                <img src="{{ asset('images/Silimbat.jpg') }}" alt="Pasar Silimbat">
                <div class="description">
                    <i class="fas fa-store-alt mr-2"></i> Pasar Silimbat
                </div>
            </div>
            <div class="gallery-item" data-aos="zoom-in" data-aos-delay="100" data-market-id="10">
                <img src="{{ asset('images/Lumban Lobu.JPG') }}" alt="Pasar Lumban Lobu">
                <div class="description">
                    <i class="fas fa-store-alt mr-2"></i> Pasar Lumban Lobu
                </div>
            </div>
            <div class="gallery-item" data-aos="zoom-in" data-aos-delay="200" data-market-id="11">
                <img src="{{ asset('images/Habinsaran.jpg') }}" alt="Pasar Habinsaran">
                <div class="description">
                    <i class="fas fa-store-alt mr-2"></i> Pasar Habinsaran
                </div>
            </div>
            <div class="gallery-item" data-aos="zoom-in" data-aos-delay="300" data-market-id="12">
                <img src="{{ asset('images/silaen.jpg') }}" alt="Pasar Silaen">
                <div class="description">
                    <i class="fas fa-store-alt mr-2"></i> Pasar Silaen
                </div>
            </div>
        </div> 
    </section>

    <!-- Market Lightbox -->
    <div id="market-lightbox" class="lightbox">
        <span class="close" id="close-market-lightbox">&times;</span>
        <div class="market-lightbox-content">
            <div class="market-image">
                <img id="market-lightbox-img" src="{{ asset('images/placeholder.svg') }}" alt="Pasar">
            </div>
            <div class="market-info">
                <h3 id="market-name">Nama Pasar</h3>
                <div class="market-description">
                    <h4>Sejarah Singkat</h4>
                    <p id="market-description" class="history-text">
                        Deskripsi sejarah pasar akan ditampilkan di sini.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Komoditi Section -->
    <section id="komoditi" class="container">
        <div class="section-header" data-aos="fade-up">
            <h2>Informasi Harga Barang Kebutuhan Pokok</h2>
            <p>Pantau harga terkini dari berbagai komoditas penting di pasar-pasar Kabupaten Toba</p>
        </div>

        <div class="search-container" data-aos="fade-up" data-aos-delay="100">
            <input type="text" id="searchBox" placeholder="Cari komoditi, pasar, atau kategori...">
            <i class="fas fa-search search-icon"></i>
        </div>

        <div class="table-controls" data-aos="fade-up" data-aos-delay="150">
            <div class="rows-info">
                Menampilkan <span id="visibleRows">0</span> dari <span id="totalRows">0</span> komoditi
            </div>
            <button class="view-all-btn" id="viewAllBtn">
                <span>Lihat Semua</span>
                <i class="fas fa-chevron-down"></i>
            </button>
        </div>

        <div class="table-container" data-aos="fade-up" data-aos-delay="200">
            <table class="komoditi" id="hargaKomoditi">
                <thead>
                    <tr>
                        <th onclick="sortTable(0, this)">
                            No
                            <span class="sort-icons">
                                <i class="fas fa-caret-up asc"></i>
                                <i class="fas fa-caret-down desc"></i>
                            </span>
                        </th>
                        <th onclick="sortTable(1, this)">
                            Komoditi
                            <span class="sort-icons">
                                <i class="fas fa-caret-up asc"></i>
                                <i class="fas fa-caret-down desc"></i>
                            </span>
                        </th>
                        <th onclick="sortTable(2, this)">
                            Harga
                            <span class="sort-icons">
                                <i class="fas fa-caret-up asc"></i>
                                <i class="fas fa-caret-down desc"></i>
                            </span>
                        </th>
                        <th onclick="sortTable(3, this)">
                            Tanggal
                            <span class="sort-icons">
                                <i class="fas fa-caret-up asc"></i>
                                <i class="fas fa-caret-down desc"></i>
                            </span>
                        </th>
                        <th onclick="sortTable(4, this)">
                            Pasar
                            <span class="sort-icons">
                                <i class="fas fa-caret-up asc"></i>
                                <i class="fas fa-caret-down desc"></i>
                            </span>
                        </th>
                        <th onclick="sortTable(5, this)">
                            Kategori
                            <span class="sort-icons">
                                <i class="fas fa-caret-up asc"></i>
                                <i class="fas fa-caret-down desc"></i>
                            </span>
                        </th>
                    </tr>
                </thead>

                <tbody>
                @foreach ($prices as $key => $price)
                <tr class="table-row">
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $price['commodity_name'] }}</td>
                    <td>Rp {{ number_format($price['current_price'], 0, ',', '.') }}</td>
                    <td>{{ date('Y-m-d', strtotime($price['change_date'])) }}</td>
                    <td>{{ $price['market_name']}}</td>
                    <td>{{ $price['category_name']}}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="pagination" id="tablePagination" data-aos="fade-up" data-aos-delay="300">
            <!-- Pagination buttons will be inserted here by JavaScript -->
        </div>
    </section>

    <!-- Tentang Section -->
    <section id="tentang" class="container">
        <div class="section-header" data-aos="fade-up">
            <h2>Tentang PARTOBA</h2>
            <p>Mengenal lebih dekat aplikasi informasi harga kebutuhan pokok Kabupaten Toba</p>
        </div>

        <div class="tentang" data-aos="fade-up" data-aos-delay="100">
            <p data-aos="fade-up" data-aos-delay="150">
                <strong>PARTOBA (Pasar Ramah Harga Mudah)</strong> adalah aplikasi yang dirancang untuk memberikan
                Informasi Harga Bahan Pokok dan Barang Penting secara Real-Time.
                Dengan aplikasi ini, Masyarakat dapat mengetahui Harga Bahan Pokok dan Barang penting di Kabupaten Toba.
            </p>
            <p data-aos="fade-up" data-aos-delay="200">
                Aplikasi ini dibawah naungan Dinas Koperasi, UKM, Perindustrian dan Perdagangan Kabupaten Toba.
                PARTOBA adalah aplikasi yang dikembangkan untuk memberikan informasi harga barang kebutuhan pokok di
                Kabupaten Toba.
            </p>
            <p data-aos="fade-up" data-aos-delay="250">
                Aplikasi ini bertujuan untuk membantu masyarakat dalam mendapatkan informasi harga barang kebutuhan
                pokok secara cepat dan akurat.
                PARTOBA bertujuan untuk memberikan kemudahan bagi masyarakat dalam mengakses informasi harga barang
                kebutuhan pokok.
            </p>
            <p data-aos="fade-up" data-aos-delay="300">
                Dengan menggunakan aplikasi ini, masyarakat dapat mengetahui harga barang kebutuhan pokok di pasar-pasar
                yang ada di Kabupaten Toba.
                Selain itu, aplikasi ini juga memberikan informasi tentang harga barang kebutuhan pokok yang berlaku di
                pasar-pasar tertentu.
                Dengan demikian, masyarakat dapat membuat keputusan yang lebih baik dalam berbelanja dan mengelola
                keuangan mereka.
            </p>
        </div>
    </section>

    <!-- Galeri Section -->
    <section id="galeri" class="container gallery-section">
        <div class="section-header" data-aos="fade-up">
            <h2>Galeri</h2>
            <p>Melihat aktivitas dan suasana pasar di Kabupaten Toba</p>
        </div>

        <div class="gallery-grid">
            <div class="gallery-item" data-aos="zoom-in" data-aos-delay="100">
                <img src="{{ asset('images/galeri1.jpg') }}" alt="Pasar Toba">
                <div class="description">
                    <i class="fas fa-store-alt mr-2"></i> Foto Bersama Tim Dinas Koperindag
                </div>
            </div>
            <div class="gallery-item" data-aos="zoom-in" data-aos-delay="200">
                <img src="{{ asset('images/galeri2.jpg') }}" alt="Aktivitas Pasar">
                <div class="description">
                    <i class="fas fa-users mr-2"></i> Sharing Informasi Harga
                </div>
            </div>
            <div class="gallery-item" data-aos="zoom-in" data-aos-delay="300">
                <img src="{{ asset('images/galeri3.jpg') }}" alt="Komoditi Pasar">
                <div class="description">
                    <i class="fas fa-carrot mr-2"></i> Pengenalan Website yang dimiliki Koperindag
                </div>
            </div>
            <div class="gallery-item" data-aos="zoom-in" data-aos-delay="400">
                <img src="{{ asset('images/galeri4.jpg') }}" alt="Transaksi Pasar">
                <div class="description">
                    <i class="fas fa-hand-holding-usd mr-2"></i> Requirement Bersama Dinas Koperindag
                </div>
            </div>
        </div>
    </section>

    <!-- Lightbox for Enlarged Image -->
    <div id="lightbox" class="lightbox">
        <span class="close" id="close-lightbox">&times;</span>
        <img id="lightbox-img" src="/placeholder.svg" alt="">
    </div>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-text">
                <p>&copy; {{ date('Y') }} PARTOBA - Dinas Koperasi, UKM, Perindustrian dan Perdagangan Kabupaten Toba.
                    All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- JavaScript Libraries -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <!-- JavaScript -->
    <script>
        // Global variables
        let currentPage = 1;
        let rowsPerPage = 10;
        let isTableExpanded = false;
        let allRows = [];
        let filteredRows = [];
        let searchTimeout;

        // Initialize AOS and all functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize AOS with specific settings to avoid conflicts
            AOS.init({
                duration: 800,
                easing: 'ease-in-out',
                once: true,
                mirror: false,
                disable: function() {
                    // Disable AOS for table rows to prevent flickering
                    return window.innerWidth < 768;
                }
            });

            // Initialize all components
            initializePreloader();
            initializeMobileMenu();
            initializeHeaderScroll();
            initializeBackToTop();
            initializeGalleryLightbox();
            initializeMarketLightbox();
            initializeTableFunctionality();
        });

        // Preloader
        function initializePreloader() {
            setTimeout(function() {
                const preloader = document.querySelector('.preloader');
                if (preloader) {
                    preloader.classList.add('fade-out');
                    setTimeout(() => {
                        preloader.style.display = 'none';
                    }, 500);
                }
            }, 1000);
        }

        // Mobile Menu Toggle
        function initializeMobileMenu() {
            const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
            const nav = document.querySelector('.nav');

            if (mobileMenuBtn && nav) {
                mobileMenuBtn.addEventListener('click', function() {
                    nav.classList.toggle('active');
                    const icon = this.querySelector('i');
                    if (icon.classList.contains('fa-bars')) {
                        icon.classList.remove('fa-bars');
                        icon.classList.add('fa-times');
                    } else {
                        icon.classList.remove('fa-times');
                        icon.classList.add('fa-bars');
                    }
                });

                // Close mobile menu when clicking on a link
                const navLinks = document.querySelectorAll('.nav a');
                navLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        if (nav.classList.contains('active')) {
                            nav.classList.remove('active');
                            const icon = mobileMenuBtn.querySelector('i');
                            icon.classList.remove('fa-times');
                            icon.classList.add('fa-bars');
                        }
                    });
                });
            }
        }

        // Header scroll effect
        function initializeHeaderScroll() {
            const header = document.querySelector('.header');
            if (header) {
                window.addEventListener('scroll', function() {
                    if (window.scrollY > 50) {
                        header.classList.add('scrolled');
                    } else {
                        header.classList.remove('scrolled');
                    }
                });
            }
        }

        // Back to top button
        function initializeBackToTop() {
            const backToTopBtn = document.querySelector('.back-to-top');
            if (backToTopBtn) {
                window.addEventListener('scroll', function() {
                    if (window.scrollY > 300) {
                        backToTopBtn.classList.add('active');
                    } else {
                        backToTopBtn.classList.remove('active');
                    }
                });

                backToTopBtn.addEventListener('click', function() {
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                });
            }
        }

        // Gallery Lightbox
        function initializeGalleryLightbox() {
            const galleryImages = document.querySelectorAll('#galeri .gallery-item img');
            const lightbox = document.getElementById('lightbox');
            const lightboxImg = document.getElementById('lightbox-img');
            const closeLightbox = document.getElementById('close-lightbox');

            if (galleryImages.length > 0 && lightbox && lightboxImg && closeLightbox) {
                // Open lightbox when image is clicked
                galleryImages.forEach(img => {
                    img.addEventListener('click', function(e) {
                        // Check if this is not a market item
                        if (!this.closest('[data-market-id]')) {
                            e.preventDefault();
                            e.stopPropagation();
                            lightbox.classList.add('active');
                            lightboxImg.src = this.src;
                            lightboxImg.alt = this.alt;
                            document.body.style.overflow = 'hidden';
                        }
                    });
                });

                // Close lightbox when clicking on the close button
                closeLightbox.addEventListener('click', function() {
                    lightbox.classList.remove('active');
                    document.body.style.overflow = '';
                });

                // Close lightbox when clicking outside of the image
                lightbox.addEventListener('click', function(e) {
                    if (e.target === lightbox) {
                        lightbox.classList.remove('active');
                        document.body.style.overflow = '';
                    }
                });
            }
        }

        // Market Lightbox
        function initializeMarketLightbox() {
            const marketData = [
                {
                    id: 1,
                    name: "Pasar Balige",
                    image: "{{ asset('images/pasar-balige-onan-balerong.jpg') }}",
                    description: "Pasar Balige adalah salah satu pasar tertua di Kabupaten Toba. Didirikan pada tahun 1920, pasar ini awalnya merupakan tempat pertukaran hasil bumi antar penduduk lokal. Seiring perkembangan zaman, pasar ini menjadi pusat ekonomi yang penting di kawasan Balige. Pasar ini terkenal dengan berbagai produk pertanian lokal dan kerajinan tangan khas Batak Toba."
                },
                {
                    id: 2,
                    name: "Pasar Laguboti",
                    image: "{{ asset('images/2lgbt.jpg') }}",
                    description: "Pasar Laguboti memiliki sejarah yang kaya sejak era kolonial Belanda. Pasar ini terkenal dengan perdagangan rempah-rempah dan hasil pertanian lokal. Hingga kini, pasar ini masih menjadi salah satu pusat perdagangan penting di Kabupaten Toba. Pasar Laguboti juga dikenal sebagai tempat jual beli ulos (kain tradisional Batak) berkualitas tinggi."
                },
                {
                    id: 4,
                    name: "Pasar Parsoburan",
                    image: "{{ asset('images/parsoburan.jpg') }}",
                    description: "Pasar Parsoburan memiliki sejarah yang dimulai sejak tahun 1940-an. Pasar ini menjadi tempat penting bagi masyarakat lokal untuk menjual hasil pertanian dan perikanan dari Danau Toba. Hingga kini, pasar ini tetap menjadi pusat ekonomi yang vital. Pasar Parsoburan terkenal dengan ikan segar dan berbagai jenis sayuran dataran tinggi."
                },
                {
                    id: 5,
                    name: "Pasar Ajibata",
                    image: "{{ asset('images/ajibata.jpeg') }}",
                    description: "Pasar Ajibata terletak di tepi Danau Toba dan memiliki sejarah sebagai pasar nelayan sejak tahun 1950. Pasar ini terkenal dengan ikan segar dari Danau Toba dan menjadi salah satu destinasi wisata kuliner di Kabupaten Toba. Pengunjung dapat menikmati pemandangan danau yang indah sambil berbelanja berbagai hasil perikanan lokal."
                },
                {
                    id: 6,
                    name: "Pasar Lumban Julu",
                    image: "{{ asset('images/Lumbanjulu.jpg') }}",
                    description: "Pasar Lumban Julu didirikan pada tahun 1945 setelah kemerdekaan Indonesia. Pasar ini menjadi pusat perdagangan hasil pertanian dari dataran tinggi sekitar Lumban Julu dan terkenal dengan sayuran segar dan buah-buahan lokal. Pasar ini memiliki peran penting dalam mendukung ekonomi petani di daerah sekitarnya."
                },
                {
                    id: 7,
                    name: "Pasar Porsea",
                    image: "{{ asset('images/porsea.jpg') }}",
                    description: "Pasar Porsea memiliki sejarah yang dimulai sejak tahun 1930-an. Pasar ini berkembang pesat setelah dibangunnya pabrik kertas di daerah tersebut dan menjadi pusat ekonomi yang penting bagi masyarakat Porsea dan sekitarnya. Pasar ini menawarkan berbagai produk lokal dan menjadi pusat aktivitas ekonomi di kawasan timur Kabupaten Toba."
                },
                {
                    id: 8,
                    name: "Pasar Silimbat",
                    image: "{{ asset('images/Silimbat.jpg') }}",
                    description: "Pasar Silimbat adalah salah satu pasar tradisional yang telah ada sejak tahun 1940. Pasar ini terkenal dengan perdagangan ulos (kain tradisional Batak) dan menjadi pusat pelestarian budaya Batak melalui perdagangan kerajinan tradisional. Para pengrajin ulos dari berbagai desa di sekitar Sigumpar membawa karya terbaik mereka ke pasar ini."
                },
                {
                    id: 10,
                    name: "Pasar Lumban Lobu",
                    image: "{{ asset('images/Lumban Lobu.JPG') }}",
                    description: "Pasar Lumban lobu memiliki sejarah yang dimulai sejak tahun 1960. Pasar ini menjadi pusat perdagangan yang menghubungkan daerah Toba dengan Simalungun dan terkenal dengan keragaman produk dari kedua daerah tersebut. Pasar ini menjadi tempat bertemunya berbagai budaya dan tradisi kuliner dari kedua daerah."
                },
                {
                    id: 11,
                    name: "Pasar Habinsaran",
                    image: "{{ asset('images/Habinsaran.jpg') }}",
                    description: "Pasar Habinsaran didirikan pada tahun 1965 dan menjadi pusat ekonomi penting di daerah timur Kabupaten Toba. Pasar ini terkenal dengan hasil pertanian dari dataran tinggi Habinsaran yang subur. Sayuran dan buah-buahan dari daerah ini dikenal memiliki kualitas yang sangat baik karena kondisi tanah dan iklim yang ideal."
                },
                {
                    id: 12,
                    name: "Pasar Silaen",
                    image: "{{ asset('images/silaen.jpg') }}",
                    description: "Pasar Silaen memiliki sejarah yang dimulai sejak tahun 1950. Pasar ini menjadi pusat perdagangan hasil pertanian dan peternakan dari daerah Silaen dan sekitarnya. Hingga kini, pasar ini tetap menjadi pusat ekonomi yang penting. Produk susu dan olahan susu dari peternakan lokal menjadi salah satu daya tarik utama pasar ini."
                }
            ];

            const marketItems = document.querySelectorAll('.gallery-item[data-market-id]');
            const marketLightbox = document.getElementById('market-lightbox');
            const marketLightboxImg = document.getElementById('market-lightbox-img');
            const marketName = document.getElementById('market-name');
            const marketDescription = document.getElementById('market-description');
            const closeMarketLightbox = document.getElementById('close-market-lightbox');

            if (marketItems.length > 0 && marketLightbox) {
                marketItems.forEach(item => {
                    item.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        const marketId = parseInt(this.getAttribute('data-market-id'));
                        const market = marketData.find(m => m.id === marketId);
                        
                        if (market) {
                            if (marketLightboxImg) {
                                marketLightboxImg.src = market.image;
                                marketLightboxImg.alt = market.name;
                            }
                            
                            if (marketName) marketName.textContent = market.name;
                            if (marketDescription) marketDescription.textContent = market.description;
                            
                            marketLightbox.classList.add('active');
                            document.body.style.overflow = 'hidden';
                        }
                    });
                });

                if (closeMarketLightbox) {
                    closeMarketLightbox.addEventListener('click', function() {
                        marketLightbox.classList.remove('active');
                        document.body.style.overflow = '';
                    });
                }

                marketLightbox.addEventListener('click', function(e) {
                    if (e.target === marketLightbox) {
                        marketLightbox.classList.remove('active');
                        document.body.style.overflow = '';
                    }
                });

                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && marketLightbox.classList.contains('active')) {
                        marketLightbox.classList.remove('active');
                        document.body.style.overflow = '';
                    }
                });
            }
        }

        // Table functionality
        function initializeTableFunctionality() {
            const table = document.getElementById('hargaKomoditi');
            if (!table) return;

            const tbody = table.querySelector('tbody');
            if (!tbody) return;

            // Get all rows and store them
            allRows = Array.from(tbody.querySelectorAll('tr'));
            filteredRows = [...allRows];

            // Remove AOS attributes from table rows to prevent flickering
            allRows.forEach(row => {
                row.removeAttribute('data-aos');
                row.removeAttribute('data-aos-delay');
                row.removeAttribute('data-aos-anchor-placement');
                row.style.opacity = '1';
                row.style.transform = 'none';
            });

            // Initialize search
            initializeSearch();
            
            // Initialize pagination
            initializePagination();
            
            // Initialize view all button
            initializeViewAllButton();
            
            // Initialize sort functionality
            initializeSortFunctionality();
            
            // Update initial counts
            updateRowCount();
        }

        // Search functionality with debouncing to prevent flickering
        function initializeSearch() {
            const searchBox = document.getElementById('searchBox');
            if (searchBox) {
                searchBox.addEventListener('input', function() {
                    // Clear previous timeout
                    if (searchTimeout) {
                        clearTimeout(searchTimeout);
                    }
                    
                    // Set new timeout to debounce search
                    searchTimeout = setTimeout(() => {
                        performSearch();
                    }, 150); // 150ms delay to prevent excessive filtering
                });
            }
        }

        function performSearch() {
            const searchBox = document.getElementById('searchBox');
            if (!searchBox) return;

            const filter = searchBox.value.toUpperCase();
            
            // Use requestAnimationFrame to ensure smooth rendering
            requestAnimationFrame(() => {
                filteredRows = allRows.filter(row => {
                    const cells = row.querySelectorAll('td');
                    return Array.from(cells).some(cell => {
                        const text = cell.textContent || cell.innerText;
                        return text.toUpperCase().indexOf(filter) > -1;
                    });
                });

                // Reset to first page when searching
                currentPage = 1;
                
                if (isTableExpanded) {
                    showAllRows();
                } else {
                    showCurrentPage();
                }
                
                updateRowCount();
            });
        }

        // Pagination functionality
        function initializePagination() {
            const pagination = document.getElementById('tablePagination');
            if (!pagination) return;

            createPaginationControls();
            showCurrentPage();
        }

        function createPaginationControls() {
            const pagination = document.getElementById('tablePagination');
            if (!pagination) return;

            pagination.innerHTML = '';

            const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
            
            if (totalPages <= 1) {
                pagination.style.display = 'none';
                return;
            }

            pagination.style.display = 'flex';

            // Previous button
            const prevBtn = document.createElement('button');
            prevBtn.innerHTML = '<i class="fas fa-chevron-left"></i> Sebelumnya';
            prevBtn.classList.add('prev-btn');
            prevBtn.addEventListener('click', function() {
                if (currentPage > 1) {
                    currentPage--;
                    showCurrentPage();
                    updatePaginationButtons();
                }
            });
            pagination.appendChild(prevBtn);

            // Page info
            const pageInfo = document.createElement('span');
            pageInfo.className = 'page-info';
            pageInfo.innerHTML = `Halaman <span id="current-page">${currentPage}</span> dari ${totalPages}`;
            pagination.appendChild(pageInfo);

            // Next button
            const nextBtn = document.createElement('button');
            nextBtn.innerHTML = 'Selanjutnya <i class="fas fa-chevron-right"></i>';
            nextBtn.classList.add('next-btn');
            nextBtn.addEventListener('click', function() {
                if (currentPage < totalPages) {
                    currentPage++;
                    showCurrentPage();
                    updatePaginationButtons();
                }
            });
            pagination.appendChild(nextBtn);

            updatePaginationButtons();
        }

        function showCurrentPage() {
            // Use requestAnimationFrame for smooth transitions
            requestAnimationFrame(() => {
                // Hide all rows first
                allRows.forEach(row => {
                    row.style.display = 'none';
                });

                // Show only current page rows
                const start = (currentPage - 1) * rowsPerPage;
                const end = start + rowsPerPage;
                
                filteredRows.slice(start, end).forEach(row => {
                    row.style.display = '';
                });

                updatePaginationButtons();
                updateRowCount();
            });
        }

        function showAllRows() {
            // Use requestAnimationFrame for smooth transitions
            requestAnimationFrame(() => {
                // Hide all rows first
                allRows.forEach(row => {
                    row.style.display = 'none';
                });

                // Show all filtered rows
                filteredRows.forEach(row => {
                    row.style.display = '';
                });

                updateRowCount();
            });
        }

        function updatePaginationButtons() {
            const pagination = document.getElementById('tablePagination');
            if (!pagination) return;

            const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
            const prevBtn = pagination.querySelector('.prev-btn');
            const nextBtn = pagination.querySelector('.next-btn');
            const currentPageSpan = document.getElementById('current-page');

            if (prevBtn) {
                prevBtn.disabled = currentPage === 1;
            }

            if (nextBtn) {
                nextBtn.disabled = currentPage === totalPages;
            }

            if (currentPageSpan) {
                currentPageSpan.textContent = currentPage;
            }

            // Update page info
            const pageInfo = pagination.querySelector('.page-info');
            if (pageInfo) {
                pageInfo.innerHTML = `Halaman <span id="current-page">${currentPage}</span> dari ${totalPages}`;
            }
        }

        // View All Button functionality
        function initializeViewAllButton() {
            const viewAllBtn = document.getElementById('viewAllBtn');
            if (!viewAllBtn) return;

            viewAllBtn.addEventListener('click', function() {
                isTableExpanded = !isTableExpanded;
                const pagination = document.getElementById('tablePagination');
                
                if (isTableExpanded) {
                    showAllRows();
                    if (pagination) pagination.style.display = 'none';
                    
                    const spanElement = viewAllBtn.querySelector('span');
                    if (spanElement) spanElement.textContent = 'Tampilkan Sebagian';
                    viewAllBtn.classList.add('expanded');
                } else {
                    if (pagination) pagination.style.display = 'flex';
                    createPaginationControls();
                    showCurrentPage();
                    
                    const spanElement = viewAllBtn.querySelector('span');
                    if (spanElement) spanElement.textContent = 'Lihat Semua';
                    viewAllBtn.classList.remove('expanded');
                }
                
                updateRowCount();
            });
        }

        // Sort functionality
        function initializeSortFunctionality() {
            const headers = document.querySelectorAll('#hargaKomoditi th[onclick]');
            headers.forEach(header => {
                header.style.cursor = 'pointer';
            });
        }

        function sortTable(columnIndex, th) {
            const table = document.getElementById("hargaKomoditi");
            if (!table) return;
            
            let dir = "asc";
            let switchcount = 0;
            
            // Reset sort icons
            const sortIcons = document.querySelectorAll('.sort-icons');
            sortIcons.forEach(icon => {
                icon.classList.remove('active-asc', 'active-desc');
            });
            
            const currentSortIcon = th.querySelector('.sort-icons');
            
            // Sort the filteredRows array
            filteredRows.sort((a, b) => {
                const aVal = a.getElementsByTagName("TD")[columnIndex].innerHTML.toLowerCase();
                const bVal = b.getElementsByTagName("TD")[columnIndex].innerHTML.toLowerCase();
                
                if (dir === "asc") {
                    return aVal > bVal ? 1 : -1;
                } else {
                    return aVal < bVal ? 1 : -1;
                }
            });

            // If no switching happened and direction is asc, try desc
            if (switchcount === 0 && dir === "asc") {
                dir = "desc";
                filteredRows.sort((a, b) => {
                    const aVal = a.getElementsByTagName("TD")[columnIndex].innerHTML.toLowerCase();
                    const bVal = b.getElementsByTagName("TD")[columnIndex].innerHTML.toLowerCase();
                    return aVal < bVal ? 1 : -1;
                });
            }

            // Update sort icon
            if (currentSortIcon) {
                if (dir === "asc") {
                    currentSortIcon.classList.add('active-asc');
                } else {
                    currentSortIcon.classList.add('active-desc');
                }
            }

            // Refresh display with smooth transition
            requestAnimationFrame(() => {
                if (isTableExpanded) {
                    showAllRows();
                } else {
                    currentPage = 1; // Reset to first page after sorting
                    createPaginationControls();
                    showCurrentPage();
                }
            });
        }

        // Update row count display
        function updateRowCount() {
            const visibleRowsSpan = document.getElementById('visibleRows');
            const totalRowsSpan = document.getElementById('totalRows');
            
            if (visibleRowsSpan && totalRowsSpan) {
                let visibleCount = 0;
                allRows.forEach(row => {
                    if (row.style.display !== 'none') {
                        visibleCount++;
                    }
                });
                
                visibleRowsSpan.textContent = visibleCount;
                totalRowsSpan.textContent = filteredRows.length;
            }
        }
    </script>
</body>

</html>