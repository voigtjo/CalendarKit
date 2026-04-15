<?php
declare(strict_types=1);

require_once __DIR__ . '/integration-bootstrap.php';
require_once __DIR__ . '/src/IntegrationTestCase.php';

foreach (glob(__DIR__ . '/integration-cases/*Test.php') ?: [] as $file) {
    require_once $file;
}

$test_classes = array_values(array_filter(
    get_declared_classes(),
    static fn(string $class): bool => is_subclass_of($class, OpenCalendarKit_IntegrationTestCase::class)
));
sort($test_classes);

$failures = [];
$run_count = 0;

foreach ($test_classes as $class) {
    $methods = array_values(array_filter(
        get_class_methods($class),
        static fn(string $method): bool => str_starts_with($method, 'test')
    ));
    sort($methods);

    foreach ($methods as $method) {
        $run_count++;
        $test = new $class();

        try {
            $test->setUp();
            $test->{$method}();
            $test->tearDown();
            echo "PASS {$class}::{$method}\n";
        } catch (Throwable $throwable) {
            $failures[] = [
                'test' => "{$class}::{$method}",
                'message' => $throwable->getMessage(),
            ];
            echo "FAIL {$class}::{$method}\n";
            echo '  ' . $throwable->getMessage() . "\n";
        }
    }
}

echo "\nRan {$run_count} integration tests.\n";

if ($failures !== []) {
    echo count($failures) . " failure(s).\n";
    exit(1);
}

echo "All integration tests passed.\n";
exit(0);
