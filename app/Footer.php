<?php

namespace App;

class Footer
{
    /** @var list<string> */
    public const LANGUAGES = ['fr', 'en'];

    /** @var list<string> */
    public const LANG_TEXT_KEYS = ['contact_label', 'credit_intro', 'credit_joiner', 'copyright'];

    /** @var list<string> */
    public const TOGGLE_KEYS = [
        'show_contact',
        'show_menu',
        'show_legal',
        'show_social',
        'show_language',
        'show_logo',
        'show_credit',
    ];

    /**
     * @return array<string, mixed>
     */
    public static function defaults(): array
    {
        return [
            'fr' => [
                'contact_label' => 'Contact',
                'credit_intro' => 'La campagne a été lancée par',
                'credit_joiner' => 'et',
                'copyright' => '© {year} Support Trans Players',
            ],
            'en' => [
                'contact_label' => 'Contact',
                'credit_intro' => 'The campaign was launched by',
                'credit_joiner' => 'and',
                'copyright' => '© {year} Support Trans Players',
            ],
            'contact_email' => 'contact@supporttransplayers.org',
            'legal_page_id' => 0,
            'horizontal_logo_id' => 0,
            'transpire_url' => '',
            'plaidact_url' => '',
            'social_links' => [],
            'show_contact' => true,
            'show_menu' => true,
            'show_legal' => true,
            'show_social' => true,
            'show_language' => true,
            'show_logo' => true,
            'show_credit' => true,
        ];
    }

    public static function themeModKey(string $key): string
    {
        return "stp_footer_{$key}";
    }

    /**
     * @return array<string, mixed>
     */
    public static function settings(): array
    {
        $defaults = self::defaults();
        $settings = $defaults;

        foreach (self::TOGGLE_KEYS as $key) {
            $settings[$key] = (bool) get_theme_mod(self::themeModKey($key), $defaults[$key]);
        }

        $settings['contact_email'] = (string) get_theme_mod(
            self::themeModKey('contact_email'),
            $defaults['contact_email']
        );

        $settings['legal_page_id'] = max(0, (int) get_theme_mod(
            self::themeModKey('legal_page_id'),
            $defaults['legal_page_id']
        ));

        $settings['horizontal_logo_id'] = max(0, (int) get_theme_mod(
            self::themeModKey('horizontal_logo_id'),
            $defaults['horizontal_logo_id']
        ));

        $settings['transpire_url'] = (string) get_theme_mod(
            self::themeModKey('transpire_url'),
            $defaults['transpire_url']
        );

        $settings['plaidact_url'] = (string) get_theme_mod(
            self::themeModKey('plaidact_url'),
            $defaults['plaidact_url']
        );

        $settings['social_links'] = self::decodeSocialLinks(
            get_theme_mod(self::themeModKey('social_links'), '')
        );

        foreach (self::LANGUAGES as $lang) {
            foreach (self::LANG_TEXT_KEYS as $key) {
                $settings[$lang][$key] = (string) get_theme_mod(
                    self::themeModKey("{$lang}_{$key}"),
                    $defaults[$lang][$key]
                );
            }
        }

        return $settings;
    }

    /**
     * @param  mixed  $value
     * @return array<int, array{label: string, url: string, platform: string}>
     */
    public static function decodeSocialLinks($value): array
    {
        if (is_array($value)) {
            return self::sanitizeSocialLinks($value);
        }

        if (! is_string($value) || $value === '') {
            return [];
        }

        $decoded = json_decode($value, true);

        return is_array($decoded) ? self::sanitizeSocialLinks($decoded) : [];
    }

    /**
     * @param  mixed  $links
     * @return array<int, array{label: string, url: string, platform: string}>
     */
    public static function sanitizeSocialLinks($links): array
    {
        if (! is_array($links)) {
            return [];
        }

        $normalized = [];

        foreach ($links as $link) {
            if (! is_array($link)) {
                continue;
            }

            $label = sanitize_text_field((string) ($link['label'] ?? ''));
            $url = esc_url_raw((string) ($link['url'] ?? ''));
            $platform = sanitize_key((string) ($link['platform'] ?? ''));

            if ($label === '' || $url === '') {
                continue;
            }

            $normalized[] = [
                'label' => $label,
                'url' => $url,
                'platform' => $platform,
            ];
        }

        return $normalized;
    }

    public static function currentLanguage(): string
    {
        if (function_exists('pll_current_language')) {
            $lang = pll_current_language('slug');

            if (is_string($lang) && in_array($lang, self::LANGUAGES, true)) {
                return $lang;
            }
        }

        return 'fr';
    }

    public static function get(string $key, ?string $lang = null): string
    {
        $settings = self::settings();
        $lang = $lang ?? self::currentLanguage();

        if (! in_array($lang, self::LANGUAGES, true)) {
            $lang = 'fr';
        }

        $value = $settings[$lang][$key] ?? $settings['fr'][$key] ?? '';

        if ($key === 'copyright') {
            $value = str_replace('{year}', (string) date('Y'), $value);
        }

        return $value;
    }

    public static function contactEmail(): string
    {
        $email = self::settings()['contact_email'] ?? '';

        return is_email($email) ? $email : self::defaults()['contact_email'];
    }

    public static function legalPageId(): ?int
    {
        $pageId = (int) (self::settings()['legal_page_id'] ?? 0);

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

    public static function legalUrl(): ?string
    {
        $pageId = self::legalPageId();

        if ($pageId === null) {
            return null;
        }

        $url = get_permalink($pageId);

        return is_string($url) && $url !== '' ? $url : null;
    }

    /**
     * @return array<int, array{label: string, url: string, platform: string}>
     */
    public static function socialLinks(): array
    {
        $links = self::settings()['social_links'] ?? [];

        return is_array($links) ? $links : [];
    }

    public static function transpireUrl(): ?string
    {
        return self::normalizePartnerUrl((string) (self::settings()['transpire_url'] ?? ''));
    }

    public static function plaidactUrl(): ?string
    {
        return self::normalizePartnerUrl((string) (self::settings()['plaidact_url'] ?? ''));
    }

    public static function normalizePartnerUrl(string $url): ?string
    {
        $url = trim($url);

        if ($url === '') {
            return null;
        }

        if (! preg_match('#^https?://#i', $url)) {
            $url = 'https://'.$url;
        }

        $sanitized = esc_url_raw($url);

        return $sanitized !== '' ? $sanitized : null;
    }

    /**
     * @return array{id: int, url: string, alt: string}|null
     */
    public static function horizontalLogo(): ?array
    {
        $attachmentId = (int) (self::settings()['horizontal_logo_id'] ?? 0);

        if ($attachmentId <= 0) {
            return null;
        }

        $url = wp_get_attachment_image_url($attachmentId, 'full');

        if (! is_string($url) || $url === '') {
            return null;
        }

        $alt = get_post_meta($attachmentId, '_wp_attachment_image_alt', true);

        return [
            'id' => $attachmentId,
            'url' => $url,
            'alt' => is_string($alt) && $alt !== '' ? $alt : __('Logo partenaires', 'sage'),
        ];
    }

    public static function isVisible(string $toggleKey): bool
    {
        if (! in_array($toggleKey, self::TOGGLE_KEYS, true)) {
            return false;
        }

        return (bool) (self::settings()[$toggleKey] ?? false);
    }
}

add_action('customize_register', function (\WP_Customize_Manager $wp_customize): void {
    require_once get_theme_file_path('app/Customizer/FooterCustomizer.php');

    Customizer\FooterCustomizer::register($wp_customize);
});

add_action('customize_controls_enqueue_scripts', function (): void {
    require_once get_theme_file_path('app/Customizer/FooterCustomizer.php');

    Customizer\FooterCustomizer::enqueueControlsScript();
});
