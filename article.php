<?php
$servername = "localhost";
$username = "ub8r6xrcxkuen";
$password = "h8hyvfly6nng";
$dbname = "dbefgs9lcup8vg";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

$article_id = isset($_GET['id']) ? (int)$_GET['id'] : 1;
$stmt = $conn->prepare("SELECT * FROM articles WHERE id = ?");
$stmt->execute([$article_id]);
$article = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$article) {
    header("Location: index.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM articles WHERE category = ? AND id != ? LIMIT 3");
$stmt->execute([$article['category'], $article_id]);
$related_articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
$categories = ['World', 'Sports', 'Technology', 'Entertainment'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($article['title']); ?> - CNN Clone</title>
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
            display: flex;
            gap: 20px;
        }
        .main-content {
            flex: 3;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .main-content img {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .main-content h1 {
            font-size: 32px;
            color: #cc0000;
            margin-bottom: 10px;
        }
        .main-content p {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        .sidebar {
            flex: 1;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .sidebar h3 {
            font-size: 20px;
            color: #cc0000;
            margin-bottom: 10px;
        }
        .related-article {
            margin-bottom: 15px;
            cursor: pointer;
        }
        .related-article img {
            width: 100%;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
        }
        .related-article h4 {
            font-size: 16px;
            color: #333;
            margin: 5px 0;
        }
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }
            .navbar h1 {
                font-size: 20px;
            }
            .nav-links a {
                font-size: 14px;
                margin: 0 10px;
            }
            .main-content h1 {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>CNN Clone</h1>
        <div class="nav-links">
            <a onclick="navigate('index.php')">Home</a>
            <?php foreach ($categories as $cat): ?>
                <a onclick="navigate('category.php?cat=<?php echo urlencode($cat); ?>')"><?php echo $cat; ?></a>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="container">
        <div class="main-content">
            <h1><?php echo htmlspecialchars($article['title']); ?></h1>
            <img src="<?php echo htmlspecialchars($article['image']); ?>" alt="<?php echo htmlspecialchars($article['title']); ?>">
            <p><?php echo htmlspecialchars($article['content']); ?></p>
        </div>
        <div class="sidebar">
            <h3>Related Articles</h3>
            <?php foreach ($related_articles as $related): ?>
                <div class="related-article" onclick="navigate('article.php?id=<?php echo $related['id']; ?>')">
                    <img src="<?php echo htmlspecialchars($related['image']); ?>" alt="<?php echo htmlspecialchars($related['title']); ?>">
                    <h4><?php echo htmlspecialchars($related['title']); ?></h4>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script>
        function navigate(url) {
            window.location.href = url;
        }
    </script>
</body>
</html>
