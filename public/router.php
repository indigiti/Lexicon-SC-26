<?php
declare(strict_types=1);

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$base = trim((string) getenv('APP_BASE_PATH'));
if ($base !== '') {
    $base = '/' . trim($base, '/');
    if ($path === $base) {
        $path = '/';
    } elseif (str_starts_with($path, $base . '/')) {
        $path = substr($path, strlen($base)) ?: '/';
    }
}

// PHP's built-in server invokes the router for every request. Serve physical
// public files directly; only application routes should reach index.php.
$file = __DIR__ . '/' . ltrim($path, '/');
if ($path !== '/' && is_file($file)) {
    if ($base === '') {
        return false;
    }
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    $types = [
        'css' => 'text/css; charset=UTF-8',
        'js' => 'text/javascript; charset=UTF-8',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'webp' => 'image/webp',
        'svg' => 'image/svg+xml',
        'json' => 'application/json; charset=UTF-8',
        'pdf' => 'application/pdf',
        'ics' => 'text/calendar; charset=UTF-8',
    ];
    if (isset($types[$ext])) {
        header('Content-Type: ' . $types[$ext]);
    }
    header('Content-Length: ' . (string) filesize($file));
    readfile($file);
    return true;
}

require __DIR__ . '/index.php';
