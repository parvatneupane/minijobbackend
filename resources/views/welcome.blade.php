<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MiniJob Freelancer</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            padding: 20px;
        }

        .container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 50px 60px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            text-align: center;
            max-width: 500px;
            width: 100%;
            transition: transform 0.3s ease;
        }

        .container:hover {
            transform: translateY(-5px);
        }

        h1 {
            color: #2d3748;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            letter-spacing: -0.5px;
        }

        .subtitle {
            color: #718096;
            font-size: 1.1rem;
            margin-bottom: 35px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 20px;
        }

        .logo-icon {
            font-size: 4rem;
            margin-bottom: 15px;
            display: block;
        }

        .button-group {
            display: flex;
            flex-direction: column;
            gap: 12px;
            width: 100%;
        }

        .btn {
            display: inline-block;
            padding: 14px 30px;
            font-size: 1rem;
            font-weight: 600;
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            text-align: center;
            letter-spacing: 0.3px;
            width: 100%;
        }

        .btn-primary {
            background: #667eea;
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-primary:hover {
            background: #5a67d8;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
        }

        .btn-secondary {
            background: #48bb78;
            color: white;
            box-shadow: 0 4px 15px rgba(72, 187, 120, 0.4);
        }

        .btn-secondary:hover {
            background: #38a169;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(72, 187, 120, 0.5);
        }

        .btn-dashboard {
            background: #4a5568;
            color: white;
            box-shadow: 0 4px 15px rgba(74, 85, 104, 0.3);
        }

        .btn-dashboard:hover {
            background: #2d3748;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(74, 85, 104, 0.4);
        }

        .btn-admin {
            background: #e53e3e;
            color: white;
            box-shadow: 0 4px 15px rgba(229, 62, 62, 0.4);
        }

        .btn-admin:hover {
            background: #c53030;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(229, 62, 62, 0.5);
        }

        .user-info {
            background: #f7fafc;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .user-name {
            color: #2d3748;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .user-role {
            background: #667eea;
            color: white;
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .user-role.admin {
            background: #e53e3e;
        }

        .btn-logout {
            background: #fc8181;
            color: white;
            margin-top: 10px;
            box-shadow: 0 4px 15px rgba(252, 129, 129, 0.3);
        }

        .btn-logout:hover {
            background: #f56565;
            transform: translateY(-2px);
        }

        @media (max-width: 480px) {
            .container {
                padding: 30px 25px;
            }

            h1 {
                font-size: 2rem;
            }

            .btn {
                padding: 12px 20px;
                font-size: 0.95rem;
            }
        }
    </style>
</head>
<body>
 <div class="container">
    <span class="logo-icon">💼</span>
    <h1>MiniJob</h1>
    <p class="subtitle">Find &amp; Hire Freelancers</p>

    @guest
        <div class="button-group">
            <a href="{{ route('login') }}" class="btn btn-primary">
                🔑 Login
            </a>
        </div>
    @endguest
</div>
</body>
</html>