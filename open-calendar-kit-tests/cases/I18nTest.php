<?php
declare(strict_types=1);

final class I18nTest extends OpenCalendarKit_TestCase {
    public function test_default_plugin_locale_uses_wordpress_site_language(): void {
        $this->setSiteLocale('de_DE');
        $this->setRuntimeLocale('en_US');
        $this->setSettings(['plugin_locale' => OpenCalendarKit_I18n::SITE_DEFAULT]);

        $this->assertSame('de_DE', OpenCalendarKit_I18n::get_effective_locale());
        $this->assertSame('de-DE', OpenCalendarKit_I18n::get_js_locale());
    }

    public function test_plugin_locale_override_replaces_wordpress_site_language(): void {
        $this->setSiteLocale('de_DE');
        $this->setRuntimeLocale('en_US');
        $this->setSettings(['plugin_locale' => 'fr_FR']);

        $this->assertSame('fr_FR', OpenCalendarKit_I18n::get_effective_locale());
        $this->assertSame('fr-FR', OpenCalendarKit_I18n::get_js_locale());
    }

    public function test_site_language_default_is_used_consistently_in_outputs(): void {
        $this->setSiteLocale('de_DE');
        $this->setRuntimeLocale('en_US');
        $this->setSettings([
            'plugin_locale' => OpenCalendarKit_I18n::SITE_DEFAULT,
            'show_status_today' => '1',
            'show_calendar_legend' => '1',
            'show_opening_hours_title' => '1',
        ]);
        $this->setHours([
            1 => ['closed' => 1, 'from' => '', 'to' => ''],
        ]);

        $opening = OpenCalendarKit_Shortcode_OpeningHours::render();
        $status = FixedNowStatusTodayShortcode::render();
        $calendar = FixedNowCalendarShortcode::render(['month' => '2026-03']);

        $this->assertContains('Öffnungszeiten', $opening);
        $this->assertContains('Geschlossen', $opening);
        $this->assertContains('Heute geschlossen', $status);
        $this->assertContains('März 2026', $calendar);
        $this->assertContains('Offen', $calendar);
        $this->assertContains('Geschlossen', $calendar);
    }

    public function test_plugin_locale_override_is_used_consistently_in_outputs(): void {
        $this->setSiteLocale('de_DE');
        $this->setRuntimeLocale('de_DE');
        $this->setSettings([
            'plugin_locale' => 'fr_FR',
            'show_status_today' => '1',
            'show_calendar_legend' => '1',
            'show_opening_hours_title' => '1',
        ]);
        $this->setHours([
            1 => ['closed' => 1, 'from' => '', 'to' => ''],
        ]);

        $opening = OpenCalendarKit_Shortcode_OpeningHours::render();
        $status = FixedNowStatusTodayShortcode::render();
        $calendar = FixedNowCalendarShortcode::render(['month' => '2026-03']);

        $this->assertContains('Horaires d&#039;ouverture', $opening);
        $this->assertContains('Fermé', $opening);
        $this->assertContains('Fermé aujourd&#039;hui', $status);
        $this->assertContains('mars 2026', mb_strtolower($calendar));
        $this->assertContains('Ouvert', $calendar);
        $this->assertContains('Fermé', $calendar);
    }
}
