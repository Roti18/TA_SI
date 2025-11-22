<?php

function redirect($page)
{
    header("Location: index.php?page=" . $page);
    exit;
}

function route($name) {
    return "index.php?page=" . $name;
}

function getBaseUrl() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    
    // Replace backslashes with forward slashes for consistency
    $docRoot = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
    $dir = str_replace('\\', '/', __DIR__);

    // Get the part of the path that is relative to the document root
    $projectPath = str_replace($docRoot, '', $dir);

    // Go one level up from the 'config' directory
    $projectPath = dirname($projectPath);

    // Ensure it's not just a slash if the project is in the root
    if ($projectPath === '/' || $projectPath === '\\') {
        $projectPath = '';
    }

    return $protocol . "://" . $host . $projectPath . '/';
}

