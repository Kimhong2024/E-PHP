<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            width: 400px;
        }

        .tabs {
            display: flex;
            margin-bottom: 2rem;
        }

        .tab {
            flex: 1;
            padding: 1rem;
            text-align: center;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .tab.active {
            border-color: #2196F3;
            color: #2196F3;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: 500;
        }

        input {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        input:focus {
            outline: none;
            border-color: #2196F3;
            box-shadow: 0 0 0 2px rgba(33,150,243,0.1);
        }

        .btn {
            width: 100%;
            padding: 1rem;
            background: #2196F3;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .btn:hover {
            background: #1976D2;
        }

        .form {
            display: none;
        }

        .form.active {
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="tabs">
            <div class="tab active" onclick="switchForm('login')">Login</div>
            <div class="tab" onclick="switchForm('register')">Register</div>
        </div>

        <!-- Login Form -->
<form id="loginForm" class="form active" action="login_process.php" method="POST">
    <div class="form-group">
        <label for="loginUsername">Username</label>
        <input type="text" id="loginUsername" name="username" required>
    </div>
    <div class="form-group">
        <label for="loginPassword">Password</label>
        <input type="password" id="loginPassword" name="password" required>
    </div>
    <button type="submit" class="btn">Login</button>
</form>

<!-- Register Form -->
<form id="registerForm" class="form" action="register_process.php" method="POST">
    <div class="form-group">
        <label for="regUsername">Username</label>
        <input type="text" id="regUsername" name="username" required>
    </div>
    <div class="form-group">
        <label for="regEmail">Email</label>
        <input type="email" id="regEmail" name="email" required>
    </div>
    <div class="form-group">
        <label for="regPassword">Password</label>
        <input type="password" id="regPassword" name="password" required>
    </div>
    <div class="form-group">
        <label for="regConfirmPassword">Confirm Password</label>
        <input type="password" id="regConfirmPassword" name="confirm_password" required>
    </div>
    <button type="submit" class="btn">Register</button>
</form>
    </div>

    <script>
        function switchForm(formType) {
            // Switch tabs
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });
            document.querySelector(`.tab[onclick="switchForm('${formType}')"]`).classList.add('active');

            // Switch forms
            document.querySelectorAll('.form').forEach(form => {
                form.classList.remove('active');
            });
            document.getElementById(`${formType}Form`).classList.add('active');
        }
    </script>
</body>
</html>