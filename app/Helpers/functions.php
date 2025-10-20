<?php
function isActive(string $path, string $currentUrl, bool $exact = false): string
{
    if ($exact) {
        return $currentUrl === $path ? 'active' : '';
    }
    return strpos($currentUrl, $path) === 0 ? 'active' : '';
}
