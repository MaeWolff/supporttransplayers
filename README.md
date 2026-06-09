# Support Trans Players — Thème WordPress

Thème du site de la campagne **Support Trans Players** : site vitrine multilingue (FR / EN) basé sur [Sage](https://roots.io/sage/).

**Stack :** Sage 11 · Acorn 6 · PHP 8.3 · Vite 8 · Tailwind CSS 4 · Polylang

---

## Prérequis

- [Local](https://localwp.com/) (ou équivalent) — site local `stp.local`
- **PHP 8.3+**, [Composer](https://getcomposer.org/), **Node 20.19+** ou **22.12+**
- Extensions WordPress :
  - [Polylang](https://wordpress.org/plugins/polylang/) — installer depuis wordpress.org (pas via `git clone`, le dossier `vendor/` est requis)
  - [Polylang Theme Strings](https://github.com/BenjaminMedia/wp-polylang-theme-strings) (optionnel, déjà prévu dans le projet)
- [WP-CLI](https://wp-cli.org/) recommandé pour les commandes de traduction

---

## Installation (première fois)

```bash
cd wp-content/themes/supporttransplayers
composer install
npm install
npm run build
```

Ensuite dans l’admin WordPress :

1. **Apparence → Thèmes** — activer **Support Trans Players**
2. **Extensions** — activer Polylang (+ Polylang Theme Strings si installé)
3. **Langues** — ajouter Français (défaut) et English
4. **Réglages → Lecture** — page d’accueil statique : Home (version FR)
5. **Pages** — lier la page Home FR et la page Home EN comme **traductions** Polylang (drapeaux dans la liste des pages)
6. **Réglages → Permaliens** — cliquer sur **Enregistrer** (flush des réécritures)

---

## Développement au quotidien

| Commande | Usage |
|----------|-------|
| `npm run dev` | Serveur Vite avec HMR (front + éditeur). `APP_URL` par défaut : `http://stp.local/` dans `vite.config.js` |
| `npm run build` | Compile les assets dans `public/build/` — **à lancer après chaque changement JS/CSS** |
| `composer install` | Dépendances PHP (Acorn) |

Le dossier `public/build/` est **ignoré par git** (voir `.gitignore`). Chaque collaborateur doit lancer `npm run build` en local.

Si les vues Blade ne se mettent pas à jour après un déploiement :

```bash
wp acorn view:clear
```

---

## Architecture du thème

```
app/
  setup.php              # Supports thème, éditeur, load_textdomain
  blocks.php             # Enregistrement des blocks Gutenberg
  helpers.php            # stp_pll__() / stp_pll_x() — i18n
  PolylangThemeStrings.php
  Support/               # HeroData, SupportersData
  View/Composers/        # Données injectées dans les vues Blade
resources/
  blocks/hero/           # Block Gutenberg → rendu Blade
  blocks/supporters/
  views/                 # layouts/, sections/, partials/
  css/app.css            # Tailwind + design tokens (@theme)
  js/editor.js           # Enregistrement des blocks (admin)
  js/app.js              # JS front (animations hero, etc.)
  lang/                  # sage.pot, fr_FR.po/mo, en_US.po/mo
public/build/            # Assets compilés (générés localement, non versionnés)
```

### Flux d’un block dynamique

```
Éditeur Gutenberg  →  post_content (attributs JSON)
       ↓
render.php  →  app/Support/*Data.php  →  resources/views/sections/*.blade.php
       ↓
the_content() sur le front
```

---

## Blocks Gutenberg custom

| Block | Dossier | Rendu Blade |
|-------|---------|---------------|
| `stp/hero` | `resources/blocks/hero/` | `resources/views/sections/hero.blade.php` |
| `stp/supporters` | `resources/blocks/supporters/` | `resources/views/sections/supporters.blade.php` |

**Rédacteurs** — le contenu (titres, textes, boutons, logos) s’édite dans Gutenberg, bloc par bloc, page par page.

**Développeurs** — le markup vit dans Blade ; la config du block dans `block.json` ; l’UI éditeur dans `index.jsx` ; la normalisation des attributs dans `app/Support/*Data.php`.

### Ajouter un nouveau block

1. Créer `resources/blocks/{nom}/` — `block.json`, `index.jsx`, `render.php`
2. Créer `resources/views/sections/{nom}.blade.php`
3. Enregistrer dans `app/blocks.php`
4. Importer dans `resources/js/editor.js`
5. `npm run build`

---

## Internationalisation (FR / EN)

Deux couches distinctes :

### A. Strings UI du thème

Labels fixes : « Aller au contenu », messages 404, menus, etc.

- Utiliser `stp_pll__()` ou `stp_pll_x()` dans Blade et PHP (`app/helpers.php`)
- Fichiers de traduction : `resources/lang/`
- Fallback WordPress via `load_textdomain()` dans `app/setup.php`

Workflow quand on ajoute ou modifie une string :

```bash
npm run translate:pot      # régénère sage.pot
npm run translate:update   # met à jour les .po existants
# Traduire dans Poedit ou Langues → Traductions de chaînes (Polylang)
npm run translate:compile  # compile .mo (+ JSON éditeur si applicable)
```

Les blocks Gutenberg côté JS utilisent `__()` de `@wordpress/i18n` — ne pas remplacer par `pll__()`.

### B. Contenu éditorial

Textes du hero, descriptions, pages : traduits via **Polylang** (pages liées FR ↔ EN), pas dans les fichiers `.po`.

Le switcher FR/EN du header utilise `pll_the_languages()` (`resources/views/sections/header.blade.php`).

### Dépannage Polylang

| Problème | Solution |
|----------|----------|
| Erreur fatale à l’activation de Polylang | Réinstaller depuis [wordpress.org/plugins/polylang](https://wordpress.org/plugins/polylang/), pas depuis GitHub |
| Clic sur EN → 404 sur `/en/home` | Lier Home FR et Home EN comme traductions ; flush permaliens |
| Accueil EN attendu | `/en/` (racine langue), pas `/en/home` si la front page est bien configurée |

---

## Conventions de contribution

- **PHP** — namespace `App\`, PSR-4 ; formater avec `vendor/bin/pint` (`app/`, fichiers PHP purs)
- **Blade / JS / CSS** — Prettier avec tri automatique des classes Tailwind : `npm run format` ou `npm run format:check`
- **Blade** — markup et affichage ; logique métier dans `app/Support/` ou les composers
- **CSS** — Tailwind dans `resources/css/app.css`, tokens dans le bloc `@theme`
- **Git** — ne pas committer `node_modules/`, `vendor/`, `public/build/`
- **Build** — toujours `npm run build` avant de tester en prod ou sans `npm run dev`

---

## Ressources

- [Documentation Sage](https://roots.io/sage/docs/)
- [Documentation Acorn](https://roots.io/acorn/docs/)
- [Polylang — documentation](https://polylang.pro/doc/)
- [Sage — Localizing the theme](https://roots.io/sage/docs/localization/)

---

## English summary

**Support Trans Players** is a multilingual (FR/EN) WordPress campaign site built with Sage 11, Acorn, Vite, Tailwind CSS 4, and Polylang.

**Quick start** (from the theme directory):

```bash
composer install
npm install
npm run build
```

Activate the theme and Polylang in WP Admin. Link FR and EN Home pages as Polylang translations.

**Daily dev:** `npm run dev` (Vite HMR) · **Production assets:** `npm run build` (output in `public/build/`, gitignored — each dev builds locally).

**Custom blocks:** `stp/hero` and `stp/supporters` — Gutenberg attributes edited in the editor, HTML rendered server-side via Blade (`resources/views/sections/`).

**i18n:** Theme UI strings use `stp_pll__()` + `resources/lang/*.po`. Page content is translated with Polylang (linked pages), not `.po` files.

**Translations workflow:** `npm run translate:pot` → `npm run translate:update` → translate → `npm run translate:compile`.

See the French sections above for full architecture, Polylang troubleshooting, and contribution guidelines.
