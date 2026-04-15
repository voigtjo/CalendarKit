# OpenCalendarKit Tests

## Test Strategy
This test suite uses three lightweight PHP-based stages:

- Stage 1: fast logic-level tests with small WordPress stubs
- Stage 2: plugin-near integration tests that load the real plugin entrypoint against a controlled WordPress-like harness
- Stage 3: WordPress-core-near tests that use real core hook and shortcode APIs from the local `wordpress/` directory

The goal is to keep the first automated test layer:
- easy to run locally on macOS
- easy to execute later in CI
- independent from a full WordPress runtime for logic-level checks

The suite focuses on Public 1.0 behavior that is already implemented:
- settings defaults and fallbacks
- shortcode output gates and overrides
- calendar legend and week start behavior
- plugin-specific time format behavior
- event notice output behavior
- plugin activation and reactivation safeguards
- shortcode and hook registration through the real plugin bootstrap
- rendering through real WordPress shortcode parsing
- plugin loading through the local `wordpress/wp-content/plugins/open-calendar-kit` path

## Execution

Run all tests:

```bash
php /Users/jvoigt/Projects/OpenCalendarKit/open-calendar-kit-tests/run.php
```

Run only the fast logic suite:

```bash
php /Users/jvoigt/Projects/OpenCalendarKit/open-calendar-kit-tests/run-unit.php
```

Run only the plugin-near integration suite:

```bash
php /Users/jvoigt/Projects/OpenCalendarKit/open-calendar-kit-tests/run-integration.php
```

Run only the WordPress-core-near suite:

```bash
php /Users/jvoigt/Projects/OpenCalendarKit/open-calendar-kit-tests/run-wp-core.php
```

## Notes
- No external test framework is required for these first two layers.
- No external test framework is required for these first three layers.
- Stage 3 uses the local `wordpress/` directory and real core files, but still does not require a full installed database-backed site.
- The suite is intended as a stable base that can later be extended with fuller WordPress integration or browser-based checks where needed.
