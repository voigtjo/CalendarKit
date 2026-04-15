<?php
declare(strict_types=1);

final class SettingsTest extends OpenCalendarKit_TestCase {
    public function test_defaults_match_public_1_0_expectations(): void {
        $this->assertSame(
            [
                'show_status_today' => '1',
                'show_calendar_legend' => '1',
                'week_starts_on' => 'monday',
                'time_format_mode' => 'site_default',
                'show_opening_hours_title' => '1',
                'plugin_locale' => OpenCalendarKit_I18n::SITE_DEFAULT,
            ],
            OpenCalendarKit_Admin_Settings::defaults()
        );
    }

    public function test_get_settings_uses_defaults_when_option_is_missing(): void {
        $this->assertSame(OpenCalendarKit_Admin_Settings::defaults(), OpenCalendarKit_Admin_Settings::get_settings());
    }

    public function test_get_settings_merges_partial_values_with_defaults(): void {
        update_option(OpenCalendarKit_Admin_Settings::OPTION_NAME, [
            'show_status_today' => '0',
            'week_starts_on' => 'sunday',
        ]);

        $settings = OpenCalendarKit_Admin_Settings::get_settings();

        $this->assertSame('0', $settings['show_status_today']);
        $this->assertSame('1', $settings['show_calendar_legend']);
        $this->assertSame('sunday', $settings['week_starts_on']);
        $this->assertSame('site_default', $settings['time_format_mode']);
        $this->assertSame('1', $settings['show_opening_hours_title']);
        $this->assertSame(OpenCalendarKit_I18n::SITE_DEFAULT, $settings['plugin_locale']);
    }

    public function test_sanitize_settings_normalizes_invalid_values_and_booleans(): void {
        $sanitized = OpenCalendarKit_Admin_Settings::sanitize_settings([
            'show_status_today' => '',
            'show_calendar_legend' => '1',
            'week_starts_on' => 'friday',
            'time_format_mode' => 'iso8601',
            'show_opening_hours_title' => 'on',
            'plugin_locale' => 'es_ES',
        ]);

        $this->assertSame(
            [
                'show_status_today' => '0',
                'show_calendar_legend' => '1',
                'week_starts_on' => 'monday',
                'time_format_mode' => 'site_default',
                'show_opening_hours_title' => '1',
                'plugin_locale' => OpenCalendarKit_I18n::SITE_DEFAULT,
            ],
            $sanitized
        );
    }

    public function test_time_format_mode_uses_site_default_or_explicit_override(): void {
        update_option('time_format', 'H.i');
        $this->setSettings(['time_format_mode' => 'site_default']);

        $this->assertSame('H.i', OpenCalendarKit_Admin_Settings::get_time_format());
        $this->assertSame('H:i', OpenCalendarKit_Admin_Settings::get_time_format('24h'));
        $this->assertSame('g:i A', OpenCalendarKit_Admin_Settings::get_time_format('12h'));
        $this->assertSame('18.30', OpenCalendarKit_Admin_Settings::format_time_value('18:30'));
        $this->assertSame('6:30 PM', OpenCalendarKit_Admin_Settings::format_time_value('18:30', '12h'));
    }
}
