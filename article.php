<?php
include 'db.php';

$article_id = $_GET['id'];

// Ambil detail artikel berdasarkan ID
$article_result = $conn->query("SELECT articles.Title, articles.Content, GROUP_CONCAT(categories.Nama SEPARATOR ', ') as Categories 
                                FROM articles 
                                JOIN article_category ON articles.ID = article_category.article_id 
                                JOIN categories ON categories.ID = article_category.category_id 
                                WHERE articles.ID = $article_id 
                                GROUP BY articles.ID");

if ($article_result->num_rows > 0) {
    $article = $article_result->fetch_assoc();
} else {
    echo "Article not found";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $article['Title']; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="navbar">
        <div class="logo">Web Articles</div>
        <div class="categories">
            <ul>
                <li><a href="index.php">Home</a></li>
            </ul>
        </div>
    </div>

    <div class="article-detail">
    <h1><?php echo $article['Title']; ?></h1>
    <p class="article-content"><?php echo nl2br($article['Content']); ?></p> <!-- Ini yang diperbarui -->
    <p><strong>Categories:</strong> <?php echo $article['Categories']; ?></p>
    <a href="index.php" class="back-button">Back to List</a>
</div>
</body>
</html>

    <div class="article-detail">
        <h1><?php echo $article['Title']; ?></h1>
        <p><?php echo $article['Content']; ?></p>
        <p><strong>Categories:</strong> <?php echo $article['Categories']; ?></p>
        <a href="index.php" class="back-button">Back to List</a>
    </div>
</body>
</html>
