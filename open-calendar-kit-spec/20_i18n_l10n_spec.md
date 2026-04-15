# i18n / l10n Spezifikation

## Ziel
OpenCalendarKit muss international einsetzbar sein.

## Grundregeln
- Alle sichtbaren Texte müssen übersetzbar sein.
- Keine fest verdrahteten deutschen oder englischen UI-Strings.
- Text Domain des Plugins: `open-calendar-kit`
- Plugin-Slug/Ordnername: `open-calendar-kit`
- Datei- und Stringverarbeitung UTF-8/Unicode-tauglich

## Lokalisierung
Das Plugin muss WordPress-Lokalisierung respektieren:
- Sprache der Website
- Zeitzone der Website
- lokalisierte Datums-/Zeitformate, wo fachlich sinnvoll

## Datums- und Zeitlogik
- Speicherung intern in stabiler Form
- Ausgabe orientiert sich an WordPress-Settings
- 24h/12h-Konfiguration zusätzlich möglich
- Wochentage und Monatsnamen sollen lokalisierbar sein

## Übersetzungsrelevante Bereiche
- Backend-Menüs
- Seitenüberschriften
- Formularlabels
- Button-Texte
- Frontend-Titel
- Legenden
- Statusausgaben (Open/Closed)
- Hinweise und Validierungstexte

## Nicht zulässig
- gemischte harte Sprachstrings
- projektspezifische Restaurantbezeichnungen
- nicht lokalisierte Datumsnamen im Frontend
