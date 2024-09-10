<?php 
include('db.php'); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Artikel</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <h1>Info Lengkapnya</h1>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
        </ul>
    </nav>
</header>

<main>
    <?php
    if (isset($_GET['id'])) {
        $article_id = intval($_GET['id']);
        $article_query = "SELECT * FROM articles WHERE ID = :article_id";
        $stmt = $connection->prepare($article_query);
        $stmt->bindParam(':article_id', $article_id, PDO::PARAM_INT);
        $stmt->execute();
        $article = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($article) {
            echo "<div class='article-container'>";
            echo "<div class='article-content-container'>";
            echo "<h2>" . htmlspecialchars($article['Title']) . "</h2>";
            echo "<div class='article-content'>";
            echo nl2br(htmlspecialchars($article['Content']));
            echo "</div>";
            echo "</div>";

            $category_query = "SELECT categories.Nama FROM categories 
                               JOIN article_category ON categories.ID = article_category.category_id 
                               WHERE article_category.article_id = :article_id";
            $stmt = $connection->prepare($category_query);
            $stmt->bindParam(':article_id', $article_id, PDO::PARAM_INT);
            $stmt->execute();
            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($categories) {
                echo "<div class='article-category'><strong>Kategori:</strong> ";
                foreach ($categories as $category) {
                    echo htmlspecialchars($category['Nama']) . " ";
                }
                echo "</div>";
            } else {
                echo "<div class='article-category'><strong>Kategori:</strong> Tidak ada kategori</div>";
            }

            echo "<a href='index.php' class='back-button'>Back to List</a>";
            echo "</div>"; // Close article-container
        } else {
            echo "<p>Artikel tidak ditemukan.</p>";
        }
    } else {
        echo "<p>ID artikel tidak diberikan.</p>";
    }
    ?>
</main>
</body>
</html>
