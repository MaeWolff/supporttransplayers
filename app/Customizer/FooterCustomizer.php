<?php

namespace App\Customizer;

use App\Footer;
use App\Support\FooterData;
use WP_Customize_Control;
use WP_Customize_Manager;

class Stp_Footer_Social_Links_Control extends WP_Customize_Control
{
    public $type = 'stp_footer_social_links';

    public function render_content(): void
    {
        $links = Footer::decodeSocialLinks($this->value());

        if ($links === []) {
            $links = [['label' => '', 'url' => '', 'platform' => '']];
        }
        ?>
        <label>
            <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
            <?php if ($this->description !== '') : ?>
                <span class="description customize-control-description"><?php echo esc_html($this->description); ?></span>
            <?php endif; ?>
        </label>
        <div class="stp-customizer-social" data-input-id="<?php echo esc_attr($this->id); ?>">
            <?php foreach ($links as $index => $link) : ?>
                <div class="stp-customizer-social-row" style="margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid #dcdcde;">
                    <p>
                        <label><?php esc_html_e('Label (accessibilité)', 'sage'); ?></label>
                        <input type="text" class="widefat stp-social-label" value="<?php echo esc_attr((string) ($link['label'] ?? '')); ?>" />
                    </p>
                    <p>
                        <label><?php esc_html_e('URL', 'sage'); ?></label>
                        <input type="url" class="widefat stp-social-url" value="<?php echo esc_attr((string) ($link['url'] ?? '')); ?>" />
                    </p>
                    <p>
                        <label><?php esc_html_e('Plateforme', 'sage'); ?></label>
                        <select class="widefat stp-social-platform">
                            <?php
                            $platforms = [
                                '' => '—',
                                'instagram' => 'Instagram',
                                'twitter' => 'X / Twitter',
                                'facebook' => 'Facebook',
                                'linkedin' => 'LinkedIn',
                                'youtube' => 'YouTube',
                                'tiktok' => 'TikTok',
                                'mastodon' => 'Mastodon',
                            ];

                foreach ($platforms as $value => $label) :
                    ?>
                                <option value="<?php echo esc_attr($value); ?>" <?php selected(($link['platform'] ?? ''), $value); ?>>
                                    <?php echo esc_html($label); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </p>
                    <button type="button" class="button stp-social-remove"><?php esc_html_e('Supprimer', 'sage'); ?></button>
                </div>
            <?php endforeach; ?>
            <button type="button" class="button stp-social-add"><?php esc_html_e('Ajouter un lien', 'sage'); ?></button>
        </div>
        <input
            id="<?php echo esc_attr($this->id); ?>"
            class="stp-customizer-social-value"
            type="hidden"
            <?php $this->link(); ?>
            value="<?php echo esc_attr((string) $this->value()); ?>"
        />
        <?php
    }
}

class FooterCustomizer
{
    public static function register(WP_Customize_Manager $wp_customize): void
    {
        $wp_customize->add_section('stp_footer', [
            'title' => __('Footer', 'sage'),
            'priority' => 21,
            'description' => __('Contenu affiché en bas de chaque page.', 'sage'),
        ]);

        self::registerPartial($wp_customize);
        self::registerToggles($wp_customize);
        self::registerGlobalFields($wp_customize);
        self::registerLanguageFields($wp_customize);
        self::registerSocialLinks($wp_customize);
    }

    private static function registerPartial(WP_Customize_Manager $wp_customize): void
    {
        if (! isset($wp_customize->selective_refresh)) {
            return;
        }

        $wp_customize->selective_refresh->add_partial('stp_footer_partial', [
            'selector' => '[data-stp-footer]',
            'render_callback' => function (): void {
                echo view('sections.footer', [
                    'footer' => FooterData::forView(),
                ])->render();
            },
        ]);
    }

    private static function registerToggles(WP_Customize_Manager $wp_customize): void
    {
        $labels = [
            'show_contact' => __('Afficher le contact', 'sage'),
            'show_menu' => __('Afficher la navigation footer', 'sage'),
            'show_legal' => __('Afficher les mentions légales', 'sage'),
            'show_social' => __('Afficher les réseaux sociaux', 'sage'),
            'show_language' => __('Afficher le sélecteur de langue', 'sage'),
            'show_logo' => __('Afficher le logo horizontal', 'sage'),
            'show_credit' => __('Afficher crédit et copyright', 'sage'),
        ];

        $priority = 10;

        foreach (Footer::TOGGLE_KEYS as $key) {
            self::addSetting($wp_customize, $key, [
                'default' => Footer::defaults()[$key],
                'sanitize_callback' => fn ($value): bool => (bool) $value,
            ]);

            $wp_customize->add_control(Footer::themeModKey($key), [
                'section' => 'stp_footer',
                'label' => $labels[$key] ?? $key,
                'type' => 'checkbox',
                'priority' => $priority,
            ]);

            $priority += 1;
        }
    }

    private static function registerGlobalFields(WP_Customize_Manager $wp_customize): void
    {
        self::addSetting($wp_customize, 'horizontal_logo_id', [
            'default' => 0,
            'sanitize_callback' => fn ($value): int => max(0, (int) $value),
        ]);

        $wp_customize->add_control(new \WP_Customize_Media_Control($wp_customize, Footer::themeModKey('horizontal_logo_id'), [
            'section' => 'stp_footer',
            'label' => __('Logo horizontal', 'sage'),
            'description' => __('Affiché en haut du footer.', 'sage'),
            'mime_type' => 'image',
            'priority' => 30,
        ]));

        self::addSetting($wp_customize, 'contact_email', [
            'default' => Footer::defaults()['contact_email'],
            'sanitize_callback' => function ($value): string {
                $email = sanitize_email((string) $value);

                return is_email($email) ? $email : Footer::defaults()['contact_email'];
            },
        ]);

        $wp_customize->add_control(Footer::themeModKey('contact_email'), [
            'section' => 'stp_footer',
            'label' => __('Adresse e-mail', 'sage'),
            'description' => __('Utilisée dans le footer et le menu burger.', 'sage'),
            'type' => 'email',
            'priority' => 31,
        ]);

        self::addSetting($wp_customize, 'transpire_url', [
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        ]);

        $wp_customize->add_control(Footer::themeModKey('transpire_url'), [
            'section' => 'stp_footer',
            'label' => __('Site TRANSpire', 'sage'),
            'type' => 'url',
            'priority' => 32,
        ]);

        self::addSetting($wp_customize, 'plaidact_url', [
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        ]);

        $wp_customize->add_control(Footer::themeModKey('plaidact_url'), [
            'section' => 'stp_footer',
            'label' => __('Site PLAID•ACT', 'sage'),
            'type' => 'url',
            'priority' => 33,
        ]);

        self::addSetting($wp_customize, 'legal_page_id', [
            'default' => 0,
            'sanitize_callback' => fn ($value): int => max(0, (int) $value),
        ]);

        $pageChoices = [0 => __('— Sélectionner —', 'sage')];

        foreach (get_pages(['sort_column' => 'post_title']) as $page) {
            $pageChoices[$page->ID] = $page->post_title;
        }

        $wp_customize->add_control(Footer::themeModKey('legal_page_id'), [
            'section' => 'stp_footer',
            'label' => __('Page mentions légales (FR)', 'sage'),
            'description' => __('Liez la traduction EN via Polylang.', 'sage'),
            'type' => 'select',
            'choices' => $pageChoices,
            'priority' => 34,
        ]);
    }

    /**
     * @param  array<string, mixed>  $args
     */
    private static function addSetting(WP_Customize_Manager $wp_customize, string $key, array $args): void
    {
        $wp_customize->add_setting(Footer::themeModKey($key), array_merge([
            'type' => 'theme_mod',
            'capability' => 'edit_theme_options',
            'transport' => 'refresh',
        ], $args));
    }

    private static function registerLanguageFields(WP_Customize_Manager $wp_customize): void
    {
        $priority = 50;

        foreach (Footer::LANGUAGES as $lang) {
            $langLabel = strtoupper($lang);

            foreach (Footer::LANG_TEXT_KEYS as $key) {
                $settingKey = "{$lang}_{$key}";

                self::addSetting($wp_customize, $settingKey, [
                    'default' => Footer::defaults()[$lang][$key],
                    'sanitize_callback' => 'sanitize_text_field',
                ]);

                $labels = [
                    'contact_label' => sprintf(__('Label contact (%s)', 'sage'), $langLabel),
                    'credit_intro' => sprintf(__('Intro crédit campagne (%s)', 'sage'), $langLabel),
                    'credit_joiner' => sprintf(__('Conjonction (%s)', 'sage'), $langLabel),
                    'copyright' => sprintf(__('Copyright (%s)', 'sage'), $langLabel),
                ];

                $descriptions = [
                    'credit_intro' => __('Ex. : « La campagne a été lancée par » — suivi des liens TRANSpire et PLAID•ACT.', 'sage'),
                    'credit_joiner' => __('Ex. : « et » (FR) ou « and » (EN).', 'sage'),
                    'copyright' => __('Utilisez {year} pour l’année courante.', 'sage'),
                ];

                $wp_customize->add_control(Footer::themeModKey($settingKey), [
                    'section' => 'stp_footer',
                    'label' => $labels[$key] ?? $settingKey,
                    'description' => $descriptions[$key] ?? '',
                    'type' => 'text',
                    'priority' => $priority,
                ]);

                $priority += 1;
            }
        }
    }

    private static function registerSocialLinks(WP_Customize_Manager $wp_customize): void
    {
        self::addSetting($wp_customize, 'social_links', [
            'default' => '',
            'sanitize_callback' => function ($value): string {
                return wp_json_encode(Footer::sanitizeSocialLinks(Footer::decodeSocialLinks($value)));
            },
        ]);

        $wp_customize->add_control(new Stp_Footer_Social_Links_Control($wp_customize, Footer::themeModKey('social_links'), [
            'section' => 'stp_footer',
            'label' => __('Réseaux sociaux', 'sage'),
            'description' => __('Liens communs à toutes les langues.', 'sage'),
            'priority' => 80,
        ]));
    }

    public static function enqueueControlsScript(): void
    {
        $handle = 'stp-customizer-footer';
        $path = get_theme_file_path('resources/js/customizer-footer.js');

        wp_enqueue_script(
            $handle,
            get_theme_file_uri('resources/js/customizer-footer.js'),
            ['customize-controls', 'jquery'],
            file_exists($path) ? (string) filemtime($path) : null,
            true
        );
    }
}
