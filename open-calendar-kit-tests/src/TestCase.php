<?php
declare(strict_types=1);

abstract class OpenCalendarKit_TestCase {
    public function setUp(): void {
        okit_test_reset_state();
    }

    public function tearDown(): void {
    }

    protected function setSettings(array $overrides): void {
        update_option(
            OpenCalendarKit_Admin_Settings::OPTION_NAME,
            array_merge(OpenCalendarKit_Admin_Settings::defaults(), $overrides)
        );
    }

    protected function setHours(array $hours): void {
        OpenCalendarKit_Admin_OpeningHours::$hours = array_replace(OpenCalendarKit_Admin_OpeningHours::default_hours(), $hours);
    }

    protected function setClosedDay(string $date, string $reason = ''): void {
        if (!in_array($date, OpenCalendarKit_Admin_ClosedDays::$closed_dates, true)) {
            OpenCalendarKit_Admin_ClosedDays::$closed_dates[] = $date;
        }
        OpenCalendarKit_Admin_ClosedDays::$reasons[$date] = $reason;
    }

    protected function setOpenOverride(string $date): void {
        if (!in_array($date, OpenCalendarKit_Admin_ClosedDays::$open_overrides, true)) {
            OpenCalendarKit_Admin_ClosedDays::$open_overrides[] = $date;
        }
    }

    protected function setSiteLocale(string $locale): void {
        $GLOBALS['okit_test_site_locale'] = $locale;
    }

    protected function setRuntimeLocale(string $locale): void {
        okit_test_set_runtime_locale($locale);
    }

    protected function setSiteTimezone(string $timezone): void {
        update_option('timezone_string', $timezone);
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

    protected function assertMatchesRegularExpression(string $pattern, string $value, string $message = ''): void {
        if (!preg_match($pattern, $value)) {
            throw new RuntimeException($message !== '' ? $message : sprintf('Expected value to match pattern "%s".', $pattern));
        }
    }
}
