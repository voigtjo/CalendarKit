# Datenmodell und Settings

## Grundsatz
Für Public 1.0 sollen WordPress-typische einfache Datenhaltungen genutzt werden. Keine eigenen Datenbanktabellen.

## Optionen
Geplante Optionen:
- `okit_opening_hours`
- `okit_opening_hours_note`
- `okit_event_notice_enabled`
- `okit_event_notice_content`
- `okit_settings`

`okit_settings` kann z. B. enthalten:
- `plugin_locale`
- `show_status_today`
- `show_calendar_legend`
- `week_starts_on`
- `time_format_mode`
- `show_opening_hours_title`

Für Public 1.0 gelten aktuell folgende Defaults:
- `plugin_locale` = `site_default`
- `show_status_today` = aktiviert
- `show_calendar_legend` = aktiviert
- `week_starts_on` = `monday`
- `time_format_mode` = `site_default`
- `show_opening_hours_title` = aktiviert

Regel für die Ausgabe:
- `plugin_locale = site_default` verwendet die WordPress-Website-Sprache.
- Ein explizit gesetztes `plugin_locale` überschreibt die WordPress-Sprache nur für OpenCalendarKit.
- Zulässige Werte für `plugin_locale` in Public 1.0:
  - `site_default`
  - `de_DE`
  - `en_US`
  - `fr_FR`
- Wenn für die explizite Plugin-Sprache keine Sprachdatei vorhanden ist, fällt OpenCalendarKit sauber auf die WordPress-Website-Sprache und danach auf die eingebauten Standardstrings zurück.
- Ein explizit gesetztes Shortcode-Attribut überschreibt das globale Setting.
- Ohne explizites Attribut greift das globale Setting.

## Schließtage
Closed Days können für Public 1.0 weiterhin über eine einfache WordPress-nahe Struktur verwaltet werden, z. B. bestehender CPT-Ansatz, sofern er sauber umbenannt und vereinfacht wird.

## Aktivierung / Reaktivierung
- Bestehende Optionen dürfen bei Aktivierung nicht überschrieben werden.
- Optionen nur bei echtem Erstinstallationsfall anlegen.

## Uninstall
- Für Public 1.0 datenfreundlich: keine ungefragte Löschung von Benutzerdaten.
- Falls später Datenlöschung gewünscht ist, dann nur als explizites Feature.
