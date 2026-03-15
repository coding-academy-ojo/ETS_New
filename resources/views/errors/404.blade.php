<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 | Page Not Found</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --orange-brand: #FF6600;
            --black-brand: #000000;
            --white-brand: #FFFFFF;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--black-brand);
            color: var(--white-brand);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .container {
            text-align: center;
            max-width: 600px;
            padding: 20px;
        }

        .error-code {
            font-size: 10rem;
            font-weight: 900;
            line-height: 1;
            margin-bottom: 20px;
            color: var(--orange-brand);
            text-shadow: 10px 10px 0px rgba(255, 102, 0, 0.1);
            animation: bounce 2s infinite ease-in-out;
        }

        .error-message {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .error-description {
            font-size: 1rem;
            margin-bottom: 40px;
            opacity: 0.7;
            line-height: 1.6;
        }

        .back-button {
            display: inline-block;
            background-color: var(--orange-brand);
            color: var(--white-brand);
            padding: 15px 40px;
            font-size: 1rem;
            font-weight: 700;
            text-decoration: none;
            border-radius: 50px;
            transition: transform 0.3s ease, background-color 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 10px 20px rgba(255, 102, 0, 0.3);
        }

        .back-button:hover {
            background-color: #e65c00;
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(255, 102, 0, 0.5);
        }

        .background-dots {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: radial-gradient(var(--orange-brand) 1px, transparent 1px);
            background-size: 40px 40px;
            opacity: 0.1;
            z-index: -1;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        @media (max-width: 768px) {
            .error-code { font-size: 6rem; }
            .error-message { font-size: 1.2rem; }
        }
    </style>
</head>
<body>
    <div class="background-dots"></div>
    <div class="container">
        <div class="error-code">404</div>
        <h1 class="error-message">Lost in Space</h1>
        <p class="error-description">
           You have to login to access this page
        </p>
        <a href="{{ route('jobcoachDashboard') }}" class="back-button">Go Back Home</a>
    </div>
</body>
</html>
