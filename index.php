<?php
session_start();

$file = 'session.class.php';

if (file_exists($file) && filesize($file) !== 0) {
    include $file;
} else {
    exit('File: '. $file. 'does not exist or is empty');
}

$session = new \Module\Session();

// Create one key which has assigned value
$session -> set(['key' => 'value', 'key2' => 'value2']);

// Return ID of session
$string = $session -> getSessionId();

// Create more session data from array given as a argument
$session -> set(['key11' => 'value11', 'key22' => 'value22', 'key33' => 'value33']);

// Check, if key exists in $_SESSION array
$result = $session -> exists('key2');

// Remove one key from $_SESSION array
$session -> removeOne('key2');

// remove all session data
$session -> remove();

