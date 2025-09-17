<?php
/**
 * Plugin Name: Block for Font Awesome
 * Plugin URI: https://getbutterfly.com/wordpress-plugins/block-for-font-awesome/
 * Description: Display a Font Awesome 5, Font Awesome 6, Font Awesome 7 or Font Awesome kit icon in a Gutenberg block or a custom HTML block.
 * Version: 1.7.3
 * Author: Ciprian Popescu
 * Author URI: https://getbutterfly.com/
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html

 * Font Awesome Free (c) (https://fontawesome.com/license)

 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

if ( ! function_exists( 'add_filter' ) ) {
    header( 'Status: 403 Forbidden' );
    header( 'HTTP/1.1 403 Forbidden' );

    exit();
}

define( 'GBFA_PLUGIN_VERSION', '1.7.3' );
define( 'GBFA5_VERSION', '5.15.4' );
define( 'GBFA6_VERSION', '6.7.2' );
define( 'GBFA7_VERSION', '7.0.1' );

require_once 'block/index.php';



function getbutterfly_fa_enqueue_all() {
    // Font Awesome 7
    if ( (int) get_option( 'fa_enqueue_fa7_fe' ) === 1 ) {
        getbutterfly_fa7_enqueue();
    }

    // Font Awesome 6
    if ( (int) get_option( 'fa_enqueue_fa6_fe' ) === 1 ) {
        getbutterfly_fa6_enqueue();
    }

    // Font Awesome 5
    if ( (int) get_option( 'fa_enqueue_fe' ) === 1 ) {
        wp_enqueue_script( 'fa5', 'https://use.fontawesome.com/releases/v' . GBFA5_VERSION . '/js/all.js', [], GBFA5_VERSION, true );
    }

    // Font Awesome Kit
    if ( (string) get_option( 'fa_enqueue_kit' ) !== '' && (int) get_option( 'fa_enqueue_kit_fe' ) === 1 ) {
        wp_enqueue_script( 'fa', get_option( 'fa_enqueue_kit' ), [], GBFA_PLUGIN_VERSION, true );
        wp_script_add_data( 'fa', [ 'crossorigin' ], [ 'anonymous' ] );
    }

    // Local stylesheets
    if ( (int) get_option( 'fa_enqueue_local_fe' ) === 1 ) {
        getbutterfly_fa_enqueue_local();
    }
}

add_action( 'wp_enqueue_scripts', 'getbutterfly_fa_enqueue_all' );



/**
 * Font Awesome Local Stylesheets
 */
function getbutterfly_fa_enqueue_local() {
    // Local stylesheets
    $fa_external_resources = get_option( 'fa_external_css' );

    if ( count( array_unique( array_filter( (array) get_option( 'fa_external_css' ) ) ) ) > 0 ) {
        $fa_external_resources = array_filter( $fa_external_resources );

        foreach ( $fa_external_resources as $resource_id => $resource ) {
            $resource = sanitize_url( $resource );

            wp_enqueue_style( 'fa-external-' . $resource_id, $resource, [], GBFA_PLUGIN_VERSION );
        }
    }
}



/**
 * Font Awesome 7
 */
function getbutterfly_fa7_enqueue() {
    $fa_enqueue_fa_source = (int) get_option( 'fa_enqueue_fa_source', 0 );

    if ( $fa_enqueue_fa_source === 1 ) {
        wp_enqueue_script( 'fa7', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/' . GBFA7_VERSION . '/js/all.min.js', [], GBFA7_VERSION, true );
    } elseif ( $fa_enqueue_fa_source === 0 ) {
        wp_enqueue_script( 'fa7', 'https://use.fontawesome.com/releases/v' . GBFA7_VERSION . '/js/all.js', [], GBFA7_VERSION, true );
    }
}



/**
 * Font Awesome 6
 */
function getbutterfly_fa6_enqueue() {
    $fa_enqueue_fa_source = (int) get_option( 'fa_enqueue_fa_source', 0 );

    if ( $fa_enqueue_fa_source === 1 ) {
        wp_enqueue_script( 'fa6', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/' . GBFA6_VERSION . '/js/all.min.js', [], GBFA6_VERSION, true );
    } elseif ( $fa_enqueue_fa_source === 0 ) {
        wp_enqueue_script( 'fa6', 'https://use.fontawesome.com/releases/v' . GBFA6_VERSION . '/js/all.js', [], GBFA6_VERSION, true );
    }
}



/**
 * Register/enqueue plugin scripts and styles (back-end)
 */
function getbutterfly_fa_enqueue_admin_scripts() {
    wp_enqueue_style( 'wpfcs', plugins_url( 'assets/css/admin.css', __FILE__ ), [], GBFA_PLUGIN_VERSION );

    // Font Awesome 7
    if ( (int) get_option( 'fa_enqueue_fa7_be' ) === 1 ) {
        getbutterfly_fa7_enqueue();
    }

    // Font Awesome 6
    if ( (int) get_option( 'fa_enqueue_fa6_be' ) === 1 ) {
        getbutterfly_fa6_enqueue();
    }

    // Font Awesome 5
    if ( (int) get_option( 'fa_enqueue_be' ) === 1 ) {
        wp_enqueue_script( 'fa5', 'https://use.fontawesome.com/releases/v' . GBFA5_VERSION . '/js/all.js', [], GBFA5_VERSION, true );
    }

    // Font Awesome Kit
    if ( (string) get_option( 'fa_enqueue_kit' ) !== '' && (int) get_option( 'fa_enqueue_kit_be' ) === 1 ) {
        wp_enqueue_script( 'fa', get_option( 'fa_enqueue_kit' ), [], GBFA_PLUGIN_VERSION, true );
        wp_script_add_data( 'fa', [ 'crossorigin' ], [ 'anonymous' ] );
    }

    // Local stylesheets
    if ( (int) get_option( 'fa_enqueue_local_be' ) === 1 ) {
        getbutterfly_fa_enqueue_local();
    }
}

add_action( 'admin_enqueue_scripts', 'getbutterfly_fa_enqueue_admin_scripts' );



add_action( 'init', 'getbutterfly_fa_block_init' );

add_filter( 'block_categories_all', 'getbutterfly_block_categories', 10, 2 );
add_action( 'enqueue_block_editor_assets', 'getbutterfly_fa_block_enqueue' );

add_shortcode( 'fa', 'getbutterfly_fa_block_render' );
add_shortcode( 'icon', 'getbutterfly_fa_block_render' );



register_activation_hook( __FILE__, 'getbutterfly_fa_on_activation' );

function getbutterfly_fa_on_activation() {
    add_option( 'fa_enqueue_fa_source', '' );

    add_option( 'fa_enqueue_fe', 0 );
    add_option( 'fa_enqueue_be', 0 );

    add_option( 'fa_enqueue_fa6_fe', 1 );
    add_option( 'fa_enqueue_fa6_be', 1 );

    add_option( 'fa_enqueue_fa7_fe', 0 );
    add_option( 'fa_enqueue_fa7_be', 0 );

    add_option( 'fa_enqueue_kit_fe', 0 );
    add_option( 'fa_enqueue_kit_be', 0 );

    add_option( 'fa_enqueue_local_fe', 0 );
    add_option( 'fa_enqueue_local_be', 0 );

    add_option( 'fa_enqueue_kit', '' );

    delete_option( 'fa_enqueue_fa6_setup' );
    delete_option( 'fa_enqueue_fa6_source' );
    delete_option( 'fa_enqueue_fa7_source' );
}



function getbutterfly_fa_menu_links() {
    add_options_page( 'Font Awesome Settings', 'Font Awesome', 'manage_options', 'fa', 'getbutterfly_fa_build_admin_page' );
}

add_action( 'admin_menu', 'getbutterfly_fa_menu_links', 10 );

function getbutterfly_fa_build_admin_page() {
    $tab     = ( filter_has_var( INPUT_GET, 'tab' ) ) ? filter_input( INPUT_GET, 'tab' ) : 'dashboard';
    $section = 'admin.php?page=fa&amp;tab=';
    ?>
    <div class="wrap">
        <h1>Font Awesome Settings</h1>

        <h2 class="nav-tab-wrapper">
            <a href="<?php echo esc_attr( $section ); ?>dashboard" class="nav-tab <?php echo $tab === 'dashboard' ? 'nav-tab-active' : ''; ?>">Dashboard</a>
            <a href="<?php echo esc_attr( $section ); ?>help" class="nav-tab <?php echo $tab === 'help' ? 'nav-tab-active' : ''; ?>">Help</a>
        </h2>

        <?php
        if ( $tab === 'dashboard' ) {
            global $wpdb;

            if ( isset( $_POST['save_fa_settings'] ) && wp_verify_nonce( $_POST['save_fa_settings_nonce_field'], 'save_fa_settings_nonce' ) ) {
                update_option( 'fa_enqueue_fa_source', (int) sanitize_text_field( wp_unslash( $_POST['fa_enqueue_fa_source'] ?? 0 ) ) );

                update_option( 'fa_enqueue_fe', (int) sanitize_text_field( wp_unslash( $_POST['fa_enqueue_fe'] ?? 0 ) ) );
                update_option( 'fa_enqueue_be', (int) sanitize_text_field( wp_unslash( $_POST['fa_enqueue_be'] ?? 0 ) ) );

                update_option( 'fa_enqueue_fa7_fe', (int) sanitize_text_field( wp_unslash( $_POST['fa_enqueue_fa7_fe'] ?? 0 ) ) );
                update_option( 'fa_enqueue_fa7_be', (int) sanitize_text_field( wp_unslash( $_POST['fa_enqueue_fa7_be'] ?? 0 ) ) );

                update_option( 'fa_enqueue_fa6_fe', (int) sanitize_text_field( wp_unslash( $_POST['fa_enqueue_fa6_fe'] ?? 0 ) ) );
                update_option( 'fa_enqueue_fa6_be', (int) sanitize_text_field( wp_unslash( $_POST['fa_enqueue_fa6_be'] ?? 0 ) ) );

                update_option( 'fa_enqueue_kit', sanitize_url( wp_unslash( $_POST['fa_enqueue_kit'] ?? '' ) ) );
                update_option( 'fa_enqueue_kit_fe', (int) sanitize_text_field( wp_unslash( $_POST['fa_enqueue_kit_fe'] ?? 0 ) ) );
                update_option( 'fa_enqueue_kit_be', (int) sanitize_text_field( wp_unslash( $_POST['fa_enqueue_kit_be'] ?? 0 ) ) );

                $fa_external_css = [];
                if ( isset( $_POST['fa_external_css'] ) && is_array( $_POST['fa_external_css'] ) ) {
                    $fa_external_css = array_map( 'sanitize_text_field', wp_unslash( $_POST['fa_external_css'] ?? '' ) );
                }
                update_option( 'fa_external_css', $fa_external_css );

                update_option( 'fa_enqueue_local_fe', (int) sanitize_text_field( wp_unslash( $_POST['fa_enqueue_local_fe'] ?? 0 ) ) );
                update_option( 'fa_enqueue_local_be', (int) sanitize_text_field( wp_unslash( $_POST['fa_enqueue_local_be'] ?? 0 ) ) );

                delete_option( 'fa_enqueue_fa6_setup' );
                delete_option( 'fa_enqueue_fa6_source' );
                delete_option( 'fa_enqueue_fa7_source' );

                echo '<div class="updated notice is-dismissible"><p>Settings updated successfully!</p></div>';
            }
            ?>

            <h2><span class="dashicons dashicons-superhero"></span> Dashboard</h2>

            <div class="gb-ad" id="gb-ad">
                <h3 class="gb-ad--header"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 68 68"><defs/><rect width="100%" height="100%" fill="none"/><g class="currentLayer"><path fill="#000000" d="M34.76 33C22.85 21.1 20.1 13.33 28.23 5.2 36.37-2.95 46.74.01 50.53 3.8c3.8 3.8 5.14 17.94-5.04 28.12-2.95 2.95-5.97 5.84-5.97 5.84L34.76 33"/><path fill="#000000" d="M43.98 42.21c5.54 5.55 14.59 11.06 20.35 5.3 5.76-5.77 3.67-13.1.98-15.79-2.68-2.68-10.87-5.25-18.07 1.96-2.95 2.95-5.96 5.84-5.96 5.84l2.7 2.7m-1.76 1.75c5.55 5.54 11.06 14.59 5.3 20.35-5.77 5.76-13.1 3.67-15.79.98-2.69-2.68-5.25-10.87 1.95-18.07 2.85-2.84 5.84-5.96 5.84-5.96l2.7 2.7" class="selected"/><path fill="#000000" d="M33 34.75c-11.9-11.9-19.67-14.67-27.8-6.52-8.15 8.14-5.2 18.5-1.4 22.3 3.8 3.79 17.95 5.13 28.13-5.05 3.1-3.11 5.84-5.97 5.84-5.97L33 34.75"/></g></svg> Thank you for using Block for Font Awesome!</h3>
                <div class="gb-ad--content">
                    <p>If you enjoy this plugin, do not forget to <a href="https://wordpress.org/support/plugin/block-for-font-awesome/reviews/?filter=5" rel="external">rate it</a>! We work hard to update it, fix bugs, add new features and make it compatible with the latest web technologies.</p>
                    <p>Have you tried our other <a href="https://getbutterfly.com/wordpress-plugins/">WordPress plugins</a>?</p>
                </div>
                <div class="gb-ad--footer">
                    <p>For support, feature requests and bug reporting, please visit the <a href="https://getbutterfly.com/" rel="external">official website</a>.<br>Built by <a href="https://getbutterfly.com/" rel="external"><strong>getButterfly</strong>.com</a> &middot; <a href="https://getbutterfly.com/wordpress-plugins/block-for-font-awesome/">Documentation</a> &middot; <small>Code wrangling since 2005</small></p>
                </div>
            </div>

            <p>Use Font Awesome 5 <strong>or</strong> Font Awesome 6 <strong>or</strong> Font Awesome 7 <strong>or</strong> a custom kit. Do not use all of them, as the kit will overwrite all other options, and Font Awesome 7 will overwrite all the other lower versions, and you will run into performance issues.</p>
            <p>Note that you can have different versions in front-end and back-end.</p>

            <form method="post">
                <?php wp_nonce_field( 'save_fa_settings_nonce', 'save_fa_settings_nonce_field' ); ?>

                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row"><label>Font Awesome Source</label></th>
                            <td>
                                <p>
                                    <label for="fa_enqueue_fa_source">Font Awesome Source</label><br>
                                    <select name="fa_enqueue_fa_source" id="fa_enqueue_fa_source">
                                        <option value="1" <?php selected( (int) get_option( 'fa_enqueue_fa_source' ), 1, true ); ?>>CDNJS</option>
                                        <option value="0" <?php selected( (int) get_option( 'fa_enqueue_fa_source' ), 0, true ); ?>>Font Awesome CDN (recommended)</option>
                                    </select>
                                </p>
                            </td>
                        </tr>

                        <tr>
                            <th scope="row"></th>
                            <td>
                                <div class="hr-sect"></div>
                            </td>
                        </tr>

                        <tr>
                            <th scope="row"><label>Font Awesome <?php echo esc_attr( GBFA7_VERSION ); ?></label></th>
                            <td>
                                <p>
                                    <input type="checkbox" class="wppd-ui-toggle" name="fa_enqueue_fa7_fe" value="1" <?php checked( 1, (int) get_option( 'fa_enqueue_fa7_fe' ) ); ?>> Enqueue on front-end
                                </p>
                                <p>
                                    <input type="checkbox" class="wppd-ui-toggle" name="fa_enqueue_fa7_be" value="1" <?php checked( 1, (int) get_option( 'fa_enqueue_fa7_be' ) ); ?>> Enqueue on back-end
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"></th>
                            <td>
                                <div class="hr-sect">OR</div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label>Font Awesome <?php echo esc_attr( GBFA6_VERSION ); ?></label></th>
                            <td>
                                <p>
                                    <input type="checkbox" class="wppd-ui-toggle" name="fa_enqueue_fa6_fe" value="1" <?php checked( 1, (int) get_option( 'fa_enqueue_fa6_fe' ) ); ?>> Enqueue on front-end
                                </p>
                                <p>
                                    <input type="checkbox" class="wppd-ui-toggle" name="fa_enqueue_fa6_be" value="1" <?php checked( 1, (int) get_option( 'fa_enqueue_fa6_be' ) ); ?>> Enqueue on back-end
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"></th>
                            <td>
                                <div class="hr-sect">OR</div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label>Font Awesome <?php echo esc_attr( GBFA5_VERSION ); ?></label></th>
                            <td>
                                <p>
                                    <input type="checkbox" class="wppd-ui-toggle" name="fa_enqueue_fe" value="1" <?php checked( 1, (int) get_option( 'fa_enqueue_fe' ) ); ?>> Enqueue on front-end
                                </p>
                                <p>
                                    <input type="checkbox" class="wppd-ui-toggle" name="fa_enqueue_be" value="1" <?php checked( 1, (int) get_option( 'fa_enqueue_be' ) ); ?>> Enqueue on back-end
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"></th>
                            <td>
                                <div class="hr-sect">OR</div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label>Font Awesome Kit</label></th>
                            <td>
                                <p>
                                    <input type="url" class="regular-text" name="fa_enqueue_kit" value="<?php echo esc_url_raw( get_option( 'fa_enqueue_kit' ) ); ?>"> Kit URL
                                    <br><small>e.g. <code>https://kit.fontawesome.com/123a456bcd.js</code></small>
                                </p>
                                <p>
                                    <input type="checkbox" class="wppd-ui-toggle" name="fa_enqueue_kit_fe" value="1" <?php checked( 1, (int) get_option( 'fa_enqueue_kit_fe' ) ); ?>> Enqueue on front-end
                                </p>
                                <p>
                                    <input type="checkbox" class="wppd-ui-toggle" name="fa_enqueue_kit_be" value="1" <?php checked( 1, (int) get_option( 'fa_enqueue_kit_be' ) ); ?>> Enqueue on back-end
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"></th>
                            <td>
                                <div class="hr-sect">OR</div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label>Font Awesome Local Stylesheets (<code>.css</code>)</label></th>
                            <td>
                                <p>Add any stylesheet URLs here (e.g. <code>sharp-thin.css</code>, <code>sharp-solid.css</code>, <code>duotone.css</code>, etc.).</p>

                                <details>
                                    <summary>Example stylesheet URLs</summary>

                                    <p>
                                        1. <code><?php echo esc_url_raw( home_url( '/' ) ); ?>cdn/font-awesome/css/sharp-thin.css</code><br>
                                        2. <code><?php echo esc_url_raw( home_url( '/' ) ); ?>cdn/font-awesome/css/sharp-solid.css</code><br>
                                        3. <code><?php echo esc_url_raw( home_url( '/' ) ); ?>cdn/font-awesome/css/duotone.css</code><br>
                                        4. <code><?php echo esc_url_raw( home_url( '/' ) ); ?>resources/css/my-custom-stylesheet.css</code><br>
                                    </p>
                                </details>

                                <div id="gbfa-repeater-fields">
                                    <?php
                                    $gbfa_external_resources = get_option( 'fa_external_css' );

                                    if ( count( array_filter( (array) get_option( 'fa_external_css' ) ) ) > 0 ) {
                                        $gbfa_external_resources = array_filter( $gbfa_external_resources );

                                        foreach ( $gbfa_external_resources as $resource ) {
                                            ?>
                                            <p>
                                                <input type="url" class="large-text" placeholder="https://" name="fa_external_css[]" value="<?php echo esc_url_raw( $resource ); ?>">
                                            </p>
                                            <?php
                                        }
                                    }
                                    ?>
                                </div>

                                <p>
                                    <a href="#" class="button button-secondary" id="gbfa-repeater-add">Add another stylesheet</a>
                                </p>

                                <script>
                                document.addEventListener('DOMContentLoaded', () => {
                                    if (document.getElementById('gbfa-repeater-add')) {
                                        /**
                                         * Add new repeater field
                                         */
                                        function addRepeaterField() {
                                            let newRepeater = document.createElement('div');

                                            newRepeater.innerHTML = '<p><input type="url" class="large-text" placeholder="https://" name="fa_external_css[]"></p>';
                                            document.getElementById('gbfa-repeater-fields').appendChild(newRepeater);
                                        }

                                        document.getElementById('gbfa-repeater-add').addEventListener('click', (e) => {
                                            e.preventDefault();

                                            addRepeaterField();
                                        });
                                    }
                                });
                                </script>

                                <p>
                                    <input type="checkbox" class="wppd-ui-toggle" name="fa_enqueue_local_fe" value="1" <?php checked( 1, (int) get_option( 'fa_enqueue_local_fe' ) ); ?>> Enqueue on front-end
                                </p>
                                <p>
                                    <input type="checkbox" class="wppd-ui-toggle" name="fa_enqueue_local_be" value="1" <?php checked( 1, (int) get_option( 'fa_enqueue_local_be' ) ); ?>> Enqueue on back-end
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><input type="submit" name="save_fa_settings" class="button button-primary" value="Save Changes"></th>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </form>
        <?php } elseif ( $tab === 'help' ) { ?>
            <h2><span class="dashicons dashicons-editor-help"></span> Help</h2>

            <p>This plugin allows you to display a Font Awesome 5, Font Awesome 6, Font Awesome 7 or Font Awesome kit icon in a Gutenberg block or a custom HTML block.</p>
            <p>You can also display inline icons by using the <code>[fa class="fas fa-fw fa-3x fa-phone"]</code> shortcode.</p>

            <p><a href="https://getbutterfly.com/wordpress-plugins/block-for-font-awesome/" class="button button-primary">Documentation</a></p>

            <hr>
            <p>For support, feature requests and bug reporting, please visit the <a href="https://getbutterfly.com/" rel="external">official website</a>. If you enjoy this plugin, don't forget to rate it. Also, try our other WordPress plugins at <a href="https://getbutterfly.com/wordpress-plugins/" rel="external" target="_blank">getButterfly.com</a>.</p>
            <p>&copy;<?php echo esc_attr( gmdate( 'Y' ) ); ?> <a href="https://getbutterfly.com/" rel="external"><strong>getButterfly</strong>.com</a> &middot; <small>Code wrangling since 2005</small></p>
        <?php } ?>
    </div>
    <?php
}
