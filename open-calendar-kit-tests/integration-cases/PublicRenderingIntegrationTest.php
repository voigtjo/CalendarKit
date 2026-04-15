<?php
declare(strict_types=1);

final class PublicRenderingIntegrationTest extends OpenCalendarKit_IntegrationTestCase {
    public function test_registered_status_today_respects_global_setting_and_time_format(): void {
        $today = new DateTime('now', wp_timezone());
        $dow = (int) $today->format('N');

        $this->setOpeningHours([
            $dow => ['closed' => 0, 'from' => '00:00', 'to' => '23:59'],
        ]);
        $this->setSettings([
            'show_status_today' => '0',
            'time_format_mode' => '12h',
        ]);

        $this->assertSame('', okit_integration_render_shortcode('openkit_status_today'));

        $html = okit_integration_render_shortcode('openkit_status_today', ['enabled' => '1']);
        $this->assertContains('Open now until 11:59 PM', $html);
    }

    public function test_registered_status_today_uses_exceptional_opening_for_a_rule_closed_day(): void {
        $today = new DateTime('now', wp_timezone());
        $dow = (int) $today->format('N');
        $ymd = $today->format('Y-m-d');

        $this->setSettings([
            'show_status_today' => '1',
            'time_format_mode' => '24h',
        ]);
        $this->setOpeningHours([
            $dow => ['closed' => 1, 'from' => '00:00', 'to' => '23:59'],
        ]);
        okit_integration_add_open_override($ymd);

        $html = okit_integration_render_shortcode('openkit_status_today');

        $this->assertContains('Open now until 23:59', $html);
    }

    public function test_registered_opening_hours_uses_global_title_setting_and_site_time_format(): void {
        update_option('time_format', 'H.i');
        $this->setSettings([
            'show_opening_hours_title' => '0',
            'time_format_mode' => 'site_default',
        ]);
        $this->setOpeningHours([
            1 => ['closed' => 0, 'from' => '09:30', 'to' => '18:15'],
        ]);
        update_option('openkit_opening_hours_note', 'Integration note');

        $html = okit_integration_render_shortcode('openkit_opening_hours');

        $this->assertNotContains('<h3>', $html);
        $this->assertContains('09.30', $html);
        $this->assertContains('18.15', $html);
        $this->assertContains('Integration note', $html);
    }

    public function test_registered_calendar_uses_global_settings_for_legend_and_week_start(): void {
        $this->setSettings([
            'show_calendar_legend' => '0',
            'week_starts_on' => 'sunday',
        ]);

        $html = okit_integration_render_shortcode('openkit_calendar', ['month' => '2026-06']);

        $this->assertContains('data-month="2026-06"', $html);
        $this->assertContains('June 2026', $html);
        $this->assertContains('data-week-starts-on="sunday"', $html);
        $this->assertContains('<th class="bkit-cell bkit-wd">Sun</th>', $html);
        $this->assertNotContains('class="bkit-legend"', $html);
    }

    public function test_registered_calendar_shortcode_attributes_override_global_settings(): void {
        $this->setSettings([
            'show_calendar_legend' => '0',
            'week_starts_on' => 'sunday',
        ]);
        okit_integration_add_closed_day('2026-06-12', 'Maintenance');

        $html = okit_integration_render_shortcode('openkit_calendar', [
            'month' => '2026-06',
            'show_legend' => '1',
            'week_starts_on' => 'monday',
        ]);

        $this->assertContains('data-show-legend="1"', $html);
        $this->assertContains('data-week-starts-on="monday"', $html);
        $this->assertContains('<th class="bkit-cell bkit-wd">Mon</th>', $html);
        $this->assertContains('data-reason="Maintenance"', $html);
        $this->assertContains('class="bkit-legend"', $html);
    }

    public function test_event_notice_content_is_retained_while_disabled(): void {
        $this->setEventNotice(false, '<em>Saved notice</em>');

        $this->assertSame('', okit_integration_render_shortcode('openkit_event_notice'));
        $this->assertSame('<em>Saved notice</em>', OpenCalendarKit_Admin_EventNotice::get_content());

        update_option('openkit_event_notice_enabled', '1');
        $this->assertContains('<em>Saved notice</em>', okit_integration_render_shortcode('openkit_event_notice'));
    }

    public function test_registered_calendar_keeps_title_and_grid_on_same_requested_month(): void {
        $march = okit_integration_render_shortcode('openkit_calendar', ['month' => '2026-03']);
        $may = okit_integration_render_shortcode('openkit_calendar', ['month' => '2026-05']);
        $december = okit_integration_render_shortcode('openkit_calendar', ['month' => '2026-12']);

        $this->assertContains('data-month="2026-03"', $march);
        $this->assertContains('March 2026', $march);
        $this->assertContains('data-month="2026-05"', $may);
        $this->assertContains('May 2026', $may);
        $this->assertContains('data-month="2026-12"', $december);
        $this->assertContains('December 2026', $december);
    }

    public function test_registered_calendar_february_2026_has_no_day_30_or_31(): void {
        $html = okit_integration_render_shortcode('openkit_calendar', ['month' => '2026-02']);

        $this->assertContains('February 2026', $html);
        $this->assertContains('<span class="num">28</span>', $html);
        $this->assertNotContains('<span class="num">30</span>', $html);
        $this->assertNotContains('<span class="num">31</span>', $html);
    }

    public function test_registered_calendar_can_reopen_a_rule_closed_day_with_an_open_exception(): void {
        $this->setOpeningHours([
            1 => ['closed' => 1, 'from' => '', 'to' => ''],
        ]);
        okit_integration_add_open_override('2026-04-20');

        $html = okit_integration_render_shortcode('openkit_calendar', ['month' => '2026-04']);

        $this->assertContains('April 2026', $html);
        $this->assertContains('class="bkit-cell day open" data-date="2026-04-20"', $html);
    }

    public function test_admin_calendar_renders_real_day_numbers_and_open_override_attribute(): void {
        $this->setOpeningHours([
            1 => ['closed' => 1, 'from' => '', 'to' => ''],
        ]);
        okit_integration_add_open_override('2026-04-20');
        $_POST = [
            'nonce' => 'nonce-openkit_admin',
            'month' => '2026-04',
        ];

        try {
            OpenCalendarKit_Admin_ClosedDays::ajax_render_calendar_month();
            throw new RuntimeException('Expected ajax_render_calendar_month to exit via wp_send_json_success.');
        } catch (RuntimeException $exception) {
            $payload = json_decode($exception->getMessage(), true);
            $html = $payload['data']['html'] ?? '';
        }

        $this->assertContains('<span class="num">20</span>', $html);
        $this->assertContains('data-open-override="1"', $html);
    }

    public function test_site_language_default_is_used_for_outputs_and_js_localization(): void {
        $dow = (int) (new DateTime('now', wp_timezone()))->format('N');
        $this->setSiteLocale('de_DE');
        $this->setRuntimeLocale('en_US');
        $this->setSettings([
            'plugin_locale' => OpenCalendarKit_I18n::SITE_DEFAULT,
            'show_status_today' => '1',
            'show_calendar_legend' => '1',
        ]);
        $this->setOpeningHours([
            $dow => ['closed' => 1, 'from' => '', 'to' => ''],
        ]);

        $opening = okit_integration_render_shortcode('openkit_opening_hours');
        $status = okit_integration_render_shortcode('openkit_status_today', ['enabled' => '1']);
        $calendar = okit_integration_render_shortcode('openkit_calendar', ['month' => '2026-03']);

        $plugin = new OpenCalendarKit_Plugin();
        $plugin->enqueue_assets();

        $this->assertContains('Öffnungszeiten', $opening);
        $this->assertContains('Heute geschlossen', $status);
        $this->assertContains('März 2026', $calendar);
        $this->assertSame('de-DE', $GLOBALS['okit_integration_localized_scripts']['open-calendar-kit']['l10n']['locale']);
        $this->assertSame('Grund:', $GLOBALS['okit_integration_localized_scripts']['open-calendar-kit']['l10n']['reason_label']);
    }

    public function test_plugin_locale_override_is_used_for_outputs_and_js_localization(): void {
        $dow = (int) (new DateTime('now', wp_timezone()))->format('N');
        $this->setSiteLocale('de_DE');
        $this->setRuntimeLocale('de_DE');
        $this->setSettings([
            'plugin_locale' => 'fr_FR',
            'show_status_today' => '1',
            'show_calendar_legend' => '1',
        ]);
        $this->setOpeningHours([
            $dow => ['closed' => 1, 'from' => '', 'to' => ''],
        ]);

        $opening = okit_integration_render_shortcode('openkit_opening_hours');
        $status = okit_integration_render_shortcode('openkit_status_today', ['enabled' => '1']);
        $calendar = okit_integration_render_shortcode('openkit_calendar', ['month' => '2026-03']);

        $plugin = new OpenCalendarKit_Plugin();
        $plugin->enqueue_assets();

        $this->assertContains('Horaires d&#039;ouverture', $opening);
        $this->assertContains('Fermé aujourd&#039;hui', $status);
        $this->assertContains('mars 2026', mb_strtolower($calendar));
        $this->assertSame('fr-FR', $GLOBALS['okit_integration_localized_scripts']['open-calendar-kit']['l10n']['locale']);
        $this->assertSame('Raison :', $GLOBALS['okit_integration_localized_scripts']['open-calendar-kit']['l10n']['reason_label']);
    }
}
