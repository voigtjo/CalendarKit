<?php
declare(strict_types=1);

final class FixedNowStatusTodayShortcode extends OpenCalendarKit_Shortcode_StatusToday {
    protected static function get_current_datetime(DateTimeZone $tz): DateTime {
        return new DateTime('2026-05-18 10:30:00', $tz);
    }
}

final class MondayClosedStatusTodayShortcode extends OpenCalendarKit_Shortcode_StatusToday {
    protected static function get_current_datetime(DateTimeZone $tz): DateTime {
        return new DateTime('2026-05-18 12:00:00', $tz);
    }
}

final class MondayExceptionBeforeOpenStatusTodayShortcode extends OpenCalendarKit_Shortcode_StatusToday {
    protected static function get_current_datetime(DateTimeZone $tz): DateTime {
        return new DateTime('2026-05-18 10:00:00', $tz);
    }
}

final class MondayExceptionDuringOpenStatusTodayShortcode extends OpenCalendarKit_Shortcode_StatusToday {
    protected static function get_current_datetime(DateTimeZone $tz): DateTime {
        return new DateTime('2026-05-18 18:00:00', $tz);
    }
}

final class TuesdayBeforeOpenStatusTodayShortcode extends OpenCalendarKit_Shortcode_StatusToday {
    protected static function get_current_datetime(DateTimeZone $tz): DateTime {
        return new DateTime('2026-05-19 10:00:00', $tz);
    }
}

final class TuesdayOpenNowStatusTodayShortcode extends OpenCalendarKit_Shortcode_StatusToday {
    protected static function get_current_datetime(DateTimeZone $tz): DateTime {
        return new DateTime('2026-05-19 18:00:00', $tz);
    }
}

final class TuesdayAfterCloseStatusTodayShortcode extends OpenCalendarKit_Shortcode_StatusToday {
    protected static function get_current_datetime(DateTimeZone $tz): DateTime {
        return new DateTime('2026-05-19 23:55:00', $tz);
    }
}

final class BerlinBeforeOpenStatusTodayShortcode extends OpenCalendarKit_Shortcode_StatusToday {
    protected static function get_current_datetime(DateTimeZone $tz): DateTime {
        return new DateTime('2026-05-19 15:00:00', $tz);
    }
}

final class BerlinDuringOpenStatusTodayShortcode extends OpenCalendarKit_Shortcode_StatusToday {
    protected static function get_current_datetime(DateTimeZone $tz): DateTime {
        return new DateTime('2026-05-19 17:00:00', $tz);
    }
}

final class BerlinAfterCloseStatusTodayShortcode extends OpenCalendarKit_Shortcode_StatusToday {
    protected static function get_current_datetime(DateTimeZone $tz): DateTime {
        return new DateTime('2026-05-19 23:00:00', $tz);
    }
}

final class BerlinClosedDayStatusTodayShortcode extends OpenCalendarKit_Shortcode_StatusToday {
    protected static function get_current_datetime(DateTimeZone $tz): DateTime {
        return new DateTime('2026-05-18 12:00:00', $tz);
    }
}

final class BerlinOpenEndBeforeStatusTodayShortcode extends OpenCalendarKit_Shortcode_StatusToday {
    protected static function get_current_datetime(DateTimeZone $tz): DateTime {
        return new DateTime('2026-05-19 15:00:00', $tz);
    }
}

final class BerlinOpenEndDuringStatusTodayShortcode extends OpenCalendarKit_Shortcode_StatusToday {
    protected static function get_current_datetime(DateTimeZone $tz): DateTime {
        return new DateTime('2026-05-19 17:00:00', $tz);
    }
}

final class FixedNowCalendarShortcode extends OpenCalendarKit_Shortcode_Calendar {
    protected static function get_current_datetime(DateTimeZone $tz): DateTime {
        return new DateTime('2026-03-15 12:00:00', $tz);
    }
}

final class PublicShortcodesTest extends OpenCalendarKit_TestCase {
    private function assertCalendarHeaderAndDataMonth(string $html, string $expectedMonth, string $expectedTitle): void {
        $this->assertContains('data-month="' . $expectedMonth . '"', $html);
        $this->assertContains('<span class="bkit-cal-title">' . $expectedTitle . '</span>', $html);
    }

    private function assertCalendarDoesNotRenderInvalidDays(string $html, int ...$days): void {
        foreach ($days as $day) {
            $this->assertNotContains('<span class="num">' . $day . '</span>', $html);
        }
    }

    private function assertCalendarFirstDayOffset(string $html, int $expectedEmptyCellsBeforeDayOne): void {
        $matched = preg_match('/<tr>(.*?)<span class="num">1<\/span>/s', $html, $matches);
        $this->assertSame(1, $matched);
        preg_match_all('/class="bkit-cell bkit-empty"/', $matches[1], $empty_matches);
        $this->assertSame($expectedEmptyCellsBeforeDayOne, count($empty_matches[0]));
    }

    public function test_status_today_returns_no_output_when_globally_disabled(): void {
        $this->setSettings(['show_status_today' => '0']);
        $this->setHours([
            1 => ['closed' => 0, 'from' => '09:00', 'to' => '18:00'],
        ]);

        $html = FixedNowStatusTodayShortcode::render();

        $this->assertSame('', $html);
    }

    public function test_status_today_shortcode_attribute_can_override_global_setting(): void {
        $this->setSettings([
            'show_status_today' => '0',
            'time_format_mode' => '24h',
        ]);
        $this->setHours([
            1 => ['closed' => 0, 'from' => '09:00', 'to' => '18:00'],
        ]);

        $html = FixedNowStatusTodayShortcode::render(['enabled' => '1']);

        $this->assertContains('Open now until 18:00', $html);
        $this->assertContains('class="bkit-status-today"', $html);
        $this->assertContains('class="bkit-ui-callout bkit-ui-callout--status bkit-ui-callout--open"', $html);
    }

    public function test_status_today_uses_today_closed_for_a_fully_closed_day(): void {
        $this->setSettings(['show_status_today' => '1']);
        $this->setHours([
            1 => ['closed' => 1, 'from' => '', 'to' => ''],
        ]);

        $html = MondayClosedStatusTodayShortcode::render();

        $this->assertContains('Today closed', $html);
        $this->assertContains('class="bkit-status-today"', $html);
        $this->assertContains('class="bkit-ui-callout bkit-ui-callout--status bkit-ui-callout--closed"', $html);
    }

    public function test_status_today_uses_exceptional_opening_before_opening_time(): void {
        $this->setSettings([
            'show_status_today' => '1',
            'time_format_mode' => '24h',
        ]);
        $this->setHours([
            1 => ['closed' => 1, 'from' => '16:30', 'to' => '22:00'],
        ]);
        $this->setOpenOverride('2026-05-18');

        $html = MondayExceptionBeforeOpenStatusTodayShortcode::render();

        $this->assertContains('Opens today at 16:30', $html);
        $this->assertContains('class="bkit-ui-callout bkit-ui-callout--status bkit-ui-callout--upcoming"', $html);
    }

    public function test_status_today_uses_exceptional_opening_during_opening_time(): void {
        $this->setSettings([
            'show_status_today' => '1',
            'time_format_mode' => '24h',
        ]);
        $this->setHours([
            1 => ['closed' => 1, 'from' => '16:30', 'to' => '22:00'],
        ]);
        $this->setOpenOverride('2026-05-18');

        $html = MondayExceptionDuringOpenStatusTodayShortcode::render();

        $this->assertContains('Open now until 22:00', $html);
        $this->assertContains('class="bkit-ui-callout bkit-ui-callout--status bkit-ui-callout--open"', $html);
    }

    public function test_status_today_says_opens_today_before_opening_time(): void {
        $this->setSettings([
            'show_status_today' => '1',
            'time_format_mode' => '24h',
        ]);
        $this->setHours([
            2 => ['closed' => 0, 'from' => '16:30', 'to' => '22:00'],
        ]);

        $html = TuesdayBeforeOpenStatusTodayShortcode::render();

        $this->assertContains('Opens today at 16:30', $html);
        $this->assertContains('class="bkit-status-today"', $html);
        $this->assertContains('class="bkit-ui-callout bkit-ui-callout--status bkit-ui-callout--upcoming"', $html);
    }

    public function test_status_today_says_open_now_until_during_opening_time(): void {
        $this->setSettings([
            'show_status_today' => '1',
            'time_format_mode' => '24h',
        ]);
        $this->setHours([
            2 => ['closed' => 0, 'from' => '16:30', 'to' => '22:00'],
        ]);

        $html = TuesdayOpenNowStatusTodayShortcode::render();

        $this->assertContains('Open now until 22:00', $html);
        $this->assertContains('class="bkit-status-today"', $html);
        $this->assertContains('class="bkit-ui-callout bkit-ui-callout--status bkit-ui-callout--open"', $html);
    }

    public function test_status_today_says_closed_now_after_closing_time(): void {
        $this->setSettings([
            'show_status_today' => '1',
            'time_format_mode' => '24h',
        ]);
        $this->setHours([
            2 => ['closed' => 0, 'from' => '16:30', 'to' => '22:00'],
        ]);

        $html = TuesdayAfterCloseStatusTodayShortcode::render();

        $this->assertContains('Closed now', $html);
        $this->assertContains('class="bkit-status-today"', $html);
        $this->assertContains('class="bkit-ui-callout bkit-ui-callout--status bkit-ui-callout--ended"', $html);
    }

    public function test_status_today_uses_wordpress_timezone_before_opening_in_berlin(): void {
        $this->setSiteTimezone('Europe/Berlin');
        $this->setSettings([
            'show_status_today' => '1',
            'time_format_mode' => '24h',
        ]);
        $this->setHours([
            2 => ['closed' => 0, 'from' => '16:30', 'to' => '22:00'],
        ]);

        $html = BerlinBeforeOpenStatusTodayShortcode::render();

        $this->assertContains('Opens today at 16:30', $html);
    }

    public function test_status_today_uses_wordpress_timezone_during_opening_in_berlin(): void {
        $this->setSiteTimezone('Europe/Berlin');
        $this->setSettings([
            'show_status_today' => '1',
            'time_format_mode' => '24h',
        ]);
        $this->setHours([
            2 => ['closed' => 0, 'from' => '16:30', 'to' => '22:00'],
        ]);

        $html = BerlinDuringOpenStatusTodayShortcode::render();

        $this->assertContains('Open now until 22:00', $html);
    }

    public function test_status_today_uses_wordpress_timezone_after_closing_in_berlin(): void {
        $this->setSiteTimezone('Europe/Berlin');
        $this->setSettings([
            'show_status_today' => '1',
            'time_format_mode' => '24h',
        ]);
        $this->setHours([
            2 => ['closed' => 0, 'from' => '16:30', 'to' => '22:00'],
        ]);

        $html = BerlinAfterCloseStatusTodayShortcode::render();

        $this->assertContains('Closed now', $html);
    }

    public function test_status_today_uses_today_closed_for_closed_day_in_berlin(): void {
        $this->setSiteTimezone('Europe/Berlin');
        $this->setSettings([
            'show_status_today' => '1',
            'time_format_mode' => '24h',
        ]);
        $this->setHours([
            1 => ['closed' => 1, 'from' => '', 'to' => ''],
        ]);

        $html = BerlinClosedDayStatusTodayShortcode::render();

        $this->assertContains('Today closed', $html);
    }

    public function test_status_today_handles_open_end_before_opening_in_berlin(): void {
        $this->setSiteTimezone('Europe/Berlin');
        $this->setSettings([
            'show_status_today' => '1',
            'time_format_mode' => '24h',
        ]);
        $this->setHours([
            2 => ['closed' => 0, 'from' => '16:30', 'to' => 'open end'],
        ]);

        $html = BerlinOpenEndBeforeStatusTodayShortcode::render();

        $this->assertContains('Opens today at 16:30', $html);
    }

    public function test_status_today_handles_open_end_during_opening_in_berlin(): void {
        $this->setSiteTimezone('Europe/Berlin');
        $this->setSettings([
            'show_status_today' => '1',
            'time_format_mode' => '24h',
        ]);
        $this->setHours([
            2 => ['closed' => 0, 'from' => '16:30', 'to' => 'open end'],
        ]);

        $html = BerlinOpenEndDuringStatusTodayShortcode::render();

        $this->assertContains('Open now', $html);
        $this->assertNotContains('Open now until', $html);
    }

    public function test_opening_hours_hides_title_when_global_setting_disables_it(): void {
        $this->setSettings(['show_opening_hours_title' => '0']);

        $html = OpenCalendarKit_Shortcode_OpeningHours::render();

        $this->assertNotContains('<h3>', $html);
        $this->assertContains('class="bkit-opening-hours"', $html);
    }

    public function test_opening_hours_shortcode_attribute_can_override_title_setting(): void {
        $this->setSettings(['show_opening_hours_title' => '0']);

        $html = OpenCalendarKit_Shortcode_OpeningHours::render(['title' => '1']);

        $this->assertContains('<h3>Opening Hours</h3>', $html);
    }

    public function test_opening_hours_uses_requested_time_format_mode(): void {
        $this->setHours([
            1 => ['closed' => 0, 'from' => '18:30', 'to' => '22:15'],
        ]);

        $html = OpenCalendarKit_Shortcode_OpeningHours::render(['time_format_mode' => '12h']);

        $this->assertContains('6:30 PM', $html);
        $this->assertContains('10:15 PM', $html);
    }

    public function test_calendar_hides_legend_when_global_setting_disables_it(): void {
        $this->setSettings([
            'show_calendar_legend' => '0',
            'week_starts_on' => 'monday',
        ]);

        $html = FixedNowCalendarShortcode::render(['month' => '2026-06']);

        $this->assertContains('data-show-legend="0"', $html);
        $this->assertNotContains('class="bkit-legend"', $html);
    }

    public function test_calendar_shortcode_attribute_can_override_legend_setting(): void {
        $this->setSettings(['show_calendar_legend' => '0']);

        $html = FixedNowCalendarShortcode::render([
            'month' => '2026-06',
            'show_legend' => '1',
        ]);

        $this->assertContains('data-show-legend="1"', $html);
        $this->assertContains('class="bkit-legend"', $html);
    }

    public function test_calendar_uses_sunday_week_start_from_settings(): void {
        $this->setSettings(['week_starts_on' => 'sunday']);

        $html = FixedNowCalendarShortcode::render(['month' => '2026-06']);
        preg_match_all('/<th class="bkit-cell bkit-wd">([^<]+)<\/th>/', $html, $matches);

        $this->assertSame('Sun', $matches[1][0] ?? '');
        $this->assertContains('data-week-starts-on="sunday"', $html);
    }

    public function test_calendar_shortcode_attribute_can_override_week_start(): void {
        $this->setSettings(['week_starts_on' => 'sunday']);

        $html = FixedNowCalendarShortcode::render([
            'month' => '2026-06',
            'week_starts_on' => 'monday',
        ]);
        preg_match_all('/<th class="bkit-cell bkit-wd">([^<]+)<\/th>/', $html, $matches);

        $this->assertSame('Mon', $matches[1][0] ?? '');
        $this->assertContains('data-week-starts-on="monday"', $html);
    }

    public function test_event_notice_renders_only_when_enabled_and_content_exists(): void {
        OpenCalendarKit_Admin_EventNotice::$enabled = false;
        OpenCalendarKit_Admin_EventNotice::$content = '<strong>Festival</strong>';
        $this->assertSame('', OpenCalendarKit_Shortcode_EventNotice::render());

        OpenCalendarKit_Admin_EventNotice::$enabled = true;
        $this->assertContains('<strong>Festival</strong>', OpenCalendarKit_Shortcode_EventNotice::render());

        OpenCalendarKit_Admin_EventNotice::$content = '   ';
        $this->assertSame('', OpenCalendarKit_Shortcode_EventNotice::render());
    }

    public function test_event_notice_applies_paragraph_wrapping_to_allowed_content(): void {
        OpenCalendarKit_Admin_EventNotice::$enabled = true;
        OpenCalendarKit_Admin_EventNotice::$content = "First paragraph\n\n<strong>Second paragraph</strong>";

        $html = OpenCalendarKit_Shortcode_EventNotice::render();

        $this->assertContains('<p>First paragraph</p>', $html);
        $this->assertContains('<p><strong>Second paragraph</strong></p>', $html);
    }

    public function test_public_shortcodes_render_expected_wrappers_in_smoke_case(): void {
        $this->setSettings([
            'show_status_today' => '1',
            'show_calendar_legend' => '1',
            'week_starts_on' => 'monday',
            'time_format_mode' => '24h',
            'show_opening_hours_title' => '1',
        ]);
        OpenCalendarKit_Admin_EventNotice::$enabled = true;
        OpenCalendarKit_Admin_EventNotice::$content = 'Holiday opening notice';
        OpenCalendarKit_Admin_OpeningHours::$note = 'Note for visitors';
        $this->setClosedDay('2026-06-12', 'Maintenance');

        $opening_hours = OpenCalendarKit_Shortcode_OpeningHours::render();
        $status_today = FixedNowStatusTodayShortcode::render();
        $calendar = FixedNowCalendarShortcode::render(['month' => '2026-06']);
        $event_notice = OpenCalendarKit_Shortcode_EventNotice::render();

        $this->assertContains('class="bkit-opening-hours"', $opening_hours);
        $this->assertContains('Note for visitors', $opening_hours);
        $this->assertContains('class="bkit-status-today"', $status_today);
        $this->assertContains('class="bkit-ui-callout bkit-ui-callout--status bkit-ui-callout--open"', $status_today);
        $this->assertContains('data-openkit-calendar="1"', $calendar);
        $this->assertContains('data-reason="Maintenance"', $calendar);
        $this->assertContains('class="bkit-event-notice bkit-ui-callout bkit-ui-callout--notice"', $event_notice);
    }

    public function test_calendar_renders_february_2026_without_invalid_days(): void {
        $html = FixedNowCalendarShortcode::render(['month' => '2026-02']);

        $this->assertCalendarHeaderAndDataMonth($html, '2026-02', 'February 2026');
        $this->assertContains('<span class="num">28</span>', $html);
        $this->assertCalendarDoesNotRenderInvalidDays($html, 29, 30, 31);
    }

    public function test_calendar_renders_requested_month_titles_consistently(): void {
        $march = FixedNowCalendarShortcode::render(['month' => '2026-03']);
        $may = FixedNowCalendarShortcode::render(['month' => '2026-05']);
        $december = FixedNowCalendarShortcode::render(['month' => '2026-12']);

        $this->assertCalendarHeaderAndDataMonth($march, '2026-03', 'March 2026');
        $this->assertCalendarHeaderAndDataMonth($may, '2026-05', 'May 2026');
        $this->assertCalendarHeaderAndDataMonth($december, '2026-12', 'December 2026');
    }

    public function test_calendar_april_2026_places_day_one_on_wednesday_with_monday_week_start(): void {
        $html = FixedNowCalendarShortcode::render([
            'month' => '2026-04',
            'week_starts_on' => 'monday',
        ]);

        $this->assertCalendarHeaderAndDataMonth($html, '2026-04', 'April 2026');
        $this->assertCalendarFirstDayOffset($html, 2);
    }

    public function test_calendar_week_start_changes_layout_but_not_month_identity(): void {
        $monday = FixedNowCalendarShortcode::render([
            'month' => '2026-04',
            'week_starts_on' => 'monday',
        ]);
        $sunday = FixedNowCalendarShortcode::render([
            'month' => '2026-04',
            'week_starts_on' => 'sunday',
        ]);

        $this->assertCalendarHeaderAndDataMonth($monday, '2026-04', 'April 2026');
        $this->assertCalendarHeaderAndDataMonth($sunday, '2026-04', 'April 2026');
        $this->assertCalendarFirstDayOffset($monday, 2);
        $this->assertCalendarFirstDayOffset($sunday, 3);
    }

    public function test_calendar_open_override_reopens_a_rule_closed_day(): void {
        $this->setHours([
            1 => ['closed' => 1, 'from' => '', 'to' => ''],
        ]);
        $this->setOpenOverride('2026-04-20');

        $html = FixedNowCalendarShortcode::render(['month' => '2026-04']);

        $this->assertContains('data-month="2026-04"', $html);
        $this->assertMatchesRegularExpression('/class="bkit-cell day open" data-date="2026-04-20"/', $html);
    }
}
