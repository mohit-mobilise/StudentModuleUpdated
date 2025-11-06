<?php
/**
 * Security HTTP Headers
 * Include this file at the top of all pages to add security headers
 */

// Prevent caching of sensitive pages
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

// X-Frame-Options: Prevent clickjacking
header('X-Frame-Options: DENY');

// X-Content-Type-Options: Prevent MIME type sniffing
header('X-Content-Type-Options: nosniff');

// X-XSS-Protection: Enable XSS filtering
header('X-XSS-Protection: 1; mode=block');

// Referrer-Policy: Control referrer information
header('Referrer-Policy: strict-origin-when-cross-origin');

// Content-Security-Policy: Restrict resource loading
// Adjust based on your application's needs
$csp = "default-src 'self'; " .
       "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdnjs.cloudflare.com https://ajax.googleapis.com https://maxcdn.bootstrapcdn.com; " .
       "style-src 'self' 'unsafe-inline' https://maxcdn.bootstrapcdn.com https://cdnjs.cloudflare.com; " .
       "img-src 'self' data: https:; " .
       "font-src 'self' https://cdnjs.cloudflare.com https://maxcdn.bootstrapcdn.com; " .
       "connect-src 'self'; " .
       "frame-ancestors 'none';";

header("Content-Security-Policy: $csp");

// Strict-Transport-Security: Force HTTPS (only if using HTTPS)
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
}

// Permissions-Policy: Restrict browser features
header('Permissions-Policy: geolocation=(), microphone=(), camera=()');

?>



