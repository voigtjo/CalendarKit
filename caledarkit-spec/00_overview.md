# CalendarKit MVP – Fachliche Spezifikation

## Zweck des Plugins
CalendarKit MVP ist ein schlankes WordPress-Plugin zur Anzeige und Verwaltung von Öffnungszeiten und Schließtagen für ein Restaurant oder einen ähnlichen Standort. Zusätzlich enthält es einen Kalender zur visuellen Darstellung geöffneter und geschlossener Tage sowie eine einfache Erfassung von Reservierungsanfragen.

Das Plugin ist aktuell bewusst als MVP ausgelegt:
- einfache Backend-Pflege
- Shortcode-basierte Frontend-Einbindung
- klare Trennung zwischen Öffnungszeiten, kalenderbasierten Schließtagen und Reservierungsanfragen
- keine komplexe Buchungslogik

## Zielgruppe
- Restaurant / Café / kleiner Dienstleister
- WordPress-Seitenbetreiber ohne komplexes Buchungssystem
- Nutzung als kompakter Informations- und Anfragebaustein auf der Website

## Aktueller fachlicher Funktionsumfang
1. Wöchentliche Öffnungszeiten im Backend pflegen
2. Zusatzhinweis unterhalb der Öffnungszeiten im Frontend anzeigen
3. Einzelne Schließtage kalenderbasiert pflegen
4. Frontend-Kalender mit Monatsnavigation anzeigen
5. Geschlossene Tage im Kalender farblich markieren
6. Grund für einen geschlossenen Tag im Frontend-Modal anzeigen
7. Tagesstatus „Heute geschlossen / offen / beendet“ per Shortcode anzeigen
8. Reservierungsanfrage per AJAX entgegennehmen und als CPT speichern

## Frontend-Shortcodes
- `[bk_status_today]`
- `[bk_opening_hours]`
- `[bk_calendar]`
- zusätzlich vorhanden: `[bk_opening_hours_pretty]`

## Backend-Bereiche
Unter dem Menüpunkt `CalendarKit`:
- `Opening Hours`
- `Calendar`

Weitere interne Datenspeicher:
- CPT `bk_closed_day`
- CPT `bk_reservation`

## Datenhaltung (fachlich)
### Optionen
- `bkit_mvp_opening_hours`
- `bkit_mvp_opening_hours_note`

### Custom Post Types
- `bk_closed_day`
  - Datum
  - Grund
- `bk_reservation`
  - Datum
  - Uhrzeit
  - Personen
  - Name
  - Telefon
  - E-Mail
  - Nachricht

## Fachliche Grundsätze
- bestehende Nutzerdaten dürfen bei Plugin-Update oder Reaktivierung nicht überschrieben werden
- Backend-Eingaben sollen ohne technische Zusatzschritte im Frontend sichtbar werden
- Frontend-Ausgabe erfolgt bewusst über klar benennbare Shortcodes
- mobile und Desktop-Darstellung sollen beide funktionieren, aber das MVP priorisiert Robustheit über komplexes Layoutverhalten
