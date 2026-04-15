<?php
declare(strict_types=1);

final class WPCoreRuntimeTest extends OpenCalendarKit_WPCoreTestCase {
    public function test_local_wordpress_directory_and_plugin_symlink_are_embedded(): void {
        $this->assertTrue(is_dir(OKIT_WORDPRESS_DIR), 'Expected local wordpress/ directory to exist.');
        $this->assertTrue(file_exists(OKIT_WP_CORE_PLUGIN_FILE), 'Expected plugin file inside wordpress/wp-content/plugins.');
        $this->assertSame('open-calendar-kit/open-calendar-kit.php', plugin_basename(OKIT_WP_CORE_PLUGIN_FILE));
        $this->assertTrue(shortcode_exists('openkit_calendar'));
        $this->assertTrue(shortcode_exists('openkit_opening_hours'));
        $this->assertTrue(shortcode_exists('openkit_status_today'));
        $this->assertTrue(shortcode_exists('openkit_event_notice'));
    }

    public function test_activation_and_reactivation_work_via_real_core_hooks(): void {
        do_action('activate_open-calendar-kit/open-calendar-kit.php');

        $this->assertSame(OpenCalendarKit_Admin_OpeningHours::default_hours(), get_option('openkit_opening_hours'));
        $this->assertSame(OpenCalendarKit_Admin_Settings::defaults(), get_option(OpenCalendarKit_Admin_Settings::OPTION_NAME));
        $this->assertTrue(isset($GLOBALS['okit_wp_core_roles']['administrator']->caps[OpenCalendarKit_Plugin::CAP_MANAGE]));

        update_option('openkit_opening_hours_note', 'Persisted note');
        update_option(OpenCalendarKit_Admin_Settings::OPTION_NAME, array_merge(OpenCalendarKit_Admin_Settings::defaults(), ['week_starts_on' => 'sunday']));

        do_action('activate_open-calendar-kit/open-calendar-kit.php');

        $this->assertSame('Persisted note', get_option('openkit_opening_hours_note'));
        $this->assertSame('sunday', get_option(OpenCalendarKit_Admin_Settings::OPTION_NAME)['week_starts_on']);
    }

    public function test_public_shortcodes_render_via_real_do_shortcode_in_core_context(): void {
        $this->setSettings([
            'show_opening_hours_title' => '1',
            'show_status_today' => '1',
            'show_calendar_legend' => '1',
            'week_starts_on' => 'monday',
            'time_format_mode' => '24h',
        ]);
        update_option('openkit_opening_hours_note', 'Core runtime note');
        update_option('openkit_event_notice_enabled', '1');
        update_option('openkit_event_notice_content', 'Core notice');
        okit_wp_core_add_closed_day('2026-06-12', 'Maintenance');

        $opening_hours = do_shortcode('[openkit_opening_hours]');
        $calendar = do_shortcode('[openkit_calendar month="2026-06"]');
        $status = do_shortcode('[openkit_status_today enabled="1"]');
        $notice = do_shortcode('[openkit_event_notice]');

        $this->assertContains('class="bkit-opening-hours"', $opening_hours);
        $this->assertContains('Core runtime note', $opening_hours);
        $this->assertContains('data-month="2026-06"', $calendar);
        $this->assertContains('data-reason="Maintenance"', $calendar);
        $this->assertContains('class="bkit-status-today', $status);
        $this->assertContains('class="bkit-event-notice bkit-ui-callout bkit-ui-callout--notice"', $notice);
    }

    public function test_settings_and_event_notice_apply_in_real_core_context(): void {
        update_option('time_format', 'H.i');
        $this->setSettings([
            'show_status_today' => '0',
            'show_calendar_legend' => '0',
            'week_starts_on' => 'sunday',
            'time_format_mode' => 'site_default',
            'show_opening_hours_title' => '0',
        ]);
        $this->setOpeningHours([
            1 => ['closed' => 0, 'from' => '09:30', 'to' => '18:15'],
        ]);
        update_option('openkit_event_notice_enabled', '0');
        update_option('openkit_event_notice_content', '<strong>Stored</strong>');

        $opening_hours = do_shortcode('[openkit_opening_hours]');
        $calendar = do_shortcode('[openkit_calendar month="2026-06"]');
        $status = do_shortcode('[openkit_status_today]');
        $notice_disabled = do_shortcode('[openkit_event_notice]');

        $this->assertNotContains('<h3>', $opening_hours);
        $this->assertContains('09.30', $opening_hours);
        $this->assertNotContains('class="bkit-legend"', $calendar);
        $this->assertContains('<th class="bkit-cell bkit-wd">Sun</th>', $calendar);
        $this->assertSame('', $status);
        $this->assertSame('', $notice_disabled);

        update_option('openkit_event_notice_enabled', '1');
        $notice_enabled = do_shortcode('[openkit_event_notice]');
        $this->assertContains('<strong>Stored</strong>', $notice_enabled);
    }

    public function test_ajax_like_calendar_request_returns_json_success_in_core_context(): void {
        $_POST = [
            'nonce' => 'nonce-openkit_frontend',
            'month' => '2026-06',
            'show_legend' => '0',
            'week_starts_on' => 'sunday',
            'max_width' => '420px',
        ];

        try {
            $plugin = new OpenCalendarKit_Plugin();
            $plugin->ajax_calendar_month();
            throw new RuntimeException('Expected JSON response exception.');
        } catch (RuntimeException $runtime_exception) {
            $payload = json_decode($runtime_exception->getMessage(), true, 512, JSON_THROW_ON_ERROR);
            $this->assertTrue($payload['success']);
            $this->assertContains('data-month="2026-06"', $payload['data']['html']);
            $this->assertContains('data-week-starts-on="sunday"', $payload['data']['html']);
            $this->assertContains('data-show-legend="0"', $payload['data']['html']);
        }
    }

    public function test_real_core_calendar_month_titles_match_requested_months(): void {
        $march = do_shortcode('[openkit_calendar month="2026-03"]');
        $may = do_shortcode('[openkit_calendar month="2026-05"]');
        $december = do_shortcode('[openkit_calendar month="2026-12"]');

        $this->assertContains('data-month="2026-03"', $march);
        $this->assertContains('March 2026', $march);
        $this->assertContains('data-month="2026-05"', $may);
        $this->assertContains('May 2026', $may);
        $this->assertContains('data-month="2026-12"', $december);
        $this->assertContains('December 2026', $december);
    }

    public function test_real_core_calendar_can_reopen_a_rule_closed_day_with_an_open_exception(): void {
        $this->setOpeningHours([
            1 => ['closed' => 1, 'from' => '', 'to' => ''],
        ]);
        okit_wp_core_add_open_override('2026-04-20');

        $calendar = do_shortcode('[openkit_calendar month="2026-04"]');

        $this->assertContains('April 2026', $calendar);
        $this->assertContains('class="bkit-cell day open" data-date="2026-04-20"', $calendar);
    }

    public function test_site_language_default_and_plugin_override_are_applied_in_core_context(): void {
        $dow = (int) (new DateTime('now', wp_timezone()))->format('N');
        $this->setOpeningHours([
            $dow => ['closed' => 1, 'from' => '', 'to' => ''],
        ]);

        $this->setSiteLocale('de_DE');
        $this->setRuntimeLocale('en_US');
        $this->setSettings([
            'plugin_locale' => OpenCalendarKit_I18n::SITE_DEFAULT,
            'show_status_today' => '1',
            'show_calendar_legend' => '1',
        ]);

        $german_calendar = do_shortcode('[openkit_calendar month="2026-03"]');
        $german_status = do_shortcode('[openkit_status_today enabled="1"]');

        $this->assertContains('März 2026', $german_calendar);
        $this->assertContains('Heute geschlossen', $german_status);

        $this->setRuntimeLocale('de_DE');
        $this->setSettings([
            'plugin_locale' => 'fr_FR',
            'show_status_today' => '1',
            'show_calendar_legend' => '1',
        ]);

        $french_calendar = do_shortcode('[openkit_calendar month="2026-03"]');
        $french_status = do_shortcode('[openkit_status_today enabled="1"]');

        $this->assertContains('mars 2026', mb_strtolower($french_calendar));
        $this->assertContains('Fermé aujourd&#039;hui', $french_status);
    }
}
