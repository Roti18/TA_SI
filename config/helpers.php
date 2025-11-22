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
    
    $docRoot = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
    $dir = str_replace('\\', '/', __DIR__);

    $projectPath = str_replace($docRoot, '', $dir);

    $projectPath = dirname($projectPath);

    if ($projectPath === '/' || $projectPath === '\\') {
        $projectPath = '';
    }

    return $protocol . "://" . $host . $projectPath . '/';
}