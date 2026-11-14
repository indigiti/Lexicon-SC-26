<?php
declare(strict_types=1);

function e(string|int|float|null $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function content(string $name): array
{
    static $repo;
    $repo ??= new ContentRepository();
    return $repo->get($name);
}

/** Return the shared campus entrance photo for a fixed campus placement. */
function campus_photo(string $slug): string
{
    foreach (content('schools') as $school) {
        if (($school['slug'] ?? '') === $slug) return (string) ($school['image'] ?? '');
    }
    return '';
}

/**
 * Public URL prefix for this installation.
 *
 * Examples:
 *   https://example.com/       => ""
 *   https://example.com/v/     => "/v"
 *
 * APP_BASE_PATH can be used as an explicit override, but normally the value
 * is inferred from SCRIPT_NAME. A trailing /public is removed so the full
 * project may safely live below a subdirectory while Apache rewrites through
 * public/index.php.
 */
function base_path(): string
{
    static $base;
    if ($base !== null) {
        return $base;
    }

    $override = getenv('APP_BASE_PATH');
    if ($override !== false && trim($override) !== '') {
        $value = '/' . trim(str_replace('\\', '/', trim($override)), '/');
        return $base = ($value === '/') ? '' : $value;
    }

    $script = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? ''));
    $dir = str_replace('\\', '/', dirname($script));
    $dir = $dir === '.' ? '' : rtrim($dir, '/');

    if ($dir === '/public') {
        $dir = '';
    } elseif (str_ends_with($dir, '/public')) {
        $dir = substr($dir, 0, -7);
    }

    if ($dir === '' || $dir === '/') {
        return $base = '';
    }

    return $base = '/' . trim($dir, '/');
}

/** Return a request path relative to the installation base. */
function path_only(): string
{
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    $base = base_path();

    if ($base !== '' && ($path === $base || str_starts_with($path, $base . '/'))) {
        $path = substr($path, strlen($base));
        if ($path === '' || $path === false) {
            $path = '/';
        }
    }

    $path = '/' . ltrim($path, '/');
    $path = rtrim($path, '/');
    return $path === '' ? '/' : $path;
}

/** Build an internal site URL without assuming the application lives at /. */
function url(string $path = '/'): string
{
    if ($path === '') {
        $path = '/';
    }

    if (
        str_starts_with($path, '#') ||
        str_starts_with($path, 'mailto:') ||
        str_starts_with($path, 'tel:') ||
        preg_match('~^(?:https?:)?//~i', $path)
    ) {
        return $path;
    }

    $path = '/' . ltrim($path, '/');
    return base_path() . $path;
}

function asset(string $path): string
{
    return url('/' . ltrim($path, '/'));
}

/** Recursively prefix public root-relative URL values returned through JSON APIs. */
function public_urls(array $data): array
{
    $walk = static function (mixed $value) use (&$walk): mixed {
        if (is_array($value)) {
            foreach ($value as $key => $item) {
                $value[$key] = $walk($item);
            }
            return $value;
        }
        if (is_string($value) && str_starts_with($value, '/') && !str_starts_with($value, '//')) {
            $base = base_path();
            if ($base !== '' && ($value === $base || str_starts_with($value, $base . '/'))) {
                return $value;
            }
            return url($value);
        }
        return $value;
    };

    return $walk($data);
}

/**
 * Compatibility guard for legacy/root-relative view markup.
 * It converts only same-site root-relative HTML attributes to the detected
 * base path. Already-prefixed URLs and protocol-relative URLs are untouched.
 */
function apply_base_path_to_html(string $html): string
{
    $base = base_path();
    if ($base === '') {
        return $html;
    }

    $html = preg_replace_callback(
        '~\b(href|src|action|formaction)=("|\')(/(?!/)[^"\']*)\2~i',
        static function (array $m) use ($base): string {
            $value = $m[3];
            if ($value === $base || str_starts_with($value, $base . '/')) {
                return $m[0];
            }
            return $m[1] . '=' . $m[2] . $base . $value . $m[2];
        },
        $html
    ) ?? $html;

    $html = preg_replace_callback(
        '~\bsrcset=("|\')([^"\']*)\1~i',
        static function (array $m) use ($base): string {
            $items = array_map(static function (string $item) use ($base): string {
                $item = trim($item);
                if ($item === '') {
                    return $item;
                }
                $parts = preg_split('/\s+/', $item, 2);
                $candidate = $parts[0] ?? '';
                if (str_starts_with($candidate, '/') && !str_starts_with($candidate, '//') && !str_starts_with($candidate, $base . '/')) {
                    $candidate = $base . $candidate;
                }
                return $candidate . (isset($parts[1]) ? ' ' . $parts[1] : '');
            }, explode(',', $m[2]));
            return 'srcset=' . $m[1] . implode(', ', $items) . $m[1];
        },
        $html
    ) ?? $html;

    return $html;
}

function render(string $view, array $data = []): never
{
    extract($data, EXTR_SKIP);
    $viewFile = BASE_PATH . '/app/Views/pages/' . $view . '.php';
    if (!is_file($viewFile)) {
        http_response_code(500);
        exit('View not found');
    }

    ob_start();
    require BASE_PATH . '/app/Views/layouts/main.php';
    $html = (string) ob_get_clean();
    echo apply_base_path_to_html($html);
    exit;
}

function json_response(array $payload, int $status = 200): never
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store');
    echo json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}
