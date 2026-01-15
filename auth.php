<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';

function is_logged_in(): bool {
    return isset($_SESSION['user_id'], $_SESSION['user_email']);
}

function require_login(): void {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

function h(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

function flash_set(string $type, string $message): void {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function flash_get(): ?array {
    if (!isset($_SESSION['flash'])) return null;
    $f = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $f;
}

function find_user_by_email(string $email): ?array {
    $stmt = db()->prepare("SELECT id, email, password_hash FROM users WHERE email = :email LIMIT 1");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();
    return $user ?: null;
}


function create_user(string $email, string $password): bool {
    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = db()->prepare("
        INSERT INTO users (email, password_hash, created_at)
        VALUES (:email, :password_hash, NOW())
    ");

    try {
        return $stmt->execute([
            ':email' => $email,
            ':password_hash' => $hash,
        ]);
    } catch (PDOException $e) {
        // Duplicate email in MySQL typically shows SQLSTATE 23000 (integrity constraint)
        if ($e->getCode() === '23000') {
            return false;
        }
        throw $e; // bubble up unexpected DB errors
    }
}

function login_user(array $user): void {
    // Basic session login
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_email'] = $user['email'];
}

function logout_user(): void {
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
}
