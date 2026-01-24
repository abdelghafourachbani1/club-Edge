<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ClubEdge</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Space Grotesk', sans-serif;
            background: #0A0A0A;
            color: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        
        .login-container {
            width: 100%;
            max-width: 450px;
            padding: 3rem;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            backdrop-filter: blur(20px);
        }
        
        .logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .logo h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #FF6B35;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #FFF;
        }
        
        .form-input {
            width: 100%;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            color: #FFF;
            font-size: 1rem;
            transition: all 0.3s;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #FF6B35;
            background: rgba(255, 255, 255, 0.08);
        }
        
        .btn-login {
            width: 100%;
            padding: 1.2rem;
            background: linear-gradient(135deg, #FF6B35, #E55A2B);
            border: none;
            border-radius: 10px;
            color: #FFF;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 1rem;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(255, 107, 53, 0.4);
        }
        
        .alert {
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
        }
        
        .alert-error {
            background: rgba(255, 107, 53, 0.1);
            border: 1px solid rgba(255, 107, 53, 0.3);
            color: #FF6B35;
        }
        
        .test-accounts {
            margin-top: 2rem;
            padding: 1.5rem;
            background: rgba(255, 255, 255, 0.02);
            border-radius: 10px;
            font-size: 0.9rem;
        }
        
        .test-accounts h3 {
            color: #FF6B35;
            margin-bottom: 1rem;
        }
        
        .test-accounts code {
            background: rgba(255, 255, 255, 0.05);
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            color: #FF6B35;
        }
        
        .back-link {
            text-align: center;
            margin-top: 1.5rem;
        }
        
        .back-link a {
            color: #888;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .back-link a:hover {
            color: #FF6B35;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <h1>🎓 ClubEdge</h1>
        </div>

        {% if session.error is defined %}
            <div class="alert alert-error">
                {{ session.error }}
            </div>
        {% endif %}

        <form action="/club-Edge/login" method="POST">
            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    class="form-input" 
                    required
                    placeholder="your.email@example.com"
                >
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="form-input" 
                    required
                    placeholder="••••••••"
                >
            </div>

            <button type="submit" class="btn-login">
                Login →
            </button>
        </form>

        <div class="test-accounts">
            <h3>🔐 Test Accounts</h3>
            <p><strong>Student:</strong> <code>jane@test.com</code> / <code>password</code></p>
            <p><strong>President:</strong> <code>john@test.com</code> / <code>password</code></p>
        </div>

        <div class="back-link">
            <a href="/club-Edge/events">← Back to Events</a>
        </div>
    </div>
</body>
</html>