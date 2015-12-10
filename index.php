<?php
session_start();

$sessionFile = 'session.class.php';

if (file_exists($sessionFile) && filesize($sessionFile) !== 0) {
    include $sessionFile;
} else {
    exit('File: '. $sessionFile. 'does not exist or is empty');
}

$session = new Module\Session();

// Create one key which has assigned value
$session -> set(['key' => 'value']);

// Return ID of session
$string = $session -> getSessionId();

// Create more session data from array given as a argument, second param tells that method should create more than one session data
$session -> get(['key1' => 'value1', 'key2' => 'value2', 'key3' => 'value3'], true);

// Check, if key exists in $_SESSION array
$result = $session -> exists('key2');

// Remove one key from $_SESSION array
$session -> removeOne('key3');

// remove all session data
$session -> remove();
