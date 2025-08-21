<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $errors = [];

    if (empty($username) || empty($email) || empty($password)) {
        $errors[] = "All fields are required.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->fetch()) {
            $errors[] = "Username or email already exists.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            if ($stmt->execute([$username, $email, $hashed_password])) {
                $_SESSION['user_id'] = $conn->lastInsertId();
                $_SESSION['username'] = $username;
                header("Location: index.php");
                exit;
            } else {
                $errors[] = "Registration failed. Try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - CNN Clone</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }
        body {
            background: #f5f5f5;
            color: #333;
        }
        .navbar {
            background: #cc0000;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }
        .navbar h1 {
            font-size: 24px;
            font-weight: bold;
        }
        .nav-links a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-size: 16px;
            cursor: pointer;
            transition: color 0.3s;
        }
        .nav-links a:hover {
            color: #ffd700;
        }
        .container {
            max-width: 600px;
            margin: 100px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        h2 {
            font-size: 28px;
            color: #cc0000;
            margin-bottom: 20px;
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            font-size: 16px;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            outline: none;
            transition: border-color 0.3s;
        }
        .form-group input:focus {
            border-color: #cc0000;
        }
        .error {
            color: #cc0000;
            font-size: 14px;
            margin-bottom: 10px;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #cc0000;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }
        button:hover {
            background: #b30000;
        }
        .link {
            text-align: center;
            margin-top: 10px;
        }
        .link a {
            color: #cc0000;
            text-decoration: none;
            transition: color 0.3s;
        }
        .link a:hover {
            color: #ffd700;
        }
        @media (max-width: 768px) {
            .navbar h1 {
                font-size: 20px;
            }
            .nav-links a {
                font-size: 14px;
                margin: 0 10px;
            }
            .container {
                margin: 80px 20px;
                padding: 15px;
            }
            h2 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>CNN Clone</h1>
        <div class="nav-links">
            <a onclick="navigate('index.php')">Home</a>
            <a onclick="navigate('category.php?cat=World')">World</a>
            <a onclick="navigate('category.php?cat=Sports')">Sports</a>
            <a onclick="navigate('category.php?cat=Technology')">Technology</a>
            <a onclick="navigate('category.php?cat=Entertainment')">Entertainment</a>
            <a onclick="navigate('login.php')">Login</a>
        </div>
    </div>
    <div class="container">
        <h2>Sign Up</h2>
        <?php if (!empty($errors)): ?>
            <?php foreach ($errors as $error): ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password">
            </div>
            <button type="submit">Sign Up</button>
        </form>
        <p class="link">Already have an account? <a onclick="navigate('login.php')">Login</a></p>
    </div>
    <script>
        function navigate(url) {
            window.location.href = url;
        }
    </script>
</body>
</html>
