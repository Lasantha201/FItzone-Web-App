<?php
require 'config.php';

if (!isset($_GET['id'])) {
    echo "<p style='text-align:center; margin-top:50px;'>No post specified.</p>";
    exit;
}

$id = (int)$_GET['id'];

// Fetch post by ID using prepared statement
$stmt = $conn->prepare("SELECT * FROM blog_posts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$post = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$post) {
    echo "<p style='text-align:center; margin-top:50px;'>Post not found.</p>";
    exit;
}

// Sanitize values
$title = htmlspecialchars($post['title']);
$content = nl2br(htmlspecialchars($post['content']));
$image = htmlspecialchars($post['image']);
$posted_on = date('F j, Y', strtotime($post['posted_on']));
$author = htmlspecialchars($post['author']);

// Determine image path
$imagePath = '';
if (!empty($image)) {
    // If the stored image already includes "uploads/" or "assets/", use as is
    if (strpos($image, 'uploads/') === 0 || strpos($image, 'assets/') === 0) {
        $imagePath = $image;
    } else {
        // Otherwise, assume image is in uploads folder
        $imagePath = 'uploads/' . $image;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title><?= $title ?></title>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap');

  body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen,
      Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
    background: #fafafa;
    margin: 40px auto;
    max-width: 720px;
    padding: 0 20px;
    color: #333;
  }

  .back-button {
    display: inline-block;
    padding: 12px 24px;
    margin-bottom: 40px;
    background: linear-gradient(135deg, #ff4c29 0%, #ff784e 100%);
    color: #fff;
    text-decoration: none;
    font-weight: 700;
    border-radius: 30px;
    box-shadow: 0 6px 12px rgba(255, 76, 41, 0.35);
    transition: background 0.3s ease, box-shadow 0.3s ease;
  }
  .back-button:hover {
    background: linear-gradient(135deg, #ff784e 0%, #ff4c29 100%);
    box-shadow: 0 8px 20px rgba(255, 120, 78, 0.5);
  }

  h1 {
    font-weight: 700;
    font-size: 2.8rem;
    line-height: 1.1;
    margin-bottom: 8px;
    color: #ff4c29;
  }

  .post-meta {
    font-size: 0.95rem;
    font-weight: 600;
    color: #888;
    margin-bottom: 32px;
    letter-spacing: 0.05em;
    text-transform: uppercase;
  }

  img.post-image {
    width: 100%;
    height: auto;
    border-radius: 15px;
    margin-bottom: 32px;
    box-shadow:
      0 4px 8px rgba(0,0,0,0.1),
      0 10px 20px rgba(255, 76, 41, 0.15);
    transition: transform 0.3s ease;
  }
  img.post-image:hover {
    transform: scale(1.03);
  }

  .post-content {
    font-size: 1.15rem;
    line-height: 1.75;
    color: #444;
    white-space: pre-wrap;
    user-select: text;
  }

  /* Responsive typography */
  @media (max-width: 480px) {
    h1 {
      font-size: 2rem;
    }
    .post-content {
      font-size: 1rem;
    }
  }
</style>
</head>
<body>

<a href="blog.php" class="back-button">&#8592; Back to Blog</a>

<article>
  <h1><?= $title ?></h1>
  <div class="post-meta">By <?= $author ?> &mdash; <?= $posted_on ?></div>

  <?php if ($imagePath): ?>
    <img src="<?= $imagePath ?>" alt="<?= $title ?>" class="post-image" loading="lazy" />
  <?php endif; ?>

  <div class="post-content"><?= $content ?></div>
</article>

</body>
</html>
