<?php

namespace Brucep\WordPress\RemoveExtraneousness;

class RemoveExtraneousness
{
    public static function byFireBePurged(): void
    {
        self::comments();
        self::emojiScripts();
        self::gutenburg();
        self::oembed();
        self::postLock();
        self::rsd();
        self::shortlink();
        self::wlwmanifest();
        self::wordPressInfo();
    }

    public static function comments(): void
    {
        add_action('admin_menu', function () {
            remove_menu_page('edit-comments.php');
            remove_submenu_page('options-general.php', 'options-discussion.php');
        }, 99);

        add_action('admin_bar_menu', fn ($bar) => $bar->remove_node('comments'), 99);
        add_filter('feed_links_show_comments_feed', fn () => false, 99);

        $notFound = function () {
            @header('HTTP/1.1 404 Not Found');
            exit();
        };

        add_action('do_feed_rss2_comments', $notFound, -99);
        add_action('do_feed_atom_comments', $notFound, -99);
    }

    public static function emojiScripts(): void
    {
        add_filter('emoji_svg_url', '__return_false');
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('wp_print_styles', 'print_emoji_styles');
    }

    public static function gutenburg(): void
    {
        add_action('wp_enqueue_scripts', fn () => wp_dequeue_style('wp-block-library'));
    }

    public static function oembed(): void
    {
        remove_action('template_redirect', 'rest_output_link_header', 11, 0);
        remove_action('wp_head', 'rest_output_link_wp_head');
        remove_action('wp_head', 'wp_oembed_add_host_js');
        remove_action('wp_head', 'wp_oembed_add_discovery_links');
        remove_filter('oembed_dataparse', 'wp_filter_oembed_result');
    }

    public static function postLock(): void
    {
        // https://wordpress.stackexchange.com/questions/120179/how-to-disable-the-post-lock-edit-lock
        add_filter('wp_check_post_lock_window', '__return_false');
        add_filter('heartbeat_settings', function ($settings) {
            return wp_parse_args(['autostart' => false], $settings);
        });
    }

    public static function rsd(): void
    {
        remove_action('wp_head', 'rsd_link');
    }

    public static function shortlink(): void
    {
        remove_action('template_redirect', 'wp_shortlink_header', 11);
        remove_action('wp_head', 'wp_shortlink_wp_head');
    }

    public static function wlwmanifest(): void
    {
        remove_action('wp_head', 'wlwmanifest_link');
    }

    public static function wordPressInfo(): void
    {
        // Remove "WordPress" from redirects
        add_filter('x_redirect_by', fn () => get_bloginfo('name'), 5, 0);
        add_filter('the_generator', fn () => '');
    }

    private function __construct()
    {
    }
}
