<?php

require_once __DIR__ . '/db-connect.php';
require_once __DIR__ . '/index.php';

start_app_session();

if (isset($_SESSION['user_id'], $_SESSION['role'])) {
	redirect_by_role($_SESSION['role']);
}

$error = '';
$notice = $_SESSION['flash_message'] ?? '';
unset($_SESSION['flash_message']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$usernameInput = $_POST['username'] ?? '';
	$passwordInput = $_POST['password'] ?? '';

	if (!is_string($usernameInput) || !is_string($passwordInput)
		|| trim($usernameInput) === '' || $passwordInput === '') {
		$error = 'Enter your username and password.';
	} else {
		$username = trim($usernameInput);
		$statement = $conn->prepare('SELECT id, username, password, role FROM users WHERE username = ? LIMIT 1');
		$statement->bind_param('s', $username);
		$statement->execute();
		$user = $statement->get_result()->fetch_assoc();
		$statement->close();

		if ($user && password_verify($passwordInput, $user['password'])) {
			session_regenerate_id(true);
			$_SESSION['user_id'] = (int) $user['id'];
			$_SESSION['username'] = $user['username'];
			$_SESSION['role'] = $user['role'];
			redirect_by_role($user['role']);
		}

		$error = 'Username or password is incorrect.';
	}
}

?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Sign in</title>
	<style>
		body { margin: 0; background: #f2f5f3; color: #17211d; font: 16px/1.5 system-ui, sans-serif; }
		main { box-sizing: border-box; width: min(100% - 32px, 420px); margin: 10vh auto; padding: 32px; background: #fff; border: 1px solid #d8e0db; border-radius: 8px; }
		h1 { margin: 0 0 8px; font-size: 28px; }
		p { margin: 0 0 20px; color: #52615a; }
		label { display: block; margin: 16px 0 6px; font-weight: 600; }
		input { box-sizing: border-box; width: 100%; padding: 11px 12px; border: 1px solid #aebbb3; border-radius: 4px; font: inherit; }
		button { width: 100%; margin-top: 22px; padding: 12px; border: 0; border-radius: 4px; background: #176b50; color: #fff; font: inherit; font-weight: 700; cursor: pointer; }
		.message { margin: 12px 0; padding: 10px 12px; border-radius: 4px; background: #edf4ef; }
		.error { background: #fff0ed; color: #8b2e1d; }
		a { color: #176b50; }
	</style>
</head>
<body>
<main>
	<h1>Sign in</h1>
	<p>Access your account.</p>
	<?php if ($notice !== ''): ?><div class="message"><?= escape_html($notice) ?></div><?php endif; ?>
	<?php if ($error !== ''): ?><div class="message error"><?= escape_html($error) ?></div><?php endif; ?>
	<form method="post" action="login.php">
		<label for="username">Username</label>
		<input id="username" name="username" autocomplete="username" maxlength="50" required>
		<label for="password">Password</label>
		<input id="password" name="password" type="password" autocomplete="current-password" required>
		<button type="submit">Sign in</button>
	</form>
	<p style="margin-top: 20px">New here? <a href="register.php">Create an account</a></p>
</main>
</body>
</html>