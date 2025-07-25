<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - RWHOIS Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background: #f4f6fa;
            min-height: 100vh;
        }
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            padding: 2.5rem 2rem;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.05);
            background: #fff;
        }
        .login-title {
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .login-logo {
            display: block;
            margin: 0 auto 1rem auto;
            width: 60px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card col-md-5 col-lg-4">
            <img src="https://img.icons8.com/ios-filled/100/2c3e50/server.png" alt="Logo" class="login-logo">
            <h2 class="login-title">RWHOIS Dashboard Login</h2>
            <form method="post" action="/login">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" class="form-control" required autofocus placeholder="Enter your username">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" class="form-control" required placeholder="Enter your password">
                </div>
                <?php if (!empty($error)) echo "<div class='alert alert-danger mt-2'>".htmlspecialchars($error)."</div>"; ?>
                <button type="submit" class="btn btn-primary btn-block">Login</button>
            </form>
        </div>
    </div>
</body>
</html> 