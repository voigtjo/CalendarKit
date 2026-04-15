# Dokumentation und Veröffentlichung

## Repo-Dokumentation
Mindestens erforderlich:
- `README.md` für GitHub
- `CHANGELOG.md`
- `LICENSE`
- später `readme.txt` für WordPress.org
- Packaging-Hinweise für ein installierbares ZIP-Paket
- `languages/`-Struktur mit klarer Basis für Sprachdateien

## README.md Inhalt
- Kurzbeschreibung
- Features
- Installationshinweise
- Shortcodes
- Konfiguration
- Scope / Nicht-Ziele
- Hinweise zu i18n/l10n
- Packaging-Hinweise
- Entwicklungsstatus
- Lizenz

## WordPress.org `readme.txt`
Muss vorbereitet werden mit:
- Plugin Name
- Contributors
- Tags
- Requires at least
- Tested up to
- Requires PHP
- Stable tag
- License
- Description
- Installation
- FAQ
- Changelog

## Distribution
Public Ziel:
- GitHub als Entwicklungs- und Quellrepository
- später WordPress Plugin Directory
- Release-ZIP mit Top-Level-Ordner `open-calendar-kit`
- Nur distributionsrelevante Plugin-Dateien gehören in das Release-Paket
- Entwicklungsreste wie verschachtelte `.git`-Daten dürfen nicht im Release-ZIP landen

## Lizenz
WordPress-kompatibel und konsistent mit Plugin-Header / Dokumentation.

## Übersetzungsstruktur
- Text Domain: `open-calendar-kit`
- Sprachdateien liegen unter `open-calendar-kit/languages/`
- Für Release- und Testvorbereitung soll mindestens eine POT-Vorlage vorhanden sein
- Reale Sprachdateien für manuelle Abnahme sollen klar dokumentiert sein, z. B. `de_DE` und `fr_FR`
- Die Plugin-Dokumentation soll klar beschreiben, dass OpenCalendarKit standardmäßig der WordPress-Website-Sprache folgt und optional eine eigene Plugin-Sprache verwenden kann
