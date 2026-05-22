#!/usr/bin/env php
<?php

// WordPress installation script - reads config from .env file
// and installs WP with the specified admin credentials
require_once __DIR__ . '/../vendor/autoload.php';

use function Env\env;

$root_dir = dirname(__DIR__);
$env_files = file_exists($root_dir . '/.env.local')
    ? ['.env', '.env.local']
    : ['.env'];

$dotenv = Dotenv\Dotenv::createUnsafeImmutable($root_dir, $env_files, false);
if (file_exists($root_dir . '/.env')) {
    $dotenv->load();
}

function wp_generate_password($length = 12) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $chars[random_int(0, strlen($chars) - 1)];
    }
    return $password;
}

$wpTitle = env('WP_TITLE') ?: 'Sperling WP Boilerplate V3';
$wpAdminUser = env('WP_ADMIN_USER') ?: 'sperling';
$wpAdminPassword = env('WP_ADMIN_PASSWORD') ?: wp_generate_password();
$wpAdminEmail = env('WP_ADMIN_EMAIL') ?: 'dev@sperlinginteractive.com';
$wpUrl = env('WP_HOME') ?: 'https://sperling-boilerplate-v3.lndo.site';

echo "\n" . str_repeat('=', 50) . "\n";
echo "WordPress Installation\n";
echo str_repeat('=', 50) . "\n";
echo "Site URL:    {$wpUrl}\n";
echo "Site Title:  {$wpTitle}\n";
echo "Admin User:  {$wpAdminUser}\n";
echo "Admin Email: {$wpAdminEmail}\n";
echo str_repeat('=', 50) . "\n\n";

$wpEnv = env('WP_ENV') ?: 'development';
$hasLandoFile = file_exists($root_dir . '/.lando.yml') || file_exists($root_dir . '/lando.yml');
$isInsideLando = getenv('LANDO') !== false || getenv('LANDO_HOST_IP') !== false;

// Determine WP-CLI command and path
// If already inside Lando container, use 'wp' directly
// If outside Lando but in development with Lando file, use 'lando wp'
// Otherwise use 'wp' directly (for production, staging)
if ($isInsideLando) {
    $wpCliPrefix = 'wp';
    $wpPath = '/app/web/wp';
} elseif ($wpEnv === 'development' && $hasLandoFile) {
    $wpCliPrefix = 'lando wp';
    $wpPath = '/app/web/wp';
} else {
    $wpCliPrefix = 'wp';
    $wpPath = $root_dir . '/web/wp';
}

$checkInstallCmd = "{$wpCliPrefix} core is-installed --path={$wpPath} 2>&1";
exec($checkInstallCmd, $installCheckOutput, $installCheckReturn);

if ($installCheckReturn === 0) {
    echo "WordPress is already installed.\n";
    
    // In production/staging, skip without prompting
    if ($wpEnv !== 'development') {
        echo "Production/Staging environment detected - skipping installation.\n";
        exit(0);
    }
    
    // In local development, prompt user
    echo "Continue anyway? This will update the admin user. (y/n): ";
    $handle = fopen("php://stdin", "r");
    $line = fgets($handle);
    if (trim($line) !== 'y' && trim($line) !== 'Y') {
        echo "Cancelled.\n";
        exit(0);
    }
    fclose($handle);
}

echo "Installing WordPress...\n";
$installCmd = sprintf(
    '%s core install --url=%s --title=%s --admin_user=%s --admin_password=%s --admin_email=%s --path=%s 2>&1',
    $wpCliPrefix,
    escapeshellarg($wpUrl),
    escapeshellarg($wpTitle),
    escapeshellarg($wpAdminUser),
    escapeshellarg($wpAdminPassword),
    escapeshellarg($wpAdminEmail),
    $wpPath
);

exec($installCmd, $installOutput, $installReturn);

if ($installReturn === 0) {
    echo "WordPress installed successfully!\n\n";
    echo "Login details:\n";
    echo "URL:      {$wpUrl}/wp/wp-admin/\n";
    echo "Username: {$wpAdminUser}\n";
    echo "Password: {$wpAdminPassword}\n";
    echo "Email:    {$wpAdminEmail}\n";
    echo "\n";
} else {
    echo "WordPress installation failed.\n";
    echo implode("\n", $installOutput) . "\n";
    exit(1);
}

// Activate all plugins
echo "\nActivating plugins...\n";
$activatePluginsCmd = "{$wpCliPrefix} plugin activate --all --path={$wpPath} --quiet 2>&1";
exec($activatePluginsCmd, $pluginOutput, $pluginReturn);

if ($pluginReturn === 0) {
    echo "Plugins activated successfully!\n";
} else {
    echo "Warning: Some plugins may not have been activated.\n";
}

// Activate theme
$themeName = env('WP_THEME') ?: 'sperling-starter-theme';
echo "\nActivating theme: {$themeName}...\n";
$activateThemeCmd = "{$wpCliPrefix} theme activate {$themeName} --path={$wpPath} --quiet 2>&1";
exec($activateThemeCmd, $themeOutput, $themeReturn);

if ($themeReturn === 0) {
    echo "Theme activated successfully!\n";
} else {
    echo "Warning: Theme activation failed.\n";
}

echo "\n" . str_repeat('=', 50) . "\n";
echo "Installation complete!\n";
echo str_repeat('=', 50) . "\n";

