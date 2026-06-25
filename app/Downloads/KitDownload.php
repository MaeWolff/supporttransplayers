<?php

namespace App\Downloads;

use App\Support\BlockLocales;

class KitDownload
{
    public const QUERY_VAR = 'stp_kit_download';

    public const QUERY_VAR_LANG = 'stp_kit_lang';

    public static function registerRewriteRules(): void
    {
        add_rewrite_rule(
            '^stp-download/kit/([0-9]+)/([a-z]{2})/?$',
            'index.php?'.self::QUERY_VAR.'=$matches[1]&'.self::QUERY_VAR_LANG.'=$matches[2]',
            'top'
        );
    }

    /**
     * @param  array<int, string>  $vars
     * @return array<int, string>
     */
    public static function registerQueryVar(array $vars): array
    {
        $vars[] = self::QUERY_VAR;
        $vars[] = self::QUERY_VAR_LANG;

        return $vars;
    }

    public static function maybeServe(): void
    {
        $postId = (int) get_query_var(self::QUERY_VAR);

        if ($postId <= 0) {
            return;
        }

        $lang = (string) get_query_var(self::QUERY_VAR_LANG);

        if (! BlockLocales::isValid($lang)) {
            wp_die(
                esc_html__('Langue de téléchargement invalide.', 'sage'),
                esc_html__('Téléchargement indisponible', 'sage'),
                ['response' => 404]
            );
        }

        $attachmentIds = ZipBuilder::validatedAttachmentIds($postId, $lang);

        if ($attachmentIds === []) {
            wp_die(
                esc_html__('Kit introuvable ou non publié.', 'sage'),
                esc_html__('Téléchargement indisponible', 'sage'),
                ['response' => 404]
            );
        }

        ZipBuilder::buildOrServe(
            $postId,
            $lang,
            $attachmentIds,
            ZipBuilder::downloadNameForPost($postId, $lang)
        );
    }

    public static function flushRewriteRules(): void
    {
        self::registerRewriteRules();
        flush_rewrite_rules();
    }
}

add_action('init', [KitDownload::class, 'registerRewriteRules']);
add_filter('query_vars', [KitDownload::class, 'registerQueryVar']);
add_action('template_redirect', [KitDownload::class, 'maybeServe'], 0);
add_action('after_switch_theme', [KitDownload::class, 'flushRewriteRules']);
