<?php
declare(strict_types=1);
$release = dirname(__DIR__) . '/release';
$required = [
    'RELEASE.json',
    'public/index.php',
    'public/.htaccess',
    'public/router.php',
    'public/build/assets/app.css',
    'public/build/assets/app.js',
    'private/app/bootstrap.php',
    'private/app/Support/helpers.php',
    'private/content/schools.json',
    'private/storage/.gitkeep',
];
foreach ($required as $relative) {
    if (!is_file($release . '/' . $relative)) {
        fwrite(STDERR, "Missing release file: $relative\n");
        exit(1);
    }
}
foreach (['app', 'content', 'storage', '.git', '.env'] as $forbidden) {
    if (file_exists($release . '/public/' . $forbidden)) {
        fwrite(STDERR, "Private resource copied to web root: $forbidden\n");
        exit(1);
    }
}
$index = (string) file_get_contents($release . '/public/index.php');
if (!str_contains($index, "private_html/lexiconschoolgroup")
    || str_contains($index, "require dirname(__DIR__) . '/app/bootstrap.php';")) {
    fwrite(STDERR, "Missing DigiOps private-root bootstrap bridge\n");
    exit(1);
}
if (!str_contains((string)file_get_contents($release . '/public/.htaccess'), 'RewriteRule ^ index.php')) {
    fwrite(STDERR, "Expected Apache rewrite route missing\n");
    exit(1);
}
$meta = json_decode((string) file_get_contents($release . '/RELEASE.json'), true, 512, JSON_THROW_ON_ERROR);
if (($meta['name'] ?? '') !== 'LexiconSchoolGroup' || ($meta['path'] ?? '') !== '/lexiconschoolgroup/'
    || ($meta['public'] ?? '') !== 'public' || ($meta['private'] ?? '') !== 'private') {
    fwrite(STDERR, "Invalid artifact metadata\n");
    exit(1);
}
echo "DigiOps release shape and private/public boundaries verified.\n";
