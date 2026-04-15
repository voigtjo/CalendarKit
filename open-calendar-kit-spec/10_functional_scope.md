# Funktionale Spezifikation Public 1.0

## 1. Öffnungszeiten
Das Plugin muss Öffnungszeiten pro Wochentag speichern und im Frontend ausgeben können.

### Backend
- Eingabe pro Wochentag:
  - geschlossen ja/nein
  - von
  - bis
- optionaler Zusatzhinweis unterhalb der Öffnungszeiten
- Speichern ohne Zurücksetzen bestehender Werte

### Frontend
Shortcode:
- `[okit_opening_hours]`

Optionen:
- Titel anzeigen ja/nein
- Zusatzhinweis anzeigen, wenn Inhalt vorhanden

## 2. Status Heute
Optionaler Shortcode für den Status des aktuellen Tages.

Shortcode:
- `[okit_status_today]`

Konfigurationsoption:
- Statusanzeige global aktivierbar/deaktivierbar

Verhalten:
- Wenn global deaktiviert: kein Output
- Wenn aktiviert: Ausgabe „Open“ / „Closed“ bzw. Übersetzung in aktueller Sprache

## 3. Monatskalender
Anzeige eines Monatskalenders mit Kennzeichnung offener und geschlossener Tage.

Shortcode:
- `[okit_calendar]`

Funktional:
- Monatsnavigation vor/zurück
- Legende optional
- offene Tage sichtbar markiert
- geschlossene Tage sichtbar markiert
- Schließtage aus Ausnahmeverwaltung überschreiben reguläre Öffnungszeiten
- Einzeltermine können bei regulär geschlossenen Wochentagen ausnahmsweise als geöffnet markiert werden
- `month="YYYY-MM"` bedeutet explizit: genau dieser Monat wird angezeigt
- Monatsüberschrift und Tagesmatrix müssen immer denselben Monat meinen
- `show_legend="1|0"` steuert die Legende explizit an/aus
- `week_starts_on="monday|sunday"` steuert den Wochenanfang des Grids

## 4. Schließtage / Closed Days
Backend-Verwaltung einzelner geschlossener Tage.

### Backend
- Datum
- optionaler Grund / Beschreibung
- Speicherung als einfache, für WordPress übliche Datenhaltung
- zusätzlich können regulär geschlossene Wochentage pro Datum als Ausnahme-Öffnung markiert und wieder entfernt werden

### Frontend
- Kalender berücksichtigt Schließtage
- Kalender berücksichtigt auch Ausnahme-Öffnungen für regulär geschlossene Wochentage
- optional kann der Grund bei Bedarf im Frontend verwendet werden, Public 1.0 benötigt aber keine komplexe Modal-Logik

## 5. Event Notice / Hinweisblock
Eine einfache, formatierte Mitteilung soll im Frontend per Shortcode eingebunden werden können.

### Backend
- Checkbox: Event notice aktiv
- Editorfeld für formatierten Inhalt
- Inhalt bleibt erhalten, auch wenn Checkbox deaktiviert wird

### Frontend
Shortcode:
- `[okit_event_notice]`

Verhalten:
- aktiviert + Inhalt vorhanden → Ausgabe
- deaktiviert oder leer → kein Output

## 6. Allgemeine Konfigurationsoptionen
Mindestens folgende Einstellungen sollen vorgesehen werden:
- Plugin-Sprache: WordPress-Sprache oder explizite Plugin-Sprache
- Statusanzeige global ein/aus
- Legende ein/aus
- Wochenstart Montag/Sonntag
- Zeitformat 24h / 12h
- Öffnungszeiten-Titel ein/aus

Diese Einstellungen können in Public 1.0 ganz oder teilweise unter einer gemeinsamen Settings-Seite zusammengeführt werden.

## 7. Sprachkonzept
- Standardmäßig verwendet OpenCalendarKit die Website-Sprache von WordPress.
- Optional kann eine Plugin-Sprache nur für OpenCalendarKit gesetzt werden.
- Diese Sprachentscheidung gilt einheitlich für:
  - Admin-Seiten
  - Shortcodes
  - Kalenderüberschrift und Wochentage
  - Legenden, Buttons, Modale und Hinweise
  - JS-lokalisierte Texte
- Redaktionelle Inhalte wie Event-Notice-Inhalt, Öffnungszeiten-Notiz und Closed-Day-Gründe werden dadurch nicht automatisch mehrsprachig.
