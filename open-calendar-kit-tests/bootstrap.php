<?php
declare(strict_types=1);

if (!defined('ABSPATH')) {
    define('ABSPATH', '/');
}

if (!defined('OPEN_CALENDAR_KIT_MAIN_FILE')) {
    define('OPEN_CALENDAR_KIT_MAIN_FILE', '/Users/jvoigt/Projects/OpenCalendarKit/open-calendar-kit/open-calendar-kit.php');
}

function okit_test_default_hours(): array {
    return [
        1 => ['closed' => 0, 'from' => '09:00', 'to' => '18:00'],
        2 => ['closed' => 0, 'from' => '09:00', 'to' => '18:00'],
        3 => ['closed' => 0, 'from' => '09:00', 'to' => '18:00'],
        4 => ['closed' => 0, 'from' => '09:00', 'to' => '18:00'],
        5 => ['closed' => 0, 'from' => '09:00', 'to' => '18:00'],
        6 => ['closed' => 0, 'from' => '10:00', 'to' => '14:00'],
        7 => ['closed' => 1, 'from' => '', 'to' => ''],
    ];
}

function okit_test_translations(): array {
    return [
        'de_DE' => [
            'Use WordPress language' => 'WordPress-Sprache verwenden',
            'Plugin language' => 'Plugin-Sprache',
            'Use the website language by default, or choose a language for OpenCalendarKit only.' => 'Standardmäßig die Website-Sprache verwenden oder eine Sprache nur für OpenCalendarKit wählen.',
            'Settings' => 'Einstellungen',
            'Opening Hours' => 'Öffnungszeiten',
            'Closed' => 'Geschlossen',
            'Open' => 'Offen',
            'Today closed' => 'Heute geschlossen',
            'Opens today at %s' => 'Öffnet heute um %s',
            'Open now until %s' => 'Jetzt geöffnet bis %s',
            'Closed now' => 'Jetzt geschlossen',
            'Open now' => 'Jetzt geöffnet',
            'Calendar' => 'Kalender',
            'Event Notice' => 'Event-Hinweis',
            'Reason:' => 'Grund:',
            'Previous month' => 'Vorheriger Monat',
            'Next month' => 'Nächster Monat',
            'Monday' => 'Montag',
            'Tuesday' => 'Dienstag',
            'Wednesday' => 'Mittwoch',
            'Thursday' => 'Donnerstag',
            'Friday' => 'Freitag',
            'Saturday' => 'Samstag',
            'Sunday' => 'Sonntag',
            'Mon' => 'Mo',
            'Tue' => 'Di',
            'Wed' => 'Mi',
            'Thu' => 'Do',
            'Fri' => 'Fr',
            'Sat' => 'Sa',
            'Sun' => 'So',
            'Close' => 'Schließen',
        ],
        'fr_FR' => [
            'Use WordPress language' => 'Utiliser la langue de WordPress',
            'Plugin language' => 'Langue de l’extension',
            'Use the website language by default, or choose a language for OpenCalendarKit only.' => 'Utilisez la langue du site par défaut ou choisissez une langue uniquement pour OpenCalendarKit.',
            'Settings' => 'Paramètres',
            'Opening Hours' => 'Horaires d\'ouverture',
            'Closed' => 'Fermé',
            'Open' => 'Ouvert',
            'Today closed' => 'Fermé aujourd\'hui',
            'Opens today at %s' => 'Ouvre aujourd\'hui à %s',
            'Open now until %s' => 'Ouvert maintenant jusqu\'à %s',
            'Closed now' => 'Fermé maintenant',
            'Open now' => 'Ouvert maintenant',
            'Calendar' => 'Calendrier',
            'Event Notice' => 'Avis d’événement',
            'Reason:' => 'Raison :',
            'Previous month' => 'Mois précédent',
            'Next month' => 'Mois suivant',
            'Monday' => 'Lundi',
            'Tuesday' => 'Mardi',
            'Wednesday' => 'Mercredi',
            'Thursday' => 'Jeudi',
            'Friday' => 'Vendredi',
            'Saturday' => 'Samedi',
            'Sunday' => 'Dimanche',
            'Mon' => 'Lun',
            'Tue' => 'Mar',
            'Wed' => 'Mer',
            'Thu' => 'Jeu',
            'Fri' => 'Ven',
            'Sat' => 'Sam',
            'Sun' => 'Dim',
            'Close' => 'Fermer',
        ],
    ];
}

function okit_test_weekday_abbrev_for_locale(string $locale): array {
    $labels = [
        'en_US' => ['Sunday' => 'Sun.', 'Monday' => 'Mon.', 'Tuesday' => 'Tue.', 'Wednesday' => 'Wed.', 'Thursday' => 'Thu.', 'Friday' => 'Fri.', 'Saturday' => 'Sat.'],
        'de_DE' => ['Sunday' => 'So.', 'Monday' => 'Mo.', 'Tuesday' => 'Di.', 'Wednesday' => 'Mi.', 'Thursday' => 'Do.', 'Friday' => 'Fr.', 'Saturday' => 'Sa.'],
        'fr_FR' => ['Sunday' => 'dim.', 'Monday' => 'lun.', 'Tuesday' => 'mar.', 'Wednesday' => 'mer.', 'Thursday' => 'jeu.', 'Friday' => 'ven.', 'Saturday' => 'sam.'],
    ];

    return $labels[$locale] ?? $labels['en_US'];
}

function okit_test_set_runtime_locale(string $locale): void {
    $GLOBALS['okit_test_runtime_locale'] = $locale;
    $GLOBALS['wp_locale'] = (object) [
        'weekday_abbrev' => okit_test_weekday_abbrev_for_locale($locale),
    ];
}

if (!function_exists('__')) {
    function __($text, $domain = null) {
        $locale = $GLOBALS['okit_test_runtime_locale'] ?? 'en_US';
        $translations = $GLOBALS['okit_test_translations'][$locale] ?? [];
        return (string) ($translations[(string) $text] ?? $text);
    }
}

if (!function_exists('esc_html__')) {
    function esc_html__($text, $domain = null) {
        return htmlspecialchars(__($text, $domain), ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('esc_attr__')) {
    function esc_attr__($text, $domain = null) {
        return htmlspecialchars(__($text, $domain), ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('esc_html_e')) {
    function esc_html_e($text, $domain = null): void {
        echo esc_html__($text, $domain);
    }
}

if (!function_exists('esc_attr_e')) {
    function esc_attr_e($text, $domain = null): void {
        echo esc_attr__($text, $domain);
    }
}

if (!function_exists('esc_html')) {
    function esc_html($text) {
        return htmlspecialchars((string) $text, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('esc_attr')) {
    function esc_attr($text) {
        return htmlspecialchars((string) $text, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('esc_url')) {
    function esc_url($url) {
        return (string) $url;
    }
}

if (!function_exists('shortcode_atts')) {
    function shortcode_atts($pairs, $atts, $shortcode = '') {
        $atts = is_array($atts) ? $atts : [];
        $output = $pairs;
        foreach ($pairs as $name => $default) {
            if (array_key_exists($name, $atts)) {
                $output[$name] = $atts[$name];
            }
        }
        return $output;
    }
}

if (!function_exists('wp_parse_args')) {
    function wp_parse_args($args, $defaults = []) {
        $args = is_array($args) ? $args : [];
        $defaults = is_array($defaults) ? $defaults : [];
        return array_merge($defaults, $args);
    }
}

if (!function_exists('sanitize_text_field')) {
    function sanitize_text_field($value) {
        return trim(strip_tags((string) $value));
    }
}

if (!function_exists('wp_strip_all_tags')) {
    function wp_strip_all_tags($text, $remove_breaks = false) {
        $text = strip_tags((string) $text);
        return $remove_breaks ? preg_replace('/[\r\n\t ]+/', ' ', $text) : $text;
    }
}

if (!function_exists('wp_kses_post')) {
    function wp_kses_post($content) {
        return (string) $content;
    }
}

if (!function_exists('wp_kses')) {
    function wp_kses($content, $allowed_html = [], $allowed_protocols = []) {
        return (string) $content;
    }
}

if (!function_exists('wp_kses_allowed_html')) {
    function wp_kses_allowed_html($context = '') {
        return [];
    }
}

if (!function_exists('wpautop')) {
    function wpautop($content) {
        $content = trim((string) $content);
        if ($content === '') {
            return '';
        }

        $paragraphs = preg_split("/\n\s*\n/", $content) ?: [];
        $paragraphs = array_map(
            static fn($paragraph) => '<p>' . trim($paragraph) . '</p>',
            array_filter($paragraphs, static fn($paragraph) => trim((string) $paragraph) !== '')
        );

        return implode("\n", $paragraphs);
    }
}

if (!function_exists('checked')) {
    function checked($checked, $current = true, $display = true) {
        $result = ((string) $checked === (string) $current) ? 'checked="checked"' : '';
        if ($display) {
            echo $result;
        }
        return $result;
    }
}

if (!function_exists('selected')) {
    function selected($selected, $current = true, $display = true) {
        $result = ((string) $selected === (string) $current) ? 'selected="selected"' : '';
        if ($display) {
            echo $result;
        }
        return $result;
    }
}

if (!function_exists('settings_fields')) {
    function settings_fields($option_group): void {
    }
}

if (!function_exists('submit_button')) {
    function submit_button($text = 'Save Changes'): void {
        echo '<button type="submit">' . esc_html($text) . '</button>';
    }
}

if (!function_exists('register_setting')) {
    function register_setting($option_group, $option_name, $args = []): void {
        $GLOBALS['okit_test_registered_settings'][$option_group][$option_name] = $args;
    }
}

if (!function_exists('add_submenu_page')) {
    function add_submenu_page($parent_slug, $page_title, $menu_title, $capability, $menu_slug, $callback = null) {
        $GLOBALS['okit_test_registered_menus'][] = compact('parent_slug', 'page_title', 'menu_title', 'capability', 'menu_slug');
        return $menu_slug;
    }
}

if (!function_exists('add_menu_page')) {
    function add_menu_page($page_title, $menu_title, $capability, $menu_slug, $callback = null, $icon_url = '', $position = null) {
        $GLOBALS['okit_test_registered_menus'][] = compact('page_title', 'menu_title', 'capability', 'menu_slug');
        return $menu_slug;
    }
}

if (!function_exists('get_locale')) {
    function get_locale() {
        return $GLOBALS['okit_test_site_locale'] ?? 'en_US';
    }
}

if (!function_exists('determine_locale')) {
    function determine_locale() {
        return $GLOBALS['okit_test_runtime_locale'] ?? get_locale();
    }
}

if (!function_exists('switch_to_locale')) {
    function switch_to_locale($locale) {
        $GLOBALS['okit_test_locale_stack'][] = determine_locale();
        okit_test_set_runtime_locale((string) $locale);
        return true;
    }
}

if (!function_exists('restore_previous_locale')) {
    function restore_previous_locale() {
        $previous = array_pop($GLOBALS['okit_test_locale_stack']);
        okit_test_set_runtime_locale(is_string($previous) && $previous !== '' ? $previous : get_locale());
        return $previous !== null;
    }
}

if (!function_exists('load_plugin_textdomain')) {
    function load_plugin_textdomain($domain, $deprecated = false, $plugin_rel_path = false): bool {
        return true;
    }
}

if (!function_exists('unload_textdomain')) {
    function unload_textdomain($domain, $reloadable = false): bool {
        return true;
    }
}

if (!function_exists('get_option')) {
    function get_option($option, $default = false) {
        return array_key_exists($option, $GLOBALS['okit_test_options']) ? $GLOBALS['okit_test_options'][$option] : $default;
    }
}

if (!function_exists('add_option')) {
    function add_option($option, $value = ''): void {
        if (!array_key_exists($option, $GLOBALS['okit_test_options'])) {
            $GLOBALS['okit_test_options'][$option] = $value;
        }
    }
}

if (!function_exists('update_option')) {
    function update_option($option, $value): void {
        $GLOBALS['okit_test_options'][$option] = $value;
    }
}

if (!function_exists('wp_timezone_string')) {
    function wp_timezone_string() {
        $timezone = get_option('timezone_string', 'UTC');
        return is_string($timezone) && $timezone !== '' ? $timezone : 'UTC';
    }
}

if (!function_exists('wp_timezone')) {
    function wp_timezone() {
        return new DateTimeZone(wp_timezone_string());
    }
}

if (!function_exists('add_query_arg')) {
    function add_query_arg($args, $url = '') {
        $args = is_array($args) ? $args : [];
        if ($url === '') {
            return '?' . http_build_query($args);
        }

        $parts = parse_url($url);
        $query = [];
        parse_str($parts['query'] ?? '', $query);
        $query = array_merge($query, $args);

        $path = $parts['path'] ?? '';
        $query_string = http_build_query($query);

        return $path . ($query_string !== '' ? '?' . $query_string : '');
    }
}

if (!function_exists('date_i18n')) {
    function date_i18n($format, $timestamp) {
        return date($format, (int) $timestamp);
    }
}

if (!function_exists('plugin_basename')) {
    function plugin_basename($file) {
        return 'open-calendar-kit/' . basename((string) $file);
    }
}

if (!function_exists('wp_date')) {
    function wp_date($format, $timestamp = null, $timezone = null) {
        $timestamp = $timestamp ?? time();
        $timezone = $timezone instanceof DateTimeZone ? $timezone : new DateTimeZone(date_default_timezone_get());
        $date = new DateTime('@' . (int) $timestamp);
        $date->setTimezone($timezone);
        if ($format === 'F Y' && class_exists('IntlDateFormatter')) {
            $formatter = new IntlDateFormatter(determine_locale(), IntlDateFormatter::NONE, IntlDateFormatter::NONE, $timezone->getName(), IntlDateFormatter::GREGORIAN, 'LLLL yyyy');
            $formatted = $formatter->format($date);
            if (is_string($formatted) && $formatted !== '') {
                return $formatted;
            }
        }
        return $date->format($format);
    }
}

class OpenCalendarKit_Plugin {
    public const CAP_MANAGE = 'openkit_manage';
    public const MENU_SLUG = 'open-calendar-kit';
    public const PAGE_CALENDAR = 'open-calendar-kit-calendar';
    public const PAGE_EVENT_NOTICE = 'open-calendar-kit-event-notice';
    public const PAGE_SETTINGS = 'open-calendar-kit-settings';
    public const SETTINGS_GROUP = 'openkit_settings';
    public const SETTINGS_OPTION = 'openkit_settings';
    public const OPTION_OPENING_HOURS = 'openkit_opening_hours';
    public const OPTION_OPENING_HOURS_NOTE = 'openkit_opening_hours_note';
    public const OPTION_EVENT_NOTICE_ENABLED = 'openkit_event_notice_enabled';
    public const OPTION_EVENT_NOTICE_CONTENT = 'openkit_event_notice_content';
    public const SHORTCODE_CALENDAR = 'openkit_calendar';
    public const SHORTCODE_OPENING_HOURS = 'openkit_opening_hours';
    public const SHORTCODE_STATUS_TODAY = 'openkit_status_today';
    public const SHORTCODE_EVENT_NOTICE = 'openkit_event_notice';
    public const CPT_CLOSED_DAY = 'openkit_closed_day';
}

class OpenCalendarKit_Admin_OpeningHours {
    public static array $hours = [];
    public static string $note = '';

    public static function default_hours(): array {
        return okit_test_default_hours();
    }

    public static function get_hours(): array {
        return self::$hours ?: self::default_hours();
    }

    public static function get_note(): string {
        return self::$note;
    }
}

class OpenCalendarKit_Admin_ClosedDays {
    public static array $closed_dates = [];
    public static array $open_overrides = [];
    public static array $reasons = [];

    public static function is_closed_on($ymd): bool {
        return in_array((string) $ymd, self::$closed_dates, true);
    }

    public static function is_open_exception_on($ymd): bool {
        return in_array((string) $ymd, self::$open_overrides, true);
    }

    public static function get_reason($ymd): string {
        return self::$reasons[(string) $ymd] ?? '';
    }
}

class OpenCalendarKit_Admin_EventNotice {
    public static bool $enabled = false;
    public static string $content = '';

    public static function is_enabled(): bool {
        return self::$enabled;
    }

    public static function get_content(): string {
        return self::$content;
    }
}

function okit_test_reset_state(): void {
    $GLOBALS['okit_test_options'] = [
        'time_format' => 'H:i',
        'timezone_string' => 'UTC',
    ];
    $GLOBALS['okit_test_registered_settings'] = [];
    $GLOBALS['okit_test_registered_menus'] = [];
    $GLOBALS['okit_test_translations'] = okit_test_translations();
    $GLOBALS['okit_test_site_locale'] = 'en_US';
    $GLOBALS['okit_test_runtime_locale'] = 'en_US';
    $GLOBALS['okit_test_locale_stack'] = [];
    $_GET = [];

    OpenCalendarKit_Admin_OpeningHours::$hours = okit_test_default_hours();
    OpenCalendarKit_Admin_OpeningHours::$note = '';
    OpenCalendarKit_Admin_ClosedDays::$closed_dates = [];
    OpenCalendarKit_Admin_ClosedDays::$open_overrides = [];
    OpenCalendarKit_Admin_ClosedDays::$reasons = [];
    OpenCalendarKit_Admin_EventNotice::$enabled = false;
    OpenCalendarKit_Admin_EventNotice::$content = '';

    okit_test_set_runtime_locale('en_US');
}

require_once '/Users/jvoigt/Projects/OpenCalendarKit/open-calendar-kit/includes/class-opencalendarkit-i18n.php';
require_once '/Users/jvoigt/Projects/OpenCalendarKit/open-calendar-kit/includes/Admin/class-opencalendarkit-admin-settings.php';
require_once '/Users/jvoigt/Projects/OpenCalendarKit/open-calendar-kit/includes/Shortcodes/class-opencalendarkit-shortcode-openinghours.php';
require_once '/Users/jvoigt/Projects/OpenCalendarKit/open-calendar-kit/includes/Shortcodes/class-opencalendarkit-shortcode-statustoday.php';
require_once '/Users/jvoigt/Projects/OpenCalendarKit/open-calendar-kit/includes/Shortcodes/class-opencalendarkit-shortcode-calendar.php';
require_once '/Users/jvoigt/Projects/OpenCalendarKit/open-calendar-kit/includes/Shortcodes/class-opencalendarkit-shortcode-eventnotice.php';

okit_test_reset_state();
