<?php
/**
 * Plugin Name:     SB Parent block
 * Plugin URI: 		https://www.oik-plugins.com/oik-plugins/sb-parent-block
 * Description:     Display a link to the parent
 * Version:         0.5.2
 * Author:          bobbingwide
 * Author URI: 		https://www.bobbingwide.com/about-bobbing-wide
 * License:         GPL-2.0-or-later
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:     sb-parent-block
 *
 * @package         sb-parent-block
 */

/**
 * Registers all block assets so that they can be enqueued through the block editor
 * in the corresponding context.
 *
 * @see https://developer.wordpress.org/block-editor/tutorials/block-tutorial/applying-styles-with-stylesheets/
 */
function sb_parent_block_block_init() {
	$dir = dirname( __FILE__ );

	$script_asset_path = "$dir/build/index.asset.php";
	if ( ! file_exists( $script_asset_path ) ) {
		throw new Error(
			'You need to run `npm start` or `npm run build` for the "sb/parent-block" block first.'
		);
	}
	$index_js     = 'build/index.js';
	$script_asset = require( $script_asset_path );
	wp_register_script(
		'sb-parent-block-block-editor',
		plugins_url( $index_js, __FILE__ ),
		$script_asset['dependencies'],
		$script_asset['version']
	);
	/*
	 * Localise the script by loading the required strings for the build/index.js file
	 * from the locale specific .json file in the languages folder
	 */
	$ok = wp_set_script_translations( 'sb-parent-block-block-editor', 'sb-parent-block' , $dir .'/languages' );


	$editor_css = 'build/index.css';
	wp_register_style(
		'sb-parent-block-block-editor',
		plugins_url( $editor_css, __FILE__ ),
		array(),
		filemtime( "$dir/$editor_css" )
	);

	$style_css = 'build/style-index.css';
	wp_register_style(
		'sb-parent-block-block',
		plugins_url( $style_css, __FILE__ ),
		array(),
		filemtime( "$dir/$style_css" )
	);

	register_block_type( 'sb/parent-block', array(
		'editor_script' => 'sb-parent-block-block-editor',
		'editor_style'  => 'sb-parent-block-block-editor',
		'style'         => 'sb-parent-block-block',
		'render_callback'=>'sb_parent_block_dynamic_block',
		'attributes' => [
			'className' => [ 'type' => 'string'],
			'noparent' => [ 'type' => 'string']
		]
	) );
}
add_action( 'init', 'sb_parent_block_block_init' );


/**
 * Displays a link to the parent or the user defined value for "No parent"
 * @param $attributes
 *
 * @return mixed|string|void
 */
function sb_parent_block_dynamic_block( $attributes ) {
	load_plugin_textdomain( 'sb-parent-block', false, 'sb-parent-block/languages' );
	$id = wp_get_post_parent_id( null );
	if ( $id ) {
		$url = get_permalink( $id );
		$title = get_the_title( $id );
		$html = "<a href=\"$url\" >$title</a>";
	} else {
		$html = null;
		if ( isset( $attributes['noparent'] ) ) {
			$html=$attributes['noparent'];
		}
		$html = '<div>' . $html . '</div>';

		// $html = __( "No parent", 'sb-parent-block' );
	}
	return $html;
}
