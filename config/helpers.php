<?php

function redirect($page)
{
    header("Location: index.php?page=" . $page);
    exit;
}

function route($name) {
    return "index.php?page=" . $name;
}
