<?php
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Return early for root
if ($uri === '/' || $uri === '/index.php') {
    require __DIR__ . '/../index.php';
    exit;
}

// Remove trailing slash
$uri = rtrim($uri, '/');

// Security: Prevent path traversal
$file = realpath(__DIR__ . '/..' . $uri);
$file2 = realpath(__DIR__ . '/..' . $uri . '.php');

$target = false;

// Check if direct php file
if ($file && is_file($file) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
    $target = $file;
} else if ($file2 && is_file($file2)) {
    // Check if php file without extension
    $target = $file2;
} else if ($file && is_dir($file)) {
    // Check if directory has an index.php
    $index = realpath($file . '/index.php');
    if ($index && is_file($index)) {
        $target = $index;
    }
}

// If we found a PHP script to run
if ($target) {
    // Set current working directory for relative includes
    chdir(dirname($target));
    require $target;
} else {
    // Fallback for static assets in case Vercel routes them here
    $static = realpath(__DIR__ . '/..' . $uri);
    if ($static && is_file($static)) {
        $ext = pathinfo($static, PATHINFO_EXTENSION);
        $mimeTypes = [
            'css'  => 'text/css',
            'js'   => 'application/javascript',
            'png'  => 'image/png',
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif'  => 'image/gif',
            'svg'  => 'image/svg+xml',
            'json' => 'application/json',
            'pdf'  => 'application/pdf',
        ];
        if (isset($mimeTypes[$ext])) {
            header('Content-Type: ' . $mimeTypes[$ext]);
            readfile($static);
            exit;
        }
    }

    http_response_code(404);
    echo "404 Not Found - Are you sure this page exists?";
}
