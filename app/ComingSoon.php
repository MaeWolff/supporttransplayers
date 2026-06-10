<?php

namespace App;

class ComingSoon
{
    public const OPTION_ENABLED = 'stp_coming_soon_enabled';

    public const OPTION_PAGE_ID = 'stp_coming_soon_page_id';

    public static function isEnabled(): bool
    {
        return (bool) get_option(self::OPTION_ENABLED, false);
    }

    public static function canBypass(): bool
    {
        return is_user_logged_in() && current_user_can('edit_posts');
    }

    public static function resolvePageId(): ?int
    {
        $pageId = (int) get_option(self::OPTION_PAGE_ID, 0);

        if ($pageId <= 0) {
            return null;
        }

        if (function_exists('pll_get_post')) {
            $translatedId = pll_get_post($pageId);

            if (is_int($translatedId) && $translatedId > 0) {
                return $translatedId;
            }
        }

        return $pageId;
    }

    public static function isComingSoonPage(): bool
    {
        $pageId = self::resolvePageId();

        if ($pageId === null) {
            return false;
        }

        return is_page($pageId);
    }

    public static function redirectIfNeeded(): void
    {
        if (! self::isEnabled()) {
            return;
        }

        if (is_admin() || wp_doing_ajax() || wp_doing_cron()) {
            return;
        }

        if (self::canBypass()) {
            return;
        }

        $pageId = self::resolvePageId();

        if ($pageId === null) {
            return;
        }

        if (self::isComingSoonPage()) {
            return;
        }

        $url = get_permalink($pageId);

        if (! is_string($url) || $url === '') {
            return;
        }

        wp_safe_redirect($url, 302);
        exit;
    }
}

add_action('admin_menu', function () {
    add_theme_page(
        __('En construction', 'sage'),
        __('En construction', 'sage'),
        'manage_options',
        'stp-coming-soon',
        function () {
            if (! current_user_can('manage_options')) {
                return;
            }

            $enabled = ComingSoon::isEnabled();
            $pageId = (int) get_option(ComingSoon::OPTION_PAGE_ID, 0);
            ?>
            <div class="wrap">
                <h1><?php echo esc_html(__('Mode en construction', 'sage')); ?></h1>
                <p><?php echo esc_html(__('Les visiteurs non connectés sont redirigés vers la page sélectionnée. Les éditeurs et administrateurs voient le site complet.', 'sage')); ?></p>
                <form method="post" action="options.php">
                    <?php
                    settings_fields('stp_coming_soon');
            do_settings_sections('stp_coming_soon');
            ?>
                    <table class="form-table" role="presentation">
                        <tr>
                            <th scope="row"><?php echo esc_html(__('Activer', 'sage')); ?></th>
                            <td>
                                <input
                                    type="hidden"
                                    name="<?php echo esc_attr(ComingSoon::OPTION_ENABLED); ?>"
                                    value="0"
                                />
                                <label>
                                    <input
                                        type="checkbox"
                                        name="<?php echo esc_attr(ComingSoon::OPTION_ENABLED); ?>"
                                        value="1"
                                        <?php checked($enabled); ?>
                                    />
                                    <?php echo esc_html(__('Rediriger les visiteurs vers la page en construction', 'sage')); ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="stp_coming_soon_page_id"><?php echo esc_html(__('Page en construction (FR)', 'sage')); ?></label>
                            </th>
                            <td>
                                <?php
                        wp_dropdown_pages([
                            'name' => ComingSoon::OPTION_PAGE_ID,
                            'id' => 'stp_coming_soon_page_id',
                            'selected' => $pageId,
                            'show_option_none' => __('— Sélectionner —', 'sage'),
                            'option_none_value' => '0',
                        ]);
            ?>
                                <p class="description">
                                    <?php echo esc_html(__('Utilisez le template « En construction ». Liez la traduction EN via Polylang.', 'sage')); ?>
                                </p>
                            </td>
                        </tr>
                    </table>
                    <?php submit_button(); ?>
                </form>
            </div>
            <?php
        }
    );
});

add_action('admin_init', function () {
    register_setting('stp_coming_soon', ComingSoon::OPTION_ENABLED, [
        'type' => 'boolean',
        'sanitize_callback' => fn ($value): bool => (bool) $value,
        'default' => false,
    ]);

    register_setting('stp_coming_soon', ComingSoon::OPTION_PAGE_ID, [
        'type' => 'integer',
        'sanitize_callback' => fn ($value): int => max(0, (int) $value),
        'default' => 0,
    ]);
});

add_action('template_redirect', [ComingSoon::class, 'redirectIfNeeded'], 1);
