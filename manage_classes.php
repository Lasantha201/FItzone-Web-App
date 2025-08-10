<?php
require 'config.php';

$error = '';
$success = '';
$edit_class = null;

// Handle Create and Update form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $coach_name = trim($_POST['coach_name'] ?? '');
    $intensity = (int)($_POST['intensity'] ?? 0);

    // Files
    $image = $_FILES['image'] ?? null;
    $coach_image = $_FILES['coach_image'] ?? null;

    // Validate required fields
    if (!$title || !$description || !$coach_name || $intensity < 0 || $intensity > 100) {
        $error = 'Please fill all required fields correctly.';
    } else {
        // Handle main image upload (optional on update)
        $image_filename = '';
        if ($image && $image['error'] !== 4) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $ext = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed)) {
                $error = 'Invalid main image file type.';
            } else {
                $image_filename = uniqid('class_img_') . '.' . $ext;
                $target = 'assets/images/' . $image_filename;
                if (!move_uploaded_file($image['tmp_name'], $target)) {
                    $error = 'Failed to upload main image.';
                }
            }
        }

        // Handle coach image upload (optional on update)
        $coach_image_filename = '';
        if ($coach_image && $coach_image['error'] !== 4) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $ext = strtolower(pathinfo($coach_image['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed)) {
                $error = 'Invalid coach image file type.';
            } else {
                $coach_image_filename = uniqid('coach_img_') . '.' . $ext;
                $target = 'assets/images/' . $coach_image_filename;
                if (!move_uploaded_file($coach_image['tmp_name'], $target)) {
                    $error = 'Failed to upload coach image.';
                }
            }
        }

        if (!$error) {
            if ($id) {
                // UPDATE existing class
                // Build query dynamically depending on whether images are uploaded
                if ($image_filename && $coach_image_filename) {
                    $stmt = $conn->prepare("UPDATE classes SET title=?, description=?, coach_name=?, intensity=?, image=?, coach_image=? WHERE id=?");
                    $stmt->bind_param("sssisii", $title, $description, $coach_name, $intensity, $image_filename, $coach_image_filename, $id);
                } elseif ($image_filename) {
                    $stmt = $conn->prepare("UPDATE classes SET title=?, description=?, coach_name=?, intensity=?, image=? WHERE id=?");
                    $stmt->bind_param("sssisi", $title, $description, $coach_name, $intensity, $image_filename, $id);
                } elseif ($coach_image_filename) {
                    $stmt = $conn->prepare("UPDATE classes SET title=?, description=?, coach_name=?, intensity=?, coach_image=? WHERE id=?");
                    $stmt->bind_param("sssisi", $title, $description, $coach_name, $intensity, $coach_image_filename, $id);
                } else {
                    $stmt = $conn->prepare("UPDATE classes SET title=?, description=?, coach_name=?, intensity=? WHERE id=?");
                    $stmt->bind_param("sssii", $title, $description, $coach_name, $intensity, $id);
                }

                if ($stmt->execute()) {
                    $success = 'Class updated successfully.';
                } else {
                    $error = 'Update failed: ' . $conn->error;
                }
                $stmt->close();

            } else {
                // INSERT new class - images required
                if (!$image_filename || !$coach_image_filename) {
                    $error = 'Both class image and coach image are required for new classes.';
                } else {
                    $stmt = $conn->prepare("INSERT INTO classes (title, description, image, coach_name, coach_image, intensity) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("sssssi", $title, $description, $image_filename, $coach_name, $coach_image_filename, $intensity);

                    if ($stmt->execute()) {
                        $success = 'Class added successfully.';
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

    // Get images to delete files
    $stmt = $conn->prepare("SELECT image, coach_image FROM classes WHERE id=?");
    $stmt->bind_param("i", $del_id);
    $stmt->execute();
    $stmt->bind_result($img_to_delete, $coach_img_to_delete);
    if ($stmt->fetch()) {
        if ($img_to_delete && file_exists('assets/images/' . $img_to_delete)) {
            unlink('assets/images/' . $img_to_delete);
        }
        if ($coach_img_to_delete && file_exists('assets/images/' . $coach_img_to_delete)) {
            unlink('assets/images/' . $coach_img_to_delete);
        }
    }
    $stmt->close();

    // Delete class record
    $stmt = $conn->prepare("DELETE FROM classes WHERE id=?");
    $stmt->bind_param("i", $del_id);
    if ($stmt->execute()) {
        $success = 'Class deleted successfully.';
    } else {
        $error = 'Delete failed: ' . $conn->error;
    }
    $stmt->close();
}

// Fetch class data for editing
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM classes WHERE id=?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_class = $result->fetch_assoc();
    $stmt->close();
}

// Fetch all classes for listing
$classes = [];
$result = $conn->query("SELECT * FROM classes ORDER BY id DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $classes[] = $row;
    }
}
?>

<link rel="stylesheet" href="./assets/css/dashboard.css" />

<div class="container">
  <a href="admin_dashboard.php" class="back-link">&#8592; Back to Dashboard</a>
  <h1>Manage Classes</h1>

  <?php if ($error): ?>
    <p class="message error"><?= htmlspecialchars($error) ?></p>
  <?php endif; ?>

  <?php if ($success): ?>
    <p class="message success"><?= htmlspecialchars($success) ?></p>
  <?php endif; ?>

  <form action="manage_classes.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= htmlspecialchars($edit_class['id'] ?? '') ?>" />

    <label for="title">Title</label>
    <input type="text" name="title" id="title" value="<?= htmlspecialchars($edit_class['title'] ?? '') ?>" required />

    <label for="description">Description</label>
    <textarea name="description" id="description" rows="4" required><?= htmlspecialchars($edit_class['description'] ?? '') ?></textarea>

    <label for="image">Class Image <?= isset($edit_class) ? '(leave blank to keep current)' : '' ?></label>
    <input type="file" name="image" id="image" accept="image/*" <?= isset($edit_class) ? '' : 'required' ?> />
    <?php if (isset($edit_class) && $edit_class['image']): ?>
      <p>Current image:</p>
      <img src="assets/images/<?= htmlspecialchars($edit_class['image']) ?>" alt="Class Image" style="width:100px; border-radius:5px; margin-bottom:15px;" />
    <?php endif; ?>

    <label for="coach_name">Coach Name</label>
    <input type="text" name="coach_name" id="coach_name" value="<?= htmlspecialchars($edit_class['coach_name'] ?? '') ?>" required />

    <label for="coach_image">Coach Image <?= isset($edit_class) ? '(leave blank to keep current)' : '' ?></label>
    <input type="file" name="coach_image" id="coach_image" accept="image/*" <?= isset($edit_class) ? '' : 'required' ?> />
    <?php if (isset($edit_class) && $edit_class['coach_image']): ?>
      <p>Current coach image:</p>
      <img src="assets/images/<?= htmlspecialchars($edit_class['coach_image']) ?>" alt="Coach Image" style="width:100px; border-radius:5px; margin-bottom:15px;" />
    <?php endif; ?>

    <label for="intensity">Intensity (%)</label>
    <input type="number" name="intensity" id="intensity" min="0" max="100" value="<?= htmlspecialchars($edit_class['intensity'] ?? '') ?>" required />

    <button type="submit"><?= isset($edit_class) ? 'Update Class' : 'Add Class' ?></button>
    <?php if (isset($edit_class)): ?>
      <a href="manage_classes.php" class="cancel-link">Cancel Edit</a>
    <?php endif; ?>
  </form>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Class Image</th>
        <th>Title</th>
        <th>Description</th>
        <th>Coach Name</th>
        <th>Coach Image</th>
        <th>Intensity</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if (count($classes) > 0): ?>
        <?php foreach ($classes as $c): ?>
          <tr>
            <td><?= htmlspecialchars($c['id']) ?></td>
            <td><img src="assets/images/<?= htmlspecialchars($c['image']) ?>" alt="<?= htmlspecialchars($c['title']) ?>" class="thumb" /></td>
            <td><?= htmlspecialchars($c['title']) ?></td>
            <td><?= htmlspecialchars(strlen($c['description']) > 50 ? substr($c['description'], 0, 50) . '...' : $c['description']) ?></td>
            <td><?= htmlspecialchars($c['coach_name']) ?></td>
            <td><img src="assets/images/<?= htmlspecialchars($c['coach_image']) ?>" alt="<?= htmlspecialchars($c['coach_name']) ?>" class="thumb" /></td>
            <td><?= htmlspecialchars($c['intensity']) ?>%</td>
            <td>
              <div class="action-buttons">
                <a class="button-link" href="manage_classes.php?edit=<?= htmlspecialchars($c['id']) ?>">Edit</a>
                <a class="button-link delete-link" href="manage_classes.php?delete=<?= htmlspecialchars($c['id']) ?>" onclick="return confirm('Delete this class?');">Delete</a>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="8" style="text-align:center;">No classes found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
