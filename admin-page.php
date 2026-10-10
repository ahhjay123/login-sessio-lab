<?php

require_once __DIR__ . '/db-connect.php';
require_once __DIR__ . '/index.php';

start_app_session();
handle_logout_request();
check_login('admin');

$users = $conn->query('SELECT id, username, role FROM users ORDER BY id');
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin dashboard</title>
    <style>
        body { margin: 0; background: #f2f5f3; color: #17211d; font: 16px/1.5 system-ui, sans-serif; }
        main { box-sizing: border-box; width: min(100% - 32px, 820px); margin: 6vh auto; padding: 32px; background: #fff; border: 1px solid #d8e0db; border-radius: 8px; }
        header { display: flex; align-items: center; justify-content: space-between; gap: 16px; }
        h1 { margin: 0; font-size: 28px; }
        p { color: #52615a; }
        button { padding: 9px 14px; border: 0; border-radius: 4px; background: #176b50; color: #fff; font: inherit; font-weight: 700; cursor: pointer; }
        table { width: 100%; margin-top: 24px; border-collapse: collapse; text-align: left; }
        th, td { padding: 10px 12px; border-bottom: 1px solid #d8e0db; }
        th { background: #edf4ef; }
        @media (max-width: 520px) { main { padding: 20px; } header { align-items: flex-start; flex-direction: column; } }
    </style>
</head>
<body>
<main>
    <header>
        <h1>Admin dashboard</h1>
        <form method="post" action="admin-page.php">
            <button type="submit" name="logout" value="1">Sign out</button>
        </form>
    </header>
    <p>Signed in as <?= escape_html($_SESSION['username']) ?>. Registered users: <?= (int) $users->num_rows ?>.</p>
    <table>
        <thead>
            <tr><th>ID</th><th>Username</th><th>Role</th></tr>
        </thead>
        <tbody>
            <?php while ($user = $users->fetch_assoc()): ?>
                <tr>
                    <td><?= (int) $user['id'] ?></td>
                    <td><?= escape_html($user['username']) ?></td>
                    <td><?= escape_html($user['role']) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</main>
</body>
</html>