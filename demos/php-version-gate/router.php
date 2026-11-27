<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

require __DIR__ . '/policy.php';

header("Content-Security-Policy: default-src 'none'; style-src 'self'; script-src 'self'; "
    . "connect-src 'self'; img-src 'self'; base-uri 'none'; form-action 'none'; "
    . "frame-ancestors 'self' https://partner-workshops.devinenterprise.com");
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: no-referrer');
header('Cache-Control: no-store');
header('Permissions-Policy: camera=(), microphone=(), geolocation=()');

/**
 * Send a JSON response and end the request.
 *
 * @param array $body
 * @param int $status
 * @return never
 */
function respondJson(array $body, int $status = 200): never
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($body, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), "\n";
    exit;
}

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

if ($path === '/api/runtime') {
    if ($method !== 'GET') {
        header('Allow: GET');
        respondJson(['error' => 'Use GET.'], 405);
    }
    respondJson([
        'simulation' => true,
        'phpVersion' => PHP_VERSION,
        'sapi' => PHP_SAPI,
        'presets' => \VersionGateDemo\PRESETS,
        'storage' => 'none',
        'outboundRequests' => 'none',
    ]);
}

if ($path === '/api/compare') {
    if ($method !== 'POST') {
        header('Allow: POST');
        respondJson(['error' => 'Use POST.'], 405);
    }
    if (($_SERVER['HTTP_SEC_FETCH_SITE'] ?? '') === 'cross-site') {
        respondJson(['error' => 'Cross-site requests are not accepted.'], 403);
    }
    if (strtolower(trim(explode(';', $_SERVER['CONTENT_TYPE'] ?? '')[0])) !== 'application/json') {
        respondJson(['error' => 'Send application/json.'], 415);
    }
    if ((int) ($_SERVER['CONTENT_LENGTH'] ?? 0) > 1024) {
        respondJson(['error' => 'Request is too large.'], 413);
    }
    $rawBody = file_get_contents('php://input', false, null, 0, 1025);
    if (strlen($rawBody) > 1024) {
        respondJson(['error' => 'Request is too large.'], 413);
    }
    try {
        $input = json_decode($rawBody, true, 8, JSON_THROW_ON_ERROR);
    } catch (\JsonException) {
        respondJson(['error' => 'Send a valid JSON object.'], 400);
    }
    if (!is_array($input) || array_keys($input) !== ['version'] || !is_string($input['version'])) {
        respondJson(['error' => 'Provide only a version string.'], 400);
    }
    try {
        respondJson(\VersionGateDemo\comparison($input['version']));
    } catch (\InvalidArgumentException) {
        respondJson(['error' => 'Choose a listed version.'], 400);
    }
}

$assets = [
    '/' => ['index.html', 'text/html; charset=utf-8'],
    '/app.js' => ['app.js', 'text/javascript; charset=utf-8'],
    '/style.css' => ['style.css', 'text/css; charset=utf-8'],
];
if (!isset($assets[$path])) {
    respondJson(['error' => 'Not found.'], 404);
}
if (!in_array($method, ['GET', 'HEAD'], true)) {
    header('Allow: GET, HEAD');
    respondJson(['error' => 'Use GET or HEAD.'], 405);
}
[$file, $contentType] = $assets[$path];
header('Content-Type: ' . $contentType);
if ($method !== 'HEAD') {
    readfile(__DIR__ . '/public/' . $file);
}
