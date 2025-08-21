<?php
session_start();
require_once 'db.php';

$categories = ['World', 'Sports', 'Technology', 'Entertainment'];
$featured = $conn->query("SELECT * FROM articles ORDER BY created_at DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CNN Clone - Homepage</title>
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
            max-width: 1200px;
            margin: 80px auto 20px;
            padding: 0 20px;
        }
        .featured {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .featured img {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
            border-radius: 8px;
        }
        .featured h2 {
            font-size: 28px;
            margin: 10px 0;
            color: #cc0000;
        }
        .featured p {
            font-size: 16px;
            line-height: 1.6;
        }
        .ticker {
            background: #333;
            color: white;
            padding: 10px;
            overflow: hidden;
            white-space: nowrap;
            margin-bottom: 20px;
        }
        .ticker span {
            display: inline-block;
            animation: ticker 20s linear infinite;
        }
        @keyframes ticker {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }
        .categories {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }
        .category-card {
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: transform 0.3s;
        }
        .category-card:hover {
            transform: translateY(-5px);
        }
        .category-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }
        .category-card h3 {
            font-size: 20px;
            padding: 10px;
            color: #cc0000;
        }
        .category-card p {
            padding: 0 10px 10px;
            font-size: 14px;
            color: #666;
        }
        @media (max-width: 768px) {
            .navbar h1 {
                font-size: 20px;
            }
            .nav-links a {
                font-size: 14px;
                margin: 0 10px;
            }
            .featured h2 {
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
            <?php foreach ($categories as $category): ?>
                <a onclick="navigate('category.php?cat=<?php echo urlencode($category); ?>')"><?php echo $category; ?></a>
            <?php endforeach; ?>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a onclick="navigate('add_article.php')">Add Article</a>
                <a onclick="navigate('logout.php')">Logout</a>
            <?php else: ?>
                <a onclick="navigate('login.php')">Login</a>
                <a onclick="navigate('signup.php')">Sign Up</a>
            <?php endif; ?>
        </div>
    </div>
    <div class="container">
        <div class="ticker">
            <span>Breaking News: Global Summit Addresses Climate Crisis | Team USA Wins Olympic Gold | New AI Innovations Unveiled</span>
        </div>
        <div class="featured">
            <h2><?php echo htmlspecialchars($featured['title']); ?></h2>
            <img src="<?php echo htmlspecialchars($featured['image']); ?>" alt="Featured News">
            <p><?php echo htmlspecialchars($featured['summary']); ?></p>
            <a onclick="navigate('article.php?id=<?php echo $featured['id']; ?>')" style="color: #cc0000; cursor: pointer;">Read More</a>
        </div>
        <div class="categories">
            <?php
            foreach ($categories as $category) {
                $stmt = $conn->prepare("SELECT * FROM articles WHERE category = ? LIMIT 1");
                $stmt->execute([$category]);
                $article = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($article):
            ?>
                <div class="category-card" onclick="navigate('category.php?cat=<?php echo urlencode($category); ?>')">
                    <img src="<?php echo htmlspecialchars($article['image']); ?>" alt="<?php echo htmlspecialchars($category); ?>">
                    <h3><?php echo htmlspecialchars($category); ?></h3>
                    <p><?php echo htmlspecialchars($article['summary']); ?></p>
                </div>
            <?php endif; }
            ?>
        </div>
    </div>
    <script>
        function navigate(url) {
            window.location.href = url;
        }
    </script>
</body>
</html>
