<?php
/**
 * Configuration file for Jyotish API
 * 
 * Uses environment variables with fallbacks for configuration
 */

// Keep PHP path defaults aligned with the entrypoint when variables are unset or empty.
$swetestPath = getenv('SWETEST_PATH');
if ($swetestPath === false || $swetestPath === '') {
    $swetestPath = $_ENV['SWETEST_PATH'] ?? $_SERVER['SWETEST_PATH'] ?? '';
}

$swephPath = getenv('SWEPH_PATH');
if ($swephPath === false || $swephPath === '') {
    $swephPath = $_ENV['SWEPH_PATH'] ?? $_SERVER['SWEPH_PATH'] ?? '';
}

define('SWETEST_PATH', $swetestPath !== '' ? $swetestPath : '/var/www/api/swetest/src');
define('SWEPH_PATH', $swephPath !== '' ? $swephPath : '/var/www/api/swetest/sweph');

unset($swetestPath, $swephPath);
