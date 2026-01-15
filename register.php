<?php
declare(strict_types=1);

require_once __DIR__ . '/auth.php';

if (is_logged_in()) {
    header('Location: index.php');
    exit;
}

$flash = flash_get();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim((string)($_POST['email'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    $confirm = (string)($_POST['confirm_password'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        flash_set('error', 'Please enter a valid email.');
        header('Location: register.php');
        exit;
    }
    if (strlen($password) < 8) {
        flash_set('error', 'Password must be at least 8 characters.');
        header('Location: register.php');
        exit;
    }
    if ($password !== $confirm) {
        flash_set('error', 'Passwords do not match.');
        header('Location: register.php');
        exit;
    }

    $ok = create_user($email, $password);
    if (!$ok) {
        flash_set('error', 'That email is already registered.');
        header('Location: register.php');
        exit;
    }

    $user = find_user_by_email($email);
    if ($user) {
        login_user($user);
        flash_set('success', 'Account created. You are now logged in.');
        header('Location: index.php');
        exit;
    }

    flash_set('error', 'Something went wrong. Please try again.');
    header('Location: register.php');
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= h(APP_NAME) ?> - Register</title>
  <link rel="stylesheet" href="styles.css" />
</head>
<body>
  <div class="container">
    <header class="topbar">
      <div class="brand"><?= h(APP_NAME) ?></div>
      <div class="topbar-right">
        <a class="btn btn-outline" href="login.php">Login</a>
      </div>
    </header>

    <?php if ($flash): ?>
      <div class="alert <?= h($flash['type']) ?>"><?= h($flash['message']) ?></div>
    <?php endif; ?>

    <main class="card">
      <h1>Create your account</h1>

      <form id="registerForm" method="post" novalidate>
        <label>
          Email
          <input type="email" name="email" required autocomplete="email" />
        </label>
<!-- password not wink 
        <label>
          Password
          <input type="password" name="password" required minlength="8" autocomplete="new-password" />
          <small class="hint">At least 8 characters.</small>
        </label>

        <label>
          Confirm password
          <input type="password" name="confirm_password" required minlength="8" autocomplete="new-password" />
        </label>

   -->
        <label>
  Password
  <div class="password-field">
    <input
      type="password"
      name="password"
      required
      minlength="8"
      autocomplete="new-password"
    />
  </div>
  <small class="hint">At least 8 characters.</small>
</label>

<label>
  Confirm password
  <div class="password-field">
    <input
      type="password"
      name="confirm_password"
      required
      minlength="8"
      autocomplete="new-password"
    />
  </div>
</label>


        <div class="row">
          <button class="btn" type="submit">Create account</button>
          <a class="link" href="login.php">Already have an account?</a>
        </div>

        <p class="form-error" id="registerError" aria-live="polite"></p>
      </form>
    </main>
  </div>

  <script src="app.js"></script>
</body>
</html>
