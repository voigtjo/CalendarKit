# Baseline-Analyse des Starterprojekts

## Ausgangsbasis
Das Starterprojekt basiert auf dem bisherigen CalendarKit/CalendarKit-MVP-Stand und enthält bereits funktionsfähige Bausteine für:

- Öffnungszeiten-Verwaltung im Backend
- Monatskalender im Frontend
- Schließtage / geschlossene Tage
- Status-Shortcode für heute
- Event Notice
- CSS- und JS-Basis

## Bestand, der fachlich übernommen werden kann
- Monatskalender mit Statusanzeige pro Tag
- Öffnungszeiten-Shortcode
- Status-Shortcode
- Closed-Days-Verwaltung
- Event Notice als schaltbarer Hinweisblock

## Bestand, der für Public 1.0 nicht übernommen werden soll
- Reservierungs-CPT und Reservierungs-Frontend
- restaurant- oder projektbezogene Logik
- projektspezifische Namen und Dateibezeichner
- technische Altlasten im Naming (`calendarkit-mvp`, `bookingkit.css` etc.)

## Notwendige Produktisierung
Für OpenCalendarKit muss der Bestand systematisch bereinigt werden:

1. Umbenennung auf öffentliches Produktnaming
2. Entfernung nicht benötigter Reservierungslogik
3. konsequente Internationalisierung aller sichtbaren Texte
4. klare Settings-Struktur für Public 1.0
5. WordPress-konforme Lizenz-, Readme- und Header-Angaben
6. robuste Aktivierungs-/Uninstall-Logik ohne Datenverlust
