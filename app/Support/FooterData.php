<?php

namespace App\Support;

use App\Footer;

class FooterData
{
    /**
     * @return array<string, mixed>
     */
    public static function forView(): array
    {
        $menuItems = stp_nav_menu_tree('footer_navigation');
        $socialLinks = Footer::socialLinks();
        $legalUrl = Footer::legalUrl();
        $legalPageId = Footer::legalPageId();
        $horizontalLogo = Footer::horizontalLogo();
        $campaignCredit = self::campaignCreditForView();

        $legalTitle = null;

        if ($legalPageId !== null) {
            $title = get_the_title($legalPageId);
            $legalTitle = is_string($title) && $title !== '' ? $title : stp_pll__('Mentions légales');
        }

        $showLanguage = Footer::isVisible('show_language') && stp_languages() !== [];
        $showLogo = Footer::isVisible('show_logo') && $horizontalLogo !== null;
        $showCredit = Footer::isVisible('show_credit') && (
            $campaignCredit !== null || Footer::get('copyright') !== ''
        );

        return [
            'contactEmail' => Footer::contactEmail(),
            'contactLabel' => Footer::get('contact_label'),
            'campaignCredit' => $campaignCredit,
            'copyright' => Footer::get('copyright'),
            'legalUrl' => $legalUrl,
            'legalTitle' => $legalTitle,
            'socialLinks' => $socialLinks,
            'menuItems' => $menuItems,
            'horizontalLogo' => $horizontalLogo,
            'showContact' => Footer::isVisible('show_contact') && Footer::contactEmail() !== '',
            'showMenu' => Footer::isVisible('show_menu') && $menuItems !== [],
            'showLegal' => Footer::isVisible('show_legal') && $legalUrl !== null,
            'showSocial' => Footer::isVisible('show_social') && $socialLinks !== [],
            'showLanguage' => $showLanguage,
            'showLogo' => $showLogo,
            'showCredit' => $showCredit,
            'showMiddleBand' => (
                (Footer::isVisible('show_menu') && $menuItems !== [])
                || (Footer::isVisible('show_legal') && $legalUrl !== null)
                || (Footer::isVisible('show_social') && $socialLinks !== [])
                || $showLanguage
                || Footer::isVisible('show_contact')
            ),
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function campaignCreditForView(): ?array
    {
        $intro = Footer::get('credit_intro');

        if ($intro === '') {
            return null;
        }

        return [
            'intro' => $intro,
            'joiner' => Footer::get('credit_joiner'),
            'transpireUrl' => Footer::transpireUrl(),
            'plaidactUrl' => Footer::plaidactUrl(),
        ];
    }
}
