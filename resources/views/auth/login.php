<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Multilines Solutions Private Limited</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= asset('favicon.png') ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-container {
            width: 100%;
            max-width: 440px;
            padding: 20px;
        }

        .auth-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 40px 36px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2), 0 10px 10px -5px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .auth-logo {
            height: 52px;
            object-fit: contain;
            margin-bottom: 20px;
        }

        .auth-title {
            font-size: 1.35rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 6px;
        }

        .auth-subtitle {
            font-size: 0.85rem;
            color: #64748b;
            margin-bottom: 24px;
        }

        .alert-error {
            background: #fee2e2;
            color: #dc2626;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 0.85rem;
            margin-bottom: 20px;
            text-align: left;
            border: 1px solid #fecaca;
        }

        .alert-success {
            background: #dcfce7;
            color: #16a34a;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 0.85rem;
            margin-bottom: 20px;
            text-align: left;
            border: 1px solid #bbf7d0;
        }

        .form-group {
            text-align: left;
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-wrapper i {
            position: absolute;
            left: 14px;
            color: #94a3b8;
            font-size: 0.9rem;
        }

        .auth-input {
            width: 100%;
            padding: 12px 14px 12px 42px;
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 0.9rem;
            color: #1e293b;
            transition: all 0.2s ease;
        }

        .auth-input:focus {
            background: #ffffff;
            border-color: #0066ff;
            box-shadow: 0 0 0 3px rgba(0, 102, 255, 0.12);
            outline: none;
        }

        .auth-btn {
            width: 100%;
            background: #0066ff;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.92rem;
            cursor: pointer;
            transition: background 0.2s ease, transform 0.1s ease;
            box-shadow: 0 4px 12px rgba(0, 102, 255, 0.25);
            margin-top: 10px;
        }

        .auth-btn:hover {
            background: #004ecc;
        }

        .auth-footer {
            margin-top: 24px;
            font-size: 0.85rem;
            color: #64748b;
        }

        .auth-footer a {
            color: #0066ff;
            text-decoration: none;
            font-weight: 600;
        }

        .auth-footer a:hover {
            text-decoration: underline;
        }

        .company-info {
            margin-top: 24px;
            text-align: center;
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.6);
            line-height: 1.4;
        }
    </style>
</head>
<body>

<div class="auth-container">
    <div class="auth-card">
        <img src="<?= asset('images/logo.png') ?>" alt="Multilines Solutions Logo" class="auth-logo">
        <div class="auth-title">Welcome Back</div>
        <div class="auth-subtitle">Sign in to your ISP Billing & Management Dashboard</div>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert-error">
                <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['success'])): ?>
            <div class="alert-success">
                <i class="fas fa-check-circle"></i> <?= htmlspecialchars($_SESSION['success']) ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <!-- Pointing action to 'auth/authenticate' where the controller processes login POST data -->
        <form method="POST" action="<?= url('auth/authenticate') ?>">
            <div class="form-group">
                <label class="form-label">Username or Email</label>
                <div class="input-wrapper">
                    <i class="fas fa-user"></i>
                    <input type="text" name="username" class="auth-input" placeholder="Enter username or email" required autocomplete="username">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" class="auth-input" placeholder="••••••••••••" required autocomplete="current-password">
                </div>
            </div>

            <button type="submit" class="auth-btn">Sign In</button>
        </form>

        <div class="auth-footer">
            Don't have an account? <a href="<?= url('auth/register') ?>">Register</a>
        </div>
    </div>

    <div class="company-info">
        <strong>Multilines Solutions Private Limited</strong>[cite: 1]<br>
        C-7, Huma Town, Jinnah Avenue, Karachi[cite: 1]
    </div>
</div>

</body>
</html>