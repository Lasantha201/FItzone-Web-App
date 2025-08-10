<?php
require 'config.php';

$error = '';
$success = '';

// Handle Create and Update form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $name = trim($_POST['name'] ?? '');
    $specialty = trim($_POST['specialty'] ?? '');
    $bio = trim($_POST['bio'] ?? '');
    $photo = $_FILES['photo'] ?? null;

    if (!$name || !$specialty || !$bio) {
        $error = 'Please fill all fields.';
    } else {
        $photo_filename = '';
        if ($photo && $photo['error'] !== 4) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $ext = strtolower(pathinfo($photo['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed)) {
                $error = 'Invalid image file type.';
            } else {
                $photo_filename = uniqid() . '.' . $ext;
                $target = 'assets/images/' . $photo_filename;
                if (!move_uploaded_file($photo['tmp_name'], $target)) {
                    $error = 'Failed to upload image.';
                }
            }
        }

        if (!$error) {
            if ($id) {
                if ($photo_filename) {
                    $stmt = $conn->prepare("UPDATE trainers SET name=?, specialty=?, bio=?, photo=? WHERE id=?");
                    $stmt->bind_param("ssssi", $name, $specialty, $bio, $photo_filename, $id);
                } else {
                    $stmt = $conn->prepare("UPDATE trainers SET name=?, specialty=?, bio=? WHERE id=?");
                    $stmt->bind_param("sssi", $name, $specialty, $bio, $id);
                }
                if ($stmt->execute()) {
                    $success = 'Trainer updated successfully.';
                } else {
                    $error = 'Update failed: ' . $conn->error;
                }
                $stmt->close();
            } else {
                if (!$photo_filename) {
                    $error = 'Photo is required for new trainer.';
                } else {
                    $stmt = $conn->prepare("INSERT INTO trainers (name, specialty, bio, photo) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("ssss", $name, $specialty, $bio, $photo_filename);
                    if ($stmt->execute()) {
                        $success = 'Trainer added successfully.';
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

    // Get photo filename to delete file
    $stmt = $conn->prepare("SELECT photo FROM trainers WHERE id=?");
    $stmt->bind_param("i", $del_id);
    $stmt->execute();
    $stmt->bind_result($del_photo);
    if ($stmt->fetch()) {
        if ($del_photo && file_exists('assets/images/' . $del_photo)) {
            unlink('assets/images/' . $del_photo);
        }
    }
    $stmt->close();

    // Delete trainer
    $stmt = $conn->prepare("DELETE FROM trainers WHERE id=?");
    $stmt->bind_param("i", $del_id);
    if ($stmt->execute()) {
        $success = 'Trainer deleted successfully.';
    } else {
        $error = 'Delete failed: ' . $conn->error;
    }
    $stmt->close();
}

// Fetch trainer data for editing
$edit_trainer = null;
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $stmt = $conn->prepare("SELECT id, name, specialty, bio, photo FROM trainers WHERE id=?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_trainer = $result->fetch_assoc();
    $stmt->close();
}

// Fetch all trainers for listing
$trainers = [];
$result = $conn->query("SELECT * FROM trainers ORDER BY id DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $trainers[] = $row;
    }
}
?>

<link rel="stylesheet" href="./assets/css/dashboard.css" />

<div class="container">

  <a href="admin_dashboard.php" class="back-link">&#8592; Back to Dashboard</a>

  <h1>Manage Trainers</h1>

  <?php if ($error): ?>
    <p class="message error"><?= htmlspecialchars($error) ?></p>
  <?php endif; ?>

  <?php if ($success): ?>
    <p class="message success"><?= htmlspecialchars($success) ?></p>
  <?php endif; ?>

  <form action="manage_trainer.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= htmlspecialchars($edit_trainer['id'] ?? '') ?>" />

    <label for="name">Name</label>
    <input type="text" name="name" id="name" value="<?= htmlspecialchars($edit_trainer['name'] ?? '') ?>" required />

    <label for="specialty">Specialty</label>
    <input type="text" name="specialty" id="specialty" value="<?= htmlspecialchars($edit_trainer['specialty'] ?? '') ?>" required />

    <label for="bio">Bio</label>
    <textarea name="bio" id="bio" rows="4" required><?= htmlspecialchars($edit_trainer['bio'] ?? '') ?></textarea>

    <label for="photo">Photo <?= isset($edit_trainer) ? '(leave blank to keep current)' : '' ?></label>
    <input type="file" name="photo" id="photo" <?= isset($edit_trainer) ? '' : 'required' ?> accept="image/*" />

    <?php if (isset($edit_trainer) && $edit_trainer['photo']): ?>
      <p>Current photo:</p>
      <img src="assets/images/<?= htmlspecialchars($edit_trainer['photo']) ?>" alt="Current photo" style="width:100px; border-radius:5px; margin-bottom:15px;" />
    <?php endif; ?>

    <button type="submit"><?= isset($edit_trainer) ? 'Update Trainer' : 'Add Trainer' ?></button>

    <?php if (isset($edit_trainer)): ?>
      <a href="manage_trainer.php" class="cancel-link">Cancel Edit</a>
    <?php endif; ?>
  </form>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Photo</th>
        <th>Name</th>
        <th>Specialty</th>
        <th>Bio</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if (count($trainers) > 0): ?>
        <?php foreach ($trainers as $t): ?>
          <tr>
            <td><?= htmlspecialchars($t['id']) ?></td>
            <td><img src="assets/images/<?= htmlspecialchars($t['photo']) ?>" alt="<?= htmlspecialchars($t['name']) ?>" class="thumb" /></td>
            <td><?= htmlspecialchars($t['name']) ?></td>
            <td><?= htmlspecialchars($t['specialty']) ?></td>
            <td><?= htmlspecialchars(strlen($t['bio']) > 50 ? substr($t['bio'], 0, 50) . '...' : $t['bio']) ?></td>
            <td>
              <div class="action-buttons">
                <a class="button-link" href="manage_trainer.php?edit=<?= htmlspecialchars($t['id']) ?>">Edit</a>
                <a class="button-link delete-link" href="manage_trainer.php?delete=<?= htmlspecialchars($t['id']) ?>" onclick="return confirm('Delete this trainer?');">Delete</a>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="6" style="text-align:center;">No trainers found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
