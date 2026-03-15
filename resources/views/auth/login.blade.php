<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login | Orange Jordan</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;900&display=swap" rel="stylesheet">
    
    <!-- Boosted CSS -->
    <link href="https://cdn.jsdelivr.net/npm/boosted@5.3.3/dist/css/boosted.min.css" rel="stylesheet" crossorigin="anonymous">

    <style>
        :root {
            --orange-primary: #FF6600;
            --black-brand: #000000;
            --white-brand: #FFFFFF;
            --input-bg: #F4F4f4;
        }

        body {
            background-color: var(--black-brand);
            font-family: 'Montserrat', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            position: relative;
            overflow: hidden;
        }

        /* Animated Background */
        .bg-gradient {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 10% 20%, rgba(255, 102, 0, 0.1) 0%, transparent 40%),
                        radial-gradient(circle at 90% 80%, rgba(255, 102, 0, 0.05) 0%, transparent 40%);
            z-index: -1;
        }

        .login-card {
            background-color: var(--white-brand);
            border-radius: 24px;
            padding: 50px 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 450px;
            position: relative;
            z-index: 1;
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .brand-logo {
            display: block;
            margin: 0 auto 30px;
            height: 60px;
            width: 60px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 35px;
        }

        .login-header h2 {
            font-weight: 900;
            font-size: 1.8rem;
            color: var(--black-brand);
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .login-header p {
            color: #666;
            font-size: 0.95rem;
        }

        /* Form Styling */
        .form-control {
            background-color: var(--input-bg);
            border: 2px solid transparent;
            border-radius: 12px;
            padding: 14px 18px;
            font-size: 0.95rem;
            transition: all 0.3s;
            color: var(--black-brand);
            font-weight: 600;
        }

        .form-control:focus {
            background-color: var(--white-brand);
            border-color: var(--orange-primary);
            box-shadow: 0 0 0 4px rgba(255, 102, 0, 0.1);
            outline: none;
        }

        .form-label {
            font-weight: 700;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #444;
            margin-bottom: 10px;
            display: block;
        }

        .btn-login {
            background-color: var(--black-brand);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 16px;
            width: 100%;
            font-weight: 700;
            font-size: 1rem;
            margin-top: 20px;
            transition: all 0.3s;
            cursor: pointer;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.2);
        }

        .btn-login:hover {
            background-color: var(--orange-primary);
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(255, 102, 0, 0.3);
        }

        .forgot-password {
            text-align: center;
            margin-top: 25px;
        }

        .forgot-password a {
            color: #888;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            transition: color 0.2s;
        }

        .forgot-password a:hover {
            color: var(--orange-primary);
        }

        .invalid-feedback {
            font-size: 0.8rem;
            font-weight: 600;
            color: #dc3545;
            margin-top: 6px;
            padding-left: 5px;
        }

        /* Custom Checkbox */
        .form-check {
            margin-top: 15px;
            display: flex;
            align-items: center;
        }
        
        .form-check-input:checked {
            background-color: var(--orange-primary);
            border-color: var(--orange-primary);
        }

        .form-check-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #666;
            margin-left: 8px;
        }

    </style>
</head>
<body>
    <div class="bg-gradient"></div>
    
    <div class="login-card">
        <img src="https://boosted.orange.com/docs/5.3/assets/brand/orange-logo.svg" alt="Orange" class="brand-logo">
        
        <div class="login-header">
            <h2>Welcome Back</h2>
            <p>Enter your credentials to access your dashboard</p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="mb-4">
                <label for="email" class="form-label">Email Address</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                       name="email" value="{{ old('email') }}" required autocomplete="email" autofocus 
                       placeholder="name@orange.com">
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                       name="password" required autocomplete="current-password" 
                       placeholder="••••••••">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

           
            <button type="submit" class="btn-login">
                Sign In
            </button>
        </form>

       
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/boosted@5.3.3/dist/js/boosted.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
