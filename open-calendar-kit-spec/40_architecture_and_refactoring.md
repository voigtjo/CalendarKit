# Architektur- und Refactoring-Regeln

## Ziel
Das Starterprojekt soll zu einem öffentlichen, sauberen Plugin refaktoriert werden, ohne unnötige Scope-Erweiterung.

## Namensregeln
- Plugin-Name: OpenCalendarKit
- Plugin-Hauptdatei: `open-calendar-kit.php`
- Text Domain: `open-calendar-kit`
- Präfix in PHP: `OKIT_` oder klar konsistente Alternative

## Zielstruktur
Beispiel:

```text
open-calendar-kit/
  open-calendar-kit.php
  uninstall.php
  readme.txt
  assets/
    css/
    js/
  includes/
    Admin/
    CPT/
    Shortcodes/
    Support/
```

## Refactoring-Ziele
- Entfernen von Reservierungslogik
- Umbenennen alter Klassen/Dateien mit MVP-/Booking-/CalendarKit-Bezug
- Umbenennen von Assets wie `bookingkit.css`
- Erhalt der bestehenden funktionalen Baseline, soweit im Public Scope

## Guardrails
- keine unnötigen Refactorings außerhalb des Public-1.0-Scopes
- keine neue Komplexität ohne fachlichen Bedarf
- keine Datenmigration ohne zwingenden Grund
