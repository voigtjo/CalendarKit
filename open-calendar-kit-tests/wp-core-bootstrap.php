<?php
declare(strict_types=1);

if (!defined('OKIT_WORDPRESS_DIR')) {
    define('OKIT_WORDPRESS_DIR', '/Users/jvoigt/Projects/OpenCalendarKit/wordpress');
}

if (!defined('ABSPATH')) {
    define('ABSPATH', OKIT_WORDPRESS_DIR . '/');
}

if (!defined('WPINC')) {
    define('WPINC', 'wp-includes');
}

if (!defined('WP_PLUGIN_DIR')) {
    define('WP_PLUGIN_DIR', ABSPATH . 'wp-content/plugins');
}

if (!defined('WPMU_PLUGIN_DIR')) {
    define('WPMU_PLUGIN_DIR', ABSPATH . 'wp-content/mu-plugins');
}

if (!defined('WP_PLUGIN_URL')) {
    define('WP_PLUGIN_URL', 'https://example.test/wp-content/plugins');
}

if (!defined('WPMU_PLUGIN_URL')) {
    define('WPMU_PLUGIN_URL', 'https://example.test/wp-content/mu-plugins');
}

if (!defined('OKIT_WP_CORE_PLUGIN_FILE')) {
    define('OKIT_WP_CORE_PLUGIN_FILE', ABSPATH . 'wp-content/plugins/open-calendar-kit/open-calendar-kit.php');
}

$wp_plugin_paths = [];
$GLOBALS['okit_wp_core_options'] = [
    'time_format' => 'H:i',
    'timezone_string' => 'UTC',
];

final class OKit_WPCore_Test_Role {
    public string $name;
    public array $caps = [];

    public function __construct(string $name) {
        $this->name = $name;
    }

    public function add_cap(string $cap): void {
        $this->caps[$cap] = true;
    }
}

final class WP_Error {
    private string $message;

    public function __construct(string $code = '', string $message = '') {
        $this->message = $message;
    }

    public function get_error_message(): string {
        return $this->message;
    }
}

final class WP_Query {
    public array $posts = [];

    public function __construct(array $args = []) {
        $all_posts = $GLOBALS['okit_wp_core_posts'] ?? [];
        $meta = $GLOBALS['okit_wp_core_post_meta'] ?? [];

        foreach ($all_posts as $post) {
            if (($args['post_type'] ?? null) !== null && ($post['post_type'] ?? null) !== $args['post_type']) {
                continue;
            }

            if (($args['post_status'] ?? null) !== null && ($post['post_status'] ?? null) !== $args['post_status']) {
                continue;
            }

            $matches_meta = true;
            foreach (($args['meta_query'] ?? []) as $clause) {
                $post_meta = $meta[$post['ID']][$clause['key']] ?? null;
                if (($clause['compare'] ?? '=') === '=' && $post_meta !== $clause['value']) {
                    $matches_meta = false;
                    break;
                }
            }

            if (!$matches_meta) {
                continue;
            }

            $this->posts[] = (($args['fields'] ?? '') === 'ids') ? $post['ID'] : (object) $post;
        }

        if (($args['posts_per_page'] ?? 0) > 0) {
            $this->posts = array_slice($this->posts, 0, (int) $args['posts_per_page']);
        }
    }

    public function have_posts(): bool {
        return $this->posts !== [];
    }
}

function okit_wp_core_translations(): array {
    return [
        'de_DE' => [
            'Reason:' => 'Grund:',
            'Opening Hours' => 'Öffnungszeiten',
            'Closed' => 'Geschlossen',
            'Open' => 'Offen',
            'Today closed' => 'Heute geschlossen',
            'Opens today at %s' => 'Öffnet heute um %s',
            'Open now until %s' => 'Jetzt geöffnet bis %s',
            'Closed now' => 'Jetzt geschlossen',
            'Open now' => 'Jetzt geöffnet',
            'Previous month' => 'Vorheriger Monat',
            'Next month' => 'Nächster Monat',
        ],
        'fr_FR' => [
            'Reason:' => 'Raison :',
            'Opening Hours' => 'Horaires d\'ouverture',
            'Closed' => 'Fermé',
            'Open' => 'Ouvert',
            'Today closed' => 'Fermé aujourd\'hui',
            'Opens today at %s' => 'Ouvre aujourd\'hui à %s',
            'Open now until %s' => 'Ouvert maintenant jusqu\'à %s',
            'Closed now' => 'Fermé maintenant',
            'Open now' => 'Ouvert maintenant',
            'Previous month' => 'Mois précédent',
            'Next month' => 'Mois suivant',
        ],
    ];
}

function okit_wp_core_weekday_abbrev_for_locale(string $locale): array {
    $labels = [
        'en_US' => ['Sunday' => 'Sun.', 'Monday' => 'Mon.', 'Tuesday' => 'Tue.', 'Wednesday' => 'Wed.', 'Thursday' => 'Thu.', 'Friday' => 'Fri.', 'Saturday' => 'Sat.'],
        'de_DE' => ['Sunday' => 'So.', 'Monday' => 'Mo.', 'Tuesday' => 'Di.', 'Wednesday' => 'Mi.', 'Thursday' => 'Do.', 'Friday' => 'Fr.', 'Saturday' => 'Sa.'],
        'fr_FR' => ['Sunday' => 'dim.', 'Monday' => 'lun.', 'Tuesday' => 'mar.', 'Wednesday' => 'mer.', 'Thursday' => 'jeu.', 'Friday' => 'ven.', 'Saturday' => 'sam.'],
    ];

    return $labels[$locale] ?? $labels['en_US'];
}

function okit_wp_core_set_runtime_locale(string $locale): void {
    $GLOBALS['okit_wp_core_runtime_locale'] = $locale;
    $GLOBALS['wp_locale'] = (object) [
        'weekday_abbrev' => okit_wp_core_weekday_abbrev_for_locale($locale),
    ];
}

if (!function_exists('__')) {
    function __($text, $domain = null) {
        $locale = $GLOBALS['okit_wp_core_runtime_locale'] ?? 'en_US';
        $translations = $GLOBALS['okit_wp_core_translations'][$locale] ?? [];
        return (string) ($translations[(string) $text] ?? $text);
    }
}

if (!function_exists('_x')) {
    function _x($text, $context, $domain = null) {
        return (string) $text;
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

if (!function_exists('esc_textarea')) {
    function esc_textarea($text) {
        return htmlspecialchars((string) $text, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('_doing_it_wrong')) {
    function _doing_it_wrong($function_name, $message, $version): void {
    }
}

if (!function_exists('trailingslashit')) {
    function trailingslashit($value) {
        return rtrim((string) $value, '/\\') . '/';
    }
}

if (!function_exists('wp_normalize_path')) {
    function wp_normalize_path($path) {
        return str_replace('\\', '/', (string) $path);
    }
}

if (!function_exists('plugins_url')) {
    function plugins_url($path = '', $plugin = '') {
        $base = WP_PLUGIN_URL;
        $plugin = wp_normalize_path((string) $plugin);
        if ($plugin !== '' && str_starts_with($plugin, wp_normalize_path(WP_PLUGIN_DIR))) {
            $relative_dir = trim(dirname(substr($plugin, strlen(wp_normalize_path(WP_PLUGIN_DIR)))), '/');
            if ($relative_dir !== '' && $relative_dir !== '.') {
                $base .= '/' . $relative_dir;
            }
        }
        $path = ltrim((string) $path, '/');
        return $path !== '' ? $base . '/' . $path : $base;
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

if (!function_exists('sanitize_textarea_field')) {
    function sanitize_textarea_field($value) {
        return trim(strip_tags((string) $value));
    }
}

if (!function_exists('wp_unslash')) {
    function wp_unslash($value) {
        return is_string($value) ? stripslashes($value) : $value;
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

if (!function_exists('wpautop')) {
    function wpautop($content, $br = true) {
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

if (!function_exists('wp_html_split')) {
    function wp_html_split($input) {
        return [(string) $input];
    }
}

if (!function_exists('wp_kses_attr_parse')) {
    function wp_kses_attr_parse($element) {
        return false;
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

if (!function_exists('wp_editor')) {
    function wp_editor($content, $editor_id, $settings = []): void {
        $name = $settings['textarea_name'] ?? $editor_id;
        echo '<textarea name="' . esc_attr((string) $name) . '">' . esc_textarea((string) $content) . '</textarea>';
    }
}

if (!function_exists('register_setting')) {
    function register_setting($option_group, $option_name, $args = []): void {
        $GLOBALS['okit_wp_core_registered_settings'][$option_group][$option_name] = $args;
    }
}

if (!function_exists('add_menu_page')) {
    function add_menu_page($page_title, $menu_title, $capability, $menu_slug, $callback = null, $icon_url = '', $position = null) {
        $GLOBALS['okit_wp_core_menus'][] = compact('page_title', 'menu_title', 'capability', 'menu_slug');
        return $menu_slug;
    }
}

if (!function_exists('add_submenu_page')) {
    function add_submenu_page($parent_slug, $page_title, $menu_title, $capability, $menu_slug, $callback = null) {
        $GLOBALS['okit_wp_core_submenus'][] = compact('parent_slug', 'page_title', 'menu_title', 'capability', 'menu_slug');
        return $menu_slug;
    }
}

if (!function_exists('add_meta_box')) {
    function add_meta_box($id, $title, $callback, $screen, $context = 'advanced', $priority = 'default'): void {
    }
}

if (!function_exists('admin_url')) {
    function admin_url($path = '') {
        return 'https://example.test/wp-admin/' . ltrim((string) $path, '/');
    }
}

if (!function_exists('wp_create_nonce')) {
    function wp_create_nonce($action = -1) {
        return 'nonce-' . (string) $action;
    }
}

if (!function_exists('determine_locale')) {
    function determine_locale() {
        return $GLOBALS['okit_wp_core_runtime_locale'] ?? ($GLOBALS['okit_wp_core_site_locale'] ?? 'en_US');
    }
}

if (!function_exists('get_locale')) {
    function get_locale() {
        return $GLOBALS['okit_wp_core_site_locale'] ?? 'en_US';
    }
}

if (!function_exists('switch_to_locale')) {
    function switch_to_locale($locale) {
        $GLOBALS['okit_wp_core_locale_stack'][] = determine_locale();
        okit_wp_core_set_runtime_locale((string) $locale);
        return true;
    }
}

if (!function_exists('restore_previous_locale')) {
    function restore_previous_locale() {
        $previous = array_pop($GLOBALS['okit_wp_core_locale_stack']);
        okit_wp_core_set_runtime_locale(is_string($previous) && $previous !== '' ? $previous : get_locale());
        return $previous !== null;
    }
}

if (!function_exists('get_option')) {
    function get_option($option, $default = false) {
        return array_key_exists($option, $GLOBALS['okit_wp_core_options']) ? $GLOBALS['okit_wp_core_options'][$option] : $default;
    }
}

if (!function_exists('add_option')) {
    function add_option($option, $value = ''): void {
        if (!array_key_exists($option, $GLOBALS['okit_wp_core_options'])) {
            $GLOBALS['okit_wp_core_options'][$option] = $value;
        }
    }
}

if (!function_exists('update_option')) {
    function update_option($option, $value): void {
        $GLOBALS['okit_wp_core_options'][$option] = $value;
    }
}

if (!function_exists('get_role')) {
    function get_role($role) {
        if (!isset($GLOBALS['okit_wp_core_roles'][$role])) {
            $GLOBALS['okit_wp_core_roles'][$role] = new OKit_WPCore_Test_Role((string) $role);
        }
        return $GLOBALS['okit_wp_core_roles'][$role];
    }
}

if (!function_exists('flush_rewrite_rules')) {
    function flush_rewrite_rules(): void {
        $GLOBALS['okit_wp_core_flush_rewrite_calls']++;
    }
}

if (!function_exists('register_post_type')) {
    function register_post_type($post_type, $args = []) {
        $GLOBALS['okit_wp_core_post_types'][$post_type] = $args;
        return (object) ['name' => $post_type];
    }
}

if (!function_exists('current_user_can')) {
    function current_user_can($capability): bool {
        return true;
    }
}

if (!function_exists('wp_nonce_field')) {
    function wp_nonce_field($action = -1, $name = '_wpnonce'): void {
        echo '<input type="hidden" name="' . esc_attr((string) $name) . '" value="nonce-' . esc_attr((string) $action) . '">';
    }
}

if (!function_exists('wp_verify_nonce')) {
    function wp_verify_nonce($nonce, $action = -1) {
        return true;
    }
}

if (!function_exists('check_ajax_referer')) {
    function check_ajax_referer($action = -1, $query_arg = false, $stop = true) {
        return true;
    }
}

if (!function_exists('is_admin')) {
    function is_admin(): bool {
        return true;
    }
}

if (!function_exists('get_post_type')) {
    function get_post_type($post = null) {
        return $GLOBALS['okit_wp_core_current_post_type'] ?? '';
    }
}

if (!function_exists('wp_enqueue_style')) {
    function wp_enqueue_style($handle, $src = '', $deps = [], $ver = false, $media = 'all'): void {
        $GLOBALS['okit_wp_core_styles'][$handle] = compact('src', 'deps', 'ver', 'media');
    }
}

if (!function_exists('wp_enqueue_script')) {
    function wp_enqueue_script($handle, $src = '', $deps = [], $ver = false, $in_footer = false): void {
        $GLOBALS['okit_wp_core_scripts'][$handle] = compact('src', 'deps', 'ver', 'in_footer');
    }
}

if (!function_exists('wp_localize_script')) {
    function wp_localize_script($handle, $object_name, $l10n): void {
        $GLOBALS['okit_wp_core_localized_scripts'][$handle] = compact('object_name', 'l10n');
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
    function date_i18n($format, $timestamp_with_offset = false, $gmt = false) {
        return date($format, (int) $timestamp_with_offset);
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

if (!function_exists('get_post_meta')) {
    function get_post_meta($post_id, $key = '', $single = false) {
        $value = $GLOBALS['okit_wp_core_post_meta'][$post_id][$key] ?? '';
        return $single ? $value : [$value];
    }
}

if (!function_exists('update_post_meta')) {
    function update_post_meta($post_id, $key, $value): void {
        $GLOBALS['okit_wp_core_post_meta'][$post_id][$key] = $value;
    }
}

if (!function_exists('wp_update_post')) {
    function wp_update_post($postarr = [], $wp_error = false, $fire_after_hooks = true) {
        $id = (int) ($postarr['ID'] ?? 0);
        if ($id > 0 && isset($GLOBALS['okit_wp_core_posts'][$id])) {
            $GLOBALS['okit_wp_core_posts'][$id] = array_merge($GLOBALS['okit_wp_core_posts'][$id], $postarr);
        }
        return $id;
    }
}

if (!function_exists('get_the_title')) {
    function get_the_title($post_id = 0) {
        return $GLOBALS['okit_wp_core_posts'][$post_id]['post_title'] ?? '';
    }
}

if (!function_exists('wp_insert_post')) {
    function wp_insert_post($postarr = [], $wp_error = false) {
        $id = ++$GLOBALS['okit_wp_core_next_post_id'];
        $GLOBALS['okit_wp_core_posts'][$id] = array_merge([
            'ID' => $id,
            'post_type' => '',
            'post_status' => 'publish',
            'post_title' => '',
        ], $postarr, ['ID' => $id]);
        return $id;
    }
}

if (!function_exists('wp_delete_post')) {
    function wp_delete_post($post_id, $force_delete = false) {
        unset($GLOBALS['okit_wp_core_posts'][$post_id], $GLOBALS['okit_wp_core_post_meta'][$post_id]);
    }
}

if (!function_exists('is_wp_error')) {
    function is_wp_error($thing): bool {
        return $thing instanceof WP_Error;
    }
}

if (!function_exists('wp_reset_postdata')) {
    function wp_reset_postdata(): void {
    }
}

if (!function_exists('wp_send_json_success')) {
    function wp_send_json_success($data = null, $status_code = null): void {
        throw new RuntimeException(json_encode(['success' => true, 'data' => $data], JSON_THROW_ON_ERROR));
    }
}

if (!function_exists('wp_send_json_error')) {
    function wp_send_json_error($data = null, $status_code = null): void {
        throw new RuntimeException(json_encode(['success' => false, 'data' => $data, 'status' => $status_code], JSON_THROW_ON_ERROR));
    }
}

require_once ABSPATH . WPINC . '/plugin.php';
require_once ABSPATH . WPINC . '/shortcodes.php';
require_once ABSPATH . WPINC . '/class-wp-locale.php';
require_once OKIT_WP_CORE_PLUGIN_FILE;

function okit_wp_core_register_plugin_runtime(): void {
    new OpenCalendarKit_Plugin();
    register_activation_hook(OKIT_WP_CORE_PLUGIN_FILE, ['OpenCalendarKit_Plugin', 'activate']);
    register_uninstall_hook(OKIT_WP_CORE_PLUGIN_FILE, ['OpenCalendarKit_Plugin', 'uninstall']);
}

function okit_wp_core_reset_state(): void {
    global $shortcode_tags, $wp_filter, $wp_actions, $wp_filters, $wp_current_filter, $wp_plugin_paths;

    $shortcode_tags = [];
    $wp_filter = [];
    $wp_actions = [];
    $wp_filters = [];
    $wp_current_filter = [];
    $wp_plugin_paths = [];

    $GLOBALS['okit_wp_core_registered_settings'] = [];
    $GLOBALS['okit_wp_core_menus'] = [];
    $GLOBALS['okit_wp_core_submenus'] = [];
    $GLOBALS['okit_wp_core_styles'] = [];
    $GLOBALS['okit_wp_core_scripts'] = [];
    $GLOBALS['okit_wp_core_localized_scripts'] = [];
    $GLOBALS['okit_wp_core_post_types'] = [];
    $GLOBALS['okit_wp_core_loaded_textdomains'] = [];
    $GLOBALS['okit_wp_core_translations'] = okit_wp_core_translations();
    $GLOBALS['okit_wp_core_site_locale'] = 'en_US';
    $GLOBALS['okit_wp_core_runtime_locale'] = 'en_US';
    $GLOBALS['okit_wp_core_locale_stack'] = [];
    $GLOBALS['okit_wp_core_options'] = [
        'time_format' => 'H:i',
        'timezone_string' => 'UTC',
    ];
    $GLOBALS['okit_wp_core_roles'] = [
        'administrator' => new OKit_WPCore_Test_Role('administrator'),
        'editor' => new OKit_WPCore_Test_Role('editor'),
    ];
    $GLOBALS['okit_wp_core_flush_rewrite_calls'] = 0;
    $GLOBALS['okit_wp_core_posts'] = [];
    $GLOBALS['okit_wp_core_post_meta'] = [];
    $GLOBALS['okit_wp_core_next_post_id'] = 100;
    $GLOBALS['okit_wp_core_current_post_type'] = '';
    $_GET = [];
    $_POST = [];

    okit_wp_core_set_runtime_locale('en_US');
    okit_wp_core_register_plugin_runtime();
}

function okit_wp_core_add_closed_day(string $date, string $reason = ''): int {
    $post_id = wp_insert_post([
        'post_type' => OpenCalendarKit_Plugin::CPT_CLOSED_DAY,
        'post_status' => 'publish',
        'post_title' => sprintf('Closed: %s', $date),
    ]);
    update_post_meta($post_id, '_bk_date', $date);
    update_post_meta($post_id, '_bk_reason', $reason);
    update_post_meta($post_id, '_bk_state', 'closed');
    return $post_id;
}

function okit_wp_core_add_open_override(string $date): int {
    $post_id = wp_insert_post([
        'post_type' => OpenCalendarKit_Plugin::CPT_CLOSED_DAY,
        'post_status' => 'publish',
        'post_title' => sprintf('Open exceptionally: %s', $date),
    ]);
    update_post_meta($post_id, '_bk_date', $date);
    update_post_meta($post_id, '_bk_reason', '');
    update_post_meta($post_id, '_bk_state', 'open');
    return $post_id;
}

okit_wp_core_reset_state();
