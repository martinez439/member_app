<?php
declare(strict_types=1);

session_start();

define('APP_NAME', 'Hello Auth App');

// XAMPP defaults:
// host: 127.0.0.1 (or localhost)
// user: root
// pass: "" (empty)
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'member_app');
define('DB_USER', 'root');
define('DB_PASS', '');  // XAMPP default is empty
define('DB_CHARSET', 'utf8mb4');
