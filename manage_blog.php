<?php
require 'config.php';

$error = '';
$success = '';

// Handle Create and Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $image = $_FILES['image'] ?? null;

    if (!$title || !$content || !$author) {
        $error = 'Please fill in all fields.';
    } else {
        $image_filename = '';
        if ($image && $image['error'] !== 4) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $ext = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed)) {
                $error = 'Invalid image file type.';
            } else {
                $image_filename = uniqid() . '.' . $ext;
                $target = 'assets/images/' . $image_filename;
                if (!move_uploaded_file($image['tmp_name'], $target)) {
                    $error = 'Failed to upload image.';
                }
            }
        }

        if (!$error) {
            if ($id) {
                if ($image_filename) {
                    $stmt = $conn->prepare("UPDATE blog_posts SET title=?, content=?, image=?, author=? WHERE id=?");
                    $stmt->bind_param("ssssi", $title, $content, $image_filename, $author, $id);
                } else {
                    $stmt = $conn->prepare("UPDATE blog_posts SET title=?, content=?, author=? WHERE id=?");
                    $stmt->bind_param("sssi", $title, $content, $author, $id);
                }
                if ($stmt->execute()) {
                    $success = 'Blog updated successfully.';
                } else {
                    $error = 'Update failed: ' . $conn->error;
                }
                $stmt->close();
            } else {
                if (!$image_filename) {
                    $error = 'Image is required for new post.';
                } else {
                    $stmt = $conn->prepare("INSERT INTO blog_posts (title, content, image, author, posted_on) VALUES (?, ?, ?, ?, NOW())");
                    $stmt->bind_param("ssss", $title, $content, $image_filename, $author);
                    if ($stmt->execute()) {
                        $success = 'Blog added successfully.';
                    } else {
                        $error = 'Insert failed: ' . $conn->error;
                    }
                    $stmt->close();
                }
            }
        }
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $del_id = (int)$_GET['delete'];

    $stmt = $conn->prepare("SELECT image FROM blog_posts WHERE id=?");
    $stmt->bind_param("i", $del_id);
    $stmt->execute();
    $stmt->bind_result($image_to_delete);
    if ($stmt->fetch() && file_exists('assets/images/' . $image_to_delete)) {
        unlink('assets/images/' . $image_to_delete);
    }
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM blog_posts WHERE id=?");
    $stmt->bind_param("i", $del_id);
    if ($stmt->execute()) {
        $success = 'Blog post deleted successfully.';
    } else {
        $error = 'Delete failed: ' . $conn->error;
    }
    $stmt->close();
}

// Fetch for editing
$edit_post = null;
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM blog_posts WHERE id=?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_post = $result->fetch_assoc();
    $stmt->close();
}

// Fetch all blog posts
$blogs = [];
$result = $conn->query("SELECT * FROM blog_posts ORDER BY posted_on DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $blogs[] = $row;
    }
}
?>

<link rel="stylesheet" href="./assets/css/dashboard.css" />

<div class="container">
  <a href="admin_dashboard.php" class="back-link">&#8592; Back to Dashboard</a>

  <h1>Manage Blogs</h1>

  <?php if ($error): ?>
    <p class="message error"><?= htmlspecialchars($error) ?></p>
  <?php endif; ?>

  <?php if ($success): ?>
    <p class="message success"><?= htmlspecialchars($success) ?></p>
  <?php endif; ?>

  <form action="manage_blog.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= htmlspecialchars($edit_post['id'] ?? '') ?>" />

    <label for="title">Title</label>
    <input type="text" name="title" id="title" value="<?= htmlspecialchars($edit_post['title'] ?? '') ?>" required />

    <label for="content">Content</label>
    <textarea name="content" id="content" rows="5" required><?= htmlspecialchars($edit_post['content'] ?? '') ?></textarea>

    <label for="author">Author</label>
    <input type="text" name="author" id="author" value="<?= htmlspecialchars($edit_post['author'] ?? '') ?>" required />

    <label for="image">Image <?= isset($edit_post) ? '(leave blank to keep current)' : '' ?></label>
    <input type="file" name="image" id="image" <?= isset($edit_post) ? '' : 'required' ?> accept="image/*" />

    <?php if (isset($edit_post) && $edit_post['image']): ?>
      <p>Current image:</p>
      <img src="assets/images/<?= htmlspecialchars($edit_post['image']) ?>" style="width:100px; border-radius:5px;" />
    <?php endif; ?>

    <button type="submit"><?= isset($edit_post) ? 'Update Blog' : 'Add Blog' ?></button>

    <?php if (isset($edit_post)): ?>
      <a href="manage_blog.php" class="cancel-link">Cancel Edit</a>
    <?php endif; ?>
  </form>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Image</th>
        <th>Title</th>
        <th>Author</th>
        <th>Posted On</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if (count($blogs) > 0): ?>
        <?php foreach ($blogs as $b): ?>
          <tr>
            <td><?= htmlspecialchars($b['id']) ?></td>
            <td><img src="assets/images/<?= htmlspecialchars($b['image']) ?>" class="thumb" /></td>
            <td><?= htmlspecialchars($b['title']) ?></td>
            <td><?= htmlspecialchars($b['author']) ?></td>
            <td><?= htmlspecialchars($b['posted_on']) ?></td>
            <td>
              <div class="action-buttons">
                <a href="manage_blog.php?edit=<?= $b['id'] ?>" class="button-link">Edit</a>
                <a href="manage_blog.php?delete=<?= $b['id'] ?>" class="button-link delete-link" onclick="return confirm('Delete this post?');">Delete</a>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="6" style="text-align:center;">No blog posts found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
