# Bestand – fachliche Spezifikation des aktuellen Projekts

## 1. Öffnungszeiten
### Ziel
Die Website soll feste wöchentliche Öffnungszeiten anzeigen.

### Backend
Der Redakteur / Administrator pflegt für jeden Wochentag:
- geschlossen ja/nein
- Uhrzeit von
- Uhrzeit bis

Zusätzlich kann ein freier Hinweistext gepflegt werden, der unter den Öffnungszeiten erscheint.

Beispiel:
- „Küche bis 21:00, Sonntags bis 20:00“

### Frontend
Der Shortcode `[bk_opening_hours]` gibt aus:
- Überschrift „Öffnungszeiten“
- Tabelle mit Wochentag und Zeitangabe
- Hinweistext unterhalb der Tabelle, falls gepflegt

## 2. Tagesstatus heute
### Ziel
Die Website soll schnell zeigen, ob heute geöffnet oder geschlossen ist.

### Frontend
Der Shortcode `[bk_status_today]` gibt einen Status-Badge aus.

### Fachliche Logik
Ein Tag gilt als geschlossen, wenn:
- der Wochentag in den regulären Öffnungszeiten als geschlossen markiert ist oder
- für das konkrete Datum ein Schließtag gepflegt wurde

## 3. Kalenderdarstellung
### Ziel
Die Website soll visuell zeigen, welche Tage offen oder geschlossen sind.

### Frontend
Der Shortcode `[bk_calendar]` zeigt:
- Monatsansicht
- Monatsnavigation
- Legende offen/geschlossen
- geschlossene Tage farblich markiert
- vergangene Tage deaktiviert / ausgegraut

### Verhalten geschlossener Tage
Bei Klick auf einen geschlossenen Tag kann ein Informations-Modal angezeigt werden.
Wenn ein Grund hinterlegt ist, wird dieser Grund im Modal dargestellt.

## 4. Schließtage
### Ziel
Zusätzlich zu den regulären Öffnungszeiten sollen einzelne Sonder-Schließtage gepflegt werden können.

### Backend
Im Admin-Kalender kann ein konkretes Datum als Schließtag erfasst werden.
Optional wird ein Grund gepflegt.

### Fachliche Wirkung
Ein expliziter Schließtag überschreibt für dieses Datum die normalen Öffnungszeiten.

## 5. Reservierungsanfragen
### Ziel
Die Website soll einfache Reservierungsanfragen entgegennehmen können.

### Aktueller Stand
Es existiert bereits eine technische Grundlage über AJAX und den CPT `bk_reservation`.
Die Anfragen werden gespeichert, aber das Plugin ist aktuell fachlich noch kein vollständiges Reservierungssystem.

## 6. Menü- und Rollenmodell
### Ziel
Das Plugin soll im Backend nur für berechtigte Nutzer sichtbar und pflegbar sein.

### Aktueller Stand
Es wird die Capability `calendarkit_manage` verwendet.
Diese wird aktuell Administratoren und Editoren vergeben.

## 7. Nicht-Ziele des aktuellen MVP
Nicht Bestandteil des aktuellen MVP sind:
- vollständige Tisch- oder Slotreservierung
- Verfügbarkeitslogik je Uhrzeit
- E-Mail-Bestätigungsworkflow
- wiederkehrende Sonderregeln
- mehrsprachige Inhalte
- Block-basierte statt Shortcode-basierte Frontend-Integration
