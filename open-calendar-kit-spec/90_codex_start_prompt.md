# Codex Start Prompt

Du arbeitest im Projekt `open-calendar-kit`.

## Kontext
- Das Starterprojekt ist die technische Basis.
- Die Spezifikation in diesem Verzeichnis ist führend.
- Ziel ist ein öffentliches, internationalisiertes WordPress-Plugin `OpenCalendarKit`.
- Das Ergebnis soll ein eigenständiges Plugin `open-calendar-kit` sein.
- Das bisherige projekt-/restaurantbezogene System ist ausdrücklich nicht Zielsystem.

## Lies zuerst mindestens diese Dateien vollständig
- `00_overview.md`
- `05_baseline_analysis.md`
- `10_functional_scope.md`
- `20_i18n_l10n_spec.md`
- `30_data_model_and_settings.md`
- `40_architecture_and_refactoring.md`
- `60_acceptance_criteria.md`
- `70_test_plan.md`

## Arbeitsweise
1. Analysiere zuerst das Starterprojekt und benenne klar:
   - was übernommen werden kann
   - was entfernt werden muss
   - welche Umbenennungen nötig sind
2. Erstelle dann einen kleinen, geordneten Migrationsplan in mehreren Schritten.
3. Implementiere nicht alles auf einmal.
4. Beginne mit dem ersten kleinen, sauberen Refactoring-Schritt.
5. Denke Tests direkt mit.
6. Melde bei jedem Schritt explizit:
   - was neu ist
   - was entfernt wurde
   - wie man es im Backend/Frontend sieht
   - wie man es testet

## Inhaltlicher Soll-Zustand
Public 1.0 enthält:
- Öffnungszeiten
- Monatskalender
- Schließtage
- optionale Statusanzeige heute
- Event Notice
- Internationalisierung/Lokalisierung
- GitHub-/WordPress-taugliche Plugin-Struktur

Nicht enthalten in Public 1.0:
- Reservierungen
- E-Mail-Workflows
- restaurantspezifische Sonderlogik
- mehrmandantenfähige Standorte

## Wichtige technische Vorgaben
- Plugin-Hauptdatei auf `open-calendar-kit.php` umstellen
- Text Domain `open-calendar-kit`
- sichtbare Strings übersetzbar machen
- keine bestehenden Daten ungefragt überschreiben
- Uninstall datenfreundlich lassen
- keine unnötigen Refactorings außerhalb des Scopes

## Erwartete Rückmeldung nach jedem Schritt
A. Kurzfassung
B. geänderte / neue / entfernte Dateien
C. technischer Ansatz
D. Testplan / Smoke-Checks
E. sichtbares Ergebnis

## Bitte beginne jetzt mit
1. Bestandsanalyse des Starterprojekts gegen die Spezifikation
2. einem kleinen Migrationsplan
3. dem ersten minimalen Refactoring-Schritt
