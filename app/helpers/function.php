<?php

if (!function_exists('uniqueKey')) {
    function uniqueKey() {
        $timestamp = time(); // Get the current timestamp with microseconds
        $uniqueId = uniqid('', true); // Generate a unique identifier

        $uniqueKey = $timestamp . $uniqueId;
        return substr($uniqueKey, 0, 32); // Outputs a 32-character unique key with timestamp and uniqid
    }
}
