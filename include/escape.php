<?php
function escapePostParams() {
    $escapedPost = [];

    foreach ($_POST as $key => $value) {
        if (is_array($value)) {
            $escapedPost[$key] = array_map('htmlspecialchars', $value);
        } else {
            $escapedPost[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        }
    }
    return $escapedPost;
}

$_POST = escapePostParams();
?>