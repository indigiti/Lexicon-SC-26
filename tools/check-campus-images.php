<?php
declare(strict_types=1);

$root = dirname(__DIR__);
$read = static fn(string $file): array => json_decode((string) file_get_contents($root . '/content/' . $file . '.json'), true, 512, JSON_THROW_ON_ERROR);
$schools = array_column($read('schools'), null, 'slug');
$pages = $read('school-pages');
$galleries = $read('campus-galleries');
$filenames = [
    'wagholi' => 'campus-wagholi-actual.webp',
    'kalyani-nagar' => 'campus-kalyani-actual.webp',
    'hadapsar' => 'campus-hadapsar-actual.webp',
];
$expected = array_map(static fn(string $file): string => '/assets/images/campuses/' . $file, $filenames);
$legacy = '~(?:/assets/images/(?:campuses|home)/campus-(?:wagholi|kalyani|hadapsar)|/assets/images/about/(?:about-story-campus|about-campus[123])|/assets/images/admissions/adm-school[123])\.webp\b~i';

if (count($schools) !== count($expected)) throw new RuntimeException('Expected three campus records');
foreach ($expected as $slug => $photo) {
    if (($schools[$slug]['image'] ?? '') !== $photo || isset($schools[$slug]['cardImage']) || isset($pages[$slug]['image'])) {
        throw new RuntimeException("Campus photo must be defined once for $slug");
    }
    $asset = $root . '/public' . $photo;
    $size = is_file($asset) ? getimagesize($asset) : false;
    if (!$size || $size[0] < 280 || $size[1] < 120 || ($size['mime'] ?? '') !== 'image/webp') {
        throw new RuntimeException("Invalid or missing campus photo for $slug");
    }
    foreach ($galleries[$slug] ?? [] as $item) {
        if (($item['image'] ?? '') === $photo) throw new RuntimeException("Duplicate entrance in gallery for $slug");
    }
}

$front = (string) file_get_contents($root . '/public/index.php');
preg_match_all("~'(/[^']*)'=>\['[a-z-]+','[^']+'\]~", $front, $routes);
$routes = $routes[1];
$mustShowAll = ['/', '/about', '/admissions', '/achievements', '/alumni', '/contact', '/careers',
    '/parent-portal', '/campus-tour', '/virtual-tour', '/apply-now', '/enquire', '/book-a-visit',
    '/transport', '/legal/disclosure'];
foreach ($expected as $slug => $photo) {
    foreach (['', '/transport', '/academics', '/academics/calendar', '/academics/assessment', '/academics/holidays'] as $suffix) {
        $routes[] = '/schools/' . $slug . $suffix;
    }
}
$routes = array_unique($routes);
foreach (array_merge($mustShowAll, array_map(static fn(string $s): string => '/schools/' . $s . '/admissions', array_keys($expected))) as $required) {
    if (!in_array($required, $routes, true)) throw new RuntimeException("Missing photo smoke route: $required");
}

$base = '/lexiconschoolgroup';
$snippet = 'putenv("APP_BASE_PATH=/lexiconschoolgroup"); $_SERVER["REQUEST_URI"] = $argv[1]; $_SERVER["SCRIPT_NAME"] = "/lexiconschoolgroup/index.php"; require $argv[2]; if (http_response_code() >= 400) exit(1);';
foreach ($routes as $route) {
    $output = [];
    exec(escapeshellarg(PHP_BINARY) . ' -r ' . escapeshellarg($snippet) . ' -- ' . escapeshellarg($base . $route) . ' ' . escapeshellarg($root . '/public/index.php'), $output, $status);
    $html = implode("\n", $output);
    if ($status !== 0 || !str_contains($html, '</html>') || preg_match($legacy, $html)) {
        throw new RuntimeException("Route failed to render or still uses a legacy campus photo: $route");
    }
    if (in_array($route, $mustShowAll, true)) {
        foreach ($expected as $photo) {
            if (!str_contains($html, $base . $photo)) throw new RuntimeException("Missing $photo on $route");
        }
    }
    foreach ($expected as $slug => $photo) {
        if ($route === '/schools/' . $slug && substr_count($html, $base . $photo) < 4) {
            throw new RuntimeException("Missing entrance photo in hero, overview, facilities or gallery on $route");
        }
        if ($route === '/schools/' . $slug . '/admissions' && !str_contains($html, $base . $photo)) {
            throw new RuntimeException("Missing campus admissions photo on $route");
        }
    }
}
echo 'Campus photo assets and rendered placements verified across ' . count($routes) . ' routes.' . PHP_EOL;
