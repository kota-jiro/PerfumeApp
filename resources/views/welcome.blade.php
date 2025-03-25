<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Welcome to Leo's Perfume</title>
    <link rel="stylesheet" href="styles.css" />
    <style>
        :root {
            --primary-color: #8e1c1c;
            --secondary-color: #6b0f0f;
            --text-color: #fff;
            --background-gradient: linear-gradient(to bottom, #fff5f5, #f8e0e0);
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            background: var(--background-gradient);
            color: var(--secondary-color);
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            max-width: 500px;
        }

        header img.logo {
            width: 100px;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 2.8rem;
            color: var(--primary-color);
        }

        p {
            font-size: 1.2rem;
            margin-bottom: 30px;
        }

        .hero button {
            background-color: var(--primary-color);
            color: var(--text-color);
            border: none;
            padding: 15px 35px;
            margin: 10px;
            font-size: 1rem;
            cursor: pointer;
            border-radius: 30px;
            transition: background-color 0.3s ease;
        }

        .hero button:hover {
            background-color: var(--secondary-color);
        }

        footer {
            margin-top: 30px;
            font-size: 0.9rem;
            color: var(--secondary-color);
        }

        .top-nav {
            position: absolute;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
        }

        .top-nav a {
            background-color: var(--primary-color);
            color: var(--text-color);
            padding: 10px 20px;
            border-radius: 30px;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .top-nav a:hover {
            background-color: var(--secondary-color);
        }
    </style>
</head>

<body>
    <div class="top-nav">
        @if (Route::has('login'))
            @auth
                <a href="{{ url('/dashboard') }}">Dashboard</a>
            @else
                <a href="{{ route('login') }}">Log in</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}">Register</a>
                @endif
            @endauth
        @endif
    </div>

    <div class="container">
        <header>
            <img src="images/logo.png" alt="Leo's Perfume Logo" class="logo"/>
            <h1>Welcome to Leo's Perfume</h1>
            <p>Discover the Essence of Elegance</p>
        </header>

        <section class="hero">
            <button onclick="window.location.href='login'">Shop Now</button>
            <button onclick="window.location.href='login'">Learn More</button>
        </section>

        <footer>
            <p>&copy; 2025 Leo's Perfume. All rights reserved.</p>
        </footer>
    </div>
</body>

</html>
