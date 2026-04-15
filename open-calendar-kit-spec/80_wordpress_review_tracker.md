# WordPress Review Tracker: OpenCalendarKit

Stand: 2026-04-15
Review-ID: `F1 open-calendar-kit/voigtjo/15Apr26/T1 15Apr26/3.9 (P0TDX297952HGN)`

Dieses Dokument fuehrt die vier Punkte aus dem WordPress.org-Review und ihren jetzigen Umsetzungsstand.
Die technische Detaildokumentation steht im separaten Bericht:

- `open-calendar-kit-spec/81_wordpress_review_implementation.md`

## Status-Legende

- `OFFEN`: noch nicht umgesetzt
- `ERLEDIGT`: Code umgesetzt und verifiziert
- `ZU PRUEFEN`: Code umgesetzt, aber Abschlusspruefung fehlt

## Gesamtuebersicht

| Task | Thema | Status | Ergebnis |
| --- | --- | --- | --- |
| `WPREV-01` | Contributors-Liste in `readme.txt` | `ERLEDIGT` | `voigtjo` in `readme.txt` eingetragen |
| `WPREV-02` | `load_plugin_textdomain()` / i18n-Loading | `ERLEDIGT` | explizites Textdomain-Loading entfernt; Locale-Switch basiert nur noch auf WordPress-Locale-Mechanik |
| `WPREV-03` | Nonce-/Berechtigungslogik fuer Input-Zugriffe | `ERLEDIGT` | unsichere GET-Monatsnavigation entfernt; Monatswechsel jetzt nonce-geschuetzt per AJAX |
| `WPREV-04` | Uneinheitliche Praefixe und zu generischer CPT-Slug | `ERLEDIGT` | aktive Plugin-Identifier auf `openkit_*` bzw. `OpenCalendarKit_*` konsolidiert; CPT auf `openkit_closed_day` umgestellt |

## WPREV-01: Contributors-Liste in `readme.txt`

**Reviewer-Hinweis**

Der WordPress.org-Benutzer des Plugin-Eigentuemers `voigtjo` fehlt in der `Contributors`-Liste.

**Umsetzung**

- `open-calendar-kit/readme.txt`
- `Contributors: open-calendar-kit` wurde auf `Contributors: voigtjo` umgestellt.

**Aenderungsnachweis**

- Status: `ERLEDIGT`
- Verifikation: Sichtpruefung im `readme.txt`

## WPREV-02: `load_plugin_textdomain()` / i18n-Loading

**Reviewer-Hinweis**

`load_plugin_textdomain()` ist fuer WordPress.org-Plugins grundsaetzlich nicht noetig und soll hier entfernt bzw. reviewer-konform ersetzt werden.

**Umsetzung**

- `open-calendar-kit/open-calendar-kit.php`
  - Hook-basierter Aufruf von `OpenCalendarKit_I18n::load_textdomain()` entfernt.
- `open-calendar-kit/includes/I18n.php`
  - die eigene `load_textdomain()`-Routine komplett entfernt
  - `with_locale()` laedt den Plugin-Textdomain nicht mehr manuell nach
  - bei plugin-spezifischer Sprachwahl wird nur noch `switch_to_locale()` / `restore_previous_locale()` verwendet

**Aenderungsnachweis**

- Status: `ERLEDIGT`
- Verifikation:
  - `php open-calendar-kit-tests/run-unit.php`
  - `php open-calendar-kit-tests/run-integration.php`
  - `php open-calendar-kit-tests/run-wp-core.php`

## WPREV-03: Nonce-/Berechtigungslogik fuer Input-Zugriffe

**Reviewer-Hinweis**

Die GET-basierte Monatsnavigation in Admin und Frontend hatte keinen Nonce-Schutz.

**Umsetzung**

- `open-calendar-kit/includes/Shortcodes/Calendar.php`
  - `$_GET['okit_month']` entfernt
  - Monatsnavigation arbeitet jetzt mit `data-target-month` und AJAX-POST
- `open-calendar-kit/assets/js/open-calendar-kit.js`
  - Frontend-Monatswechsel auf nonce-geschuetzten AJAX-Request umgestellt
- `open-calendar-kit/includes/Admin/ClosedDays.php`
  - `$_GET['okit_month']` entfernt
  - eigener AJAX-Endpunkt fuer den Admin-Kalendermonat eingebaut
  - alle Admin-Kalender-Aktionen laufen mit `check_ajax_referer()` plus `current_user_can()`
- `open-calendar-kit/assets/js/open-calendar-kit-admin.js`
  - Admin-Monatsnavigation auf nonce-geschuetzten AJAX-Flow umgestellt
- `open-calendar-kit/bootstrap.php`
  - Frontend-AJAX nutzt konsistente `openkit_*`-Actions und Nonces
  - `$_GET['page']`-Abhaengigkeit fuer Asset-Loading entfernt

**Aenderungsnachweis**

- Status: `ERLEDIGT`
- Verifikation:
  - gruene Unit-, Integration- und WP-Core-Tests
  - statischer Gegencheck: keine aktiven `$_GET['okit_month']`-Zugriffe mehr im Plugin

## WPREV-04: Uneinheitliche Praefixe und zu generischer CPT-Slug

**Reviewer-Hinweis**

Die gemischten Praefixe `okit`, `bkit_mvp` und `open_calendar_kit` sowie der CPT-Slug `bk_closed_day` sollen vereinheitlicht werden.

**Umsetzung**

- aktive Plugin-Identifier vereinheitlicht:
  - Klassen: `OpenCalendarKit_*`
  - Slugs, Optionen, Actions, Shortcodes, Nonces: `openkit_*`
- `open-calendar-kit/bootstrap.php`
  - zentrale Identifier-Konstanten eingefuehrt
  - aktive Shortcodes auf `[openkit_*]` umgestellt
  - aktive AJAX-Actions, Optionen und Settings auf `openkit_*` umgestellt
- `open-calendar-kit/includes/Admin/ClosedDays.php`
  - CPT-Slug von `bk_closed_day` auf `openkit_closed_day` umgestellt
- `open-calendar-kit/includes/Admin/*.php`
  - Admin-Klassen und Formularfelder konsolidiert
- `open-calendar-kit/includes/Shortcodes/*.php`
  - Shortcode-Klassen und Tags konsolidiert
- `open-calendar-kit/readme.txt`, `open-calendar-kit/README.md`, `open-calendar-kit/CHANGELOG.md`
  - Dokumentation auf die neuen Shortcodes aktualisiert
- Datenmigration eingebaut:
  - Legacy-Optionen `okit_*` und `bkit_mvp_*` werden in neue `openkit_*`-Optionen ueberfuehrt
  - Legacy-CPT-Daten werden auf `openkit_closed_day` migriert

**Aenderungsnachweis**

- Status: `ERLEDIGT`
- Verifikation:
  - gruene Unit-, Integration- und WP-Core-Tests
  - aktiver Code verwendet nun konsistent `openkit_*` / `OpenCalendarKit_*`

## Abschlusspruefung

Folgende Verifikation wurde nach der Umsetzung erfolgreich ausgefuehrt:

- `php -l` auf den geaenderten Plugin-Dateien
- `php -l open-calendar-kit-tests/bootstrap.php`
- `php -l open-calendar-kit-tests/integration-bootstrap.php`
- `php -l open-calendar-kit-tests/wp-core-bootstrap.php`
- `php open-calendar-kit-tests/run-unit.php`
- `php open-calendar-kit-tests/run-integration.php`
- `php open-calendar-kit-tests/run-wp-core.php`

Ergebnis:

- 38 Unit-Tests bestanden
- 18 Integration-Tests bestanden
- 8 WordPress-Core-nahe Tests bestanden

## Offene Beobachtung

Legacy-Identifier bleiben nur noch in der Migrationsschicht erhalten, damit bestehende lokale Test- oder Bestandsdaten in die neuen `openkit_*`-Kennungen uebernommen werden koennen. Die aktiv registrierten und ausgelieferten Plugin-Identifier sind auf die neue Namensfamilie umgestellt.
