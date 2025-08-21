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

$category = isset($_GET['cat']) ? $_GET['cat'] : 'World';
$stmt = $conn->prepare("SELECT * FROM articles WHERE category = ?");
$stmt->execute([$category]);
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
$categories = ['World', 'Sports', 'Technology', 'Entertainment'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CNN Clone - <?php echo htmlspecialchars($category); ?></title>
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
        .category-title {
            font-size: 32px;
            color: #cc0000;
            margin-bottom: 20px;
        }
        .articles {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }
        .article-card {
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,01);
            cursor: pointer;
            transition: transform 0.3s;
        }
        .article-card:hover {
            transform: translateY(-5px);
        }
        .article-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }
        .article-card h3 {
            font-size: 20px;
            padding: 10px;
            color: #cc0000;
        }
        .article-card p {
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
            .category-title {
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
        <h2 class="category-title"><?php echo htmlspecialchars($category); ?> News</h2>
        <div class="articles">
            <?php foreach ($articles as $article): ?>
                <div class="article-card" onclick="navigate('article.php?id=<?php echo $article['id']; ?>')">
                    <img src="<?php echo htmlspecialchars($article['image']); ?>" alt="<?php echo htmlspecialchars($article['title']); ?>">
                    <h3><?php echo htmlspecialchars($article['title']); ?></h3>
                    <p><?php echo htmlspecialchars($article['summary']); ?></p>
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
