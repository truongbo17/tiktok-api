<?php

if (!function_exists('build_external_url')) {
    /**
     * Build url from hot , path , query,....
     *
     * @param string $host
     * @param string|null $path
     * @param array $query
     * @param string|null $schema
     * @param int|null $port
     * @return string
     */
    function build_external_url(string $host, string $path = null, array $query = [], string $schema = null, int $port = null): string
    {
        $url = $host;
        if (null !== $port) {
            $url .= ':' . $port;
        }
        if (null !== $path) {
            $url .= '/' . ltrim($path, '/');
        }
        if (!empty($query)) {
            $url .= '?' . http_build_query($query);
        }
        return (null === $schema ? $url : ($schema . '://' . $url));
    }
}
