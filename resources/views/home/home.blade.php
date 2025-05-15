<!DOCTYPE html>
<html lang="id" class="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('Images/logo.png') }}">
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
            
            /* Light Theme Variables (Default) */
            --bg-main: #f8f9fa;
            --bg-card: #ffffff;
            --text-primary: #333333;
            --text-secondary: #666666;
            --border-color: #eaeaea;
            --hover-bg: #f0f4f8;
            --table-header-bg: #f3f6fb;
            --table-hover-bg: #f9f9f9;
            --header-bg: rgba(255, 255, 255, 0.95);
            --header-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            --footer-text: rgba(255, 255, 255, 0.7);
            --skeleton-bg: #f0f0f0;
            --skeleton-shine: rgba(255, 255, 255, 0.5);
        }
        
        /* Dark Theme Variables */
        html.dark {
            --bg-main: #121212;
            --bg-card: #1e1e1e;
            --text-primary: #e0e0e0;
            --text-secondary: #aaaaaa;
            --border-color: #333333;
            --hover-bg: #2a2a2a;
            --table-header-bg: #252525;
            --table-hover-bg: #2c2c2c;
            --header-bg: rgba(30, 30, 30, 0.95);
            --header-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            --footer-text: rgba(255, 255, 255, 0.5);
            --skeleton-bg: #2a2a2a;
            --skeleton-shine: rgba(255, 255, 255, 0.1);
        }

        html {
            scroll-behavior: smooth;
            scroll-padding-top: 80px;
        }

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: var(--bg-main);
            color: var(--text-primary);
            line-height: 1.6;
            overflow-x: hidden;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .container {
            max-width: 1200px;
            margin: auto;
            padding: 20px;
        }

        /* Theme Toggle Button */
        .theme-toggle {
            position: fixed;
            bottom: 30px;
            left: 30px;
            width: 50px;
            height: 50px;
            background: var(--primary-color);
            color: var(--white);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 99;
            box-shadow: 0 4px 15px rgba(5, 117, 230, 0.3);
            border: none;
            transition: all 0.3s ease;
        }

        .theme-toggle:hover {
            transform: rotate(30deg) scale(1.1);
            background: var(--secondary-color);
        }

        .theme-toggle i {
            font-size: 20px;
            transition: all 0.3s ease;
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
            background: var(--header-bg);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding: 10px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--header-shadow);
            z-index: 100;
            transition: all 0.4s ease;
        }

        .header.scrolled {
            padding: 5px 0;
            background: var(--header-bg);
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
            color: var(--text-primary);
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
            color: var(--text-primary);
            cursor: pointer;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(2, 27, 121, 0.8)), url('{{ asset('Images/toba-background.JPEG') }}');
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
            background-image: url('{{ asset('Images/toba-ornament.png') }}');
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
            border: 1px solid var(--border-color);
            border-radius: 30px;
            font-size: 16px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            background: var(--bg-card);
            color: var(--text-primary);
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
            color: var(--text-secondary);
            max-width: 700px;
            margin: 0 auto;
            font-size: 1.1rem;
        }

        /* Komoditi Table */
        .table-container {
            background: var(--bg-card);
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            overflow: hidden;
            margin-bottom: 40px;
            transition: all 0.3s ease;
        }

        .table-container:hover {
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .komoditi {
            width: 100%;
            border-collapse: collapse;
        }

        .komoditi th,
        .komoditi td {
            padding: 16px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
            transition: all 0.2s ease;
        }

        .komoditi th {
            background-color: var(--table-header-bg);
            font-weight: 600;
            color: var(--accent-color);
            position: relative;
            cursor: pointer;
            user-select: none;
        }

        .komoditi th:hover {
            background-color: var(--hover-bg);
        }

        .komoditi tr {
            transition: all 0.3s ease;
        }

        .komoditi tr:hover {
            background-color: var(--table-hover-bg);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }

        .komoditi td:nth-child(3) {
            font-weight: 600;
            color: #2a9d8f;
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

        /* Table pagination */
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
            gap: 5px;
        }

        .pagination button {
            padding: 8px 15px;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            color: var(--text-primary);
        }

        .pagination button:hover,
        .pagination button.active {
            background: var(--primary-color);
            color: var(--white);
            border-color: var(--primary-color);
        }

        /* Tentang Section */
        .tentang {
            background-color: var(--bg-card);
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
            color: var(--text-primary);
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

        /* Footer */
        footer {
            background: linear-gradient(145deg, var(--primary-color), rgba(2, 40, 138, 0.9), var(--secondary-color));
            color: var(--white);
            text-align: center;
            padding: 50px 0 30px;
            margin-top: 40px;
            position: relative;
            overflow: hidden;
        }

        footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255, 255, 255, 0.05)" d="M0,96L48,112C96,128,192,160,288,186.7C384,213,480,235,576,213.3C672,192,768,128,864,128C960,128,1056,192,1152,213.3C1248,235,1344,213,1392,202.7L1440,192L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z"></path></svg>');
            background-size: cover;
            background-position: center;
            opacity: 0.4;
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
            transform: translateY(-3px);
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
            transform: translateY(-3px);
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
                background: var(--bg-card);
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
            
            .theme-toggle {
                bottom: 90px;
                left: 20px;
                width: 45px;
                height: 45px;
            }
        }

        /* Market Lightbox Styles */
        .market-lightbox-content {
            background: var(--bg-card);
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
        
        .market-detail-image {
            width: 40%;
            height: auto;
            overflow: hidden;
        }
        
        .market-detail-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .market-info {
            width: 60%;
            padding: 25px;
            overflow-y: auto;
            background-color: var(--bg-main);
            border-left: 3px solid var(--primary-color);
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
            color: var(--text-primary);
            padding: 15px;
            background-color: rgba(5, 117, 230, 0.05);
            border-radius: 8px;
            border-left: 3px solid var(--primary-color);
            font-size: 16px;
        }
        
        @media (max-width: 768px) {
            .market-lightbox-content {
                flex-direction: column;
            }
            
            .market-detail-image {
                width: 100%;
                height: 200px;
            }
            
            .market-info {
                width: 100%;
                border-left: none;
                border-top: 3px solid var(--primary-color);
            }
        }

        .highlight-description {
            animation: pulse 1s ease;
        }

        @keyframes pulse {
            0% { background-color: rgba(5, 117, 230, 0.05); }
            50% { background-color: rgba(5, 117, 230, 0.2); }
            100% { background-color: rgba(5, 117, 230, 0.05); }
        }

        .market-description {
            scroll-margin-top: 20px;
        }

        /* Ensure the market info section takes focus on mobile */
        @media (max-width: 768px) {
            .market-lightbox-content {
                flex-direction: column-reverse;
            }
            
            .market-detail-image {
                width: 100%;
                height: 200px;
            }
            
            .market-info {
                width: 100%;
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
    
    <!-- Theme Toggle Button -->
    <button class="theme-toggle" id="themeToggle" aria-label="Toggle dark/light theme">
        <i class="fas fa-sun"></i>
    </button>

    <!-- Navbar -->
    <header class="header">
        <div class="header-container">
            <div class="logo">
                <a href="#beranda">
                    <img src="{{ asset('Images/logo.png') }}" alt="PARTOBA Logo">
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
            <a href="https://play.google.com/store/apps/details?id=com.radio.delfm&pcampaignid=web_share" 
               class="btn-download" data-aos="fade-up" data-aos-delay="300">
                <i class="fab fa-google-play"></i> Unduh Sekarang
            </a>
        </div>
    </section>

    <!-- Pasar Section (NEW) -->
    <section id="pasar" class="container">
        <div class="section-header" data-aos="fade-up">
            <h2>Pasar di Kabupaten Toba</h2>
            <p>Jelajahi berbagai pasar tradisional di Kabupaten Toba dan pelajari sejarah serta keunikan masing-masing pasar</p>
        </div>

        <div class="gallery-grid">
            <div class="gallery-item" data-aos="" data-aos-delay="100" data-market-id="1">
                <img src="{{ asset('Images/toba-background.jpeg') }}" alt="Pasar Balige">
                <div class="description">
                    <i class="fas fa-store-alt mr-2"></i> Pasar Balige
                </div>
            </div>
            <div class="gallery-item" data-aos="zoom-in" data-aos-delay="200" data-market-id="2">
                <img src="{{ asset('Images/lAGUBOTI.jpg') }}" alt="Pasar Laguboti">
                <div class="description">
                    <i class="fas fa-store-alt mr-2"></i> Pasar Laguboti
                </div>
            </div>
            {{-- <div class="gallery-item" data-aos="zoom-in" data-aos-delay="300" data-market-id="3">
                <img src="{{ asset('Images/placeholder.svg') }}" alt="Pasar Siborong-borong">
                <div class="description">
                    <i class="fas fa-store-alt mr-2"></i> Pasar Siborong-borong
                </div>
            </div> --}}
            {{-- <div class="gallery-item" data-aos="zoom-in" data-aos-delay="100" data-market-id="4">
                <img src="{{ asset('Images/placeholder.svg') }}" alt="Pasar Parsoburan">
                <div class="description">
                    <i class="fas fa-store-alt mr-2"></i> Pasar Parsoburan
                </div>
            </div> --}}
            <div class="gallery-item" data-aos="zoom-in" data-aos-delay="200" data-market-id="5">
                <img src="{{ asset('Images/ajibata.jpeg') }}" alt="Pasar Ajibata">
                <div class="description">
                    <i class="fas fa-store-alt mr-2"></i> Pasar Ajibata
                </div>
            </div>
            <div class="gallery-item" data-aos="zoom-in" data-aos-delay="300" data-market-id="6">
                <img src="{{ asset('Images/Lumbanjulu.jpg') }}" alt="Pasar Lumban Julu">
                <div class="description">
                    <i class="fas fa-store-alt mr-2"></i> Pasar Lumban Julu
                </div>
            </div>
            <div class="gallery-item" data-aos="zoom-in" data-aos-delay="100" data-market-id="7">
                <img src="{{ asset('Images/porsea2.jpg') }}" alt="Pasar Porsea">
                <div class="description">
                    <i class="fas fa-store-alt mr-2"></i> Pasar Porsea
                </div>
            </div>
            {{-- <div class="gallery-item" data-aos="zoom-in" data-aos-delay="200" data-market-id="8">
                <img src="{{ asset('Images/placeholder.svg') }}" alt="Pasar Sigumpar">
                <div class="description">
                    <i class="fas fa-store-alt mr-2"></i> Pasar Sigumpar
                </div>
            </div> --}}
            {{-- <div class="gallery-item" data-aos="zoom-in" data-aos-delay="300" data-market-id="9">
                <img src="{{ asset('Images/placeholder.svg') }}" alt="Pasar Meat">
                <div class="description">
                    <i class="fas fa-store-alt mr-2"></i> Pasar Meat
                </div>
            </div> --}}
            {{-- <div class="gallery-item" data-aos="zoom-in" data-aos-delay="100" data-market-id="10">
                <img src="{{ asset('Images/placeholder.svg') }}" alt="Pasar Siantar Narumonda">
                <div class="description">
                    <i class="fas fa-store-alt mr-2"></i> Pasar Siantar Narumonda
                </div>
            </div> --}}
            {{-- <div class="gallery-item" data-aos="zoom-in" data-aos-delay="200" data-market-id="11">
                <img src="{{ asset('Images/placeholder.svg') }}" alt="Pasar Habinsaran">
                <div class="description">
                    <i class="fas fa-store-alt mr-2"></i> Pasar Habinsaran
                </div>
            </div> --}}
            <div class="gallery-item" data-aos="zoom-in" data-aos-delay="300" data-market-id="12">
                <img src="{{ asset('Images/silaen.jpg') }}" alt="Pasar Silaen">
                <div class="description">
                    <i class="fas fa-store-alt mr-2"></i> Pasar Silaen
                </div>
            </div>
            {{-- <div class="gallery-item" data-aos="zoom-in" data-aos-delay="100" data-market-id="13">
                <img src="{{ asset('Images/placeholder.svg') }}" alt="Pasar Tambunan">
                <div class="description">
                    <i class="fas fa-store-alt mr-2"></i> Pasar Tambunan
                </div>
            </div>
        </div> --}}
    </section>

    <!-- Market Lightbox -->
<div id="market-lightbox" class="lightbox">
    <span class="close" id="close-market-lightbox">&times;</span>
    <div class="market-lightbox-content">
        <div class="market-detail-image">
            <img id="market-detail-img" src="{{ asset('Images/placeholder.svg') }}" alt="Pasar Detail">
        </div>
        <div class="market-info" id="market-info-section">
            <h3 id="market-name">Nama Pasar</h3>
            <div class="market-description" id="market-description-section">
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
            <input type="text" id="searchBox" onkeyup="searchTable()" placeholder="Cari komoditi, pasar, atau kategori...">
            <i class="fas fa-search search-icon"></i>
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
                <tr class="table-row" data-aos="fade-up" data-aos-delay="{{ 100 + ($key * 50) }}" data-aos-anchor-placement="top-bottom">
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
                <img src="{{ asset('Images/galeri1.jpg') }}" alt="Pasar Toba">
                <div class="description">
                    <i class="fas fa-store-alt mr-2"></i> Pasar Toba
                </div>
            </div>
            <div class="gallery-item" data-aos="zoom-in" data-aos-delay="200">
                <img src="{{ asset('Images/galeri2.jpg') }}" alt="Aktivitas Pasar">
                <div class="description">
                    <i class="fas fa-users mr-2"></i> Aktivitas Pasar
                </div>
            </div>
            <div class="gallery-item" data-aos="zoom-in" data-aos-delay="300">
                <img src="{{ asset('Images/galeri3.jpg') }}" alt="Komoditi Pasar">
                <div class="description">
                    <i class="fas fa-carrot mr-2"></i> Komoditi Pasar
                </div>
            </div>
            <div class="gallery-item" data-aos="zoom-in" data-aos-delay="400">
                <img src="{{ asset('Images/galeri4.jpg') }}" alt="Transaksi Pasar">
                <div class="description">
                    <i class="fas fa-hand-holding-usd mr-2"></i> Transaksi Pasar
                </div>
            </div>
            <div class="gallery-item" data-aos="zoom-in" data-aos-delay="500">
                <img src="{{ asset('Images/galeri5.jpeg') }}" alt="Pedagang Lokal">
                <div class="description">
                    <i class="fas fa-user-friends mr-2"></i> Pedagang Lokal
                </div>
            </div>
            <div class="gallery-item" data-aos="zoom-in" data-aos-delay="600">
                <img src="{{ asset('Images/galeri6.jpeg') }}" alt="Suasana Pagi di Pasar">
                <div class="description">
                    <i class="fas fa-sun mr-2"></i> Suasana Pagi di Pasar
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
            <div class="footer-text" data-aos="fade-up" data-aos-delay="100">
                <p>&copy; {{ date('Y') }} PARTOBA - Dinas Koperasi, UKM, Perindustrian dan Perdagangan Kabupaten Toba.
                    All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- JavaScript Libraries -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <!-- JavaScript -->
    <script>
        // Initialize AOS
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                duration: 800,
                easing: 'ease-in-out',
                once: true,
                mirror: false
            });

            // Theme Toggle Functionality
            const themeToggle = document.getElementById('themeToggle');
            const htmlElement = document.documentElement;
            const themeIcon = themeToggle.querySelector('i');
            
            // Check for saved theme preference or respect OS preference
            const savedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            
            // Set initial theme
            if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
                htmlElement.classList.add('dark');
                themeIcon.classList.remove('fa-sun');
                themeIcon.classList.add('fa-moon');
            }
            
            // Theme toggle event listener
            themeToggle.addEventListener('click', function() {
                if (htmlElement.classList.contains('dark')) {
                    htmlElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                    themeIcon.classList.remove('fa-moon');
                    themeIcon.classList.add('fa-sun');
                } else {
                    htmlElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                    themeIcon.classList.remove('fa-sun');
                    themeIcon.classList.add('fa-moon');
                }
            });

            // Preloader
            setTimeout(function() {
                const preloader = document.querySelector('.preloader');
                preloader.classList.add('fade-out');
                setTimeout(() => {
                    preloader.style.display = 'none';
                }, 500);
            }, 1000);

            // Mobile Menu Toggle
            const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
            const nav = document.querySelector('.nav');

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

            // Header scroll effect
            const header = document.querySelector('.header');
            window.addEventListener('scroll', function() {
                if (window.scrollY > 50) {
                    header.classList.add('scrolled');
                } else {
                    header.classList.remove('scrolled');
                }
            });

            // Back to top button
            const backToTopBtn = document.querySelector('.back-to-top');
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

            // Gallery Lightbox
            const galleryImages = document.querySelectorAll('.gallery-item img');
            const lightbox = document.getElementById('lightbox');
            const lightboxImg = document.getElementById('lightbox-img');
            const closeLightbox = document.getElementById('close-lightbox');

            // Open lightbox when image is clicked
            galleryImages.forEach(img => {
                img.addEventListener('click', function() {
                    lightbox.classList.add('active');
                    lightboxImg.src = this.src;
                    lightboxImg.alt = this.alt;
                });
            });

            // Close lightbox when clicking on the close button
            closeLightbox.addEventListener('click', function() {
                lightbox.classList.remove('active');
            });

            // Close lightbox when clicking outside of the image
            lightbox.addEventListener('click', function(e) {
                if (e.target === lightbox) {
                    lightbox.classList.remove('active');
                }
            });

            // Table pagination
            const table = document.getElementById('hargaKomoditi');
            const rowsPerPage = 10;
            const rows = table.querySelectorAll('tbody tr');
            const pageCount = Math.ceil(rows.length / rowsPerPage);
            const pagination = document.getElementById('tablePagination');

            // Create pagination buttons
            for (let i = 1; i <= pageCount; i++) {
                const btn = document.createElement('button');
                btn.innerText = i;
                btn.addEventListener('click', function() {
                    document.querySelectorAll('#tablePagination button').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    showPage(i);
                });
                pagination.appendChild(btn);
            }

            // Show first page by default
            if (pageCount > 0) {
                pagination.querySelector('button').classList.add('active');
                showPage(1);
            }

            function showPage(pageNum) {
                const start = (pageNum - 1) * rowsPerPage;
                const end = start + rowsPerPage;

                rows.forEach((row, index) => {
                    if (index >= start && index < end) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            // Add sort icons to table headers
            const tableHeaders = document.querySelectorAll('#hargaKomoditi th');
            tableHeaders.forEach(header => {
                if (!header.querySelector('.sort-icons')) {
                    const sortIcons = document.createElement('span');
                    sortIcons.className = 'sort-icons';
                    sortIcons.innerHTML = `
                        <i class="fas fa-caret-up asc"></i>
                        <i class="fas fa-caret-down desc"></i>
                    `;
                    header.appendChild(sortIcons);
                }
            });

            // Market data with detail images (different from the thumbnail images)
            const marketData = [
                {
                    id: 1,
                    name: "Pasar Balige",
                    thumbnailImage: "{{ asset('Images/toba-background.jpeg') }}", // This is the image shown in the grid
                    detailImage: "{{ asset('Images/pasar-balige-onan-balerong.jpg') }}", // This is the image shown in the lightbox
                    description: "Pasar Balige adalah salah satu pasar tertua di Kabupaten Toba. Didirikan pada tahun 1920, pasar ini awalnya merupakan tempat pertukaran hasil bumi antar penduduk lokal. Seiring perkembangan zaman, pasar ini menjadi pusat ekonomi yang penting di kawasan Balige."
                },
                {
                    id: 2,
                    name: "Pasar Laguboti",
                    thumbnailImage: "{{ asset('Images/placeholder.svg') }}",
                    detailImage: "{{ asset('Images/2lgbt.jpg') }}",
                    description: "Pasar Laguboti memiliki sejarah yang kaya sejak era kolonial Belanda. Pasar ini terkenal dengan perdagangan rempah-rempah dan hasil pertanian lokal. Hingga kini, pasar ini masih menjadi salah satu pusat perdagangan penting di Kabupaten Toba."
                },
                // {
                //     id: 3,
                //     name: "Pasar Siborong-borong",
                //     thumbnailImage: "{{ asset('Images/placeholder.svg') }}",
                //     detailImage: "{{ asset('Images/placeholder.svg') }}",
                //     description: "Pasar Siborong-borong didirikan pada tahun 1935 dan menjadi pusat perdagangan penting yang menghubungkan daerah Toba dengan Tapanuli Utara. Pasar ini terkenal dengan keragaman produk pertanian dan kerajinan tangan tradisional Batak."
                // },
                // {
                //     id: 4,
                //     name: "Pasar Parsoburan",
                //     thumbnailImage: "{{ asset('Images/placeholder.svg') }}",
                //     detailImage: "{{ asset('Images/placeholder.svg') }}",
                //     description: "Pasar Parsoburan memiliki sejarah yang dimulai sejak tahun 1940-an. Pasar ini menjadi tempat penting bagi masyarakat lokal untuk menjual hasil pertanian dan perikanan dari Danau Toba. Hingga kini, pasar ini tetap menjadi pusat ekonomi yang vital."
                // },
                {
                    id: 5,
                    name: "Pasar Ajibata",
                    thumbnailImage: "{{ asset('Images/placeholder.svg') }}",
                    detailImage: "{{ asset('Images/ajibata.jpeg') }}",
                    description: "Pasar Ajibata terletak di tepi Danau Toba dan memiliki sejarah sebagai pasar nelayan sejak tahun 1950. Pasar ini terkenal dengan ikan segar dari Danau Toba dan menjadi salah satu destinasi wisata kuliner di Kabupaten Toba."
                },
                {
                    id: 6,
                    name: "Pasar Lumban Julu",
                    thumbnailImage: "{{ asset('Images/placeholder.svg') }}",
                    detailImage: "{{ asset('Images/Lumbanjulu.jpg') }}",
                    description: "Pasar Lumban Julu didirikan pada tahun 1945 setelah kemerdekaan Indonesia. Pasar ini menjadi pusat perdagangan hasil pertanian dari dataran tinggi sekitar Lumban Julu dan terkenal dengan sayuran segar dan buah-buahan lokal."
                },
                {
                    id: 7,
                    name: "Pasar Porsea",
                    thumbnailImage: "{{ asset('Images/placeholder.svg') }}",
                    detailImage: "{{ asset('Images/porsea.jpg') }}",
                    description: "Pasar Porsea memiliki sejarah yang dimulai sejak tahun 1930-an. Pasar ini berkembang pesat setelah dibangunnya pabrik kertas di daerah tersebut dan menjadi pusat ekonomi yang penting bagi masyarakat Porsea dan sekitarnya."
                },
                // {
                //     id: 8,
                //     name: "Pasar Sigumpar",
                //     thumbnailImage: "{{ asset('Images/placeholder.svg') }}",
                //     detailImage: "{{ asset('Images/placeholder.svg') }}",
                //     description: "Pasar Sigumpar adalah salah satu pasar tradisional yang telah ada sejak tahun 1940. Pasar ini terkenal dengan perdagangan ulos (kain tradisional Batak) dan menjadi pusat pelestarian budaya Batak melalui perdagangan kerajinan tradisional."
                // },
                // {
                //     id: 9,
                //     name: "Pasar Meat",
                //     thumbnailImage: "{{ asset('Images/placeholder.svg') }}",
                //     detailImage: "{{ asset('Images/placeholder.svg') }}",
                //     description: "Pasar Meat didirikan pada tahun 1955 dan menjadi pusat perdagangan hasil pertanian dari daerah sekitar. Pasar ini terkenal dengan kopi dan hasil perkebunan lainnya yang menjadi komoditas unggulan dari daerah Meat."
                // },
                // {
                //     id: 10,
                //     name: "Pasar Siantar Narumonda",
                //     thumbnailImage: "{{ asset('Images/placeholder.svg') }}",
                //     detailImage: "{{ asset('Images/placeholder.svg') }}",
                //     description: "Pasar Siantar Narumonda memiliki sejarah yang dimulai sejak tahun 1960. Pasar ini menjadi pusat perdagangan yang menghubungkan daerah Toba dengan Simalungun dan terkenal dengan keragaman produk dari kedua daerah tersebut."
                // },
                // {
                //     id: 11,
                //     name: "Pasar Habinsaran",
                //     thumbnailImage: "{{ asset('Images/placeholder.svg') }}",
                //     detailImage: "{{ asset('Images/placeholder.svg') }}",
                //     description: "Pasar Habinsaran didirikan pada tahun 1965 dan menjadi pusat ekonomi penting di daerah timur Kabupaten Toba. Pasar ini terkenal dengan hasil pertanian dari dataran tinggi Habinsaran yang subur."
                // },
                {
                    id: 12,
                    name: "Pasar Silaen",
                    thumbnailImage: "{{ asset('Images/placeholder.svg') }}",
                    detailImage: "{{ asset('Images/silaen.jpg') }}",
                    description: "Pasar Silaen memiliki sejarah yang dimulai sejak tahun 1950. Pasar ini menjadi pusat perdagangan hasil pertanian dan peternakan dari daerah Silaen dan sekitarnya. Hingga kini, pasar ini tetap menjadi pusat ekonomi yang penting."
                }
                // {
                //     id: 13,
                //     name: "Pasar Tambunan",
                //     thumbnailImage: "{{ asset('Images/placeholder.svg') }}",
                //     detailImage: "{{ asset('Images/placeholder.svg') }}",
                //     description: "Pasar Tambunan adalah salah satu pasar tradisional yang telah ada sejak tahun 1970. Pasar ini terkenal dengan perdagangan hasil pertanian dan kerajinan tangan dari daerah Tambunan dan sekitarnya."
                // }
            ];

            // Market Lightbox
            const marketItems = document.querySelectorAll('.gallery-item[data-market-id]');
            const marketLightbox = document.getElementById('market-lightbox');
            const marketDetailImg = document.getElementById('market-detail-img');
            const marketName = document.getElementById('market-name');
            const marketDescription = document.getElementById('market-description');
            const closeMarketLightbox = document.getElementById('close-market-lightbox');

            // Open market lightbox when market item is clicked
            marketItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation(); // Prevent triggering the gallery lightbox
                    
                    const marketId = parseInt(this.getAttribute('data-market-id'));
                    const market = marketData.find(m => m.id === marketId);
                    
                    if (market) {
                        // Set the detail image (not the same as the clicked image)
                        marketDetailImg.src = market.detailImage;
                        marketDetailImg.alt = market.name;
                        
                        // Set the market name and description
                        marketName.textContent = market.name;
                        marketDescription.textContent = market.description;
                        
                        // Show the lightbox
                        marketLightbox.classList.add('active');
                        document.body.style.overflow = 'hidden'; // Prevent scrolling when lightbox is open
                        
                        // Immediately focus on the description section
                        const descriptionSection = document.getElementById('market-description-section');
                        if (descriptionSection) {
                            // Use a small timeout to ensure the lightbox is fully rendered before scrolling
                            setTimeout(() => {
                                descriptionSection.scrollIntoView({ behavior: 'auto', block: 'start' });
                                // Add a highlight effect to draw attention to the description
                                descriptionSection.classList.add('highlight-description');
                                setTimeout(() => {
                                    descriptionSection.classList.remove('highlight-description');
                                }, 1000);
                            }, 50);
                        }
                    }
                });
            });

            // Close market lightbox when close button is clicked
            closeMarketLightbox.addEventListener('click', function() {
                marketLightbox.classList.remove('active');
                document.body.style.overflow = '';
            });

            // Close market lightbox when clicking outside the content
            marketLightbox.addEventListener('click', function(e) {
                if (e.target === marketLightbox) {
                    marketLightbox.classList.remove('active');
                    document.body.style.overflow = '';
                }
            });

            // Close market lightbox with ESC key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && marketLightbox.classList.contains('active')) {
                    marketLightbox.classList.remove('active');
                    document.body.style.overflow = '';
                }
            });
        });

        // Search table function
        function searchTable() {
            let input = document.getElementById("searchBox").value.toLowerCase();
            let table = document.getElementById("hargaKomoditi");
            let tr = table.getElementsByTagName("tr");

            for (let i = 1; i < tr.length; i++) {
                let tdKomoditi = tr[i].getElementsByTagName("td")[1];
                let tdHarga = tr[i].getElementsByTagName("td")[2];
                let tdTanggal = tr[i].getElementsByTagName("td")[3];
                let tdPasar = tr[i].getElementsByTagName("td")[4];
                let tdKategori = tr[i].getElementsByTagName("td")[5];

                if (tdKomoditi && tdHarga && tdTanggal && tdPasar && tdKategori) {
                    let textKomoditi = tdKomoditi.textContent.toLowerCase();
                    let textHarga = tdHarga.textContent.toLowerCase();
                    let textTanggal = tdTanggal.textContent.toLowerCase();
                    let textPasar = tdPasar.textContent.toLowerCase();
                    let textKategori = tdKategori.textContent.toLowerCase();

                    if (textKomoditi.includes(input) ||
                        textHarga.includes(input) ||
                        textTanggal.includes(input) ||
                        textPasar.includes(input) ||
                        textKategori.includes(input)) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }

        // Sort table function
        function sortTable(columnIndex, headerElement) {
            const table = document.getElementById("hargaKomoditi");
            let rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
            switching = true;

            // Reset all sort icons
            const allSortIcons = document.querySelectorAll("#hargaKomoditi th .sort-icons");
            allSortIcons.forEach(icon => {
                icon.classList.remove("active-asc", "active-desc");
            });

            // Get the sort icon for this header
            const sortIcon = headerElement.querySelector(".sort-icons");
            
            // Set the direction
            dir = sortIcon.classList.contains("active-asc") ? "desc" : "asc";

            // Update the sort icon
            if (dir === "asc") {
                sortIcon.classList.add("active-asc");
            } else {
                sortIcon.classList.add("active-desc");
            }

            while (switching) {
                switching = false;
                rows = table.rows;

                for (i = 1; i < (rows.length - 1); i++) {
                    shouldSwitch = false;

                    x = rows[i].getElementsByTagName("TD")[columnIndex];
                    y = rows[i + 1].getElementsByTagName("TD")[columnIndex];

                    let xContent = x.textContent || x.innerText;
                    let yContent = y.textContent || y.innerText;

                    // Special handling for numbers and prices
                    if (columnIndex === 0) { // No column
                        xContent = parseInt(xContent);
                        yContent = parseInt(yContent);
                    } else if (columnIndex === 2) { // Price column
                        xContent = parseFloat(xContent.replace(/[^\d]/g, '')) || 0;
                        yContent = parseFloat(yContent.replace(/[^\d]/g, '')) || 0;
                    } else if (columnIndex === 3) { // Date column
                        xContent = new Date(xContent);
                        yContent = new Date(yContent);
                    }

                    if (dir === "asc") {
                        if (xContent > yContent) {
                            shouldSwitch = true;
                            break;
                        }
                    } else if (dir === "desc") {
                        if (xContent < yContent) {
                            shouldSwitch = true;
                            break;
                        }
                    }
                }

                if (shouldSwitch) {
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                    switchcount++;
                } else {
                    if (switchcount === 0 && dir === "asc") {
                        dir = "desc";
                        sortIcon.classList.remove("active-asc");
                        sortIcon.classList.add("active-desc");
                        switching = true;
                    }
                }
            }
        }

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();

                const targetId = this.getAttribute('href');
                const targetElement = document.querySelector(targetId);

                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
</body>

</html>