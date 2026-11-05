<?php
declare(strict_types=1);
final class ContentRepository {
    private array $cache = [];
    public function get(string $name): array {
        if (isset($this->cache[$name])) return $this->cache[$name];
        $path = BASE_PATH . '/content/' . basename($name) . '.json';
        if (!is_file($path)) return [];
        $decoded = json_decode((string) file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);
        return $this->cache[$name] = is_array($decoded) ? $decoded : [];
    }
}
