# Testplan

## 0. Automatisierte Basistests
- Im Verzeichnis `open-calendar-kit-tests/` liegt eine automatisch ausführbare Testbasis.
- Ausführung per CLI:
  - `php open-calendar-kit-tests/run.php`
- Diese Basistests decken zunächst isolierte Logiktests ohne vollständige WordPress-Instanz ab.
- Schwerpunkte der ersten Teststufe:
  - Settings-Defaults und Fallbacks
  - globale Settings und Shortcode-Overrides
  - Event Notice aktiv/inaktiv
  - Kalender-Legende, Wochenstart und Zeitformat-Logik
  - Sprachlogik: WordPress-Sprache als Default und Plugin-Locale-Override

## 0.1 Pluginnahe Integrations-Tests
- Zusätzlich gibt es eine zweite automatisierte Teststufe mit WordPress-naher Harness-Laufzeit ohne Browser-E2E.
- Diese Stufe lädt den echten Plugin-Einstiegspunkt und prüft insbesondere:
  - Aktivierung und Reaktivierung
  - Hook- und Shortcode-Registrierung
  - Rendering über die tatsächlich registrierten Public-Shortcodes
  - Zusammenspiel von Settings und Rendering
  - konsistente Anwendung der Plugin-Sprache in PHP-Rendering und JS-Lokalisierung
- Diese Stufe ist weiterhin lokal per PHP-CLI ausführbar und soll bewusst leichter bleiben als eine vollständige Browser- oder WordPress-Core-E2E-Umgebung.

## 0.2 WordPress-Core-nahe Tests gegen `wordpress/`
- Zusätzlich gibt es eine dritte Teststufe, die reale Core-Dateien aus dem lokalen Verzeichnis `wordpress/` verwendet.
- Diese Stufe prüft insbesondere:
  - Plugin-Laden über den lokalen Plugin-Pfad in `wordpress/wp-content/plugins/`
  - Public-Shortcodes über echte WordPress-Shortcode-Verarbeitung
  - Aktivierungslogik über echte WordPress-Hook-Namen
  - pluginnahe AJAX-Aufrufe ohne Browser
- Diese Stufe ist bewusst noch keine vollständige installierte WordPress-Site mit Datenbank und kein Browser-E2E-Test.
- Offene spätere Stufen bleiben:
  - echte installierte WordPress-Integration mit Datenbank
  - i18n/l10n-Endtests mit Sprachwechsel
  - Browser-/visuelle Endtests

## 1. Smoke-Test Installation
- Plugin in frischer WordPress-Instanz aktivieren
- keine PHP-Fehler
- Admin-Menüs sichtbar

## 1.1 Manuelle Gesamtabnahme auf der WordPress-Testseite

### Installation
- Installierbares ZIP mit Top-Level-Ordner `open-calendar-kit` vorbereiten
- prüfen, dass `open-calendar-kit.php`, `bootstrap.php`, `assets/`, `includes/`, `languages/`, `readme.txt`, `LICENSE` enthalten sind
- sicherstellen, dass keine Entwicklungsreste wie `.git` im ZIP enthalten sind

### Aktivierung
- Plugin auf einer WordPress-Testseite installieren und aktivieren
- keine PHP-Fehler
- Pluginliste zeigt `OpenCalendarKit`
- Menü `OpenCalendarKit` ist sichtbar

### Ersteinrichtung
- zuerst `Settings` öffnen
- danach `Opening Hours`, `Calendar`, `Event Notice` prüfen
- eine Testseite mit allen vier `okit_*`-Shortcodes anlegen

### Settings
- Defaults prüfen
- Plugin-Sprache auf `Use WordPress language`, `Deutsch`, `English`, `Français` prüfen
- `show_status_today` an/aus
- `show_calendar_legend` an/aus
- `week_starts_on` Montag/Sonntag
- `time_format_mode` site default / 24h / 12h
- `show_opening_hours_title` an/aus

### Opening Hours
- Werte eintragen und speichern
- Frontend mit `[okit_opening_hours]` prüfen
- Reload prüfen
- Deaktivieren/Aktivieren des Plugins → Werte bleiben erhalten

### Status Today
- globale Option an/aus testen
- `[okit_status_today]` prüfen
- bei aus: kein Output

### Calendar
- `[okit_calendar]` prüfen
- Monatsnavigation prüfen
- Legende ein/aus prüfen
- Wochenstart prüfen

### Closed Days
- Schließtag anlegen
- Kalenderdarstellung prüfen
- optionalen Grund prüfen, sofern sichtbar umgesetzt
- regulär geschlossenen Wochentag im Kalender ausnahmsweise öffnen
- Ausnahme-Öffnung wieder entfernen
- prüfen, dass Frontend- und Backend-Kalender den Tag danach wieder passend als offen bzw. nach Entfernen wieder als geschlossen markieren

### Event Notice
- Inhalt anlegen, Checkbox aktivieren
- `[okit_event_notice]` prüfen
- Checkbox deaktivieren → kein Output
- Checkbox reaktivieren → Inhalt wieder da

### Reaktivierung / Datenpersistenz
- Plugin deaktivieren und erneut aktivieren
- Settings, Opening Hours, Closed Days und Event Notice erneut prüfen
- keine bestehenden Daten dürfen überschrieben oder gelöscht sein

### Internationalisierung / Lokalisierung
- Website-Sprache wechseln
- prüfen, ob übersetzbare Strings sauber reagieren
- Datums-/Zeitdarstellung prüfen
- Deutsch, Englisch und Französisch auf der WordPress-Testseite prüfen
- Admin-Menüs, Settings, Opening Hours, Calendar, Closed-Day-Modal, Event Notice und Status Today prüfen
- Mit `Plugin Language = Use WordPress language` prüfen, dass OpenCalendarKit exakt der Website-Sprache folgt
- Mit Website-Sprache `Deutsch` und Plugin-Sprache `English` prüfen, dass alle Plugin-Bereiche konsistent englisch erscheinen
- Mit Website-Sprache `Deutsch` und Plugin-Sprache `Français` prüfen, dass alle Plugin-Bereiche konsistent französisch erscheinen
- Besonders prüfen:
  - Kalenderüberschrift
  - Wochentage
  - Kalender-Legende
  - Status-Texte
  - Admin-Buttons und Hinweise
  - JS-lokalisierte Modal- und Hinweistexte
- prüfen, ob `languages/open-calendar-kit.pot` als Übersetzungsbasis vorhanden ist
- prüfen, ob `open-calendar-kit-de_DE.mo` und `open-calendar-kit-fr_FR.mo` im Plugin vorhanden sind
- bei vorhandenen Übersetzungsdateien zusätzlich prüfen, ob `load_plugin_textdomain()` diese aus `languages/` lädt

### Mobile / Desktop
- Shortcode-Seite auf Desktop prüfen
- Shortcode-Seite auf Mobilbreite prüfen
- Calendar, Opening Hours, Event Notice und Status Today auf Lesbarkeit prüfen

## 2. Öffnungszeiten
- Werte eintragen und speichern
- Frontend mit `[okit_opening_hours]` prüfen
- Reload prüfen
- Deaktivieren/Aktivieren des Plugins → Werte bleiben erhalten

## 3. Status Heute
- globale Option an/aus testen
- `[okit_status_today]` prüfen
- bei aus: kein Output

## 4. Kalender
- `[okit_calendar]` prüfen
- Monatsnavigation prüfen
- Legende ein/aus prüfen
- Wochenstart prüfen

## 5. Schließtage
- Schließtag anlegen
- Kalenderdarstellung prüfen
- optionalen Grund prüfen, sofern sichtbar umgesetzt

## 6. Event Notice
- Inhalt anlegen, Checkbox aktivieren
- `[okit_event_notice]` prüfen
- Checkbox deaktivieren → kein Output
- Checkbox reaktivieren → Inhalt wieder da

## 7. Internationalisierung
- Website-Sprache wechseln
- prüfen, ob übersetzbare Strings sauber reagieren
- Datums-/Zeitdarstellung prüfen

## 8. Regression
- keine alten Reservierungsreste sichtbar
- keine beschädigten Admin-Menüs
- kein Datenverlust bei Update/Reaktivierung
