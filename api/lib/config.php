<?php
/**
 * Configuration file for Jyotish API
 * 
 * Uses environment variables with fallbacks for configuration
 */

// Keep PHP path defaults aligned with the entrypoint when variables are unset or empty.
define('SWETEST_PATH', empty($_ENV['SWETEST_PATH']) ? '/var/www/api/swetest/src' : $_ENV['SWETEST_PATH']);
define('SWEPH_PATH', empty($_ENV['SWEPH_PATH']) ? '/var/www/api/swetest/sweph' : $_ENV['SWEPH_PATH']);
