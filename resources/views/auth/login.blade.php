<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('Images/Partoba(Font Putih).png') }}">
    <title>Login - PARTOBA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #0575E6;
            --primary-dark: #021B79;
            --secondary-color: #f8f9fa;
            --text-color: #333;
            --text-muted: #6c757d;
            --border-radius: 12px;
            --box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
            --transition: all 0.3s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            height: 100vh;
            font-family: 'Poppins', sans-serif;
            background-color: #ffffff;
            overflow: hidden;
        }
        
        .login-container {
            display: flex;
            width: 100%;
            height: 100%;
            position: relative;
        }
        
        .left-side {
            flex: 1.5;
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(2, 27, 121, 0.8)), url('{{ asset('Images/rumah batak.jpg') }}');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            flex-direction: column;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .left-side::before {
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
        
        .toba-ornament {
            position: absolute;
            width: 180px;
            height: 180px;
            background-image: url('{{ asset('Images/toba-ornament.png') }}');
            background-size: contain;
            background-repeat: no-repeat;
            opacity: 0.2;
            z-index: 1;
        }
        
        .toba-ornament.top-left {
            top: 20px;
            left: 20px;
            transform: rotate(-15deg);
        }
        
        .toba-ornament.bottom-right {
            bottom: 20px;
            right: 20px;
            transform: rotate(15deg);
        }
        
        @keyframes pulse {
            0% { transform: scale(1); opacity: 0.3; }
            50% { transform: scale(1.05); opacity: 0.5; }
            100% { transform: scale(1); opacity: 0.3; }
        }
        
        .left-side img.logo {
            max-width: 80%;
            margin-bottom: 40px;
            position: relative;
            z-index: 2;
            filter: drop-shadow(0 4px 15px rgba(0, 0, 0, 0.4));
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        
        .decorative-image {
            position: absolute;
            bottom: -100px;
            left: -100px;
            width: 580px;
            height: auto;
            opacity: 0.7;
            z-index: 1;
            animation: rotate 30s linear infinite;
        }
        
        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        .right-side {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: white;
            padding: 20px;
        }
        
        .login-box {
            width: 400px;
            padding: 40px;
            border-radius: var(--border-radius);
            background-color: white;
            box-shadow: var(--box-shadow);
            transition: var(--transition);
        }
        
        .login-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }
        
        .login-box h4 {
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 8px;
            font-size: 1.8rem;
        }
        
        .login-box p {
            color: var(--text-muted);
            margin-bottom: 30px;
            font-size: 0.95rem;
        }
        
        .form-group {
            margin-bottom: 25px;
            position: relative;
        }
        
        .input-group {
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            transition: var(--transition);
        }
        
        .input-group:focus-within {
            box-shadow: 0 2px 15px rgba(5, 117, 230, 0.2);
        }
        
        .input-group-text {
            background: white;
            border: none;
            border-right: none;
            color: var(--primary-color);
            font-size: 1.2rem;
            padding-left: 15px;
        }
        
        .form-control {
            border: none;
            padding: 15px;
            font-size: 1rem;
            background-color: white;
        }
        
        .form-control:focus {
            box-shadow: none;
            border-color: transparent;
        }
        
        .btn-primary {
            border-radius: var(--border-radius);
            padding: 12px;
            background: linear-gradient(to right, var(--primary-color), var(--primary-dark));
            border: none;
            font-weight: 600;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 15px rgba(5, 117, 230, 0.3);
            transition: var(--transition);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(5, 117, 230, 0.4);
            background: linear-gradient(to right, #0468cc, #021B79);
        }
        
        .alert {
            border-radius: var(--border-radius);
            padding: 15px;
            margin-bottom: 25px;
            border: none;
            animation: fadeIn 0.5s;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .alert-danger {
            background-color: #fff2f2;
            color: #e74c3c;
            border-left: 4px solid #e74c3c;
        }
        
        /* Responsive adjustments */
        @media (max-width: 992px) {
            .login-container {
                flex-direction: column;
            }
            
            .left-side, .right-side {
                flex: 1;
                width: 100%;
            }
            
            .left-side {
                padding: 40px 20px;
            }
            
            .decorative-image {
                width: 300px;
                bottom: -50px;
                left: -50px;
            }
            
            .toba-ornament {
                width: 120px;
                height: 120px;
            }
        }
        
        @media (max-width: 576px) {
            .login-box {
                width: 100%;
                padding: 30px 20px;
            }
            
            .left-side img.logo {
                max-width: 90%;
            }
            
            .toba-ornament {
                width: 80px;
                height: 80px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Left Side -->
        <div class="left-side">
            <div class="toba-ornament top-left"></div>
            <div class="toba-ornament bottom-right"></div>
            <img src="{{ asset('images/Partoba(Font Putih).png') }}" alt="PARTOBA" class="logo">
            {{-- <img src="{{ asset('images/back.png') }}" alt="Decorative" class="decorative-image"> --}}
            {{-- PARTOBA adalah aplikasi yang dikembangkan untuk memberikan informasi harga barang kebutuhan pokok di Kabupaten Toba. --}}
        </div>

        <!-- Right Side -->
        <div class="right-side">
            <div class="login-box">
                <h4 class="text-center">Hello Again!</h4>
                <p class="text-center">Welcome Back to PARTOBA Admin Web</p>

                @if(session('error'))
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-circle me-2"></i>
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('api.login') }}" method="POST">
                    @csrf
                    <!-- Username Input -->
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" name="username" class="form-control" placeholder="Username" required autocomplete="username">
                        </div>
                    </div>

                    <!-- Password Input -->
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" class="form-control" placeholder="Password" required autocomplete="current-password">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                    </button>
                </form>
                
                <div class="text-center mt-4">
                    <small class="text-muted">© {{ date('Y') }} PARTOBA. All rights reserved.</small>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Add focus effects
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', () => {
                input.parentElement.style.boxShadow = '0 2px 15px rgba(5, 117, 230, 0.2)';
            });
            
            input.addEventListener('blur', () => {
                input.parentElement.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.08)';
            });
        });
        
        // Auto-hide alert after 5 seconds
        const alert = document.querySelector('.alert');
        if (alert) {
            setTimeout(() => {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-10px)';
                setTimeout(() => {
                    alert.style.display = 'none';
                }, 500);
            }, 5000);
        }
    </script>
</body>
</html>
