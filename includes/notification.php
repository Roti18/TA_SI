<?php
$message = $_SESSION['message'] ?? null;
$message_type = $_SESSION['message_type'] ?? null;
unset($_SESSION['message'], $_SESSION['message_type']);

if ($message): ?>
<div class="mb-4 p-4 rounded-lg text-white <?php 
    if ($message_type === 'success') {
        echo 'bg-green-500';
    } elseif ($message_type === 'delete') {
        echo 'bg-red-500';
    } else {
        echo 'bg-red-500';
    }
?>">
  <?= htmlspecialchars($message) ?>
</div>
<?php endif; ?>