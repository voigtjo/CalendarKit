# Nächste Ausbaustufe – Eventmitteilungssystem

## Ziel der Ausbaustufe
CalendarKit MVP soll um ein einfaches Eventmitteilungssystem erweitert werden.

Damit kann im Backend zentral gesteuert werden, ob aktuell ein Event angekündigt wird. Solange die Ankündigung aktiv ist, soll ein formatierbarer Text im Frontend über einen Shortcode ausgegeben werden.

## Fachliche Anforderungen

### 1. Aktivierung per Checkbox im Backend
Im Backend soll es einen Bereich geben, in dem ein Nutzer einstellen kann:
- Eventmitteilung aktiv: ja/nein

Die Aktivierung erfolgt über eine Checkbox.

### 2. Editierbarer Eventtext im Backend
Zusätzlich zur Checkbox soll es ein Eingabefeld für den eigentlichen Eventtext geben.

Anforderung an den Text:
- formatierbare Ausgabe im Frontend
- nicht nur Plain Text
- einfache redaktionelle Pflege im WordPress-Backend

Beispiele für gewünschte Inhalte:
- Datum / Uhrzeit eines Events
- kurze Beschreibung
- Hervorhebungen
- Zeilenumbrüche
- optional Links

### 3. Frontend-Ausgabe per Shortcode
Die Eventmitteilung soll auf einer WordPress-Seite über einen Shortcode eingebunden werden.

Vorgesehener Shortcode:
- `[bk_event_notice]`

### 4. Sichtbarkeitslogik
Der Shortcode soll nur dann sichtbaren Inhalt ausgeben, wenn die Eventmitteilung im Backend aktiv gesetzt ist.

Fachlich gilt:
- Checkbox aktiv → Text ausgeben
- Checkbox nicht aktiv → keine Ausgabe

### 5. Formatierte Ausgabe
Der Eventtext soll im Frontend formatiert ausgegeben werden.

Fachliche Minimalanforderung:
- Absätze
- Zeilenumbrüche
- einfache Hervorhebungen

Bevorzugt:
- WordPress-konforme formatierte Ausgabe statt rohem HTML

### 6. Bedienung im Backend
Die Pflege soll einfach und klar sein.
Empfehlung fachlich:
- eigener Untermenüpunkt unter `CalendarKit`
- Bezeichnung z. B. `Event Notice` oder `Event Announcement`

Alternativ akzeptabel:
- Integration in eine vorhandene Plugin-Seite, wenn die Struktur trotzdem klar bleibt

## Fachliche Regeln
- Wenn die Checkbox deaktiviert ist, darf im Frontend kein Platzhalter und kein Leerraum entstehen.
- Der Textinhalt darf gespeichert bleiben, auch wenn die Checkbox deaktiviert wird.
- Reaktivierung der Checkbox soll den bereits gepflegten Text wieder anzeigen.
- Plugin-Updates dürfen die Eventdaten nicht zurücksetzen.

## Vorschlag für Datenmodell
### Optionen
- `bkit_mvp_event_notice_enabled` → `0|1`
- `bkit_mvp_event_notice_content` → formatierter Textinhalt

Diese Daten sollen analog zu den vorhandenen Plugin-Optionen gespeichert werden.

## Vorschlag für Backend-Verhalten
Backend-Maske enthält:
- Checkbox: „Event announcement active“
- Textfeld / Editor: „Event text“
- Speichern-Button

## Vorschlag für Frontend-Verhalten
Shortcode `[bk_event_notice]`:
- gibt nichts aus, wenn `enabled = 0`
- gibt einen formatierten Wrapper aus, wenn `enabled = 1` und Inhalt vorhanden ist

### Beispielhafte Darstellung
- Container mit eigener CSS-Klasse
- Inhalt innerhalb eines klaren Frontend-Blocks
- Styling über bestehende Plugin-CSS-Datei ergänzbar

## Nicht Bestandteil dieser Ausbaustufe
Noch nicht Teil dieses Schritts:
- mehrere Events gleichzeitig
- Start-/Enddatum der Sichtbarkeit
- automatische Ausblendung nach Datum
- Eventarchiv
- Kalenderverknüpfung mit Eventtypen
- Anmeldelogik

## Akzeptanzkriterien
1. Im Backend kann eine Eventmitteilung aktiviert oder deaktiviert werden.
2. Im Backend kann ein formatierter Eventtext gespeichert werden.
3. Der Text bleibt gespeichert, auch wenn die Checkbox deaktiviert wird.
4. Der Shortcode `[bk_event_notice]` gibt im aktiven Zustand den Text aus.
5. Der Shortcode gibt im inaktiven Zustand keinen sichtbaren Output aus.
6. Nach Plugin-Update oder Reaktivierung bleiben die Eventdaten erhalten.
