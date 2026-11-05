<?php
declare(strict_types=1);

/**
 * Build DigiOps split-webroot artifact for the Lexicon group staging route.
 *
 * Artifact shape (upload release/ contents as digiops-release):
 * RELEASE.json
 * public/index.php, public/.htaccess, public/assets/, public/build/
 * private/app/, private/content/, private/storage/.gitkeep
 *
 * No enquiry records, credentials or other runtime state are packaged.
 */
$root = dirname(__DIR__);
$out = $root . '/release';
$slug = 'lexiconschoolgroup';

function clearPackageDirectory(string $directory): void
{
    if (!file_exists($directory)) return;
    if (is_link($directory)) throw new RuntimeException('Symlink in output directory');
    if (is_dir($directory)) {
        foreach (array_diff(scandir($directory) ?: [], ['.', '..']) as $item) {
            clearPackageDirectory($directory . '/' . $item);
        }
        if (!rmdir($directory)) throw new RuntimeException('Unable to delete directory: ' . $directory);
    } elseif (!unlink($directory)) {
        throw new RuntimeException('Unable to delete file: ' . $directory);
    }
}
function copyPackageDirectory(string $source, string $destination): void
{
    if (is_link($source)) throw new RuntimeException('Refusing symlink: ' . $source);
    if (!is_dir($source)) throw new RuntimeException('Missing source directory: ' . $source);
    if (!is_dir($destination) && !mkdir($destination, 0755, true)) throw new RuntimeException('mkdir failed: ' . $destination);
    foreach (array_diff(scandir($source) ?: [], ['.', '..']) as $entry) {
        $src = $source . '/' . $entry;
        $dst = $destination . '/' . $entry;
        if (is_link($src)) throw new RuntimeException('Refusing symlink: ' . $src);
        if (is_dir($src)) copyPackageDirectory($src, $dst);
        elseif (!is_file($src) || !copy($src, $dst)) throw new RuntimeException('Copy failed: ' . $src);
    }
}
try {
    if (!is_file($root . '/public/index.php') || !is_dir($root . '/public/assets') || !is_dir($root . '/public/build')) {
        throw new RuntimeException('Required public website files missing');
    }
    if (is_dir($out)) clearPackageDirectory($out);
    copyPackageDirectory($root . '/public', $out . '/public');
    copyPackageDirectory($root . '/app', $out . '/private/app');
    copyPackageDirectory($root . '/content', $out . '/private/content');

    // The relative bridge works when both folders are beneath the same Cloudways application:
    // <app>/public_html/lexiconschoolgroup and <app>/private_html/lexiconschoolgroup.
    $original = (string)file_get_contents($out . '/public/index.php');
    $old = "require dirname(__DIR__) . '/app/bootstrap.php';";
    if (substr_count($original, $old) !== 1) throw new RuntimeException('Unexpected canonical bootstrap; release bridge not generated');
    $new = <<<'PHP'
$cloudwaysRoot = dirname(__DIR__, 2);
$privateRoot = $cloudwaysRoot . '/private_html/lexiconschoolgroup';
if (!is_file($privateRoot . '/app/bootstrap.php')) {
    http_response_code(503);
    header('Content-Type: text/plain; charset=utf-8');
    exit('Lexicon site is temporarily unavailable.');
}
require $privateRoot . '/app/bootstrap.php';
PHP;
    $replacement = str_replace($old, $new, $original);
    if (file_put_contents($out . '/public/index.php', $replacement) === false) throw new RuntimeException('Public bridge cannot be written');
    $privateStorage = $out . '/private/storage';
    if (!is_dir($privateStorage) && !mkdir($privateStorage, 0750, true)) throw new RuntimeException('Cannot prepare storage');
    if (file_put_contents($privateStorage . '/.gitkeep', '') === false) throw new RuntimeException('Storage marker failed');

    $release = [
        'schema' => 'DIGIOPS-BUILD/1',
        'name' => 'LexiconSchoolGroup',
        'version' => '0.4.0-staging-review',
        'path' => '/lexiconschoolgroup/',
        'public' => 'public',
        'private' => 'private',
        'branch' => getenv('GITHUB_REF_NAME') ?: 'local',
        'sourceSha' => getenv('GITHUB_SHA') ?: 'local',
        'ciRunId' => getenv('GITHUB_RUN_ID') ?: '',
        'ciRunAttempt' => getenv('GITHUB_RUN_ATTEMPT') ?: '',
        'builtAt' => gmdate('c'),
    ];
    if (file_put_contents($out . '/RELEASE.json', json_encode($release, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n") === false) {
        throw new RuntimeException('Metadata write failed');
    }
    echo 'DigiOps release prepared: public assets + private PHP, content and storage directory.' . PHP_EOL;
} catch (Throwable $e) {
    fwrite(STDERR, 'PACKAGE_FAILED: ' . $e->getMessage() . PHP_EOL);
    exit(1);
}
