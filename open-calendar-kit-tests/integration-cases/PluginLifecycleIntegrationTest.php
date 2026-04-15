<?php
declare(strict_types=1);

final class PluginLifecycleIntegrationTest extends OpenCalendarKit_IntegrationTestCase {
    public function test_plugin_entry_registers_public_shortcodes_and_hooks(): void {
        $registered_shortcodes = array_keys($GLOBALS['okit_integration_shortcodes']);
        sort($registered_shortcodes);
        do_action('init');

        $this->assertSame(
            ['openkit_calendar', 'openkit_event_notice', 'openkit_opening_hours', 'openkit_status_today'],
            $registered_shortcodes
        );

        $this->assertArrayHasKey('wp_ajax_' . OpenCalendarKit_Plugin::AJAX_CALENDAR_MONTH, $GLOBALS['okit_integration_actions']);
        $this->assertArrayHasKey('wp_ajax_nopriv_' . OpenCalendarKit_Plugin::AJAX_CALENDAR_MONTH, $GLOBALS['okit_integration_actions']);
        $this->assertArrayHasKey('wp_ajax_' . OpenCalendarKit_Plugin::AJAX_ADMIN_CALENDAR_MONTH, $GLOBALS['okit_integration_actions']);
        $this->assertArrayHasKey('wp_ajax_' . OpenCalendarKit_Plugin::AJAX_SAVE_OPEN_EXCEPTION, $GLOBALS['okit_integration_actions']);
        $this->assertArrayHasKey('wp_ajax_' . OpenCalendarKit_Plugin::AJAX_DELETE_OPEN_EXCEPTION, $GLOBALS['okit_integration_actions']);
        $this->assertSame(['OpenCalendarKit_Plugin', 'activate'], $this->getActivationCallback());
        $this->assertSame(['OpenCalendarKit_Plugin', 'uninstall'], $GLOBALS['okit_integration_uninstall_hooks'][OKIT_PLUGIN_MAIN_FILE] ?? null);
    }

    public function test_activation_seeds_defaults_only_on_first_install_and_grants_caps(): void {
        $activation = $this->getActivationCallback();
        call_user_func($activation);

        $this->assertSame(OpenCalendarKit_Admin_OpeningHours::default_hours(), get_option(OpenCalendarKit_Plugin::OPTION_OPENING_HOURS));
        $this->assertSame('', get_option(OpenCalendarKit_Plugin::OPTION_OPENING_HOURS_NOTE));
        $this->assertSame('0', get_option(OpenCalendarKit_Plugin::OPTION_EVENT_NOTICE_ENABLED));
        $this->assertSame('', get_option(OpenCalendarKit_Plugin::OPTION_EVENT_NOTICE_CONTENT));
        $this->assertSame(OpenCalendarKit_Admin_Settings::defaults(), get_option(OpenCalendarKit_Admin_Settings::OPTION_NAME));
        $this->assertArrayHasKey(OpenCalendarKit_Plugin::CPT_CLOSED_DAY, $GLOBALS['okit_integration_post_types']);
        $this->assertTrue(isset($GLOBALS['okit_integration_roles']['administrator']->caps[OpenCalendarKit_Plugin::CAP_MANAGE]));
        $this->assertTrue(isset($GLOBALS['okit_integration_roles']['editor']->caps[OpenCalendarKit_Plugin::CAP_MANAGE]));
        $this->assertSame(1, $GLOBALS['okit_integration_flush_rewrite_calls']);
    }

    public function test_reactivation_does_not_overwrite_existing_content_or_settings(): void {
        update_option(OpenCalendarKit_Plugin::OPTION_OPENING_HOURS, [1 => ['closed' => 1, 'from' => '', 'to' => '']]);
        update_option(OpenCalendarKit_Plugin::OPTION_OPENING_HOURS_NOTE, 'Custom note');
        update_option(OpenCalendarKit_Plugin::OPTION_EVENT_NOTICE_ENABLED, '1');
        update_option(OpenCalendarKit_Plugin::OPTION_EVENT_NOTICE_CONTENT, '<strong>Stored notice</strong>');
        update_option(OpenCalendarKit_Admin_Settings::OPTION_NAME, [
            'show_status_today' => '0',
            'show_calendar_legend' => '0',
            'week_starts_on' => 'sunday',
            'time_format_mode' => '12h',
            'show_opening_hours_title' => '0',
        ]);

        call_user_func($this->getActivationCallback());

        $this->assertSame([1 => ['closed' => 1, 'from' => '', 'to' => '']], get_option(OpenCalendarKit_Plugin::OPTION_OPENING_HOURS));
        $this->assertSame('Custom note', get_option(OpenCalendarKit_Plugin::OPTION_OPENING_HOURS_NOTE));
        $this->assertSame('1', get_option(OpenCalendarKit_Plugin::OPTION_EVENT_NOTICE_ENABLED));
        $this->assertSame('<strong>Stored notice</strong>', get_option(OpenCalendarKit_Plugin::OPTION_EVENT_NOTICE_CONTENT));
        $this->assertSame('sunday', get_option(OpenCalendarKit_Admin_Settings::OPTION_NAME)['week_starts_on']);
    }

    public function test_admin_calendar_page_enqueues_admin_script_via_hook(): void {
        $plugin = $GLOBALS['okit_integration_plugin_instance'];
        $plugin->enqueue_admin_assets('open-calendar-kit_page_open-calendar-kit-calendar');

        $this->assertArrayHasKey('open-calendar-kit', $GLOBALS['okit_integration_styles']);
        $this->assertArrayHasKey('open-calendar-kit-admin', $GLOBALS['okit_integration_scripts']);
        $this->assertSame(
            'OPEN_CALENDAR_KIT_ADMIN',
            $GLOBALS['okit_integration_localized_scripts']['open-calendar-kit-admin']['object_name'] ?? null
        );
    }

    public function test_ajax_open_exception_can_be_created_and_removed(): void {
        okit_integration_add_closed_day('2026-04-20', 'Legacy closure');

        $_POST = [
            'nonce' => 'nonce-openkit_admin',
            'date' => '2026-04-20',
        ];

        try {
            OpenCalendarKit_Admin_ClosedDays::ajax_save_open_exception();
            throw new RuntimeException('Expected ajax_save_open_exception to exit via wp_send_json_success.');
        } catch (RuntimeException $exception) {
            $payload = json_decode($exception->getMessage(), true);
            $this->assertTrue($payload['success'] ?? false);
        }

        $this->assertTrue(OpenCalendarKit_Admin_ClosedDays::is_open_exception_on('2026-04-20'));
        $this->assertFalse(OpenCalendarKit_Admin_ClosedDays::is_closed_on('2026-04-20'));

        $_POST = [
            'nonce' => 'nonce-openkit_admin',
            'date' => '2026-04-20',
        ];

        try {
            OpenCalendarKit_Admin_ClosedDays::ajax_delete_open_exception();
            throw new RuntimeException('Expected ajax_delete_open_exception to exit via wp_send_json_success.');
        } catch (RuntimeException $exception) {
            $payload = json_decode($exception->getMessage(), true);
            $this->assertTrue($payload['success'] ?? false);
        }

        $this->assertFalse(OpenCalendarKit_Admin_ClosedDays::is_open_exception_on('2026-04-20'));
    }

    public function test_saving_closed_day_metabox_preserves_open_exception_state(): void {
        $post_id = okit_integration_add_open_override('2026-04-20');

        $_POST = [
            'openkit_closed_day_meta_nonce' => 'nonce-openkit_closed_day_meta',
            'openkit_date' => '2026-04-20',
            'openkit_reason' => '',
            'openkit_state' => 'open',
        ];

        OpenCalendarKit_Admin_ClosedDays::save_metabox($post_id);

        $this->assertTrue(OpenCalendarKit_Admin_ClosedDays::is_open_exception_on('2026-04-20'));
        $this->assertFalse(OpenCalendarKit_Admin_ClosedDays::is_closed_on('2026-04-20'));
        $this->assertSame('open', get_post_meta($post_id, '_bk_state', true));
    }
}
