<?php
session_start();
require_once 'db.php';

// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$categories = ['World', 'Sports', 'Technology', 'Entertainment'];
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $summary = trim($_POST['summary']);
    $content = trim($_POST['content']);
    $category = trim($_POST['category']);
    $image = trim($_POST['image']);

    // Validation
    if (empty($title) || empty($summary) || empty($content) || empty($category) || empty($image)) {
        $errors[] = "All fields are required.";
    }
    if (strlen($title) > 255) {
        $errors[] = "Title must be 255 characters or less.";
    }
    if (!in_array($category, $categories)) {
        $errors[] = "Invalid category selected.";
    }
    if (!filter_var($image, FILTER_VALIDATE_URL)) {
        $errors[] = "Invalid image URL.";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO articles (title, summary, content, category, image) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$title, $summary, $content, $category, $image])) {
            $success = "Article added successfully!";
        } else {
            $errors[] = "Failed to add article. Try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Article - CNN Clone</title>
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
            max-width: 800px;
            margin: 100px 20px;
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
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            outline: none;
            transition: border-color 0.3s;
        }
        .form-group textarea {
            min-height: 150px;
            resize: vertical;
        }
        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            border-color: #cc0000;
        }
        .error {
            color: #cc0000;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .success {
            color: #008000;
            font-size: 14px;
            margin-bottom: 10px;
            text-align: center;
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
            <a onclick="navigate('add_article.php')">Add Article</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a onclick="navigate('logout.php')">Logout</a>
            <?php else: ?>
                <a onclick="navigate('login.php')">Login</a>
                <a onclick="navigate('signup.php')">Sign Up</a>
            <?php endif; ?>
        </div>
    </div>
    <div class="container">
        <h2>Add New Article</h2>
        <?php if (!empty($success)): ?>
            <p class="success"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>
        <?php if (!empty($errors)): ?>
            <?php foreach ($errors as $error): ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" value="<?php echo isset($title) ? htmlspecialchars($title) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="summary">Summary</label>
                <textarea id="summary" name="summary"><?php echo isset($summary) ? htmlspecialchars($summary) : ''; ?></textarea>
            </div>
            <div class="form-group">
                <label for="content">Content</label>
                <textarea id="content" name="content"><?php echo isset($content) ? htmlspecialchars($content) : ''; ?></textarea>
            </div>
            <div class="form-group">
                <label for="category">Category</label>
                <select id="category" name="category">
                    <option value="">Select a category</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat; ?>" <?php echo isset($category) && $category === $cat ? 'selected' : ''; ?>><?php echo $cat; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="image">Image URL</label>
                <input type="url" id="image" name="image" value="<?php echo isset($image) ? htmlspecialchars($image) : ''; ?>" placeholder="e.g., https://via.placeholder.com/300x200">
            </div>
            <button type="submit">Add Article</button>
        </form>
    </div>
    <script>
        function navigate(url) {
            window.location.href = url;
        }
    </script>
</body>
</html>
