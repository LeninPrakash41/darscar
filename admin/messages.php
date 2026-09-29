<?php
require_once __DIR__ . '/../includes/admin-auth.php';
require_admin();
require_once __DIR__ . '/../includes/db.php';

$pdo = get_db_connection();
$messages = $pdo->query('SELECT * FROM contact_messages ORDER BY created_at DESC')->fetchAll();

$page_title = 'Contact Messages';
require __DIR__ . '/../includes/admin-header.php';
?>

<div class="page-head">
  <h1>Contact Messages</h1>
</div>

<div class="admin-table-wrap">
  <table class="admin-table">
    <thead>
      <tr>
        <th>From</th>
        <th>Subject</th>
        <th>Message</th>
        <th>Received</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($messages)): ?>
        <tr><td colspan="4" class="muted">No messages yet.</td></tr>
      <?php else: foreach ($messages as $m): ?>
        <tr>
          <td>
            <?= htmlspecialchars($m['full_name']) ?><br>
            <span class="muted"><?= htmlspecialchars($m['email']) ?></span>
            <?php if ($m['phone']): ?><br><span class="muted"><?= htmlspecialchars($m['phone']) ?></span><?php endif; ?>
          </td>
          <td><?= htmlspecialchars($m['subject']) ?></td>
          <td style="max-width:360px;"><?= nl2br(htmlspecialchars($m['message'])) ?></td>
          <td class="muted"><?= htmlspecialchars(date('M j, Y g:i A', strtotime($m['created_at']))) ?></td>
        </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>

<?php require __DIR__ . '/../includes/admin-footer.php'; ?>
