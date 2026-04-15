<?php
declare(strict_types=1);

abstract class OpenCalendarKit_WPCoreTestCase {
    public function setUp(): void {
        okit_wp_core_reset_state();
    }

    public function tearDown(): void {
    }

    protected function setSettings(array $overrides): void {
        update_option(
            OpenCalendarKit_Admin_Settings::OPTION_NAME,
            array_merge(OpenCalendarKit_Admin_Settings::defaults(), $overrides)
        );
    }

    protected function setOpeningHours(array $overrides): void {
        update_option(
            OpenCalendarKit_Plugin::OPTION_OPENING_HOURS,
            array_replace(OpenCalendarKit_Admin_OpeningHours::default_hours(), $overrides)
        );
    }

    protected function setSiteLocale(string $locale): void {
        $GLOBALS['okit_wp_core_site_locale'] = $locale;
    }

    protected function setRuntimeLocale(string $locale): void {
        okit_wp_core_set_runtime_locale($locale);
    }

    protected function assertSame($expected, $actual, string $message = ''): void {
        if ($expected !== $actual) {
            throw new RuntimeException($message !== '' ? $message : 'Expected values to match.');
        }
    }

    protected function assertTrue($condition, string $message = ''): void {
        if ($condition !== true) {
            throw new RuntimeException($message !== '' ? $message : 'Expected condition to be true.');
        }
    }

    protected function assertFalse($condition, string $message = ''): void {
        if ($condition !== false) {
            throw new RuntimeException($message !== '' ? $message : 'Expected condition to be false.');
        }
    }

    protected function assertContains(string $needle, string $haystack, string $message = ''): void {
        if (strpos($haystack, $needle) === false) {
            throw new RuntimeException($message !== '' ? $message : sprintf('Expected output to contain "%s".', $needle));
        }
    }

    protected function assertNotContains(string $needle, string $haystack, string $message = ''): void {
        if (strpos($haystack, $needle) !== false) {
            throw new RuntimeException($message !== '' ? $message : sprintf('Expected output not to contain "%s".', $needle));
        }
    }
}
