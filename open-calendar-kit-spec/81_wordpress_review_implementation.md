# WordPress Review Implementation Report

Stand: 2026-04-15
Bezug: WordPress.org Review fuer `open-calendar-kit`

Dieses Dokument beschreibt die konkret umgesetzten Aenderungen fuer die naechste Einreichung und haelt die abschliessende Verifikation fest.

## Zusammenfassung

Die vier im Review genannten Punkte wurden umgesetzt:

1. `readme.txt` fuehrt jetzt den WordPress.org-Benutzer `voigtjo` als Contributor.
2. Das explizite `load_plugin_textdomain()`-Loading wurde entfernt.
3. Die bisherige GET-Monatsnavigation ohne Nonce wurde in Admin und Frontend durch nonce-geschuetzte AJAX-Flows ersetzt.
4. Die aktiven Plugin-Identifier wurden auf eine konsistente `openkit_*`- / `OpenCalendarKit_*`-Familie umgestellt, inklusive neuem CPT-Slug `openkit_closed_day`.

## Umgesetzte Aenderungen

### 1. Contributors in `readme.txt`

- Datei: `open-calendar-kit/readme.txt`
- Aenderung:
  - `Contributors: voigtjo`

### 2. i18n-Loading ohne `load_plugin_textdomain()`

- Dateien:
  - `open-calendar-kit/open-calendar-kit.php`
  - `open-calendar-kit/includes/I18n.php`
- Aenderungen:
  - Hook auf `plugins_loaded` entfernt
  - manuelles Textdomain-Reloading entfernt
  - `with_locale()` arbeitet nur noch mit `switch_to_locale()` und `restore_previous_locale()`

### 3. Nonce-konforme Monatsnavigation

- Frontend:
  - `open-calendar-kit/includes/Shortcodes/Calendar.php`
  - `open-calendar-kit/assets/js/open-calendar-kit.js`
  - `open-calendar-kit/bootstrap.php`
- Admin:
  - `open-calendar-kit/includes/Admin/ClosedDays.php`
  - `open-calendar-kit/assets/js/open-calendar-kit-admin.js`
  - `open-calendar-kit/bootstrap.php`
- Aenderungen:
  - keine aktive Verarbeitung von `$_GET['okit_month']` mehr
  - Monatsnavigation verwendet `data-target-month`
  - Monatswechsel erfolgt per AJAX-POST mit Nonce
  - Admin-Endpunkte pruefen zusaetzlich `current_user_can( OpenCalendarKit_Plugin::CAP_MANAGE )`
  - die bisherige `$_GET['page']`-Abhaengigkeit im Asset-Loading wurde entfernt

### 4. Prefix-Konsolidierung und CPT-Migration

- Zentrale Identifier:
  - `open-calendar-kit/bootstrap.php`
- Klassen umgestellt:
  - `OpenCalendarKit_Admin_OpeningHours`
  - `OpenCalendarKit_Admin_ClosedDays`
  - `OpenCalendarKit_Admin_EventNotice`
  - `OpenCalendarKit_Admin_Settings`
  - `OpenCalendarKit_Shortcode_OpeningHours`
  - `OpenCalendarKit_Shortcode_StatusToday`
  - `OpenCalendarKit_Shortcode_Calendar`
  - `OpenCalendarKit_Shortcode_EventNotice`
- Aktive Slugs/Kennungen umgestellt:
  - Settings: `openkit_settings`
  - Optionen: `openkit_opening_hours`, `openkit_opening_hours_note`, `openkit_event_notice_enabled`, `openkit_event_notice_content`
  - Shortcodes: `[openkit_opening_hours]`, `[openkit_status_today]`, `[openkit_calendar]`, `[openkit_event_notice]`
  - AJAX-Actions: `openkit_*`
  - Nonces: `openkit_*`
  - CPT: `openkit_closed_day`

### 5. Bestandsdaten-Migration

- Datei: `open-calendar-kit/bootstrap.php`
- Aenderungen:
  - Migration fuer Legacy-Optionen `okit_*` und `bkit_mvp_*`
  - Migration vorhandener Legacy-CPT-Datensaetze von `bk_closed_day` auf `openkit_closed_day`
  - neuer interner Migrationsmarker `openkit_data_version`

### 6. Dokumentation aktualisiert

- Dateien:
  - `open-calendar-kit/readme.txt`
  - `open-calendar-kit/README.md`
  - `open-calendar-kit/CHANGELOG.md`
  - `open-calendar-kit/languages/open-calendar-kit-de_DE.po`
  - `open-calendar-kit/languages/open-calendar-kit-fr_FR.po`
  - `open-calendar-kit/languages/open-calendar-kit.pot`
- Aenderungen:
  - Shortcode-Namen auf `openkit_*` angepasst
  - Contributors-Eintrag korrigiert

## Verifikation

### Syntax

Erfolgreich geprueft mit `php -l`:

- `open-calendar-kit/bootstrap.php`
- `open-calendar-kit/includes/I18n.php`
- `open-calendar-kit/includes/Admin/ClosedDays.php`
- `open-calendar-kit/includes/Admin/OpeningHours.php`
- `open-calendar-kit/includes/Admin/EventNotice.php`
- `open-calendar-kit/includes/Admin/Settings.php`
- `open-calendar-kit/includes/Shortcodes/Calendar.php`
- `open-calendar-kit/includes/Shortcodes/OpeningHours.php`
- `open-calendar-kit/includes/Shortcodes/StatusToday.php`
- `open-calendar-kit/includes/Shortcodes/EventNotice.php`
- `open-calendar-kit-tests/bootstrap.php`
- `open-calendar-kit-tests/integration-bootstrap.php`
- `open-calendar-kit-tests/wp-core-bootstrap.php`

### Testlaeufe

Erfolgreich ausgefuehrt:

- `php open-calendar-kit-tests/run-unit.php`
- `php open-calendar-kit-tests/run-integration.php`
- `php open-calendar-kit-tests/run-wp-core.php`

Ergebnis:

- 38 Unit-Tests bestanden
- 18 Integration-Tests bestanden
- 8 WordPress-Core-nahe Tests bestanden

### Statischer Gegencheck

Nach dem Umbau wurde nochmals geprueft:

- keine aktiven `$_GET['okit_month']`-Zugriffe mehr
- kein aktiver `load_plugin_textdomain()`-Aufruf mehr
- aktive Plugin-Identifier laufen ueber `openkit_*` bzw. `OpenCalendarKit_*`

## Resthinweis

Legacy-Identifier kommen nur noch in der Migrationsschicht vor, damit bestehende lokale Daten sauber auf die neuen Kennungen uebernommen werden koennen. Die aktiv registrierten Shortcodes, Optionen, Actions und der CPT-Slug sind auf den neuen Namensraum umgestellt.
