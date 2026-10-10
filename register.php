<?php 
require_once __DIR__ . '/db-connect.php';
require_once __DIR__ . '/index.php';

start_app_session();

if (isset($_SESSION['user_id'], $_SESSION['role'])) {
	redirect_by_role($_SESSION['role']);
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$usernameInput = $_POST['username'] ?? '';
	$passwordInput = $_POST['password'] ?? '';
	$passwordConfirm = $_POST['password_confirm'] ?? '';

	if (!is_string($usernameInput) || !is_string($passwordInput) || !is_string($passwordConfirm)) {
		$error = 'Enter valid registration details.';
	} else {
		$username = trim($usernameInput);

		if (!preg_match('/^[A-Za-z0-9_.-]{3,50}$/', $username)) {
			$error = 'Use 3 to 50 letters, numbers, dots, underscores, or hyphens for your username.';
		} elseif (strlen($passwordInput) < 8) {
			$error = 'Choose a password with at least 8 characters.';
		} elseif ($passwordInput !== $passwordConfirm) {
			$error = 'The passwords do not match.';
		} else {
			$statement = $conn->prepare('SELECT id FROM users WHERE username = ? LIMIT 1');
			$statement->bind_param('s', $username);
			$statement->execute();
			$exists = $statement->get_result()->fetch_assoc();
			$statement->close();

			if ($exists) {
				$error = 'That username is already in use.';
			} else {
				$passwordHash = password_hash($passwordInput, PASSWORD_DEFAULT);
				$statement = $conn->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
				$statement->bind_param('ss', $username, $passwordHash);
				try {
					$statement->execute();
					$statement->close();
					$_SESSION['flash_message'] = 'Account created. Sign in to continue.';
					header('Location: login.php');
					exit();
				} catch (mysqli_sql_exception $exception) {
					$statement->close();
					if ($exception->getCode() === 1062) {
						$error = 'That username is already in use.';
					} else {
						throw $exception;
					}
				}
			}
		}
	}
}




?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Create account</title>
	<style>
		body { margin: 0; background: #f2f5f3; color: #17211d; font: 16px/1.5 system-ui, sans-serif; }
		main { box-sizing: border-box; width: min(100% - 32px, 440px); margin: 8vh auto; padding: 32px; background: #fff; border: 1px solid #d8e0db; border-radius: 8px; }
		h1 { margin: 0 0 8px; font-size: 28px; }
		p { margin: 0 0 20px; color: #52615a; }
		label { display: block; margin: 16px 0 6px; font-weight: 600; }
		input { box-sizing: border-box; width: 100%; padding: 11px 12px; border: 1px solid #aebbb3; border-radius: 4px; font: inherit; }
		button { width: 100%; margin-top: 22px; padding: 12px; border: 0; border-radius: 4px; background: #176b50; color: #fff; font: inherit; font-weight: 700; cursor: pointer; }
		.error { margin: 12px 0; padding: 10px 12px; border-radius: 4px; background: #fff0ed; color: #8b2e1d; }
		a { color: #176b50; }
	</style>
</head>
<body>
<main>
	<h1>Create account</h1>
	<p>New accounts receive regular-user access.</p>
	<?php if ($error !== ''): ?><div class="error"><?= escape_html($error) ?></div><?php endif; ?>
	<form method="post" action="register.php">
		<label for="username">Username</label>
		<input id="username" name="username" autocomplete="username" minlength="3" maxlength="50" required>
		<label for="password">Password</label>
		<input id="password" name="password" type="password" autocomplete="new-password" minlength="8" required>
		<label for="password_confirm">Confirm password</label>
		<input id="password_confirm" name="password_confirm" type="password" autocomplete="new-password" minlength="8" required>
		<button type="submit">Create account</button>
	</form>
	<p style="margin-top: 20px">Already registered? <a href="login.php">Sign in</a></p>
</main>
</body>
</html>