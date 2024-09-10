<?php include('db.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Artikel</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <h1>Selamat Datang di GhaniHub</h1>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <?php
            $category_query = "SELECT * FROM categories";
            $stmt = $connection->prepare($category_query);
            $stmt->execute();
            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($categories as $category) {
                echo "<li><a href='index.php?category_id=" . $category['ID'] . "'>" . htmlspecialchars($category['Nama']) . "</a></li>";
            }
            ?>
        </ul>
    </nav>
</header>

<div class="search-bar">
    <form method="GET" action="index.php">
        <input type="text" name="search" placeholder="Search...">
        <button type="submit">Search</button>
    </form>
</div>

<main>
    <?php
    // Pagination configuration
    $limit = 5; // Number of articles per page
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $limit;

    if (isset($_GET['category_id'])) {
        $category_id = $_GET['category_id'];
        $article_query = "SELECT articles.* FROM articles 
                          JOIN article_category ON articles.ID = article_category.article_id 
                          WHERE article_category.category_id = :category_id
                          LIMIT :limit OFFSET :offset";
        $stmt = $connection->prepare($article_query);
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    } elseif (isset($_GET['search'])) {
        $search = "%" . $_GET['search'] . "%";
        $article_query = "SELECT * FROM articles WHERE Title LIKE :search LIMIT :limit OFFSET :offset";
        $stmt = $connection->prepare($article_query);
        $stmt->bindParam(':search', $search, PDO::PARAM_STR);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    } else {
        $article_query = "SELECT * FROM articles LIMIT :limit OFFSET :offset";
        $stmt = $connection->prepare($article_query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    }

    $stmt->execute();
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($articles) > 0) {
        $index = 0;
        foreach ($articles as $article) {
            echo "<div class='article' style='--index: {$index};'>";
            echo "<h2><a href='detail.php?id=" . $article['ID'] . "'>" . htmlspecialchars($article['Title']) . "</a></h2>";
            echo "<p>" . htmlspecialchars(substr($article['Content'], 0, 100)) . "...</p>";
            echo "</div>";
            $index++;
        }
    } else {
        echo "<p>Tidak ada artikel ditemukan.</p>";
    }

    // Pagination links
    $total_query = "SELECT COUNT(*) FROM articles";
    $stmt = $connection->query($total_query);
    $total_articles = $stmt->fetchColumn();
    $total_pages = ceil($total_articles / $limit);

    echo '<div class="pagination">';
    for ($i = 1; $i <= $total_pages; $i++) {
        echo "<a href='index.php?page=$i'" . ($i === $page ? ' class="active"' : '') . ">$i</a> ";
    }
    echo '</div>';
    ?>
</main>
</body>
</html>
