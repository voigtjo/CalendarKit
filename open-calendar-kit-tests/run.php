<?php
declare(strict_types=1);

$suites = [
    'Unit' => __DIR__ . '/run-unit.php',
    'Integration' => __DIR__ . '/run-integration.php',
    'WordPress Core' => __DIR__ . '/run-wp-core.php',
];

$php = escapeshellarg(PHP_BINARY);
$failed = false;

foreach ($suites as $label => $file) {
    echo "=== {$label} Suite ===\n";
    passthru($php . ' ' . escapeshellarg($file), $exit_code);
    echo "\n";

    if ($exit_code !== 0) {
        $failed = true;
    }
}

exit($failed ? 1 : 0);
