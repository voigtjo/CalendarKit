# Codex-Handoff – Umsetzungsrahmen für den nächsten Schritt

## Ziel
Implementiere in `calendarkit-mvp` den nächsten kleinen, fachlich abgeschlossenen Ausbauschritt: ein Eventmitteilungssystem mit Backend-Aktivierung und Frontend-Shortcode.

## Scope
Nur dieser Schritt. Keine zusätzlichen Funktionsausbauten.

## Fachlicher Soll-Zustand
- Im Backend gibt es eine Plugin-Seite oder Unterseite für die Eventmitteilung.
- Dort kann eine Checkbox gesetzt werden, ob die Mitteilung aktiv ist.
- Dort kann ein formatierter Text gepflegt und gespeichert werden.
- Im Frontend gibt es einen Shortcode `[bk_event_notice]`.
- Der Shortcode zeigt den formatierten Text nur dann an, wenn die Checkbox aktiv ist.
- Ist die Checkbox nicht aktiv, wird nichts ausgegeben.
- Bestehende Plugin-Daten dürfen nicht überschrieben oder gelöscht werden.

## Implementierungspräferenzen
1. Bestehende Architektur des Plugins weiterverwenden.
2. Optionen statt neuer CPTs oder eigener Tabellen verwenden.
3. Neue Admin-Seite unter `CalendarKit` anlegen.
4. Ausgabe sauber kapseln, ähnlich wie bei den vorhandenen Shortcode-Klassen.
5. WordPress-konforme Formatierung für den Text verwenden.
6. Keine unnötige JS-Komplexität.

## Empfohlene technische Zielstruktur
- neue Admin-Klasse, z. B. `includes/Admin/EventNotice.php`
- neue Shortcode-Klasse, z. B. `includes/Shortcodes/EventNotice.php`
- Einbindung in `calendarkit-mvp.php`
- optionale CSS-Ergänzung in `assets/css/bookingkit.css`

## Erwartete Optionen
- `bkit_mvp_event_notice_enabled`
- `bkit_mvp_event_notice_content`

## Definition of Done
- Backend-Seite vorhanden und speicherbar
- Checkbox und Textfeld funktionieren
- `[bk_event_notice]` funktioniert
- Frontend gibt nur bei aktivierter Checkbox aus
- bestehende Plugin-Funktionen bleiben unverändert lauffähig
- Aktivierung/Reaktivierung des Plugins setzt die neuen oder alten Daten nicht zurück

## Erwartete Rückmeldung von Codex
Bitte liefere nach Implementierung:
1. eine Kurzfassung der Änderung
2. eine Liste aller geänderten / neuen Dateien
3. eine Erklärung, wie die Eventmitteilung technisch angebunden wurde
4. einen konkreten Testplan
5. Hinweise, wie man die Funktion im Backend und Frontend sofort prüfen kann

## Gewünschte Tests
### Smoke-Checks
1. Plugin aktivieren → bestehende Öffnungszeiten unverändert
2. Eventmitteilung speichern → Seite neu laden → Werte bleiben erhalten
3. Checkbox aktiv + Text vorhanden → Shortcode zeigt Mitteilung an
4. Checkbox inaktiv → Shortcode zeigt nichts an
5. Reaktivierung des Plugins → Eventdaten und Öffnungszeiten bleiben erhalten

### Manuelle Sichtprüfung
- Backend-Seite unter CalendarKit sichtbar
- Frontend-Block mit formatiertem Text sichtbar
- kein leerer Wrapper bei deaktivierter Mitteilung
