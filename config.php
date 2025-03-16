<?php
function getApiKey() {
    $env = parse_ini_file(__DIR__ . '/.env');
    return $env['API_KEY'] ?? 'API_KEY_TIDAK_DITEMUKAN';
}
