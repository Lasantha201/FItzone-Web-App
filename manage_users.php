<?php
session_start();
require 'config.php';

// Only admin can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: signin.php");
    exit;
}

$error = '';

// Handle role update and delete actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $userId = intval($_GET['id']);
    $action = $_GET['action'];

    if ($userId !== ($_SESSION['id'] ?? 0)) { // prevent self-change
        if ($action === 'make_admin') {
            $stmt = $conn->prepare("UPDATE users SET role = 'admin' WHERE id = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $stmt->close();
        } elseif ($action === 'remove_admin') {
            $stmt = $conn->prepare("UPDATE users SET role = 'user' WHERE id = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $stmt->close();
        } elseif ($action === 'delete') {
            $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $stmt->close();
        }
    }
    header("Location: manage_users.php");
    exit;
}

// Fetch all users
$users = $conn->query("SELECT * FROM users ORDER BY id DESC");
if (!$users) {
    $error = "Database query failed: " . $conn->error;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Manage Users - FitZone Admin</title>
  
  <link rel="stylesheet" href="./assets/css/dashboard.css" />
</head>
<body>

  <h1>Manage Users</h1>

  <a href="admin_dashboard.php" class="back-link">&#8592; Back to Dashboard</a>

  <?php if ($error): ?>
    <div class="error-message"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Username</th>
        <th>Email</th>
        <th>Role</th>
        <th>Created At</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($users && $users->num_rows > 0): ?>
        <?php while ($user = $users->fetch_assoc()): ?>
          <tr>
            <td><?= $user['id'] ?></td>
            <td><?= htmlspecialchars($user['username']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td><?= htmlspecialchars($user['role']) ?></td>
            <td><?= htmlspecialchars($user['created_at']) ?></td>
            <td class="action-buttons">
              <?php if ($user['id'] !== ($_SESSION['id'] ?? 0)): ?>
                <?php if ($user['role'] === 'user'): ?>
                  <a href="?action=make_admin&id=<?= $user['id'] ?>" class="promote">Make Admin</a>
                <?php elseif ($user['role'] === 'admin'): ?>
                  <a href="?action=remove_admin&id=<?= $user['id'] ?>" class="demote">Remove Admin</a>
                <?php endif; ?>
                <a href="?action=delete&id=<?= $user['id'] ?>" class="delete" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
              <?php else: ?>
                <span class="user-you">You</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="6" style="text-align:center;">No users found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>

</body>
</html>
