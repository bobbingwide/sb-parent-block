const config = require( '@wordpress/scripts/config/webpack.config' );

/**
 * Provide a unique name for the global scope (which is used to lazy-load chunks),
 * otherwise it throws a JS error when loading blocks compiled with `npm run build`.
 *
 *
 * @see https://webpack.js.org/configuration/output/#outputjsonpfunction
 * @see https://github.com/WordPress/gutenberg/issues/24321
 */
// ------------------------------------------------------
config.output.jsonpFunction = 'sb-parent-block';
// ------------------------------------------------------

module.exports = config;
