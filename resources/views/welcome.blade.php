<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Zukses API</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            overflow: hidden;
            background: linear-gradient(135deg, #ffffff 0%, #f0f8f0 50%, #e8f5e8 100%);
        }

        .container {
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .background-pattern {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0.1;
            background-image:
                repeating-linear-gradient(45deg, #4CAF50 0, #4CAF50 1px, transparent 1px, transparent 15px),
                repeating-linear-gradient(-45deg, #4CAF50 0, #4CAF50 1px, transparent 1px, transparent 15px);
            z-index: 1;
        }

        .content {
            z-index: 2;
            text-align: center;
            animation: fadeInUp 1.5s ease-out;
        }

        .logo {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #4CAF50, #8BC34A);
            border-radius: 30px;
            margin: 0 auto 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            font-weight: bold;
            color: white;
            box-shadow: 0 20px 40px rgba(76, 175, 80, 0.3);
            animation: float 3s ease-in-out infinite;
        }

        .welcome-text {
            font-size: 3.5rem;
            font-weight: 300;
            color: #2e7d32;
            margin-bottom: 15px;
            letter-spacing: 2px;
        }

        .subtitle {
            font-size: 1.5rem;
            color: #4CAF50;
            margin-bottom: 40px;
            font-weight: 300;
        }

        .description {
            max-width: 600px;
            margin: 0 auto 40px;
            font-size: 1.1rem;
            line-height: 1.6;
            color: #555;
        }

        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            max-width: 800px;
            margin: 0 auto;
        }

        .feature-card {
            background: white;
            padding: 30px 20px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            border-color: #4CAF50;
            box-shadow: 0 15px 40px rgba(76, 175, 80, 0.2);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #4CAF50, #8BC34A);
            border-radius: 50%;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }

        .feature-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #2e7d32;
            margin-bottom: 10px;
        }

        .feature-description {
            font-size: 0.95rem;
            color: #666;
            line-height: 1.5;
        }

        .footer {
            position: absolute;
            bottom: 30px;
            left: 0;
            right: 0;
            text-align: center;
            color: #4CAF50;
            font-size: 0.9rem;
            z-index: 2;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        @media (max-width: 768px) {
            .welcome-text {
                font-size: 2.5rem;
            }

            .subtitle {
                font-size: 1.2rem;
            }

            .features {
                grid-template-columns: 1fr;
                gap: 20px;
                margin: 0 20px;
            }

            .description {
                margin: 0 20px 30px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="background-pattern"></div>

        <div class="content">
            <div class="logo">Z</div>

            <h1 class="welcome-text">Welcome To API Zukses</h1>
            <p class="subtitle">Powering Your E-Commerce Success</p>

            <div class="description">
                A comprehensive e-commerce marketplace API designed for the Indonesian market.
                Connect users, manage products, process orders, and enable real-time communication
                with our robust backend infrastructure.
            </div>

            <div class="features">
                <div class="feature-card">
                    <div class="feature-icon">👥</div>
                    <div class="feature-title">User Management</div>
                    <div class="feature-description">Multi-role authentication with JWT security</div>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">🛍️</div>
                    <div class="feature-title">Product Catalog</div>
                    <div class="feature-description">Complete inventory and variant management</div>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">💬</div>
                    <div class="feature-title">Real-time Chat</div>
                    <div class="feature-description">Instant messaging with file sharing</div>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">💳</div>
                    <div class="feature-title">Payment Gateway</div>
                    <div class="feature-description">Secure payment processing with Midtrans</div>
                </div>
            </div>
        </div>

        <div class="footer">
            © 2024 Zukses API • E-Commerce Platform Solutions
        </div>
    </div>
</body>
</html>
