<?php
require 'partials/header.php';
require 'config.php'; 
?>

<style>
  .header { background-color: var(--rich-black-fogra-29-1) !important; }
  .navbar-link { color: var(--white) !important; }
  .navbar-link:is(:hover, :focus, .active) { color: var(--coquelicot) !important; }

  .blog-grid {
    display: grid;
    gap: 40px;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    margin-top: 60px;
  }
  .blog-card-modern {
    background: var(--white);
    border-radius: var(--radius-10);
    box-shadow: var(--shadow-1);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }
  .blog-card-modern:hover { transform: translateY(-5px); box-shadow: var(--shadow-2); }
  .blog-card-modern img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    display: block;
  }
  .blog-card-content { padding: 24px; }
  .blog-card-meta {
    font-size: var(--fs-6);
    color: var(--coquelicot);
    text-transform: uppercase;
    margin-bottom: 10px;
  }
  .blog-card-title {
    font-size: var(--fs-4);
    font-weight: var(--fw-800);
    color: var(--rich-black-fogra-29-1);
    margin-bottom: 12px;
  }
  .blog-card-text {
    font-size: 1.5rem;
    color: var(--sonic-silver);
    margin-bottom: 18px;
    height: 70px;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .blog-card-link {
    font-weight: var(--fw-700);
    color: var(--coquelicot);
    font-size: var(--fs-6);
    text-transform: uppercase;
    transition: color 0.25s;
    text-decoration: none;
  }
  .blog-card-link:hover { color: var(--rich-black-fogra-29-1); }
</style>

<section class="section blog">
  <div class="container text-center">
    <p class="section-subtitle">From Our Fitness Experts</p>
    <h2 class="h2 section-title">Latest Blog Posts</h2>

    <div class="blog-grid">
      <?php
      $sql = "SELECT * FROM blog_posts ORDER BY posted_on DESC";
      $result = $conn->query($sql);

      if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          $title = htmlspecialchars($row['title']);
          $content = htmlspecialchars($row['content']);
          $image = htmlspecialchars($row['image']);
          $posted_on = date('F j, Y', strtotime($row['posted_on']));
          $post_id = $row['id'];

          echo "
            <div class='blog-card-modern'>
              <img src='assets/images/$image' alt='Blog Image'>
              <div class='blog-card-content'>
                <p class='blog-card-meta'>$posted_on</p>
                <h3 class='blog-card-title'>$title</h3>
                <p class='blog-card-text'>$content</p>
                <a href='blog_post.php?id=$post_id' class='blog-card-link'>Read More</a>
              </div>
            </div>
          ";
        }
      } else {
        echo '<p>No blog posts available.</p>';
      }

      $conn->close();
      ?>
    </div>
  </div>
</section>

<?php require 'partials/footer.php'; ?>
