<?php 

require_once __DIR__ . '/index.php';

start_app_session();
handle_logout_request();
check_login('regular');



?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Your account</title>
	<style>
		body { margin: 0; background: #f2f5f3; color: #17211d; font: 16px/1.5 system-ui, sans-serif; }
		main { box-sizing: border-box; width: min(100% - 32px, 680px); margin: 8vh auto; padding: 32px; background: #fff; border: 1px solid #d8e0db; border-radius: 8px; }
		h1 { margin: 0 0 8px; font-size: 28px; }
		p { color: #52615a; }
		button { padding: 10px 16px; border: 0; border-radius: 4px; background: #176b50; color: #fff; font: inherit; font-weight: 700; cursor: pointer; }
	</style>
</head>
<body>
<main>
	<h1>Welcome, <?= escape_html($_SESSION['username']) ?></h1>
	<p>You are signed in with regular-user access.</p>
	<form method="post" action="regular-page.php">
		<button type="submit" name="logout" value="1">Sign out</button>
	</form>
</main>
</body>
</html>