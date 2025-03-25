<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register - Leo's Perfume</title>
    <link rel="stylesheet" href="styles.css" />
    <style>
        :root {
            --primary-color: #8e1c1c;
            --secondary-color: #6b0f0f;
            --text-color: #fff;
            --background-color: #f8e0e0;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            background-color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            display: flex;
            width: 900px;
            background-color: #fff;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            border-radius: 16px;
            overflow: hidden;
        }

        .left-section {
            background-color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 1;
            padding: 20px;
        }

        .left-section img {
            width: 300px;
            height: auto;
        }

        .right-section {
            background-color: var(--background-color);
            flex: 1.5;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 40px;
            border-radius: 16px 0 0 16px;
            text-align: center;
        }

        h1 {
            color: var(--primary-color);
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        .input-group {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        .input-group input[type="text"] {
            width: calc(50% - 12px);
            padding: 12px;
            margin-bottom: 16px;
            border: 2px solid var(--primary-color);
            border-radius: 8px;
            font-size: 1rem;
        }

        input[type="email"], input[type="password"] {
            width: calc(100% - 24px);
            padding: 12px;
            margin-bottom: 16px;
            border: 2px solid var(--primary-color);
            border-radius: 8px;
            font-size: 1rem;
        }

        .error {
            background-color: rgba(255, 0, 0, 0.1);
            color: red;
            font-size: 0.875rem;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 8px;
            border: 1px solid red;
        }

        button {
            background-color: var(--primary-color);
            color: var(--text-color);
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            margin-top: 20px;
        }

        button:hover {
            background-color: var(--secondary-color);
        }

        a {
            color: var(--secondary-color);
            font-size: 0.9rem;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left-section">
            <img src="images/logo.png" alt="Leo's Perfume Bottle" />
        </div>
        <div class="right-section">
            <h1>Register</h1>
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="input-group">
                    <input type="text" name="firstname" value="{{ old('firstname') }}" placeholder="First Name" required>
                    <input type="text" name="lastname" value="{{ old('lastname') }}" placeholder="Last Name" required>
                </div>
                @error('firstname')
                    <div class="error">{{ $message }}</div>
                @enderror
                @error('lastname')
                    <div class="error">{{ $message }}</div>
                @enderror

                <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" required>
                @error('email')
                    <div class="error">{{ $message }}</div>
                @enderror

                <input type="password" name="password" placeholder="Password" required>
                @error('password')
                    <div class="error">{{ $message }}</div>
                @enderror

                <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
                @error('password_confirmation')
                    <div class="error">{{ $message }}</div>
                @enderror

                <button type="submit">Register</button>
                <a href="{{ route('login') }}">Already have an account? Login</a>
            </form>
        </div>
    </div>
</body>
</html>
