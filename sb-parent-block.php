<?php
/**
 * Plugin Name:     SB Parent block
 * Description:     Display a link to the parent
 * Version:         0.4.0
 * Author:          bobbingwide
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
		]
	) );
}
add_action( 'init', 'sb_parent_block_block_init' );

function sb_parent_block_dynamic_block( $attributes ) {

	$id = wp_get_post_parent_id( null );
	if ( $id ) {
		$url = get_permalink( $id );
		$title = get_the_title( $id );
		$html = "<a href=\"$url\" >$title</a>";
	} else {
		$html = "No parent";
	}
	return $html;

}
