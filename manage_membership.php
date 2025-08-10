<?php
session_start();

require 'config.php';

// Handle delete
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $stmt = $conn->prepare("DELETE FROM memberships WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_membership.php");
    exit();
}

// Handle payment status toggle (mark as paid or pending)
if (isset($_GET['toggle_payment_id'])) {
    $id = intval($_GET['toggle_payment_id']);
    // Get current payment status
    $stmt = $conn->prepare("SELECT payment_status FROM memberships WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($current_status);
    $stmt->fetch();
    $stmt->close();

    $new_status = ($current_status === 'paid') ? 'pending' : 'paid';

    $stmt = $conn->prepare("UPDATE memberships SET payment_status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: manage_membership.php");
    exit();
}

// Fetch all memberships
$result = $conn->query("SELECT * FROM memberships ORDER BY registered_on DESC");

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Manage Memberships</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #ddd; padding: 8px; }
    th { background-color: #f2f2f2; }
    a.btn { padding: 6px 10px; background: #ff5722; color: white; text-decoration: none; border-radius: 4px; }
    a.btn:hover { background: #e64a19; }
    .actions a { margin-right: 10px; }
    .paid { color: green; font-weight: bold; }
    .pending { color: red; font-weight: bold; }

  .back-link {
    display: inline-block;
    margin-bottom: 20px;
    padding: 8px 15px;
    background-color: #e94e1b;
    color: white;
    border-radius: 5px;
    text-decoration: none;
    font-weight: bold;
    font-size: 14px;
    transition: background-color 0.3s ease;
}

  </style>
</head>
<body>
 <a href="admin_dashboard.php" class="back-link">&#8592; Back to Dashboard</a>
  <h1>Manage Membership Requests</h1>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Plan</th>
        <th>Message</th>
        <th>Registered On</th>
        <th>Payment Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['id']) ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['phone']) ?></td>
            <td><?= htmlspecialchars($row['plan']) ?></td>
            <td><?= nl2br(htmlspecialchars($row['message'])) ?></td>
            <td><?= htmlspecialchars($row['registered_on']) ?></td>
            <td class="<?= $row['payment_status'] === 'paid' ? 'paid' : 'pending' ?>">
              <?= htmlspecialchars(ucfirst($row['payment_status'])) ?>
            </td>
            <td class="actions">
              <a href="manage_membership.php?toggle_payment_id=<?= $row['id'] ?>" class="btn" 
                onclick="return confirm('Toggle payment status for this membership?');">
                <?= $row['payment_status'] === 'paid' ? 'Mark Pending' : 'Mark Paid' ?>
              </a>
              <a href="manage_membership.php?delete_id=<?= $row['id'] ?>" class="btn" 
                onclick="return confirm('Are you sure you want to delete this membership?');">Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="9" style="text-align:center;">No memberships found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>

</body>
</html>
