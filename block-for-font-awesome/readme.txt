=== Block for Font Awesome Icons – Searchable Icon Picker, SVG, FA 7 ===
Contributors: butterflymedia
Donate link: https://buymeacoffee.com/wolffe
Tags: font awesome, icon, svg, icon block, fontawesome
Requires at least: 7.1
Tested up to: 7.1.3
Requires PHP: 8.0
Stable tag: 1.8.0
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

For anyone who wants Font Awesome icons in the block editor without class names or a heavy icon font: search, click, done. Free icons render as SVG.

== Description ==

Add a **Font Awesome Icon** block, click **Choose icon**, search by name or keyword ("phone", "home", "arrow") and pick from 2,000+ Font Awesome Free 7 icons. No class names to remember.

Free icons are output as small inline SVG, so your pages don't need to load the Font Awesome script or font at all. Icons follow your text colour and work with the block Color, Typography (size) and Spacing panels.

* Visual, searchable icon picker with style filter (solid, regular, brands) and keyboard navigation.
* Inline SVG output: no `all.js`, no web font, no layout shift.
* Accessible: decorative icons are hidden from screen readers, or add a label for icons that carry meaning.
* Optional link, new tab, size, fixed width and alignment.
* Adds Font Awesome Free as a collection in the core WordPress **Icon** block, with transforms between the two blocks.
* Two patterns: an icon feature list and a social icons row.
* Existing content keeps working: old blocks and the `[fa class="fa-solid fa-phone"]` and `[icon prefix="fas" name="phone"]` shortcodes now render Free icons as SVG too.
* Font Awesome Pro and kits are still supported through the optional script loader in Settings → Font Awesome.

Read more about the [Block for Font Awesome plugin](https://getbutterfly.com/wordpress-plugins/block-for-font-awesome/) here.

Explore more [WordPress Plugins](https://getbutterfly.com/wordpress-plugins/).

Font Awesome Free icons by [Fonticons, Inc.](https://fontawesome.com/), licensed under [CC BY 4.0](https://creativecommons.org/licenses/by/4.0/). Font Awesome is a trademark of Fonticons, Inc. This plugin is not affiliated with or endorsed by Fonticons, Inc.

### From the same author

* [Active Contacts - WordPress CRM & Follow-up Plugin](https://getbutterfly.com/wordpress-plugins/active-contacts/)
* [LazyClone - Copy a WordPress Site Without FTP](https://getbutterfly.com/wordpress-plugins/lazyclone/)
* [Lighthouse - WordPress Performance & Speed Optimization Plugin](https://getbutterfly.com/wordpress-plugins/lighthouse/)
* [Active Analytics - Privacy-Friendly WordPress Analytics Plugin](https://getbutterfly.com/wordpress-plugins/active-analytics/)
* [ImagePress - WordPress Image Gallery & Community Photo Plugin](https://getbutterfly.com/wordpress-plugins/imagepress/)
* [eCards - WordPress eCard Plugin with Email Designer](https://getbutterfly.com/wordpress-plugins/wordpress-ecards-plugin/)
* [Repeater for Gravity Forms - Repeater Field Add-on](https://getbutterfly.com/wordpress-plugins/gravity-forms-repeater-plugin/)
* [Fixtures & Results - WordPress Sports League & GAA Club Plugin](https://getbutterfly.com/wordpress-plugins/fixtures-and-results/)
* [WP Google Consent Platform (GCP)](https://getbutterfly.com/wordpress-plugins/wp-gcp-a-wordpress-plugin-for-google-consent-mode-v2/)

== Installation ==

1. Log into your WordPress site
2. Install and activate plugin
3. Add a Font Awesome Icon block and click "Choose icon"

== Frequently Asked Questions ==

= Do I need to know Font Awesome class names? =

No. Click "Choose icon" and search by name or keyword. Class names are only needed for Font Awesome Pro or kit icons.

= Will it slow my site down? =

No. Free icons are inline SVG (usually under 1 KB each) and no Font Awesome script or font is loaded unless you turn one on in Settings → Font Awesome for Pro icons or kits.

= I updated from an older version. Do I still need the Font Awesome script? =

Not for Free icons in this block or the shortcodes; they now render as SVG. Keep the script on only if your theme or other content uses `<i class="fa-...">` markup, Pro icons or a kit.

= Can I use the icons in the core Icon block? =

Yes. On WordPress 7.1+ the core Icon block shows a "Font Awesome Free" tab. You can also transform between the two blocks.

== Screenshots ==

1. Searchable icon picker
2. Icon block settings
3. Front-end icons
4. Plugin settings

== Changelog ==

= 1.8.0 =
* FEATURE: Visual, searchable icon picker (2,000+ Font Awesome Free 7.3.1 icons) with style filter and keyboard navigation
* FEATURE: Free icons render as inline SVG, so no Font Awesome script is needed; new installs no longer load one by default
* FEATURE: Font Awesome Free collection for the core Icon block (WordPress 7.1 icon API), with transforms between blocks
* FEATURE: Color, typography (font size), spacing and anchor block supports
* FEATURE: Accessible label option; decorative icons are hidden from screen readers
* FEATURE: Icon feature list and social icons row patterns
* UPDATE: Existing blocks and the `[fa]` / `[icon]` shortcodes render Free icons as SVG; Pro icons and kits still use the Font Awesome script
* UPDATE: The block's default colour is now your text colour instead of black; blocks with a chosen colour keep it
* FIX: Register the block from `block.json` and remove the invalid `parent` setting
* FIX: `[icon prefix="fas"]` produced an invalid class
* UPDATE: Requires WordPress 7.1 and PHP 8.0

= 1.7.10 =
* UPDATE: Update Font Awesome 7 to 7.3.1 and declare Block API version 3 metadata
* UPDATE: Tested up to WordPress 7.1
* DOCS: Add WordPress Plugins directory and donation link

= 1.7.9 =
* UPDATE: Update Font Awesome 7 to 7.3.1 (from 7.3.0)
* UPDATE: Tested up to WordPress 7.1
* DOCS: Add WordPress Plugins directory and donation link

= 1.7.8 =
* UPDATE: Tested up to WordPress 7.0

= 1.7.7 =
* UPDATE: Update Font Awesome 7 to 7.2.0 (from 7.1.0)
* UPDATE: Update WordPress compatibility

= 1.7.6 =
* SECURITY: Add direct file access protection
* FIX: Fix WPCS nonce validation warnings
* FIX: Remove five-star review filter from plugin review link

= 1.7.5 =
* UPDATE: Upgrade block to API version 3 for iframe editor compatibility

= 1.7.4 =
* UPDATE: Update Font Awesome 7 to 7.1.0 (from 7.0.1)
* UPDATE: Update WordPress compatibility

= 1.7.3 =
* UPDATE: Update Font Awesome 7 to 7.0.1 (from 7.0.0)

= 1.7.2 =
* FIX: Fix block control types for latest block editor versions
* FEATURE: Add better support for "icon" shortcodes

= 1.7.1 =
* FEATURE: Refactor resource loading and CDN source

= 1.7.0 =
* FEATURE: Add support for Font Awesome 7
* UPDATE: Update WordPress compatibility

= 1.6.1 =
* UPDATE: Add support for "icon" shortcode for easier migration from Font Awesome 5 to Font Awesome 6+

= 1.6.0 =
* FEATURE: Add icon size
* UPDATE: Add a default icon class so the icon is visible in the editor
* UPDATE: Update Font Awesome 6 to 6.7.2 (from 6.6.0)
* UPDATE: Update color picker to use ColorPalette
* UPDATE: Update WordPress compatibility

= 1.5.0 =
* SECURITY: Further sanitize and escape all settings
* UPDATE: Update Font Awesome 6 to 6.6.0 (from 6.5.2)
* UPDATE: Add useBlockProps from wp.blockEditor
* UPDATE: Add PanelBody to the variable declarations at the top
* UPDATE: Change wp.editor.InspectorControls to wp.blockEditor.InspectorControls since wp.editor is deprecated

= 1.4.6 =
* FIX: Fix external CSS array not being initialized when saving settings

= 1.4.5 =
* FIX: Fix XSS vulnerability

= 1.4.4 =
* FIX: Fix XSS vulnerability
* UPDATE: Update Font Awesome 6 to 6.5.2 (from 6.5.1)

= 1.4.3 =
* FIX: Add extra condition for loading Font Awesome 6 + Local stylesheets

= 1.4.2 =
* FEATURE: Add option to add an array of external (or local) stylesheets
* UPDATE: Update WordPress compatibility

= 1.4.1 =
* FIX: Add nonce to settings page

= 1.4.0 =
* FIX: Fix CSRF vulnerability
* SECURITY: Restrict directory indexing
* SECURITY: Make sure all options are sanitized before casting to integer
* UPDATE: Update WordPress compatibility
* UPDATE: Update Font Awesome 6 to 6.5.1 (from 6.4.2)

= 1.3.3 =
* UPDATE: Updated WordPress compatibility
* UPDATE: Updated Font Awesome 6 to 6.4.2 (from 6.4.0)

= 1.3.2 =
* UPDATE: Updated WordPress compatibility

= 1.3.1 =
* UPDATE: Updated WordPress compatibility
* UPDATE: Updated Font Awesome 6 to 6.4.0 (from 6.2.1)

= 1.3.0 =
* FIX: Fixed Font Awesome kit not being loaded in the back-end
* UPDATE: Updated WordPress compatibility
* UPDATE: Updated Font Awesome 6 to 6.2.1 (from 6.2.0)

= 1.2.6 =
* FIX: Fixed color picker for new Gutenberg versions (replaced ColorPalette with ColorPicker)
* UPDATE: Updated WordPress compatibility
* UPDATE: Updated Font Awesome 6 to 6.2.0 (from 6.1.2)

= 1.2.5 =
* UPDATE: Updated WordPress compatibility
* UPDATE: Updated description to include Font Awesome 6 and Font Awesome kit icons
* UPDATE: Updated Font Awesome 6 to 6.1.2 (from 6.1.1)

= 1.2.4 =
* UPDATE: Updated block editor filter to support WordPress 5.8+
* UPDATE: Updated WordPress requirements to support WordPress 5.8+

= 1.2.3 =
* FIX: Fixed Font Awesome 6 source being hardcoded to a local path

= 1.2.2 =
* FIX: Fixed Font Awesome 6 source option not being selected

= 1.2.1 =
* UPDATE: Removed hardcoded Font Awesome 6 source files and replaced with CDN versions

= 1.2.0 =
* FEATURE: Added Font Awesome 6 (6.1.1) support
* FEATURE: Added Font Awesome Kit support
* FEATURE: Added link target (self or new tab)
* UPDATE: Updated Font Awesome to 5.15.4 (from 5.15.3)
* UPDATE: Updated WordPress compatibility
* UPDATE: Updated codebase to conform to latest WordPress Coding Standards (WPCS) ruleset
* UPDATE: Added plugin version parameter to admin.css to fix caching issues

= 1.1.10 =
* UPDATE: Updated Font Awesome to 5.15.3 (from 5.15.2)
* UPDATE: Updated WordPress compatibility

= 1.1.9 =
* FIX: Fixed array and string offset access syntax with curly braces in PHP 8

= 1.1.8 =
* UPDATE: Updated Font Awesome to 5.15.2 (from 5.15.1)

= 1.1.7 =
* FEATURE: Added option to enqueue Font Awesome on front-end
* FEATURE: Added option to enqueue Font Awesome on back-end
* UPDATE: Updated WordPress compatibility

= 1.1.6 =
* FEATURE: Added icon URL (link)
* FEATURE: Added icon alignment
* UPDATE: Updated Font Awesome to 5.15.1 (from 5.15.0)
* FIX: Added missing translations

= 1.1.5 =
* UPDATE: Updated Font Awesome to 5.15.0 (from 5.14.0)
* UPDATE: Updated WordPress compatibility

= 1.1.4 =
* UPDATE: Updated Font Awesome to 5.14.0 (from 5.13.0)
* UPDATE: Updated WordPress compatibility
* UPDATE: Tested with the latest Gutenberg plugin version

= 1.1.3 =
* UPDATE: Updated Font Awesome to 5.13.0 (from 5.12.1) to include the new COVID-19 icons
* UPDATE: Tested with the latest Gutenberg plugin version

= 1.1.2 =
* UPDATE: Updated Font Awesome to 5.12.1 (from 5.10.1)
* UPDATE: Updated WordPress compatibility

= 1.1.1 =
* FIX: Fixed SVN import
* UPDATE: Updated plugin assets

= 1.1.0 =
* FIX: Enqueued Font Awesome 5 in admin section (do not depend on other themes and plugins)
* FIX: Fixed wrong inspector panel label
* UPDATE: Updated Font Awesome to 5.10.1 (from 5.9.0)
* UPDATE: Updated description to include the shortcode

= 1.0.2 =
* UPDATE: Added fixed-width parameter
* UPDATE: Added custom colour picker

= 1.0.1 =
* FIX: Changed plugin initialization to avoid a console error
* UPDATE: Changed name and slug
* UPDATE: Updated Font Awesome to 5.9.0 (from 5.8.2)
* UPDATE: General cleanup

= 1.0.0 =
* Initial release

