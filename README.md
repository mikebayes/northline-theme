# Northline

A custom WordPress theme for **Northline Electrical**, a (fictional) licensed electrical contractor in Winnipeg, Manitoba.

Northline is a **hybrid classic theme**: PHP templates provide structure, `theme.json` provides the design system, and every word of page content is authored in the standard WordPress admin. No page copy is hard-coded into templates, and the theme has **no plugin dependencies** — no ACF, no page builder, no form plugin.

---

## Deployment

The repository root **is** the theme root. Deploy the contents of this repo to:

```
/wp-content/themes/northline
```

Nothing outside that directory belongs to this project. The repo contains theme files only — no WordPress core, plugins, uploads or `wp-config.php`.

**Requirements:** WordPress 6.5+, PHP 7.4+.

---

## First-time setup

On a brand-new site, activating Northline triggers WordPress's native **starter content**, which creates the five pages, both menus and the front-page settings for you. Open **Appearance → Customize** and click *Publish* to keep it.

On an existing site, do it manually — it takes about five minutes:

1. **Pages** → create `Home`, `About`, `Services`, `Insights`, `Contact`.
2. **Settings → Reading** → *Your homepage displays: A static page*; Homepage = `Home`, Posts page = `Insights`.
3. **Appearance → Menus** → build a menu with those pages, assign it to *Primary Menu (header)*. Repeat for *Footer Menu* and, optionally, *Legal Menu*.
4. **Appearance → Customize → Site Identity** → set the logo, site title and tagline.
5. **Appearance → Widgets** → fill *Footer Column 1–3* with contact details, hours and service areas.
6. Edit each page and insert the matching pattern (below) as a starting point.

---

## How content is edited

Everything a client would reasonably want to change lives in the admin:

| Content | Edited in |
| --- | --- |
| Home page sections (hero, services, about, CTA…) | **Pages → Home**, block editor |
| Interior page copy | **Pages**, block editor |
| Page intro line under the title | The page's **Excerpt** field |
| Page and article header images | **Featured image** |
| Insights articles | **Posts** |
| Header and footer navigation | **Appearance → Menus** |
| Logo, site title, tagline | **Customize → Site Identity** |
| Footer contact details, hours | **Appearance → Widgets** (Footer Column 1–3) |
| Colours, type sizes, spacing | Block sidebar, using the presets from `theme.json` |

Templates only render structure: the site chrome, page headers, article grids and pagination.

---

## Designing pages with core blocks

Three mechanisms make ordinary blocks look bespoke.

### 1. Design tokens (`theme.json`)

The palette, fluid type scale, spacing scale and layout widths are declared once and appear in every block's sidebar.

| Colour | Slug | Use |
| --- | --- | --- |
| Ink `#0b1622` | `ink` | Dark sections, headings |
| Slate `#16283a` | `slate` | Body text |
| Steel `#5b6e81` | `steel` | Secondary text |
| Hairline `#dbe3ec` | `line` | Borders and rules |
| Surface `#f2f6f9` | `surface` | Alternating section bands |
| Voltage `#f2a01d` | `primary` | Accents, buttons |
| Voltage Deep `#c67c07` | `primary-deep` | Hover, links on light |
| Aurora `#2bb3a3` | `accent` | Secondary accent |

Layout: content width `46rem`, wide width `78rem`.

### 2. Block style variations

Select a block, open the *Styles* section of the sidebar, pick a variation:

| Block | Variation | Effect |
| --- | --- | --- |
| Group | **Section — Dark** | Full-bleed midnight band with a schematic grid and amber glow |
| Group | **Section — Surface** | Pale alternating band |
| Group / Column | **Card** | White card with hairline border and hover lift |
| Group | **Section Intro** | Narrow measure for eyebrow + heading + lead, pinned to the section's left edge |
| Group | **Outlined Panel** | Bordered panel, no fill |
| Group | **Accent Rule (top)** | Short amber rule on the top edge |
| Columns | **Card Grid** | Every column becomes a card |
| Columns | **Divided Columns** | Hairline dividers, stacking on mobile |
| Heading / Paragraph | **Eyebrow** | Small uppercase label with a leading rule |
| Heading | **Accent Underline** | Amber underline |
| Paragraph | **Lead Paragraph** | Larger introductory text |
| List | **Checklist** | Amber tick marks |
| List | **Spec List** | Label/value rows with hairlines |
| Image | **Framed** / **Soft Shadow** | Matted frame or lifted shadow |
| Quote | **Testimonial** | Card with an amber rule and small-caps citation |
| Separator | **Accent Bar** | Short amber bar |
| Cover | **Editorial Scrim** | Bottom-weighted gradient scrim |
| Media & Text | **Panelled** | Content side on a surface panel |
| Table | **Clean Rows** | Borderless rows with an uppercase header |
| Buttons | **Full Width on Mobile** | Buttons stack and fill below 600px |

Dark sections restyle their children automatically — cards, eyebrows, lists, quotes and outline buttons all invert without any per-block changes.

### 3. Block patterns

**Inserter → Patterns**, categories *Northline: Sections* and *Northline: Full Pages*. Creating a new Page also offers the full-page patterns in the "choose a pattern" modal.

Sections: Hero — Dark · Services — Card Grid · About — Split with Credentials Panel · Process — Numbered Steps · Testimonials — Three Up · Service Areas — Checklist Columns · Contact — Details, Hours and Emergency · Insights — Latest Articles · CTA — Dark Band

Full pages: Page — Home · Page — About · Page — Services · Page — Contact

The full-page patterns are composed from the section patterns at registration time (see `patterns/page-home.php`), so editing a section updates every page pattern that uses it. Once inserted into a page, the blocks are ordinary content and belong to the editor.

---

## Structure

```
style.css                 Theme header only
theme.json                Design tokens, layout, per-block defaults
functions.php             Bootstrap
inc/
  setup.php               Theme supports, menus, image sizes, widget areas
  enqueue.php             Front-end and editor assets
  block-styles.php        register_block_style() variations
  patterns.php            Pattern categories
  template-tags.php       Template helpers
  starter-content.php     Native starter content for fresh installs
header.php footer.php     Site chrome
front-page.php            Home — edge-to-edge Gutenberg canvas
page.php                  Interior pages
home.php                  Insights index (Posts page)
single.php archive.php search.php 404.php index.php
searchform.php comments.php
template-parts/
  header/                 branding, navigation
  content/                page-header, card, none
patterns/                 Block patterns (auto-registered from file headers)
assets/
  css/theme.css           Front end
  css/blocks.css          Block styling — front end AND editor canvas
  css/editor.css          Editor-only tokens and tweaks
  js/navigation.js        Mobile menu (the theme's only script, ~60 lines)
```

### Menu conventions

Give a menu item the CSS class `nl-menu-cta` (Appearance → Menus → Screen Options → CSS Classes) to render it as a button in the header.

---

## Notes and known limits

- **Contact forms.** The Contact page ships with phone, email, address, hours and an emergency panel, but **no form** — a working form needs a plugin, which this stage deliberately excludes. When one is added, drop its block into the first card of the *Contact — Details* pattern.
- **Photography.** Patterns are text-and-shape only so the theme carries no stock imagery. Add real photos via Featured Images and Image blocks; `Framed` and `Soft Shadow` styles are there for them.
- **Placeholder details.** Phone numbers use the reserved `555-01xx` range, the licence number is marked `(placeholder)`, and the email is on `example.com`. Replace before launch.
- **Alignment.** `.entry-content` uses a named-line CSS grid for `alignwide` / `alignfull`, so full-bleed sections work without wrapper markup.
- **JavaScript.** One file, no dependencies, no build step. Sub-menus work on hover and `:focus-within` alone.
